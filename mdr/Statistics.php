<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
class Statistics extends CAO
{
    private $p_actualIntervals;
    private $p_expectedIntervals;
    private $p_fromDate;
    private $p_percentageFilled;
    private $p_percentageUptime;
    private $p_lastIntervalDate;
    private $p_toDate;

    function actualIntervals() {
        return $this->p_actualIntervals;
    }

    function expectedIntervals() {
        return $this->p_expectedIntervals;
    }

    function percentageFilled() {
        if (is_numeric($this->p_percentageFilled)) {
            return number_format($this->p_percentageFilled, 2, ".", ",");
        } else {
            return $this->p_percentageFilled;
        }
    }

    function percentageUptime() {
        return number_format($this->p_percentageUptime, 2, ".", ",");
    }

    function lastIntervalDate() {
        return $this->p_lastIntervalDate;
    }

    function __construct() {

        parent::__construct();

        $this->p_actualIntervals = 0;
        $this->p_expectedIntervals = 0;
        $this->p_fromDate = "";
        $this->p_percentageFilled = 0;
        $this->p_percentageUptime = 0;
        $this->p_toDate = "";
    }

    function __destruct() {

        parent::__destruct();

    }

    function GetUptimeStatistics($pointID, $channelID, $fromDate, $toDate) {

        $getUptimeStatistics = true;

        $fromParts = explode('-',$fromDate);
        $toParts = explode('-',$toDate);

        $this->p_fromDate = $fromParts[2].'-'.$fromParts[0].'-'.$fromParts[1];
        $this->p_toDate = $toParts[2].'-'.$toParts[0].'-'.$toParts[1];;
        

        $sql = "select
                  iss.PointObjectID,
                  iss.ChannelID,
                  count(*) ActualIntervals,
                  (to_days('" . $this->p_toDate . "') - to_days('" . $this->p_fromDate . "'))*1440/p.ReadInterval +
                  if('" . $this->p_toDate . "' = curdate(), floor(time_to_sec(curtime())/(60 * p.ReadInterval)), 1440/p.ReadInterval) ExpectedIntervals,
                  date_sub(pcs.LastUnfilledIntervalDate,
                           INTERVAL if((pcs.LastUnfilledIntervalDate >= '2006-04-02 06:00:00' and pcs.LastUnfilledIntervalDate <= '2006-10-29 06:00:00') or
                                       (pcs.LastUnfilledIntervalDate >= '2007-03-11 06:00:00' and pcs.LastUnfilledIntervalDate <= '2007-11-04 06:00:00') or
                                       (pcs.LastUnfilledIntervalDate >= '2008-03-09 06:00:00' and pcs.LastUnfilledIntervalDate <= '2008-11-02 06:00:00') or
                                       (pcs.LastUnfilledIntervalDate >= '2009-03-08 06:00:00' and pcs.LastUnfilledIntervalDate <= '2009-11-01 06:00:00') or
                                       (pcs.LastUnfilledIntervalDate >= '2010-03-07 06:00:00' and pcs.LastUnfilledIntervalDate <= '2010-11-07 06:00:00'), 4, 5) HOUR) LastIntervalDate
                from
                  t_intervalsettypes ist,
                  t_intervalsets iss,
                  t_intervals i,
                  t_points p,
                  t_pointchannelstatistics pcs
                where
                  ist.IntervalSetTypeName = 'IntervalSet' and
                  iss.IntervalSetTypeID = ist.IntervalSetTypeID and
                  iss.PointObjectID = " . $pointID . " and
                  iss.ChannelID = " . $channelID . " and
                  iss.IntervalSetBaseDate between '" . $this->p_fromDate . "' and '" . $this->p_toDate . "' and
                  i.IntervalSetID = iss.IntervalSetID and
                  (i.IsFilled = 0 or
                  i.CreatedDate > i.UpdatedDate) and
                  pcs.ObjectID = iss.PointObjectID and
                  pcs.ChannelID = iss.ChannelID and
                  p.ObjectID = pcs.ObjectID
                group by
                  iss.PointObjectID,
                  iss.ChannelID";

        $result = mysql_query($sql, $this->sqlConnection());
        //$this->preDebugger($sql);
        if ($row = mysql_fetch_array($result)) {
            
            $this->p_actualIntervals = $row["ActualIntervals"];
            $this->p_expectedIntervals = round($row['ExpectedIntervals'], 0);
            $this->p_percentageUptime = round($this->p_actualIntervals/$this->p_expectedIntervals * 100.0, 2);
            $this->p_percentageFilled = round(100.0 - $this->p_percentageUptime, 2);
            $this->p_lastIntervalDate = $row["LastIntervalDate"];

        } else {
            
            $localTime = localtime(time(), true);
            $localSecs = ($localTime['tm_hour'] * 60 + $localTime['tm_min']) * 60 + $localTime['tm_sec'];
            $this->p_actualIntervals = 0;
            $this->p_expectedIntervals = (strtotime($toDate) - strtotime($fromDate))/300 + ($toDate == date("m-d-Y")?floor($localSecs/300):288);
            $this->p_percentageUptime = 0;
            $this->p_percentageFilled = "--";
            $this->p_lastIntervalDate = "??";
        }

        return $getUptimeStatistics;

    }

    private function toMySqlDate($date) {
        $dps = explode("-", $date);

        return $dps[2] . "-" . $dps[0] . "-" . $dps[1];
    }
}
?>
