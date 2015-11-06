<?PHP
 /**
 * displayReports.inc.php
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


function viewReports($userID,$domainID)
{
    $viewReports = "";
    $chart = '';
    $statistics = "";
    $chartSeriesData = '';
    $chartGraphData = '';
    $chartData = '';
    $showChart = false;
    $gid = 0;
    $valueRotation = 0;
    $graphSettings = '';
    $labelFreq = '1';
    $gridCount = '4';
    $settingsString = '';

    $aryColors = array(1=>'#1B6097',2=>'#7C1787',3=>'#874417',4=>'#228717',5=>'#207DBC',6=>'#BC5E20',7=>'#17877C',8=>'#871722',9=>'#172287',10=>'#87175A',11=>'#877C17',12=>'#178744',13=>'#441787',14=>'#5A8717',15=>'#3B9DDE',16=>'#DE7C3B');
    $aryColorPairs = array(1=>'#1B6097',2=>'#2994DB',3=>'#7C1787',4=>'#C929DB',5=>'#874417',6=>'#DB7029',7=>'#228717',8=>'#3BDB29',9=>'#207DBC',10=>'#5EAEE4',11=>'#BC5E20',12=>'#E4935E',13=>'#17877C',14=>'#29DBC9',15=>'#871722',16=>'#DB293B',17=>'#172287',18=>'#293BDB',19=>'#87175A',20=>'#DB2994',21=>'#877C17',22=>'#DBC929',23=>'#178744',24=>'#29DB70',25=>'#441787',26=>'#7029DB',27=>'#5A8717',28=>'#94DB29',29=>'#3B9DDE',30=>'#92C8ED',31=>'#DE7C3B',32=>'#EDB692');

    $aryUnits = array('KWh'=>'KW', 'kVarh'=>'kVar');

    $reports = new Reports();

    $userObject = clone $_SESSION['UserObject'];

	
	
    if (!isset($_POST['repPoints']))
    {
        $viewReports = '<div class="error" style="width: 700px;">You must select one or more points to display a report.</div>' . "\n";
    }
    elseif (!(isset($_POST['repBaseDateFrom']) || isset($_POST['repBaseDateTo'])))
    {
        $viewReports = '<div class="error" style="width: 700px;">You must select a from date and a to date to display a report.</div>' . "\n";
    }
    elseif (!isset($_POST['repBaseDateFrom']))
    {
        $viewReports = '<div class="error" style="width: 700px;">You must select a from date to display a report.</div>' . "\n";
    }
    elseif (!isset($_POST['repBaseDateTo']))
    {
        $viewReports = '<div class="error" style="width: 700px;">You must select a to date to display a report.</div>' . "\n";
    }
    elseif (!isset($_POST['report'])) 
    {
        $viewReports = '<div class="error" style="width: 700px;">You must select a Report Type to display.</div>' . "\n";
    }
//AVERAGE HOURLY PROFILE
//Bar Chart
    elseif ($_POST['report'] == 'aveHourlyProfile')
    {
        $viewReports = BuildReportHeader("Average Hourly Profile", $_POST['repBaseDateFrom'], $_POST['repBaseDateTo']);
		
        foreach ($_POST['repPoints'] as $pointChannel=>$state) 
		{
            $ids = explode(":", $pointChannel);
			$chartSeriesData = '';
			$chartData = '';
			$chartGraphData = '';
			
			if ($reports->GetAverageHourlyProfile($ids[0], $ids[1], $_POST['repBaseDateFrom'], $_POST['repBaseDateTo']))
            {
				$gid++;
				$graphName = $userObject->pointChannels()->pointChannel($ids[0], $ids[1])->channelDescription();
				$valueUnits = $userObject->pointChannels()->pointChannel($ids[0], $ids[1])->units()->unitOfMeasureName();
				
				$graphSettings .= compileGraphSettings($gid, $aryColors[$gid], $graphName);
				
				$chartGraphData .= '<graph gid=\"'.$gid.'\">';
				
                for ($inx=0; $inx<$reports->size(); $inx++)
                {
					$chartGraphData .= '<value xid=\"'.$inx.'\">'.$reports->values($inx).'</value>';		
                }
				
				$chartGraphData .= '</graph>';
				
				for ($inx=0; $inx<$reports->size(); $inx++)
				{
					$chartSeriesData .= '<value xid=\"'.$inx.'\">'.$reports->labels($inx).'</value>';
				}

				$chartData = '<chart><series>'.$chartSeriesData.'</series><graphs>'.$chartGraphData.'</graphs></chart>';
				
				$chart .= assembleCharts($ids[0].'_'.$ids[1], 'bar', $chartData, $valueRotation, $aryUnits[$valueUnits], $reports->labelUnits(), $labelFreq, $gridCount, $graphSettings);

                $chart .= '<div align="center">' .
                                  '<table border="1" cellspacing="0" cellpadding="3">' .
                                    '<tr><td align="right">Average Hourly Demand:</td><td align="right">' . $reports->statistic("AverageHourlyDemand") . '</td></tr>' .
                                    '<tr><td align="right">Maximum Hourly Demand:</td><td align="right">' . $reports->statistic("MaximumHourlyDemand") . '</td></tr>' .
                                    '<tr><td align="right">Total Usage:</td><td align="right">' . $reports->statistic("TotalUsage") . '</td></tr>' .
                                  '</table>' .
                              '</div>';
            }
            else
            {
                $viewReports .= '<div class="error" style="width: 700px;">Could not retreive Average Hourly Profile Report for ' . $userObject->pointChannels()->pointChannel($ids[0], $ids[1])->channelDescription() . ' for the selected dates.</div>' . "\n";
            }
        }
	
    }
//AVERAGE HOUR VS. PEAK HOUR
//Line Chart
	elseif ($_POST['report'] == 'aveHourVsPeakHour')
    {
		$viewReports = BuildReportHeader("Average Hour Vs. Peak Hour", $_POST['repBaseDateFrom'], $_POST['repBaseDateTo']);
		//$viewReports = '<table>';
		
        foreach ($_POST['repPoints'] as $pointChannel=>$state) {
            $ids = explode(":", $pointChannel);
			$chartSeriesData = '';
			$chartData = '';
			$chartGraphData01 = '';
			$chartGraphData02 = '';
			
            if ($reports->GetAverageHourlyProfile($ids[0], $ids[1], $_POST['repBaseDateFrom'], $_POST['repBaseDateTo']))
            {				
                for ($inx=0; $inx<$reports->size(); $inx++) $averageHour[$inx] = $reports->values($inx);
				

                if ($reports->GetPeakHourlyProfile($ids[0], $ids[1], $_POST['repBaseDateFrom'], $_POST['repBaseDateTo']))
                {
                    $graphName = $userObject->pointChannels()->pointChannel($ids[0], $ids[1])->channelDescription();
                    $valueUnits = $userObject->pointChannels()->pointChannel($ids[0], $ids[1])->units()->unitOfMeasureName();

                    $gid++;
                    $chartGraphData01 .= '<graph gid=\"'.$gid.'\">';
                    $graphSettings .= compileLineGraphSettings($gid, $aryColorPairs[$gid], $graphName.': Average '.$aryUnits[$valueUnits]);

                    $gid++;
                    $chartGraphData02 .= '<graph gid=\"'.$gid.'\">';
                    $graphSettings .= compileLineGraphSettings($gid, $aryColorPairs[$gid], $graphName.': Peak '.$aryUnits[$valueUnits]);
					
					
                    //$viewReports .= '<tr><td>' . $reports->labelUnits() . '</td><td> Average ' . $reports->valueUnits() . '</td><td> Peak ' . $reports->valueUnits() . '</td></tr>' . "\n";

                    for ($inx=0; $inx<$reports->size(); $inx++)
                    {
                        //$viewReports .= '<tr><td>' . $reports->labels($inx) . '</td><td>' . $averageHour[$inx] . '</td><td>' . $reports->values($inx) . '</td></tr>' . "\n";
                        $chartGraphData01 .= '<value xid=\"'.$inx.'\">'.$averageHour[$inx].'</value>';

                        $chartGraphData02 .= '<value xid=\"'.$inx.'\">'.$reports->values($inx).'</value>';
						
                    }

                    $chartGraphData01 .= '</graph>';
                    $chartGraphData02 .= '</graph>';
					
							
                    for ($inx=0; $inx<$reports->size(); $inx++)
                    {
                            $chartSeriesData .= '<value xid=\"'.$inx.'\">'.$reports->labels($inx).'</value>';
                    }

                    $valueRotation = 0;
                    $chartData = '<chart><series>'.$chartSeriesData.'</series><graphs>'.$chartGraphData01.$chartGraphData02.'</graphs></chart>';

                    $chart .= assembleCharts($ids[0].'_'.$ids[1], 'line', $chartData, $valueRotation, $aryUnits[$valueUnits], $reports->labelUnits(), 1, 24, $graphSettings);

                    $chart .= '<div align="center">' .
                                '<table border="1" cellspacing="0" cellpadding="3">' .
                                    '<tr><td>Maximum Demand:</td><td>' . $reports->statistic("IntervalValue") . '</td><td> on ' . $reports->statistic("IntervalDate") . '</td></tr>' .
                                '</table>' .
                            '</div>';
                }
                else
                {
                    $viewReports .= '<div class="error" style="width: 700px;">Could not retreive Average Hour Vs. Peak Hour Report for ' . $userObject->pointChannels()->pointChannel($ids[0], $ids[1])->channelDescription() . ' for the selected dates.</div>' . "\n";
                }
            }
            else
            {
                $viewReports .= '<div class="error" style="width: 700px;">Could not retreive Average Hour Vs. Peak Hour Report for ' . $userObject->pointChannels()->pointChannel($ids[0], $ids[1])->channelDescription() . ' for the selected dates.</div>' . "\n";
            }
			//$viewReports .= '</tbody>' . "\n" . '</table>' . "\n";
            
            

        }
		
    }
//TOP TEN PEAKS
// Bar Chart
    elseif ($_POST['report'] == 'topTenPeaks')
    {
        $viewReports = BuildReportHeader("Top Ten Peaks", $_POST['repBaseDateFrom'], $_POST['repBaseDateTo']);

        foreach ($_POST['repPoints'] as $pointChannel=>$state) {
            $ids = explode(":", $pointChannel);			
			$chartSeriesData = '';
			$chartData = '';
			$chartGraphData = '';

            if ($reports->GetTopTenPeaks($ids[0], $ids[1], $_POST['repBaseDateFrom'], $_POST['repBaseDateTo']))
            {
                $gid++;
				$graphName = $userObject->pointChannels()->pointChannel($ids[0], $ids[1])->channelDescription();
				$valueUnits = $userObject->pointChannels()->pointChannel($ids[0], $ids[1])->units()->unitOfMeasureName();
				
				$graphSettings .= compileGraphSettings($gid, $aryColors[$gid], $graphName);

				$chartGraphData .= '<graph gid=\"'.$gid.'\">';
				
                for ($inx=0; $inx<$reports->size(); $inx++)
                {					
					$chartGraphData .= '<value xid=\"'.$inx.'\">'.$reports->values($inx).'</value>';
                }
				
				$chartGraphData .= '</graph>';
				
				for ($inx=0; $inx<$reports->size(); $inx++)
				{
					$chartSeriesData .= '<value xid=\"'.$inx.'\">'.$reports->labels($inx).'</value>';
				}

				$chartData = '<chart><series>'.$chartSeriesData.'</series><graphs>'.$chartGraphData.'</graphs></chart>';
				
				$chart .= assembleCharts($ids[0].'_'.$ids[1], 'bar', $chartData, 30, $aryUnits[$valueUnits], $reports->labelUnits(), $labelFreq, $gridCount, $graphSettings);

                $chart .= '<div align="center">' .
                                  '<table border="1" cellspacing="0" cellpadding="3">' .
                                    '<tr><td colspan="3">Top Ten Peaks</td></tr>' .
                                    '<tr><td>&nbsp;</td><td>Demand (KW)</td><td>Date</td></tr>';
                $inx = 0;
                foreach ($reports->statistics() as $date=>$value) $chart .= "<tr><td>" . ++$inx . "</td><td>{$value}</td><td>{$date}</td></tr>";

                $chart .= '</table></div>';
            }
            else
            {
                $viewReports .= '<div class="error" style="width: 700px;">Could not retreive Top Ten Peaks Report for ' . $userObject->pointChannels()->pointChannel($ids[0], $ids[1])->channelDescription() . ' for the selected dates.</div>' . "\n";
            }
        }
		
		if($chartGraphData != "")
		{
			for ($inx=0; $inx<$reports->size(); $inx++)
			{
				$chartSeriesData .= '<value xid=\"'.$inx.'\">'.$reports->labels($inx).'</value>';
			}
		}

    }
//DAILY USAGE PROFILE
// Line Chart
    elseif ($_POST['report'] == 'dailyUsageProfile')
    {

        $viewReports = BuildReportHeader("Daily Usage Profile", $_POST['repBaseDateFrom'], $_POST['repBaseDateTo']);


        foreach ($_POST['repPoints'] as $pointChannel=>$state) {
            $ids = explode(":", $pointChannel);
			$chartSeriesData = '';
			$chartData = '';
			$chartGraphData = '';
            $valueRotation = 90;

            if ($reports->GetDailyUsageProfile($ids[0], $ids[1], $_POST['repBaseDateFrom'], $_POST['repBaseDateTo']))
            {
                //$reports->preDebugger($reports);
                $gid++;
				$graphName = $userObject->pointChannels()->pointChannel($ids[0], $ids[1])->channelDescription();
				$valueUnits = $userObject->pointChannels()->pointChannel($ids[0], $ids[1])->units()->unitOfMeasureName();
				
				$graphSettings .= compileGraphSettings($gid, $aryColors[$gid], $graphName);

				$chartGraphData .= '<graph gid=\"'.$gid.'\">';

                for ($inx=0; $inx<$reports->size(); $inx++)
                {
                    $chartGraphData .= '<value xid=\"'.$inx.'\">'.$reports->values($inx).'</value>';
					
                }
				
				$chartGraphData .= '</graph>';
				
				for ($inx=0; $inx<$reports->size(); $inx++)
				{
					$chartSeriesData .= '<value xid=\"'.$inx.'\">'.$reports->labels($inx).'</value>';
				}
				
				$chartData = '<chart><series>'.$chartSeriesData.'</series><graphs>'.$chartGraphData.'</graphs></chart>';
				
				$chart .= assembleCharts($ids[0].'_'.$ids[1], 'line', $chartData, $valueRotation, $aryUnits[$valueUnits], $reports->labelUnits(), 1, 24, $graphSettings);
				
                $chart .= '<div align="center">' .
                                  '<table border="1" cellspacing="0" cellpadding="3">' .
                                    '<tr>' .
                                        '<td align="right">Maximim Hourly Usage:</td><td align="right">' . $reports->statistic("MaximumUsage") . '</td>' . 
                                        '<td>' . $reports->statistic("MaximumUsageDate") . '</td>' .
                                    '<tr>' .
                                        '<td align="right">Average Hourly Usage:</td><td align="right">' . $reports->statistic("AverageUsage") . '</td>' . 
                                        '<td>&nbsp;</td>' .
                                    '</tr>' .
                                    '<tr>' .
                                        '<td align="right">Minimum Hourly Usage:</td><td align="right">' . $reports->statistic("MinimumUsage") . '</td>' .
                                        '<td>' . $reports->statistic("MinimumUsageDate") . '</td>' .
                                    '</tr>' .
                                    '<tr>' .
                                        '<td align="right">Total Usage:</td><td align="right">' . $reports->statistic("TotalUsage") . '</td>' .
                                        '<td>&nbsp;</td>' .
                                    '</tr>' .
                                  '</table>' .
                              '</div>';
            }
            else
            {
                $viewReports .= '<div class="error" style="width: 700px;">Could not retreive Daily Usage Profile Report for ' . $userObject->pointChannels()->pointChannel($ids[0], $ids[1])->channelDescription() . ' for the selected dates.</div>' . "\n";
            }
        }

    }
//WEEKLY USAGE PROFILE
// Line Chart
    elseif ($_POST['report'] == 'weeklyUsageProfile')
    {
		/*
		print '<pre>';
		print_r($reports);
		print '</pre>';
		*/

        $fromDate = strtotime(str_replace("-", "/", $_POST['repBaseDateFrom']));
        $toDate = strtotime(str_replace("-", "/", $_POST['repBaseDateTo']));
        $daySpan = 1 + ($toDate - $fromDate)/86400;

        if ($daySpan >= 7) {
            $viewReports = BuildReportHeader("Weekly Usage Profile", $_POST['repBaseDateFrom'], $_POST['repBaseDateTo']);
    
            foreach ($_POST['repPoints'] as $pointChannel=>$state) {
                $ids = explode(":", $pointChannel);
    			$chartSeriesData = '';
    			$chartData = '';
    			$chartGraphData = '';
    
                if ($reports->GetWeeklyUsageProfile($ids[0], $ids[1], $_POST['repBaseDateFrom'], $_POST['repBaseDateTo']))
                {
                    /*
					print '<pre>';
            		print_r($reports);
		            print '</pre>';
                    */

                    $gid++;
    				$graphName = $userObject->pointChannels()->pointChannel($ids[0], $ids[1])->channelDescription();
    				$valueUnits = $userObject->pointChannels()->pointChannel($ids[0], $ids[1])->units()->unitOfMeasureName();
    				
    				$graphSettings .= compileGraphSettings($gid, $aryColors[$gid], $graphName);
    
    				$chartGraphData .= '<graph gid=\"'.$gid.'\">';
    				
                    for ($inx=0; $inx<$reports->size(); $inx++)
                    {
                        $chartGraphData .= '<value xid=\"'.$inx.'\">'.$reports->values($inx).'</value>';
                    }
    						
    				$chartGraphData .= '</graph>';
    				
    				for ($inx=0; $inx<$reports->size(); $inx++)
    				{
						
    					$chartSeriesData .= '<value xid=\"'.$inx.'\">'.$reports->labels($inx).'</value>';
    				}
    
    				$chartData = '<chart><series>'.$chartSeriesData.'</series><graphs>'.$chartGraphData.'</graphs></chart>';
					/*
					print '<pre>';
					print_r($chartData);
					print '</pre>';
    				*/
    				$chart .= assembleCharts($ids[0].'_'.$ids[1], 'line', $chartData, $valueRotation, $aryUnits[$valueUnits], $reports->labelUnits(), '1', '7', $graphSettings);
    				
                    $chart .= '<div align="center">' .
                                      '<table border="1" cellspacing="0" cellpadding="3">' .
                                        '<tr>' .
                                            '<td align="right">Maximim Daily Usage:</td><td align="right">' . $reports->statistic("MaximumUsage") . '</td>' . 
                                            '<td>' . $reports->statistic("MaximumUsageDate") . '</td>' .
                                        '<tr>' .
                                            '<td align="right">Average Daily Usage:</td><td align="right">' . $reports->statistic("AverageUsage") . '</td>' . 
                                            '<td>&nbsp;</td>' .
                                        '</tr>' .
                                        '<tr>' .
                                            '<td align="right">Minimum Daily Usage:</td><td align="right">' . $reports->statistic("MinimumUsage") . '</td>' .
                                            '<td>' . $reports->statistic("MinimumUsageDate") . '</td>' .
                                        '</tr>' .
                                        '<tr>' .
                                            '<td align="right">Total Usage:</td><td align="right">' . $reports->statistic("TotalUsage") . '</td>' .
                                            '<td>&nbsp;</td>' .
                                        '</tr>' .
                                      '</table>' .
                                  '</div>';
                }
                else
                {
                    $viewReports .= '<div class="error" style="width: 700px;">Could not retreive Weekly Usage Report for ' . $userObject->pointChannels()->pointChannel($ids[0], $ids[1])->channelDescription() . ' for the selected dates.</div>' . "\n";
                }
            }
    
             if($chartGraphData != "")
    		{
    			for ($inx=0; $inx<$reports->size(); $inx++)
    			{
    				$chartSeriesData .= '<value xid=\"'.$inx.'\">'.$reports->labels($inx).'</value>';
    			}
    		
    			$chartType = 'line';
    			$showChart = true;
                $labelFreq = '24';
                $gridCount = '24';
    			$valueUnits = $reports->valueUnits();
    			$labelUnits = $reports->labelUnits();
    
    			$chartData = '<chart><series>'.$chartSeriesData.'</series><graphs>'.$chartGraphData.'</graphs></chart>';
    		}
        }
        else
        {
            $viewReports = '<div class="error" style="width: 700px;">You must select a minimum date span of 7 days to display the Weekly Usage Profile.</div>' . "\n";
        }
    }
//MONTHLY USAGE PROFILE
// Bar Chart
    elseif ($_POST['report'] == 'monthlyUsageProfile')
    {
        $fromDate = strtotime(str_replace("-", "/", $_POST['repBaseDateFrom']));
        $toDate = strtotime(str_replace("-", "/", $_POST['repBaseDateTo']));
        $daySpan = 1 + ($toDate - $fromDate)/86400;

        if ($daySpan >= 28) {
            $viewReports = BuildReportHeader("Monthly Usage Profile", $_POST['repBaseDateFrom'], $_POST['repBaseDateTo']);
    
            foreach ($_POST['repPoints'] as $pointChannel=>$state) {
                $ids = explode(":", $pointChannel);
    			$chartSeriesData = '';
    			$chartData = '';
    			$chartGraphData = '';
    
                if ($reports->GetMonthlyUsageProfile($ids[0], $ids[1], $_POST['repBaseDateFrom'], $_POST['repBaseDateTo']))
                {
                    $gid++;
    				$graphName = $userObject->pointChannels()->pointChannel($ids[0], $ids[1])->channelDescription();
    				$valueUnits = $userObject->pointChannels()->pointChannel($ids[0], $ids[1])->units()->unitOfMeasureName();
    				
    				$graphSettings .= compileGraphSettings($gid, $aryColors[$gid], $graphName);
    
    				$chartGraphData .= '<graph gid=\"'.$gid.'\">';
    				
                    for ($inx=0; $inx<$reports->size(); $inx++)
                    {
                        $chartGraphData .= '<value xid=\"'.$inx.'\">'.$reports->values($inx).'</value>';
                    }
    						
    				$chartGraphData .= '</graph>';
    				
    				for ($inx=0; $inx<$reports->size(); $inx++)
    				{
    					$chartSeriesData .= '<value xid=\"'.$inx.'\">'.$reports->labels($inx).'</value>';
    				}
    
    				$chartData = '<chart><series>'.$chartSeriesData.'</series><graphs>'.$chartGraphData.'</graphs></chart>';
    				
    				$chart .= assembleCharts($ids[0].'_'.$ids[1], 'bar', $chartData, $valueRotation, $aryUnits[$valueUnits], $reports->labelUnits(), $labelFreq, $gridCount, $graphSettings);
    				
                    $chart .= '<div align="center">' .
                                      '<table border="1" cellspacing="0" cellpadding="3">' .
                                        '<tr>' .
                                            '<td align="right">Maximim Monthly Usage:</td><td align="right">' . $reports->statistic("MaximumUsage") . '</td>' . 
                                            '<td>' . $reports->statistic("MaximumUsageDate") . '</td>' .
                                        '<tr>' .
                                            '<td align="right">Average Monthly Usage:</td><td align="right">' . $reports->statistic("AverageUsage") . '</td>' . 
                                            '<td>&nbsp;</td>' .
                                        '</tr>' .
                                        '<tr>' .
                                            '<td align="right">Minimum Monthly Usage:</td><td align="right">' . $reports->statistic("MinimumUsage") . '</td>' .
                                            '<td>' . $reports->statistic("MinimumUsageDate") . '</td>' .
                                        '</tr>' .
                                        '<tr>' .
                                            '<td align="right">Total Usage:</td><td align="right">' . $reports->statistic("TotalUsage") . '</td>' .
                                            '<td>&nbsp;</td>' .
                                        '</tr>' .
                                      '</table>' .
                                  '</div>';
                }
                else
                {
                    $viewReports .= '<div class="error" style="width: 700px;">Could not retreive Monthly Usage Report for ' . $userObject->pointChannels()->pointChannel($ids[0], $ids[1])->channelDescription() . ' for the selected dates.</div>' . "\n";
                }
            }
    
             if($chartGraphData != "")
    		{
    			for ($inx=0; $inx<$reports->size(); $inx++)
    			{
    				$chartSeriesData .= '<value xid=\"'.$inx.'\">'.$reports->labels($inx).'</value>';
    			}
    		
    			$chartType = 'bar';
    			$showChart = true;
    			$valueUnits = $reports->valueUnits();
    			$labelUnits = $reports->labelUnits();
    
    			$chartData = '<chart><series>'.$chartSeriesData.'</series><graphs>'.$chartGraphData.'</graphs></chart>';
    		}
        }
        else
        {
            $viewReports = '<div class="error" style="width: 700px;">You must select a minimum date span of 28 days to display the Monthly Usage Profile.</div>' . "\n";
        }
    }
    else
    {
        $viewReports = '<div class="error" style="width: 700px;">You have selected an unknown Report Type to display.</div>';
    }

    return $viewReports . $chart;

}

function assembleCharts($id, $chartType, $chartData, $valueRotation, $valueUnits, $labelUnits, $labelFreq, $gridCount, $graphSettings)
{

	if($valueRotation > 0 & $chartType != 'line')
	{
		$legendPosition = 475;
		$swfHeight = 525;
		$unitPosition = 440;
		$bottomMargin = 150;
	}
	else
	{
		$legendPosition = 390;
		$swfHeight = 430;
		$unitPosition = 365;
		$bottomMargin = 100;
	}
	
	
	if($chartType == "line")
	{
		$chartDir = 'amline/';	
		$swfFile = 'amline.swf';
		$settings = LineChartSettings($valueUnits, $labelUnits, $valueRotation, $legendPosition, $unitPosition, $bottomMargin, $graphSettings, $labelFreq, $gridCount);
	}
	else
	{
		$chartDir = 'amcolumn/';
		$swfFile = 'amcolumn.swf';
		$settings = BarChartSettings($valueUnits, $labelUnits, $valueRotation, $legendPosition, $unitPosition, $bottomMargin, $graphSettings);	
	}

	$chart = '
		<div id="flashcontent_'.$id.'">
			<strong>If this message displays for more than a couple of minutes,<br /> you may need to upgrade your Flash Player</strong>
		</div>		
		<script type="text/javascript" src="'.$chartDir.'swfobject.js"></script>
		<script type="text/javascript">		
			// <![CDATA[
			var so = new SWFObject("'.$chartDir.$swfFile.'", "amcolumn", "765", "'.$swfHeight.'", "8", "#CBCCD9");		
			so.addVariable("path", "'.$chartDir.'")                  
			so.addVariable("chart_settings", escape("'.$settings.'"));		
			so.addVariable("chart_data", escape("'.$chartData.'"));
			so.addVariable("preloader_color", "#CBCCD9");		
			so.addParam("wmode", "transparent");
			so.write("flashcontent_'.$id.'");
			// ]]>		
		</script>
	';
	
	return $chart;
}

function BuildReportHeader($reportTitle, $fromDate, $toDate)
{
	$reportHeader = '<div style="text-align: center; font-weight: bold;">' . "\n";
	$reportHeader .= $reportTitle . "\n";
	$reportHeader .= '<br />' . "\n";
	$reportHeader .= 'From ' . $fromDate . ' to ' . $toDate . "\n";
	$reportHeader .= '</div>' . "\n";

    return $reportHeader;
}


function compileGraphSettings($graphID, $colorString, $graphName)
{	
    if (!isset($settingsString)) $settingsString = '';
	$settingsString .= '<graph gid=\"'.$graphID.'\">';
	$settingsString .= '<type>column</type>';
	$settingsString .= '<title>'.$graphName.'</title>';
	$settingsString .= '<color>'.$colorString.'</color>';
	$settingsString .= '<alpha></alpha>';
	$settingsString .= '<data_labels>';
	$settingsString .= '<![CDATA[]]>';
	$settingsString .= '</data_labels>';
	$settingsString .= '<gradient_fill_colors></gradient_fill_colors>';
	$settingsString .= '<balloon_color></balloon_color>';
	$settingsString .= '<balloon_alpha>100</balloon_alpha>';
	$settingsString .= '<balloon_text_color>#FFFFFF</balloon_text_color>';
	$settingsString .= '<balloon_text>';
	$settingsString .= '<![CDATA[]]>';
	$settingsString .= '</balloon_text>';
	$settingsString .= '<fill_alpha></fill_alpha>';
	$settingsString .= '<width></width>';
	$settingsString .= '<bullet></bullet>';
	$settingsString .= '<bullet_size></bullet_size>';
	$settingsString .= '<bullet_color></bullet_color>';
	$settingsString .= '<visible_in_legend></visible_in_legend>';
	$settingsString .= '<pattern></pattern>';
	$settingsString .= '<pattern_color></pattern_color>';
	$settingsString .= '</graph>';
	
	return $settingsString;
}

function BarChartSettings($valueUnits, $labelUnits, $valueRotation, $legendPosition, $unitPosition, $bottomMargin, $graphSettings)
{
	
    $barChartSettings = '<settings>';	
	$barChartSettings .= '<type>column</type>';
	$barChartSettings .= '<data_type>xml</data_type>';
	$barChartSettings .= '<csv_separator></csv_separator>';
	$barChartSettings .= '<skip_rows></skip_rows>';
	$barChartSettings .= '<font></font>';
	$barChartSettings .= '<text_size>10</text_size>';
	$barChartSettings .= '<text_color>#FFFFFF</text_color>';
	$barChartSettings .= '<decimals_separator>.</decimals_separator>';
	$barChartSettings .= '<thousands_separator>,</thousands_separator>';
	$barChartSettings .= '<scientific_min></scientific_min>';
	$barChartSettings .= '<scientific_max></scientific_max>';
	$barChartSettings .= '<digits_after_decimal></digits_after_decimal>';
	$barChartSettings .= '<redraw></redraw>';
	$barChartSettings .= '<reload_data_interval></reload_data_interval>';
	$barChartSettings .= '<preloader_on_reload></preloader_on_reload>';
	$barChartSettings .= '<add_time_stamp></add_time_stamp>';
	$barChartSettings .= '<precision></precision>';
	$barChartSettings .= '<depth>0</depth>';
	$barChartSettings .= '<angle>0</angle>';
	$barChartSettings .= '<colors></colors>';
	$barChartSettings .= '<js_enabled></js_enabled>';
	/*
	<balloon_text>
{title}: {value} downloads
</balloon_text>*/
	//column
	$barChartSettings .= '<column>';
	$barChartSettings .= '<type></type>';
	$barChartSettings .= '<width>85</width>';
	$barChartSettings .= '<spacing>0</spacing>';
	$barChartSettings .= '<grow_time>3</grow_time>';
	$barChartSettings .= '<grow_effect></grow_effect>';
	$barChartSettings .= '<sequenced_grow>true</sequenced_grow>';
	$barChartSettings .= '<alpha></alpha>';
	$barChartSettings .= '<border_color></border_color>';
	$barChartSettings .= '<border_alpha></border_alpha>';
	$barChartSettings .= '<data_labels>';
	$barChartSettings .= '<![CDATA[]]>';
	$barChartSettings .= '</data_labels>';
	$barChartSettings .= '<data_labels_text_color></data_labels_text_color>';
	$barChartSettings .= '<data_labels_text_size></data_labels_text_size>';
	$barChartSettings .= '<data_labels_position></data_labels_position>';
	$barChartSettings .= '<data_labels_always_on></data_labels_always_on>';
	$barChartSettings .= '<balloon_text>';
	$barChartSettings .= '{value}';
	$barChartSettings .= '</balloon_text>';
	$barChartSettings .= '<link_target></link_target>';
	$barChartSettings .= '<gradient></gradient>';
	$barChartSettings .= '<bullet_offset></bullet_offset>';
	$barChartSettings .= '<hover_brightness>30</hover_brightness>';
	$barChartSettings .= '</column>';
    /*
	//line
	$barChartSettings .= '<line>';
	$barChartSettings .= '<connect></connect>';
	$barChartSettings .= '<width></width>';
	$barChartSettings .= '<alpha></alpha>';
	$barChartSettings .= '<fill_alpha></fill_alpha>';
	$barChartSettings .= '<bullet></bullet>';
	$barChartSettings .= '<bullet_size></bullet_size>';
	$barChartSettings .= '<data_labels>';
	$barChartSettings .= '<![CDATA[]]>';
	$barChartSettings .= '</data_labels>';
	$barChartSettings .= '<data_labels_text_color></data_labels_text_color>';
	$barChartSettings .= '<data_labels_text_size></data_labels_text_size>';
	$barChartSettings .= '<balloon_text>';
	$barChartSettings .= '<![CDATA[]]>';
	$barChartSettings .= '</balloon_text>';
	$barChartSettings .= '<link_target></link_target>';
	$barChartSettings .= '</line>';
*/
     
	//background
	$barChartSettings .= '<background>';
	$barChartSettings .= '<color></color>';
	$barChartSettings .= '<alpha>0</alpha>';
	$barChartSettings .= '<border_color></border_color>';
	$barChartSettings .= '<border_alpha>0</border_alpha>';
	$barChartSettings .= '<file></file>';
	$barChartSettings .= '</background>';

	//plot_area
	$barChartSettings .= '<plot_area>';
	$barChartSettings .= '<color>#CBCCD9</color>';
	$barChartSettings .= '<alpha>100</alpha>';
	$barChartSettings .= '<border_color>#FFFFFF</border_color>';
	$barChartSettings .= '<border_alpha></border_alpha>';
	$barChartSettings .= '<margins>';
	$barChartSettings .= '<left>90</left>';
	$barChartSettings .= '<top>30</top>';
	$barChartSettings .= '<right>50</right>';
	$barChartSettings .= '<bottom>'.$bottomMargin.'</bottom>';
	$barChartSettings .= '</margins>';
	$barChartSettings .= '</plot_area>';
/*
	//grid
	$barChartSettings .= '<grid>';
	$barChartSettings .= '<category>';
	$barChartSettings .= '<color>#FFFFFF</color>';
	$barChartSettings .= '<alpha>100</alpha>';
	$barChartSettings .= '<dashed></dashed>';
	$barChartSettings .= '<dash_length></dash_length>';
	$barChartSettings .= '</category>';
	$barChartSettings .= '<value>';
	$barChartSettings .= '<color>#FFFFFF</color>';
	$barChartSettings .= '<alpha>100</alpha>';
	$barChartSettings .= '<dashed></dashed>';
	$barChartSettings .= '<dash_length></dash_length>';
	$barChartSettings .= '<approx_count></approx_count>';
	$barChartSettings .= '<fill_color>#FFFFFF</fill_color>';
	$barChartSettings .= '<fill_alpha>100</fill_alpha>';
	$barChartSettings .= '</value>';
	$barChartSettings .= '</grid>';
*/
	//values
	$barChartSettings .= '<values>';
	$barChartSettings .= '<category>';
	$barChartSettings .= '<enabled></enabled>';
	$barChartSettings .= '<frequency></frequency>';
	$barChartSettings .= '<start_from></start_from>';
	$barChartSettings .= '<rotate>'.$valueRotation.'</rotate>';
	$barChartSettings .= '<color>#FFFFFF</color>';
	$barChartSettings .= '<text_size>10</text_size>';
	$barChartSettings .= '<inside></inside>';
	$barChartSettings .= '</category>';
	$barChartSettings .= '<value>';
	$barChartSettings .= '<enabled>true</enabled>';
	$barChartSettings .= '<reverse></reverse>';
	$barChartSettings .= '<min>0</min>';
	$barChartSettings .= '<max></max>';
	$barChartSettings .= '<strict_min_max></strict_min_max>';
	$barChartSettings .= '<frequency></frequency>';
	$barChartSettings .= '<rotate></rotate>';
	$barChartSettings .= '<skip_first></skip_first>';
	$barChartSettings .= '<skip_last></skip_last>';
	$barChartSettings .= '<color>#FFFFFF</color>';
	$barChartSettings .= '<text_size></text_size>';
	$barChartSettings .= '<unit></unit>';
	$barChartSettings .= '<unit_position></unit_position>';
	$barChartSettings .= '<integers_only></integers_only>';
	$barChartSettings .= '<inside></inside>';
	$barChartSettings .= '<duration></duration>';
	$barChartSettings .= '</value>';
	$barChartSettings .= '</values>';
	
	//axes
	$barChartSettings .= '<axes>';
	$barChartSettings .= '<category>';
	$barChartSettings .= '<color>#FFFFFF</color>';
	$barChartSettings .= '<alpha>100</alpha>';
	$barChartSettings .= '<width>1</width>';
	$barChartSettings .= '<tick_length></tick_length>';
	$barChartSettings .= '</category>';
	$barChartSettings .= '<value>';
	$barChartSettings .= '<color>#FFFFFF</color>';
	$barChartSettings .= '<alpha>100</alpha>';
	$barChartSettings .= '<width>1</width>';
	$barChartSettings .= '<tick_length></tick_length>';
	$barChartSettings .= '<logarithmic></logarithmic>';
	$barChartSettings .= '</value>';
	$barChartSettings .= '</axes>';


	//balloon
		/*
	$barChartSettings .= '<balloon>';
	$barChartSettings .= '<enabled>true</enabled>';
	$barChartSettings .= '<alpha>20</alpha>';

	$barChartSettings .= '<color>980000</color>';
	
	$barChartSettings .= '<text_color>980000</text_color>';
	$barChartSettings .= '<text_size></text_size>';
	$barChartSettings .= '<max_width></max_width>';
	$barChartSettings .= '<corner_radius></corner_radius>';
	$barChartSettings .= '<border_width></border_width>';
	$barChartSettings .= '<border_alpha></border_alpha>';
	$barChartSettings .= '<border_color></border_color>';
	
	$barChartSettings .= '</balloon>';
	*/
	
	
	//legend
	
	$barChartSettings .= '<legend>';
	$barChartSettings .= '<enabled>true</enabled>';
	$barChartSettings .= '<x></x>';
	$barChartSettings .= '<y>'.$legendPosition.'</y>';
	$barChartSettings .= '<width></width>';
	$barChartSettings .= '<max_columns></max_columns>';
	$barChartSettings .= '<color>#FFFFFF</color>';
	$barChartSettings .= '<alpha></alpha>';
	$barChartSettings .= '<border_color></border_color>';
	$barChartSettings .= '<border_alpha></border_alpha>';
	$barChartSettings .= '<text_color>#FFFFFF</text_color>';
	$barChartSettings .= '<text_size></text_size>';
	$barChartSettings .= '<spacing></spacing>';
	$barChartSettings .= '<margins></margins>';
	$barChartSettings .= '<reverse_order></reverse_order>';
	$barChartSettings .= '<align></align>';
	$barChartSettings .= '<key>';
	$barChartSettings .= '<size></size>';
	$barChartSettings .= '<border_color></border_color>';
	$barChartSettings .= '</key>';
	$barChartSettings .= '</legend>';
	/*
	//export_as_image
	$barChartSettings .= '<export_as_image>';
	$barChartSettings .= '<file></file>';
	$barChartSettings .= '<target></target>';
	$barChartSettings .= '<x></x>';
	$barChartSettings .= '<y></y>';
	$barChartSettings .= '<color></color>';
	$barChartSettings .= '<alpha></alpha>';
	$barChartSettings .= '<text_color></text_color>';
	$barChartSettings .= '<text_size></text_size>';
	$barChartSettings .= '</export_as_image>';
	//error_messages
	$barChartSettings .= '<error_messages>';
	$barChartSettings .= '<enabled></enabled>';
	$barChartSettings .= '<x></x>';
	$barChartSettings .= '<y></y>';
	$barChartSettings .= '<color></color>';
	$barChartSettings .= '<alpha></alpha>';
	$barChartSettings .= '<text_color></text_color>';
	$barChartSettings .= '<text_size></text_size>';
	$barChartSettings .= '</error_messages>';
	//strings
	$barChartSettings .= '<strings>';
	$barChartSettings .= '<no_data></no_data>';
	$barChartSettings .= '<export_as_image></export_as_image>';
	$barChartSettings .= '<collecting_data></collecting_data>';
	$barChartSettings .= '<ss></ss>';
	$barChartSettings .= '<mm></mm>';
	$barChartSettings .= '<hh></hh>';
	$barChartSettings .= '<DD>&nbsp;</DD>';
	$barChartSettings .= '</strings>';
	//context_menu
	*/
	$barChartSettings .= '<context_menu>';
	$barChartSettings .= '<default_items>';
	$barChartSettings .= '<zoom></zoom>';
	$barChartSettings .= '<print></print>';
	$barChartSettings .= '</default_items>';
	$barChartSettings .= '</context_menu>';
      
	//labels
	$barChartSettings .= '<labels>';
	$barChartSettings .= '<label lid=\"0\">';
	$barChartSettings .= '<x>-725</x>';
	$barChartSettings .= '<y>150</y>';
	$barChartSettings .= '<rotate>false</rotate>';
	$barChartSettings .= '<width></width>';
	$barChartSettings .= '<align>center</align>';
	$barChartSettings .= '<text_color></text_color>';
	$barChartSettings .= '<text_size></text_size>';
	$barChartSettings .= '<text>';
	$barChartSettings .= '<![CDATA['.$valueUnits.']]>';
	$barChartSettings .= '</text>';
	$barChartSettings .= '</label>';
	$barChartSettings .= '<label lid=\"1\">';
	$barChartSettings .= '<x>10</x>';
	$barChartSettings .= '<y>'.$unitPosition.'</y>';
	$barChartSettings .= '<width></width>';
	$barChartSettings .= '<align>center</align>';
	$barChartSettings .= '<text_color></text_color>';
	$barChartSettings .= '<text_size>11</text_size>';
	$barChartSettings .= '<text>';
	$barChartSettings .= '<![CDATA['.$labelUnits.']]>';
	$barChartSettings .= '</text>';
	$barChartSettings .= '</label>';
	$barChartSettings .= '</labels>';
    
	//graphs

	$barChartSettings .= '<graphs>';
	$barChartSettings .= $graphSettings;
	$barChartSettings .= '</graphs>';
	

	/*
	//guides
	$barChartSettings .= '<guides>';
	$barChartSettings .= '<max_min></max_min>';
	$barChartSettings .= '<guide>';
	$barChartSettings .= '<behind></behind>';
	$barChartSettings .= '<start_value></start_value>';
	$barChartSettings .= '<end_value></end_value>';
	$barChartSettings .= '<title></title>';
	$barChartSettings .= '<width></width>';
	$barChartSettings .= '<color></color>';
	$barChartSettings .= '<alpha></alpha>';
	$barChartSettings .= '<fill_color></fill_color>';
	$barChartSettings .= '<fill_alpha></fill_alpha>';
	$barChartSettings .= '<inside></inside>';
	$barChartSettings .= '<centered></centered>';
	$barChartSettings .= '<rotate></rotate>';
	$barChartSettings .= '<text_size></text_size>';
	$barChartSettings .= '<text_color></text_color>';
	$barChartSettings .= '<dashed></dashed>';
	$barChartSettings .= '<dash_length></dash_length>';
	$barChartSettings .= '</guide>';
	$barChartSettings .= '</guides>';*/

	$barChartSettings .= '</settings>';
	
    return $barChartSettings;
}

function compileLineGraphSettings($graphID, $colorString, $graphName)
{
	$settingsString = '<graph gid=\"'.$graphID.'\">';
	$settingsString .= '<axis>left</axis>';
	$settingsString .= '<title>'.$graphName.'</title>';
	$settingsString .= '<color>'.$colorString.'</color>';
	$settingsString .= '<color_hover>#FF6701</color_hover>';
	$settingsString .= '<line_alpha></line_alpha>';
	$settingsString .= '<line_width></line_width>';
	$settingsString .= '<fill_alpha>0</fill_alpha>';
	$settingsString .= '<fill_color></fill_color>';
	$settingsString .= '<balloon_color></balloon_color>';
	$settingsString .= '<balloon_alpha></balloon_alpha>';
	$settingsString .= '<balloon_text_color></balloon_text_color>';
	$settingsString .= '<bullet></bullet>';
	$settingsString .= '<bullet_size></bullet_size>';
	$settingsString .= '<bullet_color></bullet_color>';
	$settingsString .= '<bullet_alpha></bullet_alpha>';
	$settingsString .= '<hidden></hidden>';
	$settingsString .= '<selected></selected>';
	$settingsString .= '<balloon_text>';
	$settingsString .= '<![CDATA[]]>';
	$settingsString .= '</balloon_text>';
	$settingsString .= '<vertical_lines></vertical_lines>';
	$settingsString .= '</graph>';

	return $settingsString;
}


function LineChartSettings($valueUnits, $labelUnits, $valueRotation, $legendPosition, $unitPosition, $bottomMargin, $graphSettings, $labelFreq, $gridCount) 
{

	$settingsString = '<settings>';	
	
	$settingsString .= '<export_image_file>amline/export.php</export_image_file>';
	$settingsString .= '<decimals_separator>.</decimals_separator>';
	$settingsString .= '<thousands_separator>,</thousands_separator>';
	$settingsString .= '<connect>false</connect>';
	//plot area
	$settingsString .= '<plot_area>';
	$settingsString .= '<color>#CBCCD9</color>';
	$settingsString .= '<alpha>100</alpha>';
    $settingsString .= '<margins>';
	$settingsString .= '<left>80</left>';
	$settingsString .= '<top>10</top>';
	$settingsString .= '<right>10</right>';
	$settingsString .= '<bottom>100</bottom>';
    $settingsString .= '</margins>';
	$settingsString .= '</plot_area>';	
	//grid
	$settingsString .= '<grid>';
	$settingsString .= '<x>';
	$settingsString .= '<approx_count>' . $gridCount . '</approx_count>';
	$settingsString .= '</x>';
    $settingsString .= '<y_right>';
    $settingsString .= '<enabled>False</enabled>';
    $settingsString .= '</y_right>';
	$settingsString .= '</grid>';
	//values
	$settingsString .= '<values>';
	$settingsString .= '<x>';
	$settingsString .= '<rotate>'.$valueRotation.'</rotate>';
	$settingsString .= '<frequency>' . $labelFreq . '</frequency>';
	$settingsString .= '<color>#FFFFFF</color>';
	$settingsString .= '<text_size>10</text_size>';
	$settingsString .= '</x>';
	$settingsString .= '<y_left>';
	$settingsString .= '<color>#FFFFFF</color>';
	$settingsString .= '<text_size>10</text_size>';
	$settingsString .= '</y_left>';
    $settingsString .= '<y_right>';
	$settingsString .= '<color>#FFFFFF</color>';
	$settingsString .= '<text_size>10</text_size>';
	$settingsString .= '</y_right>';
	$settingsString .= '</values>';
	//indicator
	$settingsString .= '<indicator>';
	$settingsString .= '<color>#1B6097</color>';
	$settingsString .= '<selection_color>#FF6701</selection_color>';
	$settingsString .= '<x_balloon_text_color>#FFFFFF</x_balloon_text_color>';
	$settingsString .= '<one_y_balloon>true</one_y_balloon>';
	$settingsString .= '</indicator>';
  	//axes
	$settingsString .= '<axes>.';
	$settingsString .= '<x>';
	$settingsString .= '<color>#CBCCD9</color>';
    $settingsString .= '</x>';
	$settingsString .= '<y_left>';
	$settingsString .= '<color>#CBCCD9</color>';
    $settingsString .= '</y_left>';
   	$settingsString .= '<y_right>';
	$settingsString .= '<color>#CBCCD9</color>';
    $settingsString .= '</y_right>';
    $settingsString .= '</axes>.';
	//legend
	$settingsString .= '<legend>';
	$settingsString .= '<text_color>#FFFFFF</text_color>';
	$settingsString .= '<text_color_hover>#FF6701</text_color_hover>';
	$settingsString .= '<text_size>9</text_size>';
	$settingsString .= '<spacing>5</spacing>';
	$settingsString .= '<margins>10</margins>';
	$settingsString .= '<max_columns></max_columns>';
	$settingsString .= '<key>';
	$settingsString .= '<size>10</size>';
	$settingsString .= '</key>';
	$settingsString .= '<values>';
	$settingsString .= '<enabled>false</enabled>';
	$settingsString .= '</values>';
	$settingsString .= '</legend>';
	//strings
	$settingsString .= '<strings>';
	$settingsString .= '<collecting_data>Collecting Data . . . </collecting_data>';
	$settingsString .= '</strings>';
	
	//labels
	$settingsString .= '<labels>';
	$settingsString .= '<label lid=\"0\">';
	$settingsString .= '<x>-740</x>';
	$settingsString .= '<y>125</y>';
	$settingsString .= '<rotate>false</rotate>';
	$settingsString .= '<width></width>';
	$settingsString .= '<align>center</align>';
	$settingsString .= '<text_color>#FFFFFF</text_color>';
	$settingsString .= '<text_size>10</text_size>';
	$settingsString .= '<text>';
	$settingsString .= '<![CDATA['.$valueUnits.']]>';
	$settingsString .= '</text>';
	$settingsString .= '</label>';
	$settingsString .= '<label lid=\"1\">';
	$settingsString .= '<x>15</x>';
	$settingsString .= '<y>'.$unitPosition.'</y>';
	$settingsString .= '<rotate>false</rotate>';
	$settingsString .= '<width></width>';
	$settingsString .= '<align>center</align>';
	$settingsString .= '<text_color>#FFFFFF</text_color>';
	$settingsString .= '<text_size>10</text_size>';
	$settingsString .= '<text>';
	$settingsString .= '<![CDATA['.$labelUnits.']]>';
	$settingsString .= '</text>';
	$settingsString .= '</label>';
	$settingsString .= '</labels>';
	
	//graphs
	$settingsString .= '<graphs>';
	$settingsString .= $graphSettings;
	$settingsString .= '</graphs>';
	
	$settingsString .= '</settings>';
	
	return $settingsString;
}
