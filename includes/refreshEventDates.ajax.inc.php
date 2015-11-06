<?php
/**
 * refreshEventDates
 *
 * @package IEMS
 * @name Refresh Event Dates
 * @author Marian C. Buford, Rearview Enterprises, Inc.
 * @copyright Copyright Conservation Resource Solutions, Inc. 2008.
 * @version 2.0
 * @access public
 * @abstract Event dates required some additional handling beyond the Calendar control; this additional page to facilitates the automatic updating of the date field when point selections are modified.
 * 
 * @uses Connections/crsolutions.php, includes/clsControlPanel.inc.php
 */
define('APPLICATION', TRUE);
define('GROK', TRUE);
define('iEMS_PATH','../');

require_once iEMS_PATH.'displayEventSummary.inc.php';    
require_once iEMS_PATH.'Connections/crsolutions.php';    
require_once iEMS_PATH.'includes/clsInterface.inc.php';   
require_once iEMS_PATH.'includes/clsControlPanel.inc.php'; 

require_once iEMS_PATH.'iEMSLoader.php';    
$Loader = new iEMSLoader(false); //arg: bool(true|false) enables/disables logging to iemslog.txt
                            //to watch log live, from command-line: tail logpath/logfilename -f
$oControlPanel = new controlPanel;

$mdrUser = new User();

//session_start();

if (empty($_SESSION['UserObject'])) {
    //echo "INDEX: In UserObject empty...<br>\n";
    $mdrUser->Login($username, $password);
    $_SESSION['UserObject'] = $mdrUser;
} else {
    //echo "INDEX: In UserObject NOT empty...<br>\n";
    $mdrUser = $_SESSION['UserObject'];
}


if(empty($_POST['evtPoints']))
{
	print '<div style="padding-top: 18px; padding-bottom: 18px;">There are no points selected.</div>';
}
else
{
	print $oControlPanel->eventDateSelectBuilder($_POST['evtPoints'],$mdrUser,'js');
}

?>
