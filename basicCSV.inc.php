<?php
 /**
 * tabularData.inc.php
 *
 * @package IEMS
 * @name Tabular Data
 * @author Marian C. Buford, Rearview Enterprises, Inc.
 * @copyright Copyright Conservation Resource Solutions, Inc. 2008.
 * @version 2.0
 * @access public
 *
 * @abstract Generates the page for the Tabular functionality, including CSV final output.
 *
 * @uses Connections/crsolutions.php, includes/clsInterface.inc.php, includes/clsControlPanel.inc.php
 *
 */
if (!defined('DEBUG')) define('DEBUG',FALSE);

if (!defined('APPLICATION'))
{
    define('APPLICATION', TRUE);
    define('GROK', TRUE);
    define('iEMS_PATH','');

    require_once iEMS_PATH.'Connections/crsolutions.php';
    
    require_once iEMS_PATH.'iEMSLoader.php';
    $Loader = new iEMSLoader(); //arg: bool(true|false) enables/disables logging to iemslog.txt
                                //to watch log live, from command-line: tail logpath/logfilename -f
}

require_once 'displayStatistics.inc.php';
require_once 'manageContacts.inc.php';	
require_once 'displayEventSummary.inc.php';	

if(isset($_GET['userID']))
{
	$userID = $_SESSION['iemsID'];
}
else
{
	$userID = '';
}

if(isset($_GET['domainID']))
{
	$domainID = $_SESSION['iemsDID'];
}
else
{
	$domainID = '';
}

if($_GET['formUsed'] == 'uptimeForm')
{
	$reportName = 'UptimeReport';
}
elseif($_GET['formUsed'] == 'eventsForm')
{
    $_SESSION['evtSummarySections'] = '';

	$reportName = 'EventSummary';
	$eventDate = $_SESSION['evtSummaryDate'];

    $_SESSION['evtSummarySections'] = explode(":",rtrim($_GET['sections'],":"));

    //$Loader->preDebugger($_SESSION['evtSummarySections']);

}
else
{
	$reportName = 'ContactReport';
}

   $savename = 'iEMS2_'.$reportName.'_'.date('Y_m_d_H_i').'.csv';
   ini_set('zlib.output_compression','Off');

if(!DEBUG) {
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
   header('Content-Disposition: attachment; filename="'.$savename.'"');
}
else
{
    print '<pre>';
}


    if(DEBUG && isset($_SESSION['debugSQL'])) 
          $mdrUser->preDebugger($_SESSION['debugSQL']); 


    if($_GET['formUsed'] == 'uptimeForm')
    {
    	print viewStatistics($userID,$domainID,true);
    }
    elseif($_GET['formUsed'] == 'eventsForm')
    {
    	print viewEventSummary($domainID, $eventDate, true);
    }
    else
    {
    	print viewContactReport($userID, $domainID, true, $_GET['contactProgram']);
    }

    //$Loader->preDebugger($_SESSION['evtSummarySections']);

?>

