<?php
/**
 * controlPanel
 *
 * @package IEMS 2.0
 * @name Control Panel
 * @author Marian C. Buford, Rearview Enterprises, Inc.
 * @copyright Copyright Conservation Resource Solutions, Inc. 2008.
 * @version 2.0
 * @access public
 *
 * @abstract As its name implies, this class manages all functionality involved with the control panel, including a good deal of the JavaScript. This class also includes the ajax-called preference forms.
 *
 */

/*  Refactoring note: Need to revisit  processBasicCSVExport javascript et al -- get vars are being pushed through as a query, no need to parse them individually, really.
*/
//Ensuring that the page cannot be called directly. Definition is set in the calling pages.
if(!defined('APPLICATION'))
{
  header('HTTP/1.0 404 not found');
  exit;
}

class controlPanel
{
  /**
   * controlPanel::panel()
   *
   * @param mixed $baseDateArray
   * @param mixed $dateSpan
   * @param mixed $username
   * @param mixed $userID
   * @param mixed $domainID
   * @param mixed $connection
   * @param string $selectedPoints
   * @param string $selectedView
   * @param string $selectedPresentation
   * @param mixed $comparisonArray
   * @param mixed $selectedRange
   * @param mixed $lastAction
   * @param mixed $mvcSelection
   * @return
   *
   * @abstract This is the core function for building the control panel.
   *
   * {@source}
   */
	function panel($baseDateArray,$dateSpan,$mdrUser,$connection,$master_connection,$selectedPoints = '',$selectedContactProfiles,$selectedView = 'charts',$selectedPresentation = 'individual',$selectedReport,$comparisonArray,$selectedRange,$repSelectedRange,$lastAction,$mvcSelection)
	{
        //$mdrUser->preDebugger($_SESSION);
		//not sure why I decided to split creation of some of the form elements between here, and their respective forms below --- really really need to refactor this; it is schizophrenic

        //echo "clsControlPanel->panel: connection='" . $connection . "', master_connection='" . $master_connection . "'<br>\n";

		$cpString = '';
		$viewOptions = '';
		$presentationOptions = '';
		$rangeOptions = '';
        $repRangeOptions = '';
        $reportOptions = '';
		$advPointString = '';
		$src = '';

		$aryDateRange = array(
			'thisWeek'=>'This Week',
			'lastWeek'=>'Last Week',
			'thisMonth'=>'This Month',
			'lastMonth'=>'Last Month'
			);

		foreach($aryDateRange as $inx=>$value)
		{
			if($inx == $selectedRange)
			{
				$rangeFlag = ' SELECTED';
			}
			else
			{
				$rangeFlag = '';
			}
			$rangeOptions .= '<option value="'.$inx.'"'.$rangeFlag.'>'.$value.'</option>';
		}

		$rangeOptions = '<option value="">-- none --</option>'.$rangeOptions;

		$aryView = array(
				'tabularData'=>'Tabular Data',
				'tabularPrices'=>'Tabular Prices',
			);


		foreach($aryView as $inx=>$value)
		{
			$checkedView = '';
			if($selectedView != '')
			{
				if($inx == $selectedView)
				{
					$checkedView = ' checked';
				}
			}
			if($inx == 'charts')
			{
				$visibilityFlag = 'visible';
			}
			else
			{
				$visibilityFlag = 'hidden';
			}

			$script = '';
			$viewOptions .= '<li><input type="radio" name="view" value="'.$inx.'" '.$script.' '.$checkedView.' />'.$value.'</li>';
		}
		$viewOptions = '
			<div name="viewOptions">'.$viewOptions.'</div>
		';
		$aryPresentation = array(
				'individual'=>'Individual Charts',
				'allInOne'=>'All-in-One Chart',
				'aggregate'=>'Aggregate Points'
			);

		 foreach($aryPresentation as $inx=>$value)
		{
			$checkedPresentation = '';
			$disabledFlag = '';
			if($selectedPresentation != '')
			{
				if($selectedPresentation == 'comparison')//another opportunity for refactoring. categorically, comparison falls into presentation, but it is not part of the presentation checkboxes, so this becomes confusing/misleading when dealing with the code.
				{
					if($inx != 'allInOne')
					{
						$disabledFlag = 'disabled';
					}
					else
					{
						$checkedPresentation = ' checked';
					}

				}
				elseif($inx == $selectedPresentation)
				{
					$checkedPresentation = ' checked';
				}
			}
			elseif($inx == 'allInOne')
			{
				$checkedPresentation = ' checked';
			}

			$presentationOptions .= '<li><label><input type="radio" id="presentation" name="presentation" value="'.$inx.'"'.$checkedPresentation.' '.$disabledFlag.'/>'.$value.'</label></li>';
		}

		$repDateRange = array(
			'thisWeek'=>'This Week',
			'lastWeek'=>'Last Week',
			'thisMonth'=>'This Month',
			'lastMonth'=>'Last Month',
            'thisYear'=>'This Year',
            'lastYear'=>'Last Year'
			);


		foreach($repDateRange as $inx=>$value)
		{
			if($inx == $repSelectedRange)
			{
				$rangeFlag = ' SELECTED';
			}
			else
			{
				$rangeFlag = '';
			}
			$repRangeOptions .= '<option value="'.$inx.'"'.$rangeFlag.'>'.$value.'</option>';
		}

		$repRangeOptions = '<option value="">-- none --</option>'.$repRangeOptions;

        $reports = array('aveHourlyProfile'=>'Ave. Hourly Profile',
                         'aveHourVsPeakHour'=>'Ave. Hour vs. Peak Hour',
                         'topTenPeaks'=>'Top Ten Peaks',
                         'dailyUsageProfile'=>'Daily Usage Profile',
                         'weeklyUsageProfile'=>'Weekly Usage Profile',
                         'monthlyUsageProfile'=>'Monthly Usage Profile');

        foreach ($reports as $inx=>$value)
        {
            //$isChecked = ($inx == $selectedReport);
			if($inx == $selectedReport)
			{
				$isChecked = ' checked';
			}
			else
			{
				$isChecked = '';
			}

            $reportOptions .= '<li><label><input type="radio" id="report" name="report" value="' . $inx . '" ' . $isChecked . '/>' . $value . '</label></li>';
        }

		if($selectedPoints == '')
		{
			$sp = $this->gatherDefaultPoints($master_connection, $mdrUser);
			$selectedPoints['basic'] = $sp;
			$selectedPoints['advanced'] = $sp;
			$selectedPoints['event'] = $sp;
            $selectedPoints['reports'] = $sp;
		}

		if(!empty($selectedPoints) && $selectedPoints['advanced'] != '')
		{
			$ids = '';
			foreach($selectedPoints['advanced'] as $id=>$status)
			{
				$ids .= $id.',';
			}

			$advPointString = rtrim($ids,',');
		}

		$visiblePoints = $this->gatherVisiblePoints($master_connection,$mdrUser,true);
        $dayAheadPoints = $this->gatherVisibleDayAheadPoints($mdrUser,true);

		$meterForm = $this->meterForm($mdrUser->userName(),$visiblePoints,$selectedPoints['basic'],$baseDateArray['basic']);
        $dayAheadForm = '';
        $dayAheadForm = $this->dayAheadForm($dayAheadPoints,$selectedPoints['dayAhead']);

		$eventDateSelect = '';

		if($selectedPoints['event'] != '')
		{
			$eventDateSelect = $this->eventDateSelectBuilder($selectedPoints['event'],$mdrUser,$src);
		}

		$multiVersusCompare = '';


		$aryMVC[0]['value'] = 'multi';
		$aryMVC[0]['caption'] = 'Multi-Day Chart';
		//$aryMVC[0]['script'] = 'onClick="mvcToggle0()"';


		$aryMVC[1]['value'] = 'compare';
		$aryMVC[1]['caption'] = 'Comparison Chart';
		//$aryMVC[1]['script'] = 'onClick="mvcToggle1()"';

		$aryMVC[2]['value'] = 'export';
		$aryMVC[2]['caption'] = 'Long-Term CSV Export';
		//$aryMVC[2]['script'] = 'onClick="mvcToggle2()"';



		foreach($aryMVC as $inx=>$fieldSettings)
		{
			$mvcChecked = '';

			if($fieldSettings['value'] == $mvcSelection)
			{
				$aryMVC[$inx]['status'] = 'checked';
			}
			else
			{
				$aryMVC[$inx]['status'] = '';
			}
		}

		$eventsForm = $this->eventsForm($mdrUser->userName(),$visiblePoints,$selectedPoints['event'],$eventDateSelect,$baseDateArray['event'], $mdrUser);
		//now that we feed so many dates into advMeterForm, we should consider using an array
		$advMeterForm = $this->advMeterForm($baseDateArray['advancedFrom'],$baseDateArray['advancedTo'],$baseDateArray['advancedCSVFrom'],$baseDateArray['advancedCSVTo'],$rangeOptions,$visiblePoints,$selectedPoints['advanced'],$viewOptions,$presentationOptions,$mdrUser->userName(),$mdrUser->id(),$connection,$comparisonArray,$aryMVC);

        $uptimeForm = $this->uptimeStatisticsForm($baseDateArray['uptimeFrom'],$baseDateArray['uptimeTo'],$repRangeOptions,$visiblePoints,$selectedPoints['time'],$mdrUser->userName(),$mdrUser->id(),$connection,$comparisonArray,$aryMVC);

        $contactManager = new ContactManager($mdrUser->Domains(0)->id(), $mdrUser->id());
        $contactProfiles = $contactManager->GetUniqueProfiles();
        $contactUses = $contactManager->GetContactUses();
        //$selectedContactProfiles = '';

        $assetForm = '';
        //$assetForm = $mdrUser->isLseUser() ? $this->assetForm($mdrUser) : '';
        

        //$mdrUser->preDebugger($contactProfiles);
		$contactProfilesForm = $this->contactProfilesForm($mdrUser->Domains(0)->id(), $mdrUser->id(), $contactUses, $contactProfiles, $selectedContactProfiles,$mdrUser);

        $summaryReportsForm = $this->summaryReportsForm($baseDateArray['reportFrom'],$baseDateArray['reportTo'],$repRangeOptions,$visiblePoints,$selectedPoints['reports'],$reportOptions,$mdrUser->userName(),$mdrUser->id(),$connection,$comparisonArray,$aryMVC);

		return '
			<!-- begin control panel -->'.
			$this->assemble($meterForm, $advMeterForm, $uptimeForm, $eventsForm, $assetForm, $contactProfilesForm, $summaryReportsForm, $mdrUser->id(),$mdrUser->userName(),$lastAction,$advPointString,$mdrUser,$dayAheadForm)
			.'<!-- end control panel -->
		';
	}

    function assetForm($mdrUser)
    {
        $result = null;

        $programList = $mdrUser->PointChannels()->participationTypeList();

        //$mdrUser->preDebugger($programList);

        if($programList)
        {
            // MCB Beware -- this still needs some work -- need to trap no resources in the ajax list
            // and something is odd about the dates -- not sure

            /*  Holding this until we have back-end logic =================================================== */

            $result = '<div id="assetForm_box">';

            //$dateToCompare = strtotime('2010-12-26');
            $dateToCompare = time();

            //print date('Y-m-d',mktime(0, 0, 0, date('n')+1, -6, date('Y'))).'<br />';
            //print date('Y-m-d',$dateToCompare).'<br />';

            //if(mktime(0, 0, 0, date('n')+1, -6, date('Y')) <= $dateToCompare)
            if(true)
            {
                $result .= '<form id="assetSelectionForm">';
                $result .= '<div style="margin-bottom: 5px;">Month: ';

                $nextMonth = date('F',mktime(0, 0, 0, date('n',$dateToCompare)+1, 1, date('Y',$dateToCompare))); //first day of next month (tests indicate year increments correctly)
                $nextMonthNumeric = date('n',mktime(0, 0, 0, date('n',$dateToCompare)+1, 1, date('Y',$dateToCompare))); //first day of next month (tests indicate year increments correctly)
                $nextYear = date('Y',mktime(0, 0, 0, date('n',$dateToCompare)+1, 1, date('Y',$dateToCompare))); //first day of next month (tests indicate year increments correctly)
                
                $result .= '<select id="assetModelDateSelection">';
                $result .= '<option value="'.date('n',$dateToCompare).':'.date('Y',$dateToCompare).'">'.date('F',$dateToCompare).'</option>';
                $result .= '<option value="'.$nextMonthNumeric.':'.$nextYear.'">'.$nextMonth.'</option>';
                $result .= '</select>';

                $result .= '<div id="assetPointList">
							<h4>Select Program:</h4>';

                foreach($programList as $id=>$description)
                {

                    $result .= '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border: none;">';
                    $result .= '<tr>';
                    $result .= '<td>';
                    $result .= '<input
                                name="program"
                                value="'.$id.'"
                                type="radio"
                                onClick="javascript:callModellingForm('.$id.',\''.$description.'\');"
                                />';
                    $result .= '</td>';
                    $result .= '<td style="font-size: 12px; color: #000;">';
                    $result .= $description;
                    $result .= '</td></tr>';
                    $result .= '</table>';
                }
            }
            else
            {
                $result .= 'This service will be available again on <strong>NEED TARGET DATE FORMULA</strong>';
            }

            
            

            $result .= '</form>';
            $result .= '</div>';

			$result .= '<div style="text-align: center; margin: 10px;"> -- or -- </div>';

			$result .= '<div id="assetUploadForm_box">';

            $result .= '<div style="cursor: pointer;"
                onClick="javascript:callUploadForm();"
                ><h4>Upload CSV File</h4></div>';

			$result .= '</div>';
           /* =================================================== */


        }
        else
        {
            $result = 'There was an error with the request.';
        }

            $lastUsedAsset = '&nbsp;<img src="_template/images/left.gif" class="cpFormSelectedArrow" id="lastUsedAsset" border="0" style="display: none;"/>';
            $result = '<tr>
            		<td>
            		<table align="center" cellpadding="0" cellspacing="0" border="0">
            			<tr>
            				<td align="center">
            					<h2 id="cpSlice06"><a id="alarmToggle" class="alarmTip" href="#"><div class="h2Bump">Asset Management'.$lastUsedAsset.'</div></a></h2>
            					<div id="cpAlarm">
            						<table width="202" cellpadding="0" cellspacing="0" border="0">
            							<tr>
            								<td class="shadowTop"></td>
            							</tr>
            							<tr>
            								<td class="cpContainerCell" style="padding-left: 10px;">

												'.$result.'
												<div id="alarmHide"><a href="#"><img src="_template/images/blank.gif" width="15" height="14" border="0" /></a></div>

											</td>
            							</tr>
            							<tr>
            								<td class="shadowBottom"></td>
            							</tr>
            						</table>
            					</div>
            				</td>
            			</tr>
            		</table>
                        <script>
                            var alarmSlide = new Fx.Slide("cpAlarm");

                    		alarmSlide.hide();

                    		$("alarmToggle").addEvent("click", function(e){
                    			e = new Event(e);
                    			alarmSlide.toggle();
                    			toggleArrowVisibility("lastUsedAsset");
                    			e.stop();
                    		});
                    		$("alarmHide").addEvent("click", function(e){
                    			e = new Event(e);
                    			alarmSlide.hide();
                    			e.stop();
                    		});

                            
                        </script>
            		</td>
            	</tr>';

        return $result;

    }


	function assetReportsForm()
	{
		$result = '
				<script>
                /*  ===============================================================================
                    FUNCTION: getAssetToResourceReport()
                    =============================================================================== */
                    function getAssetToResourceReport()
                    {
                        var targetDiv = dojo.byId("dataReturn_table");
						var previousDiv = dojo.byId("dataReturn_res");

                        // Gather up everything that our xhr call needs.
                        var xhrArgs = {
                            url: "assetReports.ajax.php",
                            handleAs: "text",
                            form: "assetReportsRequestForm",
                            load: function(data) {
                                targetDiv.innerHTML = data;
								dojo.style(targetDiv,"display","block");
								dojo.style(previousDiv,"display","none");
                                //console.log(data); //for troubleshooting
                            },
                            error: function(error) {
                                // this handles any errors with the ajax xhr call
                                //console.log(error); //for troubleshooting
                                targetDiv.innerHTML = "There was an error with your request.  Please try again.<br />If the problem persists contact <a href=\"http://help.crsolutions.us/\" target=\"_blank\">CRS Helpdesk</a>.";
                            }
                        }
                        // let it fly . . .
                        targetDiv.innerHTML = "Processing Your Request . . ." // Communicate the the user what\'s going on.
                        dojo.xhrPost(xhrArgs);
                    } // end getAssetToResourceReport()
                </script>
				<hr />
                <div style="text-align: left; margin-top: 10px; margin-left: 10px; color: #000;">
                    <h4 style="text-align: left; margin: 0px; padding: 0px;">
                        Resource Contacts CSV
                    </h4>
                        <input
                            type="radio"
                            name="assetReportFormatCSV"
                            value="flat"
                            onClick="window.location.href = \'assetReports.csv.php?format=flat&contacts=true\';"
                            />&nbsp;Flat&nbsp;&nbsp;
                        <input
                            type="radio"
                            name="assetReportFormatCSV"
                            value="hierarchical"
                            onClick="window.location.href = \'assetReports.csv.php?format=hierarchical&contacts=true\';"
                            />&nbsp;Tree<br />


                    <h4 style="text-align: left; margin: 0px; padding: 0px; margin-top: 10px;">
                        Assets by Resource Report
                    </h4>
                    <form id="assetReportsRequestForm" method="POST" style="margin: 0px;">
                            <input
                                type="radio"
                                name="assetReportFormat"
                                value="flat"
                                onClick = "getAssetToResourceReport();"
                                />&nbsp;Flat&nbsp;&nbsp;
                            <input
                                type="radio"
                                name="assetReportFormat"
                                value="hierarchical"
                                onClick = "getAssetToResourceReport();"
                                />&nbsp;Tree<br />
                    </form>
                </div>
                ';
		return $result;
	}
  /**
   * controlPanel::eventDateSelectBuilder()
   *
   * @param mixed $selectedPoints
   * @param mixed $connection
   * @param mixed $src
   * @return
   */
	function eventDateSelectBuilder($selectedPoints,$mdrUser,$src)
	{
		$eventDateSelect = '';
        $doRefreshEventDates = false;

		if($src == 'js')
		{
            $doRefreshEventDates = true;
			$updatedString = '[updated]';
		}
		else
		{
			$updatedString = '&nbsp;';
		}

		foreach($selectedPoints as $id=>$point)
		{
			$eventDateOptions = '';
			$idArray = explode(':',$id);

            $eventDates = $mdrUser->pointChannels()->meterPoint($idArray[0])->eventDates();

            if (!count($eventDates) || $doRefreshEventDates) $mdrUser->pointChannels()->meterPoint($idArray[0])->refreshEventDates();
            $eventDates = $mdrUser->pointChannels()->meterPoint($idArray[0])->eventDates();
            foreach($eventDates as $dateId) {
                //$mdrUser->preDebugger($dateId);
                $eventDateOptions .= '<option>' . $dateId['startDate'] . '</option>';
            }

			if($eventDateOptions != '')
			{
				$charCount = strlen($point);
				if($charCount > 18)
				{
					$truncString = substr($point,18);
					$pLabel = rtrim($point,$truncString).'...';
				}
				else
				{
					$pLabel = $point;
				}

				$eventDateSelect .= '<optgroup label="'.$pLabel.'">'.$eventDateOptions.'</optgroup>';
			}

		}

		if($eventDateSelect != '')
		{

			$eventDateSelect = '<div style="width: 100%; borde: 1px solid; padding: 0px;">Event Date:<br /><select id="evtBaseDate" name="evtBaseDate" class="eventDateSelect">'.$eventDateSelect.'</select></div>';
		}

		else
		{
			$eventDateSelect = '<div style="padding-top: 3px; padding-bottom: 4px;">The Selected Meter(s) Have No Event Dates</div>';
		}

		$eventDateSelect = '
			<style>
			form#eventsForm optgroup {background:#fff; color:#1B6097; font-family: Geneva, Arial, Helvetica, sans-serif; font-style: normal; font-weight: bold; font-size: 13px;}
			form#eventsForm optgroup option {background:#fff; color:#000000;}
			</style>
			'.$eventDateSelect.'<div id="eventDateUpdated" style="color: #FF6701; font-size: 11px;">'.$updatedString.'</div>';

		return $eventDateSelect;

	}
  /**
   * controlPanel::dateSpanCalculator()
   *
   * @param mixed $fromDate
   * @param mixed $toDate
   * @param mixed $rangeCategory
   * @return
   */
    function dateSpanCalculator($fromDate,$toDate,$rangeCategory)
	{
		$uxDay = 60 * 60 * 24;
		if($rangeCategory != '')
		{
			$dateToParts = explode('-',$toDate);
			$toMonth = $dateToParts[0];
			$toDay = $dateToParts[1];
			$toYear = $dateToParts[2];
			$toDateOutput = strtotime($toYear.'-'.$toMonth.'-'.$toDay,0);
            switch($rangeCategory)
			{
			case 'thisWeek':
				$dayOfWeek = date('N',$toDateOutput);
				$baseDate = date('Y-m-d',$toDateOutput - (($dayOfWeek-1) * $uxDay));
				$dateSpan = 7;
				break;
			case 'lastWeek':
				$dayOfWeek = date('N',$toDateOutput);
				$thisMonday = $toDateOutput - (($dayOfWeek-1) * $uxDay);
				$lastMonday = $thisMonday - ($uxDay * 7);
				$baseDate = date('Y-m-d',$lastMonday);
				$dateSpan = 7;
				break;
			case 'thisMonth':
				$baseDate = $toYear.'-'.$toMonth.'-01';
				$dateSpan = date('t',$toDateOutput);
				break;
			case 'lastMonth':
				$thisMonth = date('m',$toDateOutput);
				$lastMonth = $thisMonth-1;
				$baseDateOutput = strtotime($toYear.'-'.$lastMonth.'-'.'01',0);
				$baseDate = date('Y-m-d',$baseDateOutput);
				$dateSpan = date('t',$baseDateOutput);
				break;
			case 'thisYear':
				$baseDate = $toYear.'-01-01';
				$dateSpan = date('z',$toDateOutput) + 1;
				break;
			case 'lastYear':
				$thisYear = date('y',$toDateOutput);
				$lastYear = $thisYear-1;
				$baseDateOutput = strtotime($lastYear.'-01-01',0);
				$baseDate = date('Y-m-d',$baseDateOutput);
				$dateSpan = date('z',strtotime($lastYear.'12-31',0)) + 1;
				break;
			}

		}
		else
		{
			$dateFromParts = explode('-',$fromDate);
			$fromMonth = $dateFromParts[0];
			$fromDay = $dateFromParts[1];
			$fromYear = $dateFromParts[2];
			$fromDateOutput = strtotime($fromYear.'-'.$fromMonth.'-'.$fromDay,0);

			$dateToParts = explode('-',$toDate);
			$toMonth = $dateToParts[0];
			$toDay = $dateToParts[1];
			$toYear = $dateToParts[2];
			$toDateOutput = strtotime($toYear.'-'.$toMonth.'-'.$toDay,0);

			$difference = $toDateOutput - $fromDateOutput; // Difference in seconds

			$dateSpan = round($difference / $uxDay) +1; //for this app, we add a day as our span is inclusive
			$baseDate = date('Y-m-d',$fromDateOutput);
		}

		return array('dateSpan'=>$dateSpan,'baseDate'=>$baseDate);
	}

  /**
   * controlPanel::gatherHiddenPoints()
   *
   * @param mixed $connection
   * @param mixed $userID
   * @return
   */
	function gatherHiddenPoints($master_connection, $mdrUser)
	{
        $mdrUser->refreshPrefs();

		$hiddenPointList = '';

		$hiddenCount = 0;

        for ($inx=0; $inx<$mdrUser->pointChannels()->length(); $inx++) {
            $preference = "HiddenPointChannel." . $mdrUser->pointChannels()->item($inx)->channelName();
            if ($mdrUser->HasPreference($preference)) {
                $key = $mdrUser->pointChannels()->item($inx)->objectId().':'.$mdrUser->pointChannels()->item($inx)->channelId();
                $hiddenPointList[$key] = $mdrUser->pointChannels()->item($inx)->channelDescription();
                $hiddenCount++;
            }
        }

        return $hiddenPointList;
	}

  /**
   * controlPanel::gatherVisiblePoints()
   *
   * @param mixed $connection
   * @param mixed $userID
   * @param bool $includeDefault
   * @return
   */
	function gatherVisiblePoints($master_connection, $mdrUser, $includeDefault = true)
	{
        $mdrUser->refreshPrefs();

		$visiblePointList = '';

		$visibleCount = 0;

        $unixDay = 86400;
        $lastDayOfMonthPlus101 = date('Y-m-d 00:00:00',(time()+ (((date('t') - date('j')) * $unixDay) + ($unixDay * 101))));

        for ($inx=0; $inx<$mdrUser->pointChannels()->length(); $inx++) {
            if($mdrUser->pointChannels()->item($inx)->retirementDate() == null || 
               !$mdrUser->HasPreference('HideRetiredPointchannels') ||
                ($mdrUser->HasPreference('HideRetiredPointchannels') && ($mdrUser->pointChannels()->item($inx)->retirementDate() != null && $mdrUser->pointChannels()->item($inx)->retirementDate() > $lastDayOfMonthPlus101))
               )
            { 
                $preference = "HiddenPointChannel." . $mdrUser->pointChannels()->item($inx)->channelName();
                if (!$mdrUser->HasPreference($preference)) {
                    if ($includeDefault !== true) {
                        $preference = "DefaultPointChannel." . $mdrUser->pointChannels()->item($inx)->channelName();
                        if ($mdrUser->HasPreference($preference)) continue;
                    }
    
                    $key = $mdrUser->pointChannels()->item($inx)->objectId().':'.$mdrUser->pointChannels()->item($inx)->channelId();
                    $visiblePointList[$key] = $mdrUser->pointChannels()->item($inx)->channelDescription();
                    $visibleCount++;
                }
            }
        }
        //$mdrUser->preDebugger($visibleCount);
        //$mdrUser->preDebugger($visiblePointList);
        return $visiblePointList;
	}
/*  ===============================================================================
    FUNCTION : gatherVisibleDayAheadPoints()
    =============================================================================== */
    function gatherVisibleDayAheadPoints($User)
	{
        $User->refreshPrefs();

		$visiblePointList = '';

		$visibleCount = 0;

        for ($inx=0; $inx<  $User->pointChannels()->length(); $inx++) 
        {
            $preference = "HiddenPointChannel." . $User->pointChannels()->item($inx)->channelName();
            
            if (!$User->HasPreference($preference) && $User->pointChannels()->item($inx)->participationTypeId() == 1) 
            {
                $key = $User->pointChannels()->item($inx)->objectId().':'.$User->pointChannels()->item($inx)->channelId();
                $visibleDayAheadPointList[$key] = $User->pointChannels()->item($inx)->channelDescription();
                $visibleCount++;
            }
        }

        return $visibleDayAheadPointList;
	} // gatherVisibleDayAheadPoints()

  /**
   * controlPanel::gatherDefaultPoints()
   *
   * @param mixed $connection
   * @param mixed $userID
   * @return
   */
	function gatherDefaultPoints($master_connection, $mdrUser)
	{
        $mdrUser->refreshPrefs();
        $defaultPointList = '';

		$defaultCount = 0;
        //print '<pre>';
        for ($inx=0; $inx<$mdrUser->pointChannels()->length(); $inx++) {

            $preference = "DefaultPointChannel." . $mdrUser->pointChannels()->item($inx)->channelName();
            //print $preference."\n";
            if ($mdrUser->HasPreference($preference)) {
                $key = $mdrUser->pointChannels()->item($inx)->objectId().':'.$mdrUser->pointChannels()->item($inx)->channelId();
                $defaultPointList[$key] = $mdrUser->pointChannels()->item($inx)->channelDescription();
                $defaultCount++;
            }
        }
        //print '</pre>';
        return $defaultPointList;
	}

  /**
   * controlPanel::gatherDefaultPresentation()
   *
   * @param mixed $connection
   * @param mixed $userID
   * @return
   */
	function gatherDefaultPresentation($connection, $mdrUser)
	{
        $prefTypeSQL = '
			SELECT
				PreferenceTypeID,
				PreferenceTypeName
			FROM
				t_preferencetypes
		';
        
		$prefTypeResult = mysql_query($prefTypeSQL,$connection);

		while($prefTypeRow = mysql_fetch_assoc($prefTypeResult))
		{
            //$mdrUser->preDebugger($prefTypeRow,'pink');
			$prefType[$prefTypeRow['PreferenceTypeName']] = $prefTypeRow['PreferenceTypeID'];
			$preferenceIDSQL = '
				SELECT
					UserPreferenceID
				FROM
					t_userpreferences up
				WHERE
					up.UserObjectID = '.$mdrUser->id().' and
					up.PreferenceTypeID = '.$prefTypeRow['PreferenceTypeID']
			;
		    //echo $preferenceIDSQL . "<br>\n";

			$preferenceIDResult = mysql_query($preferenceIDSQL,$connection);
			$preferenceIDRow = mysql_fetch_assoc($preferenceIDResult);

			 $userPrefID[$prefTypeRow['PreferenceTypeName']] = $preferenceIDRow['UserPreferenceID'];
		}

        //$mdrUser->preDebugger($userPrefID);
		return array('system'=>$prefType,'user'=>$userPrefID);
	}

  /**
   * controlPanel::gatherZipCode()
   *
   * @param mixed $connection
   * @param mixed $userID
   * @return
   */
function gatherZipCode($master_connection, $userID)
{
    $sql = '
		select
			zc.ContactValue PrimaryZipCode
		from
			t_contacts zc,
			t_contacttypes zct
		where
			zc.ObjectID = "'.$userID.'" and
			zc.ContactTypeID = zct.ContactTypeID and
			zct.ContactTypeName = "c_address"
	';

	$result = mysql_query($sql, $master_connection);

		if(mysql_numrows($result) == 0)
		{
			$zipString = '';
		}
		else
		{
			$row = mysql_fetch_array($result, MYSQL_ASSOC);
			$zipString = $row['PrimaryZipCode'];
		}

		return $zipString;
}

  /**
   * controlPanel::hidePointsForm()
   *
   * @param mixed $connection
   * @param mixed $userID
   * @return
   */
function hidePointsForm($master_connection, $mdrUser)
{
	$visibleOptions = '';
	$hiddenOptions = '';

	$visiblePoints = $this->gatherVisiblePoints($master_connection, $mdrUser,false);
	$hiddenPoints = $this->gatherHiddenPoints($master_connection, $mdrUser);

	if($hiddenPoints == '')
	{
		$hiddenList = '<div class="error" style="width: 232px;">You are currently displaying all points.</div>';
		$showLink = '';
	}
	else
	{
		$hiddenCount = count($hiddenPoints);

		foreach($hiddenPoints as $inx=>$value)
		{
			$hiddenOptions .= '<li class="alt"><label><input name="hidden['.$inx.']" type="checkbox" />'.$value.'</label></li>';
		}

		if($hiddenCount >= 6){$style = 'style="height: 10em;"';}else{$style = '';}

		$hiddenList = '<ul class="checklist pointChecklist" '.$style.'>'.$hiddenOptions.'</ul>';
		$showLink = '<a id="showLink" href="#" onClick="processShow();"><p><<< Show</p></a>';
	}

	if($visiblePoints == '')
	{
		$visibleList = '<div class="error"  style="width: 232px;">You currently have no visible points selected. </div>';
		$hideLink = '';
	}
	else
	{
		$visibleCount = count($visiblePoints);

		foreach($visiblePoints as $inx=>$value)
		{
			$visibleOptions .= '<li class="alt"><label><input name="visible['.$inx.']" type="checkbox" />'.$value.'</label></li>';
		}

		if($visibleCount >= 6){$style = 'style="height: 10em;"';}else{$style = '';}

		$visibleList = '<ul class="checklist pointChecklist" '.$style.'>'.$visibleOptions.'</ul>';
		$hideLink = '<a id="hideLink" href="#" onClick="processHide();"><p>Hide >>> </p></a>';
	}

	return '
		<table align="center" width="700" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>
					<div id="hidePointsForm">
					<table align="center" cellpadding="5" cellspacing="0" border="0">
						<tr>
							<td width="250">
								<form id="hidePoint" action="includes/setPrefs.inc.php" method="post">
									<h2>Visible Points</h2>
									<div>'.$visibleList.'</div>
									<input type="hidden" name="userID" value="'.$mdrUser->id().'" />
								</form>
							</td>
							<td width="250">
								<form id="showPoint" action="includes/setPrefs.inc.php" method="post">
									<h2>Hidden Points</h2>
									<div>'.$hiddenList.'</div>
									<input type="hidden" name="userID" value="'.$mdrUser->id().'" />
								</form>
							</td>
						</tr>
						<tr>
						<td id="hideControlContainer">
						'.$hideLink.'
						</td>
						<td id="defaultControlContainer">
						'.$showLink.'
						</td>
						</tr>
					</table>
					</div>
		 		</td>
	 		</tr>
		</table>
	';
}

  /**
   * controlPanel::defaultPointsForm()
   *
   * @param mixed $connection
   * @param mixed $userID
   * @param mixed $defaultChartPref
   * @return
   */
function defaultPointsForm($master_connection, $mdrUser,$defaultChartPref)
{
	$visibleOptions = '';
	$defaultOptions = '';
	$chartPref = '';

	$visiblePoints = $this->gatherVisiblePoints($master_connection, $mdrUser, false);
	$defaultPoints = $this->gatherDefaultPoints($master_connection, $mdrUser);

	$defaultCount = count($defaultPoints);

	if($defaultCount > 1)
	{
		if($defaultChartPref == '')
		{
			$defaultChartChoices = '
				<input type="radio" name="defaultChartPref" value="individual" checked />Individual
				<input type="radio" name="defaultChartPref" value="allInOne" />All-In-One<br />
			';
		}
		else
		{
			$defaultChartChoices = '
				<input type="radio" name="defaultChartPref" value="individual" />Individual
				<input type="radio" name="defaultChartPref" value="allInOne" checked />All-In-One<br />
			';
		}
		$chartPref = '
			<tr>
				<td>
					<div style="text-align: center;">
						<h4>How would you prefer your charts to be displayed when you first log in to the system?</h4>
					</div>
					<form id="defaultPointPref" action="includes/setPrefs.inc.php" method="post">
						'.$defaultChartChoices.'
						<input type="hidden" name="userID" value="'.$mdrUser->id().'">
					</form>
					<div style="padding: 20px;">
					<a id="setChartPref" href="#" style="padding: 3px; border: 1px solid;" onClick="processChartPref();">Set Preference</a>
						<div class="note">
						Selecting All-In-One will remove pricing from your default chart. To view pricing, simply return to the Set Preferences section of the Control Panel, and select Individual for your chart display preference.
						</div>
 					</div>
				</td>
			</tr>
		';
	}



	if($defaultPoints == '')
	{
		$defaultList = '<div class="error" style="width: 232px;">You currently have no default meter points selected.</div>';
		$removeLink = '';
	}
	else
	{
		$defaultCount = count($defaultPoints);

		foreach($defaultPoints as $inx=>$value)
		{
			$defaultOptions .= '<li class="alt"><label><input name="default['.$inx.']" value="'.$value.'" type="checkbox" />'.$value.'</label></li>';
		}

		if($defaultCount >= 6){$style = 'style="height: 10em;"';}else{$style = '';}

		$defaultList = '<ul class="checklist pointChecklist" '.$style.'>'.$defaultOptions.'</ul>';
		$removeLink = '<a id="showLink" href="#" onClick="processDefaultRemove();"><<< Remove</a>';
	}

	if($visiblePoints == '')
	{
		$visibleList = '<div class="error"  style="width: 232px;">You currently have no visible points selected. Please click on Hide Meter Points within the Set Preferences area of the Control Panel and place at least one point in your visible points list.</div>';
		$addLink = '';
	}
	else
	{
		$visibleCount = count($visiblePoints);

		foreach($visiblePoints as $inx=>$value)
		{
			$visibleOptions .= '<li class="alt"><label><input name="visible['.$inx.']" value="'.$value.'" type="checkbox" />'.$value.'</label></li>';
		}

		if($visibleCount >= 6){$style = 'style="height: 10em;"';}else{$style = '';}

		$visibleList = '<ul class="checklist pointChecklist" '.$style.'>'.$visibleOptions.'</ul>';
		$addLink = '<a id="hideLink" href="#" onClick="processDefaultAdd();"><p>Add >>> </p></a>';
	}

	return '
		<table align="center" width="700" cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>
					<div id="defaultPointsForm">
					<table align="center" cellpadding="5" cellspacing="0" border="0">
						<tr>
							<td width="250">
								<form id="addDefaultPoint" action="includes/setPrefs.inc.php" method="post">
									<h2>Visible Points</h2>
									<div>'.$visibleList.'</div>
									<input type="hidden" name="userID" value="'.$mdrUser->id().'" />
								</form>
							</td>
							<td width="250">
								<form id="removeDefaultPoint" action="includes/setPrefs.inc.php" method="post">
									<h2>Default Points</h2>
									<div>'.$defaultList.'</div>
									<input type="hidden" name="userID" value="'.$mdrUser->id().'" />
								</form>
							</td>
						</tr>
						<tr>
							<td id="hideControlContainer">
							'.$addLink.'
							</td>
							<td id="defaultControlContainer">
							'.$removeLink.'
							</td>
						</tr>
					</table>
					<table align="center" width="700" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td class="note">We suggest limiting your selection to three meter points.</td>
						</tr>
						'.$chartPref.'
					</table>
					</div>
		 		</td>
	 		</tr>
		</table>
	';



}

function setZipForm($master_connection, $userID)
{
	$zipCode = $this->gatherZipcode($master_connection, $userID);

	return '

			<div style="padding-bottom: 30px; text-align: center;">
			<a href="#" class="defaultButton" style="padding: 3px;" onClick="$(\'dataReturn_res\').setStyle(\'display\', \'block\');$(\'dataReturn_table\').setStyle(\'display\', \'none\')" >Return to Chart</a>
			</div>

		<div id="setZipForm" style="text-align: center;">
			<form id="setZip" action="includes/setPrefs.inc.php" method="post">
				<input type="text" name="zipCode" value="'.$zipCode.'" />
				<input type="hidden" name="userID" value="'.$userID.'" />
				<a id="submitZip" href="#" class="defaultButton" style="padding: 3px; border: 1px solid #FF6701;" onClick="processZip();">Set Zip Code</a>

			</form>
		</div>
	';
}

  /**
   * controlPanel::meterForm()
   *
   * @param mixed $username
   * @param mixed $points
   * @param mixed $selectedPoints
   * @param mixed $baseDate
   * @return
   */
	function meterForm($username,$points,$selectedPoints,$baseDate)
	{
		$pointOptions = '';
		$selectedOptions = '';
		$pointCount = count($points);
        $pointNumber = 0;

		if($points)
		{
			foreach($points as $inx=>$value)
			{
				$checkedPoint = '';

				if(!empty($selectedPoints))
				{
					if(array_key_exists($inx,$selectedPoints))
					{
						$selectedOptions .= '<li class="cpAlt"><label><input name="basicPoints['.$inx.']" type="checkbox" id="meterFormPoint_'.$pointNumber.'" checked />'.$value.'</label></li>'."\n";
					}
					else
					{
						$pointOptions .= '<li class="cpAlt"><label><input name="basicPoints['.$inx.']" id="meterFormPoint_'.$pointNumber.'" type="checkbox" />'.$value.'</label></li>'."\n";
					}
				}
				else
				{
					$selectedOptions = '';
					$pointOptions .= '<li class="cpAlt"><label><input name="basicPoints['.$inx.']" type="checkbox" id="meterFormPoint_'.$pointNumber.'" '.$checkedPoint.' />'.$value.'</label></li>'."\n";
				}
				$pointNumber++;
			}
		}
		else
		{
			$pointOptions = 'No Meters Available';
		}

		$pointOptions = $selectedOptions.$pointOptions;

		if($pointCount >= 6){$style = 'style="height: 10em; padding-top: 0px; margin-top: 0px;"';}else{$style = '';}

		$pointList = '<ul class="cpChecklist cpPointChecklist" '.$style.'>'.$pointOptions.'</ul>';

		return '
			<form id="pointsForm" action="index.php" method="post">
			<div id="form_box">
				<div style="text-align: center; padding-top: 10px; padding-bottom: 0px;">
				<input class="calendar" name="baseDate" id="baseDate" type="text" value="'.$baseDate.'" /><br /><br />
				</div>
				<div id="basicPointList">
				'.$pointList.'
				</div>
					<input type="hidden" name="username" value="'.$username.'" />
					<input type="hidden" name="action" value="basic" />
					<input type="hidden" name="formUsed" value="pointsForm" />
					<div align="center">
					<input type="submit" name="clear" class="cpButton" value="Reset"/>&nbsp;&nbsp;&nbsp;<input type="submit" name="fetchPoints" value="Display Chart" class="cpButton" />
				</div>
			</div>
			</form>
		';
	}
/*  ===============================================================================
    FUNCTION : dayAheadForm()
    =============================================================================== */
    function dayAheadForm($points,$selectedPoint = '')
	{
		$pointOptions = '';
		$pointCount = count($points);
        $pointNumber = 0;

		if($points)
		{
			foreach($points as $inx=>$value)
			{
				$checkedPoint = '';

				$checked = $inx == $selectedPoint ? 'checked ' : '';

                $pointOptions .= '<li class="cpAlt"><label><input name="dayAheadPoint" type="radio"  value="'.$inx.'" '.$checked.'/>'.$value.'</label></li>'."\n";
			}
		}
		else
		{
			$pointOptions = 'No Meters Available';
		}

		if($pointCount >= 6){$style = 'style="height: 10em; padding-top: 0px; margin-top: 0px;"';}else{$style = '';}

		$pointList = '<ul class="cpChecklist cpPointChecklist" '.$style.'>'.$pointOptions.'</ul>';
/* 
        <div style="text-align: center; padding-top: 10px; padding-bottom: 0px;">
            <input class="calendar" name="dayAheadBaseDate" id="dayAheadBaseDate" type="text" value="'.$baseDate.'" /><br />
        </div> 
*/ 
		return '
            <form id="dayAheadForm" action="index.php" method="post">
                <div id="dayAheadForm_box">
                    <div id="dayAheadPointList">
                        '.$pointList.'
                    </div>
                    <input type="hidden" name="action" value="dayAheadStart" />
                    <input type="hidden" name="formUsed" value="dayAheadForm" />
                    <div align="center">
                        <input type="submit" name="dayAheadBids" value="Display" class="cpButton" />
                    </div>
                </div>
            </form>
		';
	} // dayAheadForm()

  /**
   * controlPanel::eventsForm()
   *
   * @param mixed $username
   * @param mixed $points
   * @param mixed $selectedPoints
   * @param mixed $dateSelect
   * @param mixed $baseDate
   * @return
   */
	function eventsForm($username,$points,$selectedPoints,$dateSelect,$baseDate,$mdrUser)
	{

			$pointOptions = '';
			$selectedOptions = '';
			$pointCount = count($points);

			if($points)
			{
				foreach($points as $inx=>$value)
				{
					$checkedPoint = '';

					if(!empty($selectedPoints))
					{
						if(array_key_exists($inx,$selectedPoints))
						{
							$selectedOptions .= '<li class="cpAlt"><label><input name="evtPoints['.$inx.']" type="checkbox" checked value="'.$value.'" onChange="updateEventDates();" />'.$value.'</label></li>';
						}
						else
						{
							$pointOptions .= '<li class="cpAlt"><label><input name="evtPoints['.$inx.']" type="checkbox" value="'.$value.'" onChange="updateEventDates();" />'.$value.'</label></li>';
						}

					}
					else
					{
						$selectedOptions = '';
						$pointOptions .= '<li class="cpAlt"><label><input name="evtPoints['.$inx.']" type="checkbox" '.$checkedPoint.' value="'.$value.'" onChange="updateEventDates();"  />'.$value.'</label></li>';
					}

				}
			}
			else
			{
				$pointOptions = 'No Meters Available';
			}

			$pointOptions = $selectedOptions.$pointOptions;

			if($pointCount >= 6){$style = 'style="height: 10em;"';}else{$style = '';}

			$pointList = '<ul class="cpChecklist cpPointChecklist" '.$style.'>'.$pointOptions.'</ul>';

            //$mdrUser->preDebugger($mdrUser->localDomain()->eventDates());
            $eventSummaryDates = $mdrUser->localDomain()->eventDates();
            $eventSummaryDateSelect = '<div style="padding-top: 3px; padding-bottom: 10px;">Summary Date:<br /><select id="evtSummaryDate" name="evtSummaryDate" class="eventDateSelect">';

			if(isset($_POST['evtSummaryDate']))
			{
				$selectedDate = $_POST['evtSummaryDate'];
			}
			else
			{
				$selectedDate = '';
			}

			foreach ($eventSummaryDates as $inx=>$date)
            {

				if($selectedDate == $date)
				{
					$selectedFlag = ' SELECTED';
				}
				else
				{
					$selectedFlag = '';
				}

                $eventSummaryDateSelect .= "<option ".$selectedFlag.">{$date}</option>";
            }

            $eventSummaryDateSelect .= "</select></div>";

			$returnString = '
				<form id="eventsForm" action="index.php" method="post">
    				<div id="evtForm_box">
						<div id="evtBaseDateContainer" >
							' . $dateSelect . '
						</div>
						<div id="eventPointList" >' . $pointList . '</div>
							<input type="hidden" name="username" value="' . $username . '" />
							<input type="hidden" name="action" value="event" />
							<input type="hidden" name="formUsed" value="eventsForm" />
							<input type="hidden" name="dateUsed" value="' . $baseDate . '" />
						<div align="center">
							<input type="submit" name="clear" class="cpButton" value="Reset"/>&nbsp;&nbsp;&nbsp;<input type="submit" name="fetchEvents" value="Display Chart" class="cpButton" />
						</div>

						<fieldset style="margin-top: 10px; margin-bottom: 10px;">
							<legend>Event Summaries</legend>
							<div id="evtSummaryDateContainer">
								' . $eventSummaryDateSelect . '
							</div>
							<div align="center" style="margin-top: 10px;">
								<input type="submit" name="fetchEventSummary" value="Get Event Summary" class="cpButton" />
							</div>
						</fieldset>
					</div>
				</form>
			';

            /*if($mdrUser->isLseUser())
                $returnString .= '<div style="margin-left: 10px; margin-right: 10px;"><hr /><a href="missing_read_report/">Missing Read Report</a><hr /></div>';
            */
		return $returnString;
	}

  /**
   * controlPanel::advMeterForm()
   *
   * @param mixed $baseDateFrom
   * @param mixed $baseDateTo
   * @param mixed $csvBaseDateFrom
   * @param mixed $csvBaseDateTo
   * @param mixed $rangeOptions
   * @param mixed $points
   * @param mixed $selectedPoints
   * @param mixed $viewOptions
   * @param mixed $presentationOptions
   * @param mixed $username
   * @param mixed $userID
   * @param mixed $connection
   * @param mixed $comparisonArray
   * @param mixed $mvcArray
   * @return
   */
function advMeterForm($baseDateFrom,$baseDateTo,$csvBaseDateFrom,$csvBaseDateTo,$rangeOptions,$points,$selectedPoints,$viewOptions,$presentationOptions,$username,$userID,$connection, $comparisonArray, $mvcArray)
{
	$pointOptions = '';
	$selectedOptions = '';
	$cmpOptions = '';
	$pointCount = count($points);
	$priceFlag = '';
	$pointString = '';
	$ids = '';

	if(!empty($comparisonArray))
	{
		foreach($comparisonArray as $cmpValue)
		{
			$cmpOptions .= '<option value="'.$cmpValue.'">'.$cmpValue.'</option>';
		}

	}

	if(!empty($selectedPoints))
	{
		foreach($selectedPoints as $id=>$status)
		{
			$ids .= $id.',';
		}

		$pointString = rtrim($ids,',');
	}

	$csvChoicesDisabled = '';
	$buttonLabel = 'Display Chart';

	foreach($mvcArray as $inx=>$variableSet)
	{
		//'.$variableSet['script'].'
		$multiVersusCompare[$variableSet['value']] = '<input type="radio" name="mvc" id="mvc"  value="'.$variableSet['value'].'" '.$variableSet['status'].' />&nbsp;'.$variableSet['caption'].'<br />';
		//$multiVersusCompare[$variableSet['value']] = '<input type="radio" clickname="mvc" id="mvc" '.$variableSet['script'].' value="'.$variableSet['value'].'" '.$variableSet['status'].' />&nbsp;'.$variableSet['caption'].'<br />';
		if($variableSet['status'] == 'checked')
		{
			$mvcDisabled[$variableSet['value']] = '';
		}
		else
		{
			$mvcDisabled[$variableSet['value']] = 'disabled';
		}
		if($variableSet['value'] == 'export' && $variableSet['status'] == 'checked')
		{
			$csvChoicesDisabled = 'disabled';
			$buttonLabel = 'Create Export';
		}
		else
		{
			$csvChoicesDisabled = 'disabled';
		}
	}

	if($mvcDisabled['compare'] == 'disabled')
	{
		$removeLinkStyle = 'style="visibility:hidden;';
	}
	else
	{
		$removeLinkStyle = 'style="visibility:visible;';
	}
	$csvChoices = '
		<input type="checkbox" id="csvChoices" name="csvChoices[0]" value="pricing" checked '.$csvChoicesDisabled.' /> Pricing<br />
		<input type="checkbox" id="csvChoices" name="csvChoices[1]" value="data" checked '.$csvChoicesDisabled.' /> Data<br />
		<input type="checkbox" id="csvChoices" name="csvChoices[2]" value="hourlyRollup" '.$csvChoicesDisabled.' /> Hourly Rollup<br />
	';

	if($points)
	{
		foreach($points as $inx=>$value)
		{
			$checkedPoint = '';

			if(!empty($selectedPoints))
			{
				if(array_key_exists($inx,$selectedPoints))
				{
					$selectedOptions .= '<li class="cpAlt"><label><input name="advPoints['.$inx.']" type="checkbox" checked />'.$value.'</label></li>';
				}
				else
				{
					$pointOptions .= '<li class="cpAlt"><label><input name="advPoints['.$inx.']" type="checkbox" />'.$value.'</label></li>';
				}
			}
			else
			{
				$selectedOptions = '';
				$pointOptions .= '<li class="cpAlt"><label><input name="advPoints['.$inx.']" type="checkbox" '.$checkedPoint.' />'.$value.'</label></li>';
			}

		}
	}
	else
	{
		$pointOptions = 'No Meters Available';
	}

	$pointOptions = $selectedOptions.$pointOptions;

	if($pointCount >= 6){$style = 'style="height: 10em;"';}else{$style = '';}

	$pointList = '<ul class="cpChecklist cpPointChecklist" '.$style.'>'.$pointOptions.'</ul>';

	return '
		<div id="advForm_box" style="width: 180px;">
			<form id="advPointsForm" name="advPointsForm" action="index.php" method="post">
			<h4>Meters to Chart</h4>
			<div id="advPointList" style="margin-left: 5px;">
				'.$pointList.'
			</div>
<!-- Mutli-Day -->
		<a name="MultiDayChart" onClick="mvcToggle0();" style="padding: 0px; margin: 0px;"  href="#"><h4 style="padding: 0px; margin: 0px;">'.$multiVersusCompare['multi'].'</h4></a>
			<div id="cpAdvMulti" style="margin-left: 15px; padding-top: 10px; padding-bottom: 15px;">
				<table cellpadding="0" cellspacing="0" border="0" style="border: none;">
				<tr>
					<td>Quick Pick:</td>
					<td style="padding-left: 3px;">
						<div><select id="dateRange_id" name="dateRange" class="dateSpan" OnChange="setDates(document.advPointsForm.dateRange_id,document.advPointsForm.advBaseDateFrom,document.advPointsForm.advBaseDateTo);" '.$mvcDisabled['multi'].'>
						'.$rangeOptions.'
						</select></div>
					</td>
				</tr>
				</table>
				<table cellpadding="3" cellspacing="0" border="0" style="border: none;">
				<tr>
					<td colspan="2" style="text-align: center; padding-top: 12px;">- or Select a Range -<br /></td>
				</tr>
				<tr>
					<td>From:</td><td style="padding-left: 2px;"><input class="calendar" name="advBaseDateFrom" id="advBaseDateFrom" type="text" value="'.$baseDateFrom.'"  '.$mvcDisabled['multi'].'/></td>
				</tr>
				<tr>
					<td>To:</td><td style="padding-left: 2px;"><input class="calendar" name="advBaseDateTo" id="advBaseDateTo" type="text" value="'.$baseDateTo.'"  '.$mvcDisabled['multi'].'/></td>
				</tr>
				</table>
			</div>
<!-- Comparison -->
            <a name="Comparison" onClick="mvcToggle1();" style="padding: 0px; margin: 0px;" href="#"><h4 style="padding: 0px; margin: 0px;" >'.$multiVersusCompare['compare'].'</h4></a>
				<div id="cpAdvCompare" style="padding-top: 10px; padding-bottom: 15px;">
					<div style="margin-left: 25px;">
						<select class="calendar" size="3" id="cmpSelect_id" name="cmpSelect[]" multiple style="width: 100px; overflow: hidden;" '.$mvcDisabled['compare'].' >
						'.$cmpOptions.'
						</select>
					</div>
					<div id="removeLinks" name="removeLinks" '.$removeLinkStyle.' padding-left: 15px;">
						<a href="#" onClick="removeSelectedDate($(\'cmpSelect_id\'),false)" >Remove Selected Date</a><br /><a href="#" onClick="removeSelectedDate($(\'cmpSelect_id\'),true)" >Remove All Dates</a>
					</div>
				</div>

<!-- CSV Export -->
			<a name="LongTermCSVExport" onClick="mvcToggle2();" style="padding: 0px; margin: 0px;"  href="#"><h4 style="padding: 0px; margin: 0px;">'.$multiVersusCompare['export'].'</h4></a>
			<div id="cpAdvCSVExport" style="padding-top: 10px; padding-bottom: 15px;">
				<div style="margin-left: 15px;">
					<table cellpadding="3" cellspacing="0" border="0" style="border: none;">
					<tr>
						<td>From:</td><td style="padding-left: 2px;"><input class="calendar" name="advCSVBaseDateFrom" id="advCSVBaseDateFrom" type="text" value="'.$csvBaseDateFrom.'"  '.$mvcDisabled['export'].'/></td>
					</tr>
					<tr>
						<td>To:</td><td style="padding-left: 2px;"><input class="calendar" name="advCSVBaseDateTo" id="advCSVBaseDateTo" type="text" value="'.$baseDateTo.'"  '.$mvcDisabled['export'].'/></td>
					</tr>
					<tr>
						<td colspan="2">Options:<div style="padding-left: 10px;">'.$csvChoices.'</div></td>
					</tr>
					</table>

				</div>
			</div>
<!-- Presentation -->
			<div id="presentation_id">
				<br />
				<fieldset>
					<legend>Chart Types</legend>
					<ul class="radio">
					'.$presentationOptions.'
					</ul>
				</fieldset>
				<br />
			</div>
				<input type="hidden" name="username" value="'.$username.'" />
				<input type="hidden" name="action" value="advanced" />
				<input type="hidden" name="formUsed" value="advPointsForm" />
			<div align="center" >
				<input type="submit" name="clear" class="cpButton" value="Reset"/>&nbsp;&nbsp;&nbsp;<input type="submit" id="fetchAdvPoints" name="fetchAdvPoints" value="'.$buttonLabel.'" class="cpButton" />

			</div>
		</form>
		</div>

	';
					//onclick="processAdvancedForm(\'advPointsForm\',\''.$pointString.'\');"

}


  /**
   * controlPanel::summaryReportsForm()
   *
   * @param mixed $domainID
   * @param mixed $userID
   * @param mixed $contactProfiles
   * @param mixed $selectedContactProfiles
   * @return
   */
	function uptimeStatisticsForm($baseDateFrom,$baseDateTo,$rangeOptions,
                                  $points,$selectedPoints,$username,$userID,
                                  $connection)
	{
	$pointOptions = '';
	$selectedOptions = '';
	$cmpOptions = '';
	$pointCount = count($points);
	$priceFlag = '';
	$pointString = '';
	$ids = '';

	if(!empty($selectedPoints))
	{
		foreach($selectedPoints as $id=>$status)
		{
			$ids .= $id.',';
		}

		$pointString = rtrim($ids,',');
	}

	$buttonLabel = 'Display Statistics';

	if($points)
	{
		foreach($points as $inx=>$value)
		{
			$checkedPoint = '';

			if(!empty($selectedPoints))
			{
				if(array_key_exists($inx,$selectedPoints))
				{
					$selectedOptions .= '<li class="cpAlt"><label><input name="uptPoints['.$inx.']" type="checkbox" checked />'.$value.'</label></li>';
				}
				else
				{
					$pointOptions .= '<li class="cpAlt"><label><input name="uptPoints['.$inx.']" type="checkbox" />'.$value.'</label></li>';
				}
			}
			else
			{
				$selectedOptions = '';
				$pointOptions .= '<li class="cpAlt"><label><input name="uptPoints['.$inx.']" type="checkbox" '.$checkedPoint.' />'.$value.'</label></li>';
			}

		}
	}
	else
	{
		$pointOptions = 'No Meters Available';
	}

	$pointOptions = $selectedOptions.$pointOptions;

	if($pointCount >= 6){$style = 'style="height: 10em;"';}else{$style = '';}

	$pointList = '<ul class="cpChecklist cpPointChecklist" '.$style.'>'.$pointOptions.'</ul>';

	return '
        <form id="uptimeForm" name="uptimeForm" action="index.php" method="post">
        <div id="uptForm_box">

			<h4>Meters to Report</h4>
			<div id="uptPointList" style="margin-left: 5px;">
				'.$pointList.'
                <div align="center">
                    <input type="button" name="checkAll" class="cpButton" value="Check All" onClick="CheckAll(document.uptimeForm, true)" />
                    <input type="button" name="uncheckAll" class="cpButton" value="Uncheck All" onClick="CheckAll(document.uptimeForm, false)" />
                </div>
			</div>
<!-- Mutli-Day -->
		    <h4 style="padding: 0px; padding-top: 10px; margin: 0px;">Dates to Report</h4>
			<div id="cpUptMulti" style="margin-left: 15px; padding-top: 10px; padding-bottom: 15px;">
				<table cellpadding="0" cellspacing="0" border="0" style="border: none;">
				<tr>
					<td>Quick Pick:</td>
					<td style="padding-left: 3px;">
						<div><select id="uptDateRange_id" name="uptDateRange" class="dateSpan" OnChange="setDates(document.uptimeForm.uptDateRange_id,document.uptimeForm.uptBaseDateFrom,document.uptimeForm.uptBaseDateTo);">
						'.$rangeOptions.'
						</select></div>
					</td>
				</tr>
				</table>
				<table cellpadding="3" cellspacing="0" border="0" style="border: none;">
				<tr>
					<td colspan="2" style="text-align: center; padding-top: 12px;">- or Select a Range -<br /></td>
				</tr>
				<tr>
					<td>From:</td><td style="padding-left: 2px;"><input class="calendar" name="uptBaseDateFrom" id="uptBaseDateFrom" type="text" value="'.$baseDateFrom.'" /></td>
				</tr>
				<tr>
					<td>To:</td><td style="padding-left: 2px;"><input class="calendar" name="uptBaseDateTo" id="uptBaseDateTo" type="text" value="'.$baseDateTo.'" /></td>
				</tr>
				</table>
			</div>
				<input type="hidden" name="username" value="'.$username.'" />
				<input type="hidden" name="action" value="uptime" />
				<input type="hidden" name="formUsed" value="uptimeForm" />
			<div align="center" >
				<input type="submit" name="clear" class="cpButton" value="Reset"/>
                <input type="submit" id="fetchStatistics" name="fetchStatistics" class="cpButton" value="Display Statistics"/>

			</div>
		</div>
		</form>

	';

	}
//<input type="submit" id="fetchUptime" name="fetchUptime" value="'.$buttonLabel.'" class="cpButton" size="10" />

  /**
   * controlPanel::contactProfilesForm()
   *
   * @param mixed $domainID
   * @param mixed $userID
   * @param mixed $contactProfiles
   * @param mixed $selectedContactProfiles
   * @return
   */
	function contactProfilesForm($domainID,$userID,$contactUses,$contactProfiles,$selectedContactProfiles,$mdrUser)
	{
		$profileOptions = '';
		$selectedOptions = '';
		$profileCount = count($contactProfiles);

        $useOptions = "<div style=\"padding-bottom: 15px;\">Use: <select name=\"ContactUse\" size=\"1\">\n";
        $selected = "selected ";
		for ($inx=0; $inx<count($contactUses); $inx++) {
            $useOptions .= "<option {$selected} value=\"". $contactUses[$inx]->name()."\">".$contactUses[$inx]->description()."</option>\n";
            $selected = "";
        }
        $useOptions .= "</select></div>\n";

		for ($inx=0; $inx<$profileCount; $inx++) {
			$checkedProfile = '';
            $checked = null;

			if(!$mdrUser->HasPreference('HideRetiredPointchannels') || $contactProfiles[$inx]->flag())
			{
				if(!empty($selectedContactProfiles))
				{
					if(array_key_exists($inx,$selectedContactProfiles))
					{
						$selectedOptions .= '<li class="cpAlt"><label><input name="basicProfiles['.$inx.']" type="checkbox" checked />'.$contactProfiles[$inx]->description().'</label></li>';
					}
					else
					{
						$profileOptions .= '<li class="cpAlt"><label><input name="basicProfiles['.$inx.']" type="checkbox" />'.$contactProfiles[$inx]->description().'</label></li>';
					}
				}
				else
				{
					$selectedOptions = '';
					$profileOptions .= '<li class="cpAlt"><label><input name="basicProfiles['.$inx.']" type="checkbox" '.$checkedProfile.' />'.$contactProfiles[$inx]->description().'</label></li>';
				}
			}
		}

		$profileOptions = $selectedOptions.$profileOptions;

		if($profileCount >= 6){$style = 'style="height: 10em; padding-top: 0px; margin-top: 0px;"';}else{$style = '';}

		$profileList = $useOptions.'<ul class="cpChecklist cpPointChecklist" '.$style.'>'.$profileOptions.'</ul>';

		return '
			<form id="profilesForm" action="index.php" method="post">
			<div id="profileForm_box">
				<h4>User Login Information:</h4>
				<div id="cpChangeEmail"><a href="#">Change Email Address</a></div>
				<div id="cpChangeTelephone"><a href="#">Change Primary Telephone</a></div>
				<h4>Notification Updates:</h4>
				<div id="basicProfileList" style="margin-left: 5px;">
				'.$profileList.'
				</div>
				<div align="center">
					<input type="submit" name="clear" class="cpButton" value="Reset"/>&nbsp;<input type="submit" name="fetchProfiles" value="Display Profile" class="cpButton" />
				</div>
                <div style="text-align: center; margin: 10px;">-- or --</div>
                '.$this->programSelectList($mdrUser).'
                
                <div align="center">
                    <input type="checkbox" name="includeInactive" value="true" />Include Inactive?
                <br />    
                    <input type="submit" name="viewContactReport" class="cpButton" value="View Contact Report" />
                </div>
			</div>
                <input type="hidden" name="userid" value="'.$userID.'" />
                <input type="hidden" name="domainID" value="'.$domainID.'" />
                <input type="hidden" name="action" value="contact" />
                <input type="hidden" name="formUsed" value="profilesForm" />
			</form>
        ';

	}

    function programSelectList($mdrUser)
    {
        for ($inx=0; $inx<$mdrUser->pointChannels()->length(); $inx++)
        {
            $programList[$mdrUser->pointChannels()->item($inx)->MeterPoint()->participationTypeId()] = $mdrUser->pointChannels()->item($inx)->MeterPoint()->participationTypeDescription();
        }

        $result = '';

        if($programList)
        {
            $result .= '<div id="contactAssetPointList">
							<h4>Select Program:</h4>';

            $checked = 'checked';
            foreach($programList as $id=>$description)
            {
                $result .= '<table border="0" cellpadding="0" cellspacing="0" style="border: none;">';
                $result .= '<tr>';
                $result .= '<td>';
                $result .= '<input
                            name="contactProgram"
                            value="'.$id.'"
                            type="radio"
                            '.$checked.'
                            />';
                $result .= '</td>';
                $result .= '<td style="font-size: 12px; color: #000;">';
                $result .= $description;
                $result .= '</td></tr>';
                $result .= '</table>';
                $checked = null;
            }

            $result .= '</div>';
        }
        else
        {
            $result = 'There was an error with the request.';
        }

        //return '<a href="#" onClick="javascript:callModellingForm();"><span style="cursor: pointer;">Manage Assets</span></a>';
        return $result;
    }

  /**
   * controlPanel::summaryReportsForm()
   *
   * @param mixed $domainID
   * @param mixed $userID
   * @param mixed $contactProfiles
   * @param mixed $selectedContactProfiles
   * @return
   */
	function summaryReportsForm($baseDateFrom,$baseDateTo,$rangeOptions,
                                $points,$selectedPoints,$reportOptions,$username,$userID,
                                $connection)
	{
	$pointOptions = '';
	$selectedOptions = '';
	$cmpOptions = '';
	$pointCount = count($points);
	$priceFlag = '';
	$pointString = '';
	$ids = '';

	if(!empty($selectedPoints))
	{
		foreach($selectedPoints as $id=>$status)
		{
			$ids .= $id.',';
		}

		$pointString = rtrim($ids,',');
	}

	$buttonLabel = 'Display Report';

	if($points)
	{
		foreach($points as $inx=>$value)
		{
			$checkedPoint = '';

			if(!empty($selectedPoints))
			{
				if(array_key_exists($inx,$selectedPoints))
				{
					$selectedOptions .= '<li class="cpAlt"><label><input name="repPoints['.$inx.']" type="checkbox" checked />'.$value.'</label></li>';
				}
				else
				{
					$pointOptions .= '<li class="cpAlt"><label><input name="repPoints['.$inx.']" type="checkbox" />'.$value.'</label></li>';
				}
			}
			else
			{
				$selectedOptions = '';
				$pointOptions .= '<li class="cpAlt"><label><input name="repPoints['.$inx.']" type="checkbox" '.$checkedPoint.' />'.$value.'</label></li>';
			}

		}
	}
	else
	{
		$pointOptions = 'No Meters Available';
	}

	$pointOptions = $selectedOptions.$pointOptions;

	if($pointCount >= 6){$style = 'style="height: 10em;"';}else{$style = '';}

	$pointList = '<ul class="cpChecklist cpPointChecklist" '.$style.'>'.$pointOptions.'</ul>';

	return '
        <form id="reportsForm" name="reportsForm" action="index.php" method="post">
        <div id="reportsForm_box">
			<h4>Meters to Report</h4>
			<div id="repPointList" style="margin-left: 5px;">
				'.$pointList.'
                <div align="center">
                    <input type="button" name="checkAll" class="cpButton" value="Check All" onClick="CheckAll(document.reportsForm, true)" />
                    <input type="button" name="uncheckAll" class="cpButton" value="Uncheck All" onClick="CheckAll(document.reportsForm, false)" />
                </div>
			</div>
<!-- Mutli-Day -->
		    <h4 style="padding: 0px; padding-top: 10px; margin: 0px;">Dates to Report</h4>
			<div id="cpRepMulti" style="margin-left: 15px; padding-top: 10px; padding-bottom: 15px;">
				<table cellpadding="0" cellspacing="0" border="0" style="border: none;">
				<tr>
					<td>Quick Pick:</td>
					<td style="padding-left: 3px;">
						<div><select id="repDateRange_id" name="repDateRange" class="dateSpan" OnChange="setDates(document.reportsForm.repDateRange_id,document.reportsForm.repBaseDateFrom,document.reportsForm.repBaseDateTo);">
						'.$rangeOptions.'
						</select></div>
					</td>
				</tr>
				</table>
				<table cellpadding="3" cellspacing="0" border="0" style="border: none;">
				<tr>
					<td colspan="2" style="text-align: center; padding-top: 12px;">- or Select a Range -<br /></td>
				</tr>
				<tr>
					<td>From:</td><td style="padding-left: 2px;"><input class="calendar" name="repBaseDateFrom" id="repBaseDateFrom" type="text" value="'.$baseDateFrom.'" /></td>
				</tr>
				<tr>
					<td>To:</td><td style="padding-left: 2px;"><input class="calendar" name="repBaseDateTo" id="repBaseDateTo" type="text" value="'.$baseDateTo.'" /></td>
				</tr>
				</table>
			</div>
<!-- Presentation -->
			<div id="presentation_id">
				<br />
				<fieldset>
					<legend>Report Types</legend>
					<ul class="radio">
					'.$reportOptions.'
					</ul>
				</fieldset>
				<br />
			</div>
				<input type="hidden" name="username" value="'.$username.'" />
				<input type="hidden" name="action" value="report" />
				<input type="hidden" name="formUsed" value="reportsForm" />
			<div align="center" >
				<input type="submit" name="clear" class="cpButton" value="Reset"/>&nbsp;&nbsp;&nbsp;<input type="submit" id="fetchReports" name="fetchReports" value="'.$buttonLabel.'" class="cpButton" />

			</div>
		</div>
		</form>

	';
					//onclick="processReportForm(\'repPointsForm\',\''.$pointString.'\');"
	}


  /**
   * controlPanel::assemble()
   *
   * @param mixed $meterForm
   * @param mixed $advMeterForm
   * @param mixed $eventsForm
   * @param mixed $userID
   * @param mixed $username
   * @param mixed $action
   * @param mixed $advPointString
   * @return
   */
	function assemble($meterForm, $advMeterForm, $uptimeForm, $eventsForm, $assetForm, $profilesForm, $reportsForm, $userID, $username, $action, $advPointString,$mdrUser,$dayAheadForm)
	{
		$lastUsedBasic = '&nbsp;<img src="_template/images/left.gif" class="cpFormSelectedArrow" id="lastUsedBasic" border="0" style="display: none;"/>';
		$lastUsedAdvanced = '&nbsp;<img src="_template/images/left.gif" class="cpFormSelectedArrow" id="lastUsedAdvanced" border="0" style="display: none;"/>';
		$lastUsedEvent = '&nbsp;<img src="_template/images/left.gif" class="cpFormSelectedArrow" id="lastUsedEvent" border="0" style="display: none;"/>';
        $lastUsedContact = '&nbsp;<img src="_template/images/left.gif" class="cpFormSelectedArrow" id="lastUsedContact" border="0" style="display: none;"/>';
        $lastUsedUptime = '&nbsp;<img src="_template/images/left.gif" class="cpFormSelectedArrow" id="lastUsedUptime" border="0" style="display: none;"/>';
        $lastUsedReports = '&nbsp;<img src="_template/images/left.gif" class="cpFormSelectedArrow" id="lastUsedReports" border="0" style="display: none;"/>';
        $lastUsedDayAhead = '&nbsp;<img src="_template/images/left.gif" class="cpFormSelectedArrow" id="lastUsedDayAhead" border="0" style="display: none;"/>';

		switch ($action)
		{
		case 'basic':
			$lastUsedBasic = '&nbsp;<img src="_template/images/left.gif" class="cpFormSelectedArrow" id="lastUsedBasic" border="0" style="display: inline;"/>';
			break;
		case 'advanced':
			$lastUsedAdvanced = '&nbsp;<img src="_template/images/left.gif" class="cpFormSelectedArrow" id="lastUsedAdvanced" border="0" style="display: inline;"/>';
			break;
		case 'event':
			$lastUsedEvent = '&nbsp;<img src="_template/images/left.gif" class="cpFormSelectedArrow" id="lastUsedEvent" border="0" style="display: inline;"/>';
			break;
		case 'uptime':
			$lastUsedUptime = '&nbsp;<img src="_template/images/left.gif" class="cpFormSelectedArrow" id="lastUsedUptime" border="0" style="display: inline;"/>';
			break;
		case 'contact':
			$lastUsedContact = '&nbsp;<img src="_template/images/left.gif" class="cpFormSelectedArrow" id="lastUsedContact" border="0" style="display: inline;"/>';
			break;
		case 'report':
			$lastUsedReports = '&nbsp;<img src="_template/images/left.gif" class="cpFormSelectedArrow" id="lastUsedReports" border="0" style="display: inline;"/>';
			break;
        case 'dayAhead':
			$lastUsedDayAhead = '&nbsp;<img src="_template/images/left.gif" class="cpFormSelectedArrow" id="lastUsedDayAhead" border="0" style="display: inline;"/>';
			break;
        }

        //these are odd  'cause it's ajaxed so control panel never gets refreshed when in use
        $lastUsedPrefs = '&nbsp;<img src="_template/images/left.gif" class="cpFormSelectedArrow" id="lastUsedPrefs" border="0" style="display: none;"/>';
		$assetReportsForm = $mdrUser->isLseUser() ? $this->assetReportsForm() : '';
        //$assetReportsForm = '';

        $retiredString = $mdrUser->HasPreference('HideRetiredPointChannels') ? 'Show Retired' : 'Hide Retired';

        if($mdrUser->HasPrivilege('read.checkbox_dayaheadbids'))
        {
            $dayAhead = '';
            $dayAheadHold = '<!-- DAY AHEAD BIDS -->
            	<tr>
            		<td>
            		<table align="center" cellpadding="0" cellspacing="0" border="0">
            			<tr>
            				<td align="center">
            					<h2 id="cpSlice09"><a id="dayAheadToggle" class="meterTip" href="#">
                                    <div class="h2Bump">Day Ahead Bids'.$lastUsedBasic.'</div>
                                    </a></h2>
            					<div id="cpDayAhead">
            						<table width="202" cellpadding="0" cellspacing="0" border="0">
            							<tr>
            								<td class="shadowTop"></td>
            							</tr>
            							<tr>
            								<td class="cpContainerCell">
												<div id="selectDayAhead">'.$dayAheadForm.'</div>
												<div id="dayAheadHide">
                                                    <a href="#"><img src="_template/images/blank.gif" width="15" height="14" border="0" /></a>
                                                </div>
            								</td>
            							</tr>
            							<tr>
            								<td class="shadowBottom"></td>
            							</tr>
            						</table>
                <script>
            /***** dayAheadSlide *****/
		
    		var dayAheadSlide = new Fx.Slide("cpDayAhead");
    		
    		dayAheadSlide.hide(); 
    		//basicPoints.setStyle("visibility","hidden");
    		
    		$("dayAheadToggle").addEvent("click", function(e){
    			e = new Event(e);
    			dayAheadSlide.toggle();
    			toggleArrowVisibility("lastUsedDayAhead");
    			e.stop();
    		});
    		$("dayAheadHide").addEvent("click", function(e){
    			e = new Event(e);
    			dayAheadSlide.hide();
    			e.stop();
    		});
            </script>
            					</div>
            				</td>
            			</tr>
            		</table>
            		</td>
            	</tr>';


        }

		return '
		<div id="cpContainer">
            <div id="cp">
            <table width="202" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td id="cpHeader"><h1>. : CONTROL PANEL : . </h1></td>
                </tr>
<!-- SET PREFERENCES -->
                <tr>
                    <td>
                    <table align="center" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td align="center">
                                <h2 id="cpSlice01" align="center"><a onclick="cpPrefSlide.toggle(); toggleArrowVisibility(\'lastUsedPrefs\');" id="prefsTip" class="prefsTip" href="#"><div class="h2Bump">Set Preferences'.$lastUsedPrefs.'</div></a></h2>
                                <div id="cpPref">
                                    <table  width="202" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td class="shadowTop"></td>
                                        </tr>
                                        <tr>
                                            <td class="cpContainerCell">

												<div id="cpHidePoint"><a href="#">Set Viewable Points</a></div>
												<div id="cpSetDefaultPoint"><a href="#">Set Default Meter Point</a></div>
												<div id="cpSetZip"><a href="#">Set Weather Zip Code</a></div>
												<div id="cpChangePassword"><a href="#">Change Password</a></div>
                                                <div id="cpToggleRetired" onClick="toggleRetired();"><a href="#">'.$retiredString.'</a>
                                                    <div id="toggleRetiredFeedback" style="display: hidden; color: #FF6701;"></div>
                                                </div>
												<div id="prefHide"><a href="#"><img src="_template/images/blank.gif" width="15" height="14" border="0" /></a></div>

        									</td>
        								</tr>
        								<tr>
        									<td class="shadowBottom"></td>
        								</tr>
        							</table>
        						</div>
            				</td>
            			</tr>
            		</table>
            		</td>
            	</tr>
<!-- SELECT NEW METER -->
                	<tr>
            		<td>
            		<table align="center" cellpadding="0" cellspacing="0" border="0">
            			<tr>
            				<td align="center">
            					<h2 id="cpSlice02"><a id="pointToggle" class="meterTip" href="#"><div class="h2Bump">Select New Meter'.$lastUsedBasic.'</div></a></h2>
            					<div id="cpPoint">
            						<table width="202" cellpadding="0" cellspacing="0" border="0">
            							<tr>
            								<td class="shadowTop"></td>
            							</tr>
            							<tr>
            								<td class="cpContainerCell">

												<div id="selectMeter">'.$meterForm.'</div>
												<div id="pointHide"><a href="#"><img src="_template/images/blank.gif" width="15" height="14" border="0" /></a></div>

            								</td>
            							</tr>
            							<tr>
            								<td class="shadowBottom"></td>
            							</tr>
            						</table>
            					</div>
            				</td>
            			</tr>
            		</table>
            		</td>
            	</tr>
<!-- EVENT PERFORMANCE -->
                <tr>
                    <td>
                    <table align="center" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td align="center">
                                <h2 id="cpSlice03"><a id="eventToggle" class="eventTip" href="#"><div class="h2Bump">Event Performance'.$lastUsedEvent.'</div></a></h2>
                                <div id="cpEvent">
                                    <table width="202" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td class="shadowTop"></td>
                                        </tr>
                                        <tr>
                                            <td class="cpContainerCell">

												<div>'.$eventsForm.'</div>
												<div id="eventHide"><a href="#"><img src="_template/images/blank.gif" width="15" height="14" border="0" /></a></div>
											</td>
                                        </tr>
                                        <tr>
                                            <td class="shadowBottom"></td>
                                        </tr>
            						</table>
            					</div>
            				</td>
            			</tr>
            		</table>
            		</td>
            	</tr>
<!-- ADVANCED CHARTING -->
            	<tr>
            		<td>
            		<table align="center" cellpadding="0" cellspacing="0" border="0">
            			<tr>
            				<td align="center">
            					<h2 id="cpSlice04" align="center"><a onclick="cpAdvPointSlide.toggle(); toggleArrowVisibility(\'lastUsedAdvanced\');" id="advPointTip" class="advPointTip" href="#"><div class="h2Bump">Advanced Charting'.$lastUsedAdvanced.'</div></a></h2>

            						<div id="cpAdvPoint">
            							<table width="202" cellpadding="0" cellspacing="0" border="0">
            								<tr>
            									<td class="shadowTop"></td>
            								</tr>
            								<tr>
												<td class="cpContainerCell">
													'.$advMeterForm.'
													<div id="advPointHide"><a href="#"><img src="_template/images/blank.gif" width="15" height="14" border="0" /></a></div>

												</td>
            								</tr>
            								<tr>
            									<td class="shadowBottom"></td>
            								</tr>
            							</table>
            						</div>
            				</td>
            			</tr>
            		</table>
            		</td>
            	</tr>
<!-- UPTIME STATISTICS -->
            	<tr>
            		<td>
            		<table align="center" cellpadding="0" cellspacing="0" border="0">
            			<tr>
            				<td align="center">
            					<h2 id="cpSlice05"><a id="statsToggle" class="statsTip" href="#"><div class="h2Bump">Uptime Statistics'.$lastUsedUptime.'</div></a></h2>
            					<div id="cpStats">
            						<table width="202" cellpadding="0" cellspacing="0" border="0">
            							<tr>
            								<td class="shadowTop"></td>
            							</tr>
            							<tr>
            								<td class="cpContainerCell">
												'.$uptimeForm.'
												<div id="statsHide"><a href="#"><img src="_template/images/blank.gif" width="15" height="14" border="0" /></a></div>
											</td>
            							</tr>
            							<tr>
            								<td class="shadowBottom"></td>
            							</tr>
            						</table>
            					</div>
            				</td>
            			</tr>
            		</table>
            		</td>
            	</tr>
<!-- ASSET MANAGEMENT -->
                '.$assetForm.'
<!-- CONTACT MANAGEMENT -->
            	<tr>
            		<td>
            		<table align="center" cellpadding="0" cellspacing="0" border="0">
            			<tr>
            				<td align="center">
            					<h2 id="cpSlice07"><a id="contactToggle" class="contactTip" href="#"><div class="h2Bump">Contact Management'.$lastUsedContact.'</div></a></h2>
            					<div id="cpContact">
            						<table width="202" cellpadding="0" cellspacing="0" border="0">
            							<tr>
            								<td class="shadowTop"></td>
            							</tr>
            							<tr>
            								<td class="cpContainerCell">

												<div id="selectProfile">'.$profilesForm.'</div>
												'.$assetReportsForm.'
												<div id="contactHide"><a href="#"><img src="_template/images/blank.gif" width="15" height="14" border="0" /></a></div>

            								</td>
            							</tr>
            							<tr>
            								<td class="shadowBottom"></td>
            							</tr>
            						</table>
            					</div>
            				</td>
            			</tr>
            		</table>
            		</td>
            	</tr>
<!-- SUMMARY REPORTS -->
            	<tr>
            		<td>
            		<table align="center" cellpadding="0" cellspacing="0" border="0">
            			<tr>
            				<td align="center">
            					<h2 id="cpSlice08"><a id="summaryToggle" class="summaryTip" href="#"><div class="h2Bump">Summary Reports'.$lastUsedReports.'</div></a></h2>
            					<div id="cpSummary">
            						<table width="202" cellpadding="0" cellspacing="0" border="0">
            							<tr>
            								<td class="shadowTop"></td>
            							</tr>
                                        <tr>
                                            <td class="cpContainerCell" width="196">
												'.$reportsForm.'
												<div id="summaryHide"><a href="#"><img src="_template/images/blank.gif" width="15" height="14" border="0" /></a></div>

                                            </td>
                                        </tr>
            							<tr>
            								<td class="shadowBottom"></td>
            							</tr>
            						</table>
            					</div>
            				</td>
            			</tr>
            		</table>
            		</td>
            	</tr>
                '.$dayAhead.'
<!-- BOTTOM -->
            </table>
            </div>
        </div>
<div id="cpContainerTimer"></div>
<script type="text/javascript">
/***** cpPrefSlide *****/
	var cpPrefSlide = new Fx.Slide(\'cpPref\',{
		\'onComplete\':
			function(cpPrefSlide) {
				var hidden = cpPrefSlide.getParent().getStyle(\'height\') == \'0px\' ? true : false;
				cpPrefSlide.getParent().setStyle(\'height\',\'\');
				if(window.ie6 && hidden) cpPrefSlide.getParent().setStyle(\'height\',\'0px\');
			}
	});
	cpPrefSlide.hide();

	$(\'prefHide\').addEvent(\'click\', function(e){
			e = new Event(e);
			cpPrefSlide.hide();

			e.stop();
		});

/***** cpAdvPointSlide *****/
	var cpAdvPointSlide = new Fx.Slide(\'cpAdvPoint\',{
		\'onComplete\':
			function(cpAdvPointSlide) {
				var hidden = cpAdvPointSlide.getParent().getStyle(\'height\') == \'0px\' ? true : false;
				cpAdvPointSlide.getParent().setStyle(\'height\',\'\');
				if(window.ie6 && hidden) cpAdvPointSlide.getParent().setStyle(\'height\',\'0px\');
			}
	});

	cpAdvPointSlide.hide();

	$(\'advPointHide\').addEvent(\'click\', function(e){
			e = new Event(e);

			cpAdvPointSlide.hide();

			e.stop();
		});

	var cpAdvMeter_sub01 = new Fx.Slide(\'cpAdvMulti\');
	var cpAdvMeter_sub02 = new Fx.Slide(\'cpAdvCompare\');
	var cpAdvMeter_sub03 = new Fx.Slide(\'cpAdvCSVExport\');


		cpAdvMeter_sub02.hide();
		cpAdvMeter_sub03.hide();

	function CheckAll(form, state) {
        var inputs = form.getElementsByTagName("input");

        for (inx=0; inx<inputs.length; inx++) if (inputs[inx].getAttribute("type") == "checkbox") inputs[inx].checked = state;
    }

	function mvcToggle0(){
		cpAdvMeter_sub01.show();
		cpAdvMeter_sub02.hide();
		cpAdvMeter_sub03.hide();
		document.advPointsForm.mvc[0].checked=true; //ie seems unable to register the click for the tagged element
		document.advPointsForm.mvc[1].checked=false;
		document.advPointsForm.mvc[2].checked=false;
		document.advPointsForm.presentation[0].disabled=false;
		document.advPointsForm.presentation[1].checked=true;
		document.advPointsForm.presentation[1].disabled=false;
		document.advPointsForm.presentation[2].disabled=false;
		document.advPointsForm.advBaseDateFrom.disabled=false;
		document.advPointsForm.advBaseDateTo.disabled=false;
		document.advPointsForm.dateRange_id.disabled=false;
		document.advPointsForm.cmpSelect_id.disabled=true;
		document.advPointsForm.advCSVBaseDateTo.disabled=true;
		document.advPointsForm.advCSVBaseDateFrom.disabled=true;
		document.advPointsForm.csvChoices[0].disabled=true;
		document.advPointsForm.csvChoices[1].disabled=true;
		document.advPointsForm.csvChoices[2].disabled=true;
		$(\'removeLinks\').style.visibility=\'hidden\';
		$(\'fetchAdvPoints\').value=\'Display Chart\';
	};
	function mvcToggle1(){
		cpAdvMeter_sub01.hide();
		cpAdvMeter_sub02.show();
		cpAdvMeter_sub03.hide();
		document.advPointsForm.mvc[0].checked=false; //ie seems unable to register the click for the tagged element
		document.advPointsForm.mvc[1].checked=true;
		document.advPointsForm.mvc[2].checked=false;
        document.advPointsForm.presentation[0].disabled=true;
		document.advPointsForm.presentation[1].checked=true;
		document.advPointsForm.presentation[2].disabled=true;
		document.advPointsForm.advBaseDateFrom.disabled=true;
		document.advPointsForm.advBaseDateTo.disabled=true;
		document.advPointsForm.dateRange_id.disabled=true;
		document.advPointsForm.cmpSelect_id.disabled=false;
		document.advPointsForm.advCSVBaseDateTo.disabled=true;
		document.advPointsForm.advCSVBaseDateFrom.disabled=true;
		document.advPointsForm.csvChoices[0].disabled=true;
		document.advPointsForm.csvChoices[1].disabled=true;
		document.advPointsForm.csvChoices[2].disabled=true;
		$(\'removeLinks\').style.visibility=\'visible\';
		$(\'fetchAdvPoints\').value=\'Display Chart\';
	};
	function mvcToggle2(){
		cpAdvMeter_sub01.hide();
		cpAdvMeter_sub02.hide();
		cpAdvMeter_sub03.show();
		document.advPointsForm.mvc[0].checked=false; //ie seems unable to register the click for the tagged element
		document.advPointsForm.mvc[1].checked=false;
		document.advPointsForm.mvc[2].checked=true;
        document.advPointsForm.presentation[0].checked=false;
		document.advPointsForm.presentation[1].checked=false;
		document.advPointsForm.presentation[2].checked=false;
		document.advPointsForm.presentation[0].disabled=true;
		document.advPointsForm.presentation[1].disabled=true;
		document.advPointsForm.presentation[2].disabled=true;
		document.advPointsForm.advBaseDateFrom.disabled=true;
		document.advPointsForm.advBaseDateTo.disabled=true;
		document.advPointsForm.dateRange_id.disabled=true;
		document.advPointsForm.cmpSelect_id.disabled=true;
		document.advPointsForm.advCSVBaseDateTo.disabled=false;
		document.advPointsForm.advCSVBaseDateFrom.disabled=false;
		document.advPointsForm.csvChoices[0].disabled=false;
		document.advPointsForm.csvChoices[1].disabled=false;
		document.advPointsForm.csvChoices[2].disabled=false;
		$(\'removeLinks\').style.visibility=\'hidden\';
		$(\'fetchAdvPoints\').value=\'Create Export\';
	};


	$(\'cpChangeEmail\').addEvent( \'click\', function(evt){
		new Event(evt).stop();

		var dataReturn_chart = $(\'dataReturn_res\');
		var dataReturn_table = $(\'dataReturn_table\');

		new Request.HTML({
		method: \'post\',
		url: \'includes/setEmail.i.inc.php?userID='.$userID.'&username='.$username.'\',
		evalScripts: true,
		onComplete: function(respTree,respElements, respHTML, respJavascript) {
			dataReturn_table.setProperty(\'html\',respHTML);
			eval(respJavascript);
			dataReturn_chart.setStyle(\'display\', \'none\');
			dataReturn_table.setStyle(\'display\', \'block\');
		}
	}).send();

	});

	$(\'cpChangeTelephone\').addEvent( \'click\', function(evt){
		new Event(evt).stop();

		var dataReturn_chart = $(\'dataReturn_res\');
		var dataReturn_table = $(\'dataReturn_table\');

		new Request.HTML({
		method: \'post\',
		url: \'includes/setTelephone.i.inc.php?userID='.$userID.'&username='.$username.'\',
		evalScripts: true,
		onComplete: function(respTree,respElements, respHTML, respJavascript) {
			dataReturn_table.setProperty(\'html\',respHTML);
			eval(respJavascript);
			dataReturn_chart.setStyle(\'display\', \'none\');
			dataReturn_table.setStyle(\'display\', \'block\');
		}
		}).send();

	});


	$(\'cpHidePoint\').addEvent( \'click\', function(evt){
		new Event(evt).stop();

		var dataReturn_chart = $(\'dataReturn_res\');
		var dataReturn_table = $(\'dataReturn_table\');

		new Request.HTML({
		method: \'post\',
		url: \'includes/setPrefs.inc.php?userID='.$userID.'&process=hideMeterPoints\',
		evalScripts: true,
		onComplete: function(respTree,respElements, respHTML, respJavascript) {
			dataReturn_table.setProperty(\'html\',respHTML);
			eval(respJavascript);
			dataReturn_chart.setStyle(\'display\', \'none\');
			dataReturn_table.setStyle(\'display\', \'block\');
		}
		}).send();

	});

	$(\'cpSetDefaultPoint\').addEvent( \'click\', function(evt){
		new Event(evt).stop();

		var dataReturn_chart = $(\'dataReturn_res\');
		var dataReturn_table = $(\'dataReturn_table\');


		new Request.HTML({
		method: \'post\',
		url: \'includes/setPrefs.inc.php?userID='.$userID.'&process=setDefault\',
		evalScripts: true,
		onComplete: function(respTree,respElements, respHTML, respJavascript) {
			dataReturn_table.setProperty(\'html\',respHTML);
			eval(respJavascript);
			dataReturn_chart.setStyle(\'display\', \'none\');
			dataReturn_table.setStyle(\'display\', \'block\');
		}
		}).send();

	});
	$(\'cpChangePassword\').addEvent( \'click\', function(evt){
		new Event(evt).stop();

		var dataReturn_chart = $(\'dataReturn_res\');
		var dataReturn_table = $(\'dataReturn_table\');

		new Request.HTML({
		method: \'post\',
		url: \'includes/setPassword.i.inc.php?userID='.$userID.'\',
		evalScripts: true,
		onComplete: function(respTree,respElements, respHTML, respJavascript) {
			dataReturn_table.setProperty(\'html\',respHTML);
			eval(respJavascript);
			dataReturn_chart.setStyle(\'display\', \'none\');
			dataReturn_table.setStyle(\'display\', \'block\');
		}
		}).send();
	});

function processTelephone() {
	alert(\'got function\');
};
function updateEventDates(){

	new Request.HTML({
		method: \'post\',
		url: \'includes/refreshEventDates.ajax.inc.php\',
		data: $(\'eventsForm\'),
		evalScripts: true,
		onComplete: function(respTree,respElements, respHTML, respJavascript) {
			$(\'evtBaseDateContainer\').setProperty(\'html\',respHTML);
			eval(respJavascript);
		}
		}).send();
};

/*  ===============================================================================
    FUNCTION : processHide()
    =============================================================================== */

    function processHide(){
    	var dataReturn_chart = $(\'dataReturn_res\');
    	var dataReturn_table = $(\'dataReturn_table\');

        var messageDiv = dojo.byId(\'ajaxFeedbackDiv\');
        messageDiv.innerHTML = "Processing . . . ";

    	new Request.HTML({
    		method: \'post\',
    		url: \'includes/setPrefs.inc.php?userID='.$userID.'&process=hideMeterPoints&action=hide\',
    		method: \'post\',
    		data: $(\'hidePoint\'),
    		evalScripts: true,
    		onComplete: function(respTree,respElements, respHTML, respJavascript) {
    			dataReturn_table.setProperty(\'html\',respHTML);
    			eval(respJavascript);
    			dataReturn_chart.setStyle(\'display\', \'none\');
    			dataReturn_table.setStyle(\'display\', \'block\');
                messageDiv.innerHTML = "";
    		}
    	}).send();
    }; // processHide()

/*  ===============================================================================
    FUNCTION : processShow()
    =============================================================================== */

    function processShow(){
    	var dataReturn_chart = $(\'dataReturn_res\');
    	var dataReturn_table = $(\'dataReturn_table\');

        var messageDiv = dojo.byId(\'ajaxFeedbackDiv\');
        messageDiv.innerHTML = "Processing . . . ";

    	new Request.HTML({
    		url: \'includes/setPrefs.inc.php?userID='.$userID.'&process=hideMeterPoints&action=show\',
    		method: \'post\',
    		data: $(\'showPoint\'),
    		evalScripts: true,
    		onComplete: function(respTree,respElements, respHTML, respJavascript) {
    			dataReturn_table.setProperty(\'html\',respHTML);
    			eval(respJavascript);
    			dataReturn_chart.setStyle(\'display\', \'none\');
    			dataReturn_table.setStyle(\'display\', \'block\');
                messageDiv.innerHTML = "";
    		}
    	}).send();
    }; // processShow()

/*  ===============================================================================
    FUNCTION : processDefaultAdd()
    =============================================================================== */

    function processDefaultAdd(){
    	var dataReturn_chart = $(\'dataReturn_res\');
    	var dataReturn_table = $(\'dataReturn_table\');

        var messageDiv = dojo.byId(\'ajaxFeedbackDiv\');
        messageDiv.innerHTML = "Processing . . . ";

    	new Request.HTML({
    		url: \'includes/setPrefs.inc.php?userID='.$userID.'&process=setDefault&action=add\',
    		method: \'post\',
    		data: $(\'addDefaultPoint\'),
    		evalScripts: true,
    		onComplete: function(respTree,respElements, respHTML, respJavascript) {
    			dataReturn_table.setProperty(\'html\',respHTML);
    			eval(respJavascript);
    			dataReturn_chart.setStyle(\'display\', \'none\');
    			dataReturn_table.setStyle(\'display\', \'block\');
                messageDiv.innerHTML = "";
    		}
    	}).send();
    }; // processDefaultAdd()

/*  ===============================================================================
    FUNCTION : processDefaultRemove()
    =============================================================================== */

    function processDefaultRemove(){
    	var dataReturn_chart = $(\'dataReturn_res\');
    	var dataReturn_table = $(\'dataReturn_table\');

        var messageDiv = dojo.byId(\'ajaxFeedbackDiv\');
        messageDiv.innerHTML = "Processing . . . ";

    	new Request.HTML({
    		url: \'includes/setPrefs.inc.php?userID='.$userID.'&process=setDefault&action=remove\',
    		method: \'post\',
    		data: $(\'removeDefaultPoint\'),
    		evalScripts: true,
    		onComplete: function(respTree,respElements, respHTML, respJavascript) {
    			dataReturn_table.setProperty(\'html\',respHTML);
    			eval(respJavascript);
    			dataReturn_chart.setStyle(\'display\', \'none\');
    			dataReturn_table.setStyle(\'display\', \'block\');
                messageDiv.innerHTML = "";
    		}
    	}).send(); // processDefaultRemove()
    };

/*  ===============================================================================
    FUNCTION : processChartPref()
    =============================================================================== */
    function processChartPref(){
    	var dataReturn_chart = $(\'dataReturn_res\');
    	var dataReturn_table = $(\'dataReturn_table\');

        var messageDiv = dojo.byId(\'ajaxFeedbackDiv\');
        messageDiv.innerHTML = "Processing . . . ";

    	new Request.HTML({
    		url: \'includes/setPrefs.inc.php?userID='.$userID.'&process=setDefault&action=type\',
    		method: \'post\',
    		data: $(\'defaultPointPref\'),
    		evalScripts: true,
    		onComplete: function(respTree,respElements, respHTML, respJavascript) {
    			dataReturn_table.setProperty(\'html\',respHTML);
    			eval(respJavascript);
    			dataReturn_chart.setStyle(\'display\', \'none\');
    			dataReturn_table.setStyle(\'display\', \'block\');
                messageDiv.innerHTML = "";
    		}
    	}).send();
    };	// processChartPref()

/*  ===============================================================================  */

function CaptureUpdate(control) {
    alert("Control Name=" + control.name + ", Value=" + control.value);
};


/**********  begin zip code  **********/

$(\'cpSetZip\').addEvent( \'click\', function(evt){
new Event(evt).stop();

var dataReturn_chart = $(\'dataReturn_res\');
var dataReturn_table = $(\'dataReturn_table\');

//dataReturn_chart.setHTML(\'<img src="_template/images/ticker.gif" />\');

	new Request.HTML({
		url: \'includes/setPrefs.inc.php?userID='.$userID.'&process=setZip\',
		onComplete: function(respTree,respElements, respHTML, respJavascript) {
			dataReturn_table.setProperty(\'html\',respHTML);
			eval(respJavascript);
			dataReturn_chart.setStyle(\'display\', \'none\');
			dataReturn_table.setStyle(\'display\', \'block\');
		}
	}).send();

});


	function processZip(){
		var dataReturn_chart = $(\'dataReturn_res\');
		var dataReturn_table = $(\'dataReturn_table\');

		var weatherReturn = $(\'weatherReturn_res\');
		weatherReturn.setProperty(\'html\',\'<img src="_template/images/ticker.gif" />\');

		new Request.HTML({
			url: \'includes/setPrefs.inc.php?userID='.$userID.'&process=setZip&action=zip\',
			method: \'post\',
			data: $(\'setZip\'),
			evalScripts: true,
			update:\'dataReturn_table\',
			onComplete: function(respTree,respElements, respHTML, respJavascript) {
				dataReturn_table.setProperty(\'html\',respHTML);
				dataReturn_chart.setStyle(\'display\', \'none\');
				dataReturn_table.setStyle(\'display\', \'block\');
				new Request.HTML({
					url: \'includes/setPrefs.inc.php?action=refreshWeather\',
					method: \'post\',
					data: $(\'setZip\'),
					evalScripts: true,
					onComplete: function(zipTree,zipElements, zipHTML, zipJavascript) {
						weatherReturn.setProperty(\'html\',zipHTML);
					}
				}).send();
			}
		}).send();

	};



/**********  end zip code  **********/

/*  ===============================================================
	Contact Management
	=============================================================== */

	function processMeterContacts(){
		var targetDiv = $("dataReturn_res");

		new Request.HTML({
			url: \'manageContacts.inc.php?userID='.$_SESSION['iemsID'].'&domainID='.$_SESSION['iemsDID'].'\',
			method: \'post\',
			data: $(\'meterContactsForm\'),
			onComplete: function(respTree,respElements, respHTML, respJavascript) {
				targetDiv.setProperty("html",respHTML);
				eval(respJavascript);
			}
		}).send();
	};

	function validate(action,type,inputField)
	{

		var contactValue = inputField.value;

		if(action == "Add") {
			if($(type).options[$(type).selectedIndex].value == 1) {
				type = "Email";
			} else {
				type = "Phone";
			}
		}

		if(type == "Email") {
			emailCheck = checkEmail(contactValue);
			if(emailCheck != "") {
				alert(emailCheck);
				return false;
			}else{
				return true;
			}
		}else{
			phoneCheck = checkPhone(contactValue);
			if(phoneCheck != "") {
				alert(phoneCheck);
				return false;
			}else{
				return true;
			}
		}
	}
	function processContactUpdates(vpSpin, profileObjectId, profileId, contactOwnerId, contactValueId, contactUseId, thisId){
		//alert("In processContactUpdates(" + vpSpin + ", " + profileObjectId + ", " + contactOwnerId + ", " + contactValueId + ", " + contactUseId + ")...");

		var ownerName = document.getElementById("OwnerName[" + vpSpin + "][" + profileObjectId + "][" + contactOwnerId + "]");
		if (ownerName) ownerName = escape(ownerName.value);
		var oldOwnerName = document.getElementById("OldOwnerName[" + vpSpin + "][" + profileObjectId + "][" + contactOwnerId + "]");
		if (oldOwnerName) oldOwnerName = escape(oldOwnerName.value);
		//alert("Got OwnerName of " + ownerName + "\nGot OldOwnerName  of " + oldOwnerName);

		//alert("Looking for ContactValueType ID of " + "ContactValueType[" + vpSpin + "][" + profileObjectId + "][" + contactValueId + "]");
		var contactValueType = document.getElementById("ContactValueType[" + vpSpin + "][" + profileObjectId + "][" + contactValueId + "]");
		//alert("Got ContactValueType of " + contactValueType);
		//alert("Got ContactValueType.selectedIndex of " + contactValueType.selectedIndex);
		contactValueType = contactValueType.options[contactValueType.selectedIndex].value;
		var oldContactValueType = document.getElementById("OldContactValueType[" + vpSpin + "][" + profileObjectId + "][" + contactValueId + "]").value;
		//alert("Got ContactValueType of " + contactValueType + "\nGot OldContactValueType of " + oldContactValueType);

		//alert("Looking for ContactValueSubtype ID of " + "ContactValueSubtype[" + vpSpin + "][" + profileObjectId + "][" + contactValueId + "]");
		var contactValueSubtype = document.getElementById("ContactValueSubtype[" + vpSpin + "][" + profileObjectId + "][" + contactValueId + "]");
		//alert("Got ContactValueSubtype of " + contactValueSubtype);
		//alert("Got ContactValueSubtype.selectedIndex of " + contactValueSubtype.selectedIndex);
		contactValueSubtype = contactValueSubtype.options[contactValueSubtype.selectedIndex].value;
		var oldContactValueSubtype = document.getElementById("OldContactValueSubtype[" + vpSpin + "][" + profileObjectId + "][" + contactValueId + "]").value;
		//alert("Got ContactValueSubtype of " + contactValueSubtype + "\nGot OldContactValueSubtype of " + oldContactValueSubtype);

		//alert("Looking for Priority ID of " + "Priority[" + vpSpin + "][" + profileObjectId + "][" + contactValueId + "]");
		var priority = document.getElementById("Priority[" + vpSpin + "][" + profileObjectId + "][" + contactValueId + "]");
		//alert("Got Priority of " + priority);
		//alert("Got Priority.selectedIndex of " + priority.selectedIndex);
		priority = priority.options[priority.selectedIndex].value;
		var oldPriority = document.getElementById("OldPriority[" + vpSpin + "][" + profileObjectId + "][" + contactValueId + "]").value;
		//alert("Got Priority of " + priority + "\nGot OldPriority of " + oldPriority);

		var contactValue = escape(document.getElementById("ContactValue[" + vpSpin + "][" + profileObjectId + "][" + contactValueId + "]").value);
		var oldContactValue = escape(document.getElementById("OldContactValue[" + vpSpin + "][" + profileObjectId + "][" + contactValueId + "]").value);
		//alert("Got ContactValue of " + contactValue + "\nGot OldContactValue of " + oldContactValue);

		//alert("Looking for Status ID of " + "CvStatus[" + vpSpin + "][" + profileObjectId + "][" + contactValueId + "]");
		var cvStatus = document.getElementById("CvStatus[" + vpSpin + "][" + profileObjectId + "][" + contactValueId + "]");
		//alert("Got Status of " + cvStatus);
		//alert("Got cvStatus of " + cvStatus.selectedIndex);
		cvStatus = cvStatus.options[cvStatus.selectedIndex].value;
		var oldCvStatus = document.getElementById("OldCvStatus[" + vpSpin + "][" + profileObjectId + "][" + contactValueId + "]").value;
		//alert("Got Status of " + cvStatus + "\nGot OldStatus of " + oldCvStatus);

		var targetDiv = $("dataReturn_res");

		//alert("ownerName=" + ownerName + "\nContactValue=" + contactValue + "\nIsInactive=" + isInactive);

		new Request.HTML({
			url: \'manageContacts.inc.php?update=true&ownerName=\' + ownerName + \'&oldOwnerName=\' + oldOwnerName + \'&contactValueId=\' + contactValueId + \'&contactValue=\' + contactValue + \'&oldContactValue=\' + oldContactValue + \'&contactValueType=\' + contactValueType + \'&oldContactValueType=\' + oldContactValueType + \'&contactValueSubtype=\' + contactValueSubtype + \'&oldContactValueSubtype=\' + oldContactValueSubtype + \'&priority=\' + priority + \'&oldPriority=\' + oldPriority + \'&cvStatus=\' + cvStatus + \'&oldCvStatus=\' + oldCvStatus + \'&profileObjectId=\' + profileObjectId + \'&profileId=\' + profileId + \'&contactUseId=\' + contactUseId + \'&userID='.$_SESSION['iemsID'].'&domainID='.$_SESSION['iemsDID'].'\',
			onComplete: function(respTree,respElements, respHTML, respJavascript) {
				targetDiv.setProperty("html",respHTML);
				eval(respJavascript);

				lineId = thisId.replace("updateContactValue.","");

				if(cvStatus != "DeleteFromUse" || cvStatus != "DeleteFromAll")
				{
					dojo.byId("updateMessage." + lineId).innerHTML = \'<img src="_template/images/checkmark.gif" />\';
				}
				else
				{
					dojo.byId("updateMessage." + lineId).innerHTML = "";
				}
			}
		}).send();
	};

	function processContactAdditions(vpSpin, profileObjectId, contactUseId){
		//alert(\'In processContactAdditions(\' + vpSpin + \', \' + profileObjectId + \', \' + contactUseId + \')...\');

		var ownerName = escape(document.getElementById("OwnerName[" + vpSpin + "][" + profileObjectId + "]").value);
		var contactValue = escape(document.getElementById("ContactValue[" + vpSpin + "][" + profileObjectId + "]").value);

		var contactTypes = document.getElementById("CvType[" + vpSpin + "][" + profileObjectId + "]");
		var contactType = contactTypes.options[contactTypes.selectedIndex].value;

		var contactSubtypes = document.getElementById("CvSubtype[" + vpSpin + "][" + profileObjectId + "]");
		var contactSubtype = contactSubtypes.options[contactSubtypes.selectedIndex].value;

		var priorities = document.getElementById("Priority[" + vpSpin + "][" + profileObjectId + "]");
		//alert("Got priorities of " + priorities);
		//alert("Got priorities of " + priorities.selectedIndex);
		var priority = priorities.options[priorities.selectedIndex].value;
		//alert("Got priority of " + priority);

		var dpoEmail = document.getElementById("DenyPriorityOneEmail[" + vpSpin + "][" + profileObjectId + "]").value;
		var dptEmail = document.getElementById("DenyPriorityTwoEmail[" + vpSpin + "][" + profileObjectId + "]").value;
		var dpoPhone = document.getElementById("DenyPriorityOnePhone[" + vpSpin + "][" + profileObjectId + "]").value;
		var dptPhone = document.getElementById("DenyPriorityTwoPhone[" + vpSpin + "][" + profileObjectId + "]").value;

		//alert("OwnerName=" + ownerName + "\nContactType=" + contactType + "\nContactSubtype=" + contactSubtype + "\nContactValue=" + contactValue);

		var targetDiv = $("dataReturn_res");

		new Request.HTML({
			url: \'manageContacts.inc.php?add=true&ownerName=\' + ownerName + \'&CvType=\' + contactType + \'&CvSubtype=\' + contactSubtype + \'&contactValue=\' + contactValue + \'&profileObjectId=\' + profileObjectId + \'&contactUseId=\' + contactUseId + \'&dpoEmail=\' + dpoEmail + \'&dptEmail=\' + dptEmail + \'&dpoPhone=\' + dpoPhone + \'&dptPhone=\' + dptPhone + \'&priority=\' + priority + \'&userID='.$_SESSION['iemsID'].'&domainID='.$_SESSION['iemsDID'].'\',
			method: \'post\',
			data: $(\'meterContactsForm\'),
			onComplete: function(respTree,respElements, respHTML, respJavascript) {
				targetDiv.setProperty(\'html\',respHTML);
				eval(respJavascript);
			}
		}).send();
	};

	function processBasicCSVExport(formUsed,points,domain) {
		var urlString = "basicCSV.inc.php?userID='.$userID.'&domainID=" + domain;
        var sections = "";
        var sectionsToSend = [];
        var allSections = [];
 
        dojo.query(".toggleable").forEach(function(node, index, arr){
            allSections.push(escape(node.id));
            if(dojo.style(node,"display") == "block"){sectionsToSend.push(escape(node.id));}
        });
        
        if(sectionsToSend.length == 0){sectionsToSend = allSections;}

        sections = sectionsToSend.join(":"); 

        console.log(urlString + "&" + $(formUsed).toQueryString() + "&sections=" + sections);
		window.open(urlString + "&" + $(formUsed).toQueryString() + "&sections=" + sections);
	}



    function processBasicCSVExportWithProgram(points,domain,program) {
		var urlString = \'basicCSV.inc.php?userID='.$userID.'&contactProgram=\' + program + \'&domainID=\' + domain + \'&formUsed=profilesForm\';

		window.open(urlString);
        console.log(urlString);
	}


	function processTabularData(formUsed,points,csvFlag){	
        console.info( this );
		var urlString = \'tabularData.inc.php?userID='.$userID.'&pointsToUse=\' + points + \'&csvFlag=\' + csvFlag;
		if(formUsed == \'advPointsForm\')
		{
			for ( i = 0; i < $$(\'input[name=mvc]\').length;i++)
			{
				if($$(\'input[name=mvc]\')[i].checked == true)
				{
					if ( $$(\'input[name=mvc]\')[i].value == \'compare\')
					{
						$(\'presentation\').value = \'comparison\';
						selectCompareDates(document.advPointsForm.cmpSelect_id);
					}
				}
			}
		}

		if(csvFlag == true) {
			window.open(urlString + \'&\' + $(formUsed).toQueryString());
		}
		else {
			var dataReturn_chart = $(\'dataReturn_res\');
			var dataReturn_table = $(\'dataReturn_table\');                       

			var req = new Request.HTML({
			method: \'post\',
			url: urlString,
			data: $(formUsed),
			evalScripts: true,
			onComplete: function(respTree,respElements, respHTML, respJavascript) {
				//dataReturn_table.setProperty(\'html\',respHTML);
				console.log(respHTML);
				//dojo.byId("dataReturn_table").innerHTML = respHTML;
                                dojo.byId("dataReturn_table").innerHTML = "THIS IS MY AJAX RETURN";
				eval(respJavascript);
				dataReturn_chart.setStyle(\'display\', \'none\');
				dataReturn_table.setStyle(\'display\', \'block\');
				var printTip = new Tips($(\'printTip\'));
				$(\'printTip\').store(\'tip:title\', \'Full Sized Table for Printing\');
				$(\'printTip\').store(\'tip:text\', \'Upon selection, table will resize for optimal printing\');
				var exportTableTip = new Tips($(\'exportTableTip\'));
				$(\'exportTableTip\').store(\'tip:title\', \'CSV Output\');
				$(\'exportTableTip\').store(\'tip:text\', \'Upon selection, data is transformed to a CSV file format for saving or opening immediately in MS-Excel.\');

			}
		}).send();
			//dojo.style(dojo.byId("dataReturn_table"),"display","block");
			
			//console.log(dataReturn_table);
			//console.log(dojo.byId(\'dataReturn_table\').innerHTML);
		}
                
                
	};

	$(\'fetchAdvPoints\').addEvent( \'click\', function(evt){
					new Event(evt).stop();                                        
					processAdvancedForm(\''.$advPointString.'\');
				} );

	function processAdvancedForm(points){

		for ( i = 0; i < $$(\'input[name=mvc]\').length;i++)
		{
			if($$(\'input[name=mvc]\')[i].checked == true)
			{
				if ( $$(\'input[name=mvc]\')[i].value == \'export\')
				{

					fromDateString = $(\'advCSVBaseDateFrom\').value;
					toDateString = $(\'advCSVBaseDateTo\').value;

					var fromDateParts = fromDateString.split(\'-\');
					var toDateParts = toDateString.split(\'-\');

					var oFromDate = new Date();
					var oToDate = new Date();

					oFromDate.setFullYear(fromDateParts[2],fromDateParts[0]-1,fromDateParts[1]);
					oToDate.setFullYear(toDateParts[2],toDateParts[0]-1,toDateParts[1]);

					// The number of milliseconds in one day
					var ONE_DAY = 1000 * 60 * 60 * 24

					// Convert both dates to milliseconds
					var date1_ms = oToDate.getTime();
					var date2_ms = oFromDate.getTime()

					// Calculate the difference in milliseconds
					var difference_ms = Math.abs(date1_ms - date2_ms);

					// Convert back to days and return
					if(Math.round(difference_ms/ONE_DAY) > 92) //three months (2x31 + 30)
					{
                                            alert(\'Please limit your selected dates to three months (90 days).\');
					}
					else
					{
                                            processTabularData(\'advPointsForm\',points,true);
					}
				}
				else {
					if ( $$(\'input[name=mvc]\')[i].value == \'compare\')
					{
						selectCompareDates(document.advPointsForm.cmpSelect_id);
					}
					document.advPointsForm.submit();
				}



			}
		}
	};

    function toggleRetired()
    {
        var targetDiv = dojo.byId("toggleRetiredFeedback");

        // Gather up everything that our xhr call needs.
        var xhrArgs = {
            url: "includes/toggleRetired.ajax.php",
            handleAs: "text",
            //form: "assetReportsRequestForm",
            load: function(data) {
                window.location = \'index.php\';
            },
            error: function(error) {
                targetDiv.innerHTML = "<br />There was an error with your request.  Please try again.<br />If the problem persists contact <a href=\"http://help.crsolutions.us/\" target=\"_blank\" style=\"color: #FF6701; text-decoration: underline;\">CRS Helpdesk</a>.";
                dojo.style(targetDiv,"display","block");
            }
        }
        // let it fly . . .
        targetDiv.innerHTML = "Processing . . ." // Communicate the the user what\'s going on.
        dojo.xhrPost(xhrArgs);
    } // toggleRetired()
    
			</script>
		';
		//load order necessitates putting this portion of cp javascript in-line.
	}

}
?>

