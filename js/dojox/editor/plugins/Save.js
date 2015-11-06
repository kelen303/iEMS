/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


if(!dojo._hasResource["dojox.editor.plugins.Save"]){ //_hasResource checks added by build. Do not use _hasResource directly in your code.
dojo._hasResource["dojox.editor.plugins.Save"] = true;
dojo.provide("dojox.editor.plugins.Save");

dojo.require("dijit._editor._Plugin");
dojo.require("dijit.form.Button");
dojo.require("dojo.i18n");

dojo.requireLocalization("dojox.editor.plugins", "Save", null, "ROOT,ko");

dojo.declare("dojox.editor.plugins.Save",dijit._editor._Plugin,{
	// summary:
	//		This plugin provides Save cabability to the editor.  When 
	//		clicked, the document in the editor frame will be osted to the URL
	//		provided, or none, if none provided.  Users who desire a different save
	//		function can extend this plugin (via dojo.extend) and over-ride the 
	//		save method	while save is in process, the save button is disabled.

	// iconClassPrefix: [const] String
	//		The CSS class name for the button node is formed from `iconClassPrefix` 
	//		and `command`
	iconClassPrefix: "dijitAdditionalEditorIcon",

	// url [public]	String
	//		The URL to POST the content back to.  Used by the save function. 
	url: "",

	// logErrors [public] boolean
	//		Boolean flag to indicate that the default action for save and 
	//		error handlers is to just log to console.  Default is true.
	logResults: true,

	_initButton: function(){
		// summary:
		//		Over-ride for creation of the save button.
		var strings = dojo.i18n.getLocalization("dojox.editor.plugins", "Save");
		this.button = new dijit.form.Button({
			label: strings["save"],
			showLabel: false,
			iconClass: this.iconClassPrefix + " " + this.iconClassPrefix + "Save",
			tabIndex: "-1",
			onClick: dojo.hitch(this, "_save")
		});
	},

	setEditor: function(editor){
		// summary:
		//		Over-ride for the setting of the editor.
		// editor: Object
		//		The editor to configure for this plugin to use.
		this.editor = editor;
		this._initButton();
	},

	_save: function(){
		// summary:
		//		Function to trigger saving of the editor document
		// tags:
		//		private
		var content = this.editor.attr("value");
		this.save(content);
	},

	save: function(content){
		// summary:
		//		User over-ridable save function for the editor content.
		//		Please note that the service URL provided should do content
		//		filtering of the posted content to avoid XSS injection via
		//		the data from the editor.
		// tags:
		//		public

		// Set the default header to post as a body of text/html.
		var headers = {
			"Content-Type": "text/html"
		};
		if(this.url){
			var postArgs = {
				url: this.url,
				postData: content,
				headers: headers,
				handleAs: "text"
			};
			this.button.attr("disabled", true);
			var deferred = dojo.xhrPost(postArgs);
			deferred.addCallback(dojo.hitch(this, this.onSuccess));
			deferred.addErrback(dojo.hitch(this, this.onError));
		}else{
			console.log("No URL provided, no post-back of content: " + content);
		}
	},

	onSuccess: function(resp, ioargs){
		// summary:
		//		User over-ridable save success function for editor content.  
		//		Be sure to call this.inherited(arguments) if over-riding this method.
		// resp:
		//		The response from the server, if any, in text format.
		// tags:
		//		public
		this.button.attr("disabled", false);
		if(this.logResults){
			console.log(resp);
		}
	},

	onError: function(error, ioargs){
		// summary:
		//		User over-ridable save success function for editor content.  
		//		Be sure to call this.inherited(arguments) if over-riding this method.
		// resp:
		//		The response from the server, if any, in text format.
		// tags:
		//		public
		this.button.attr("disabled", false);
		if(this.logResults){
			console.log(error);
		}
	}
});

// Register this plugin.
dojo.subscribe(dijit._scopeName + ".Editor.getPlugin",null,function(o){
	if(o.plugin){ return; }
	var name = o.args.name.toLowerCase();
	if(name === "save"){
		o.plugin = new dojox.editor.plugins.Save({
			url: ("url" in o.args)?o.args.url:"",
			logResults: ("logResults" in o.args)?o.args.logResults:true
		});
	}
});

}