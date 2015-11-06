
<?php
    $pageStartTime = microtime(true);
 /**
 * index.php
 *
 * @package IEMS
 * @name Index
 * @author Marian C. Buford, Rearview Enterprises, Inc.
 * @copyright Copyright Conservation Resource Solutions, Inc. 2008.
 * @version 2.0
 * @access public
 *
 * @abstract Where it all begins.<br />This handles the initial login functionality and any preliminary validation prior to calling the rendering and query classes.
 *
 *
 * @uses Connections/crsolutions.php, includes/clsInterface.inc.php, includes/clsControlPanel.inc.php, includes/mdr.php
 *
 */
 
//************* BIG FAT REFACTOR NOTE *************//
//  A huge chunk of this is being duplicated in    //
//  tabularData.inc.php as there is duplicate      //
//  functionality between the two -- refacgtoring  //
//  should involve ajaxing the whole ball of wax   //
//  and consolidating.                             //
//*************************************************//

//Sets flag which is checked by objects to limit them to being called from a page
//with this flag set. Objects will not run without this flag.


define('APPLICATION', TRUE);
define('GROK', TRUE);
define('iEMS_PATH','');

require_once iEMS_PATH.'Connections/crsolutions.php';
require_once iEMS_PATH.'includes/clsInterface.inc.php';
require_once iEMS_PATH.'includes/clsControlPanel.inc.php';
require_once iEMS_PATH.'manageContacts.inc.php';
require_once iEMS_PATH.'displayReports.inc.php';
require_once iEMS_PATH.'displayEventSummary.inc.php';
require_once iEMS_PATH.'displayStatistics.inc.php';

require_once iEMS_PATH.'iEMSLoader.php';

$server = ' :: '.strtoupper(php_uname('n')).' | '.strtoupper(DSN);

//this is handy for turning logging on for a refresh or two
//otherwise, go to iEMSLoader.php and turn it on there for long
//sessions where logging is handy.
//http://dev2.crsolutions.us/iEMS2.2/index.php?logged
$logged = true;

$Loader = new iEMSLoader($logged); 
if($logged) $log = new Logging(); 

/** ****************************************************************
***                Variable & Object Declaration                 ***
**************************************************************** **/

$connection = $crsolutions; //passing the return from the local connection doc into a generic name
$master_connection = $crs_master; //passing the return from the master connection doc into a generic name
$url = 'http://php.crsolutions.us/';
//$hostingServer = $_SERVER['HTTP_HOSTar'];
$titleString = 'Welcome to iEMS v.2.2';

$oInterface = new userInterface;
$oControlPanel = new controlPanel;
$mdrUser = null;
// at some point, stuff these into a little function 'cause reset buttons use these also
$userString = '';
$logoutString = '';
$meterSummaries = '';
$messageString = '';
$ticker = '';
$defaultPresentation = 'individual';
$alternatePresentation = 'allInOne';
$selectedPresentation = $defaultPresentation;
$dateSpan = 1;
$selectedRange = '';
$repSelectedRange = '';
$dataString = '';
$userTable = '';
$action = '';
$includesHead = '';
$includesFoot = '';
$formUsed = 'pointsForm';
$selectedView = 'charts';
$username = '';
$password = '';
$numberOfPoints = 0;

$uxDay = 60 * 60 * 24;
$ux30DayMonth = $uxDay * 30;

$baseDateArray['basic'] = date('m-d-Y');
$baseDateArray['advancedFrom'] = date('m-d-Y');
$baseDateArray['advancedTo'] = date('m-d-Y');
$baseDateArray['event'] = date('m-d-Y');
$baseDateArray['comparison'] = array();
$baseDateArray['advancedCSVFrom'] = date('m-d-Y',date('U') - $ux30DayMonth);
$baseDateArray['advancedCSVTo'] = date('m-d-Y');
$baseDateArray['uptimeFrom'] = date('m-d-Y');
$baseDateArray['uptimeTo'] = date('m-d-Y');
$baseDateArray['reportFrom'] = date('m-d-Y');
$baseDateArray['reportTo'] = date('m-d-Y');
$baseDateArray['evtSummaryDate'] = date('m-d-Y');

$selectedPoints['basic'] = '';
$selectedPoints['advanced'] = '';
$selectedPoints['event'] = '';
$selectedPoints['reports'] = '';
$selectedPoints['time'] = '';

$selectedProfiles = '';

$mvcSelection = 'Multi';
$eventDisclaimerMessage = '';
$eventDisclaimer = '<div class="error" style="width: 750px; margin-left: 200px;">Baselines displayed do not <strong>currently</strong> include corrected data submitted to ISO-NE.<br />They provide indicative guidance only  and should not be used for financial settlement purposes.</div>';
//$eventDisclaimer = '<div class="error" style="width: 750px; margin-left: 200px; color: #ffdd22; font-weight: bold;">Event Performance Summary data is not currently accurate.<br />This will be fixed in a post-event maintenance shutdown.<br />Please use the display charts under "Select New Meter" button for guidance on performance.</div>';


/** ***********************************************************
***         Checking for Authentication Status              ***
*********************************************************** **/


unset($_SESSION['viewEventSummary']);
/*
if(isset($_REQUEST['logout']))
{
    unset($_SESSION['UserObject']);
	unset($_SESSION['iemsName']);
	unset($_SESSION['iemsID']);
	unset($_SESSION['iemsDID']);
	unset($_SESSION['iemsPW']);
	unset($_SESSION['basicSelection']);
	unset($_SESSION['eventSelection']);
	unset($_SESSION['formUsed']);
    unset($_SESSION['currentSelection']);
    header('Location:index.php'); 
     
}
*/
if(empty($_SESSION['iemsID']))
{
/** ***********************************************************
***         offering authentication opportunity             ***
*********************************************************** **/
    header('location: login.php');
}

if(!empty($_SESSION['iemsID']))
{

	$username = $_SESSION['iemsName'];
	$userID = $_SESSION['iemsID'];
	$domainID = $_SESSION['iemsDID'];
	$password = $_SESSION['iemsPW'];

    $mdrUser = new User();

    if (empty($_SESSION['UserObject'])) {
        //echo "INDEX: In UserObject empty...<br>\n";
        $mdrUser->Login($username, $password);
        $_SESSION['UserObject'] = $mdrUser;
       
    } else {
        //echo "INDEX: In UserObject NOT empty...<br>\n";
        $mdrUser = $_SESSION['UserObject'];
    }
//$mdrUser->preDebugger($_SESSION['iemsID']);

if (isset($_GET['action'])) {
    //echo "index: Refreshing the user object...<br>\n";
    $mdrUser = new User();
	$mdrUser->Login($username, $password);

    $_SESSION['UserObject'] = $mdrUser;
}

	//manual entries that need to be replaced
	$index = 0;

/** ****************************************************************
***                       Page Preparation                       ***
**************************************************************** **/
//$mdrUser->preDebugger($_SERVER);
    $logFlag = $logged ? '&logged' : '';
	$logoutString = '<a href="logout.php" style="font-weight: bold;">:: Log Out ::</a>';
	

	$zipcode = $mdrUser->primaryZipCode();

	
	$prefs = $oControlPanel->gatherDefaultPresentation($connection, $mdrUser);
	$sp = $oControlPanel->gatherDefaultPoints($connection,$mdrUser);
	
	if(isset($_POST['presentation']) && $_POST['presentation'] != 'comparison')
	{
		$selectedPresentation = $_POST['presentation'];
	}
	else
	{
		if($prefs['user']['AlternateChartPresentation'] != '')
		{
			$selectedPresentation = $alternatePresentation;
		}
		else
		{
			$selectedPresentation = $defaultPresentation;
		}
	}

    if (isset($_POST['report'])) 
    {
        $selectedReport = $_POST['report'];
    }
    else
    {
        $selectedReport = '';
    }

	if(isset($_POST['action'])){$action = $_POST['action'];}
	if(isset($_POST['view'])){$selectedView = $_POST['view'];}
    if(isset($_POST['formUsed'])){$formUsed = $_POST['formUsed'];}
	if(isset($_POST['mvc'])){$mvcSelection = $_POST['mvc'];}
		else{$mvcSelection = 'multi';}
	
	if(isset($_POST['basicPoints'])){$selectedPoints['basic'] = $_POST['basicPoints'];}
		else{$selectedPoints['basic'] = $sp;}
	if(isset($_POST['advPoints'])){$selectedPoints['advanced'] = $_POST['advPoints'];}
		else{$selectedPoints['advanced'] = $sp;}
	if(isset($_POST['evtPoints'])){$selectedPoints['event'] = $_POST['evtPoints'];}
		else{$selectedPoints['event'] = $sp;}
	if(isset($_POST['repPoints'])){$selectedPoints['reports'] = $_POST['repPoints'];}
		else{$selectedPoints['reports'] = $sp;}
	if(isset($_POST['uptPoints'])){$selectedPoints['time'] = $_POST['uptPoints'];}
		else{$selectedPoints['time'] = $sp;}
		
	if(isset($_POST['basicProfiles'])){$selectedContactProfiles = $_POST['basicProfiles'];}
		else{$selectedContactProfiles = '';}

	if(isset($_POST['fetchPoints']) && isset($_POST['basicPoints']))
	{
		$dateParts = explode('-',$_POST['baseDate']);
		$month = $dateParts[0];
		$day = $dateParts[1];
		$year = $dateParts[2];
		$baseDate = $year.'-'.$month.'-'.$day;
        $baseDateArray['basic'] = $_POST['baseDate'];
    }
	elseif(isset($_POST['advPoints']))
	{
		//control panel needs these two values
		if(isset($_POST['advBaseDateFrom']))
		{
			$baseDateArray['advancedFrom'] = $_POST['advBaseDateFrom'];
			$baseDateArray['advancedTo'] = $_POST['advBaseDateTo'];
		}
		else
		{
			$baseDateArray['advancedFrom'] = date('m-d-Y');
			$baseDateArray['advancedTo'] = date('m-d-Y');
		}

		if(isset($_POST['advCSVBaseDateFrom']))
		{
			$baseDateArray['advancedCSVFrom'] = $_POST['advCSVBaseDateFrom'];
			$baseDateArray['advancedCSVTo'] = $_POST['advCSVBaseDateTo'];
		}
		else
		{
			$baseDateArray['advancedCSVFrom'] = date('m-d-Y');
			$baseDateArray['advancedCSVTo'] = date('m-d-Y');
		}
		
		if(isset($_POST['cmpSelect']) && $_POST['cmpSelect'] != '')
		{
			//klk
			//this is where we're stacking up the dates.  Two arrays:
			//baseDateArray is needed by the control panel -- it holds the 'pretty' dates
			//here we turn $baseDate into an array to contain the individual date values 
			//that comparison needs.
			$selectedPresentation = 'comparison';
			sort($_POST['cmpSelect']);
			$baseDateArray['comparison'] = $_POST['cmpSelect'];
			$dateSpan = 1;
			
			foreach($_POST['cmpSelect'] as $cmpIndex=>$cmpValue)
			{
				$dateParts = explode('-',$cmpValue);
				$month = $dateParts[0];
				$day = $dateParts[1];
				$year = $dateParts[2];
				$date = $year.'-'.$month.'-'.$day;
				$cmpDateArray[$cmpIndex] = $date;
			}
			$baseDate = $cmpDateArray;
		}
		else
		{
			$dateSet = $oControlPanel->dateSpanCalculator($baseDateArray['advancedFrom'],$baseDateArray['advancedTo'],'');
			$dateSpan = $dateSet['dateSpan'];
			$baseDate = $dateSet['baseDate'];
		}

		$selectedPoints['advanced'] = $_POST['advPoints'];
		$numberOfPoints = count($_POST['advPoints']);
	}
	elseif(isset($_POST['fetchEvents']) && isset($_POST['evtPoints']))
	{   
		$selectedPoints['event'] = $_POST['evtPoints'];

        if(isset($_POST['evtBaseDate']))
		{
			$baseDateArray['event'] = $_POST['evtBaseDate'];
			$dateParts = explode('-',$_POST['evtBaseDate']);
			$month = $dateParts[0];
			$day = $dateParts[1];
			$year = $dateParts[2];
	
			$baseDate = $year.'-'.$month.'-'.$day;
		}
		else
		{
			$baseDateArray['event'] = '';
			$baseDate = date('Y-m-d');
		}

	}
    elseif(isset($_POST['fetchReports']))
    {
		if(isset($_POST['dateRange']))
		{
			$selectedRange = $_POST['dateRange'];
		}
		
		if(isset($_POST['repDateRange']))
		{
			$repSelectedRange = $_POST['repDateRange'];
		}

		if(isset($_POST['repBaseDateFrom']))
		{
			$baseDateArray['reportFrom'] = $_POST['repBaseDateFrom'];
			$baseDateArray['reportTo'] = $_POST['repBaseDateTo'];
		}
		else
		{
			$baseDateArray['reportFrom'] = date('m-d-Y');
			$baseDateArray['reportTo'] = date('m-d-Y');
		}

        $baseDate = $baseDateArray['reportFrom'];
    }
    elseif(isset($_POST['fetchStatistics']))
    {
		if(isset($_POST['uptBaseDateFrom']))
		{
			$baseDateArray['uptimeFrom'] = $_POST['uptBaseDateFrom'];
			$baseDateArray['uptimeTo'] = $_POST['uptBaseDateTo'];
		}
		else
		{
			$baseDateArray['uptimeFrom'] = date('m-d-Y');
			$baseDateArray['uptimeTo'] = date('m-d-Y');
		}
    }
    elseif(isset($_POST['fetchEventSummary']))
    {
        if (isset($_POST['evtSummaryDate']))
        {
            $baseDateArray['evtSummaryDate'] = $_POST['evtSummaryDate'];

			$dateParts = explode('-',$_POST['evtSummaryDate']);
			$month = $dateParts[0];
			$day = $dateParts[1];
			$year = $dateParts[2];
	
			$baseDate = $year.'-'.$month.'-'.$day;
        }
        else
        {
            $baseDateArray['evtSummaryDate'] = '';
            $baseDate = date('Y-m-d');
        }
    }
	else
    {
		$baseDate = date('Y-m-d');
		$dateSpan = 1;
		$baseDateArray['basic'] = date('m-d-Y');
	}

	if(isset($_POST['clear']))
	{
		//resetting to defaualt values; tempting to just unset the $_POST but we're wanting to continue carrying the userid value and any others, so we'll do this manually.
		$baseDate = date('Y-m-d');
		$dateSpan = 1;
		
		unset($_POST['points']);
		unset($_POST['presentation']);
		if($prefs['user']['AlternateChartPresentation'] != '')
		{
			$selectedPresentation = $alternatePresentation;
		}

		unset($_POST['view']);
		$selectedView = 'charts';
        $baseDateArray['basic'] = date('m-d-Y');
        $baseDateArray['advancedFrom'] = date('m-d-Y');
        $baseDateArray['advancedTo'] = date('m-d-Y');
        $baseDateArray['event'] = date('m-d-Y');
        $baseDateArray['cmpSelect'] = '';
		$baseDateArray['evtSummaryDate'] = date('m-d-Y');

        $baseDateArray['advancedCSVFrom'] = date('m-d-Y',date('U') - $ux30DayMonth);
        $baseDateArray['advancedCSVTo'] = date('m-d-Y');
        $sp = $oControlPanel->gatherDefaultPoints($connection,$mdrUser);
        $selectedPoints['basic'] = $sp;
        $selectedPoints['advanced'] = $sp;
        $selectedPoints['event'] = $sp;
        $selectedPoints['reports'] = $sp;
        $action = '';
        $formUsed = 'pointsForm';
    }
	
	
	
//validation to determine if we're ready to send to gather()

    if(
       (isset($_POST['fetchPoints']) && empty($selectedPoints['basic'])) && 
       (empty($selectedPoints['advanced']) || $selectedPoints['advanced'] == '') && 
       (isset($_POST['fetchEvents']) && empty($selectedPoints['event']))
       )
	{
		$meterSummaries = '<div class="error" style="width: 700px;">You must specify a Default Meter under Set Preferences.</div>';
	}
	elseif(((count($selectedPoints['basic']) > 6) && isset($_POST['fetchPoints'])) | ((count($selectedPoints['advanced']) > 6)) | ((count($selectedPoints['event']) > 6) && isset($_POST['fetchEvents'])))
	{
		$meterSummaries = '<div class="error" style="width: 700px;">Please limit your selection to fewer than six (6) meters.</div>';
	}
	else
	{
		if(isset($_POST['fetchPoints']))
		{
            //mcb 2010.06.29 if($baseDate == date('Y-m-d') && ($_SESSION['formUsed'] == 'pointsForm' || $_SESSION['formUsed'] == 'eventsForm') && isset($_SESSION['currentSelection']))
            //{
                $meterStartTime = microtime(true);
    			$meterSummaries = 	'<!-- start meter summaries -->'.
    								$oInterface->gather($action,$selectedPoints['basic'],$baseDate,$dateSpan,$selectedPresentation,$selectedView,$connection,$formUsed,$mdrUser).
    								'<!-- end meter summaries | time to render gather() '.(microtime(true) - $meterStartTime).' -->';
    			$_SESSION['currentSelection'] = $selectedPoints['basic'];
            //}
			
		}
		elseif(isset($_POST['mvc']) || isset($_POST['fetchComparison']))
		{	
			if($numberOfPoints > 1 && $selectedPresentation == 'comparison')
			{
				$meterSummaries = '<div class="error" style="width: 700px;">Please limit your selection to a single point.</div>';
			}
			else
			{   
				$meterStartTime = microtime(true);
				$meterSummaries = 	'<!-- start meter summaries -->'.
									$oInterface->gather($action,$selectedPoints['advanced'],$baseDate,$dateSpan,$selectedPresentation,$selectedView,$connection,$formUsed,$mdrUser).
									'<!-- end meter summaries | time to render gather() '.(microtime(true) - $meterStartTime).' -->';	
			}
		}
		elseif(isset($_POST['fetchEvents']))
		{  
            if(isset($_POST['evtBaseDate'])) {
                    $_SESSION['evtBaseDate'] = $_POST['evtBaseDate'];
                    $eventDisclaimerMessage = $eventDisclaimer;

                    $selectedPresentation = 'individual';
                    $meterStartTime = microtime(true);            
                    $meterSummaries = 	'<!-- start meter summaries -->'.
                                        $oInterface->gatherEvent($action,$selectedPoints['event'],$baseDate,$dateSpan,$selectedPresentation,$selectedView,$connection,$formUsed,$mdrUser, $_POST['evtBaseDate']).
                                        '<!-- end meter summaries | time to render gather() '.(microtime(true) - $meterStartTime).' -->';	
            }
            								
			$_SESSION['currentSelection'] = $selectedPoints['event'];			
		}
        elseif(isset($_POST['fetchEventSummary']))
        {
            $_SESSION['viewEventSummary'] = "1";
			$_SESSION['evtSummaryDate'] = $baseDateArray['evtSummaryDate'];
            $eventDisclaimerMessage = $eventDisclaimer;
			//$mdrUser->preDebugger($baseDateArray);
			$meterStartTime = microtime(true);

            $meterSummaries = 	'<!-- start meter summaries -->'.
								viewEventSummary($_SESSION['iemsDID'], $baseDateArray['evtSummaryDate'], false).
								'<!-- end meter summaries | time to render gather() '.(microtime(true) - $meterStartTime).' -->';									
        }
        elseif(isset($_POST['fetchStatistics']))
        {
			$meterStartTime = microtime(true);
            $meterSummaries = 	'<!-- start meter summaries -->'.
								viewStatistics($userID, $domainID, false).
								'<!-- end meter summaries | time to render gather() '.(microtime(true) - $meterStartTime).' -->';
        }
        elseif(isset($_POST['fetchProfiles']))
        {
			$meterStartTime = microtime(true);
           $meterSummaries = 	'<!-- start meter summaries -->'.
		   						viewProfiles($userID, $domainID).
								'<!-- end meter summaries | time to render gather() '.(microtime(true) - $meterStartTime).' -->';
        }
        elseif(isset($_POST['viewContactReport']))
        {
			$meterStartTime = microtime(true);
           $meterSummaries = 	'<!-- start meter summaries -->'.
		   						viewContactReport($userID, $domainID, false, isset($_POST['contactProgram'])?$_POST['contactProgram']:'').
								'<!-- end meter summaries | time to render gather() '.(microtime(true) - $meterStartTime).' -->';
        }
        elseif(isset($_POST['fetchReports']))
        {
			$meterStartTime = microtime(true);
            $meterSummaries = 	'<!-- start meter summaries -->'.
								viewReports($userID, $domainID).
								'<!-- end meter summaries | time to render gather() '.(microtime(true) - $meterStartTime).' -->';
        }
		else
		{
			if($selectedPoints['basic'] == '' && $selectedPoints['advanced'] == '' && $selectedPoints['event'] == '')
			{
				$meterSummaries = '<div class="error" style="width: 700px;">Please access the Set Preferences area of the Control Panel to select a default meter.</div>';
			}
			else
			{
				$meterStartTime = microtime(true);
				$meterSummaries = 	'<!-- start meter summaries -->'.
									$oInterface->gather($action,$selectedPoints['basic'],$baseDate,$dateSpan,$selectedPresentation,$selectedView,$connection,$formUsed,$mdrUser).
									'<!-- end meter summaries | time to render gather() '.(microtime(true) - $meterStartTime).' -->';
				$_SESSION['currentSelection'] = $selectedPoints['basic'];
			}
		}
	}

	//mcb 2008.05.15 setting up some session variables and flags for automatically refreshing basic and event forms, if date used is today.

	$_SESSION['action'] = $action; 
	$_SESSION['baseDate'] = isset($baseDate) ? $baseDate : date('Y-m-d');
	$_SESSION['dateSpan'] = $dateSpan;
	$_SESSION['selectedPresentation'] = $selectedPresentation;
	$_SESSION['selectedView'] = $selectedView;
    //echo "formUsed='", $formUsed, "'<<br>\n";
	$_SESSION['formUsed'] = $formUsed;	
	/*
	ajaxThing = new Ajax("refresh.inc.php?detectflash=false").request();
	
	onComplete: function() {
						dataReturn_chart.setStyle(\'display\', \'none\')
						dataReturn_table.setStyle(\'display\', \'block\');
						
					}
					
										dataReturn_chart.innerHTML = eval(ajaxThing);
										dataReturn_chart.innerHTML = eval(responseText);
	*/
	$includesHead = '
		<script type="text/javascript" src="mootools/mootools-1.2-core.js"></script>
		<script type="text/javascript" src="mootools/mootools-1.2-more.js"></script>

		<script type="text/javascript" src="mootools/calendar/calendar.compat.js"></script>
		<script type="text/javascript" src="includes/scrValidate.js"></script>
		<script type="text/javascript" src="mootools/formcheck_1.4/formcheck.js"></script>

		<script type="text/javascript">            

			function CheckIsIE()
			{
				if (navigator.appName.toUpperCase() == \'MICROSOFT INTERNET EXPLORER\') { return true;}
				else { return false; }
			}
			
			
			function PrintThisPage()
			{
				if (CheckIsIE() == true)
				{
					document.magnifyFrame.focus();
					document.magnifyFrame.print();
				}
				else
				{
					window.frames[\'magnifyFrame\'].focus();
					window.frames[\'magnifyFrame\'].print();
				}
			}

		</script>
	';
	

    //$includesHead = $mdrUser->Dump() . $includesHead;

    //echo "_SESSION['formUsed']='", $_SESSION['formUsed'], "'<br>\n";
    //echo "baseDate='", $baseDate, "'<br>\n";
    $baseDate = isset($baseDate) ? $baseDate : date('Y-m-d');
	if($baseDate == date('Y-m-d') && ($_SESSION['formUsed'] == 'pointsForm' || $_SESSION['formUsed'] == 'eventsForm') && isset($_SESSION['currentSelection']))
	{
		$includesHead .= '		
			<script type="text/javascript">
                window.addEvent("domready", function(){				
                    refreshChart();
                });
                    
					var t;
					
					function refreshChart(){
						
						var c=0;						
                        
						var dataReturn_chart = dojo.byId("dataReturn_res");
						
						var req = new Request.HTML({												   
							method: "get",
							url: "refresh.inc.php",							
							onComplete: function(respTree,respElements, respHTML, respJavascript) {								
								dataReturn_chart.innerHTML = respHTML;
								eval(respJavascript);
							}
							
						}).send();
						
						t=setTimeout("refreshChart()",300000);
					}	
				
				</script>
				';
	}


	$includesFoot = '	
		<script type="text/javascript" src="mootools/crs-controlPanel.js"></script>		
		<script type="text/javascript" src="mootools/smoothbox.js"></script><!-- this is down here for internet explorer; known bug re: prematurely terminating the dom; affects versions 5-7 -->
        
	';
	
	//<script type="text/javascript" src="meterPrefsFix.js"></script>		

	/*
	print '<pre>';
	print_r($_SESSION);
	print '</pre>';
	*/
    //echo "index: crsolutions='" . $crsolutions . "', crs_master='" . $crs_master . "'<br>\n";
    //echo "index: connection='" . $connection . "', master_connection='" . $master_connection . "'<br>\n";
	
	$userString .= $oControlPanel->panel($baseDateArray,$dateSpan,$mdrUser,$connection,$master_connection, $selectedPoints,$selectedContactProfiles,$selectedView,$selectedPresentation,$selectedReport,$baseDateArray['comparison'],$selectedRange,$repSelectedRange,$action,$mvcSelection); 
	$userTable = '<h1>'.$titleString.'</h1>';
    $userTable .= '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td>';
	$userTable .= $oInterface->usertable($mdrUser, $username, $logoutString, $mdrUser->fullName(), $mdrUser->Domains($index)->description(), $mdrUser->primaryEmailAddress(), $mdrUser->primaryPhoneNumber());

	
/********************
 ****** WEATHER *****
 ********************/
	// require_once 'includes/weather.inc.php';
	//$weather = new weather;

/*
	$userTable .= '
		<td width="150">
			<table width="150" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td style="text-align: center;">
					<div id="weatherReturn">
//$weather->Load($zipcode)

					</div>
					</td>
			  	</tr>
			</table>
		</td>
	';	
*/
	$userTable .= '</td>
		<td width="150">
			<table width="150" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td style="text-align: center;">
                            <img src="' . $mdrUser->lseDomain()->logoPath() . '" width="114" height="45" style="border: 1px solid #FFF;"/><br /><br />
                    
					<div id="weatherReturn">
						<div id="weatherReturn_res">'.$zipcode.'</div>
					</div>
					</td>
			  	</tr>
			</table>
		</td>
     </tr>
 </table>
	';	

/********************/
    $tickerArray = $mdrUser->lseDomain()->tickerMessages();
    $tickerMessage = '';

	//$tickerMessage = $tickerArray['TickerMessage'].'                                                                                            ';	//adding these spaces allows the ticker to scroll completely off -- definitely a kludge.

	$ticker = '
		<table align="center" width="600" cellpadding="0" cellspacing="0" border="0"  >
			<tr>
				<td >
                <div style="color: #33FFFF;">
				'.$tickerMessage.'
                </div>
				</td>
			</tr>
		</table>
	';
		
}
$userString = '
	<td width="200">
		'.$userString.'
	</td>
	<td>
	<div id="user">
		'.$userTable.'
	</div>
	</td>
';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!-- forcing ie8 into compatibility mode here -->
<meta http-equiv="X-UA-Compatible" content="IE=7.5" >
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $titleString.$server; ?></title>

<link href="_template/crs_php.inc.css" rel="stylesheet" type="text/css" />
<link href="_template/crs_php_cal.inc.css" rel="stylesheet" type="text/css" />
<link href="_template/crs_php_cp.inc.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="_template/crs_php_smoothbox.css" type="text/css" media="screen" />

<script type="text/javascript" src="mootools/sorttable/sorttable.js"></script>

    <!-- Added with 2.2 -->        
<link rel="stylesheet" href="js/thejekels/themes/nihilo/thejekels.css" />
<link href="js/dijit/themes/nihilo/nihilo.css" rel="stylesheet" type="text/css" />         
<link href="js/dojox/form/resources/FileInput.css" rel="stylesheet" type="text/css" />         


<script type="text/javascript"> 
    djConfig = {
        isDebug: false, 
        parseOnLoad: true
        }; 
</script>

<script type="text/javascript" src="js/dojo/dojo.js"></script>
<script type="text/javascript" src="js/dijit/dijit.js"></script>
<script type="text/javascript" src="js/iems/assetModelling.js"></script>

<!-- / 2.2 add -->


<?php echo $includesHead; ?>
<style type="text/css">
.contactUpdated {
    background: transparent url('_template/images/checkmark.gif') no-repeat top left;
                }
</style></head>
<body class="nihilo">        
    <div id="canvas">
        <div id="wrapper">            
    		<table align="center" width="100%" cellpadding="0" cellspacing="0" border="0">
    		    <tr>
    		        <?php echo $userString; ?>
    		    </tr>
    		</table>
            <?php echo $eventDisclaimerMessage; ?>
            <div id="tickerContainer"><?php 
                                            if(is_object($mdrUser))
                                            {
                                                if($mdrUser->isLseUser()) require_once('calledNotice.php'); 
                                            }
											
                                      ?></div>
            <div>
        		<table align="right" cellpadding="0" cellspacing="0" border="0">
            		<tr>
                		<td style="padding-right: 30px;">
                			<div id="dataReturn">
                                <div id="ajaxFeedbackDiv" style="text-align: right;"></div>
                    			<div id="dataReturn_res" style="text-align: left;"><?php print $meterSummaries; ?></div>
                    			<div id="dataReturn_table" style="display: none; "></div>
                                <?php //require_once('assetModelling/fileUploader.html'); ?>
                			</div>
                		</td>
            		</tr>
        		</table>
    	    </div>
            
           
    	<br /><br />
        </div>
        &copy; Copyright Conservation Resource Solutions, Inc. All rights reserved. Site Design by <a href="http://www.rvadv.com" target="_blank">Rearview</a>. 
    </div>
<?php 
	  //$mdrUser->preDebugger($_SESSION['UserObject']->PointChannels()->participationTypeList());
      //$mdrUser->preDebugger($_POST); 
      //$mdrUser->preDebugger($_COOKIE['dsn']); 
      //$mdrUser->preDebugger($_SESSION); 
?>

</body>
<?php echo $includesFoot; ?>
<?php
if(!empty($_SESSION['iemsID']))
{
	require_once 'includes/weather.inc.php';
	$weather = new weather;
	
	echo "
		<script type=\"text/javascript\">\n        
		$('weatherReturn_res').setProperty('html','".$weather->Load($zipcode)."');\n
		</script>
	";
}


?>
<?php 	echo '<!-- end page | time to render: '.(microtime(true) - $pageStartTime).'-->'; ?>
