dojo.require('dojo.parser');
dojo.require('dojo.data.ItemFileWriteStore');
dojo.require('dojo.io.iframe');

dojo.require('dijit.form.Button');
dojo.require('dijit.form.CheckBox');
dojo.require('dojox.form.FileInput');
dojo.require('dijit.form.Form');
dojo.require('dijit.form.NumberSpinner');
dojo.require('dijit.form.TextBox');
dojo.require('dijit.layout.BorderContainer');
dojo.require('dijit.layout.ContentPane');
dojo.require('dijit.ProgressBar');

dojo.registerModulePath('thejekels','../thejekels');
dojo.require('thejekels.CheckBoxTree');

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
            url: "meters.store.php?program=" + programId + "&month=" + monthSelection + '&type=json'
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
