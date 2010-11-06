// Smart Pageload

if (typeof(Premanager) == 'undefined')
	Premanager = {};

Premanager.SmartPageload = {
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
			var title;
			var contentTag = document.getElementById('content');
			while (contentTag.childNodes.length)
				contentTag.removeChild(contentTag.childNodes[0]);
			
			if (child = getChild(node, 'title')) {
				var title = child.text;
				//getChild(getChild(document.documentElement, 'head'), 'title').textContent = title;
			} else
				return;
			
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
				} catch (Exception) {
					location.href = url;
				}
			},
			
			onFailure: function(response) {
				location.href = url;
			}});
	}
}

Event.observe(window, 'load', function() { Premanager.SmartPageload.replaceLinks(); });