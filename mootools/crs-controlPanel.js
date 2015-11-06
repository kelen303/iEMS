window.addEvent('domready', function(){
    basicCal = new Calendar({ baseDate: 'm-d-Y'},{direction: -1,navigation: 1});
	advCalFrom = new Calendar({ advBaseDateFrom: 'm-d-Y'},{direction: -1,navigation: 1});
    advCalTo = new Calendar({ advBaseDateTo: 'm-d-Y'},{direction: -1,navigation: 1});

	advCSVCalFrom = new Calendar({ advCSVBaseDateFrom: 'm-d-Y'},{direction: -1,navigation: 1});
    advCSVCalTo = new Calendar({ advCSVBaseDateTo: 'm-d-Y'},{direction: -1,navigation: 1});

	uptCalFrom = new Calendar({ uptBaseDateFrom: 'm-d-Y'},{direction: -1,navigation: 1});
    uptCalTo = new Calendar({ uptBaseDateTo: 'm-d-Y'},{direction: -1,navigation: 1});

	repCalFrom = new Calendar({ repBaseDateFrom: 'm-d-Y'},{direction: -1,navigation: 1});
    repCalTo = new Calendar({ repBaseDateTo: 'm-d-Y'},{direction: -1,navigation: 1});

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
			toggleArrowVisibility('lastUsedBasic');
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
			toggleArrowVisibility('lastUsedEvent');
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
/***** advMultiSlide *****/
/***** statsSlide *****/

		var statsSlide = new Fx.Slide('cpStats');

		statsSlide.hide();

		$('statsToggle').addEvent('click', function(e){
			e = new Event(e);
			statsSlide.toggle();
			toggleArrowVisibility('lastUsedUptime');
			e.stop();
		});
		$('statsHide').addEvent('click', function(e){
			e = new Event(e);
			statsSlide.hide();
			e.stop();
		});

/***** alarmSlide *****/

/***** contactSlide *****/

		var contactSlide = new Fx.Slide('cpContact');

		contactSlide.hide();

		$('contactToggle').addEvent('click', function(e){
			e = new Event(e);
			contactSlide.toggle();
			toggleArrowVisibility('lastUsedContact');
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
			toggleArrowVisibility('lastUsedReports');
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
	$('eventToggle').store('tip:text', 'Current day event information now automatically refreshes.  Complete event summaries are now available.');

	var advPointTip = new Tips($('advPointTip'));
	$('advPointTip').store('tip:title', 'Advanced Charting');
	$('advPointTip').store('tip:text', 'Here you can plot multiple days (up to 6) of the same meter point on one chart and view up to one month\'s worth of data for one meter point.');

	var statsTip = new Tips($('statsToggle'));
	$('statsToggle').store('tip:title', 'Uptime Statistics');
	$('statsToggle').store('tip:text', 'Meter statistics report will show key metrics on meter performance.');

	var contactTip = new Tips($('contactToggle'));
	$('contactToggle').store('tip:title', 'Contact Management');
	$('contactToggle').store('tip:text', 'You can dynamically manage and update your demand response event contacts (email and phone) for notification purposes.  Complete contact summaries are now available.');

	var summaryTip = new Tips($('summaryToggle'));
	$('summaryToggle').store('tip:title', 'Summary Reports');
	$('summaryToggle').store('tip:text', 'You can define the type of report and time period desired from a group of most popular analysis reports.');

	var weatherReq = new Request.HTML({
        method: "get",
        url: "/includes/weather.inc.php?weatherZip=30076",
        onComplete: function(respTree,respElements, respHTML) {
				$('weatherReturn_res').innerHTML = respHTML;
        }

    }).send();

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


function toggleArrowVisibility(clickedId)
{

	dojo.query('.cpFormSelectedArrow').forEach(function(node,index,arr){
		if(node.id == clickedId) {
			//node.setStyle('display','inline');
			dojo.style(node, 'display', 'inline');
		}
		else
		{
			//node.setStyle('display','none');
			dojo.style(node, 'display', 'none');
		}
	});
}
