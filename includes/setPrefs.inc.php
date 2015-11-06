<?php
 /**
 * setPrefs.inc.php
 *
 * @package IEMS
 * @name Set Preferences [Core]
 * @author Marian C. Buford, Rearview Enterprises, Inc.
 * @copyright Copyright Conservation Resource Solutions, Inc. 2008.
 * @version 2.0
 * @access public
 *
 * @abstract Generates the page for the Visible Points, Default Points, and some of Zip functionality.
 *
 *
 * @uses Connections/crsolutions.php, includes/clsInterface.inc.php
 *
 */
 
define('APPLICATION', TRUE);
define('GROK', TRUE);
define('iEMS_PATH','../');

require_once iEMS_PATH.'displayEventSummary.inc.php';    
require_once iEMS_PATH.'Connections/crsolutions.php';    
require_once iEMS_PATH.'includes/clsInterface.inc.php';   
require_once iEMS_PATH.'includes/clsControlPanel.inc.php'; 

require_once iEMS_PATH.'iEMSLoader.php';
$Loader = new iEMSLoader(false);    //arg: bool(true|false) enables/disables logging to iemslog.txt
                                    //to watch log live, from command-line: tail logpath/logfilename -f

$Prefs = new Preferences;

if (!isset($_SESSION)) session_start();

$oControlPanel = new controlPanel;

$mdrUser = new User();
$mdrUser = $_SESSION['UserObject'];

$connection = $mdrUser->sqlConnection(); //passing the return from the connection doc into a generic name
$master_connection = $mdrUser->sqlMasterConnection(); //passing the return from the connection doc into a generic name

//echo "mdrUser->id()='" . $mdrUser->id() . "'<br>\n";

$whereString = '';
$tally = 0;
$defaultChartType = 'individual';
$message = '';

//$mdrUser->preDebugger($_POST);

$process = isset($_REQUEST['process']) ? $process = $_REQUEST['process'] : '';

$count = isset($_POST['hidden']) ? count($_POST['hidden']) : 0;


$action = isset($_GET['action']) ? $_GET['action'] : '';

$standardErrorString = 'There was a problem with your request.<br />Please try again.<br />If the problem persists, please contact the CRS Help Desk.';

//$mdrUser->preDebugger($action,'yellow');
//mcb following is four nearly identical query sets. when we have a few moments, let's combine into a single function/process.
if($action == 'zip' || $action == 'refreshWeather')
{
    $result = $Prefs->updatePostalCode($_POST['zipCode'],$_SESSION['iemsID']);
    
    if(!$result['error'])
    {
        
        $message =  'Your default postal code has been set to '.$_POST['zipCode'];
        if($_GET['action'] == 'refreshWeather')
        {
			$_REQUEST['weatherZip'] = $_POST['zipCode'];
            require_once 'weather.inc.php';                      
        }
    }else
    {
        $message =  $standardErrorString;
    }
}
else
{
    $prefs = $oControlPanel->gatherDefaultPresentation($master_connection, $mdrUser);    
    $systemPrefID = $prefs['system'];
    $userPrefID = $prefs['user'];
    //$mdrUser->preDebugger($prefs,'yellow');
}

if($action == 'hide' && (isset($_POST['visible']) && count($_POST['visible']) > 0))
{
    foreach($_POST['visible'] as $pointToHide=>$state)
    {
        $ids = explode(':',$pointToHide);
        $pointID = $ids[0];
        $channelID = $ids[1];

        $sql = '
            insert into
                t_pointchannelpreferences
            (UserPreferenceID, PointObjectID, ChannelID) values
            (' . $userPrefID['HiddenPointChannel'].', ' . $pointID . ', ' . $channelID . ')'
        ;
    
        mysql_query($sql, $master_connection);
    }

	$message = '<div><div class="error" style="width: 500px;">Your preference has been saved.<br />To apply the changes to your Control Panel, click the Apply to Control Panel button:<br /><br /><a href="index.php?action=refresh" class="defaultButton" style="padding: 3px;">Apply to Control Panel</a></div></div>';
}

if($action == 'show' && (isset($_POST['hidden']) && count($_POST['hidden']) > 0))
    
{
    foreach($_POST['hidden'] as $pointToShow=>$state)
    {
        $ids = explode(':',$pointToShow);
        $pointID = $ids[0];
        $channelID = $ids[1];

        $sql = '
            DELETE FROM
                t_pointchannelpreferences
            where
                UserPreferenceID = '.$userPrefID['HiddenPointChannel'].' and PointObjectID = ' . $pointID . ' and ChannelID = ' . $channelID
        ;
       
        mysql_query($sql, $master_connection);
    }

	$message = '<div><div class="error" style="width: 500px;">Your preference has been saved.<br />To apply the changes to your Control Panel, click the Apply to Control Panel button:<br /><br /><a href="index.php?action=refresh" class="defaultButton" style="padding: 3px;">Apply to Control Panel</a></div></div>';
}

if(isset($_POST['visible']) && count($_POST['visible']) > 0)
{
    foreach($_POST['visible'] as $pointToSet=>$state)
    {
        $ids = explode(':',$pointToSet);
        $pointID = $ids[0];
        $channelID = $ids[1];
    
        $sql = '
            INSERT INTO
                t_pointchannelpreferences
            (UserPreferenceID, PointObjectID, ChannelID) values
            (' . $userPrefID['DefaultPointChannel'] . ', ' . $pointID . ', ' . $channelID . ')'
        ;            
        
        //$mdrUser->preDebugger($sql,'orange');
        $result = $mdrUser->processQuery($sql,$master_connection,'insert');
        if(!$result['error'])
        {
            $message = '<div><div class="error" style="width: 500px;">Your preference has been saved.<br />To apply the changes to your Control Panel, click the Apply to Control Panel button:<br /><br /><a href="index.php?action=refresh" class="defaultButton" style="padding: 3px;">Apply to Control Panel</a></div></div>';
        }
        else
        {
            $message =  $standardErrorString;
        }
    }

	
}


if($action == 'remove' && (isset($_POST['default']) && count($_POST['default']) > 0))
{
    foreach($_POST['default'] as $pointToSet=>$state)
    {
        $ids = explode(':',$pointToSet);
        $pointID = $ids[0];
        $channelID = $ids[1];
    
        $sql = '
            DELETE FROM
                t_pointchannelpreferences
            WHERE
                UserPreferenceID = '.$userPrefID['DefaultPointChannel'].' and PointObjectID = ' . $pointID . ' and ChannelID = ' . $channelID
        ;
    
        mysql_query($sql, $master_connection);
    }

	$message = '<div><div class="error" style="width: 500px;">Your preference has been saved.<br />To apply the changes to your Control Panel, click the Apply to Control Panel button:<br /><br /><a href="index.php?action=refresh" class="defaultButton" style="padding: 3px;">Apply to Control Panel</a></div></div>';
}

if($action == 'type')
{
	if($_POST['defaultChartPref'] == $defaultChartType && $userPrefID['AlternateChartPresentation'] != '')
	{
        $deleteSQL = '
            DELETE FROM
                t_userpreferences
            WHERE
				UserPreferenceID = '.$userPrefID['AlternateChartPresentation']
        ;
		 mysql_query($deleteSQL, $master_connection);
		$userPrefID['AlternateChartPresentation'] = '';
		$message = '<div><div class="error" style="width: 500px;">Your preference has been saved.<br />To apply the changes to your Control Panel, click the Apply to Control Panel button:<br /><br /><a href="index.php?action=refresh" class="defaultButton" style="padding: 3px;">Apply to Control Panel</a></div></div>';
	}

	if($_POST['defaultChartPref'] != $defaultChartType && $userPrefID['AlternateChartPresentation'] == '')
	{
		$insertSQL = '
            INSERT INTO
                t_userpreferences
            VALUES
    				("",
    				'.$systemPrefID['AlternateChartPresentation'].',
                    '.$mdrUser->id().',
                    NOW(),
    				0)
        ';

        mysql_query($insertSQL, $master_connection);
		$userPrefID['AlternateChartPresentation'] = mysql_insert_id();
		$message = '<div><div class="error" style="width: 500px;">Your preference has been saved.<br />To apply the changes to your Control Panel, click the Apply to Control Panel button:<br /><br /><a href="index.php?action=refresh" class="defaultButton" style="padding: 3px;">Apply to Control Panel</a></div></div>';
	}
}

/****************************************************************/
	switch ($process)
	{
		case 'hideMeterPoints':
			print '
				<table align="center" width="700" cellpadding="10" cellspacing="0" border="0">
					<tr>
						<td><center>'.$message.'</center></td>
					</tr>
					<tr>
						<td>'.$oControlPanel->hidePointsForm($master_connection, $mdrUser).'</td>
					</tr>
				</table>
			';
			break;
		case 'setDefault':
			print '
				<table align="center" width="700" cellpadding="10" cellspacing="0" border="0">
					<tr>
						<td><center>'.$message.'</center></td>
					</tr>
					<tr>
						<td>'.$oControlPanel->defaultPointsForm($master_connection, $mdrUser, $userPrefID['AlternateChartPresentation']).'</td>
					</tr>
				</table>
			';
			break;
		case 'setZip':
			print '
				<table align="center" width="700" cellpadding="10" cellspacing="0" border="0">
					<tr>
						<td><center>'.$message.'</center></td>
					</tr>
					<tr>
						<td>'.$oControlPanel->setZipForm($master_connection, $mdrUser->id()).'</td>
					</tr>
				</table>
			';
			break;

	}
?>

