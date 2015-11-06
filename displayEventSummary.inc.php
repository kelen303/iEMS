<?php
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

if (!defined('APPLICATION')) define('APPLICATION', TRUE);

//if (session_id() == "") session_start();

define('UNID',uniqid());

$CSOs                       = array();
$oUser                      = null;
$calledPrograms             = null;
$epsReportProgram           = null;
$epsReportProgramDetails    = null;
$calledZones                = null;
$epsReportZone              = null;
$epsReportZoneDetails       = null;
$PointChannels              = null;
$viewEventSummary           = null;

/*  ===========================================================================
    FUNCTION : viewEventSummary()
    =========================================================================== */   
    function viewEventSummary($domainID, $eventDate, $CSVFlag)
    {
        
        global $CSOs, $oUser;
        global $calledPrograms, $epsReportProgram, $epsReportProgramDetails;
        global $calledZones, $epsReportZone, $epsReportZoneDetails;
        global $PointChannels;
        global $viewEventSummary;

        $oUser = clone $_SESSION['UserObject'];
        $dateParts = explode('-',$eventDate);
        $month = $dateParts[0];
        $day = $dateParts[1];
        $year = $dateParts[2];

        $eventDate = $year.'-'.$month.'-'.$day;
    
        $eventPerformanceSummary    = new EventPerformanceSummary($domainID, $eventDate);
        $eventPerformanceSummary->Get();

        $involvedAssetsList         = $eventPerformanceSummary->allInvolvedAssets();

        $calledPrograms             = $involvedAssetsList['calledPrograms'];
        //$calledPrograms             = $eventPerformanceSummary->calledPrograms(); MCB: leaving a traceable trail behind as today's mods are a shim

        $epsReportProgram           = $eventPerformanceSummary->reportProgram();        
        $epsReportProgramDetails    = $eventPerformanceSummary->reportProgramDetails();
        
        //preDebugger($epsReportProgram,'blue');
        //preDebugger($epsReportProgramDetails,'green');

        $calledZones                = $involvedAssetsList['calledZones'];
        //$calledZones                = $eventPerformanceSummary->calledZones(); MCB: leaving a traceable trail behind as today's mods are a shim
        
        $epsReportZone              = $eventPerformanceSummary->reportZone();
        $epsReportZoneDetails       = $eventPerformanceSummary->reportZoneDetails();

        $viewEventSummary           = BuildEventSummaryHeader("Event Summary", $oUser->localDomain()->description(), $eventDate, $domainID, $CSVFlag);
    
        // TODO: This can be reworked so that the PointChannels object isn't needed -- see 
        // mdr/EvtPerfSummary.php for clues :)
        // For now, we're filling display data from there so we'll leave it alone (2012-05-31)

        $PointChannels              = new PointChannels();
        $PointChannels->Load($oUser->id(),$oUser->Domains(0)->id(),null,null,true,$month,$year);

        //preDebugger($involvedAssetsList,'blue');        

        foreach ($calledPrograms as $inx=>$calledProgram) 
        {
            $CSOs[$calledProgram]['value'] = 0;          
            
            foreach($PointChannels->Resources() as $resourceObjectId=>$attribs)
            {   
                if(array_key_exists($resourceObjectId,$involvedAssetsList['reportProgramSummary'][$calledProgram])) 
                {       
                    
                        $programDetailToUse = isset($epsReportProgramDetails[$calledProgram][$resourceObjectId])
                                              ? $epsReportProgramDetails[$calledProgram][$resourceObjectId]
                                              : $involvedAssetsList['epsReportProgramDetails'][$calledProgram][$resourceObjectId];
                        
                        //preDebugger("<strong>$epsReportProgramDetails[$calledProgram][$resourceObjectId]</strong>",'red');
                        //preDebugger($epsReportProgramDetails[$calledProgram][$resourceObjectId],'red');
                        //preDebugger($programDetailToUse);

                        $programStack[$calledProgram][$attribs['identifier']] = restackAsset($resourceObjectId,
                                                                                             $attribs['assets'],
                                                                                             $programDetailToUse,
                                                                                             $attribs,
                                                                                             $calledProgram,
                                                                                             null,
                                                                                             $involvedAssetsList['epsReportProgramDetails'][$calledProgram][$resourceObjectId]);
                }
                
                foreach($calledZones[$calledProgram] as $inx=>$calledZone)
                {
                    if(!isset($CSOs[$calledProgram][$calledZone]['value']))
                        $CSOs[$calledProgram][$calledZone]['value'] = 0;

                    if(array_key_exists($resourceObjectId,$involvedAssetsList['epsReportZoneDetails'][$calledProgram][$calledZone]))
                    {
                        $zoneDetailToUse = isset($epsReportZoneDetails[$calledProgram][$calledZone][$resourceObjectId])
                                              ? $epsReportZoneDetails[$calledProgram][$calledZone][$resourceObjectId]
                                              : $involvedAssetsList['epsReportZoneDetails'][$calledProgram][$calledZone][$resourceObjectId];
                        
                        $zoneStack[$calledProgram][$calledZone][$attribs['identifier']] = restackAsset($resourceObjectId,
                                                                                                       $attribs['assets'],
                                                                                                       $zoneDetailToUse,
                                                                                                       $attribs,
                                                                                                       $calledProgram,
                                                                                                       $calledZone,
                                                                                                       $involvedAssetsList['epsReportZoneDetails']);
                    }
                }
            }
        }
        //preDebugger($programStack,'green');

        return $CSVFlag ? renderCSV($programStack, $zoneStack) : renderHMTL($programStack, $zoneStack);
        
    } // viewEventSummary

/*  ===========================================================================
    FUNCTION : BuildEventSummaryHeader()
    =========================================================================== */    
    function BuildEventSummaryHeader($reportTitle, $domain, $summaryDate,$domainID, $CSVFlag)
    {
	if($CSVFlag === true)
	{
		$reportHeader = $reportTitle . ' For ' . $domain . ' on ' . $summaryDate . "\n";
	}
	else
	{
            $_SESSION['viewEventSummary'] = "1";
            $pointString = "";
    
        	$reportHeader = '<script  type="text/javascript">' . "\n" .
                    '   function ToggleDivVisibility(divID) {' . "\n" .
                            '//console.log(dojo.byId(divID)); ' . 
                    '       var div, vis;' . "\n" .
                    '       if (document.getElementById) {' . "\n" .
                    '           // Standard browsers code...' . "\n" .
                    '           div = document.getElementById(divID);' . "\n" .
                    '       } else if (document.all) {' . "\n" .
                    '           // this is the way old MS Internet Explorer versions work...' . "\n" .
                    '           div = document.all[divID];' . "\n" .
                    '       } else if (document.layers) {' . "\n" .
                    '           // this is the way Netscape Navigator 4 works...' . "\n" .
                    '           div = document.layers[divID];' . "\n" .
                    '       }' . "\n" .
                    "\n" .
                    '       vis = div.style;' . "\n" .
                    '       // if the style.display value is blank we try to figure it out here...' . "\n" .
                    '       if ((vis.display == "") && ' . "\n" .
                    '           ((div.offsetWidth != undefined) && (div.offsetHeight != undefined)))' . "\n" .
                    '           vis.display = ((div.offsetWidth != 0) && (div.offsetHeight != 0))?"block":"none";' . "\n" .
                    '       vis.display = (vis.display == "" || vis.display=="block")?"none":"block";' . "\n" .
                    '   }' .  "\n" .
                    '   window.addEvent(\'domready\', function(){' .
                    "\n" .            
            
                    '        var psCalledProgramTip = new Tips($("psCalledProgram"));' .
                    '        $("psCalledProgram").store("tip:title", "Called Program");' .
                    '        $("psCalledProgram").store("tip:text", "Click on the program name to display the program\'s detail report.");' .  
                    "\n" .
                    
            
                    '        var psZonalTip = new Tips($("psZonal"));' .
                    '        $("psZonal").store("tip:title", "Zonal");' .
                    '        $("psZonal").store("tip:text", "Click on Summary (below) for the Zonal Summary of the row\'s program.");' .  
                    "\n" .            
            
                    '        var psTotalCrTip = new Tips($("psTotalCr"));' .
                    '        $("psTotalCr").store("tip:title", "Total Est. KW Capability");' .
                    '        $("psTotalCr").store("tip:text", "This is the total committed reduction of all reporting assets in each program.");' .                                         
                    "\n" .
            
                    '        var psFcmDeltaTip = new Tips($("psFcmDelta"));' .
                    '        $("psFcmDelta").store("tip:title", "Delta");' .
                    '        $("psFcmDelta").store("tip:text", "This is the difference between the baseline and the FCM load for all assets in each program.");' .
                    "\n" .
            
                    '        var psFcmCrTip = new Tips($("psFcmCr"));' .
                    '        $("psFcmCr").store("tip:title", "%Est. KW Capability");' .
                    '        $("psFcmCr").store("tip:text", "This is the percentage of the committed reduction achieved by all assets in each program.");' .
            
                    '   });' .
                    '</script>' . "\n\n";
        
		$reportHeader .= '<table align="right" cellpadding="0" cellspacing="0" border="0">'."\n";
		$reportHeader .= '<tr>'."\n";
		$reportHeader .= '<td class="export"><a href="#" id="exportTableTip" onClick="processBasicCSVExport(\'eventsForm\',\''.rtrim($pointString,',').'\',\''.$domainID.'\');" ><img src="_template/images/blank.gif" height="31" width="31" border="0" /></a></td>'."\n";
		$reportHeader .= '</tr>'."\n";
		$reportHeader .= '</table>'."\n";
		$reportHeader .= '<div style="width: 750px; text-align: left; font-weight: bold;">' . "\n";
		$reportHeader .= $reportTitle . "\n";
		$reportHeader .= '<br />' . "\n";
		$reportHeader .= 'For ' . $domain . ' on ' . $summaryDate . "\n";
		$reportHeader .= '<br />' . "\n";
		$reportHeader .= date("H:i:s") . "\n";
		$reportHeader .= '</div><br />' . "\n";
        
	}
        return $reportHeader;
    } // BuildEventSummaryHeader

/*  ===========================================================================
    FUNCTION : performancePercent()
    =========================================================================== */
    function performancePercent($delta,$CSO)
    {
        $perfArray['value'] = 0;
        $perfArray['color'] = "red";

        if($CSO > 0)
        {
            $perfArray['value'] = round(((str_replace(',','',$delta)/str_replace(',','',$CSO))*100),2);
            $perfArray['color'] = $perfArray['value'] < 75 ? "red" : ($perfArray['value'] < 100 ? "orange" : "green");
        }
        
        return $perfArray;
    } // performancePercent

/*  ===========================================================================
    FUNCTION : avgByResource()
    =========================================================================== */
    function avgByResource($lines,$calledProgram,$epsReport,$type = 'program',$calledZone = null)
    {
        if(empty($epsReport)) return;

        global $oUser;
        $avgs = array();

        $avgs['fcmDelta']        = 0;
        $avgs['updatedDate']     = null;
        
        $avgs['totalFCMDelta']   = 0;
        
        foreach($lines as $status=>$statusGroup)
        {   
            foreach($statusGroup as $inx=>$assetInfo)
            {
                if(!isset($avgs['resourceID'])) $avgs['resourceID'] = $assetInfo['resourceID'];
    
                $avgs['resourceDesc'] = $assetInfo['resourceDesc'];
                $avgs['totalFCMDelta'] += $assetInfo['fcmDelta'];
            }
        }

        $avgs['fcmDelta']       = $avgs['totalFCMDelta'];        

        $avgs['updatedDate'] = $type == 'program' ? $epsReport[$calledProgram]->updatedDate() : $epsReport[$calledProgram][$calledZone]->updatedDate();
        
        return $avgs;
        
    } // avgByResource

/*  ===========================================================================
    FUNCTION : restackAsset()
    =========================================================================== */
    function restackAsset($resourceObjectId,$assets,$detailItem,$attribs,$calledProgram,$calledZone = null,$involvedAssets)
    {
        //preDebugger($calledZone);
        global $CSOs;
        global $oUser;        
        
        if($calledZone == null) $CSOs[$calledProgram][$attribs['identifier']] = 0;
        $resourceCR = 0;
        foreach($assets as $asset)
        {                   
            $key = 'unknown';
            $start = "No Data";
            $end = "No Data";
            $updated = "No Data";
            
            if(array_key_exists($asset['assetIdentifier'],$detailItem) && $detailItem[$asset['assetIdentifier']]->createdDate() != null)
            {   
                // the above created date tells us who actually has data as that date comes from the interval info.  
                $key = $detailItem[$asset['assetIdentifier']]->doNotDispatch() ? 'doNotDispatch' : 'dispatch';
                $stack[$key][$asset['assetIdentifier']] = clone $detailItem[$asset['assetIdentifier']]; 
                $resourceCR = $stack[$key][$asset['assetIdentifier']]->resourceCR();
            }
            else
            {   
                //preDebugger($detailItem);    
                if(is_object($involvedAssets[$asset['assetIdentifier']]))                                
                {   // anything falling out of here is really having an issue -- likely data side.
                    $key = $involvedAssets[$asset['assetIdentifier']]->doNotDispatch() ? 'doNotDispatch' : 'dispatch';                        

                    if($key == 'dispatch')
                    {
                        $effectiveTimeParts     = explode(' ', $involvedAssets[$asset['assetIdentifier']]->effectiveTime());
                        $restorationTimeParts   = explode(' ', $involvedAssets[$asset['assetIdentifier']]->restorationTime()); 
    
                        $start = explode(':',$effectiveTimeParts[1]);
                        $end = explode(':',$restorationTimeParts[1]);
    
                        $start = $start[0] . ':' . $start[1];
                        $end = $end[0] . ':' . $end[1];
                        
                        $updated = $effectiveTimeParts[0];
                    }
                }                                   
                
                $stack[$key][$asset['assetIdentifier']]['resourceObjectId'] = $resourceObjectId; 
                $stack[$key][$asset['assetIdentifier']]['resourceDescription'] = $attribs['description'];                        
                $stack[$key][$asset['assetIdentifier']]['objectId'] = $asset['id'];                        
                $stack[$key][$asset['assetIdentifier']]['channelId'] = $asset['channelId'];
                $stack[$key][$asset['assetIdentifier']]['description'] = $asset['description'];            
                $stack[$key][$asset['assetIdentifier']]['programId'] = $asset['programId'];
                $stack[$key][$asset['assetIdentifier']]['programDescription'] = $asset['programDescription'];
                
                $stack[$key][$asset['assetIdentifier']]['start'] = $start;
                $stack[$key][$asset['assetIdentifier']]['end'] =  $end;
                $stack[$key][$asset['assetIdentifier']]['updated'] = $updated;

            } 
            if($resourceCR > 0) $CSOs[$calledProgram][$attribs['identifier']] = $resourceCR;
        }
        
        if($calledZone != null)
        {
            $CSOs[$calledProgram][$calledZone][$attribs['identifier']] = $resourceCR;
            $CSOs[$calledProgram][$calledZone]['value'] += $resourceCR;    
        }
        else
        {
            $CSOs[$calledProgram]['value']  += $resourceCR;
        }

        return $stack;
    } // restackAsset
    
    function prepareDispatchString($isDispatched, $dispatchedTime, $effectiveTime, $isRestored, $restorationTime)
    {
        $dispatchDetailString = '<div style="font-size: 10px;">Dispatched at ';
        $dispatchDetailString .= $epsLineItem->isDispatched() ? $epsLineItem->dispatchedTime() : 'TBD';
        $dispatchDetailString .= '</div>';

        return $dispatchDetailString;
    }

/*  ===========================================================================
    FUNCTION : prepareAssetCount()
    ---------------------------------- notes ----------------------------------
    remember to subtract one from the counts as the groups have a heading entry
    =========================================================================== */
    function prepareAssetCount($statusGroups)
    {
        global $oUser;
        //preDebugger($statusGroups);
        $count['unknown'] = (isset($statusGroups['unknown']) && count($statusGroups['unknown'])-1 > 0) ? (count($statusGroups['unknown'])-1) : 0;
        $count['dispatch'] = (isset($statusGroups['dispatch']) && count($statusGroups['dispatch'])-1 > 0) ? (count($statusGroups['dispatch'])-1) : 0;
        $count['doNotDispatch'] = (isset($statusGroups['doNotDispatch']) && count($statusGroups['doNotDispatch'])-1 > 0) ? (count($statusGroups['doNotDispatch'])-1) : 0;

        $totalAssetCount = $count['unknown'] + $count['dispatch'] + $count['doNotDispatch'];

        $parentheticalString = null;

        if(isset($statusGroups['unknown']) || isset($statusGroups['doNotDispatch']))
        {
            $parentheticalString  = '(';
            $parentheticalString .= $count['dispatch'].' dispatched';
            if($count['doNotDispatch'] > 0) $parentheticalString .= ', '.$count['doNotDispatch'].' do not dispatch';
            if($count['unknown'] > 0) $parentheticalString .= ', '.$count['unknown'].' no data';
            $parentheticalString .= ')';
        }

        return array('base'=>$totalAssetCount, 'exceptions'=>$parentheticalString);
    } // prepareAssetCount

/*  ===========================================================================
    FUNCTION : renderCSV()
    =========================================================================== */
    function renderCSV($programStack, $zoneStack)
    {
        global $CSOs, $oUser;
        global $calledPrograms, $epsReportProgram, $epsReportProgramDetails;
        global $calledZones, $epsReportZone, $epsReportZoneDetails;
        global $PointChannels;
        global $viewEventSummary;

        $modified = array();
        $modifiedIndex = 0;
        $modifiedOutput = null;
    	$modifiedFinal = null;

        $viewProgramDetail = "";
        $viewZoneSummary = "";
        $viewZoneDetail = "";

        $viewProgramSummary =   'Program Summary' . "\n" ;
        $viewProgramSummary .=  'Called Program,' .
                                'Total CSO KW,' .								
                                'Hr. Avg.,' .
                                '%CSO,' . 
                                'Start,' .
                                'End,' .
                                'Updated Date' . "\n" ;


            foreach ($calledPrograms as $inx=>$calledProgram) 
            {
               $calledProgramAbbrev = "";
             
                foreach(explode(' ',$calledProgram) as $inx=>$item) 
                    $calledProgramAbbrev .= substr($item,0,1);
            
    /*  ===========================================================================
        Program Details CSV (response summary)------------------------------------- */              

                $thisProgramDetails = str_replace(" ", "_", $calledProgram) . '_detail';
    
                if(array_search($thisProgramDetails,$_SESSION['evtSummarySections']) !== false)
                {
                    foreach($programStack[$calledProgram] as $resourceIdentifier=>$assetGroups) 
                    {                           
                        foreach($assetGroups as $status=>$epsAssetStack) 
                        {
                            foreach($epsAssetStack as $inx=>$epsLineItem) 
                            {
                                if(is_object($epsLineItem))
                                {
                                     $resourceSummaryStack[$resourceIdentifier][$status][] = array(
                                        'resourceID' => $resourceIdentifier,
                                        'resourceDesc' => str_replace(',','',$epsLineItem->resource()),
                                        'resourceCR' => str_replace(',','',$epsLineItem->resourceCR()),
                                        'adjustment' => str_replace(',','',$epsLineItem->adjustment()),
                                        'committedReduction' => str_replace(',','',$epsLineItem->committedReduction()),
                                        'fcmDelta' => str_replace(',','',$epsLineItem->fcmDelta()),
                                        'fcmPCR' => str_replace(',','',$epsLineItem->fcmPCR()),
                                        );
                                     
                                     $effectiveTimeParts     = explode(' ', $epsLineItem->effectiveTime());
                                     $restorationTimeParts   = explode(' ', $epsLineItem->restorationTime()); 
                
                                     $start = explode(':',$effectiveTimeParts[1]);
                                     $end = explode(':',$restorationTimeParts[1]);
                
                                     $start = $start[0] . ':' . $start[1];
                                     $end = $end[0] . ':' . $end[1];

                                     $resourceDetailStack[$resourceIdentifier][$status][] = array(
                                        'asset'              => $epsLineItem->asset(),
                                        'assetId'            => $epsLineItem->assetID(),
                                        'start'              => $start,
                                        'end'                => $end,
                                        'updatedDate'        => $epsLineItem->updatedDate(),
                                        'adjustment'         => str_replace(',','',$epsLineItem->adjustment()),
                                        'committedReduction' => str_replace(',','',$epsLineItem->committedReduction()),
                                        'fcmDelta'           => str_replace(',','',$epsLineItem->fcmDelta()),
                                        'fcmPCR'             => str_replace(',','',$epsLineItem->fcmPCR())
                                    );
                                }
                                else
                                {   
                                    $resourceSummaryStack[$resourceIdentifier][$status][] = array(
                                        'resourceID' => $resourceIdentifier,
                                        'resourceDesc' => $epsLineItem['resourceDescription'],
                                        'resourceCR' => 0,
                                        'adjustment' => 0,
                                        'committedReduction' => 0,
                                        'fcmDelta' => 0,
                                        'fcmPCR' => 0
                                    );
        
                                    $resourceDetailStack[$resourceIdentifier][$status][] = array(
                                        'asset' => $epsLineItem['description'],
                                        'assetId' => $inx ,
                                        'updatedDate' => $epsLineItem['updated'],
                                        'adjustment' => 'No Data',
                                        'committedReduction' => 'No Data',
                                        'fcmDelta' => 'No Data',
                                        'fcmPCR' => 'No Data',
                                        'start' => $epsLineItem['start'],
                                        'end' => $epsLineItem['end']
                                    );
                                }
                            }


                                //preDebugger($resourceSummaryStack[$resourceIdentifier][$status],'blue');
                            foreach($resourceSummaryStack as $resource=>$statusGroup) // MCB added 21 June 2011
                                $avgByResource[$resource] = avgByResource($statusGroup,$calledProgram,$epsReportProgram,'program');
                            
                            $assetCount         = prepareAssetCount($resourceDetailStack[$resourceIdentifier]);
                            $assetCountString   = $assetCount['base'];
                            $assetCountString  .= isset($assetCount['exceptions']) ? ' '.$assetCount['exceptions'] : null;
        
                            $viewProgramDetail .=   '"Program",'.
                                                    '"Resource",' .
                                                    '"Resource ID",' .
                                                    '"CSO KW",' .
                                                    '"Total Delta KW",' .
                                                    '"% Perf.",' .
                                                    '"Total # Assets",' .
                                                    '"Updated Date"'  . "\n"; 
        
                            
                            $perfPercent = performancePercent($avgByResource[$resourceIdentifier]['fcmDelta'],$CSOs[$calledProgram][$resourceIdentifier]);
        
                            $rName = is_object($epsLineItem) ? $epsLineItem->resource() : $epsLineItem['resourceDescription'];


                            $viewProgramDetail .=   '"'.$calledProgramAbbrev.'"'. ',' .
                                                    '"'. $rName . ' Summary "' . ',' .
                                                    '"'.$resourceIdentifier . '"' . ',' .
                                                    '"'.round($CSOs[$calledProgram][$resourceIdentifier],3) . '"' . ',' .
                                                    '"'.round($avgByResource[$resourceIdentifier]['fcmDelta'],3) . '"' . ',' .
                                                    '"'.$perfPercent['value'] . '"' . ',' .
                                                    $assetCountString . ',' .
                                                    '"'.$avgByResource[$resourceIdentifier]['updatedDate'] . '"' . "\n"
                                                    ;
        
                /*  ===========================================================================
                    Program Response Details CSV ---------------------------------------------- */   

                        $thisProgramResourceDetail = str_replace(" ", "_", $rName) . '_program_resource_detail';
                        if(array_search($thisProgramResourceDetail ,$_SESSION['evtSummarySections']) !== false)
                        { 
                            $viewProgramDetail .= "----------------------------------\n".
                                                                    'Program, ' .
                                                                    'Resource,' .
                                                                    'Asset,' .
                                                                    'Asset ID,' .
                                                                    'Adj KW,' .
                                                                    'Est. KW Capability,' .												
                                                                    'Hr. Avg.,' .
                                                                    '%Est. Capability,' . 
                                                                    'Start,' .
                                                                    'End,' .
                                                                    'Updated Date' . "\n" ;
                            
                            foreach($resourceDetailStack[$resourceIdentifier] as $resourceId=>$detailStack) 
                            {  
                                foreach($detailStack as $inx=>$detailLineItem)
                                {
                                    $viewProgramDetail .= 	'"'.$calledProgramAbbrev . '"' . ',' .
                                                            '"'.$rName . '"' . ',' .
                                                            '"'.$detailLineItem['asset'] . '"' . ',' .
                                                            '"'.$detailLineItem['assetId'] . '"' . ',' .
                                                            '"'.$detailLineItem['adjustment'] . '"' . ',' .
                                                            '"'.$detailLineItem['committedReduction'] . '"' . ',' .
                                                            '"'.$detailLineItem['fcmDelta'] . '"' . ',' .
                                                            '"'.$detailLineItem['fcmPCR'] . '"'  . ',' .
                                                            '"'.$detailLineItem['start'] . '"'  . ',' .
                                                            '"'.$detailLineItem['end'] . '"'  . ',' .
                                                            '"'.$detailLineItem['updatedDate'] . '"' . "\n";
                                }
                            }
                            $viewProgramDetail .=  "----------------------------------\n";
                        }
                } // $epsAssetStack
            } // $dispatchable
        }   // called_program_resource_detail | 
                    // array_search($thisProgramDetails,$_SESSION['evtSummarySections'])
    
             /*  ===========================================================================
                Program Summary :   CSV Data
                =========================================================================== */                

                if(isset($epsReportProgram) && !empty($epsReportProgram))
                {            
                    $perfPercent = performancePercent($epsReportProgram[$calledProgram]->fcmDelta(),$CSOs[$calledProgram]['value']);
    
                    $effectiveTimeParts     = explode(' ', $epsReportProgram[$calledProgram]->effectiveTime());
                    $restorationTimeParts   = explode(' ', $epsReportProgram[$calledProgram]->restorationTime());
    
                    $csoCalledProgramValueString = $CSOs[$calledProgram]['value'];
                    $reportProgramCalledProgramFCMDelta = $epsReportProgram[$calledProgram]->fcmDelta();
                    $reportProgramCalledProgramUpdatedDate = $epsReportProgram[$calledProgram]->updatedDate();
                    $displayPerformancePercent = $perfPercent['value'];
                }
                else
                {
                    $perfArray['value'] = 'No Data';
                    $perfArray['color'] = 'red';
    
                    $csoCalledProgramValueString = "No Data";
                    $reportProgramCalledProgramFCMDelta = "No Data"; 
                    $reportProgramCalledProgramUpdatedDate = $epsLineItem['updated'];
                    $displayPerformancePercent = "No Data";
                }

                $viewProgramSummary .=  $calledProgram . ',' .
                                        '"'. $csoCalledProgramValueString . '"' . ',' .
                                        '"'. $reportProgramCalledProgramFCMDelta . '"' . ',' .
                                        '"'.$perfPercent['value'] . '"' . ',' .
                                        '"'. $reportProgramCalledProgramUpdatedDate . '"' . "\n";
                 
    /*  ===========================================================================
        Program Zone Summary CSV
        =========================================================================== */
                if(array_search(str_replace(" ", "_", $calledProgram) . '_zone_summary',$_SESSION['evtSummarySections']) !== false)
                {
                    $viewZoneSummary .= $calledProgram . ' Zone Summary' . "\n" .
                                                         'Called Zone,' .
                                                         'Total CSO KW,' .
                                                         'Hr. Avg.,' .
                                                         '%CSO' . 
                                                         'Updated Date' . "\n";
                    
                    foreach ($calledZones[$calledProgram] as $inx=>$calledZone) 
                    {
                            foreach($zoneStack[$calledProgram][$calledZone] as $resourceIdentifier=>$assetStack)
                            {
                                foreach($assetStack as $inx=>$epsLineItem) 
                                {
                                    if(is_object($epsLineItem))
                                    {
                                        $resourceZoneSummaryStack[$resourceIdentifier][$status][] = array(
                                            'resourceCR' => str_replace(',','',$epsLineItem->resourceCR()),
                                            'adjustment' => str_replace(',','',$epsLineItem->adjustment()),
                                            'committedReduction' => str_replace(',','',$epsLineItem->committedReduction()),
                                            'fcmDelta' => str_replace(',','',$epsLineItem->fcmDelta()),
                                            'fcmPCR' => str_replace(',','',$epsLineItem->fcmPCR())
                                        );
            
                                         $resourceZoneDetailStack[$resourceIdentifier][$status][] = array(
                                            'asset' => $epsLineItem->asset(),
                                            'assetId' => $epsLineItem->assetID(),
                                            'updatedDate' => $epsLineItem->updatedDate(),
                                            'adjustment' => str_replace(',','',$epsLineItem->adjustment()),
                                            'committedReduction' => str_replace(',','',$epsLineItem->committedReduction()),
                                            'fcmDelta' => str_replace(',','',$epsLineItem->fcmDelta()),
                                            'fcmPCR' => str_replace(',','',$epsLineItem->fcmPCR())
                                        );
                                    }
                                    else
                                    {
                                        $resourceZoneSummaryStack[$resourceIdentifier][$status][] = array(
                                            'resourceCR' => 0,
                                            'adjustment' => 0,
                                            'committedReduction' => 0,
                                            'fcmDelta' => 0,
                                            'fcmPCR' => 0
                                        );
            
                                        $resourceZoneDetailStack[$resourceIdentifier][$status][] = array(
                                            'asset' => $epsLineItem['description'],
                                            'assetId' => $inx ,
                                            'updatedDate' => $epsLineItem['updated'],
                                            'adjustment' => 'No Data',
                                            'committedReduction' => 'No Data',
                                            'fcmDelta' => 'No Data',
                                            'fcmPCR' => 'No Data'
                                        );
                                    }
                                }
                                
                                foreach($resourceZoneSummaryStack as $resource=>$assetDetail) // MCB added 21 June 2011
                                {
                                    $zoneAvgByResource[$resource] = avgByResource($lines,$calledProgram,$epsReportProgram,'zone',$calledZone);
                                    $numberOfAssets = count($assetDetail);
                                    
                                    $totalFCMDelta = 0;
                                    
                                    $zoneAvgByResource[$resource]['fcmDelta'] = 0;
                                    $zoneAvgByResource[$resource]['updatedDate'] = $epsReportZone[$calledProgram][$calledZone]->updatedDate();
            
                                    foreach($assetDetail as $inx=>$assetInfo)
                                    {
                                        if(!isset($zoneAvgByResource[$resource]['resourceID']))
                                            $zoneAvgByResource[$resource]['resourceID'] = $resourceIdentifier;
    
                                        $totalFCMDelta += $assetInfo['fcmDelta'];
                                        if($assetInfo['status'] == 'out')
                                                    $zoneAvgByResource[$resource]['noDataCount']++;
                                    }
                                    
                                    $zoneAvgByResource[$resource]['fcmDelta'] = $totalFCMDelta;
                                    $zoneAvgByResource[$resource]['updatedDate'] = $epsReportZone[$calledProgram][$calledZone]->updatedDate();   
                                }
    
    /*  ===========================================================================
        Program Zone Details CSV
        =========================================================================== */
                        if( $_SESSION['evtSummarySections'][0] == 'all' || 
                            array_search(str_replace(" ", "_", $calledProgram . "_" . $calledZone) . '_detail',$_SESSION['evtSummarySections']) !== false)
                        {
                            // Give zone details...
                            $viewZoneDetail .= 	$calledProgram . " -- " . $calledZone . ' Detail' . "\n" .
                            'Zone -- Resource,' .
                            'Resource Id,' . 
                            'Adj KW,' .
                            'Est. KW Capability,' .
                            'Hr. Avg.,' .
                            '%Est. Capability,' . 
                            '# of Assets' . "\n";
            
                                $assetCountString = $zoneAvgByResource[$resource]['noDataCount'] > 0 ? '"' . $numberOfAssets . ' ('.$zoneAvgByResource[$resource]['noDataCount'].' no data)"' : $numberOfAssets;
    
    
                                $viewZoneDetail .=  ',' .
                                                '"Resource ID",' .
                                                '"CSO KW",' .
                                                '"Total Delta KW",' .
                                                '"% Perf.",' .
                                                '"Total # Assets",' .
                                                '"Updated Date"'  . "\n"; 

                                $perfPercent = performancePercent($zoneAvgByResource[$resource]['fcmDelta'],$CSOs[$calledProgram][$calledZone][$zoneAvgByResource[$resource]['resourceID']]);
    
                                $viewZoneDetail .= 	'"'.$calledZone .' -- ' . $epsLineItem->resource() . ' Summary "' . ',' .
                                                        '"'.$zoneAvgByResource[$resource]['resourceID'] . '"' . ',' .
                                                        '"'.round($CSOs[$calledProgram][$calledZone][$zoneAvgByResource[$resource]['resourceID']],3) . '"' . ',' .
                                                        '"'.round($zoneAvgByResource[$resource]['fcmDelta'],3) . '"' . ',' .
                                                        '"'.$perfPercent['value']. '"' . ',' .
                                                        $assetCountString . ',' .
                                                        '"'.$zoneAvgByResource[$resource]['updatedDate'] . '"'  . "\n"; 
    
                                
                                if( $_SESSION['evtSummarySections'][0] == 'all' || array_search(str_replace(" ", "_", $epsLineItem->resource()) . '_zone_resource_detail',$_SESSION['evtSummarySections']) !== false)
                                {
                                    $viewZoneDetail .= 	$calledProgram . " -- " . $calledZone . ' Detail' . "\n" .
                                        'Resource,' .
                                        'Asset,' .
                                        'Asset ID,' .
                                        'Adj KW,' .
                                        'Est. KW Capability,' .
                                        'Hr. Avg.,' .
                                        '%Est. Capability,' . 
                						'Updated Date' . "\n";
    
                                    foreach($resourceZoneDetailStack as $resourceName=>$assetDetail)
                                    {
                                        foreach($assetDetail as $detailLineItem)
                                        {
                                            $viewZoneDetail .= 	'"' .  $epsLineItem->resource() . '"' . ',' .
                                                                    '"' . $detailLineItem['asset'] . '"' . ',' .
                                                                    '"'.$detailLineItem['assetId'] . '"' . ',' .
                                                                    '"'.$detailLineItem['adjustment'] . '"' . ',' .
                                                                    '"'.$detailLineItem['committedReduction'] . '"' . ',' .
                                                                    '"'.$detailLineItem['fcmDelta'] . '"' . ',' .
                                                                    '"'.$detailLineItem['fcmPCR'] . '"'  . ',' .
                                                                    '"'.$detailLineItem['updatedDate'] . '"' . "\n";
                                        }
                                        //$viewZoneDetail .=  "----------------------------------\n";
                                    }
                                } // if zone resource detail
                            } 
                        } 

                        $perfPercent = performancePercent($epsReportZone[$calledProgram][$calledZone]->fcmDelta(),$CSOs[$calledProgram][$calledZone]['value']);

                        $viewZoneSummary .= '"'.$calledZone . '"' . ',' .
                                            '"'.$CSOs[$calledProgram][$calledZone]['value'] . '"' . ',' .
                                            '"'.$epsReportZone[$calledProgram][$calledZone]->fcmDelta() . '"' . ',' .
                                            '"'. $perfPercent['value'] . '"'  . ',' .
                                            '"'.$epsReportZone[$calledProgram][$calledZone]->updatedDate() . '"' . "\n";
                    }
                    
                } // if zone summary
            }

            return $viewEventSummary . $viewProgramSummary . "\n----------------------------------\n" . $viewProgramDetail . $viewZoneSummary . $viewZoneDetail;
    } // renderCSV()

/*  ===========================================================================
    FUNCTION : renderHTML()
    =========================================================================== */    
    function renderHMTL($programStack, $zoneStack)
    {
        //preDebugger($programStack,'red');
        global $CSOs, $oUser;
        global $calledPrograms, $epsReportProgram, $epsReportProgramDetails;
        global $calledZones, $epsReportZone, $epsReportZoneDetails;
        global $PointChannels;
        global $viewEventSummary;

        $modified = array();
        $modifiedIndex = 0;
        $modifiedOutput = null;
    	$modifiedFinal = null;

        $viewProgramDetail = "";
        $viewZoneSummary = "";
        $viewZoneDetail = "";

        $viewProgramSummary = '<!-- START EVENT PERFORMANCE SUMMARY DRAW -->';
        $viewProgramSummary .= '<table class="sortable" width="930" border="1" cellspacing="0" cellpadding="3">' . "\n" .
                                '<thead>' . "\n" .
                                    '<tr>' . "\n" .
                                        '<th colspan="8">Program Summary</th>' . "\n" .
                                    '</tr>' . "\n" .
                                    '<tr>' . "\n" .
                                        '<th id="psCalledProgram" class="psCalledProgramTip">Called Program</th>' . "\n" .
                                        '<th id="psZonal" class="psZonalTip">Zonal</th>' . "\n" .
                                        '<th id="psTotalCr" class="psTotalCrTip">Total CSO KW</th>' . "\n" .
                                        '<th id="psFcmDelta" class="psFcmDeltaTip">Hr. Avg.</th>' . "\n" .
                                        '<th id="psFcmCr" class="psFcmCrTip">%CSO</th>' . "\n" .
                                        '<th id="psUpdated" width="80">Updated Date</th>' . "\n" .
                                    '</tr>' . "\n" .
                                 '</thead>' . "\n" .
                                 '<tbody>' . "\n\n";
            
        foreach ($calledPrograms as $inx=>$calledProgram) 
        {   
            $viewProgramDetailLines = array();
            $viewProgramSummaryLines = array();
            
            foreach($programStack[$calledProgram] as $resourceIdentifier=>$assetGroups) 
            {  
                //preDebugger($programStack);
                $modifiedIndex++;
                foreach($assetGroups as $status=>$epsAssetStack) 
                {
                    switch($status)
                    {
                        case 'dispatch':
                            $viewProgramDetailLines[$resourceIdentifier][$status][] = '<tr><th colspan="9" style="text-align: left; background-color: #0E3651;">Dispatched</th></tr>' . "\n";
                            break;
                        case 'doNotDispatch':
                            $viewProgramDetailLines[$resourceIdentifier][$status][] = '<tr><th colspan="9" style="text-align: left; background-color: #0E3651;">Not Dispatched</th></tr>' . "\n";
                            break;
                        default:                        
                            $viewProgramDetailLines[$resourceIdentifier][$status][] = '<tr><th colspan="9" style="text-align: left; background-color: #0E3651;">Status Unknown</th></tr>' . "\n";
                    }

                    foreach($epsAssetStack as $inx=>$epsLineItem) 
                    {
                        //preDebugger($epsAssetStack,'green');
                        if(is_object($epsLineItem))
                        {
                            if($epsLineItem->wasUpdated())
                            {
                                $modified[$epsLineItem->updatedDate()][$calledProgram][$modifiedIndex]['name'] = $epsLineItem->resource();
                                $modified[$epsLineItem->updatedDate()][$calledProgram][$modifiedIndex]['assets'][$epsLineItem->assetID()] = htmlspecialchars($epsLineItem->asset());
                            }
                            
                            $fcmColor = (is_numeric(str_replace(',','',$epsLineItem->fcmPCR()))?(str_replace(',','',$epsLineItem->fcmPCR()) < 75?"red":(str_replace(',','',$epsLineItem->fcmPCR()) < 100?"orange":"green")):"transparent");
                            
                            //$dispatchDetail = prepareDispatchString($epsLineItem->isDispatched(), $epsLineItem->dispatchedTime(), $epsLineItem->effectiveTime(), $epsLineItem->isRestored(), $epsLineItem->restorationTime());
                            $dispatchDetailString = '';
                            
                            $effectiveTimeParts     = explode(' ', $epsLineItem->effectiveTime());
                            $restorationTimeParts   = explode(' ', $epsLineItem->restorationTime());

                            $start = explode(':',$effectiveTimeParts[1]);
                            $end = explode(':',$restorationTimeParts[1]);
        
                            $start = $start[0] . ':' . $start[1];
                            $end = $end[0] . ':' . $end[1];


                            $viewProgramDetailLines[$resourceIdentifier][$status][] = '<tr>' .  "\n" .                                       
                                                    '<td align="left" class="htmlspecial">' . htmlspecialchars($epsLineItem->asset()) . $dispatchDetailString . '</td>' . "\n" .
                                                    '<td align="right">' . $epsLineItem->assetID() . '</td>' . "\n" .
                                                    '<td align="right">' . $epsLineItem->adjustment() . '</td>' . "\n" .
                                                    '<td align="right">' . $epsLineItem->committedReduction() . '</td>' . "\n" .
                                                    '<td align="right">' . $epsLineItem->fcmDelta() . '</td>' . "\n" .
                                                    '<td align="right" style="font-weight: bold; background-color: ' . $fcmColor . ';">' . $epsLineItem->fcmPCR() . '</td>' . "\n" .
                                                    '<td align="right">' . $start . '</td>' . "\n" .
                                                    '<td align="right">' . $end . '</td>' . "\n" .
                                                    '<td align="right">' . $epsLineItem->updatedDate() . '</td>' . "\n" .
                                                 '</tr>' .  "\n";        
                            
                            $viewProgramSummaryLines[$resourceIdentifier][$status][] = array(
                                'resourceID' => $resourceIdentifier,
                                'resourceDesc' => $epsLineItem->resource(),
                                'resourceCR' => str_replace(',','',$epsLineItem->resourceCR()),
                                'adjustment' => str_replace(',','',$epsLineItem->adjustment()),
                                'committedReduction' => str_replace(',','',$epsLineItem->committedReduction()),
                                'fcmDelta' => str_replace(',','',$epsLineItem->fcmDelta()),
                                'fcmPCR' => str_replace(',','',$epsLineItem->fcmPCR())
                                );
                        }
                        else
                        {                
                            $viewProgramDetailLines[$resourceIdentifier][$status][] = '<tr>' . "\n" .
                                                    '<td align="left" style="font-weight: bold; color: #FC6701;" class="htmlspecial">' . htmlspecialchars($epsLineItem['description']) . '</td>' . "\n" .
                                                    '<td align="right" style="font-weight: bold; color: #FC6701;">' . $inx . '</td>' . "\n" .
                                                    '<td align="center" style="font-weight: bold; color: #FC6701;">No Data</td>' . "\n" .
                                                    '<td align="center" style="font-weight: bold; color: #FC6701;">No Data</td>' . "\n" .
                                                    '<td align="center" style="font-weight: bold; color: #FC6701;">No Data</td>' . "\n" .
                                                    '<td align="center" style="font-weight: bold; color: #FC6701;">No Data</td>' . "\n" .
                                                    '<td align="right" style="font-weight: bold; color: #FC6701;">' . $epsLineItem['start'] . '</td>' . "\n" .
                                                    '<td align="right" style="font-weight: bold; color: #FC6701;">' . $epsLineItem['end'] . '</td>' . "\n" .
                                                    '<td align="right" style="font-weight: bold; color: #FC6701;">' . $epsLineItem['updated'] . '</td>' . "\n" .
                                                 '</tr>' .  "\n"; 
                               //preDebugger($epsLineItem['resourceDescription'],'red');
                            $viewProgramSummaryLines[$resourceIdentifier][$status][] = array(
                                'resourceID' => $resourceIdentifier,
                                'resourceDesc' => $epsLineItem['resourceDescription'],
                                'resourceCR' => 0,
                                'adjustment' => 0,
                                'committedReduction' => 0,
                                'fcmDelta' => 0,
                                'fcmPCR' => 0
                                );
                            $viewProgramSummaryTitle[$resourceIdentifier]['title'] = $epsLineItem['resourceDescription'];
                            $viewProgramSummaryTitle[$resourceIdentifier]['updated'] = $epsLineItem['updated'];
                            
                        }
                    }
                } // $epsAssetStack
            } // $dispatchable
             //preDebugger($viewProgramSummaryTitle,'red'); 
            foreach($viewProgramSummaryLines as $resource=>$statusGroup)
                $avgByResource[$resource] = avgByResource($statusGroup,$calledProgram,$epsReportProgram,'program');

            
// ======================= begin drawing the tables
// ======================= Give program summary...             
            //preDebugger($epsReportProgram); 

            if(isset($epsReportProgram) && !empty($epsReportProgram))
            {            
                $perfPercent = performancePercent($epsReportProgram[$calledProgram]->fcmDelta(),$CSOs[$calledProgram]['value']);

                $effectiveTimeParts     = explode(' ', $epsReportProgram[$calledProgram]->effectiveTime());
                $restorationTimeParts   = explode(' ', $epsReportProgram[$calledProgram]->restorationTime());

                $csoCalledProgramValueString = number_format($CSOs[$calledProgram]['value']);
                $reportProgramCalledProgramFCMDelta = $epsReportProgram[$calledProgram]->fcmDelta();
                $reportProgramCalledProgramUpdatedDate = $epsReportProgram[$calledProgram]->updatedDate();
                $displayPerformancePercent = $perfPercent['value'];
            }
            else
            {
                $perfArray['value'] = 'No Data';
                $perfArray['color'] = 'red';

                $csoCalledProgramValueString = "No Data";
                $reportProgramCalledProgramFCMDelta = "No Data"; 
                $reportProgramCalledProgramUpdatedDate = $epsLineItem['updated'];
                $displayPerformancePercent = "No Data";                
            }

            $cpDetailName = str_replace(" ", "_", str_replace("&","and",$calledProgram)) . '_detail';
                 
            $viewProgramSummary .= '<tr>' . "\n" .
                                   '<td align="left" class="htmlspecial" ><div style="cursor: pointer;" onClick="ToggleDivVisibility(\'' . $cpDetailName . '\')">' . htmlspecialchars($calledProgram) . '</div></td>' . "\n" .
                                   '<td align="center" class="htmlspecial"><div style="cursor: pointer;" onClick="ToggleDivVisibility(\'' . str_replace(" ", "_", str_replace("&", "and",$calledProgram)) . '_zone_summary\');">Summary</div></td>' . "\n" .
                                   '<td align="right">' . $csoCalledProgramValueString . '</td>' . "\n" .
                                   '<td align="right">' . $reportProgramCalledProgramFCMDelta . '</td>' . "\n" .
                                   '<td align="right" style="font-weight: bold; background-color: ' . $perfPercent['color'] . ';">'. $displayPerformancePercent .'</td>' . "\n" .
                                   '<td align="right">' . $reportProgramCalledProgramUpdatedDate . '</td>' . "\n" .
                           '</tr>'.  "\n";										
            
// ======================= Give program details...            
            $viewProgramDetail .= '<div id="' . str_replace(" ", "_", str_replace("&", "and", $calledProgram)) . '_detail" style="display: none; text-align: left;" class="toggleable">' . "\n" .
                            '<div style="font-weight: bold; color: #CD6701; margin-top: 10px; margin-bottom: 10px;" class="htmlspecial">' . htmlspecialchars($calledProgram) . '</div>' .  "\n" . 
                            '<div style="font-size: 11px; text-align: center;">[Hint: Click on the resource name to toggle details.]</div>' . "\n";

            $sortIndex = 0;

            foreach($viewProgramDetailLines as $resource=>$statusGroups)
            {
                
                $sortIndex++;
                
                $assetCount         = prepareAssetCount($statusGroups);
                $assetCountString   = $assetCount['base'];
                $assetCountString  .= isset($assetCount['exceptions']) ? '<div style="color: #CD6701;">'.$assetCount['exceptions'].'</div>' : null;

                if(isset($avgByResource[$resource]['fcmDelta']))
                {
                    $perfPercent                 = performancePercent($avgByResource[$resource]['fcmDelta'],$CSOs[$calledProgram][$avgByResource[$resource]['resourceID']]);
                    $avgByResourceValue          = $avgByResource[$resource]['resourceID'];
                    $avgByResourceDescription    = $avgByResource[$resource]['resourceDesc'];
                    $avgByResourceUpdatedDate    = $avgByResource[$resource]['updatedDate'];
                    $csoCalledProgramValueString = number_format(round($CSOs[$calledProgram][$avgByResource[$resource]['resourceID']],3));
                    $avgByResourceFCMDeltaString = round($avgByResource[$resource]['fcmDelta'],3);
                }
                else
                {

                    /*[38009] => Array
        (
            [dispatch] => Array
                (
                    [0] => Array
                        (
                            [resourceID] => 38009
                            [resourceDesc] => Dispatched Resource 38009 -- RTEG_50092_Eastern CT (7500) - 2
                */
                    //preDebugger($statusGroups, 'red');
                //preDebugger($viewProgramDetailLines,'orange');
                    $perfPercent                 = array('value'=>'No Data','color'=>'red');
                    $avgByResourceValue          =  $resource;
                    $avgByResourceDescription    = $viewProgramSummaryTitle[$resource]['title'];
                    $avgByResourceUpdatedDate    = $viewProgramSummaryTitle[$resource]['updated'];
                    $csoCalledProgramValueString = "No Data";
                    $avgByResourceFCMDeltaString = "No Data";
                }
                
                
                $viewProgramDetail .=   '<table width="930" border="1" cellspacing="0" cellpadding="3">' . "\n" .
                                            '<thead>' .   "\n" .
                                                '<tr>' . "\n" .
                                                    '<th style="border: none;" width="300">&nbsp;</th>' . "\n" .
                                                    '<th width="50">Res. ID</th>' . "\n" .
                                                    '<th width="90">CSO KW</th>' . "\n" .
                                                    '<th width="100">Total Delta KW</th>' . "\n" .
                                                    '<th width="70">% Perf.</th>' . "\n" .
                                                    '<th width="150">Total # Assets</th>' . "\n" .
                                                    '<th width="70">Updated Date</th>' . "\n" .
                                                '</tr>' . "\n" .
                                            '</thead>' . "\n" .
                                            '<tbody>' . "\n" .
                                                '<tr>' . "\n" .
                                                    '<td style="border: none; cursor: pointer;"><div onClick="ToggleDivVisibility(\'' . str_replace(" ", "_", str_replace("&", "and", $avgByResourceDescription)) . '_program_resource_detail\');">'.htmlspecialchars($avgByResourceDescription). '</div></td>' . "\n" . 
                                                    '<td align="right">' . $avgByResourceValue . '</td>' . "\n" .
                                                    '<td align="right">' . $csoCalledProgramValueString . '</td>' . "\n" .
                                                    '<td align="right">' . $avgByResourceFCMDeltaString . '</td>' . "\n" .
                                                    '<td align="right" style="font-weight: bold; background-color: ' . $perfPercent['color'] . ';">' . $perfPercent['value'] . '</td>' . "\n" .
                                                    '<td align="right">' . $assetCountString . '</td>' . 
                                                    '<td align="right">' . $avgByResourceUpdatedDate . '</td>' . "\n" .
                                                '</tr>' . "\n" .
                                            '</tbody>' . "\n" . 
                                        '</table>' . "\n" . 
                                        
                                    '<div id="' . str_replace(" ", "_", str_replace("&", "and", $avgByResourceDescription)) . '_program_resource_detail" style="display: none;" class="toggleable">' . "\n" . 
                                        '<table width="930" class="sortable" border="1" cellspacing="0" cellpadding="3">' . "\n" .
                                            '<thead>' . "\n" .
                                                '<tr>' . "\n" .
                                                    '<th id="vpdAsset_'.$sortIndex.'" width="300">Asset</th>' . "\n" .
                                                    '<th id="vpdAssetID_'.$sortIndex.'" width="50">Asset<br />ID</th>' . "\n" .
                                                    '<th id="vpdAdjKW_'.$sortIndex.'" width="90">Adj KW</th>' . "\n" .
                                                    '<th id="vpdCRKW_'.$sortIndex.'" width="100">Est. KW Capability</th>' . "\n" .
                                                    '<th id="vpdHrAvg_'.$sortIndex.'">Hr. Avg.</th>' . "\n" .
                                                    '<th width="70" id="vpdPercentCR_'.$sortIndex.'">%Est. Capability</th>' . "\n" .
                                                    '<th id="vpdStart_'.$sortIndex.'">Start</th>' . "\n" .
                                                    '<th id="vpdEnd_'.$sortIndex.'">End</th>' . "\n" .
                                                    '<th id="vpdUpdated_'.$sortIndex.'" width="70">Updated Date</th>' . "\n" .
                                                '</tr>' . "\n" .
                                            '</thead>' . "\n" .
                                            '<tbody>' . "\n";
    
                    //preDebugger($statusGroups);
                    $unknownProgramDetail       = null;
                    $doNotDispatchProgramDetail = null;
                    foreach($statusGroups as $status=>$lines)
                    {   switch($status)
                        {
                        case 'unknown':
                            foreach($lines as $line) $unknownProgramDetail .= $line;
                            break;
                        case 'doNotDispatch':
                            foreach($lines as $line) $doNotDispatchProgramDetail .= $line;
                            break;
                        default:
                            foreach($lines as $line) $viewProgramDetail .= $line;
                        }
                    }
                     $viewProgramDetail .=   $doNotDispatchProgramDetail . $unknownProgramDetail ;
                     $viewProgramDetail .=   '</tbody></table><br /></div>' . "\n";
                }
            

            $viewProgramDetail .= '</div>' . "\n";

            $viewZoneSummary .= '<div id="' . str_replace(" ", "_", str_replace("&", "and", $calledProgram)) . '_zone_summary" style="display: none;">' . "\n" .
                                     '<table width="930" class="sortable" border="1" cellspacing="0" cellpadding="3">' . "\n" .
                                             '<thead>' . "\n" .
                                                     '<tr>' . "\n" .
                                                             '<th colspan="5">' . $calledProgram . ' Zone Summary</th>' . "\n" .
                                                     '</tr>' . "\n" .
                                                     '<tr>' . "\n" .
                                                             '<th id="vzsCalledZone_'.$sortIndex.'" width="360">Called Zone</th>' . "\n" .
                                                             '<th id="vzsTotalCRKW_'.$sortIndex.'" width="110">Total CSO KW</th>' . "\n" .
                                                             '<th id="vzsHrAvg_'.$sortIndex.'">Hr. Avg.</th>' . "\n" .
                                                             '<th width="70" id="vzsPCR_'.$sortIndex.'" width="110">%CSO</th>' . "\n" .
                                                             '<th id="vzsUpdated_'.$sortIndex.'" width="110">Updated Date</th>' . "\n" .
                                                     '</tr>' . "\n" .
                                              '</thead>' . "\n" .
                                              '<tbody>';

            
            foreach ($calledZones[$calledProgram] as $inx=>$calledZone) 
            { 
// ======================= Give zone details...
                $viewZoneDetail .= '<div id="' . str_replace(" ", "_", str_replace("&", "and", $calledProgram) . "_" . str_replace("&", "and", $calledZone)) . '_detail" style="display: none; text-align: left;">' . "\n" .
                                        '<div style="font-weight: bold; color: #CD6701; margin-top: 10px; margin-bottom: 10px;" class="htmlspecial">' . htmlspecialchars($calledProgram) . '<br />'. htmlspecialchars($calledZone) .' Detail</div>' .  "\n" .
                                        '<div style="font-size: 11px; text-align: center;">[Hint: Click on the resource name to toggle details.]</div>' . "\n";
                                            
                
                $viewZoneDetailLines = '';

                foreach($zoneStack[$calledProgram][$calledZone] as $resourceIdentifier=>$assetGroups)
                {
                    foreach($assetGroups as $status=>$epsAssetStack) 
                    {
                        switch($status)
                        {
                        case 'unknown':
                            $viewZoneDetailLines[$resourceIdentifier][$status][] = '<tr><td colspan="9" style="text-align: left; background-color: #0E3651;">Status Unknown</td></tr>' . "\n";
                            break;
                        case 'doNotDispatch':
                            $viewZoneDetailLines[$resourceIdentifier][$status][] = '<tr><td colspan="9" style="text-align: left; background-color: #0E3651;">Not Dispatched</td></tr>' . "\n";
                            break;
                        default:
                            $viewZoneDetailLines[$resourceIdentifier][$status][] = '<tr><td colspan="9" style="text-align: left; background-color: #0E3651;">Dispatched</td></tr>' . "\n";
                        }
                        foreach($epsAssetStack as $inx=>$epsLineItem) 
                        {
                            //preDebugger($epsLineItem);
                            
                            if(is_object($epsLineItem))
                            {
                                $effectiveTimeParts     = explode(' ', $epsLineItem->effectiveTime());
                                $restorationTimeParts   = explode(' ', $epsLineItem->restorationTime());

                                $start = explode(':',$effectiveTimeParts[1]);
                                $end = explode(':',$restorationTimeParts[1]);
            
                                $start = $start[0] . ':' . $start[1];
                                $end = $end[0] . ':' . $end[1];
                                
                                $fcmColor = (is_numeric(str_replace(',','',$epsLineItem->fcmPCR()))?(str_replace(',','',$epsLineItem->fcmPCR()) < 75?"red":(str_replace(',','',$epsLineItem->fcmPCR()) < 100?"orange":"green")):"transparent");
                
                                $viewZoneDetailLines[$resourceIdentifier][$status][] = '<tr>' . "\n" .
                                                        '<td align="left" class="htmlspecial">' . htmlspecialchars($epsLineItem->asset()) . '</td>' . "\n" .
                                                        '<td align="left">' . $epsLineItem->assetID() . '</td>' . "\n" .
                                                        '<td align="right">' . $epsLineItem->adjustment() . '</td>' . "\n" .
                                                        '<td align="right">' . $epsLineItem->committedReduction() . '</td>' . "\n" .
                                                        '<td align="right">' . $epsLineItem->fcmDelta() . '</td>' . "\n" .
                                                        '<td align="right" style="font-weight: bold; background-color: ' . $fcmColor . ';">' . $epsLineItem->fcmPCR() . '</td>' . "\n" .
                                                        '<td align="right">'. $start .'</td>' . "\n" .
                                                        '<td align="right">'. $end . "\n" .
                                                        '<td align="right">' . $epsLineItem->updatedDate() . '</td>' . "\n" .
                                                     '</tr>' .  "\n";  
                                
                                $viewZoneSummaryLines[$resourceIdentifier][$status][] = array(
                                    'resourceID' => $resourceIdentifier,
                                    'resourceDesc' => $epsLineItem->resource(),
                                    'resourceCR' => str_replace(',','',$epsLineItem->resourceCR()),
                                    'adjustment' => str_replace(',','',$epsLineItem->adjustment()),
                                    'committedReduction' => str_replace(',','',$epsLineItem->committedReduction()),
                                    'fcmDelta' => str_replace(',','',$epsLineItem->fcmDelta()),
                                    'fcmPCR' => str_replace(',','',$epsLineItem->fcmPCR())
                                );
                                                         
                            }
                            else
                            {
                                $viewZoneDetailLines[$resourceIdentifier][$status][] = '<tr>' . "\n" .
                                                '<td align="left" style="font-weight: bold; color: #FC6701;" class="htmlspecial">' . htmlspecialchars($epsLineItem['description']) . '</td>' . "\n" .
                                                '<td align="left" style="font-weight: bold; color: #FC6701;">' . $inx . '</td>' . "\n" .
                                                '<td align="center" style="font-weight: bold; color: #FC6701;">No Data</td>' . "\n" .
                                                '<td align="center" style="font-weight: bold; color: #FC6701;">No Data</td>' . "\n" .
                                                '<td align="center" style="font-weight: bold; color: #FC6701;">No Data</td>' . "\n" .
                                                '<td align="center" style="font-weight: bold; color: #FC6701;">No Data</td>' . "\n" .
                                                '<td align="center" style="font-weight: bold; color: #FC6701;">' . $epsLineItem['start'] . '</td>' . "\n" .
                                                '<td align="center" style="font-weight: bold; color: #FC6701;">' . $epsLineItem['end'] . '</td>' . "\n" .
                                                '<td align="center" style="font-weight: bold; color: #FC6701;">' . $epsLineItem['updated'] . '</td>' . "\n" .
                                             '</tr>' .  "\n"; 
    
                                $viewZoneSummaryLines[$resourceIdentifier][$status][] = array(
                                    'resourceID' => $resourceIdentifier,
                                    'resourceDesc' => $epsLineItem['resourceDescription'],
                                    'resourceCR' => 0,
                                    'adjustment' => 0,
                                    'committedReduction' => 0,
                                    'fcmDelta' => 0,
                                    'fcmPCR' => 0
                                );
                            }
                        }
                    }
                }//$zoneStack[$calledProgram] as $resourceIdentifier=>$assetStack

                
                foreach($viewZoneSummaryLines as $resource=>$statusGroup)
                    $zoneAvgByResource[$resource] = avgByResource($statusGroup,$calledProgram,$epsReportProgram,'program');                
                
            // Give zone summary...


                if(isset($epsReportZone) && !empty($epsReportZone))
                {            
                    $perfPercent = performancePercent($epsReportZone[$calledProgram][$calledZone]->fcmDelta(),$CSOs[$calledProgram][$calledZone]['value']);
    
                    $effectiveTimeParts     = explode(' ', $epsReportProgram[$calledProgram]->effectiveTime());
                    $restorationTimeParts   = explode(' ', $epsReportProgram[$calledProgram]->restorationTime());
    
                    $csoCalledZoneValueString = number_format($CSOs[$calledProgram][$calledZone]['value']);
                    $reportZoneCalledZoneFCMDelta = $epsReportZone[$calledProgram][$calledZone]->fcmDelta();
                    $reportZoneCalledZoneUpdatedDate = $epsReportZone[$calledProgram][$calledZone]->updatedDate();
                    $displayPerformancePercent = $perfPercent['value'];
                }
                else
                {
                    $perfPercent['value'] = 'No Data';
                    $perfPercent['color'] = 'red';
    
                    $csoCalledZoneValueString = "No Data";
                    $reportZoneCalledZoneFCMDelta = "No Data"; 
                    $reportZoneCalledZoneUpdatedDate = $epsLineItem['updated'];
                    $displayPerformancePercent = "No Data";
                }                

                $viewZoneSummary .= '<tr>' . "\n" .
                                        '<td align="left" class="htmlspecial">
                                            <div style="cursor: pointer;" onClick="ToggleDivVisibility(\'' . str_replace(" ", "_", str_replace("&", "and",$calledProgram) . "_" . str_replace("&", "and",$calledZone)) . '_detail\');">' . htmlspecialchars($calledZone) . '</div></td>' . "\n" .
                                        '<td align="right" style="padding-right: 15px;">' . $csoCalledZoneValueString . '</td>' . "\n" .
                                        '<td align="right" style="padding-right: 15px;">' . $reportZoneCalledZoneFCMDelta . '</td>' . "\n" .
                                        '<td align="right" style="font-weight: bold; background-color: ' . $perfPercent['color'] . ';padding-right: 15px;">' . $perfPercent['value'] . '</td>' . "\n" .
                                        '<td align="right" style="padding-right: 15px;">' . $reportZoneCalledZoneUpdatedDate . '</td>' . "\n" .
                                    '</tr>' .  "\n";

                $zoneSortIndex = 0;
                foreach($viewZoneDetailLines as $resource=>$lines)
                {
                    $zoneSortIndex++;
                                        
                    $assetCount         = prepareAssetCount($lines);
                    $assetCountString   = $assetCount['base'];
                    $assetCountString  .= isset($assetCount['exceptions']) ? '<div style="color: #CD6701;">'.$assetCount['exceptions'].'</div>' : null;

                    if(isset($zoneAvgByResource[$resource]['fcmDelta']))
                    {
                        $perfPercent                     = performancePercent($zoneAvgByResource[$resource]['fcmDelta'],$CSOs[$calledProgram][$calledZone][$zoneAvgByResource[$resource]['resourceID']]);
                        $zoneAvgByResourceValue          = $zoneAvgByResource[$resource]['resourceID'];
                        $zoneAvgByResourceDescription    = $zoneAvgByResource[$resource]['resourceDesc'];
                        $zoneAvgByResourceUpdatedDate    = $zoneAvgByResource[$resource]['updatedDate'];
                        $csoCalledZoneValueString        = number_format(round($CSOs[$calledProgram][$avgByResource[$resource]['resourceID']],3));
                        $zoneAvgByResourceFCMDeltaString = number_format(round($CSOs[$calledProgram][$calledZone][$zoneAvgByResource[$resource]['resourceID']],3));
                    }
                    else
                    {
                        $perfPercent                     = array('value'=>'No Data','color'=>'red');
                        $zoneAvgByResourceValue          =  $resource;
                        $zoneAvgByResourceDescription    = $epsLineItem['resourceDescription'];
                        $zoneAvgByResourceUpdatedDate    = $epsLineItem['updated'];
                        $csoCalledZoneValueString        = "No Data";
                        $zoneAvgByResourceFCMDeltaString = "No Data";
                    }

                    $viewZoneDetail .=   '<table width="930" border="1" cellspacing="0" cellpadding="3">' . "\n" .
                                        '<thead>' .   "\n" .
                                            '<tr>' . "\n" .
                                                '<th style="border: none;" width="300" >&nbsp;</th>' . "\n" .
                                                '<th width="50">Res. ID</th>' . "\n" .
                                                '<th width="90">CSO KW</th>' . "\n" .
                                                '<th width="100">Total Delta KW</th>' . "\n" .
                                                '<th width="70">% Perf.</th>' . "\n" .
                                                '<th>Total # Assets</th>' . "\n" .
                                                '<th width="70">Updated Date</th>' . "\n" .
                                            '</tr>' . "\n" .
                                        '</thead>' . "\n" .
                                        '<tbody>' . "\n" .
                                            '</tr>' . "\n" .
                                                '<td style="border: none; cursor: pointer;"><div onClick="ToggleDivVisibility(\'' . str_replace(" ", "_", str_replace("&", "and", $zoneAvgByResourceDescription)) . '_zone_resource_detail\');">'.htmlspecialchars($zoneAvgByResourceDescription). '</div></td>' . "\n" . 
                                                '<td align="right">' . $zoneAvgByResourceValue . '</td>' . "\n" .
                                                '<td align="right">' . $csoCalledZoneValueString . '</td>' . "\n" .
                                                '<td align="right">' . $zoneAvgByResourceFCMDeltaString . '</td>' . "\n" .
                                                '<td align="right" style="font-weight: bold; background-color: ' . $perfPercent['color'] . ';">' . $perfPercent['value'] . '</td>' . "\n" .
                                                '<td align="right" width="130">' . $assetCountString . '</td>' .
                                                '<td align="right">' . $zoneAvgByResourceUpdatedDate . '</td>' . "\n" .
                                            '</tr>' . "\n" .
                                        '</tbody>' . "\n" . 
                                    '</table>' . "\n" . 
                                    
                                    '<div id="' . str_replace(" ", "_", str_replace("&", "and", $zoneAvgByResourceDescription)) . '_zone_resource_detail" style="display: none;">' . "\n" . 
                                        '<table class="sortable" width="930" border="1" cellspacing="0" cellpadding="3">' . "\n" .
                                            '<thead>' . "\n" .
                                                '<tr>' . "\n" .
                                                    '<th colspan="9" class="htmlspecial">'.htmlspecialchars($zoneAvgByResourceDescription).'</th>' . "\n" .
                                                '</tr>' . "\n" .
                                                '<tr>' . "\n" .
                                                    '<th id="vzdAsset">Asset</th>' . "\n" .
                                                    '<th id="vzdAssetID" width="50">Asset<br />ID</th>' . "\n" .
                                                    '<th id="vzdAdjKW" width="90">Adj KW</th>' . "\n" .
                                                    '<th id="vzdCRKW" width="100">Est. KW Capability</th>' . "\n" .
                                                    '<th id="vzdHrAvg">Hr. Avg.</th>' . "\n" .
                                                    '<th id="vzdPCR" width="70">%Est. Capability</th>' . "\n" .
                                                    '<th id="vzdStart" width="50">Start</th>' . "\n" .
                                                    '<th id="vzdEnd" width="50">End</th>' . "\n" .
                                                    '<th id="vzdUpdated" width="70">Updated Date</th>' . "\n" .
                                                '</tr>' . "\n" .
                                            '</thead>' . "\n" .
                                            '<tbody>';

                    $unknownZoneDetail       = null;
                    $doNotDispatchZoneDetail = null;
                    
                    foreach($lines as $status=>$statusSet)
                    {   
                        switch($status)
                        {
                        case 'unknown':
                            foreach($statusSet as $line) $unknownZoneDetail .= $line;
                            break;
                        case 'doNotDispatch':
                            foreach($statusSet as $line) $doNotDispatchZoneDetail .= $line;
                            break;
                        default:
                            foreach($statusSet as $line) $viewZoneDetail .= $line;
                        }

                        
                    }
                    $viewZoneDetail .=   $doNotDispatchZoneDetail . $unknownZoneDetail ;
                    $viewZoneDetail .=   '</tbody></table><br /></div>' . "\n";
    
                }
    
                $viewZoneDetail .= '</div>' . "\n";

            } // $calledZones[$calledProgram] as $inx=>$calledZone
        
            $viewZoneSummary .= '</tbody></table></div><!-- closing summary div -->' . "\n";
        }

        $viewProgramSummary .= '</tbody></table><!-- END EVENT PERFORMANCE SUMMARY DRAW -->' . "\n";        
        
        if(count($modified) > 0)
        {
           $modifiedFinal = null; 
            foreach($modified as $date=>$program)
            {
                $dateTally = 0;
                $modifiedOutput = null;
                foreach($program as $programName=>$resources)
                {
                    $modifiedOutput .= '<strong>' . $programName . '</strong><br />';
                    
                    foreach($resources as $inx=>$resource)
                    {
                        $modifiedOutput .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $resource['name'] . '<br />';
    
                        foreach($resource['assets'] as $id=>$name)
                        {
                            $modifiedOutput .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $id . ': ' . $name . '<br />';
                            $dateTally++;
                        }
                    }
                }
                $plural = $dateTally == 1 ? 'asset' : 'assets';
                $modifiedFinal .= '<div style="padding-bottom: 20px;"><strong>On '. $date .' the performance data for '. $dateTally .' ' . $plural . ' was updated.  
                                            <span onClick="ToggleDivVisibility(\'modifiedToggle'.$date.'\')" style="color: #CD6701; cursor: pointer;">Click here for full list.</span>
                                        </strong>
                                        <div id="modifiedToggle'.$date.'" style="display: none; margin-top: 10px; ">'.$modifiedOutput.'
                                            <span onClick="ToggleDivVisibility(\'modifiedToggle'.$date.'\')" style="color: #CD6701; cursor: pointer;">[ Hide ]</span>
                                        </div>
                                   </div>';
            }
        }

        return $viewEventSummary . '<div id="eventSummaryContainerDiv">' . $modifiedFinal .  $viewProgramSummary . $viewProgramDetail . $viewZoneSummary . $viewZoneDetail . '</div><!-- eventSummaryContainerDiv -->';
    } // renderHMTL()

?>

