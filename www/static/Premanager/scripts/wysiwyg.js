/*
 * Javascript WYSIWYG HTML control
 * Version 0.2
 *
 * Copyright (c) 2004 Paul James
 * All rights reserved.
 *
 * This software is covered by the BSD License, please find a copy of this
 * license at http://peej.co.uk/sandbox/wysiwyg/
 */
 // these are constants but IE doesn't like the const keyword
var WYSIWYG_VALUE_NONE = null;
var WYSIWYG_VALUE_PROMPT = 1;
var WYSIWYG_VALUE_FUNCTION = 2;
var WYSIWYG_BUTTONS_AS_FORM_ELEMENTS = true;  
var UPDATE_AFTER_CHANGE_DELAY = 5000;
var LISTTYPE_UL = 1;
var LISTTYPE_OL = 2;

if (!wysiwygs)
	var wysiwygs = new Array();

// define toolbar buttons
if (!wysiwyg_toolbarButtons) {
	var wysiwyg_toolbarButtons = new Array(
		//command, display name, value, title, class name, prompt/function, default text
		/*["bold", "Strong", WYSIWYG_VALUE_NONE, "Give text strength", 'tool-bold'],
		["italic", "Emphasis", WYSIWYG_VALUE_NONE, "Give text emphasis", 'tool-italic'],*/
		/*["insertimage", "Image", WYSIWYG_VALUE_PROMPT, "Insert an image", "Enter the URL of the image:", "http://", 'tool-insert-image'],*/
		
		// Undoes the last action
		['Undo', 'Undo last action', 'tool-undo',
			function(wysiwyg, e, i) {
				e('undo');
			}
		],
		
		// Redoes last undone action
		['Redo', 'Redo last undone action', 'tool-redo',
			function(wysiwyg, e, i) {
				e('redo');
			}
		],
		
		['div'],
		
		// Cuts selection to clipboard
		['Cut', 'Cut to clipboard', 'tool-cut',
			function(wysiwyg, e, i) {
				try {
					e('cut')
				} catch(err) {
					notifyClipboardDisabled(); 
				}
			}
		],
		
		// Copies selection to clipboard
		['Copy', 'Copy to clipboard', 'tool-copy',
			function(wysiwyg, e, i) {
				try {
					e('copy')
				} catch(err) {
					notifyClipboardDisabled(); 
				}
			}
		],
		
		// Pastes from clipboard
		['Paste', 'Paste from clipboard', 'tool-paste',
			function(wysiwyg, e, i) {
				try {
					e('paste')
				} catch(err) {
					notifyClipboardDisabled();
				}
			}
		],
		
		['div'],
		
		// Formats current block as paragraph
		['Paragraph', 'Format as paragraph', 'tool-paragraph',
			function(wysiwyg, e, i) {
				e('formatblock', '<P>');
			}
		],
		
		// Formats current blcok as top-level heading
		['Heading 1', 'Format as top-level heading', 'tool-heading1',
			function(wysiwyg, e, i) {
				e('formatblock', '<H1>');
			}
		],
		
		// Formats current block as second-level heading
		['Heading 2', 'Format as second-level heading', 'tool-heading2',
			function(wysiwyg, e, i) {
				e('formatblock', '<H2>');
			}
		],
		
		// Formats current block as third-level heading
		['Heading 3', 'Format as third-level heading', 'tool-heading3',
			function(wysiwyg, e, i) {
				e('formatblock', '<H3>');
			}
		],
		
		// Formats current block as item of unordered list  
		['Unordered List', 'Make an unordered list', 'tool-unordered-list',
			function(wysiwyg, e, i) {
				e('insertunorderedlist');
			}
		],
		
		// Formats current block as item of ordered list
		['Ordered List', 'Make an ordered list', 'tool-ordered-list',
			function(wysiwyg, e, i) {    
				e('insertorderedlist');
			}
		],
		
		// Indents current list item
		['Indent', 'Indent', 'tool-indent',
			function(wysiwyg, e, i) {
				e('indent');
			}
		],
		
		// Outdents current list item  
		['Outdent', 'Outdent', 'tool-outdent',
			function(wysiwyg, e, i) {
				e('outdent');
			}
		],
		
		['div'],
		
		// Inserst a hyperlink  
		['Link', 'Create a hyperlink', 'tool-add-link',
			function(wysiwyg, e, i) {
				var element = wysiwyg.control.contentWindow.getSelection().focusNode;
				while (element && element.nodeName.toLowerCase() != 'a')
					element = element.parentNode;
				if (element)
					var url = element.href;
				else
					var url = 'http://';
			
				Window.prompt('Create Link', 'Please insert url below.', url, 'URL:',
					function(value) { // confirm
						e('createlink', value);
					},
					function() { // Close
						wysiwyg.control.contentWindow.focus();
					},
					function(value) { // Check
						return RegExps.URL.test(value);
					}
				);
				focusEditorAfter = false;
				return true;
			}
		],
		
		// Removes current hyperlink
		['Remove Link', 'Remove hyperlink', 'tool-remove-link',
			function(wysiwyg, e, i) {
				e('unlink');
			}
		],
		
		['div'],
		
		// Toggles between wysiwyg and code view
		['View Source', 'Switch between WYSIWYG editor and source editor', 'tool-toggle-source', 'toggleview']
	);
}

// map control elements to desired elements
if (!wysiwyg_elementMap) {
	var wysiwyg_elementMap = new Array(
		//control regex, desired regex replacement
		[/<(B|b|STRONG)>(.*?)<\/\1>/gm, "<strong>$2</strong>"],
		[/<(I|i|EM)>(.*?)<\/\1>/gm, "<em>$2</em>"],
		[/<P>(.*?)<\/P>/gm, "<p>$1</p>"],
		[/<H1>(.*?)<\/H1>/gm, "<h1>$1</h1>"],
		[/<H2>(.*?)<\/H2>/gm, "<h2>$1</h2>"],
		[/<H3>(.*?)<\/H3>/gm, "<h3>$1</h3>"],
		[/<PRE>(.*?)<\/PRE>/gm, "<pre>$1</pre>"],
		[/<A (.*?)<\/A>/gm, "<a $1</a>"],
		[/<IMG (.*?)>/gm, "<img $1 alt=\"Image\" />"],
		[/<img (.*?)>/gm, "<img $1 />"], 
		[/<LI>(.*?)<\/LI>/gm, "<li>$1</li>"],
		[/<UL>(.*?)<\/UL>/gm, "<ul>$1</ul>"],
		[/<span style="font-weight: normal;">(.*?)<\/span>/gm, "$1"],
		[/<span style="font-weight: bold;">(.*?)<\/span>/gm, "<strong>$1</strong>"],
		[/<span style="font-style: italic;">(.*?)<\/span>/gm, "<em>$1</em>"],
		[/<span style="(font-weight: bold; ?|font-style: italic; ?){2}">(.*?)<\/span>/gm, "<strong><em>$2</em></strong>"],
		[/<([a-z]+) style="font-weight: normal;">(.*?)<\/\1>/gm, "<$1>$2</$1>"],
		[/<([a-z]+) style="font-weight: bold;">(.*?)<\/\1>/gm, "<$1><strong>$2</strong></$1>"],
		[/<([a-z]+) style="font-style: italic;">(.*?)<\/\1>/gm, "<$1><em>$2</em></$1>"],
		[/<([a-z]+) style="(font-weight: bold; ?|font-style: italic; ?){2}">(.*?)<\/\1>/gm, "<$1><strong><em>$3</em></strong></$1>"],
		[/<(br|BR)>/g, "<br />"],
		[/<(hr|HR)( style="width: 100%; height: 2px;")?>/g, "<hr />"]
	);
}


// attach to window onload event
if (document.getElementById && document.designMode) {
	if (window.addEventListener){
		window.addEventListener("load", initWysiwyg, false);
	} else if (window.attachEvent){
		window.attachEvent("onload", initWysiwyg);
	} else {
		alert("Could not init wysiwyg");
	}
}

function initWysiwyg() { 
	createWysiwygControls();

	// do this last and after a slight delay cos otherwise it can get turned off in Gecko	
	setTimeout(initWysiwygControls, 1); 

	// turn textareas into wysiwyg controls
	function createWysiwygControls() {
		var textareas = document.getElementsByTagName("textarea");
		for (var i = 0; i < textareas.length; i++) {
			if (textareas[i].className.indexOf("wysiwyg") > -1) {
				var wysiwyg = document.createElement("div");
				var wrap = document.createElement("div");
				var control = document.createElement("iframe");
				var textarea = textareas[i];       
				
				// Wysiwyg
				wysiwyg.wrap = wrap;
				wysiwyg.control = control;
				wysiwyg.textarea = textarea;               
				wysiwyg.className = textarea.className;  
				textarea.parentNode.replaceChild(wysiwyg, textarea);  

				// Wrap
				wrap.className = 'wysiwyg-wrap';
				wysiwyg.appendChild(wrap);      
				
				// Control
				wrap.appendChild(control);
				
				// Textarea
				textarea.style.display = "none";   
				textarea.className = textarea.className.replace('wysiwyg', '');  
				wrap.appendChild(textarea); 

				createToolbar(wysiwyg);
				
				wysiwygs.push(wysiwyg);
			}
		}
	}
	
	// initiate wysiwyg controls
	function initWysiwygControls() {
		// no wysiwygs needed
		if (!wysiwygs[0])
			return; 

		// if not loaded yet, wait and try again
		if (!wysiwygs[wysiwygs.length-1].control.contentDocument.body) {
			setTimeout(initWysiwygControls, 1);
			return;
		}

		for (var i = 0; i < wysiwygs.length; i++) {
			// turn on design mode for wysiwyg controls
			wysiwygs[i].control.contentWindow.document.designMode = "on";

			var head = wysiwygs[i].control.contentDocument.getElementsByTagName('head')[0];
			              
			// Link stylesheet
			var styleNode = document.createElement('link');
			styleNode.setAttribute('rel', 'stylesheet');
			styleNode.setAttribute('type', 'text/css'); 
			styleNode.setAttribute('href', Config.staticURLPrefix+'Premanager/styles/wysiwyg.css');
			head.appendChild(styleNode);
			
			// Link tools javascript
			var scriptNode = document.createElement('script');
			scriptNode.setAttribute('type', 'text/javascript'); 
			scriptNode.setAttribute('src', Config.staticURLPrefix+'Premanager/scripts/tools.js');
			head.appendChild(scriptNode);
			
			// Attach keypress method which updates textarea after a delay
			wysiwygs[i].control.contentWindow.document.body.parentNode.onkeypress = function() {
				wysiwygChange(wysiwygs[i]);
			}
			
			// attach submit method
			var element = wysiwygs[i].control;
			while (element.tagName && element.tagName.toLowerCase() != "form") {
				if (!element.parentNode) break;
				element = element.parentNode;
			}

			if (element.tagName && element.tagName.toLowerCase() == "form" && !element.wysiwygAttached) {
				if (element.onsubmit) {
					element.onsubmit = function() {
						element.onsubmit();
						wysiwygSubmit();
					}
				} else {
					element.onsubmit = wysiwygSubmit;
				}
				element.wysiwygAttached = true;
			}
		}

		// schedule init of content (we do this due to IE)
		setTimeout(initContent, 1);
	}
	
	// set initial content
	function initContent() {
		for (var i = 0; i < wysiwygs.length; i++) {
			wysiwygUpdate(wysiwygs[i]);
		}
	}
	
	// create a toolbar for the control
	function createToolbar(wysiwyg) {
		var toolbar = document.createElement("ul");
		var bar = 0;
		var isNewGroup = false;
		toolbar.className = "toolbar toolbar" + bar;
		for (var i = 0; i < wysiwyg_toolbarButtons.length; i++) {
			if (wysiwyg_toolbarButtons[i][3] == "toggleview") {
				var button = createButton(wysiwyg, i, isNewGroup);
				button.onclick = toggleView;
				button.htmlTitle = wysiwyg_toolbarButtons[i][1];
				button.composeTitle = wysiwyg_toolbarButtons[i][2];
				toolbar.appendChild(button.parentNode);
				isNewGroup = false;
			} else if (wysiwyg_toolbarButtons[i].length >= 4) {
				var button = createButton(wysiwyg, i, isNewGroup);
				button.onclick = execCommand;
				toolbar.appendChild(button.parentNode);  
				isNewGroup = false;
			} else if (wysiwyg_toolbarButtons[i][0] == "div") {
				isNewGroup = true;
			} else {
				bar++;
				wysiwyg.insertBefore(toolbar, wysiwyg.wrap);
				var toolbar = document.createElement("div");
				toolbar.className = "toolbar toolbar" + bar;
			}
		}
		wysiwyg.insertBefore(toolbar, wysiwyg.wrap);
	}
	
	// create a button for the toolbar
	function createButton(wysiwyg, number, isNewGroup) {
		var li = document.createElement('li');
		var button = document.createElement('a');    	
		if (isNewGroup)
			li.setAttribute('class', 'new-group');
		li.appendChild(button);
			button.appendChild(document.createTextNode(wysiwyg_toolbarButtons[number][0]));
		button.number = number;
		//button.className = "toolbarButton toolbarButton" + number;
		button.command = wysiwyg_toolbarButtons[number][3];
		button.title = wysiwyg_toolbarButtons[number][1];
		button.setAttribute('class', wysiwyg_toolbarButtons[number][2]);
			
		button.wysiwyg = wysiwyg;
		return button;
	}
		 
	// execute a toolbar command
	function execCommand() {
		var wysiwyg = this.wysiwyg;
		var focusEditorAfter = !wysiwyg_toolbarButtons[this.number][3](
			wysiwyg,
			function(cmd, value) {
				wysiwyg.control.contentWindow.document.execCommand(cmd, false, value);
			},
			function(value) {
				insertContent(wysiwyg, value);
			}
		);

		textareaUpdate(this.wysiwyg);
		if (focusEditorAfter)
			this.wysiwyg.control.contentWindow.focus();
		return false;
	}
	
	// insert HTML content into control
	function insertContent(wysiwyg, content) {
		var textarea = wysiwyg.textarea;
		var control = wysiwyg.control;
		
		// IE
		if (document.selection) { 
			control.focus();
			sel = document.selection.createRange();
			sel.text = content;
		} else { // Mozilla 
			var sel = control.contentWindow.getSelection();
			var range = sel.getRangeAt(0);
			sel.removeAllRanges();
			range.deleteContents();
			var oldContent = control.contentWindow.document.body.innerHTML;
			var inTag = false;
			var insertPos = 0;
			for (var i = 0, pos = 0; i < oldContent.length; i++) {
				var aChar = oldContent.substr(i, 1);
				if (aChar == "<") {
					inTag = true;
				}
				if (!inTag) {
					pos++;
					if (pos == range.startOffset) {
					insertPos = i + 1;
				}
			}
			if (aChar == ">") {
				inTag = false;
			}
		}
		control.contentWindow.document.body.innerHTML = oldContent.substr(0, insertPos) + content + oldContent.substr(insertPos, oldContent.length);
		}
		textareaUpdate(wysiwyg);
	}
	
	// show textarea view
	function toggleView() {
		var control = this.wysiwyg.control;
		var textarea = this.wysiwyg.textarea;
		var toolbars = this.wysiwyg.getElementsByTagName("ul");
		if (textarea.style.display == "none") {
			textareaUpdate(this.wysiwyg);
			control.style.display = "none";
			textarea.style.display = "block";
			for (var i = 0; i < toolbars.length; i++) {
				for (var j = 0; j < toolbars[i].childNodes.length; j++) {
					var li = toolbars[i].childNodes[j];
					if (li.childNodes.count == 0)
						continue;
					var button = li.childNodes[0];
					if (button.command != "toggleview") {
						button.oldClick = button.onclick;
						button.onclick = null;
						button.oldClassName = button.className;
						button.className += " disabled";
					}
				}
			}
		} else {
			wysiwygUpdate(this.wysiwyg);
			textarea.style.display = "none";
			control.style.display = "block";
			control.contentWindow.document.designMode = "on";
			for (var i = 0; i < toolbars.length; i++) {
				for (var j = 0; j < toolbars[i].childNodes.length; j++) {
					var li = toolbars[i].childNodes[j];
					if (li.childNodes.count == 0)
						continue;
					var button = li.childNodes[0];
					if (button.command != "toggleview") {
					if (button.oldClick) button.onclick = button.oldClick;
					if (button.oldClassName) button.className = button.oldClassName;
					}
				}
			}
		}
		return false;
	}
	 
	// update the textarea to contain the source for the wysiwyg control
	function textareaUpdate(wysiwyg) {
		var node = wysiwyg.control.contentWindow.document.body;
		wysiwyg.textarea.value = htmlToXML(node);
	}
	
	// update the wysiwyg to contain the source for the textarea control
	function wysiwygUpdate(wysiwyg) {
		var node = xmlToHTML(wysiwyg.textarea.value);
		
		// Translate again because it has to be checked
		node = xmlToHTML(htmlToXML(node));
		
		wysiwyg.control.contentWindow.document.body.innerHTML = node.innerHTML;

		// Empty documents would otherwise cause linebreaks on pressing enter 		
		if (node.innerHTML == '')
			wysiwyg.control.contentWindow.document.execCommand('formatblock', false, '<P>');
	}

	// update for upon submit
	function wysiwygSubmit() {
		var divs = this.getElementsByTagName("div");
		for (var i = 0; i < divs.length; i++) {
			if (divs[i].className.indexOf('wysiwyg') >= 0) {
				textareaUpdate(divs[i]);
			}
		}
	}
	
	// update textarea after a delay
	function wysiwygChange(wysiwyg) {
		if (wysiwyg.timeout != null)
			clearTimeout(wysiwyg.timeout);
		thiwysiwyg.timeout = setTimeout('textareaUpdate(wysiwyg);', UPDATE_AFTER_CHANGE_DELAY);	
	} 
	
	// Converts an html node to xml
	function htmlToXML(node) {
		var rootNode = document.createElement('root');
		var xmlNode = rootNode;
			
		parseNode(node);   
		checkNode(rootNode);
		return getInnerXHTML(rootNode, '	').trim();
		
		// Closes the current block node and sets xmlNode to its parent (before, it closes all inline elements)
		function closeBlockElement() {
			closeInlineElements();
			switch (xmlNode.nodeName.toLowerCase()) {
				case 'p':
				case 'h1':
				case 'h2':
				case 'h3':
				case 'ul':
				case 'ol':
					var child = xmlNode;
					xmlNode = xmlNode.parentNode;
					break;
			}			
		}
		
		// Closes the deepmost container (ul, ol)
		function closeContainer() {
			var node = xmlNode;
			while (node != rootNode && node.nodeName.toLowerCase() != 'ul' && node.nodeName.toLowerCase() != 'ol') {
				node = node.parentNode;
			}
			
			if (node.nodeName.toLowerCase() == 'ul' || node.nodeName.toLowerCase() == 'ol') {
				xmlNode = node.parentNode;			
			}		
		}      
		
		// Closes the deepmost list item if there is one before first ul/ol
		function closeListItem() {
			var node = xmlNode;
			while (node != rootNode && node.nodeName.toLowerCase() != 'ul' && node.nodeName.toLowerCase() != 'ol' && node.nodeName.toLowerCase() != 'li') {
				node = node.parentNode;
			}
			
			if (node.nodeName.toLowerCase() == 'li') {
				xmlNode = node.parentNode;			
			}		
		}
		
		// Closes the current inline element
		function closeInlineElement() {
			switch (xmlNode.nodeName.toLowerCase()) {
				case 'a':
					var child = xmlNode;                     
					xmlNode = xmlNode.parentNode;
					break;
			}
		}
		
		// Closes all inline elements until a block element is reached
		function closeInlineElements() {
			var node = xmlNode;
			while (node != rootNode) {
				var isBlock = false;
				switch (node.nodeName.toLowerCase()) {
					case 'a':
						break;
						
					default:
						isBlock = true; 				
				}
				if (isBlock)
					break;
				node = node.parentNode;
			}
			xmlNode = node;
		}

		// Adds a new element, but does not enter it
		function addElement(name) {
			var child = document.createElement(name);
			xmlNode.appendChild(child);
			return child;
		}

		// Begins a new element and enters it
		function beginElement(name) {
			xmlNode = addElement(name);
			return xmlNode;
		}
		
		// Begins a new paragraph after having closed the last element
		function beginParagraph() {
			closeBlockElement();	
			beginElement('p');	
		}     
		
		// Begins a new heading after having closed the last element
		function beginHeading(order) {
			closeBlockElement();
			beginElement('h'+order);
		}      
		
		// Begins a new list after having closed the last element
		function beginList(type) {
			closeBlockElement();
			
			var name = type == LISTTYPE_OL ? 'ol' : 'ul';
			
			// Don't allow two identical lists back-to-back
			if (xmlNode.childNodes.length > 0 && xmlNode.childNodes[xmlNode.childNodes.length-1].nodeName.toLowerCase() == name) {
				xmlNode = xmlNode.childNodes[xmlNode.childNodes.length-1]; 			
			} else {
				beginElement(name);			
			}
		}   
		
		// Begins a new list item in the current element
		function beginListItem() {
			beginElement('li');
		}   
		
		// Begins a new link
		function beginLink(href) {
			beginElement('a').setAttribute('href', href);
		}

		// Adds a line break to the current node
		function addLineBreak() {
			addElement('br');										
		}
		
		// Adds text to the current node
		function addText(value) {
			// Combine multiple white-chars to one space
			value = value.replace(/\s+/g, ' ');
			
			if (value == '')
				return;
			var child = document.createTextNode(value);
			xmlNode.appendChild(child);		
		} 

		// Walks through a html node and all its child nodes
		function parseNode(node) {
			// Don't parse style and script tags
			if (node.nodeName.toLowerCase() == 'script' || node.nodeName.toLowerCase() == 'style')
				return;
		
			for (var i = 0; i < node.childNodes.length; i++) {
				var child = node.childNodes[i];
				var isImplicitContainer = false;
				var isImplicitListItem = false;					
				
				switch (child.nodeName.toLowerCase()) {
					case 'p':    
						switch (xmlNode.nodeName.toLowerCase()) {
							case 'root':             
							case 'p':     
							case 'h1': 
							case 'h2':
							case 'h3':  
							case 'li':
								beginParagraph();
								break;
								
							case 'ul':
							case 'ol':    
								isImplicitListItem = true;
								beginListItem();
								beginParagraph();
								break;
								
							case 'a':
								closeInlineElements();
								beginParagraph();
								break;
						}
						break;
						
					case 'h1':   
					case 'h2':
					case 'h3':
						switch (xmlNode.nodeName.toLowerCase()) {
							case 'root':             
							case 'p':
							case 'h1': 
							case 'h2':
							case 'h3':  
							case 'li':
								beginHeading(child.nodeName.substr(1));
								break;  
								
							case 'ul':
							case 'ol':    
								isImplicitListItem = true;
								beginListItem();
								beginHeading(child.nodeName.substr(1));
								break;    
								
							case 'a':
								closeInlineElements();
								beginHeading(child.nodeName.substr(1));
								break;
						}
						break;   
						
					case 'ul':    
					case 'ol':
						switch (xmlNode.nodeName.toLowerCase()) {
							case 'root':             
							case 'p':
							case 'h1': 
							case 'h2':
							case 'h3':
							case 'li':
								beginList(child.nodeName.toLowerCase() == 'ol' ? LISTTYPE_OL : LISTTYPE_UL);
								break;  
								
							case 'ul':
							case 'ol':
								// If there is a list directly in another list, try to move this list to the last item.
								if (xmlNode.childNodes.length > 0 && xmlNode.childNodes[xmlNode.childNodes.length-1].nodeName.toLowerCase() == 'li') {
									xmlNode = xmlNode.childNodes[xmlNode.childNodes.length-1];
									isImplicitContainer = true;								
								} else {
									beginListItem();            
									isImplicitListItem = true;
								}
								
								beginList(child.nodeName.toLowerCase() == 'ol' ? LISTTYPE_OL : LISTTYPE_UL);
								break;  
								
							case 'a':
								closeInlineElements();
								beginList(child.nodeName.toLowerCase() == 'ol' ? LISTTYPE_OL : LISTTYPE_UL);
								break; 
						}
						break; 

					case 'li':
						switch (xmlNode.nodeName.toLowerCase()) {
							case 'root':
							case 'p':
							case 'h1':
							case 'h2':
							case 'h3':
							case 'li':
								switch (xmlNode.getAttribute('type')) {
									case 'ordered':
										beginList(LISTTYPE_OL);
									break;
									
									default:
										beginList(LISTTYPE_UL);		
								}
								beginListItem();
								break; 
								
							case 'ul':
							case 'ol':  
								beginListItem();
								break;   
								
							case 'a':
								closeInlineElements();
								beginListItem();
								break; 
						}
						break;
						
					case 'a':
					  var href = child.getAttribute('href').trim();
					  var linkAdded = false;
					                             
					  // Check if we have to remove the dot at the beginning (in internal link)
					  if (/^\.\//.test(href))   
					  	href = href.substr(1);
					  	             
					  // Check if we have to add a "http"
					  if (/^((http|https|ftp)\:\/\/)?(([0-9a-z][0-9a-z-]*[0-9a-z]\.)+([a-z]{2,6})(\/(.*))?)$/i.test(href) && !/^http|https|ftp\:\/\/(([0-9a-z][0-9a-z-]*[0-9a-z]\.)+([a-z]{2,6})(\/(.*))?)$/i.test(href))
						  	href = 'http://'+href;
					  
					  // If it's not internal and it's no correct http(s)/ftp url, discard link
						if (/^\//.test(href) || /^(http|https|ftp)\:\/\/(([0-9a-z][0-9a-z-]*[0-9a-z]\.)+([a-z]{2,6})(\/(.*))?)$/i.test(href)) {         
							switch (xmlNode.nodeName.toLowerCase()) {
								case 'root':
									beginParagraph();
									beginLink(href);         
								  var linkAdded = true;	
								break;	
								
								case 'ul':
								case 'ol':       
									isImplicitListItem = true;
									beginListItem(); 
									beginParagraph();
									beginLink(href);       
								  var linkAdded = true;
									break;  	
								
								case 'li': 
									beginParagraph();
									beginLink(href);     
								  var linkAdded = true;
									break;
	
								case 'p':
								case 'h1':
								case 'h2':
								case 'h3':
									beginLink(href);     
								  var linkAdded = true;
								  break;
								  
								case 'a':
									closeInlineElement();
									beginLink(href);        
								  var linkAdded = true;
									break;
							}
						}
						break;
						
					case 'br':
						switch (xmlNode.nodeName.toLowerCase()) {
							case 'root':
							case 'ul':
							case 'ol':
								break;

							case 'p': 
							case 'h1':
							case 'h2':
							case 'h3':
							case 'li':
							case 'a':
								// Two line breaks back-to-back are replaced by an paragraph divider (headings are finished then!)
								if (xmlNode.childNodes.length > 0 && xmlNode.childNodes[xmlNode.childNodes.length-1].nodeName.toLowerCase() == 'br')
									beginParagraph();
								else
									addLineBreak();
								break;
						}
						break;
						
					case '#text':   
						if (child.nodeValue.trim() != '') {
							switch (xmlNode.nodeName.toLowerCase()) {
								case 'root':
									beginParagraph();
									addText(child.nodeValue);					
								break;	
								
								case 'ul':
								case 'ol':       
									isImplicitListItem = true;
									beginListItem(); 
									beginParagraph();
									addText(child.nodeValue);	
									break;  	
								
								case 'li': 
									beginParagraph();
									addText(child.nodeValue);	
									break;
	
								case 'p':
								case 'h1':
								case 'h2':
								case 'h3':
								case 'a':
									addText(child.nodeValue);
							}
						}
						break;
				}
				
				parseNode(child);		
				
				if (isImplicitListItem)
					closeListItem();
				if (isImplicitContainer)
					closeContainer();	
				
				// Node closed				
				switch (child.nodeName.toLowerCase()) {
					case 'ul':
					case 'ol':
						closeContainer();
						break;				
						
					case 'li':
						closeListItem();
						break;
						
					case 'a':
						if (linkAdded)
							closeInlineElement();
						break;
						
					// Headings should be closed correctly
					case 'h1':
					case 'h2':
					case 'h3':
						beginParagraph();
						break;
				}
			}	
		}     
		
		// Normalizes a paragraph or heading node 
		function checkLeaf(node) {			
		}
		
		// Walks trough a xml node and checks ist
		function checkNode(node) {
			for (var i = 0; i < node.childNodes.length;) {
				var child = node.childNodes[i];
				var deleted = false;
				
				// Remove line breaks that are at the beginning and at the end
				while (child.childNodes.length > 0 && child.childNodes[0].nodeName.toLowerCase() == 'br')
					child.removeChild(child.childNodes[0]);
				while (child.childNodes.length > 0 && child.childNodes[child.childNodes.length-1].nodeName.toLowerCase() == 'br')
					child.removeChild(child.childNodes[child.childNodes.length-1]);
	
				// Combine text nodes
				for (var j = 1; j < child.childNodes.length;) {
					if (child.childNodes[j].nodeName.toLowerCase() == '#text' && child.childNodes[j-1].nodeName.toLowerCase() == '#text') {
						child.childNodes[j-1].nodeValue += ' ' + child.childNodes[j].nodeValue;
						child.removeChild(child.childNodes[j]);					
					} else
						j	++;		
				}  
					
				// If this is a block element, remove white-chars froöm beginning and end
				switch (child.nodeName.toLowerCase()) {
					case 'p':
					case 'h1':
					case 'h2':
					case 'h3':
					case 'ul':
					case 'ol':
					case 'li':
						function crawlForFirstTextNode(node) {
							if (node.nodeType == 3)
								return node;
							for (var i = 0; i < node.childNodes.length; i++) {
								var n = crawlForFirstTextNode(node.childNodes[i]);
								if (n != null)
									return n;
							}
							return null;
						}
						var n = crawlForFirstTextNode(child);
						if (n != null)
							n.nodeValue = n.nodeValue.ltrim();
							
						function crawlForLastTextNode(node) {
							if (node.nodeType == 3)
								return node;
							for (var i = node.childNodes.length-1; i >= 0 ; i--) {
								var n = crawlForLastTextNode(node.childNodes[i]);
								if (n != null)
									return n;
							}
							return null;
						}
						var n = crawlForLastTextNode(child);
						if (n != null)
							n.nodeValue = n.nodeValue.rtrim();
						break;
				}
		
				// Remove empty nodes
				if (child.nodeType == 1 && child.nodeName.toLowerCase() != 'br' && child.childNodes.length == 0) {
					child.parentNode.removeChild(child);
				} else {
					checkNode(child);
					i++;
				}	
			}	
		}

		function getOuterXHTML(node, indent, indentDepth) {
			if (indentDepth == null)
				indentDepth = 0;

			switch (node.nodeType) {
				case 1:
					var code = '<'+node.nodeName.toLowerCase();
					for (var i = 0; i < node.attributes.length; i++) {
						code += ' ' + node.attributes[i].name + '="' + addHTMLEntities(node.attributes[i].value) + '"';					
					}
				
					if (node.childNodes.length == 0) {
						code += ' />';;
						if (indent != null)
							return indent.repeat(indentDepth) + code + '\n';
						else
							return code;
					} else {
						code += '>';
						               
						// Paragraph contents must not be indented, because there spaces will
						// not be removed by the browser
						switch (node.nodeName.toLowerCase()) {
							case 'p':
							case 'h1':
							case 'h2':
							case 'h3':
								var innerIndent = null;
								break;
							
							default:
								var innerIndent = indent;
						}
						
						if (indent != null)
							code = indent.repeat(indentDepth) + code;
						if (innerIndent != null)
							code += '\n';
						code += getInnerXHTML(node, innerIndent, indentDepth+1)
						var code2 = '</'+node.nodeName.toLowerCase()+'>';   
						if (innerIndent != null)
							code2 = indent.repeat(indentDepth) + code2;   
						if (indent != null)
							code2 = code2 + '\n';
						code += code2;
						return code;
					}
					break;
					
				case 3:
					return addHTMLEntities(node.nodeValue);
			}		
		}
		
		function getInnerXHTML(node, indent, indentDepth) {
			if (indentDepth == null)
				indentDepth = 0;
				
			var code = '';
			for (var i = 0; i < node.childNodes.length; i++) {
				code += getOuterXHTML(node.childNodes[i], indent, indentDepth);				
			}
			return code;
		}
	}
	
	// Converts an xml string to a html node
	function xmlToHTML(code) {
		var xmlNode = document.createElement('root');
		xmlNode.innerHTML = code;
		var rootNode = document.createElement('root');
		var currentNode = rootNode;
		var listType = LISTTYPE_UL;
		parseNode(xmlNode, true);
		return rootNode;
		
		function beginElement(name) {
			var node = document.createElement(name);
			currentNode.appendChild(node);
			currentNode = node;
			return currentNode;
		}      
		
		function replaceElement(name) {
			if (currentNode.parentChild == null)
				return beginElement(name);
			else {
				var node = document.createElement(name);

				currentNode.parentChild.replaceChild(node, currentNode);
				currentNode = node;
				return currentNode;
			}
		}
		
		function closeElement() {
			if (currentNode.parentNode != null) {
				var node = currentNode;
				currentNode = currentNode.parentNode;
				
				// If this was a block element, remove white-chars froöm beginning and end
				switch (node.nodeName.toLowerCase()) {
					case 'p':
					case 'h1':
					case 'h2':
					case 'h3':
					case 'ul':
					case 'ol':
					case 'li':
						function crawlForFirstTextNode(node) {
							if (node.nodeType == 3)
								return node;
							for (var i = 0; i < node.childNodes.length; i++) {
								var n = crawlForFirstTextNode(node.childNodes[i]);
								if (n != null)
									return n;
							}
							return null;
						}
						var n = crawlForFirstTextNode(node);
						if (n != null)
							n.nodeValue = n.nodeValue.ltrim();
							
						function crawlForLastTextNode(node) {
							if (node.nodeType == 3)
								return node;
							for (var i = node.childNodes.length-1; i >= 0 ; i--) {
								var n = crawlForLastTextNode(node.childNodes[i]);
								if (n != null)
									return n;
							}
							return null;
						}
						var n = crawlForLastTextNode(node);
						if (n != null)
							n.nodeValue = n.nodeValue.rtrim();
						break;
				}
				
				// Remove empty nodes
				if (node.nodeType == 1 && node.nodeName.toLowerCase() != 'br' && node.childNodes.length == 0)
					currentNode.removeChild(node);
			}
			return currentNode;
		}

		function addText(value) {
			var node = document.createTextNode(value);
			currentNode.appendChild(node);
			return node;
		}

		function parseNode(xmlNode, isRootNode) {
			var elementOpened = false;
			switch (xmlNode.nodeName.toLowerCase()) {
				case 'p':
					if (currentNode.nodeName.toLowerCase() != 'li') {
						beginElement('p');
						elementOpened = true;
					} else if (xmlNode.parentNode != null && xmlNode != xmlNode.parentNode.children[0]) {
						// XML: <li><p>a</p><p>b</p></li>
						// HTML: <li>a<br /><br />p</li>
						beginElement('br');
						closeElement();
						beginElement('br');
						closeElement();						
					}
					break;

				case 'h1':
				case 'h2':
				case 'h3':
				case 'li':
				case 'br':
					beginElement(xmlNode.nodeName.toLowerCase());	 
					elementOpened = true;
					break;    
					
				case 'ul':
				case 'ol':
					if (currentNode.nodeName.toLowerCase() == 'li') {
						// XML syntax: <ul><li>1<ul><li>1.1</li></ul></li></ul>
						// HTML syntax: <ul><li>1</li><ul><li>1.1</li></ul></ul>
						// (of course, 1 and 1.1 are covered with <p> in xml)
						// So: Close list item and place new list directly in parent list.
						// Do not set elementOpened because we have already closed an element 
						closeElement();
						beginElement(xmlNode.nodeName.toLowerCase());   
					} else {
						beginElement(xmlNode.nodeName.toLowerCase());
						elementOpened = true;
					}
					break;        
					
				case 'a':
					beginElement('a').setAttribute('href', xmlNode.getAttribute('href'));
					elementOpened = true;					
					break;
					
				case '#text':
					if (xmlNode.nodeValue != null) {
						value = xmlNode.nodeValue;
						// Combine multiple white-chars to one space
						value = value.replace(/\s+/g, ' ');
						if (value != '')
							addText(value);
					}
				break;
			}

			for (var i = 0; i < xmlNode.childNodes.length; i++) {
				parseNode(xmlNode.childNodes[i]);
			}
			
			if (elementOpened)
				closeElement();
		}
	}
}

function notifyClipboardDisabled() {
	var window = new Window('Information', Window.Flag.CLOSE_BUTTON | Window.Flag.MODAL, function(window) {
	  window.addParagraph('Cut, copy and paste toolbar buttons are not available on your browser.');
	  window.addParagraph('Please use instead the shortcuts (Ctrl-X, Ctrl-C, Ctrl-V).');
	  var p = document.createElement('p');
	  var a = document.createElement('a');
	  a.setAttribute('href', 'http://www.mozilla.org/editor/midasdemo/securityprefs.html');
	  a.appendChild(document.createTextNode('Click here to read more about this issue.'));
	  p.appendChild(a);
	  window.addNode(p);
	  window.addCloseButton();
	  window.center();
	});
}