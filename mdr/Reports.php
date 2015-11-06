<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
class Reports extends CAO {

    private $p_labels = array();
    private $p_lunits = "";
    private $p_size = 0;
    private $p_values = array();
    private $p_vunits = "";

    private $p_statistics = array();

    private $p_dayOfWeek = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');

    private $p_monthOfYear = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

    function labels($index) {
        return $this->p_labels[$index];
    }
    function subLabels($index) {
        return $this->p_subLabels[$index];
    }
    function labelUnits() {
        return $this->p_lunits;
    }

    function size() {
        return $this->p_size;
    }

    function values($index) {
        if (array_key_exists($index, $this->p_values)) {
            return $this->p_values[$index];
        } else {
            return null;
        }
    }

    function valueUnits() {
        return $this->p_vunits;
    }

    function statistic($key) {
        return $this->p_statistics[$key];
    }

    function statistics() {
        return $this->p_statistics;
    }

    function __construct() {

        parent::__construct();

    }

    function __destruct() {

        parent::__destruct();

    }

    function GetAverageHourlyProfile($pointID, $channelID, $fromDate, $toDate) {
        
        //echo "In GetAverageHourlyProfile({$pointID}, {$channelID}, {$fromDate}, {$toDate})...<br>\n";
        $this->p_labels = array();
        $this->p_values = array();
        $this->p_statistics = array();

        // for some reason (likely something I'm not seeing) strottime($toDate) & strottime($fromDate)
        // are failing so we'll break them down into date part arrays and re-assemble.     
        
        //mcb note to self 2010.06.05: go through all MDR code and see if there are any new commands that allow 
        // us a more automated approach to dst handling.

        $sql = "select
                  IntervalSetBaseDate,
                  hour(date_sub(IntervalDate,
                                INTERVAL if((IntervalDate >= '2006-04-02 06:00:00' and IntervalDate <= '2006-10-29 06:00:00') or
                                            (IntervalDate >= '2007-03-11 06:00:00' and IntervalDate <= '2007-11-04 06:00:00') or
                                            (IntervalDate >= '2008-03-09 06:00:00' and IntervalDate <= '2008-11-02 06:00:00') or
                                            (IntervalDate >= '2009-03-08 06:00:00' and IntervalDate <= '2009-11-01 06:00:00') or
                                            (IntervalDate >= '2010-03-07 06:00:00' and IntervalDate <= '2010-11-07 06:00:00'), \"4:05\", \"5:05\") HOUR_MINUTE))+1 HourEnding,
                  sum(IntervalValue) IntervalValue
              From
                  t_intervalsettypes ist,
                  t_intervalsets iss,
                  t_intervals i,
                  t_points p
              Where
                  ist.IntervalSetTypeName = 'IntervalSet' and
                  iss.IntervalSetTypeID = ist.IntervalSetTypeID and
                  iss.PointObjectID = " . $pointID . " and
                  iss.ChannelID = " . $channelID . " and
                  iss.IntervalSetBaseDate between '" . $this->fixDate($fromDate) . "' and '" . $this->fixDate($toDate). "' and
                  i.IntervalSetID = iss.IntervalSetID and
                  p.ObjectID = iss.pointObjectId
              Group By
                 IntervalSetBaseDate,
                 HourEnding
              Order By
                  IntervalDate";
//iss.IntervalSetBaseDate between '" . $this->toMySqlDate($fromDate) . "' and '" . $this->toMySqlDate($toDate) . "' and
//$this->preDebugger($sql);
        $result = mysql_query($sql, $this->sqlConnection());

        $this->p_size = 0;
        $totalUsage = 0;
        $maximumUsage = 0;
        $hourCount = 0;
        $seenHour = array();

        $date = '--';
        $hourEnding = '--';

        while ($row = mysql_fetch_array($result)) {            
            
            $hour = $row['HourEnding'] - 1;
            $this->p_labels[$hour] = $row['HourEnding'];

            if (array_key_exists($hour, $this->p_values)) {
                $this->p_values[$hour] += $row['IntervalValue'];
            } else {
                $this->p_values[$hour] = $row['IntervalValue'];
            }

            if ($row['IntervalValue'] > $maximumUsage) {
                $maximumUsage = $row['IntervalValue'];
                $date = $row["IntervalSetBaseDate"];
                $hourEnding = $row['HourEnding'];
            }

            $totalUsage += $row['IntervalValue'];

            if (array_key_exists($hour, $seenHour)) {
                $seenHour[$hour]++;
            } else {
                $seenHour[$hour] = 1;
            }

            $hourCount++;
        }

        $this->p_size = sizeof($this->p_labels);
        $this->p_lunits = "Hour";
        $this->p_vunits = "kW";

        //for ($inx=0; $inx<$this->p_size; $inx++) echo "hourCount={$hourCount}, this->p_values[{$inx}]={$this->p_values[$inx]}, seenHour[{$inx}]={$seenHour[$inx]}<br/>\n";
        for ($inx=0; $inx<$this->p_size; $inx++) {
            if ($seenHour[$inx]) {
                $this->p_values[$inx] = round($this->p_values[$inx]/$seenHour[$inx], 3);
            } else {
                $this->p_values[$inx] = '';
                $this->p_labels[$inx] = $inx+1;
            }
        }

        if ($this->p_size && $date != '--') {
            $this->p_statistics["AverageHourlyDemand"] = round($totalUsage/$hourCount, 3) . " KW";
            $this->p_statistics["MaximumHourlyDemand"] = round($maximumUsage, 3) . " KW on " . date('m-d-Y',strtotime($date)) . " hour ending " . $hourEnding;
            $this->p_statistics["TotalUsage"] = round($totalUsage, 3) . " KWH";
        } else {
            $this->p_statistics["AverageHourlyDemand"] = "0.0 KW";
            $this->p_statistics["MaximumHourlyDemand"] = "0.0 KW";
            $this->p_statistics["TotalUsage"] = "0.0 KWH";
        }

        return ($this->p_size > 0);
    }

    function GetPeakHourlyProfile($pointID, $channelID, $fromDate, $toDate) {
        
        //echo "In GetPeakHourlyProfile({$pointID}, {$channelID}, {$fromDate}, {$toDate})...<br>\n";
        $this->p_labels = array();
        $this->p_values = array();
        $this->p_statistics = array();        

        $sql = "select
                  IntervalSetBaseDate,
                  hour(date_sub(IntervalDate,
                                INTERVAL if((IntervalDate >= '2006-04-02 06:00:00' and IntervalDate <= '2006-10-29 06:00:00') or
                                            (IntervalDate >= '2007-03-11 06:00:00' and IntervalDate <= '2007-11-04 06:00:00') or
                                            (IntervalDate >= '2008-03-09 06:00:00' and IntervalDate <= '2008-11-02 06:00:00') or
                                            (IntervalDate >= '2009-03-08 06:00:00' and IntervalDate <= '2009-11-01 06:00:00') or
                                            (IntervalDate >= '2010-03-07 06:00:00' and IntervalDate <= '2010-11-07 06:00:00'), \"4:05\", \"5:05\") HOUR_MINUTE))+1 HourEnding,
                  sum(IntervalValue) IntervalValue
              From
                  t_intervalsettypes ist,
                  t_intervalsets iss,
                  t_intervals i
              Where
                  ist.IntervalSetTypeName = 'IntervalSet' and
                  iss.IntervalSetTypeID = ist.IntervalSetTypeID and
                  iss.PointObjectID = " . $pointID . " and
                  iss.ChannelID = " . $channelID . " and
                  iss.IntervalSetBaseDate between '" . $this->fixDate($fromDate) . "' and '" . $this->fixDate($toDate) . "' and
                  i.IntervalSetID = iss.IntervalSetID
              Group By
                 IntervalSetBaseDate,
                 HourEnding
              Order By
                  HourEnding,
                  IntervalValue desc";

        //echo "sql=", $sql, "<br>\n";
        $result = mysql_query($sql, $this->sqlConnection());

        $this->p_size = 0;
        $priorHourEnding = 0;
        $maxIntervalValue = 0;
        while ($row = mysql_fetch_array($result)) {
            if ($priorHourEnding < $row['HourEnding']) {
                $this->p_labels[$this->p_size] = $priorHourEnding = $row['HourEnding'];
                $this->p_values[$this->p_size++] = $row['IntervalValue'];

                if ($maxIntervalValue < $row['IntervalValue']) {
                    $maxIntervalValue = $row['IntervalValue'];
                    $this->p_statistics["IntervalValue"] = $maxIntervalValue . " KW";
                    $this->p_statistics["IntervalDate"] = date('m-d-Y', strtotime($row['IntervalSetBaseDate'])) . ", Hour Ending " . $row['HourEnding'];
                }
            }
        }

        $this->p_lunits = "Hour";
        $this->p_vunits = "kW";

        return ($this->p_size > 0);

    }

    function GetTopTenPeaks($pointID, $channelID, $fromDate, $toDate) {
        
        //echo "In GetTopTenPeaks({$pointID}, {$channelID}, {$fromDate}, {$toDate})...<br>\n";
        $this->p_labels = array();
        $this->p_values = array();
        $this->p_statistics = array();

        $sql = "select
                  date_sub(IntervalDate,
                           INTERVAL if((IntervalDate >= '2006-04-02 06:00:00' and IntervalDate <= '2006-10-29 06:00:00') or
                                       (IntervalDate >= '2007-03-11 06:00:00' and IntervalDate <= '2007-11-04 06:00:00') or
                                       (IntervalDate >= '2008-03-09 06:00:00' and IntervalDate <= '2008-11-02 06:00:00') or
                                       (IntervalDate >= '2009-03-08 06:00:00' and IntervalDate <= '2009-11-01 06:00:00') or
                                       (IntervalDate >= '2010-03-07 06:00:00' and IntervalDate <= '2010-11-07 06:00:00'), \"4:05\", \"5:05\") HOUR_MINUTE) IntervalDate,
                  IntervalValue * 12.0 IntervalValue
              From
                  t_intervalsettypes ist,
                  t_intervalsets iss,
                  t_intervals i
              Where
                  ist.IntervalSetTypeName = 'IntervalSet' and
                  iss.IntervalSetTypeID = ist.IntervalSetTypeID and
                  iss.PointObjectID = " . $pointID . " and
                  iss.ChannelID = " . $channelID . " and
                  iss.IntervalSetBaseDate between '" . $this->fixDate($fromDate) . "' and '" . $this->fixDate($toDate) . "' and
                  i.IntervalSetID = iss.IntervalSetID
              Order By
                  IntervalValue desc
              limit
                  10";

        //echo "sql=", $sql, "<br>\n";
        $result = mysql_query($sql, $this->sqlConnection());

        $this->p_size = 0;
        while ($row = mysql_fetch_array($result)) {
            $this->p_labels[$this->p_size] = date('m-d-Y H:i:s', strtotime($row['IntervalDate']));
            $this->p_values[$this->p_size] = $row['IntervalValue'];

            $this->p_statistics[$this->p_labels[$this->p_size]] = $this->p_values[$this->p_size++];
        }

        $this->p_lunits = "Date";
        $this->p_vunits = "kW";

        arsort($this->p_statistics, SORT_NUMERIC);

        return ($this->p_size > 0);
    }

    function GetDailyUsageProfile($pointID, $channelID, $fromDate, $toDate) {
        
        //echo "In GetDailyUsageProfile({$pointID}, {$channelID}, {$fromDate}, {$toDate})...<br>\n";
        $this->p_labels = array();
        $this->p_values = array();
        $this->p_statistics = array();        

        $sql = "select
                  hour(date_sub(IntervalDate,
                                INTERVAL if((IntervalDate >= '2006-04-02 06:00:00' and IntervalDate <= '2006-10-29 06:00:00') or
                                            (IntervalDate >= '2007-03-11 06:00:00' and IntervalDate <= '2007-11-04 06:00:00') or
                                            (IntervalDate >= '2008-03-09 06:00:00' and IntervalDate <= '2008-11-02 06:00:00') or
                                            (IntervalDate >= '2009-03-08 06:00:00' and IntervalDate <= '2009-11-01 06:00:00') or
                                            (IntervalDate >= '2010-03-07 06:00:00' and IntervalDate <= '2010-11-07 06:00:00'), 4, 5) HOUR)) intHour,
                  minute(date_sub(IntervalDate,
                                  INTERVAL if((IntervalDate >= '2006-04-02 06:00:00' and IntervalDate <= '2006-10-29 06:00:00') or
                                              (IntervalDate >= '2007-03-11 06:00:00' and IntervalDate <= '2007-11-04 06:00:00') or
                                              (IntervalDate >= '2008-03-09 06:00:00' and IntervalDate <= '2008-11-02 06:00:00') or
                                              (IntervalDate >= '2009-03-08 06:00:00' and IntervalDate <= '2009-11-01 06:00:00') or
                                              (IntervalDate >= '2010-03-07 06:00:00' and IntervalDate <= '2010-11-07 06:00:00'), 4, 5) HOUR)) intMinute,
                
                  avg(i.IntervalValue) * 60/ReadInterval IntervalValue
                
                from
                  t_intervalsettypes ist,
                  t_intervalsets iss,
                  t_intervals i,
                  t_points p
                where
                  ist.IntervalSetTypeName = 'IntervalSet' and
                  iss.IntervalSetTypeID = ist.IntervalSetTypeID and
                  iss.PointObjectID = " . $pointID . " and
                  iss.ChannelID = " . $channelID . " and
                  iss.IntervalSetBaseDate between '" . $this->fixDate($fromDate) . "' and '" . $this->fixDate($toDate) . "' and
                  i.IntervalSetID = iss.IntervalSetID and
                  p.ObjectID = iss.PointObjectID
                group by
                  intHour,
                  intMinute
                order by
                  IntervalDate";

        //$this->preDebugger($sql);
        $result = mysql_query($sql, $this->sqlConnection());

        $this->p_size = 1;
        $this->p_labels[0] = "00";
        $this->p_values[0] = null;

       
        while ($row = mysql_fetch_array($result)) {            
            $hour = (($row['intHour'] == 0) && ($row["intMinute"] == 0))?24:$row['intHour'];
            $this->p_labels[$this->p_size] = str_pad($hour, 2, "0", STR_PAD_LEFT) . ($row['intMinute']?":" . str_pad($row['intMinute'], 2, "0", STR_PAD_LEFT):"");
            $this->p_values[$this->p_size++] = $row['IntervalValue'];
        }       

        $this->p_lunits = "Time";
        $this->p_vunits = "kWh";

        $sql = "select
                  IntervalSetBaseDate,
                  hour(date_sub(IntervalDate,
                                INTERVAL if((IntervalDate >= '2006-04-02 06:00:00' and IntervalDate <= '2006-10-29 06:00:00') or
                                            (IntervalDate >= '2007-03-11 06:00:00' and IntervalDate <= '2007-11-04 06:00:00') or
                                            (IntervalDate >= '2008-03-09 06:00:00' and IntervalDate <= '2008-11-02 06:00:00') or
                                            (IntervalDate >= '2009-03-08 06:00:00' and IntervalDate <= '2009-11-01 06:00:00') or
                                            (IntervalDate >= '2010-03-07 06:00:00' and IntervalDate <= '2010-11-07 06:00:00'), \"4:05\", \"5:05\") HOUR_MINUTE))+1 HourEnding,
                  sum(IntervalValue) IntervalValue
              From
                  t_intervalsettypes ist,
                  t_intervalsets iss,
                  t_intervals i,
                  t_points p
              Where
                  ist.IntervalSetTypeName = 'IntervalSet' and
                  iss.IntervalSetTypeID = ist.IntervalSetTypeID and
                  iss.PointObjectID = " . $pointID . " and
                  iss.ChannelID = " . $channelID . " and
                  iss.IntervalSetBaseDate Between '" . $this->fixDate($fromDate) . "' and '" . $this->fixDate($toDate) . "' and
                  i.IntervalSetID = iss.IntervalSetID and
                  p.ObjectID = iss.pointObjectId
              Group By
                 IntervalSetBaseDate,
                 HourEnding
              Order By
                  IntervalDate";

        
        $result = mysql_query($sql, $this->sqlConnection());

        $this->p_statistics["MaximumUsage"] = -1E100;
        $this->p_statistics["TotalUsage"] = 0;
        $this->p_statistics["MinimumUsage"] = 1E100;
        $hourCount = 0;
        while ($row = mysql_fetch_array($result)) {
           //$this->preDebugger($row);

            $intervalValue = $row["IntervalValue"];
            if ($intervalValue < $this->p_statistics["MinimumUsage"]) {
                $this->p_statistics["MinimumUsage"] = $intervalValue;
                $this->p_statistics["MinimumUsageDate"] = date('m-d-Y', strtotime($row['IntervalSetBaseDate'])) . ", Hour Ending " . $row["HourEnding"];
            }

            if ($intervalValue > $this->p_statistics["MaximumUsage"]) {
                $this->p_statistics["MaximumUsage"] = $intervalValue;
                $this->p_statistics["MaximumUsageDate"] = date('m-d-Y', strtotime($row['IntervalSetBaseDate'])) . ", Hour Ending " . $row["HourEnding"];
            }

            $this->p_statistics["TotalUsage"] += $intervalValue;
            $hourCount++;
        }

        if ($hourCount) {
            $this->p_statistics["AverageUsage"] = round($this->p_statistics["TotalUsage"] / $hourCount, 3) . " kWH";
            $this->p_statistics["MaximumUsage"] .= " kWH";
            $this->p_statistics["TotalUsage"] .= " kWH";
            $this->p_statistics["MinimumUsage"] .= " kWH";
        } else {
            $this->p_statistics["AverageUsage"] = "0.0 kWH";
            $this->p_statistics["MaximumUsage"] = "0.0 kWH";
            $this->p_statistics["TotalUsage"] = "0.0 kWH";
            $this->p_statistics["MinimumUsage"] = "0.0 kWH";
        }
        
        return ($this->p_size > 0);
    }

    function GetWeeklyUsageProfile($pointID, $channelID, $fromDate, $toDate) {
        
        //echo "In GetMonthlyUsageProfile({$pointID}, {$channelID}, {$fromDate}, {$toDate})...<br>\n";
        $this->p_labels = array();
        $this->p_values = array();
        $this->p_statistics = array();

        $sql = "select
                  if(dayofweek(IntervalSetBaseDate)=1,6,dayofweek(IntervalSetBaseDate)-2) Dow,
                  hour(date_sub(IntervalDate,
                                INTERVAL if((IntervalDate >= '2006-04-02 06:00:00' and IntervalDate <= '2006-10-29 06:00:00') or
                                            (IntervalDate >= '2007-03-11 06:00:00' and IntervalDate <= '2007-11-04 06:00:00') or
                                            (IntervalDate >= '2008-03-09 06:00:00' and IntervalDate <= '2008-11-02 06:00:00') or
                                            (IntervalDate >= '2009-03-08 06:00:00' and IntervalDate <= '2009-11-01 06:00:00') or
                                            (IntervalDate >= '2010-03-07 06:00:00' and IntervalDate <= '2010-11-07 06:00:00'), \"4:05\", \"5:05\") HOUR_MINUTE))+1 \"Hour\",
                  round(avg(i.IntervalValue)*60/p.ReadInterval, 3) IntervalValue
                from
                  t_intervalsettypes ist,
                  t_intervalsets iss,
                  t_intervals i,
                  t_points p
                where
                  ist.IntervalSetTypeName = 'IntervalSet' and
                  iss.IntervalSetTypeID = ist.IntervalSetTypeID and
                  iss.PointObjectID = " . $pointID . " and
                  iss.ChannelID = " . $channelID . " and
                  iss.IntervalSetBaseDate between '" . $this->fixDate($fromDate) . "' and '" . $this->fixDate($toDate) . "' and
                  i.IntervalSetID = iss.IntervalSetID and
                  p.ObjectID = iss.PointObjectID
                group by
                  Dow,
                  Hour
                order by
                  Dow,
                  Hour";

        //echo "sql=", $sql, "<br>\n";
        $result = mysql_query($sql, $this->sqlConnection());

        $this->p_size = 0;
        $expectedHour = 1;
        $expectedDow = date("w", strtotime($fromDate));
        //echo 'From Date: ', date("m-d-Y", strtotime($this->toMySqlDate($fromDate))), ', Raw Expected DOW: ', $expectedDow, "<br>\n";
        $expectedDow = ($expectedDow == 0?6:$expectedDow-1);
        while ($row = mysql_fetch_array($result)) {
            //echo 'Expecting DOW ', $expectedDow, ' Hour: ', $expectedHour, "<br>\n";
            //echo 'Have Actual DOW ', $row["Dow"], ' Actual Hour: ', $row["Hour"], "<br>\n";
            if ($row["Dow"] == $expectedDow) {
                if ($row["Hour"] == $expectedHour) { 
                    //echo 'Placing Actual DOW ', $row["Dow"], ' Actual Hour: ', $row["Hour"], "<br>\n";
                    $this->p_labels[$this->p_size] = $this->p_dayOfWeek[$row["Dow"]] . ($row["Hour"]>1?" " . $row["Hour"] . ":00:00":"");
                    $this->p_values[$this->p_size++] = $row['IntervalValue'];

                    $expectedHour++;
                    if ($expectedHour == 25) {
                        $expectedHour = 1;
                        $expectedDow++;
                        if ($expectedDow == 7) $expectedDow = 0;
                    }
                } else {
                    // We need to label the missing hour(s)...
                    while ($expectedHour < $row["Hour"]) {
                        //echo 'Filling DOW ', $row["Dow"], ' Expected Hour: ', $expectedHour, ', Actual Hour: ', $row["Hour"], "<br>\n";
                        $this->p_labels[$this->p_size++] = $this->p_dayOfWeek[$row["Dow"]] . ($expectedHour>1?" " . $expectedHour . ":00:00":"");
                        $expectedHour++;
                    }
                    
                    //echo 'Placing Actual DOW ', $row["Dow"], ' Actual Hour: ', $row["Hour"], "<br>\n";
                    $this->p_labels[$this->p_size] = $this->p_dayOfWeek[$row["Dow"]] . ($row["Hour"]>1?" " . $row["Hour"] . ":00:00":"");
                    $this->p_values[$this->p_size++] = $row['IntervalValue'];

                    $expectedHour++;
                    if ($expectedHour == 25) {
                        $expectedHour = 1;
                        $expectedDow++;
                        if ($expectedDow == 7) $expectedDow = 0;
                    }
                }
            } else {
                // We need to label the missing day(s)...
                $hour = 1;
                while ($expectedDow != $row["Dow"]) {
                    //echo 'Filling Expected DOW: ', $expectedDow, ', Actual DOW: ', $row["Dow"], ', Hour: ', $hour, "<br>\n";
                    $this->p_labels[$this->p_size++] = $this->p_dayOfWeek[$expectedDow] . ($hour>1?" " . $hour . ":00:00":"");
                    $hour++;
                    if ($hour == 25) {
                        $hour = 1;
                        $expectedDow++;
                        if ($expectedDow == 7) $expectedDow = 0;
                    }
                }

                while ($expectedHour < $row["Hour"]) {
                    //echo 'Filling DOW ', $row["Dow"], ' Expected Hour: ', $expectedHour, ', Actual Hour: ', $row["Hour"], "<br>\n";
                    $this->p_labels[$this->p_size++] = $this->p_dayOfWeek[$row["Dow"]] . ($expectedHour>1?" " . $expectedHour . ":00:00":"");
                    $expectedHour++;
                }

                //echo 'Placing Actual DOW ', $row["Dow"], ' Actual Hour: ', $row["Hour"], "<br>\n";
                $this->p_labels[$this->p_size] = $this->p_dayOfWeek[$row["Dow"]] . ($row["Hour"]>1?" " . $row["Hour"] . ":00:00":"");
                $this->p_values[$this->p_size++] = $row['IntervalValue'];

                $expectedHour++;
                if ($expectedHour == 25) {
                    $expectedHour = 1;
                    $expectedDow++;
                    if ($expectedDow == 7) $expectedDow = 0;
                }
            }
        }

        $this->p_lunits = "Day";
        $this->p_vunits = "kWh";

        $sql = "select
                  IntervalSetBaseDate,
                  dayofyear(date_sub(IntervalDate,
                                     INTERVAL if((IntervalDate >= '2006-04-02 06:00:00' and IntervalDate <= '2006-10-29 06:00:00') or
                                                 (IntervalDate >= '2007-03-11 06:00:00' and IntervalDate <= '2007-11-04 06:00:00') or
                                                 (IntervalDate >= '2008-03-09 06:00:00' and IntervalDate <= '2008-11-02 06:00:00') or
                                                 (IntervalDate >= '2009-03-08 06:00:00' and IntervalDate <= '2009-11-01 06:00:00') or
                                                 (IntervalDate >= '2010-03-07 06:00:00' and IntervalDate <= '2010-11-07 06:00:00'), \"4:05\", \"5:05\") HOUR_MINUTE)) \"Day\",
                  sum(IntervalValue) IntervalValue
              From
                  t_intervalsettypes ist,
                  t_intervalsets iss,
                  t_intervals i,
                  t_points p
              Where
                  ist.IntervalSetTypeName = 'IntervalSet' and
                  iss.IntervalSetTypeID = ist.IntervalSetTypeID and
                  iss.PointObjectID = " . $pointID . " and
                  iss.ChannelID = " . $channelID . " and
                  iss.IntervalSetBaseDate Between '" . $this->fixDate($fromDate) . "' and '" . $this->fixDate($toDate) . "' and
                  i.IntervalSetID = iss.IntervalSetID and
                  p.ObjectID = iss.pointObjectId
              Group By
                 IntervalSetBaseDate,
                 \"Day\"
              Order By
                  IntervalDate";

        //echo "sql=", $sql, "<br>\n";
        $result = mysql_query($sql, $this->sqlConnection());

        $this->p_statistics["MaximumUsage"] = -1E100;
        $this->p_statistics["TotalUsage"] = 0;
        $this->p_statistics["MinimumUsage"] = 1E100;
        $dayCount = 0;
        while ($row = mysql_fetch_array($result)) {
            $intervalValue = $row["IntervalValue"];
            if ($intervalValue < $this->p_statistics["MinimumUsage"]) {
                $this->p_statistics["MinimumUsage"] = $intervalValue;
                $this->p_statistics["MinimumUsageDate"] = date('m-d-Y', strtotime($row['IntervalSetBaseDate']));
            }

            if ($intervalValue > $this->p_statistics["MaximumUsage"]) {
                $this->p_statistics["MaximumUsage"] = $intervalValue;
                $this->p_statistics["MaximumUsageDate"] = date('m-d-Y', strtotime($row['IntervalSetBaseDate']));
            }

            $this->p_statistics["TotalUsage"] += $intervalValue;
            $dayCount++;
        }

        if ($dayCount) {
            $this->p_statistics["AverageUsage"] = round($this->p_statistics["TotalUsage"] / $dayCount, 3) . " kWH";
            $this->p_statistics["MaximumUsage"] .= " kWH";
            $this->p_statistics["TotalUsage"] .= " kWH";
            $this->p_statistics["MinimumUsage"] .= " kWH";
        } else {
            $this->p_statistics["AverageUsage"] = "0.0 kWH";
            $this->p_statistics["MaximumUsage"] = "0.0 kWH";
            $this->p_statistics["TotalUsage"] = "0.0 kWH";
            $this->p_statistics["MinimumUsage"] = "0.0 kWH";
        }

        return ($this->p_size > 0);
    }

    function GetMonthlyUsageProfile($pointID, $channelID, $fromDate, $toDate) {
        
        //echo "In GetMonthlyUsageProfile({$pointID}, {$channelID}, {$fromDate}, {$toDate})...<br>\n";
        $this->p_labels = array();
        $this->p_values = array();
        $this->p_statistics = array();       

        $sql = "select
                  dayofmonth(IntervalSetBaseDate) Dom,
                  round(avg(i.IntervalValue)*1440/p.ReadInterval, 3) IntervalValue
                from
                  t_intervalsettypes ist,
                  t_intervalsets iss,
                  t_intervals i,
                  t_points p
                where
                  ist.IntervalSetTypeName = 'IntervalSet' and
                  iss.IntervalSetTypeID = ist.IntervalSetTypeID and
                  iss.PointObjectID = " . $pointID . " and
                  iss.ChannelID = " . $channelID . " and
                  iss.IntervalSetBaseDate between '" . $this->fixDate($fromDate) . "' and '" . $this->fixDate($toDate) . "' and
                  i.IntervalSetID = iss.IntervalSetID and
                  p.ObjectID = iss.PointObjectID
                group by
                  Dom
                order by
                  Dom";

        //echo "sql=", $sql, "<br>\n";
        $result = mysql_query($sql, $this->sqlConnection());

        $this->p_size = 0;
        while ($row = mysql_fetch_array($result)) {
            $this->p_labels[$this->p_size] = $row["Dom"];
            $this->p_values[$this->p_size++] = $row['IntervalValue'];
        }

        $this->p_lunits = "Day";
        $this->p_vunits = "kWh";

        $sql = "select
                  year(IntervalSetBaseDate) intYear,
                  month(date_sub(IntervalDate,
                                 INTERVAL if((IntervalDate >= '2010-03-14 06:00:00' and IntervalDate <= '2010-11-07 06:00:00') or
                                             (IntervalDate >= '2011-03-13 06:00:00' and IntervalDate <= '2011-11-06 06:00:00') or
                                             (IntervalDate >= '2012-03-11 06:00:00' and IntervalDate <= '2012-11-04 06:00:00') or
                                             (IntervalDate >= '2013-03-10 06:00:00' and IntervalDate <= '2013-11-03 06:00:00') or
                                             (IntervalDate >= '2014-03-09 06:00:00' and IntervalDate <= '2014-11-02 06:00:00'), \"4:05\", \"5:05\") HOUR_MINUTE))-1 intMonth,
                  sum(IntervalValue) IntervalValue
              From
                  t_intervalsettypes ist,
                  t_intervalsets iss,
                  t_intervals i,
                  t_points p
              Where
                  ist.IntervalSetTypeName = 'IntervalSet' and
                  iss.IntervalSetTypeID = ist.IntervalSetTypeID and
                  iss.PointObjectID = " . $pointID . " and
                  iss.ChannelID = " . $channelID . " and
                  iss.IntervalSetBaseDate Between '" . $this->fixDate($fromDate) . "' and '" . $this->fixDate($toDate) . "' and
                  i.IntervalSetID = iss.IntervalSetID and
                  p.ObjectID = iss.pointObjectId
              Group By
                 intYear,
                 intMonth
              Order By
                  IntervalDate";

        $result = mysql_query($sql, $this->sqlConnection());

        $this->p_statistics["MaximumUsage"] = -1E100;
        $this->p_statistics["TotalUsage"] = 0;
        $this->p_statistics["MinimumUsage"] = 1E100;
        $monthCount = 0;
        while ($row = mysql_fetch_array($result)) {
            $intervalValue = $row["IntervalValue"];
            if ($intervalValue < $this->p_statistics["MinimumUsage"]) {
                $this->p_statistics["MinimumUsage"] = $intervalValue;
                $this->p_statistics["MinimumUsageDate"] = $this->p_monthOfYear[$row["intMonth"]] . " " . $row["intYear"];
            }

            if ($intervalValue > $this->p_statistics["MaximumUsage"]) {
                $this->p_statistics["MaximumUsage"] = $intervalValue;
                $this->p_statistics["MaximumUsageDate"] = $this->p_monthOfYear[$row["intMonth"]] . " " . $row["intYear"];
            }

            $this->p_statistics["TotalUsage"] += $intervalValue;
            $monthCount++;
        }
        if ($monthCount) {
            $this->p_statistics["AverageUsage"] = round($this->p_statistics["TotalUsage"] / $monthCount, 3) . " kWH";
            $this->p_statistics["MaximumUsage"] .= " kWH";
            $this->p_statistics["TotalUsage"] .= " kWH";
            $this->p_statistics["MinimumUsage"] .= " kWH";
        } else {
            $this->p_statistics["AverageUsage"] = "0.0 kWH";
            $this->p_statistics["MaximumUsage"] = "0.0 kWH";
            $this->p_statistics["TotalUsage"] = "0.0 kWH";
            $this->p_statistics["MinimumUsage"] = "0.0 kWH";
        }

        return ($this->p_size > 0);
    }

    function fixDate($date)
    {
        $dateArray = explode('-',$date);         
        
        return $dateArray[2] . '-' . $dateArray[0] . '-' . $dateArray[1];        
    }

    function preDebugger($data, $color = 'blue')
    {
        print '<pre style="color: '.$color.';">';
        print_r($data);
        print '</pre>';
    }
}

?>
