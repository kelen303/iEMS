/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/

/*
	This is a compiled version of Dojo, built for deployment and not for
	development. To get an editable version, please visit:

		http://dojotoolkit.org

	for documentation and information on getting the source.
*/

dojo.provide("iems.login");
if(!dojo._hasResource["dijit._base.manager"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit._base.manager"] = true;
dojo.provide("dijit._base.manager");

dojo.declare("dijit.WidgetSet", null, {
	// summary:
	//		A set of widgets indexed by id. A default instance of this class is
	//		available as `dijit.registry`
	//
	// example:
	//		Create a small list of widgets:
	//		|	var ws = new dijit.WidgetSet();
	//		|	ws.add(dijit.byId("one"));
	//		| 	ws.add(dijit.byId("two"));
	//		|	// destroy both:
	//		|	ws.forEach(function(w){ w.destroy(); });
	//
	// example:
	//		Using dijit.registry:
	//		|	dijit.registry.forEach(function(w){ /* do something */ });

	constructor: function(){
		this._hash = {};
		this.length = 0;
	},

	add: function(/*dijit._Widget*/ widget){
		// summary:
		//		Add a widget to this list. If a duplicate ID is detected, a error is thrown.
		//
		// widget: dijit._Widget
		//		Any dijit._Widget subclass.
		if(this._hash[widget.id]){
			throw new Error("Tried to register widget with id==" + widget.id + " but that id is already registered");
		}
		this._hash[widget.id] = widget;
		this.length++;
	},

	remove: function(/*String*/ id){
		// summary:
		//		Remove a widget from this WidgetSet. Does not destroy the widget; simply
		//		removes the reference.
		if(this._hash[id]){
			delete this._hash[id];
			this.length--;
		}
	},

	forEach: function(/*Function*/ func, /* Object? */thisObj){
		// summary:
		//		Call specified function for each widget in this set.
		//
		// func:
		//		A callback function to run for each item. Is passed the widget, the index
		//		in the iteration, and the full hash, similar to `dojo.forEach`.
		//
		// thisObj:
		//		An optional scope parameter
		//
		// example:
		//		Using the default `dijit.registry` instance:
		//		|	dijit.registry.forEach(function(widget){
		//		|		console.log(widget.declaredClass);
		//		|	});
		//
		// returns:
		//		Returns self, in order to allow for further chaining.

		thisObj = thisObj || dojo.global;
		var i = 0, id;
		for(id in this._hash){
			func.call(thisObj, this._hash[id], i++, this._hash);
		}
		return this;	// dijit.WidgetSet
	},

	filter: function(/*Function*/ filter, /* Object? */thisObj){
		// summary:
		//		Filter down this WidgetSet to a smaller new WidgetSet
		//		Works the same as `dojo.filter` and `dojo.NodeList.filter`
		//
		// filter:
		//		Callback function to test truthiness. Is passed the widget
		//		reference and the pseudo-index in the object.
		//
		// thisObj: Object?
		//		Option scope to use for the filter function.
		//
		// example:
		//		Arbitrary: select the odd widgets in this list
		//		|	dijit.registry.filter(function(w, i){
		//		|		return i % 2 == 0;
		//		|	}).forEach(function(w){ /* odd ones */ });

		thisObj = thisObj || dojo.global;
		var res = new dijit.WidgetSet(), i = 0, id;
		for(id in this._hash){
			var w = this._hash[id];
			if(filter.call(thisObj, w, i++, this._hash)){
				res.add(w);
			}
		}
		return res; // dijit.WidgetSet
	},

	byId: function(/*String*/ id){
		// summary:
		//		Find a widget in this list by it's id.
		// example:
		//		Test if an id is in a particular WidgetSet
		//		| var ws = new dijit.WidgetSet();
		//		| ws.add(dijit.byId("bar"));
		//		| var t = ws.byId("bar") // returns a widget
		//		| var x = ws.byId("foo"); // returns undefined

		return this._hash[id];	// dijit._Widget
	},

	byClass: function(/*String*/ cls){
		// summary:
		//		Reduce this widgetset to a new WidgetSet of a particular `declaredClass`
		//
		// cls: String
		//		The Class to scan for. Full dot-notated string.
		//
		// example:
		//		Find all `dijit.TitlePane`s in a page:
		//		|	dijit.registry.byClass("dijit.TitlePane").forEach(function(tp){ tp.close(); });

		var res = new dijit.WidgetSet(), id, widget;
		for(id in this._hash){
			widget = this._hash[id];
			if(widget.declaredClass == cls){
				res.add(widget);
			}
		 }
		 return res; // dijit.WidgetSet
},

	toArray: function(){
		// summary:
		//		Convert this WidgetSet into a true Array
		//
		// example:
		//		Work with the widget .domNodes in a real Array
		//		|	dojo.map(dijit.registry.toArray(), function(w){ return w.domNode; });

		var ar = [];
		for(var id in this._hash){
			ar.push(this._hash[id]);
		}
		return ar;	// dijit._Widget[]
},

	map: function(/* Function */func, /* Object? */thisObj){
		// summary:
		//		Create a new Array from this WidgetSet, following the same rules as `dojo.map`
		// example:
		//		|	var nodes = dijit.registry.map(function(w){ return w.domNode; });
		//
		// returns:
		//		A new array of the returned values.
		return dojo.map(this.toArray(), func, thisObj); // Array
	},

	every: function(func, thisObj){
		// summary:
		// 		A synthetic clone of `dojo.every` acting explictly on this WidgetSet
		//
		// func: Function
		//		A callback function run for every widget in this list. Exits loop
		//		when the first false return is encountered.
		//
		// thisObj: Object?
		//		Optional scope parameter to use for the callback

		thisObj = thisObj || dojo.global;
		var x = 0, i;
		for(i in this._hash){
			if(!func.call(thisObj, this._hash[i], x++, this._hash)){
				return false; // Boolean
			}
		}
		return true; // Boolean
	},

	some: function(func, thisObj){
		// summary:
		// 		A synthetic clone of `dojo.some` acting explictly on this WidgetSet
		//
		// func: Function
		//		A callback function run for every widget in this list. Exits loop
		//		when the first true return is encountered.
		//
		// thisObj: Object?
		//		Optional scope parameter to use for the callback

		thisObj = thisObj || dojo.global;
		var x = 0, i;
		for(i in this._hash){
			if(func.call(thisObj, this._hash[i], x++, this._hash)){
				return true; // Boolean
			}
		}
		return false; // Boolean
	}

});

/*=====
dijit.registry = {
	// summary:
	//		A list of widgets on a page.
	// description:
	//		Is an instance of `dijit.WidgetSet`
};
=====*/
dijit.registry= new dijit.WidgetSet();

dijit._widgetTypeCtr = {};

dijit.getUniqueId = function(/*String*/widgetType){
	// summary:
	//		Generates a unique id for a given widgetType

	var id;
	do{
		id = widgetType + "_" +
			(widgetType in dijit._widgetTypeCtr ?
				++dijit._widgetTypeCtr[widgetType] : dijit._widgetTypeCtr[widgetType] = 0);
	}while(dijit.byId(id));
	return id; // String
};

dijit.findWidgets = function(/*DomNode*/ root){
	// summary:
	//		Search subtree under root returning widgets found.
	//		Doesn't search for nested widgets (ie, widgets inside other widgets).

	var outAry = [];

	function getChildrenHelper(root){
		for(var node = root.firstChild; node; node = node.nextSibling){
			if(node.nodeType == 1){
				var widgetId = node.getAttribute("widgetId");
				if(widgetId){
					var widget = dijit.byId(widgetId);
					outAry.push(widget);
				}else{
					getChildrenHelper(node);
				}
			}
		}
	}

	getChildrenHelper(root);
	return outAry;
};

dijit._destroyAll = function(){
	// summary:
	//		Code to destroy all widgets and do other cleanup on page unload

	// Clean up focus manager lingering references to widgets and nodes
	dijit._curFocus = null;
	dijit._prevFocus = null;
	dijit._activeStack = [];

	// Destroy all the widgets, top down
	dojo.forEach(dijit.findWidgets(dojo.body()), function(widget){
		// Avoid double destroy of widgets like Menu that are attached to <body>
		// even though they are logically children of other widgets.
		if(!widget._destroyed){
			if(widget.destroyRecursive){
				widget.destroyRecursive();
			}else if(widget.destroy){
				widget.destroy();
			}
		}
	});
};

if(dojo.isIE){
	// Only run _destroyAll() for IE because we think it's only necessary in that case,
	// and because it causes problems on FF.  See bug #3531 for details.
	dojo.addOnWindowUnload(function(){
		dijit._destroyAll();
	});
}

dijit.byId = function(/*String|Widget*/id){
	// summary:
	//		Returns a widget by it's id, or if passed a widget, no-op (like dojo.byId())
	return typeof id == "string" ? dijit.registry._hash[id] : id; // dijit._Widget
};

dijit.byNode = function(/* DOMNode */ node){
	// summary:
	//		Returns the widget corresponding to the given DOMNode
	return dijit.registry.byId(node.getAttribute("widgetId")); // dijit._Widget
};

dijit.getEnclosingWidget = function(/* DOMNode */ node){
	// summary:
	//		Returns the widget whose DOM tree contains the specified DOMNode, or null if
	//		the node is not contained within the DOM tree of any widget
	while(node){
		var id = node.getAttribute && node.getAttribute("widgetId");
		if(id){
			return dijit.byId(id);
		}
		node = node.parentNode;
	}
	return null;
};

dijit._isElementShown = function(/*Element*/elem){
	var style = dojo.style(elem);
	return (style.visibility != "hidden")
		&& (style.visibility != "collapsed")
		&& (style.display != "none")
		&& (dojo.attr(elem, "type") != "hidden");
}

dijit.isTabNavigable = function(/*Element*/elem){
	// summary:
	//		Tests if an element is tab-navigable

	// TODO: convert (and rename method) to return effectivite tabIndex; will save time in _getTabNavigable()
	if(dojo.attr(elem, "disabled")){
		return false;
	}else if(dojo.hasAttr(elem, "tabIndex")){
		// Explicit tab index setting
		return dojo.attr(elem, "tabIndex") >= 0; // boolean
	}else{
		// No explicit tabIndex setting, need to investigate node type
		switch(elem.nodeName.toLowerCase()){
			case "a":
				// An <a> w/out a tabindex is only navigable if it has an href
				return dojo.hasAttr(elem, "href");
			case "area":
			case "button":
			case "input":
			case "object":
			case "select":
			case "textarea":
				// These are navigable by default
				return true;
			case "iframe":
				// If it's an editor <iframe> then it's tab navigable.
				if(dojo.isMoz){
					return elem.contentDocument.designMode == "on";
				}else if(dojo.isWebKit){
					var doc = elem.contentDocument,
						body = doc && doc.body;
					return body && body.contentEditable == 'true';
				}else{
					doc = elem.contentWindow.document;
					body = doc && doc.body;
					return body && body.firstChild && body.firstChild.contentEditable == 'true';
				}
			default:
				return elem.contentEditable == 'true';
		}
	}
};

dijit._getTabNavigable = function(/*DOMNode*/root){
	// summary:
	//		Finds descendants of the specified root node.
	//
	// description:
	//		Finds the following descendants of the specified root node:
	//		* the first tab-navigable element in document order
	//		  without a tabIndex or with tabIndex="0"
	//		* the last tab-navigable element in document order
	//		  without a tabIndex or with tabIndex="0"
	//		* the first element in document order with the lowest
	//		  positive tabIndex value
	//		* the last element in document order with the highest
	//		  positive tabIndex value
	var first, last, lowest, lowestTabindex, highest, highestTabindex;
	var walkTree = function(/*DOMNode*/parent){
		dojo.query("> *", parent).forEach(function(child){
			var isShown = dijit._isElementShown(child);
			if(isShown && dijit.isTabNavigable(child)){
				var tabindex = dojo.attr(child, "tabIndex");
				if(!dojo.hasAttr(child, "tabIndex") || tabindex == 0){
					if(!first){ first = child; }
					last = child;
				}else if(tabindex > 0){
					if(!lowest || tabindex < lowestTabindex){
						lowestTabindex = tabindex;
						lowest = child;
					}
					if(!highest || tabindex >= highestTabindex){
						highestTabindex = tabindex;
						highest = child;
					}
				}
			}
			if(isShown && child.nodeName.toUpperCase() != 'SELECT'){ walkTree(child) }
		});
	};
	if(dijit._isElementShown(root)){ walkTree(root) }
	return { first: first, last: last, lowest: lowest, highest: highest };
}
dijit.getFirstInTabbingOrder = function(/*String|DOMNode*/root){
	// summary:
	//		Finds the descendant of the specified root node
	//		that is first in the tabbing order
	var elems = dijit._getTabNavigable(dojo.byId(root));
	return elems.lowest ? elems.lowest : elems.first; // DomNode
};

dijit.getLastInTabbingOrder = function(/*String|DOMNode*/root){
	// summary:
	//		Finds the descendant of the specified root node
	//		that is last in the tabbing order
	var elems = dijit._getTabNavigable(dojo.byId(root));
	return elems.last ? elems.last : elems.highest; // DomNode
};

/*=====
dojo.mixin(dijit, {
	// defaultDuration: Integer
	//		The default animation speed (in ms) to use for all Dijit
	//		transitional animations, unless otherwise specified
	//		on a per-instance basis. Defaults to 200, overrided by
	//		`djConfig.defaultDuration`
	defaultDuration: 300
});
=====*/

dijit.defaultDuration = dojo.config["defaultDuration"] || 200;

}

if(!dojo._hasResource["dijit._base.focus"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit._base.focus"] = true;
dojo.provide("dijit._base.focus");

	// for dijit.isTabNavigable()

// summary:
//		These functions are used to query or set the focus and selection.
//
//		Also, they trace when widgets become activated/deactivated,
//		so that the widget can fire _onFocus/_onBlur events.
//		"Active" here means something similar to "focused", but
//		"focus" isn't quite the right word because we keep track of
//		a whole stack of "active" widgets.  Example: ComboButton --> Menu -->
//		MenuItem.  The onBlur event for ComboButton doesn't fire due to focusing
//		on the Menu or a MenuItem, since they are considered part of the
//		ComboButton widget.  It only happens when focus is shifted
//		somewhere completely different.

dojo.mixin(dijit, {
	// _curFocus: DomNode
	//		Currently focused item on screen
	_curFocus: null,

	// _prevFocus: DomNode
	//		Previously focused item on screen
	_prevFocus: null,

	isCollapsed: function(){
		// summary:
		//		Returns true if there is no text selected
		return dijit.getBookmark().isCollapsed;
	},

	getBookmark: function(){
		// summary:
		//		Retrieves a bookmark that can be used with moveToBookmark to return to the same range
		var bm, rg, tg, sel = dojo.doc.selection, cf = dijit._curFocus;

		if(dojo.global.getSelection){
			//W3C Range API for selections.
			sel = dojo.global.getSelection();
			if(sel){
				if(sel.isCollapsed){
					tg = cf? cf.tagName : "";
					if(tg){
						//Create a fake rangelike item to restore selections.
						tg = tg.toLowerCase();
						if(tg == "textarea" ||
								(tg == "input" && (!cf.type || cf.type.toLowerCase() == "text"))){
							sel = {
								start: cf.selectionStart,
								end: cf.selectionEnd,
								node: cf,
								pRange: true
							};
							return {isCollapsed: (sel.end <= sel.start), mark: sel}; //Object.
						}
					}
					bm = {isCollapsed:true};
				}else{
					rg = sel.getRangeAt(0);
					bm = {isCollapsed: false, mark: rg.cloneRange()};
				}
			}
		}else if(sel){
			// If the current focus was a input of some sort and no selection, don't bother saving
			// a native bookmark.  This is because it causes issues with dialog/page selection restore.
			// So, we need to create psuedo bookmarks to work with.
			tg = cf ? cf.tagName : "";
			tg = tg.toLowerCase();
			if(cf && tg && (tg == "button" || tg == "textarea" || tg == "input")){
				if(sel.type && sel.type.toLowerCase() == "none"){
					return {
						isCollapsed: true,
						mark: null
					}
				}else{
					rg = sel.createRange();
					return {
						isCollapsed: rg.text && rg.text.length?false:true,
						mark: {
							range: rg,
							pRange: true
						}
					};
				}
			}
			bm = {};

			//'IE' way for selections.
			try{
				// createRange() throws exception when dojo in iframe
				//and nothing selected, see #9632
				rg = sel.createRange();
				bm.isCollapsed = !(sel.type == 'Text' ? rg.htmlText.length : rg.length);
			}catch(e){
				bm.isCollapsed = true;
				return bm;
			}
			if(sel.type.toUpperCase() == 'CONTROL'){
				if(rg.length){
					bm.mark=[];
					var i=0,len=rg.length;
					while(i<len){
						bm.mark.push(rg.item(i++));
					}
				}else{
					bm.isCollapsed = true;
					bm.mark = null;
				}
			}else{
				bm.mark = rg.getBookmark();
			}
		}else{
			console.warn("No idea how to store the current selection for this browser!");
		}
		return bm; // Object
	},

	moveToBookmark: function(/*Object*/bookmark){
		// summary:
		//		Moves current selection to a bookmark
		// bookmark:
		//		This should be a returned object from dijit.getBookmark()

		var _doc = dojo.doc,
			mark = bookmark.mark;
		if(mark){
			if(dojo.global.getSelection){
				//W3C Rangi API (FF, WebKit, Opera, etc)
				var sel = dojo.global.getSelection();
				if(sel && sel.removeAllRanges){
					if(mark.pRange){
						var r = mark;
						var n = r.node;
						n.selectionStart = r.start;
						n.selectionEnd = r.end;
					}else{
						sel.removeAllRanges();
						sel.addRange(mark);
					}
				}else{
					console.warn("No idea how to restore selection for this browser!");
				}
			}else if(_doc.selection && mark){
				//'IE' way.
				var rg;
				if(mark.pRange){
					rg = mark.range;
				}else if(dojo.isArray(mark)){
					rg = _doc.body.createControlRange();
					//rg.addElement does not have call/apply method, so can not call it directly
					//rg is not available in "range.addElement(item)", so can't use that either
					dojo.forEach(mark, function(n){
						rg.addElement(n);
					});
				}else{
					rg = _doc.body.createTextRange();
					rg.moveToBookmark(mark);
				}
				rg.select();
			}
		}
	},

	getFocus: function(/*Widget?*/ menu, /*Window?*/ openedForWindow){
		// summary:
		//		Called as getFocus(), this returns an Object showing the current focus
		//		and selected text.
		//
		//		Called as getFocus(widget), where widget is a (widget representing) a button
		//		that was just pressed, it returns where focus was before that button
		//		was pressed.   (Pressing the button may have either shifted focus to the button,
		//		or removed focus altogether.)   In this case the selected text is not returned,
		//		since it can't be accurately determined.
		//
		// menu: dijit._Widget or {domNode: DomNode} structure
		//		The button that was just pressed.  If focus has disappeared or moved
		//		to this button, returns the previous focus.  In this case the bookmark
		//		information is already lost, and null is returned.
		//
		// openedForWindow:
		//		iframe in which menu was opened
		//
		// returns:
		//		A handle to restore focus/selection, to be passed to `dijit.focus`
		var node = !dijit._curFocus || (menu && dojo.isDescendant(dijit._curFocus, menu.domNode)) ? dijit._prevFocus : dijit._curFocus;
		return {
			node: node,
			bookmark: (node == dijit._curFocus) && dojo.withGlobal(openedForWindow || dojo.global, dijit.getBookmark),
			openedForWindow: openedForWindow
		}; // Object
	},

	focus: function(/*Object || DomNode */ handle){
		// summary:
		//		Sets the focused node and the selection according to argument.
		//		To set focus to an iframe's content, pass in the iframe itself.
		// handle:
		//		object returned by get(), or a DomNode

		if(!handle){ return; }

		var node = "node" in handle ? handle.node : handle,		// because handle is either DomNode or a composite object
			bookmark = handle.bookmark,
			openedForWindow = handle.openedForWindow,
			collapsed = bookmark ? bookmark.isCollapsed : false;

		// Set the focus
		// Note that for iframe's we need to use the <iframe> to follow the parentNode chain,
		// but we need to set focus to iframe.contentWindow
		if(node){
			var focusNode = (node.tagName.toLowerCase() == "iframe") ? node.contentWindow : node;
			if(focusNode && focusNode.focus){
				try{
					// Gecko throws sometimes if setting focus is impossible,
					// node not displayed or something like that
					focusNode.focus();
				}catch(e){/*quiet*/}
			}
			dijit._onFocusNode(node);
		}

		// set the selection
		// do not need to restore if current selection is not empty
		// (use keyboard to select a menu item) or if previous selection was collapsed
		// as it may cause focus shift (Esp in IE).
		if(bookmark && dojo.withGlobal(openedForWindow || dojo.global, dijit.isCollapsed) && !collapsed){
			if(openedForWindow){
				openedForWindow.focus();
			}
			try{
				dojo.withGlobal(openedForWindow || dojo.global, dijit.moveToBookmark, null, [bookmark]);
			}catch(e2){
				/*squelch IE internal error, see http://trac.dojotoolkit.org/ticket/1984 */
			}
		}
	},

	// _activeStack: dijit._Widget[]
	//		List of currently active widgets (focused widget and it's ancestors)
	_activeStack: [],

	registerIframe: function(/*DomNode*/ iframe){
		// summary:
		//		Registers listeners on the specified iframe so that any click
		//		or focus event on that iframe (or anything in it) is reported
		//		as a focus/click event on the <iframe> itself.
		// description:
		//		Currently only used by editor.
		// returns:
		//		Handle to pass to unregisterIframe()
		return dijit.registerWin(iframe.contentWindow, iframe);
	},

	unregisterIframe: function(/*Object*/ handle){
		// summary:
		//		Unregisters listeners on the specified iframe created by registerIframe.
		//		After calling be sure to delete or null out the handle itself.
		// handle:
		//		Handle returned by registerIframe()

		dijit.unregisterWin(handle);
	},

	registerWin: function(/*Window?*/targetWindow, /*DomNode?*/ effectiveNode){
		// summary:
		//		Registers listeners on the specified window (either the main
		//		window or an iframe's window) to detect when the user has clicked somewhere
		//		or focused somewhere.
		// description:
		//		Users should call registerIframe() instead of this method.
		// targetWindow:
		//		If specified this is the window associated with the iframe,
		//		i.e. iframe.contentWindow.
		// effectiveNode:
		//		If specified, report any focus events inside targetWindow as
		//		an event on effectiveNode, rather than on evt.target.
		// returns:
		//		Handle to pass to unregisterWin()

		// TODO: make this function private in 2.0; Editor/users should call registerIframe(),

		var mousedownListener = function(evt){
			dijit._justMouseDowned = true;
			setTimeout(function(){ dijit._justMouseDowned = false; }, 0);
			dijit._onTouchNode(effectiveNode || evt.target || evt.srcElement, "mouse");
		};
		//dojo.connect(targetWindow, "onscroll", ???);

		// Listen for blur and focus events on targetWindow's document.
		// IIRC, I'm using attachEvent() rather than dojo.connect() because focus/blur events don't bubble
		// through dojo.connect(), and also maybe to catch the focus events early, before onfocus handlers
		// fire.
		// Connect to <html> (rather than document) on IE to avoid memory leaks, but document on other browsers because
		// (at least for FF) the focus event doesn't fire on <html> or <body>.
		var doc = dojo.isIE ? targetWindow.document.documentElement : targetWindow.document;
		if(doc){
			if(dojo.isIE){
				doc.attachEvent('onmousedown', mousedownListener);
				var activateListener = function(evt){
					// IE reports that nodes like <body> have gotten focus, even though they have tabIndex=-1,
					// Should consider those more like a mouse-click than a focus....
					if(evt.srcElement.tagName.toLowerCase() != "#document" &&
						dijit.isTabNavigable(evt.srcElement)){
						dijit._onFocusNode(effectiveNode || evt.srcElement);
					}else{
						dijit._onTouchNode(effectiveNode || evt.srcElement);
					}
				};
				doc.attachEvent('onactivate', activateListener);
				var deactivateListener =  function(evt){
					dijit._onBlurNode(effectiveNode || evt.srcElement);
				};
				doc.attachEvent('ondeactivate', deactivateListener);

				return function(){
					doc.detachEvent('onmousedown', mousedownListener);
					doc.detachEvent('onactivate', activateListener);
					doc.detachEvent('ondeactivate', deactivateListener);
					doc = null;	// prevent memory leak (apparent circular reference via closure)
				};
			}else{
				doc.addEventListener('mousedown', mousedownListener, true);
				var focusListener = function(evt){
					dijit._onFocusNode(effectiveNode || evt.target);
				};
				doc.addEventListener('focus', focusListener, true);
				var blurListener = function(evt){
					dijit._onBlurNode(effectiveNode || evt.target);
				};
				doc.addEventListener('blur', blurListener, true);

				return function(){
					doc.removeEventListener('mousedown', mousedownListener, true);
					doc.removeEventListener('focus', focusListener, true);
					doc.removeEventListener('blur', blurListener, true);
					doc = null;	// prevent memory leak (apparent circular reference via closure)
				};
			}
		}
	},

	unregisterWin: function(/*Handle*/ handle){
		// summary:
		//		Unregisters listeners on the specified window (either the main
		//		window or an iframe's window) according to handle returned from registerWin().
		//		After calling be sure to delete or null out the handle itself.

		// Currently our handle is actually a function
		handle && handle();
	},

	_onBlurNode: function(/*DomNode*/ node){
		// summary:
		// 		Called when focus leaves a node.
		//		Usually ignored, _unless_ it *isn't* follwed by touching another node,
		//		which indicates that we tabbed off the last field on the page,
		//		in which case every widget is marked inactive
		dijit._prevFocus = dijit._curFocus;
		dijit._curFocus = null;

		if(dijit._justMouseDowned){
			// the mouse down caused a new widget to be marked as active; this blur event
			// is coming late, so ignore it.
			return;
		}

		// if the blur event isn't followed by a focus event then mark all widgets as inactive.
		if(dijit._clearActiveWidgetsTimer){
			clearTimeout(dijit._clearActiveWidgetsTimer);
		}
		dijit._clearActiveWidgetsTimer = setTimeout(function(){
			delete dijit._clearActiveWidgetsTimer;
			dijit._setStack([]);
			dijit._prevFocus = null;
		}, 100);
	},

	_onTouchNode: function(/*DomNode*/ node, /*String*/ by){
		// summary:
		//		Callback when node is focused or mouse-downed
		// node:
		//		The node that was touched.
		// by:
		//		"mouse" if the focus/touch was caused by a mouse down event

		// ignore the recent blurNode event
		if(dijit._clearActiveWidgetsTimer){
			clearTimeout(dijit._clearActiveWidgetsTimer);
			delete dijit._clearActiveWidgetsTimer;
		}

		// compute stack of active widgets (ex: ComboButton --> Menu --> MenuItem)
		var newStack=[];
		try{
			while(node){
				var popupParent = dojo.attr(node, "dijitPopupParent");
				if(popupParent){
					node=dijit.byId(popupParent).domNode;
				}else if(node.tagName && node.tagName.toLowerCase() == "body"){
					// is this the root of the document or just the root of an iframe?
					if(node === dojo.body()){
						// node is the root of the main document
						break;
					}
					// otherwise, find the iframe this node refers to (can't access it via parentNode,
					// need to do this trick instead). window.frameElement is supported in IE/FF/Webkit
					node=dijit.getDocumentWindow(node.ownerDocument).frameElement;
				}else{
					var id = node.getAttribute && node.getAttribute("widgetId");
					if(id){
						newStack.unshift(id);
					}
					node=node.parentNode;
				}
			}
		}catch(e){ /* squelch */ }

		dijit._setStack(newStack, by);
	},

	_onFocusNode: function(/*DomNode*/ node){
		// summary:
		//		Callback when node is focused

		if(!node){
			return;
		}

		if(node.nodeType == 9){
			// Ignore focus events on the document itself.  This is here so that
			// (for example) clicking the up/down arrows of a spinner
			// (which don't get focus) won't cause that widget to blur. (FF issue)
			return;
		}

		dijit._onTouchNode(node);

		if(node == dijit._curFocus){ return; }
		if(dijit._curFocus){
			dijit._prevFocus = dijit._curFocus;
		}
		dijit._curFocus = node;
		dojo.publish("focusNode", [node]);
	},

	_setStack: function(/*String[]*/ newStack, /*String*/ by){
		// summary:
		//		The stack of active widgets has changed.  Send out appropriate events and records new stack.
		// newStack:
		//		array of widget id's, starting from the top (outermost) widget
		// by:
		//		"mouse" if the focus/touch was caused by a mouse down event

		var oldStack = dijit._activeStack;
		dijit._activeStack = newStack;

		// compare old stack to new stack to see how many elements they have in common
		for(var nCommon=0; nCommon<Math.min(oldStack.length, newStack.length); nCommon++){
			if(oldStack[nCommon] != newStack[nCommon]){
				break;
			}
		}

		var widget;
		// for all elements that have gone out of focus, send blur event
		for(var i=oldStack.length-1; i>=nCommon; i--){
			widget = dijit.byId(oldStack[i]);
			if(widget){
				widget._focused = false;
				widget._hasBeenBlurred = true;
				if(widget._onBlur){
					widget._onBlur(by);
				}
				if(widget._setStateClass){
					widget._setStateClass();
				}
				dojo.publish("widgetBlur", [widget, by]);
			}
		}

		// for all element that have come into focus, send focus event
		for(i=nCommon; i<newStack.length; i++){
			widget = dijit.byId(newStack[i]);
			if(widget){
				widget._focused = true;
				if(widget._onFocus){
					widget._onFocus(by);
				}
				if(widget._setStateClass){
					widget._setStateClass();
				}
				dojo.publish("widgetFocus", [widget, by]);
			}
		}
	}
});

// register top window and all the iframes it contains
dojo.addOnLoad(function(){
	var handle = dijit.registerWin(window);
	if(dojo.isIE){
		dojo.addOnWindowUnload(function(){
			dijit.unregisterWin(handle);
			handle = null;
		})
	}
});

}

if(!dojo._hasResource["dojo.AdapterRegistry"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojo.AdapterRegistry"] = true;
dojo.provide("dojo.AdapterRegistry");

dojo.AdapterRegistry = function(/*Boolean?*/ returnWrappers){
	//	summary:
	//		A registry to make contextual calling/searching easier.
	//	description:
	//		Objects of this class keep list of arrays in the form [name, check,
	//		wrap, directReturn] that are used to determine what the contextual
	//		result of a set of checked arguments is. All check/wrap functions
	//		in this registry should be of the same arity.
	//	example:
	//	|	// create a new registry
	//	|	var reg = new dojo.AdapterRegistry();
	//	|	reg.register("handleString",
	//	|		dojo.isString,
	//	|		function(str){
	//	|			// do something with the string here
	//	|		}
	//	|	);
	//	|	reg.register("handleArr",
	//	|		dojo.isArray,
	//	|		function(arr){
	//	|			// do something with the array here
	//	|		}
	//	|	);
	//	|
	//	|	// now we can pass reg.match() *either* an array or a string and
	//	|	// the value we pass will get handled by the right function
	//	|	reg.match("someValue"); // will call the first function
	//	|	reg.match(["someValue"]); // will call the second

	this.pairs = [];
	this.returnWrappers = returnWrappers || false; // Boolean
}

dojo.extend(dojo.AdapterRegistry, {
	register: function(/*String*/ name, /*Function*/ check, /*Function*/ wrap, /*Boolean?*/ directReturn, /*Boolean?*/ override){
		//	summary: 
		//		register a check function to determine if the wrap function or
		//		object gets selected
		//	name:
		//		a way to identify this matcher.
		//	check:
		//		a function that arguments are passed to from the adapter's
		//		match() function.  The check function should return true if the
		//		given arguments are appropriate for the wrap function.
		//	directReturn:
		//		If directReturn is true, the value passed in for wrap will be
		//		returned instead of being called. Alternately, the
		//		AdapterRegistry can be set globally to "return not call" using
		//		the returnWrappers property. Either way, this behavior allows
		//		the registry to act as a "search" function instead of a
		//		function interception library.
		//	override:
		//		If override is given and true, the check function will be given
		//		highest priority. Otherwise, it will be the lowest priority
		//		adapter.
		this.pairs[((override) ? "unshift" : "push")]([name, check, wrap, directReturn]);
	},

	match: function(/* ... */){
		// summary:
		//		Find an adapter for the given arguments. If no suitable adapter
		//		is found, throws an exception. match() accepts any number of
		//		arguments, all of which are passed to all matching functions
		//		from the registered pairs.
		for(var i = 0; i < this.pairs.length; i++){
			var pair = this.pairs[i];
			if(pair[1].apply(this, arguments)){
				if((pair[3])||(this.returnWrappers)){
					return pair[2];
				}else{
					return pair[2].apply(this, arguments);
				}
			}
		}
		throw new Error("No match found");
	},

	unregister: function(name){
		// summary: Remove a named adapter from the registry

		// FIXME: this is kind of a dumb way to handle this. On a large
		// registry this will be slow-ish and we can use the name as a lookup
		// should we choose to trade memory for speed.
		for(var i = 0; i < this.pairs.length; i++){
			var pair = this.pairs[i];
			if(pair[0] == name){
				this.pairs.splice(i, 1);
				return true;
			}
		}
		return false;
	}
});

}

if(!dojo._hasResource["dijit._base.place"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit._base.place"] = true;
dojo.provide("dijit._base.place");



// ported from dojo.html.util

dijit.getViewport = function(){
	// summary:
	//		Returns the dimensions and scroll position of the viewable area of a browser window

	var scrollRoot = (dojo.doc.compatMode == 'BackCompat')? dojo.body() : dojo.doc.documentElement;

	// get scroll position
	var scroll = dojo._docScroll(); // scrollRoot.scrollTop/Left should work
	return { w: scrollRoot.clientWidth, h: scrollRoot.clientHeight, l: scroll.x, t: scroll.y };
};

/*=====
dijit.__Position = function(){
	// x: Integer
	//		horizontal coordinate in pixels, relative to document body
	// y: Integer
	//		vertical coordinate in pixels, relative to document body

	thix.x = x;
	this.y = y;
}
=====*/


dijit.placeOnScreen = function(
	/* DomNode */			node,
	/* dijit.__Position */	pos,
	/* String[] */			corners,
	/* dijit.__Position? */	padding){
	// summary:
	//		Positions one of the node's corners at specified position
	//		such that node is fully visible in viewport.
	// description:
	//		NOTE: node is assumed to be absolutely or relatively positioned.
	//	pos:
	//		Object like {x: 10, y: 20}
	//	corners:
	//		Array of Strings representing order to try corners in, like ["TR", "BL"].
	//		Possible values are:
	//			* "BL" - bottom left
	//			* "BR" - bottom right
	//			* "TL" - top left
	//			* "TR" - top right
	//	padding:
	//		set padding to put some buffer around the element you want to position.
	// example:
	//		Try to place node's top right corner at (10,20).
	//		If that makes node go (partially) off screen, then try placing
	//		bottom left corner at (10,20).
	//	|	placeOnScreen(node, {x: 10, y: 20}, ["TR", "BL"])

	var choices = dojo.map(corners, function(corner){
		var c = { corner: corner, pos: {x:pos.x,y:pos.y} };
		if(padding){
			c.pos.x += corner.charAt(1) == 'L' ? padding.x : -padding.x;
			c.pos.y += corner.charAt(0) == 'T' ? padding.y : -padding.y;
		}
		return c;
	});

	return dijit._place(node, choices);
}

dijit._place = function(/*DomNode*/ node, /* Array */ choices, /* Function */ layoutNode){
	// summary:
	//		Given a list of spots to put node, put it at the first spot where it fits,
	//		of if it doesn't fit anywhere then the place with the least overflow
	// choices: Array
	//		Array of elements like: {corner: 'TL', pos: {x: 10, y: 20} }
	//		Above example says to put the top-left corner of the node at (10,20)
	// layoutNode: Function(node, aroundNodeCorner, nodeCorner)
	//		for things like tooltip, they are displayed differently (and have different dimensions)
	//		based on their orientation relative to the parent.   This adjusts the popup based on orientation.

	// get {x: 10, y: 10, w: 100, h:100} type obj representing position of
	// viewport over document
	var view = dijit.getViewport();

	// This won't work if the node is inside a <div style="position: relative">,
	// so reattach it to dojo.doc.body.   (Otherwise, the positioning will be wrong
	// and also it might get cutoff)
	if(!node.parentNode || String(node.parentNode.tagName).toLowerCase() != "body"){
		dojo.body().appendChild(node);
	}

	var best = null;
	dojo.some(choices, function(choice){
		var corner = choice.corner;
		var pos = choice.pos;

		// configure node to be displayed in given position relative to button
		// (need to do this in order to get an accurate size for the node, because
		// a tooltips size changes based on position, due to triangle)
		if(layoutNode){
			layoutNode(node, choice.aroundCorner, corner);
		}

		// get node's size
		var style = node.style;
		var oldDisplay = style.display;
		var oldVis = style.visibility;
		style.visibility = "hidden";
		style.display = "";
		var mb = dojo.marginBox(node);
		style.display = oldDisplay;
		style.visibility = oldVis;

		// coordinates and size of node with specified corner placed at pos,
		// and clipped by viewport
		var startX = Math.max(view.l, corner.charAt(1) == 'L' ? pos.x : (pos.x - mb.w)),
			startY = Math.max(view.t, corner.charAt(0) == 'T' ? pos.y : (pos.y - mb.h)),
			endX = Math.min(view.l + view.w, corner.charAt(1) == 'L' ? (startX + mb.w) : pos.x),
			endY = Math.min(view.t + view.h, corner.charAt(0) == 'T' ? (startY + mb.h) : pos.y),
			width = endX - startX,
			height = endY - startY,
			overflow = (mb.w - width) + (mb.h - height);

		if(best == null || overflow < best.overflow){
			best = {
				corner: corner,
				aroundCorner: choice.aroundCorner,
				x: startX,
				y: startY,
				w: width,
				h: height,
				overflow: overflow
			};
		}
		return !overflow;
	});

	node.style.left = best.x + "px";
	node.style.top = best.y + "px";
	if(best.overflow && layoutNode){
		layoutNode(node, best.aroundCorner, best.corner);
	}
	return best;
}

dijit.placeOnScreenAroundNode = function(
	/* DomNode */		node,
	/* DomNode */		aroundNode,
	/* Object */		aroundCorners,
	/* Function? */		layoutNode){

	// summary:
	//		Position node adjacent or kitty-corner to aroundNode
	//		such that it's fully visible in viewport.
	//
	// description:
	//		Place node such that corner of node touches a corner of
	//		aroundNode, and that node is fully visible.
	//
	// aroundCorners:
	//		Ordered list of pairs of corners to try matching up.
	//		Each pair of corners is represented as a key/value in the hash,
	//		where the key corresponds to the aroundNode's corner, and
	//		the value corresponds to the node's corner:
	//
	//	|	{ aroundNodeCorner1: nodeCorner1, aroundNodeCorner2: nodeCorner2, ...}
	//
	//		The following strings are used to represent the four corners:
	//			* "BL" - bottom left
	//			* "BR" - bottom right
	//			* "TL" - top left
	//			* "TR" - top right
	//
	// layoutNode: Function(node, aroundNodeCorner, nodeCorner)
	//		For things like tooltip, they are displayed differently (and have different dimensions)
	//		based on their orientation relative to the parent.   This adjusts the popup based on orientation.
	//
	// example:
	//	|	dijit.placeOnScreenAroundNode(node, aroundNode, {'BL':'TL', 'TR':'BR'});
	//		This will try to position node such that node's top-left corner is at the same position
	//		as the bottom left corner of the aroundNode (ie, put node below
	//		aroundNode, with left edges aligned).  If that fails it will try to put
	// 		the bottom-right corner of node where the top right corner of aroundNode is
	//		(ie, put node above aroundNode, with right edges aligned)
	//

	// get coordinates of aroundNode
	aroundNode = dojo.byId(aroundNode);
	var oldDisplay = aroundNode.style.display;
	aroundNode.style.display="";
	// #3172: use the slightly tighter border box instead of marginBox
	var aroundNodePos = dojo.position(aroundNode, true);
	aroundNode.style.display=oldDisplay;

	// place the node around the calculated rectangle
	return dijit._placeOnScreenAroundRect(node,
		aroundNodePos.x, aroundNodePos.y, aroundNodePos.w, aroundNodePos.h,	// rectangle
		aroundCorners, layoutNode);
};

/*=====
dijit.__Rectangle = function(){
	// x: Integer
	//		horizontal offset in pixels, relative to document body
	// y: Integer
	//		vertical offset in pixels, relative to document body
	// width: Integer
	//		width in pixels
	// height: Integer
	//		height in pixels

	this.x = x;
	this.y = y;
	this.width = width;
	this.height = height;
}
=====*/


dijit.placeOnScreenAroundRectangle = function(
	/* DomNode */			node,
	/* dijit.__Rectangle */	aroundRect,
	/* Object */			aroundCorners,
	/* Function */			layoutNode){

	// summary:
	//		Like dijit.placeOnScreenAroundNode(), except that the "around"
	//		parameter is an arbitrary rectangle on the screen (x, y, width, height)
	//		instead of a dom node.

	return dijit._placeOnScreenAroundRect(node,
		aroundRect.x, aroundRect.y, aroundRect.width, aroundRect.height,	// rectangle
		aroundCorners, layoutNode);
};

dijit._placeOnScreenAroundRect = function(
	/* DomNode */		node,
	/* Number */		x,
	/* Number */		y,
	/* Number */		width,
	/* Number */		height,
	/* Object */		aroundCorners,
	/* Function */		layoutNode){

	// summary:
	//		Like dijit.placeOnScreenAroundNode(), except it accepts coordinates
	//		of a rectangle to place node adjacent to.

	// TODO: combine with placeOnScreenAroundRectangle()

	// Generate list of possible positions for node
	var choices = [];
	for(var nodeCorner in aroundCorners){
		choices.push( {
			aroundCorner: nodeCorner,
			corner: aroundCorners[nodeCorner],
			pos: {
				x: x + (nodeCorner.charAt(1) == 'L' ? 0 : width),
				y: y + (nodeCorner.charAt(0) == 'T' ? 0 : height)
			}
		});
	}

	return dijit._place(node, choices, layoutNode);
};

dijit.placementRegistry= new dojo.AdapterRegistry();
dijit.placementRegistry.register("node",
	function(n, x){
		return typeof x == "object" &&
			typeof x.offsetWidth != "undefined" && typeof x.offsetHeight != "undefined";
	},
	dijit.placeOnScreenAroundNode);
dijit.placementRegistry.register("rect",
	function(n, x){
		return typeof x == "object" &&
			"x" in x && "y" in x && "width" in x && "height" in x;
	},
	dijit.placeOnScreenAroundRectangle);

dijit.placeOnScreenAroundElement = function(
	/* DomNode */		node,
	/* Object */		aroundElement,
	/* Object */		aroundCorners,
	/* Function */		layoutNode){

	// summary:
	//		Like dijit.placeOnScreenAroundNode(), except it accepts an arbitrary object
	//		for the "around" argument and finds a proper processor to place a node.

	return dijit.placementRegistry.match.apply(dijit.placementRegistry, arguments);
};

dijit.getPopupAlignment = function(/*Array*/ position, /*Boolean*/ leftToRight){
	// summary:
	//		Transforms the passed array of preferred positions into a format suitable for passing as the aroundCorners argument to dijit.placeOnScreenAroundElement.
	//
	// position: String[]
	//		This variable controls the position of the drop down.
	//		It's an array of strings with the following values:
	//
	//			* before: places drop down to the left of the target node/widget, or to the right in
	//			  the case of RTL scripts like Hebrew and Arabic
	//			* after: places drop down to the right of the target node/widget, or to the left in
	//			  the case of RTL scripts like Hebrew and Arabic
	//			* above: drop down goes above target node
	//			* below: drop down goes below target node
	//
	//		The list is positions is tried, in order, until a position is found where the drop down fits
	//		within the viewport.
	//
	// leftToRight: Boolean
	//		Whether the popup will be displaying in leftToRight mode.
	//
	var align = {};
	dojo.forEach(position, function(pos){
		switch(pos){
			case "after":
				align[leftToRight ? "BR" : "BL"] = leftToRight ? "BL" : "BR";
				break;
			case "before":
				align[leftToRight ? "BL" : "BR"] = leftToRight ? "BR" : "BL";
				break;
			case "below":
				// first try to align left borders, next try to align right borders (or reverse for RTL mode)
				align[leftToRight ? "BL" : "BR"] = leftToRight ? "TL" : "TR";
				align[leftToRight ? "BR" : "BL"] = leftToRight ? "TR" : "TL";
				break;
			case "above":
			default:
				// first try to align left borders, next try to align right borders (or reverse for RTL mode)
				align[leftToRight ? "TL" : "TR"] = leftToRight ? "BL" : "BR";
				align[leftToRight ? "TR" : "TL"] = leftToRight ? "BR" : "BL";
				break;
		}
	});
	return align;
};
dijit.getPopupAroundAlignment = function(/*Array*/ position, /*Boolean*/ leftToRight){
	// summary:
	//		Transforms the passed array of preferred positions into a format suitable for passing as the aroundCorners argument to dijit.placeOnScreenAroundElement.
	//
	// position: String[]
	//		This variable controls the position of the drop down.
	//		It's an array of strings with the following values:
	//
	//			* before: places drop down to the left of the target node/widget, or to the right in
	//			  the case of RTL scripts like Hebrew and Arabic
	//			* after: places drop down to the right of the target node/widget, or to the left in
	//			  the case of RTL scripts like Hebrew and Arabic
	//			* above: drop down goes above target node
	//			* below: drop down goes below target node
	//
	//		The list is positions is tried, in order, until a position is found where the drop down fits
	//		within the viewport.
	//
	// leftToRight: Boolean
	//		Whether the popup will be displaying in leftToRight mode.
	//
	var align = {};
	dojo.forEach(position, function(pos){
		switch(pos){
			case "after":
				align[leftToRight ? "BR" : "BL"] = leftToRight ? "BL" : "BR";
				break;
			case "before":
				align[leftToRight ? "BL" : "BR"] = leftToRight ? "BR" : "BL";
				break;
			case "below":
				// first try to align left borders, next try to align right borders (or reverse for RTL mode)
				align[leftToRight ? "BL" : "BR"] = leftToRight ? "TL" : "TR";
				align[leftToRight ? "BR" : "BL"] = leftToRight ? "TR" : "TL";
				break;
			case "above":
			default:
				// first try to align left borders, next try to align right borders (or reverse for RTL mode)
				align[leftToRight ? "TL" : "TR"] = leftToRight ? "BL" : "BR";
				align[leftToRight ? "TR" : "TL"] = leftToRight ? "BR" : "BL";
				break;
		}
	});
	return align;
};

}

if(!dojo._hasResource["dijit._base.window"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit._base.window"] = true;
dojo.provide("dijit._base.window");

// TODO: remove this in 2.0, it's not used anymore, or at least not internally

dijit.getDocumentWindow = function(doc){
	// summary:
	// 		Get window object associated with document doc

	// In some IE versions (at least 6.0), document.parentWindow does not return a
	// reference to the real window object (maybe a copy), so we must fix it as well
	// We use IE specific execScript to attach the real window reference to
	// document._parentWindow for later use
	if(dojo.isIE && window !== document.parentWindow && !doc._parentWindow){
		/*
		In IE 6, only the variable "window" can be used to connect events (others
		may be only copies).
		*/
		doc.parentWindow.execScript("document._parentWindow = window;", "Javascript");
		//to prevent memory leak, unset it after use
		//another possibility is to add an onUnload handler which seems overkill to me (liucougar)
		var win = doc._parentWindow;
		doc._parentWindow = null;
		return win;	//	Window
	}

	return doc._parentWindow || doc.parentWindow || doc.defaultView;	//	Window
}

}

if(!dojo._hasResource["dijit._base.popup"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit._base.popup"] = true;
dojo.provide("dijit._base.popup");





dijit.popup = new function(){
	// summary:
	//		This class is used to show/hide widgets as popups.

	var stack = [],
		beginZIndex=1000,
		idGen = 1;

	this.moveOffScreen = function(/*DomNode*/ node){
		// summary:
		//		Moves node offscreen without hiding it (so that all layout widgets included 
		//		in this node can still layout properly)
		//
		// description:
		//		Attaches node to dojo.doc.body, and
		//		positions it off screen, but not display:none, so that
		//		the widget doesn't appear in the page flow and/or cause a blank
		//		area at the bottom of the viewport (making scrollbar longer), but
		//		initialization of contained widgets works correctly

		var s = node.style;
		s.visibility = "hidden";	// so TAB key doesn't navigate to hidden popup
		s.position = "absolute";
		s.top = "-9999px";
		if(s.display == "none"){
			s.display="";
		}
		dojo.body().appendChild(node);
	};

/*=====
dijit.popup.__OpenArgs = function(){
	// popup: Widget
	//		widget to display
	// parent: Widget
	//		the button etc. that is displaying this popup
	// around: DomNode
	//		DOM node (typically a button); place popup relative to this node.  (Specify this *or* "x" and "y" parameters.)
	// x: Integer
	//		Absolute horizontal position (in pixels) to place node at.  (Specify this *or* "around" parameter.)
	// y: Integer
	//		Absolute vertical position (in pixels) to place node at.  (Specity this *or* "around" parameter.)
	// orient: Object || String
	//		When the around parameter is specified, orient should be an
	//		ordered list of tuples of the form (around-node-corner, popup-node-corner).
	//		dijit.popup.open() tries to position the popup according to each tuple in the list, in order,
	//		until the popup appears fully within the viewport.
	//
	//		The default value is {BL:'TL', TL:'BL'}, which represents a list of two tuples:
	//			1. (BL, TL)
	//			2. (TL, BL)
	//		where BL means "bottom left" and "TL" means "top left".
	//		So by default, it first tries putting the popup below the around node, left-aligning them,
	//		and then tries to put it above the around node, still left-aligning them.   Note that the
	//		default is horizontally reversed when in RTL mode.
	//
	//		When an (x,y) position is specified rather than an around node, orient is either
	//		"R" or "L".  R (for right) means that it tries to put the popup to the right of the mouse,
	//		specifically positioning the popup's top-right corner at the mouse position, and if that doesn't
	//		fit in the viewport, then it tries, in order, the bottom-right corner, the top left corner,
	//		and the top-right corner.
	// onCancel: Function
	//		callback when user has canceled the popup by
	//			1. hitting ESC or
	//			2. by using the popup widget's proprietary cancel mechanism (like a cancel button in a dialog);
	//			   i.e. whenever popupWidget.onCancel() is called, args.onCancel is called
	// onClose: Function
	//		callback whenever this popup is closed
	// onExecute: Function
	//		callback when user "executed" on the popup/sub-popup by selecting a menu choice, etc. (top menu only)
	// padding: dijit.__Position
	//		adding a buffer around the opening position. This is only useful when around is not set.
	this.popup = popup;
	this.parent = parent;
	this.around = around;
	this.x = x;
	this.y = y;
	this.orient = orient;
	this.onCancel = onCancel;
	this.onClose = onClose;
	this.onExecute = onExecute;
	this.padding = padding;
}
=====*/

	// Compute the closest ancestor popup that's *not* a child of another popup.
	// Ex: For a TooltipDialog with a button that spawns a tree of menus, find the popup of the button.
	var getTopPopup = function(){
		for(var pi=stack.length-1; pi > 0 && stack[pi].parent === stack[pi-1].widget; pi--){
			/* do nothing, just trying to get right value for pi */
		}
		return stack[pi];
	};

	var wrappers=[];
	this.open = function(/*dijit.popup.__OpenArgs*/ args){
		// summary:
		//		Popup the widget at the specified position
		//
		// example:
		//		opening at the mouse position
		//		|		dijit.popup.open({popup: menuWidget, x: evt.pageX, y: evt.pageY});
		//
		// example:
		//		opening the widget as a dropdown
		//		|		dijit.popup.open({parent: this, popup: menuWidget, around: this.domNode, onClose: function(){...}});
		//
		//		Note that whatever widget called dijit.popup.open() should also listen to its own _onBlur callback
		//		(fired from _base/focus.js) to know that focus has moved somewhere else and thus the popup should be closed.

		var widget = args.popup,
			orient = args.orient || (
				dojo._isBodyLtr() ?
				{'BL':'TL', 'BR':'TR', 'TL':'BL', 'TR':'BR'} :
				{'BR':'TR', 'BL':'TL', 'TR':'BR', 'TL':'BL'}
			),
			around = args.around,
			id = (args.around && args.around.id) ? (args.around.id+"_dropdown") : ("popup_"+idGen++);

		// make wrapper div to hold widget and possibly hold iframe behind it.
		// we can't attach the iframe as a child of the widget.domNode because
		// widget.domNode might be a <table>, <ul>, etc.

		var wrapperobj = wrappers.pop(), wrapper, iframe;
		if(!wrapperobj){
			wrapper = dojo.create("div",{
				"class":"dijitPopup"
			}, dojo.body());
			dijit.setWaiRole(wrapper, "presentation");
		}else{
			// recycled a old wrapper, so that we don't need to reattach the iframe
			// which is slow even if the iframe is empty, see #10167
			wrapper = wrapperobj[0];
			iframe = wrapperobj[1];
		}

		dojo.attr(wrapper,{
			id: id,
			style:{
				zIndex: beginZIndex + stack.length,
				visibility:"hidden",
				// prevent transient scrollbar causing misalign (#5776), and initial flash in upper left (#10111)
				top: "-9999px"
			},
			dijitPopupParent: args.parent ? args.parent.id : ""
		});

		var s = widget.domNode.style;
		s.display = "";
		s.visibility = "";
		s.position = "";
		s.top = "0px";
		wrapper.appendChild(widget.domNode);

		if(!iframe){
			iframe = new dijit.BackgroundIframe(wrapper);
		}else{
			iframe.resize(wrapper)
		}

		// position the wrapper node
		var best = around ?
			dijit.placeOnScreenAroundElement(wrapper, around, orient, widget.orient ? dojo.hitch(widget, "orient") : null) :
			dijit.placeOnScreen(wrapper, args, orient == 'R' ? ['TR','BR','TL','BL'] : ['TL','BL','TR','BR'], args.padding);

		wrapper.style.visibility = "visible";
		// TODO: use effects to fade in wrapper

		var handlers = [];

		// provide default escape and tab key handling
		// (this will work for any widget, not just menu)
		handlers.push(dojo.connect(wrapper, "onkeypress", this, function(evt){
			if(evt.charOrCode == dojo.keys.ESCAPE && args.onCancel){
				dojo.stopEvent(evt);
				args.onCancel();
			}else if(evt.charOrCode === dojo.keys.TAB){
				dojo.stopEvent(evt);
				var topPopup = getTopPopup();
				if(topPopup && topPopup.onCancel){
					topPopup.onCancel();
				}
			}
		}));

		// watch for cancel/execute events on the popup and notify the caller
		// (for a menu, "execute" means clicking an item)
		if(widget.onCancel){
			handlers.push(dojo.connect(widget, "onCancel", args.onCancel));
		}

		handlers.push(dojo.connect(widget, widget.onExecute ? "onExecute" : "onChange", function(){
			var topPopup = getTopPopup();
			if(topPopup && topPopup.onExecute){
				topPopup.onExecute();
			}
		}));

		stack.push({
			wrapper: wrapper,
			iframe: iframe,
			widget: widget,
			parent: args.parent,
			onExecute: args.onExecute,
			onCancel: args.onCancel,
 			onClose: args.onClose,
			handlers: handlers
		});

		if(widget.onOpen){
			// TODO: in 2.0 standardize onShow() (used by StackContainer) and onOpen() (used here)
			widget.onOpen(best);
		}

		return best;
	};

	this.close = function(/*dijit._Widget*/ popup){
		// summary:
		//		Close specified popup and any popups that it parented
		
		// Basically work backwards from the top of the stack closing popups
		// until we hit the specified popup, but IIRC there was some issue where closing
		// a popup would cause others to close too.  Thus if we are trying to close B in [A,B,C]
		// closing C might close B indirectly and then the while() condition will run where stack==[A]...
		// so the while condition is constructed defensively.
		while(dojo.some(stack, function(elem){return elem.widget == popup;})){
			var top = stack.pop(),
				wrapper = top.wrapper,
				iframe = top.iframe,
				widget = top.widget,
				onClose = top.onClose;

			if(widget.onClose){
				// TODO: in 2.0 standardize onHide() (used by StackContainer) and onClose() (used here)
				widget.onClose();
			}
			dojo.forEach(top.handlers, dojo.disconnect);

			// Move the widget offscreen, unless it has already been destroyed in above onClose() etc.
			if(widget && widget.domNode){
				this.moveOffScreen(widget.domNode);
			}
                        
			// recycle the wrapper plus iframe, so we prevent reattaching iframe everytime an popup opens
			// don't use moveOffScreen which would also reattach the wrapper to body, which causes reloading of iframe
			wrapper.style.top = "-9999px";
			wrapper.style.visibility = "hidden";
			wrappers.push([wrapper,iframe]);

			if(onClose){
				onClose();
			}
		}
	};
}();

dijit._frames = new function(){
	// summary:
	//		cache of iframes
	var queue = [];

	this.pop = function(){
		var iframe;
		if(queue.length){
			iframe = queue.pop();
			iframe.style.display="";
		}else{
			if(dojo.isIE){
				var burl = dojo.config["dojoBlankHtmlUrl"] || (dojo.moduleUrl("dojo", "resources/blank.html")+"") || "javascript:\"\"";
				var html="<iframe src='" + burl + "'"
					+ " style='position: absolute; left: 0px; top: 0px;"
					+ "z-index: -1; filter:Alpha(Opacity=\"0\");'>";
				iframe = dojo.doc.createElement(html);
			}else{
			 	iframe = dojo.create("iframe");
				iframe.src = 'javascript:""';
				iframe.className = "dijitBackgroundIframe";
				dojo.style(iframe, "opacity", 0.1);
			}
			iframe.tabIndex = -1; // Magic to prevent iframe from getting focus on tab keypress - as style didnt work.
		}
		return iframe;
	};

	this.push = function(iframe){
		iframe.style.display="none";
		queue.push(iframe);
	}
}();


dijit.BackgroundIframe = function(/* DomNode */node){
	// summary:
	//		For IE/FF z-index schenanigans. id attribute is required.
	//
	// description:
	//		new dijit.BackgroundIframe(node)
	//			Makes a background iframe as a child of node, that fills
	//			area (and position) of node

	if(!node.id){ throw new Error("no id"); }
	if(dojo.isIE || dojo.isMoz){
		var iframe = dijit._frames.pop();
		node.appendChild(iframe);
		if(dojo.isIE<7){
			this.resize(node);
			this._conn = dojo.connect(node, 'onresize', this, function(){
				this.resize(node);
			});
		}else{
			dojo.style(iframe, {
				width: '100%',
				height: '100%'
			});
		}
		this.iframe = iframe;
	}
};

dojo.extend(dijit.BackgroundIframe, {
	resize: function(node){
		// summary:
		// 		resize the iframe so its the same size as node
		// description:
		//		this function is a no-op in all browsers except
		//		IE6, which does not support 100% width/height 
		//		of absolute positioned iframes
		if(this.iframe && dojo.isIE<7){
			dojo.style(this.iframe, {
				width: node.offsetWidth + 'px',
				height: node.offsetHeight + 'px'
			});
		}
	},
	destroy: function(){
		// summary:
		//		destroy the iframe
		if(this._conn){
			dojo.disconnect(this._conn);
			this._conn = null;
		}
		if(this.iframe){
			dijit._frames.push(this.iframe);
			delete this.iframe;
		}
	}
});

}

if(!dojo._hasResource["dijit._base.scroll"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit._base.scroll"] = true;
dojo.provide("dijit._base.scroll");

dijit.scrollIntoView = function(/*DomNode*/ node, /*Object?*/ pos){
	// summary:
	//		Scroll the passed node into view, if it is not already.
	
	// don't rely on that node.scrollIntoView works just because the function is there

	try{ // catch unexpected/unrecreatable errors (#7808) since we can recover using a semi-acceptable native method
	node = dojo.byId(node);
	var doc = node.ownerDocument || dojo.doc,
		body = doc.body || dojo.body(),
		html = doc.documentElement || body.parentNode,
		isIE = dojo.isIE, isWK = dojo.isWebKit;
	// if an untested browser, then use the native method
	if((!(dojo.isMoz || isIE || isWK) || node == body || node == html) && (typeof node.scrollIntoView != "undefined")){
		node.scrollIntoView(false); // short-circuit to native if possible
		return;
	}
	var backCompat = doc.compatMode == 'BackCompat',
		clientAreaRoot = backCompat? body : html,
		scrollRoot = isWK ? body : clientAreaRoot,
		rootWidth = clientAreaRoot.clientWidth,
		rootHeight = clientAreaRoot.clientHeight,
		rtl = !dojo._isBodyLtr(),
		nodePos = pos || dojo.position(node),
		el = node.parentNode,
		isFixed = function(el){
			return ((isIE <= 6 || (isIE && backCompat))? false : (dojo.style(el, 'position').toLowerCase() == "fixed"));
		};
	if(isFixed(node)){ return; } // nothing to do
	while(el){
		if(el == body){ el = scrollRoot; }
		var elPos = dojo.position(el),
			fixedPos = isFixed(el);
		with(elPos){
			if(el == scrollRoot){
				w = rootWidth, h = rootHeight;
				if(scrollRoot == html && isIE && rtl){ x += scrollRoot.offsetWidth-w; } // IE workaround where scrollbar causes negative x
				if(x < 0 || !isIE){ x = 0; } // IE can have values > 0
				if(y < 0 || !isIE){ y = 0; }
			}else{
				var pb = dojo._getPadBorderExtents(el);
				w -= pb.w; h -= pb.h; x += pb.l; y += pb.t;
			}
			with(el){
				if(el != scrollRoot){ // body, html sizes already have the scrollbar removed
					var clientSize = clientWidth,
						scrollBarSize = w - clientSize;
					if(clientSize > 0 && scrollBarSize > 0){
						w = clientSize;
						if(isIE && rtl){ x += scrollBarSize; }
					}
					clientSize = clientHeight;
					scrollBarSize = h - clientSize;
					if(clientSize > 0 && scrollBarSize > 0){
						h = clientSize;
					}
				}
				if(fixedPos){ // bounded by viewport, not parents
					if(y < 0){
						h += y, y = 0;
					}
					if(x < 0){
						w += x, x = 0;
					}
					if(y + h > rootHeight){
						h = rootHeight - y;
					}
					if(x + w > rootWidth){
						w = rootWidth - x;
					}
				}
				// calculate overflow in all 4 directions
				var l = nodePos.x - x, // beyond left: < 0
					t = nodePos.y - Math.max(y, 0), // beyond top: < 0
					r = l + nodePos.w - w, // beyond right: > 0
					bot = t + nodePos.h - h; // beyond bottom: > 0
				if(r * l > 0){
					var s = Math[l < 0? "max" : "min"](l, r);
					nodePos.x += scrollLeft;
					scrollLeft += (isIE >= 8 && !backCompat && rtl)? -s : s;
					nodePos.x -= scrollLeft;
				}
				if(bot * t > 0){
					nodePos.y += scrollTop;
					scrollTop += Math[t < 0? "max" : "min"](t, bot);
					nodePos.y -= scrollTop;
				}
			}
		}
		el = (el != scrollRoot) && !fixedPos && el.parentNode;
	}
	}catch(error){
		console.error('scrollIntoView: ' + error);
		node.scrollIntoView(false);
	}
};

}

if(!dojo._hasResource["dijit._base.sniff"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit._base.sniff"] = true;
// summary:
//		Applies pre-set CSS classes to the top-level HTML node, based on:
// 			- browser (ex: dj_ie)
//			- browser version (ex: dj_ie6)
//			- box model (ex: dj_contentBox)
//			- text direction (ex: dijitRtl)
//
//		In addition, browser, browser version, and box model are
//		combined with an RTL flag when browser text is RTL.  ex: dj_ie-rtl.
//
//		Simply doing a require on this module will
//		establish this CSS.  Modified version of Morris' CSS hack.

dojo.provide("dijit._base.sniff");

(function(){

	var d = dojo,
		html = d.doc.documentElement,
		ie = d.isIE,
		opera = d.isOpera,
		maj = Math.floor,
		ff = d.isFF,
		boxModel = d.boxModel.replace(/-/,''),

		classes = {
			dj_ie: ie,
			dj_ie6: maj(ie) == 6,
			dj_ie7: maj(ie) == 7,
			dj_ie8: maj(ie) == 8,
			dj_iequirks: ie && d.isQuirks,

			// NOTE: Opera not supported by dijit
			dj_opera: opera,

			dj_khtml: d.isKhtml,

			dj_webkit: d.isWebKit,
			dj_safari: d.isSafari,
			dj_chrome: d.isChrome,

			dj_gecko: d.isMozilla,
			dj_ff3: maj(ff) == 3
		}; // no dojo unsupported browsers

	classes["dj_" + boxModel] = true;

	// apply browser, browser version, and box model class names
	for(var p in classes){
		if(classes[p]){
			if(html.className){
				html.className += " " + p;
			}else{
				html.className = p;
			}
		}
	}

	// If RTL mode then add dijitRtl flag plus repeat existing classes
	// with -rtl extension
	// (unshift is to make this code run after <body> node is loaded but before parser runs)
	dojo._loaders.unshift(function(){
		if(!dojo._isBodyLtr()){
			html.className += " dijitRtl";
			for(var p in classes){
				if(classes[p]){
					html.className += " " + p + "-rtl";
				}
			}
		}
	});

})();

}

if(!dojo._hasResource["dijit._base.typematic"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit._base.typematic"] = true;
dojo.provide("dijit._base.typematic");

dijit.typematic = {
	// summary:
	//		These functions are used to repetitively call a user specified callback
	//		method when a specific key or mouse click over a specific DOM node is
	//		held down for a specific amount of time.
	//		Only 1 such event is allowed to occur on the browser page at 1 time.

	_fireEventAndReload: function(){
		this._timer = null;
		this._callback(++this._count, this._node, this._evt);
		
		// Schedule next event, reducing the timer a little bit each iteration, bottoming-out at 10 to avoid
		// browser overload (particularly avoiding starving DOH robot so it never gets to send a mouseup)
		this._currentTimeout = Math.max(
			this._currentTimeout < 0 ? this._initialDelay :
				(this._subsequentDelay > 1 ? this._subsequentDelay : Math.round(this._currentTimeout * this._subsequentDelay)),
			10);
		this._timer = setTimeout(dojo.hitch(this, "_fireEventAndReload"), this._currentTimeout);
	},

	trigger: function(/*Event*/ evt, /* Object */ _this, /*DOMNode*/ node, /* Function */ callback, /* Object */ obj, /* Number */ subsequentDelay, /* Number */ initialDelay){
		// summary:
		//		Start a timed, repeating callback sequence.
		//		If already started, the function call is ignored.
		//		This method is not normally called by the user but can be
		//		when the normal listener code is insufficient.
		// evt:
		//		key or mouse event object to pass to the user callback
		// _this:
		//		pointer to the user's widget space.
		// node:
		//		the DOM node object to pass the the callback function
		// callback:
		//		function to call until the sequence is stopped called with 3 parameters:
		// count:
		//		integer representing number of repeated calls (0..n) with -1 indicating the iteration has stopped
		// node:
		//		the DOM node object passed in
		// evt:
		//		key or mouse event object
		// obj:
		//		user space object used to uniquely identify each typematic sequence
		// subsequentDelay:
		//		if > 1, the number of milliseconds until the 3->n events occur
		//		or else the fractional time multiplier for the next event's delay, default=0.9
		// initialDelay:
		//		the number of milliseconds until the 2nd event occurs, default=500ms
		if(obj != this._obj){
			this.stop();
			this._initialDelay = initialDelay || 500;
			this._subsequentDelay = subsequentDelay || 0.90;
			this._obj = obj;
			this._evt = evt;
			this._node = node;
			this._currentTimeout = -1;
			this._count = -1;
			this._callback = dojo.hitch(_this, callback);
			this._fireEventAndReload();
		}
	},

	stop: function(){
		// summary:
		//		Stop an ongoing timed, repeating callback sequence.
		if(this._timer){
			clearTimeout(this._timer);
			this._timer = null;
		}
		if(this._obj){
			this._callback(-1, this._node, this._evt);
			this._obj = null;
		}
	},

	addKeyListener: function(/*DOMNode*/ node, /*Object*/ keyObject, /*Object*/ _this, /*Function*/ callback, /*Number*/ subsequentDelay, /*Number*/ initialDelay){
		// summary:
		//		Start listening for a specific typematic key.
		//		See also the trigger method for other parameters.
		// keyObject:
		//		an object defining the key to listen for.
		// charOrCode:
		//		the printable character (string) or keyCode (number) to listen for.
		// keyCode:
		//		(deprecated - use charOrCode) the keyCode (number) to listen for (implies charCode = 0).
		// charCode:
		//		(deprecated - use charOrCode) the charCode (number) to listen for.
		// ctrlKey:
		//		desired ctrl key state to initiate the calback sequence:
		//			- pressed (true)
		//			- released (false)
		//			- either (unspecified)
		// altKey:
		//		same as ctrlKey but for the alt key
		// shiftKey:
		//		same as ctrlKey but for the shift key
		// returns:
		//		an array of dojo.connect handles
		if(keyObject.keyCode){
			keyObject.charOrCode = keyObject.keyCode;
			dojo.deprecated("keyCode attribute parameter for dijit.typematic.addKeyListener is deprecated. Use charOrCode instead.", "", "2.0");
		}else if(keyObject.charCode){
			keyObject.charOrCode = String.fromCharCode(keyObject.charCode);
			dojo.deprecated("charCode attribute parameter for dijit.typematic.addKeyListener is deprecated. Use charOrCode instead.", "", "2.0");
		}
		return [
			dojo.connect(node, "onkeypress", this, function(evt){
				if(evt.charOrCode == keyObject.charOrCode &&
				(keyObject.ctrlKey === undefined || keyObject.ctrlKey == evt.ctrlKey) &&
				(keyObject.altKey === undefined || keyObject.altKey == evt.altKey) &&
				(keyObject.metaKey === undefined || keyObject.metaKey == (evt.metaKey || false)) && // IE doesn't even set metaKey
				(keyObject.shiftKey === undefined || keyObject.shiftKey == evt.shiftKey)){
					dojo.stopEvent(evt);
					dijit.typematic.trigger(keyObject, _this, node, callback, keyObject, subsequentDelay, initialDelay);
				}else if(dijit.typematic._obj == keyObject){
					dijit.typematic.stop();
				}
			}),
			dojo.connect(node, "onkeyup", this, function(evt){
				if(dijit.typematic._obj == keyObject){
					dijit.typematic.stop();
				}
			})
		];
	},

	addMouseListener: function(/*DOMNode*/ node, /*Object*/ _this, /*Function*/ callback, /*Number*/ subsequentDelay, /*Number*/ initialDelay){
		// summary:
		//		Start listening for a typematic mouse click.
		//		See the trigger method for other parameters.
		// returns:
		//		an array of dojo.connect handles
		var dc = dojo.connect;
		return [
			dc(node, "mousedown", this, function(evt){
				dojo.stopEvent(evt);
				dijit.typematic.trigger(evt, _this, node, callback, node, subsequentDelay, initialDelay);
			}),
			dc(node, "mouseup", this, function(evt){
				dojo.stopEvent(evt);
				dijit.typematic.stop();
			}),
			dc(node, "mouseout", this, function(evt){
				dojo.stopEvent(evt);
				dijit.typematic.stop();
			}),
			dc(node, "mousemove", this, function(evt){
				dojo.stopEvent(evt);
			}),
			dc(node, "dblclick", this, function(evt){
				dojo.stopEvent(evt);
				if(dojo.isIE){
					dijit.typematic.trigger(evt, _this, node, callback, node, subsequentDelay, initialDelay);
					setTimeout(dojo.hitch(this, dijit.typematic.stop), 50);
				}
			})
		];
	},

	addListener: function(/*Node*/ mouseNode, /*Node*/ keyNode, /*Object*/ keyObject, /*Object*/ _this, /*Function*/ callback, /*Number*/ subsequentDelay, /*Number*/ initialDelay){
		// summary:
		//		Start listening for a specific typematic key and mouseclick.
		//		This is a thin wrapper to addKeyListener and addMouseListener.
		//		See the addMouseListener and addKeyListener methods for other parameters.
		// mouseNode:
		//		the DOM node object to listen on for mouse events.
		// keyNode:
		//		the DOM node object to listen on for key events.
		// returns:
		//		an array of dojo.connect handles
		return this.addKeyListener(keyNode, keyObject, _this, callback, subsequentDelay, initialDelay).concat(
			this.addMouseListener(mouseNode, _this, callback, subsequentDelay, initialDelay));
	}
};

}

if(!dojo._hasResource["dijit._base.wai"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit._base.wai"] = true;
dojo.provide("dijit._base.wai");

dijit.wai = {
	onload: function(){
		// summary:
		//		Detects if we are in high-contrast mode or not

		// This must be a named function and not an anonymous
		// function, so that the widget parsing code can make sure it
		// registers its onload function after this function.
		// DO NOT USE "this" within this function.

		// create div for testing if high contrast mode is on or images are turned off
		var div = dojo.create("div",{
			id: "a11yTestNode",
			style:{
				cssText:'border: 1px solid;'
					+ 'border-color:red green;'
					+ 'position: absolute;'
					+ 'height: 5px;'
					+ 'top: -999px;'
					+ 'background-image: url("' + (dojo.config.blankGif || dojo.moduleUrl("dojo", "resources/blank.gif")) + '");'
			}
		}, dojo.body());

		// test it
		var cs = dojo.getComputedStyle(div);
		if(cs){
			var bkImg = cs.backgroundImage;
			var needsA11y = (cs.borderTopColor == cs.borderRightColor) || (bkImg != null && (bkImg == "none" || bkImg == "url(invalid-url:)" ));
			dojo[needsA11y ? "addClass" : "removeClass"](dojo.body(), "dijit_a11y");
			if(dojo.isIE){
				div.outerHTML = "";		// prevent mixed-content warning, see http://support.microsoft.com/kb/925014
			}else{
				dojo.body().removeChild(div);
			}
		}
	}
};

// Test if computer is in high contrast mode.
// Make sure the a11y test runs first, before widgets are instantiated.
if(dojo.isIE || dojo.isMoz){	// NOTE: checking in Safari messes things up
	dojo._loaders.unshift(dijit.wai.onload);
}

dojo.mixin(dijit, {
	_XhtmlRoles: /banner|contentinfo|definition|main|navigation|search|note|secondary|seealso/,

	hasWaiRole: function(/*Element*/ elem, /*String*/ role){
		// summary:
		//		Determines if an element has a particular non-XHTML role.
		// returns:
		//		True if elem has the specific non-XHTML role attribute and false if not.
		// 		For backwards compatibility if role parameter not provided,
		// 		returns true if has non XHTML role
		var waiRole = this.getWaiRole(elem);
		return role ? (waiRole.indexOf(role) > -1) : (waiRole.length > 0);
	},

	getWaiRole: function(/*Element*/ elem){
		// summary:
		//		Gets the non-XHTML role for an element (which should be a wai role).
		// returns:
		//		The non-XHTML role of elem or an empty string if elem
		//		does not have a role.
		 return dojo.trim((dojo.attr(elem, "role") || "").replace(this._XhtmlRoles,"").replace("wairole:",""));
	},

	setWaiRole: function(/*Element*/ elem, /*String*/ role){
		// summary:
		//		Sets the role on an element.
		// description:
		//		Replace existing role attribute with new role.
		//		If elem already has an XHTML role, append this role to XHTML role
		//		and remove other ARIA roles.

		var curRole = dojo.attr(elem, "role") || "";
		if(!this._XhtmlRoles.test(curRole)){
			dojo.attr(elem, "role", role);
		}else{
			if((" "+ curRole +" ").indexOf(" " + role + " ") < 0){
				var clearXhtml = dojo.trim(curRole.replace(this._XhtmlRoles, ""));
				var cleanRole = dojo.trim(curRole.replace(clearXhtml, ""));
				dojo.attr(elem, "role", cleanRole + (cleanRole ? ' ' : '') + role);
			}
		}
	},

	removeWaiRole: function(/*Element*/ elem, /*String*/ role){
		// summary:
		//		Removes the specified non-XHTML role from an element.
		// 		Removes role attribute if no specific role provided (for backwards compat.)

		var roleValue = dojo.attr(elem, "role");
		if(!roleValue){ return; }
		if(role){
			var t = dojo.trim((" " + roleValue + " ").replace(" " + role + " ", " "));
			dojo.attr(elem, "role", t);
		}else{
			elem.removeAttribute("role");
		}
	},

	hasWaiState: function(/*Element*/ elem, /*String*/ state){
		// summary:
		//		Determines if an element has a given state.
		// description:
		//		Checks for an attribute called "aria-"+state.
		// returns:
		//		true if elem has a value for the given state and
		//		false if it does not.

		return elem.hasAttribute ? elem.hasAttribute("aria-"+state) : !!elem.getAttribute("aria-"+state);
	},

	getWaiState: function(/*Element*/ elem, /*String*/ state){
		// summary:
		//		Gets the value of a state on an element.
		// description:
		//		Checks for an attribute called "aria-"+state.
		// returns:
		//		The value of the requested state on elem
		//		or an empty string if elem has no value for state.

		return elem.getAttribute("aria-"+state) || "";
	},

	setWaiState: function(/*Element*/ elem, /*String*/ state, /*String*/ value){
		// summary:
		//		Sets a state on an element.
		// description:
		//		Sets an attribute called "aria-"+state.

		elem.setAttribute("aria-"+state, value);
	},

	removeWaiState: function(/*Element*/ elem, /*String*/ state){
		// summary:
		//		Removes a state from an element.
		// description:
		//		Sets an attribute called "aria-"+state.

		elem.removeAttribute("aria-"+state);
	}
});

}

if(!dojo._hasResource["dijit._base"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit._base"] = true;
dojo.provide("dijit._base");











}

if(!dojo._hasResource["dijit._Widget"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit._Widget"] = true;
dojo.provide("dijit._Widget");

dojo.require( "dijit._base" );


// This code is to assist deferring dojo.connect() calls in widgets (connecting to events on the widgets'
// DOM nodes) until someone actually needs to monitor that event.
dojo.connect(dojo, "_connect",
	function(/*dijit._Widget*/ widget, /*String*/ event){
		if(widget && dojo.isFunction(widget._onConnect)){
			widget._onConnect(event);
		}
	});

dijit._connectOnUseEventHandler = function(/*Event*/ event){};

// Keep track of where the last keydown event was, to help avoid generating
// spurious ondijitclick events when:
// 1. focus is on a <button> or <a>
// 2. user presses then releases the ENTER key
// 3. onclick handler fires and shifts focus to another node, with an ondijitclick handler
// 4. onkeyup event fires, causing the ondijitclick handler to fire
dijit._lastKeyDownNode = null;
if(dojo.isIE){
	(function(){
		var keydownCallback = function(evt){
			dijit._lastKeyDownNode = evt.srcElement;
		};
		dojo.doc.attachEvent('onkeydown', keydownCallback);
		dojo.addOnWindowUnload(function(){
			dojo.doc.detachEvent('onkeydown', keydownCallback);
		});
	})();
}else{
	dojo.doc.addEventListener('keydown', function(evt){
		dijit._lastKeyDownNode = evt.target;
	}, true);
}

(function(){

var _attrReg = {},	// cached results from getSetterAttributes
	getSetterAttributes = function(widget){
		// summary:
		//		Returns list of attributes with custom setters for specified widget
		var dc = widget.declaredClass;
		if(!_attrReg[dc]){
			var r = [],
				attrs,
				proto = widget.constructor.prototype;
			for(var fxName in proto){
				if(dojo.isFunction(proto[fxName]) && (attrs = fxName.match(/^_set([a-zA-Z]*)Attr$/)) && attrs[1]){
					r.push(attrs[1].charAt(0).toLowerCase() + attrs[1].substr(1));
				}
			}
			_attrReg[dc] = r;
		}
		return _attrReg[dc] || [];	// String[]
	};

dojo.declare("dijit._Widget", null, {
	// summary:
	//		Base class for all Dijit widgets.

	// id: [const] String
	//		A unique, opaque ID string that can be assigned by users or by the
	//		system. If the developer passes an ID which is known not to be
	//		unique, the specified ID is ignored and the system-generated ID is
	//		used instead.
	id: "",

	// lang: [const] String
	//		Rarely used.  Overrides the default Dojo locale used to render this widget,
	//		as defined by the [HTML LANG](http://www.w3.org/TR/html401/struct/dirlang.html#adef-lang) attribute.
	//		Value must be among the list of locales specified during by the Dojo bootstrap,
	//		formatted according to [RFC 3066](http://www.ietf.org/rfc/rfc3066.txt) (like en-us).
	lang: "",

	// dir: [const] String
	//		Unsupported by Dijit, but here for completeness.  Dijit only supports setting text direction on the
	//		entire document.
	//		Bi-directional support, as defined by the [HTML DIR](http://www.w3.org/TR/html401/struct/dirlang.html#adef-dir)
	//		attribute. Either left-to-right "ltr" or right-to-left "rtl".
	dir: "",

	// class: String
	//		HTML class attribute
	"class": "",

	// style: String||Object
	//		HTML style attributes as cssText string or name/value hash
	style: "",

	// title: String
	//		HTML title attribute.
	//
	//		For form widgets this specifies a tooltip to display when hovering over
	//		the widget (just like the native HTML title attribute).
	//
	//		For TitlePane or for when this widget is a child of a TabContainer, AccordionContainer,
	//		etc., it's used to specify the tab label, accordion pane title, etc.
	title: "",

	// tooltip: String
	//		When this widget's title attribute is used to for a tab label, accordion pane title, etc.,
	//		this specifies the tooltip to appear when the mouse is hovered over that text.
	tooltip: "",

	// srcNodeRef: [readonly] DomNode
	//		pointer to original DOM node
	srcNodeRef: null,

	// domNode: [readonly] DomNode
	//		This is our visible representation of the widget! Other DOM
	//		Nodes may by assigned to other properties, usually through the
	//		template system's dojoAttachPoint syntax, but the domNode
	//		property is the canonical "top level" node in widget UI.
	domNode: null,

	// containerNode: [readonly] DomNode
	//		Designates where children of the source DOM node will be placed.
	//		"Children" in this case refers to both DOM nodes and widgets.
	//		For example, for myWidget:
	//
	//		|	<div dojoType=myWidget>
	//		|		<b> here's a plain DOM node
	//		|		<span dojoType=subWidget>and a widget</span>
	//		|		<i> and another plain DOM node </i>
	//		|	</div>
	//
	//		containerNode would point to:
	//
	//		|		<b> here's a plain DOM node
	//		|		<span dojoType=subWidget>and a widget</span>
	//		|		<i> and another plain DOM node </i>
	//
	//		In templated widgets, "containerNode" is set via a
	//		dojoAttachPoint assignment.
	//
	//		containerNode must be defined for any widget that accepts innerHTML
	//		(like ContentPane or BorderContainer or even Button), and conversely
	//		is null for widgets that don't, like TextBox.
	containerNode: null,

/*=====
	// _started: Boolean
	//		startup() has completed.
	_started: false,
=====*/

	// attributeMap: [protected] Object
	//		attributeMap sets up a "binding" between attributes (aka properties)
	//		of the widget and the widget's DOM.
	//		Changes to widget attributes listed in attributeMap will be
	//		reflected into the DOM.
	//
	//		For example, calling attr('title', 'hello')
	//		on a TitlePane will automatically cause the TitlePane's DOM to update
	//		with the new title.
	//
	//		attributeMap is a hash where the key is an attribute of the widget,
	//		and the value reflects a binding to a:
	//
	//		- DOM node attribute
	// |		focus: {node: "focusNode", type: "attribute"}
	// 		Maps this.focus to this.focusNode.focus
	//
	//		- DOM node innerHTML
	//	|		title: { node: "titleNode", type: "innerHTML" }
	//		Maps this.title to this.titleNode.innerHTML
	//
	//		- DOM node innerText
	//	|		title: { node: "titleNode", type: "innerText" }
	//		Maps this.title to this.titleNode.innerText
	//
	//		- DOM node CSS class
	// |		myClass: { node: "domNode", type: "class" }
	//		Maps this.myClass to this.domNode.className
	//
	//		If the value is an array, then each element in the array matches one of the
	//		formats of the above list.
	//
	//		There are also some shorthands for backwards compatibility:
	//		- string --> { node: string, type: "attribute" }, for example:
	//	|	"focusNode" ---> { node: "focusNode", type: "attribute" }
	//		- "" --> { node: "domNode", type: "attribute" }
	attributeMap: {id:"", dir:"", lang:"", "class":"", style:"", title:""},

	// _deferredConnects: [protected] Object
	//		attributeMap addendum for event handlers that should be connected only on first use
	_deferredConnects: {
		onClick: "",
		onDblClick: "",
		onKeyDown: "",
		onKeyPress: "",
		onKeyUp: "",
		onMouseMove: "",
		onMouseDown: "",
		onMouseOut: "",
		onMouseOver: "",
		onMouseLeave: "",
		onMouseEnter: "",
		onMouseUp: ""
	},

	onClick: dijit._connectOnUseEventHandler,
	/*=====
	onClick: function(event){
		// summary:
		//		Connect to this function to receive notifications of mouse click events.
		// event:
		//		mouse Event
		// tags:
		//		callback
	},
	=====*/
	onDblClick: dijit._connectOnUseEventHandler,
	/*=====
	onDblClick: function(event){
		// summary:
		//		Connect to this function to receive notifications of mouse double click events.
		// event:
		//		mouse Event
		// tags:
		//		callback
	},
	=====*/
	onKeyDown: dijit._connectOnUseEventHandler,
	/*=====
	onKeyDown: function(event){
		// summary:
		//		Connect to this function to receive notifications of keys being pressed down.
		// event:
		//		key Event
		// tags:
		//		callback
	},
	=====*/
	onKeyPress: dijit._connectOnUseEventHandler,
	/*=====
	onKeyPress: function(event){
		// summary:
		//		Connect to this function to receive notifications of printable keys being typed.
		// event:
		//		key Event
		// tags:
		//		callback
	},
	=====*/
	onKeyUp: dijit._connectOnUseEventHandler,
	/*=====
	onKeyUp: function(event){
		// summary:
		//		Connect to this function to receive notifications of keys being released.
		// event:
		//		key Event
		// tags:
		//		callback
	},
	=====*/
	onMouseDown: dijit._connectOnUseEventHandler,
	/*=====
	onMouseDown: function(event){
		// summary:
		//		Connect to this function to receive notifications of when the mouse button is pressed down.
		// event:
		//		mouse Event
		// tags:
		//		callback
	},
	=====*/
	onMouseMove: dijit._connectOnUseEventHandler,
	/*=====
	onMouseMove: function(event){
		// summary:
		//		Connect to this function to receive notifications of when the mouse moves over nodes contained within this widget.
		// event:
		//		mouse Event
		// tags:
		//		callback
	},
	=====*/
	onMouseOut: dijit._connectOnUseEventHandler,
	/*=====
	onMouseOut: function(event){
		// summary:
		//		Connect to this function to receive notifications of when the mouse moves off of nodes contained within this widget.
		// event:
		//		mouse Event
		// tags:
		//		callback
	},
	=====*/
	onMouseOver: dijit._connectOnUseEventHandler,
	/*=====
	onMouseOver: function(event){
		// summary:
		//		Connect to this function to receive notifications of when the mouse moves onto nodes contained within this widget.
		// event:
		//		mouse Event
		// tags:
		//		callback
	},
	=====*/
	onMouseLeave: dijit._connectOnUseEventHandler,
	/*=====
	onMouseLeave: function(event){
		// summary:
		//		Connect to this function to receive notifications of when the mouse moves off of this widget.
		// event:
		//		mouse Event
		// tags:
		//		callback
	},
	=====*/
	onMouseEnter: dijit._connectOnUseEventHandler,
	/*=====
	onMouseEnter: function(event){
		// summary:
		//		Connect to this function to receive notifications of when the mouse moves onto this widget.
		// event:
		//		mouse Event
		// tags:
		//		callback
	},
	=====*/
	onMouseUp: dijit._connectOnUseEventHandler,
	/*=====
	onMouseUp: function(event){
		// summary:
		//		Connect to this function to receive notifications of when the mouse button is released.
		// event:
		//		mouse Event
		// tags:
		//		callback
	},
	=====*/

	// Constants used in templates

	// _blankGif: [protected] String
	//		Path to a blank 1x1 image.
	//		Used by <img> nodes in templates that really get their image via CSS background-image.
	_blankGif: (dojo.config.blankGif || dojo.moduleUrl("dojo", "resources/blank.gif")).toString(),

	//////////// INITIALIZATION METHODS ///////////////////////////////////////

	postscript: function(/*Object?*/params, /*DomNode|String*/srcNodeRef){
		// summary:
		//		Kicks off widget instantiation.  See create() for details.
		// tags:
		//		private
		this.create(params, srcNodeRef);
	},

	create: function(/*Object?*/params, /*DomNode|String?*/srcNodeRef){
		// summary:
		//		Kick off the life-cycle of a widget
		// params:
		//		Hash of initialization parameters for widget, including
		//		scalar values (like title, duration etc.) and functions,
		//		typically callbacks like onClick.
		// srcNodeRef:
		//		If a srcNodeRef (DOM node) is specified:
		//			- use srcNodeRef.innerHTML as my contents
		//			- if this is a behavioral widget then apply behavior
		//			  to that srcNodeRef
		//			- otherwise, replace srcNodeRef with my generated DOM
		//			  tree
		// description:
		//		Create calls a number of widget methods (postMixInProperties, buildRendering, postCreate,
		//		etc.), some of which of you'll want to override. See http://docs.dojocampus.org/dijit/_Widget
		//		for a discussion of the widget creation lifecycle.
		//
		//		Of course, adventurous developers could override create entirely, but this should
		//		only be done as a last resort.
		// tags:
		//		private

		// store pointer to original DOM tree
		this.srcNodeRef = dojo.byId(srcNodeRef);

		// For garbage collection.  An array of handles returned by Widget.connect()
		// Each handle returned from Widget.connect() is an array of handles from dojo.connect()
		this._connects = [];

		// For garbage collection.  An array of handles returned by Widget.subscribe()
		// The handle returned from Widget.subscribe() is the handle returned from dojo.subscribe()
		this._subscribes = [];

		// To avoid double-connects, remove entries from _deferredConnects
		// that have been setup manually by a subclass (ex, by dojoAttachEvent).
		// If a subclass has redefined a callback (ex: onClick) then assume it's being
		// connected to manually.
		this._deferredConnects = dojo.clone(this._deferredConnects);
		for(var attr in this.attributeMap){
			delete this._deferredConnects[attr]; // can't be in both attributeMap and _deferredConnects
		}
		for(attr in this._deferredConnects){
			if(this[attr] !== dijit._connectOnUseEventHandler){
				delete this._deferredConnects[attr];	// redefined, probably dojoAttachEvent exists
			}
		}

		//mixin our passed parameters
		if(this.srcNodeRef && (typeof this.srcNodeRef.id == "string")){ this.id = this.srcNodeRef.id; }
		if(params){
			this.params = params;
			dojo.mixin(this,params);
		}
		this.postMixInProperties();

		// generate an id for the widget if one wasn't specified
		// (be sure to do this before buildRendering() because that function might
		// expect the id to be there.)
		if(!this.id){
			this.id = dijit.getUniqueId(this.declaredClass.replace(/\./g,"_"));
		}
		dijit.registry.add(this);

		this.buildRendering();

		if(this.domNode){
			// Copy attributes listed in attributeMap into the [newly created] DOM for the widget.
			this._applyAttributes();

			var source = this.srcNodeRef;
			if(source && source.parentNode){
				source.parentNode.replaceChild(this.domNode, source);
			}

			// If the developer has specified a handler as a widget parameter
			// (ex: new Button({onClick: ...})
			// then naturally need to connect from DOM node to that handler immediately,
			for(attr in this.params){
				this._onConnect(attr);
			}
		}

		if(this.domNode){
			this.domNode.setAttribute("widgetId", this.id);
		}
		this.postCreate();

		// If srcNodeRef has been processed and removed from the DOM (e.g. TemplatedWidget) then delete it to allow GC.
		if(this.srcNodeRef && !this.srcNodeRef.parentNode){
			delete this.srcNodeRef;
		}

		this._created = true;
	},

	_applyAttributes: function(){
		// summary:
		//		Step during widget creation to copy all widget attributes to the
		//		DOM as per attributeMap and _setXXXAttr functions.
		// description:
		//		Skips over blank/false attribute values, unless they were explicitly specified
		//		as parameters to the widget, since those are the default anyway,
		//		and setting tabIndex="" is different than not setting tabIndex at all.
		//
		//		It processes the attributes in the attribute map first, and then
		//		it goes through and processes the attributes for the _setXXXAttr
		//		functions that have been specified
		// tags:
		//		private
		var condAttrApply = function(attr, scope){
			if((scope.params && attr in scope.params) || scope[attr]){
				scope.attr(attr, scope[attr]);
			}
		};

		// Do the attributes in attributeMap
		for(var attr in this.attributeMap){
			condAttrApply(attr, this);
		}

		// And also any attributes with custom setters
		dojo.forEach(getSetterAttributes(this), function(a){
			if(!(a in this.attributeMap)){
				condAttrApply(a, this);
			}
		}, this);
	},

	postMixInProperties: function(){
		// summary:
		//		Called after the parameters to the widget have been read-in,
		//		but before the widget template is instantiated. Especially
		//		useful to set properties that are referenced in the widget
		//		template.
		// tags:
		//		protected
	},

	buildRendering: function(){
		// summary:
		//		Construct the UI for this widget, setting this.domNode
		// description:
		//		Most widgets will mixin `dijit._Templated`, which implements this
		//		method.
		// tags:
		//		protected
		this.domNode = this.srcNodeRef || dojo.create('div');
	},

	postCreate: function(){
		// summary:
		//		Processing after the DOM fragment is created
		// description:
		//		Called after the DOM fragment has been created, but not necessarily
		//		added to the document.  Do not include any operations which rely on
		//		node dimensions or placement.
		// tags:
		//		protected
	},

	startup: function(){
		// summary:
		//		Processing after the DOM fragment is added to the document
		// description:
		//		Called after a widget and its children have been created and added to the page,
		//		and all related widgets have finished their create() cycle, up through postCreate().
		//		This is useful for composite widgets that need to control or layout sub-widgets.
		//		Many layout widgets can use this as a wiring phase.
		this._started = true;
	},

	//////////// DESTROY FUNCTIONS ////////////////////////////////

	destroyRecursive: function(/*Boolean?*/ preserveDom){
		// summary:
		// 		Destroy this widget and its descendants
		// description:
		//		This is the generic "destructor" function that all widget users
		// 		should call to cleanly discard with a widget. Once a widget is
		// 		destroyed, it is removed from the manager object.
		// preserveDom:
		//		If true, this method will leave the original DOM structure
		//		alone of descendant Widgets. Note: This will NOT work with
		//		dijit._Templated widgets.

		this._beingDestroyed = true;
		this.destroyDescendants(preserveDom);
		this.destroy(preserveDom);
	},

	destroy: function(/*Boolean*/ preserveDom){
		// summary:
		// 		Destroy this widget, but not its descendants.
		//		This method will, however, destroy internal widgets such as those used within a template.
		// preserveDom: Boolean
		//		If true, this method will leave the original DOM structure alone.
		//		Note: This will not yet work with _Templated widgets

		this._beingDestroyed = true;
		this.uninitialize();
		var d = dojo,
			dfe = d.forEach,
			dun = d.unsubscribe;
		dfe(this._connects, function(array){
			dfe(array, d.disconnect);
		});
		dfe(this._subscribes, function(handle){
			dun(handle);
		});

		// destroy widgets created as part of template, etc.
		dfe(this._supportingWidgets || [], function(w){
			if(w.destroyRecursive){
				w.destroyRecursive();
			}else if(w.destroy){
				w.destroy();
			}
		});

		this.destroyRendering(preserveDom);
		dijit.registry.remove(this.id);
		this._destroyed = true;
	},

	destroyRendering: function(/*Boolean?*/ preserveDom){
		// summary:
		//		Destroys the DOM nodes associated with this widget
		// preserveDom:
		//		If true, this method will leave the original DOM structure alone
		//		during tear-down. Note: this will not work with _Templated
		//		widgets yet.
		// tags:
		//		protected

		if(this.bgIframe){
			this.bgIframe.destroy(preserveDom);
			delete this.bgIframe;
		}

		if(this.domNode){
			if(preserveDom){
				dojo.removeAttr(this.domNode, "widgetId");
			}else{
				dojo.destroy(this.domNode);
			}
			delete this.domNode;
		}

		if(this.srcNodeRef){
			if(!preserveDom){
				dojo.destroy(this.srcNodeRef);
			}
			delete this.srcNodeRef;
		}
	},

	destroyDescendants: function(/*Boolean?*/ preserveDom){
		// summary:
		//		Recursively destroy the children of this widget and their
		//		descendants.
		// preserveDom:
		//		If true, the preserveDom attribute is passed to all descendant
		//		widget's .destroy() method. Not for use with _Templated
		//		widgets.

		// get all direct descendants and destroy them recursively
		dojo.forEach(this.getChildren(), function(widget){
			if(widget.destroyRecursive){
				widget.destroyRecursive(preserveDom);
			}
		});
	},


	uninitialize: function(){
		// summary:
		//		Stub function. Override to implement custom widget tear-down
		//		behavior.
		// tags:
		//		protected
		return false;
	},

	////////////////// MISCELLANEOUS METHODS ///////////////////

	onFocus: function(){
		// summary:
		//		Called when the widget becomes "active" because
		//		it or a widget inside of it either has focus, or has recently
		//		been clicked.
		// tags:
		//		callback
	},

	onBlur: function(){
		// summary:
		//		Called when the widget stops being "active" because
		//		focus moved to something outside of it, or the user
		//		clicked somewhere outside of it, or the widget was
		//		hidden.
		// tags:
		//		callback
	},

	_onFocus: function(e){
		// summary:
		//		This is where widgets do processing for when they are active,
		//		such as changing CSS classes.  See onFocus() for more details.
		// tags:
		//		protected
		this.onFocus();
	},

	_onBlur: function(){
		// summary:
		//		This is where widgets do processing for when they stop being active,
		//		such as changing CSS classes.  See onBlur() for more details.
		// tags:
		//		protected
		this.onBlur();
	},

	_onConnect: function(/*String*/ event){
		// summary:
		//		Called when someone connects to one of my handlers.
		//		"Turn on" that handler if it isn't active yet.
		//
		//		This is also called for every single initialization parameter
		//		so need to do nothing for parameters like "id".
		// tags:
		//		private
		if(event in this._deferredConnects){
			var mapNode = this[this._deferredConnects[event] || 'domNode'];
			this.connect(mapNode, event.toLowerCase(), event);
			delete this._deferredConnects[event];
		}
	},

	_setClassAttr: function(/*String*/ value){
		// summary:
		//		Custom setter for the CSS "class" attribute
		// tags:
		//		protected
		var mapNode = this[this.attributeMap["class"] || 'domNode'];
		dojo.removeClass(mapNode, this["class"])
		this["class"] = value;
		dojo.addClass(mapNode, value);
	},

	_setStyleAttr: function(/*String||Object*/ value){
		// summary:
		//		Sets the style attribut of the widget according to value,
		//		which is either a hash like {height: "5px", width: "3px"}
		//		or a plain string
		// description:
		//		Determines which node to set the style on based on style setting
		//		in attributeMap.
		// tags:
		//		protected

		var mapNode = this[this.attributeMap.style || 'domNode'];

		// Note: technically we should revert any style setting made in a previous call
		// to his method, but that's difficult to keep track of.

		if(dojo.isObject(value)){
			dojo.style(mapNode, value);
		}else{
			if(mapNode.style.cssText){
				mapNode.style.cssText += "; " + value;
			}else{
				mapNode.style.cssText = value;
			}
		}

		this.style = value;
	},

	setAttribute: function(/*String*/ attr, /*anything*/ value){
		// summary:
		//		Deprecated.  Use attr() instead.
		// tags:
		//		deprecated
		dojo.deprecated(this.declaredClass+"::setAttribute() is deprecated. Use attr() instead.", "", "2.0");
		this.attr(attr, value);
	},

	_attrToDom: function(/*String*/ attr, /*String*/ value){
		// summary:
		//		Reflect a widget attribute (title, tabIndex, duration etc.) to
		//		the widget DOM, as specified in attributeMap.
		//
		// description:
		//		Also sets this["attr"] to the new value.
		//		Note some attributes like "type"
		//		cannot be processed this way as they are not mutable.
		//
		// tags:
		//		private

		var commands = this.attributeMap[attr];
		dojo.forEach(dojo.isArray(commands) ? commands : [commands], function(command){

			// Get target node and what we are doing to that node
			var mapNode = this[command.node || command || "domNode"];	// DOM node
			var type = command.type || "attribute";	// class, innerHTML, innerText, or attribute

			switch(type){
				case "attribute":
					if(dojo.isFunction(value)){ // functions execute in the context of the widget
						value = dojo.hitch(this, value);
					}

					// Get the name of the DOM node attribute; usually it's the same
					// as the name of the attribute in the widget (attr), but can be overridden.
					// Also maps handler names to lowercase, like onSubmit --> onsubmit
					var attrName = command.attribute ? command.attribute :
						(/^on[A-Z][a-zA-Z]*$/.test(attr) ? attr.toLowerCase() : attr);

					dojo.attr(mapNode, attrName, value);
					break;
				case "innerText":
					mapNode.innerHTML = "";
					mapNode.appendChild(dojo.doc.createTextNode(value));
					break;
				case "innerHTML":
					mapNode.innerHTML = value;
					break;
				case "class":
					dojo.removeClass(mapNode, this[attr]);
					dojo.addClass(mapNode, value);
					break;
			}
		}, this);
		this[attr] = value;
	},

	attr: function(/*String|Object*/name, /*Object?*/value){
		// summary:
		//		Set or get properties on a widget instance.
		//	name:
		//		The property to get or set. If an object is passed here and not
		//		a string, its keys are used as names of attributes to be set
		//		and the value of the object as values to set in the widget.
		//	value:
		//		Optional. If provided, attr() operates as a setter. If omitted,
		//		the current value of the named property is returned.
		// description:
		//		Get or set named properties on a widget. If no value is
		//		provided, the current value of the attribute is returned,
		//		potentially via a getter method. If a value is provided, then
		//		the method acts as a setter, assigning the value to the name,
		//		potentially calling any explicitly provided setters to handle
		//		the operation. For instance, if the widget has properties "foo"
		//		and "bar" and a method named "_setFooAttr", calling:
		//	|	myWidget.attr("foo", "Howdy!");
		//		would be equivalent to calling:
		//	|	widget._setFooAttr("Howdy!");
		//		while calling:
		//	|	myWidget.attr("bar", "Howdy!");
		//		would be the same as writing:
		//	|	widget.bar = "Howdy!";
		//		It also tries to copy the changes to the widget's DOM according
		//		to settings in attributeMap (see description of `dijit._Widget.attributeMap`
		//		for details)
		//		For example, calling:
		//	|	myTitlePane.attr("title", "Howdy!");
		//		will do
		//	|	myTitlePane.title = "Howdy!";
		//	|	myTitlePane.title.innerHTML = "Howdy!";
		//		It works for DOM node attributes too.  Calling
		//	|	widget.attr("disabled", true)
		//		will set the disabled attribute on the widget's focusNode,
		//		among other housekeeping for a change in disabled state.

		//	open questions:
		//		- how to handle build shortcut for attributes which want to map
		//		into DOM attributes?
		//		- what relationship should setAttribute()/attr() have to
		//		layout() calls?
		var args = arguments.length;
		if(args == 1 && !dojo.isString(name)){
			for(var x in name){ this.attr(x, name[x]); }
			return this;
		}
		var names = this._getAttrNames(name);
		if(args >= 2){ // setter
			if(this[names.s]){
				// use the explicit setter
				args = dojo._toArray(arguments, 1);
				return this[names.s].apply(this, args) || this;
			}else{
				// if param is specified as DOM node attribute, copy it
				if(name in this.attributeMap){
					this._attrToDom(name, value);
				}

				// FIXME: what about function assignments? Any way to connect() here?
				this[name] = value;
			}
			return this;
		}else{ // getter
			return this[names.g] ? this[names.g]() : this[name];
		}
	},

	_attrPairNames: {},		// shared between all widgets
	_getAttrNames: function(name){
		// summary:
		//		Helper function for Widget.attr().
		//		Caches attribute name values so we don't do the string ops every time.
		// tags:
		//		private

		var apn = this._attrPairNames;
		if(apn[name]){ return apn[name]; }
		var uc = name.charAt(0).toUpperCase() + name.substr(1);
		return (apn[name] = {
			n: name+"Node",
			s: "_set"+uc+"Attr",
			g: "_get"+uc+"Attr"
		});
	},

	toString: function(){
		// summary:
		//		Returns a string that represents the widget
		// description:
		//		When a widget is cast to a string, this method will be used to generate the
		//		output. Currently, it does not implement any sort of reversible
		//		serialization.
		return '[Widget ' + this.declaredClass + ', ' + (this.id || 'NO ID') + ']'; // String
	},

	getDescendants: function(){
		// summary:
		//		Returns all the widgets contained by this, i.e., all widgets underneath this.containerNode.
		//		This method should generally be avoided as it returns widgets declared in templates, which are
		//		supposed to be internal/hidden, but it's left here for back-compat reasons.

		return this.containerNode ? dojo.query('[widgetId]', this.containerNode).map(dijit.byNode) : []; // dijit._Widget[]
	},

	getChildren: function(){
		// summary:
		//		Returns all the widgets contained by this, i.e., all widgets underneath this.containerNode.
		//		Does not return nested widgets, nor widgets that are part of this widget's template.
		return this.containerNode ? dijit.findWidgets(this.containerNode) : []; // dijit._Widget[]
	},

	// nodesWithKeyClick: [private] String[]
	//		List of nodes that correctly handle click events via native browser support,
	//		and don't need dijit's help
	nodesWithKeyClick: ["input", "button"],

	connect: function(
			/*Object|null*/ obj,
			/*String|Function*/ event,
			/*String|Function*/ method){
		// summary:
		//		Connects specified obj/event to specified method of this object
		//		and registers for disconnect() on widget destroy.
		// description:
		//		Provide widget-specific analog to dojo.connect, except with the
		//		implicit use of this widget as the target object.
		//		This version of connect also provides a special "ondijitclick"
		//		event which triggers on a click or space or enter keyup
		// returns:
		//		A handle that can be passed to `disconnect` in order to disconnect before
		//		the widget is destroyed.
		// example:
		//	|	var btn = new dijit.form.Button();
		//	|	// when foo.bar() is called, call the listener we're going to
		//	|	// provide in the scope of btn
		//	|	btn.connect(foo, "bar", function(){
		//	|		console.debug(this.toString());
		//	|	});
		// tags:
		//		protected

		var d = dojo,
			dc = d._connect,
			handles = [];
		if(event == "ondijitclick"){
			// add key based click activation for unsupported nodes.
			// do all processing onkey up to prevent spurious clicks
			// for details see comments at top of this file where _lastKeyDownNode is defined
			if(!this.nodesWithKeyClick[obj.tagName.toLowerCase()]){
				var m = d.hitch(this, method);
				handles.push(
					dc(obj, "onkeydown", this, function(e){
						//console.log(this.id + ": onkeydown, e.target = ", e.target, ", lastKeyDownNode was ", dijit._lastKeyDownNode, ", equality is ", (e.target === dijit._lastKeyDownNode));
						if((e.keyCode == d.keys.ENTER || e.keyCode == d.keys.SPACE) &&
							!e.ctrlKey && !e.shiftKey && !e.altKey && !e.metaKey){
							// needed on IE for when focus changes between keydown and keyup - otherwise dropdown menus do not work
							dijit._lastKeyDownNode = e.target;
							d.stopEvent(e);		// stop event to prevent scrolling on space key in IE
						}
			 		}),
					dc(obj, "onkeyup", this, function(e){
						//console.log(this.id + ": onkeyup, e.target = ", e.target, ", lastKeyDownNode was ", dijit._lastKeyDownNode, ", equality is ", (e.target === dijit._lastKeyDownNode));
						if( (e.keyCode == d.keys.ENTER || e.keyCode == d.keys.SPACE) &&
							e.target === dijit._lastKeyDownNode &&
							!e.ctrlKey && !e.shiftKey && !e.altKey && !e.metaKey){
								//need reset here or have problems in FF when focus returns to trigger element after closing popup/alert
								dijit._lastKeyDownNode = null;
								return m(e);
						}
					})
				);
			}
			event = "onclick";
		}
		handles.push(dc(obj, event, this, method));

		this._connects.push(handles);
		return handles;		// _Widget.Handle
	},

	disconnect: function(/* _Widget.Handle */ handles){
		// summary:
		//		Disconnects handle created by `connect`.
		//		Also removes handle from this widget's list of connects.
		// tags:
		//		protected
		for(var i=0; i<this._connects.length; i++){
			if(this._connects[i] == handles){
				dojo.forEach(handles, dojo.disconnect);
				this._connects.splice(i, 1);
				return;
			}
		}
	},

	subscribe: function(
			/*String*/ topic,
			/*String|Function*/ method){
		// summary:
		//		Subscribes to the specified topic and calls the specified method
		//		of this object and registers for unsubscribe() on widget destroy.
		// description:
		//		Provide widget-specific analog to dojo.subscribe, except with the
		//		implicit use of this widget as the target object.
		// example:
		//	|	var btn = new dijit.form.Button();
		//	|	// when /my/topic is published, this button changes its label to
		//	|   // be the parameter of the topic.
		//	|	btn.subscribe("/my/topic", function(v){
		//	|		this.attr("label", v);
		//	|	});
		var d = dojo,
			handle = d.subscribe(topic, this, method);

		// return handles for Any widget that may need them
		this._subscribes.push(handle);
		return handle;
	},

	unsubscribe: function(/*Object*/ handle){
		// summary:
		//		Unsubscribes handle created by this.subscribe.
		//		Also removes handle from this widget's list of subscriptions
		for(var i=0; i<this._subscribes.length; i++){
			if(this._subscribes[i] == handle){
				dojo.unsubscribe(handle);
				this._subscribes.splice(i, 1);
				return;
			}
		}
	},

	isLeftToRight: function(){
		// summary:
		//		Checks the page for text direction
		// tags:
		//		protected
		return dojo._isBodyLtr(); //Boolean
	},

	isFocusable: function(){
		// summary:
		//		Return true if this widget can currently be focused
		//		and false if not
		return this.focus && (dojo.style(this.domNode, "display") != "none");
	},

	placeAt: function(/* String|DomNode|_Widget */reference, /* String?|Int? */position){
		// summary:
		//		Place this widget's domNode reference somewhere in the DOM based
		//		on standard dojo.place conventions, or passing a Widget reference that
		//		contains and addChild member.
		//
		// description:
		//		A convenience function provided in all _Widgets, providing a simple
		//		shorthand mechanism to put an existing (or newly created) Widget
		//		somewhere in the dom, and allow chaining.
		//
		// reference:
		//		The String id of a domNode, a domNode reference, or a reference to a Widget posessing
		//		an addChild method.
		//
		// position:
		//		If passed a string or domNode reference, the position argument
		//		accepts a string just as dojo.place does, one of: "first", "last",
		//		"before", or "after".
		//
		//		If passed a _Widget reference, and that widget reference has an ".addChild" method,
		//		it will be called passing this widget instance into that method, supplying the optional
		//		position index passed.
		//
		// returns:
		//		dijit._Widget
		//		Provides a useful return of the newly created dijit._Widget instance so you
		//		can "chain" this function by instantiating, placing, then saving the return value
		//		to a variable.
		//
		// example:
		// | 	// create a Button with no srcNodeRef, and place it in the body:
		// | 	var button = new dijit.form.Button({ label:"click" }).placeAt(dojo.body());
		// | 	// now, 'button' is still the widget reference to the newly created button
		// | 	dojo.connect(button, "onClick", function(e){ console.log('click'); });
		//
		// example:
		// |	// create a button out of a node with id="src" and append it to id="wrapper":
		// | 	var button = new dijit.form.Button({},"src").placeAt("wrapper");
		//
		// example:
		// |	// place a new button as the first element of some div
		// |	var button = new dijit.form.Button({ label:"click" }).placeAt("wrapper","first");
		//
		// example:
		// |	// create a contentpane and add it to a TabContainer
		// |	var tc = dijit.byId("myTabs");
		// |	new dijit.layout.ContentPane({ href:"foo.html", title:"Wow!" }).placeAt(tc)

		if(reference.declaredClass && reference.addChild){
			reference.addChild(this, position);
		}else{
			dojo.place(this.domNode, reference, position);
		}
		return this;
	},

	_onShow: function(){
		// summary:
		//		Internal method called when this widget is made visible.
		//		See `onShow` for details.
		this.onShow();
	},

	onShow: function(){
		// summary:
		//		Called when this widget becomes the selected pane in a
		//		`dijit.layout.TabContainer`, `dijit.layout.StackContainer`,
		//		`dijit.layout.AccordionContainer`, etc.
		//
		//		Also called to indicate display of a `dijit.Dialog`, `dijit.TooltipDialog`, or `dijit.TitlePane`.
		// tags:
		//		callback
	},

	onHide: function(){
		// summary:
			//		Called when another widget becomes the selected pane in a
			//		`dijit.layout.TabContainer`, `dijit.layout.StackContainer`,
			//		`dijit.layout.AccordionContainer`, etc.
			//
			//		Also called to indicate hide of a `dijit.Dialog`, `dijit.TooltipDialog`, or `dijit.TitlePane`.
			// tags:
			//		callback
	}
});

})();

}

if(!dojo._hasResource["dojo.string"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojo.string"] = true;
dojo.provide("dojo.string");

/*=====
dojo.string = { 
	// summary: String utilities for Dojo
};
=====*/

dojo.string.rep = function(/*String*/str, /*Integer*/num){
	//	summary:
	//		Efficiently replicate a string `n` times.
	//	str:
	//		the string to replicate
	//	num:
	//		number of times to replicate the string
	
	if(num <= 0 || !str){ return ""; }
	
	var buf = [];
	for(;;){
		if(num & 1){
			buf.push(str);
		}
		if(!(num >>= 1)){ break; }
		str += str;
	}
	return buf.join("");	// String
};

dojo.string.pad = function(/*String*/text, /*Integer*/size, /*String?*/ch, /*Boolean?*/end){
	//	summary:
	//		Pad a string to guarantee that it is at least `size` length by
	//		filling with the character `ch` at either the start or end of the
	//		string. Pads at the start, by default.
	//	text:
	//		the string to pad
	//	size:
	//		length to provide padding
	//	ch:
	//		character to pad, defaults to '0'
	//	end:
	//		adds padding at the end if true, otherwise pads at start
	//	example:
	//	|	// Fill the string to length 10 with "+" characters on the right.  Yields "Dojo++++++".
	//	|	dojo.string.pad("Dojo", 10, "+", true);

	if(!ch){
		ch = '0';
	}
	var out = String(text),
		pad = dojo.string.rep(ch, Math.ceil((size - out.length) / ch.length));
	return end ? out + pad : pad + out;	// String
};

dojo.string.substitute = function(	/*String*/		template, 
									/*Object|Array*/map, 
									/*Function?*/	transform, 
									/*Object?*/		thisObject){
	//	summary:
	//		Performs parameterized substitutions on a string. Throws an
	//		exception if any parameter is unmatched.
	//	template: 
	//		a string with expressions in the form `${key}` to be replaced or
	//		`${key:format}` which specifies a format function. keys are case-sensitive. 
	//	map:
	//		hash to search for substitutions
	//	transform: 
	//		a function to process all parameters before substitution takes
	//		place, e.g. mylib.encodeXML
	//	thisObject: 
	//		where to look for optional format function; default to the global
	//		namespace
	//	example:
	//		Substitutes two expressions in a string from an Array or Object
	//	|	// returns "File 'foo.html' is not found in directory '/temp'."
	//	|	// by providing substitution data in an Array
	//	|	dojo.string.substitute(
	//	|		"File '${0}' is not found in directory '${1}'.",
	//	|		["foo.html","/temp"]
	//	|	);
	//	|
	//	|	// also returns "File 'foo.html' is not found in directory '/temp'."
	//	|	// but provides substitution data in an Object structure.  Dotted
	//	|	// notation may be used to traverse the structure.
	//	|	dojo.string.substitute(
	//	|		"File '${name}' is not found in directory '${info.dir}'.",
	//	|		{ name: "foo.html", info: { dir: "/temp" } }
	//	|	);
	//	example:
	//		Use a transform function to modify the values:
	//	|	// returns "file 'foo.html' is not found in directory '/temp'."
	//	|	dojo.string.substitute(
	//	|		"${0} is not found in ${1}.",
	//	|		["foo.html","/temp"],
	//	|		function(str){
	//	|			// try to figure out the type
	//	|			var prefix = (str.charAt(0) == "/") ? "directory": "file";
	//	|			return prefix + " '" + str + "'";
	//	|		}
	//	|	);
	//	example:
	//		Use a formatter
	//	|	// returns "thinger -- howdy"
	//	|	dojo.string.substitute(
	//	|		"${0:postfix}", ["thinger"], null, {
	//	|			postfix: function(value, key){
	//	|				return value + " -- howdy";
	//	|			}
	//	|		}
	//	|	);

	thisObject = thisObject || dojo.global;
	transform = transform ? 
		dojo.hitch(thisObject, transform) : function(v){ return v; };

	return template.replace(/\$\{([^\s\:\}]+)(?:\:([^\s\:\}]+))?\}/g,
		function(match, key, format){
			var value = dojo.getObject(key, false, map);
			if(format){
				value = dojo.getObject(format, false, thisObject).call(thisObject, value, key);
			}
			return transform(value, key).toString();
		}); // String
};

/*=====
dojo.string.trim = function(str){
	//	summary:
	//		Trims whitespace from both sides of the string
	//	str: String
	//		String to be trimmed
	//	returns: String
	//		Returns the trimmed string
	//	description:
	//		This version of trim() was taken from [Steven Levithan's blog](http://blog.stevenlevithan.com/archives/faster-trim-javascript).
	//		The short yet performant version of this function is dojo.trim(),
	//		which is part of Dojo base.  Uses String.prototype.trim instead, if available.
	return "";	// String
}
=====*/

dojo.string.trim = String.prototype.trim ?
	dojo.trim : // aliasing to the native function
	function(str){
		str = str.replace(/^\s+/, '');
		for(var i = str.length - 1; i >= 0; i--){
			if(/\S/.test(str.charAt(i))){
				str = str.substring(0, i + 1);
				break;
			}
		}
		return str;
	};

}

if(!dojo._hasResource["dojo.date.stamp"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojo.date.stamp"] = true;
dojo.provide("dojo.date.stamp");

// Methods to convert dates to or from a wire (string) format using well-known conventions

dojo.date.stamp.fromISOString = function(/*String*/formattedString, /*Number?*/defaultTime){
	//	summary:
	//		Returns a Date object given a string formatted according to a subset of the ISO-8601 standard.
	//
	//	description:
	//		Accepts a string formatted according to a profile of ISO8601 as defined by
	//		[RFC3339](http://www.ietf.org/rfc/rfc3339.txt), except that partial input is allowed.
	//		Can also process dates as specified [by the W3C](http://www.w3.org/TR/NOTE-datetime)
	//		The following combinations are valid:
	//
	//			* dates only
	//			|	* yyyy
	//			|	* yyyy-MM
	//			|	* yyyy-MM-dd
	// 			* times only, with an optional time zone appended
	//			|	* THH:mm
	//			|	* THH:mm:ss
	//			|	* THH:mm:ss.SSS
	// 			* and "datetimes" which could be any combination of the above
	//
	//		timezones may be specified as Z (for UTC) or +/- followed by a time expression HH:mm
	//		Assumes the local time zone if not specified.  Does not validate.  Improperly formatted
	//		input may return null.  Arguments which are out of bounds will be handled
	// 		by the Date constructor (e.g. January 32nd typically gets resolved to February 1st)
	//		Only years between 100 and 9999 are supported.
	//
  	//	formattedString:
	//		A string such as 2005-06-30T08:05:00-07:00 or 2005-06-30 or T08:05:00
	//
	//	defaultTime:
	//		Used for defaults for fields omitted in the formattedString.
	//		Uses 1970-01-01T00:00:00.0Z by default.

	if(!dojo.date.stamp._isoRegExp){
		dojo.date.stamp._isoRegExp =
//TODO: could be more restrictive and check for 00-59, etc.
			/^(?:(\d{4})(?:-(\d{2})(?:-(\d{2}))?)?)?(?:T(\d{2}):(\d{2})(?::(\d{2})(.\d+)?)?((?:[+-](\d{2}):(\d{2}))|Z)?)?$/;
	}

	var match = dojo.date.stamp._isoRegExp.exec(formattedString),
		result = null;

	if(match){
		match.shift();
		if(match[1]){match[1]--;} // Javascript Date months are 0-based
		if(match[6]){match[6] *= 1000;} // Javascript Date expects fractional seconds as milliseconds

		if(defaultTime){
			// mix in defaultTime.  Relatively expensive, so use || operators for the fast path of defaultTime === 0
			defaultTime = new Date(defaultTime);
			dojo.map(["FullYear", "Month", "Date", "Hours", "Minutes", "Seconds", "Milliseconds"], function(prop){
				return defaultTime["get" + prop]();
			}).forEach(function(value, index){
				if(match[index] === undefined){
					match[index] = value;
				}
			});
		}
		result = new Date(match[0]||1970, match[1]||0, match[2]||1, match[3]||0, match[4]||0, match[5]||0, match[6]||0); //TODO: UTC defaults
		if(match[0] < 100){
			result.setFullYear(match[0] || 1970);
		}

		var offset = 0,
			zoneSign = match[7] && match[7].charAt(0);
		if(zoneSign != 'Z'){
			offset = ((match[8] || 0) * 60) + (Number(match[9]) || 0);
			if(zoneSign != '-'){ offset *= -1; }
		}
		if(zoneSign){
			offset -= result.getTimezoneOffset();
		}
		if(offset){
			result.setTime(result.getTime() + offset * 60000);
		}
	}

	return result; // Date or null
}

/*=====
	dojo.date.stamp.__Options = function(){
		//	selector: String
		//		"date" or "time" for partial formatting of the Date object.
		//		Both date and time will be formatted by default.
		//	zulu: Boolean
		//		if true, UTC/GMT is used for a timezone
		//	milliseconds: Boolean
		//		if true, output milliseconds
		this.selector = selector;
		this.zulu = zulu;
		this.milliseconds = milliseconds;
	}
=====*/

dojo.date.stamp.toISOString = function(/*Date*/dateObject, /*dojo.date.stamp.__Options?*/options){
	//	summary:
	//		Format a Date object as a string according a subset of the ISO-8601 standard
	//
	//	description:
	//		When options.selector is omitted, output follows [RFC3339](http://www.ietf.org/rfc/rfc3339.txt)
	//		The local time zone is included as an offset from GMT, except when selector=='time' (time without a date)
	//		Does not check bounds.  Only years between 100 and 9999 are supported.
	//
	//	dateObject:
	//		A Date object

	var _ = function(n){ return (n < 10) ? "0" + n : n; };
	options = options || {};
	var formattedDate = [],
		getter = options.zulu ? "getUTC" : "get",
		date = "";
	if(options.selector != "time"){
		var year = dateObject[getter+"FullYear"]();
		date = ["0000".substr((year+"").length)+year, _(dateObject[getter+"Month"]()+1), _(dateObject[getter+"Date"]())].join('-');
	}
	formattedDate.push(date);
	if(options.selector != "date"){
		var time = [_(dateObject[getter+"Hours"]()), _(dateObject[getter+"Minutes"]()), _(dateObject[getter+"Seconds"]())].join(':');
		var millis = dateObject[getter+"Milliseconds"]();
		if(options.milliseconds){
			time += "."+ (millis < 100 ? "0" : "") + _(millis);
		}
		if(options.zulu){
			time += "Z";
		}else if(options.selector != "time"){
			var timezoneOffset = dateObject.getTimezoneOffset();
			var absOffset = Math.abs(timezoneOffset);
			time += (timezoneOffset > 0 ? "-" : "+") + 
				_(Math.floor(absOffset/60)) + ":" + _(absOffset%60);
		}
		formattedDate.push(time);
	}
	return formattedDate.join('T'); // String
}

}

if(!dojo._hasResource["dojo.parser"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojo.parser"] = true;
dojo.provide("dojo.parser");


dojo.parser = new function(){
	// summary: The Dom/Widget parsing package

	var d = dojo;
	this._attrName = d._scopeName + "Type";
	this._query = "[" + this._attrName + "]";

	function val2type(/*Object*/ value){
		// summary:
		//		Returns name of type of given value.

		if(d.isString(value)){ return "string"; }
		if(typeof value == "number"){ return "number"; }
		if(typeof value == "boolean"){ return "boolean"; }
		if(d.isFunction(value)){ return "function"; }
		if(d.isArray(value)){ return "array"; } // typeof [] == "object"
		if(value instanceof Date) { return "date"; } // assume timestamp
		if(value instanceof d._Url){ return "url"; }
		return "object";
	}

	function str2obj(/*String*/ value, /*String*/ type){
		// summary:
		//		Convert given string value to given type
		switch(type){
			case "string":
				return value;
			case "number":
				return value.length ? Number(value) : NaN;
			case "boolean":
				// for checked/disabled value might be "" or "checked".  interpret as true.
				return typeof value == "boolean" ? value : !(value.toLowerCase()=="false");
			case "function":
				if(d.isFunction(value)){
					// IE gives us a function, even when we say something like onClick="foo"
					// (in which case it gives us an invalid function "function(){ foo }"). 
					//  Therefore, convert to string
					value=value.toString();
					value=d.trim(value.substring(value.indexOf('{')+1, value.length-1));
				}
				try{
					if(value.search(/[^\w\.]+/i) != -1){
						// The user has specified some text for a function like "return x+5"
						return new Function(value);
					}else{
						// The user has specified the name of a function like "myOnClick"
						return d.getObject(value, false);
					}
				}catch(e){ return new Function(); }
			case "array":
				return value ? value.split(/\s*,\s*/) : [];
			case "date":
				switch(value){
					case "": return new Date("");	// the NaN of dates
					case "now": return new Date();	// current date
					default: return d.date.stamp.fromISOString(value);
				}
			case "url":
				return d.baseUrl + value;
			default:
				return d.fromJson(value);
		}
	}

	var instanceClasses = {
		// map from fully qualified name (like "dijit.Button") to structure like
		// { cls: dijit.Button, params: {label: "string", disabled: "boolean"} }
	};

	// Widgets like BorderContainer add properties to _Widget via dojo.extend().
	// If BorderContainer is loaded after _Widget's parameter list has been cached,
	// we need to refresh that parameter list (for _Widget and all widgets that extend _Widget).
	dojo.connect(dojo, "extend", function(){
		instanceClasses = {};
	});

	function getClassInfo(/*String*/ className){
		// className:
		//		fully qualified name (like "dijit.form.Button")
		// returns:
		//		structure like
		//			{ 
		//				cls: dijit.Button, 
		//				params: { label: "string", disabled: "boolean"}
		//			}

		if(!instanceClasses[className]){
			// get pointer to widget class
			var cls = d.getObject(className);
			if(!d.isFunction(cls)){
				throw new Error("Could not load class '" + className +
					"'. Did you spell the name correctly and use a full path, like 'dijit.form.Button'?");
			}
			var proto = cls.prototype;
	
			// get table of parameter names & types
			var params = {}, dummyClass = {};
			for(var name in proto){
				if(name.charAt(0)=="_"){ continue; } 	// skip internal properties
				if(name in dummyClass){ continue; }		// skip "constructor" and "toString"
				var defVal = proto[name];
				params[name]=val2type(defVal);
			}

			instanceClasses[className] = { cls: cls, params: params };
		}
		return instanceClasses[className];
	}

	this._functionFromScript = function(script){
		var preamble = "";
		var suffix = "";
		var argsStr = script.getAttribute("args");
		if(argsStr){
			d.forEach(argsStr.split(/\s*,\s*/), function(part, idx){
				preamble += "var "+part+" = arguments["+idx+"]; ";
			});
		}
		var withStr = script.getAttribute("with");
		if(withStr && withStr.length){
			d.forEach(withStr.split(/\s*,\s*/), function(part){
				preamble += "with("+part+"){";
				suffix += "}";
			});
		}
		return new Function(preamble+script.innerHTML+suffix);
	}

	this.instantiate = function(/* Array */nodes, /* Object? */mixin, /* Object? */args){
		// summary:
		//		Takes array of nodes, and turns them into class instances and
		//		potentially calls a layout method to allow them to connect with
		//		any children		
		// mixin: Object?
		//		An object that will be mixed in with each node in the array.
		//		Values in the mixin will override values in the node, if they
		//		exist.
		// args: Object?
		//		An object used to hold kwArgs for instantiation.
		//		Only supports 'noStart' currently.
		var thelist = [], dp = dojo.parser;
		mixin = mixin||{};
		args = args||{};
		
		d.forEach(nodes, function(node){
			if(!node){ return; }
			var type = dp._attrName in mixin?mixin[dp._attrName]:node.getAttribute(dp._attrName);
			if(!type || !type.length){ return; }
			var clsInfo = getClassInfo(type),
				clazz = clsInfo.cls,
				ps = clazz._noScript || clazz.prototype._noScript;

			// read parameters (ie, attributes).
			// clsInfo.params lists expected params like {"checked": "boolean", "n": "number"}
			var params = {},
				attributes = node.attributes;
			for(var name in clsInfo.params){
				var item = name in mixin?{value:mixin[name],specified:true}:attributes.getNamedItem(name);
				if(!item || (!item.specified && (!dojo.isIE || name.toLowerCase()!="value"))){ continue; }
				var value = item.value;
				// Deal with IE quirks for 'class' and 'style'
				switch(name){
				case "class":
					value = "className" in mixin?mixin.className:node.className;
					break;
				case "style":
					value = "style" in mixin?mixin.style:(node.style && node.style.cssText); // FIXME: Opera?
				}
				var _type = clsInfo.params[name];
				if(typeof value == "string"){
					params[name] = str2obj(value, _type);
				}else{
					params[name] = value;
				}
			}

			// Process <script type="dojo/*"> script tags
			// <script type="dojo/method" event="foo"> tags are added to params, and passed to
			// the widget on instantiation.
			// <script type="dojo/method"> tags (with no event) are executed after instantiation
			// <script type="dojo/connect" event="foo"> tags are dojo.connected after instantiation
			// note: dojo/* script tags cannot exist in self closing widgets, like <input />
			if(!ps){
				var connects = [],	// functions to connect after instantiation
					calls = [];		// functions to call after instantiation

				d.query("> script[type^='dojo/']", node).orphan().forEach(function(script){
					var event = script.getAttribute("event"),
						type = script.getAttribute("type"),
						nf = d.parser._functionFromScript(script);
					if(event){
						if(type == "dojo/connect"){
							connects.push({event: event, func: nf});
						}else{
							params[event] = nf;
						}
					}else{
						calls.push(nf);
					}
				});
			}

			var markupFactory = clazz.markupFactory || clazz.prototype && clazz.prototype.markupFactory;
			// create the instance
			var instance = markupFactory ? markupFactory(params, node, clazz) : new clazz(params, node);
			thelist.push(instance);

			// map it to the JS namespace if that makes sense
			var jsname = node.getAttribute("jsId");
			if(jsname){
				d.setObject(jsname, instance);
			}

			// process connections and startup functions
			if(!ps){
				d.forEach(connects, function(connect){
					d.connect(instance, connect.event, null, connect.func);
				});
				d.forEach(calls, function(func){
					func.call(instance);
				});
			}
		});

		// Call startup on each top level instance if it makes sense (as for
		// widgets).  Parent widgets will recursively call startup on their
		// (non-top level) children
		if(!mixin._started){
			d.forEach(thelist, function(instance){
				if(	!args.noStart && instance  && 
					instance.startup &&
					!instance._started && 
					(!instance.getParent || !instance.getParent())
				){
					instance.startup();
				}
			});
		}
		return thelist;
	};

	this.parse = function(/*DomNode?*/ rootNode, /* Object? */ args){
		// summary:
		//		Scan the DOM for class instances, and instantiate them.
		//
		// description:
		//		Search specified node (or root node) recursively for class instances,
		//		and instantiate them Searches for
		//		dojoType="qualified.class.name"
		//
		// rootNode: DomNode?
		//		A default starting root node from which to start the parsing. Can be
		//		omitted, defaulting to the entire document. If omitted, the `args`
		//		object can be passed in this place. If the `args` object has a 
		//		`rootNode` member, that is used.
		//
		// args:
		//		a kwArgs object passed along to instantiate()
		//		
		//			* noStart: Boolean?
		//				when set will prevent the parser from calling .startup()
		//				when locating the nodes. 
		//			* rootNode: DomNode?
		//				identical to the function's `rootNode` argument, though
		//				allowed to be passed in via this `args object. 
		//
		// example:
		//		Parse all widgets on a page:
		//	|		dojo.parser.parse();
		//
		// example:
		//		Parse all classes within the node with id="foo"
		//	|		dojo.parser.parse(dojo.byId(foo));
		//
		// example:
		//		Parse all classes in a page, but do not call .startup() on any 
		//		child
		//	|		dojo.parser.parse({ noStart: true })
		//
		// example:
		//		Parse all classes in a node, but do not call .startup()
		//	|		dojo.parser.parse(someNode, { noStart:true });
		//	|		// or
		// 	|		dojo.parser.parse({ noStart:true, rootNode: someNode });

		// determine the root node based on the passed arguments.
		var root;
		if(!args && rootNode && rootNode.rootNode){
			args = rootNode;
			root = args.rootNode;
		}else{
			root = rootNode;
		}

		var	list = d.query(this._query, root);
			// go build the object instances
		return this.instantiate(list, null, args); // Array

	};
}();

//Register the parser callback. It should be the first callback
//after the a11y test.

(function(){
	var parseRunner = function(){ 
		if(dojo.config.parseOnLoad){
			dojo.parser.parse(); 
		}
	};

	// FIXME: need to clobber cross-dependency!!
	if(dojo.exists("dijit.wai.onload") && (dijit.wai.onload === dojo._loaders[0])){
		dojo._loaders.splice(1, 0, parseRunner);
	}else{
		dojo._loaders.unshift(parseRunner);
	}
})();

}

if(!dojo._hasResource["dojo.cache"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojo.cache"] = true;
dojo.provide("dojo.cache");

/*=====
dojo.cache = { 
	// summary:
	// 		A way to cache string content that is fetchable via `dojo.moduleUrl`.
};
=====*/

(function(){
	var cache = {};
	dojo.cache = function(/*String||Object*/module, /*String*/url, /*String||Object?*/value){
		// summary:
		// 		A getter and setter for storing the string content associated with the
		// 		module and url arguments.
		// description:
		// 		module and url are used to call `dojo.moduleUrl()` to generate a module URL.
		// 		If value is specified, the cache value for the moduleUrl will be set to
		// 		that value. Otherwise, dojo.cache will fetch the moduleUrl and store it
		// 		in its internal cache and return that cached value for the URL. To clear
		// 		a cache value pass null for value. Since XMLHttpRequest (XHR) is used to fetch the
		// 		the URL contents, only modules on the same domain of the page can use this capability.
		// 		The build system can inline the cache values though, to allow for xdomain hosting.
		// module: String||Object
		// 		If a String, the module name to use for the base part of the URL, similar to module argument
		// 		to `dojo.moduleUrl`. If an Object, something that has a .toString() method that
		// 		generates a valid path for the cache item. For example, a dojo._Url object.
		// url: String
		// 		The rest of the path to append to the path derived from the module argument. If
		// 		module is an object, then this second argument should be the "value" argument instead.
		// value: String||Object?
		// 		If a String, the value to use in the cache for the module/url combination.
		// 		If an Object, it can have two properties: value and sanitize. The value property
		// 		should be the value to use in the cache, and sanitize can be set to true or false,
		// 		to indicate if XML declarations should be removed from the value and if the HTML
		// 		inside a body tag in the value should be extracted as the real value. The value argument
		// 		or the value property on the value argument are usually only used by the build system
		// 		as it inlines cache content.
		//	example:
		//		To ask dojo.cache to fetch content and store it in the cache (the dojo["cache"] style
		// 		of call is used to avoid an issue with the build system erroneously trying to intern
		// 		this example. To get the build system to intern your dojo.cache calls, use the
		// 		"dojo.cache" style of call):
		// 		|	//If template.html contains "<h1>Hello</h1>" that will be
		// 		|	//the value for the text variable.
		//		|	var text = dojo["cache"]("my.module", "template.html");
		//	example:
		//		To ask dojo.cache to fetch content and store it in the cache, and sanitize the input
		// 		 (the dojo["cache"] style of call is used to avoid an issue with the build system 
		// 		erroneously trying to intern this example. To get the build system to intern your
		// 		dojo.cache calls, use the "dojo.cache" style of call):
		// 		|	//If template.html contains "<html><body><h1>Hello</h1></body></html>", the
		// 		|	//text variable will contain just "<h1>Hello</h1>".
		//		|	var text = dojo["cache"]("my.module", "template.html", {sanitize: true});
		//	example:
		//		Same example as previous, but demostrates how an object can be passed in as
		//		the first argument, then the value argument can then be the second argument.
		// 		|	//If template.html contains "<html><body><h1>Hello</h1></body></html>", the
		// 		|	//text variable will contain just "<h1>Hello</h1>".
		//		|	var text = dojo["cache"](new dojo._Url("my/module/template.html"), {sanitize: true});

		//Module could be a string, or an object that has a toString() method
		//that will return a useful path. If it is an object, then the "url" argument
		//will actually be the value argument.
		if(typeof module == "string"){
			var pathObj = dojo.moduleUrl(module, url);
		}else{
			pathObj = module;
			value = url;
		}
		var key = pathObj.toString();

		var val = value;
		if(value !== undefined && !dojo.isString(value)){
			val = ("value" in value ? value.value : undefined);
		}

		var sanitize = value && value.sanitize ? true : false;

		if(val || val === null){
			//We have a value, either clear or set the cache value.
			if(val == null){
				delete cache[key];
			}else{
				val = cache[key] = sanitize ? dojo.cache._sanitize(val) : val;
			}
		}else{
			//Allow cache values to be empty strings. If key property does
			//not exist, fetch it.
			if(!(key in cache)){
				val = dojo._getText(key);
				cache[key] = sanitize ? dojo.cache._sanitize(val) : val;
			}
			val = cache[key];
		}
		return val; //String
	};

	dojo.cache._sanitize = function(/*String*/val){
		// summary: 
		//		Strips <?xml ...?> declarations so that external SVG and XML
		// 		documents can be added to a document without worry. Also, if the string
		//		is an HTML document, only the part inside the body tag is returned.
		// description:
		// 		Copied from dijit._Templated._sanitizeTemplateString.
		if(val){
			val = val.replace(/^\s*<\?xml(\s)+version=[\'\"](\d)*.(\d)*[\'\"](\s)*\?>/im, "");
			var matches = val.match(/<body[^>]*>\s*([\s\S]+)\s*<\/body>/im);
			if(matches){
				val = matches[1];
			}
		}else{
			val = "";
		}
		return val; //String
	};
})();

}

if(!dojo._hasResource["dijit._Templated"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit._Templated"] = true;
dojo.provide("dijit._Templated");






dojo.declare("dijit._Templated",
	null,
	{
		// summary:
		//		Mixin for widgets that are instantiated from a template

		// templateString: [protected] String
		//		A string that represents the widget template. Pre-empts the
		//		templatePath. In builds that have their strings "interned", the
		//		templatePath is converted to an inline templateString, thereby
		//		preventing a synchronous network call.
		//
		//		Use in conjunction with dojo.cache() to load from a file.
		templateString: null,

		// templatePath: [protected deprecated] String
		//		Path to template (HTML file) for this widget relative to dojo.baseUrl.
		//		Deprecated: use templateString with dojo.cache() instead.
		templatePath: null,

		// widgetsInTemplate: [protected] Boolean
		//		Should we parse the template to find widgets that might be
		//		declared in markup inside it?  False by default.
		widgetsInTemplate: false,

		// skipNodeCache: [protected] Boolean
		//		If using a cached widget template node poses issues for a
		//		particular widget class, it can set this property to ensure
		//		that its template is always re-built from a string
		_skipNodeCache: false,

		// _earlyTemplatedStartup: Boolean
		//		A fallback to preserve the 1.0 - 1.3 behavior of children in
		//		templates having their startup called before the parent widget
		//		fires postCreate. Defaults to 'false', causing child widgets to
		//		have their .startup() called immediately before a parent widget
		//		.startup(), but always after the parent .postCreate(). Set to
		//		'true' to re-enable to previous, arguably broken, behavior.
		_earlyTemplatedStartup: false,

		// _attachPoints: [private] String[]
		//		List of widget attribute names associated with dojoAttachPoint=... in the
		//		template, ex: ["containerNode", "labelNode"]
/*=====
 		_attachPoints: [],
 =====*/

		constructor: function(){
			this._attachPoints = [];
		},

		_stringRepl: function(tmpl){
			// summary:
			//		Does substitution of ${foo} type properties in template string
			// tags:
			//		private
			var className = this.declaredClass, _this = this;
			// Cache contains a string because we need to do property replacement
			// do the property replacement
			return dojo.string.substitute(tmpl, this, function(value, key){
				if(key.charAt(0) == '!'){ value = dojo.getObject(key.substr(1), false, _this); }
				if(typeof value == "undefined"){ throw new Error(className+" template:"+key); } // a debugging aide
				if(value == null){ return ""; }

				// Substitution keys beginning with ! will skip the transform step,
				// in case a user wishes to insert unescaped markup, e.g. ${!foo}
				return key.charAt(0) == "!" ? value :
					// Safer substitution, see heading "Attribute values" in
					// http://www.w3.org/TR/REC-html40/appendix/notes.html#h-B.3.2
					value.toString().replace(/"/g,"&quot;"); //TODO: add &amp? use encodeXML method?
			}, this);
		},

		// method over-ride
		buildRendering: function(){
			// summary:
			//		Construct the UI for this widget from a template, setting this.domNode.
			// tags:
			//		protected

			// Lookup cached version of template, and download to cache if it
			// isn't there already.  Returns either a DomNode or a string, depending on
			// whether or not the template contains ${foo} replacement parameters.
			var cached = dijit._Templated.getCachedTemplate(this.templatePath, this.templateString, this._skipNodeCache);

			var node;
			if(dojo.isString(cached)){
				node = dojo._toDom(this._stringRepl(cached));
				if(node.nodeType != 1){
					// Flag common problems such as templates with multiple top level nodes (nodeType == 11)
					throw new Error("Invalid template: " + cached);
				}
			}else{
				// if it's a node, all we have to do is clone it
				node = cached.cloneNode(true);
			}

			this.domNode = node;

			// recurse through the node, looking for, and attaching to, our
			// attachment points and events, which should be defined on the template node.
			this._attachTemplateNodes(node);

			if(this.widgetsInTemplate){
				// Make sure dojoType is used for parsing widgets in template.
				// The dojo.parser.query could be changed from multiversion support.
				var parser = dojo.parser, qry, attr;
				if(parser._query != "[dojoType]"){
					qry = parser._query;
					attr = parser._attrName;
					parser._query = "[dojoType]";
					parser._attrName = "dojoType";
				}

				// Store widgets that we need to start at a later point in time
				var cw = (this._startupWidgets = dojo.parser.parse(node, {
					noStart: !this._earlyTemplatedStartup
				}));

				// Restore the query.
				if(qry){
					parser._query = qry;
					parser._attrName = attr;
				}

				this._supportingWidgets = dijit.findWidgets(node);

				this._attachTemplateNodes(cw, function(n,p){
					return n[p];
				});
			}

			this._fillContent(this.srcNodeRef);
		},

		_fillContent: function(/*DomNode*/ source){
			// summary:
			//		Relocate source contents to templated container node.
			//		this.containerNode must be able to receive children, or exceptions will be thrown.
			// tags:
			//		protected
			var dest = this.containerNode;
			if(source && dest){
				while(source.hasChildNodes()){
					dest.appendChild(source.firstChild);
				}
			}
		},

		_attachTemplateNodes: function(rootNode, getAttrFunc){
			// summary:
			//		Iterate through the template and attach functions and nodes accordingly.
			// description:
			//		Map widget properties and functions to the handlers specified in
			//		the dom node and it's descendants. This function iterates over all
			//		nodes and looks for these properties:
			//			* dojoAttachPoint
			//			* dojoAttachEvent
			//			* waiRole
			//			* waiState
			// rootNode: DomNode|Array[Widgets]
			//		the node to search for properties. All children will be searched.
			// getAttrFunc: Function?
			//		a function which will be used to obtain property for a given
			//		DomNode/Widget
			// tags:
			//		private

			getAttrFunc = getAttrFunc || function(n,p){ return n.getAttribute(p); };

			var nodes = dojo.isArray(rootNode) ? rootNode : (rootNode.all || rootNode.getElementsByTagName("*"));
			var x = dojo.isArray(rootNode) ? 0 : -1;
			for(; x<nodes.length; x++){
				var baseNode = (x == -1) ? rootNode : nodes[x];
				if(this.widgetsInTemplate && getAttrFunc(baseNode, "dojoType")){
					continue;
				}
				// Process dojoAttachPoint
				var attachPoint = getAttrFunc(baseNode, "dojoAttachPoint");
				if(attachPoint){
					var point, points = attachPoint.split(/\s*,\s*/);
					while((point = points.shift())){
						if(dojo.isArray(this[point])){
							this[point].push(baseNode);
						}else{
							this[point]=baseNode;
						}
						this._attachPoints.push(point);
					}
				}

				// Process dojoAttachEvent
				var attachEvent = getAttrFunc(baseNode, "dojoAttachEvent");
				if(attachEvent){
					// NOTE: we want to support attributes that have the form
					// "domEvent: nativeEvent; ..."
					var event, events = attachEvent.split(/\s*,\s*/);
					var trim = dojo.trim;
					while((event = events.shift())){
						if(event){
							var thisFunc = null;
							if(event.indexOf(":") != -1){
								// oh, if only JS had tuple assignment
								var funcNameArr = event.split(":");
								event = trim(funcNameArr[0]);
								thisFunc = trim(funcNameArr[1]);
							}else{
								event = trim(event);
							}
							if(!thisFunc){
								thisFunc = event;
							}
							this.connect(baseNode, event, thisFunc);
						}
					}
				}

				// waiRole, waiState
				var role = getAttrFunc(baseNode, "waiRole");
				if(role){
					dijit.setWaiRole(baseNode, role);
				}
				var values = getAttrFunc(baseNode, "waiState");
				if(values){
					dojo.forEach(values.split(/\s*,\s*/), function(stateValue){
						if(stateValue.indexOf('-') != -1){
							var pair = stateValue.split('-');
							dijit.setWaiState(baseNode, pair[0], pair[1]);
						}
					});
				}
			}
		},

		startup: function(){
			dojo.forEach(this._startupWidgets, function(w){
				if(w && !w._started && w.startup){
					w.startup();
				}
			});
			this.inherited(arguments);
		},

		destroyRendering: function(){
			// Delete all attach points to prevent IE6 memory leaks.
			dojo.forEach(this._attachPoints, function(point){
				delete this[point];
			}, this);
			this._attachPoints = [];

			this.inherited(arguments);
		}
	}
);

// key is either templatePath or templateString; object is either string or DOM tree
dijit._Templated._templateCache = {};

dijit._Templated.getCachedTemplate = function(templatePath, templateString, alwaysUseString){
	// summary:
	//		Static method to get a template based on the templatePath or
	//		templateString key
	// templatePath: String||dojo.uri.Uri
	//		The URL to get the template from.
	// templateString: String?
	//		a string to use in lieu of fetching the template from a URL. Takes precedence
	//		over templatePath
	// returns: Mixed
	//		Either string (if there are ${} variables that need to be replaced) or just
	//		a DOM tree (if the node can be cloned directly)

	// is it already cached?
	var tmplts = dijit._Templated._templateCache;
	var key = templateString || templatePath;
	var cached = tmplts[key];
	if(cached){
		try{
			// if the cached value is an innerHTML string (no ownerDocument) or a DOM tree created within the current document, then use the current cached value
			if(!cached.ownerDocument || cached.ownerDocument == dojo.doc){
				// string or node of the same document
				return cached;
			}
		}catch(e){ /* squelch */ } // IE can throw an exception if cached.ownerDocument was reloaded
		dojo.destroy(cached);
	}

	// If necessary, load template string from template path
	if(!templateString){
		templateString = dojo.cache(templatePath, {sanitize: true});
	}
	templateString = dojo.string.trim(templateString);

	if(alwaysUseString || templateString.match(/\$\{([^\}]+)\}/g)){
		// there are variables in the template so all we can do is cache the string
		return (tmplts[key] = templateString); //String
	}else{
		// there are no variables in the template so we can cache the DOM tree
		var node = dojo._toDom(templateString);
		if(node.nodeType != 1){
			throw new Error("Invalid template: " + templateString);
		}
		return (tmplts[key] = node); //Node
	}
};

if(dojo.isIE){
	dojo.addOnWindowUnload(function(){
		var cache = dijit._Templated._templateCache;
		for(var key in cache){
			var value = cache[key];
			if(typeof value == "object"){ // value is either a string or a DOM node template
				dojo.destroy(value);
			}
			delete cache[key];
		}
	});
}

// These arguments can be specified for widgets which are used in templates.
// Since any widget can be specified as sub widgets in template, mix it
// into the base widget class.  (This is a hack, but it's effective.)
dojo.extend(dijit._Widget,{
	dojoAttachEvent: "",
	dojoAttachPoint: "",
	waiRole: "",
	waiState:""
});

}

if(!dojo._hasResource["dijit.form._FormMixin"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit.form._FormMixin"] = true;
dojo.provide("dijit.form._FormMixin");

dojo.declare("dijit.form._FormMixin", null,
	{
	// summary:
	//		Mixin for containers of form widgets (i.e. widgets that represent a single value
	//		and can be children of a <form> node or dijit.form.Form widget)
	// description:
	//		Can extract all the form widgets
	//		values and combine them into a single javascript object, or alternately
	//		take such an object and set the values for all the contained
	//		form widgets

/*=====
    // value: Object
	//		Name/value hash for each form element.
	//		If there are multiple elements w/the same name, value is an array,
	//		unless they are radio buttons in which case value is a scalar since only
	//		one can be checked at a time.
	//
	//		If the name is a dot separated list (like a.b.c.d), it's a nested structure.
	//		Only works on widget form elements.
	// example:
	//	| { name: "John Smith", interests: ["sports", "movies"] }
=====*/

	//	TODO:
	//	* Repeater
	//	* better handling for arrays.  Often form elements have names with [] like
	//	* people[3].sex (for a list of people [{name: Bill, sex: M}, ...])
	//
	//

		reset: function(){
			dojo.forEach(this.getDescendants(), function(widget){
				if(widget.reset){
					widget.reset();
				}
			});
		},

		validate: function(){
			// summary:
			//		returns if the form is valid - same as isValid - but
			//			provides a few additional (ui-specific) features.
			//			1 - it will highlight any sub-widgets that are not
			//				valid
			//			2 - it will call focus() on the first invalid
			//				sub-widget
			var didFocus = false;
			return dojo.every(dojo.map(this.getDescendants(), function(widget){
				// Need to set this so that "required" widgets get their
				// state set.
				widget._hasBeenBlurred = true;
				var valid = widget.disabled || !widget.validate || widget.validate();
				if(!valid && !didFocus){
					// Set focus of the first non-valid widget
					dijit.scrollIntoView(widget.containerNode || widget.domNode);
					widget.focus();
					didFocus = true;
				}
	 			return valid;
	 		}), function(item){ return item; });
		},

		setValues: function(val){
			dojo.deprecated(this.declaredClass+"::setValues() is deprecated. Use attr('value', val) instead.", "", "2.0");
			return this.attr('value', val);
		},
		_setValueAttr: function(/*object*/obj){
			// summary:
			//		Fill in form values from according to an Object (in the format returned by attr('value'))

			// generate map from name --> [list of widgets with that name]
			var map = { };
			dojo.forEach(this.getDescendants(), function(widget){
				if(!widget.name){ return; }
				var entry = map[widget.name] || (map[widget.name] = [] );
				entry.push(widget);
			});

			for(var name in map){
				if(!map.hasOwnProperty(name)){
					continue;
				}
				var widgets = map[name],						// array of widgets w/this name
					values = dojo.getObject(name, false, obj);	// list of values for those widgets

				if(values === undefined){
					continue;
				}
				if(!dojo.isArray(values)){
					values = [ values ];
				}
				if(typeof widgets[0].checked == 'boolean'){
					// for checkbox/radio, values is a list of which widgets should be checked
					dojo.forEach(widgets, function(w, i){
						w.attr('value', dojo.indexOf(values, w.value) != -1);
					});
				}else if(widgets[0].multiple){
					// it takes an array (e.g. multi-select)
					widgets[0].attr('value', values);
				}else{
					// otherwise, values is a list of values to be assigned sequentially to each widget
					dojo.forEach(widgets, function(w, i){
						w.attr('value', values[i]);
					});
				}
			}

			/***
			 * 	TODO: code for plain input boxes (this shouldn't run for inputs that are part of widgets)

			dojo.forEach(this.containerNode.elements, function(element){
				if(element.name == ''){return};	// like "continue"
				var namePath = element.name.split(".");
				var myObj=obj;
				var name=namePath[namePath.length-1];
				for(var j=1,len2=namePath.length;j<len2;++j){
					var p=namePath[j - 1];
					// repeater support block
					var nameA=p.split("[");
					if(nameA.length > 1){
						if(typeof(myObj[nameA[0]]) == "undefined"){
							myObj[nameA[0]]=[ ];
						} // if

						nameIndex=parseInt(nameA[1]);
						if(typeof(myObj[nameA[0]][nameIndex]) == "undefined"){
							myObj[nameA[0]][nameIndex] = { };
						}
						myObj=myObj[nameA[0]][nameIndex];
						continue;
					} // repeater support ends

					if(typeof(myObj[p]) == "undefined"){
						myObj=undefined;
						break;
					};
					myObj=myObj[p];
				}

				if(typeof(myObj) == "undefined"){
					return;		// like "continue"
				}
				if(typeof(myObj[name]) == "undefined" && this.ignoreNullValues){
					return;		// like "continue"
				}

				// TODO: widget values (just call attr('value', ...) on the widget)

				// TODO: maybe should call dojo.getNodeProp() instead
				switch(element.type){
					case "checkbox":
						element.checked = (name in myObj) &&
							dojo.some(myObj[name], function(val){ return val == element.value; });
						break;
					case "radio":
						element.checked = (name in myObj) && myObj[name] == element.value;
						break;
					case "select-multiple":
						element.selectedIndex=-1;
						dojo.forEach(element.options, function(option){
							option.selected = dojo.some(myObj[name], function(val){ return option.value == val; });
						});
						break;
					case "select-one":
						element.selectedIndex="0";
						dojo.forEach(element.options, function(option){
							option.selected = option.value == myObj[name];
						});
						break;
					case "hidden":
					case "text":
					case "textarea":
					case "password":
						element.value = myObj[name] || "";
						break;
				}
	  		});
	  		*/
		},

		getValues: function(){
			dojo.deprecated(this.declaredClass+"::getValues() is deprecated. Use attr('value') instead.", "", "2.0");
			return this.attr('value');
		},
		_getValueAttr: function(){
			// summary:
			// 		Returns Object representing form values.
			// description:
			//		Returns name/value hash for each form element.
			//		If there are multiple elements w/the same name, value is an array,
			//		unless they are radio buttons in which case value is a scalar since only
			//		one can be checked at a time.
			//
			//		If the name is a dot separated list (like a.b.c.d), creates a nested structure.
			//		Only works on widget form elements.
			// example:
			//		| { name: "John Smith", interests: ["sports", "movies"] }

			// get widget values
			var obj = { };
			dojo.forEach(this.getDescendants(), function(widget){
				var name = widget.name;
				if(!name || widget.disabled){ return; }

				// Single value widget (checkbox, radio, or plain <input> type widget
				var value = widget.attr('value');

				// Store widget's value(s) as a scalar, except for checkboxes which are automatically arrays
				if(typeof widget.checked == 'boolean'){
					if(/Radio/.test(widget.declaredClass)){
						// radio button
						if(value !== false){
							dojo.setObject(name, value, obj);
						}else{
							// give radio widgets a default of null
							value = dojo.getObject(name, false, obj);
							if(value === undefined){
								dojo.setObject(name, null, obj);
							}
						}
					}else{
						// checkbox/toggle button
						var ary=dojo.getObject(name, false, obj);
						if(!ary){
							ary=[];
							dojo.setObject(name, ary, obj);
						}
						if(value !== false){
							ary.push(value);
						}
					}
				}else{
					var prev=dojo.getObject(name, false, obj);
					if(typeof prev != "undefined"){
						if(dojo.isArray(prev)){
							prev.push(value);
						}else{
							dojo.setObject(name, [prev, value], obj);
						}
					}else{
						// unique name
						dojo.setObject(name, value, obj);
					}
				}
			});

			/***
			 * code for plain input boxes (see also dojo.formToObject, can we use that instead of this code?
			 * but it doesn't understand [] notation, presumably)
			var obj = { };
			dojo.forEach(this.containerNode.elements, function(elm){
				if(!elm.name)	{
					return;		// like "continue"
				}
				var namePath = elm.name.split(".");
				var myObj=obj;
				var name=namePath[namePath.length-1];
				for(var j=1,len2=namePath.length;j<len2;++j){
					var nameIndex = null;
					var p=namePath[j - 1];
					var nameA=p.split("[");
					if(nameA.length > 1){
						if(typeof(myObj[nameA[0]]) == "undefined"){
							myObj[nameA[0]]=[ ];
						} // if
						nameIndex=parseInt(nameA[1]);
						if(typeof(myObj[nameA[0]][nameIndex]) == "undefined"){
							myObj[nameA[0]][nameIndex] = { };
						}
					} else if(typeof(myObj[nameA[0]]) == "undefined"){
						myObj[nameA[0]] = { }
					} // if

					if(nameA.length == 1){
						myObj=myObj[nameA[0]];
					} else{
						myObj=myObj[nameA[0]][nameIndex];
					} // if
				} // for

				if((elm.type != "select-multiple" && elm.type != "checkbox" && elm.type != "radio") || (elm.type == "radio" && elm.checked)){
					if(name == name.split("[")[0]){
						myObj[name]=elm.value;
					} else{
						// can not set value when there is no name
					}
				} else if(elm.type == "checkbox" && elm.checked){
					if(typeof(myObj[name]) == 'undefined'){
						myObj[name]=[ ];
					}
					myObj[name].push(elm.value);
				} else if(elm.type == "select-multiple"){
					if(typeof(myObj[name]) == 'undefined'){
						myObj[name]=[ ];
					}
					for(var jdx=0,len3=elm.options.length; jdx<len3; ++jdx){
						if(elm.options[jdx].selected){
							myObj[name].push(elm.options[jdx].value);
						}
					}
				} // if
				name=undefined;
			}); // forEach
			***/
			return obj;
		},

		// TODO: ComboBox might need time to process a recently input value.  This should be async?
	 	isValid: function(){
	 		// summary:
	 		//		Returns true if all of the widgets are valid

	 		// This also populate this._invalidWidgets[] array with list of invalid widgets...
	 		// TODO: put that into separate function?   It's confusing to have that as a side effect
	 		// of a method named isValid().

			this._invalidWidgets = dojo.filter(this.getDescendants(), function(widget){
				return !widget.disabled && widget.isValid && !widget.isValid();
	 		});
			return !this._invalidWidgets.length;
		},


		onValidStateChange: function(isValid){
			// summary:
			//		Stub function to connect to if you want to do something
			//		(like disable/enable a submit button) when the valid
			//		state changes on the form as a whole.
		},

		_widgetChange: function(widget){
			// summary:
			//		Connected to a widget's onChange function - update our
			//		valid state, if needed.
			var isValid = this._lastValidState;
			if(!widget || this._lastValidState === undefined){
				// We have passed a null widget, or we haven't been validated
				// yet - let's re-check all our children
				// This happens when we connect (or reconnect) our children
				isValid = this.isValid();
				if(this._lastValidState === undefined){
					// Set this so that we don't fire an onValidStateChange
					// the first time
					this._lastValidState = isValid;
				}
			}else if(widget.isValid){
				this._invalidWidgets = dojo.filter(this._invalidWidgets || [], function(w){
					return (w != widget);
				}, this);
				if(!widget.isValid() && !widget.attr("disabled")){
					this._invalidWidgets.push(widget);
				}
				isValid = (this._invalidWidgets.length === 0);
			}
			if(isValid !== this._lastValidState){
				this._lastValidState = isValid;
				this.onValidStateChange(isValid);
			}
		},

		connectChildren: function(){
			// summary:
			//		Connects to the onChange function of all children to
			//		track valid state changes.  You can call this function
			//		directly, ex. in the event that you programmatically
			//		add a widget to the form *after* the form has been
			//		initialized.
			dojo.forEach(this._changeConnections, dojo.hitch(this, "disconnect"));
			var _this = this;

			// we connect to validate - so that it better reflects the states
			// of the widgets - also, we only connect if it has a validate
			// function (to avoid too many unneeded connections)
			var conns = this._changeConnections = [];
			dojo.forEach(dojo.filter(this.getDescendants(),
				function(item){ return item.validate; }
			),
			function(widget){
				// We are interested in whenever the widget is validated - or
				// whenever the disabled attribute on that widget is changed
				conns.push(_this.connect(widget, "validate",
									dojo.hitch(_this, "_widgetChange", widget)));
				conns.push(_this.connect(widget, "_setDisabledAttr",
									dojo.hitch(_this, "_widgetChange", widget)));
			});

			// Call the widget change function to update the valid state, in
			// case something is different now.
			this._widgetChange(null);
		},

		startup: function(){
			this.inherited(arguments);
			// Initialize our valid state tracking.  Needs to be done in startup
			// because it's not guaranteed that our children are initialized
			// yet.
			this._changeConnections = [];
			this.connectChildren();
		}
	});

}

if(!dojo._hasResource["dijit.form.Form"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit.form.Form"] = true;
dojo.provide("dijit.form.Form");





dojo.declare(
	"dijit.form.Form",
	[dijit._Widget, dijit._Templated, dijit.form._FormMixin],
	{
		// summary:
		//		Widget corresponding to HTML form tag, for validation and serialization
		//
		// example:
		//	|	<form dojoType="dijit.form.Form" id="myForm">
		//	|		Name: <input type="text" name="name" />
		//	|	</form>
		//	|	myObj = {name: "John Doe"};
		//	|	dijit.byId('myForm').attr('value', myObj);
		//	|
		//	|	myObj=dijit.byId('myForm').attr('value');

		// HTML <FORM> attributes

		// name: String?
		//		Name of form for scripting.
		name: "",

		// action: String?
		//		Server-side form handler.
		action: "",

		// method: String?
		//		HTTP method used to submit the form, either "GET" or "POST".
		method: "",

		// encType: String?
		//		Encoding type for the form, ex: application/x-www-form-urlencoded.
		encType: "",

		// accept-charset: String?
		//		List of supported charsets.
		"accept-charset": "",

		// accept: String?
		//		List of MIME types for file upload.
		accept: "",

		// target: String?
		//		Target frame for the document to be opened in.
		target: "",

		templateString: "<form dojoAttachPoint='containerNode' dojoAttachEvent='onreset:_onReset,onsubmit:_onSubmit' ${nameAttrSetting}></form>",

		attributeMap: dojo.delegate(dijit._Widget.prototype.attributeMap, {
			action: "",
			method: "",
			encType: "",
			"accept-charset": "",
			accept: "",
			target: ""
		}),

		postMixInProperties: function(){
			// Setup name=foo string to be referenced from the template (but only if a name has been specified)
			// Unfortunately we can't use attributeMap to set the name due to IE limitations, see #8660
			this.nameAttrSetting = this.name ? ("name='" + this.name + "'") : "";
			this.inherited(arguments);
		},

		execute: function(/*Object*/ formContents){
			// summary:
			//		Deprecated: use submit()
			// tags:
			//		deprecated
		},

		onExecute: function(){
			// summary:
			//		Deprecated: use onSubmit()
			// tags:
			//		deprecated
		},

		_setEncTypeAttr: function(/*String*/ value){
			this.encType = value;
			dojo.attr(this.domNode, "encType", value);
			if(dojo.isIE){ this.domNode.encoding = value; }
		},

		postCreate: function(){
			// IE tries to hide encType
			// TODO: this code should be in parser, not here.
			if(dojo.isIE && this.srcNodeRef && this.srcNodeRef.attributes){
				var item = this.srcNodeRef.attributes.getNamedItem('encType');
				if(item && !item.specified && (typeof item.value == "string")){
					this.attr('encType', item.value);
				}
			}
			this.inherited(arguments);
		},

		onReset: function(/*Event?*/ e){
			// summary:
			//		Callback when user resets the form. This method is intended
			//		to be over-ridden. When the `reset` method is called
			//		programmatically, the return value from `onReset` is used
			//		to compute whether or not resetting should proceed
			// tags:
			//		callback
			return true; // Boolean
		},

		_onReset: function(e){
			// create fake event so we can know if preventDefault() is called
			var faux = {
				returnValue: true, // the IE way
				preventDefault: function(){ // not IE
							this.returnValue = false;
						},
				stopPropagation: function(){}, currentTarget: e.currentTarget, target: e.target
			};
			// if return value is not exactly false, and haven't called preventDefault(), then reset
			if(!(this.onReset(faux) === false) && faux.returnValue){
				this.reset();
			}
			dojo.stopEvent(e);
			return false;
		},

		_onSubmit: function(e){
			var fp = dijit.form.Form.prototype;
			// TODO: remove this if statement beginning with 2.0
			if(this.execute != fp.execute || this.onExecute != fp.onExecute){
				dojo.deprecated("dijit.form.Form:execute()/onExecute() are deprecated. Use onSubmit() instead.", "", "2.0");
				this.onExecute();
				this.execute(this.getValues());
			}
			if(this.onSubmit(e) === false){ // only exactly false stops submit
				dojo.stopEvent(e);
			}
		},

		onSubmit: function(/*Event?*/e){
			// summary:
			//		Callback when user submits the form.
			// description:
			//		This method is intended to be over-ridden, but by default it checks and
			//		returns the validity of form elements. When the `submit`
			//		method is called programmatically, the return value from
			//		`onSubmit` is used to compute whether or not submission
			//		should proceed
			// tags:
			//		extension

			return this.isValid(); // Boolean
		},

		submit: function(){
			// summary:
			//		programmatically submit form if and only if the `onSubmit` returns true
			if(!(this.onSubmit() === false)){
				this.containerNode.submit();
			}
		}
	}
);

}

if(!dojo._hasResource["dijit.form._FormWidget"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit.form._FormWidget"] = true;
dojo.provide("dijit.form._FormWidget");




dojo.declare("dijit.form._FormWidget", [dijit._Widget, dijit._Templated],
	{
	// summary:
	//		Base class for widgets corresponding to native HTML elements such as <checkbox> or <button>,
	//		which can be children of a <form> node or a `dijit.form.Form` widget.
	//
	// description:
	//		Represents a single HTML element.
	//		All these widgets should have these attributes just like native HTML input elements.
	//		You can set them during widget construction or afterwards, via `dijit._Widget.attr`.
	//
	//		They also share some common methods.

	// baseClass: [protected] String
	//		Root CSS class of the widget (ex: dijitTextBox), used to add CSS classes of widget
	//		(ex: "dijitTextBox dijitTextBoxInvalid dijitTextBoxFocused dijitTextBoxInvalidFocused")
	//		See _setStateClass().
	baseClass: "",

	// name: String
	//		Name used when submitting form; same as "name" attribute or plain HTML elements
	name: "",

	// alt: String
	//		Corresponds to the native HTML <input> element's attribute.
	alt: "",

	// value: String
	//		Corresponds to the native HTML <input> element's attribute.
	value: "",

	// type: String
	//		Corresponds to the native HTML <input> element's attribute.
	type: "text",

	// tabIndex: Integer
	//		Order fields are traversed when user hits the tab key
	tabIndex: "0",

	// disabled: Boolean
	//		Should this widget respond to user input?
	//		In markup, this is specified as "disabled='disabled'", or just "disabled".
	disabled: false,

	// intermediateChanges: Boolean
	//		Fires onChange for each value change or only on demand
	intermediateChanges: false,

	// scrollOnFocus: Boolean
	//		On focus, should this widget scroll into view?
	scrollOnFocus: true,

	// These mixins assume that the focus node is an INPUT, as many but not all _FormWidgets are.
	attributeMap: dojo.delegate(dijit._Widget.prototype.attributeMap, {
		value: "focusNode",
		id: "focusNode",
		tabIndex: "focusNode",
		alt: "focusNode",
		title: "focusNode"
	}),

	postMixInProperties: function(){
		// Setup name=foo string to be referenced from the template (but only if a name has been specified)
		// Unfortunately we can't use attributeMap to set the name due to IE limitations, see #8660
		this.nameAttrSetting = this.name ? ("name='" + this.name + "'") : "";
		this.inherited(arguments);
	},

	_setDisabledAttr: function(/*Boolean*/ value){
		this.disabled = value;
		dojo.attr(this.focusNode, 'disabled', value);
		if(this.valueNode){
			dojo.attr(this.valueNode, 'disabled', value);
		}
		dijit.setWaiState(this.focusNode, "disabled", value);

		if(value){
			// reset those, because after the domNode is disabled, we can no longer receive
			// mouse related events, see #4200
			this._hovering = false;
			this._active = false;
			// remove the tabIndex, especially for FF
			this.focusNode.setAttribute('tabIndex', "-1");
		}else{
			this.focusNode.setAttribute('tabIndex', this.tabIndex);
		}
		this._setStateClass();
	},

	setDisabled: function(/*Boolean*/ disabled){
		// summary:
		//		Deprecated.   Use attr('disabled', ...) instead.
		dojo.deprecated("setDisabled("+disabled+") is deprecated. Use attr('disabled',"+disabled+") instead.", "", "2.0");
		this.attr('disabled', disabled);
	},

	_onFocus: function(e){
		if(this.scrollOnFocus){
			dijit.scrollIntoView(this.domNode);
		}
		this.inherited(arguments);
	},

	_onMouse : function(/*Event*/ event){
		// summary:
		//	Sets _hovering, _active, and stateModifier properties depending on mouse state,
		//	then calls setStateClass() to set appropriate CSS classes for this.domNode.
		//
		//	To get a different CSS class for hover, send onmouseover and onmouseout events to this method.
		//	To get a different CSS class while mouse button is depressed, send onmousedown to this method.

		var mouseNode = event.currentTarget;
		if(mouseNode && mouseNode.getAttribute){
			this.stateModifier = mouseNode.getAttribute("stateModifier") || "";
		}

		if(!this.disabled){
			switch(event.type){
				case "mouseenter":
				case "mouseover":
					this._hovering = true;
					this._active = this._mouseDown;
					break;

				case "mouseout":
				case "mouseleave":
					this._hovering = false;
					this._active = false;
					break;

				case "mousedown" :
					this._active = true;
					this._mouseDown = true;
					// set a global event to handle mouseup, so it fires properly
					//	even if the cursor leaves the button
					var mouseUpConnector = this.connect(dojo.body(), "onmouseup", function(){
						// if user clicks on the button, even if the mouse is released outside of it,
						// this button should get focus (which mimics native browser buttons)
						if(this._mouseDown && this.isFocusable()){
							this.focus();
						}
						this._active = false;
						this._mouseDown = false;
						this._setStateClass();
						this.disconnect(mouseUpConnector);
					});
					break;
			}
			this._setStateClass();
		}
	},

	isFocusable: function(){
		// summary:
		//		Tells if this widget is focusable or not.   Used internally by dijit.
		// tags:
		//		protected
		return !this.disabled && !this.readOnly && this.focusNode && (dojo.style(this.domNode, "display") != "none");
	},

	focus: function(){
		// summary:
		//		Put focus on this widget
		dijit.focus(this.focusNode);
	},

	_setStateClass: function(){
		// summary:
		//		Update the visual state of the widget by setting the css classes on this.domNode
		//		(or this.stateNode if defined) by combining this.baseClass with
		//		various suffixes that represent the current widget state(s).
		//
		// description:
		//		In the case where a widget has multiple
		//		states, it sets the class based on all possible
		//	 	combinations.  For example, an invalid form widget that is being hovered
		//		will be "dijitInput dijitInputInvalid dijitInputHover dijitInputInvalidHover".
		//
		//		For complex widgets with multiple regions, there can be various hover/active states,
		//		such as "Hover" or "CloseButtonHover" (for tab buttons).
		//		This is controlled by a stateModifier="CloseButton" attribute on the close button node.
		//
		//		The widget may have one or more of the following states, determined
		//		by this.state, this.checked, this.valid, and this.selected:
		//			- Error - ValidationTextBox sets this.state to "Error" if the current input value is invalid
		//			- Checked - ex: a checkmark or a ToggleButton in a checked state, will have this.checked==true
		//			- Selected - ex: currently selected tab will have this.selected==true
		//
		//		In addition, it may have one or more of the following states,
		//		based on this.disabled and flags set in _onMouse (this._active, this._hovering, this._focused):
		//			- Disabled	- if the widget is disabled
		//			- Active		- if the mouse (or space/enter key?) is being pressed down
		//			- Focused		- if the widget has focus
		//			- Hover		- if the mouse is over the widget

		// Compute new set of classes
		var newStateClasses = this.baseClass.split(" ");

		function multiply(modifier){
			newStateClasses = newStateClasses.concat(dojo.map(newStateClasses, function(c){ return c+modifier; }), "dijit"+modifier);
		}

		if(this.checked){
			multiply("Checked");
		}
		if(this.state){
			multiply(this.state);
		}
		if(this.selected){
			multiply("Selected");
		}

		if(this.disabled){
			multiply("Disabled");
		}else if(this.readOnly){
			multiply("ReadOnly");
		}else if(this._active){
			multiply(this.stateModifier+"Active");
		}else{
			if(this._focused){
				multiply("Focused");
			}
			if(this._hovering){
				multiply(this.stateModifier+"Hover");
			}
		}

		// Remove old state classes and add new ones.
		// For performance concerns we only write into domNode.className once.
		var tn = this.stateNode || this.domNode,
			classHash = {};	// set of all classes (state and otherwise) for node

		dojo.forEach(tn.className.split(" "), function(c){ classHash[c] = true; });

		if("_stateClasses" in this){
			dojo.forEach(this._stateClasses, function(c){ delete classHash[c]; });
		}

		dojo.forEach(newStateClasses, function(c){ classHash[c] = true; });

		var newClasses = [];
		for(var c in classHash){
			newClasses.push(c);
		}
		tn.className = newClasses.join(" ");

		this._stateClasses = newStateClasses;
	},

	compare: function(/*anything*/val1, /*anything*/val2){
		// summary:
		//		Compare 2 values (as returned by attr('value') for this widget).
		// tags:
		//		protected
		if(typeof val1 == "number" && typeof val2 == "number"){
			return (isNaN(val1) && isNaN(val2)) ? 0 : val1 - val2;
		}else if(val1 > val2){
			return 1;
		}else if(val1 < val2){
			return -1;
		}else{
			return 0;
		}
	},

	onChange: function(newValue){
		// summary:
		//		Callback when this widget's value is changed.
		// tags:
		//		callback
	},

	// _onChangeActive: [private] Boolean
	//		Indicates that changes to the value should call onChange() callback.
	//		This is false during widget initialization, to avoid calling onChange()
	//		when the initial value is set.
	_onChangeActive: false,

	_handleOnChange: function(/*anything*/ newValue, /* Boolean? */ priorityChange){
		// summary:
		//		Called when the value of the widget is set.  Calls onChange() if appropriate
		// newValue:
		//		the new value
		// priorityChange:
		//		For a slider, for example, dragging the slider is priorityChange==false,
		//		but on mouse up, it's priorityChange==true.  If intermediateChanges==true,
		//		onChange is only called form priorityChange=true events.
		// tags:
		//		private
		this._lastValue = newValue;
		if(this._lastValueReported == undefined && (priorityChange === null || !this._onChangeActive)){
			// this block executes not for a change, but during initialization,
			// and is used to store away the original value (or for ToggleButton, the original checked state)
			this._resetValue = this._lastValueReported = newValue;
		}
		if((this.intermediateChanges || priorityChange || priorityChange === undefined) &&
			((typeof newValue != typeof this._lastValueReported) ||
				this.compare(newValue, this._lastValueReported) != 0)){
			this._lastValueReported = newValue;
			if(this._onChangeActive){
				if(this._onChangeHandle){
					clearTimeout(this._onChangeHandle);
				}
				// setTimout allows hidden value processing to run and
				// also the onChange handler can safely adjust focus, etc
				this._onChangeHandle = setTimeout(dojo.hitch(this,
					function(){
						this._onChangeHandle = null;
						this.onChange(newValue);
					}), 0); // try to collapse multiple onChange's fired faster than can be processed
			}
		}
	},

	create: function(){
		// Overrides _Widget.create()
		this.inherited(arguments);
		this._onChangeActive = true;
		this._setStateClass();
	},

	destroy: function(){
		if(this._onChangeHandle){ // destroy called before last onChange has fired
			clearTimeout(this._onChangeHandle);
			this.onChange(this._lastValueReported);
		}
		this.inherited(arguments);
	},

	setValue: function(/*String*/ value){
		// summary:
		//		Deprecated.   Use attr('value', ...) instead.
		dojo.deprecated("dijit.form._FormWidget:setValue("+value+") is deprecated.  Use attr('value',"+value+") instead.", "", "2.0");
		this.attr('value', value);
	},

	getValue: function(){
		// summary:
		//		Deprecated.   Use attr('value') instead.
		dojo.deprecated(this.declaredClass+"::getValue() is deprecated. Use attr('value') instead.", "", "2.0");
		return this.attr('value');
	}
});

dojo.declare("dijit.form._FormValueWidget", dijit.form._FormWidget,
{
	// summary:
	//		Base class for widgets corresponding to native HTML elements such as <input> or <select> that have user changeable values.
	// description:
	//		Each _FormValueWidget represents a single input value, and has a (possibly hidden) <input> element,
	//		to which it serializes it's input value, so that form submission (either normal submission or via FormBind?)
	//		works as expected.

	// Don't attempt to mixin the 'type', 'name' attributes here programatically -- they must be declared
	// directly in the template as read by the parser in order to function. IE is known to specifically
	// require the 'name' attribute at element creation time.   See #8484, #8660.
	// TODO: unclear what that {value: ""} is for; FormWidget.attributeMap copies value to focusNode,
	// so maybe {value: ""} is so the value *doesn't* get copied to focusNode?
	// Seems like we really want value removed from attributeMap altogether
	// (although there's no easy way to do that now)

	// readOnly: Boolean
	//		Should this widget respond to user input?
	//		In markup, this is specified as "readOnly".
	//		Similar to disabled except readOnly form values are submitted.
	readOnly: false,

	attributeMap: dojo.delegate(dijit.form._FormWidget.prototype.attributeMap, {
		value: "",
		readOnly: "focusNode"
	}),

	_setReadOnlyAttr: function(/*Boolean*/ value){
		this.readOnly = value;
		dojo.attr(this.focusNode, 'readOnly', value);
		dijit.setWaiState(this.focusNode, "readonly", value);
		this._setStateClass();
	},

	postCreate: function(){
		if(dojo.isIE){ // IE won't stop the event with keypress
			this.connect(this.focusNode || this.domNode, "onkeydown", this._onKeyDown);
		}
		// Update our reset value if it hasn't yet been set (because this.attr
		// is only called when there *is* a value
		if(this._resetValue === undefined){
			this._resetValue = this.value;
		}
	},

	_setValueAttr: function(/*anything*/ newValue, /*Boolean, optional*/ priorityChange){
		// summary:
		//		Hook so attr('value', value) works.
		// description:
		//		Sets the value of the widget.
		//		If the value has changed, then fire onChange event, unless priorityChange
		//		is specified as null (or false?)
		this.value = newValue;
		this._handleOnChange(newValue, priorityChange);
	},

	_getValueAttr: function(){
		// summary:
		//		Hook so attr('value') works.
		return this._lastValue;
	},

	undo: function(){
		// summary:
		//		Restore the value to the last value passed to onChange
		this._setValueAttr(this._lastValueReported, false);
	},

	reset: function(){
		// summary:
		//		Reset the widget's value to what it was at initialization time
		this._hasBeenBlurred = false;
		this._setValueAttr(this._resetValue, true);
	},

	_onKeyDown: function(e){
		if(e.keyCode == dojo.keys.ESCAPE && !(e.ctrlKey || e.altKey || e.metaKey)){
			var te;
			if(dojo.isIE){
				e.preventDefault(); // default behavior needs to be stopped here since keypress is too late
				te = document.createEventObject();
				te.keyCode = dojo.keys.ESCAPE;
				te.shiftKey = e.shiftKey;
				e.srcElement.fireEvent('onkeypress', te);
			}
		}
	},

	_layoutHackIE7: function(){
		// summary:
		//		Work around table sizing bugs on IE7 by forcing redraw

		if(dojo.isIE == 7){ // fix IE7 layout bug when the widget is scrolled out of sight
			var domNode = this.domNode;
			var parent = domNode.parentNode;
			var pingNode = domNode.firstChild || domNode; // target node most unlikely to have a custom filter
			var origFilter = pingNode.style.filter; // save custom filter, most likely nothing
			while(parent && parent.clientHeight == 0){ // search for parents that haven't rendered yet
				parent._disconnectHandle = this.connect(parent, "onscroll", dojo.hitch(this, function(e){
					this.disconnect(parent._disconnectHandle); // only call once
					parent.removeAttribute("_disconnectHandle"); // clean up DOM node
					pingNode.style.filter = (new Date()).getMilliseconds(); // set to anything that's unique
					setTimeout(function(){ pingNode.style.filter = origFilter }, 0); // restore custom filter, if any
				}));
				parent = parent.parentNode;
			}
		}
	}
});

}

if(!dojo._hasResource["dijit._Container"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit._Container"] = true;
dojo.provide("dijit._Container");

dojo.declare("dijit._Container",
	null,
	{
		// summary:
		//		Mixin for widgets that contain a set of widget children.
		// description:
		//		Use this mixin for widgets that needs to know about and
		//		keep track of their widget children. Suitable for widgets like BorderContainer
		//		and TabContainer which contain (only) a set of child widgets.
		//
		//		It's not suitable for widgets like ContentPane
		//		which contains mixed HTML (plain DOM nodes in addition to widgets),
		//		and where contained widgets are not necessarily directly below
		//		this.containerNode.   In that case calls like addChild(node, position)
		//		wouldn't make sense.

		// isContainer: [protected] Boolean
		//		Indicates that this widget acts as a "parent" to the descendant widgets.
		//		When the parent is started it will call startup() on the child widgets.
		//		See also `isLayoutContainer`.
		isContainer: true,

		buildRendering: function(){
			this.inherited(arguments);
			if(!this.containerNode){
				// all widgets with descendants must set containerNode
	 				this.containerNode = this.domNode;
			}
		},

		addChild: function(/*dijit._Widget*/ widget, /*int?*/ insertIndex){
			// summary:
			//		Makes the given widget a child of this widget.
			// description:
			//		Inserts specified child widget's dom node as a child of this widget's
			//		container node, and possibly does other processing (such as layout).

			var refNode = this.containerNode;
			if(insertIndex && typeof insertIndex == "number"){
				var children = this.getChildren();
				if(children && children.length >= insertIndex){
					refNode = children[insertIndex-1].domNode;
					insertIndex = "after";
				}
			}
			dojo.place(widget.domNode, refNode, insertIndex);

			// If I've been started but the child widget hasn't been started,
			// start it now.  Make sure to do this after widget has been
			// inserted into the DOM tree, so it can see that it's being controlled by me,
			// so it doesn't try to size itself.
			if(this._started && !widget._started){
				widget.startup();
			}
		},

		removeChild: function(/*Widget or int*/ widget){
			// summary:
			//		Removes the passed widget instance from this widget but does
			//		not destroy it.  You can also pass in an integer indicating
			//		the index within the container to remove

			if(typeof widget == "number" && widget > 0){
				widget = this.getChildren()[widget];
			}

			if(widget && widget.domNode){
				var node = widget.domNode;
				node.parentNode.removeChild(node); // detach but don't destroy
			}
		},

		getChildren: function(){
			// summary:
			//		Returns array of children widgets.
			// description:
			//		Returns the widgets that are directly under this.containerNode.
			return dojo.query("> [widgetId]", this.containerNode).map(dijit.byNode); // Widget[]
		},

		hasChildren: function(){
			// summary:
			//		Returns true if widget has children, i.e. if this.containerNode contains something.
			return dojo.query("> [widgetId]", this.containerNode).length > 0;	// Boolean
		},

		destroyDescendants: function(/*Boolean*/ preserveDom){
			// summary:
			//      Destroys all the widgets inside this.containerNode,
			//      but not this widget itself
			dojo.forEach(this.getChildren(), function(child){ child.destroyRecursive(preserveDom); });
		},

		_getSiblingOfChild: function(/*dijit._Widget*/ child, /*int*/ dir){
			// summary:
			//		Get the next or previous widget sibling of child
			// dir:
			//		if 1, get the next sibling
			//		if -1, get the previous sibling
			// tags:
			//      private
			var node = child.domNode,
				which = (dir>0 ? "nextSibling" : "previousSibling");
			do{
				node = node[which];
			}while(node && (node.nodeType != 1 || !dijit.byNode(node)));
			return node && dijit.byNode(node);	// dijit._Widget
		},

		getIndexOfChild: function(/*dijit._Widget*/ child){
			// summary:
			//		Gets the index of the child in this container or -1 if not found
			return dojo.indexOf(this.getChildren(), child);	// int
		},

		startup: function(){
			// summary:
			//		Called after all the widgets have been instantiated and their
			//		dom nodes have been inserted somewhere under dojo.doc.body.
			//
			//		Widgets should override this method to do any initialization
			//		dependent on other widgets existing, and then call
			//		this superclass method to finish things off.
			//
			//		startup() in subclasses shouldn't do anything
			//		size related because the size of the widget hasn't been set yet.

			if(this._started){ return; }

			// Startup all children of this widget
			dojo.forEach(this.getChildren(), function(child){ child.startup(); });

			this.inherited(arguments);
		}
	}
);

}

if(!dojo._hasResource["dijit._HasDropDown"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit._HasDropDown"] = true;
dojo.provide("dijit._HasDropDown");




dojo.declare("dijit._HasDropDown",
	null,
	{
		// summary:
		//		Mixin for widgets that need drop down ability.

		// _buttonNode: [protected] DomNode
		//		The button/icon/node to click to display the drop down.
		//		Can be set via a dojoAttachPoint assignment.
		//		If missing, then either focusNode or domNode (if focusNode is also missing) will be used.
		_buttonNode: null,

		// _arrowWrapperNode: [protected] DomNode
		//		Will set CSS class dijitUpArrow, dijitDownArrow, dijitRightArrow etc. on this node depending
		//		on where the drop down is set to be positioned.
		//		Can be set via a dojoAttachPoint assignment.
		//		If missing, then _buttonNode will be used.
		_arrowWrapperNode: null,

		// _popupStateNode: [protected] DomNode
		//		The node to set the popupActive class on.
		//		Can be set via a dojoAttachPoint assignment.
		//		If missing, then focusNode or _buttonNode (if focusNode is missing) will be used.
		_popupStateNode: null,

		// _aroundNode: [protected] DomNode
		//		The node to display the popup around.
		//		Can be set via a dojoAttachPoint assignment.
		//		If missing, then domNode will be used.
		_aroundNode: null,

		// dropDown: [protected] Widget
		//		The widget to display as a popup.  This widget *must* be
		//		defined before the startup function is called.
		dropDown: null,

		// autoWidth: [protected] Boolean
		//		Set to true to make the drop down at least as wide as this
		//		widget.  Set to false if the drop down should just be its
		//		default width
		autoWidth: true,

		// forceWidth: [protected] Boolean
		//		Set to true to make the drop down exactly as wide as this
		//		widget.  Overrides autoWidth.
		forceWidth: false,

		// maxHeight: [protected] Integer
		//		The max height for our dropdown.  Set to 0 for no max height.
		//		any dropdown taller than this will have scrollbars
		maxHeight: 0,

		// dropDownPosition: [const] String[]
		//		This variable controls the position of the drop down.
		//		It's an array of strings with the following values:
		//
		//			* before: places drop down to the left of the target node/widget, or to the right in
		//			  the case of RTL scripts like Hebrew and Arabic
		//			* after: places drop down to the right of the target node/widget, or to the left in
		//			  the case of RTL scripts like Hebrew and Arabic
		//			* above: drop down goes above target node
		//			* below: drop down goes below target node
		//
		//		The list is positions is tried, in order, until a position is found where the drop down fits
		//		within the viewport.
		//
		dropDownPosition: ["below","above"],

		// _stopClickEvents: Boolean
		//		When set to false, the click events will not be stopped, in
		//		case you want to use them in your subwidget
		_stopClickEvents: true,

		_onDropDownMouse: function(/*Event*/ e){
			// summary:
			//		Callback when the user mouse clicks on the arrow icon, or presses the down
			//		arrow key, to open the drop down.

			// We handle mouse events using onmousedown in order to allow for selecting via
			// a mouseDown --> mouseMove --> mouseUp.  So, our click is already handled, unless
			// we are executed via keypress - in which case, this._seenKeydown
			// will be set to true.
			if(e.type == "click" && !this._seenKeydown){ return; }
			this._seenKeydown = false;

			// If we are a mouse event, set up the mouseup handler.  See _onDropDownMouse() for
			// details on this handler.
			if(e.type == "mousedown"){
				this._docHandler = this.connect(dojo.doc, "onmouseup", "_onDropDownMouseup");
			}
			if(this.disabled || this.readOnly){ return; }
			if(this._stopClickEvents){
				dojo.stopEvent(e);
			}
			this.toggleDropDown();

			// If we are a click, then we'll pretend we did a mouse up
			if(e.type == "click" || e.type == "keypress"){
				this._onDropDownMouseup();
			}
		},

		_onDropDownMouseup: function(/*Event?*/ e){
			// summary:
			//		Callback when the user lifts their mouse after mouse down on the arrow icon.
			//		If the drop is a simple menu and the mouse is over the menu, we execute it, otherwise, we focus our
			//		dropDown node.  If the event is missing, then we are not
			//		a mouseup event.
			//
			//		This is useful for the common mouse movement pattern
			//		with native browser <select> nodes:
			//			1. mouse down on the select node (probably on the arrow)
			//			2. move mouse to a menu item while holding down the mouse button
			//			3. mouse up.  this selects the menu item as though the user had clicked it.

			if(e && this._docHandler){
				this.disconnect(this._docHandler);
			}
			var dropDown = this.dropDown, overMenu = false;

			if(e && this._opened){
				// This code deals with the corner-case when the drop down covers the original widget,
				// because it's so large.  In that case mouse-up shouldn't select a value from the menu.
				// Find out if our target is somewhere in our dropdown widget,
				// but not over our _buttonNode (the clickable node)
				var c = dojo.position(this._buttonNode, true);
				if(!(e.pageX >= c.x && e.pageX <= c.x + c.w) ||
					!(e.pageY >= c.y && e.pageY <= c.y + c.h)){
					var t = e.target;
					while(t && !overMenu){
						if(dojo.hasClass(t, "dijitPopup")){
							overMenu = true;
						}else{
							t = t.parentNode;
						}
					}
					if(overMenu){
						t = e.target;
						if(dropDown.onItemClick){
							var menuItem;
							while(t && !(menuItem = dijit.byNode(t))){
								t = t.parentNode;
							}
							if(menuItem && menuItem.onClick && menuItem.getParent){
								menuItem.getParent().onItemClick(menuItem, e);
							}
						}
						return;
					}
				}
			}
			if(this._opened && dropDown.focus){
				// Focus the dropdown widget - do it on a delay so that we
				// don't steal our own focus.
				window.setTimeout(dojo.hitch(dropDown, "focus"), 1);
			}
		},

		_setupDropdown: function(){
			// summary:
			//		set up nodes and connect our mouse and keypress events
			this._buttonNode = this._buttonNode || this.focusNode || this.domNode;
			this._popupStateNode = this._popupStateNode || this.focusNode || this._buttonNode;
			this._aroundNode = this._aroundNode || this.domNode;
			this.connect(this._buttonNode, "onmousedown", "_onDropDownMouse");
			this.connect(this._buttonNode, "onclick", "_onDropDownMouse");
			this.connect(this._buttonNode, "onkeydown", "_onDropDownKeydown");
			this.connect(this._buttonNode, "onblur", "_onDropDownBlur");
			this.connect(this._buttonNode, "onkeypress", "_onKey");

			// If we have a _setStateClass function (which happens when
			// we are a form widget), then we need to connect our open/close
			// functions to it
			if(this._setStateClass){
				this.connect(this, "openDropDown", "_setStateClass");
				this.connect(this, "closeDropDown", "_setStateClass");
			}

			// Add a class to the "dijitDownArrowButton" type class to _buttonNode so theme can set direction of arrow
			// based on where drop down will normally appear
			var defaultPos = {
					"after" : this.isLeftToRight() ? "Right" : "Left",
					"before" : this.isLeftToRight() ? "Left" : "Right",
					"above" : "Up",
					"below" : "Down",
					"left" : "Left",
					"right" : "Right"
			}[this.dropDownPosition[0]] || this.dropDownPosition[0] || "Down";
			dojo.addClass(this._arrowWrapperNode || this._buttonNode, "dijit" + defaultPos + "ArrowButton");
		},

		postCreate: function(){
			this._setupDropdown();
			this.inherited(arguments);
		},

		destroyDescendants: function(){
			if(this.dropDown){
				// Destroy the drop down, unless it's already been destroyed.  This can happen because
				// the drop down is a direct child of <body> even though it's logically my child.
				if(!this.dropDown._destroyed){
					this.dropDown.destroyRecursive();
				}
				delete this.dropDown;
			}
			this.inherited(arguments);
		},

		_onDropDownKeydown: function(/*Event*/ e){
			this._seenKeydown = true;
		},

		_onKeyPress: function(/*Event*/ e){
			if(this._opened && e.charOrCode == dojo.keys.ESCAPE && !e.shiftKey && !e.ctrlKey && !e.altKey){
				this.toggleDropDown();
				dojo.stopEvent(e);
				return;
			}
			this.inherited(arguments);
		},

		_onDropDownBlur: function(/*Event*/ e){
			this._seenKeydown = false;
		},

		_onKey: function(/*Event*/ e){
			// summary:
			//		Callback when the user presses a key on menu popup node

			if(this.disabled || this.readOnly){ return; }
			var d = this.dropDown;
			if(d && this._opened && d.handleKey){
				if(d.handleKey(e) === false){ return; }
			}
			if(d && this._opened && e.keyCode == dojo.keys.ESCAPE){
				this.toggleDropDown();
				return;
			}
			if(e.keyCode == dojo.keys.DOWN_ARROW || e.keyCode == dojo.keys.ENTER || e.charOrCode == " "){
				this._onDropDownMouse(e);
			}
		},

		_onBlur: function(){
			// summary:
			//		Called magically when focus has shifted away from this widget and it's dropdown

			this.closeDropDown();
			// don't focus on button.  the user has explicitly focused on something else.
			this.inherited(arguments);
		},

		isLoaded: function(){
			// summary:
			//		Returns whether or not the dropdown is loaded.  This can
			//		be overridden in order to force a call to loadDropDown().
			// tags:
			//		protected

			return true;
		},

		loadDropDown: function(/* Function */ loadCallback){
			// summary:
			//		Loads the data for the dropdown, and at some point, calls
			//		the given callback
			// tags:
			//		protected

			loadCallback();
		},

		toggleDropDown: function(){
			// summary:
			//		Toggle the drop-down widget; if it is up, close it, if not, open it
			// tags:
			//		protected

			if(this.disabled || this.readOnly){ return; }
			this.focus();
			var dropDown = this.dropDown;
			if(!dropDown){ return; }
			if(!this._opened){
				// If we aren't loaded, load it first so there isn't a flicker
				if(!this.isLoaded()){
					this.loadDropDown(dojo.hitch(this, "openDropDown"));
					return;
				}else{
					this.openDropDown();
				}
			}else{
				this.closeDropDown();
			}
		},

		openDropDown: function(){
			// summary:
			//		Opens the dropdown for this widget - it returns the
			//		return value of dijit.popup.open
			// tags:
			//		protected

			var dropDown = this.dropDown;
			var ddNode = dropDown.domNode;
			var self = this;

			// Prepare our popup's height and honor maxHeight if it exists.

			// TODO: isn't maxHeight dependent on the return value from dijit.popup.open(),
			// ie, dependent on how much space is available (BK)

			if(!this._preparedNode){
				dijit.popup.moveOffScreen(ddNode);
				this._preparedNode = true;			
				// Check if we have explicitly set width and height on the dropdown widget dom node
				if(ddNode.style.width){
					this._explicitDDWidth = true;
				}
				if(ddNode.style.height){
					this._explicitDDHeight = true;
				}
			}
			if(this.maxHeight || this.forceWidth || this.autoWidth){
				var myStyle = {
					display: "",
					visibility: "hidden"
				};
				if(!this._explicitDDWidth){
					myStyle.width = "";
				}
				if(!this._explicitDDHeight){
					myStyle.height = "";
				}
				dojo.style(ddNode, myStyle);
				var mb = dojo.marginBox(ddNode);
				var overHeight = (this.maxHeight && mb.h > this.maxHeight);
				dojo.style(ddNode, {overflow: overHeight ? "auto" : "hidden"});
				if(this.forceWidth){
					mb.w = this.domNode.offsetWidth;
				}else if(this.autoWidth){
					mb.w = Math.max(mb.w, this.domNode.offsetWidth);
				}else{
					delete mb.w;
				}
				if(overHeight){
					mb.h = this.maxHeight;
					if("w" in mb){
						mb.w += 16;
					}
				}else{
					delete mb.h;
				}
				delete mb.t;
				delete mb.l;
				if(dojo.isFunction(dropDown.resize)){
					dropDown.resize(mb);
				}else{
					dojo.marginBox(ddNode, mb);
				}
			}
			var retVal = dijit.popup.open({
				parent: this,
				popup: dropDown,
				around: this._aroundNode,
				orient: dijit.getPopupAroundAlignment((this.dropDownPosition && this.dropDownPosition.length) ? this.dropDownPosition : ["below"],this.isLeftToRight()),
				onExecute: function(){
					self.closeDropDown(true);
				},
				onCancel: function(){
					self.closeDropDown(true);
				},
				onClose: function(){
					dojo.attr(self._popupStateNode, "popupActive", false);
					dojo.removeClass(self._popupStateNode, "dijitHasDropDownOpen");
					self._opened = false;
					self.state = "";
				}
			});
			dojo.attr(this._popupStateNode, "popupActive", "true");
			dojo.addClass(self._popupStateNode, "dijitHasDropDownOpen");
			this._opened=true;
			this.state="Opened";
			// TODO: set this.checked and call setStateClass(), to affect button look while drop down is shown
			return retVal;
		},

		closeDropDown: function(/*Boolean*/ focus){
			// summary:
			//		Closes the drop down on this widget
			// tags:
			//		protected

			if(this._opened){
				dijit.popup.close(this.dropDown);
				if(focus){ this.focus(); }
				this._opened = false;
				this.state = "";
			}
		}

	}
);

}

if(!dojo._hasResource["dijit.form.Button"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit.form.Button"] = true;
dojo.provide("dijit.form.Button");





dojo.declare("dijit.form.Button",
	dijit.form._FormWidget,
	{
	// summary:
	//		Basically the same thing as a normal HTML button, but with special styling.
	// description:
	//		Buttons can display a label, an icon, or both.
	//		A label should always be specified (through innerHTML) or the label
	//		attribute.  It can be hidden via showLabel=false.
	// example:
	// |	<button dojoType="dijit.form.Button" onClick="...">Hello world</button>
	//
	// example:
	// |	var button1 = new dijit.form.Button({label: "hello world", onClick: foo});
	// |	dojo.body().appendChild(button1.domNode);

	// label: HTML String
	//		Text to display in button.
	//		If the label is hidden (showLabel=false) then and no title has
	//		been specified, then label is also set as title attribute of icon.
	label: "",

	// showLabel: Boolean
	//		Set this to true to hide the label text and display only the icon.
	//		(If showLabel=false then iconClass must be specified.)
	//		Especially useful for toolbars.
	//		If showLabel=true, the label will become the title (a.k.a. tooltip/hint) of the icon.
	//
	//		The exception case is for computers in high-contrast mode, where the label
	//		will still be displayed, since the icon doesn't appear.
	showLabel: true,

	// iconClass: String
	//		Class to apply to DOMNode in button to make it display an icon
	iconClass: "",

	// type: String
	//		Defines the type of button.  "button", "submit", or "reset".
	type: "button",

	baseClass: "dijitButton",

	templateString: dojo.cache("dijit.form", "templates/Button.html", "<span class=\"dijit dijitReset dijitLeft dijitInline\"\r\n\tdojoAttachEvent=\"onclick:_onButtonClick,onmouseenter:_onMouse,onmouseleave:_onMouse,onmousedown:_onMouse\"\r\n\t><span class=\"dijitReset dijitRight dijitInline\"\r\n\t\t><span class=\"dijitReset dijitInline dijitButtonNode\"\r\n\t\t\t><button class=\"dijitReset dijitStretch dijitButtonContents\"\r\n\t\t\t\tdojoAttachPoint=\"titleNode,focusNode\"\r\n\t\t\t\t${nameAttrSetting} type=\"${type}\" value=\"${value}\" waiRole=\"button\" waiState=\"labelledby-${id}_label\"\r\n\t\t\t\t><span class=\"dijitReset dijitInline\" dojoAttachPoint=\"iconNode\"\r\n\t\t\t\t\t><span class=\"dijitReset dijitToggleButtonIconChar\">&#10003;</span\r\n\t\t\t\t></span\r\n\t\t\t\t><span class=\"dijitReset dijitInline dijitButtonText\"\r\n\t\t\t\t\tid=\"${id}_label\"\r\n\t\t\t\t\tdojoAttachPoint=\"containerNode\"\r\n\t\t\t\t></span\r\n\t\t\t></button\r\n\t\t></span\r\n\t></span\r\n></span>\r\n"),

	attributeMap: dojo.delegate(dijit.form._FormWidget.prototype.attributeMap, {
		label: { node: "containerNode", type: "innerHTML" },
		iconClass: { node: "iconNode", type: "class" }
	}),


	_onClick: function(/*Event*/ e){
		// summary:
		//		Internal function to handle click actions
		if(this.disabled){
			return false;
		}
		this._clicked(); // widget click actions
		return this.onClick(e); // user click actions
	},

	_onButtonClick: function(/*Event*/ e){
		// summary:
		//		Handler when the user activates the button portion.
		if(this._onClick(e) === false){ // returning nothing is same as true
			e.preventDefault(); // needed for checkbox
		}else if(this.type == "submit" && !this.focusNode.form){ // see if a nonform widget needs to be signalled
			for(var node=this.domNode; node.parentNode/*#5935*/; node=node.parentNode){
				var widget=dijit.byNode(node);
				if(widget && typeof widget._onSubmit == "function"){
					widget._onSubmit(e);
					break;
				}
			}
		}
	},

	_setValueAttr: function(/*String*/ value){
		// Verify that value cannot be set for BUTTON elements.
		var attr = this.attributeMap.value || '';
		if(this[attr.node || attr || 'domNode'].tagName == 'BUTTON'){
			// On IE, setting value actually overrides innerHTML, so disallow for everyone for consistency
			if(value != this.value){
				console.debug('Cannot change the value attribute on a Button widget.');
			}
		}
	},

	_fillContent: function(/*DomNode*/ source){
		// Overrides _Templated._fillContent().
		// If button label is specified as srcNodeRef.innerHTML rather than
		// this.params.label, handle it here.
		if(source && (!this.params || !("label" in this.params))){
			this.attr('label', source.innerHTML);
		}
	},

	postCreate: function(){
		dojo.setSelectable(this.focusNode, false);
		this.inherited(arguments);
	},

	_setShowLabelAttr: function(val){
		if(this.containerNode){
			dojo.toggleClass(this.containerNode, "dijitDisplayNone", !val);
		}
		this.showLabel = val;
	},

	onClick: function(/*Event*/ e){
		// summary:
		//		Callback for when button is clicked.
		//		If type="submit", return true to perform submit, or false to cancel it.
		// type:
		//		callback
		return true;		// Boolean
	},

	_clicked: function(/*Event*/ e){
		// summary:
		//		Internal overridable function for when the button is clicked
	},

	setLabel: function(/*String*/ content){
		// summary:
		//		Deprecated.  Use attr('label', ...) instead.
		dojo.deprecated("dijit.form.Button.setLabel() is deprecated.  Use attr('label', ...) instead.", "", "2.0");
		this.attr("label", content);
	},
	_setLabelAttr: function(/*String*/ content){
		// summary:
		//		Hook for attr('label', ...) to work.
		// description:
		//		Set the label (text) of the button; takes an HTML string.
		this.containerNode.innerHTML = this.label = content;
		if(this.showLabel == false && !this.params.title){
			this.titleNode.title = dojo.trim(this.containerNode.innerText || this.containerNode.textContent || '');
		}
	}
});


dojo.declare("dijit.form.DropDownButton", [dijit.form.Button, dijit._Container, dijit._HasDropDown], {
	// summary:
	//		A button with a drop down
	//
	// example:
	// |	<button dojoType="dijit.form.DropDownButton" label="Hello world">
	// |		<div dojotype="dijit.Menu">...</div>
	// |	</button>
	//
	// example:
	// |	var button1 = new dijit.form.DropDownButton({ label: "hi", dropDown: new dijit.Menu(...) });
	// |	dojo.body().appendChild(button1);
	//

	baseClass : "dijitDropDownButton",

	templateString: dojo.cache("dijit.form", "templates/DropDownButton.html", "<span class=\"dijit dijitReset dijitLeft dijitInline\"\r\n\tdojoAttachPoint=\"_buttonNode\"\r\n\tdojoAttachEvent=\"onmouseenter:_onMouse,onmouseleave:_onMouse,onmousedown:_onMouse\"\r\n\t><span class='dijitReset dijitRight dijitInline'\r\n\t\t><span class='dijitReset dijitInline dijitButtonNode'\r\n\t\t\t><button class=\"dijitReset dijitStretch dijitButtonContents\"\r\n\t\t\t\t${nameAttrSetting} type=\"${type}\" value=\"${value}\"\r\n\t\t\t\tdojoAttachPoint=\"focusNode,titleNode,_arrowWrapperNode\"\r\n\t\t\t\twaiRole=\"button\" waiState=\"haspopup-true,labelledby-${id}_label\"\r\n\t\t\t\t><span class=\"dijitReset dijitInline\"\r\n\t\t\t\t\tdojoAttachPoint=\"iconNode\"\r\n\t\t\t\t></span\r\n\t\t\t\t><span class=\"dijitReset dijitInline dijitButtonText\"\r\n\t\t\t\t\tdojoAttachPoint=\"containerNode,_popupStateNode\"\r\n\t\t\t\t\tid=\"${id}_label\"\r\n\t\t\t\t></span\r\n\t\t\t\t><span class=\"dijitReset dijitInline dijitArrowButtonInner\">&thinsp;</span\r\n\t\t\t\t><span class=\"dijitReset dijitInline dijitArrowButtonChar\">&#9660;</span\r\n\t\t\t></button\r\n\t\t></span\r\n\t></span\r\n></span>\r\n"),

	_fillContent: function(){
		// Overrides Button._fillContent().
		//
		// My inner HTML contains both the button contents and a drop down widget, like
		// <DropDownButton>  <span>push me</span>  <Menu> ... </Menu> </DropDownButton>
		// The first node is assumed to be the button content. The widget is the popup.

		if(this.srcNodeRef){ // programatically created buttons might not define srcNodeRef
			//FIXME: figure out how to filter out the widget and use all remaining nodes as button
			//	content, not just nodes[0]
			var nodes = dojo.query("*", this.srcNodeRef);
			dijit.form.DropDownButton.superclass._fillContent.call(this, nodes[0]);

			// save pointer to srcNode so we can grab the drop down widget after it's instantiated
			this.dropDownContainer = this.srcNodeRef;
		}
	},

	startup: function(){
		if(this._started){ return; }

		// the child widget from srcNodeRef is the dropdown widget.  Insert it in the page DOM,
		// make it invisible, and store a reference to pass to the popup code.
		if(!this.dropDown){
			var dropDownNode = dojo.query("[widgetId]", this.dropDownContainer)[0];
			this.dropDown = dijit.byNode(dropDownNode);
			delete this.dropDownContainer;
		}
		dijit.popup.moveOffScreen(this.dropDown.domNode);

		this.inherited(arguments);
	},

	isLoaded: function(){
		// Returns whether or not we are loaded - if our dropdown has an href,
		// then we want to check that.
		var dropDown = this.dropDown;
		return (!dropDown.href || dropDown.isLoaded);
	},

	loadDropDown: function(){
		// Loads our dropdown
		var dropDown = this.dropDown;
		if(!dropDown){ return; }
		if(!this.isLoaded()){
			var handler = dojo.connect(dropDown, "onLoad", this, function(){
				dojo.disconnect(handler);
				this.openDropDown();
			});
			dropDown.refresh();
		}else{
			this.openDropDown();
		}
	},

	isFocusable: function(){
		// Overridden so that focus is handled by the _HasDropDown mixin, not by
		// the _FormWidget mixin.
		return this.inherited(arguments) && !this._mouseDown;
	}
});

dojo.declare("dijit.form.ComboButton", dijit.form.DropDownButton, {
	// summary:
	//		A combination button and drop-down button.
	//		Users can click one side to "press" the button, or click an arrow
	//		icon to display the drop down.
	//
	// example:
	// |	<button dojoType="dijit.form.ComboButton" onClick="...">
	// |		<span>Hello world</span>
	// |		<div dojoType="dijit.Menu">...</div>
	// |	</button>
	//
	// example:
	// |	var button1 = new dijit.form.ComboButton({label: "hello world", onClick: foo, dropDown: "myMenu"});
	// |	dojo.body().appendChild(button1.domNode);
	//

	templateString: dojo.cache("dijit.form", "templates/ComboButton.html", "<table class='dijit dijitReset dijitInline dijitLeft'\r\n\tcellspacing='0' cellpadding='0' waiRole=\"presentation\"\r\n\t><tbody waiRole=\"presentation\"><tr waiRole=\"presentation\"\r\n\t\t><td class=\"dijitReset dijitStretch dijitButtonNode\"><button id=\"${id}_button\" class=\"dijitReset dijitButtonContents\"\r\n\t\t\tdojoAttachEvent=\"onclick:_onButtonClick,onmouseenter:_onMouse,onmouseleave:_onMouse,onmousedown:_onMouse,onkeypress:_onButtonKeyPress\"  dojoAttachPoint=\"titleNode\"\r\n\t\t\twaiRole=\"button\" waiState=\"labelledby-${id}_label\"\r\n\t\t\t><div class=\"dijitReset dijitInline\" dojoAttachPoint=\"iconNode\" waiRole=\"presentation\"></div\r\n\t\t\t><div class=\"dijitReset dijitInline dijitButtonText\" id=\"${id}_label\" dojoAttachPoint=\"containerNode\" waiRole=\"presentation\"></div\r\n\t\t></button></td\r\n\t\t><td id=\"${id}_arrow\" class='dijitReset dijitRight dijitButtonNode dijitArrowButton'\r\n\t\t\tdojoAttachPoint=\"_popupStateNode,focusNode,_buttonNode\"\r\n\t\t\tdojoAttachEvent=\"onmouseenter:_onMouse,onmouseleave:_onMouse,onkeypress:_onArrowKeyPress\"\r\n\t\t\tstateModifier=\"DownArrow\"\r\n\t\t\ttitle=\"${optionsTitle}\" ${nameAttrSetting}\r\n\t\t\twaiRole=\"button\" waiState=\"haspopup-true\"\r\n\t\t\t><div class=\"dijitReset dijitArrowButtonInner\" waiRole=\"presentation\">&thinsp;</div\r\n\t\t\t><div class=\"dijitReset dijitArrowButtonChar\" waiRole=\"presentation\">&#9660;</div\r\n\t\t></td\r\n\t></tr></tbody\r\n></table>\r\n"),

	attributeMap: dojo.mixin(dojo.clone(dijit.form.Button.prototype.attributeMap), {
		id: "",
		tabIndex: ["focusNode", "titleNode"],
		title: "titleNode"
	}),

	// optionsTitle: String
	//		Text that describes the options menu (accessibility)
	optionsTitle: "",

	baseClass: "dijitComboButton",

	_focusedNode: null,

	postCreate: function(){
		this.inherited(arguments);
		this._focalNodes = [this.titleNode, this._popupStateNode];
		var isIE = dojo.isIE;
		dojo.forEach(this._focalNodes, dojo.hitch(this, function(node){
			this.connect(node, isIE? "onactivate" : "onfocus", this._onNodeFocus);
			this.connect(node, isIE? "ondeactivate" : "onblur", this._onNodeBlur);
		}));
		if(isIE && (isIE < 8 || dojo.isQuirks)){ // fixed in IE8/strict
			with(this.titleNode){ // resize BUTTON tag so parent TD won't inherit extra padding
				style.width = scrollWidth + "px";
				this.connect(this.titleNode, "onresize", function(){
					setTimeout( function(){ style.width = scrollWidth + "px"; }, 0);
				});
			}
		}
	},

	_onNodeFocus: function(evt){
		this._focusedNode = evt.currentTarget;
		var fnc = this._focusedNode == this.focusNode ? "dijitDownArrowButtonFocused" : "dijitButtonContentsFocused";
		dojo.addClass(this._focusedNode, fnc);
	},

	_onNodeBlur: function(evt){
		var fnc = evt.currentTarget == this.focusNode ? "dijitDownArrowButtonFocused" : "dijitButtonContentsFocused";
		dojo.removeClass(evt.currentTarget, fnc);
	},

	_onBlur: function(){
		this.inherited(arguments);
		this._focusedNode = null;
	},
	
	_onButtonKeyPress: function(/*Event*/ evt){
		// summary:
		//		Handler for right arrow key when focus is on left part of button
		if(evt.charOrCode == dojo.keys[this.isLeftToRight() ? "RIGHT_ARROW" : "LEFT_ARROW"]){
			dijit.focus(this._popupStateNode);
			dojo.stopEvent(evt);
		}
	},

	_onArrowKeyPress: function(/*Event*/ evt){
		// summary:
		//		Handler for left arrow key when focus is on right part of button
		if(evt.charOrCode == dojo.keys[this.isLeftToRight() ? "LEFT_ARROW" : "RIGHT_ARROW"]){
			dijit.focus(this.titleNode);
			dojo.stopEvent(evt);
		}
	},
	
	focus: function(/*String*/ position){
		// summary:
		//		Focuses this widget to according to position, if specified,
		//		otherwise on arrow node
		// position:
		//		"start" or "end"
		
		dijit.focus(position == "start" ? this.titleNode : this._popupStateNode);
	}
});

dojo.declare("dijit.form.ToggleButton", dijit.form.Button, {
	// summary:
	//		A button that can be in two states (checked or not).
	//		Can be base class for things like tabs or checkbox or radio buttons

	baseClass: "dijitToggleButton",

	// checked: Boolean
	//		Corresponds to the native HTML <input> element's attribute.
	//		In markup, specified as "checked='checked'" or just "checked".
	//		True if the button is depressed, or the checkbox is checked,
	//		or the radio button is selected, etc.
	checked: false,

	attributeMap: dojo.mixin(dojo.clone(dijit.form.Button.prototype.attributeMap), {
		checked:"focusNode"
	}),

	_clicked: function(/*Event*/ evt){
		this.attr('checked', !this.checked);
	},

	_setCheckedAttr: function(/*Boolean*/ value){
		this.checked = value;
		dojo.attr(this.focusNode || this.domNode, "checked", value);
		dijit.setWaiState(this.focusNode || this.domNode, "pressed", value);
		this._setStateClass();
		this._handleOnChange(value, true);
	},

	setChecked: function(/*Boolean*/ checked){
		// summary:
		//		Deprecated.   Use attr('checked', true/false) instead.
		dojo.deprecated("setChecked("+checked+") is deprecated. Use attr('checked',"+checked+") instead.", "", "2.0");
		this.attr('checked', checked);
	},

	reset: function(){
		// summary:
		//		Reset the widget's value to what it was at initialization time

		this._hasBeenBlurred = false;

		// set checked state to original setting
		this.attr('checked', this.params.checked || false);
	}
});

}

if(!dojo._hasResource["dojo.i18n"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojo.i18n"] = true;
dojo.provide("dojo.i18n");

/*=====
dojo.i18n = {
	// summary: Utility classes to enable loading of resources for internationalization (i18n)
};
=====*/

dojo.i18n.getLocalization = function(/*String*/packageName, /*String*/bundleName, /*String?*/locale){
	//	summary:
	//		Returns an Object containing the localization for a given resource
	//		bundle in a package, matching the specified locale.
	//	description:
	//		Returns a hash containing name/value pairs in its prototypesuch
	//		that values can be easily overridden.  Throws an exception if the
	//		bundle is not found.  Bundle must have already been loaded by
	//		`dojo.requireLocalization()` or by a build optimization step.  NOTE:
	//		try not to call this method as part of an object property
	//		definition (`var foo = { bar: dojo.i18n.getLocalization() }`).  In
	//		some loading situations, the bundle may not be available in time
	//		for the object definition.  Instead, call this method inside a
	//		function that is run after all modules load or the page loads (like
	//		in `dojo.addOnLoad()`), or in a widget lifecycle method.
	//	packageName:
	//		package which is associated with this resource
	//	bundleName:
	//		the base filename of the resource bundle (without the ".js" suffix)
	//	locale:
	//		the variant to load (optional).  By default, the locale defined by
	//		the host environment: dojo.locale

	locale = dojo.i18n.normalizeLocale(locale);

	// look for nearest locale match
	var elements = locale.split('-');
	var module = [packageName,"nls",bundleName].join('.');
	var bundle = dojo._loadedModules[module];
	if(bundle){
		var localization;
		for(var i = elements.length; i > 0; i--){
			var loc = elements.slice(0, i).join('_');
			if(bundle[loc]){
				localization = bundle[loc];
				break;
			}
		}
		if(!localization){
			localization = bundle.ROOT;
		}

		// make a singleton prototype so that the caller won't accidentally change the values globally
		if(localization){
			var clazz = function(){};
			clazz.prototype = localization;
			return new clazz(); // Object
		}
	}

	throw new Error("Bundle not found: " + bundleName + " in " + packageName+" , locale=" + locale);
};

dojo.i18n.normalizeLocale = function(/*String?*/locale){
	//	summary:
	//		Returns canonical form of locale, as used by Dojo.
	//
	//  description:
	//		All variants are case-insensitive and are separated by '-' as specified in [RFC 3066](http://www.ietf.org/rfc/rfc3066.txt).
	//		If no locale is specified, the dojo.locale is returned.  dojo.locale is defined by
	//		the user agent's locale unless overridden by djConfig.

	var result = locale ? locale.toLowerCase() : dojo.locale;
	if(result == "root"){
		result = "ROOT";
	}
	return result; // String
};

dojo.i18n._requireLocalization = function(/*String*/moduleName, /*String*/bundleName, /*String?*/locale, /*String?*/availableFlatLocales){
	//	summary:
	//		See dojo.requireLocalization()
	//	description:
	// 		Called by the bootstrap, but factored out so that it is only
	// 		included in the build when needed.

	var targetLocale = dojo.i18n.normalizeLocale(locale);
 	var bundlePackage = [moduleName, "nls", bundleName].join(".");
	// NOTE: 
	//		When loading these resources, the packaging does not match what is
	//		on disk.  This is an implementation detail, as this is just a
	//		private data structure to hold the loaded resources.  e.g.
	//		`tests/hello/nls/en-us/salutations.js` is loaded as the object
	//		`tests.hello.nls.salutations.en_us={...}` The structure on disk is
	//		intended to be most convenient for developers and translators, but
	//		in memory it is more logical and efficient to store in a different
	//		order.  Locales cannot use dashes, since the resulting path will
	//		not evaluate as valid JS, so we translate them to underscores.
	
	//Find the best-match locale to load if we have available flat locales.
	var bestLocale = "";
	if(availableFlatLocales){
		var flatLocales = availableFlatLocales.split(",");
		for(var i = 0; i < flatLocales.length; i++){
			//Locale must match from start of string.
			//Using ["indexOf"] so customBase builds do not see
			//this as a dojo._base.array dependency.
			if(targetLocale["indexOf"](flatLocales[i]) == 0){
				if(flatLocales[i].length > bestLocale.length){
					bestLocale = flatLocales[i];
				}
			}
		}
		if(!bestLocale){
			bestLocale = "ROOT";
		}		
	}

	//See if the desired locale is already loaded.
	var tempLocale = availableFlatLocales ? bestLocale : targetLocale;
	var bundle = dojo._loadedModules[bundlePackage];
	var localizedBundle = null;
	if(bundle){
		if(dojo.config.localizationComplete && bundle._built){return;}
		var jsLoc = tempLocale.replace(/-/g, '_');
		var translationPackage = bundlePackage+"."+jsLoc;
		localizedBundle = dojo._loadedModules[translationPackage];
	}

	if(!localizedBundle){
		bundle = dojo["provide"](bundlePackage);
		var syms = dojo._getModuleSymbols(moduleName);
		var modpath = syms.concat("nls").join("/");
		var parent;

		dojo.i18n._searchLocalePath(tempLocale, availableFlatLocales, function(loc){
			var jsLoc = loc.replace(/-/g, '_');
			var translationPackage = bundlePackage + "." + jsLoc;
			var loaded = false;
			if(!dojo._loadedModules[translationPackage]){
				// Mark loaded whether it's found or not, so that further load attempts will not be made
				dojo["provide"](translationPackage);
				var module = [modpath];
				if(loc != "ROOT"){module.push(loc);}
				module.push(bundleName);
				var filespec = module.join("/") + '.js';
				loaded = dojo._loadPath(filespec, null, function(hash){
					// Use singleton with prototype to point to parent bundle, then mix-in result from loadPath
					var clazz = function(){};
					clazz.prototype = parent;
					bundle[jsLoc] = new clazz();
					for(var j in hash){ bundle[jsLoc][j] = hash[j]; }
				});
			}else{
				loaded = true;
			}
			if(loaded && bundle[jsLoc]){
				parent = bundle[jsLoc];
			}else{
				bundle[jsLoc] = parent;
			}
			
			if(availableFlatLocales){
				//Stop the locale path searching if we know the availableFlatLocales, since
				//the first call to this function will load the only bundle that is needed.
				return true;
			}
		});
	}

	//Save the best locale bundle as the target locale bundle when we know the
	//the available bundles.
	if(availableFlatLocales && targetLocale != bestLocale){
		bundle[targetLocale.replace(/-/g, '_')] = bundle[bestLocale.replace(/-/g, '_')];
	}
};

(function(){
	// If other locales are used, dojo.requireLocalization should load them as
	// well, by default. 
	// 
	// Override dojo.requireLocalization to do load the default bundle, then
	// iterate through the extraLocale list and load those translations as
	// well, unless a particular locale was requested.

	var extra = dojo.config.extraLocale;
	if(extra){
		if(!extra instanceof Array){
			extra = [extra];
		}

		var req = dojo.i18n._requireLocalization;
		dojo.i18n._requireLocalization = function(m, b, locale, availableFlatLocales){
			req(m,b,locale, availableFlatLocales);
			if(locale){return;}
			for(var i=0; i<extra.length; i++){
				req(m,b,extra[i], availableFlatLocales);
			}
		};
	}
})();

dojo.i18n._searchLocalePath = function(/*String*/locale, /*Boolean*/down, /*Function*/searchFunc){
	//	summary:
	//		A helper method to assist in searching for locale-based resources.
	//		Will iterate through the variants of a particular locale, either up
	//		or down, executing a callback function.  For example, "en-us" and
	//		true will try "en-us" followed by "en" and finally "ROOT".

	locale = dojo.i18n.normalizeLocale(locale);

	var elements = locale.split('-');
	var searchlist = [];
	for(var i = elements.length; i > 0; i--){
		searchlist.push(elements.slice(0, i).join('-'));
	}
	searchlist.push(false);
	if(down){searchlist.reverse();}

	for(var j = searchlist.length - 1; j >= 0; j--){
		var loc = searchlist[j] || "ROOT";
		var stop = searchFunc(loc);
		if(stop){ break; }
	}
};

dojo.i18n._preloadLocalizations = function(/*String*/bundlePrefix, /*Array*/localesGenerated){
	//	summary:
	//		Load built, flattened resource bundles, if available for all
	//		locales used in the page. Only called by built layer files.

	function preload(locale){
		locale = dojo.i18n.normalizeLocale(locale);
		dojo.i18n._searchLocalePath(locale, true, function(loc){
			for(var i=0; i<localesGenerated.length;i++){
				if(localesGenerated[i] == loc){
					dojo["require"](bundlePrefix+"_"+loc);
					return true; // Boolean
				}
			}
			return false; // Boolean
		});
	}
	preload();
	var extra = dojo.config.extraLocale||[];
	for(var i=0; i<extra.length; i++){
		preload(extra[i]);
	}
};

}

if(!dojo._hasResource["dijit.form.TextBox"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit.form.TextBox"] = true;
dojo.provide("dijit.form.TextBox");



dojo.declare(
	"dijit.form.TextBox",
	dijit.form._FormValueWidget,
	{
		// summary:
		//		A base class for textbox form inputs

		// trim: Boolean
		//		Removes leading and trailing whitespace if true.  Default is false.
		trim: false,

		// uppercase: Boolean
		//		Converts all characters to uppercase if true.  Default is false.
		uppercase: false,

		// lowercase: Boolean
		//		Converts all characters to lowercase if true.  Default is false.
		lowercase: false,

		// propercase: Boolean
		//		Converts the first character of each word to uppercase if true.
		propercase: false,

		//	maxLength: String
		//		HTML INPUT tag maxLength declaration.
		maxLength: "",

		//	selectOnClick: [const] Boolean
		//		If true, all text will be selected when focused with mouse
		selectOnClick: false,

		templateString: dojo.cache("dijit.form", "templates/TextBox.html", "<input class=\"dijit dijitReset dijitLeft\" dojoAttachPoint='textbox,focusNode'\r\n\tdojoAttachEvent='onmouseenter:_onMouse,onmouseleave:_onMouse'\r\n\tautocomplete=\"off\" type=\"${type}\" ${nameAttrSetting}\r\n\t/>\r\n"),
		baseClass: "dijitTextBox",

		attributeMap: dojo.delegate(dijit.form._FormValueWidget.prototype.attributeMap, {
			maxLength: "focusNode"
		}),

		_getValueAttr: function(){
			// summary:
			//		Hook so attr('value') works as we like.
			// description:
			//		For `dijit.form.TextBox` this basically returns the value of the <input>.
			//
			//		For `dijit.form.MappedTextBox` subclasses, which have both
			//		a "displayed value" and a separate "submit value",
			//		This treats the "displayed value" as the master value, computing the
			//		submit value from it via this.parse().
			return this.parse(this.attr('displayedValue'), this.constraints);
		},

		_setValueAttr: function(value, /*Boolean?*/ priorityChange, /*String?*/ formattedValue){
			// summary:
			//		Hook so attr('value', ...) works.
			//
			// description:
			//		Sets the value of the widget to "value" which can be of
			//		any type as determined by the widget.
			//
			// value:
			//		The visual element value is also set to a corresponding,
			//		but not necessarily the same, value.
			//
			// formattedValue:
			//		If specified, used to set the visual element value,
			//		otherwise a computed visual value is used.
			//
			// priorityChange:
			//		If true, an onChange event is fired immediately instead of
			//		waiting for the next blur event.

			var filteredValue;
			if(value !== undefined){
				// TODO: this is calling filter() on both the display value and the actual value.
				// I added a comment to the filter() definition about this, but it should be changed.
				filteredValue = this.filter(value);
				if(typeof formattedValue != "string"){
					if(filteredValue !== null && ((typeof filteredValue != "number") || !isNaN(filteredValue))){
						formattedValue = this.filter(this.format(filteredValue, this.constraints));
					}else{ formattedValue = ''; }
				}
			}
			if(formattedValue != null && formattedValue != undefined && ((typeof formattedValue) != "number" || !isNaN(formattedValue)) && this.textbox.value != formattedValue){
				this.textbox.value = formattedValue;
			}
			this.inherited(arguments, [filteredValue, priorityChange]);
		},

		// displayedValue: String
		//		For subclasses like ComboBox where the displayed value
		//		(ex: Kentucky) and the serialized value (ex: KY) are different,
		//		this represents the displayed value.
		//
		//		Setting 'displayedValue' through attr('displayedValue', ...)
		//		updates 'value', and vice-versa.  Othewise 'value' is updated
		//		from 'displayedValue' periodically, like onBlur etc.
		//
		//		TODO: move declaration to MappedTextBox?
		//		Problem is that ComboBox references displayedValue,
		//		for benefit of FilteringSelect.
		displayedValue: "",

		getDisplayedValue: function(){
			// summary:
			//		Deprecated.   Use attr('displayedValue') instead.
			// tags:
			//		deprecated
			dojo.deprecated(this.declaredClass+"::getDisplayedValue() is deprecated. Use attr('displayedValue') instead.", "", "2.0");
			return this.attr('displayedValue');
		},

		_getDisplayedValueAttr: function(){
			// summary:
			//		Hook so attr('displayedValue') works.
			// description:
			//		Returns the displayed value (what the user sees on the screen),
			// 		after filtering (ie, trimming spaces etc.).
			//
			//		For some subclasses of TextBox (like ComboBox), the displayed value
			//		is different from the serialized value that's actually
			//		sent to the server (see dijit.form.ValidationTextBox.serialize)

			return this.filter(this.textbox.value);
		},

		setDisplayedValue: function(/*String*/value){
			// summary:
			//		Deprecated.   Use attr('displayedValue', ...) instead.
			// tags:
			//		deprecated
			dojo.deprecated(this.declaredClass+"::setDisplayedValue() is deprecated. Use attr('displayedValue', ...) instead.", "", "2.0");
			this.attr('displayedValue', value);
		},

		_setDisplayedValueAttr: function(/*String*/value){
			// summary:
			//		Hook so attr('displayedValue', ...) works.
			// description:
			//		Sets the value of the visual element to the string "value".
			//		The widget value is also set to a corresponding,
			//		but not necessarily the same, value.

			if(value === null || value === undefined){ value = '' }
			else if(typeof value != "string"){ value = String(value) }
			this.textbox.value = value;
			this._setValueAttr(this.attr('value'), undefined, value);
		},

		format: function(/* String */ value, /* Object */ constraints){
			// summary:
			//		Replacable function to convert a value to a properly formatted string.
			// tags:
			//		protected extension
			return ((value == null || value == undefined) ? "" : (value.toString ? value.toString() : value));
		},

		parse: function(/* String */ value, /* Object */ constraints){
			// summary:
			//		Replacable function to convert a formatted string to a value
			// tags:
			//		protected extension

			return value;	// String
		},

		_refreshState: function(){
			// summary:
			//		After the user types some characters, etc., this method is
			//		called to check the field for validity etc.  The base method
			//		in `dijit.form.TextBox` does nothing, but subclasses override.
			// tags:
			//		protected
		},

		_onInput: function(e){
			if(e && e.type && /key/i.test(e.type) && e.keyCode){
				switch(e.keyCode){
					case dojo.keys.SHIFT:
					case dojo.keys.ALT:
					case dojo.keys.CTRL:
					case dojo.keys.TAB:
						return;
				}
			}
			if(this.intermediateChanges){
				var _this = this;
				// the setTimeout allows the key to post to the widget input box
				setTimeout(function(){ _this._handleOnChange(_this.attr('value'), false); }, 0);
			}
			this._refreshState();
		},

		postCreate: function(){
			// setting the value here is needed since value="" in the template causes "undefined"
			// and setting in the DOM (instead of the JS object) helps with form reset actions
			this.textbox.setAttribute("value", this.textbox.value); // DOM and JS values shuld be the same
			this.inherited(arguments);
			if(dojo.isMoz || dojo.isOpera){
				this.connect(this.textbox, "oninput", this._onInput);
			}else{
				this.connect(this.textbox, "onkeydown", this._onInput);
				this.connect(this.textbox, "onkeyup", this._onInput);
				this.connect(this.textbox, "onpaste", this._onInput);
				this.connect(this.textbox, "oncut", this._onInput);
			}
		},

		_blankValue: '', // if the textbox is blank, what value should be reported
		filter: function(val){
			// summary:
			//		Auto-corrections (such as trimming) that are applied to textbox
			//		value on blur or form submit.
			// description:
			//		For MappedTextBox subclasses, this is called twice
			// 			- once with the display value
			//			- once the value as set/returned by attr('value', ...)
			//		and attr('value'), ex: a Number for NumberTextBox.
			//
			//		In the latter case it does corrections like converting null to NaN.  In
			//		the former case the NumberTextBox.filter() method calls this.inherited()
			//		to execute standard trimming code in TextBox.filter().
			//
			//		TODO: break this into two methods in 2.0
			//
			// tags:
			//		protected extension
			if(val === null){ return this._blankValue; }
			if(typeof val != "string"){ return val; }
			if(this.trim){
				val = dojo.trim(val);
			}
			if(this.uppercase){
				val = val.toUpperCase();
			}
			if(this.lowercase){
				val = val.toLowerCase();
			}
			if(this.propercase){
				val = val.replace(/[^\s]+/g, function(word){
					return word.substring(0,1).toUpperCase() + word.substring(1);
				});
			}
			return val;
		},

		_setBlurValue: function(){
			this._setValueAttr(this.attr('value'), true);
		},

		_onBlur: function(e){
			if(this.disabled){ return; }
			this._setBlurValue();
			this.inherited(arguments);

			if(this._selectOnClickHandle){
				this.disconnect(this._selectOnClickHandle);
			}
			if(this.selectOnClick && dojo.isMoz){
				this.textbox.selectionStart = this.textbox.selectionEnd = undefined; // clear selection so that the next mouse click doesn't reselect
			}
		},

		_onFocus: function(/*String*/ by){
			if(this.disabled || this.readOnly){ return; }

			// Select all text on focus via click if nothing already selected.
			// Since mouse-up will clear the selection need to defer selection until after mouse-up.
			// Don't do anything on focus by tabbing into the widgetm since there's no associated mouse-up event.
			if(this.selectOnClick && by == "mouse"){
				this._selectOnClickHandle = this.connect(this.domNode, "onmouseup", function(){
					// Only select all text on first click; otherwise users would have no way to clear
					// the selection.
					this.disconnect(this._selectOnClickHandle);

					// Check if the user selected some text manually (mouse-down, mouse-move, mouse-up)
					// and if not, then select all the text
					var textIsNotSelected;
					if(dojo.isIE){
						var range = dojo.doc.selection.createRange();
						var parent = range.parentElement();
						textIsNotSelected = parent == this.textbox && range.text.length == 0;
					}else{
						textIsNotSelected = this.textbox.selectionStart == this.textbox.selectionEnd;
					}
					if(textIsNotSelected){
						dijit.selectInputText(this.textbox);
					}
				});
			}

			this._refreshState();
			this.inherited(arguments);
		},

		reset: function(){
			// Overrides dijit._FormWidget.reset().
			// Additionally resets the displayed textbox value to ''
			this.textbox.value = '';
			this.inherited(arguments);
		}
	}
);

dijit.selectInputText = function(/*DomNode*/element, /*Number?*/ start, /*Number?*/ stop){
	// summary:
	//		Select text in the input element argument, from start (default 0), to stop (default end).

	// TODO: use functions in _editor/selection.js?
	var _window = dojo.global;
	var _document = dojo.doc;
	element = dojo.byId(element);
	if(isNaN(start)){ start = 0; }
	if(isNaN(stop)){ stop = element.value ? element.value.length : 0; }
	dijit.focus(element);
	if(_document["selection"] && dojo.body()["createTextRange"]){ // IE
		if(element.createTextRange){
			var range = element.createTextRange();
			with(range){
				collapse(true);
				moveStart("character", -99999); // move to 0
				moveStart("character", start); // delta from 0 is the correct position
				moveEnd("character", stop-start);
				select();
			}
		}
	}else if(_window["getSelection"]){
		if(element.setSelectionRange){
			element.setSelectionRange(start, stop);
		}
	}
};

}

if(!dojo._hasResource["dijit.Tooltip"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit.Tooltip"] = true;
dojo.provide("dijit.Tooltip");




dojo.declare(
	"dijit._MasterTooltip",
	[dijit._Widget, dijit._Templated],
	{
		// summary:
		//		Internal widget that holds the actual tooltip markup,
		//		which occurs once per page.
		//		Called by Tooltip widgets which are just containers to hold
		//		the markup
		// tags:
		//		protected

		// duration: Integer
		//		Milliseconds to fade in/fade out
		duration: dijit.defaultDuration,

		templateString: dojo.cache("dijit", "templates/Tooltip.html", "<div class=\"dijitTooltip dijitTooltipLeft\" id=\"dojoTooltip\">\r\n\t<div class=\"dijitTooltipContainer dijitTooltipContents\" dojoAttachPoint=\"containerNode\" waiRole='alert'></div>\r\n\t<div class=\"dijitTooltipConnector\"></div>\r\n</div>\r\n"),

		postCreate: function(){
			dojo.body().appendChild(this.domNode);

			this.bgIframe = new dijit.BackgroundIframe(this.domNode);

			// Setup fade-in and fade-out functions.
			this.fadeIn = dojo.fadeIn({ node: this.domNode, duration: this.duration, onEnd: dojo.hitch(this, "_onShow") });
			this.fadeOut = dojo.fadeOut({ node: this.domNode, duration: this.duration, onEnd: dojo.hitch(this, "_onHide") });

		},

		show: function(/*String*/ innerHTML, /*DomNode*/ aroundNode, /*String[]?*/ position){
			// summary:
			//		Display tooltip w/specified contents to right of specified node
			//		(To left if there's no space on the right, or if LTR==right)

			if(this.aroundNode && this.aroundNode === aroundNode){
				return;
			}

			if(this.fadeOut.status() == "playing"){
				// previous tooltip is being hidden; wait until the hide completes then show new one
				this._onDeck=arguments;
				return;
			}
			this.containerNode.innerHTML=innerHTML;

			// Firefox bug. when innerHTML changes to be shorter than previous
			// one, the node size will not be updated until it moves.
			this.domNode.style.top = (this.domNode.offsetTop + 1) + "px";

			var pos = dijit.placeOnScreenAroundElement(this.domNode, aroundNode, dijit.getPopupAroundAlignment((position && position.length) ? position : dijit.Tooltip.defaultPosition, this.isLeftToRight()), dojo.hitch(this, "orient"));

			// show it
			dojo.style(this.domNode, "opacity", 0);
			this.fadeIn.play();
			this.isShowingNow = true;
			this.aroundNode = aroundNode;
		},

		orient: function(/* DomNode */ node, /* String */ aroundCorner, /* String */ tooltipCorner){
			// summary:
			//		Private function to set CSS for tooltip node based on which position it's in.
			//		This is called by the dijit popup code.
			// tags:
			//		protected

			node.className = "dijitTooltip " +
				{
					"BL-TL": "dijitTooltipBelow dijitTooltipABLeft",
					"TL-BL": "dijitTooltipAbove dijitTooltipABLeft",
					"BR-TR": "dijitTooltipBelow dijitTooltipABRight",
					"TR-BR": "dijitTooltipAbove dijitTooltipABRight",
					"BR-BL": "dijitTooltipRight",
					"BL-BR": "dijitTooltipLeft"
				}[aroundCorner + "-" + tooltipCorner];
		},

		_onShow: function(){
			// summary:
			//		Called at end of fade-in operation
			// tags:
			//		protected
			if(dojo.isIE){
				// the arrow won't show up on a node w/an opacity filter
				this.domNode.style.filter="";
			}
		},

		hide: function(aroundNode){
			// summary:
			//		Hide the tooltip
			if(this._onDeck && this._onDeck[1] == aroundNode){
				// this hide request is for a show() that hasn't even started yet;
				// just cancel the pending show()
				this._onDeck=null;
			}else if(this.aroundNode === aroundNode){
				// this hide request is for the currently displayed tooltip
				this.fadeIn.stop();
				this.isShowingNow = false;
				this.aroundNode = null;
				this.fadeOut.play();
			}else{
				// just ignore the call, it's for a tooltip that has already been erased
			}
		},

		_onHide: function(){
			// summary:
			//		Called at end of fade-out operation
			// tags:
			//		protected

			this.domNode.style.cssText="";	// to position offscreen again
			if(this._onDeck){
				// a show request has been queued up; do it now
				this.show.apply(this, this._onDeck);
				this._onDeck=null;
			}
		}

	}
);

dijit.showTooltip = function(/*String*/ innerHTML, /*DomNode*/ aroundNode, /*String[]?*/ position){
	// summary:
	//		Display tooltip w/specified contents in specified position.
	//		See description of dijit.Tooltip.defaultPosition for details on position parameter.
	//		If position is not specified then dijit.Tooltip.defaultPosition is used.
	if(!dijit._masterTT){ dijit._masterTT = new dijit._MasterTooltip(); }
	return dijit._masterTT.show(innerHTML, aroundNode, position);
};

dijit.hideTooltip = function(aroundNode){
	// summary:
	//		Hide the tooltip
	if(!dijit._masterTT){ dijit._masterTT = new dijit._MasterTooltip(); }
	return dijit._masterTT.hide(aroundNode);
};

dojo.declare(
	"dijit.Tooltip",
	dijit._Widget,
	{
		// summary:
		//		Pops up a tooltip (a help message) when you hover over a node.

		// label: String
		//		Text to display in the tooltip.
		//		Specified as innerHTML when creating the widget from markup.
		label: "",

		// showDelay: Integer
		//		Number of milliseconds to wait after hovering over/focusing on the object, before
		//		the tooltip is displayed.
		showDelay: 400,

		// connectId: [const] String[]
		//		Id's of domNodes to attach the tooltip to.
		//		When user hovers over any of the specified dom nodes, the tooltip will appear.
		//
		//		Note: Currently connectId can only be specified on initialization, it cannot
		//		be changed via attr('connectId', ...)
		//
		//		Note: in 2.0 this will be renamed to connectIds for less confusion.
		connectId: [],

		// position: String[]
		//		See description of `dijit.Tooltip.defaultPosition` for details on position parameter.
		position: [],

		constructor: function(){
			// Map id's of nodes I'm connected to to a list of the this.connect() handles
			this._nodeConnectionsById = {};
		},

		_setConnectIdAttr: function(newIds){
			for(var oldId in this._nodeConnectionsById){
				this.removeTarget(oldId);
			}
			dojo.forEach(dojo.isArrayLike(newIds) ? newIds : [newIds], this.addTarget, this);
		},

		_getConnectIdAttr: function(){
			var ary = [];
			for(var id in this._nodeConnectionsById){
				ary.push(id);
			}
			return ary;
		},

		addTarget: function(/*DOMNODE || String*/ id){
			// summary:
			//		Attach tooltip to specified node, if it's not already connected
			var node = dojo.byId(id);
			if(!node){ return; }
			if(node.id in this._nodeConnectionsById){ return; }//Already connected

			this._nodeConnectionsById[node.id] = [
				this.connect(node, "onmouseenter", "_onTargetMouseEnter"),
				this.connect(node, "onmouseleave", "_onTargetMouseLeave"),
				this.connect(node, "onfocus", "_onTargetFocus"),
				this.connect(node, "onblur", "_onTargetBlur")
			];
			if(dojo.isIE && !node.style.zoom){//preserve zoom
				// BiDi workaround
				node.style.zoom = 1;
			}
		},

		removeTarget: function(/*DOMNODE || String*/ node){
			// summary:
			//		Detach tooltip from specified node

			// map from DOMNode back to plain id string
			var id = node.id || node;

			if(id in this._nodeConnectionsById){
				dojo.forEach(this._nodeConnectionsById[id], this.disconnect, this);
				delete this._nodeConnectionsById[id];
			}
		},

		postCreate: function(){
			dojo.addClass(this.domNode,"dijitTooltipData");
		},

		startup: function(){
			this.inherited(arguments);

			// If this tooltip was created in a template, or for some other reason the specified connectId[s]
			// didn't exist during the widget's initialization, then connect now.
			var ids = this.connectId;
			dojo.forEach(dojo.isArrayLike(ids) ? ids : [ids], this.addTarget, this);
		},

		_onTargetMouseEnter: function(/*Event*/ e){
			// summary:
			//		Handler for mouseenter event on the target node
			// tags:
			//		private
			this._onHover(e);
		},

		_onTargetMouseLeave: function(/*Event*/ e){
			// summary:
			//		Handler for mouseleave event on the target node
			// tags:
			//		private
			this._onUnHover(e);
		},

		_onTargetFocus: function(/*Event*/ e){
			// summary:
			//		Handler for focus event on the target node
			// tags:
			//		private

			this._focus = true;
			this._onHover(e);
		},

		_onTargetBlur: function(/*Event*/ e){
			// summary:
			//		Handler for blur event on the target node
			// tags:
			//		private

			this._focus = false;
			this._onUnHover(e);
		},

		_onHover: function(/*Event*/ e){
			// summary:
			//		Despite the name of this method, it actually handles both hover and focus
			//		events on the target node, setting a timer to show the tooltip.
			// tags:
			//		private
			if(!this._showTimer){
				var target = e.target;
				this._showTimer = setTimeout(dojo.hitch(this, function(){this.open(target)}), this.showDelay);
			}
		},

		_onUnHover: function(/*Event*/ e){
			// summary:
			//		Despite the name of this method, it actually handles both mouseleave and blur
			//		events on the target node, hiding the tooltip.
			// tags:
			//		private

			// keep a tooltip open if the associated element still has focus (even though the
			// mouse moved away)
			if(this._focus){ return; }

			if(this._showTimer){
				clearTimeout(this._showTimer);
				delete this._showTimer;
			}
			this.close();
		},

		open: function(/*DomNode*/ target){
 			// summary:
			//		Display the tooltip; usually not called directly.
			// tags:
			//		private

			if(this._showTimer){
				clearTimeout(this._showTimer);
				delete this._showTimer;
			}
			dijit.showTooltip(this.label || this.domNode.innerHTML, target, this.position);

			this._connectNode = target;
			this.onShow(target, this.position);
		},

		close: function(){
			// summary:
			//		Hide the tooltip or cancel timer for show of tooltip
			// tags:
			//		private

			if(this._connectNode){
				// if tooltip is currently shown
				dijit.hideTooltip(this._connectNode);
				delete this._connectNode;
				this.onHide();
			}
			if(this._showTimer){
				// if tooltip is scheduled to be shown (after a brief delay)
				clearTimeout(this._showTimer);
				delete this._showTimer;
			}
		},

		onShow: function(target, position){
			// summary:
			//		Called when the tooltip is shown
			// tags:
			//		callback
		},

		onHide: function(){
			// summary:
			//		Called when the tooltip is hidden
			// tags:
			//		callback
		},

		uninitialize: function(){
			this.close();
			this.inherited(arguments);
		}
	}
);

// dijit.Tooltip.defaultPosition: String[]
//		This variable controls the position of tooltips, if the position is not specified to
//		the Tooltip widget or *TextBox widget itself.  It's an array of strings with the following values:
//
//			* before: places tooltip to the left of the target node/widget, or to the right in
//			  the case of RTL scripts like Hebrew and Arabic
//			* after: places tooltip to the right of the target node/widget, or to the left in
//			  the case of RTL scripts like Hebrew and Arabic
//			* above: tooltip goes above target node
//			* below: tooltip goes below target node
//
//		The list is positions is tried, in order, until a position is found where the tooltip fits
//		within the viewport.
//
//		Be careful setting this parameter.  A value of "above" may work fine until the user scrolls
//		the screen so that there's no room above the target node.   Nodes with drop downs, like
//		DropDownButton or FilteringSelect, are especially problematic, in that you need to be sure
//		that the drop down and tooltip don't overlap, even when the viewport is scrolled so that there
//		is only room below (or above) the target node, but not both.
dijit.Tooltip.defaultPosition = ["after", "before"];

}

if(!dojo._hasResource["dijit.form.ValidationTextBox"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit.form.ValidationTextBox"] = true;
dojo.provide("dijit.form.ValidationTextBox");








/*=====
	dijit.form.ValidationTextBox.__Constraints = function(){
		// locale: String
		//		locale used for validation, picks up value from this widget's lang attribute
		// _flags_: anything
		//		various flags passed to regExpGen function
		this.locale = "";
		this._flags_ = "";
	}
=====*/

dojo.declare(
	"dijit.form.ValidationTextBox",
	dijit.form.TextBox,
	{
		// summary:
		//		Base class for textbox widgets with the ability to validate content of various types and provide user feedback.
		// tags:
		//		protected

		templateString: dojo.cache("dijit.form", "templates/ValidationTextBox.html", "<div class=\"dijit dijitReset dijitInlineTable dijitLeft\"\r\n\tid=\"widget_${id}\"\r\n\tdojoAttachEvent=\"onmouseenter:_onMouse,onmouseleave:_onMouse,onmousedown:_onMouse\" waiRole=\"presentation\"\r\n\t><div style=\"overflow:hidden;\"\r\n\t\t><div class=\"dijitReset dijitValidationIcon\"><br></div\r\n\t\t><div class=\"dijitReset dijitValidationIconText\">&Chi;</div\r\n\t\t><div class=\"dijitReset dijitInputField\"\r\n\t\t\t><input class=\"dijitReset\" dojoAttachPoint='textbox,focusNode' autocomplete=\"off\"\r\n\t\t\t${nameAttrSetting} type='${type}'\r\n\t\t/></div\r\n\t></div\r\n></div>\r\n"),
		baseClass: "dijitTextBox",

		// required: Boolean
		//		User is required to enter data into this field.
		required: false,

		// promptMessage: String
		//		If defined, display this hint string immediately on focus to the textbox, if empty.
		//		Think of this like a tooltip that tells the user what to do, not an error message
		//		that tells the user what they've done wrong.
		//
		//		Message disappears when user starts typing.
		promptMessage: "",

		// invalidMessage: String
		// 		The message to display if value is invalid.
		invalidMessage: "$_unset_$", // read from the message file if not overridden

		// constraints: dijit.form.ValidationTextBox.__Constraints
		//		user-defined object needed to pass parameters to the validator functions
		constraints: {},

		// regExp: [extension protected] String
		//		regular expression string used to validate the input
		//		Do not specify both regExp and regExpGen
		regExp: ".*",

		regExpGen: function(/*dijit.form.ValidationTextBox.__Constraints*/constraints){
			// summary:
			//		Overridable function used to generate regExp when dependent on constraints.
			//		Do not specify both regExp and regExpGen.
			// tags:
			//		extension protected
			return this.regExp; // String
		},

		// state: [readonly] String
		//		Shows current state (ie, validation result) of input (Normal, Warning, or Error)
		state: "",

		// tooltipPosition: String[]
		//		See description of `dijit.Tooltip.defaultPosition` for details on this parameter.
		tooltipPosition: [],

		_setValueAttr: function(){
			// summary:
			//		Hook so attr('value', ...) works.
			this.inherited(arguments);
			this.validate(this._focused);
		},

		validator: function(/*anything*/value, /*dijit.form.ValidationTextBox.__Constraints*/constraints){
			// summary:
			//		Overridable function used to validate the text input against the regular expression.
			// tags:
			//		protected
			return (new RegExp("^(?:" + this.regExpGen(constraints) + ")"+(this.required?"":"?")+"$")).test(value) &&
				(!this.required || !this._isEmpty(value)) &&
				(this._isEmpty(value) || this.parse(value, constraints) !== undefined); // Boolean
		},

		_isValidSubset: function(){
			// summary:
			//		Returns true if the value is either already valid or could be made valid by appending characters.
			//		This is used for validation while the user [may be] still typing.
			return this.textbox.value.search(this._partialre) == 0;
		},

		isValid: function(/*Boolean*/ isFocused){
			// summary:
			//		Tests if value is valid.
			//		Can override with your own routine in a subclass.
			// tags:
			//		protected
			return this.validator(this.textbox.value, this.constraints);
		},

		_isEmpty: function(value){
			// summary:
			//		Checks for whitespace
			return /^\s*$/.test(value); // Boolean
		},

		getErrorMessage: function(/*Boolean*/ isFocused){
			// summary:
			//		Return an error message to show if appropriate
			// tags:
			//		protected
			return this.invalidMessage; // String
		},

		getPromptMessage: function(/*Boolean*/ isFocused){
			// summary:
			//		Return a hint message to show when widget is first focused
			// tags:
			//		protected
			return this.promptMessage; // String
		},

		_maskValidSubsetError: true,
		validate: function(/*Boolean*/ isFocused){
			// summary:
			//		Called by oninit, onblur, and onkeypress.
			// description:
			//		Show missing or invalid messages if appropriate, and highlight textbox field.
			// tags:
			//		protected
			var message = "";
			var isValid = this.disabled || this.isValid(isFocused);
			if(isValid){ this._maskValidSubsetError = true; }
			var isValidSubset = !isValid && isFocused && this._isValidSubset();
			var isEmpty = this._isEmpty(this.textbox.value);
			if(isEmpty){ this._maskValidSubsetError = true; }
			this.state = (isValid || (!this._hasBeenBlurred && isEmpty) || isValidSubset) ? "" : "Error";
			if(this.state == "Error"){ this._maskValidSubsetError = false; }
			this._setStateClass();
			dijit.setWaiState(this.focusNode, "invalid", isValid ? "false" : "true");
			if(isFocused){
				if(isEmpty){
					message = this.getPromptMessage(true);
				}
				if(!message && (this.state == "Error" || (isValidSubset && !this._maskValidSubsetError))){
					message = this.getErrorMessage(true);
				}
			}
			this.displayMessage(message);
			return isValid;
		},

		// _message: String
		//		Currently displayed message
		_message: "",

		displayMessage: function(/*String*/ message){
			// summary:
			//		Overridable method to display validation errors/hints.
			//		By default uses a tooltip.
			// tags:
			//		extension
			if(this._message == message){ return; }
			this._message = message;
			dijit.hideTooltip(this.domNode);
			if(message){
				dijit.showTooltip(message, this.domNode, this.tooltipPosition);
			}
		},

		_refreshState: function(){
			// Overrides TextBox._refreshState()
			this.validate(this._focused);
			this.inherited(arguments);
		},

		//////////// INITIALIZATION METHODS ///////////////////////////////////////

		constructor: function(){
			this.constraints = {};
		},

		postMixInProperties: function(){
			this.inherited(arguments);
			this.constraints.locale = this.lang;
			this.messages = dojo.i18n.getLocalization("dijit.form", "validate", this.lang);
			if(this.invalidMessage == "$_unset_$"){ this.invalidMessage = this.messages.invalidMessage; }
			var p = this.regExpGen(this.constraints);
			this.regExp = p;
			var partialre = "";
			// parse the regexp and produce a new regexp that matches valid subsets
			// if the regexp is .* then there's no use in matching subsets since everything is valid
			if(p != ".*"){ this.regExp.replace(/\\.|\[\]|\[.*?[^\\]{1}\]|\{.*?\}|\(\?[=:!]|./g,
				function (re){
					switch(re.charAt(0)){
						case '{':
						case '+':
						case '?':
						case '*':
						case '^':
						case '$':
						case '|':
						case '(':
							partialre += re;
							break;
						case ")":
							partialre += "|$)";
							break;
						 default:
							partialre += "(?:"+re+"|$)";
							break;
					}
				}
			);}
			try{ // this is needed for now since the above regexp parsing needs more test verification
				"".search(partialre);
			}catch(e){ // should never be here unless the original RE is bad or the parsing is bad
				partialre = this.regExp;
				console.warn('RegExp error in ' + this.declaredClass + ': ' + this.regExp);
			} // should never be here unless the original RE is bad or the parsing is bad
			this._partialre = "^(?:" + partialre + ")$";
		},

		_setDisabledAttr: function(/*Boolean*/ value){
			this.inherited(arguments);	// call FormValueWidget._setDisabledAttr()
			this._refreshState();
		},

		_setRequiredAttr: function(/*Boolean*/ value){
			this.required = value;
			dijit.setWaiState(this.focusNode,"required", value);
			this._refreshState();
		},

		postCreate: function(){
			if(dojo.isIE){ // IE INPUT tag fontFamily has to be set directly using STYLE
				var s = dojo.getComputedStyle(this.focusNode);
				if(s){
					var ff = s.fontFamily;
					if(ff){
						this.focusNode.style.fontFamily = ff;
					}
				}
			}
			this.inherited(arguments);
		},

		reset:function(){
			// Overrides dijit.form.TextBox.reset() by also
			// hiding errors about partial matches
			this._maskValidSubsetError = true;
			this.inherited(arguments);
		},

		_onBlur: function(){
			this.displayMessage('');
			this.inherited(arguments);
		}
	}
);

dojo.declare(
	"dijit.form.MappedTextBox",
	dijit.form.ValidationTextBox,
	{
		// summary:
		//		A dijit.form.ValidationTextBox subclass which provides a base class for widgets that have
		//		a visible formatted display value, and a serializable
		//		value in a hidden input field which is actually sent to the server.
		// description:
		//		The visible display may
		//		be locale-dependent and interactive.  The value sent to the server is stored in a hidden
		//		input field which uses the `name` attribute declared by the original widget.  That value sent
		//		to the server is defined by the dijit.form.MappedTextBox.serialize method and is typically
		//		locale-neutral.
		// tags:
		//		protected

		postMixInProperties: function(){
			this.inherited(arguments);

			// we want the name attribute to go to the hidden <input>, not the displayed <input>,
			// so override _FormWidget.postMixInProperties() setting of nameAttrSetting
			this.nameAttrSetting = "";
		},

		serialize: function(/*anything*/val, /*Object?*/options){
			// summary:
			//		Overridable function used to convert the attr('value') result to a canonical
			//		(non-localized) string.  For example, will print dates in ISO format, and
			//		numbers the same way as they are represented in javascript.
			// tags:
			//		protected extension
			return val.toString ? val.toString() : ""; // String
		},

		toString: function(){
			// summary:
			//		Returns widget as a printable string using the widget's value
			// tags:
			//		protected
			var val = this.filter(this.attr('value')); // call filter in case value is nonstring and filter has been customized
			return val != null ? (typeof val == "string" ? val : this.serialize(val, this.constraints)) : ""; // String
		},

		validate: function(){
			// Overrides `dijit.form.TextBox.validate`
			this.valueNode.value = this.toString();
			return this.inherited(arguments);
		},

		buildRendering: function(){
			// Overrides `dijit._Templated.buildRendering`

			this.inherited(arguments);

			// Create a hidden <input> node with the serialized value used for submit
			// (as opposed to the displayed value).
			// Passing in name as markup rather than calling dojo.create() with an attrs argument
			// to make dojo.query(input[name=...]) work on IE. (see #8660)
			this.valueNode = dojo.place("<input type='hidden'" + (this.name ? " name='" + this.name + "'" : "") + ">", this.textbox, "after");
		},

		reset:function(){
			// Overrides `dijit.form.ValidationTextBox.reset` to
			// reset the hidden textbox value to ''
			this.valueNode.value = '';
			this.inherited(arguments);
		}
	}
);

/*=====
	dijit.form.RangeBoundTextBox.__Constraints = function(){
		// min: Number
		//		Minimum signed value.  Default is -Infinity
		// max: Number
		//		Maximum signed value.  Default is +Infinity
		this.min = min;
		this.max = max;
	}
=====*/

dojo.declare(
	"dijit.form.RangeBoundTextBox",
	dijit.form.MappedTextBox,
	{
		// summary:
		//		Base class for textbox form widgets which defines a range of valid values.

		// rangeMessage: String
		//		The message to display if value is out-of-range
		rangeMessage: "",

		/*=====
		// constraints: dijit.form.RangeBoundTextBox.__Constraints
		constraints: {},
		======*/

		rangeCheck: function(/*Number*/ primitive, /*dijit.form.RangeBoundTextBox.__Constraints*/ constraints){
			// summary:
			//		Overridable function used to validate the range of the numeric input value.
			// tags:
			//		protected
			return	("min" in constraints? (this.compare(primitive,constraints.min) >= 0) : true) &&
				("max" in constraints? (this.compare(primitive,constraints.max) <= 0) : true); // Boolean
		},

		isInRange: function(/*Boolean*/ isFocused){
			// summary:
			//		Tests if the value is in the min/max range specified in constraints
			// tags:
			//		protected
			return this.rangeCheck(this.attr('value'), this.constraints);
		},

		_isDefinitelyOutOfRange: function(){
			// summary:
			//		Returns true if the value is out of range and will remain
			//		out of range even if the user types more characters
			var val = this.attr('value');
			var isTooLittle = false;
			var isTooMuch = false;
			if("min" in this.constraints){
				var min = this.constraints.min;
				min = this.compare(val, ((typeof min == "number") && min >= 0 && val !=0) ? 0 : min);
				isTooLittle = (typeof min == "number") && min < 0;
			}
			if("max" in this.constraints){
				var max = this.constraints.max;
				max = this.compare(val, ((typeof max != "number") || max > 0) ? max : 0);
				isTooMuch = (typeof max == "number") && max > 0;
			}
			return isTooLittle || isTooMuch;
		},

		_isValidSubset: function(){
			// summary:
			//		Overrides `dijit.form.ValidationTextBox._isValidSubset`.
			//		Returns true if the input is syntactically valid, and either within
			//		range or could be made in range by more typing.
			return this.inherited(arguments) && !this._isDefinitelyOutOfRange();
		},

		isValid: function(/*Boolean*/ isFocused){
			// Overrides dijit.form.ValidationTextBox.isValid to check that the value is also in range.
			return this.inherited(arguments) &&
				((this._isEmpty(this.textbox.value) && !this.required) || this.isInRange(isFocused)); // Boolean
		},

		getErrorMessage: function(/*Boolean*/ isFocused){
			// Overrides dijit.form.ValidationTextBox.getErrorMessage to print "out of range" message if appropriate
			var v = this.attr('value');
			if(v !== null && v !== '' && v !== undefined && !this.isInRange(isFocused)){ // don't check isInRange w/o a real value
				return this.rangeMessage; // String
			}
			return this.inherited(arguments);
		},

		postMixInProperties: function(){
			this.inherited(arguments);
			if(!this.rangeMessage){
				this.messages = dojo.i18n.getLocalization("dijit.form", "validate", this.lang);
				this.rangeMessage = this.messages.rangeMessage;
			}
		},

		postCreate: function(){
			this.inherited(arguments);
			if(this.constraints.min !== undefined){
				dijit.setWaiState(this.focusNode, "valuemin", this.constraints.min);
			}
			if(this.constraints.max !== undefined){
				dijit.setWaiState(this.focusNode, "valuemax", this.constraints.max);
			}
		},

		_setValueAttr: function(/*Number*/ value, /*Boolean?*/ priorityChange){
			// summary:
			//		Hook so attr('value', ...) works.

			dijit.setWaiState(this.focusNode, "valuenow", value);
			this.inherited(arguments);
		}
	}
);

}

/*  ===============================================================================
    ------------------------------------login.js-----------------------------------
                                         v 1.0                                    
                                                                               
    Loads dojo elements and handles XHR and miscellaneous form functionality.     
                                                                               
    Created by: Marian C. Buford                                                 
                Conservation Resource Solutions, Inc. (CRS) 
    Created on: 06.10.2010
    License:    Proprietary
    Copyright:  2010 Conservation Resource Solutions, Inc.  All rights reserved.
                                                                               
    =============================================================================== */

/*  ===============================================================================
    DECLARATIONS: dojo elements
    =============================================================================== */
    
    
    

/*  ===============================================================================
    FUNCTION: iEMSToggler()
    ----------------------------------- params ------------------------------------
    targetDiv       dojo object : the dom node where we place our output
    =============================================================================== */
    function iEMSToggler(targetDiv)
    {
        //console.info(targetDiv); for troubleshooting
        if(dojo.style(targetDiv,'display') == 'none') {
            dojo.style(targetDiv,'display','inline');
        }
        else
        {
            dojo.style(targetDiv,'display','none');
        }
    }

if(!dojo._hasResource["dojo.data.util.filter"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojo.data.util.filter"] = true;
dojo.provide("dojo.data.util.filter");

dojo.data.util.filter.patternToRegExp = function(/*String*/pattern, /*boolean?*/ ignoreCase){
	//	summary:  
	//		Helper function to convert a simple pattern to a regular expression for matching.
	//	description:
	//		Returns a regular expression object that conforms to the defined conversion rules.
	//		For example:  
	//			ca*   -> /^ca.*$/
	//			*ca*  -> /^.*ca.*$/
	//			*c\*a*  -> /^.*c\*a.*$/
	//			*c\*a?*  -> /^.*c\*a..*$/
	//			and so on.
	//
	//	pattern: string
	//		A simple matching pattern to convert that follows basic rules:
	//			* Means match anything, so ca* means match anything starting with ca
	//			? Means match single character.  So, b?b will match to bob and bab, and so on.
	//      	\ is an escape character.  So for example, \* means do not treat * as a match, but literal character *.
	//				To use a \ as a character in the string, it must be escaped.  So in the pattern it should be 
	//				represented by \\ to be treated as an ordinary \ character instead of an escape.
	//
	//	ignoreCase:
	//		An optional flag to indicate if the pattern matching should be treated as case-sensitive or not when comparing
	//		By default, it is assumed case sensitive.

	var rxp = "^";
	var c = null;
	for(var i = 0; i < pattern.length; i++){
		c = pattern.charAt(i);
		switch(c){
			case '\\':
				rxp += c;
				i++;
				rxp += pattern.charAt(i);
				break;
			case '*':
				rxp += ".*"; break;
			case '?':
				rxp += "."; break;
			case '$':
			case '^':
			case '/':
			case '+':
			case '.':
			case '|':
			case '(':
			case ')':
			case '{':
			case '}':
			case '[':
			case ']':
				rxp += "\\"; //fallthrough
			default:
				rxp += c;
		}
	}
	rxp += "$";
	if(ignoreCase){
		return new RegExp(rxp,"mi"); //RegExp
	}else{
		return new RegExp(rxp,"m"); //RegExp
	}
	
};

}

if(!dojo._hasResource["dojo.data.util.sorter"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojo.data.util.sorter"] = true;
dojo.provide("dojo.data.util.sorter");

dojo.data.util.sorter.basicComparator = function(	/*anything*/ a, 
													/*anything*/ b){
	//	summary:  
	//		Basic comparision function that compares if an item is greater or less than another item
	//	description:  
	//		returns 1 if a > b, -1 if a < b, 0 if equal.
	//		'null' values (null, undefined) are treated as larger values so that they're pushed to the end of the list.
	//		And compared to each other, null is equivalent to undefined.
	
	//null is a problematic compare, so if null, we set to undefined.
	//Makes the check logic simple, compact, and consistent
	//And (null == undefined) === true, so the check later against null
	//works for undefined and is less bytes.
	var r = -1;
	if(a === null){
		a = undefined;
	}
	if(b === null){
		b = undefined;
	}
	if(a == b){
		r = 0; 
	}else if(a > b || a == null){
		r = 1; 
	}
	return r; //int {-1,0,1}
};

dojo.data.util.sorter.createSortFunction = function(	/* attributes array */sortSpec,
														/*dojo.data.core.Read*/ store){
	//	summary:  
	//		Helper function to generate the sorting function based off the list of sort attributes.
	//	description:  
	//		The sort function creation will look for a property on the store called 'comparatorMap'.  If it exists
	//		it will look in the mapping for comparisons function for the attributes.  If one is found, it will
	//		use it instead of the basic comparator, which is typically used for strings, ints, booleans, and dates.
	//		Returns the sorting function for this particular list of attributes and sorting directions.
	//
	//	sortSpec: array
	//		A JS object that array that defines out what attribute names to sort on and whether it should be descenting or asending.
	//		The objects should be formatted as follows:
	//		{
	//			attribute: "attributeName-string" || attribute,
	//			descending: true|false;   // Default is false.
	//		}
	//	store: object
	//		The datastore object to look up item values from.
	//
	var sortFunctions=[];

	function createSortFunction(attr, dir, comp, s){
		//Passing in comp and s (comparator and store), makes this
		//function much faster.
		return function(itemA, itemB){
			var a = s.getValue(itemA, attr);
			var b = s.getValue(itemB, attr);
			return dir * comp(a,b); //int
		};
	}
	var sortAttribute;
	var map = store.comparatorMap;
	var bc = dojo.data.util.sorter.basicComparator;
	for(var i = 0; i < sortSpec.length; i++){
		sortAttribute = sortSpec[i];
		var attr = sortAttribute.attribute;
		if(attr){
			var dir = (sortAttribute.descending) ? -1 : 1;
			var comp = bc;
			if(map){
				if(typeof attr !== "string" && ("toString" in attr)){
					 attr = attr.toString();
				}
				comp = map[attr] || bc;
			}
			sortFunctions.push(createSortFunction(attr, 
				dir, comp, store));
		}
	}
	return function(rowA, rowB){
		var i=0;
		while(i < sortFunctions.length){
			var ret = sortFunctions[i++](rowA, rowB);
			if(ret !== 0){
				return ret;//int
			}
		}
		return 0; //int  
	}; // Function
};

}

if(!dojo._hasResource["dojo.data.util.simpleFetch"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojo.data.util.simpleFetch"] = true;
dojo.provide("dojo.data.util.simpleFetch");


dojo.data.util.simpleFetch.fetch = function(/* Object? */ request){
	//	summary:
	//		The simpleFetch mixin is designed to serve as a set of function(s) that can
	//		be mixed into other datastore implementations to accelerate their development.  
	//		The simpleFetch mixin should work well for any datastore that can respond to a _fetchItems() 
	//		call by returning an array of all the found items that matched the query.  The simpleFetch mixin
	//		is not designed to work for datastores that respond to a fetch() call by incrementally
	//		loading items, or sequentially loading partial batches of the result
	//		set.  For datastores that mixin simpleFetch, simpleFetch 
	//		implements a fetch method that automatically handles eight of the fetch()
	//		arguments -- onBegin, onItem, onComplete, onError, start, count, sort and scope
	//		The class mixing in simpleFetch should not implement fetch(),
	//		but should instead implement a _fetchItems() method.  The _fetchItems() 
	//		method takes three arguments, the keywordArgs object that was passed 
	//		to fetch(), a callback function to be called when the result array is
	//		available, and an error callback to be called if something goes wrong.
	//		The _fetchItems() method should ignore any keywordArgs parameters for
	//		start, count, onBegin, onItem, onComplete, onError, sort, and scope.  
	//		The _fetchItems() method needs to correctly handle any other keywordArgs
	//		parameters, including the query parameter and any optional parameters 
	//		(such as includeChildren).  The _fetchItems() method should create an array of 
	//		result items and pass it to the fetchHandler along with the original request object 
	//		-- or, the _fetchItems() method may, if it wants to, create an new request object 
	//		with other specifics about the request that are specific to the datastore and pass 
	//		that as the request object to the handler.
	//
	//		For more information on this specific function, see dojo.data.api.Read.fetch()
	request = request || {};
	if(!request.store){
		request.store = this;
	}
	var self = this;

	var _errorHandler = function(errorData, requestObject){
		if(requestObject.onError){
			var scope = requestObject.scope || dojo.global;
			requestObject.onError.call(scope, errorData, requestObject);
		}
	};

	var _fetchHandler = function(items, requestObject){
		var oldAbortFunction = requestObject.abort || null;
		var aborted = false;

		var startIndex = requestObject.start?requestObject.start:0;
		var endIndex = (requestObject.count && (requestObject.count !== Infinity))?(startIndex + requestObject.count):items.length;

		requestObject.abort = function(){
			aborted = true;
			if(oldAbortFunction){
				oldAbortFunction.call(requestObject);
			}
		};

		var scope = requestObject.scope || dojo.global;
		if(!requestObject.store){
			requestObject.store = self;
		}
		if(requestObject.onBegin){
			requestObject.onBegin.call(scope, items.length, requestObject);
		}
		if(requestObject.sort){
			items.sort(dojo.data.util.sorter.createSortFunction(requestObject.sort, self));
		}
		if(requestObject.onItem){
			for(var i = startIndex; (i < items.length) && (i < endIndex); ++i){
				var item = items[i];
				if(!aborted){
					requestObject.onItem.call(scope, item, requestObject);
				}
			}
		}
		if(requestObject.onComplete && !aborted){
			var subset = null;
			if(!requestObject.onItem){
				subset = items.slice(startIndex, endIndex);
			}
			requestObject.onComplete.call(scope, subset, requestObject);
		}
	};
	this._fetchItems(request, _fetchHandler, _errorHandler);
	return request;	// Object
};

}

if(!dojo._hasResource["dojo.data.ItemFileReadStore"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojo.data.ItemFileReadStore"] = true;
dojo.provide("dojo.data.ItemFileReadStore");





dojo.declare("dojo.data.ItemFileReadStore", null,{
	//	summary:
	//		The ItemFileReadStore implements the dojo.data.api.Read API and reads
	//		data from JSON files that have contents in this format --
	//		{ items: [
	//			{ name:'Kermit', color:'green', age:12, friends:['Gonzo', {_reference:{name:'Fozzie Bear'}}]},
	//			{ name:'Fozzie Bear', wears:['hat', 'tie']},
	//			{ name:'Miss Piggy', pets:'Foo-Foo'}
	//		]}
	//		Note that it can also contain an 'identifer' property that specified which attribute on the items 
	//		in the array of items that acts as the unique identifier for that item.
	//
	constructor: function(/* Object */ keywordParameters){
		//	summary: constructor
		//	keywordParameters: {url: String}
		//	keywordParameters: {data: jsonObject}
		//	keywordParameters: {typeMap: object)
		//		The structure of the typeMap object is as follows:
		//		{
		//			type0: function || object,
		//			type1: function || object,
		//			...
		//			typeN: function || object
		//		}
		//		Where if it is a function, it is assumed to be an object constructor that takes the 
		//		value of _value as the initialization parameters.  If it is an object, then it is assumed
		//		to be an object of general form:
		//		{
		//			type: function, //constructor.
		//			deserialize:	function(value) //The function that parses the value and constructs the object defined by type appropriately.
		//		}
	
		this._arrayOfAllItems = [];
		this._arrayOfTopLevelItems = [];
		this._loadFinished = false;
		this._jsonFileUrl = keywordParameters.url;
		this._ccUrl = keywordParameters.url;
		this.url = keywordParameters.url;
		this._jsonData = keywordParameters.data;
		this.data = null;
		this._datatypeMap = keywordParameters.typeMap || {};
		if(!this._datatypeMap['Date']){
			//If no default mapping for dates, then set this as default.
			//We use the dojo.date.stamp here because the ISO format is the 'dojo way'
			//of generically representing dates.
			this._datatypeMap['Date'] = {
											type: Date,
											deserialize: function(value){
												return dojo.date.stamp.fromISOString(value);
											}
										};
		}
		this._features = {'dojo.data.api.Read':true, 'dojo.data.api.Identity':true};
		this._itemsByIdentity = null;
		this._storeRefPropName = "_S"; // Default name for the store reference to attach to every item.
		this._itemNumPropName = "_0"; // Default Item Id for isItem to attach to every item.
		this._rootItemPropName = "_RI"; // Default Item Id for isItem to attach to every item.
		this._reverseRefMap = "_RRM"; // Default attribute for constructing a reverse reference map for use with reference integrity
		this._loadInProgress = false; //Got to track the initial load to prevent duelling loads of the dataset.
		this._queuedFetches = [];
		if(keywordParameters.urlPreventCache !== undefined){
			this.urlPreventCache = keywordParameters.urlPreventCache?true:false;
		}
		if(keywordParameters.hierarchical !== undefined){
			this.hierarchical = keywordParameters.hierarchical?true:false;
		}
		if(keywordParameters.clearOnClose){
			this.clearOnClose = true;
		}
		if("failOk" in keywordParameters){
			this.failOk = keywordParameters.failOk?true:false;
		}
	},
	
	url: "",	// use "" rather than undefined for the benefit of the parser (#3539)

	//Internal var, crossCheckUrl.  Used so that setting either url or _jsonFileUrl, can still trigger a reload
	//when clearOnClose and close is used.
	_ccUrl: "",

	data: null,	// define this so that the parser can populate it

	typeMap: null, //Define so parser can populate.
	
	//Parameter to allow users to specify if a close call should force a reload or not.
	//By default, it retains the old behavior of not clearing if close is called.  But
	//if set true, the store will be reset to default state.  Note that by doing this,
	//all item handles will become invalid and a new fetch must be issued.
	clearOnClose: false,

	//Parameter to allow specifying if preventCache should be passed to the xhrGet call or not when loading data from a url.  
	//Note this does not mean the store calls the server on each fetch, only that the data load has preventCache set as an option.
	//Added for tracker: #6072
	urlPreventCache: false,
	
	//Parameter for specifying that it is OK for the xhrGet call to fail silently.
	failOk: false,

	//Parameter to indicate to process data from the url as hierarchical 
	//(data items can contain other data items in js form).  Default is true 
	//for backwards compatibility.  False means only root items are processed 
	//as items, all child objects outside of type-mapped objects and those in 
	//specific reference format, are left straight JS data objects.
	hierarchical: true,

	_assertIsItem: function(/* item */ item){
		//	summary:
		//		This function tests whether the item passed in is indeed an item in the store.
		//	item: 
		//		The item to test for being contained by the store.
		if(!this.isItem(item)){ 
			throw new Error("dojo.data.ItemFileReadStore: Invalid item argument.");
		}
	},

	_assertIsAttribute: function(/* attribute-name-string */ attribute){
		//	summary:
		//		This function tests whether the item passed in is indeed a valid 'attribute' like type for the store.
		//	attribute: 
		//		The attribute to test for being contained by the store.
		if(typeof attribute !== "string"){ 
			throw new Error("dojo.data.ItemFileReadStore: Invalid attribute argument.");
		}
	},

	getValue: function(	/* item */ item, 
						/* attribute-name-string */ attribute, 
						/* value? */ defaultValue){
		//	summary: 
		//		See dojo.data.api.Read.getValue()
		var values = this.getValues(item, attribute);
		return (values.length > 0)?values[0]:defaultValue; // mixed
	},

	getValues: function(/* item */ item, 
						/* attribute-name-string */ attribute){
		//	summary: 
		//		See dojo.data.api.Read.getValues()

		this._assertIsItem(item);
		this._assertIsAttribute(attribute);
		return item[attribute] || []; // Array
	},

	getAttributes: function(/* item */ item){
		//	summary: 
		//		See dojo.data.api.Read.getAttributes()
		this._assertIsItem(item);
		var attributes = [];
		for(var key in item){
			// Save off only the real item attributes, not the special id marks for O(1) isItem.
			if((key !== this._storeRefPropName) && (key !== this._itemNumPropName) && (key !== this._rootItemPropName) && (key !== this._reverseRefMap)){
				attributes.push(key);
			}
		}
		return attributes; // Array
	},

	hasAttribute: function(	/* item */ item,
							/* attribute-name-string */ attribute){
		//	summary: 
		//		See dojo.data.api.Read.hasAttribute()
		this._assertIsItem(item);
		this._assertIsAttribute(attribute);
		return (attribute in item);
	},

	containsValue: function(/* item */ item, 
							/* attribute-name-string */ attribute, 
							/* anything */ value){
		//	summary: 
		//		See dojo.data.api.Read.containsValue()
		var regexp = undefined;
		if(typeof value === "string"){
			regexp = dojo.data.util.filter.patternToRegExp(value, false);
		}
		return this._containsValue(item, attribute, value, regexp); //boolean.
	},

	_containsValue: function(	/* item */ item, 
								/* attribute-name-string */ attribute, 
								/* anything */ value,
								/* RegExp?*/ regexp){
		//	summary: 
		//		Internal function for looking at the values contained by the item.
		//	description: 
		//		Internal function for looking at the values contained by the item.  This 
		//		function allows for denoting if the comparison should be case sensitive for
		//		strings or not (for handling filtering cases where string case should not matter)
		//	
		//	item:
		//		The data item to examine for attribute values.
		//	attribute:
		//		The attribute to inspect.
		//	value:	
		//		The value to match.
		//	regexp:
		//		Optional regular expression generated off value if value was of string type to handle wildcarding.
		//		If present and attribute values are string, then it can be used for comparison instead of 'value'
		return dojo.some(this.getValues(item, attribute), function(possibleValue){
			if(possibleValue !== null && !dojo.isObject(possibleValue) && regexp){
				if(possibleValue.toString().match(regexp)){
					return true; // Boolean
				}
			}else if(value === possibleValue){
				return true; // Boolean
			}
		});
	},

	isItem: function(/* anything */ something){
		//	summary: 
		//		See dojo.data.api.Read.isItem()
		if(something && something[this._storeRefPropName] === this){
			if(this._arrayOfAllItems[something[this._itemNumPropName]] === something){
				return true;
			}
		}
		return false; // Boolean
	},

	isItemLoaded: function(/* anything */ something){
		//	summary: 
		//		See dojo.data.api.Read.isItemLoaded()
		return this.isItem(something); //boolean
	},

	loadItem: function(/* object */ keywordArgs){
		//	summary: 
		//		See dojo.data.api.Read.loadItem()
		this._assertIsItem(keywordArgs.item);
	},

	getFeatures: function(){
		//	summary: 
		//		See dojo.data.api.Read.getFeatures()
		return this._features; //Object
	},

	getLabel: function(/* item */ item){
		//	summary: 
		//		See dojo.data.api.Read.getLabel()
		if(this._labelAttr && this.isItem(item)){
			return this.getValue(item,this._labelAttr); //String
		}
		return undefined; //undefined
	},

	getLabelAttributes: function(/* item */ item){
		//	summary: 
		//		See dojo.data.api.Read.getLabelAttributes()
		if(this._labelAttr){
			return [this._labelAttr]; //array
		}
		return null; //null
	},

	_fetchItems: function(	/* Object */ keywordArgs, 
							/* Function */ findCallback, 
							/* Function */ errorCallback){
		//	summary: 
		//		See dojo.data.util.simpleFetch.fetch()
		var self = this;
		var filter = function(requestArgs, arrayOfItems){
			var items = [];
			var i, key;
			if(requestArgs.query){
				var value;
				var ignoreCase = requestArgs.queryOptions ? requestArgs.queryOptions.ignoreCase : false; 

				//See if there are any string values that can be regexp parsed first to avoid multiple regexp gens on the
				//same value for each item examined.  Much more efficient.
				var regexpList = {};
				for(key in requestArgs.query){
					value = requestArgs.query[key];
					if(typeof value === "string"){
						regexpList[key] = dojo.data.util.filter.patternToRegExp(value, ignoreCase);
					}else if(value instanceof RegExp){
						regexpList[key] = value;
					}
				}
				for(i = 0; i < arrayOfItems.length; ++i){
					var match = true;
					var candidateItem = arrayOfItems[i];
					if(candidateItem === null){
						match = false;
					}else{
						for(key in requestArgs.query){
							value = requestArgs.query[key];
							if(!self._containsValue(candidateItem, key, value, regexpList[key])){
								match = false;
							}
						}
					}
					if(match){
						items.push(candidateItem);
					}
				}
				findCallback(items, requestArgs);
			}else{
				// We want a copy to pass back in case the parent wishes to sort the array. 
				// We shouldn't allow resort of the internal list, so that multiple callers 
				// can get lists and sort without affecting each other.  We also need to
				// filter out any null values that have been left as a result of deleteItem()
				// calls in ItemFileWriteStore.
				for(i = 0; i < arrayOfItems.length; ++i){
					var item = arrayOfItems[i];
					if(item !== null){
						items.push(item);
					}
				}
				findCallback(items, requestArgs);
			}
		};

		if(this._loadFinished){
			filter(keywordArgs, this._getItemsArray(keywordArgs.queryOptions));
		}else{
			//Do a check on the JsonFileUrl and crosscheck it.
			//If it doesn't match the cross-check, it needs to be updated
			//This allows for either url or _jsonFileUrl to he changed to
			//reset the store load location.  Done this way for backwards 
			//compatibility.  People use _jsonFileUrl (even though officially
			//private.
			if(this._jsonFileUrl !== this._ccUrl){
				dojo.deprecated("dojo.data.ItemFileReadStore: ", 
					"To change the url, set the url property of the store," +
					" not _jsonFileUrl.  _jsonFileUrl support will be removed in 2.0");
				this._ccUrl = this._jsonFileUrl;
				this.url = this._jsonFileUrl;
			}else if(this.url !== this._ccUrl){
				this._jsonFileUrl = this.url;
				this._ccUrl = this.url;
			}

			//See if there was any forced reset of data.
			if(this.data != null && this._jsonData == null){
				this._jsonData = this.data;
				this.data = null;
			}

			if(this._jsonFileUrl){
				//If fetches come in before the loading has finished, but while
				//a load is in progress, we have to defer the fetching to be 
				//invoked in the callback.
				if(this._loadInProgress){
					this._queuedFetches.push({args: keywordArgs, filter: filter});
				}else{
					this._loadInProgress = true;
					var getArgs = {
							url: self._jsonFileUrl, 
							handleAs: "json-comment-optional",
							preventCache: this.urlPreventCache,
							failOk: this.failOk
						};
					var getHandler = dojo.xhrGet(getArgs);
					getHandler.addCallback(function(data){
						try{
							self._getItemsFromLoadedData(data);
							self._loadFinished = true;
							self._loadInProgress = false;
							
							filter(keywordArgs, self._getItemsArray(keywordArgs.queryOptions));
							self._handleQueuedFetches();
						}catch(e){
							self._loadFinished = true;
							self._loadInProgress = false;
							errorCallback(e, keywordArgs);
						}
					});
					getHandler.addErrback(function(error){
						self._loadInProgress = false;
						errorCallback(error, keywordArgs);
					});

					//Wire up the cancel to abort of the request
					//This call cancel on the deferred if it hasn't been called
					//yet and then will chain to the simple abort of the
					//simpleFetch keywordArgs
					var oldAbort = null;
					if(keywordArgs.abort){
						oldAbort = keywordArgs.abort;
					}
					keywordArgs.abort = function(){
						var df = getHandler;
						if(df && df.fired === -1){
							df.cancel();
							df = null;
						}
						if(oldAbort){
							oldAbort.call(keywordArgs);
						}
					};
				}
			}else if(this._jsonData){
				try{
					this._loadFinished = true;
					this._getItemsFromLoadedData(this._jsonData);
					this._jsonData = null;
					filter(keywordArgs, this._getItemsArray(keywordArgs.queryOptions));
				}catch(e){
					errorCallback(e, keywordArgs);
				}
			}else{
				errorCallback(new Error("dojo.data.ItemFileReadStore: No JSON source data was provided as either URL or a nested Javascript object."), keywordArgs);
			}
		}
	},

	_handleQueuedFetches: function(){
		//	summary: 
		//		Internal function to execute delayed request in the store.
		//Execute any deferred fetches now.
		if(this._queuedFetches.length > 0){
			for(var i = 0; i < this._queuedFetches.length; i++){
				var fData = this._queuedFetches[i];
				var delayedQuery = fData.args;
				var delayedFilter = fData.filter;
				if(delayedFilter){
					delayedFilter(delayedQuery, this._getItemsArray(delayedQuery.queryOptions)); 
				}else{
					this.fetchItemByIdentity(delayedQuery);
				}
			}
			this._queuedFetches = [];
		}
	},

	_getItemsArray: function(/*object?*/queryOptions){
		//	summary: 
		//		Internal function to determine which list of items to search over.
		//	queryOptions: The query options parameter, if any.
		if(queryOptions && queryOptions.deep){
			return this._arrayOfAllItems; 
		}
		return this._arrayOfTopLevelItems;
	},

	close: function(/*dojo.data.api.Request || keywordArgs || null */ request){
		 //	summary: 
		 //		See dojo.data.api.Read.close()
		 if(this.clearOnClose && 
			this._loadFinished && 
			!this._loadInProgress){
			 //Reset all internalsback to default state.  This will force a reload
			 //on next fetch.  This also checks that the data or url param was set 
			 //so that the store knows it can get data.  Without one of those being set,
			 //the next fetch will trigger an error.

			 if(((this._jsonFileUrl == "" || this._jsonFileUrl == null) && 
				 (this.url == "" || this.url == null)
				) && this.data == null){
				 console.debug("dojo.data.ItemFileReadStore: WARNING!  Data reload " +
					" information has not been provided." + 
					"  Please set 'url' or 'data' to the appropriate value before" +
					" the next fetch");
			 }
			 this._arrayOfAllItems = [];
			 this._arrayOfTopLevelItems = [];
			 this._loadFinished = false;
			 this._itemsByIdentity = null;
			 this._loadInProgress = false;
			 this._queuedFetches = [];
		 }
	},

	_getItemsFromLoadedData: function(/* Object */ dataObject){
		//	summary:
		//		Function to parse the loaded data into item format and build the internal items array.
		//	description:
		//		Function to parse the loaded data into item format and build the internal items array.
		//
		//	dataObject:
		//		The JS data object containing the raw data to convery into item format.
		//
		// 	returns: array
		//		Array of items in store item format.
		
		// First, we define a couple little utility functions...
		var addingArrays = false;
		var self = this;
		
		function valueIsAnItem(/* anything */ aValue){
			// summary:
			//		Given any sort of value that could be in the raw json data,
			//		return true if we should interpret the value as being an
			//		item itself, rather than a literal value or a reference.
			// example:
			// 	|	false == valueIsAnItem("Kermit");
			// 	|	false == valueIsAnItem(42);
			// 	|	false == valueIsAnItem(new Date());
			// 	|	false == valueIsAnItem({_type:'Date', _value:'May 14, 1802'});
			// 	|	false == valueIsAnItem({_reference:'Kermit'});
			// 	|	true == valueIsAnItem({name:'Kermit', color:'green'});
			// 	|	true == valueIsAnItem({iggy:'pop'});
			// 	|	true == valueIsAnItem({foo:42});
			var isItem = (
				(aValue !== null) &&
				(typeof aValue === "object") &&
				(!dojo.isArray(aValue) || addingArrays) &&
				(!dojo.isFunction(aValue)) &&
				(aValue.constructor == Object || dojo.isArray(aValue)) &&
				(typeof aValue._reference === "undefined") && 
				(typeof aValue._type === "undefined") && 
				(typeof aValue._value === "undefined") &&
				self.hierarchical
			);
			return isItem;
		}
		
		function addItemAndSubItemsToArrayOfAllItems(/* Item */ anItem){
			self._arrayOfAllItems.push(anItem);
			for(var attribute in anItem){
				var valueForAttribute = anItem[attribute];
				if(valueForAttribute){
					if(dojo.isArray(valueForAttribute)){
						var valueArray = valueForAttribute;
						for(var k = 0; k < valueArray.length; ++k){
							var singleValue = valueArray[k];
							if(valueIsAnItem(singleValue)){
								addItemAndSubItemsToArrayOfAllItems(singleValue);
							}
						}
					}else{
						if(valueIsAnItem(valueForAttribute)){
							addItemAndSubItemsToArrayOfAllItems(valueForAttribute);
						}
					}
				}
			}
		}

		this._labelAttr = dataObject.label;

		// We need to do some transformations to convert the data structure
		// that we read from the file into a format that will be convenient
		// to work with in memory.

		// Step 1: Walk through the object hierarchy and build a list of all items
		var i;
		var item;
		this._arrayOfAllItems = [];
		this._arrayOfTopLevelItems = dataObject.items;

		for(i = 0; i < this._arrayOfTopLevelItems.length; ++i){
			item = this._arrayOfTopLevelItems[i];
			if(dojo.isArray(item)){
				addingArrays = true;
			}
			addItemAndSubItemsToArrayOfAllItems(item);
			item[this._rootItemPropName]=true;
		}

		// Step 2: Walk through all the attribute values of all the items, 
		// and replace single values with arrays.  For example, we change this:
		//		{ name:'Miss Piggy', pets:'Foo-Foo'}
		// into this:
		//		{ name:['Miss Piggy'], pets:['Foo-Foo']}
		// 
		// We also store the attribute names so we can validate our store  
		// reference and item id special properties for the O(1) isItem
		var allAttributeNames = {};
		var key;

		for(i = 0; i < this._arrayOfAllItems.length; ++i){
			item = this._arrayOfAllItems[i];
			for(key in item){
				if(key !== this._rootItemPropName){
					var value = item[key];
					if(value !== null){
						if(!dojo.isArray(value)){
							item[key] = [value];
						}
					}else{
						item[key] = [null];
					}
				}
				allAttributeNames[key]=key;
			}
		}

		// Step 3: Build unique property names to use for the _storeRefPropName and _itemNumPropName
		// This should go really fast, it will generally never even run the loop.
		while(allAttributeNames[this._storeRefPropName]){
			this._storeRefPropName += "_";
		}
		while(allAttributeNames[this._itemNumPropName]){
			this._itemNumPropName += "_";
		}
		while(allAttributeNames[this._reverseRefMap]){
			this._reverseRefMap += "_";
		}

		// Step 4: Some data files specify an optional 'identifier', which is 
		// the name of an attribute that holds the identity of each item. 
		// If this data file specified an identifier attribute, then build a 
		// hash table of items keyed by the identity of the items.
		var arrayOfValues;

		var identifier = dataObject.identifier;
		if(identifier){
			this._itemsByIdentity = {};
			this._features['dojo.data.api.Identity'] = identifier;
			for(i = 0; i < this._arrayOfAllItems.length; ++i){
				item = this._arrayOfAllItems[i];
				arrayOfValues = item[identifier];
				var identity = arrayOfValues[0];
				if(!this._itemsByIdentity[identity]){
					this._itemsByIdentity[identity] = item;
				}else{
					if(this._jsonFileUrl){
						throw new Error("dojo.data.ItemFileReadStore:  The json data as specified by: [" + this._jsonFileUrl + "] is malformed.  Items within the list have identifier: [" + identifier + "].  Value collided: [" + identity + "]");
					}else if(this._jsonData){
						throw new Error("dojo.data.ItemFileReadStore:  The json data provided by the creation arguments is malformed.  Items within the list have identifier: [" + identifier + "].  Value collided: [" + identity + "]");
					}
				}
			}
		}else{
			this._features['dojo.data.api.Identity'] = Number;
		}

		// Step 5: Walk through all the items, and set each item's properties 
		// for _storeRefPropName and _itemNumPropName, so that store.isItem() will return true.
		for(i = 0; i < this._arrayOfAllItems.length; ++i){
			item = this._arrayOfAllItems[i];
			item[this._storeRefPropName] = this;
			item[this._itemNumPropName] = i;
		}

		// Step 6: We walk through all the attribute values of all the items,
		// looking for type/value literals and item-references.
		//
		// We replace item-references with pointers to items.  For example, we change:
		//		{ name:['Kermit'], friends:[{_reference:{name:'Miss Piggy'}}] }
		// into this:
		//		{ name:['Kermit'], friends:[miss_piggy] } 
		// (where miss_piggy is the object representing the 'Miss Piggy' item).
		//
		// We replace type/value pairs with typed-literals.  For example, we change:
		//		{ name:['Nelson Mandela'], born:[{_type:'Date', _value:'July 18, 1918'}] }
		// into this:
		//		{ name:['Kermit'], born:(new Date('July 18, 1918')) } 
		//
		// We also generate the associate map for all items for the O(1) isItem function.
		for(i = 0; i < this._arrayOfAllItems.length; ++i){
			item = this._arrayOfAllItems[i]; // example: { name:['Kermit'], friends:[{_reference:{name:'Miss Piggy'}}] }
			for(key in item){
				arrayOfValues = item[key]; // example: [{_reference:{name:'Miss Piggy'}}]
				for(var j = 0; j < arrayOfValues.length; ++j){
					value = arrayOfValues[j]; // example: {_reference:{name:'Miss Piggy'}}
					if(value !== null && typeof value == "object"){
						if(("_type" in value) && ("_value" in value)){
							var type = value._type; // examples: 'Date', 'Color', or 'ComplexNumber'
							var mappingObj = this._datatypeMap[type]; // examples: Date, dojo.Color, foo.math.ComplexNumber, {type: dojo.Color, deserialize(value){ return new dojo.Color(value)}}
							if(!mappingObj){ 
								throw new Error("dojo.data.ItemFileReadStore: in the typeMap constructor arg, no object class was specified for the datatype '" + type + "'");
							}else if(dojo.isFunction(mappingObj)){
								arrayOfValues[j] = new mappingObj(value._value);
							}else if(dojo.isFunction(mappingObj.deserialize)){
								arrayOfValues[j] = mappingObj.deserialize(value._value);
							}else{
								throw new Error("dojo.data.ItemFileReadStore: Value provided in typeMap was neither a constructor, nor a an object with a deserialize function");
							}
						}
						if(value._reference){
							var referenceDescription = value._reference; // example: {name:'Miss Piggy'}
							if(!dojo.isObject(referenceDescription)){
								// example: 'Miss Piggy'
								// from an item like: { name:['Kermit'], friends:[{_reference:'Miss Piggy'}]}
								arrayOfValues[j] = this._itemsByIdentity[referenceDescription];
							}else{
								// example: {name:'Miss Piggy'}
								// from an item like: { name:['Kermit'], friends:[{_reference:{name:'Miss Piggy'}}] }
								for(var k = 0; k < this._arrayOfAllItems.length; ++k){
									var candidateItem = this._arrayOfAllItems[k];
									var found = true;
									for(var refKey in referenceDescription){
										if(candidateItem[refKey] != referenceDescription[refKey]){ 
											found = false; 
										}
									}
									if(found){ 
										arrayOfValues[j] = candidateItem; 
									}
								}
							}
							if(this.referenceIntegrity){
								var refItem = arrayOfValues[j];
								if(this.isItem(refItem)){
									this._addReferenceToMap(refItem, item, key);
								}
							}
						}else if(this.isItem(value)){
							//It's a child item (not one referenced through _reference).  
							//We need to treat this as a referenced item, so it can be cleaned up
							//in a write store easily.
							if(this.referenceIntegrity){
								this._addReferenceToMap(value, item, key);
							}
						}
					}
				}
			}
		}
	},

	_addReferenceToMap: function(/*item*/ refItem, /*item*/ parentItem, /*string*/ attribute){
		 //	summary:
		 //		Method to add an reference map entry for an item and attribute.
		 //	description:
		 //		Method to add an reference map entry for an item and attribute. 		 //
		 //	refItem:
		 //		The item that is referenced.
		 //	parentItem:
		 //		The item that holds the new reference to refItem.
		 //	attribute:
		 //		The attribute on parentItem that contains the new reference.
		 
		 //Stub function, does nothing.  Real processing is in ItemFileWriteStore.
	},

	getIdentity: function(/* item */ item){
		//	summary: 
		//		See dojo.data.api.Identity.getIdentity()
		var identifier = this._features['dojo.data.api.Identity'];
		if(identifier === Number){
			return item[this._itemNumPropName]; // Number
		}else{
			var arrayOfValues = item[identifier];
			if(arrayOfValues){
				return arrayOfValues[0]; // Object || String
			}
		}
		return null; // null
	},

	fetchItemByIdentity: function(/* Object */ keywordArgs){
		//	summary: 
		//		See dojo.data.api.Identity.fetchItemByIdentity()

		// Hasn't loaded yet, we have to trigger the load.
		var item;
		var scope;
		if(!this._loadFinished){
			var self = this;
			//Do a check on the JsonFileUrl and crosscheck it.
			//If it doesn't match the cross-check, it needs to be updated
			//This allows for either url or _jsonFileUrl to he changed to
			//reset the store load location.  Done this way for backwards 
			//compatibility.  People use _jsonFileUrl (even though officially
			//private.
			if(this._jsonFileUrl !== this._ccUrl){
				dojo.deprecated("dojo.data.ItemFileReadStore: ", 
					"To change the url, set the url property of the store," +
					" not _jsonFileUrl.  _jsonFileUrl support will be removed in 2.0");
				this._ccUrl = this._jsonFileUrl;
				this.url = this._jsonFileUrl;
			}else if(this.url !== this._ccUrl){
				this._jsonFileUrl = this.url;
				this._ccUrl = this.url;
			}
			
			//See if there was any forced reset of data.
			if(this.data != null && this._jsonData == null){
				this._jsonData = this.data;
				this.data = null;
			}

			if(this._jsonFileUrl){

				if(this._loadInProgress){
					this._queuedFetches.push({args: keywordArgs});
				}else{
					this._loadInProgress = true;
					var getArgs = {
							url: self._jsonFileUrl, 
							handleAs: "json-comment-optional",
							preventCache: this.urlPreventCache,
							failOk: this.failOk
					};
					var getHandler = dojo.xhrGet(getArgs);
					getHandler.addCallback(function(data){
						var scope = keywordArgs.scope?keywordArgs.scope:dojo.global;
						try{
							self._getItemsFromLoadedData(data);
							self._loadFinished = true;
							self._loadInProgress = false;
							item = self._getItemByIdentity(keywordArgs.identity);
							if(keywordArgs.onItem){
								keywordArgs.onItem.call(scope, item);
							}
							self._handleQueuedFetches();
						}catch(error){
							self._loadInProgress = false;
							if(keywordArgs.onError){
								keywordArgs.onError.call(scope, error);
							}
						}
					});
					getHandler.addErrback(function(error){
						self._loadInProgress = false;
						if(keywordArgs.onError){
							var scope = keywordArgs.scope?keywordArgs.scope:dojo.global;
							keywordArgs.onError.call(scope, error);
						}
					});
				}

			}else if(this._jsonData){
				// Passed in data, no need to xhr.
				self._getItemsFromLoadedData(self._jsonData);
				self._jsonData = null;
				self._loadFinished = true;
				item = self._getItemByIdentity(keywordArgs.identity);
				if(keywordArgs.onItem){
					scope = keywordArgs.scope?keywordArgs.scope:dojo.global;
					keywordArgs.onItem.call(scope, item);
				}
			} 
		}else{
			// Already loaded.  We can just look it up and call back.
			item = this._getItemByIdentity(keywordArgs.identity);
			if(keywordArgs.onItem){
				scope = keywordArgs.scope?keywordArgs.scope:dojo.global;
				keywordArgs.onItem.call(scope, item);
			}
		}
	},

	_getItemByIdentity: function(/* Object */ identity){
		//	summary:
		//		Internal function to look an item up by its identity map.
		var item = null;
		if(this._itemsByIdentity){
			item = this._itemsByIdentity[identity];
		}else{
			item = this._arrayOfAllItems[identity];
		}
		if(item === undefined){
			item = null;
		}
		return item; // Object
	},

	getIdentityAttributes: function(/* item */ item){
		//	summary: 
		//		See dojo.data.api.Identity.getIdentifierAttributes()
		 
		var identifier = this._features['dojo.data.api.Identity'];
		if(identifier === Number){
			// If (identifier === Number) it means getIdentity() just returns
			// an integer item-number for each item.  The dojo.data.api.Identity
			// spec says we need to return null if the identity is not composed 
			// of attributes 
			return null; // null
		}else{
			return [identifier]; // Array
		}
	},
	
	_forceLoad: function(){
		//	summary: 
		//		Internal function to force a load of the store if it hasn't occurred yet.  This is required
		//		for specific functions to work properly.  
		var self = this;
		//Do a check on the JsonFileUrl and crosscheck it.
		//If it doesn't match the cross-check, it needs to be updated
		//This allows for either url or _jsonFileUrl to he changed to
		//reset the store load location.  Done this way for backwards 
		//compatibility.  People use _jsonFileUrl (even though officially
		//private.
		if(this._jsonFileUrl !== this._ccUrl){
			dojo.deprecated("dojo.data.ItemFileReadStore: ", 
				"To change the url, set the url property of the store," +
				" not _jsonFileUrl.  _jsonFileUrl support will be removed in 2.0");
			this._ccUrl = this._jsonFileUrl;
			this.url = this._jsonFileUrl;
		}else if(this.url !== this._ccUrl){
			this._jsonFileUrl = this.url;
			this._ccUrl = this.url;
		}

		//See if there was any forced reset of data.
		if(this.data != null && this._jsonData == null){
			this._jsonData = this.data;
			this.data = null;
		}

		if(this._jsonFileUrl){
				var getArgs = {
					url: this._jsonFileUrl, 
					handleAs: "json-comment-optional",
					preventCache: this.urlPreventCache,
					failOk: this.failOk,
					sync: true
				};
			var getHandler = dojo.xhrGet(getArgs);
			getHandler.addCallback(function(data){
				try{
					//Check to be sure there wasn't another load going on concurrently 
					//So we don't clobber data that comes in on it.  If there is a load going on
					//then do not save this data.  It will potentially clobber current data.
					//We mainly wanted to sync/wait here.
					//TODO:  Revisit the loading scheme of this store to improve multi-initial
					//request handling.
					if(self._loadInProgress !== true && !self._loadFinished){
						self._getItemsFromLoadedData(data);
						self._loadFinished = true;
					}else if(self._loadInProgress){
						//Okay, we hit an error state we can't recover from.  A forced load occurred
						//while an async load was occurring.  Since we cannot block at this point, the best
						//that can be managed is to throw an error.
						throw new Error("dojo.data.ItemFileReadStore:  Unable to perform a synchronous load, an async load is in progress."); 
					}
				}catch(e){
					console.log(e);
					throw e;
				}
			});
			getHandler.addErrback(function(error){
				throw error;
			});
		}else if(this._jsonData){
			self._getItemsFromLoadedData(self._jsonData);
			self._jsonData = null;
			self._loadFinished = true;
		} 
	}
});
//Mix in the simple fetch implementation to this class.
dojo.extend(dojo.data.ItemFileReadStore,dojo.data.util.simpleFetch);

}

if(!dojo._hasResource["dojo.data.ItemFileWriteStore"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojo.data.ItemFileWriteStore"] = true;
dojo.provide("dojo.data.ItemFileWriteStore");


dojo.declare("dojo.data.ItemFileWriteStore", dojo.data.ItemFileReadStore, {
	constructor: function(/* object */ keywordParameters){
		//	keywordParameters: {typeMap: object)
		//		The structure of the typeMap object is as follows:
		//		{
		//			type0: function || object,
		//			type1: function || object,
		//			...
		//			typeN: function || object
		//		}
		//		Where if it is a function, it is assumed to be an object constructor that takes the 
		//		value of _value as the initialization parameters.  It is serialized assuming object.toString()
		//		serialization.  If it is an object, then it is assumed
		//		to be an object of general form:
		//		{
		//			type: function, //constructor.
		//			deserialize:	function(value) //The function that parses the value and constructs the object defined by type appropriately.
		//			serialize:	function(object) //The function that converts the object back into the proper file format form.
		//		}

		// ItemFileWriteStore extends ItemFileReadStore to implement these additional dojo.data APIs
		this._features['dojo.data.api.Write'] = true;
		this._features['dojo.data.api.Notification'] = true;
		
		// For keeping track of changes so that we can implement isDirty and revert
		this._pending = {
			_newItems:{}, 
			_modifiedItems:{}, 
			_deletedItems:{}
		};

		if(!this._datatypeMap['Date'].serialize){
			this._datatypeMap['Date'].serialize = function(obj){
				return dojo.date.stamp.toISOString(obj, {zulu:true});
			};
		}
		//Disable only if explicitly set to false.
		if(keywordParameters && (keywordParameters.referenceIntegrity === false)){
			this.referenceIntegrity = false;
		}

		// this._saveInProgress is set to true, briefly, from when save() is first called to when it completes
		this._saveInProgress = false;
	},

	referenceIntegrity: true, //Flag that defaultly enabled reference integrity tracking.  This way it can also be disabled pogrammatially or declaratively.

	_assert: function(/* boolean */ condition){
		if(!condition){
			throw new Error("assertion failed in ItemFileWriteStore");
		}
	},

	_getIdentifierAttribute: function(){
		var identifierAttribute = this.getFeatures()['dojo.data.api.Identity'];
		// this._assert((identifierAttribute === Number) || (dojo.isString(identifierAttribute)));
		return identifierAttribute;
	},
	
	
/* dojo.data.api.Write */

	newItem: function(/* Object? */ keywordArgs, /* Object? */ parentInfo){
		// summary: See dojo.data.api.Write.newItem()

		this._assert(!this._saveInProgress);

		if(!this._loadFinished){
			// We need to do this here so that we'll be able to find out what
			// identifierAttribute was specified in the data file.
			this._forceLoad();
		}

		if(typeof keywordArgs != "object" && typeof keywordArgs != "undefined"){
			throw new Error("newItem() was passed something other than an object");
		}
		var newIdentity = null;
		var identifierAttribute = this._getIdentifierAttribute();
		if(identifierAttribute === Number){
			newIdentity = this._arrayOfAllItems.length;
		}else{
			newIdentity = keywordArgs[identifierAttribute];
			if(typeof newIdentity === "undefined"){
				throw new Error("newItem() was not passed an identity for the new item");
			}
			if(dojo.isArray(newIdentity)){
				throw new Error("newItem() was not passed an single-valued identity");
			}
		}
		
		// make sure this identity is not already in use by another item, if identifiers were 
		// defined in the file.  Otherwise it would be the item count, 
		// which should always be unique in this case.
		if(this._itemsByIdentity){
			this._assert(typeof this._itemsByIdentity[newIdentity] === "undefined");
		}
		this._assert(typeof this._pending._newItems[newIdentity] === "undefined");
		this._assert(typeof this._pending._deletedItems[newIdentity] === "undefined");
		
		var newItem = {};
		newItem[this._storeRefPropName] = this;		
		newItem[this._itemNumPropName] = this._arrayOfAllItems.length;
		if(this._itemsByIdentity){
			this._itemsByIdentity[newIdentity] = newItem;
			//We have to set the identifier now, otherwise we can't look it
			//up at calls to setValueorValues in parentInfo handling.
			newItem[identifierAttribute] = [newIdentity];
		}
		this._arrayOfAllItems.push(newItem);

		//We need to construct some data for the onNew call too...
		var pInfo = null;
		
		// Now we need to check to see where we want to assign this thingm if any.
		if(parentInfo && parentInfo.parent && parentInfo.attribute){
			pInfo = {
				item: parentInfo.parent,
				attribute: parentInfo.attribute,
				oldValue: undefined
			};

			//See if it is multi-valued or not and handle appropriately
			//Generally, all attributes are multi-valued for this store
			//So, we only need to append if there are already values present.
			var values = this.getValues(parentInfo.parent, parentInfo.attribute);
			if(values && values.length > 0){
				var tempValues = values.slice(0, values.length);
				if(values.length === 1){
					pInfo.oldValue = values[0];
				}else{
					pInfo.oldValue = values.slice(0, values.length);
				}
				tempValues.push(newItem);
				this._setValueOrValues(parentInfo.parent, parentInfo.attribute, tempValues, false);
				pInfo.newValue = this.getValues(parentInfo.parent, parentInfo.attribute);
			}else{
				this._setValueOrValues(parentInfo.parent, parentInfo.attribute, newItem, false);
				pInfo.newValue = newItem;
			}
		}else{
			//Toplevel item, add to both top list as well as all list.
			newItem[this._rootItemPropName]=true;
			this._arrayOfTopLevelItems.push(newItem);
		}
		
		this._pending._newItems[newIdentity] = newItem;
		
		//Clone over the properties to the new item
		for(var key in keywordArgs){
			if(key === this._storeRefPropName || key === this._itemNumPropName){
				// Bummer, the user is trying to do something like
				// newItem({_S:"foo"}).  Unfortunately, our superclass,
				// ItemFileReadStore, is already using _S in each of our items
				// to hold private info.  To avoid a naming collision, we 
				// need to move all our private info to some other property 
				// of all the items/objects.  So, we need to iterate over all
				// the items and do something like: 
				//    item.__S = item._S;
				//    item._S = undefined;
				// But first we have to make sure the new "__S" variable is 
				// not in use, which means we have to iterate over all the 
				// items checking for that.
				throw new Error("encountered bug in ItemFileWriteStore.newItem");
			}
			var value = keywordArgs[key];
			if(!dojo.isArray(value)){
				value = [value];
			}
			newItem[key] = value;
			if(this.referenceIntegrity){
				for(var i = 0; i < value.length; i++){
					var val = value[i];
					if(this.isItem(val)){
						this._addReferenceToMap(val, newItem, key);
					}
				}
			}
		}
		this.onNew(newItem, pInfo); // dojo.data.api.Notification call
		return newItem; // item
	},
	
	_removeArrayElement: function(/* Array */ array, /* anything */ element){
		var index = dojo.indexOf(array, element);
		if(index != -1){
			array.splice(index, 1);
			return true;
		}
		return false;
	},
	
	deleteItem: function(/* item */ item){
		// summary: See dojo.data.api.Write.deleteItem()
		this._assert(!this._saveInProgress);
		this._assertIsItem(item);

		// Remove this item from the _arrayOfAllItems, but leave a null value in place
		// of the item, so as not to change the length of the array, so that in newItem() 
		// we can still safely do: newIdentity = this._arrayOfAllItems.length;
		var indexInArrayOfAllItems = item[this._itemNumPropName];
		var identity = this.getIdentity(item);

		//If we have reference integrity on, we need to do reference cleanup for the deleted item
		if(this.referenceIntegrity){
			//First scan all the attributes of this items for references and clean them up in the map 
			//As this item is going away, no need to track its references anymore.

			//Get the attributes list before we generate the backup so it 
			//doesn't pollute the attributes list.
			var attributes = this.getAttributes(item);

			//Backup the map, we'll have to restore it potentially, in a revert.
			if(item[this._reverseRefMap]){
				item["backup_" + this._reverseRefMap] = dojo.clone(item[this._reverseRefMap]);
			}
			
			//TODO:  This causes a reversion problem.  This list won't be restored on revert since it is
			//attached to the 'value'. item, not ours.  Need to back tese up somehow too.
			//Maybe build a map of the backup of the entries and attach it to the deleted item to be restored
			//later.  Or just record them and call _addReferenceToMap on them in revert.
			dojo.forEach(attributes, function(attribute){
				dojo.forEach(this.getValues(item, attribute), function(value){
					if(this.isItem(value)){
						//We have to back up all the references we had to others so they can be restored on a revert.
						if(!item["backupRefs_" + this._reverseRefMap]){
							item["backupRefs_" + this._reverseRefMap] = [];
						}
						item["backupRefs_" + this._reverseRefMap].push({id: this.getIdentity(value), attr: attribute});
						this._removeReferenceFromMap(value, item, attribute);
					}
				}, this);
			}, this);

			//Next, see if we have references to this item, if we do, we have to clean them up too.
			var references = item[this._reverseRefMap];
			if(references){
				//Look through all the items noted as references to clean them up.
				for(var itemId in references){
					var containingItem = null;
					if(this._itemsByIdentity){
						containingItem = this._itemsByIdentity[itemId];
					}else{
						containingItem = this._arrayOfAllItems[itemId];
					}
					//We have a reference to a containing item, now we have to process the
					//attributes and clear all references to the item being deleted.
					if(containingItem){
						for(var attribute in references[itemId]){
							var oldValues = this.getValues(containingItem, attribute) || [];
							var newValues = dojo.filter(oldValues, function(possibleItem){
								return !(this.isItem(possibleItem) && this.getIdentity(possibleItem) == identity);
							}, this);
							//Remove the note of the reference to the item and set the values on the modified attribute.
							this._removeReferenceFromMap(item, containingItem, attribute); 
							if(newValues.length < oldValues.length){
								this._setValueOrValues(containingItem, attribute, newValues, true);
							}
						}
					}
				}
			}
		}

		this._arrayOfAllItems[indexInArrayOfAllItems] = null;

		item[this._storeRefPropName] = null;
		if(this._itemsByIdentity){
			delete this._itemsByIdentity[identity];
		}
		this._pending._deletedItems[identity] = item;
		
		//Remove from the toplevel items, if necessary...
		if(item[this._rootItemPropName]){
			this._removeArrayElement(this._arrayOfTopLevelItems, item);
		}
		this.onDelete(item); // dojo.data.api.Notification call
		return true;
	},

	setValue: function(/* item */ item, /* attribute-name-string */ attribute, /* almost anything */ value){
		// summary: See dojo.data.api.Write.set()
		return this._setValueOrValues(item, attribute, value, true); // boolean
	},
	
	setValues: function(/* item */ item, /* attribute-name-string */ attribute, /* array */ values){
		// summary: See dojo.data.api.Write.setValues()
		return this._setValueOrValues(item, attribute, values, true); // boolean
	},
	
	unsetAttribute: function(/* item */ item, /* attribute-name-string */ attribute){
		// summary: See dojo.data.api.Write.unsetAttribute()
		return this._setValueOrValues(item, attribute, [], true);
	},
	
	_setValueOrValues: function(/* item */ item, /* attribute-name-string */ attribute, /* anything */ newValueOrValues, /*boolean?*/ callOnSet){
		this._assert(!this._saveInProgress);
		
		// Check for valid arguments
		this._assertIsItem(item);
		this._assert(dojo.isString(attribute));
		this._assert(typeof newValueOrValues !== "undefined");

		// Make sure the user isn't trying to change the item's identity
		var identifierAttribute = this._getIdentifierAttribute();
		if(attribute == identifierAttribute){
			throw new Error("ItemFileWriteStore does not have support for changing the value of an item's identifier.");
		}

		// To implement the Notification API, we need to make a note of what
		// the old attribute value was, so that we can pass that info when
		// we call the onSet method.
		var oldValueOrValues = this._getValueOrValues(item, attribute);

		var identity = this.getIdentity(item);
		if(!this._pending._modifiedItems[identity]){
			// Before we actually change the item, we make a copy of it to 
			// record the original state, so that we'll be able to revert if 
			// the revert method gets called.  If the item has already been
			// modified then there's no need to do this now, since we already
			// have a record of the original state.						
			var copyOfItemState = {};
			for(var key in item){
				if((key === this._storeRefPropName) || (key === this._itemNumPropName) || (key === this._rootItemPropName)){
					copyOfItemState[key] = item[key];
				}else if(key === this._reverseRefMap){
					copyOfItemState[key] = dojo.clone(item[key]);
				}else{
					copyOfItemState[key] = item[key].slice(0, item[key].length);
				}
			}
			// Now mark the item as dirty, and save the copy of the original state
			this._pending._modifiedItems[identity] = copyOfItemState;
		}
		
		// Okay, now we can actually change this attribute on the item
		var success = false;
		
		if(dojo.isArray(newValueOrValues) && newValueOrValues.length === 0){
			
			// If we were passed an empty array as the value, that counts
			// as "unsetting" the attribute, so we need to remove this 
			// attribute from the item.
			success = delete item[attribute];
			newValueOrValues = undefined; // used in the onSet Notification call below

			if(this.referenceIntegrity && oldValueOrValues){
				var oldValues = oldValueOrValues;
				if(!dojo.isArray(oldValues)){
					oldValues = [oldValues];
				}
				for(var i = 0; i < oldValues.length; i++){
					var value = oldValues[i];
					if(this.isItem(value)){
						this._removeReferenceFromMap(value, item, attribute);
					}
				}
			}
		}else{
			var newValueArray;
			if(dojo.isArray(newValueOrValues)){
				var newValues = newValueOrValues;
				// Unfortunately, it's not safe to just do this:
				//    newValueArray = newValues;
				// Instead, we need to copy the array, which slice() does very nicely.
				// This is so that our internal data structure won't  
				// get corrupted if the user mucks with the values array *after*
				// calling setValues().
				newValueArray = newValueOrValues.slice(0, newValueOrValues.length);
			}else{
				newValueArray = [newValueOrValues];
			}

			//We need to handle reference integrity if this is on. 
			//In the case of set, we need to see if references were added or removed
			//and update the reference tracking map accordingly.
			if(this.referenceIntegrity){
				if(oldValueOrValues){
					var oldValues = oldValueOrValues;
					if(!dojo.isArray(oldValues)){
						oldValues = [oldValues];
					}
					//Use an associative map to determine what was added/removed from the list.
					//Should be O(n) performant.  First look at all the old values and make a list of them
					//Then for any item not in the old list, we add it.  If it was already present, we remove it.
					//Then we pass over the map and any references left it it need to be removed (IE, no match in
					//the new values list).
					var map = {};
					dojo.forEach(oldValues, function(possibleItem){
						if(this.isItem(possibleItem)){
							var id = this.getIdentity(possibleItem);
							map[id.toString()] = true;
						}
					}, this);
					dojo.forEach(newValueArray, function(possibleItem){
						if(this.isItem(possibleItem)){
							var id = this.getIdentity(possibleItem);
							if(map[id.toString()]){
								delete map[id.toString()];
							}else{
								this._addReferenceToMap(possibleItem, item, attribute); 
							}
						}
					}, this);
					for(var rId in map){
						var removedItem;
						if(this._itemsByIdentity){
							removedItem = this._itemsByIdentity[rId];
						}else{
							removedItem = this._arrayOfAllItems[rId];
						}
						this._removeReferenceFromMap(removedItem, item, attribute);
					}
				}else{
					//Everything is new (no old values) so we have to just
					//insert all the references, if any.
					for(var i = 0; i < newValueArray.length; i++){
						var value = newValueArray[i];
						if(this.isItem(value)){
							this._addReferenceToMap(value, item, attribute);
						}
					}
				}
			}
			item[attribute] = newValueArray;
			success = true;
		}

		// Now we make the dojo.data.api.Notification call
		if(callOnSet){
			this.onSet(item, attribute, oldValueOrValues, newValueOrValues); 
		}
		return success; // boolean
	},

	_addReferenceToMap: function(/*item*/ refItem, /*item*/ parentItem, /*string*/ attribute){
		//	summary:
		//		Method to add an reference map entry for an item and attribute.
		//	description:
		//		Method to add an reference map entry for an item and attribute. 		 //
		//	refItem:
		//		The item that is referenced.
		//	parentItem:
		//		The item that holds the new reference to refItem.
		//	attribute:
		//		The attribute on parentItem that contains the new reference.
		 
		var parentId = this.getIdentity(parentItem);
		var references = refItem[this._reverseRefMap];

		if(!references){
			references = refItem[this._reverseRefMap] = {};
		}
		var itemRef = references[parentId];
		if(!itemRef){
			itemRef = references[parentId] = {};
		}
		itemRef[attribute] = true;
	},

	_removeReferenceFromMap: function(/* item */ refItem, /* item */ parentItem, /*strin*/ attribute){
		//	summary:
		//		Method to remove an reference map entry for an item and attribute.
		//	description:
		//		Method to remove an reference map entry for an item and attribute.  This will
		//		also perform cleanup on the map such that if there are no more references at all to 
		//		the item, its reference object and entry are removed.
		//
		//	refItem:
		//		The item that is referenced.
		//	parentItem:
		//		The item holding a reference to refItem.
		//	attribute:
		//		The attribute on parentItem that contains the reference.
		var identity = this.getIdentity(parentItem);
		var references = refItem[this._reverseRefMap];
		var itemId;
		if(references){
			for(itemId in references){
				if(itemId == identity){
					delete references[itemId][attribute];
					if(this._isEmpty(references[itemId])){
						delete references[itemId];
					}
				}
			}
			if(this._isEmpty(references)){
				delete refItem[this._reverseRefMap];
			}
		}
	},

	_dumpReferenceMap: function(){
		//	summary:
		//		Function to dump the reverse reference map of all items in the store for debug purposes.
		//	description:
		//		Function to dump the reverse reference map of all items in the store for debug purposes.
		var i;
		for(i = 0; i < this._arrayOfAllItems.length; i++){
			var item = this._arrayOfAllItems[i];
			if(item && item[this._reverseRefMap]){
				console.log("Item: [" + this.getIdentity(item) + "] is referenced by: " + dojo.toJson(item[this._reverseRefMap]));
			}
		}
	},
	
	_getValueOrValues: function(/* item */ item, /* attribute-name-string */ attribute){
		var valueOrValues = undefined;
		if(this.hasAttribute(item, attribute)){
			var valueArray = this.getValues(item, attribute);
			if(valueArray.length == 1){
				valueOrValues = valueArray[0];
			}else{
				valueOrValues = valueArray;
			}
		}
		return valueOrValues;
	},
	
	_flatten: function(/* anything */ value){
		if(this.isItem(value)){
			var item = value;
			// Given an item, return an serializable object that provides a 
			// reference to the item.
			// For example, given kermit:
			//    var kermit = store.newItem({id:2, name:"Kermit"});
			// we want to return
			//    {_reference:2}
			var identity = this.getIdentity(item);
			var referenceObject = {_reference: identity};
			return referenceObject;
		}else{
			if(typeof value === "object"){
				for(var type in this._datatypeMap){
					var typeMap = this._datatypeMap[type];
					if(dojo.isObject(typeMap) && !dojo.isFunction(typeMap)){
						if(value instanceof typeMap.type){
							if(!typeMap.serialize){
								throw new Error("ItemFileWriteStore:  No serializer defined for type mapping: [" + type + "]");
							}
							return {_type: type, _value: typeMap.serialize(value)};
						}
					} else if(value instanceof typeMap){
						//SImple mapping, therefore, return as a toString serialization.
						return {_type: type, _value: value.toString()};
					}
				}
			}
			return value;
		}
	},
	
	_getNewFileContentString: function(){
		// summary: 
		//		Generate a string that can be saved to a file.
		//		The result should look similar to:
		//		http://trac.dojotoolkit.org/browser/dojo/trunk/tests/data/countries.json
		var serializableStructure = {};
		
		var identifierAttribute = this._getIdentifierAttribute();
		if(identifierAttribute !== Number){
			serializableStructure.identifier = identifierAttribute;
		}
		if(this._labelAttr){
			serializableStructure.label = this._labelAttr;
		}
		serializableStructure.items = [];
		for(var i = 0; i < this._arrayOfAllItems.length; ++i){
			var item = this._arrayOfAllItems[i];
			if(item !== null){
				var serializableItem = {};
				for(var key in item){
					if(key !== this._storeRefPropName && key !== this._itemNumPropName && key !== this._reverseRefMap && key !== this._rootItemPropName){
						var attribute = key;
						var valueArray = this.getValues(item, attribute);
						if(valueArray.length == 1){
							serializableItem[attribute] = this._flatten(valueArray[0]);
						}else{
							var serializableArray = [];
							for(var j = 0; j < valueArray.length; ++j){
								serializableArray.push(this._flatten(valueArray[j]));
								serializableItem[attribute] = serializableArray;
							}
						}
					}
				}
				serializableStructure.items.push(serializableItem);
			}
		}
		var prettyPrint = true;
		return dojo.toJson(serializableStructure, prettyPrint);
	},

	_isEmpty: function(something){
		//	summary: 
		//		Function to determine if an array or object has no properties or values.
		//	something:
		//		The array or object to examine.
		var empty = true;
		if(dojo.isObject(something)){
			var i;
			for(i in something){
				empty = false;
				break;
			}
		}else if(dojo.isArray(something)){
			if(something.length > 0){
				empty = false;
			}
		}
		return empty; //boolean
	},
	
	save: function(/* object */ keywordArgs){
		// summary: See dojo.data.api.Write.save()
		this._assert(!this._saveInProgress);
		
		// this._saveInProgress is set to true, briefly, from when save is first called to when it completes
		this._saveInProgress = true;
		
		var self = this;
		var saveCompleteCallback = function(){
			self._pending = {
				_newItems:{}, 
				_modifiedItems:{},
				_deletedItems:{}
			};

			self._saveInProgress = false; // must come after this._pending is cleared, but before any callbacks
			if(keywordArgs && keywordArgs.onComplete){
				var scope = keywordArgs.scope || dojo.global;
				keywordArgs.onComplete.call(scope);
			}
		};
		var saveFailedCallback = function(err){
			self._saveInProgress = false;
			if(keywordArgs && keywordArgs.onError){
				var scope = keywordArgs.scope || dojo.global;
				keywordArgs.onError.call(scope, err);
			}
		};
		
		if(this._saveEverything){
			var newFileContentString = this._getNewFileContentString();
			this._saveEverything(saveCompleteCallback, saveFailedCallback, newFileContentString);
		}
		if(this._saveCustom){
			this._saveCustom(saveCompleteCallback, saveFailedCallback);
		}
		if(!this._saveEverything && !this._saveCustom){
			// Looks like there is no user-defined save-handler function.
			// That's fine, it just means the datastore is acting as a "mock-write"
			// store -- changes get saved in memory but don't get saved to disk.
			saveCompleteCallback();
		}
	},
	
	revert: function(){
		// summary: See dojo.data.api.Write.revert()
		this._assert(!this._saveInProgress);

		var identity;
		for(identity in this._pending._modifiedItems){
			// find the original item and the modified item that replaced it
			var copyOfItemState = this._pending._modifiedItems[identity];
			var modifiedItem = null;
			if(this._itemsByIdentity){
				modifiedItem = this._itemsByIdentity[identity];
			}else{
				modifiedItem = this._arrayOfAllItems[identity];
			}
	
			// Restore the original item into a full-fledged item again, we want to try to 
			// keep the same object instance as if we don't it, causes bugs like #9022.
			copyOfItemState[this._storeRefPropName] = this;
			for(key in modifiedItem){
				delete modifiedItem[key];
			}
			dojo.mixin(modifiedItem, copyOfItemState);
		}
		var deletedItem;
		for(identity in this._pending._deletedItems){
			deletedItem = this._pending._deletedItems[identity];
			deletedItem[this._storeRefPropName] = this;
			var index = deletedItem[this._itemNumPropName];

			//Restore the reverse refererence map, if any.
			if(deletedItem["backup_" + this._reverseRefMap]){
				deletedItem[this._reverseRefMap] = deletedItem["backup_" + this._reverseRefMap];
				delete deletedItem["backup_" + this._reverseRefMap];
			}
			this._arrayOfAllItems[index] = deletedItem;
			if(this._itemsByIdentity){
				this._itemsByIdentity[identity] = deletedItem;
			}
			if(deletedItem[this._rootItemPropName]){
				this._arrayOfTopLevelItems.push(deletedItem);
			}
		}
		//We have to pass through it again and restore the reference maps after all the
		//undeletes have occurred.
		for(identity in this._pending._deletedItems){
			deletedItem = this._pending._deletedItems[identity];
			if(deletedItem["backupRefs_" + this._reverseRefMap]){
				dojo.forEach(deletedItem["backupRefs_" + this._reverseRefMap], function(reference){
					var refItem;
					if(this._itemsByIdentity){
						refItem = this._itemsByIdentity[reference.id];
					}else{
						refItem = this._arrayOfAllItems[reference.id];
					}
					this._addReferenceToMap(refItem, deletedItem, reference.attr);
				}, this);
				delete deletedItem["backupRefs_" + this._reverseRefMap]; 
			}
		}

		for(identity in this._pending._newItems){
			var newItem = this._pending._newItems[identity];
			newItem[this._storeRefPropName] = null;
			// null out the new item, but don't change the array index so
			// so we can keep using _arrayOfAllItems.length.
			this._arrayOfAllItems[newItem[this._itemNumPropName]] = null;
			if(newItem[this._rootItemPropName]){
				this._removeArrayElement(this._arrayOfTopLevelItems, newItem);
			}
			if(this._itemsByIdentity){
				delete this._itemsByIdentity[identity];
			}
		}

		this._pending = {
			_newItems:{}, 
			_modifiedItems:{}, 
			_deletedItems:{}
		};
		return true; // boolean
	},
	
	isDirty: function(/* item? */ item){
		// summary: See dojo.data.api.Write.isDirty()
		if(item){
			// return true if the item is dirty
			var identity = this.getIdentity(item);
			return new Boolean(this._pending._newItems[identity] || 
				this._pending._modifiedItems[identity] ||
				this._pending._deletedItems[identity]).valueOf(); // boolean
		}else{
			// return true if the store is dirty -- which means return true
			// if there are any new items, dirty items, or modified items
			if(!this._isEmpty(this._pending._newItems) || 
				!this._isEmpty(this._pending._modifiedItems) ||
				!this._isEmpty(this._pending._deletedItems)){
				return true;
			}
			return false; // boolean
		}
	},

/* dojo.data.api.Notification */

	onSet: function(/* item */ item, 
					/*attribute-name-string*/ attribute, 
					/*object | array*/ oldValue,
					/*object | array*/ newValue){
		// summary: See dojo.data.api.Notification.onSet()
		
		// No need to do anything. This method is here just so that the 
		// client code can connect observers to it.
	},

	onNew: function(/* item */ newItem, /*object?*/ parentInfo){
		// summary: See dojo.data.api.Notification.onNew()
		
		// No need to do anything. This method is here just so that the 
		// client code can connect observers to it. 
	},

	onDelete: function(/* item */ deletedItem){
		// summary: See dojo.data.api.Notification.onDelete()
		
		// No need to do anything. This method is here just so that the 
		// client code can connect observers to it. 
	},

	close: function(/* object? */ request){
		 // summary:
		 //		Over-ride of base close function of ItemFileReadStore to add in check for store state.
		 // description:
		 //		Over-ride of base close function of ItemFileReadStore to add in check for store state.
		 //		If the store is still dirty (unsaved changes), then an error will be thrown instead of
		 //		clearing the internal state for reload from the url.

		 //Clear if not dirty ... or throw an error
		 if(this.clearOnClose){
			 if(!this.isDirty()){
				 this.inherited(arguments);
			 }else{
				 //Only throw an error if the store was dirty and we were loading from a url (cannot reload from url until state is saved).
				 throw new Error("dojo.data.ItemFileWriteStore: There are unsaved changes present in the store.  Please save or revert the changes before invoking close.");
			 }
		 }
	}
});

}

if(!dojo._hasResource["dojo.io.iframe"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojo.io.iframe"] = true;
dojo.provide("dojo.io.iframe");

/*=====
dojo.declare("dojo.io.iframe.__ioArgs", dojo.__IoArgs, {
	constructor: function(){
		//	summary:
		//		All the properties described in the dojo.__ioArgs type, apply
		//		to this type. The following additional properties are allowed
		//		for dojo.io.iframe.send():
		//	method: String?
		//		The HTTP method to use. "GET" or "POST" are the only supported
		//		values.  It will try to read the value from the form node's
		//		method, then try this argument. If neither one exists, then it
		//		defaults to POST.
		//	handleAs: String?
		//		Specifies what format the result data should be given to the
		//		load/handle callback. Valid values are: text, html, xml, json,
		//		javascript. IMPORTANT: For all values EXCEPT html and xml, The
		//		server response should be an HTML file with a textarea element.
		//		The response data should be inside the textarea element. Using an
		//		HTML document the only reliable, cross-browser way this
		//		transport can know when the response has loaded. For the html
		//		handleAs value, just return a normal HTML document.  NOTE: xml
		//		is now supported with this transport (as of 1.1+); a known issue
		//		is if the XML document in question is malformed, Internet Explorer
		//		will throw an uncatchable error.
		//	content: Object?
		//		If "form" is one of the other args properties, then the content
		//		object properties become hidden form form elements. For
		//		instance, a content object of {name1 : "value1"} is converted
		//		to a hidden form element with a name of "name1" and a value of
		//		"value1". If there is not a "form" property, then the content
		//		object is converted into a name=value&name=value string, by
		//		using dojo.objectToQuery().
		this.method = method;
		this.handleAs = handleAs;
		this.content = content;
	}
});
=====*/

dojo.io.iframe = {
	// summary: 
	//		Sends an Ajax I/O call using and Iframe (for instance, to upload files)
	
	create: function(/*String*/fname, /*String*/onloadstr, /*String?*/uri){
		//	summary:
		//		Creates a hidden iframe in the page. Used mostly for IO
		//		transports.  You do not need to call this to start a
		//		dojo.io.iframe request. Just call send().
		//	fname: String
		//		The name of the iframe. Used for the name attribute on the
		//		iframe.
		//	onloadstr: String
		//		A string of JavaScript that will be executed when the content
		//		in the iframe loads.
		//	uri: String
		//		The value of the src attribute on the iframe element. If a
		//		value is not given, then dojo/resources/blank.html will be
		//		used.
		if(window[fname]){ return window[fname]; }
		if(window.frames[fname]){ return window.frames[fname]; }
		var cframe = null;
		var turi = uri;
		if(!turi){
			if(dojo.config["useXDomain"] && !dojo.config["dojoBlankHtmlUrl"]){
				console.warn("dojo.io.iframe.create: When using cross-domain Dojo builds,"
					+ " please save dojo/resources/blank.html to your domain and set djConfig.dojoBlankHtmlUrl"
					+ " to the path on your domain to blank.html");
			}
			turi = (dojo.config["dojoBlankHtmlUrl"]||dojo.moduleUrl("dojo", "resources/blank.html"));
		}
		var ifrstr = dojo.isIE ? '<iframe name="'+fname+'" src="'+turi+'" onload="'+onloadstr+'">' : 'iframe';
		cframe = dojo.doc.createElement(ifrstr);
		with(cframe){
			name = fname;
			setAttribute("name", fname);
			id = fname;
		}
		dojo.body().appendChild(cframe);
		window[fname] = cframe;
	
		with(cframe.style){
			if(!(dojo.isSafari < 3)){
				//We can't change the src in Safari 2.0.3 if absolute position. Bizarro.
				position = "absolute";
			}
			left = top = "1px";
			height = width = "1px";
			visibility = "hidden";
		}

		if(!dojo.isIE){
			this.setSrc(cframe, turi, true);
			cframe.onload = new Function(onloadstr);
		}

		return cframe;
	},

	setSrc: function(/*DOMNode*/iframe, /*String*/src, /*Boolean*/replace){
		//summary:
		//		Sets the URL that is loaded in an IFrame. The replace parameter
		//		indicates whether location.replace() should be used when
		//		changing the location of the iframe.
		try{
			if(!replace){
				if(dojo.isWebKit){
					iframe.location = src;
				}else{
					frames[iframe.name].location = src;
				}
			}else{
				// Fun with DOM 0 incompatibilities!
				var idoc;
				//WebKit > 521 corresponds with Safari 3, which started with 522 WebKit version.
				if(dojo.isIE || dojo.isWebKit > 521){
					idoc = iframe.contentWindow.document;
				}else if(dojo.isSafari){
					idoc = iframe.document;
				}else{ //  if(d.isMozilla){
					idoc = iframe.contentWindow;
				}
	
				//For Safari (at least 2.0.3) and Opera, if the iframe
				//has just been created but it doesn't have content
				//yet, then iframe.document may be null. In that case,
				//use iframe.location and return.
				if(!idoc){
					iframe.location = src;
					return;
				}else{
					idoc.location.replace(src);
				}
			}
		}catch(e){ 
			console.log("dojo.io.iframe.setSrc: ", e); 
		}
	},

	doc: function(/*DOMNode*/iframeNode){
		//summary: Returns the document object associated with the iframe DOM Node argument.
		var doc = iframeNode.contentDocument || // W3
			(
				(
					(iframeNode.name) && (iframeNode.document) && 
					(dojo.doc.getElementsByTagName("iframe")[iframeNode.name].contentWindow) &&
					(dojo.doc.getElementsByTagName("iframe")[iframeNode.name].contentWindow.document)
				)
			) ||  // IE
			(
				(iframeNode.name)&&(dojo.doc.frames[iframeNode.name])&&
				(dojo.doc.frames[iframeNode.name].document)
			) || null;
		return doc;
	},

	send: function(/*dojo.io.iframe.__ioArgs*/args){
		//summary: 
		//		Function that sends the request to the server.
		//		This transport can only process one send() request at a time, so if send() is called
		//multiple times, it will queue up the calls and only process one at a time.
		if(!this["_frame"]){
			this._frame = this.create(this._iframeName, dojo._scopeName + ".io.iframe._iframeOnload();");
		}

		//Set up the deferred.
		var dfd = dojo._ioSetArgs(
			args,
			function(/*Deferred*/dfd){
				//summary: canceller function for dojo._ioSetArgs call.
				dfd.canceled = true;
				dfd.ioArgs._callNext();
			},
			function(/*Deferred*/dfd){
				//summary: okHandler function for dojo._ioSetArgs call.
				var value = null;
				try{
					var ioArgs = dfd.ioArgs;
					var dii = dojo.io.iframe;
					var ifd = dii.doc(dii._frame);
					var handleAs = ioArgs.handleAs;

					//Assign correct value based on handleAs value.
					value = ifd; //html
					if(handleAs != "html"){
						if(handleAs == "xml"){
							//	FF, Saf 3+ and Opera all seem to be fine with ifd being xml.  We have to
							//	do it manually for IE.  Refs #6334.
							if(dojo.isIE){
								dojo.query("a", dii._frame.contentWindow.document.documentElement).orphan();
								var xmlText=(dii._frame.contentWindow.document).documentElement.innerText;
								xmlText=xmlText.replace(/>\s+</g, "><");
								xmlText=dojo.trim(xmlText);
								//Reusing some code in base dojo for handling XML content.  Simpler and keeps
								//Core from duplicating the effort needed to locate the XML Parser on IE.
								var fauxXhr = { responseText: xmlText };
								value = dojo._contentHandlers["xml"](fauxXhr); // DOMDocument
							}
						}else{
							value = ifd.getElementsByTagName("textarea")[0].value; //text
							if(handleAs == "json"){
								value = dojo.fromJson(value); //json
							}else if(handleAs == "javascript"){
								value = dojo.eval(value); //javascript
							}
						}
					}
				}catch(e){
					value = e;
				}finally{
					ioArgs._callNext();				
				}
				return value;
			},
			function(/*Error*/error, /*Deferred*/dfd){
				//summary: errHandler function for dojo._ioSetArgs call.
				dfd.ioArgs._hasError = true;
				dfd.ioArgs._callNext();
				return error;
			}
		);

		//Set up a function that will fire the next iframe request. Make sure it only
		//happens once per deferred.
		dfd.ioArgs._callNext = function(){
			if(!this["_calledNext"]){
				this._calledNext = true;
				dojo.io.iframe._currentDfd = null;
				dojo.io.iframe._fireNextRequest();
			}
		}

		this._dfdQueue.push(dfd);
		this._fireNextRequest();
		
		//Add it the IO watch queue, to get things like timeout support.
		dojo._ioWatch(
			dfd,
			function(/*Deferred*/dfd){
				//validCheck
				return !dfd.ioArgs["_hasError"];
			},
			function(dfd){
				//ioCheck
				return (!!dfd.ioArgs["_finished"]);
			},
			function(dfd){
				//resHandle
				if(dfd.ioArgs._finished){
					dfd.callback(dfd);
				}else{
					dfd.errback(new Error("Invalid dojo.io.iframe request state"));
				}
			}
		);

		return dfd;
	},

	_currentDfd: null,
	_dfdQueue: [],
	_iframeName: dojo._scopeName + "IoIframe",

	_fireNextRequest: function(){
		//summary: Internal method used to fire the next request in the bind queue.
		try{
			if((this._currentDfd)||(this._dfdQueue.length == 0)){ return; }
			//Find next deferred, skip the canceled ones.
			do{
				var dfd = this._currentDfd = this._dfdQueue.shift();
			} while(dfd && dfd.canceled && this._dfdQueue.length);

			//If no more dfds, cancel.
			if(!dfd || dfd.canceled){
				this._currentDfd =  null;
				return;
			}

			var ioArgs = dfd.ioArgs;
			var args = ioArgs.args;

			ioArgs._contentToClean = [];
			var fn = dojo.byId(args["form"]);
			var content = args["content"] || {};
			if(fn){
				if(content){
					// if we have things in content, we need to add them to the form
					// before submission
					var pHandler = function(name, value) {
						var tn;
						if(dojo.isIE){
							tn = dojo.doc.createElement("<input type='hidden' name='"+name+"'>");
						}else{
							tn = dojo.doc.createElement("input");
							tn.type = "hidden";
							tn.name = name;
						}
						tn.value = value;
						fn.appendChild(tn);
						ioArgs._contentToClean.push(name);
					};
					for(var x in content){
						var val = content[x];
						if(dojo.isArray(val) && val.length > 1){
							var i;
							for (i = 0; i < val.length; i++) {
								pHandler(x,val[i]);
							}
						}else{
							if(!fn[x]){
								pHandler(x,val);
							}else{
								fn[x].value = val;
							}
						}
					}
				}
				//IE requires going through getAttributeNode instead of just getAttribute in some form cases, 
				//so use it for all.  See #2844
				var actnNode = fn.getAttributeNode("action");
				var mthdNode = fn.getAttributeNode("method");
				var trgtNode = fn.getAttributeNode("target");
				if(args["url"]){
					ioArgs._originalAction = actnNode ? actnNode.value : null;
					if(actnNode){
						actnNode.value = args.url;
					}else{
						fn.setAttribute("action",args.url);
					}
				}
				if(!mthdNode || !mthdNode.value){
					if(mthdNode){
						mthdNode.value= (args["method"]) ? args["method"] : "post";
					}else{
						fn.setAttribute("method", (args["method"]) ? args["method"] : "post");
					}
				}
				ioArgs._originalTarget = trgtNode ? trgtNode.value: null;
				if(trgtNode){
					trgtNode.value = this._iframeName;
				}else{
					fn.setAttribute("target", this._iframeName);
				}
				fn.target = this._iframeName;
				dojo._ioNotifyStart(dfd);
				fn.submit();
			}else{
				// otherwise we post a GET string by changing URL location for the
				// iframe
				var tmpUrl = args.url + (args.url.indexOf("?") > -1 ? "&" : "?") + ioArgs.query;
				dojo._ioNotifyStart(dfd);
				this.setSrc(this._frame, tmpUrl, true);
			}
		}catch(e){
			dfd.errback(e);
		}
	},

	_iframeOnload: function(){
		var dfd = this._currentDfd;
		if(!dfd){
			this._fireNextRequest();
			return;
		}

		var ioArgs = dfd.ioArgs;
		var args = ioArgs.args;
		var fNode = dojo.byId(args.form);
	
		if(fNode){
			// remove all the hidden content inputs
			var toClean = ioArgs._contentToClean;
			for(var i = 0; i < toClean.length; i++) {
				var key = toClean[i];
				//Need to cycle over all nodes since we may have added
				//an array value which means that more than one node could
				//have the same .name value.
				for(var j = 0; j < fNode.childNodes.length; j++){
					var chNode = fNode.childNodes[j];
					if(chNode.name == key){
						dojo.destroy(chNode);
						break;
					}
				}
			}

			// restore original action + target
			if(ioArgs["_originalAction"]){
				fNode.setAttribute("action", ioArgs._originalAction);
			}
			if(ioArgs["_originalTarget"]){
				fNode.setAttribute("target", ioArgs._originalTarget);
				fNode.target = ioArgs._originalTarget;
			}
		}

		ioArgs._finished = true;
	}
}

}

if(!dojo._hasResource["dijit.form.CheckBox"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit.form.CheckBox"] = true;
dojo.provide("dijit.form.CheckBox");



dojo.declare(
	"dijit.form.CheckBox",
	dijit.form.ToggleButton,
	{
		// summary:
		// 		Same as an HTML checkbox, but with fancy styling.
		//
		// description:
		// User interacts with real html inputs.
		// On onclick (which occurs by mouse click, space-bar, or
		// using the arrow keys to switch the selected radio button),
		// we update the state of the checkbox/radio.
		//
		// There are two modes:
		//   1. High contrast mode
		//   2. Normal mode
		// In case 1, the regular html inputs are shown and used by the user.
		// In case 2, the regular html inputs are invisible but still used by
		// the user. They are turned quasi-invisible and overlay the background-image.

		templateString: dojo.cache("dijit.form", "templates/CheckBox.html", "<div class=\"dijitReset dijitInline\" waiRole=\"presentation\"\r\n\t><input\r\n\t \t${nameAttrSetting} type=\"${type}\" ${checkedAttrSetting}\r\n\t\tclass=\"dijitReset dijitCheckBoxInput\"\r\n\t\tdojoAttachPoint=\"focusNode\"\r\n\t \tdojoAttachEvent=\"onmouseover:_onMouse,onmouseout:_onMouse,onclick:_onClick\"\r\n/></div>\r\n"),

		baseClass: "dijitCheckBox",

		// type: [private] String
		//		type attribute on <input> node.
		//		Overrides `dijit.form.Button.type`.   Users should not change this value.
		type: "checkbox",

		// value: String
		//		As an initialization parameter, equivalent to value field on normal checkbox
		//		(if checked, the value is passed as the value when form is submitted).
		//
		//		However, attr('value') will return either the string or false depending on
		//		whether or not the checkbox is checked.
		//
		//		attr('value', string) will check the checkbox and change the value to the
		//		specified string
		//
		//		attr('value', boolean) will change the checked state.
		value: "on",

		// readOnly: Boolean
		//		Should this widget respond to user input?
		//		In markup, this is specified as "readOnly".
		//		Similar to disabled except readOnly form values are submitted.
		readOnly: false,

		attributeMap: dojo.delegate(dijit.form.ToggleButton.prototype.attributeMap, {
			readOnly: "focusNode"
		}),

		_setReadOnlyAttr: function(/*Boolean*/ value){
			this.readOnly = value;
			dojo.attr(this.focusNode, 'readOnly', value);
			dijit.setWaiState(this.focusNode, "readonly", value);
			this._setStateClass();
		},

		_setValueAttr: function(/*String or Boolean*/ newValue){
			// summary:
			//		Handler for value= attribute to constructor, and also calls to
			//		attr('value', val).
			// description:
			//		During initialization, just saves as attribute to the <input type=checkbox>.
			//
			//		After initialization,
			//		when passed a boolean, controls whether or not the CheckBox is checked.
			//		If passed a string, changes the value attribute of the CheckBox (the one
			//		specified as "value" when the CheckBox was constructed (ex: <input
			//		dojoType="dijit.CheckBox" value="chicken">)
			if(typeof newValue == "string"){
				this.value = newValue;
				dojo.attr(this.focusNode, 'value', newValue);
				newValue = true;
			}
			if(this._created){
				this.attr('checked', newValue);
			}
		},
		_getValueAttr: function(){
			// summary:
			//		Hook so attr('value') works.
			// description:
			//		If the CheckBox is checked, returns the value attribute.
			//		Otherwise returns false.
			return (this.checked ? this.value : false);
		},

		postMixInProperties: function(){
			if(this.value == ""){
				this.value = "on";
			}

			// Need to set initial checked state as part of template, so that form submit works.
			// dojo.attr(node, "checked", bool) doesn't work on IEuntil node has been attached
			// to <body>, see #8666
			this.checkedAttrSetting = this.checked ? "checked" : "";

			this.inherited(arguments);
		},

		 _fillContent: function(/*DomNode*/ source){
			// Override Button::_fillContent() since it doesn't make sense for CheckBox,
			// since CheckBox doesn't even have a container
		},

		reset: function(){
			// Override ToggleButton.reset()

			this._hasBeenBlurred = false;

			this.attr('checked', this.params.checked || false);

			// Handle unlikely event that the <input type=checkbox> value attribute has changed
			this.value = this.params.value || "on";
			dojo.attr(this.focusNode, 'value', this.value);
		},

		_onFocus: function(){
			if(this.id){
				dojo.query("label[for='"+this.id+"']").addClass("dijitFocusedLabel");
			}
		},

		_onBlur: function(){
			if(this.id){
				dojo.query("label[for='"+this.id+"']").removeClass("dijitFocusedLabel");
			}
		},

		_onClick: function(/*Event*/ e){
			// summary:
			//		Internal function to handle click actions - need to check
			//		readOnly, since button no longer does that check.
			if(this.readOnly){
				return false;
			}
			return this.inherited(arguments);
		}
	}
);

dojo.declare(
	"dijit.form.RadioButton",
	dijit.form.CheckBox,
	{
		// summary:
		// 		Same as an HTML radio, but with fancy styling.

		type: "radio",
		baseClass: "dijitRadio",

		_setCheckedAttr: function(/*Boolean*/ value){
			// If I am being checked then have to deselect currently checked radio button
			this.inherited(arguments);
			if(!this._created){ return; }
			if(value){
				var _this = this;
				// search for radio buttons with the same name that need to be unchecked
				dojo.query("INPUT[type=radio]", this.focusNode.form || dojo.doc).forEach( // can't use name= since dojo.query doesn't support [] in the name
					function(inputNode){
						if(inputNode.name == _this.name && inputNode != _this.focusNode && inputNode.form == _this.focusNode.form){
							var widget = dijit.getEnclosingWidget(inputNode);
							if(widget && widget.checked){
								widget.attr('checked', false);
							}
						}
					}
				);
			}
		},

		_clicked: function(/*Event*/ e){
			if(!this.checked){
				this.attr('checked', true);
			}
		}
	}
);

}

if(!dojo._hasResource["dojox.form.FileInput"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojox.form.FileInput"] = true;
dojo.provide("dojox.form.FileInput");
dojo.experimental("dojox.form.FileInput"); 


 

dojo.declare("dojox.form.FileInput",
	dijit.form._FormWidget,
	{
	// summary: A styled input type="file"
	//
	// description: A input type="file" form widget, with a button for uploading to be styled via css,
	//	a cancel button to clear selection, and FormWidget mixin to provide standard dijit.form.Form
	//	support (FIXME: maybe not fully implemented) 

	// label: String
	//	the title text of the "Browse" button
	label: "Browse ...",

	// cancelText: String
	//	the title of the "Cancel" button
	cancelText: "Cancel",

	// name: String
	//	ugh, this should be pulled from this.domNode
	name: "uploadFile",

	templateString: dojo.cache("dojox.form", "resources/FileInput.html", "<div class=\"dijitFileInput\">\r\n\t<input id=\"${id}\" class=\"dijitFileInputReal\" type=\"file\" dojoAttachPoint=\"fileInput\" name=\"${name}\" />\r\n\t<div class=\"dijitFakeInput\">\r\n\t\t<input class=\"dijitFileInputVisible\" type=\"text\" dojoAttachPoint=\"focusNode, inputNode\" />\r\n\t\t<div class=\"dijitInline dijitFileInputText\" dojoAttachPoint=\"titleNode\">${label}</div>\r\n\t\t<div class=\"dijitInline dijitFileInputButton\" dojoAttachPoint=\"cancelNode\" \r\n\t\t\tdojoAttachEvent=\"onclick:reset\">${cancelText}</div>\r\n\t</div>\r\n</div>\r\n"),
	
	startup: function(){
		// summary: listen for changes on our real file input
		this._listener = this.connect(this.fileInput,"onchange","_matchValue");
		this._keyListener = this.connect(this.fileInput,"onkeyup","_matchValue");
	},

	_matchValue: function(){
		// summary: set the content of the upper input based on the semi-hidden file input
		this.inputNode.value = this.fileInput.value;
		if(this.inputNode.value){
			this.cancelNode.style.visibility = "visible";
			dojo.fadeIn({ node: this.cancelNode, duration:275 }).play();
		}
	},

	setLabel: function(/* String */label,/* String? */cssClass){
		// summary: method to allow use to change button label
		this.titleNode.innerHTML = label;
	},

	reset: function(/* Event */e){
		// summary: on click of cancel button, since we can't clear the input because of
		// 	security reasons, we destroy it, and add a new one in it's place.
		this.disconnect(this._listener);
		this.disconnect(this._keyListener);
		if(this.fileInput){
			this.domNode.removeChild(this.fileInput);
		}
		dojo.fadeOut({ node: this.cancelNode, duration:275 }).play(); 

		// should we use cloneNode()? can we?
		this.fileInput = document.createElement('input');
		// dojo.attr(this.fileInput,{
		//	"type":"file", "id":this.id, "name": this.name	
		//});
		this.fileInput.setAttribute("type","file");
		this.fileInput.setAttribute("id", this.id);
		this.fileInput.setAttribute("name", this.name);
		dojo.addClass(this.fileInput,"dijitFileInputReal");
		this.domNode.appendChild(this.fileInput);

		this._keyListener = this.connect(this.fileInput, "onkeyup", "_matchValue");
		this._listener = this.connect(this.fileInput, "onchange", "_matchValue"); 
		this.inputNode.value = ""; 
	}

});

}

if(!dojo._hasResource["dijit.form._Spinner"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit.form._Spinner"] = true;
dojo.provide("dijit.form._Spinner");



dojo.declare(
	"dijit.form._Spinner",
	dijit.form.RangeBoundTextBox,
	{
		// summary:
		//		Mixin for validation widgets with a spinner.
		// description:
		//		This class basically (conceptually) extends `dijit.form.ValidationTextBox`.
		//		It modifies the template to have up/down arrows, and provides related handling code.

		// defaultTimeout: Number
		//		Number of milliseconds before a held arrow key or up/down button becomes typematic
		defaultTimeout: 500,

		// timeoutChangeRate: Number
		//		Fraction of time used to change the typematic timer between events.
		//		1.0 means that each typematic event fires at defaultTimeout intervals.
		//		< 1.0 means that each typematic event fires at an increasing faster rate.
		timeoutChangeRate: 0.90,

		// smallDelta: Number
		//	  Adjust the value by this much when spinning using the arrow keys/buttons
		smallDelta: 1,

		// largeDelta: Number
		//	  Adjust the value by this much when spinning using the PgUp/Dn keys
		largeDelta: 10,

		templateString: dojo.cache("dijit.form", "templates/Spinner.html", "<div class=\"dijit dijitReset dijitInlineTable dijitLeft\"\r\n\tid=\"widget_${id}\"\r\n\tdojoAttachEvent=\"onmouseenter:_onMouse,onmouseleave:_onMouse,onmousedown:_onMouse\" waiRole=\"presentation\"\r\n\t><div class=\"dijitInputLayoutContainer\"\r\n\t\t><div class=\"dijitReset dijitSpinnerButtonContainer\"\r\n\t\t\t>&nbsp;<div class=\"dijitReset dijitLeft dijitButtonNode dijitArrowButton dijitUpArrowButton\"\r\n\t\t\t\tdojoAttachPoint=\"upArrowNode\"\r\n\t\t\t\tdojoAttachEvent=\"onmouseenter:_onMouse,onmouseleave:_onMouse\"\r\n\t\t\t\tstateModifier=\"UpArrow\"\r\n\t\t\t\t><div class=\"dijitArrowButtonInner\">&thinsp;</div\r\n\t\t\t\t><div class=\"dijitArrowButtonChar\">&#9650;</div\r\n\t\t\t></div\r\n\t\t\t><div class=\"dijitReset dijitLeft dijitButtonNode dijitArrowButton dijitDownArrowButton\"\r\n\t\t\t\tdojoAttachPoint=\"downArrowNode\"\r\n\t\t\t\tdojoAttachEvent=\"onmouseenter:_onMouse,onmouseleave:_onMouse\"\r\n\t\t\t\tstateModifier=\"DownArrow\"\r\n\t\t\t\t><div class=\"dijitArrowButtonInner\">&thinsp;</div\r\n\t\t\t\t><div class=\"dijitArrowButtonChar\">&#9660;</div\r\n\t\t\t></div\r\n\t\t></div\r\n\t\t><div class=\"dijitReset dijitValidationIcon\"><br></div\r\n\t\t><div class=\"dijitReset dijitValidationIconText\">&Chi;</div\r\n\t\t><div class=\"dijitReset dijitInputField\"\r\n\t\t\t><input class='dijitReset' dojoAttachPoint=\"textbox,focusNode\" type=\"${type}\" dojoAttachEvent=\"onkeypress:_onKeyPress\"\r\n\t\t\t\twaiRole=\"spinbutton\" autocomplete=\"off\" ${nameAttrSetting}\r\n\t\t/></div\r\n\t></div\r\n></div>\r\n"),
		baseClass: "dijitSpinner",

		adjust: function(/* Object */ val, /*Number*/ delta){
			// summary:
			//		Overridable function used to adjust a primitive value(Number/Date/...) by the delta amount specified.
			// 		The val is adjusted in a way that makes sense to the object type.
			// tags:
			//		protected extension
			return val;
		},

		_arrowState: function(/*Node*/ node, /*Boolean*/ pressed){
			// summary:
			//		Called when an arrow key is pressed to update the relevant CSS classes
			this._active = pressed;
			this.stateModifier = node.getAttribute("stateModifier") || "";
			this._setStateClass();
		},

		_arrowPressed: function(/*Node*/ nodePressed, /*Number*/ direction, /*Number*/ increment){
			// summary:
			//		Handler for arrow button or arrow key being pressed
			if(this.disabled || this.readOnly){ return; }
			this._arrowState(nodePressed, true);
			this._setValueAttr(this.adjust(this.attr('value'), direction*increment), false);
			dijit.selectInputText(this.textbox, this.textbox.value.length);
		},

		_arrowReleased: function(/*Node*/ node){
			// summary:
			//		Handler for arrow button or arrow key being released
			this._wheelTimer = null;
			if(this.disabled || this.readOnly){ return; }
			this._arrowState(node, false);
		},

		_typematicCallback: function(/*Number*/ count, /*DOMNode*/ node, /*Event*/ evt){
			var inc=this.smallDelta;
			if(node == this.textbox){
				var k=dojo.keys;
				var key = evt.charOrCode;
				inc = (key == k.PAGE_UP || key == k.PAGE_DOWN) ? this.largeDelta : this.smallDelta;
				node = (key == k.UP_ARROW || key == k.PAGE_UP) ? this.upArrowNode : this.downArrowNode;
			}
			if(count == -1){ this._arrowReleased(node); }
			else{ this._arrowPressed(node, (node == this.upArrowNode) ? 1 : -1, inc); }
		},

		_wheelTimer: null,
		_mouseWheeled: function(/*Event*/ evt){
			// summary:
			//		Mouse wheel listener where supported

			dojo.stopEvent(evt);
			// FIXME: Safari bubbles

			// be nice to DOH and scroll as much as the event says to
			var scrollAmount = evt.detail ? (evt.detail * -1) : (evt.wheelDelta / 120);
			if(scrollAmount !== 0){
				var node = this[(scrollAmount > 0 ? "upArrowNode" : "downArrowNode" )];

				this._arrowPressed(node, scrollAmount, this.smallDelta);

				if(!this._wheelTimer){
					clearTimeout(this._wheelTimer);
				}
				this._wheelTimer = setTimeout(dojo.hitch(this,"_arrowReleased",node), 50);
			}

		},

		postCreate: function(){
			this.inherited(arguments);

			// extra listeners
			this.connect(this.domNode, !dojo.isMozilla ? "onmousewheel" : 'DOMMouseScroll', "_mouseWheeled");
			this._connects.push(dijit.typematic.addListener(this.upArrowNode, this.textbox, {charOrCode:dojo.keys.UP_ARROW,ctrlKey:false,altKey:false,shiftKey:false,metaKey:false}, this, "_typematicCallback", this.timeoutChangeRate, this.defaultTimeout));
			this._connects.push(dijit.typematic.addListener(this.downArrowNode, this.textbox, {charOrCode:dojo.keys.DOWN_ARROW,ctrlKey:false,altKey:false,shiftKey:false,metaKey:false}, this, "_typematicCallback", this.timeoutChangeRate, this.defaultTimeout));
			this._connects.push(dijit.typematic.addListener(this.upArrowNode, this.textbox, {charOrCode:dojo.keys.PAGE_UP,ctrlKey:false,altKey:false,shiftKey:false,metaKey:false}, this, "_typematicCallback", this.timeoutChangeRate, this.defaultTimeout));
			this._connects.push(dijit.typematic.addListener(this.downArrowNode, this.textbox, {charOrCode:dojo.keys.PAGE_DOWN,ctrlKey:false,altKey:false,shiftKey:false,metaKey:false}, this, "_typematicCallback", this.timeoutChangeRate, this.defaultTimeout));
			if(dojo.isIE){
				var _this = this;
				(function resize(){
					var sz = _this.upArrowNode.parentNode.offsetHeight;
					if(sz){
						_this.upArrowNode.style.height = sz >> 1;
						_this.downArrowNode.style.height = sz - (sz >> 1);
						_this.focusNode.parentNode.style.height = sz;
					}
				})();
				this.connect(this.domNode, "onresize",
					function(){ setTimeout(
						function(){
							resize();
							// cause IE to rerender when spinner is moved from hidden to visible
							_this._setStateClass();
						}, 0);
					}
				);
				this._layoutHackIE7();
			}
		}
});

}

if(!dojo._hasResource["dojo.regexp"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojo.regexp"] = true;
dojo.provide("dojo.regexp");

/*=====
dojo.regexp = {
	// summary: Regular expressions and Builder resources
};
=====*/

dojo.regexp.escapeString = function(/*String*/str, /*String?*/except){
	//	summary:
	//		Adds escape sequences for special characters in regular expressions
	// except:
	//		a String with special characters to be left unescaped

	return str.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, function(ch){
		if(except && except.indexOf(ch) != -1){
			return ch;
		}
		return "\\" + ch;
	}); // String
}

dojo.regexp.buildGroupRE = function(/*Object|Array*/arr, /*Function*/re, /*Boolean?*/nonCapture){
	//	summary:
	//		Builds a regular expression that groups subexpressions
	//	description:
	//		A utility function used by some of the RE generators. The
	//		subexpressions are constructed by the function, re, in the second
	//		parameter.  re builds one subexpression for each elem in the array
	//		a, in the first parameter. Returns a string for a regular
	//		expression that groups all the subexpressions.
	// arr:
	//		A single value or an array of values.
	// re:
	//		A function. Takes one parameter and converts it to a regular
	//		expression. 
	// nonCapture:
	//		If true, uses non-capturing match, otherwise matches are retained
	//		by regular expression. Defaults to false

	// case 1: a is a single value.
	if(!(arr instanceof Array)){
		return re(arr); // String
	}

	// case 2: a is an array
	var b = [];
	for(var i = 0; i < arr.length; i++){
		// convert each elem to a RE
		b.push(re(arr[i]));
	}

	 // join the REs as alternatives in a RE group.
	return dojo.regexp.group(b.join("|"), nonCapture); // String
}

dojo.regexp.group = function(/*String*/expression, /*Boolean?*/nonCapture){
	// summary:
	//		adds group match to expression
	// nonCapture:
	//		If true, uses non-capturing match, otherwise matches are retained
	//		by regular expression. 
	return "(" + (nonCapture ? "?:":"") + expression + ")"; // String
}

}

if(!dojo._hasResource["dojo.number"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojo.number"] = true;
dojo.provide("dojo.number");







/*=====
dojo.number = {
	// summary: localized formatting and parsing routines for Number
}

dojo.number.__FormatOptions = function(){
	//	pattern: String?
	//		override [formatting pattern](http://www.unicode.org/reports/tr35/#Number_Format_Patterns)
	//		with this string.  Default value is based on locale.  Overriding this property will defeat
	//		localization.
	//	type: String?
	//		choose a format type based on the locale from the following:
	//		decimal, scientific (not yet supported), percent, currency. decimal by default.
	//	places: Number?
	//		fixed number of decimal places to show.  This overrides any
	//		information in the provided pattern.
	//	round: Number?
	//		5 rounds to nearest .5; 0 rounds to nearest whole (default). -1
	//		means do not round.
	//	locale: String?
	//		override the locale used to determine formatting rules
	this.pattern = pattern;
	this.type = type;
	this.places = places;
	this.round = round;
	this.locale = locale;
}
=====*/

dojo.number.format = function(/*Number*/value, /*dojo.number.__FormatOptions?*/options){
	// summary:
	//		Format a Number as a String, using locale-specific settings
	// description:
	//		Create a string from a Number using a known localized pattern.
	//		Formatting patterns appropriate to the locale are chosen from the
	//		[CLDR](http://unicode.org/cldr) as well as the appropriate symbols and
	//		delimiters.  See <http://www.unicode.org/reports/tr35/#Number_Elements>
	//		If value is Infinity, -Infinity, or is not a valid JavaScript number, return null.
	// value:
	//		the number to be formatted

	options = dojo.mixin({}, options || {});
	var locale = dojo.i18n.normalizeLocale(options.locale);
	var bundle = dojo.i18n.getLocalization("dojo.cldr", "number", locale);
	options.customs = bundle;
	var pattern = options.pattern || bundle[(options.type || "decimal") + "Format"];
	if(isNaN(value) || Math.abs(value) == Infinity){ return null; } // null
	return dojo.number._applyPattern(value, pattern, options); // String
};

//dojo.number._numberPatternRE = /(?:[#0]*,?)*[#0](?:\.0*#*)?/; // not precise, but good enough
dojo.number._numberPatternRE = /[#0,]*[#0](?:\.0*#*)?/; // not precise, but good enough

dojo.number._applyPattern = function(/*Number*/value, /*String*/pattern, /*dojo.number.__FormatOptions?*/options){
	// summary:
	//		Apply pattern to format value as a string using options. Gives no
	//		consideration to local customs.
	// value:
	//		the number to be formatted.
	// pattern:
	//		a pattern string as described by
	//		[unicode.org TR35](http://www.unicode.org/reports/tr35/#Number_Format_Patterns)
	// options: dojo.number.__FormatOptions?
	//		_applyPattern is usually called via `dojo.number.format()` which
	//		populates an extra property in the options parameter, "customs".
	//		The customs object specifies group and decimal parameters if set.

	//TODO: support escapes
	options = options || {};
	var group = options.customs.group;
	var decimal = options.customs.decimal;

	var patternList = pattern.split(';');
	var positivePattern = patternList[0];
	pattern = patternList[(value < 0) ? 1 : 0] || ("-" + positivePattern);

	//TODO: only test against unescaped
	if(pattern.indexOf('%') != -1){
		value *= 100;
	}else if(pattern.indexOf('\u2030') != -1){
		value *= 1000; // per mille
	}else if(pattern.indexOf('\u00a4') != -1){
		group = options.customs.currencyGroup || group;//mixins instead?
		decimal = options.customs.currencyDecimal || decimal;// Should these be mixins instead?
		pattern = pattern.replace(/\u00a4{1,3}/, function(match){
			var prop = ["symbol", "currency", "displayName"][match.length-1];
			return options[prop] || options.currency || "";
		});
	}else if(pattern.indexOf('E') != -1){
		throw new Error("exponential notation not supported");
	}
	
	//TODO: support @ sig figs?
	var numberPatternRE = dojo.number._numberPatternRE;
	var numberPattern = positivePattern.match(numberPatternRE);
	if(!numberPattern){
		throw new Error("unable to find a number expression in pattern: "+pattern);
	}
	if(options.fractional === false){ options.places = 0; }
	return pattern.replace(numberPatternRE,
		dojo.number._formatAbsolute(value, numberPattern[0], {decimal: decimal, group: group, places: options.places, round: options.round}));
}

dojo.number.round = function(/*Number*/value, /*Number?*/places, /*Number?*/increment){
	//	summary:
	//		Rounds to the nearest value with the given number of decimal places, away from zero
	//	description:
	//		Rounds to the nearest value with the given number of decimal places, away from zero if equal.
	//		Similar to Number.toFixed(), but compensates for browser quirks. Rounding can be done by
	//		fractional increments also, such as the nearest quarter.
	//		NOTE: Subject to floating point errors.  See dojox.math.round for experimental workaround.
	//	value:
	//		The number to round
	//	places:
	//		The number of decimal places where rounding takes place.  Defaults to 0 for whole rounding.
	//		Must be non-negative.
	//	increment:
	//		Rounds next place to nearest value of increment/10.  10 by default.
	//	example:
	//		>>> dojo.number.round(-0.5)
	//		-1
	//		>>> dojo.number.round(162.295, 2)
	//		162.29  // note floating point error.  Should be 162.3
	//		>>> dojo.number.round(10.71, 0, 2.5)
	//		10.75
	var factor = 10 / (increment || 10);
	return (factor * +value).toFixed(places) / factor; // Number
}

if((0.9).toFixed() == 0){
	// (isIE) toFixed() bug workaround: Rounding fails on IE when most significant digit
	// is just after the rounding place and is >=5
	(function(){
		var round = dojo.number.round;
		dojo.number.round = function(v, p, m){
			var d = Math.pow(10, -p || 0), a = Math.abs(v);
			if(!v || a >= d || a * Math.pow(10, p + 1) < 5){
				d = 0;
			}
			return round(v, p, m) + (v > 0 ? d : -d);
		}
	})();
}

/*=====
dojo.number.__FormatAbsoluteOptions = function(){
	//	decimal: String?
	//		the decimal separator
	//	group: String?
	//		the group separator
	//	places: Number?|String?
	//		number of decimal places.  the range "n,m" will format to m places.
	//	round: Number?
	//		5 rounds to nearest .5; 0 rounds to nearest whole (default). -1
	//		means don't round.
	this.decimal = decimal;
	this.group = group;
	this.places = places;
	this.round = round;
}
=====*/

dojo.number._formatAbsolute = function(/*Number*/value, /*String*/pattern, /*dojo.number.__FormatAbsoluteOptions?*/options){
	// summary: 
	//		Apply numeric pattern to absolute value using options. Gives no
	//		consideration to local customs.
	// value:
	//		the number to be formatted, ignores sign
	// pattern:
	//		the number portion of a pattern (e.g. `#,##0.00`)
	options = options || {};
	if(options.places === true){options.places=0;}
	if(options.places === Infinity){options.places=6;} // avoid a loop; pick a limit

	var patternParts = pattern.split(".");
	var maxPlaces = (options.places >= 0) ? options.places : (patternParts[1] && patternParts[1].length) || 0;
	if(!(options.round < 0)){
		value = dojo.number.round(value, maxPlaces, options.round);
	}

	var valueParts = String(Math.abs(value)).split(".");
	var fractional = valueParts[1] || "";
	if(options.places){
		var comma = dojo.isString(options.places) && options.places.indexOf(",");
		if(comma){
			options.places = options.places.substring(comma+1);
		}
		valueParts[1] = dojo.string.pad(fractional.substr(0, options.places), options.places, '0', true);
	}else if(patternParts[1] && options.places !== 0){
		// Pad fractional with trailing zeros
		var pad = patternParts[1].lastIndexOf("0") + 1;
		if(pad > fractional.length){
			valueParts[1] = dojo.string.pad(fractional, pad, '0', true);
		}

		// Truncate fractional
		var places = patternParts[1].length;
		if(places < fractional.length){
			valueParts[1] = fractional.substr(0, places);
		}
	}else{
		if(valueParts[1]){ valueParts.pop(); }
	}

	// Pad whole with leading zeros
	var patternDigits = patternParts[0].replace(',', '');
	pad = patternDigits.indexOf("0");
	if(pad != -1){
		pad = patternDigits.length - pad;
		if(pad > valueParts[0].length){
			valueParts[0] = dojo.string.pad(valueParts[0], pad);
		}

		// Truncate whole
		if(patternDigits.indexOf("#") == -1){
			valueParts[0] = valueParts[0].substr(valueParts[0].length - pad);
		}
	}

	// Add group separators
	var index = patternParts[0].lastIndexOf(',');
	var groupSize, groupSize2;
	if(index != -1){
		groupSize = patternParts[0].length - index - 1;
		var remainder = patternParts[0].substr(0, index);
		index = remainder.lastIndexOf(',');
		if(index != -1){
			groupSize2 = remainder.length - index - 1;
		}
	}
	var pieces = [];
	for(var whole = valueParts[0]; whole;){
		var off = whole.length - groupSize;
		pieces.push((off > 0) ? whole.substr(off) : whole);
		whole = (off > 0) ? whole.slice(0, off) : "";
		if(groupSize2){
			groupSize = groupSize2;
			delete groupSize2;
		}
	}
	valueParts[0] = pieces.reverse().join(options.group || ",");

	return valueParts.join(options.decimal || ".");
};

/*=====
dojo.number.__RegexpOptions = function(){
	//	pattern: String?
	//		override [formatting pattern](http://www.unicode.org/reports/tr35/#Number_Format_Patterns)
	//		with this string.  Default value is based on locale.  Overriding this property will defeat
	//		localization.
	//	type: String?
	//		choose a format type based on the locale from the following:
	//		decimal, scientific (not yet supported), percent, currency. decimal by default.
	//	locale: String?
	//		override the locale used to determine formatting rules
	//	strict: Boolean?
	//		strict parsing, false by default.  Strict parsing requires input as produced by the format() method.
	//		Non-strict is more permissive, e.g. flexible on white space, omitting thousands separators
	//	places: Number|String?
	//		number of decimal places to accept: Infinity, a positive number, or
	//		a range "n,m".  Defined by pattern or Infinity if pattern not provided.
	this.pattern = pattern;
	this.type = type;
	this.locale = locale;
	this.strict = strict;
	this.places = places;
}
=====*/
dojo.number.regexp = function(/*dojo.number.__RegexpOptions?*/options){
	//	summary:
	//		Builds the regular needed to parse a number
	//	description:
	//		Returns regular expression with positive and negative match, group
	//		and decimal separators
	return dojo.number._parseInfo(options).regexp; // String
}

dojo.number._parseInfo = function(/*Object?*/options){
	options = options || {};
	var locale = dojo.i18n.normalizeLocale(options.locale);
	var bundle = dojo.i18n.getLocalization("dojo.cldr", "number", locale);
	var pattern = options.pattern || bundle[(options.type || "decimal") + "Format"];
//TODO: memoize?
	var group = bundle.group;
	var decimal = bundle.decimal;
	var factor = 1;

	if(pattern.indexOf('%') != -1){
		factor /= 100;
	}else if(pattern.indexOf('\u2030') != -1){
		factor /= 1000; // per mille
	}else{
		var isCurrency = pattern.indexOf('\u00a4') != -1;
		if(isCurrency){
			group = bundle.currencyGroup || group;
			decimal = bundle.currencyDecimal || decimal;
		}
	}

	//TODO: handle quoted escapes
	var patternList = pattern.split(';');
	if(patternList.length == 1){
		patternList.push("-" + patternList[0]);
	}

	var re = dojo.regexp.buildGroupRE(patternList, function(pattern){
		pattern = "(?:"+dojo.regexp.escapeString(pattern, '.')+")";
		return pattern.replace(dojo.number._numberPatternRE, function(format){
			var flags = {
				signed: false,
				separator: options.strict ? group : [group,""],
				fractional: options.fractional,
				decimal: decimal,
				exponent: false};
			var parts = format.split('.');
			var places = options.places;
			if(parts.length == 1 || places === 0){flags.fractional = false;}
			else{
				if(places === undefined){ places = options.pattern ? parts[1].lastIndexOf('0')+1 : Infinity; }
				if(places && options.fractional == undefined){flags.fractional = true;} // required fractional, unless otherwise specified
				if(!options.places && (places < parts[1].length)){ places += "," + parts[1].length; }
				flags.places = places;
			}
			var groups = parts[0].split(',');
			if(groups.length>1){
				flags.groupSize = groups.pop().length;
				if(groups.length>1){
					flags.groupSize2 = groups.pop().length;
				}
			}
			return "("+dojo.number._realNumberRegexp(flags)+")";
		});
	}, true);

	if(isCurrency){
		// substitute the currency symbol for the placeholder in the pattern
		re = re.replace(/([\s\xa0]*)(\u00a4{1,3})([\s\xa0]*)/g, function(match, before, target, after){
			var prop = ["symbol", "currency", "displayName"][target.length-1];
			var symbol = dojo.regexp.escapeString(options[prop] || options.currency || "");
			before = before ? "[\\s\\xa0]" : "";
			after = after ? "[\\s\\xa0]" : "";
			if(!options.strict){
				if(before){before += "*";}
				if(after){after += "*";}
				return "(?:"+before+symbol+after+")?";
			}
			return before+symbol+after;
		});
	}

//TODO: substitute localized sign/percent/permille/etc.?

	// normalize whitespace and return
	return {regexp: re.replace(/[\xa0 ]/g, "[\\s\\xa0]"), group: group, decimal: decimal, factor: factor}; // Object
}

/*=====
dojo.number.__ParseOptions = function(){
	//	pattern: String?
	//		override [formatting pattern](http://www.unicode.org/reports/tr35/#Number_Format_Patterns)
	//		with this string.  Default value is based on locale.  Overriding this property will defeat
	//		localization.
	//	type: String?
	//		choose a format type based on the locale from the following:
	//		decimal, scientific (not yet supported), percent, currency. decimal by default.
	//	locale: String?
	//		override the locale used to determine formatting rules
	//	strict: Boolean?
	//		strict parsing, false by default.  Strict parsing requires input as produced by the format() method.
	//		Non-strict is more permissive, e.g. flexible on white space, omitting thousands separators
	this.pattern = pattern;
	this.type = type;
	this.locale = locale;
	this.strict = strict;
}
=====*/
dojo.number.parse = function(/*String*/expression, /*dojo.number.__ParseOptions?*/options){
	// summary:
	//		Convert a properly formatted string to a primitive Number, using
	//		locale-specific settings.
	// description:
	//		Create a Number from a string using a known localized pattern.
	//		Formatting patterns are chosen appropriate to the locale
	//		and follow the syntax described by
	//		[unicode.org TR35](http://www.unicode.org/reports/tr35/#Number_Format_Patterns)
	// expression:
	//		A string representation of a Number
	var info = dojo.number._parseInfo(options);
	var results = (new RegExp("^"+info.regexp+"$")).exec(expression);
	if(!results){
		return NaN; //NaN
	}
	var absoluteMatch = results[1]; // match for the positive expression
	if(!results[1]){
		if(!results[2]){
			return NaN; //NaN
		}
		// matched the negative pattern
		absoluteMatch =results[2];
		info.factor *= -1;
	}

	// Transform it to something Javascript can parse as a number.  Normalize
	// decimal point and strip out group separators or alternate forms of whitespace
	absoluteMatch = absoluteMatch.
		replace(new RegExp("["+info.group + "\\s\\xa0"+"]", "g"), "").
		replace(info.decimal, ".");
	// Adjust for negative sign, percent, etc. as necessary
	return absoluteMatch * info.factor; //Number
};

/*=====
dojo.number.__RealNumberRegexpFlags = function(){
	//	places: Number?
	//		The integer number of decimal places or a range given as "n,m".  If
	//		not given, the decimal part is optional and the number of places is
	//		unlimited.
	//	decimal: String?
	//		A string for the character used as the decimal point.  Default
	//		is ".".
	//	fractional: Boolean?|Array?
	//		Whether decimal places are used.  Can be true, false, or [true,
	//		false].  Default is [true, false] which means optional.
	//	exponent: Boolean?|Array?
	//		Express in exponential notation.  Can be true, false, or [true,
	//		false]. Default is [true, false], (i.e. will match if the
	//		exponential part is present are not).
	//	eSigned: Boolean?|Array?
	//		The leading plus-or-minus sign on the exponent.  Can be true,
	//		false, or [true, false].  Default is [true, false], (i.e. will
	//		match if it is signed or unsigned).  flags in regexp.integer can be
	//		applied.
	this.places = places;
	this.decimal = decimal;
	this.fractional = fractional;
	this.exponent = exponent;
	this.eSigned = eSigned;
}
=====*/

dojo.number._realNumberRegexp = function(/*dojo.number.__RealNumberRegexpFlags?*/flags){
	// summary:
	//		Builds a regular expression to match a real number in exponential
	//		notation

	// assign default values to missing paramters
	flags = flags || {};
	//TODO: use mixin instead?
	if(!("places" in flags)){ flags.places = Infinity; }
	if(typeof flags.decimal != "string"){ flags.decimal = "."; }
	if(!("fractional" in flags) || /^0/.test(flags.places)){ flags.fractional = [true, false]; }
	if(!("exponent" in flags)){ flags.exponent = [true, false]; }
	if(!("eSigned" in flags)){ flags.eSigned = [true, false]; }

	// integer RE
	var integerRE = dojo.number._integerRegexp(flags);

	// decimal RE
	var decimalRE = dojo.regexp.buildGroupRE(flags.fractional,
		function(q){
			var re = "";
			if(q && (flags.places!==0)){
				re = "\\" + flags.decimal;
				if(flags.places == Infinity){ 
					re = "(?:" + re + "\\d+)?"; 
				}else{
					re += "\\d{" + flags.places + "}"; 
				}
			}
			return re;
		},
		true
	);

	// exponent RE
	var exponentRE = dojo.regexp.buildGroupRE(flags.exponent,
		function(q){ 
			if(q){ return "([eE]" + dojo.number._integerRegexp({ signed: flags.eSigned}) + ")"; }
			return ""; 
		}
	);

	// real number RE
	var realRE = integerRE + decimalRE;
	// allow for decimals without integers, e.g. .25
	if(decimalRE){realRE = "(?:(?:"+ realRE + ")|(?:" + decimalRE + "))";}
	return realRE + exponentRE; // String
};

/*=====
dojo.number.__IntegerRegexpFlags = function(){
	//	signed: Boolean?
	//		The leading plus-or-minus sign. Can be true, false, or `[true,false]`.
	//		Default is `[true, false]`, (i.e. will match if it is signed
	//		or unsigned).
	//	separator: String?
	//		The character used as the thousands separator. Default is no
	//		separator. For more than one symbol use an array, e.g. `[",", ""]`,
	//		makes ',' optional.
	//	groupSize: Number?
	//		group size between separators
	//	groupSize2: Number?
	//		second grouping, where separators 2..n have a different interval than the first separator (for India)
	this.signed = signed;
	this.separator = separator;
	this.groupSize = groupSize;
	this.groupSize2 = groupSize2;
}
=====*/

dojo.number._integerRegexp = function(/*dojo.number.__IntegerRegexpFlags?*/flags){
	// summary: 
	//		Builds a regular expression that matches an integer

	// assign default values to missing paramters
	flags = flags || {};
	if(!("signed" in flags)){ flags.signed = [true, false]; }
	if(!("separator" in flags)){
		flags.separator = "";
	}else if(!("groupSize" in flags)){
		flags.groupSize = 3;
	}
	// build sign RE
	var signRE = dojo.regexp.buildGroupRE(flags.signed,
		function(q){ return q ? "[-+]" : ""; },
		true
	);

	// number RE
	var numberRE = dojo.regexp.buildGroupRE(flags.separator,
		function(sep){
			if(!sep){
				return "(?:\\d+)";
			}

			sep = dojo.regexp.escapeString(sep);
			if(sep == " "){ sep = "\\s"; }
			else if(sep == "\xa0"){ sep = "\\s\\xa0"; }

			var grp = flags.groupSize, grp2 = flags.groupSize2;
			//TODO: should we continue to enforce that numbers with separators begin with 1-9?  See #6933
			if(grp2){
				var grp2RE = "(?:0|[1-9]\\d{0," + (grp2-1) + "}(?:[" + sep + "]\\d{" + grp2 + "})*[" + sep + "]\\d{" + grp + "})";
				return ((grp-grp2) > 0) ? "(?:" + grp2RE + "|(?:0|[1-9]\\d{0," + (grp-1) + "}))" : grp2RE;
			}
			return "(?:0|[1-9]\\d{0," + (grp-1) + "}(?:[" + sep + "]\\d{" + grp + "})*)";
		},
		true
	);

	// integer RE
	return signRE + numberRE; // String
}

}

if(!dojo._hasResource["dijit.form.NumberTextBox"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit.form.NumberTextBox"] = true;
dojo.provide("dijit.form.NumberTextBox");




/*=====
dojo.declare(
	"dijit.form.NumberTextBox.__Constraints",
	[dijit.form.RangeBoundTextBox.__Constraints, dojo.number.__FormatOptions, dojo.number.__ParseOptions], {
	// summary:
	//		Specifies both the rules on valid/invalid values (minimum, maximum,
	//		number of required decimal places), and also formatting options for
	//		displaying the value when the field is not focused.
	// example:
	//		Minimum/maximum:
	//		To specify a field between 0 and 120:
	//	|		{min:0,max:120}
	//		To specify a field that must be an integer:
	//	|		{fractional:false}
	//		To specify a field where 0 to 3 decimal places are allowed on input,
	//		but after the field is blurred the value is displayed with 3 decimal places:
	//	|		{places:'0,3'}
});
=====*/

dojo.declare("dijit.form.NumberTextBoxMixin",
	null,
	{
		// summary:
		//		A mixin for all number textboxes
		// tags:
		//		protected

		// Override ValidationTextBox.regExpGen().... we use a reg-ex generating function rather
		// than a straight regexp to deal with locale (plus formatting options too?)
		regExpGen: dojo.number.regexp,

		/*=====
		// constraints: dijit.form.NumberTextBox.__Constraints
		//		Despite the name, this parameter specifies both constraints on the input
		//		(including minimum/maximum allowed values) as well as
		//		formatting options like places (the number of digits to display after
		//		the decimal point).   See `dijit.form.NumberTextBox.__Constraints` for details.
		constraints: {},
		======*/

		// value: Number
		//		The value of this NumberTextBox as a javascript Number (ie, not a String).
		//		If the displayed value is blank, the value is NaN, and if the user types in
		//		an gibberish value (like "hello world"), the value is undefined
		//		(i.e. attr('value') returns undefined).
		//
		//		Symetrically, attr('value', NaN) will clear the displayed value,
		//		whereas attr('value', undefined) will have no effect.
		value: NaN,

		// editOptions: [protected] Object
		//		Properties to mix into constraints when the value is being edited.
		//		This is here because we edit the number in the format "12345", which is
		//		different than the display value (ex: "12,345")
		editOptions: { pattern: '#.######' },

		/*=====
		_formatter: function(value, options){
			// summary:
			//		_formatter() is called by format().   It's the base routine for formatting a number,
			//		as a string, for example converting 12345 into "12,345".
			// value: Number
			//		The number to be converted into a string.
			// options: dojo.number.__FormatOptions?
			//		Formatting options
			// tags:
			//		protected extension

			return "12345";		// String
		},
		 =====*/
		_formatter: dojo.number.format,

		postMixInProperties: function(){
			var places = typeof this.constraints.places == "number"? this.constraints.places : 0;
			if(places){ places++; } // decimal rounding errors take away another digit of precision
			if(typeof this.constraints.max != "number"){
				this.constraints.max = 9 * Math.pow(10, 15-places);
			}
			if(typeof this.constraints.min != "number"){
				this.constraints.min = -9 * Math.pow(10, 15-places);
			}
			this.inherited(arguments);
		},

		_onFocus: function(){
			if(this.disabled){ return; }
			var val = this.attr('value');
			if(typeof val == "number" && !isNaN(val)){
				var formattedValue = this.format(val, this.constraints);
				if(formattedValue !== undefined){
					this.textbox.value = formattedValue;
				}
			}
			this.inherited(arguments);
		},

		format: function(/*Number*/ value, /*dojo.number.__FormatOptions*/ constraints){
			// summary:
			//		Formats the value as a Number, according to constraints.
			// tags:
			//		protected

			if(typeof value != "number"){ return String(value); }
			if(isNaN(value)){ return ""; }
			if(("rangeCheck" in this) && !this.rangeCheck(value, constraints)){ return String(value) }
			if(this.editOptions && this._focused){
				constraints = dojo.mixin({}, constraints, this.editOptions);
			}
			return this._formatter(value, constraints);
		},

		/*=====
		parse: function(value, constraints){
			// summary:
			//		Parses the string value as a Number, according to constraints.
			// value: String
			//		String representing a number
			// constraints: dojo.number.__ParseOptions
			//		Formatting options
			// tags:
			//		protected

			return 123.45;		// Number
		},
		=====*/
		parse: dojo.number.parse,

		_getDisplayedValueAttr: function(){
			var v = this.inherited(arguments);
			return isNaN(v) ? this.textbox.value : v;
		},

		filter: function(/*Number*/ value){
			// summary:
			//		This is called with both the display value (string), and the actual value (a number).
			//		When called with the actual value it does corrections so that '' etc. are represented as NaN.
			//		Otherwise it dispatches to the superclass's filter() method.
			//
			//		See `dijit.form.TextBox.filter` for more details.
			return (value === null || value === '' || value === undefined) ? NaN : this.inherited(arguments); // attr('value', null||''||undefined) should fire onChange(NaN)
		},

		serialize: function(/*Number*/ value, /*Object?*/options){
			// summary:
			//		Convert value (a Number) into a canonical string (ie, how the number literal is written in javascript/java/C/etc.)
			// tags:
			//		protected
			return (typeof value != "number" || isNaN(value)) ? '' : this.inherited(arguments);
		},

		_setValueAttr: function(/*Number*/ value, /*Boolean?*/ priorityChange, /*String?*/formattedValue){
			// summary:
			//		Hook so attr('value', ...) works.
			if(value !== undefined && formattedValue === undefined){
				if(typeof value == "number"){
					if(isNaN(value)){ formattedValue = '' }
					else if(("rangeCheck" in this) && !this.rangeCheck(value, this.constraints)){
						formattedValue = String(value);
					}
				}else if(!value){ // 0 processed in if branch above, ''|null|undefined flow thru here
					formattedValue = '';
					value = NaN;
				}else{ // non-numeric values
					formattedValue = String(value);
					value = undefined;
				}
			}
			this.inherited(arguments, [value, priorityChange, formattedValue]);
		},

		_getValueAttr: function(){
			// summary:
			//		Hook so attr('value') works.
			//		Returns Number, NaN for '', or undefined for unparsable text
			var v = this.inherited(arguments); // returns Number for all values accepted by parse() or NaN for all other displayed values

			// If the displayed value of the textbox is gibberish (ex: "hello world"), this.inherited() above
			// returns NaN; this if() branch converts the return value to undefined.
			// Returning undefined prevents user text from being overwritten when doing _setValueAttr(_getValueAttr()).
			// A blank displayed value is still returned as NaN.
			if(isNaN(v) && this.textbox.value !== ''){
				if(this.constraints.exponent !== false && /\de[-+]?|\d/i.test(this.textbox.value) && (new RegExp("^"+dojo.number._realNumberRegexp(dojo.mixin({}, this.constraints))+"$").test(this.textbox.value))){	// check for exponential notation that parse() rejected (erroneously?)
					var n = Number(this.textbox.value);
					return isNaN(n) ? undefined : n; // return exponential Number or undefined for random text (may not be possible to do with the above RegExp check)
				}else{
					return undefined; // gibberish
				}
			}else{
				return v; // Number or NaN for ''
			}
		},

		isValid: function(/*Boolean*/ isFocused){
			// Overrides dijit.form.RangeBoundTextBox.isValid to check that the editing-mode value is valid since
			// it may not be formatted according to the regExp vaidation rules
			if(!this._focused || this._isEmpty(this.textbox.value)){
				return this.inherited(arguments);
			}else{
				var v = this.attr('value');
				if(!isNaN(v) && this.rangeCheck(v, this.constraints)){
					if(this.constraints.exponent !== false && /\de[-+]?\d/i.test(this.textbox.value)){ // exponential, parse doesn't like it
						return true; // valid exponential number in range
					}else{
						return this.inherited(arguments);
					}
				}else{
					return false;
				}
			}
		}
	}
);

dojo.declare("dijit.form.NumberTextBox",
	[dijit.form.RangeBoundTextBox,dijit.form.NumberTextBoxMixin],
	{
		// summary:
		//		A TextBox for entering numbers, with formatting and range checking
		// description:
		//		NumberTextBox is a textbox for entering and displaying numbers, supporting
		//		the following main features:
		//
		//			1. Enforce minimum/maximum allowed values (as well as enforcing that the user types
		//				a number rather than a random string)
		//			2. NLS support (altering roles of comma and dot as "thousands-separator" and "decimal-point"
		//				depending on locale).
		//			3. Separate modes for editing the value and displaying it, specifically that
		//				the thousands separator character (typically comma) disappears when editing
		//				but reappears after the field is blurred.
		//			4. Formatting and constraints regarding the number of places (digits after the decimal point)
		//				allowed on input, and number of places displayed when blurred (see `constraints` parameter).
	}
);

}

if(!dojo._hasResource["dijit.form.NumberSpinner"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit.form.NumberSpinner"] = true;
dojo.provide("dijit.form.NumberSpinner");




dojo.declare("dijit.form.NumberSpinner",
	[dijit.form._Spinner, dijit.form.NumberTextBoxMixin],
	{
	// summary:
	//		Extends NumberTextBox to add up/down arrows and pageup/pagedown for incremental change to the value
	//
	// description:
	//		A `dijit.form.NumberTextBox` extension to provide keyboard accessible value selection
	//		as well as icons for spinning direction. When using the keyboard, the typematic rules
	//		apply, meaning holding the key will gradually increarease or decrease the value and
	// 		accelerate.
	//
	// example:
	//	| new dijit.form.NumberSpinner({ constraints:{ max:300, min:100 }}, "someInput");

	adjust: function(/* Object */val, /* Number*/delta){
		// summary:
		//		Change Number val by the given amount
		// tags:
		//		protected

		var tc = this.constraints,
			v = isNaN(val),
			gotMax = !isNaN(tc.max),
			gotMin = !isNaN(tc.min)
		;
		if(v && delta != 0){ // blank or invalid value and they want to spin, so create defaults
			val = (delta > 0) ?
				gotMin ? tc.min : gotMax ? tc.max : 0 :
				gotMax ? this.constraints.max : gotMin ? tc.min : 0
			;
		}
		var newval = val + delta;
		if(v || isNaN(newval)){ return val; }
		if(gotMax && (newval > tc.max)){
			newval = tc.max;
		}
		if(gotMin && (newval < tc.min)){
			newval = tc.min;
		}
		return newval;
	},

	_onKeyPress: function(e){
		if((e.charOrCode == dojo.keys.HOME || e.charOrCode == dojo.keys.END) && !(e.ctrlKey || e.altKey || e.metaKey)
		&& typeof this.attr('value') != 'undefined' /* gibberish, so HOME and END are default editing keys*/){
			var value = this.constraints[(e.charOrCode == dojo.keys.HOME ? "min" : "max")];
			if(value){
				this._setValueAttr(value,true);
			}
			// eat home or end key whether we change the value or not
			dojo.stopEvent(e);
		}
	}

});

}

if(!dojo._hasResource["dijit._Contained"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit._Contained"] = true;
dojo.provide("dijit._Contained");

dojo.declare("dijit._Contained",
		null,
		{
			// summary:
			//		Mixin for widgets that are children of a container widget
			//
			// example:
			// | 	// make a basic custom widget that knows about it's parents
			// |	dojo.declare("my.customClass",[dijit._Widget,dijit._Contained],{});

			getParent: function(){
				// summary:
				//		Returns the parent widget of this widget, assuming the parent
				//		specifies isContainer
				var parent = dijit.getEnclosingWidget(this.domNode.parentNode);
				return parent && parent.isContainer ? parent : null;
			},

			_getSibling: function(/*String*/ which){
				// summary:
				//      Returns next or previous sibling
				// which:
				//      Either "next" or "previous"
				// tags:
				//      private
				var node = this.domNode;
				do{
					node = node[which+"Sibling"];
				}while(node && node.nodeType != 1);
				return node && dijit.byNode(node);	// dijit._Widget
			},

			getPreviousSibling: function(){
				// summary:
				//		Returns null if this is the first child of the parent,
				//		otherwise returns the next element sibling to the "left".

				return this._getSibling("previous"); // dijit._Widget
			},

			getNextSibling: function(){
				// summary:
				//		Returns null if this is the last child of the parent,
				//		otherwise returns the next element sibling to the "right".

				return this._getSibling("next"); // dijit._Widget
			},

			getIndexInParent: function(){
				// summary:
				//		Returns the index of this widget within its container parent.
				//		It returns -1 if the parent does not exist, or if the parent
				//		is not a dijit._Container

				var p = this.getParent();
				if(!p || !p.getIndexOfChild){
					return -1; // int
				}
				return p.getIndexOfChild(this); // int
			}
		}
	);


}

if(!dojo._hasResource["dijit.layout._LayoutWidget"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit.layout._LayoutWidget"] = true;
dojo.provide("dijit.layout._LayoutWidget");





dojo.declare("dijit.layout._LayoutWidget",
	[dijit._Widget, dijit._Container, dijit._Contained],
	{
		// summary:
		//		Base class for a _Container widget which is responsible for laying out its children.
		//		Widgets which mixin this code must define layout() to manage placement and sizing of the children.

		// baseClass: [protected extension] String
		//		This class name is applied to the widget's domNode
		//		and also may be used to generate names for sub nodes,
		//		for example dijitTabContainer-content.
		baseClass: "dijitLayoutContainer",

		// isLayoutContainer: [protected] Boolean
		//		Indicates that this widget is going to call resize() on its
		//		children widgets, setting their size, when they become visible.
		isLayoutContainer: true,

		postCreate: function(){
			dojo.addClass(this.domNode, "dijitContainer");
			dojo.addClass(this.domNode, this.baseClass);

			this.inherited(arguments);
		},

		startup: function(){
			// summary:
			//		Called after all the widgets have been instantiated and their
			//		dom nodes have been inserted somewhere under dojo.doc.body.
			//
			//		Widgets should override this method to do any initialization
			//		dependent on other widgets existing, and then call
			//		this superclass method to finish things off.
			//
			//		startup() in subclasses shouldn't do anything
			//		size related because the size of the widget hasn't been set yet.

			if(this._started){ return; }

			// Need to call inherited first - so that child widgets get started
			// up correctly
			this.inherited(arguments);

			// If I am a not being controlled by a parent layout widget...
			var parent = this.getParent && this.getParent()
			if(!(parent && parent.isLayoutContainer)){
				// Do recursive sizing and layout of all my descendants
				// (passing in no argument to resize means that it has to glean the size itself)
				this.resize();

				// Since my parent isn't a layout container, and my style *may be* width=height=100%
				// or something similar (either set directly or via a CSS class),
				// monitor when my size changes so that I can re-layout.
				// For browsers where I can't directly monitor when my size changes,
				// monitor when the viewport changes size, which *may* indicate a size change for me.
				this.connect(dojo.isIE ? this.domNode : dojo.global, 'onresize', function(){
					// Using function(){} closure to ensure no arguments to resize.
					this.resize();
				});
			}
		},

		resize: function(changeSize, resultSize){
			// summary:
			//		Call this to resize a widget, or after its size has changed.
			// description:
			//		Change size mode:
			//			When changeSize is specified, changes the marginBox of this widget
			//			and forces it to relayout its contents accordingly.
			//			changeSize may specify height, width, or both.
			//
			//			If resultSize is specified it indicates the size the widget will
			//			become after changeSize has been applied.
			//
			//		Notification mode:
			//			When changeSize is null, indicates that the caller has already changed
			//			the size of the widget, or perhaps it changed because the browser
			//			window was resized.  Tells widget to relayout its contents accordingly.
			//
			//			If resultSize is also specified it indicates the size the widget has
			//			become.
			//
			//		In either mode, this method also:
			//			1. Sets this._borderBox and this._contentBox to the new size of
			//				the widget.  Queries the current domNode size if necessary.
			//			2. Calls layout() to resize contents (and maybe adjust child widgets).
			//
			// changeSize: Object?
			//		Sets the widget to this margin-box size and position.
			//		May include any/all of the following properties:
			//	|	{w: int, h: int, l: int, t: int}
			//
			// resultSize: Object?
			//		The margin-box size of this widget after applying changeSize (if
			//		changeSize is specified).  If caller knows this size and
			//		passes it in, we don't need to query the browser to get the size.
			//	|	{w: int, h: int}

			var node = this.domNode;

			// set margin box size, unless it wasn't specified, in which case use current size
			if(changeSize){
				dojo.marginBox(node, changeSize);

				// set offset of the node
				if(changeSize.t){ node.style.top = changeSize.t + "px"; }
				if(changeSize.l){ node.style.left = changeSize.l + "px"; }
			}

			// If either height or width wasn't specified by the user, then query node for it.
			// But note that setting the margin box and then immediately querying dimensions may return
			// inaccurate results, so try not to depend on it.
			var mb = resultSize || {};
			dojo.mixin(mb, changeSize || {});	// changeSize overrides resultSize
			if( !("h" in mb) || !("w" in mb) ){
				mb = dojo.mixin(dojo.marginBox(node), mb);	// just use dojo.marginBox() to fill in missing values
			}

			// Compute and save the size of my border box and content box
			// (w/out calling dojo.contentBox() since that may fail if size was recently set)
			var cs = dojo.getComputedStyle(node);
			var me = dojo._getMarginExtents(node, cs);
			var be = dojo._getBorderExtents(node, cs);
			var bb = (this._borderBox = {
				w: mb.w - (me.w + be.w),
				h: mb.h - (me.h + be.h)
			});
			var pe = dojo._getPadExtents(node, cs);
			this._contentBox = {
				l: dojo._toPixelValue(node, cs.paddingLeft),
				t: dojo._toPixelValue(node, cs.paddingTop),
				w: bb.w - pe.w,
				h: bb.h - pe.h
			};

			// Callback for widget to adjust size of its children
			this.layout();
		},

		layout: function(){
			// summary:
			//		Widgets override this method to size and position their contents/children.
			//		When this is called this._contentBox is guaranteed to be set (see resize()).
			//
			//		This is called after startup(), and also when the widget's size has been
			//		changed.
			// tags:
			//		protected extension
		},

		_setupChild: function(/*dijit._Widget*/child){
			// summary:
			//		Common setup for initial children and children which are added after startup
			// tags:
			//		protected extension

			dojo.addClass(child.domNode, this.baseClass+"-child");
			if(child.baseClass){
				dojo.addClass(child.domNode, this.baseClass+"-"+child.baseClass);
			}
		},

		addChild: function(/*dijit._Widget*/ child, /*Integer?*/ insertIndex){
			// Overrides _Container.addChild() to call _setupChild()
			this.inherited(arguments);
			if(this._started){
				this._setupChild(child);
			}
		},

		removeChild: function(/*dijit._Widget*/ child){
			// Overrides _Container.removeChild() to remove class added by _setupChild()
			dojo.removeClass(child.domNode, this.baseClass+"-child");
			if(child.baseClass){
				dojo.removeClass(child.domNode, this.baseClass+"-"+child.baseClass);
			}
			this.inherited(arguments);
		}
	}
);

dijit.layout.marginBox2contentBox = function(/*DomNode*/ node, /*Object*/ mb){
	// summary:
	//		Given the margin-box size of a node, return its content box size.
	//		Functions like dojo.contentBox() but is more reliable since it doesn't have
	//		to wait for the browser to compute sizes.
	var cs = dojo.getComputedStyle(node);
	var me = dojo._getMarginExtents(node, cs);
	var pb = dojo._getPadBorderExtents(node, cs);
	return {
		l: dojo._toPixelValue(node, cs.paddingLeft),
		t: dojo._toPixelValue(node, cs.paddingTop),
		w: mb.w - (me.w + pb.w),
		h: mb.h - (me.h + pb.h)
	};
};

(function(){
	var capitalize = function(word){
		return word.substring(0,1).toUpperCase() + word.substring(1);
	};

	var size = function(widget, dim){
		// size the child
		widget.resize ? widget.resize(dim) : dojo.marginBox(widget.domNode, dim);

		// record child's size, but favor our own numbers when we have them.
		// the browser lies sometimes
		dojo.mixin(widget, dojo.marginBox(widget.domNode));
		dojo.mixin(widget, dim);
	};

	dijit.layout.layoutChildren = function(/*DomNode*/ container, /*Object*/ dim, /*Object[]*/ children){
		// summary
		//		Layout a bunch of child dom nodes within a parent dom node
		// container:
		//		parent node
		// dim:
		//		{l, t, w, h} object specifying dimensions of container into which to place children
		// children:
		//		an array like [ {domNode: foo, layoutAlign: "bottom" }, {domNode: bar, layoutAlign: "client"} ]

		// copy dim because we are going to modify it
		dim = dojo.mixin({}, dim);

		dojo.addClass(container, "dijitLayoutContainer");

		// Move "client" elements to the end of the array for layout.  a11y dictates that the author
		// needs to be able to put them in the document in tab-order, but this algorithm requires that
		// client be last.
		children = dojo.filter(children, function(item){ return item.layoutAlign != "client"; })
			.concat(dojo.filter(children, function(item){ return item.layoutAlign == "client"; }));

		// set positions/sizes
		dojo.forEach(children, function(child){
			var elm = child.domNode,
				pos = child.layoutAlign;

			// set elem to upper left corner of unused space; may move it later
			var elmStyle = elm.style;
			elmStyle.left = dim.l+"px";
			elmStyle.top = dim.t+"px";
			elmStyle.bottom = elmStyle.right = "auto";

			dojo.addClass(elm, "dijitAlign" + capitalize(pos));

			// set size && adjust record of remaining space.
			// note that setting the width of a <div> may affect its height.
			if(pos == "top" || pos == "bottom"){
				size(child, { w: dim.w });
				dim.h -= child.h;
				if(pos == "top"){
					dim.t += child.h;
				}else{
					elmStyle.top = dim.t + dim.h + "px";
				}
			}else if(pos == "left" || pos == "right"){
				size(child, { h: dim.h });
				dim.w -= child.w;
				if(pos == "left"){
					dim.l += child.w;
				}else{
					elmStyle.left = dim.l + dim.w + "px";
				}
			}else if(pos == "client"){
				size(child, dim);
			}
		});
	};

})();

}

if(!dojo._hasResource["dojo.cookie"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojo.cookie"] = true;
dojo.provide("dojo.cookie");



/*=====
dojo.__cookieProps = function(){
	//	expires: Date|String|Number?
	//		If a number, the number of days from today at which the cookie
	//		will expire. If a date, the date past which the cookie will expire.
	//		If expires is in the past, the cookie will be deleted.
	//		If expires is omitted or is 0, the cookie will expire when the browser closes. << FIXME: 0 seems to disappear right away? FF3.
	//	path: String?
	//		The path to use for the cookie.
	//	domain: String?
	//		The domain to use for the cookie.
	//	secure: Boolean?
	//		Whether to only send the cookie on secure connections
	this.expires = expires;
	this.path = path;
	this.domain = domain;
	this.secure = secure;
}
=====*/


dojo.cookie = function(/*String*/name, /*String?*/value, /*dojo.__cookieProps?*/props){
	//	summary: 
	//		Get or set a cookie.
	//	description:
	// 		If one argument is passed, returns the value of the cookie
	// 		For two or more arguments, acts as a setter.
	//	name:
	//		Name of the cookie
	//	value:
	//		Value for the cookie
	//	props: 
	//		Properties for the cookie
	//	example:
	//		set a cookie with the JSON-serialized contents of an object which
	//		will expire 5 days from now:
	//	|	dojo.cookie("configObj", dojo.toJson(config), { expires: 5 });
	//	
	//	example:
	//		de-serialize a cookie back into a JavaScript object:
	//	|	var config = dojo.fromJson(dojo.cookie("configObj"));
	//	
	//	example:
	//		delete a cookie:
	//	|	dojo.cookie("configObj", null, {expires: -1});
	var c = document.cookie;
	if(arguments.length == 1){
		var matches = c.match(new RegExp("(?:^|; )" + dojo.regexp.escapeString(name) + "=([^;]*)"));
		return matches ? decodeURIComponent(matches[1]) : undefined; // String or undefined
	}else{
		props = props || {};
// FIXME: expires=0 seems to disappear right away, not on close? (FF3)  Change docs?
		var exp = props.expires;
		if(typeof exp == "number"){ 
			var d = new Date();
			d.setTime(d.getTime() + exp*24*60*60*1000);
			exp = props.expires = d;
		}
		if(exp && exp.toUTCString){ props.expires = exp.toUTCString(); }

		value = encodeURIComponent(value);
		var updatedCookie = name + "=" + value, propName;
		for(propName in props){
			updatedCookie += "; " + propName;
			var propValue = props[propName];
			if(propValue !== true){ updatedCookie += "=" + propValue; }
		}
		document.cookie = updatedCookie;
	}
};

dojo.cookie.isSupported = function(){
	//	summary:
	//		Use to determine if the current browser supports cookies or not.
	//		
	//		Returns true if user allows cookies.
	//		Returns false if user doesn't allow cookies.

	if(!("cookieEnabled" in navigator)){
		this("__djCookieTest__", "CookiesAllowed");
		navigator.cookieEnabled = this("__djCookieTest__") == "CookiesAllowed";
		if(navigator.cookieEnabled){
			this("__djCookieTest__", "", {expires: -1});
		}
	}
	return navigator.cookieEnabled;
};

}

if(!dojo._hasResource["dijit.layout.BorderContainer"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit.layout.BorderContainer"] = true;
dojo.provide("dijit.layout.BorderContainer");




dojo.declare(
	"dijit.layout.BorderContainer",
	dijit.layout._LayoutWidget,
{
	// summary:
	//		Provides layout in up to 5 regions, a mandatory center with optional borders along its 4 sides.
	//
	// description:
	//		A BorderContainer is a box with a specified size, such as style="width: 500px; height: 500px;",
	//		that contains a child widget marked region="center" and optionally children widgets marked
	//		region equal to "top", "bottom", "leading", "trailing", "left" or "right".
	//		Children along the edges will be laid out according to width or height dimensions and may
	//		include optional splitters (splitter="true") to make them resizable by the user.  The remaining
	//		space is designated for the center region.
	//
	//		NOTE: Splitters must not be more than 50 pixels in width.
	//
	//		The outer size must be specified on the BorderContainer node.  Width must be specified for the sides
	//		and height for the top and bottom, respectively.  No dimensions should be specified on the center;
	//		it will fill the remaining space.  Regions named "leading" and "trailing" may be used just like
	//		"left" and "right" except that they will be reversed in right-to-left environments.
	//
	// example:
	// |	<div dojoType="dijit.layout.BorderContainer" design="sidebar" gutters="false"
	// |            style="width: 400px; height: 300px;">
	// |		<div dojoType="ContentPane" region="top">header text</div>
	// |		<div dojoType="ContentPane" region="right" splitter="true" style="width: 200px;">table of contents</div>
	// |		<div dojoType="ContentPane" region="center">client area</div>
	// |	</div>

	// design: String
	//		Which design is used for the layout:
	//			- "headline" (default) where the top and bottom extend
	//				the full width of the container
	//			- "sidebar" where the left and right sides extend from top to bottom.
	design: "headline",

	// gutters: Boolean
	//		Give each pane a border and margin.
	//		Margin determined by domNode.paddingLeft.
	//		When false, only resizable panes have a gutter (i.e. draggable splitter) for resizing.
	gutters: true,

	// liveSplitters: Boolean
	//		Specifies whether splitters resize as you drag (true) or only upon mouseup (false)
	liveSplitters: true,

	// persist: Boolean
	//		Save splitter positions in a cookie.
	persist: false,

	baseClass: "dijitBorderContainer",

	// _splitterClass: String
	// 		Optional hook to override the default Splitter widget used by BorderContainer
	_splitterClass: "dijit.layout._Splitter",

	postMixInProperties: function(){
		// change class name to indicate that BorderContainer is being used purely for
		// layout (like LayoutContainer) rather than for pretty formatting.
		if(!this.gutters){
			this.baseClass += "NoGutter";
		}
		this.inherited(arguments);
	},

	postCreate: function(){
		this.inherited(arguments);

		this._splitters = {};
		this._splitterThickness = {};
	},

	startup: function(){
		if(this._started){ return; }
		dojo.forEach(this.getChildren(), this._setupChild, this);
		this.inherited(arguments);
	},

	_setupChild: function(/*dijit._Widget*/ child){
		// Override _LayoutWidget._setupChild().

		var region = child.region;
		if(region){
			this.inherited(arguments);

			dojo.addClass(child.domNode, this.baseClass+"Pane");

			var ltr = this.isLeftToRight();
			if(region == "leading"){ region = ltr ? "left" : "right"; }
			if(region == "trailing"){ region = ltr ? "right" : "left"; }

			//FIXME: redundant?
			this["_"+region] = child.domNode;
			this["_"+region+"Widget"] = child;

			// Create draggable splitter for resizing pane,
			// or alternately if splitter=false but BorderContainer.gutters=true then
			// insert dummy div just for spacing
			if((child.splitter || this.gutters) && !this._splitters[region]){
				var _Splitter = dojo.getObject(child.splitter ? this._splitterClass : "dijit.layout._Gutter");
				var splitter = new _Splitter({
					container: this,
					child: child,
					region: region,
					live: this.liveSplitters
				});
				splitter.isSplitter = true;
				this._splitters[region] = splitter.domNode;
				dojo.place(this._splitters[region], child.domNode, "after");

				// Splitters arent added as Contained children, so we need to call startup explicitly
				splitter.startup();
			}
			child.region = region;
		}
	},

	_computeSplitterThickness: function(region){
		this._splitterThickness[region] = this._splitterThickness[region] ||
			dojo.marginBox(this._splitters[region])[(/top|bottom/.test(region) ? 'h' : 'w')];
	},

	layout: function(){
		// Implement _LayoutWidget.layout() virtual method.
		for(var region in this._splitters){ this._computeSplitterThickness(region); }
		this._layoutChildren();
	},

	addChild: function(/*dijit._Widget*/ child, /*Integer?*/ insertIndex){
		// Override _LayoutWidget.addChild().
		this.inherited(arguments);
		if(this._started){
			this.layout(); //OPT
		}
	},

	removeChild: function(/*dijit._Widget*/ child){
		// Override _LayoutWidget.removeChild().
		var region = child.region;
		var splitter = this._splitters[region];
		if(splitter){
			dijit.byNode(splitter).destroy();
			delete this._splitters[region];
			delete this._splitterThickness[region];
		}
		this.inherited(arguments);
		delete this["_"+region];
		delete this["_" +region+"Widget"];
		if(this._started){
			this._layoutChildren(child.region);
		}
		dojo.removeClass(child.domNode, this.baseClass+"Pane");
	},

	getChildren: function(){
		// Override _LayoutWidget.getChildren() to only return real children, not the splitters.
		return dojo.filter(this.inherited(arguments), function(widget){
			return !widget.isSplitter;
		});
	},

	getSplitter: function(/*String*/region){
		// summary:
		//		Returns the widget responsible for rendering the splitter associated with region
		var splitter = this._splitters[region];
		return splitter ? dijit.byNode(splitter) : null;
	},

	resize: function(newSize, currentSize){
		// Overrides _LayoutWidget.resize().

		// resetting potential padding to 0px to provide support for 100% width/height + padding
		// TODO: this hack doesn't respect the box model and is a temporary fix
		if(!this.cs || !this.pe){
			var node = this.domNode;
			this.cs = dojo.getComputedStyle(node);
			this.pe = dojo._getPadExtents(node, this.cs);
			this.pe.r = dojo._toPixelValue(node, this.cs.paddingRight);
			this.pe.b = dojo._toPixelValue(node, this.cs.paddingBottom);

			dojo.style(node, "padding", "0px");
		}

		this.inherited(arguments);
	},

	_layoutChildren: function(/*String?*/changedRegion){
		// summary:
		//		This is the main routine for setting size/position of each child

		if(!this._borderBox || !this._borderBox.h){
			// We are currently hidden, or we haven't been sized by our parent yet.
			// Abort.   Someone will resize us later.
			return;
		}

		var sidebarLayout = (this.design == "sidebar");
		var topHeight = 0, bottomHeight = 0, leftWidth = 0, rightWidth = 0;
		var topStyle = {}, leftStyle = {}, rightStyle = {}, bottomStyle = {},
			centerStyle = (this._center && this._center.style) || {};

		var changedSide = /left|right/.test(changedRegion);

		var layoutSides = !changedRegion || (!changedSide && !sidebarLayout);
		var layoutTopBottom = !changedRegion || (changedSide && sidebarLayout);

		// Ask browser for width/height of side panes.
		// Would be nice to cache this but height can change according to width
		// (because words wrap around).  I don't think width will ever change though
		// (except when the user drags a splitter).
		if(this._top){
			topStyle = layoutTopBottom && this._top.style;
			topHeight = dojo.marginBox(this._top).h;
		}
		if(this._left){
			leftStyle = layoutSides && this._left.style;
			leftWidth = dojo.marginBox(this._left).w;
		}
		if(this._right){
			rightStyle = layoutSides && this._right.style;
			rightWidth = dojo.marginBox(this._right).w;
		}
		if(this._bottom){
			bottomStyle = layoutTopBottom && this._bottom.style;
			bottomHeight = dojo.marginBox(this._bottom).h;
		}

		var splitters = this._splitters;
		var topSplitter = splitters.top, bottomSplitter = splitters.bottom,
			leftSplitter = splitters.left, rightSplitter = splitters.right;
		var splitterThickness = this._splitterThickness;
		var topSplitterThickness = splitterThickness.top || 0,
			leftSplitterThickness = splitterThickness.left || 0,
			rightSplitterThickness = splitterThickness.right || 0,
			bottomSplitterThickness = splitterThickness.bottom || 0;

		// Check for race condition where CSS hasn't finished loading, so
		// the splitter width == the viewport width (#5824)
		if(leftSplitterThickness > 50 || rightSplitterThickness > 50){
			setTimeout(dojo.hitch(this, function(){
				// Results are invalid.  Clear them out.
				this._splitterThickness = {};

				for(var region in this._splitters){
					this._computeSplitterThickness(region);
				}
				this._layoutChildren();
			}), 50);
			return false;
		}

		var pe = this.pe;

		var splitterBounds = {
			left: (sidebarLayout ? leftWidth + leftSplitterThickness: 0) + pe.l + "px",
			right: (sidebarLayout ? rightWidth + rightSplitterThickness: 0) + pe.r + "px"
		};

		if(topSplitter){
			dojo.mixin(topSplitter.style, splitterBounds);
			topSplitter.style.top = topHeight + pe.t + "px";
		}

		if(bottomSplitter){
			dojo.mixin(bottomSplitter.style, splitterBounds);
			bottomSplitter.style.bottom = bottomHeight + pe.b + "px";
		}

		splitterBounds = {
			top: (sidebarLayout ? 0 : topHeight + topSplitterThickness) + pe.t + "px",
			bottom: (sidebarLayout ? 0 : bottomHeight + bottomSplitterThickness) + pe.b + "px"
		};

		if(leftSplitter){
			dojo.mixin(leftSplitter.style, splitterBounds);
			leftSplitter.style.left = leftWidth + pe.l + "px";
		}

		if(rightSplitter){
			dojo.mixin(rightSplitter.style, splitterBounds);
			rightSplitter.style.right = rightWidth + pe.r +	"px";
		}

		dojo.mixin(centerStyle, {
			top: pe.t + topHeight + topSplitterThickness + "px",
			left: pe.l + leftWidth + leftSplitterThickness + "px",
			right: pe.r + rightWidth + rightSplitterThickness + "px",
			bottom: pe.b + bottomHeight + bottomSplitterThickness + "px"
		});

		var bounds = {
			top: sidebarLayout ? pe.t + "px" : centerStyle.top,
			bottom: sidebarLayout ? pe.b + "px" : centerStyle.bottom
		};
		dojo.mixin(leftStyle, bounds);
		dojo.mixin(rightStyle, bounds);
		leftStyle.left = pe.l + "px"; rightStyle.right = pe.r + "px"; topStyle.top = pe.t + "px"; bottomStyle.bottom = pe.b + "px";
		if(sidebarLayout){
			topStyle.left = bottomStyle.left = leftWidth + leftSplitterThickness + pe.l + "px";
			topStyle.right = bottomStyle.right = rightWidth + rightSplitterThickness + pe.r + "px";
		}else{
			topStyle.left = bottomStyle.left = pe.l + "px";
			topStyle.right = bottomStyle.right = pe.r + "px";
		}

		// More calculations about sizes of panes
		var containerHeight = this._borderBox.h - pe.t - pe.b,
			middleHeight = containerHeight - ( topHeight + topSplitterThickness + bottomHeight + bottomSplitterThickness),
			sidebarHeight = sidebarLayout ? containerHeight : middleHeight;

		var containerWidth = this._borderBox.w - pe.l - pe.r,
			middleWidth = containerWidth - (leftWidth + leftSplitterThickness + rightWidth + rightSplitterThickness),
			sidebarWidth = sidebarLayout ? middleWidth : containerWidth;

		// New margin-box size of each pane
		var dim = {
			top:	{ w: sidebarWidth, h: topHeight },
			bottom: { w: sidebarWidth, h: bottomHeight },
			left:	{ w: leftWidth, h: sidebarHeight },
			right:	{ w: rightWidth, h: sidebarHeight },
			center:	{ h: middleHeight, w: middleWidth }
		};

		// Nodes in IE<8 don't respond to t/l/b/r, and TEXTAREA doesn't respond in any browser
		var janky = dojo.isIE < 8 || (dojo.isIE && dojo.isQuirks) || dojo.some(this.getChildren(), function(child){
			return child.domNode.tagName == "TEXTAREA" || child.domNode.tagName == "INPUT";
		});
		if(janky){
			// Set the size of the children the old fashioned way, by setting
			// CSS width and height

			var resizeWidget = function(widget, changes, result){
				if(widget){
					(widget.resize ? widget.resize(changes, result) : dojo.marginBox(widget.domNode, changes));
				}
			};

			if(leftSplitter){ leftSplitter.style.height = sidebarHeight; }
			if(rightSplitter){ rightSplitter.style.height = sidebarHeight; }
			resizeWidget(this._leftWidget, {h: sidebarHeight}, dim.left);
			resizeWidget(this._rightWidget, {h: sidebarHeight}, dim.right);

			if(topSplitter){ topSplitter.style.width = sidebarWidth; }
			if(bottomSplitter){ bottomSplitter.style.width = sidebarWidth; }
			resizeWidget(this._topWidget, {w: sidebarWidth}, dim.top);
			resizeWidget(this._bottomWidget, {w: sidebarWidth}, dim.bottom);

			resizeWidget(this._centerWidget, dim.center);
		}else{
			// We've already sized the children by setting style.top/bottom/left/right...
			// Now just need to call resize() on those children telling them their new size,
			// so they can re-layout themselves

			// Calculate which panes need a notification
			var resizeList = {};
			if(changedRegion){
				resizeList[changedRegion] = resizeList.center = true;
				if(/top|bottom/.test(changedRegion) && this.design != "sidebar"){
					resizeList.left = resizeList.right = true;
				}else if(/left|right/.test(changedRegion) && this.design == "sidebar"){
					resizeList.top = resizeList.bottom = true;
				}
			}

			dojo.forEach(this.getChildren(), function(child){
				if(child.resize && (!changedRegion || child.region in resizeList)){
					child.resize(null, dim[child.region]);
				}
			}, this);
		}
	},

	destroy: function(){
		for(var region in this._splitters){
			var splitter = this._splitters[region];
			dijit.byNode(splitter).destroy();
			dojo.destroy(splitter);
		}
		delete this._splitters;
		delete this._splitterThickness;
		this.inherited(arguments);
	}
});

// This argument can be specified for the children of a BorderContainer.
// Since any widget can be specified as a LayoutContainer child, mix it
// into the base widget class.  (This is a hack, but it's effective.)
dojo.extend(dijit._Widget, {
	// region: [const] String
	//		Parameter for children of `dijit.layout.BorderContainer`.
	//		Values: "top", "bottom", "leading", "trailing", "left", "right", "center".
	//		See the `dijit.layout.BorderContainer` description for details.
	region: '',

	// splitter: [const] Boolean
	//		Parameter for child of `dijit.layout.BorderContainer` where region != "center".
	//		If true, enables user to resize the widget by putting a draggable splitter between
	//		this widget and the region=center widget.
	splitter: false,

	// minSize: [const] Number
	//		Parameter for children of `dijit.layout.BorderContainer`.
	//		Specifies a minimum size (in pixels) for this widget when resized by a splitter.
	minSize: 0,

	// maxSize: [const] Number
	//		Parameter for children of `dijit.layout.BorderContainer`.
	//		Specifies a maximum size (in pixels) for this widget when resized by a splitter.
	maxSize: Infinity
});



dojo.declare("dijit.layout._Splitter", [ dijit._Widget, dijit._Templated ],
{
	// summary:
	//		A draggable spacer between two items in a `dijit.layout.BorderContainer`.
	// description:
	//		This is instantiated by `dijit.layout.BorderContainer`.  Users should not
	//		create it directly.
	// tags:
	//		private

/*=====
 	// container: [const] dijit.layout.BorderContainer
 	//		Pointer to the parent BorderContainer
	container: null,

	// child: [const] dijit.layout._LayoutWidget
	//		Pointer to the pane associated with this splitter
	child: null,

	// region: String
	//		Region of pane associated with this splitter.
	//		"top", "bottom", "left", "right".
	region: null,
=====*/

	// live: [const] Boolean
	//		If true, the child's size changes and the child widget is redrawn as you drag the splitter;
	//		otherwise, the size doesn't change until you drop the splitter (by mouse-up)
	live: true,

	templateString: '<div class="dijitSplitter" dojoAttachEvent="onkeypress:_onKeyPress,onmousedown:_startDrag,onmouseenter:_onMouse,onmouseleave:_onMouse" tabIndex="0" waiRole="separator"><div class="dijitSplitterThumb"></div></div>',

	postCreate: function(){
		this.inherited(arguments);
		this.horizontal = /top|bottom/.test(this.region);
		dojo.addClass(this.domNode, "dijitSplitter" + (this.horizontal ? "H" : "V"));
//		dojo.addClass(this.child.domNode, "dijitSplitterPane");
//		dojo.setSelectable(this.domNode, false); //TODO is this necessary?

		this._factor = /top|left/.test(this.region) ? 1 : -1;

		this._cookieName = this.container.id + "_" + this.region;
		if(this.container.persist){
			// restore old size
			var persistSize = dojo.cookie(this._cookieName);
			if(persistSize){
				this.child.domNode.style[this.horizontal ? "height" : "width"] = persistSize;
			}
		}
	},

	_computeMaxSize: function(){
		// summary:
		//		Compute the maximum size that my corresponding pane can be set to

		var dim = this.horizontal ? 'h' : 'w',
			thickness = this.container._splitterThickness[this.region];
			
		// Get DOMNode of opposite pane, if an opposite pane exists.
		// Ex: if I am the _Splitter for the left pane, then get the right pane.
		var flip = {left:'right', right:'left', top:'bottom', bottom:'top', leading:'trailing', trailing:'leading'},
			oppNode = this.container["_" + flip[this.region]];
		
		// I can expand up to the edge of the opposite pane, or if there's no opposite pane, then to
		// edge of BorderContainer
		var available = dojo.contentBox(this.container.domNode)[dim] -
				(oppNode ? dojo.marginBox(oppNode)[dim] : 0) -
				20 - thickness * 2;

		return Math.min(this.child.maxSize, available);
	},

	_startDrag: function(e){
		if(!this.cover){
			this.cover = dojo.doc.createElement('div');
			dojo.addClass(this.cover, "dijitSplitterCover");
			dojo.place(this.cover, this.child.domNode, "after");
		}
		dojo.addClass(this.cover, "dijitSplitterCoverActive");

		// Safeguard in case the stop event was missed.  Shouldn't be necessary if we always get the mouse up.
		if(this.fake){ dojo.destroy(this.fake); }
		if(!(this._resize = this.live)){ //TODO: disable live for IE6?
			// create fake splitter to display at old position while we drag
			(this.fake = this.domNode.cloneNode(true)).removeAttribute("id");
			dojo.addClass(this.domNode, "dijitSplitterShadow");
			dojo.place(this.fake, this.domNode, "after");
		}
		dojo.addClass(this.domNode, "dijitSplitterActive");
		dojo.addClass(this.domNode, "dijitSplitter" + (this.horizontal ? "H" : "V") + "Active");
		if(this.fake){
			dojo.removeClass(this.fake, "dijitSplitterHover");
			dojo.removeClass(this.fake, "dijitSplitter" + (this.horizontal ? "H" : "V") + "Hover");
		}

		//Performance: load data info local vars for onmousevent function closure
		var factor = this._factor,
			max = this._computeMaxSize(),
			min = this.child.minSize || 20,
			isHorizontal = this.horizontal,
			axis = isHorizontal ? "pageY" : "pageX",
			pageStart = e[axis],
			splitterStyle = this.domNode.style,
			dim = isHorizontal ? 'h' : 'w',
			childStart = dojo.marginBox(this.child.domNode)[dim],
			region = this.region,
			splitterStart = parseInt(this.domNode.style[region], 10),
			resize = this._resize,
			mb = {},
			childNode = this.child.domNode,
			layoutFunc = dojo.hitch(this.container, this.container._layoutChildren),
			de = dojo.doc.body;

		this._handlers = (this._handlers || []).concat([
			dojo.connect(de, "onmousemove", this._drag = function(e, forceResize){
				var delta = e[axis] - pageStart,
					childSize = factor * delta + childStart,
					boundChildSize = Math.max(Math.min(childSize, max), min);

				if(resize || forceResize){
					mb[dim] = boundChildSize;
					// TODO: inefficient; we set the marginBox here and then immediately layoutFunc() needs to query it
					dojo.marginBox(childNode, mb);
					layoutFunc(region);
				}
				splitterStyle[region] = factor * delta + splitterStart + (boundChildSize - childSize) + "px";
			}),
			dojo.connect(dojo.doc, "ondragstart", dojo.stopEvent),
			dojo.connect(dojo.body(), "onselectstart", dojo.stopEvent),
			dojo.connect(de, "onmouseup", this, "_stopDrag")
		]);
		dojo.stopEvent(e);
	},

	_onMouse: function(e){
		var o = (e.type == "mouseover" || e.type == "mouseenter");
		dojo.toggleClass(this.domNode, "dijitSplitterHover", o);
		dojo.toggleClass(this.domNode, "dijitSplitter" + (this.horizontal ? "H" : "V") + "Hover", o);
	},

	_stopDrag: function(e){
		try{
			if(this.cover){
				dojo.removeClass(this.cover, "dijitSplitterCoverActive");
			}
			if(this.fake){ dojo.destroy(this.fake); }
			dojo.removeClass(this.domNode, "dijitSplitterActive");
			dojo.removeClass(this.domNode, "dijitSplitter" + (this.horizontal ? "H" : "V") + "Active");
			dojo.removeClass(this.domNode, "dijitSplitterShadow");
			this._drag(e); //TODO: redundant with onmousemove?
			this._drag(e, true);
		}finally{
			this._cleanupHandlers();
			delete this._drag;
		}

		if(this.container.persist){
			dojo.cookie(this._cookieName, this.child.domNode.style[this.horizontal ? "height" : "width"], {expires:365});
		}
	},

	_cleanupHandlers: function(){
		dojo.forEach(this._handlers, dojo.disconnect);
		delete this._handlers;
	},

	_onKeyPress: function(/*Event*/ e){
		// should we apply typematic to this?
		this._resize = true;
		var horizontal = this.horizontal;
		var tick = 1;
		var dk = dojo.keys;
		switch(e.charOrCode){
			case horizontal ? dk.UP_ARROW : dk.LEFT_ARROW:
				tick *= -1;
//				break;
			case horizontal ? dk.DOWN_ARROW : dk.RIGHT_ARROW:
				break;
			default:
//				this.inherited(arguments);
				return;
		}
		var childSize = dojo.marginBox(this.child.domNode)[ horizontal ? 'h' : 'w' ] + this._factor * tick;
		var mb = {};
		mb[ this.horizontal ? "h" : "w"] = Math.max(Math.min(childSize, this._computeMaxSize()), this.child.minSize);
		dojo.marginBox(this.child.domNode, mb);
		this.container._layoutChildren(this.region);
		dojo.stopEvent(e);
	},

	destroy: function(){
		this._cleanupHandlers();
		delete this.child;
		delete this.container;
		delete this.cover;
		delete this.fake;
		this.inherited(arguments);
	}
});

dojo.declare("dijit.layout._Gutter", [dijit._Widget, dijit._Templated ],
{
	// summary:
	// 		Just a spacer div to separate side pane from center pane.
	//		Basically a trick to lookup the gutter/splitter width from the theme.
	// description:
	//		Instantiated by `dijit.layout.BorderContainer`.  Users should not
	//		create directly.
	// tags:
	//		private

	templateString: '<div class="dijitGutter" waiRole="presentation"></div>',

	postCreate: function(){
		this.horizontal = /top|bottom/.test(this.region);
		dojo.addClass(this.domNode, "dijitGutter" + (this.horizontal ? "H" : "V"));
	}
});

}

if(!dojo._hasResource["dojo.html"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojo.html"] = true;
dojo.provide("dojo.html");

// the parser might be needed..
 

(function(){ // private scope, sort of a namespace

	// idCounter is incremented with each instantiation to allow asignment of a unique id for tracking, logging purposes
	var idCounter = 0, 
		d = dojo;
	
	dojo.html._secureForInnerHtml = function(/*String*/ cont){
		// summary:
		//		removes !DOCTYPE and title elements from the html string.
		// 
		//		khtml is picky about dom faults, you can't attach a style or <title> node as child of body
		//		must go into head, so we need to cut out those tags
		//	cont:
		//		An html string for insertion into the dom
		//	
		return cont.replace(/(?:\s*<!DOCTYPE\s[^>]+>|<title[^>]*>[\s\S]*?<\/title>)/ig, ""); // String
	};

/*====
	dojo.html._emptyNode = function(node){
		// summary:
		//		removes all child nodes from the given node
		//	node: DOMNode
		//		the parent element
	};
=====*/
	dojo.html._emptyNode = dojo.empty;

	dojo.html._setNodeContent = function(/* DomNode */ node, /* String|DomNode|NodeList */ cont){
		// summary:
		//		inserts the given content into the given node
		//	node:
		//		the parent element
		//	content:
		//		the content to be set on the parent element. 
		//		This can be an html string, a node reference or a NodeList, dojo.NodeList, Array or other enumerable list of nodes
		
		// always empty
		d.empty(node);

		if(cont) {
			if(typeof cont == "string") {
				cont = d._toDom(cont, node.ownerDocument);
			}
			if(!cont.nodeType && d.isArrayLike(cont)) {
				// handle as enumerable, but it may shrink as we enumerate it
				for(var startlen=cont.length, i=0; i<cont.length; i=startlen==cont.length ? i+1 : 0) {
					d.place( cont[i], node, "last");
				}
			} else {
				// pass nodes, documentFragments and unknowns through to dojo.place
				d.place(cont, node, "last");
			}
		}

		// return DomNode
		return node;
	};

	// we wrap up the content-setting operation in a object
	dojo.declare("dojo.html._ContentSetter", null, 
		{
			// node: DomNode|String
			//		An node which will be the parent element that we set content into
			node: "",

			// content: String|DomNode|DomNode[]
			//		The content to be placed in the node. Can be an HTML string, a node reference, or a enumerable list of nodes
			content: "",
			
			// id: String?
			//		Usually only used internally, and auto-generated with each instance 
			id: "",

			// cleanContent: Boolean
			//		Should the content be treated as a full html document, 
			//		and the real content stripped of <html>, <body> wrapper before injection
			cleanContent: false,
			
			// extractContent: Boolean
			//		Should the content be treated as a full html document, and the real content stripped of <html>, <body> wrapper before injection
			extractContent: false,

			// parseContent: Boolean
			//		Should the node by passed to the parser after the new content is set
			parseContent: false,
			
			// lifecyle methods
			constructor: function(/* Object */params, /* String|DomNode */node){
				//	summary:
				//		Provides a configurable, extensible object to wrap the setting on content on a node
				//		call the set() method to actually set the content..
 
				// the original params are mixed directly into the instance "this"
				dojo.mixin(this, params || {});

				// give precedence to params.node vs. the node argument
				// and ensure its a node, not an id string
				node = this.node = dojo.byId( this.node || node );
	
				if(!this.id){
					this.id = [
						"Setter",
						(node) ? node.id || node.tagName : "", 
						idCounter++
					].join("_");
				}

				if(! (this.node || node)){
					new Error(this.declaredClass + ": no node provided to " + this.id);
				}
			},
			set: function(/* String|DomNode|NodeList? */ cont, /* Object? */ params){
				// summary:
				//		front-end to the set-content sequence 
				//	cont:
				//		An html string, node or enumerable list of nodes for insertion into the dom
				//		If not provided, the object's content property will be used
				if(undefined !== cont){
					this.content = cont;
				}
				// in the re-use scenario, set needs to be able to mixin new configuration
				if(params){
					this._mixin(params);
				}

				this.onBegin();
				this.setContent();
				this.onEnd();

				return this.node;
			},
			setContent: function(){
				// summary:
				//		sets the content on the node 

				var node = this.node; 
				if(!node) {
					console.error("setContent given no node");
				}
				try{
					node = dojo.html._setNodeContent(node, this.content);
				}catch(e){
					// check if a domfault occurs when we are appending this.errorMessage
					// like for instance if domNode is a UL and we try append a DIV
	
					// FIXME: need to allow the user to provide a content error message string
					var errMess = this.onContentError(e); 
					try{
						node.innerHTML = errMess;
					}catch(e){
						console.error('Fatal ' + this.declaredClass + '.setContent could not change content due to '+e.message, e);
					}
				}
				// always put back the node for the next method
				this.node = node; // DomNode
			},
			
			empty: function() {
				// summary
				//	cleanly empty out existing content

				// destroy any widgets from a previous run
				// NOTE: if you dont want this you'll need to empty 
				// the parseResults array property yourself to avoid bad things happenning
				if(this.parseResults && this.parseResults.length) {
					dojo.forEach(this.parseResults, function(w) {
						if(w.destroy){
							w.destroy();
						}
					});
					delete this.parseResults;
				}
				// this is fast, but if you know its already empty or safe, you could 
				// override empty to skip this step
				dojo.html._emptyNode(this.node);
			},
	
			onBegin: function(){
				// summary
				//		Called after instantiation, but before set(); 
				//		It allows modification of any of the object properties 
				//		- including the node and content provided - before the set operation actually takes place
				//		This default implementation checks for cleanContent and extractContent flags to 
				//		optionally pre-process html string content
				var cont = this.content;
	
				if(dojo.isString(cont)){
					if(this.cleanContent){
						cont = dojo.html._secureForInnerHtml(cont);
					}
  
					if(this.extractContent){
						var match = cont.match(/<body[^>]*>\s*([\s\S]+)\s*<\/body>/im);
						if(match){ cont = match[1]; }
					}
				}

				// clean out the node and any cruft associated with it - like widgets
				this.empty();
				
				this.content = cont;
				return this.node; /* DomNode */
			},
	
			onEnd: function(){
				// summary
				//		Called after set(), when the new content has been pushed into the node
				//		It provides an opportunity for post-processing before handing back the node to the caller
				//		This default implementation checks a parseContent flag to optionally run the dojo parser over the new content
				if(this.parseContent){
					// populates this.parseResults if you need those..
					this._parse();
				}
				return this.node; /* DomNode */
			},
	
			tearDown: function(){
				// summary
				//		manually reset the Setter instance if its being re-used for example for another set()
				// description
				//		tearDown() is not called automatically. 
				//		In normal use, the Setter instance properties are simply allowed to fall out of scope
				//		but the tearDown method can be called to explicitly reset this instance.
				delete this.parseResults; 
				delete this.node; 
				delete this.content; 
			},
  
			onContentError: function(err){
				return "Error occured setting content: " + err; 
			},
			
			_mixin: function(params){
				// mix properties/methods into the instance
				// TODO: the intention with tearDown is to put the Setter's state 
				// back to that of the original constructor (vs. deleting/resetting everything regardless of ctor params)
				// so we could do something here to move the original properties aside for later restoration
				var empty = {}, key;
				for(key in params){
					if(key in empty){ continue; }
					// TODO: here's our opportunity to mask the properties we dont consider configurable/overridable
					// .. but history shows we'll almost always guess wrong
					this[key] = params[key]; 
				}
			},
			_parse: function(){
				// summary: 
				//		runs the dojo parser over the node contents, storing any results in this.parseResults
				//		Any errors resulting from parsing are passed to _onError for handling

				var rootNode = this.node;
				try{
					// store the results (widgets, whatever) for potential retrieval
					this.parseResults = dojo.parser.parse(rootNode, true);
				}catch(e){
					this._onError('Content', e, "Error parsing in _ContentSetter#"+this.id);
				}
			},
  
			_onError: function(type, err, consoleText){
				// summary:
				//		shows user the string that is returned by on[type]Error
				//		overide/implement on[type]Error and return your own string to customize
				var errText = this['on' + type + 'Error'].call(this, err);
				if(consoleText){
					console.error(consoleText, err);
				}else if(errText){ // a empty string won't change current content
					dojo.html._setNodeContent(this.node, errText, true);
				}
			}
	}); // end dojo.declare()

	dojo.html.set = function(/* DomNode */ node, /* String|DomNode|NodeList */ cont, /* Object? */ params){
			// summary:
			//		inserts (replaces) the given content into the given node. dojo.place(cont, node, "only")
			//		may be a better choice for simple HTML insertion.
			// description:
			//		Unless you need to use the params capabilities of this method, you should use
			//		dojo.place(cont, node, "only"). dojo.place() has more robust support for injecting
			//		an HTML string into the DOM, but it only handles inserting an HTML string as DOM
			//		elements, or inserting a DOM node. dojo.place does not handle NodeList insertions
			//		or the other capabilities as defined by the params object for this method.
			//	node:
			//		the parent element that will receive the content
			//	cont:
			//		the content to be set on the parent element. 
			//		This can be an html string, a node reference or a NodeList, dojo.NodeList, Array or other enumerable list of nodes
			//	params: 
			//		Optional flags/properties to configure the content-setting. See dojo.html._ContentSetter
			//	example:
			//		A safe string/node/nodelist content replacement/injection with hooks for extension
			//		Example Usage: 
			//		dojo.html.set(node, "some string"); 
			//		dojo.html.set(node, contentNode, {options}); 
			//		dojo.html.set(node, myNode.childNodes, {options}); 
		if(undefined == cont){
			console.warn("dojo.html.set: no cont argument provided, using empty string");
			cont = "";
		}	
		if(!params){
			// simple and fast
			return dojo.html._setNodeContent(node, cont, true);
		}else{ 
			// more options but slower
			// note the arguments are reversed in order, to match the convention for instantiation via the parser
			var op = new dojo.html._ContentSetter(dojo.mixin( 
					params, 
					{ content: cont, node: node } 
			));
			return op.set();
		}
	};
})();

}

if(!dojo._hasResource["dijit.layout.ContentPane"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit.layout.ContentPane"] = true;
dojo.provide("dijit.layout.ContentPane");



	// for dijit.layout.marginBox2contentBox()






dojo.declare(
	"dijit.layout.ContentPane", dijit._Widget,
{
	// summary:
	//		A widget that acts as a container for mixed HTML and widgets, and includes an Ajax interface
	// description:
	//		A widget that can be used as a stand alone widget
	//		or as a base class for other widgets.
	//
	//		Handles replacement of document fragment using either external uri or javascript
	//		generated markup or DOM content, instantiating widgets within that content.
	//		Don't confuse it with an iframe, it only needs/wants document fragments.
	//		It's useful as a child of LayoutContainer, SplitContainer, or TabContainer.
	//		But note that those classes can contain any widget as a child.
	// example:
	//		Some quick samples:
	//		To change the innerHTML use .attr('content', '<b>new content</b>')
	//
	//		Or you can send it a NodeList, .attr('content', dojo.query('div [class=selected]', userSelection))
	//		please note that the nodes in NodeList will copied, not moved
	//
	//		To do a ajax update use .attr('href', url)

	// href: String
	//		The href of the content that displays now.
	//		Set this at construction if you want to load data externally when the
	//		pane is shown.  (Set preload=true to load it immediately.)
	//		Changing href after creation doesn't have any effect; use attr('href', ...);
	href: "",

/*=====
	// content: String || DomNode || NodeList || dijit._Widget
	//		The innerHTML of the ContentPane.
	//		Note that the initialization parameter / argument to attr("content", ...)
	//		can be a String, DomNode, Nodelist, or _Widget.
	content: "",
=====*/

	// extractContent: Boolean
	//		Extract visible content from inside of <body> .... </body>.
	//		I.e., strip <html> and <head> (and it's contents) from the href
	extractContent: false,

	// parseOnLoad: Boolean
	//		Parse content and create the widgets, if any.
	parseOnLoad: true,

	// preventCache: Boolean
	//		Prevent caching of data from href's by appending a timestamp to the href.
	preventCache: false,

	// preload: Boolean
	//		Force load of data on initialization even if pane is hidden.
	preload: false,

	// refreshOnShow: Boolean
	//		Refresh (re-download) content when pane goes from hidden to shown
	refreshOnShow: false,

	// loadingMessage: String
	//		Message that shows while downloading
	loadingMessage: "<span class='dijitContentPaneLoading'>${loadingState}</span>",

	// errorMessage: String
	//		Message that shows if an error occurs
	errorMessage: "<span class='dijitContentPaneError'>${errorState}</span>",

	// isLoaded: [readonly] Boolean
	//		True if the ContentPane has data in it, either specified
	//		during initialization (via href or inline content), or set
	//		via attr('content', ...) / attr('href', ...)
	//
	//		False if it doesn't have any content, or if ContentPane is
	//		still in the process of downloading href.
	isLoaded: false,

	baseClass: "dijitContentPane",

	// doLayout: Boolean
	//		- false - don't adjust size of children
	//		- true - if there is a single visible child widget, set it's size to
	//				however big the ContentPane is
	doLayout: true,

	// ioArgs: Object
	//		Parameters to pass to xhrGet() request, for example:
	// |	<div dojoType="dijit.layout.ContentPane" href="./bar" ioArgs="{timeout: 500}">
	ioArgs: {},

	// isContainer: [protected] Boolean
	//		Indicates that this widget acts as a "parent" to the descendant widgets.
	//		When the parent is started it will call startup() on the child widgets.
	//		See also `isLayoutContainer`.
	isContainer: true,

	// isLayoutContainer: [protected] Boolean
	//		Indicates that this widget will call resize() on it's child widgets
	//		when they become visible.
	isLayoutContainer: true,

	// onLoadDeferred: [readonly] dojo.Deferred
	//		This is the `dojo.Deferred` returned by attr('href', ...) and refresh().
	//		Calling onLoadDeferred.addCallback() or addErrback() registers your
	//		callback to be called only once, when the prior attr('href', ...) call or
	//		the initial href parameter to the constructor finishes loading.
	//
	//		This is different than an onLoad() handler which gets called any time any href is loaded.
	onLoadDeferred: null,

	// Override _Widget's attributeMap because we don't want the title attribute (used to specify
	// tab labels) to be copied to ContentPane.domNode... otherwise a tooltip shows up over the
	// entire pane.
	attributeMap: dojo.delegate(dijit._Widget.prototype.attributeMap, {
		title: []
	}),

	postMixInProperties: function(){
		this.inherited(arguments);
		var messages = dojo.i18n.getLocalization("dijit", "loading", this.lang);
		this.loadingMessage = dojo.string.substitute(this.loadingMessage, messages);
		this.errorMessage = dojo.string.substitute(this.errorMessage, messages);

		// Detect if we were initialized with data
		if(!this.href && this.srcNodeRef && this.srcNodeRef.innerHTML){
			this.isLoaded = true;
		}
	},

	buildRendering: function(){
		// Overrides Widget.buildRendering().
		// Since we have no template we need to set this.containerNode ourselves.
		// For subclasses of ContentPane do have a template, does nothing.
		this.inherited(arguments);
		if(!this.containerNode){
			// make getDescendants() work
			this.containerNode = this.domNode;
		}
	},

	postCreate: function(){
		// remove the title attribute so it doesn't show up when hovering
		// over a node
		this.domNode.title = "";

		if(!dojo.attr(this.domNode,"role")){
			dijit.setWaiRole(this.domNode, "group");
		}

		dojo.addClass(this.domNode, this.baseClass);
	},

	startup: function(){
		// summary:
		//		See `dijit.layout._LayoutWidget.startup` for description.
		//		Although ContentPane doesn't extend _LayoutWidget, it does implement
		//		the same API.
		if(this._started){ return; }

		var parent = dijit._Contained.prototype.getParent.call(this);
		this._childOfLayoutWidget = parent && parent.isLayoutContainer;

		// I need to call resize() on my child/children (when I become visible), unless
		// I'm the child of a layout widget in which case my parent will call resize() on me and I'll do it then.
		this._needLayout = !this._childOfLayoutWidget;

		if(this.isLoaded){
			dojo.forEach(this.getChildren(), function(child){
				child.startup();
			});
		}

		if(this._isShown() || this.preload){
			this._onShow();
		}

		this.inherited(arguments);
	},

	_checkIfSingleChild: function(){
		// summary:
		//		Test if we have exactly one visible widget as a child,
		//		and if so assume that we are a container for that widget,
		//		and should propogate startup() and resize() calls to it.
		//		Skips over things like data stores since they aren't visible.

		var childNodes = dojo.query("> *", this.containerNode).filter(function(node){
				return node.tagName !== "SCRIPT"; // or a regexp for hidden elements like script|area|map|etc..
			}),
			childWidgetNodes = childNodes.filter(function(node){
				return dojo.hasAttr(node, "dojoType") || dojo.hasAttr(node, "widgetId");
			}),
			candidateWidgets = dojo.filter(childWidgetNodes.map(dijit.byNode), function(widget){
				return widget && widget.domNode && widget.resize;
			});

		if(
			// all child nodes are widgets
			childNodes.length == childWidgetNodes.length &&

			// all but one are invisible (like dojo.data)
			candidateWidgets.length == 1
		){
			this._singleChild = candidateWidgets[0];
		}else{
			delete this._singleChild;
		}

		// So we can set overflow: hidden to avoid a safari bug w/scrollbars showing up (#9449)
		dojo.toggleClass(this.containerNode, this.baseClass + "SingleChild", !!this._singleChild);
	},

	setHref: function(/*String|Uri*/ href){
		// summary:
		//		Deprecated.   Use attr('href', ...) instead.
		dojo.deprecated("dijit.layout.ContentPane.setHref() is deprecated. Use attr('href', ...) instead.", "", "2.0");
		return this.attr("href", href);
	},
	_setHrefAttr: function(/*String|Uri*/ href){
		// summary:
		//		Hook so attr("href", ...) works.
		// description:
		//		Reset the (external defined) content of this pane and replace with new url
		//		Note: It delays the download until widget is shown if preload is false.
		//	href:
		//		url to the page you want to get, must be within the same domain as your mainpage

		// Cancel any in-flight requests (an attr('href') will cancel any in-flight attr('href', ...))
		this.cancel();

		this.onLoadDeferred = new dojo.Deferred(dojo.hitch(this, "cancel"));

		this.href = href;

		// _setHrefAttr() is called during creation and by the user, after creation.
		// only in the second case do we actually load the URL; otherwise it's done in startup()
		if(this._created && (this.preload || this._isShown())){
			this._load();
		}else{
			// Set flag to indicate that href needs to be loaded the next time the
			// ContentPane is made visible
			this._hrefChanged = true;
		}

		return this.onLoadDeferred;		// dojo.Deferred
	},

	setContent: function(/*String|DomNode|Nodelist*/data){
		// summary:
		//		Deprecated.   Use attr('content', ...) instead.
		dojo.deprecated("dijit.layout.ContentPane.setContent() is deprecated.  Use attr('content', ...) instead.", "", "2.0");
		this.attr("content", data);
	},
	_setContentAttr: function(/*String|DomNode|Nodelist*/data){
		// summary:
		//		Hook to make attr("content", ...) work.
		//		Replaces old content with data content, include style classes from old content
		//	data:
		//		the new Content may be String, DomNode or NodeList
		//
		//		if data is a NodeList (or an array of nodes) nodes are copied
		//		so you can import nodes from another document implicitly

		// clear href so we can't run refresh and clear content
		// refresh should only work if we downloaded the content
		this.href = "";

		// Cancel any in-flight requests (an attr('content') will cancel any in-flight attr('href', ...))
		this.cancel();

		// Even though user is just setting content directly, still need to define an onLoadDeferred
		// because the _onLoadHandler() handler is still getting called from setContent()
		this.onLoadDeferred = new dojo.Deferred(dojo.hitch(this, "cancel"));

		this._setContent(data || "");

		this._isDownloaded = false; // mark that content is from a attr('content') not an attr('href')

		return this.onLoadDeferred; 	// dojo.Deferred
	},
	_getContentAttr: function(){
		// summary:
		//		Hook to make attr("content") work
		return this.containerNode.innerHTML;
	},

	cancel: function(){
		// summary:
		//		Cancels an in-flight download of content
		if(this._xhrDfd && (this._xhrDfd.fired == -1)){
			this._xhrDfd.cancel();
		}
		delete this._xhrDfd; // garbage collect

		this.onLoadDeferred = null;
	},

	uninitialize: function(){
		if(this._beingDestroyed){
			this.cancel();
		}
		this.inherited(arguments);
	},

	destroyRecursive: function(/*Boolean*/ preserveDom){
		// summary:
		//		Destroy the ContentPane and its contents

		// if we have multiple controllers destroying us, bail after the first
		if(this._beingDestroyed){
			return;
		}
		this.inherited(arguments);
	},

	resize: function(changeSize, resultSize){
		// summary:
		//		See `dijit.layout._LayoutWidget.resize` for description.
		//		Although ContentPane doesn't extend _LayoutWidget, it does implement
		//		the same API.

		// For the TabContainer --> BorderContainer --> ContentPane case, _onShow() is
		// never called, so resize() is our trigger to do the initial href download.
		if(!this._wasShown){
			this._onShow();
		}

		this._resizeCalled = true;

		// Set margin box size, unless it wasn't specified, in which case use current size.
		if(changeSize){
			dojo.marginBox(this.domNode, changeSize);
		}

		// Compute content box size of containerNode in case we [later] need to size our single child.
		var cn = this.containerNode;
		if(cn === this.domNode){
			// If changeSize or resultSize was passed to this method and this.containerNode ==
			// this.domNode then we can compute the content-box size without querying the node,
			// which is more reliable (similar to LayoutWidget.resize) (see for example #9449).
			var mb = resultSize || {};
			dojo.mixin(mb, changeSize || {}); // changeSize overrides resultSize
			if(!("h" in mb) || !("w" in mb)){
				mb = dojo.mixin(dojo.marginBox(cn), mb); // just use dojo.marginBox() to fill in missing values
			}
			this._contentBox = dijit.layout.marginBox2contentBox(cn, mb);
		}else{
			this._contentBox = dojo.contentBox(cn);
		}

		// Make my children layout, or size my single child widget
		this._layoutChildren();
	},

	_isShown: function(){
		// summary:
		//		Returns true if the content is currently shown.
		// description:
		//		If I am a child of a layout widget then it actually returns true if I've ever been visible,
		//		not whether I'm currently visible, since that's much faster than tracing up the DOM/widget
		//		tree every call, and at least solves the performance problem on page load by deferring loading
		//		hidden ContentPanes until they are first shown

		if(this._childOfLayoutWidget){
			// If we are TitlePane, etc - we return that only *IF* we've been resized
			if(this._resizeCalled && "open" in this){
				return this.open;
			}
			return this._resizeCalled;
		}else if("open" in this){
			return this.open;		// for TitlePane, etc.
		}else{
			// TODO: with _childOfLayoutWidget check maybe this branch no longer necessary?
			var node = this.domNode;
			return (node.style.display != 'none') && (node.style.visibility != 'hidden') && !dojo.hasClass(node, "dijitHidden");
		}
	},

	_onShow: function(){
		// summary:
		//		Called when the ContentPane is made visible
		// description:
		//		For a plain ContentPane, this is called on initialization, from startup().
		//		If the ContentPane is a hidden pane of a TabContainer etc., then it's
		//		called whenever the pane is made visible.
		//
		//		Does necessary processing, including href download and layout/resize of
		//		child widget(s)

		if(this.href){
			if(!this._xhrDfd && // if there's an href that isn't already being loaded
				(!this.isLoaded || this._hrefChanged || this.refreshOnShow)
			){
				this.refresh();
			}
		}else{
			// If we are the child of a layout widget then the layout widget will call resize() on
			// us, and then we will size our child/children.   Otherwise, we need to do it now.
			if(!this._childOfLayoutWidget && this._needLayout){
				// If a layout has been scheduled for when we become visible, do it now
				this._layoutChildren();
			}
		}

		this.inherited(arguments);

		// Need to keep track of whether ContentPane has been shown (which is different than
		// whether or not it's currently visible).
		this._wasShown = true;
	},

	refresh: function(){
		// summary:
		//		[Re]download contents of href and display
		// description:
		//		1. cancels any currently in-flight requests
		//		2. posts "loading..." message
		//		3. sends XHR to download new data

		// Cancel possible prior in-flight request
		this.cancel();

		this.onLoadDeferred = new dojo.Deferred(dojo.hitch(this, "cancel"));
		this._load();
		return this.onLoadDeferred;
	},

	_load: function(){
		// summary:
		//		Load/reload the href specified in this.href

		// display loading message
		this._setContent(this.onDownloadStart(), true);

		var self = this;
		var getArgs = {
			preventCache: (this.preventCache || this.refreshOnShow),
			url: this.href,
			handleAs: "text"
		};
		if(dojo.isObject(this.ioArgs)){
			dojo.mixin(getArgs, this.ioArgs);
		}

		var hand = (this._xhrDfd = (this.ioMethod || dojo.xhrGet)(getArgs));

		hand.addCallback(function(html){
			try{
				self._isDownloaded = true;
				self._setContent(html, false);
				self.onDownloadEnd();
			}catch(err){
				self._onError('Content', err); // onContentError
			}
			delete self._xhrDfd;
			return html;
		});

		hand.addErrback(function(err){
			if(!hand.canceled){
				// show error message in the pane
				self._onError('Download', err); // onDownloadError
			}
			delete self._xhrDfd;
			return err;
		});

		// Remove flag saying that a load is needed
		delete this._hrefChanged;
	},

	_onLoadHandler: function(data){
		// summary:
		//		This is called whenever new content is being loaded
		this.isLoaded = true;
		try{
			this.onLoadDeferred.callback(data);
			this.onLoad(data);
		}catch(e){
			console.error('Error '+this.widgetId+' running custom onLoad code: ' + e.message);
		}
	},

	_onUnloadHandler: function(){
		// summary:
		//		This is called whenever the content is being unloaded
		this.isLoaded = false;
		try{
			this.onUnload();
		}catch(e){
			console.error('Error '+this.widgetId+' running custom onUnload code: ' + e.message);
		}
	},

	destroyDescendants: function(){
		// summary:
		//		Destroy all the widgets inside the ContentPane and empty containerNode

		// Make sure we call onUnload (but only when the ContentPane has real content)
		if(this.isLoaded){
			this._onUnloadHandler();
		}

		// Even if this.isLoaded == false there might still be a "Loading..." message
		// to erase, so continue...

		// For historical reasons we need to delete all widgets under this.containerNode,
		// even ones that the user has created manually.
		var setter = this._contentSetter;
		dojo.forEach(this.getChildren(), function(widget){
			if(widget.destroyRecursive){
				widget.destroyRecursive();
			}
		});
		if(setter){
			// Most of the widgets in setter.parseResults have already been destroyed, but
			// things like Menu that have been moved to <body> haven't yet
			dojo.forEach(setter.parseResults, function(widget){
				if(widget.destroyRecursive && widget.domNode && widget.domNode.parentNode == dojo.body()){
					widget.destroyRecursive();
				}
			});
			delete setter.parseResults;
		}

		// And then clear away all the DOM nodes
		dojo.html._emptyNode(this.containerNode);

		// Delete any state information we have about current contents
		delete this._singleChild;
	},

	_setContent: function(cont, isFakeContent){
		// summary:
		//		Insert the content into the container node

		// first get rid of child widgets
		this.destroyDescendants();

		// dojo.html.set will take care of the rest of the details
		// we provide an override for the error handling to ensure the widget gets the errors
		// configure the setter instance with only the relevant widget instance properties
		// NOTE: unless we hook into attr, or provide property setters for each property,
		// we need to re-configure the ContentSetter with each use
		var setter = this._contentSetter;
		if(! (setter && setter instanceof dojo.html._ContentSetter)){
			setter = this._contentSetter = new dojo.html._ContentSetter({
				node: this.containerNode,
				_onError: dojo.hitch(this, this._onError),
				onContentError: dojo.hitch(this, function(e){
					// fires if a domfault occurs when we are appending this.errorMessage
					// like for instance if domNode is a UL and we try append a DIV
					var errMess = this.onContentError(e);
					try{
						this.containerNode.innerHTML = errMess;
					}catch(e){
						console.error('Fatal '+this.id+' could not change content due to '+e.message, e);
					}
				})/*,
				_onError */
			});
		};

		var setterParams = dojo.mixin({
			cleanContent: this.cleanContent,
			extractContent: this.extractContent,
			parseContent: this.parseOnLoad
		}, this._contentSetterParams || {});

		dojo.mixin(setter, setterParams);

		setter.set( (dojo.isObject(cont) && cont.domNode) ? cont.domNode : cont );

		// setter params must be pulled afresh from the ContentPane each time
		delete this._contentSetterParams;

		if(!isFakeContent){
			// Startup each top level child widget (and they will start their children, recursively)
			dojo.forEach(this.getChildren(), function(child){
				// The parser has already called startup on all widgets *without* a getParent() method
				if(!this.parseOnLoad || child.getParent){
					child.startup();
				}
			}, this);

			// Call resize() on each of my child layout widgets,
			// or resize() on my single child layout widget...
			// either now (if I'm currently visible)
			// or when I become visible
			this._scheduleLayout();

			this._onLoadHandler(cont);
		}
	},

	_onError: function(type, err, consoleText){
		this.onLoadDeferred.errback(err);

		// shows user the string that is returned by on[type]Error
		// overide on[type]Error and return your own string to customize
		var errText = this['on' + type + 'Error'].call(this, err);
		if(consoleText){
			console.error(consoleText, err);
		}else if(errText){// a empty string won't change current content
			this._setContent(errText, true);
		}
	},

	_scheduleLayout: function(){
		// summary:
		//		Call resize() on each of my child layout widgets, either now
		//		(if I'm currently visible) or when I become visible
		if(this._isShown()){
			this._layoutChildren();
		}else{
			this._needLayout = true;
		}
	},

	_layoutChildren: function(){
		// summary:
		//		Since I am a Container widget, each of my children expects me to
		//		call resize() or layout() on them.
		// description:
		//		Should be called on initialization and also whenever we get new content
		//		(from an href, or from attr('content', ...))... but deferred until
		//		the ContentPane is visible

		if(this.doLayout){
			this._checkIfSingleChild();
		}

		if(this._singleChild && this._singleChild.resize){
			var cb = this._contentBox || dojo.contentBox(this.containerNode);

			// note: if widget has padding this._contentBox will have l and t set,
			// but don't pass them to resize() or it will doubly-offset the child
			this._singleChild.resize({w: cb.w, h: cb.h});
		}else{
			// All my child widgets are independently sized (rather than matching my size),
			// but I still need to call resize() on each child to make it layout.
			dojo.forEach(this.getChildren(), function(widget){
				if(widget.resize){
					widget.resize();
				}
			});
		}
		delete this._needLayout;
	},

	// EVENT's, should be overide-able
	onLoad: function(data){
		// summary:
		//		Event hook, is called after everything is loaded and widgetified
		// tags:
		//		callback
	},

	onUnload: function(){
		// summary:
		//		Event hook, is called before old content is cleared
		// tags:
		//		callback
	},

	onDownloadStart: function(){
		// summary:
		//		Called before download starts.
		// description:
		//		The string returned by this function will be the html
		//		that tells the user we are loading something.
		//		Override with your own function if you want to change text.
		// tags:
		//		extension
		return this.loadingMessage;
	},

	onContentError: function(/*Error*/ error){
		// summary:
		//		Called on DOM faults, require faults etc. in content.
		//
		//		In order to display an error message in the pane, return
		//		the error message from this method, as an HTML string.
		//
		//		By default (if this method is not overriden), it returns
		//		nothing, so the error message is just printed to the console.
		// tags:
		//		extension
	},

	onDownloadError: function(/*Error*/ error){
		// summary:
		//		Called when download error occurs.
		//
		//		In order to display an error message in the pane, return
		//		the error message from this method, as an HTML string.
		//
		//		Default behavior (if this method is not overriden) is to display
		//		the error message inside the pane.
		// tags:
		//		extension
		return this.errorMessage;
	},

	onDownloadEnd: function(){
		// summary:
		//		Called when download is finished.
		// tags:
		//		callback
	}
});

}

if(!dojo._hasResource["dojo.fx.Toggler"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojo.fx.Toggler"] = true;
dojo.provide("dojo.fx.Toggler");

dojo.declare("dojo.fx.Toggler", null, {
	// summary:
	//		A simple `dojo.Animation` toggler API.
	//
	// description:
	//		class constructor for an animation toggler. It accepts a packed
	//		set of arguments about what type of animation to use in each
	//		direction, duration, etc. All available members are mixed into 
	//		these animations from the constructor (for example, `node`, 
	//		`showDuration`, `hideDuration`). 
	//
	// example:
	//	|	var t = new dojo.fx.Toggler({
	//	|		node: "nodeId",
	//	|		showDuration: 500,
	//	|		// hideDuration will default to "200"
	//	|		showFunc: dojo.fx.wipeIn, 
	//	|		// hideFunc will default to "fadeOut"
	//	|	});
	//	|	t.show(100); // delay showing for 100ms
	//	|	// ...time passes...
	//	|	t.hide();

	// node: DomNode
	//		the node to target for the showing and hiding animations
	node: null,

	// showFunc: Function
	//		The function that returns the `dojo.Animation` to show the node
	showFunc: dojo.fadeIn,

	// hideFunc: Function	
	//		The function that returns the `dojo.Animation` to hide the node
	hideFunc: dojo.fadeOut,

	// showDuration:
	//		Time in milliseconds to run the show Animation
	showDuration: 200,

	// hideDuration:
	//		Time in milliseconds to run the hide Animation
	hideDuration: 200,

	// FIXME: need a policy for where the toggler should "be" the next
	// time show/hide are called if we're stopped somewhere in the
	// middle.
	// FIXME: also would be nice to specify individual showArgs/hideArgs mixed into
	// each animation individually. 
	// FIXME: also would be nice to have events from the animations exposed/bridged

	/*=====
	_showArgs: null,
	_showAnim: null,

	_hideArgs: null,
	_hideAnim: null,

	_isShowing: false,
	_isHiding: false,
	=====*/

	constructor: function(args){
		var _t = this;

		dojo.mixin(_t, args);
		_t.node = args.node;
		_t._showArgs = dojo.mixin({}, args);
		_t._showArgs.node = _t.node;
		_t._showArgs.duration = _t.showDuration;
		_t.showAnim = _t.showFunc(_t._showArgs);

		_t._hideArgs = dojo.mixin({}, args);
		_t._hideArgs.node = _t.node;
		_t._hideArgs.duration = _t.hideDuration;
		_t.hideAnim = _t.hideFunc(_t._hideArgs);

		dojo.connect(_t.showAnim, "beforeBegin", dojo.hitch(_t.hideAnim, "stop", true));
		dojo.connect(_t.hideAnim, "beforeBegin", dojo.hitch(_t.showAnim, "stop", true));
	},

	show: function(delay){
		// summary: Toggle the node to showing
		// delay: Integer?
		//		Ammount of time to stall playing the show animation
		return this.showAnim.play(delay || 0);
	},

	hide: function(delay){
		// summary: Toggle the node to hidden
		// delay: Integer?
		//		Ammount of time to stall playing the hide animation
		return this.hideAnim.play(delay || 0);
	}
});

}

if(!dojo._hasResource["dojo.fx"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojo.fx"] = true;
dojo.provide("dojo.fx");
 // FIXME: remove this back-compat require in 2.0 
/*=====
dojo.fx = {
	// summary: Effects library on top of Base animations
};
=====*/
(function(){
	
	var d = dojo, 
		_baseObj = {
			_fire: function(evt, args){
				if(this[evt]){
					this[evt].apply(this, args||[]);
				}
				return this;
			}
		};

	var _chain = function(animations){
		this._index = -1;
		this._animations = animations||[];
		this._current = this._onAnimateCtx = this._onEndCtx = null;

		this.duration = 0;
		d.forEach(this._animations, function(a){
			this.duration += a.duration;
			if(a.delay){ this.duration += a.delay; }
		}, this);
	};
	d.extend(_chain, {
		_onAnimate: function(){
			this._fire("onAnimate", arguments);
		},
		_onEnd: function(){
			d.disconnect(this._onAnimateCtx);
			d.disconnect(this._onEndCtx);
			this._onAnimateCtx = this._onEndCtx = null;
			if(this._index + 1 == this._animations.length){
				this._fire("onEnd");
			}else{
				// switch animations
				this._current = this._animations[++this._index];
				this._onAnimateCtx = d.connect(this._current, "onAnimate", this, "_onAnimate");
				this._onEndCtx = d.connect(this._current, "onEnd", this, "_onEnd");
				this._current.play(0, true);
			}
		},
		play: function(/*int?*/ delay, /*Boolean?*/ gotoStart){
			if(!this._current){ this._current = this._animations[this._index = 0]; }
			if(!gotoStart && this._current.status() == "playing"){ return this; }
			var beforeBegin = d.connect(this._current, "beforeBegin", this, function(){
					this._fire("beforeBegin");
				}),
				onBegin = d.connect(this._current, "onBegin", this, function(arg){
					this._fire("onBegin", arguments);
				}),
				onPlay = d.connect(this._current, "onPlay", this, function(arg){
					this._fire("onPlay", arguments);
					d.disconnect(beforeBegin);
					d.disconnect(onBegin);
					d.disconnect(onPlay);
				});
			if(this._onAnimateCtx){
				d.disconnect(this._onAnimateCtx);
			}
			this._onAnimateCtx = d.connect(this._current, "onAnimate", this, "_onAnimate");
			if(this._onEndCtx){
				d.disconnect(this._onEndCtx);
			}
			this._onEndCtx = d.connect(this._current, "onEnd", this, "_onEnd");
			this._current.play.apply(this._current, arguments);
			return this;
		},
		pause: function(){
			if(this._current){
				var e = d.connect(this._current, "onPause", this, function(arg){
						this._fire("onPause", arguments);
						d.disconnect(e);
					});
				this._current.pause();
			}
			return this;
		},
		gotoPercent: function(/*Decimal*/percent, /*Boolean?*/ andPlay){
			this.pause();
			var offset = this.duration * percent;
			this._current = null;
			d.some(this._animations, function(a){
				if(a.duration <= offset){
					this._current = a;
					return true;
				}
				offset -= a.duration;
				return false;
			});
			if(this._current){
				this._current.gotoPercent(offset / this._current.duration, andPlay);
			}
			return this;
		},
		stop: function(/*boolean?*/ gotoEnd){
			if(this._current){
				if(gotoEnd){
					for(; this._index + 1 < this._animations.length; ++this._index){
						this._animations[this._index].stop(true);
					}
					this._current = this._animations[this._index];
				}
				var e = d.connect(this._current, "onStop", this, function(arg){
						this._fire("onStop", arguments);
						d.disconnect(e);
					});
				this._current.stop();
			}
			return this;
		},
		status: function(){
			return this._current ? this._current.status() : "stopped";
		},
		destroy: function(){
			if(this._onAnimateCtx){ d.disconnect(this._onAnimateCtx); }
			if(this._onEndCtx){ d.disconnect(this._onEndCtx); }
		}
	});
	d.extend(_chain, _baseObj);

	dojo.fx.chain = function(/*dojo.Animation[]*/ animations){
		// summary: 
		//		Chain a list of `dojo.Animation`s to run in sequence
		//
		// description:
		//		Return a `dojo.Animation` which will play all passed
		//		`dojo.Animation` instances in sequence, firing its own
		//		synthesized events simulating a single animation. (eg:
		//		onEnd of this animation means the end of the chain, 
		//		not the individual animations within)
		//
		// example:
		//	Once `node` is faded out, fade in `otherNode`
		//	|	dojo.fx.chain([
		//	|		dojo.fadeIn({ node:node }),
		//	|		dojo.fadeOut({ node:otherNode })
		//	|	]).play();
		//
		return new _chain(animations) // dojo.Animation
	};

	var _combine = function(animations){
		this._animations = animations||[];
		this._connects = [];
		this._finished = 0;

		this.duration = 0;
		d.forEach(animations, function(a){
			var duration = a.duration;
			if(a.delay){ duration += a.delay; }
			if(this.duration < duration){ this.duration = duration; }
			this._connects.push(d.connect(a, "onEnd", this, "_onEnd"));
		}, this);
		
		this._pseudoAnimation = new d.Animation({curve: [0, 1], duration: this.duration});
		var self = this;
		d.forEach(["beforeBegin", "onBegin", "onPlay", "onAnimate", "onPause", "onStop", "onEnd"], 
			function(evt){
				self._connects.push(d.connect(self._pseudoAnimation, evt,
					function(){ self._fire(evt, arguments); }
				));
			}
		);
	};
	d.extend(_combine, {
		_doAction: function(action, args){
			d.forEach(this._animations, function(a){
				a[action].apply(a, args);
			});
			return this;
		},
		_onEnd: function(){
			if(++this._finished > this._animations.length){
				this._fire("onEnd");
			}
		},
		_call: function(action, args){
			var t = this._pseudoAnimation;
			t[action].apply(t, args);
		},
		play: function(/*int?*/ delay, /*Boolean?*/ gotoStart){
			this._finished = 0;
			this._doAction("play", arguments);
			this._call("play", arguments);
			return this;
		},
		pause: function(){
			this._doAction("pause", arguments);
			this._call("pause", arguments);
			return this;
		},
		gotoPercent: function(/*Decimal*/percent, /*Boolean?*/ andPlay){
			var ms = this.duration * percent;
			d.forEach(this._animations, function(a){
				a.gotoPercent(a.duration < ms ? 1 : (ms / a.duration), andPlay);
			});
			this._call("gotoPercent", arguments);
			return this;
		},
		stop: function(/*boolean?*/ gotoEnd){
			this._doAction("stop", arguments);
			this._call("stop", arguments);
			return this;
		},
		status: function(){
			return this._pseudoAnimation.status();
		},
		destroy: function(){
			d.forEach(this._connects, dojo.disconnect);
		}
	});
	d.extend(_combine, _baseObj);

	dojo.fx.combine = function(/*dojo.Animation[]*/ animations){
		// summary: 
		//		Combine a list of `dojo.Animation`s to run in parallel
		//
		// description:
		//		Combine an array of `dojo.Animation`s to run in parallel, 
		//		providing a new `dojo.Animation` instance encompasing each
		//		animation, firing standard animation events.
		//
		// example:
		//	Fade out `node` while fading in `otherNode` simultaneously
		//	|	dojo.fx.combine([
		//	|		dojo.fadeIn({ node:node }),
		//	|		dojo.fadeOut({ node:otherNode })
		//	|	]).play();
		//
		// example:
		//	When the longest animation ends, execute a function:
		//	|	var anim = dojo.fx.combine([
		//	|		dojo.fadeIn({ node: n, duration:700 }),
		//	|		dojo.fadeOut({ node: otherNode, duration: 300 })
		//	|	]);
		//	|	dojo.connect(anim, "onEnd", function(){
		//	|		// overall animation is done.
		//	|	});
		//	|	anim.play(); // play the animation
		//
		return new _combine(animations); // dojo.Animation
	};

	dojo.fx.wipeIn = function(/*Object*/ args){
		// summary:
		//		Expand a node to it's natural height.
		//
		// description:
		//		Returns an animation that will expand the
		//		node defined in 'args' object from it's current height to
		//		it's natural height (with no scrollbar).
		//		Node must have no margin/border/padding.
		//
		// args: Object
		//		A hash-map of standard `dojo.Animation` constructor properties
		//		(such as easing: node: duration: and so on)
		//
		// example:
		//	|	dojo.fx.wipeIn({
		//	|		node:"someId"
		//	|	}).play()
		var node = args.node = d.byId(args.node), s = node.style, o;

		var anim = d.animateProperty(d.mixin({
			properties: {
				height: {
					// wrapped in functions so we wait till the last second to query (in case value has changed)
					start: function(){
						// start at current [computed] height, but use 1px rather than 0
						// because 0 causes IE to display the whole panel
						o = s.overflow;
						s.overflow = "hidden";
						if(s.visibility == "hidden" || s.display == "none"){
							s.height = "1px";
							s.display = "";
							s.visibility = "";
							return 1;
						}else{
							var height = d.style(node, "height");
							return Math.max(height, 1);
						}
					},
					end: function(){
						return node.scrollHeight;
					}
				}
			}
		}, args));

		d.connect(anim, "onEnd", function(){ 
			s.height = "auto";
			s.overflow = o;
		});

		return anim; // dojo.Animation
	}

	dojo.fx.wipeOut = function(/*Object*/ args){
		// summary:
		//		Shrink a node to nothing and hide it. 
		//
		// description:
		//		Returns an animation that will shrink node defined in "args"
		//		from it's current height to 1px, and then hide it.
		//
		// args: Object
		//		A hash-map of standard `dojo.Animation` constructor properties
		//		(such as easing: node: duration: and so on)
		// 
		// example:
		//	|	dojo.fx.wipeOut({ node:"someId" }).play()
		
		var node = args.node = d.byId(args.node), s = node.style, o;
		
		var anim = d.animateProperty(d.mixin({
			properties: {
				height: {
					end: 1 // 0 causes IE to display the whole panel
				}
			}
		}, args));

		d.connect(anim, "beforeBegin", function(){
			o = s.overflow;
			s.overflow = "hidden";
			s.display = "";
		});
		d.connect(anim, "onEnd", function(){
			s.overflow = o;
			s.height = "auto";
			s.display = "none";
		});

		return anim; // dojo.Animation
	}

	dojo.fx.slideTo = function(/*Object*/ args){
		// summary:
		//		Slide a node to a new top/left position
		//
		// description:
		//		Returns an animation that will slide "node" 
		//		defined in args Object from its current position to
		//		the position defined by (args.left, args.top).
		//
		// args: Object
		//		A hash-map of standard `dojo.Animation` constructor properties
		//		(such as easing: node: duration: and so on). Special args members
		//		are `top` and `left`, which indicate the new position to slide to.
		//
		// example:
		//	|	dojo.fx.slideTo({ node: node, left:"40", top:"50", units:"px" }).play()

		var node = args.node = d.byId(args.node), 
			top = null, left = null;

		var init = (function(n){
			return function(){
				var cs = d.getComputedStyle(n);
				var pos = cs.position;
				top = (pos == 'absolute' ? n.offsetTop : parseInt(cs.top) || 0);
				left = (pos == 'absolute' ? n.offsetLeft : parseInt(cs.left) || 0);
				if(pos != 'absolute' && pos != 'relative'){
					var ret = d.position(n, true);
					top = ret.y;
					left = ret.x;
					n.style.position="absolute";
					n.style.top=top+"px";
					n.style.left=left+"px";
				}
			};
		})(node);
		init();

		var anim = d.animateProperty(d.mixin({
			properties: {
				top: args.top || 0,
				left: args.left || 0
			}
		}, args));
		d.connect(anim, "beforeBegin", anim, init);

		return anim; // dojo.Animation
	}

})();

}

if(!dojo._hasResource["dijit.ProgressBar"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit.ProgressBar"] = true;
dojo.provide("dijit.ProgressBar");







dojo.declare("dijit.ProgressBar", [dijit._Widget, dijit._Templated], {
	// summary:
	//		A progress indication widget, showing the amount completed
	//		(often the percentage completed) of a task.
	//
	// example:
	// |	<div dojoType="ProgressBar"
	// |		 places="0"
	// |		 progress="..." maximum="...">
	// |	</div>
	//
	// description:
	//		Note that the progress bar is updated via (a non-standard)
	//		update() method, rather than via attr() like other widgets.

	// progress: [const] String (Percentage or Number)
	//		Number or percentage indicating amount of task completed.
	// 		With "%": percentage value, 0% <= progress <= 100%, or
	// 		without "%": absolute value, 0 <= progress <= maximum
	// TODO: rename to value for 2.0
	progress: "0",

	// maximum: [const] Float
	//		Max sample number
	maximum: 100,

	// places: [const] Number
	//		Number of places to show in values; 0 by default
	places: 0,

	// indeterminate: [const] Boolean
	// 		If false: show progress value (number or percentage).
	// 		If true: show that a process is underway but that the amount completed is unknown.
	indeterminate: false,

	// name: String
	//		this is the field name (for a form) if set. This needs to be set if you want to use
	//		this widget in a dijit.form.Form widget (such as dijit.Dialog)
	name: '',

	templateString: dojo.cache("dijit", "templates/ProgressBar.html", "<div class=\"dijitProgressBar dijitProgressBarEmpty\"\r\n\t><div waiRole=\"progressbar\" dojoAttachPoint=\"internalProgress\" class=\"dijitProgressBarFull\"\r\n\t\t><div class=\"dijitProgressBarTile\"></div\r\n\t\t><span style=\"visibility:hidden\">&nbsp;</span\r\n\t></div\r\n\t><div dojoAttachPoint=\"label\" class=\"dijitProgressBarLabel\" id=\"${id}_label\">&nbsp;</div\r\n\t><img dojoAttachPoint=\"indeterminateHighContrastImage\" class=\"dijitProgressBarIndeterminateHighContrastImage\" alt=\"\"\r\n\t></img\r\n></div>\r\n"),

	// _indeterminateHighContrastImagePath: [private] dojo._URL
	//		URL to image to use for indeterminate progress bar when display is in high contrast mode
	_indeterminateHighContrastImagePath:
		dojo.moduleUrl("dijit", "themes/a11y/indeterminate_progress.gif"),

	// public functions
	postCreate: function(){
		this.inherited(arguments);
		this.indeterminateHighContrastImage.setAttribute("src",
			this._indeterminateHighContrastImagePath.toString());
		this.update();
	},

	update: function(/*Object?*/attributes){
		// summary:
		//		Change attributes of ProgressBar, similar to attr(hash).
		//
		// attributes:
		//		May provide progress and/or maximum properties on this parameter;
		//		see attribute specs for details.
		//
		// example:
		//	|	myProgressBar.update({'indeterminate': true});
		//	|	myProgressBar.update({'progress': 80});

		// TODO: deprecate this method and use attr() instead

		dojo.mixin(this, attributes || {});
		var tip = this.internalProgress;
		var percent = 1, classFunc;
		if(this.indeterminate){
			classFunc = "addClass";
			dijit.removeWaiState(tip, "valuenow");
			dijit.removeWaiState(tip, "valuemin");
			dijit.removeWaiState(tip, "valuemax");
		}else{
			classFunc = "removeClass";
			if(String(this.progress).indexOf("%") != -1){
				percent = Math.min(parseFloat(this.progress)/100, 1);
				this.progress = percent * this.maximum;
			}else{
				this.progress = Math.min(this.progress, this.maximum);
				percent = this.progress / this.maximum;
			}
			var text = this.report(percent);
			this.label.firstChild.nodeValue = text;
			dijit.setWaiState(tip, "describedby", this.label.id);
			dijit.setWaiState(tip, "valuenow", this.progress);
			dijit.setWaiState(tip, "valuemin", 0);
			dijit.setWaiState(tip, "valuemax", this.maximum);
		}
		dojo[classFunc](this.domNode, "dijitProgressBarIndeterminate");
		tip.style.width = (percent * 100) + "%";
		this.onChange();
	},

	_setValueAttr: function(v){
		if(v == Infinity){
			this.update({indeterminate:true});
		}else{
			this.update({indeterminate:false, progress:v});
		}
	},

	_getValueAttr: function(){
		return this.progress;
	},

	report: function(/*float*/percent){
		// summary:
		//		Generates message to show inside progress bar (normally indicating amount of task completed).
		//		May be overridden.
		// tags:
		//		extension

		return dojo.number.format(percent, { type: "percent", places: this.places, locale: this.lang });
	},

	onChange: function(){
		// summary:
		//		Callback fired when progress updates.
		// tags:
		//		progress
	}
});

}

if(!dojo._hasResource["dojo.DeferredList"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojo.DeferredList"] = true;
dojo.provide("dojo.DeferredList");
dojo.declare("dojo.DeferredList", dojo.Deferred, {
	constructor: function(/*Array*/ list, /*Boolean?*/ fireOnOneCallback, /*Boolean?*/ fireOnOneErrback, /*Boolean?*/ consumeErrors, /*Function?*/ canceller){
		// summary:
		//		Provides event handling for a group of Deferred objects.
		// description:
		//		DeferredList takes an array of existing deferreds and returns a new deferred of its own
		//		this new deferred will typically have its callback fired when all of the deferreds in
		//		the given list have fired their own deferreds.  The parameters `fireOnOneCallback` and
		//		fireOnOneErrback, will fire before all the deferreds as appropriate
		//
		//	list:
		//		The list of deferreds to be synchronizied with this DeferredList
		//	fireOnOneCallback:
		//		Will cause the DeferredLists callback to be fired as soon as any
		//		of the deferreds in its list have been fired instead of waiting until
		//		the entire list has finished
		//	fireonOneErrback:
		//		Will cause the errback to fire upon any of the deferreds errback
		//	canceller:
		//		A deferred canceller function, see dojo.Deferred
		this.list = list;
		this.resultList = new Array(this.list.length);

		// Deferred init
		this.chain = [];
		this.id = this._nextId();
		this.fired = -1;
		this.paused = 0;
		this.results = [null, null];
		this.canceller = canceller;
		this.silentlyCancelled = false;

		if(this.list.length === 0 && !fireOnOneCallback){
			this.callback(this.resultList);
		}

		this.finishedCount = 0;
		this.fireOnOneCallback = fireOnOneCallback;
		this.fireOnOneErrback = fireOnOneErrback;
		this.consumeErrors = consumeErrors;

		dojo.forEach(this.list, function(d, index){
			d.addCallback(this, function(r){ this._cbDeferred(index, true, r); return r; });
			d.addErrback(this, function(r){ this._cbDeferred(index, false, r); return r; });
		}, this);
	},

	_cbDeferred: function(index, succeeded, result){
		// summary:
		//	The DeferredLists' callback handler

		this.resultList[index] = [succeeded, result]; this.finishedCount += 1;
		if(this.fired !== 0){
			if(succeeded && this.fireOnOneCallback){
				this.callback([index, result]);
			}else if(!succeeded && this.fireOnOneErrback){
				this.errback(result);
			}else if(this.finishedCount == this.list.length){
				this.callback(this.resultList);
			}
		}
		if(!succeeded && this.consumeErrors){
			result = null;
		}
		return result;
	},

	gatherResults: function(deferredList){
		// summary:	
		//	Gathers the results of the deferreds for packaging
		//	as the parameters to the Deferred Lists' callback

		var d = new dojo.DeferredList(deferredList, false, true, false);
		d.addCallback(function(results){
			var ret = [];
			dojo.forEach(results, function(result){
				ret.push(result[1]);
			});
			return ret;
		});
		return d;
	}
});

}

if(!dojo._hasResource["dijit.tree.TreeStoreModel"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit.tree.TreeStoreModel"] = true;
dojo.provide("dijit.tree.TreeStoreModel");

dojo.declare(
		"dijit.tree.TreeStoreModel",
		null,
	{
		// summary:
		//		Implements dijit.Tree.model connecting to a store with a single
		//		root item.  Any methods passed into the constructor will override
		//		the ones defined here.

		// store: dojo.data.Store
		//		Underlying store
		store: null,

		// childrenAttrs: String[]
		//		One or more attribute names (attributes in the dojo.data item) that specify that item's children
		childrenAttrs: ["children"],

		// newItemIdAttr: String
		//		Name of attribute in the Object passed to newItem() that specifies the id.
		//
		//		If newItemIdAttr is set then it's used when newItem() is called to see if an
		//		item with the same id already exists, and if so just links to the old item
		//		(so that the old item ends up with two parents).
		//
		//		Setting this to null or "" will make every drop create a new item.
		newItemIdAttr: "id",

		// labelAttr: String
		//		If specified, get label for tree node from this attribute, rather
		//		than by calling store.getLabel()
		labelAttr: "",

	 	// root: [readonly] dojo.data.Item
		//		Pointer to the root item (read only, not a parameter)
		root: null,

		// query: anything
		//		Specifies datastore query to return the root item for the tree.
		//		Must only return a single item.   Alternately can just pass in pointer
		//		to root item.
		// example:
		//	|	{id:'ROOT'}
		query: null,

		// deferItemLoadingUntilExpand: Boolean
		//		Setting this to true will cause the TreeStoreModel to defer calling loadItem on nodes
		// 		until they are expanded. This allows for lazying loading where only one
		//		loadItem (and generally one network call, consequently) per expansion
		// 		(rather than one for each child).
		// 		This relies on partial loading of the children items; each children item of a
		// 		fully loaded item should contain the label and info about having children.
		deferItemLoadingUntilExpand: false,

		constructor: function(/* Object */ args){
			// summary:
			//		Passed the arguments listed above (store, etc)
			// tags:
			//		private

			dojo.mixin(this, args);

			this.connects = [];

			var store = this.store;
			if(!store.getFeatures()['dojo.data.api.Identity']){
				throw new Error("dijit.Tree: store must support dojo.data.Identity");
			}

			// if the store supports Notification, subscribe to the notification events
			if(store.getFeatures()['dojo.data.api.Notification']){
				this.connects = this.connects.concat([
					dojo.connect(store, "onNew", this, "onNewItem"),
					dojo.connect(store, "onDelete", this, "onDeleteItem"),
					dojo.connect(store, "onSet", this, "onSetItem")
				]);
			}
		},

		destroy: function(){
			dojo.forEach(this.connects, dojo.disconnect);
			// TODO: should cancel any in-progress processing of getRoot(), getChildren()
		},

		// =======================================================================
		// Methods for traversing hierarchy

		getRoot: function(onItem, onError){
			// summary:
			//		Calls onItem with the root item for the tree, possibly a fabricated item.
			//		Calls onError on error.
			if(this.root){
				onItem(this.root);
			}else{
				this.store.fetch({
					query: this.query,
					onComplete: dojo.hitch(this, function(items){
						if(items.length != 1){
							throw new Error(this.declaredClass + ": query " + dojo.toJson(this.query) + " returned " + items.length +
							 	" items, but must return exactly one item");
						}
						this.root = items[0];
						onItem(this.root);
					}),
					onError: onError
				});
			}
		},

		mayHaveChildren: function(/*dojo.data.Item*/ item){
			// summary:
			//		Tells if an item has or may have children.  Implementing logic here
			//		avoids showing +/- expando icon for nodes that we know don't have children.
			//		(For efficiency reasons we may not want to check if an element actually
			//		has children until user clicks the expando node)
			return dojo.some(this.childrenAttrs, function(attr){
				return this.store.hasAttribute(item, attr);
			}, this);
		},

		getChildren: function(/*dojo.data.Item*/ parentItem, /*function(items)*/ onComplete, /*function*/ onError){
			// summary:
			// 		Calls onComplete() with array of child items of given parent item, all loaded.

			var store = this.store;
			if(!store.isItemLoaded(parentItem)){
				// The parent is not loaded yet, we must be in deferItemLoadingUntilExpand
				// mode, so we will load it and just return the children (without loading each
				// child item)
				var getChildren = dojo.hitch(this, arguments.callee);
				store.loadItem({
					item: parentItem,
					onItem: function(parentItem){
						getChildren(parentItem, onComplete, onError);
					},
					onError: onError
				});
				return;
			}
			// get children of specified item
			var childItems = [];
			for(var i=0; i<this.childrenAttrs.length; i++){
				var vals = store.getValues(parentItem, this.childrenAttrs[i]);
				childItems = childItems.concat(vals);
			}

			// count how many items need to be loaded
			var _waitCount = 0;
			if(!this.deferItemLoadingUntilExpand){
				dojo.forEach(childItems, function(item){ if(!store.isItemLoaded(item)){ _waitCount++; } });
			}

			if(_waitCount == 0){
				// all items are already loaded (or we aren't loading them).  proceed...
				onComplete(childItems);
			}else{
				// still waiting for some or all of the items to load
				var onItem = function onItem(item){
					if(--_waitCount == 0){
						// all nodes have been loaded, send them to the tree
						onComplete(childItems);
					}
				}
				dojo.forEach(childItems, function(item){
					if(!store.isItemLoaded(item)){
						store.loadItem({
							item: item,
							onItem: onItem,
							onError: onError
						});
					}
				});
			}
		},

		// =======================================================================
		// Inspecting items

		isItem: function(/* anything */ something){
			return this.store.isItem(something);	// Boolean
		},

		fetchItemByIdentity: function(/* object */ keywordArgs){
			this.store.fetchItemByIdentity(keywordArgs);
		},

		getIdentity: function(/* item */ item){
			return this.store.getIdentity(item);	// Object
		},

		getLabel: function(/*dojo.data.Item*/ item){
			// summary:
			//		Get the label for an item
			if(this.labelAttr){
				return this.store.getValue(item,this.labelAttr);	// String
			}else{
				return this.store.getLabel(item);	// String
			}
		},

		// =======================================================================
		// Write interface

		newItem: function(/* dojo.dnd.Item */ args, /*Item*/ parent, /*int?*/ insertIndex){
			// summary:
			//		Creates a new item.   See `dojo.data.api.Write` for details on args.
			//		Used in drag & drop when item from external source dropped onto tree.
			// description:
			//		Developers will need to override this method if new items get added
			//		to parents with multiple children attributes, in order to define which
			//		children attribute points to the new item.

			var pInfo = {parent: parent, attribute: this.childrenAttrs[0], insertIndex: insertIndex};

			if(this.newItemIdAttr && args[this.newItemIdAttr]){
				// Maybe there's already a corresponding item in the store; if so, reuse it.
				this.fetchItemByIdentity({identity: args[this.newItemIdAttr], scope: this, onItem: function(item){
					if(item){
						// There's already a matching item in store, use it
						this.pasteItem(item, null, parent, true, insertIndex);
					}else{
						// Create new item in the tree, based on the drag source.
						this.store.newItem(args, pInfo);
					}
				}});
			}else{
				// [as far as we know] there is no id so we must assume this is a new item
				this.store.newItem(args, pInfo);
			}
		},

		pasteItem: function(/*Item*/ childItem, /*Item*/ oldParentItem, /*Item*/ newParentItem, /*Boolean*/ bCopy, /*int?*/ insertIndex){
			// summary:
			//		Move or copy an item from one parent item to another.
			//		Used in drag & drop
			var store = this.store,
				parentAttr = this.childrenAttrs[0];	// name of "children" attr in parent item

			// remove child from source item, and record the attribute that child occurred in
			if(oldParentItem){
				dojo.forEach(this.childrenAttrs, function(attr){
					if(store.containsValue(oldParentItem, attr, childItem)){
						if(!bCopy){
							var values = dojo.filter(store.getValues(oldParentItem, attr), function(x){
								return x != childItem;
							});
							store.setValues(oldParentItem, attr, values);
						}
						parentAttr = attr;
					}
				});
			}

			// modify target item's children attribute to include this item
			if(newParentItem){
				if(typeof insertIndex == "number"){
					var childItems = store.getValues(newParentItem, parentAttr);
					childItems.splice(insertIndex, 0, childItem);
					store.setValues(newParentItem, parentAttr, childItems);
				}else{
				store.setValues(newParentItem, parentAttr,
					store.getValues(newParentItem, parentAttr).concat(childItem));
			}
			}
		},

		// =======================================================================
		// Callbacks

		onChange: function(/*dojo.data.Item*/ item){
			// summary:
			//		Callback whenever an item has changed, so that Tree
			//		can update the label, icon, etc.   Note that changes
			//		to an item's children or parent(s) will trigger an
			//		onChildrenChange() so you can ignore those changes here.
			// tags:
			//		callback
		},

		onChildrenChange: function(/*dojo.data.Item*/ parent, /*dojo.data.Item[]*/ newChildrenList){
			// summary:
			//		Callback to do notifications about new, updated, or deleted items.
			// tags:
			//		callback
		},

		onDelete: function(/*dojo.data.Item*/ parent, /*dojo.data.Item[]*/ newChildrenList){
			// summary:
			//		Callback when an item has been deleted.
			// description:
			//		Note that there will also be an onChildrenChange() callback for the parent
			//		of this item.
			// tags:
			//		callback
		},

		// =======================================================================
		// Events from data store

		onNewItem: function(/* dojo.data.Item */ item, /* Object */ parentInfo){
			// summary:
			//		Handler for when new items appear in the store, either from a drop operation
			//		or some other way.   Updates the tree view (if necessary).
			// description:
			//		If the new item is a child of an existing item,
			//		calls onChildrenChange() with the new list of children
			//		for that existing item.
			//
			// tags:
			//		extension

			// We only care about the new item if it has a parent that corresponds to a TreeNode
			// we are currently displaying
			if(!parentInfo){
				return;
			}

			// Call onChildrenChange() on parent (ie, existing) item with new list of children
			// In the common case, the new list of children is simply parentInfo.newValue or
			// [ parentInfo.newValue ], although if items in the store has multiple
			// child attributes (see `childrenAttr`), then it's a superset of parentInfo.newValue,
			// so call getChildren() to be sure to get right answer.
			this.getChildren(parentInfo.item, dojo.hitch(this, function(children){
				this.onChildrenChange(parentInfo.item, children);
			}));
		},

		onDeleteItem: function(/*Object*/ item){
			// summary:
			//		Handler for delete notifications from underlying store
			this.onDelete(item);
		},

		onSetItem: function(/* item */ item,
						/* attribute-name-string */ attribute,
						/* object | array */ oldValue,
						/* object | array */ newValue){
			// summary:
			//		Updates the tree view according to changes in the data store.
			// description:
			//		Handles updates to an item's children by calling onChildrenChange(), and
			//		other updates to an item by calling onChange().
			//
			//		See `onNewItem` for more details on handling updates to an item's children.
			// tags:
			//		extension

			if(dojo.indexOf(this.childrenAttrs, attribute) != -1){
				// item's children list changed
				this.getChildren(item, dojo.hitch(this, function(children){
					// See comments in onNewItem() about calling getChildren()
					this.onChildrenChange(item, children);
				}));
			}else{
				// item's label/icon/etc. changed.
				this.onChange(item);
			}
		}
	});



}

if(!dojo._hasResource["dijit.tree.ForestStoreModel"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit.tree.ForestStoreModel"] = true;
dojo.provide("dijit.tree.ForestStoreModel");



dojo.declare("dijit.tree.ForestStoreModel", dijit.tree.TreeStoreModel, {
	// summary:
	//		Interface between Tree and a dojo.store that doesn't have a root item,
	//		i.e. has multiple "top level" items.
	//
	// description
	//		Use this class to wrap a dojo.store, making all the items matching the specified query
	//		appear as children of a fabricated "root item".  If no query is specified then all the
	//		items returned by fetch() on the underlying store become children of the root item.
	//		It allows dijit.Tree to assume a single root item, even if the store doesn't have one.

	// Parameters to constructor

	// rootId: String
	//		ID of fabricated root item
	rootId: "$root$",

	// rootLabel: String
	//		Label of fabricated root item
	rootLabel: "ROOT",

	// query: String
	//		Specifies the set of children of the root item.
	// example:
	//	|	{type:'continent'}
	query: null,

	// End of parameters to constructor

	constructor: function(params){
		// summary:
		//		Sets up variables, etc.
		// tags:
		//		private

		// Make dummy root item
		this.root = {
			store: this,
			root: true,
			id: params.rootId,
			label: params.rootLabel,
			children: params.rootChildren	// optional param
		};
	},

	// =======================================================================
	// Methods for traversing hierarchy

	mayHaveChildren: function(/*dojo.data.Item*/ item){
		// summary:
		//		Tells if an item has or may have children.  Implementing logic here
		//		avoids showing +/- expando icon for nodes that we know don't have children.
		//		(For efficiency reasons we may not want to check if an element actually
		//		has children until user clicks the expando node)
		// tags:
		//		extension
		return item === this.root || this.inherited(arguments);
	},

	getChildren: function(/*dojo.data.Item*/ parentItem, /*function(items)*/ callback, /*function*/ onError){
		// summary:
		// 		Calls onComplete() with array of child items of given parent item, all loaded.
		if(parentItem === this.root){
			if(this.root.children){
				// already loaded, just return
				callback(this.root.children);
			}else{
				this.store.fetch({
					query: this.query,
					onComplete: dojo.hitch(this, function(items){
						this.root.children = items;
						callback(items);
					}),
					onError: onError
				});
			}
		}else{
			this.inherited(arguments);
		}
	},

	// =======================================================================
	// Inspecting items

	isItem: function(/* anything */ something){
		return (something === this.root) ? true : this.inherited(arguments);
	},

	fetchItemByIdentity: function(/* object */ keywordArgs){
		if(keywordArgs.identity == this.root.id){
			var scope = keywordArgs.scope?keywordArgs.scope:dojo.global;
			if(keywordArgs.onItem){
				keywordArgs.onItem.call(scope, this.root);
			}
		}else{
			this.inherited(arguments);
		}
	},

	getIdentity: function(/* item */ item){
		return (item === this.root) ? this.root.id : this.inherited(arguments);
	},

	getLabel: function(/* item */ item){
		return	(item === this.root) ? this.root.label : this.inherited(arguments);
	},

	// =======================================================================
	// Write interface

	newItem: function(/* dojo.dnd.Item */ args, /*Item*/ parent, /*int?*/ insertIndex){
		// summary:
		//		Creates a new item.   See dojo.data.api.Write for details on args.
		//		Used in drag & drop when item from external source dropped onto tree.
		if(parent === this.root){
			this.onNewRootItem(args);
			return this.store.newItem(args);
		}else{
			return this.inherited(arguments);
		}
	},

	onNewRootItem: function(args){
		// summary:
		//		User can override this method to modify a new element that's being
		//		added to the root of the tree, for example to add a flag like root=true
	},

	pasteItem: function(/*Item*/ childItem, /*Item*/ oldParentItem, /*Item*/ newParentItem, /*Boolean*/ bCopy, /*int?*/ insertIndex){
		// summary:
		//		Move or copy an item from one parent item to another.
		//		Used in drag & drop
		if(oldParentItem === this.root){
			if(!bCopy){
				// It's onLeaveRoot()'s responsibility to modify the item so it no longer matches
				// this.query... thus triggering an onChildrenChange() event to notify the Tree
				// that this element is no longer a child of the root node
				this.onLeaveRoot(childItem);
			}
		}
		dijit.tree.TreeStoreModel.prototype.pasteItem.call(this, childItem,
			oldParentItem === this.root ? null : oldParentItem,
			newParentItem === this.root ? null : newParentItem,
			bCopy,
			insertIndex
		);
		if(newParentItem === this.root){
			// It's onAddToRoot()'s responsibility to modify the item so it matches
			// this.query... thus triggering an onChildrenChange() event to notify the Tree
			// that this element is now a child of the root node
			this.onAddToRoot(childItem);
		}
	},

	// =======================================================================
	// Handling for top level children

	onAddToRoot: function(/* item */ item){
		// summary:
		//		Called when item added to root of tree; user must override this method
		//		to modify the item so that it matches the query for top level items
		// example:
		//	|	store.setValue(item, "root", true);
		// tags:
		//		extension
		console.log(this, ": item ", item, " added to root");
	},

	onLeaveRoot: function(/* item */ item){
		// summary:
		//		Called when item removed from root of tree; user must override this method
		//		to modify the item so it doesn't match the query for top level items
		// example:
		// 	|	store.unsetAttribute(item, "root");
		// tags:
		//		extension
		console.log(this, ": item ", item, " removed from root");
	},

	// =======================================================================
	// Events from data store

	_requeryTop: function(){
		// reruns the query for the children of the root node,
		// sending out an onSet notification if those children have changed
		var oldChildren = this.root.children || [];
		this.store.fetch({
			query: this.query,
			onComplete: dojo.hitch(this, function(newChildren){
				this.root.children = newChildren;

				// If the list of children or the order of children has changed...
				if(oldChildren.length != newChildren.length ||
					dojo.some(oldChildren, function(item, idx){ return newChildren[idx] != item;})){
					this.onChildrenChange(this.root, newChildren);
				}
			})
		});
	},

	onNewItem: function(/* dojo.data.Item */ item, /* Object */ parentInfo){
		// summary:
		//		Handler for when new items appear in the store.  Developers should override this
		//		method to be more efficient based on their app/data.
		// description:
		//		Note that the default implementation requeries the top level items every time
		//		a new item is created, since any new item could be a top level item (even in
		//		addition to being a child of another item, since items can have multiple parents).
		//
		//		Developers can override this function to do something more efficient if they can
		//		detect which items are possible top level items (based on the item and the
		//		parentInfo parameters).  Often all top level items have parentInfo==null, but
		//		that will depend on which store you use and what your data is like.
		// tags:
		//		extension
		this._requeryTop();

		this.inherited(arguments);
	},

	onDeleteItem: function(/*Object*/ item){
		// summary:
		//		Handler for delete notifications from underlying store

		// check if this was a child of root, and if so send notification that root's children
		// have changed
		if(dojo.indexOf(this.root.children, item) != -1){
			this._requeryTop();
		}

		this.inherited(arguments);
	}
});



}

if(!dojo._hasResource["dijit.Tree"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dijit.Tree"] = true;
dojo.provide("dijit.Tree");










dojo.declare(
	"dijit._TreeNode",
	[dijit._Widget, dijit._Templated, dijit._Container, dijit._Contained],
{
	// summary:
	//		Single node within a tree.   This class is used internally
	//		by Tree and should not be accessed directly.
	// tags:
	//		private

	// item: dojo.data.Item
	//		the dojo.data entry this tree represents
	item: null,

	// isTreeNode: [protected] Boolean
	//		Indicates that this is a TreeNode.   Used by `dijit.Tree` only,
	//		should not be accessed directly.
	isTreeNode: true,

	// label: String
	//		Text of this tree node
	label: "",

	// isExpandable: [private] Boolean
	//		This node has children, so show the expando node (+ sign)
	isExpandable: null,

	// isExpanded: [readonly] Boolean
	//		This node is currently expanded (ie, opened)
	isExpanded: false,

	// state: [private] String
	//		Dynamic loading-related stuff.
	//		When an empty folder node appears, it is "UNCHECKED" first,
	//		then after dojo.data query it becomes "LOADING" and, finally "LOADED"
	state: "UNCHECKED",

	templateString: dojo.cache("dijit", "templates/TreeNode.html", "<div class=\"dijitTreeNode\" waiRole=\"presentation\"\r\n\t><div dojoAttachPoint=\"rowNode\" class=\"dijitTreeRow\" waiRole=\"presentation\" dojoAttachEvent=\"onmouseenter:_onMouseEnter, onmouseleave:_onMouseLeave, onclick:_onClick, ondblclick:_onDblClick\"\r\n\t\t><img src=\"${_blankGif}\" alt=\"\" dojoAttachPoint=\"expandoNode\" class=\"dijitTreeExpando\" waiRole=\"presentation\"\r\n\t\t><span dojoAttachPoint=\"expandoNodeText\" class=\"dijitExpandoText\" waiRole=\"presentation\"\r\n\t\t></span\r\n\t\t><span dojoAttachPoint=\"contentNode\"\r\n\t\t\tclass=\"dijitTreeContent\" waiRole=\"presentation\">\r\n\t\t\t<img src=\"${_blankGif}\" alt=\"\" dojoAttachPoint=\"iconNode\" class=\"dijitTreeIcon\" waiRole=\"presentation\"\r\n\t\t\t><span dojoAttachPoint=\"labelNode\" class=\"dijitTreeLabel\" wairole=\"treeitem\" tabindex=\"-1\" waiState=\"selected-false\" dojoAttachEvent=\"onfocus:_onLabelFocus, onblur:_onLabelBlur\"></span>\r\n\t\t</span\r\n\t></div>\r\n\t<div dojoAttachPoint=\"containerNode\" class=\"dijitTreeContainer\" waiRole=\"presentation\" style=\"display: none;\"></div>\r\n</div>\r\n"),

	attributeMap: dojo.delegate(dijit._Widget.prototype.attributeMap, {
		label: {node: "labelNode", type: "innerText"},
		tooltip: {node: "rowNode", type: "attribute", attribute: "title"}
	}),

	postCreate: function(){
		// set expand icon for leaf
		this._setExpando();

		// set icon and label class based on item
		this._updateItemClasses(this.item);

		if(this.isExpandable){
			dijit.setWaiState(this.labelNode, "expanded", this.isExpanded);
		}
	},

	_setIndentAttr: function(indent){
		// summary:
		//		Tell this node how many levels it should be indented
		// description:
		//		0 for top level nodes, 1 for their children, 2 for their
		//		grandchildren, etc.
		this.indent = indent;

		// Math.max() is to prevent negative padding on hidden root node (when indent == -1)
		var pixels = (Math.max(indent, 0) * this.tree._nodePixelIndent) + "px";

		dojo.style(this.domNode, "backgroundPosition",	pixels + " 0px");
		dojo.style(this.rowNode, dojo._isBodyLtr() ? "paddingLeft" : "paddingRight", pixels);

		dojo.forEach(this.getChildren(), function(child){
			child.attr("indent", indent+1);
		});
	},

	markProcessing: function(){
		// summary:
		//		Visually denote that tree is loading data, etc.
		// tags:
		//		private
		this.state = "LOADING";
		this._setExpando(true);
	},

	unmarkProcessing: function(){
		// summary:
		//		Clear markup from markProcessing() call
		// tags:
		//		private
		this._setExpando(false);
	},

	_updateItemClasses: function(item){
		// summary:
		//		Set appropriate CSS classes for icon and label dom node
		//		(used to allow for item updates to change respective CSS)
		// tags:
		//		private
		var tree = this.tree, model = tree.model;
		if(tree._v10Compat && item === model.root){
			// For back-compat with 1.0, need to use null to specify root item (TODO: remove in 2.0)
			item = null;
		}
		this._applyClassAndStyle(item, "icon", "Icon");
		this._applyClassAndStyle(item, "label", "Label");
		this._applyClassAndStyle(item, "row", "Row");
	},

	_applyClassAndStyle: function(item, lower, upper){
		// summary:
		//		Set the appropriate CSS classes and styles for labels, icons and rows.
		//
		// item:
		//		The data item.
		//
		// lower:
		//		The lower case attribute to use, e.g. 'icon', 'label' or 'row'.
		//
		// upper:
		//		The upper case attribute to use, e.g. 'Icon', 'Label' or 'Row'.
		//
		// tags:
		//		private

		var clsName = "_" + lower + "Class";
		var nodeName = lower + "Node";

		if(this[clsName]){
			dojo.removeClass(this[nodeName], this[clsName]);
 		}
		this[clsName] = this.tree["get" + upper + "Class"](item, this.isExpanded);
		if(this[clsName]){
			dojo.addClass(this[nodeName], this[clsName]);
 		}
		dojo.style(this[nodeName], this.tree["get" + upper + "Style"](item, this.isExpanded) || {});
 	},

	_updateLayout: function(){
		// summary:
		//		Set appropriate CSS classes for this.domNode
		// tags:
		//		private
		var parent = this.getParent();
		if(!parent || parent.rowNode.style.display == "none"){
			/* if we are hiding the root node then make every first level child look like a root node */
			dojo.addClass(this.domNode, "dijitTreeIsRoot");
		}else{
			dojo.toggleClass(this.domNode, "dijitTreeIsLast", !this.getNextSibling());
		}
	},

	_setExpando: function(/*Boolean*/ processing){
		// summary:
		//		Set the right image for the expando node
		// tags:
		//		private

		var styles = ["dijitTreeExpandoLoading", "dijitTreeExpandoOpened",
						"dijitTreeExpandoClosed", "dijitTreeExpandoLeaf"],
			_a11yStates = ["*","-","+","*"],
			idx = processing ? 0 : (this.isExpandable ?	(this.isExpanded ? 1 : 2) : 3);

		// apply the appropriate class to the expando node
		dojo.removeClass(this.expandoNode, styles);
		dojo.addClass(this.expandoNode, styles[idx]);

		// provide a non-image based indicator for images-off mode
		this.expandoNodeText.innerHTML = _a11yStates[idx];

	},

	expand: function(){
		// summary:
		//		Show my children
		// returns:
		//		Deferred that fires when expansion is complete

		// If there's already an expand in progress or we are already expanded, just return
		if(this._expandDeferred){
			return this._expandDeferred;		// dojo.Deferred
		}

		// cancel in progress collapse operation
		this._wipeOut && this._wipeOut.stop();

		// All the state information for when a node is expanded, maybe this should be
		// set when the animation completes instead
		this.isExpanded = true;
		dijit.setWaiState(this.labelNode, "expanded", "true");
		dijit.setWaiRole(this.containerNode, "group");
		dojo.addClass(this.contentNode,'dijitTreeContentExpanded');
		this._setExpando();
		this._updateItemClasses(this.item);
		if(this == this.tree.rootNode){
			dijit.setWaiState(this.tree.domNode, "expanded", "true");
		}

		var def,
			wipeIn = dojo.fx.wipeIn({
				node: this.containerNode, duration: dijit.defaultDuration,
				onEnd: function(){
					def.callback(true);
				}
			});

		// Deferred that fires when expand is complete
		def = (this._expandDeferred = new dojo.Deferred(function(){
			// Canceller
			wipeIn.stop();
		}));

		wipeIn.play();

		return def;		// dojo.Deferred
	},

	collapse: function(){
		// summary:
		//		Collapse this node (if it's expanded)

		if(!this.isExpanded){ return; }

		// cancel in progress expand operation
		if(this._expandDeferred){
			this._expandDeferred.cancel();
			delete this._expandDeferred;
		}

		this.isExpanded = false;
		dijit.setWaiState(this.labelNode, "expanded", "false");
		if(this == this.tree.rootNode){
			dijit.setWaiState(this.tree.domNode, "expanded", "false");
		}
		dojo.removeClass(this.contentNode,'dijitTreeContentExpanded');
		this._setExpando();
		this._updateItemClasses(this.item);

		if(!this._wipeOut){
			this._wipeOut = dojo.fx.wipeOut({
				node: this.containerNode, duration: dijit.defaultDuration
			});
		}
		this._wipeOut.play();
	},

	// indent: Integer
	//		Levels from this node to the root node
	indent: 0,

	setChildItems: function(/* Object[] */ items){
		// summary:
		//		Sets the child items of this node, removing/adding nodes
		//		from current children to match specified items[] array.
		//		Also, if this.persist == true, expands any children that were previously
		// 		opened.
		// returns:
		//		Deferred object that fires after all previously opened children
		//		have been expanded again (or fires instantly if there are no such children).

		var tree = this.tree,
			model = tree.model,
			defs = [];	// list of deferreds that need to fire before I am complete


		// Orphan all my existing children.
		// If items contains some of the same items as before then we will reattach them.
		// Don't call this.removeChild() because that will collapse the tree etc.
		this.getChildren().forEach(function(child){
			dijit._Container.prototype.removeChild.call(this, child);
		}, this);

		this.state = "LOADED";

		if(items && items.length > 0){
			this.isExpandable = true;

			// Create _TreeNode widget for each specified tree node, unless one already
			// exists and isn't being used (presumably it's from a DnD move and was recently
			// released
			dojo.forEach(items, function(item){
				var id = model.getIdentity(item),
					existingNodes = tree._itemNodesMap[id],
					node;
				if(existingNodes){
					for(var i=0;i<existingNodes.length;i++){
						if(existingNodes[i] && !existingNodes[i].getParent()){
							node = existingNodes[i];
							node.attr('indent', this.indent+1);
							break;
						}
					}
				}
				if(!node){
					node = this.tree._createTreeNode({
							item: item,
							tree: tree,
							isExpandable: model.mayHaveChildren(item),
							label: tree.getLabel(item),
							tooltip: tree.getTooltip(item),
							indent: this.indent + 1
						});
					if(existingNodes){
						existingNodes.push(node);
					}else{
						tree._itemNodesMap[id] = [node];
					}
				}
				this.addChild(node);

				// If node was previously opened then open it again now (this may trigger
				// more data store accesses, recursively)
				if(this.tree.autoExpand || this.tree._state(item)){
					defs.push(tree._expandNode(node));
				}
			}, this);

			// note that updateLayout() needs to be called on each child after
			// _all_ the children exist
			dojo.forEach(this.getChildren(), function(child, idx){
				child._updateLayout();
			});
		}else{
			this.isExpandable=false;
		}

		if(this._setExpando){
			// change expando to/from dot or + icon, as appropriate
			this._setExpando(false);
		}

		// On initial tree show, make the selected TreeNode as either the root node of the tree,
		// or the first child, if the root node is hidden
		if(this == tree.rootNode){
			var fc = this.tree.showRoot ? this : this.getChildren()[0];
			if(fc){
				fc.setSelected(true);
				tree.lastFocused = fc;
			}else{
				// fallback: no nodes in tree so focus on Tree <div> itself
				tree.domNode.setAttribute("tabIndex", "0");
			}
		}

		return new dojo.DeferredList(defs);	// dojo.Deferred
	},

	removeChild: function(/* treeNode */ node){
		this.inherited(arguments);

		var children = this.getChildren();
		if(children.length == 0){
			this.isExpandable = false;
			this.collapse();
		}

		dojo.forEach(children, function(child){
				child._updateLayout();
		});
	},

	makeExpandable: function(){
		// summary:
		//		if this node wasn't already showing the expando node,
		//		turn it into one and call _setExpando()

		// TODO: hmm this isn't called from anywhere, maybe should remove it for 2.0

		this.isExpandable = true;
		this._setExpando(false);
	},

	_onLabelFocus: function(evt){
		// summary:
		//		Called when this node is focused (possibly programatically)
		// tags:
		//		private
		dojo.addClass(this.labelNode, "dijitTreeLabelFocused");
		this.tree._onNodeFocus(this);
	},

	_onLabelBlur: function(evt){
		// summary:
		//		Called when focus was moved away from this node, either to
		//		another TreeNode or away from the Tree entirely.
		//		Note that we aren't using _onFocus/_onBlur builtin to dijit
		//		because _onBlur() isn't called when focus is moved to my child TreeNode.
		// tags:
		//		private
		dojo.removeClass(this.labelNode, "dijitTreeLabelFocused");
	},

	setSelected: function(/*Boolean*/ selected){
		// summary:
		//		A Tree has a (single) currently selected node.
		//		Mark that this node is/isn't that currently selected node.
		// description:
		//		In particular, setting a node as selected involves setting tabIndex
		//		so that when user tabs to the tree, focus will go to that node (only).
		var labelNode = this.labelNode;
		labelNode.setAttribute("tabIndex", selected ? "0" : "-1");
		dijit.setWaiState(labelNode, "selected", selected);
		dojo.toggleClass(this.rowNode, "dijitTreeNodeSelected", selected);
	},

	_onClick: function(evt){
		// summary:
		//		Handler for onclick event on a node
		// tags:
		//		private
		this.tree._onClick(this, evt);
	},
	_onDblClick: function(evt){
		// summary:
		//		Handler for ondblclick event on a node
		// tags:
		//		private
		this.tree._onDblClick(this, evt);
	},

	_onMouseEnter: function(evt){
		// summary:
		//		Handler for onmouseenter event on a node
		// tags:
		//		private
		dojo.addClass(this.rowNode, "dijitTreeNodeHover");
		this.tree._onNodeMouseEnter(this, evt);
	},

	_onMouseLeave: function(evt){
		// summary:
		//		Handler for onmouseenter event on a node
		// tags:
		//		private
		dojo.removeClass(this.rowNode, "dijitTreeNodeHover");
		this.tree._onNodeMouseLeave(this, evt);
	}
});

dojo.declare(
	"dijit.Tree",
	[dijit._Widget, dijit._Templated],
{
	// summary:
	//		This widget displays hierarchical data from a store.

	// store: [deprecated] String||dojo.data.Store
	//		Deprecated.  Use "model" parameter instead.
	//		The store to get data to display in the tree.
	store: null,

	// model: dijit.Tree.model
	//		Interface to read tree data, get notifications of changes to tree data,
	//		and for handling drop operations (i.e drag and drop onto the tree)
	model: null,

	// query: [deprecated] anything
	//		Deprecated.  User should specify query to the model directly instead.
	//		Specifies datastore query to return the root item or top items for the tree.
	query: null,

	// label: [deprecated] String
	//		Deprecated.  Use dijit.tree.ForestStoreModel directly instead.
	//		Used in conjunction with query parameter.
	//		If a query is specified (rather than a root node id), and a label is also specified,
	//		then a fake root node is created and displayed, with this label.
	label: "",

	// showRoot: [const] Boolean
	//		Should the root node be displayed, or hidden?
	showRoot: true,

	// childrenAttr: [deprecated] String[]
	//		Deprecated.   This information should be specified in the model.
	//		One ore more attributes that holds children of a tree node
	childrenAttr: ["children"],

	// path: String[] or Item[]
	//		Full path from rootNode to selected node expressed as array of items or array of ids.
	path: [],

	// selectedItem: [readonly] Item
	//		The currently selected item in this tree.
	//		This property can only be set (via attr('selectedItem', ...)) when that item is already
	//		visible in the tree.   (I.e. the tree has already been expanded to show that node.)
	//		Should generally use `path` attribute to set the selected item instead.
	selectedItem: null,

	// openOnClick: Boolean
	//		If true, clicking a folder node's label will open it, rather than calling onClick()
	openOnClick: false,

	// openOnDblClick: Boolean
	//		If true, double-clicking a folder node's label will open it, rather than calling onDblClick()
	openOnDblClick: false,

	templateString: dojo.cache("dijit", "templates/Tree.html", "<div class=\"dijitTree dijitTreeContainer\" waiRole=\"tree\"\r\n\tdojoAttachEvent=\"onkeypress:_onKeyPress\">\r\n\t<div class=\"dijitInline dijitTreeIndent\" style=\"position: absolute; top: -9999px\" dojoAttachPoint=\"indentDetector\"></div>\r\n</div>\r\n"),

	// persist: Boolean
	//		Enables/disables use of cookies for state saving.
	persist: true,

	// autoExpand: Boolean
	//		Fully expand the tree on load.   Overrides `persist`
	autoExpand: false,

	// dndController: [protected] String
	//		Class name to use as as the dnd controller.  Specifying this class enables DnD.
	//		Generally you should specify this as "dijit.tree.dndSource".
	dndController: null,

	// parameters to pull off of the tree and pass on to the dndController as its params
	dndParams: ["onDndDrop","itemCreator","onDndCancel","checkAcceptance", "checkItemAcceptance", "dragThreshold", "betweenThreshold"],

	//declare the above items so they can be pulled from the tree's markup

	// onDndDrop: [protected] Function
	//		Parameter to dndController, see `dijit.tree.dndSource.onDndDrop`.
	//		Generally this doesn't need to be set.
	onDndDrop: null,

	/*=====
	itemCreator: function(nodes, target, source){
		// summary:
		//		Returns objects passed to `Tree.model.newItem()` based on DnD nodes
		//		dropped onto the tree.   Developer must override this method to enable
		// 		dropping from external sources onto this Tree, unless the Tree.model's items
		//		happen to look like {id: 123, name: "Apple" } with no other attributes.
		// description:
		//		For each node in nodes[], which came from source, create a hash of name/value
		//		pairs to be passed to Tree.model.newItem().  Returns array of those hashes.
		// nodes: DomNode[]
		//		The DOMNodes dragged from the source container
		// target: DomNode
		//		The target TreeNode.rowNode
		// source: dojo.dnd.Source
		//		The source container the nodes were dragged from, perhaps another Tree or a plain dojo.dnd.Source
		// returns: Object[]
		//		Array of name/value hashes for each new item to be added to the Tree, like:
		// |	[
		// |		{ id: 123, label: "apple", foo: "bar" },
		// |		{ id: 456, label: "pear", zaz: "bam" }
		// |	]
		// tags:
		//		extension
		return [{}];
	},
	=====*/
	itemCreator: null,

	// onDndCancel: [protected] Function
	//		Parameter to dndController, see `dijit.tree.dndSource.onDndCancel`.
	//		Generally this doesn't need to be set.
	onDndCancel: null,

/*=====
	checkAcceptance: function(source, nodes){
		// summary:
		//		Checks if the Tree itself can accept nodes from this source
		// source: dijit.tree._dndSource
		//		The source which provides items
		// nodes: DOMNode[]
		//		Array of DOM nodes corresponding to nodes being dropped, dijitTreeRow nodes if
		//		source is a dijit.Tree.
		// tags:
		//		extension
		return true;	// Boolean
	},
=====*/
	checkAcceptance: null,

/*=====
	checkItemAcceptance: function(target, source, position){
		// summary:
		//		Stub function to be overridden if one wants to check for the ability to drop at the node/item level
		// description:
		//		In the base case, this is called to check if target can become a child of source.
		//		When betweenThreshold is set, position="before" or "after" means that we
		//		are asking if the source node can be dropped before/after the target node.
		// target: DOMNode
		//		The dijitTreeRoot DOM node inside of the TreeNode that we are dropping on to
		//		Use dijit.getEnclosingWidget(target) to get the TreeNode.
		// source: dijit.tree.dndSource
		//		The (set of) nodes we are dropping
		// position: String
		//		"over", "before", or "after"
		// tags:
		//		extension
		return true;	// Boolean
	},
=====*/
	checkItemAcceptance: null,

	// dragThreshold: Integer
	//		Number of pixels mouse moves before it's considered the start of a drag operation
	dragThreshold: 5,

	// betweenThreshold: Integer
	//		Set to a positive value to allow drag and drop "between" nodes.
	//
	//		If during DnD mouse is over a (target) node but less than betweenThreshold
	//		pixels from the bottom edge, dropping the the dragged node will make it
	//		the next sibling of the target node, rather than the child.
	//
	//		Similarly, if mouse is over a target node but less that betweenThreshold
	//		pixels from the top edge, dropping the dragged node will make it
	//		the target node's previous sibling rather than the target node's child.
	betweenThreshold: 0,

	// _nodePixelIndent: Integer
	//		Number of pixels to indent tree nodes (relative to parent node).
	//		Default is 19 but can be overridden by setting CSS class dijitTreeIndent
	//		and calling resize() or startup() on tree after it's in the DOM.
	_nodePixelIndent: 19,

	_publish: function(/*String*/ topicName, /*Object*/ message){
		// summary:
		//		Publish a message for this widget/topic
		dojo.publish(this.id, [dojo.mixin({tree: this, event: topicName}, message || {})]);
	},

	postMixInProperties: function(){
		this.tree = this;

		this._itemNodesMap={};

		if(!this.cookieName){
			this.cookieName = this.id + "SaveStateCookie";
		}

		this._loadDeferred = new dojo.Deferred();

		this.inherited(arguments);
	},

	postCreate: function(){
		this._initState();

		// Create glue between store and Tree, if not specified directly by user
		if(!this.model){
			this._store2model();
		}

		// monitor changes to items
		this.connect(this.model, "onChange", "_onItemChange");
		this.connect(this.model, "onChildrenChange", "_onItemChildrenChange");
		this.connect(this.model, "onDelete", "_onItemDelete");

		this._load();

		this.inherited(arguments);

		if(this.dndController){
			if(dojo.isString(this.dndController)){
				this.dndController = dojo.getObject(this.dndController);
			}
			var params={};
			for(var i=0; i<this.dndParams.length;i++){
				if(this[this.dndParams[i]]){
					params[this.dndParams[i]] = this[this.dndParams[i]];
				}
			}
			this.dndController = new this.dndController(this, params);
		}
	},

	_store2model: function(){
		// summary:
		//		User specified a store&query rather than model, so create model from store/query
		this._v10Compat = true;
		dojo.deprecated("Tree: from version 2.0, should specify a model object rather than a store/query");

		var modelParams = {
			id: this.id + "_ForestStoreModel",
			store: this.store,
			query: this.query,
			childrenAttrs: this.childrenAttr
		};

		// Only override the model's mayHaveChildren() method if the user has specified an override
		if(this.params.mayHaveChildren){
			modelParams.mayHaveChildren = dojo.hitch(this, "mayHaveChildren");
		}

		if(this.params.getItemChildren){
			modelParams.getChildren = dojo.hitch(this, function(item, onComplete, onError){
				this.getItemChildren((this._v10Compat && item === this.model.root) ? null : item, onComplete, onError);
			});
		}
		this.model = new dijit.tree.ForestStoreModel(modelParams);

		// For backwards compatibility, the visibility of the root node is controlled by
		// whether or not the user has specified a label
		this.showRoot = Boolean(this.label);
	},

	onLoad: function(){
		// summary:
		//		Called when tree finishes loading and expanding.
		// description:
		//		If persist == true the loading may encompass many levels of fetches
		//		from the data store, each asynchronous.   Waits for all to finish.
		// tags:
		//		callback
	},

	_load: function(){
		// summary:
		//		Initial load of the tree.
		//		Load root node (possibly hidden) and it's children.
		this.model.getRoot(
			dojo.hitch(this, function(item){
				var rn = (this.rootNode = this.tree._createTreeNode({
					item: item,
					tree: this,
					isExpandable: true,
					label: this.label || this.getLabel(item),
					indent: this.showRoot ? 0 : -1
				}));
				if(!this.showRoot){
					rn.rowNode.style.display="none";
				}
				this.domNode.appendChild(rn.domNode);
				var identity = this.model.getIdentity(item);
				if(this._itemNodesMap[identity]){
					this._itemNodesMap[identity].push(rn);
				}else{
					this._itemNodesMap[identity] = [rn];
				}

				rn._updateLayout();		// sets "dijitTreeIsRoot" CSS classname

				// load top level children and then fire onLoad() event
				this._expandNode(rn).addCallback(dojo.hitch(this, function(){
					this._loadDeferred.callback(true);
					this.onLoad();
				}));
			}),
			function(err){
				console.error(this, ": error loading root: ", err);
			}
		);
	},

	getNodesByItem: function(/*dojo.data.Item or id*/ item){
		// summary:
		//		Returns all tree nodes that refer to an item
		// returns:
		//		Array of tree nodes that refer to passed item

		if(!item){ return []; }
		var identity = dojo.isString(item) ? item : this.model.getIdentity(item);
		// return a copy so widget don't get messed up by changes to returned array
		return [].concat(this._itemNodesMap[identity]);
	},

	_setSelectedItemAttr: function(/*dojo.data.Item or id*/ item){
		// summary:
		//		Select a tree node related to passed item.
		//		WARNING: if model use multi-parented items or desired tree node isn't already loaded
		//		behavior is not granted. Use 'path' attr instead for full support.
		var oldValue = this.attr("selectedItem");
		var identity = (!item || dojo.isString(item)) ? item : this.model.getIdentity(item);
		if(identity == oldValue ? this.model.getIdentity(oldValue) : null){ return; }
		var nodes = this._itemNodesMap[identity];
		if(nodes && nodes.length){
			//select the first item
			this.focusNode(nodes[0]);
		}else if(this.lastFocused){
			// Select none so deselect current
			this.lastFocused.setSelected(false);
			this.lastFocused = null;
		}
	},

	_getSelectedItemAttr: function(){
		// summary:
		//		Return item related to selected tree node.
		return this.lastFocused && this.lastFocused.item;
	},

	_setPathAttr: function(/*Item[] || String[]*/ path){
		// summary:
		//		Select the tree node identified by passed path.
		// path:
		//		Array of items or item id's

		if(!path || !path.length){ return; }

		// If this is called during initialization, defer running until Tree has finished loading
		this._loadDeferred.addCallback(dojo.hitch(this, function(){
			if(!this.rootNode){
				console.debug("!this.rootNode");
				return;
			}
			if(path[0] !== this.rootNode.item && (dojo.isString(path[0]) && path[0] != this.model.getIdentity(this.rootNode.item))){
				console.error(this, ":path[0] doesn't match this.rootNode.item.  Maybe you are using the wrong tree.");
				return;
			}
			path.shift();

			var node = this.rootNode;

			function advance(){
				// summary:
				// 		Called when "node" has completed loading and expanding.   Pop the next item from the path
				//		(which must be a child of "node") and advance to it, and then recurse.

				// Set item and identity to next item in path (node is pointing to the item that was popped
				// from the path _last_ time.
				var item = path.shift(),
					identity = dojo.isString(item) ? item : this.model.getIdentity(item);

				// Change "node" from previous item in path to the item we just popped from path
				dojo.some(this._itemNodesMap[identity], function(n){
					if(n.getParent() == node){
						node = n;
						return true;
					}
					return false;
				});

				if(path.length){
					// Need to do more expanding
					this._expandNode(node).addCallback(dojo.hitch(this, advance));
				}else{
					// Final destination node, select it
					if(this.lastFocused != node){
						this.focusNode(node);
					}
				}
			}

			this._expandNode(node).addCallback(dojo.hitch(this, advance));
		}));
	},

	_getPathAttr: function(){
		// summary:
		//		Return an array of items that is the path to selected tree node.
		if(!this.lastFocused){ return; }
		var res = [];
		var treeNode = this.lastFocused;
		while(treeNode && treeNode !== this.rootNode){
			res.unshift(treeNode.item);
			treeNode = treeNode.getParent();
		}
		res.unshift(this.rootNode.item);
		return res;
	},

	////////////// Data store related functions //////////////////////
	// These just get passed to the model; they are here for back-compat

	mayHaveChildren: function(/*dojo.data.Item*/ item){
		// summary:
		//		Deprecated.   This should be specified on the model itself.
		//
		//		Overridable function to tell if an item has or may have children.
		//		Controls whether or not +/- expando icon is shown.
		//		(For efficiency reasons we may not want to check if an element actually
		//		has children until user clicks the expando node)
		// tags:
		//		deprecated
	},

	getItemChildren: function(/*dojo.data.Item*/ parentItem, /*function(items)*/ onComplete){
		// summary:
		//		Deprecated.   This should be specified on the model itself.
		//
		// 		Overridable function that return array of child items of given parent item,
		//		or if parentItem==null then return top items in tree
		// tags:
		//		deprecated
	},

	///////////////////////////////////////////////////////
	// Functions for converting an item to a TreeNode
	getLabel: function(/*dojo.data.Item*/ item){
		// summary:
		//		Overridable function to get the label for a tree node (given the item)
		// tags:
		//		extension
		return this.model.getLabel(item);	// String
	},

	getIconClass: function(/*dojo.data.Item*/ item, /*Boolean*/ opened){
		// summary:
		//		Overridable function to return CSS class name to display icon
		// tags:
		//		extension
		return (!item || this.model.mayHaveChildren(item)) ? (opened ? "dijitFolderOpened" : "dijitFolderClosed") : "dijitLeaf"
	},

	getLabelClass: function(/*dojo.data.Item*/ item, /*Boolean*/ opened){
		// summary:
		//		Overridable function to return CSS class name to display label
		// tags:
		//		extension
	},

	getRowClass: function(/*dojo.data.Item*/ item, /*Boolean*/ opened){
		// summary:
		//		Overridable function to return CSS class name to display row
		// tags:
		//		extension
	},

	getIconStyle: function(/*dojo.data.Item*/ item, /*Boolean*/ opened){
		// summary:
		//		Overridable function to return CSS styles to display icon
		// returns:
		//		Object suitable for input to dojo.style() like {backgroundImage: "url(...)"}
		// tags:
		//		extension
	},

	getLabelStyle: function(/*dojo.data.Item*/ item, /*Boolean*/ opened){
		// summary:
		//		Overridable function to return CSS styles to display label
		// returns:
		//		Object suitable for input to dojo.style() like {color: "red", background: "green"}
		// tags:
		//		extension
	},

	getRowStyle: function(/*dojo.data.Item*/ item, /*Boolean*/ opened){
		// summary:
		//		Overridable function to return CSS styles to display row
		// returns:
		//		Object suitable for input to dojo.style() like {background-color: "#bbb"}
		// tags:
		//		extension
	},

	getTooltip: function(/*dojo.data.Item*/ item){
		// summary:
		//		Overridable function to get the tooltip for a tree node (given the item)
		// tags:
		//		extension
		return "";	// String
	},

	/////////// Keyboard and Mouse handlers ////////////////////

	_onKeyPress: function(/*Event*/ e){
		// summary:
		//		Translates keypress events into commands for the controller
		if(e.altKey){ return; }
		var dk = dojo.keys;
		var treeNode = dijit.getEnclosingWidget(e.target);
		if(!treeNode){ return; }

		var key = e.charOrCode;
		if(typeof key == "string"){	// handle printables (letter navigation)
			// Check for key navigation.
			if(!e.altKey && !e.ctrlKey && !e.shiftKey && !e.metaKey){
				this._onLetterKeyNav( { node: treeNode, key: key.toLowerCase() } );
				dojo.stopEvent(e);
			}
		}else{	// handle non-printables (arrow keys)
			// clear record of recent printables (being saved for multi-char letter navigation),
			// because "a", down-arrow, "b" shouldn't search for "ab"
			if(this._curSearch){
				clearTimeout(this._curSearch.timer);
				delete this._curSearch;
			}

			var map = this._keyHandlerMap;
			if(!map){
				// setup table mapping keys to events
				map = {};
				map[dk.ENTER]="_onEnterKey";
				map[this.isLeftToRight() ? dk.LEFT_ARROW : dk.RIGHT_ARROW]="_onLeftArrow";
				map[this.isLeftToRight() ? dk.RIGHT_ARROW : dk.LEFT_ARROW]="_onRightArrow";
				map[dk.UP_ARROW]="_onUpArrow";
				map[dk.DOWN_ARROW]="_onDownArrow";
				map[dk.HOME]="_onHomeKey";
				map[dk.END]="_onEndKey";
				this._keyHandlerMap = map;
			}
			if(this._keyHandlerMap[key]){
				this[this._keyHandlerMap[key]]( { node: treeNode, item: treeNode.item, evt: e } );
				dojo.stopEvent(e);
			}
		}
	},

	_onEnterKey: function(/*Object*/ message, /*Event*/ evt){
		this._publish("execute", { item: message.item, node: message.node } );
		this.onClick(message.item, message.node, evt);
	},

	_onDownArrow: function(/*Object*/ message){
		// summary:
		//		down arrow pressed; get next visible node, set focus there
		var node = this._getNextNode(message.node);
		if(node && node.isTreeNode){
			this.focusNode(node);
		}
	},

	_onUpArrow: function(/*Object*/ message){
		// summary:
		//		Up arrow pressed; move to previous visible node

		var node = message.node;

		// if younger siblings
		var previousSibling = node.getPreviousSibling();
		if(previousSibling){
			node = previousSibling;
			// if the previous node is expanded, dive in deep
			while(node.isExpandable && node.isExpanded && node.hasChildren()){
				// move to the last child
				var children = node.getChildren();
				node = children[children.length-1];
			}
		}else{
			// if this is the first child, return the parent
			// unless the parent is the root of a tree with a hidden root
			var parent = node.getParent();
			if(!(!this.showRoot && parent === this.rootNode)){
				node = parent;
			}
		}

		if(node && node.isTreeNode){
			this.focusNode(node);
		}
	},

	_onRightArrow: function(/*Object*/ message){
		// summary:
		//		Right arrow pressed; go to child node
		var node = message.node;

		// if not expanded, expand, else move to 1st child
		if(node.isExpandable && !node.isExpanded){
			this._expandNode(node);
		}else if(node.hasChildren()){
			node = node.getChildren()[0];
			if(node && node.isTreeNode){
				this.focusNode(node);
			}
		}
	},

	_onLeftArrow: function(/*Object*/ message){
		// summary:
		//		Left arrow pressed.
		//		If not collapsed, collapse, else move to parent.

		var node = message.node;

		if(node.isExpandable && node.isExpanded){
			this._collapseNode(node);
		}else{
			var parent = node.getParent();
			if(parent && parent.isTreeNode && !(!this.showRoot && parent === this.rootNode)){
				this.focusNode(parent);
			}
		}
	},

	_onHomeKey: function(){
		// summary:
		//		Home key pressed; get first visible node, and set focus there
		var node = this._getRootOrFirstNode();
		if(node){
			this.focusNode(node);
		}
	},

	_onEndKey: function(/*Object*/ message){
		// summary:
		//		End key pressed; go to last visible node.

		var node = this.rootNode;
		while(node.isExpanded){
			var c = node.getChildren();
			node = c[c.length - 1];
		}

		if(node && node.isTreeNode){
			this.focusNode(node);
		}
	},

	// multiCharSearchDuration: Number
	//		If multiple characters are typed where each keystroke happens within
	//		multiCharSearchDuration of the previous keystroke,
	//		search for nodes matching all the keystrokes.
	//
	//		For example, typing "ab" will search for entries starting with
	//		"ab" unless the delay between "a" and "b" is greater than multiCharSearchDuration.
	multiCharSearchDuration: 250,

	_onLetterKeyNav: function(message){
		// summary:
		//		Called when user presses a prinatable key; search for node starting with recently typed letters.
		// message: Object
		//		Like { node: TreeNode, key: 'a' } where key is the key the user pressed.

		// Branch depending on whether this key starts a new search, or modifies an existing search
		var cs = this._curSearch;
		if(cs){
			// We are continuing a search.  Ex: user has pressed 'a', and now has pressed
			// 'b', so we want to search for nodes starting w/"ab".
			cs.pattern = cs.pattern + message.key;
			clearTimeout(cs.timer);
		}else{
			// We are starting a new search
			cs = this._curSearch = {
					pattern: message.key,
					startNode: message.node
			};
		}

		// set/reset timer to forget recent keystrokes
		var self = this;
		cs.timer = setTimeout(function(){
			delete self._curSearch;
		}, this.multiCharSearchDuration);

		// Navigate to TreeNode matching keystrokes [entered so far].
		var node = cs.startNode;
		do{
			node = this._getNextNode(node);
			//check for last node, jump to first node if necessary
			if(!node){
				node = this._getRootOrFirstNode();
			}
		}while(node !== cs.startNode && (node.label.toLowerCase().substr(0, cs.pattern.length) != cs.pattern));
		if(node && node.isTreeNode){
			// no need to set focus if back where we started
			if(node !== cs.startNode){
				this.focusNode(node);
			}
		}
	},

	_onClick: function(/*TreeNode*/ nodeWidget, /*Event*/ e){
		// summary:
		//		Translates click events into commands for the controller to process

		var domElement = e.target;

		if( (this.openOnClick && nodeWidget.isExpandable) ||
			(domElement == nodeWidget.expandoNode || domElement == nodeWidget.expandoNodeText) ){
			// expando node was clicked, or label of a folder node was clicked; open it
			if(nodeWidget.isExpandable){
				this._onExpandoClick({node:nodeWidget});
			}
		}else{
			this._publish("execute", { item: nodeWidget.item, node: nodeWidget, evt: e } );
			this.onClick(nodeWidget.item, nodeWidget, e);
			this.focusNode(nodeWidget);
		}
		dojo.stopEvent(e);
	},
	_onDblClick: function(/*TreeNode*/ nodeWidget, /*Event*/ e){
		// summary:
		//		Translates double-click events into commands for the controller to process

		var domElement = e.target;

		if( (this.openOnDblClick && nodeWidget.isExpandable) ||
			(domElement == nodeWidget.expandoNode || domElement == nodeWidget.expandoNodeText) ){
			// expando node was clicked, or label of a folder node was clicked; open it
			if(nodeWidget.isExpandable){
				this._onExpandoClick({node:nodeWidget});
			}
		}else{
			this._publish("execute", { item: nodeWidget.item, node: nodeWidget, evt: e } );
			this.onDblClick(nodeWidget.item, nodeWidget, e);
			this.focusNode(nodeWidget);
		}
		dojo.stopEvent(e);
	},

	_onExpandoClick: function(/*Object*/ message){
		// summary:
		//		User clicked the +/- icon; expand or collapse my children.
		var node = message.node;

		// If we are collapsing, we might be hiding the currently focused node.
		// Also, clicking the expando node might have erased focus from the current node.
		// For simplicity's sake just focus on the node with the expando.
		this.focusNode(node);

		if(node.isExpanded){
			this._collapseNode(node);
		}else{
			this._expandNode(node);
		}
	},

	onClick: function(/* dojo.data */ item, /*TreeNode*/ node, /*Event*/ evt){
		// summary:
		//		Callback when a tree node is clicked
		// tags:
		//		callback
	},
	onDblClick: function(/* dojo.data */ item, /*TreeNode*/ node, /*Event*/ evt){
		// summary:
		//		Callback when a tree node is double-clicked
		// tags:
		//		callback
	},
	onOpen: function(/* dojo.data */ item, /*TreeNode*/ node){
		// summary:
		//		Callback when a node is opened
		// tags:
		//		callback
	},
	onClose: function(/* dojo.data */ item, /*TreeNode*/ node){
		// summary:
		//		Callback when a node is closed
		// tags:
		//		callback
	},

	_getNextNode: function(node){
		// summary:
		//		Get next visible node

		if(node.isExpandable && node.isExpanded && node.hasChildren()){
			// if this is an expanded node, get the first child
			return node.getChildren()[0];		// _TreeNode
		}else{
			// find a parent node with a sibling
			while(node && node.isTreeNode){
				var returnNode = node.getNextSibling();
				if(returnNode){
					return returnNode;		// _TreeNode
				}
				node = node.getParent();
			}
			return null;
		}
	},

	_getRootOrFirstNode: function(){
		// summary:
		//		Get first visible node
		return this.showRoot ? this.rootNode : this.rootNode.getChildren()[0];
	},

	_collapseNode: function(/*_TreeNode*/ node){
		// summary:
		//		Called when the user has requested to collapse the node

		if(node._expandNodeDeferred){
			delete node._expandNodeDeferred;
		}

		if(node.isExpandable){
			if(node.state == "LOADING"){
				// ignore clicks while we are in the process of loading data
				return;
			}

			node.collapse();
			this.onClose(node.item, node);

			if(node.item){
				this._state(node.item,false);
				this._saveState();
			}
		}
	},

	_expandNode: function(/*_TreeNode*/ node, /*Boolean?*/ recursive){
		// summary:
		//		Called when the user has requested to expand the node
		// recursive:
		//		Internal flag used when _expandNode() calls itself, don't set.
		// returns:
		//		Deferred that fires when the node is loaded and opened and (if persist=true) all it's descendants
		//		that were previously opened too

		if(node._expandNodeDeferred && !recursive){
			// there's already an expand in progress (or completed), so just return
			return node._expandNodeDeferred;	// dojo.Deferred
		}

		var model = this.model,
			item = node.item,
			_this = this;

		switch(node.state){
			case "UNCHECKED":
				// need to load all the children, and then expand
				node.markProcessing();

				// Setup deferred to signal when the load and expand are finished.
				// Save that deferred in this._expandDeferred as a flag that operation is in progress.
				var def = (node._expandNodeDeferred = new dojo.Deferred());

				// Get the children
				model.getChildren(
					item,
					function(items){
						node.unmarkProcessing();

						// Display the children and also start expanding any children that were previously expanded
						// (if this.persist == true).   The returned Deferred will fire when those expansions finish.
						var scid = node.setChildItems(items);

						// Call _expandNode() again but this time it will just to do the animation (default branch).
						// The returned Deferred will fire when the animation completes.
						// TODO: seems like I can avoid recursion and just use a deferred to sequence the events?
						var ed = _this._expandNode(node, true);

						// After the above two tasks (setChildItems() and recursive _expandNode()) finish,
						// signal that I am done.
						scid.addCallback(function(){
							ed.addCallback(function(){
								def.callback();
							})
						});
					},
					function(err){
						console.error(_this, ": error loading root children: ", err);
					}
				);
				break;

			default:	// "LOADED"
				// data is already loaded; just expand node
				def = (node._expandNodeDeferred = node.expand());

				this.onOpen(node.item, node);

				if(item){
					this._state(item, true);
					this._saveState();
				}
		}

		return def;	// dojo.Deferred
	},

	////////////////// Miscellaneous functions ////////////////

	focusNode: function(/* _tree.Node */ node){
		// summary:
		//		Focus on the specified node (which must be visible)
		// tags:
		//		protected

		// set focus so that the label will be voiced using screen readers
		dijit.focus(node.labelNode);
	},

	_onNodeFocus: function(/*dijit._Widget*/ node){
		// summary:
		//		Called when a TreeNode gets focus, either by user clicking
		//		it, or programatically by arrow key handling code.
		// description:
		//		It marks that the current node is the selected one, and the previously
		//		selected node no longer is.

		if(node){
			if(node != this.lastFocused && this.lastFocused && !this.lastFocused._destroyed){
				// mark that the previously selected node is no longer the selected one
				this.lastFocused.setSelected(false);
			}

			// mark that the new node is the currently selected one
			node.setSelected(true);
			this.lastFocused = node;
		}
	},

	_onNodeMouseEnter: function(/*dijit._Widget*/ node){
		// summary:
		//		Called when mouse is over a node (onmouseenter event)
	},

	_onNodeMouseLeave: function(/*dijit._Widget*/ node){
		// summary:
		//		Called when mouse is over a node (onmouseenter event)
	},

	//////////////// Events from the model //////////////////////////

	_onItemChange: function(/*Item*/ item){
		// summary:
		//		Processes notification of a change to an item's scalar values like label
		var model = this.model,
			identity = model.getIdentity(item),
			nodes = this._itemNodesMap[identity];

		if(nodes){
			var self = this;
			dojo.forEach(nodes,function(node){
				node.attr({
					label: self.getLabel(item),
					tooltip: self.getTooltip(item)
				});
				node._updateItemClasses(item);
			});
		}
	},

	_onItemChildrenChange: function(/*dojo.data.Item*/ parent, /*dojo.data.Item[]*/ newChildrenList){
		// summary:
		//		Processes notification of a change to an item's children
		var model = this.model,
			identity = model.getIdentity(parent),
			parentNodes = this._itemNodesMap[identity];

		if(parentNodes){
			dojo.forEach(parentNodes,function(parentNode){
				parentNode.setChildItems(newChildrenList);
			});
		}
	},

	_onItemDelete: function(/*Item*/ item){
		// summary:
		//		Processes notification of a deletion of an item
		var model = this.model,
			identity = model.getIdentity(item),
			nodes = this._itemNodesMap[identity];

		if(nodes){
			dojo.forEach(nodes,function(node){
				var parent = node.getParent();
				if(parent){
					// if node has not already been orphaned from a _onSetItem(parent, "children", ..) call...
					parent.removeChild(node);
				}
				node.destroyRecursive();
			});
			delete this._itemNodesMap[identity];
		}
	},

	/////////////// Miscellaneous funcs

	_initState: function(){
		// summary:
		//		Load in which nodes should be opened automatically
		if(this.persist){
			var cookie = dojo.cookie(this.cookieName);
			this._openedItemIds = {};
			if(cookie){
				dojo.forEach(cookie.split(','), function(item){
					this._openedItemIds[item] = true;
				}, this);
			}
		}
	},
	_state: function(item,expanded){
		// summary:
		//		Query or set expanded state for an item,
		if(!this.persist){
			return false;
		}
		var id=this.model.getIdentity(item);
		if(arguments.length === 1){
			return this._openedItemIds[id];
		}
		if(expanded){
			this._openedItemIds[id] = true;
		}else{
			delete this._openedItemIds[id];
		}
	},
	_saveState: function(){
		// summary:
		//		Create and save a cookie with the currently expanded nodes identifiers
		if(!this.persist){
			return;
		}
		var ary = [];
		for(var id in this._openedItemIds){
			ary.push(id);
		}
		dojo.cookie(this.cookieName, ary.join(","), {expires:365});
	},

	destroy: function(){
		if(this._curSearch){
			clearTimeout(this._curSearch.timer);
			delete this._curSearch;
		}
		if(this.rootNode){
			this.rootNode.destroyRecursive();
		}
		if(this.dndController && !dojo.isString(this.dndController)){
			this.dndController.destroy();
		}
		this.rootNode = null;
		this.inherited(arguments);
	},

	destroyRecursive: function(){
		// A tree is treated as a leaf, not as a node with children (like a grid),
		// but defining destroyRecursive for back-compat.
		this.destroy();
	},

	resize: function(changeSize){
		if(changeSize){
			dojo.marginBox(this.domNode, changeSize);
			dojo.style(this.domNode, "overflow", "auto");	// for scrollbars
		}

		// The only JS sizing involved w/tree is the indentation, which is specified
		// in CSS and read in through this dummy indentDetector node (tree must be
		// visible and attached to the DOM to read this)
		this._nodePixelIndent = dojo.marginBox(this.tree.indentDetector).w;

		if(this.tree.rootNode){
			// If tree has already loaded, then reset indent for all the nodes
			this.tree.rootNode.attr('indent', this.showRoot ? 0 : -1);
		}
	},

	_createTreeNode: function(/*Object*/ args){
		// summary:
		//		creates a TreeNode
		// description:
		//		Developers can override this method to define their own TreeNode class;
		//		However it will probably be removed in a future release in favor of a way
		//		of just specifying a widget for the label, rather than one that contains
		//		the children too.
		return new dijit._TreeNode(args);
	}
});

// For back-compat.  TODO: remove in 2.0



}

if(!dojo._hasResource["thejekels.CheckBox"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["thejekels.CheckBox"] = true;
dojo.provide("thejekels.CheckBox");
dojo.provide("thejekels.CheckBoxTree");
dojo.provide("thejekels.CheckBoxStoreModel");

// THIS WIDGET IS BASED ON DOJO/DIJIT 1.4.0 AND WILL NOT WORK WITH PREVIOUS VERSIONS
//
//	Release date: 02/12/2010
//





dojo.declare( "thejekels.CheckBox", dijit.form.CheckBox,
{
	// baseClass: [protected] String
	//		Root CSS class of the widget (ex: thejekelsCheckBox), used to add CSS classes of widget
	//		(ex: "thejekelsCheckBox thejekelsCheckBoxChecked thejekelsCheckBoxFocused thejekelsCheckBoxMixed")
	//		See _setStateClass().
	baseClass: "thejekelsCheckBox",

	// value: String
	//		As an initialization parameter, equivalent to value field on normal checkbox
	//		(if checked, the value is passed as the value when form is submitted).
	//
	//		However, attr('value') will return either the string or false depending on
	//		whether or not the checkbox is checked.
	//
	value: "unchecked",

	_setStateClass: function(){
		// summary:
		//		Update the visual state of the widget by setting the css classes on this.domNode
		//		(or this.stateNode if defined) by combining this.baseClass with	various suffixes 
		//		that represent the current widget state(s).
		//		Overwrites the default _setStateClass method.
		//
		// description:
		//		In the case where a widget has multiple	states, it sets the class based on all 
		//		possible combinations.  For example, an invalid form widget that is being hovered
		//		will be "dijitInput dijitInputInvalid dijitInputHover dijitInputInvalidHover".
		//
		var newStateClasses = this.baseClass.split(" ");

		function multiply(modifier){
			newStateClasses = newStateClasses.concat(dojo.map(newStateClasses, 
				function(c){ return c+modifier; }), 
				"dijit"+modifier);
		}

		if(this.state){	multiply(this.state); }
		if(this.attr('value') == "mixed" ){ 
			multiply("Mixed"); 
		} else if(this.checked){ 
			multiply("Checked"); 
		}
		if(this.disabled){ 
			multiply("Disabled");
		}else if(this._active){
			multiply(this.stateModifier+"Active");
		}else{
			if(this._focused){
				multiply("Focused");
			}
			if(this._hovering){
				multiply(this.stateModifier+"Hover");
			}
		}

		// Remove old state classes and add new ones.
		// For performance concerns we only write into domNode.className once.
		var tn = this.stateNode || this.domNode,
			classHash = {};	// set of all classes (state and otherwise) for node

		dojo.forEach(tn.className.split(" "), function(c){ classHash[c] = true; });

		if("_stateClasses" in this){
			dojo.forEach(this._stateClasses, function(c){ delete classHash[c]; });
		}

		dojo.forEach(newStateClasses, function(c){ classHash[c] = true; });

		var newClasses = [];
		for(var c in classHash){
			newClasses.push(c);
		}
		tn.className = newClasses.join(" ");

		this._stateClasses = newStateClasses;
	},

	_setValueAttr: function(/*String or Boolean*/ newValue){
		// summary:
		//		Handler for value= attribute to constructor, and also calls to attr('value', val).
		//		Overwrites the default '_setValueAttr' function as we will handle the Checkbox 
		//		checked attribute explictly.
		// description:
		//		If passed a string, changes the value attribute of the CheckBox (the one specified 
		//		as "value" when the CheckBox was constructed.

		if(typeof newValue == "string"){
			this.value = newValue;
			dojo.attr(this.focusNode, 'value', newValue);
		}
	},

	_getValueAttr: function(){
		// summary:
		//		Hook so attr('value') works.
		// description:
		//		Returns the value attribute.
		return this.value;
	}
	
});

dojo.declare( "thejekels.CheckBoxStoreModel", dijit.tree.ForestStoreModel, 
{
	// checkboxAll: Boolean
	//		If true, every node in the tree will receive a checkbox regardless if the 'checkbox' attribute 
	//		is specified in the dojo.data.
	checkboxAll: true,

	// checkboxState: Boolean
	// 		The default state applied to every checkbox unless otherwise specified in the dojo.data.
	//		(see also: checkboxIdent)
	checkboxState: false,

	// checkboxRoot: Boolean
	//		If true, the root node will receive a checkbox eventhough it's not a true entry in the store.
	//		This attribute is independent of the showRoot attribute of the tree itself. If the tree
	//		attribute 'showRoot' is set to false the checkbox for the root will not show either.  
	checkboxRoot: false,

	// checkboxStrict: Boolean
	//		If true, a strict parent-child checkbox relation is maintained. For example, if all children 
	//		are checked the parent will automatically be checked or if any of the children are unchecked
	//		the parent will be unchecked. 
	checkboxStrict: true,

	// checkboxIdent: String
	//		The attribute name (attribute of the dojo.data.item) that specifies the items checkbox initial
	//		state. Example:	{ name:'Egypt', type:'country', checkbox: true }
	//		If a dojo.data.item has no 'checkbox' attribute specified it will depend on the attribute
	//		'checkboxAll' if one will be created automatically and if so what the initial state will be as
	//		specified by 'checkboxState'. 
	checkboxIdent: "checkbox",
	
	// checkboxMState: String
	//		The attribute name (attribute of the dojo.data/item) that will hold the checkbox mixed state. 
	//		The mixed state is seperate from the default checked/unchecked state of a checkbox. The mixed 
	//		state attribute will be either true or false.
	checkboxMState: "mixed",
	
	updateCheckbox: function(/*dojo.data.Item*/ storeItem, /*Boolean*/ newState ) {
		// summary:
		//		Update the checkbox state (true/false) for the store item and the associated parent
		//		and child checkboxes if any. 
		// description:
		//		Update a single checkbox state (true/false) for the store item and the associated parent 
		//		and child checkboxes if any. This function is called from the tree if a user checked
		//		or unchecked a checkbox on the tree. The parent and child tree nodes are updated to 
		//		maintain consistency if 'checkboxStrict' is set to true.
		//	storeItem:
		//		The item in the dojo.data.store whos checkbox state needs updating.
		//	newState:
		//		The new state of the checkbox: true or false
		//	example:
		//	| model.updateCheckboxState(item, true);
		//
		if( !this.checkboxStrict ) {
			this._setCheckboxState( storeItem, newState, false );		// Just update the checkbox state
		} else {
			this._updateChildCheckbox( storeItem, newState );			// Update children and parent(s).
		}
	},

	getCheckboxState: function(/*dojo.data.Item*/ storeItem) {
		// summary:
		//		Get the current checkbox state from the dojo.data.store.
		// description:
		//		Get the current checkbox state from the dojo.data store. A checkbox can have three
		//		different states: true, false or undefined. Undefined in this context means no
		//		checkbox identifier (checkboxIdent) was found in the dojo.data store. Depending on
		//		the checkbox attributes as specified above the following will take place:
		//		a) 	If the current checkbox state is undefined and the checkbox attribute 'checkboxAll' or
		//			'checkboxRoot' is true one will be created and the default state 'checkboxState' will
		//			be applied.
		//		b)	If the current state is undefined and 'checkboxAll' is false the state undefined remains
		//			unchanged and is returned. This will prevent any tree node from creating a checkbox.
		//
		//	storeItem:
		//		The item in the dojo.data.store whos checkbox state is returned.
		//	example:
		//	| var currState = model.getCheckboxState(item);
		//		
		var state = { checked: undefined, mixed: false };	// create and initialize state object
		
		// Special handling required for the 'fake' root entry (the root is NOT a dojo.data.item).
		if ( storeItem == this.root ) {
			if( typeof(storeItem.checkbox) == "undefined" ) {
				this.root.checkbox = undefined;		// create a new checbox reference as undefined.
				this.root.mixedState = false;
				if( this.checkboxRoot ) {
					this._setCheckboxState( storeItem, this.checkboxState, false );
					state.checked = this.checkboxState;
				}
			} else {
				state.checked = this.root.checkbox;
				state.mixed	  = this.root.mixedState;
			}
		} else {	// a valid dojo.store.item
			state.checked = this.store.getValue(storeItem, this.checkboxIdent);
			state.mixed	  = this.store.getValue(storeItem, this.checkboxMState);
			if( state.checked == undefined && this.checkboxAll) {
				this._setCheckboxState( storeItem, this.checkboxState, false );
				state.checked = this.checkboxState;
			}
		}
		return state  // the current state of the checkbox (true/false or undefined)
	},

	_setCheckboxState: function(/*dojo.data.Item*/ storeItem, /*Boolean*/ newState, /*Boolean*/ mixedState ) {
		// summary:
		//		Set/update (stores) the checkbox state on the dojo.data store.
		// description:
		//		Set/update the checkbox state on the dojo.data.store. Retreive the current
		//		state of the checkbox and validate if an update is required, this will keep 
		//		update events to a minimum. On completion a 'onCheckboxChange' event is
		//		triggered if required. 
		//		If the current state is undefined (ie: no checkbox attribute specified for 
		//		this dojo.data.item) the 'checkboxAll' attribute is checked	to see if one 
		//		needs to be created. In case of the root the 'checkboxRoot' attribute is checked.
		//		NOTE: the store.setValue function will create the 'checkbox' attribute for the 
		//		item if none exists.   
		//	storeItem:
		//		The item in the dojo.data.store whos checkbox state is updated.
		//	newState:
		//		The new state of the checkbox: true or false
		//	mixedState:
		//		Indicates if a parent checkbox has a mixed state (i.e some children checked but not all)
		//	example:
		//	| model.setCheckboxState(item, true, false);
		//
		var stateChanged = true;

		if( storeItem != this.root ) {
			var currState = this.store.getValue(storeItem, this.checkboxIdent);
			var currMixed = this.store.getValue(storeItem, this.checkboxMState);
			if( (currState != newState || currMixed != mixedState) && (currState !== undefined || this.checkboxAll) ) {
				this.store.setValue(storeItem, this.checkboxIdent, newState);
				this.store.setValue(storeItem, this.checkboxMState, mixedState);
			} else {
				stateChanged = false;
			}
		} else {  // Tree root instance
			if( ( this.root.checkbox != newState || this.root.mixedState != mixedState ) && 
				( this.root.checkbox !== undefined || this.checkboxRoot ) ) {
				this.root.checkbox   = newState;
				this.root.mixedState = mixedState;
			} else {
				stateChanged = false;
			}
		}
		if( stateChanged ) {	// In case of any changes trigger the update event.
			this.onCheckboxChange(storeItem, mixedState);
		}
		return stateChanged;
	},

	_updateChildCheckbox: function(/*dojo.data.Item*/ storeItem, /*Boolean*/ newState ) {
		//	summary:
		//		Set the parent (storeItem) and all child checkboxes to true/false
		//	description:
		//		If a parent checkbox changes state, all child and grandchild checkboxes will be 
		//		updated to reflect the change. For example, if the parent state is set to true, 
		//		all child and grandchild checkboxes will receive that same 'true' state.
		//		If a child checkbox changes state all of its parents need to be re-evaluated.
		//	storeItem:
		//		The parent dojo.data.item whos child/grandchild checkboxes require updating.
		//	newState:
		//		The new state of the checkbox: true or false

		if( this.mayHaveChildren( storeItem )) {
			this.getChildren( storeItem, dojo.hitch( this,
				function( children ) {
					dojo.forEach( children, function(child) {
						this._updateChildCheckbox( child, newState );
					}, this );					
				}),
				function(err) {
					console.error(this, ": updating child checkboxes: ", err);
				});
		} else {
			if( this._setCheckboxState(storeItem, newState, false) ) {
				this._updateParentCheckbox( storeItem );
			}
		}
	},

	_updateParentCheckbox: function(/*dojo.data.Item*/ storeItem ) {
		//	summary:
		//		Update the parent checkbox states depending on the state of all child checkboxes.
		//	description:
		//		Update the parent checkbox states depending on the state of all child checkboxes.
		//		The parent checkbox automatically changes state if ALL child checkboxes are true
		//		or false. If, as a result, the parent checkbox changes state, we will check if 
		//		its parent needs to be updated as well, all the way upto the root. 
		//		NOTE: 	If any of the children have a mixed state, the parent must get a mixed 
		//				state too
		//	storeItem:
		//		The dojo.data.item whos parent checkboxes require updating.
		//
		var parents = this._getParentsItem( storeItem );
		dojo.forEach( parents, function( parentItem ) {
			this.getChildren( parentItem, dojo.hitch( this,
				function(children) {
					var hasChecked   	= false,
						hasUnChecked 	= false,
						isMixed			= false;
					dojo.some( children, function(child) {
						state = this.getCheckboxState(child);
						isMixed |= state.mixed;
						switch( state.checked ) {	// ignore 'undefined' state
							case true:
								hasChecked = true;
								break;
							case false: 
								hasUnChecked = true;
								break;
						}
						return isMixed;
					}, this );
					isMixed |= !(hasChecked ^ hasUnChecked);
					// If the parent has a mixed state its checked state is always true.
					if( this._setCheckboxState( parentItem,	isMixed ? true: hasChecked, isMixed ? true: false ) ) {
						this._updateParentCheckbox( parentItem );
					}
				}),
				function(err) {
					console.error(this, ": fetching mixed state: ", err);
				});
		}, this );
	},
	
	_getParentsItem: function(/*dojo.data.Item*/ storeItem ) {
		// summary:
		//		Get the parent(s) of a dojo.data item.  
		// description:
		//		Get the parent(s) of a dojo.data item. The '_reverseRefMap' entry of the item is
		//		used to identify the parent(s). A child will have a parent reference if the parent 
		//		specified the '_reference' attribute. 
		//		For example: children:[{_reference:'Mexico'}, {_reference:'Canada'}, ...
		//	storeItem:
		//		The dojo.data.item whos parent(s) will be returned.
		//
		var parents = [];

		if( storeItem != this.root ) {
			var references = storeItem[this.store._reverseRefMap];
			for(itemId in references ) {
				parents.push(this.store._itemsByIdentity[itemId]);
			}
			if (!parents.length) {
				parents.push(this.root);
			}
		}
		return parents // parent(s) of a dojo.data.item (Array of dojo.data.items)
	},
	
	validateData: function(/*dojo.data.Item*/ storeItem, /*thisObject*/ scope ) {
		// summary:
		//		Validate/normalize the parent-child checkbox relationship if the attribute
		//		'checkboxStrict' is set to true. This function is called as part of the post 
		//		creation of the Tree instance. First we try a forced synchronous load of the
		//		Json datafile dramatically improving the startup time.
		//	storeItem:
		//		The element to start traversing the dojo.data.store, typically the tree root
		//	scope:
		//		The scope to use when executing this method.

		if( scope.checkboxStrict ) {
			try {
				scope.store._forceLoad();		// Try a forced synchronous load
			} catch(e) { 
				console.log(e);
			}
			dojo.hitch( scope, scope._validateStore ) ( storeItem ); 
		}
	},

	_validateStore: function(/*dojo.data.Item*/ storeItem ) {
		// summary:
		//		Validate/normalize the parent(s) checkbox data in the dojo.data store.
		// description:
		//		All parent checkboxes are set to the appropriate state according to the actual 
		//		state(s) of their children. This will potentionally overwrite whatever was 
		//		specified for the parent in the	dojo.data store. This will garantee the tree 
		//		is in a consistent state after startup.
		//	storeItem:
		//		The element to start traversing the dojo.data.store, typically model.root
		//	example:
		//	| this._validateStore( storeItem );
		//
		this.getChildren( storeItem, dojo.hitch( this,
			function(children) {
				var hasGrandChild = false,
					oneChild	  = null;					
				dojo.forEach( children, function( child ) {
					if( this.mayHaveChildren( child )) {
						this._validateStore( child );
						hasGrandChild = true;
					} else {
						oneChild = child;
					}
				},this );
				if( !hasGrandChild && oneChild ) {		// Find a child on the lowest branches
					this._updateParentCheckbox( oneChild );
				}
			}),
			function(err) {
				console.error(this, ": validating checkbox data: ", err);
			} 
		);
	},
	
	onCheckboxChange: function(/*dojo.data.Item*/ storeItem ) {
		// summary:
		//		Callback whenever a checkbox state has changed state, so that 
		//		the Tree can update the checkbox.  This callback is generally
		//		triggered by the '_setCheckboxState' function. 
		// tags:
		//		callback
	}
		
});

dojo.declare( "thejekels._CheckBoxTreeNode", dijit._TreeNode,
{
	// _checkbox: [protected] dojo.doc.element
	//		Local reference to the dojo.doc.element of type 'checkbox'
	_checkbox: null,

	_createCheckbox: function() {
		// summary:
		//		Create a checkbox on the CheckBoxTreeNode
		// description:
		//		Create a checkbox on the CheckBoxTreeNode. The checkbox is ONLY created if a
		//		valid checkbox attribute was found in the dojo.data store or the attribute 
		//		'checkboxAll' is set to true. 
		//		If the 'checked' property of the state is 'undefined' no reference was found
		//		and if 'checkboxAll' is set to false no checkbox will be created.
		//		NOTE: the attribute 'checkboxAll' is validated by the _getCheckboxState function
		//			  therefore no need to do it here. (see _getCheckboxState for details).
		//		
		var	state = this.tree.model.getCheckboxState( this.item );
		if( state.checked !== undefined ) {
			if (this.tree.checkboxStyle == "dijit" ) {
				this._checkbox = new thejekels.CheckBox().placeAt(this.expandoNode,'after');
			} else {
				this._checkbox = dojo.doc.createElement('input');
				this._checkbox.className = 'thejekelsHTMLCheckBox';
				this._checkbox.type      = 'checkbox';
				dojo.place(this._checkbox, this.expandoNode, 'after');
			}
			this._setCheckedState( state );
		}
		if( this.isExpandable ) {
			if ( !this.tree.branchIcons ) {
				dojo.removeClass(this.iconNode,"dijitTreeIcon");
			}
		} else {
			if( !this.tree.nodeIcons ) {
				dojo.removeClass(this.iconNode,"dijitTreeIcon");
			}
		}
	},
	
	_setCheckedState: function( /*Object*/ state ) {
		// summary:
		//		Update the 'checked' and 'value' attributes of a HTML or dijit checkbox.
		// description:
		//		Update the checkbox 'checked' state and 'value'. Considering a checkbox can only have a
		//		'checked/unchecked' state the thru state 'checked/unchecked/mixed' is store in the value
		//		property of the checkbox. The visual aspect of a mixed checkbox state is only supported
		//		when using the dijit style checkbox (default).
		//	state:
		//		The state argument is an object with two properties: 'checked' and 'mixed' 

		if( this.tree.checkboxStyle == "dijit" ) {
			if( this.tree.checkboxMultiState ) {
				this._checkbox.attr('value', state.mixed ? "mixed" : state.checked ? "checked" : "unchecked" );
			} else {
				this._checkbox.attr('value', state.checked ? "checked" : "unchecked" );
			}
			this._checkbox.attr('checked', state.checked );
		} else {	// HTML native checkbox
			if( this.tree.checkboxMultiState ) {
				this._checkbox.value = state.mixed ? "mixed" : state.checked ? "checked" : "unchecked";
			} else {
				this._checkbox.value = state.checked ? "checked" : "unchecked";
			}
			this._checkbox.checked = state.checked;
		}
	},
	
	_getCheckedState: function() {
		// summery:
		//		Get the current checked state of the checkbox.
		// description:
		//		Get the current checked state of the checkbox. It returns true or false.

		return this._checkbox.checked;
	},
	
	postCreate: function() {
		// summary:
		//		Handle the creation of the checkbox after the CheckBoxTreeNode has been instanciated.
		// description:
		//		Handle the creation of the checkbox after the CheckBoxTreeNode has been instanciated.
		this._createCheckbox();
		this.inherited( arguments );
	}

});

dojo.declare( "thejekels.CheckBoxTree", dijit.Tree,
{
	// checkboxStyle: String
	//		Sets the style of the checkbox to be used. The default is "dijit" anything else will force the
	//		use of the native HTML style checkbox. The visual representation of a mixed state checkbox is
	//		only supported with a dijit style checkbox. 
	checkboxStyle: "dijit",

	// checkboxMultiState: Boolean
	//		Determines if Multi State checkbox behaviour is required, default is true. If set to false the
	//		value property of a checkbox will only be 'checked' or 'unchecked'. If true the value can be
	//		'checked', 'unchecked' or 'mixed'
	checkboxMultiState: true,

	// branchIcons: Boolean
	//		Determines if the FolderOpen/FolderClosed icon is displayed.
	branchIcons: true,

	// nodeIcons: Boolean
	//		Determines if the Leaf icon is displayed.
	nodeIcons: true,
	
	onNodeChecked: function(/*dojo.data.Item*/ storeItem, /*treeNode*/ treeNode) {
		// summary:
		//		Callback when a checkbox tree node is checked
		// tags:
		//		callback
	},
	
	onNodeUnchecked: function(/*dojo.data.Item*/ storeItem, /* treeNode */ treeNode) {
		// summary:
		//		Callback when a checkbox tree node is unchecked
		// tags:
		//		callback
	},
	
	_onClick: function(/*TreeNode*/ nodeWidget, /*Event*/ e) {
		// summary:
		//		Translates click events into commands for the controller to process
		// description:
		//		the _onClick function is called whenever a 'click' is detected. This
		//		instance of _onClick only handles the click events associated with
		//		the checkbox whos DOM name is INPUT.
		// 
		var domElement = e.target;

		// Only handle checkbox clicks here
		if(domElement.nodeName != 'INPUT') {
			return this.inherited( arguments );
		}
		this._publish("execute", { item: nodeWidget.item, node: nodeWidget} );
		// Go tell the model to update the checkbox state
		this.model.updateCheckbox( nodeWidget.item, nodeWidget._getCheckedState() ); 
		// Generate some additional events
		this.onClick( nodeWidget.item, nodeWidget, e );
		if(nodeWidget._getCheckedState() ) {
			this.onNodeChecked( nodeWidget.item, nodeWidget);
		} else {
			this.onNodeUnchecked( nodeWidget.item, nodeWidget);
		}
		this.focusNode(nodeWidget);
		if( this.checkboxStyle == "dijit" ) 
			dojo.stopEvent(e);
	},
	
	_onCheckboxChange: function(/*dojo.data.Item*/ storeItem ) {
		// summary:
		//		Process notification of a change to a checkbox state (triggered by the model).
		// description:
		//		Whenever the model changes the state of a checkbox in the dojo.data.store it will 
		//		trigger the 'onCheckboxChange' event allowing the Tree to make the same changes 
		//		on the tree Node. There is a condition however when we get a checkbox update but 
		//		the associated tree node does not exist:
		//		- The node has not been created yet because the user has not expanded the tree/branch
		//		  or the initial data validation triggered the update in which case there are no
		//		  tree nodes at all.
		// tags:
		//		callback

		var model 	 = this.model,
			state	 = model.getCheckboxState( storeItem ),
			identity = model.getIdentity(storeItem),
			nodes 	 = this._itemNodesMap[identity];
	
		// As of dijit.Tree 1.4 multiple references (parents) are supported, therefore we may have
		// to update multiple nodes which are all associated with the same dojo.data.item.
		if( nodes ) {
			dojo.forEach( nodes, function(node) {
				if( node._checkbox != null )
					node._setCheckedState( state );
			}, this );
		}
	}, 

	postCreate: function() {
		// summary:
		//		Handle any specifics related to the tree and model after the instanciation of the Tree. 
		// description:
		//		Validate if we have a 'write' store first. Subscribe to the 'onCheckboxChange' event 
		//		(triggered by the model) and kickoff the initial checkbox data validation.
		//
		var store = this.model.store;
		if(!store.getFeatures()['dojo.data.api.Write']){
			throw new Error("thejekels.CheckboxTree: store must support dojo.data.Write");
		}
		this.connect(this.model, "onCheckboxChange", "_onCheckboxChange");
		this.model.validateData( this.model.root, this.model );
		this.inherited(arguments);
	},
	
	_createTreeNode: function( args ) {
		// summary:
		//		Create a new CheckboxTreeNode instance.
		// description:
		//		Create a new CheckboxTreeNode instance.
		return new thejekels._CheckBoxTreeNode(args);
	}

});

}















dojo.registerModulePath('thejekels','../thejekels');


function processModel()
{
    //console.info('processModel()');
    
    /*var checkedItems = dojo.query('.thejekelsCheckBoxChecked > :checked').forEach(function(node, index, arr){
      console.debug(arr);
  }); */
      
    /*
    var checkedItems = dojo.query('#menuTree').forEach(function(node, index, arr){
        //console.debug(node);
    });   

    */
    targetDiv = 'modellingResponse';

    var xhrArgs = {
     url: 'js/iems/resources/assetModelling.ajax.php?action=final',
     handleAs: "text",
     form: 'modellingForm',
     load: function(data) {
         dojo.byId(targetDiv).innerHTML = data;         
     },
     error: function(error) {

         dojo.byId(targetDiv).innerHTML = 'There was an error with your request.  Please try again.<br />If the problem persists contact <a href="http://help.crsolutions.us/" target="_blank">CRS Helpdesk</a>.';
     }
    }
    dojo.byId(targetDiv).innerHTML = 'Processing Your Modelling Information . . .'
    dojo.xhrPost(xhrArgs); 

}

function callModellingForm(programId, programName)
{
    var alarmSlide = new Fx.Slide('cpAlarm');
    alarmSlide.hide();
    if( dijit.byId('bc') )
    {        
        dijit.byId('cp1').destroy();
        dijit.byId('cp2').destroy();
        dijit.byId('bc').destroy();
        dijit.byId('modellingForm').destroy();
        dijit.byId('goButton').destroy();
    }

    var bc = new dijit.layout.BorderContainer({
        style: "min-height: 900px; width: 850px; background-color: transparent; border: none;",
        id: 'bc',
        gutter: false,
        liveSplitters: true
    });        
    
    var cp1 = new dijit.layout.ContentPane({
       region: 'left',
       style: 'height: 100%; margin-top: 30px; background-color: transparent; text-align: left; border: none;',       
       id: 'cp1',
       splitter: true
    });
    bc.addChild(cp1);

    var cp2 = new dijit.layout.ContentPane({
       region: 'center',
       style: 'height: 100%; background-color: transparent; text-align: left; border: none;',
       href: 'js/iems/resources/assetModelling.ajax.php?action=init',
       id: 'cp2'
    });
    bc.addChild(cp2);
    
    //document.body.appendChild(bc.domNode);
    
    var responseDiv = dojo.create('div',{id: 'modellingResponse'});
    dojo.style(responseDiv, 'text-align', 'left');

    dojo.byId('dataReturn_res').innerHTML = '';

    dojo.byId('dataReturn_res').appendChild(responseDiv);
    dojo.byId('dataReturn_res').appendChild(bc.domNode);

    dojo.style(dojo.byId('dataReturn_res'),'display','block');
    dojo.style(dojo.byId('dataReturn_table'),'display','none');    
    
    
    bc.startup();

    assetModelTree( 'cp1' , programId, programName, dojo.byId('assetModelDateSelection').value);    
    bc.resize();

}

function assetModelTree( domLocation , programId , programName , monthSelection) {
    if( dijit.byId('menuTree'))
    {
        dijit.byId('menuTree').destroy();
    }
    var store = new dojo.data.ItemFileWriteStore( {
            //url: "../meters.store.php"
            url: "meters.store.php?program=" + programId + "&month=" + monthSelection
      });


        var model = new thejekels.CheckBoxStoreModel( {
                        store: store,
                        query: {type: 'resource'},
                        rootLabel: programName,
                        checkboxAll:  true,
                        checkboxRoot: true,
                        checkboxState: false,
                        checkboxStrict: true
                        });    
    
        var tree = new thejekels.CheckBoxTree( {
                        model: model,
                        id: "menuTree",
                        allowMultiState: true,
                        branchIcons: false,
                        nodeIcons: false,
                        showRoot: true
                        });
        
        tree.placeAt( domLocation );


    dojo.connect( tree,"onNodeChecked", function(storeItem, nodeWidget){        
        if( !storeItem.root )
        {
            if( storeItem.children )
            {   
                storeItem.children.forEach(function(node, index, arr){
                    createAssetSet(node.id,node.name);
                });                
            }
            else
            {
                //console.info(storeItem.value);
                createAssetSet(storeItem.id,storeItem.name);
            }
        }
        else
        {
            storeItem.children.forEach(function(parent, parentIndex, parentArr){
                parent.children.forEach(function(node, index, arr){
                    createAssetSet(node.id,node.name);
                });                
            });
        }
        dojo.byId('checkStackMessage').innerHTML = '';
        
    });

    dojo.connect( tree,"onNodeUnchecked", function(storeItem, nodeWidget){
        if( !storeItem.root )
        {
            if( storeItem.children )
            {                
                storeItem.children.forEach(function(node, index, arr){ 
                    try
                    {
                        //console.info('destroying ' + 'assetPriority[' + node.id + ']');
                        if( dojo.byId('assetDiv[' + node.id + ']') )
                        {
                            dijit.byId('assetPriority[' + node.id + ']').destroy();
        
                            //console.info('destroying ' + 'checkedAssets[' + node.id + ']');
                            dijit.byId('checkedAssets[' + node.id + ']').destroy();            
                            
                            //console.info('destroying' + 'assetDiv[' + node.id + ']');
                            dojo.byId('checkStackDiv').removeChild(dojo.byId('assetDiv[' + node.id + ']')); //dojo .destroy() doesn't work here in ie
                        }
                    } catch( e )
                    {
                        console.info(e);
                    }
                  });                
                
            }
            else
            {   if(dojo.byId('assetDiv[' + storeItem.id + ']')){
                    //console.info('destroying ' + 'assetPriority[' + storeItem.id + ']');
                    dijit.byId('assetPriority[' + storeItem.id + ']').destroy();
    
                    //console.info('destroying ' + 'checkedAssets[' + storeItem.id + ']');
                    dijit.byId('checkedAssets[' + storeItem.id + ']').destroy();                
    
                    //console.info('destroying ' + 'assetDiv[' + storeItem.id + ']');
                   //alert('destroying ' + 'assetDiv[' + storeItem.id + ']');
                    dojo.byId('checkStackDiv').removeChild(dojo.byId('assetDiv[' + storeItem.id + ']')); //dojo .destroy() doesn't work here in ie
                    
                }
            }
        }
        else
        {
            dojo.query('#checkStackDiv >').forEach(function(node, index, arr)
            {
                console.log(dojo.query('>',dojo.byId(node.id)));
                dojo.query('>',dojo.byId(node.id)).forEach(function(childNode, childIndex, childArr)
                {
                    //console.log(childNode);

                    var childCoreId = childNode.id.replace('widget_',''); 
                    //console.log(childCoreId);

                    /* mcb start here
                    if(childCoreId != '')
                    {
                        alert(childCoreId);
                    }

                    */
                    if(childCoreId != '')
                    { 
                        //console.info(dijit.byId(childCoreId));
                        //dijit.byId(childCoreId).destroy();
                        //alert(childCoreId);
                        dijit.byId(childCoreId).destroy();
                       
                    }
                    

                }); 
                 
                //node.destroy();
                dojo.byId('checkStackDiv').removeChild(node); //dojo .destroy() doesn't work here in ie
            });
        }
    });

}

function createAssetSet(id,name)
{
    var newDiv = dojo.create('div',{id: 'assetDiv[' + id + ']'});    
    var newSpan = dojo.create('span');
    
    dojo.style(newSpan,'color','#FF6701');

    var newSpinner = new dijit.form.NumberSpinner({
            value: 1,
            smallDelta: 1,
            constraints: {
                min: 0,
                max: 3,
                places: 0
            },
            id: 'assetPriority[' + id + ']',
            name: 'assetPriority[' + id + ']',
            style: "background: transparent; width: 50px; padding-left: 20px; margin-right: 20px; margin-bottom: 10px;"
        });
    
    var newItem = new dijit.form.TextBox({
        value: name,
        name: 'checkedAssets[' + id + ']',
        id: 'checkedAssets[' + id + ']',
        type: 'hidden'
    });                

    newSpan.innerHTML = name;

    newDiv.appendChild(newSpinner.domNode); 
    newDiv.appendChild(newSpan); 
    newDiv.appendChild(newItem.domNode); 

    dojo.byId('checkStackDiv').appendChild(newDiv); 
}

function setAllPriorities(newValue)
{
    dojo.query('.dijitSpinner').forEach(function(node, index, arr){
        coreId = node.id.replace('widget_','');
        dojo.byId(coreId).value = newValue;
        //console.log(coreId);

        //dojo.query('> *', dojo.byId('container'))

        //console.info(dojo.query('~',dojo.byId(coreId)));
        dojo.query('~',dojo.byId(coreId)).forEach(function(childNode, childIndex, childArr){
            //console.log(childNode);
            childNode.value = newValue;
        });

    });
    
}

function callUploadForm()
{   
    if( dijit.byId('timerBar') ){dijit.byId('timerBar').destroy();} //remark this out when using test.php
    if( dijit.byId('asset_file') ){dijit.byId('asset_file').destroy();} //remark this out when using test.php

    targetDiv = dojo.byId('dataReturn_res');
    bar = dijit.byId("timerBar");    

    var xhrArgs = {
         url: 'progressbar/upload.php',
         //url: 'upload.php',  //use this when testing via test.php
         handleAs: "text",
         load: function(data) 
         {
             targetDiv.innerHTML = data;
             dojo.parser.parse(targetDiv);
         },
         error: function(error) {
             //targetDiv.innerHTML = 'There was an error with your request.  Please try again.<br />If the problem persists contact <a href="http://help.crsolutions.us/" target="_blank">CRS Helpdesk</a>.';
              targetDiv.innerHTML = error;
         }
    }    
    dojo.xhrPost(xhrArgs);

    dojo.style(targetDiv,'display','block');
    dojo.style(dojo.byId('dataReturn_table'),'display','none');

}

function processUpload()
{
    startProgress();
    
    targetDiv = dojo.byId('uploadResponse');      
    // because the form contains a file upload, we must use this framed method for management
    dojo.io.iframe.send({          
        form: "upload_form",
        url: 'progressbar/target.php',          
        //url: 'target.php', //use this when testing via test.php         
        handleAs: "text",              
        load: function(response, ioArgs) {
            targetDiv.innerHTML = response;
            //console.log(response);
        },
        error: function(error) {           
            targetDiv.innerHTML = 'There was an error with your request.  Please try again.<br />If the problem persists contact <a href="http://help.crsolutions.us/" target="_blank">CRS Helpdesk</a>.';
            console.log(error);
        }
    });      
    //dijit.byId('ubc').resize();
}

function getUploadProgress()
{    
    targetDiv = dojo.byId('uploadResponse');
    bar = dijit.byId("timerBar");    

    var xhrArgs = {
         url: 'progressbar/getprogress.php?progress_key=' + dojo.byId('progress_key').value,
         //url: 'getprogress.php?progress_key=' + dojo.byId('progress_key').value, //use this when testing via test.php
         handleAs: "text",
         load: function(percent) 
         {
             //document.getElementById("progressinner").style.width = percent+"%";
                   if (percent < 100){
                       bar.update({progress: percent});
                        setTimeout("getUploadProgress()", 100);
                   }
                   else
                   {
                       bar.update({progress: percent});
                   }
         },
         error: function(error) {
             targetDiv.innerHTML = 'There was an error with your request.  Please try again.<br />If the problem persists contact CRS Helpdesk.';
         }
    }    
    dojo.xhrPost(xhrArgs);
}

function startProgress(){
    dijit.byId('timerBar').update({progress: 0});
    dojo.style('timerBarContainer','display','inline');

    setTimeout("getUploadProgress()", 1000);
}

function fixFileName()
{
    //this is a little fixer to get rid of the IE8 "<drive>:\fakepath" silliness
    //it is aesthetic only; primarily intended to minimize user confusion.
    //this works, but so far getting a handle on an event to implement it has been the 
    //biggest challenge.  Tabling for now: 2010.05.26

    //console.log(dijit.byId('asset_file').inputNode); // thismay be useful
    var pathname = dojo.byId('asset_file').value;
    dijit.byId('asset_file').setValue(pathname.substr(pathname.lastIndexOf("\\")+1,pathname.length));
    console.log('fixed file name');
}


function validateUpload(targetDiv)
{
    console.log(dijit.byId('asset_file').inputNode);
    var ext = dojo.byId('asset_file').value;
	  	ext = ext.substring(ext.length-3,ext.length);
	  	ext = ext.toLowerCase();
        
	  	if(ext == 'csv')
			processUpload()
		else if(ext == 'txt') 
			processUpload()
	  	else {
	    		alert("You selected a ." + ext + " file; this is not allowed.");
	    		return false; 
		}
        
}

/*  ===============================================================================
    FUNCTION: AMToggler()
    ----------------------------------- params ------------------------------------
    targetDiv       :   dojo object
    =============================================================================== */
    function AMToggler(targetDiv)
    {
        //console.info(targetDiv); for troubleshooting
        if(dojo.style(targetDiv,'display') == 'none') {
            dojo.style(targetDiv,'display','inline');
        }
        else
        {
            dojo.style(targetDiv,'display','none');
        }
    }


dojo.i18n._preloadLocalizations("iems.nls.login", ["ROOT","ar","ca","cs","da","de","de-de","el","en","en-gb","en-us","es","es-es","fi","fi-fi","fr","fr-fr","he","he-il","hu","it","it-it","ja","ja-jp","ko","ko-kr","nl","nl-nl","no","pl","pt","pt-br","pt-pt","ru","sk","sl","sv","th","tr","xx","zh","zh-cn","zh-tw"]);
