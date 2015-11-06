<?php
 /**
 * tabularData.inc.php
 *
 * @package IEMS
 * @name Tabular Data
 * @author Marian C. Buford, Rearview Enterprises, Inc.
 * @copyright Copyright Conservation Resource Solutions, Inc. 2008.
 * @version 2.2
 * @access public
 *
 * @abstract Generates the page for the Tabular functionality, including CSV final output.
 *
 * @uses Connections/crsolutions.php, includes/clsInterface.inc.php, includes/clsControlPanel.inc.php
 *
 */
if (!defined('DEBUG')) define('DEBUG',FALSE);

define('APPLICATION', TRUE);
define('GROK', TRUE);
define('iEMS_PATH','');

require_once iEMS_PATH.'displayEventSummary.inc.php';    
require_once iEMS_PATH.'Connections/crsolutions.php';    
require_once iEMS_PATH.'includes/clsInterface.inc.php';    
require_once iEMS_PATH.'includes/clsControlPanel.inc.php';    

require_once iEMS_PATH.'iEMSLoader.php';    
$Loader = new iEMSLoader(); //arg: bool(true|false) enables/disables logging to iemslog.txt
							//to watch log live, from command-line: tail logpath/logfilename -f

$mdrUser = new User();

$mdrUser = $_SESSION['UserObject'];

$oControlPanel = new controlPanel;
$oInterface = new userInterface;

$connection = $mdrUser->sqlConnection(); //passing the return from the connection doc into a generic name


//$mdrUser->preDebugger($_GET);
//$mdrUser->preDebugger($_POST);

$userID = $_REQUEST['userID'];

$dateSpan = 1;

$pointArray = explode(',',$_REQUEST['pointsToUse']);

foreach($pointArray as $pointID)
{
	$selectedPoints[$pointID] = 'on';
}

if(isset($_REQUEST['formUsed'])){$formUsed = $_REQUEST['formUsed'];}
if(isset($_REQUEST['action'])){$action = $_REQUEST['action'];}
if(isset($_REQUEST['presentation'])){$selectedPresentation = $_REQUEST['presentation'];}
	else{$selectedPresentation = 'individual';}
if(isset($_REQUEST['mvc'])){$mvcSelection = $_REQUEST['mvc'];}
	else{$mvcSelection = 'multi';}

if($_GET['csvFlag'] == 'true')
{
	if($mvcSelection == 'export')
	{
		$selectedView = 'advExportCSV';
	}
	else
	{
		$selectedView = 'tabularDataCSV';
	}
}
else
{
	$selectedView = 'tabularData';
}
	
if(isset($_REQUEST['basicPoints']))
{
	$dateParts = explode('-',$_REQUEST['baseDate']);
	$month = $dateParts[0];
	$day = $dateParts[1];
	$year = $dateParts[2];
	$baseDate = $year.'-'.$month.'-'.$day;
	$baseDateArray['basic'] = $_REQUEST['baseDate'];
}
elseif(isset($_REQUEST['advPoints']))
{
   
	if(isset($_POST['dateRange']))
	{
		$selectedRange = $_POST['dateRange'];
	}
		
	if(isset($_REQUEST['cmpSelect']) && $_REQUEST['cmpSelect'] != '')
	{
		$selectedPresentation = 'comparison';
		sort($_REQUEST['cmpSelect']);
		$baseDateArray['comparison'] = $_REQUEST['cmpSelect'];
		$dateSpan = 1;
		
		foreach($_REQUEST['cmpSelect'] as $cmpIndex=>$cmpValue)
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
	elseif($mvcSelection == 'export')
	{
		$selectedPoints = $_REQUEST['advPoints'];

		$dateSet = $oControlPanel->dateSpanCalculator($_REQUEST['advCSVBaseDateFrom'],$_REQUEST['advCSVBaseDateTo'],'');
		$dateSpan = $dateSet['dateSpan'];
		$baseDate = $dateSet['baseDate'];

		$selectedPresentation = 'individual';
		$action = 'basic';
		
		$hasPrices = false;
		$hasData = false;
		$rollup = false;

		if(isset($_REQUEST['csvChoices']))
		{
			foreach($_REQUEST['csvChoices'] as $inx=>$choice)
			{
				if ($choice == 'pricing') 
				{
					$hasPrices = true;
				}
				if ($choice == 'data') 
				{
					$hasData = true;
				}
				if ($choice == 'hourlyRollup') 
				{
					$rollup = true;
				}
			}
		}
		else
		{
			$hasPrices = true;
			$hasData = true;
		}
		
		if($hasPrices == true)
		{
			$action == 'basic';
		}
		else
		{
			$action = 'advanced';
		}

		if(($hasPrices == true) && ($hasData == false))
		{
			if($rollup == true)
			{
				$selectedView = 'exportPricingHourly';
			}
			else
			{
				$selectedView = 'exportPricingFiveMinute';
			}
		}
		else
		{
			if($rollup == true)
			{
				$selectedView = 'exportTabularHourly';
			}
			else
			{
				$selectedView = 'exportTabularFiveMinute';
			}
		}
		
	}
	else
	{
		$dateSet = $oControlPanel->dateSpanCalculator($_REQUEST['advBaseDateFrom'],$_REQUEST['advBaseDateTo'],'');
		$dateSpan = $dateSet['dateSpan'];
		$baseDate = $dateSet['baseDate'];
	}

	$numberOfPoints = count($_REQUEST['advPoints']);
}
elseif(isset($_REQUEST['evtPoints']))
{
	$baseDateArray['event'] = $_REQUEST['dateUsed'];

	$dateParts = explode('-',$_REQUEST['dateUsed']);
	$month = $dateParts[0];
	$day = $dateParts[1];
	$year = $dateParts[2];

	$baseDate = $year.'-'.$month.'-'.$day;
}
else
{
	$baseDate = date('Y-m-d');
	$baseDateArray['basic'] = date('m-d-Y');
}


if(isset($_REQUEST['evtPoints']))
{   
	$selectedPresentation = 'individual';
	$action = 'eventCSV';
}

if($action == 'eventCSV')
{
    $meterSummaries = $oInterface->gatherEvent($action,$selectedPoints,$baseDate,$dateSpan,$selectedPresentation,$selectedView,$connection,$formUsed,$mdrUser, $_SESSION['evtBaseDate']);    
}
else
{
    $meterSummaries = $oInterface->gather($action,$selectedPoints,$baseDate,$dateSpan,$selectedPresentation,$selectedView,$connection,$formUsed,$_SESSION['UserObject']);	
}




if($selectedView == 'tabularData')
{
	$meterSummaries = '
		<script type="text/javascript" src="mootools/mootools-1.2-core.js"></script>
		<script type="text/javascript" src="mootools/mootools-1.2-more.js"></script>		
		'.$meterSummaries.'
		<script type="text/javascript" src="mootools/smoothbox.js"></script><!-- this is down here for internet explorer; known bug re: prematurely terminating the dom; affects versions 5-7 -->

	';
		print $meterSummaries;
}
else
{  
	

   if(DEBUG && isset($_SESSION['debugSQL'])) 
   {
       $mdrUser->preDebugger($_SESSION['debugSQL']); 
   }
   else
   {
       $savename = 'iEMS2_'.date('Y_m_d_H_i').'.csv';
       ini_set('zlib.output_compression','Off');
    
       header('Pragma: public');
       header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");                  // Date in the past   
       header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
       header('Cache-Control: no-store, no-cache, must-revalidate');     // HTTP/1.1
       header('Cache-Control: pre-check=0, post-check=0, max-age=0');    // HTTP/1.1
       header("Pragma: no-cache");
       header("Expires: 0");
       
       header('Content-Transfer-Encoding: none');
       header('Content-Type: text/css');
       header('Content-Type: application/vnd.ms-excel;');                 // This should work for IE & Opera
       header("Content-type: application/x-msexcel");                    // This should work for the rest
       header('Content-Disposition: attachment; filename="iEMS2_'.date('Y_m_d_H_i').'.csv"');
   }
   


   
   print $meterSummaries; // uhm . . . doesn't seem to be working for events so I'm printing out from the event summary csv function

}

?>

