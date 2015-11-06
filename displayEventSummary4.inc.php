
 /**
 * displayStatistics.inc.php
 *
 * @package IEMS
 * @name Summary Reports
 * @author Kevin L. Keegan, CRS, Inc.
 * @copyright Copyright Conservation Resource Solutions, Inc. 2009.
 * @version 2.1
 * @access public
 *
 */

//Sets flag which is checked by objects to limit them to being called from a page
//with this flag set. Objects will not run without this flag.

if (!defined('APPLICATION')) define('APPLICATION', TRUE);
//if (session_id() == "") session_start();

function viewEventSummary($domainID, $eventDate, $CSVFlag)
{
	$userObject = clone $_SESSION['UserObject'];
/* this is a fix for the date not coming in the way we want -- dont' want to tip the apple cart 2010.06.13 */
    $dateParts = explode('-',$eventDate);
    $month = $dateParts[0];
    $day = $dateParts[1];
    $year = $dateParts[2];

    $eventDate = $year.'-'.$month.'-'.$day;

    $eventPerformanceSummary = new EventPerformanceSummary($domainID, $eventDate);
    $eventPerformanceSummary->Get();

    $calledPrograms = $eventPerformanceSummary->calledPrograms();

    $calledZones = $eventPerformanceSummary->calledZones();

    $epsReportProgram = $eventPerformanceSummary->reportProgram();
    $epsReportProgramDetails = $eventPerformanceSummary->reportProgramDetails();
    
    $epsReportZone = $eventPerformanceSummary->reportZone();
    $epsReportZoneDetails = $eventPerformanceSummary->reportZoneDetails();

	//$viewEventSummary = BuildEventSummaryHeader("Event Summary", $userObject->localDomain()->description(), $eventDate, $domainID, $CSVFlag);

    $PointChannels = new PointChannels();
    $PointChannels->Load($userObject->id(),$userObject->Domains(0)->id(),null,null,true,$month,$year);     

    foreach ($calledPrograms as $inx=>$calledProgram) 
    {
        //$userObject->preDebugger($epsReportProgramDetails[$calledProgram],'#980000');    
        foreach($PointChannels->Resources() as $resourceObjectId=>$attribs)
        {
            if(array_key_exists($resourceObjectId,$epsReportProgramDetails[$calledProgram]))
            {
                //$userObject->preDebugger('<hr />','yellow');
                //$userObject->preDebugger($resourceObjectId,'yellow');
                
                foreach($attribs['assets'] as $asset)
                {
                    //$userObject->preDebugger($asset['assetIdentifier'],'orange');
                    if(!array_key_exists($asset['assetIdentifier'],$epsReportProgramDetails[$calledProgram][$resourceObjectId]))
                    {
                        //$userObject->preDebugger($asset['description'],'#989800');
                        $epsReportProgramDetailsRestack[$calledProgram][$resourceObjectId][$asset['assetIdentifier']]['resourceObjectId'] = $resourceObjectId;                        
                        $epsReportProgramDetailsRestack[$calledProgram][$resourceObjectId][$asset['assetIdentifier']]['objectId'] = $asset['id'];                        
                        $epsReportProgramDetailsRestack[$calledProgram][$resourceObjectId][$asset['assetIdentifier']]['channelId'] = $asset['channelId'];
                        $epsReportProgramDetailsRestack[$calledProgram][$resourceObjectId][$asset['assetIdentifier']]['description'] = $asset['description'];            
                        $epsReportProgramDetailsRestack[$calledProgram][$resourceObjectId][$asset['assetIdentifier']]['programId'] = $asset['programId'];
                        $epsReportProgramDetailsRestack[$calledProgram][$resourceObjectId][$asset['assetIdentifier']]['programDescription'] = $asset['programDescription'];
                    }
                    else
                    {
                        $epsReportProgramDetailsRestack[$calledProgram][$resourceObjectId][$asset['assetIdentifier']] = clone $epsReportProgramDetails[$calledProgram][$resourceObjectId][$asset['assetIdentifier']];
                    }
                }             
            }
        }
        /*
        foreach($epsReportProgramDetailsRestack[$calledProgram] as $resourceId=>$assets)
        {
            foreach($assets as $inx=>$assetObject)
            {
                if(is_object($assetObject))
                {
                    //$userObject->preDebugger($assetObject,'#989800');
                }
                else
                {
                    $userObject->preDebugger($assetObject,'#980000');
                }
            }
            
        } 
        
    }*/
