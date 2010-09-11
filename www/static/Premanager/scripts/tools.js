// Array
Array.prototype.contains = function(obj) {
	for (var i = 0; i < this.length; i++) {
		if (this[i] == obj)
			return true;
	}
	return false;	
}

Array.prototype.remove = function(obj) {
	for (var i = 0; i < this.length; i++) {
		if (this[i] == obj) {
			this.splice(i, 1);
			return true;
		}
	}
	return false;	
}

/*// Object
Object.extend = function(destination, source) {
  for (var property in source)
    destination[property] = source[property];
  return destination;
};

// Event
function Event() {
	this.clients = new Array();
}

Event.prototype.add = function(obj, method) {
	for (var i = 0; i < this.clients.length; i++) {
		if (this.clients.obj == obj && this.clients.method == method)
			return false;
	}
	this.clients.shift({obj: obj, method: method});    
	return true;
}       

Event.prototype.remove = function(obj, method) {
	for (var i = 0; i < this.clients.length; i++) {
		if (this.clients.obj == obj && this.clients.method == method)
			this.clients.splice(i, 1);
			return true;
	}    
	return false;
}

Event.prototype.call = function(e) {
	for (var i = 0; i < this.clients.length; i++) {
		this.clients[i].method.call(this.clients[i].obj, e);		
	}	
}

Event.make = function(obj, name) {	
	obj[name] = function(e) {obj[name].event.call(e);};
	obj[name].event = new Event();
	obj[name].add = function(a, b) {
		obj[name].event.add.call(obj[name].event, a, b);
	};
	obj[name].remove = function(a, b) {
		obj[name].event.remove.call(obj[name].event, a, b);
	}; 
}

//document.onclick = function(e) {document.onclick.event.call(e);};
//document.onclick.event = new Event();

/*function event() {
	// Old handler is event?
	if (arguments.length >= 1 && arguments[0] != null && arguments[0].isEvent) {
		for (var i = 1; i < arguments.length; i++) {
			arguments[0].add(arguments[i]);
		}
		return arguments[0];		
	} else { 
		var clients = arguments;
		var method = function(event) {
			for (var i = 0; i < clients.length; i++) {
				clients[i](event);		
			}	
		}
		method.add = function() {
			for (var i = 0; i < arguments.length; i++) {
				if (!clients.contains(arguments[i]))
					clients.add(arguments[i]);
			}
		}
		method.remove = function() {
			for (var i = 0; i < arguments.length; i++) {
				clients.remove(arguments[i]);
			}
		}
		method.isEvent = true;
		return method;
	}
}*/

//+ Jonas Raoni Soares Silva
//@ http://jsfromhell.com [v1.0]
String.prototype.repeat = function(l){
	return new Array(l+1).join(this);
};

// Thanks to http://www.evocomp.de/beispiele/javascript/trim.html
String.prototype.trim = function () {
	return this.ltrim().rtrim();
}
String.prototype.ltrim = function () {
	return this.replace (/^\s+/, '');
}
String.prototype.rtrim = function () {
	return this.replace (/\s+$/, '');
}
	
// Converts html special chars into entities
function addHTMLEntities(value) {
	return value.replace(/\&/g, '&amp;').replace(/\</g, '&lt;').replace(/\>/g, '&gt;').replace(/\"/g, '&quot;');	
}        

// Capture mouse movement
var cursorPos;
document.onmousemove = function (event){
	event = event || window.event;
	cursorPos = mouseCoords(event);
}

function mouseCoords(event){
	if(event.pageX || event.pageY){
		return {x:event.pageX, y:event.pageY};
	}
	return {
		x: event.clientX + document.body.scrollLeft - document.body.clientLeft,
		y: event.clientY + document.body.scrollTop  - document.body.clientTop
	};
}

function getUniqueID() {
	// Thanks to http://bytes.com/topic/javascript/answers/523253-how-create-guid-javascript#post2042003
	function S4() {                                                    
		return (((1+Math.random())*0x10000)|0).toString(16).substring(1)
	}
	
	return 'unique_'+S4()+S4()+S4();
}

RegExps = {
	URL: /^(http|https|ftp)\:\/\/([0-9a-z]([0-9a-z-]*[0-9a-z])?\.)+[0-9a-z]{2,6}(\/.*)?$/i,
	EMAIL: /^[0-9a-z\.!#$%&\'*+\/=?^_`{|}~-]+@([0-9a-z]([0-9a-z-]*[0-9a-z]{2,6})?\.)+[0-9a-z]$/i
};