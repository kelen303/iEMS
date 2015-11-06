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

function viewStatistics($userID,$domainID,$CSVFlag)
{
	$userObject = clone $_SESSION['UserObject'];

    $viewStatistics = "";
    $pointString = "";

    $statistics = new Statistics();

    if (!isset($_POST['uptPoints']) & !isset($_GET['uptPoints']))
    {
        $viewStatistics = '<div class="error" style="width: 700px;">You must select one or more points to display statistics.</div>' . "\n";
    }
    elseif ((!(isset($_POST['uptBaseDateFrom']) || isset($_POST['uptBaseDateTo']))) & (!(isset($_GET['uptBaseDateFrom']) || isset($_GET['uptBaseDateTo']))))
    {
        $viewStatistics = '<div class="error" style="width: 700px;">You must select a from date and a to date to display statistics.</div>' . "\n";
    }
    elseif (!isset($_POST['uptBaseDateFrom']) & !isset($_GET['uptBaseDateFrom']))
    {
        $viewStatistics = '<div class="error" style="width: 700px;">You must select a from date to display statistics.</div>' . "\n";
    }
    elseif (!isset($_POST['uptBaseDateTo']) & !isset($_GET['uptBaseDateTo']))
    {
        $viewStatistics = '<div class="error" style="width: 700px;">You must select a to date to display statistics.</div>' . "\n";
    }
    else
    {
        // Bar Chart.
        $viewHeader = ''; //mcb 2009.07.30 moved the header below 'cause we want to prepend a button which needs point data.

		if($CSVFlag === true)
		{
			$viewStatistics = 	'Data Recorder,' . 
								'Actual Intervals,' . 
								'Expected Intervals,' . 
								'Percent Uptime,' . 
								'Percent Filled,' . 
								'Last Interval Date' . "\n";								
		}
		else
		{
			$viewStatistics = '<table class="sortable" width="100%" cellpadding="3" cellspacing="0" border="1">' .
                            '<thead>' . 
                                '<tr>' . 
                                    '<th>Data Recorder</th>' . 
                                    '<th>Actual<br>Intervals</th>' . 
                                    '<th>Expected<br>Intervals</th>' . 
                                    '<th>Percent<br/>Uptime</th>' . 
                                    '<th>Percent<br/>Filled</th>' . 
                                    '<th>Last Interval<br/>Date</th>' .
                                '</tr>' .
                            '</thead>' . "\n";'<tbody>';
		}
        
        $totalActual = 0;
        $totalExpected = 0;
		
		if(isset($_POST['uptPoints']))
		{
			$points = $_POST['uptPoints'];
			$baseDateFrom = $_POST['uptBaseDateFrom'];
			$baseDateTo = $_POST['uptBaseDateTo'];
			$_GET['uptBaseDateFrom'] = $_POST['uptBaseDateFrom'];
			$_GET['uptBaseDateTo'] = $_POST['uptBaseDateTo'];
		}
		elseif(isset($_GET['uptPoints']))
		{
			$points = $_GET['uptPoints'];
			$baseDateFrom = $_GET['uptBaseDateFrom'];
			$baseDateTo = $_GET['uptBaseDateTo'];
		}

        foreach ($points as $pointChannel=>$state) {
            
            $pointString .= $pointChannel.',';
            $ids = explode(":", $pointChannel);

            $channelDescription = $userObject->pointChannels()->pointChannel($ids[0], $ids[1])->channelDescription();

            if ($statistics->GetUptimeStatistics($ids[0], $ids[1], $baseDateFrom, $baseDateTo))
            {
                
                
                $uptimeColor = (is_numeric($statistics->percentageUptime())?($statistics->percentageUptime() < 92.36?"red":($statistics->percentageUptime() < 98?"orange":"green")):"transparent"); 
                $filledColor = (is_numeric($statistics->percentageFilled())?($statistics->percentageFilled() <= 2?"green":($statistics->percentageFilled() <= 7.64?"orange":"red")):"transparent");
            	
				if($CSVFlag === true)
				{
					$viewStatistics .= $channelDescription . ',' .
                                   $statistics->actualIntervals() . ',' .
                                   $statistics->expectedIntervals() . ',' .
                                   $statistics->percentageUptime() . ',' .
                                   $statistics->percentageFilled() . ',' .
                                   $statistics->lastIntervalDate() . "\n";
				}
				else
				{
                	$viewStatistics .= '<tr>' . 
                                   '<td align="left">' . $channelDescription . '</td>' .
                                   '<td align="right">' . $statistics->actualIntervals() . '</td>' .
                                   '<td align="right">' . $statistics->expectedIntervals() . '</td>' .
                                   '<td align="right" style="font-weight: bold; background-color: ' . $uptimeColor . ';">' . $statistics->percentageUptime() . '</td>' .
                                   '<td align="right" style="font-weight: bold; background-color: ' . $filledColor . ';">' . $statistics->percentageFilled() . '</td>' .
                                   '<td align="right">' . $statistics->lastIntervalDate() . '</td>' .
                                   '</tr>' . "\n";
				}
                $totalActual += $statistics->actualIntervals();
                $totalExpected += $statistics->expectedIntervals();
            }
            else
            {
                $viewStatistics .= '<div class="error" style="width: 700px;">Could not retreive Uptime Statistics Report for ' . $channelDescription . ' for the selected dates.</div>' . "\n";
            }
        }
        
        $cutoverString = '';

        $toParts = explode('-',$baseDateTo);
        strtotime($toParts[2].'-'.$toParts[0].'-'.$toParts[1]);

        $fromParts = explode('-',$baseDateFrom);
        strtotime($fromParts[2].'-'.$fromParts[0].'-'.$fromParts[1]);

        if(($toParts[2] <= 2010 and $fromParts[2] >= 2010) && ($toParts[0] >= 5 and $fromParts[0] <= 5))
        {
            $cutoverString = 'May 2010 data, especially the first week, is transitional data moving from the IBCS structure to the FCM structure and could be incomplete for some days. Incomplete data for days after June 1st, 2010 is unexpected and should be reported as meter trouble as soon as possible.';
        }

		if($CSVFlag === true)
		{   
            $viewHeader = $cutoverString.'';
		}
		else
		{
            $cutoverString = '<div style="width: 750px; color: #FF7601; padding-bottom: 10px;">'.$cutoverString.'</div>';
                        
            $viewHeader = $cutoverString.'<table align="right" cellpadding="0" cellspacing="0" border="0">'."\n";
			$viewHeader .= '<tr>'."\n";
			$viewHeader .= '<td class="export"><a href="#" id="exportTableTip" onClick="processBasicCSVExport(\'uptimeForm\',\''.rtrim($pointString,',').'\',\''.$domainID.'\');" ><img src="_template/images/blank.gif" height="31" width="31" border="0" /><a/></td>'."\n";
			$viewHeader .= '</tr>'."\n";
			$viewHeader .= '</table>'."\n";
		}
        
        $viewHeader .= BuildStatisticsHeader("Uptime Statistics", $baseDateFrom, $baseDateTo, $CSVFlag);
		
		if(!$CSVFlag === true)
		{			
			$viewStatistics .= '</tbody>' . "\n" . '</table>' . "\n\n";
		}
        
        $percentageUptime = number_format(($totalActual/$totalExpected) * 100.0, 2, ".", ",");
        $percentageFilled = number_format(100.0 - $percentageUptime, 2, ".", ",");
        $uptimeColor = (is_numeric($percentageUptime)?($percentageUptime < 92.36?"red":($percentageUptime < 98?"orange":"green")):"transparent"); 
        $filledColor = (is_numeric($percentageFilled)?($percentageFilled <= 2?"green":($percentageFilled <= 7.64?"orange":"red")):"transparent");
		
		if($CSVFlag === true)
		{
			$viewSummary = 'Summary,' . 
							'Actual Intervals,' . 
							'Expected Intervals,' . 
							'Percent Uptime,' . 
							'Percent Filled' ."\n" .
							'Totals,' .
							$totalActual . ',' .
							$totalExpected . ',' .
							$percentageUptime . ',' .
							$percentageFilled ."\n";
		}
		else
		{
			$viewSummary = '<table  width="100%" cellpadding="3" cellspacing="0" border="1">' .
                            '<thead>' . 
                                '<tr>' . 
                                    '<th>Summary</th>' . 
                                    '<th>Actual<br>Intervals</th>' . 
                                    '<th>Expected<br>Intervals</th>' . 
                                    '<th>Percent<br/>Uptime</th>' . 
                                    '<th>Percent<br/>Filled</th>' .
                                '</tr>' .
                            '</thead>' .
                            '<tbody>' .
                                '<tr>' . 
                                  '<td align="left">Totals</td>' .
                                  '<td align="right">' . $totalActual . '</td>' .
                                  '<td align="right">' . $totalExpected . '</td>' .
                                  '<td align="right" style="font-weight: bold; background-color: ' . $uptimeColor . ';">' . $percentageUptime . '</td>' .
                                  '<td align="right" style="font-weight: bold; background-color: ' . $filledColor . ';">' . $percentageFilled . '</td>' .
                                  '</tr>' .
                            '</tbody>' . 
                         '</table>' . 
                         '<br/>' . "\n\n";
		}
        

    }

    
    return $viewHeader . $viewSummary . $viewStatistics;
}

function BuildStatisticsHeader($reportTitle, $fromDate, $toDate, $CSVFlag)
{
    
	if($CSVFlag === true)
	{        
		$reportHeader .= $reportTitle . "\n";
		$reportHeader .= 'From ' . $fromDate . ' to ' . $toDate . "\n";
        
	}
	else
	{        
		$reportHeader = '<div style="width: 750px; text-align: center; font-weight: bold;">' . "\n";
		$reportHeader .= $reportTitle . "\n";
		$reportHeader .= '<br />' . "\n";
		$reportHeader .= 'From ' . $fromDate . ' to ' . $toDate . "\n";        
		$reportHeader .= '</div><br />' . "\n\n";
	}
	

    return $reportHeader;
}
