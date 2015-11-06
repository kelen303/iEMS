<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
/**
 * IntervalValueSets
 *
 * @package IEMS 
 * @name Interval Value Sets
 * @author Kevin L. Keegan, Conservation Resource Solutions, Inc.
 * @copyright 2008
 * @version 2.0
 * @access public
 */
class IntervalValueSets extends CAO
{
    private $p_meterPoint;
    private $p_timeZone;
    private $p_intervalSetBaseDate;
    private $p_intervalSetBaseDates;
    private $p_intervalSetType;
    private $p_dateSpan;
    private $p_pointObjectId;
    private $p_channelId;
    private $p_readInterval;
    private $p_minimumValue;
    private $p_averageValue;
    private $p_maximumValue;
    private $p_minimumValueDateTime;
    private $p_maximumValueDateTime;
    private $p_minutesPerDay;
    private $p_spanDate;
    private $p_forceHourlyRollup;
    private $p_percentageFill;
    private $p_inList;
	private $p_whereDateString; //mcb
    private $p_isAdjustedBaseline;
    private $p_adjustmentAmount;
    
    private $p_dates;
    private $p_isPresent;
    
    
    private $p_subLabels;
    private $p_hoursPerDay;
    private $p_labelCount;
    
    private $p_length;
    
    private $p_madeDstStdChange;
    private $p_offsetDstStdChange;
	
	public $p_labels;
	public $p_values;
	
  function adjustmentAmount()  
  {
    return $this->p_adjustmentAmount;
  }

  function isAdjustedBaseline()
  {
    return $this->p_isAdjustedBaseline;
  }
  
  /**
   * IntervalValueSets::pointObjectId()
   *
   * @return
   */
    function pointObjectId()
    {
        return $this->p_pointObjectId;
    }
    
  /**
   * IntervalValueSets::channelId()
   *
   * @return
   */
    function channelId()
    {
        return $this->p_channelId;
    }
    
  /**
   * IntervalValueSets::readInterval()
   *
   * @return
   */
    function readInterval()
    {
        return $this->p_readInterval;
    }
    
  /**
   * IntervalValueSets::intervalSetBaseDate()
   *
   * @return
   */
    function intervalSetBaseDate()
    {
        return $this->p_intervalSetBaseDate->Format("m/d/Y");
    }
    
  /**
   * IntervalValueSets::intervalSetType()
   *
   * @return
   */
    function intervalSetType()
    {
        return $this->p_intervalSetType;
    }
    
  /**
   * IntervalValueSets::dateSpan()
   *
   * @return
   */
    function dateSpan()
    {
        return $this->p_dateSpan;
    }

  /**
   * IntervalValueSets::minimumValue()
   *
   * @return
   */
    function minimumValue()
    {
        return round($this->p_minimumValue, 3);
    }
    
  /**
   * IntervalValueSets::averageValue()
   *
   * @return
   */
    function averageValue()
    {
        return round($this->p_averageValue, 3);
    }
    
  /**
   * IntervalValueSets::maximumValue()
   *
   * @return
   */
    function maximumValue()
    {
        return round($this->p_maximumValue, 3);
    }
    
  /**
   * IntervalValueSets::minimumValueDateTime()
   *
   * @return
   */
    function minimumValueDateTime()
    {
        return $this->p_minimumValueDateTime;
    }
    
  /**
   * IntervalValueSets::maximumValueDateTime()
   *
   * @return
   */
    function maximumValueDateTime()
    {
        return $this->p_maximumValueDateTime;
    }
    
  /**
   * IntervalValueSets::labels()
   *
   * @param mixed $index
   * @return
   */
    function labels($index)
    {
        return $this->p_labels[$index];
    }
  /**
   * IntervalValueSets::displayLabels()
   *
   * @return
   */
    function displayLabels()
    {
        return $this->p_labels;
    }
  /**
   * IntervalValueSets::subLabels()
   *
   * @return
   */
    function subLabels()
    {
        return $this->p_subLabels;
    }
    
  /**
   * IntervalValueSets::hoursPerDay()
   *
   * @param mixed $index
   * @return
   */
    function hoursPerDay($index)
    {
       return $this->p_hoursPerDay[index];
    }
    
  /**
   * IntervalValueSets::labelCount()
   *
   * @return
   */
    function labelCount()
    {
        return $this->p_labelCount;
    }
    
  /**
   * IntervalValueSets::length()
   *
   * @return
   */
    function length()
    {
        return $this->p_length;
    }
    
  /**
   * IntervalValueSets::percentageFill()
   *
   * @return
   */
    function percentageFill()
    {
        return $this->p_percentageFill;
    }
  /**
   * IntervalValueSets::values()
   *
   * @return
   */
    function values()
    {
        return $this->p_values;
    }
  /**
   * IntervalValueSets::timeStamp()
   *
   * @return
   */
    function timeStamp()
    {
        return $this->p_timeStamp;
    }
  /**
   * IntervalValueSets::intervalTotal()
   *
   * @return
   */
    function intervalTotal()
    {
        return $this->p_intervalTotal;
    }
  /**
   * IntervalValueSets::dates()
   *
   * @return
   */
    function dates()
    {
        return $this->p_dates;
    }
  /**
   * IntervalValueSets::__construct()
   *
   * @return
   */
    function __construct()
    {
        parent::__construct();
		
		$this->p_intervalSetBaseDate = "";
        $this->p_pointObjectId = -1;
		$pointID = -1;
        $this->p_adjustmentAmount = 0.0;
        $this->p_isAdjustedBaseline = false;
    }

    function __destruct()
    {
        parent::__destruct();
    }
    
  /**
   * IntervalValueSets::Init()
   *
   * @param mixed $oMeterPoint
   * @return
   */
    function Init($oMeterPoint)
    {
        $this->p_meterPoint = clone $oMeterPoint;

        $this->p_pointObjectId = $this->p_meterPoint->id;
        $this->p_readInterval = $this->p_meterPoint->readInterval;
    }    

  /**
   * IntervalValueSets::Load()
   *
   * @param mixed $channelID
   * @param mixed $pointID
   * @param mixed $intervalSetType
   * @param mixed $dateSpan
   * @param mixed $forceHourlyRollup
   * @param mixed $intervalSetBaseDate
   * @param mixed $crsDate
   * @param mixed $frequency
   * @return
   */
    function Load($oPointChannel, $oMeterPoint, $intervalSetType, $dateSpan, $forceHourlyRollup, $intervalSetBaseDate, $crsDate)
    {
        $this->p_pointObjectId = $oPointChannel->objectId();
        $this->p_channelId = $oPointChannel->channelId();                

        if (!isset($forceHourlyRollup)) 
        {
            $this->p_forceHourlyRollup = false;
        }
        else
        {
        $this->p_forceHourlyRollup = $forceHourlyRollup;
        }

        if ($intervalSetBaseDate == "") 
		{
			$this->p_intervalSetBaseDate = date('Y-m-d');
        }
		else
		{
            $this->p_intervalSetBaseDate = $intervalSetBaseDate;
        }

        if (strtolower($intervalSetType) == "baselineset") 
		{
           if ($this->HasAdjustedBaseline()) {
               $intervalSetType = "AdjustedBaselineSet";
               $this->p_isAdjustedBaseline = true;
               //echo "Automatically replacing the baseline set with the adjusted baseline set.<br>\n";
           }
        }
		
        if (strtolower($intervalSetType) == "performanceintervalset") 
            {
           if ($this->HasAdjustedBaseline()) {
               $this->p_isAdjustedBaseline = true;
               //echo "Automatically replacing the baseline set with the adjusted baseline set.<br>\n";
           }
        }

        $this->p_meterPoint = $oMeterPoint;

        $this->p_timeZone = $this->p_meterPoint->timeZone();

        //$this->preDebugger($oMeterPoint->timeZone());               
        
        $this->p_dateSpan = $dateSpan;

        $this->p_intervalSetType = $intervalSetType;

        $this->p_readInterval = $oMeterPoint->readInterval();       


        $this->crsDate = $crsDate;
        
        $this->uxMinute = 60;
        $this->uxHour = $this->uxMinute * 60;
        $this->uxDay = $this->uxHour * 24;
        $this->uxWeek = $this->uxDay * 7;
        
        $startIndex = 0;
        
        $p_length = 0;
        
        $this->expectedLength = 0;

            for ($day=0; $day<$this->p_dateSpan; $day++) 
            {
                $labelDate = $crsDate->DateAdd("d", $day);

                $this->p_subLabels[$day] = $labelDate->Format("m/d/Y");

                if ($this->p_timeZone->IsDstToStdTransition($labelDate)) 
                {
                        $this->p_hoursPerDay[$day] = 25;
                        $this->p_length = $this->p_length + 1500 / $this->p_readInterval;
                        $this->p_labelCount = $this->p_labelCount + 1;
                        
                        $this->p_labels[$startIndex] = $dateSpan == 1?"0":$labelDate->Format("m/d/Y");
                        $this->p_labels[$startIndex + 1] = "1";
                        $this->p_labels[$startIndex + 2] = "2";
                        $label = 2;
                        for ($inx = $startIndex + 3; $inx<$startIndex + 25; $inx++) 
                        {
                                $this->p_labels[$inx] = $label;
                                $label++;
                        }
                        $startIndex += 25;
                }
                elseif ($this->p_timeZone->IsStdToDstTransition($labelDate)) 
                {
                        $this->p_hoursPerDay[$day] = 23;
                        $this->p_length = $this->p_length + 1380 / $this->p_readInterval;
                        $this->p_labelCount = $this->p_labelCount - 1;
                        
                        $this->p_labels[$startIndex] = $dateSpan == 1?"0":$labelDate->Format("m/d/Y");
                        $this->p_labels[$startIndex + 1] = "1";
                        $label = 3;
                        for ($inx=$startIndex + 2; $inx<$startIndex + 23; $inx++) 
                        {
                                $this->p_labels[$inx] = $label;
                                $label++;
                        }

                        $startIndex += 23;
                        
                }
                else
                {
                        $this->p_hoursPerDay[$day] = 24;

                        $p_length = $p_length + 1440 / $this->p_readInterval;
                        
                        $this->p_labels[$startIndex] = $dateSpan == 1?"0":$labelDate->Format("m/d/Y");
                        $label = 1;
                        for ($inx = $startIndex + 1; $inx<$startIndex + 24; $inx++) 
                        {
                                $this->p_labels[$inx] = $label;
                                $label++;
                        }
                        $startIndex += 24;
                }
                
                if($dateSpan > 1 || $forceHourlyRollup == true)
                {
                        $this->expectedLength += $this->p_hoursPerDay[$day];
                }
                else
                {
                        $this->expectedLength = $p_length;
            }
        }
        
       
        $this->p_length = $p_length;

        $this->Refresh();
        
    }
    
  /**
   * IntervalValueSets::Load_list()
   *
   * @param mixed $channelID
   * @param mixed $pointID
   * @param mixed $intervalSetType
   * @param mixed $forceHourlyRollup
   * @param mixed $dates
   * @param mixed $frequency
   * @return
   */
    function Load_list($oPointChannel, $oMeterPoint, $intervalSetType, $forceHourlyRollup, $dates)
    {
        for ($inx=0; $inx<count($dates)-1; $inx++) {
            for ($iny=$inx+1; $iny<count($dates); $iny++) {
                if ($dates[$inx]->asDate() > $dates[$iny]->asDate()) {
                    $tempDate = clone $dates[$inx];                    
                    $dates[$inx] = clone $dates[$iny];
                    $dates[$iny] = clone $tempDate;
                }
            }
        }

        $this->p_pointObjectId = $oPointChannel->objectId();
        $this->p_channelId = $oPointChannel->channelId();   

        if (!isset($forceHourlyRollup)) 
		{
            $this->p_forceHourlyRollup = false;
        }
		else
		{
            $this->p_forceHourlyRollup = $forceHourlyRollup;
        }

        $this->p_intervalSetBaseDates = $dates;

        
        if (strtolower($intervalSetType) == "baselineset") 
		{
           //echo 'Getting the baseline set.<br>\n';
           if ($this->HasAdjustedBaseline()) {
               $intervalSetType = "AdjustedBaselineSet";
               $this->p_isAdjustedBaseline = true;
               //echo 'Automatically exchanging the baseline set for the adjusted baseline set.<br>\n';
           }
        }

        if (strtolower($intervalSetType) == "performanceintervalset") 
            {
           if ($this->HasAdjustedBaseline()) {
               $this->p_isAdjustedBaseline = true;
               //echo "Automatically replacing the baseline set with the adjusted baseline set.<br>\n";
           }
        }
		
		$this->p_meterPoint = $oMeterPoint;

		$this->p_timeZone = $this->p_meterPoint->timeZone();
		
		$this->p_dateSpan = 0;

                $this->p_intervalSetType = $intervalSetType;

		$this->p_readInterval = $this->p_meterPoint->readInterval();
		
		$this->p_dates = $dates;
		
		$this->uxMinute = 60;
		$this->uxHour = $this->uxMinute * 60;
		$this->uxDay = $this->uxHour * 24;
		$this->uxWeek = $this->uxDay * 7;
		
		$startIndex = 0;
        
        $p_length = 0;
        
        $this->expectedLength = 0;

        $this->p_inList = "";

		for ($day=0; $day<count($this->p_dates); $day++) 
		{
			$labelDate = $this->p_dates[$day];

            $this->p_inList = $this->p_inList . "'" . $labelDate->Format("Y-m-d") . "', ";
            $this->p_subLabels[$day] = $labelDate->Format("m/d/Y");

			if ($this->p_timeZone->IsDstToStdTransition($labelDate)) 
			{
				$this->p_hoursPerDay[$day] = 25;
				$this->p_length = $this->p_length + 1500 / $this->p_readInterval;
				$this->p_labelCount = $this->p_labelCount + 1;
				
				$this->p_labels[$day][0] = dateSpan == 1?"0":$labelDate->Format("m/d/Y");
				$this->p_labels[$day][1] = "1";
				$this->p_labels[$day][2] = "2";
				$label = 2;
				for ($inx = 3; $inx<25; $inx++) 
				{
					$this->p_labels[$day][$inx] = $label;
					$label++;
				}
				$startIndex += 25;
			}
			elseif ($this->p_timeZone->IsStdToDstTransition($labelDate)) 
			{
				$this->p_hoursPerDay[$day] = 23;
				$this->p_length = $this->p_length + 1380 / $this->p_readInterval;
				$this->p_labelCount = $this->p_labelCount - 1;
				
				$this->p_labels[$day][0] = $dateSpan == 1?"0":$labelDate->Format("m/d/Y");
				$this->p_labels[$day][1] = "1";
				$label = 3;
				for ($inx=2; $inx<23; $inx++) 
				{
					$this->p_labels[$day][$inx] = $label;
					$label++;
				}

				$startIndex += 23;
				
			}
			else
			{
				$this->p_hoursPerDay[$day] = 24;

				$p_length = $p_length + 1440 / $this->p_readInterval;
				
				$this->p_labels[$day][0] = $this->p_dateSpan == 1?"0":$labelDate->Format("m/d/Y");
				$label = 1;
				for ($inx = 1; $inx<24; $inx++) 
				{
					$this->p_labels[$day][$inx] = $label;
					$label++;
				}
				$startIndex += 24;
			}
			
			if($this->p_dateSpan > 1 || $forceHourlyRollup == true)
			{
				$this->expectedLength += $this->p_hoursPerDay[$day];
			}
			else
			{
				$this->expectedLength = $p_length;
			}
        }
        
        $this->p_inList = substr($this->p_inList, 0, strlen($this->p_inList)-2);
       // echo "inList=", $this->p_inList, "<br>\n";

        $this->p_length = $p_length;

        $this->Refresh();
    }
    
  /**
   * IntervalValueSets::Refresh()
   *
   * @return
   */
    function Refresh() 
    {    	
        //echo "p_dateSpan=", $this->p_dateSpan, ", inList=", $this->p_inList, ", pointId=", $this->p_pointObjectId, ", channelId=", $this->p_channelId, "<br>\n";  

        if ($this->p_dateSpan == 0)
        {
            //echo '<h1>if</h1>';
            $sql = "select " .
                     "IntervalSetBaseDate, " .
                     "IntervalDate, " .
                     "IntervalValue * 60.0/p.ReadInterval IntervalValue, " .
                     "IsFilled " .
                   "from " .
                     "t_intervals i, " .
                     "t_intervalsets iss, " .
                     "t_intervalsettypes ist, " .
                     "t_points p " .
                   "where " .
                     "iss.IntervalSetBaseDate in (" . $this->p_inList . ") and " .
                    "iss.PointObjectID = '".$this->p_pointObjectId."' and " .
                     "iss.ChannelID = '".$this->p_channelId."' and " .
                     "iss.IntervalSetTypeID = ist.IntervalSetTypeID and " .
                     "ist.IntervalSetTypeName = '".$this->p_intervalSetType."' and " .
                     "p.ObjectID = iss.PointObjectID and " .
                     "i.IntervalSetID = iss.IntervalSetID " .
//                     "i.IntervalSetID = iss.IntervalSetID and " .
//                     "i.IsFilled = 0 " .
                   "order by " .
                     "IntervalDate";
            
        }
        elseif ($this->p_forceHourlyRollup == false) 
        {
            if($this->p_dateSpan == 1)
            {
                    $this->p_whereDateString = "iss.IntervalSetBaseDate = '" . $this->p_intervalSetBaseDate . "' and ";

                    if($this->p_intervalSetType == 'PerformanceIntervalSet' || $this->p_intervalSetType == 'PercentagePerformanceIntervalSet' )
                    {
                        $intervalSelectString = 'IntervalValue, ';
                    }
                    else
                    {
                        $intervalSelectString = "IntervalValue * 60.0/p.ReadInterval IntervalValue, ";
                    }
            }
            else
            {
                    $spanDate = $this->crsDate->DateAdd("d", $this->p_dateSpan - 1);
                    $this->p_whereDateString = "iss.IntervalSetBaseDate Between '" . $this->crsDate->Format("Y-m-d") . "' and '" . $spanDate->Format("Y-m-d") . "' and ";
                    $intervalSelectString = "IntervalValue * 60.0/p.ReadInterval IntervalValue, ";
            }
                
            $sql = "select
                    IntervalSetBaseDate,
                    IntervalDate,
                    " . $intervalSelectString . "
                    IsFilled
                from
                    t_intervals i,
                    t_intervalsets iss,
                    t_intervalsettypes ist, 
                    t_points p 
                where 
                    " . $this->p_whereDateString . "
                    iss.PointObjectID = '" . $this->p_pointObjectId . "' and
                    iss.ChannelID = '" . $this->p_channelId . "' and
                    iss.IntervalSetTypeID = ist.IntervalSetTypeID and 
                    ist.IntervalSetTypeName = '" . $this->p_intervalSetType . "' and 
                    p.ObjectID = iss.PointObjectID and 
                    i.IntervalSetID = iss.IntervalSetID 
                order by 
                    IntervalDate";
            //$this->preDebugger($sql);
        }
        else
        {
            $spanDate = $this->crsDate->DateAdd("d", $this->p_dateSpan - 1);

            $sql = "
                SELECT
                    IntervalSetBaseDate,
                    concat(IntervalSetBaseDate, ' ',
                           right(lpad(hour(convert_tz(date_sub(IntervalDate, INTERVAL 5 MINUTE), '+00:00', tz.TimeZoneDescription))+1, 2, '0'), 2),
                           ':00') IntervalDate,
                    hour(convert_tz(date_sub(IntervalDate, INTERVAL 5 MINUTE), '+00:00', tz.TimeZoneDescription))+1 HourEnding,
                    sum(IntervalValue) IntervalValue,
                    if (sum(IsFilled)>0,1,0) IsFilled,
                    if (count(*)<60/p.ReadInterval,1,0) PartialHour
                FROM
                    t_intervalsettypes ist,
                    t_intervalsets iss,
                    t_intervals i,
                    t_points p,
                    t_timezones tz
                WHERE
                    iss.PointObjectID =  " . $this->p_pointObjectId . "  and
                    iss.ChannelID =  " . $this->p_channelId . "  and
                    iss.IntervalSetBaseDate Between '" . $this->crsDate->Format("Y-m-d") . "' and '" . $spanDate->Format("Y-m-d") . "' and
                    iss.IntervalSetTypeID = ist.IntervalSetTypeID and
                    ist.IntervalSetTypeName = '" . $this->p_intervalSetType . "' and
                    p.ObjectID = iss.PointObjectID and
                    tz.TimeZoneID = p.TimeZoneID and
                    i.IntervalSetID = iss.IntervalSetID
                GROUP BY
                    IntervalSetBaseDate,
                    HourEnding
                ORDER BY
                    IntervalSetBaseDate,
                    HourEnding
                ";

            //$this->preDebugger($sql);
        }

        if(defined('DEBUG')) $_SESSION['debugSQL'] = $sql;
        //$this->preDebugger($sql);
        $this->p_madeDstStdChange = false;

        $c_length = 0;

        $totalValue = 0.0;
        $totalCount = 0;
        $fillCount = 0;
        $dateString = '';
        $timeString = '';
        $dstCheck = '';
        $flag = '';
        $hourCheck = '';
        $springFlag = false;
        $timeChange = true;
		
        $cumulativeValue = 0;
        
        $elementCount = 0;
        
        $rollupIndex = 0;
        
        $result = mysql_query($sql, $this->sqlConnection());
        
        $lastHour = '';
        $lastDay = '';
		
        $this->recordsReturned = mysql_numrows($result);

		if (mysql_numrows($result) > 0)
		{                    
		    $day = -1;

			while ($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{

                $intervalValue = $row['IntervalValue'];
                //first, we need to convert the date to unix time; this appears to minimize
                //processing into something managable when we're pulling large stacks of records
                //while we're at it we'll adjust the time minus 5 minutes to allow for the 
                //five minute offset in the records due to transmission delay when gathered
                                        
                date_default_timezone_set(timezone_name_from_abbr('UTC'));

                //$this->preDebugger($row['IntervalDate'],'purple');

                if(isset($row['HourEnding'])) {                    
                    if($row['HourEnding'] == 24) 
                    {
                        //kludge: dates are coming out of the new mulit day query as 24:00 instead of 00:00 and php doesn't know how to deal with that.
                        $dateValue = explode(' ',$row['IntervalDate']);

                        $intervalDate = strtotime($dateValue[0].' 00:00:00') + 86400;
                        //$this->preDebugger(date('Y-m-d H:i:s',$intervalDate));
                    }
                    else
                    {
                        $intervalDate = strtotime($row['IntervalDate']);
                    }
                }
                else
                {
                    $intervalDate = strtotime($row['IntervalDate']);
                }                
                //$this->preDebugger(date('Y-m-d H:i:s',$intervalDate),'purple');

                $timeProperties = localtime($intervalDate,true);
                $thisHour = $timeProperties['tm_hour'];
                
                $baseDate = strtotime($row['IntervalSetBaseDate']);
                $baseProperties = localtime($baseDate, true);
                $thisDay = $baseProperties['tm_mday'];

                if (($this->p_dateSpan == 0) && ($thisDay != $lastDay))
                {
                   // echo "<br>\n", "date changed to ", $baseProperties['tm_year'] + 1900, "-", $baseProperties['tm_mon'] + 1, "-", $baseProperties['tm_mday'] , "<br>\n";
                   // echo "UTC=", $intervalDate, "<br>\n";

                    $day++;
                    while (true) {
                       // echo "this->p_dates[", $day, "]=", $this->p_dates[$day]->Format("Y-m-d"), "<br>\n"; 
                       // echo "baseDate=", Date("Y-m-d", $baseDate), "<br>\n";
                        if (($this->p_dates[$day]->Format("Y-m-d") == Date("Y-m-d", $baseDate)) || 
                            ($day == count($this->p_dates)-1)) break;
                        $day++;
                    }

                    $lastDay = $thisDay;
                }


                if ($this->p_dateSpan == 0)
                {
                    $this->p_values[$day][$intervalDate]['value'] = $row['IntervalValue'];
                    $this->p_values[$day][$intervalDate]['isFilled'] = $row['IsFilled'];
                   // echo "p_values[", $day, "][", $intervalDate, "]=", $this->p_values[$day][$intervalDate], "<br>\n"; 
                }
                else
                {
                    $this->p_values[$intervalDate]['value'] = $row['IntervalValue'];
                    $this->p_values[$intervalDate]['isFilled'] = $row['IsFilled'];	
                    				
                    //$this->preDebugger($intervalDate.' == '.date('Y-m-d H:i:s',$intervalDate),'#980000');
                    //$this->preDebugger($row['IntervalValue'],'green');
                }
			}
			$nextIntervalDate = $intervalDate;
		}

		mysql_free_result($result);
		
                if ($this->p_dateSpan == 0) 
                {
                    $sql = "select " .
                             "LastUnfilledIntervalDate, " .
                             "min(i.IntervalValue)*60.0/p.ReadInterval MinimumIntervalValue, " .
                             "max(i.IntervalValue)*60.0/p.ReadInterval MaximumIntervalValue, " .
                             "avg(i.IntervalValue)*60.0/p.ReadInterval AverageIntervalValue, " .
        					 "min(i.IntervalValue) MinimumUsageValue, " .
                             "max(i.IntervalValue) MaximumUsageValue " .
                           "from " .
                             "t_pointchannelstatistics pcs, " .
                             "t_intervalsets iss, " .
                             "t_intervalsettypes ist, " .
                             "t_intervals i, " .
                             "t_points p " .
                           "where " .
                             "iss.PointObjectID = " . $this->p_pointObjectId . " and " .
                             "iss.ChannelID = " . $this->p_channelId . " and " .
                             "iss.IntervalSetBaseDate in (" . $this->p_inList . ") and " .
                             "iss.IntervalSetTypeID = ist.IntervalSetTypeID and " .
                             "ist.IntervalSetTypeName = '" . $this->p_intervalSetType . "' and " .
                             "pcs.ObjectID = iss.PointObjectID and " .
                             "pcs.ChannelID = iss.ChannelID and " .
                             "i.IntervalSetID = iss.IntervalSetID and " .
                             "p.ObjectID = pcs.ObjectID " .
                           "group by " .
                             "LastUnfilledIntervalDate";
                }
                elseif ($this->p_dateSpan == 1)
                {
                    $sql = "select " .
                             "LastUnfilledIntervalDate, " .
                             "min(i.IntervalValue)*60.0/p.ReadInterval MinimumIntervalValue, " .
                             "max(i.IntervalValue)*60.0/p.ReadInterval MaximumIntervalValue, " .
                             "avg(i.IntervalValue)*60.0/p.ReadInterval AverageIntervalValue, " .
        					 "min(i.IntervalValue) MinimumUsageValue, " .
                             "max(i.IntervalValue) MaximumUsageValue " .
                           "from " .
                             "t_pointchannelstatistics pcs, " .
                             "t_intervalsets iss, " .
                             "t_intervalsettypes ist, " .
                             "t_intervals i, " .
                             "t_points p " .
                           "where " .
                             "iss.PointObjectID = " . $this->p_pointObjectId . " and " .
                             "iss.ChannelID = " . $this->p_channelId . " and " .
                             "iss.IntervalSetBaseDate = '" . $this->p_intervalSetBaseDate . "' and " .
                             "iss.IntervalSetTypeID = ist.IntervalSetTypeID and " .
                             "ist.IntervalSetTypeName = '" . $this->p_intervalSetType . "' and " .
                             "pcs.ObjectID = iss.PointObjectID and " .
                             "pcs.ChannelID = iss.ChannelID and " .
                             "i.IntervalSetID = iss.IntervalSetID and " .
                             "p.ObjectID = pcs.ObjectID " .
                           "group by " .
                             "LastUnfilledIntervalDate";
                }
                else
                {
                    $sql = "select " .
                             "LastUnfilledIntervalDate, " .
                             "min(i.IntervalValue)*60/p.ReadInterval MinimumIntervalValue, " .
                             "max(i.IntervalValue)*60/p.ReadInterval MaximumIntervalValue, " .
                             "avg(i.IntervalValue)*60.0/p.ReadInterval AverageIntervalValue, " .
        					 "min(i.IntervalValue) MinimumUsageValue, " .
                             "max(i.IntervalValue) MaximumUsageValue " .
                           "from " .
                             "t_pointchannelstatistics pcs, " .
                             "t_intervalsets iss, " .
                             "t_intervalsettypes ist, " .
                             "t_intervals i, " .
                             "t_points p " .
                           "where " .
                             "iss.PointObjectID = " . $this->p_pointObjectId . " and " .
                             "iss.ChannelID = " . $this->p_channelId . " and " .
                        	 "iss.IntervalSetBaseDate between '" . $this->p_intervalSetBaseDate . "' and '" . $spanDate->Format("Y-m-d") . "' and " .
                             "iss.IntervalSetTypeID = ist.IntervalSetTypeID and " .
                             "ist.IntervalSetTypeName = '" . $this->p_intervalSetType . "' and " .
                             "pcs.ObjectID = iss.PointObjectID and " .
                             "pcs.ChannelID = iss.ChannelID and " .
                             "i.IntervalSetID = iss.IntervalSetID and " .
                             "p.ObjectID = pcs.ObjectID " .
                           "group by " .
                             "LastUnfilledIntervalDate";
                }

       // echo "sql=", $sql, "<br>\n";
        //$_SESSION['debugSQL'] = $sql;

        $result = mysql_query($sql, $this->sqlConnection());

        if ($row = mysql_fetch_array($result)) {
            $this->p_minimumValue = $row["MinimumIntervalValue"];
            $this->p_maximumValue = $row["MaximumIntervalValue"];
            $this->p_averageValue = $row["AverageIntervalValue"];
            $this->p_lastIntervalDate = $row["LastUnfilledIntervalDate"];
			$this->p_minimumUsageValue = $row['MinimumUsageValue'];
			$this->p_maximumUsageValue = $row['MaximumUsageValue'];

			//$this->p_minimumValueDateTime = $this->oTimeZone->ToLocalTime($minimumDate);
			//$this->p_minimumValueDateTime = $this->oTimeZone->ToLocalTime($minimumDate);

            if ($this->p_dateSpan == 0)
            {
                $sql = "select " .
                          "min(i.IntervalDate) IntervalDate " .
                       "from " .
                          "t_intervals i, " .
                          "t_intervalsets iss, " .
                          "t_intervalsettypes ist " .
                       "where " .
                          "ist.IntervalSetTypeName = '" . $this->p_intervalSetType . "' and " .
                          "iss.IntervalSetTypeID = ist.IntervalSetTypeID and " .
                          "iss.IntervalSetBaseDate in (" . $this->p_inList . ") and " .
                          "iss.PointObjectID = " . $this->p_pointObjectId . " and " .
                          "iss.ChannelID = " . $this->p_channelId . " and " .
                          "i.IntervalSetID = iss.IntervalSetID and " .
                          "i.IntervalValue = " .$this->p_minimumUsageValue ;

                $result = mysql_query($sql, $this->sqlConnection());
                
                if ($row = mysql_fetch_array($result)) {
                    $this->p_minimumValueDateTime = new CrsDate($row["IntervalDate"]);
                    $this->p_minimumValueDateTime = $this->p_timeZone->ToLocalTime($this->p_minimumValueDateTime);
                } else {
                }
                            
                $sql = "select " .
                          "min(i.IntervalDate) IntervalDate " .
                       "from " .
                          "t_intervals i, " .
                          "t_intervalsets iss, " .
                          "t_intervalsettypes ist " .
                       "where " .
                          "ist.IntervalSetTypeName = '" . $this->p_intervalSetType . "' and " .
                          "iss.IntervalSetTypeID = ist.IntervalSetTypeID and " .
                          "iss.IntervalSetBaseDate in (" . $this->p_inList . ") and " .
                          "iss.PointObjectID = " . $this->p_pointObjectId . " and " .
                          "iss.ChannelID = " . $this->p_channelId . " and " .
                          "i.IntervalSetID = iss.IntervalSetID and " .
                          "i.IntervalValue = " . $this->p_maximumUsageValue;

                $result = mysql_query($sql, $this->sqlConnection());
        
                if ($row = mysql_fetch_array($result)) {
                    $this->p_maximumValueDateTime = new CrsDate($row["IntervalDate"]);
                    $this->p_maximumValueDateTime = $this->p_timeZone->ToLocalTime($this->p_maximumValueDateTime);
                } else {
                }
            }
            elseif ($this->p_dateSpan == 1)
            {
                $sql = "select " .
                          "min(i.IntervalDate) IntervalDate " .
                       "from " .
                          "t_intervals i, " .
                          "t_intervalsets iss, " .
                          "t_intervalsettypes ist " .
                       "where " .
                          "ist.IntervalSetTypeName = '" . $this->p_intervalSetType . "' and " .
                          "iss.IntervalSetTypeID = ist.IntervalSetTypeID and " .
                          "iss.IntervalSetBaseDate = '" . $this->p_intervalSetBaseDate . "' and " .
                          "iss.PointObjectID = " . $this->p_pointObjectId . " and " .
                          "iss.ChannelID = " . $this->p_channelId . " and " .
                          "i.IntervalSetID = iss.IntervalSetID and " .
                          "i.IntervalValue = " . $this->p_minimumUsageValue;

                $result = mysql_query($sql, $this->sqlConnection());
        
                if ($row = mysql_fetch_array($result)) {
                    $this->p_minimumValueDateTime = new CrsDate($row["IntervalDate"]);
                    $this->p_minimumValueDateTime = $this->p_timeZone->ToLocalTime($this->p_minimumValueDateTime);
                } else {
                }
                            
                $sql = "select " .
                          "min(i.IntervalDate) IntervalDate " .
                       "from " .
                          "t_intervals i, " .
                          "t_intervalsets iss, " .
                          "t_intervalsettypes ist " .
                       "where " .
                          "ist.IntervalSetTypeName = '" . $this->p_intervalSetType . "' and " .
                          "iss.IntervalSetTypeID = ist.IntervalSetTypeID and " .
                          "iss.IntervalSetBaseDate = '" . $this->p_intervalSetBaseDate . "' and " .
                          "iss.PointObjectID = " . $this->p_pointObjectId . " and " .
                          "iss.ChannelID = " . $this->p_channelId . " and " .
                          "i.IntervalSetID = iss.IntervalSetID and " .
                          "i.IntervalValue = " . $this->p_maximumUsageValue;

                $result = mysql_query($sql, $this->sqlConnection());
        
                if ($row = mysql_fetch_array($result)) {
                    $this->p_maximumValueDateTime = new CrsDate($row["IntervalDate"]);
                    $this->p_maximumValueDateTime = $this->p_timeZone->ToLocalTime($this->p_maximumValueDateTime);
                } else {
                }
            }
            else
            {
                $sql = "select " .
                          "min(i.IntervalDate) IntervalDate " .
                       "from " .
                          "t_intervals i, " .
                          "t_intervalsets iss, " .
                          "t_intervalsettypes ist " .
                       "where " .
                          "ist.IntervalSetTypeName = '" . $this->p_intervalSetType . "' and " .
                          "iss.IntervalSetTypeID = ist.IntervalSetTypeID and " .
                          "iss.IntervalSetBaseDate between '" . $this->p_intervalSetBaseDate . "' and '" . $spanDate->Format("Y-m-d") . "' and " .
                          "iss.PointObjectID = " . $this->p_pointObjectId . " and " .
                          "iss.ChannelID = " . $this->p_channelId . " and " .
                          "i.IntervalSetID = iss.IntervalSetID and " .
                          "i.IntervalValue = " . $this->p_minimumUsageValue;

                $result = mysql_query($sql, $this->sqlConnection());
        
                if ($row = mysql_fetch_array($result)) {
                    $this->p_minimumValueDateTime = new CrsDate($row["IntervalDate"]);
                    $this->p_minimumValueDateTime = $this->p_timeZone->ToLocalTime($this->p_minimumValueDateTime);
                } else {
                }
                            
                $sql = "select " .
                          "min(i.IntervalDate) IntervalDate " .
                       "from " .
                          "t_intervals i, " .
                          "t_intervalsets iss, " .
                          "t_intervalsettypes ist " .
                       "where " .
                          "ist.IntervalSetTypeName = '" . $this->p_intervalSetType . "' and " .
                          "iss.IntervalSetTypeID = ist.IntervalSetTypeID and " .
                          "iss.IntervalSetBaseDate between '" . $this->p_intervalSetBaseDate . "' and '" . $spanDate->Format("Y-m-d") . "' and " .
                          "iss.PointObjectID = " . $this->p_pointObjectId . " and " .
                          "iss.ChannelID = " . $this->p_channelId . " and " .
                          "i.IntervalSetID = iss.IntervalSetID and " .
                          "i.IntervalValue = " . $this->p_maximumUsageValue;

                $result = mysql_query($sql, $this->sqlConnection());
        
                if ($row = mysql_fetch_array($result)) {
                    $this->p_maximumValueDateTime = new CrsDate($row["IntervalDate"]);
                    $this->p_maximumValueDateTime = $this->p_timeZone->ToLocalTime($this->p_maximumValueDateTime);
                } else {
                }
            }

        }
		else
		{
            $this->p_averageValue = 0;
            $this->p_minimumValue = 0;
            $this->p_maximumValue = 0;
            $this->p_minimumValueDateTime = new CrsDate($this->p_intervalSetBaseDate);
            $this->p_maximumValueDateTime = new CrsDate($this->p_intervalSetBaseDate);
        }
        
    }

  /**
   * IntervalValueSets::HasAdjustedBaseline()
   *
   * @return
   */
    private function HasAdjustedBaseline()
    {
        $sql = "select " .
                 "AdjustmentValue " .
               "from " .
                 "t_intervalsets iss, " .
                 "t_intervalsettypes ist, " .
                 "t_baselines b " .
               "where " .
                 "ist.IntervalSetTypeName='AdjustedBaselineSet' and " .
                 "iss.IntervalSetTypeID = ist.IntervalSetTypeID and " .
				 "iss.IntervalSetBaseDate = '" . $this->p_intervalSetBaseDate . "' and " .
                 "iss.PointObjectID = ".$this->p_pointObjectId." and " .
                 "iss.ChannelID = ".$this->p_channelId." and " .
                 "b.IntervalSetID = iss.IntervalSetID";
        
        $result = mysql_query($sql, $this->sqlConnection());
        if ($row = mysql_fetch_array($result)) 
		{
            $hasAdjustedBaseline = true;
            $this->p_adjustmentAmount = $row['AdjustmentValue'] * 12.0;
        }
		else
		{
            $hasAdjustedBaseline = false;
        }

        //echo "Point Channel ({$this->p_pointObjectId}, {$this->p_channelId})", ($hasAdjustedBaseline?" has ":" does not have "), " an adjusted baseline for {$this->p_intervalSetBaseDate}.<br>\n";
        return $hasAdjustedBaseline;
    }

    /**
   * IntervalValueSets::queryAdjustedBaseline()
   *
    *@return 
    *@details Creating this to get a handle on the adjusted 
    *baseline value *without* having to query an entire 
    *interval value set -- this is being caused because 
    *our javascript grids are loaded as two componenets, 
    *the header, and the data, in separate transactions. 
    * 
   */

    
    function queryAdjustedBaseline($intervalSetBaseDate,$pointObjectId,$channelId)
    {
        $sql = "select " .
                 "AdjustmentValue " .
               "from " .
                 "t_intervalsets iss, " .
                 "t_intervalsettypes ist, " .
                 "t_baselines b " .
               "where " .
                 "ist.IntervalSetTypeName='AdjustedBaselineSet' and " .
                 "iss.IntervalSetTypeID = ist.IntervalSetTypeID and " .
				 "iss.IntervalSetBaseDate = '" . $intervalSetBaseDate . "' and " .
                 "iss.PointObjectID = ".$pointObjectId." and " .
                 "iss.ChannelID = ".$channelId." and " .
                 "b.IntervalSetID = iss.IntervalSetID";
        
        $result = mysql_query($sql, $this->sqlConnection());
        if ($row = mysql_fetch_array($result)) 
		{
            //$hasAdjustedBaseline = true;
            $hasAdjustedBaseline = $row['AdjustmentValue'] * 12.0;
        }
		else
		{
            $hasAdjustedBaseline = false;
        }

        //echo "Point Channel ({$this->p_pointObjectId}, {$this->p_channelId})", ($hasAdjustedBaseline?" has ":" does not have "), " an adjusted baseline for {$this->p_intervalSetBaseDate}.<br>\n";
        return $hasAdjustedBaseline;
    }
}
?>

