
console.info('Loaded: meterPrefsFix.js');

function processDefaultAdd(userId){
	var dataReturn_chart = dojo.byId('dataReturn_res');
	var dataReturn_table = dojo.byId('dataReturn_table');
    var messageDiv = dojo.byId('ajaxFeedbackDiv');  

	console.log(dojo.byId('addDefaultPoint'));
	var xhrArgs = {
		url: 'includes/setPrefs.inc.php?userID=' + userId + '&process=setDefault&action=add',
		handleAs: "text",
		form: dojo.byId('addDefaultPoint'),
		load: function(data) {
			dojo.byId(dataReturn_table).innerHTML = data; 			
		},
		error: function(error) {		
			dojo.byId(messageDiv).innerHTML = 'There was an error with your request.  Please try again.<br />If the problem persists contact <a href="http://help.crsolutions.us/" target="_blank">CRS Helpdesk</a>.';
		}
	}
	dojo.byId(dataReturn_table).innerHTML = 'Processing Your Request . . .'
	dojo.xhrPost(xhrArgs); 

};
