// Smart Pageload

if (typeof(Premanager) == 'undefined')
	Premanager = {};

//TODO: replace these with translated string
Premanager.Translation = {
	titleDivider: ' – '
}

Premanager.SmartPageload = {
		
	replaceLinks: function(node) {
		function getHostAndPath(url) {
			url = url.substring('http://'.length, url.length);
			return url.split('/', 2);
		}
		
		function isInternal(url) {
			var hostAndPath = getHostAndPath(url);
			return hostAndPath[0].endsWith(trunk[0]) && hostAndPath[1].startsWith(trunk[1]);
		}
		var trunk = getHostAndPath(Config.emptyURLPrefix);
		
		var crawl = function(node) {
			switch (node.nodeName.toLowerCase()) {
				case 'a':
					if (isInternal(node.href)) {
						node.onclick = function() {
							Premanager.SmartPageload.browse(node.href);
							return false;
						}
					}
					break;
					
				/*case 'form':
					if (isInternal(node.action)) {
						node.onsubmit = function() {
							Premanager.SmartPageload.browse(node.action);
							return false;
						};
					}
					break;*/
			}
			
			for (var i = 0; i < node.childNodes.length; i++) {
				crawl(node.childNodes[i]);
			}
		};
		crawl(node ? node : document.documentElement);
	},

	browse: function(url) {
		var startTime = new Date().getTime();
		
		function loadPage(node) {
			var responseTime = new Date().getTime();
			function getChild(node, name) {
				for (var i = 0; i < node.childNodes.length; i++) {
					if (node.childNodes[i].nodeName.toUpperCase() == name.toUpperCase())
						return node.childNodes[i];
				}
				return null;
			}
			
			function clone(node) {
				if (node.nodeType == node.TEXT_NODE)
					var newNode = document.createTextNode(node.textContent);
				else
					var newNode = document.createElement(node.nodeName);
				if (node.attributes) {
					for (var i = 0; i < node.attributes.length; i++) {
						newNode.setAttribute(node.attributes[i].name, node.attributes[i].value);
					}
				}
				if (node.childNodes) {
					for (var i = 0; i < node.childNodes.length; i++) {
						var child = clone(node.childNodes[i]); 
						newNode.appendChild(child);
					}
				}
				return newNode;
			}
			
			var node;
			var child;
			var child2;
			var contentTag = document.getElementById('content');

			var pageTitle;
			var projectName;
			var projectTitle;
			var projectSubtitle = '';
			var location;
			
			// Title
			if (child = getChild(node, 'title'))
				pageTitle = child.textContent;
			else
				return;
			
			// Project
			if (child = getChild(node, 'project')) {
				if (child2 = getChild(child, 'name'))
					projectName = child2.textContent;
				else
					return;
				
				if (child2 = getChild(child, 'title'))
					projectTitle = child2.textContent;
				else
					return;
				
				if (child2 = getChild(child, 'subtitle'))
					projectSubtitle = child2.textContent;
			} else {
				projectName = '';
				projectTitle = $('organization-heading').textContent;
			}
			
			// Location
			if (child = getChild(node, 'location')) {
				if (child = getChild(child, 'node')) {
					function getNodeInfo(node) {
						var isActive = node.hasAttribute('active');
						
						var child; 
						if (child = getChild(node, 'url')) {
							var url = child.textContent;
						} else
							return null;
	
						if (child = getChild(node, 'title')) {
							var title = child.textContent;
						} else
							return null;
						
						// get children
						var children = new Array();
						for (var i = 0; i < node.childNodes.length; i++) {
							if (node.childNodes[i].nodeName.toLowerCase() == 'node') {
								var nodeInfo = getNodeInfo(node.childNodes[i]);
								if (nodeInfo != null)
									children[children.length] = nodeInfo;
							}
						}
						
						return {
							url: url,
							title: title,
							isActive: isActive,
							children: children
						};
					}
					location = getNodeInfo(child);
				} else
					return;
			} else
				return;
			
			var isIndexPage = (projectName != '' && location.length <= 2) ||
				(projectName == '' && location.length <= 1);
			
			// Update title
			if (!isIndexPage)
				document.title = projectTitle + Premanager.Translation.titleDivider + pageTitle;
			else if (projectSubtitle != '')
				document.title = projectTitle + Premanager.Translation.titleDivider + projectSubtitle;
			else
				document.title = projectTitle;
			
			// Update header
			var projectHeadingTag = $('project-heading');
			if (projectName == '') {
				if (projectHeadingTag)
					projectHeadingTag.parentNode.removeChild(projectHeadingTag);
			} else {
				if (projectHeadingTag) {
					var aTag = getChild(projectHeadingTag, 'a');
					if (!aTag) {
						aTag = document.createElement('a');
						projectHeadingTag.appendChild(aTag);
					}
					aTag.textContent = projectTitle;
					aTag.href = document.baseURI + projectName;
				} else {
					var hgroupTag = getChild($('header'), 'hgroup');
					projectHeadingTag = document.createElement('h2');
					projectHeadingTag.id = 'project-heading';
					hgroupTag.appendChild(projectHeadingTag);
					var aTag = document.createElement('a');
					projectHeadingTag.appendChild(aTag);
					aTag.href = document.baseURI + projectName;
					aTag.appendChild(document.createTextNode(projectTitle));
				}
				Premanager.SmartPageload.replaceLinks(aTag);
			}
			
			// Update navbar
			var navbarTag = $('navbar');
			var navbarListTag = getChild(navbarTag, 'ul');
			while (navbarListTag.childNodes.length)
				navbarListTag.removeChild(navbarListTag.childNodes[0]);
			var nodeInfo = location;
			while (nodeInfo != null) {
				var itemTag = document.createElement('li');
				navbarListTag.appendChild(itemTag);
				var aTag = document.createElement('a');
				itemTag.appendChild(aTag);
				aTag.href = './' + nodeInfo.url;
				aTag.textContent = nodeInfo.title;
				
				function getNext() {
					if (nodeInfo.children.length) {
						// The main child is either an active node or has
						// children
						for (var i = 0; i < nodeInfo.children.length; i++) {
							if (nodeInfo.children[i].isActive)
								return nodeInfo.children[i];
							if (nodeInfo.children[i].children.length > 0)
								return nodeInfo.children[i];
						}
					}
				}
				
				nodeInfo = getNext();
			}
			
			// Update navigation tree
			var navtreeTag = $('navigation-tree');
			var listTag = getChild(navtreeTag, 'ul');
			function nodeInfoToTag(nodeInfo) {
				var tag = document.createElement('li');
				if (nodeInfo.isActive)
					tag.setAttribute('class', 'active');
				var aTag = document.createElement('a');
				aTag.textContent = nodeInfo.title;
				aTag.href = './' + nodeInfo.url;
				tag.appendChild(aTag);
				
				if (nodeInfo.children.length > 0) {
					var listTag = document.createElement('ul');
					tag.appendChild(listTag);
					for (var i = 0; i < nodeInfo.children.length; i++) {
						var childTag = nodeInfoToTag(nodeInfo.children[i]);
						listTag.appendChild(childTag);
					}
				}
				return tag;
			}
			var navtreeRootTag = nodeInfoToTag(location);
			if (navtreeRootTag == null)
				return;
			while (listTag.childNodes.length)
				listTag.removeChild(listTag.childNodes[0]);
			listTag.appendChild(navtreeRootTag);
			Premanager.SmartPageload.replaceLinks(navtreeRootTag);
			
			// Update content
			while (contentTag.childNodes.length)
				contentTag.removeChild(contentTag.childNodes[0]);
			
			if (child = getChild(node, 'content')) {
				for (var i = 0; i < child.childNodes.length; i++) {
					if (child.childNodes[i].nodeName.toLowerCase() != 'row')
						continue;
					var row = child.childNodes[i];
					
					var rowTag = document.createElement('div');
					rowTag.className = 'block-row';
					contentTag.appendChild(rowTag);

					for (var j = 0; j < row.childNodes.length; j++) {
						if (row.childNodes[j].nodeName.toLowerCase() != 'col')
							continue;
						var col = row.childNodes[j];
						
						var colTag = document.createElement('div');
						colTag.className = 'block-col';
						rowTag.appendChild(colTag);

						for (var k = 0; k < col.childNodes.length; k++) {
							if (col.childNodes[k].nodeName.toLowerCase() != 'block')
								continue;
							var block = col.childNodes[k];
							if (content = getChild(block, 'content')) {
								for (var l = 0; l < content.childNodes.length; l++) {
									colTag.appendChild(clone(content.childNodes[l]));
								}
							}
						}
					}
				}
				Premanager.SmartPageload.replaceLinks(contentTag);

				var elapsedTime = responseTime - startTime;
				var buildingTime = new Date().getTime() - responseTime;
				var p = $('footer-time-info');
				if (!p) {
					var footer = $('footer');
					var p = document.createElement('p');
					p.id = 'footer-time-info';
					footer.appendChild(p);
				}
				p.textContent = elapsedTime + ' ms' + ' + ' + buildingTime + ' ms';
				return true;
			} else
				return;
		}
		
		var ajaxURL = document.documentElement.baseURI + '%21Premanager/dynamic?url=' + encodeURIComponent(url);
		new Ajax.Request(ajaxURL, {
			method: 'GET',
			onSuccess: function(response) {
				try {
					xml = response.responseXML;
					if (xml) {
						var node = xml.documentElement;
						switch (node ? node.getAttribute('type') : null) {
							case 'page':
								if (!loadPage(node))
									location.href = url;
								break;
							case 'not-found':
								location.href = url;
								break;
							default:
								location.href = url;
						}
					} else
						location.href = url;
				} catch (exception) {
					location.href = url;
				}
			},
			
			onFailure: function(response) {
				location.href = url;
			}});
	}
}

Event.observe(window, 'load', function() { Premanager.SmartPageload.replaceLinks(); });