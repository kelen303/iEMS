<?php
    
	define('APPLICATION', TRUE);
    define('GROK', TRUE);
    define('iEMS_PATH','');
	
	require_once iEMS_PATH.'displayEventSummary.inc.php';    
    require_once iEMS_PATH.'Connections/crsolutions.php';    
    require_once iEMS_PATH.'includes/clsInterface.inc.php';    
    
    require_once iEMS_PATH.'iEMSLoader.php';    
    $Loader = new iEMSLoader(); //arg: bool(true|false) enables/disables logging to iemslog.txt
                                //to watch log live, from command-line: tail logpath/logfilename -f

	$oInterface = new userInterface;

    $mdrUser = new User();
    $connection = $mdrUser->sqlConnection(); //passing the return from the local connection doc into a generic name$connection = $mdrUser->sqlConnection(); //passing the return from the local connection doc into a generic name
    if (empty($_SESSION['UserObject'])) {
        //echo "REFRESH: In UserObject empty...<br>\n";
        $mdrUser->Login($_SESSION["iemsName"], $_SESSION["iemsPW"]);
        $_SESSION['UserObject'] = $mdrUser;        

    } else {
        //echo "REFRESH: In UserObject NOT empty...<br>\n";
        $mdrUser = $_SESSION['UserObject'];
    }

    $meterSummary = '';
    $tabTipScript = '';    

    if (isset($_SESSION['viewEventSummary']))
    {
        $meterSummary = viewEventSummary($_SESSION['iemsDID'], $_SESSION['evtSummaryDate'], false, true);
    }
    else
    {
        if($_SESSION['formUsed'] == 'eventsForm')
        {
            if(isset($_SESSION['evtBaseDate']))
                $meterSummary = $oInterface->gatherEvent($_SESSION['action'],$_SESSION['currentSelection'],$_SESSION['baseDate'],$_SESSION['dateSpan'],$_SESSION['selectedPresentation'],$_SESSION['selectedView'],$connection,$_SESSION['formUsed'],$mdrUser,$_SESSION['evtBaseDate'],true);            
        }
        else
        {
            $meterSummary = $oInterface->gather($_SESSION['action'],$_SESSION['currentSelection'],$_SESSION['baseDate'],$_SESSION['dateSpan'],$_SESSION['selectedPresentation'],$_SESSION['selectedView'],$connection,$_SESSION['formUsed'],$mdrUser,true);
        }

        
    }
?>    
    <script type="text/javascript" src="mootools/mootools-1.2-core.js"></script>
    <script type="text/javascript" src="mootools/mootools-1.2-more.js"></script>
    <script type="text/javascript" src="mootools/mootools-compat-core.js"></script>
    <script type="text/javascript" src="mootools/mootools-compat-more.js"></script>	
    <script type="text/javascript" src="mootools/crs-controlPanel.js"></script>

<?php print $meterSummary; ?>

<script type="text/javascript" src="mootools/smoothbox.js"></script><!-- this is down here for internet explorer; known bug re: prematurely terminating the dom; affects versions 5-7 -->

