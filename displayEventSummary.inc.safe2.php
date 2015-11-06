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

function viewEventSummary($domainID, $eventDate, $CSVFlag)
{
	$userObject = clone $_SESSION['UserObject'];

    $eventPerformanceSummary = new EventPerformanceSummary($domainID, $eventDate);
    $eventPerformanceSummary->Get();

    $calledPrograms = $eventPerformanceSummary->calledPrograms();

    $calledZones = $eventPerformanceSummary->calledZones();

    $epsReportProgram = $eventPerformanceSummary->reportProgram();
    $epsReportProgramDetails = $eventPerformanceSummary->reportProgramDetails();
    $epsReportZone = $eventPerformanceSummary->reportZone();
    $epsReportZoneDetails = $eventPerformanceSummary->reportZoneDetails();

	$viewEventSummary = BuildEventSummaryHeader("Event Summary", $userObject->localDomain()->description(), $eventDate, $domainID, $CSVFlag);

	if($CSVFlag === true)
	{
		$viewProgramSummary = 	'Program Summary' . "\n" .
								',,,' .
								'5 Min Peak Est,,' .
								'FCM Estimate,,,,' . "\n" .
								'Called Program,' .
								'Zonal,' .
								'Total CR KW,' .
								'Delta KW,' .
								'%CR,' .
								'Baseline KW,' .
								'Load KW,' .
								'Delta KW,' .
								'%CR' . "\n" ;
	}
	else
	{
    	$viewProgramSummary = '<div align="right">' .
                                '<table class="sortable" width="100%" border="1" cellspacing="0" cellpadding="3">' .
                                    '<thead>' .
                                        '<tr>' .
                                            '<th colspan="9">Program Summary</th>' .
                                        '</tr>' .
                                        '<tr>' .
                                            '<th colspan="3">&nbsp;</th>' .
                                            '<th colspan="2">5 Min Peak Est</th>' .
                                            '<th colspan="4">FCM Estimate</th>' .
                                        '</tr>' .
                                        '<tr>' .
                                            '<th id="psCalledProgram" class="psCalledProgramTip">Called Program</th>' .
                                            '<th id="psZonal" class="psZonalTip">Zonal</th>' .
                                            '<th id="psTotalCr" class="psTotalCrTip">Total CR KW</th>' .
                                            '<th id="psPeakDelta" class="psPeakDeltaTip">Delta KW</th>' .
                                            '<th id="psPeakCr" class="psPeakCTip">%CR</th>' .
                                            '<th>Baseline KW</th>' .
                                            '<th>Load KW</th>' .
                                            '<th id="psFcmDelta" class="psFcmDeltaTip">Delta KW</th>' .
                                            '<th id="psFcmCr" class="psFcmCrTip">%CR</th>' .
                                        '</tr>' .
                                     '</thead>' .
                                     '<tbody>' . "\n\n";
	}
    $viewProgramDetail = "";
    $viewZoneSummary = "";
    $viewZoneDetail = "";

   if($CSVFlag === true)
   {
	   foreach ($calledPrograms as $inx=>$calledProgram) 
		{
			// Give program summary...
			$viewProgramSummary .= 	$calledProgram . ',' .
									'Summary,' .
									'"'.$epsReportProgram[$calledProgram]->committedReduction() . '"' . ',' .
									'"'.$epsReportProgram[$calledProgram]->peakDelta() . '"' . ',' .
									'"'.$epsReportProgram[$calledProgram]->peakPCR() . '"' . ',' .
									'"'.$epsReportProgram[$calledProgram]->fcmBaseline() . '"' . ',' .
									'"'.$epsReportProgram[$calledProgram]->fcmLoad() . '"' . ',' .
									'"'.$epsReportProgram[$calledProgram]->fcmDelta() . '"' . ',' .
									'"'.$epsReportProgram[$calledProgram]->fcmPCR() . '"' . "\n";
										
			// Give program details...
			$viewProgramDetail .= $calledProgram . ' Detail,,,,' .
												'5 Min Peak Est,,' .
												'FCM Estimate,,,,' . "\n" .
												'Asset,' .
												'Asset ID,' .
												'Adj KW,' .
												'CR KW,' .
												'Delta KW,' .
												'%CR,' .
												'Baseline KW,' .
												'Load KW,' .
												'Delta KW,' .
												'%CR' . "\n";
			foreach($epsReportProgramDetails[$calledProgram] as $inx=>$epsLineItem) 
			{
				$viewProgramDetail .= 	'"'.$epsLineItem->asset() . '"' . ',' .
										'"'.$epsLineItem->assetID() . '"' . ',' .
										'"'.$epsLineItem->adjustment() . '"' . ',' .
										'"'.$epsLineItem->committedReduction() . '"' . ',' .
										'"'.$epsLineItem->peakDelta() . '"' . ',' .
										'"'.$epsLineItem->peakPCR() . '"' . ',' .
										'"'.$epsLineItem->fcmBaseline() . '"' . ',' .
										'"'.$epsLineItem->fcmLoad() . '"' . ',' .
										'"'.$epsLineItem->fcmDelta() . '"' . ',' .
										'"'.$epsLineItem->fcmPCR() . '"' . "\n";
			}
	
			$viewZoneSummary .= $calledProgram . ' Zone Summary' . "\n" .
											 	',,' .
												 '5 Min Peak Est,,' .
												 'FCM Estimate,,,,' . "\n" .
												 'Called Zone,' .
												 'Total CR KW,' .
												 'Delta KW,' .
												 '%CR,' .
												 'Baseline KW,' .
												 'Load KW,' .
												 'Delta KW,' .
												 '%CR' . "\n";
			foreach ($calledZones[$calledProgram] as $inx=>$calledZone) {
				// Give zone summary...
				$viewZoneSummary .= '"'.$calledZone . '"' . ',' .
									'"'.$epsReportZone[$calledProgram][$calledZone]->committedReduction() . '"' . ',' .
									'"'.$epsReportZone[$calledProgram][$calledZone]->peakDelta() . '"' . ',' .
									'"'.$epsReportZone[$calledProgram][$calledZone]->peakPCR() . '"' . ',' .
									'"'.$epsReportZone[$calledProgram][$calledZone]->fcmBaseline() . '"' . ',' .
									'"'.$epsReportZone[$calledProgram][$calledZone]->fcmLoad() . '"' . ',' .
									'"'.$epsReportZone[$calledProgram][$calledZone]->fcmDelta() . '"' . ',' .
									'"'.$epsReportZone[$calledProgram][$calledZone]->fcmPCR() . '"' . "\n";
	
				// Give zone details...
				$viewZoneDetail .= 	$calledProgram . " -- " . $calledZone . ' Detail,,,,' .
									'5 Min Peak Est,,' .
									'FCM Estimate,,,,' . "\n" .
									'Asset,' .
									'Asset ID,' .
									'Adj KW,' .
									'CR KW,' .
									'Delta KW,' .
									'%CR,' .
									'Baseline KW,' .
									'Load KW,' .
									'Delta KW,' .
									'%CR' . "\n";
				foreach($epsReportZoneDetails[$calledProgram][$calledZone] as $inx=>$epsLineItem) 
				{
					$viewZoneDetail .= 	'"' . $epsLineItem->asset() . '"' . ',' .
					'"'.$epsLineItem->assetID() . '"' . ',' .
					'"'.$epsLineItem->adjustment() . '"' . ',' .
					'"'.$epsLineItem->committedReduction() . '"' . ',' .
					'"'.$epsLineItem->peakDelta() . '"' . ',' .
					'"'.$epsLineItem->peakPCR() . '"' . ',' .
					'"'.$epsLineItem->fcmBaseline() . '"' . ',' .
					'"'.$epsLineItem->fcmLoad() . '"' . ',' .
					'"'.$epsLineItem->fcmDelta() . '"' . ',' .
					'"'.$epsLineItem->fcmPCR() . '"' . "\n";
				}
			}
		}
   }
   else
   {
	    foreach ($calledPrograms as $inx=>$calledProgram) 
		{
			// Give program summary...
			$peakColor = (is_numeric($epsReportProgram[$calledProgram]->peakPCR())?($epsReportProgram[$calledProgram]->peakPCR() < 75?"red":($epsReportProgram[$calledProgram]->peakPCR() < 100?"orange":"green")):"transparent");
			$fcmColor = (is_numeric($epsReportProgram[$calledProgram]->fcmPCR())?($epsReportProgram[$calledProgram]->fcmPCR() < 75?"red":($epsReportProgram[$calledProgram]->fcmPCR() < 100?"orange":"green")):"transparent");

            $cpDetailName = str_replace(" ", "_", $calledProgram) . '_detail';

			$viewProgramSummary .= '<tr>' .
									   '<td align="left"><a href="#" onClick="ToggleDivVisibility(\'' . $cpDetailName . '\')">' . $calledProgram . '</a></td>' .
									   '<td align="center"><a href="#" onClick="ToggleDivVisibility(\'' . str_replace(" ", "_", $calledProgram) . '_zone_summary\')">Summary</a></td>' .
									   '<td align="right">' . $epsReportProgram[$calledProgram]->committedReduction() . '</td>' .
									   '<td align="right">' . $epsReportProgram[$calledProgram]->peakDelta() . '</td>' .
									   '<td align="right" style="font-weight: bold; background-color: ' . $peakColor . ';">' . $epsReportProgram[$calledProgram]->peakPCR() . '</td>' .
									   '<td align="right">' . $epsReportProgram[$calledProgram]->fcmBaseline() . '</td>' .
									   '<td align="right">' . $epsReportProgram[$calledProgram]->fcmLoad() . '</td>' .
									   '<td align="right">' . $epsReportProgram[$calledProgram]->fcmDelta() . '</td>' .
									   '<td align="right" style="font-weight: bold; background-color: ' . $fcmColor . ';">' . $epsReportProgram[$calledProgram]->fcmPCR() . '</td>' .
								   '</tr>';
										
			// Give program details...
			$viewProgramDetail .= '<div align="right" id="' . str_replace(" ", "_", $calledProgram) . '_detail" style="display: none;">' .
									'<table class="sortable" width="100%" border="1" cellspacing="0" cellpadding="3">' .
										'<thead>' .
											'<tr>' .
												'<th colspan="4">' . $calledProgram . ' Detail</th>' .
												'<th colspan="2">5 Min Peak Est</th>' .
												'<th colspan="4">FCM Estimate</th>' .
											'</tr>' .
											'<tr>' .
												'<th>Asset</th>' .
												'<th>Asset ID</th>' .
												'<th>Adj KW</th>' .
												'<th>CR KW</th>' .
												'<th>Delta KW</th>' .
												'<th>%CR</th>' .
												'<th>Baseline KW</th>' .
												'<th>Load KW</th>' .
												'<th>Delta KW</th>' .
												'<th>%CR</th>' .
											'</tr>' .
										'</thead>' .
										'<tbody>';
			foreach($epsReportProgramDetails[$calledProgram] as $inx=>$epsLineItem) 
			{
				$peakColor = (is_numeric($epsLineItem->peakPCR())?($epsLineItem->peakPCR() < 75?"red":($epsLineItem->peakPCR() < 100?"orange":"green")):"transparent");
				$fcmColor = (is_numeric($epsLineItem->fcmPCR())?($epsLineItem->fcmPCR() < 75?"red":($epsLineItem->fcmPCR() < 100?"orange":"green")):"transparent");
	
				$viewProgramDetail .= '<tr>' .
										'<td align="left">' . $epsLineItem->asset() . '</td>' .
										'<td align="left">' . $epsLineItem->assetID() . '</td>' .
										'<td align="right">' . $epsLineItem->adjustment() . '</td>' .
										'<td align="right">' . $epsLineItem->committedReduction() . '</td>' .
										'<td align="right">' . $epsLineItem->peakDelta() . '</td>' .
										'<td align="right" style="font-weight: bold; background-color: ' . $peakColor . ';">' . $epsLineItem->peakPCR() . '</td>' .
										'<td align="right">' . $epsLineItem->fcmBaseline() . '</td>' .
										'<td align="right">' . $epsLineItem->fcmLoad() . '</td>' .
										'<td align="right">' . $epsLineItem->fcmDelta() . '</td>' .
										'<td align="right" style="font-weight: bold; background-color: ' . $fcmColor . ';">' . $epsLineItem->fcmPCR() . '</td>' .
									 '</tr>';
			}
	
			$viewProgramDetail .= '</tbody></table></div>' . "\n";
	
			$viewZoneSummary .= '<div align="right" id="' . str_replace(" ", "_", $calledProgram) . '_zone_summary" style="display: none;">' .
									 '<table class="sortable" width="100%" border="1" cellspacing="0" cellpadding="3">' .
										 '<thead>' .
											 '<tr>' .
												 '<th colspan="8">' . $calledProgram . ' Zone Summary</th>' .
											 '</tr>' .
											 '<tr>' .
												 '<th colspan="2">&nbsp;</th>' .
												 '<th colspan="2">5 Min Peak Est</th>' .
												 '<th colspan="4">FCM Estimate</th>' .
											 '</tr>' .
											 '<tr>' .
												 '<th>Called Zone</th>' .
												 '<th>Total CR KW</th>' .
												 '<th>Delta KW</th>' .
												 '<th>%CR</th>' .
												 '<th>Baseline KW</th>' .
												 '<th>Load KW</th>' .
												 '<th>Delta KW</th>' .
												 '<th>%CR</th>' .
											 '</tr>' .
										  '</thead>' .
										  '<tbody>';
			foreach ($calledZones[$calledProgram] as $inx=>$calledZone) {
				// Give zone summary...
				$peakColor = (is_numeric($epsReportZone[$calledProgram][$calledZone]->peakPCR())?($epsReportZone[$calledProgram][$calledZone]->peakPCR() < 75?"red":($epsReportZone[$calledProgram][$calledZone]->peakPCR() < 100?"orange":"green")):"transparent");
				$fcmColor = (is_numeric($epsReportZone[$calledProgram][$calledZone]->fcmPCR())?($epsReportZone[$calledProgram][$calledZone]->fcmPCR() < 75?"red":($epsReportZone[$calledProgram][$calledZone]->fcmPCR() < 100?"orange":"green")):"transparent");
	
				$viewZoneSummary .= '<tr>' .
										'<td align="left"><a href="#" onClick="ToggleDivVisibility(\'' . str_replace(" ", "_", $calledProgram . "_" . $calledZone) . '_detail\')">' . $calledZone . '</a></td>' .
										'<td align="right">' . $epsReportZone[$calledProgram][$calledZone]->committedReduction() . '</td>' .
										'<td align="right">' . $epsReportZone[$calledProgram][$calledZone]->peakDelta() . '</td>' .
										'<td align="right" style="font-weight: bold; background-color: ' . $peakColor . ';">' . $epsReportZone[$calledProgram][$calledZone]->peakPCR() . '</td>' .
										'<td align="right">' . $epsReportZone[$calledProgram][$calledZone]->fcmBaseline() . '</td>' .
										'<td align="right">' . $epsReportZone[$calledProgram][$calledZone]->fcmLoad() . '</td>' .
										'<td align="right">' . $epsReportZone[$calledProgram][$calledZone]->fcmDelta() . '</td>' .
										'<td align="right" style="font-weight: bold; background-color: ' . $fcmColor . ';">' . $epsReportZone[$calledProgram][$calledZone]->fcmPCR() . '</td>' .
									'</tr>';
	
				// Give zone details...
				$viewZoneDetail .= '<div align="right" id="' . str_replace(" ", "_", $calledProgram . "_" . $calledZone) . '_detail" style="display: none;">' .
										'<table class="sortable" width="100%" border="1" cellspacing="0" cellpadding="3">' .
											'<thead>' .
												'<tr>' .
													'<th colspan="4">' . $calledProgram . " -- " . $calledZone . ' Detail</th>' .
													'<th colspan="2">5 Min Peak Est</th>' .
													'<th colspan="4">FCM Estimate</th>' .
												'</tr>' .
												'<tr>' .
													'<th>Asset</th>' .
													'<th>Asset ID</th>' .
													'<th>Adj KW</th>' .
													'<th>CR KW</th>' .
													'<th>Delta KW</th>' .
													'<th>%CR</th>' .
													'<th>Baseline KW</th>' .
													'<th>Load KW</th>' .
													'<th>Delta KW</th>' .
													'<th>%CR</th>' .
												'</tr>' .
											'</thead>' .
											'<tbody>';
				foreach($epsReportZoneDetails[$calledProgram][$calledZone] as $inx=>$epsLineItem) {
					$peakColor = (is_numeric($epsLineItem->peakPCR())?($epsLineItem->peakPCR() < 75?"red":($epsLineItem->peakPCR() < 100?"orange":"green")):"transparent");
					$fcmColor = (is_numeric($epsLineItem->fcmPCR())?($epsLineItem->fcmPCR() < 75?"red":($epsLineItem->fcmPCR() < 100?"orange":"green")):"transparent");
	
					$viewZoneDetail .= '<tr>' .
											'<td align="left">' . $epsLineItem->asset() . '</td>' .
											'<td align="left">' . $epsLineItem->assetID() . '</td>' .
											'<td align="right">' . $epsLineItem->adjustment() . '</td>' .
											'<td align="right">' . $epsLineItem->committedReduction() . '</td>' .
											'<td align="right">' . $epsLineItem->peakDelta() . '</td>' .
											'<td align="right" style="font-weight: bold; background-color: ' . $peakColor . ';">' . $epsLineItem->peakPCR() . '</td>' .
											'<td align="right">' . $epsLineItem->fcmBaseline() . '</td>' .
											'<td align="right">' . $epsLineItem->fcmLoad() . '</td>' .
											'<td align="right">' . $epsLineItem->fcmDelta() . '</td>' .
											'<td align="right" style="font-weight: bold; background-color: ' . $fcmColor . ';">' . $epsLineItem->fcmPCR() . '</td>' .
										 '</tr>';
				}
	
				$viewZoneDetail .= '</tbody></table></div>' . "\n";
			}
	
			$viewZoneSummary .= '</tbody></table></div>' . "\n";
		}
	
		$viewProgramSummary .= '</tbody></table></div>' . "\n";
    }

    return $viewEventSummary . $viewProgramSummary . $viewProgramDetail . $viewZoneSummary . $viewZoneDetail;
    //return date("H:i:s");
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
                    '        var psPeakDeltaTip = new Tips($("psPeakDelta"));' .
                    '        $("psPeakDelta").store("tip:title", "Delta");' .
                    '        $("psPeakDelta").store("tip:text", "This is the difference between the baseline and the 5 minute peak load for all assets in each program.");' .
                    "\n" .
                    '        var psCrTip = new Tips($("psPeakCr"));' .
                    '        $("psPeakCr").store("tip:title", "%CR");' .
                    '        $("psPeakCr").store("tip:text", "This is the percentage of the committed reduction achieved by all assets in each program.");' .
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
//		$reportHeader .= '<td class="export"><a href="#" id="exportTableTip" onClick="processBasicCSVExport(\'eventsForm\',\'\',\''.$domainID.'\');" ><img src="_template/images/blank.gif" height="31" width="31" border="0" /></a></td>'."\n";
		$reportHeader .= '</tr>'."\n";
		$reportHeader .= '</table>'."\n";
		$reportHeader .= '<div style="width: 700px; text-align: center; font-weight: bold;">' . "\n";
		$reportHeader .= $reportTitle . "\n";
		$reportHeader .= '<br />' . "\n";
		$reportHeader .= 'For ' . $domain . ' on ' . $summaryDate . "\n";
		$reportHeader .= '<br />' . "\n";
		$reportHeader .= date("H:i:s") . "\n";
		$reportHeader .= '</div><br />' . "\n";

        return $reportHeader;
	}
	
    
}
