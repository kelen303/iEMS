window.addEvent('domready', function(){
    basicCal = new Calendar({ baseDate: 'm-d-Y'},{direction: -1,navigation: 1});
	advCalFrom = new Calendar({ advBaseDateFrom: 'm-d-Y'},{direction: -1,navigation: 1});
    advCalTo = new Calendar({ advBaseDateTo: 'm-d-Y'},{direction: -1,navigation: 1});
	
	advCSVCalFrom = new Calendar({ advCSVBaseDateFrom: 'm-d-Y'},{direction: -1,navigation: 1});
    advCSVCalTo = new Calendar({ advCSVBaseDateTo: 'm-d-Y'},{direction: -1,navigation: 1});

	myCalMultiple = new Calendar({ cmpSelect_id: 'm-d-Y' },{direction: -1,navigation: 1});

//	id="cmpSelect_id" name="cmpSelect[]"
	
	var container = $('cpContainer');
    var containerTimer = $('cpContainerTimer');
    containerTimer.style.visibility="hidden";
    container.style.visibility="visible";

   // var advPoints = $('advPointList');
	//var basicPoints = $('basicPointList');
	//var eventPoints = $('eventPointList');

//prefs and advanced slide controls are in includes/clsControlPanel.inc.php; they wouldn't work from this include -- kludge for now.

/***** pointSlide *****/
		
		var pointSlide = new Fx.Slide('cpPoint');
		
		pointSlide.hide(); 
		//basicPoints.setStyle('visibility','hidden');
		
		$('pointToggle').addEvent('click', function(e){
			e = new Event(e);
			pointSlide.toggle();
			//basicPoints.setStyle('visibility','visible');
			e.stop();
		});
		$('pointHide').addEvent('click', function(e){
			e = new Event(e);
			//basicPoints.setStyle('visibility','hidden');
			pointSlide.hide();

			e.stop();
		});
			//overflow: scroll;
/***** eventSlide *****/
		
		var eventSlide = new Fx.Slide('cpEvent');
		
		eventSlide.hide(); 
		//eventPoints.setStyle('visibility','hidden');

		$('eventToggle').addEvent('click', function(e){
			e = new Event(e);
			eventSlide.toggle();
			//eventPoints.setStyle('visibility','visible');
			e.stop();
		});
		$('eventHide').addEvent('click', function(e){
			e = new Event(e);
			//eventPoints.setStyle('visibility','hidden');
			eventSlide.hide();
			e.stop();
		});

/***** advPointSlide *****/
/*		
		var advPointSlide = new Fx.Slide('cpAdvPoint');
		
		advPointSlide.hide(); 
		//advPoints.setStyle('visibility','hidden');
		
		$('advPointToggle').addEvent('click', function(e){
			e = new Event(e);
			advPointSlide.toggle();
			//advPoints.setStyle('visibility','hidden');
			e.stop();
		});
		$('advPointHide').addEvent('click', function(e){
			e = new Event(e);
			//advPoints.setStyle('visibility','hidden');
			advPointSlide.hide();
			
			e.stop();
		});
*/
/***** advMultiSlide *****/
		/* need to work on this later --- close, but not there yet.
		var advMultiSlide = new Fx.Slide('cpAdvMulti');
		var parentSlide = $('multiToggler').getParent();
		parentSlide.setStyle('height',$('multiToggler').offsetHeight);
		
		$('advMultiToggle').addEvent('click', function(e){
			e = new Event(e);
			parentSlide.setStyle('height','auto');
			advMultiSlide.toggle();
			
			e.stop();
		});
		*/
		/*
		<script type="text/javascript" >
                var accordion = new Accordion(\'h4.atStart\', \'div.atStart\', {
                	opacity: false,
                	onActive: function(toggler, element){
                		toggler.setStyle(\'color\', \'#FF6701\');
                	},
                 
                	onBackground: function(toggler, element){
                		toggler.setStyle(\'color\', \'#FFFFFF\');
                        toggler.setStyle(\'cursor\', \'pointer\');
                	}
                }, $(\'accordion\'));
                 
            </script>
			*/
		/*
		new Fx.Slide('cpAdvMulti').addEvent('onBackground', function(toggler,element){
			element.setStyle('height',element.offsetHeight);
		});
		
		$$('.element').each(function(element,i){
			new Accordion('.nestedToggler','.nestedElement',{},element).addEvent('onActive', function(toggler,element){
				toggler.getParent().setStyle('height','auto');
			});
		});
		var containerTimer = $('cpContainerTimer');
    containerTimer.style.visibility="hidden";
	*/
	   
/***** statsSlide *****/
		
		var statsSlide = new Fx.Slide('cpStats');
		
		statsSlide.hide(); 
		
		$('statsToggle').addEvent('click', function(e){
			e = new Event(e);
			statsSlide.toggle();
			e.stop();
		});
		$('statsHide').addEvent('click', function(e){
			e = new Event(e);
			statsSlide.hide();
			e.stop();
		});
		
/***** alarmSlide *****/
		
		var alarmSlide = new Fx.Slide('cpAlarm');
		
		alarmSlide.hide(); 
		
		$('alarmToggle').addEvent('click', function(e){
			e = new Event(e);
			alarmSlide.toggle();
			e.stop();
		});
		$('alarmHide').addEvent('click', function(e){
			e = new Event(e);
			alarmSlide.hide();
			e.stop();
		});
		

/***** contactSlide *****/
		
		var contactSlide = new Fx.Slide('cpContact');
		
		contactSlide.hide(); 
		
		$('contactToggle').addEvent('click', function(e){
			e = new Event(e);
			contactSlide.toggle();
			e.stop();
		});
		$('contactHide').addEvent('click', function(e){
			e = new Event(e);
			contactSlide.hide();
			e.stop();
		});
		
/***** summarySlide *****/
		
		var summarySlide = new Fx.Slide('cpSummary');
		
		summarySlide.hide(); 
		
		$('summaryToggle').addEvent('click', function(e){
			e = new Event(e);
			summarySlide.toggle();
			e.stop();
		});
		$('summaryHide').addEvent('click', function(e){
			e = new Event(e);
			summarySlide.hide();
			e.stop();
		});
	

		
/***************** tips  *****************/

	var prefsTip = new Tips($('prefsTip'));
	$('prefsTip').store('tip:title', 'Set Preferences');  
	$('prefsTip').store('tip:text', 'Here user can set the default meter(s) up to 3 that will display upon login, also set viewable meter points allowing you to view only the meters you wish to see, and specify the zip code for viewing weather and update your password.');  
	
	var meterTip = new Tips($('pointToggle'));
	$('pointToggle').store('tip:title', 'Select New Meter');  
	$('pointToggle').store('tip:text', 'Here you can select other viewable meters (up to 6 on one chart) in addition to meter(s) pre-selected as default meter points. This list is set under the Set Preferences tab using the Set Viewable Points button.');  

	var eventTip = new Tips($('eventToggle'));
	$('eventToggle').store('tip:title', 'Event Performance');  
	$('eventToggle').store('tip:text', 'TO SEE RECENT DATA CLICK ON THE REFRESH BUTTON.');  
	
	var advPointTip = new Tips($('advPointTip'));
	$('advPointTip').store('tip:title', 'Advanced Charting');  
	$('advPointTip').store('tip:text', 'Here you can plot multiple days (up to 6) of the same meter point on one chart and view up to one month\'s worth of data for one meter point.');  
	
	var statsTip = new Tips($('statsToggle'));
	$('statsToggle').store('tip:title', 'Uptime Statistics');  
	$('statsToggle').store('tip:text', 'Available with the next release of iEMS. Meter statistics report will show key metrics on meter performance.');  
	
	var alarmTip = new Tips($('alarmToggle'));
	$('alarmToggle').store('tip:title', 'Alarm Management');  
	$('alarmToggle').store('tip:text', 'Available with the next release of iEMS. You can set price and demand threshold alarms based on your own defined threshold limits.');  
	
	var contactTip = new Tips($('contactToggle'));
	$('contactToggle').store('tip:title', 'Contact Management');  
	$('contactToggle').store('tip:text', 'You can dynamically manage and update your demand response event contacts (email and phone) for notification purposes.');  
	
	var summaryTip = new Tips($('summaryToggle'));
	$('summaryToggle').store('tip:title', 'Summary Reports');  
	$('summaryToggle').store('tip:text', 'Available with the next release of iEMS. You can define the type of report and time period desired from a group of most popular analysis reports and also view a report of your demand response event and alarm contacts.');  
		

	//NOTE: printTip is in tabularData.inc.php due to being an ajax/iFrame call.  it throws an error if it is here 'cause it doesn't exist
	//exportTip is also re-iterated there.
	
	//NOTE: All three of these get re-iterated in refresh.inc.php -- ajax/iFrame
	
	//NOTE: Tabular tip loads from clsInterface.inc.php
	  		
	
	//NOTE: starting to see a pattern -- during re-factor, let's consider putting these with the tips themselves. or something :p~
	/*

	var printChartTip = new Tips($('printChartTip'));
	$('printChartTip').store('tip:title', 'Full Sized Chart for Printing');  
	$('printChartTip').store('tip:text', 'Upon selection, chart will resize for optimal printing');  
		
	var exportTip = new Tips($('exportTip'));
	$('exportTip').store('tip:title', 'CSV Output');  
	$('exportTip').store('tip:text', 'Upon selection, data is transformed to a CSV file format for saving or opening immediately in MS-Excel.');  

	var magnifyTip = new Tips($('magnifyTip'));
	$('magnifyTip').store('tip:title', 'Full Sized Chart for Viewing');  
	$('magnifyTip').store('tip:text', 'Upon selection, chart will resize for optimal viewing.');  
*/
	});

/***** advanced form accordion *****/

	var accordion = new Accordion('h4.atStart', 'div.atStart', {
		opacity: false,
		onActive: function(toggler, element){
			toggler.setStyle('color', '#FF6701');
		},
	 
		onBackground: function(toggler, element){
			toggler.setStyle('color', '#FFFFFF');
			toggler.setStyle('cursor', 'pointer');
		}
	}, $('accordion'));

 function SetSingleSelectMode(ssmBox) {
	document.metermenu.metermenu.single_select_mode.value = ssmBox.checked;
	if (ssmBox.checked) document.metermenu.SetSingleSelectMode();
}
