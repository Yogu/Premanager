// Smart Pageload

if (typeof(Premanager) == 'undefined')
	Premanager = {};

Premanager.SmartPageload = {
	titleDivider: ' – ', //TODO: replace this with translated string	
		
	replaceLinks: function(node) {
		var getHostAndPath = function(url) {
			url = url.substring('http://'.length, url.length);
			return url.split('/', 2);
		}
		
		var trunk = getHostAndPath(Config.emptyURLPrefix);
		
		var crawl = function(node) {
			if (node.nodeName == 'A') {
				var hostAndPath = getHostAndPath(node.href);
				if (hostAndPath[0].endsWith(trunk[0]) && hostAndPath[1].startsWith(trunk[1])) {
					node.onclick = function() {
						Premanager.SmartPageload.browse(node.href);
						return false;
					}
				}
			}
			for (var i = 0; i < node.childNodes.length; i++) {
				crawl(node.childNodes[i]);
			}
		};
		crawl(node ? node : document.documentElement);
	},

	browse: function(url) {
		var loadPage = function(node) {
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
			var location = new Array();
			
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
				for (var i = 0; i < child.childNodes.length; i++) {
					if (child.childNodes[i].nodeName.toLowerCase() == 'node') {
						if (child2 =  getChild(child.childNodes[i], 'url')) {
							var url = child2.textContent;
						} else
							continue;

						if (child2 =  getChild(child.childNodes[i], 'title')) {
							var title = child2.textContent;
						} else
							continue;
						
						location[location.length] = {url: url, title: title};
					}
				}
			} else
				return;
			
			var isIndexPage = (projectName != '' && location.length <= 2) ||
				(projectName == '' && location.length <= 1);
			
			// Update title
			if (!isIndexPage)
				document.title = projectTitle + Premanager.SmartPageload.titleDivider + pageTitle;
			else if (projectSubtitle != '')
				document.title = projectTitle + Premanager.SmartPageload.titleDivider + projectSubtitle;
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
			for (var i = 0; i < location.length; i++) {
				var itemTag = document.createElement('li');
				navbarListTag.appendChild(itemTag);
				var aTag = document.createElement('a');
				itemTag.appendChild(aTag);
				aTag.href = './' + location[i].url;
				aTag.textContent = location[i].title;
			}
			Premanager.SmartPageload.replaceLinks(navbarListTag);
			
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