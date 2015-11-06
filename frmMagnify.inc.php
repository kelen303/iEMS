<?php
 /**
 * frmMagnify.inc.php
 *
 * @package IEMS
 * @name Magnify
 * @author Marian C. Buford, Rearview Enterprises, Inc.
 * @copyright Copyright Conservation Resource Solutions, Inc. 2008.
 * @version 2.0
 * @access public
 *
 * @abstract Generates the page for the Zoom functionality.
 *
 *
 * @uses Connections/crsolutions.php, includes/clsInterface.inc.php
 *
 */
 
//Sets flag which is checked by objects to limit them to being called from a page
//with this flag set. Objects will not run without this flag.

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

$oControlPanel = new controlPanel;
$oInterface = new userInterface;

$mdrUser = new User();
$mdrUser = $_SESSION['UserObject'];
$connection = $mdrUser->sqlConnection(); //passing the return from the local connection doc into a generic name


$userID = $_SESSION['iemsID'];

if($_GET['pres'] == 'individual')
{
	$points[$_GET['ID']] =  'on';
}
else
{
	$ids = explode(',',$_GET['ID']);
	foreach($ids as $idSet)
	{
		$points[$idSet] = 'on';
	}	
}
$chartID = 'amline';
if($_GET['pres'] == 'comparison')
{
	$baseDate = explode(',',$_GET['Date']);
}
else
{
	$baseDate = $_GET['Date'];
    $dateParts = explode('-',$baseDate);
    $origBaseDate = $dateParts[1] . '-' . $dateParts[2] . '-' . $dateParts[0];
}

if(isset($_GET['view']) && $_GET['view'] == 'tabularData')
{
	$chart = $oInterface->gather($_GET['action'],$points,$baseDate,$_GET['Span'],$_GET['pres'],'tabularData',$connection,$_GET['formUsed'],$_SESSION['UserObject']);
}
elseif(isset($_GET['view']) && $_GET['view'] == 'tabularPrices')
{
	$chart = $oInterface->gather($_GET['action'],$points,$baseDate,$_GET['Span'],$_GET['pres'],'tabularPrices',$connection,$_GET['formUsed'],$_SESSION['UserObject']);
}
else
{
    
    $chart = ($_GET['formUsed'] == 'eventsForm') ? 
        $oInterface->gatherEvent($_GET['action'],$points,$baseDate,$_GET['Span'],$_GET['pres'],'charts',$connection,$_GET['formUsed'],$mdrUser, $origBaseDate,false) : 
        $oInterface->gather($_GET['action'],$points,$baseDate,$_GET['Span'],$_GET['pres'],'charts',$connection,$_GET['formUsed'],$_SESSION['UserObject']);
}



if($_GET['action'] == 'modalPrint' || $_GET['action'] == 'printEvent' || $_GET['action'] == 'modalDisplay')
{
	$backColor = '#FFFFFF';
}
else
{
	$backColor = '#CDCDCD';
}

//<body style="background-color: <?php echo $backColor;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<!-- forcing ie8 into compatibility mode here -->
	<meta http-equiv="X-UA-Compatible" content="IE=7.5" >
</head>
<body>

	<script type="text/javascript" src="mootools/mootools-1.2-core.js"></script>
	<script type="text/javascript" src="mootools/mootools-1.2-more.js"></script>
	
	<div style="color: #000000; margin: 0px; padding: 0px;">
	
		<table align="center" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td style="color: #000000;"><?php echo $chart; ?></td>
		</tr>
		</table>
	</div>
</body>
</html>

