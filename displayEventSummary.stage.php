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

//Sets flag which is checked by objects to limit them to being called from a page
//with this flag set. Objects will not run without this flag.

if (!defined('APPLICATION')) define('APPLICATION', TRUE);
//if (session_id() == "") session_start();

define('UNID',uniqid());
function viewEventSummary($domainID, $eventDate, $CSVFlag)
{
	$userObject = clone $_SESSION['UserObject'];
/* this is a fix for the date not coming in the way we want -- dont' want to tip the apple cart 2010.06.13 */
    $dateParts = explode('-',$eventDate);
    $month = $dateParts[0];
    $day = $dateParts[1];
    $year = $dateParts[2];

    $lineCount = 0;
    $modified = array();
    $modifiedIndex = 0;
    $modifiedOutput = null;
	$modifiedFinal = null;

    $eventDate = $year.'-'.$month.'-'.$day;

    $eventPerformanceSummary = new EventPerformanceSummary($domainID, $eventDate);
    $eventPerformanceSummary->Get();

	//$userObject->preDebugger($eventPerformanceSummary);

    $calledPrograms = $eventPerformanceSummary->calledPrograms();

    $calledZones = $eventPerformanceSummary->calledZones();

    $epsReportProgram = $eventPerformanceSummary->reportProgram();
    //$userObject->preDebugger($epsReportProgram);
    $epsReportProgramDetails = $eventPerformanceSummary->reportProgramDetails();
    $epsReportZone = $eventPerformanceSummary->reportZone();
    $epsReportZoneDetails = $eventPerformanceSummary->reportZoneDetails();
    //$userObject->preDebugger($epsReportProgramDetails,'#989800');
    $viewEventSummary = BuildEventSummaryHeader("Event Summary", $userObject->localDomain()->description(), $eventDate, $domainID, $CSVFlag);

    $PointChannels = new PointChannels();
    $PointChannels->Load($userObject->id(),$userObject->Domains(0)->id(),null,null,true,$month,$year);     
    //$userObject->preDebugger($PointChannels);
	//$userObject->preDebugger($calledZones);

    //$userObject->preDebugger($PointChannels->Resources());
    foreach ($calledPrograms as $inx=>$calledProgram) 
    {
        //$userObject->preDebugger($PointChannels->Resources(),'#980000');    
        $CSOs[$calledProgram]['value'] = 0;
        
        foreach($PointChannels->Resources() as $resourceObjectId=>$attribs)
        {
           // $userObject->preDebugger($epsReportProgramDetails[$calledProgram],'orange');
            if(array_key_exists($resourceObjectId,$epsReportProgramDetails[$calledProgram]))
            {   
                foreach($attribs['assets'] as $asset)
                {
                    $CSOs[$calledProgram][$attribs['identifier']] = 0;
                    $resourceCR = 0;
                    if(!array_key_exists($asset['assetIdentifier'],$epsReportProgramDetails[$calledProgram][$resourceObjectId]))
                    {
                        $epsReportProgramDetailsRestack[$calledProgram][$attribs['identifier']][$asset['assetIdentifier']]['resourceObjectId'] = $resourceObjectId; 
                        $epsReportProgramDetailsRestack[$calledProgram][$attribs['identifier']][$asset['assetIdentifier']]['resourceDescription'] = $attribs['description'];                        
                        $epsReportProgramDetailsRestack[$calledProgram][$attribs['identifier']][$asset['assetIdentifier']]['objectId'] = $asset['id'];                        
                        $epsReportProgramDetailsRestack[$calledProgram][$attribs['identifier']][$asset['assetIdentifier']]['channelId'] = $asset['channelId'];
                        $epsReportProgramDetailsRestack[$calledProgram][$attribs['identifier']][$asset['assetIdentifier']]['description'] = $asset['description'];            
                        $epsReportProgramDetailsRestack[$calledProgram][$attribs['identifier']][$asset['assetIdentifier']]['programId'] = $asset['programId'];
                        $epsReportProgramDetailsRestack[$calledProgram][$attribs['identifier']][$asset['assetIdentifier']]['programDescription'] = $asset['programDescription'];
                    }
                    else
                    {
                        $epsReportProgramDetailsRestack[$calledProgram][$attribs['identifier']][$asset['assetIdentifier']] = clone $epsReportProgramDetails[$calledProgram][$resourceObjectId][$asset['assetIdentifier']];                        
                        $resourceCR = $epsReportProgramDetailsRestack[$calledProgram][$attribs['identifier']][$asset['assetIdentifier']]->resourceCR();
                    }      
                    if($resourceCR > 0)              
                        $CSOs[$calledProgram][$attribs['identifier']] = $resourceCR;
                } 
                $CSOs[$calledProgram]['value']  += $resourceCR;
            }

            foreach($calledZones[$calledProgram] as $inx=>$calledZone)
            {
                if(array_key_exists($resourceObjectId,$epsReportZoneDetails[$calledProgram][$calledZone]))
                {   
                    foreach($attribs['assets'] as $asset)
                    {  
                        $CSOs[$calledProgram][$calledZone][$attribs['identifier']] = 0;
                        $resourceCR = 0;
                        if(!array_key_exists($asset['assetIdentifier'],$epsReportZoneDetails[$calledProgram][$calledZone][$resourceObjectId]))
                        {
                            //$userObject->preDebugger($asset['description'],'#989800');
                            $epsReportZoneDetailsRestack[$calledProgram][$calledZone][$attribs['identifier']][$asset['assetIdentifier']]['resourceObjectId'] = $resourceObjectId;                        
                            $epsReportZoneDetailsRestack[$calledProgram][$calledZone][$attribs['identifier']][$asset['assetIdentifier']]['resourceDescription'] = $attribs['description'];                        
                            $epsReportZoneDetailsRestack[$calledProgram][$calledZone][$attribs['identifier']][$asset['assetIdentifier']]['objectId'] = $asset['id'];                        
                            $epsReportZoneDetailsRestack[$calledProgram][$calledZone][$attribs['identifier']][$asset['assetIdentifier']]['channelId'] = $asset['channelId'];
                            $epsReportZoneDetailsRestack[$calledProgram][$calledZone][$attribs['identifier']][$asset['assetIdentifier']]['description'] = $asset['description'];            
                            $epsReportZoneDetailsRestack[$calledProgram][$calledZone][$attribs['identifier']][$asset['assetIdentifier']]['programId'] = $asset['programId'];
                            $epsReportZoneDetailsRestack[$calledProgram][$calledZone][$attribs['identifier']][$asset['assetIdentifier']]['programDescription'] = $asset['programDescription'];                        
                        }
                        else
                        {
                            $epsReportZoneDetailsRestack[$calledProgram][$calledZone][$attribs['identifier']][$asset['assetIdentifier']] = clone $epsReportZoneDetails[$calledProgram][$calledZone][$resourceObjectId][$asset['assetIdentifier']];                        
                            $resourceCR = $epsReportZoneDetailsRestack[$calledProgram][$calledZone][$attribs['identifier']][$asset['assetIdentifier']] ->resourceCR();
                        }                    
                        if($resourceCR > 0)
                            $CSOs[$calledProgram][$calledZone][$attribs['identifier']] = $resourceCR;
                    }
                    if(isset($CSOs[$calledProgram][$calledZone]['value']))
                    {
                        $CSOs[$calledProgram][$calledZone]['value']  += $resourceCR;    
                    }
                    else
                    {
                        $CSOs[$calledProgram][$calledZone]['value']  = $resourceCR;    
                    }
                    
                }
                
            }
        }
    }
    //$userObject->preDebugger("$CSOs[$calledProgram][$calledZone]['value']");
    //$userObject->preDebugger($CSOs,'#980098');
	if($CSVFlag === true)
	{
    /*  ===========================================================================
        Program Summary :   CSV Heading
        =========================================================================== */
            $viewProgramSummary = 'Program Summary' . "\n" .
                                                            'Called Program,' .
                                                            'Total CSO KW,' .								
                                                            'Hr. Avg.,' .
                                                            '%CSO,' . 
                                                            'Updated Date' . "\n" ;
	}
	else
	{
    /*  ===========================================================================
        Program Summary :   HTML Heading
        =========================================================================== */
    	$viewProgramSummary = '<!-- START EVENT PERFORMANCE SUMMARY DRAW -->' .
                                '<table class="sortable" width="850" border="1" cellspacing="0" cellpadding="3">' . "\n" .
                                    '<thead>' . "\n" .
                                        '<tr>' . "\n" .
                                            '<th colspan="6">Program Summary</th>' . "\n" .
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
	}
    $viewProgramDetail = "";
    $viewZoneSummary = "";
    $viewZoneDetail = "";
/*  ===========================================================================
    BEGIN STACKING
    ---------------------------------------------------------------------------
    CSV
    =========================================================================== */
    if($CSVFlag === true)
    {
	   foreach ($calledPrograms as $inx=>$calledProgram) 
		{

               $calledProgramAbbrev = "";
             
                foreach(explode(' ',$calledProgram) as $inx=>$item)
                {
                    $calledProgramAbbrev .= substr($item,0,1);
                }
        
             
/*  ===========================================================================
    Program Details CSV (response summary)
    =========================================================================== */              
            $thisProgramDetails = str_replace(" ", "_", $calledProgram) . '_detail';

            if(array_search($thisProgramDetails,$_SESSION['evtSummarySections']) !== false)
            {

                foreach($epsReportProgramDetailsRestack[$calledProgram] as $resourceIdentifier=>$epsAssetStack) 
                {
                    $programSummaryAveragesByResource[$resourceIdentifier]['noDataCount'] = 0;   
                    foreach($epsAssetStack as $inx=>$epsLineItem) 
                    {
             
                        if(is_object($epsLineItem))
                        {
                             $resourceSummaryStack[$epsLineItem->resource()][] = array(
                                'status' => 'responding',
                                'resourceCR' => str_replace(',','',$epsLineItem->resourceCR()),
                                'adjustment' => str_replace(',','',$epsLineItem->adjustment()),
                                'committedReduction' => str_replace(',','',$epsLineItem->committedReduction()),
                                'fcmDelta' => str_replace(',','',$epsLineItem->fcmDelta()),
                                'fcmPCR' => str_replace(',','',$epsLineItem->fcmPCR()),
                            );

                             $resourceDetailStack[$epsLineItem->resource()][] = array(
                                'status' => 'responding',
                                'asset' => $epsLineItem->asset(),
                                'assetId' => $epsLineItem->assetID(),
                                'updatedDate' => $epsLineItem->updatedDate(),
                                'adjustment' => str_replace(',','',$epsLineItem->adjustment()),
                                'committedReduction' => str_replace(',','',$epsLineItem->committedReduction()),
                                'fcmDelta' => str_replace(',','',$epsLineItem->fcmDelta()),
                                'fcmPCR' => str_replace(',','',$epsLineItem->fcmPCR())
                            );


                             //$userObject->preDebugger($resourceSummaryStack);
                        }
                        else
                        {
                            $resourceSummaryStack[$epsLineItem['resourceDescription']][] = array(
                                'status' => 'out',
                                'resourceCR' => 0,
                                'adjustment' => 0,
                                'committedReduction' => 0,
                                'fcmDelta' => 0,
                                'fcmPCR' => 0
                            );

                            $resourceDetailStack[$epsLineItem['resourceDescription']][] = array(
                                'status' => 'out',
                                'asset' => $epsLineItem['description'],
                                'assetId' => $inx ,
                                'updatedDate' => 'No Data',
                                'adjustment' => 'No Data',
                                'committedReduction' => 'No Data',
                                'fcmDelta' => 'No Data',
                                'fcmPCR' => 'No Data'
                            );
                        }
                       
                    }

                    //$userObject->preDebugger($resourceSummaryStack,'#989800');
                        
                    foreach($resourceSummaryStack as $resource=>$lines) // MCB added 21 June 2011
                    {
                        $numberOfAssets = count($lines);
                        
                        $totalAdjustment = 0;
                        $totalCommittedReduction = 0;
                        $totalFCMDelta = 0;
                        $totalFCMPCR = 0;
                        
                        
                        $programSummaryAveragesByResource[$resourceIdentifier]['fcmDelta'] = 0;
                        $programSummaryAveragesByResource[$resourceIdentifier]['updatedDate'] = $epsReportProgram[$calledProgram]->updatedDate();

                       foreach($lines as $inx=>$assetInfo)
                        {
                            
        
                            $totalCommittedReduction += $assetInfo['committedReduction'];
                            //$userObject->preDebugger($assetInfo['fcmDelta'],'#989800');
                            $totalFCMDelta += $assetInfo['fcmDelta'];
                            $totalFCMPCR += $assetInfo['fcmPCR'];
                            if($assetInfo['status'] == 'out')
                                $programSummaryAveragesByResource[$resourceIdentifier]['noDataCount']++;
                                        
                        }
        
                       //$userObject->preDebugger( $programSummaryAveragesByResource[$resource]);
                        $programSummaryAveragesByResource[$resourceIdentifier]['fcmDelta'] = $totalFCMDelta;
                        $programSummaryAveragesByResource[$resourceIdentifier]['updatedDate'] = $epsReportProgram[$calledProgram]->updatedDate();
                        
                    }

                    $assetCountString = $programSummaryAveragesByResource[$resource]['noDataCount'] > 0 ? '"' . $numberOfAssets . ' ('.$programSummaryAveragesByResource[$resource]['noDataCount'].' no data)"' : $numberOfAssets;

                    $viewProgramDetail .=   '"Program",'.
                                            '"Resource",' .
                                            '"Resource ID",' .
                                            '"CSO KW",' .
                                            '"Total Delta KW",' .
                                            '"% Perf.",' .
                                            '"Total # Assets",' .
                                            '"Updated Date"'  . "\n"; 

                    //$userObject->preDebugger($programSummaryAveragesByResource);
                    // CSV Program Summary
                    $performancePercent = round(((str_replace(',','',$programSummaryAveragesByResource[$resourceIdentifier]['fcmDelta'])/str_replace(',','',$CSOs[$calledProgram][$resourceIdentifier]))*100),2);
                    //$userObject->preDebugger($epsLineItem);

                    $rName = is_object($epsLineItem) ? $epsLineItem->resource() : $epsLineItem['resourceDescription'];

                    $viewProgramDetail .=   '"'.$calledProgramAbbrev.'"'. ',' .
                                            '"'. $rName . ' Summary "' . ',' .
                                            '"'.$resourceIdentifier . '"' . ',' .
                                            '"'.round($CSOs[$calledProgram][$resourceIdentifier],3) . '"' . ',' .
                                            '"'.round($programSummaryAveragesByResource[$resourceIdentifier]['fcmDelta'],3) . '"' . ',' .
                                            '"'.$performancePercent . '"' . ',' .
                                            $assetCountString . ',' .
                                            '"'.$programSummaryAveragesByResource[$resourceIdentifier]['updatedDate'] . '"' . "\n"
                                            ;

        /*  ===========================================================================
            Program Response Details CSV
            =========================================================================== */      
                  
                
                $thisProgramResourceDetail = str_replace(" ", "_", $rName) . '_program_resource_detail';
                if(array_search($thisProgramResourceDetail ,$_SESSION['evtSummarySections']) !== false)
                { //$userObject->preDebugger('got Program Resource Detail '.$thisProgramResourceDetail , 'purple');
                    //$userObject->preDebugger($epsLineItem->resource());    
                    $viewProgramDetail .= "----------------------------------\n".
                                                            'Program, ' .
                                                            'Resource,' .
                                                            'Asset,' .
                                                            'Asset ID,' .
                                                            'Adj KW,' .
                                                            'CR KW,' .												
                                                            'Hr. Avg.,' .
                                                            '%CR,' . 
                                                            'Updated Date' . "\n" ;
    

                    foreach($resourceDetailStack[$rName] as $resourceName=>$detailLineItem) 
                    {  

                        $viewProgramDetail .= 	'"'.$calledProgramAbbrev . '"' . ',' .
                                                '"'.$rName . '"' . ',' .
                                                '"'.$detailLineItem['asset'] . '"' . ',' .
                                                '"'.$detailLineItem['assetId'] . '"' . ',' .
                                                '"'.$detailLineItem['adjustment'] . '"' . ',' .
                                                '"'.$detailLineItem['committedReduction'] . '"' . ',' .
                                                '"'.$detailLineItem['fcmDelta'] . '"' . ',' .
                                                '"'.$detailLineItem['fcmPCR'] . '"'  . ',' .
                                                '"'.$detailLineItem['updatedDate'] . '"' . "\n";
                    }
                    $viewProgramDetail .=  "----------------------------------\n";
                }
                } 
            }   // called_program_resource_detail | 
                // array_search($thisProgramDetails,$_SESSION['evtSummarySections'])

         /*  ===========================================================================
            Program Summary :   CSV Data
            =========================================================================== */
                // Give program summary...
           $performancePercent = round(((str_replace(',','',$epsReportProgram[$calledProgram]->fcmDelta())/str_replace(',','',$CSOs[$calledProgram]['value']))*100),2);
            $viewProgramSummary .=  $calledProgram . ',' .
                                    '"'. $CSOs[$calledProgram]['value'] . '"' . ',' .
                                    '"'.$epsReportProgram[$calledProgram]->fcmDelta() . '"' . ',' .
                                    '"'.$performancePercent . '"' . ',' .
                                    '"'.$epsReportProgram[$calledProgram]->updatedDate() . '"' . "\n";
             
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
                        foreach($epsReportZoneDetailsRestack[$calledProgram][$calledZone] as $resourceIdentifier=>$assetStack)
                        {
                            foreach($assetStack as $inx=>$epsLineItem) 
                            {
                                if(is_object($epsLineItem))
                                {
                                    $resourceZoneSummaryStack[$epsLineItem->resource()][] = array(
                                        'status' => 'responding',
                                        'resourceCR' => str_replace(',','',$epsLineItem->resourceCR()),
                                        'adjustment' => str_replace(',','',$epsLineItem->adjustment()),
                                        'committedReduction' => str_replace(',','',$epsLineItem->committedReduction()),
                                        'fcmDelta' => str_replace(',','',$epsLineItem->fcmDelta()),
                                        'fcmPCR' => str_replace(',','',$epsLineItem->fcmPCR())
                                    );
        
                                     $resourceZoneDetailStack[$epsLineItem->resource()][] = array(
                                        'status' => 'responding',
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
                                    $resourceZoneSummaryStack[$epsLineItem['resourceDescription']][] = array(
                                        'status' => 'out',
                                        'resourceCR' => 0,
                                        'adjustment' => 0,
                                        'committedReduction' => 0,
                                        'fcmDelta' => 0,
                                        'fcmPCR' => 0
                                    );
        
                                    $resourceZoneDetailStack[$epsLineItem['resourceDescription']][] = array(
                                        'status' => 'out',
                                        'asset' => $epsLineItem['description'],
                                        'assetId' => $inx ,
                                        'updatedDate' => 'No Data',
                                        'adjustment' => 'No Data',
                                        'committedReduction' => 'No Data',
                                        'fcmDelta' => 'No Data',
                                        'fcmPCR' => 'No Data'
                                    );
                                }
                            }
                            
                            foreach($resourceZoneSummaryStack as $resource=>$assetDetail) // MCB added 21 June 2011
                            {
                                
                                $numberOfAssets = count($assetDetail);
                                
                                $totalAdjustment = 0;
                                $totalCommittedReduction = 0;
                                $totalFCMDelta = 0;
                                $totalFCMPCR = 0;
                                
                                $zoneSummaryAveragesByResource[$resource]['fcmDelta'] = 0;
                                $zoneSummaryAveragesByResource[$resource]['updatedDate'] = $epsReportZone[$calledProgram][$calledZone]->updatedDate();
        
                                foreach($assetDetail as $inx=>$assetInfo)
                                {
                                    if(!isset($zoneSummaryAveragesByResource[$resource]['resourceID']))
                                        $zoneSummaryAveragesByResource[$resource]['resourceID'] = $resourceIdentifier;

                                    $totalCommittedReduction += $assetInfo['committedReduction'];
                                    $totalFCMDelta += $assetInfo['fcmDelta'];
                                    $totalFCMPCR += $assetInfo['fcmPCR'];
                                    if($assetInfo['status'] == 'out')
                                                $zoneSummaryAveragesByResource[$resource]['noDataCount']++;
                                }
                                
                                $zoneSummaryAveragesByResource[$resource]['fcmDelta'] = $totalFCMDelta;
                                $zoneSummaryAveragesByResource[$resource]['updatedDate'] = $epsReportZone[$calledProgram][$calledZone]->updatedDate();
                                
                            }

/*  ===========================================================================
    Program Zone Details CSV
    =========================================================================== */
                    //$eventPerformanceSummary->preDebugger(str_replace(" ", "_", $calledProgram . "_" . $calledZone) . '_detail');
                    //print array_search(str_replace(" ", "_", $calledProgram . "_" . $calledZone) . '_detail',$_SESSION['evtSummarySections']);
                    if( $_SESSION['evtSummarySections'][0] == 'all' || 
                        array_search(str_replace(" ", "_", $calledProgram . "_" . $calledZone) . '_detail',$_SESSION['evtSummarySections']) !== false)
                    {
                        // Give zone details...
                        $viewZoneDetail .= 	$calledProgram . " -- " . $calledZone . ' Detail' . "\n" .
                        'Zone -- Resource,' .
                        'Resource Id,' . 
                        'Adj KW,' .
                        'CR KW,' .
                        'Hr. Avg.,' .
                        '%CR,' . 
						'# of Assets' . "\n";
        
                            $assetCountString = $zoneSummaryAveragesByResource[$resource]['noDataCount'] > 0 ? '"' . $numberOfAssets . ' ('.$zoneSummaryAveragesByResource[$resource]['noDataCount'].' no data)"' : $numberOfAssets;


                            $viewZoneDetail .=  ',' .
                                            '"Resource ID",' .
                                            '"CSO KW",' .
                                            '"Total Delta KW",' .
                                            '"% Perf.",' .
                                            '"Total # Assets",' .
                                            '"Updated Date"'  . "\n"; 

                            $performancePercent = round(((str_replace(',','',$zoneSummaryAveragesByResource[$resource]['fcmDelta'])/
                                                          str_replace(',','',$CSOs[$calledProgram][$calledZone][$zoneSummaryAveragesByResource[$resource]['resourceID']]))*100),2);

                            $viewZoneDetail .= 	'"'.$calledZone .' -- ' . $epsLineItem->resource() . ' Summary "' . ',' .
                                                    '"'.$zoneSummaryAveragesByResource[$resource]['resourceID'] . '"' . ',' .
                                                    '"'.round($CSOs[$calledProgram][$calledZone][$zoneSummaryAveragesByResource[$resource]['resourceID']],3) . '"' . ',' .
                                                    '"'.round($zoneSummaryAveragesByResource[$resource]['fcmDelta'],3) . '"' . ',' .
                                                    '"'.$performancePercent. '"' . ',' .
                                                    $assetCountString . ',' .
                                                    '"'.$zoneSummaryAveragesByResource[$resource]['updatedDate'] . '"'  . "\n"; 

                            
                            if( $_SESSION['evtSummarySections'][0] == 'all' || array_search(str_replace(" ", "_", $epsLineItem->resource()) . '_zone_resource_detail',$_SESSION['evtSummarySections']) !== false)
                            {
                                $viewZoneDetail .= 	$calledProgram . " -- " . $calledZone . ' Detail' . "\n" .
                                    'Resource,' .
                                    'Asset,' .
                                    'Asset ID,' .
                                    'Adj KW,' .
                                    'CR KW,' .
                                    'Hr. Avg.,' .
                                    '%CR,' . 
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
                        $performancePercent = round(((str_replace(',','',$epsReportZone[$calledProgram][$calledZone]->fcmDelta())/
                                                          str_replace(',','',$CSOs[$calledProgram][$calledZone]['value']))*100),2);
                $viewZoneSummary .= '"'.$calledZone . '"' . ',' .
                                        '"'.$CSOs[$calledProgram][$calledZone]['value'] . '"' . ',' .
                                        '"'.$epsReportZone[$calledProgram][$calledZone]->fcmDelta() . '"' . ',' .
                                        '"'. $performancePercent . '"'  . ',' .
                                        '"'.$epsReportZone[$calledProgram][$calledZone]->updatedDate() . '"' . "\n";
                }
                
            } // if zone summary
		}
    }
/*  ===========================================================================
    BEGIN STACKING
    ---------------------------------------------------------------------------
    HTML
    =========================================================================== */
    else
    {
	    foreach ($calledPrograms as $inx=>$calledProgram) 
		{
            $viewProgramDetailLines = array();
            $viewProgramSummaryLines = array();
        /*  ===========================================================================
            Program Summary Table
            -----------------------------------Notes-----------------------------------
            Items in Called Program  and Zonal columns are linked to Real Time demand
            Real Time Demand Response Detail table
            =========================================================================== */
			
            //$userObject->preDebugger($epsReportProgramDetailsRestack);
            foreach($epsReportProgramDetailsRestack[$calledProgram] as $resourceIdentifier=>$epsAssetStack) 
			{  
                $modifiedIndex++;
                
                foreach($epsAssetStack as $inx=>$epsLineItem) 
                {
                   $lineCount++;
                   
                    if(is_object($epsLineItem) && $inx != 'identifier')
                    {
                       //if($userObject->isLSEUser() && $epsLineItem->wasUpdated())
					   if($epsLineItem->wasUpdated())
                       {
                           $modified[$epsLineItem->updatedDate()][$calledProgram][$modifiedIndex]['name'] = $epsLineItem->resource();
                           $modified[$epsLineItem->updatedDate()][$calledProgram][$modifiedIndex]['assets'][$epsLineItem->assetID()] = htmlspecialchars($epsLineItem->asset());
                       }
                        
                        $fcmColor = (is_numeric($epsLineItem->fcmPCR())?($epsLineItem->fcmPCR() < 75?"red":($epsLineItem->fcmPCR() < 100?"orange":"green")):"transparent");
        
                        $viewProgramDetailLines[$epsLineItem->resource()]['responding'][] = '<tr>' .  "\n" .                                       
                                                '<td align="left" class="htmlspecial">' . htmlspecialchars($epsLineItem->asset()) . '</td>' . "\n" .
                                                '<td align="right">' . $epsLineItem->assetID() . '</td>' . "\n" .
                                                '<td align="right">' . $epsLineItem->adjustment() . '</td>' . "\n" .
                                                '<td align="right">' . $epsLineItem->committedReduction() . '</td>' . "\n" .
                                                '<td align="right">' . $epsLineItem->fcmDelta() . '</td>' . "\n" .
                                                '<td align="right" style="font-weight: bold; background-color: ' . $fcmColor . ';">' . $epsLineItem->fcmPCR() . '</td>' . "\n" .
												'<td align="right">' . $epsLineItem->updatedDate() . '</td>' . "\n" .
                                             '</tr>' .  "\n";        
                        
                        $viewProgramSummaryLines[$epsLineItem->resource()][] = array(
                            'status' => 'responding',
                            'resourceID' => $resourceIdentifier,
                            'resourceCR' => str_replace(',','',$epsLineItem->resourceCR()),
                            'adjustment' => str_replace(',','',$epsLineItem->adjustment()),
                            'committedReduction' => str_replace(',','',$epsLineItem->committedReduction()),
                            'fcmDelta' => str_replace(',','',$epsLineItem->fcmDelta()),
                            'fcmPCR' => str_replace(',','',$epsLineItem->fcmPCR())
                            );
                    }
                    else
                    {
                        $viewProgramDetailLines[$epsLineItem['resourceDescription']]['out'][] = '<tr>' . "\n" .
                                                '<td align="left" style="font-weight: bold; color: #FC6701;" class="htmlspecial">' . htmlspecialchars($epsLineItem['description']) . '</td>' . "\n" .
                                                '<td align="right" style="font-weight: bold; color: #FC6701;">' . $inx . '</td>' . "\n" .
                                                '<td align="center" style="font-weight: bold; color: #FC6701;">No Data</td>' . "\n" .
                                                '<td align="center" style="font-weight: bold; color: #FC6701;">No Data</td>' . "\n" .
                                                '<td align="center" style="font-weight: bold; color: #FC6701;">No Data</td>' . "\n" .
                                                '<td align="center" style="font-weight: bold; color: #FC6701;">No Data</td>' . "\n" .
												'<td align="center" style="font-weight: bold; color: #FC6701;">No Data</td>' . "\n" .
                                             '</tr>' .  "\n"; 
                        $viewProgramSummaryLines[$epsLineItem['resourceDescription']][] = array(
                            'status' => 'out',
                            'resourceID' => $resourceIdentifier,
                            'resourceCR' => 0,
                            'adjustment' => 0,
                            'committedReduction' => 0,
                            'fcmDelta' => 0,
                            'fcmPCR' => 0
                            );
                    }
                }

			}

            //$eventPerformanceSummary->preDebugger($viewProgramSummaryLines);

            foreach($viewProgramSummaryLines as $resource=>$lines) // MCB added 21 June 2011
            {
                $programSummaryAveragesByResource[$resource]['noDataCount'] = 0;
                $numberOfAssets = count($viewProgramSummaryLines[$resource]);

                $totalAdjustment = 0;
                $totalCommittedReduction = 0;
                $totalFCMDelta = 0;
                $totalFCMPCR = 0;
                
                $programSummaryAveragesByResource[$resource]['numberOfAssets'] = $numberOfAssets;
                
                $programSummaryAveragesByResource[$resource]['fcmDelta'] = 0;
                $programSummaryAveragesByResource[$resource]['updatedDate'] = $epsReportProgram[$calledProgram]->updatedDate();

                foreach($lines as $resourceToSummarize=>$assetInfo)
                {
                    if(!isset($programSummaryAveragesByResource[$resource]['resourceID']))
                        $programSummaryAveragesByResource[$resource]['resourceID'] = $assetInfo['resourceID'];

                    $totalCommittedReduction += $assetInfo['committedReduction'];
                    $totalFCMDelta += $assetInfo['fcmDelta'];
                    $totalFCMPCR += $assetInfo['fcmPCR'];
                    if($assetInfo['status'] == 'out')
                        $programSummaryAveragesByResource[$resource]['noDataCount']++;
                }

                
                $programSummaryAveragesByResource[$resource]['fcmDelta'] = $totalFCMDelta;
                //$userObject->preDebugger($programSummaryAveragesByResource[$resource]['resourceID'].' : '.$programSummaryAveragesByResource[$resource]['fcmDelta']);
                $programSummaryAveragesByResource[$resource]['updatedDate'] = $epsReportProgram[$calledProgram]->updatedDate();
            }
                
            
// ======================= begin drawing the tables
// ======================= Give program summary...
            //$performancePercent = '['.str_replace(',','',$epsReportProgram[$calledProgram]->fcmDelta()).'] ['.str_replace(',','',$CSOs[$calledProgram]['value']).'] '.((str_replace(',','',$epsReportProgram[$calledProgram]->fcmDelta())/str_replace(',','',$CSOs[$calledProgram]['value']))*100).': '.round(((str_replace(',','',$epsReportProgram[$calledProgram]->fcmDelta())/str_replace(',','',$CSOs[$calledProgram]['value']))*100),2);
            $performancePercent = round(((str_replace(',','',$epsReportProgram[$calledProgram]->fcmDelta())/str_replace(',','',$CSOs[$calledProgram]['value']))*100),2);

			$fcmColor = (is_numeric($performancePercent)?($performancePercent < 75?"red":($performancePercent < 100?"orange":"green")):"transparent");

            $cpDetailName = str_replace(" ", "_", str_replace("&","and",$calledProgram)) . '_detail';

            
			$viewProgramSummary .= '<tr>' . "\n" .
									   '<td align="left" class="htmlspecial"><div style="cursor: pointer;" onClick="ToggleDivVisibility(\'' . $cpDetailName . '\')">' . htmlspecialchars($calledProgram) . '</div></td>' . "\n" .
									   '<td align="center" class="htmlspecial"><div style="cursor: pointer;" onClick="ToggleDivVisibility(\'' . str_replace(" ", "_", str_replace("&", "and",$calledProgram)) . '_zone_summary\');">Summary</div></td>' . "\n" .
									   '<td align="right">' . number_format($CSOs[$calledProgram]['value']) . '</td>' . "\n" .
									   '<td align="right">' . $epsReportProgram[$calledProgram]->fcmDelta() . '</td>' . "\n" .
									   '<td align="right" style="font-weight: bold; background-color: ' . $fcmColor . ';">'.$performancePercent.'</td>' . "\n" .
									   '<td align="right">' . $epsReportProgram[$calledProgram]->updatedDate() . '</td>' . "\n" .
								   '</tr>'.  "\n";										

// ======================= Give program details...            
			$viewProgramDetail .= '<div id="' . str_replace(" ", "_", str_replace("&", "and", $calledProgram)) . '_detail" style="display: none; text-align: left;" class="toggleable">' . "\n" .
                                    '<div style="font-weight: bold; color: #CD6701; margin-top: 10px; margin-bottom: 10px;" class="htmlspecial">' . htmlspecialchars($calledProgram) . '</div>' .  "\n" . 
                                    '<div style="font-size: 11px; text-align: center;">[Hint: Click on the resource name to toggle details.]</div>' . "\n";

            $sortIndex = 0;
            foreach($viewProgramDetailLines as $resource=>$lines)
            {   
                $sortIndex++;

                $noDataString = $programSummaryAveragesByResource[$resource]['noDataCount'] > 0 ? 
                                        '<div style="color: #CD6701;">('.$programSummaryAveragesByResource[$resource]['noDataCount'].' no data)</div>' : 
                                        '';
                if($CSOs[$calledProgram][$programSummaryAveragesByResource[$resource]['resourceID']] > 0)
                    $performancePercent = round(((str_replace(',','',$programSummaryAveragesByResource[$resource]['fcmDelta'])/str_replace(',','',$CSOs[$calledProgram][$programSummaryAveragesByResource[$resource]['resourceID']]))*100),2);

                $avgFCMColor = (is_numeric($performancePercent) ? ($performancePercent < 75 ? "red": ($performancePercent < 100?"orange":"green")): "transparent");

                
                $viewProgramDetail .=   '<table width="850" border="1" cellspacing="0" cellpadding="3">' . "\n" .
                                            '<thead>' .   "\n" .
                                                '<tr>' . "\n" .
                                                    '<th style="border: none;">&nbsp;</th>' . "\n" .
                                                    '<th>Resource ID</th>' . "\n" .
                                                    '<th>CSO KW</th>' . "\n" .
                                                    '<th>Total Delta KW</th>' . "\n" .
                                                    '<th>% Perf.</th>' . "\n" .
                                                    '<th>Total # Assets</th>' . "\n" .
                                                    '<th>Updated Date</th>' . "\n" .
                                                '</tr>' . "\n" .
                                            '</thead>' . "\n" .
                                            '<tbody>' . "\n" .
                                                '</tr>' . "\n" .
                                                    '<td style="border: none; cursor: pointer;"><div onClick="ToggleDivVisibility(\'' . str_replace(" ", "_", str_replace("&", "and", $resource)) . '_program_resource_detail\');">'.htmlspecialchars($resource). '</div></td>' . "\n" . 
                                                    '<td align="right" width="80">' . $programSummaryAveragesByResource[$resource]['resourceID'] . '</td>' . "\n" .
                                                    '<td align="right" width="80">' . number_format(round($CSOs[$calledProgram][$programSummaryAveragesByResource[$resource]['resourceID']],3)) . '</td>' . "\n" .
                                                    '<td align="right" width="80">' . round($programSummaryAveragesByResource[$resource]['fcmDelta'],3) . '</td>' . "\n" .
                                                    '<td align="right"  width="80"style="font-weight: bold; background-color: ' . $avgFCMColor . ';">' . $performancePercent . '</td>' . "\n" .
                                                    '<td align="right" width="80">' . $programSummaryAveragesByResource[$resource]['numberOfAssets'] . $noDataString . '</td>' . 
                                                    '<td align="right" width="80">' . $programSummaryAveragesByResource[$resource]['updatedDate'] . '</td>' . "\n" .
                                                '</tr>' . "\n" .
                                            '</tbody>' . "\n" . 
                                        '</table>' . "\n" . 
                                        
                                        '<div id="' . str_replace(" ", "_", str_replace("&", "and", $resource)) . '_program_resource_detail" style="display: none;" class="toggleable">' . "\n" . 
                                        '<table width="850" class="sortable" border="1" cellspacing="0" cellpadding="3">' . "\n" .
                                            '<thead>' . "\n" .
                                                '<tr>' . "\n" .
                                                    '<th id="vpdAsset_'.$sortIndex.'">Asset</th>' . "\n" .
                                                    '<th id="vpdAssetID_'.$sortIndex.'" width="80">Asset ID</th>' . "\n" .
                                                    '<th id="vpdAdjKW_'.$sortIndex.'" width="80">Adj KW</th>' . "\n" .
                                                    '<th id="vpdCRKW_'.$sortIndex.'" width="80">CR KW</th>' . "\n" .
                                                    '<th id="vpdHrAvg_'.$sortIndex.'" width="80">Hr. Avg.</th>' . "\n" .
                                                    '<th id="vpdPercentCR_'.$sortIndex.'" width="80">%CR</th>' . "\n" .
                                                    '<th id="vpdUpdated_'.$sortIndex.'" width="80">Updated Date</th>' . "\n" .
                                                '</tr>' . "\n" .
                                            '</thead>' . "\n" .
                                            '<tbody>' . "\n";

                if(isset($lines['out']))
                {
                    foreach($lines['out'] as $lineNumber=>$value)
                    {
                        $viewProgramDetail .=   $value;
                    }
                }

                if(isset($lines['responding']))
                {
                    foreach($lines['responding'] as $lineNumber=>$value)
                    {
                        $viewProgramDetail .=   $value;
                    }
                }

                 $viewProgramDetail .=   '</tbody></table></br></div>' . "\n";

            }

			$viewProgramDetail .= '</div>' . "\n";
	
			$viewZoneSummary .= '<div id="' . str_replace(" ", "_", str_replace("&", "and", $calledProgram)) . '_zone_summary" style="display: none;">' . "\n" .
									 '<table width="850" class="sortable" border="1" cellspacing="0" cellpadding="3">' . "\n" .
										 '<thead>' . "\n" .
											 '<tr>' . "\n" .
												 '<th colspan="5">' . $calledProgram . ' Zone Summary</th>' . "\n" .
											 '</tr>' . "\n" .
											 '<tr>' . "\n" .
												 '<th id="vzsCalledZone_'.$sortIndex.'">Called Zone</th>' . "\n" .
												 '<th id="vzsTotalCRKW_'.$sortIndex.'">Total CSO KW</th>' . "\n" .
												 '<th id="vzsHrAvg_'.$sortIndex.'">Hr. Avg.</th>' . "\n" .
												 '<th id="vzsPCR_'.$sortIndex.'">%CSO</th>' . "\n" .
												 '<th id="vzsUpdated_'.$sortIndex.'" width="80">Updated Date</th>' . "\n" .
											 '</tr>' . "\n" .
										  '</thead>' . "\n" .
										  '<tbody>';

         //$userObject->preDebugger($calledZones);

                foreach ($calledZones[$calledProgram] as $inx=>$calledZone) 
                { 
// ======================= Give zone details...
                        $viewZoneDetail .= '<div id="' . str_replace(" ", "_", str_replace("&", "and", $calledProgram) . "_" . str_replace("&", "and", $calledZone)) . '_detail" style="display: none; text-align: left;">' . "\n" .
                                                '<div style="font-weight: bold; color: #CD6701; margin-top: 10px; margin-bottom: 10px;" class="htmlspecial">' . htmlspecialchars($calledProgram) . '<br />'. htmlspecialchars($calledZone) .' Detail</div>' .  "\n" .
                                                '<div style="font-size: 11px; text-align: center;">[Hint: Click on the resource name to toggle details.]</div>' . "\n";
                                                    
                        
                        $viewZoneDetailLines = '';

    //    $userObject->preDebugger($epsReportZoneDetailsRestack);
					
                    foreach($epsReportZoneDetailsRestack[$calledProgram][$calledZone] as $resourceIdentifier=>$assetStack)
                    {
                        foreach($assetStack as $inx=>$epsLineItem) 
                        {
                            if(is_object($epsLineItem))
                            {
                                $fcmColor = (is_numeric($epsLineItem->fcmPCR())?($epsLineItem->fcmPCR() < 75?"red":($epsLineItem->fcmPCR() < 100?"orange":"green")):"transparent");
                
                                $viewZoneDetailLines[$epsLineItem->resource()]['responding'][] = '<tr>' . "\n" .
                                                        '<td align="left" class="htmlspecial">' . htmlspecialchars($epsLineItem->asset()) . '</td>' . "\n" .
                                                        '<td align="left">' . $epsLineItem->assetID() . '</td>' . "\n" .
                                                        '<td align="right">' . $epsLineItem->adjustment() . '</td>' . "\n" .
                                                        '<td align="right">' . $epsLineItem->committedReduction() . '</td>' . "\n" .
                                                        '<td align="right">' . $epsLineItem->fcmDelta() . '</td>' . "\n" .
                                                        '<td align="right" style="font-weight: bold; background-color: ' . $fcmColor . ';">' . $epsLineItem->fcmPCR() . '</td>' . "\n" .
														'<td align="right">' . $epsLineItem->updatedDate() . '</td>' . "\n" .
                                                     '</tr>' .  "\n";  
                                
                                $viewZoneSummaryLines[$epsLineItem->resource()][] = array(
                                    'status' => 'responding',
                                    'resourceID' => $resourceIdentifier,
                                    'resourceCR' => str_replace(',','',$epsLineItem->resourceCR()),
                                    'adjustment' => str_replace(',','',$epsLineItem->adjustment()),
                                    'committedReduction' => str_replace(',','',$epsLineItem->committedReduction()),
                                    'fcmDelta' => str_replace(',','',$epsLineItem->fcmDelta()),
                                    'fcmPCR' => str_replace(',','',$epsLineItem->fcmPCR())
                                );
                                                         
                            }
                            else
                            {
                                $viewZoneDetailLines[$epsLineItem['resourceDescription']]['out'][] = '<tr>' . "\n" .
                                                '<td align="left" style="font-weight: bold; color: #FC6701;" class="htmlspecial">' . htmlspecialchars($epsLineItem['description']) . '</td>' . "\n" .
                                                '<td align="left" style="font-weight: bold; color: #FC6701;">' . $inx . '</td>' . "\n" .
                                                '<td align="center" style="font-weight: bold; color: #FC6701;">No Data</td>' . "\n" .
                                                '<td align="center" style="font-weight: bold; color: #FC6701;">No Data</td>' . "\n" .
                                                '<td align="center" style="font-weight: bold; color: #FC6701;">No Data</td>' . "\n" .
                                                '<td align="center" style="font-weight: bold; color: #FC6701;">No Data</td>' . "\n" .
												'<td align="center" style="font-weight: bold; color: #FC6701;">No Data</td>' . "\n" .
                                             '</tr>' .  "\n"; 

                                $viewZoneSummaryLines[$epsLineItem['resourceDescription']][] = array(
                                    'status' => 'out',
                                    'resourceID' => $resourceIdentifier,
                                    'resourceCR' => 0,
                                    'adjustment' => 0,
                                    'committedReduction' => 0,
                                    'fcmDelta' => 0,
                                    'fcmPCR' => 0
                                );
                            }
                        }
                    }//$epsReportZoneDetailsRestack[$calledProgram] as $resourceIdentifier=>$assetStack

                    //$userObject->preDebugger($resourceIdentifier);
                    //$userObject->preDebugger($viewZoneSummaryLines);
                    foreach($viewZoneSummaryLines as $resource=>$lines)
                    {//$userObject->preDebugger($epsReportZone[$calledProgram][$calledZone]);
                        $numberOfAssets = count($viewProgramSummaryLines[$resource]);
        
                        $totalAdjustment = 0;
                        $totalCommittedReduction = 0;
                        $totalFCMDelta = 0;
                        $totalFCMPCR = 0;
                        
                        $zoneSummaryAveragesByResource[$resource]['numberOfAssets'] = $numberOfAssets;
                        $zoneSummaryAveragesByResource[$resource]['noDataCount'] = 0;

                        $zoneSummaryAveragesByResource[$resource]['resourceID'] = $resourceIdentifier;
                        $zoneSummaryAveragesByResource[$resource]['fcmDelta'] = 0;
                        $zoneSummaryAveragesByResource[$resource]['updatedDate'] = $epsReportProgram[$calledProgram]->updatedDate();
        
                        foreach($lines as $resourceToSummarize=>$assetInfo)
                        {
                            if(!isset($zoneSummaryAveragesByResource[$resource]['resourceID']))
                                $zoneSummaryAveragesByResource[$resource]['resourceID'] = $assetInfo['resourceID'];
        
                            $totalCommittedReduction += $assetInfo['committedReduction'];
                            $totalFCMDelta += $assetInfo['fcmDelta'];
                            $totalFCMPCR += $assetInfo['fcmPCR'];
                            if($assetInfo['status'] == 'out')
                                        $zoneSummaryAveragesByResource[$resource]['noDataCount']++;
                        }
        
                        
                        $zoneSummaryAveragesByResource[$resource]['fcmDelta'] = $totalFCMDelta;
                        $zoneSummaryAveragesByResource[$resource]['updatedDate'] = $epsReportZone[$calledProgram][$calledZone]->updatedDate();

                    }

                // Give zone summary...
                    if($CSOs[$calledProgram][$calledZone]['value'] > 0)
                        $performancePercent = round(((str_replace(',','',$epsReportZone[$calledProgram][$calledZone]->fcmDelta())/str_replace(',','',$CSOs[$calledProgram][$calledZone]['value']))*100),2);
                    $fcmColor = (is_numeric($performancePercent)?($performancePercent < 75?"red":($performancePercent < 100?"orange":"green")):"transparent");
        
                    $viewZoneSummary .= '<tr>' . "\n" .
                                            '<td align="left" class="htmlspecial">
                                                <div style="cursor: pointer;" onClick="ToggleDivVisibility(\'' . str_replace(" ", "_", str_replace("&", "and",$calledProgram) . "_" . str_replace("&", "and",$calledZone)) . '_detail\');">' . htmlspecialchars($calledZone) . '</div></td>' . "\n" .
                                            '<td align="right">' . number_format($CSOs[$calledProgram][$calledZone]['value']) . '</td>' . "\n" .
                                            '<td align="right">' . $epsReportZone[$calledProgram][$calledZone]->fcmDelta() . '</td>' . "\n" .
                                            '<td align="right" style="font-weight: bold; background-color: ' . $fcmColor . ';">' . $performancePercent . '</td>' . "\n" .
                                            '<td align="right">' . $epsReportZone[$calledProgram][$calledZone]->updatedDate() . '</td>' . "\n" .
                                        '</tr>' .  "\n";

                    $zoneSortIndex = 0;
                    foreach($viewZoneDetailLines as $resource=>$lines)
                    {
                        
                        $zoneSortIndex++;
                        $noDataString = $zoneSummaryAveragesByResource[$resource]['noDataCount'] > 0 ? 
                                        '<div style="color: #CD6701;">('.$zoneSummaryAveragesByResource[$resource]['noDataCount'].' no data)</div>' : 
                                        '';
                        if($CSOs[$calledProgram][$calledZone][$zoneSummaryAveragesByResource[$resource]['resourceID']] > 0)
                            $performancePercent = round(((str_replace(',','',$zoneSummaryAveragesByResource[$resource]['fcmDelta'])/str_replace(',','',$CSOs[$calledProgram][$calledZone][$zoneSummaryAveragesByResource[$resource]['resourceID']]))*100),2);
                        $avgFCMColor = (is_numeric($performancePercent) ? ($performancePercent < 75 ? "red": ($performancePercent < 100?"orange":"green")): "transparent");

                        $viewZoneDetail .=   '<table width="850" border="1" cellspacing="0" cellpadding="3">' . "\n" .
                                            '<thead>' .   "\n" .
                                                '<tr>' . "\n" .
                                                    '<th style="border: none;">&nbsp;</th>' . "\n" .
                                                    '<th>Resource ID</th>' . "\n" .
                                                    '<th>CSO KW</th>' . "\n" .
                                                    '<th>Total Delta KW</th>' . "\n" .
                                                    '<th>% Perf.</th>' . "\n" .
                                                    '<th>Total # Assets</th>' . "\n" .
                                                    '<th>Updated Date</th>' . "\n" .
                                                '</tr>' . "\n" .
                                            '</thead>' . "\n" .
                                            '<tbody>' . "\n" .
                                                '</tr>' . "\n" .
                                                    '<td style="border: none; cursor: pointer;"><div onClick="ToggleDivVisibility(\'' . str_replace(" ", "_", str_replace("&", "and", $resource)) . '_zone_resource_detail\');">'.htmlspecialchars($resource). '</div></td>' . "\n" . 
                                                    '<td align="right" width="80">' . $zoneSummaryAveragesByResource[$resource]['resourceID'] . '</td>' . "\n" .
                                                    '<td align="right" width="80">' . number_format(round($CSOs[$calledProgram][$calledZone][$zoneSummaryAveragesByResource[$resource]['resourceID']],3)) . '</td>' . "\n" .
                                                    '<td align="right" width="80">' . round($zoneSummaryAveragesByResource[$resource]['fcmDelta'],3) . '</td>' . "\n" .
                                                    '<td align="right"  width="80"style="font-weight: bold; background-color: ' . $avgFCMColor . ';">' . $performancePercent . '</td>' . "\n" .
                                                    '<td align="right" width="80">' . $zoneSummaryAveragesByResource[$resource]['numberOfAssets'] . $noDataString . '</td>' . 
                                                    '<td align="right" width="80">' . $zoneSummaryAveragesByResource[$resource]['updatedDate'] . '</td>' . "\n" .
                                                '</tr>' . "\n" .
                                            '</tbody>' . "\n" . 
                                        '</table>' . "\n" . 
                                        
                                        '<div id="' . str_replace(" ", "_", str_replace("&", "and", $resource)) . '_zone_resource_detail" style="display: none;">' . "\n" . 
                                            '<table class="sortable" width="850" border="1" cellspacing="0" cellpadding="3">' . "\n" .
                                                '<thead>' . "\n" .
                                                    '<tr>' . "\n" .
                                                        '<th colspan="7" class="htmlspecial">'.htmlspecialchars($resource).'</th>' . "\n" .
                                                    '</tr>' . "\n" .
                                                    '<tr>' . "\n" .
                                                        '<th id="vzdAsset">Asset</th>' . "\n" .
                                                        '<th id="vzdAssetID" width="80">Asset ID</th>' . "\n" .
                                                        '<th id="vzdAdjKW" width="80">Adj KW</th>' . "\n" .
                                                        '<th id="vzdCRKW" width="80">CR KW</th>' . "\n" .
                                                        '<th id="vzdHrAvg" width="80">Hr. Avg.</th>' . "\n" .
                                                        '<th id="vzdPCR" width="80">%CR</th>' . "\n" .
														'<th id="vzdUpdated" width="80">Updated Date</th>' . "\n" .
                                                    '</tr>' . "\n" .
                                                '</thead>' . "\n" .
                                                '<tbody>';
        
                        if(isset($lines['out']))
                        {
                            foreach($lines['out'] as $lineNumber=>$value)
                            {
                                $viewZoneDetail .=   $value;
                            }
                        }

                        if(isset($lines['responding']))
                        {
                            foreach($lines['responding'] as $lineNumber=>$value)
                            {
                                $viewZoneDetail .=   $value;
                            }
                        }
        
                         $viewZoneDetail .=   '</tbody></table><br /></div>' . "\n";
        
                    }
        
                    $viewZoneDetail .= '</div>' . "\n";
    
                } // $calledZones[$calledProgram] as $inx=>$calledZone
            //}
	
			$viewZoneSummary .= '</tbody></table></div><!-- closing summary div -->' . "\n";
		}
	
		$viewProgramSummary .= '</tbody></table><!-- END EVENT PERFORMANCE SUMMARY DRAW -->' . "\n";        

        //$userObject->preDebugger($modified);
        //if($userObject->isLSEUser() && count($modified) > 0)
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
    }

   if($CSVFlag === true)
   {
       return $viewEventSummary . $viewProgramSummary . "\n----------------------------------\n" . $viewProgramDetail . $viewZoneSummary . $viewZoneDetail;
   }
   else
   {
        //$viewProgramSummary = null;
        //$viewProgramDetail = null;
        //$viewZoneSummary = null;
        //$viewZoneDetail = null;
        return $viewEventSummary . '<div id="eventSummaryContainerDiv">' . $modifiedFinal .  $viewProgramSummary . $viewProgramDetail . $viewZoneSummary . $viewZoneDetail . '</div><!-- eventSummaryContainerDiv -->';
   }

   
}




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
                    '        $("psTotalCr").store("tip:title", "Total CR");' .
                    '        $("psTotalCr").store("tip:text", "This is the total committed reduction of all reporting assets in each program.");' .                                         
                    "\n" .
            
                    '        var psFcmDeltaTip = new Tips($("psFcmDelta"));' .
                    '        $("psFcmDelta").store("tip:title", "Delta");' .
                    '        $("psFcmDelta").store("tip:text", "This is the difference between the baseline and the FCM load for all assets in each program.");' .
                    "\n" .
            
                    '        var psFcmCrTip = new Tips($("psFcmCr"));' .
                    '        $("psFcmCr").store("tip:title", "%CR");' .
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

        return $reportHeader;
        
	}
}

