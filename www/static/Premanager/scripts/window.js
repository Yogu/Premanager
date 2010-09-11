Window = Class.create();

// Static members
Object.extend(Window, {
	Flag: {
		CLOSE_BUTTON: 1,
		MAXIMIZE_BUTTON: 2,
		MINIMIZE_BUTTON: 4,
		RESIZEABLE: 8,
		MODAL: 16
	},
	
	State: {
		NORMAL: 0,
		MINIMIZED: 1,
		MAXIMIZED: 2
	},	
	
	currentZIndex: 2,	
	modals: new Array(),
	windows: new Array(),

	makeModal: function(window) {
		if (!Window.modalOverlay) {
			Window.modalOverlay = document.createElement('div');
			Window.modalOverlay.className = 'window-modal-overlay';
			document.body.appendChild(Window.modalOverlay);
		}
		
		Window.modalOverlay.style.zIndex = window.element.style.zIndex-1;
		
		Window.modals.push(window);
	},
	
	removeModal: function(window) {
		if (Window.modals.length == 0)
			return;
			
		if (Window.modals[Window.modals.length-1] == window) {
			if (Window.modals.length >= 2) {
				Window.modalOverlay.style.zIndex = Window.modals[Window.modals.length-2].element.style.zIndex-1;
			} else {
				document.body.removeChild(Window.modalOverlay);
				Window.modalOverlay = null;	
			}
		}
		
		var newModals = new Array();
		for (var i = 0; i < Window.modals.length; i++) {
			if (Window.modals[i] != window)
				newModals.push(Window.modals[i]);	
		}
		Window.modals = newModals;
		
		if (Window.modals.length == 0 && Window.modalOverlay) {
			document.body.removeChild(Window.modalOverlay);
			Window.modalOverlay = null;	
		}
	},
	
	prompt: function(title, text, defaultValue, inputTitle, onConfirm, onClose, onCheck) {
		var input;
		var window = new Window(title, Window.Flag.CLOSE_BUTTON | Window.Flag.MODAL, function(window) {
			if (text)
				window.addParagraph(text);
				
			window.width = 600;
			window.height = 200;
			window.center();
	
			window.openInputFieldset();
			var input = window.addInput(inputTitle, 'text', '', defaultValue);
			window.addOKCancelButtons();
			
			if (onCheck) {
				function check() {
					window.lastOKButton.disabled = !onCheck(input.value);
				}
				
				input.onchange = check;				
				input.onkeypress = check;
				input.onkeydown = check;
				input.onkeyup = check;
				check();
			}
			
			if (onConfirm)
				window.onConfirm = function() {
					onConfirm(input.value);
				}
			
			if (onClose)
				window.onClose = onClose;
			
			window.onLoad = function() {
				input.focus();
				input.select();							
			}
		});
	}
});

// Non-static members
Window.prototype = {
	title: '',
	flags: 0,
	state: Window.State.NORMAL,
	visible: true,
	x: 50,
	y: 50,
	width: 400,
	height: 300,	
	
	initialize: function(title, flags, componentCreator) {
		if (flags == null)
			flags = Window.Flag.CLOSE_BUTTON;
			
		this.keyboardAction = this.keyboardAction.bindAsEventListener(this);
		this.mouseDownEvent = this.beginMove.bindAsEventListener(this);
		this.mouseMoveEvent = this.updateMove.bindAsEventListener(this);
		this.mouseUpEvent = this.endMove.bindAsEventListener(this);
		document.observe('keydown', this.keyboardAction);
		document.observe('mouseup', this.mouseUpEvent);
	
		this.title = title;
		this.flags = flags;
		this.componentCreator = componentCreator;
		Window.windows.push(this);
		
		this.element = document.createElement('div');
		this.element.className = 'window';
		this.element.style.display = 'none';       
		this.element.window = this,
		document.body.appendChild(this.element);
		
		/*this.element.onmousedown = function(event) {
			if (event == null)
				event = window.event;
			var window = this.window;
			             
			window.bringToTop();
		}
		
		this.element.onmouseup = function(event) {
			if (event == null)
				event = window.event;
	
			windowsHandleMouseUp(event);	
		}*/
		
		this.head = document.createElement('div');
		this.head.className = 'window-head';  
		this.head.window = this,
		this.element.appendChild(this.head);        
		
		this.moveArea = document.createElement('div');
		this.moveArea.className = 'window-move-area';  
		this.moveArea.window = this,
		this.head.appendChild(this.moveArea);   
		this.moveArea.observe('mousedown', this.mouseDownEvent);
		
		this.titleElement = document.createElement('span');
		this.titleElement.className = 'window-title';
		this.titleElement.appendChild(document.createTextNode(this.title));
		this.titleElement.window = this;
		this.moveArea.appendChild(this.titleElement);
		
		if (flags | Window.Flag.CLOSE_BUTTON || flags | Window.Flag.MAXIMIZE_BUTTON || flags | Window.Flag.MINIMIZE_BUTTON) {
			this.buttonsElement = document.createElement('span');
			this.buttonsElement.className = 'window-buttons';
			this.buttonsElement.window = this;
			this.head.appendChild(this.buttonsElement);
	
			if (flags & Window.Flag.MINIMIZE_BUTTON) {
				this.minimizeButton = document.createElement('a');     
				this.minimizeButton.title = 'Minimize';
				this.minimizeButton.className = 'window-minimize-button';
				this.minimizeButton.appendChild(document.createTextNode('Minimize'));
				this.minimizeButton.window = this;
				this.minimizeButton.onclick = function() {
					this.window.minimize();
				}
				this.buttonsElement.appendChild(this.minimizeButton);
			}   
			
			if (flags & Window.Flag.MINIMIZE_BUTTON || flags & Window.Flag.MAXIMIZE_BUTTON) {   
				this.restoreButton = document.createElement('a');
				this.restoreButton.className = 'window-restore-button';
				this.restoreButton.style.display = 'none';     
				this.restoreButton.appendChild(document.createTextNode('Restore'));
				this.restoreButton.title = 'Restore';     
				this.restoreButton.window = this;
				this.restoreButton.onclick = function() {
					this.window.restore();
				}
				this.buttonsElement.appendChild(this.restoreButton);
			}   
			 
			if (flags & Window.Flag.MAXIMIZE_BUTTON) {
				this.maximizeButton = document.createElement('a');
				this.maximizeButton.className = 'window-maximize-button';  
				this.maximizeButton.appendChild(document.createTextNode('Maxmimize'));
				this.maximizeButton.title = 'Maximize';     
				this.maximizeButton.window = this;
				this.maximizeButton.onclick = function() {
					this.window.maximize();
				}
				this.buttonsElement.appendChild(this.maximizeButton);
			}
			
			if (flags & Window.Flag.CLOSE_BUTTON) {
				this.closeButton = document.createElement('a');
				this.closeButton.className = 'window-close-button';  
				this.closeButton.appendChild(document.createTextNode('Close'));
				this.closeButton.title = 'Close';     
				this.closeButton.window = this;
				this.closeButton.onclick = function() {
					this.window.close();
				}
				this.buttonsElement.appendChild(this.closeButton);
			}
		}
		
		this.body = document.createElement('div');
		this.body.className = 'window-body';       
		this.body.window = this,
		this.element.appendChild(this.body);    
		
		if (componentCreator)
			componentCreator(this);    
		
			
		this.updateBounds();
		if (this.visible) {
			this.show();
		
			// Need to wait until show() is called because otherwise z-index is missing
			if (flags & Window.Flag.MODAL)
				Window.makeModal(this);
		}
		
		if (this.onLoad)
			this.onLoad();
	},

	minimize: function() {
		this.minimizeButton.style.display = 'none';
		this.restoreButton.style.display = '';  
		if (this.maximizeButton)
			this.maximizeButton.style.display = ''; 
		
		this.state = Window.State.MINIMIZED;
	},     

	maximize: function() {
		this.maximizeButton.style.display = 'none';
		this.restoreButton.style.display = '';   
		if (this.minimizeButton)
			this.minimizeButton.style.display = ''; 
		
		this.state = Window.State.MAXIMIZED;
	},              
	
	restore: function() {
		this.restoreButton.style.display = 'none';
		if (this.maximizeButton)
			this.maximizeButton.style.display = '';   
		if (this.minimizeButton)
			this.minimizeButton.style.display = ''; 
				
		this.state = Window.State.NORMAL;
	},
	
	close: function() {
		if (this.onCanClose)
			if (!this.onCanClose())
				return;
		
		if (this.onClose)
			this.onClose();
			
		if (this.flags & Window.Flag.MODAL)
			Window.removeModal(this);
			
		document.body.removeChild(this.element);
	},
	
	show: function() {
		this.visible = true;
		
		this.element.style.display = 'block';
		this.bringToTop();    
	
		if (this.flags & Window.Flag.MODAL)
			Window.makeModal(this);
	},      
	
	hide: function() {
		this.visible = false;
		
		this.element.style.display = 'none';   
	
		if (this.flags & Window.Flag.MODAL)
			Window.removeModal(this);
	},
	
	updateBounds: function() {
		this.element.style.left = this.x+'px';
		this.element.style.top = this.y+'px';
		this.element.style.width = this.width+'px';
		this.element.style.height = this.height+'px';
	},
	
	move: function(x, y) {
		this.x = x;
		this.y = y;
		this.updateBounds();
	},   
	
	resize: function(width, height) {
		this.width = width;
		this.height = height;
		this.updateBounds();
	},
	
	setBounds: function(x,y, width, height) {    
		this.x = x;
		this.y = y;
		this.width = width;
		this.height = height;
		this.updateBounds();
	},
	
	log: function(text) {
		var p = document.createElement('p');
		p.appendChild(document.createTextNode(text));
		this.body.appendChild(p);
	},
	
	bringToTop: function() {
		this.element.style.zIndex = Window.currentZIndex;
		Window.currentZIndex += 2;
		this.element.focus();
	},    
	
	center: function() {
		this.move(
			(window.innerWidth - this.width) / 2,
			(window.innerHeight - this.height) / 2);
	}, 
	
	addParagraph: function(content) {
		var p = document.createElement('p');
		p.appendChild(document.createTextNode(content));
		this.addNode(p);
	},
	
	addNode: function(node) {
		node.window = this;
		if (this.currentNode)
			this.currentNode.appendChild(node);
		else
			this.body.appendChild(node);
	},
	
	openInputFieldset : function() {
		var fieldset = document.createElement('fieldset');
		fieldset.className = 'inputs';
		this.body.appendChild(fieldset);
		this.currentNode = fieldset;
	},     
	
	addInput: function(title, type, className, value, description) {
		var id = getUniqueID();
	
		var dl = document.createElement('dl');
		this.addNode(dl);
		
		var dt = document.createElement('dt');
		dl.appendChild(dt);
		
		var label = document.createElement('label');
		label.appendChild(document.createTextNode(title));
		label.setAttribute('for', id); 
		dt.appendChild(label);
		
		var dd = document.createElement('dd');
		dl.appendChild(dd);
		
		var input = document.createElement('input');
		if (!type)
			type = 'text';
		input.setAttribute('type', type);
		input.id = id;
		if (className)
			input.className = className;
		if (value)
			input.setAttribute('value', value);
		dd.appendChild(input);
		
		if (description) {
			var p = document.createElement('p');
			p.appendChild(document.createtextNode(description));
			dd.appendChild(p);	
		}
		
		return input;
	},
	
	openButtonFieldset: function() {
		var fieldset = document.createElement('fieldset');
		fieldset.className = 'buttons';
		this.body.appendChild(fieldset);
		this.currentNode = fieldset;
	},   
	
	closeNode: function() {
		this.currentNode = null;
	},
	
	addCloseButton: function() {
		this.openButtonFieldset();
		
		var button = document.createElement('button');
		button.appendChild(document.createTextNode('Close')); 
		button.className = 'main';
		button.window = this;
		this.addNode(button);
		this.defaultButton = button;
		
		button.onclick = function() {
			this.window.close();	
		}
	},   
	
	addOKCancelButtons: function() {
		this.openButtonFieldset();
		
		var button = document.createElement('button');
		button.appendChild(document.createTextNode('OK'));
		button.className = 'main';
		button.window = this;
		this.lastOKButton = button;
		this.addNode(button);
		this.defaultButton = button;

		button.onclick = function() {
			this.window.confirmed = true;
			if (this.window.onConfirm)
				this.window.onConfirm();
			this.window.close();	
		}
		
		button = document.createElement('button');
		button.appendChild(document.createTextNode('Cancel'));    
		button.className = 'main';
		button.window = this;
		this.lastCancelButton = button;
		this.addNode(button);       
		
		button.onclick = function() {
			this.window.close();	
		}
	},
	
	keyboardAction: function(event) {
		var escapeKey = event.DOM_VK_ESCAPE ? event.DOM_VK_ESCAPE : 27;
		var returnKey = event.DOM_VK_RETURN ? event.DOM_VK_RETURN : 13;

		if (event.keyCode == escapeKey) {
			this.close();
		} else if (event.keyCode == returnKey) {
			if (this.defaultButton && !this.defaultButton.disabled)
				this.defaultButton.onclick();
			return false;					
		}
	},
	
	beginMove: function(event) {
		if (event.button == 0) {
			this.moveCursorPos = cursorPos;
			this.moveWindowPos = {x: window.x, y: window.y};
			
			document.observe('mousemove', this.mouseMoveEvent);
			
			/*// Create invisible overlay
			this.invisibleOverlay = document.createElement('div');
			this.invisibleOverlay.className = 'window-invisible-overlay';
			document.body.appendChild(this.invisibleOverlay);
			this.invisibleOverlay.style.zIndex = Window.currentZIndex;     
			Window.currentZIndex += 2;*/
		}
	},
	
	updateMove: function(event) {
		cursor = {
			x: Math.max(cursorPos.x, 0),
			y: Math.max(cursorPos.y, 0)}; 
	 
		var diff = {
			x: cursor.x - this.moveCursorPos.x,
		 	y: cursor.y - this.moveCursorPos.y};
		this.moveCursorPos = cursor;
		
		this.move(
			this.x + diff.x,
			this.y + diff.y);   
	},
	
	endMove: function(event) {
		if (this.mouseMoveEvent != null)
			document.stopObserving('mousemove', this.mouseMoveEvent);
		/*if (this.invisibleOverlay != null) {
			this.invisibleOverlay.parentNode.removeChild(this.invisibleOverlay);
			this.invisibleOverlay = null;
		}*/			
	}
};