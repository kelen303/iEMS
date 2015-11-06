<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
/**
 * TimeZone
 *
 * @package IEMS 
 * @name Time Zone
 * @author Kevin L. Keegan, Conservation Resource Solutions, Inc.
 * @copyright 2008
 * @version 2.0
 * @access public
 */
class TimeZone extends CAO
{
    const gbPrimaryKey = 0;
    const gbName = 1;

    // Properties...

    private $allocationCount;
    private $qTimeZoneById;
    private $qTimeZoneByName;

    private $p_getBy;

    private $p_id;
    private $p_name;
    private $p_description;
    private $p_isDstActive;
    private $p_stdAbbreviation;
    private $p_stdDescription;
    private $p_stdOffset;
    private $p_stdMonth;
    private $p_stdWeek;
    private $p_stdDay;
    private $p_stdHour;
    private $p_dstAbbreviation;
    private $p_dstDescription;
    private $p_dstOffset;
    private $p_dstMonth;
    private $p_dstWeek;
    private $p_dstDay;
    private $p_dstHour;
    private $p_createdBy;
    private $p_createdDate;
    private $p_updatedBy;
    private $p_updatedDate;

  /**
   * TimeZone::id()
   *
   * @param mixed $id
   * @return
   */
    function id($id = null)
    {
    	return $this->p_id;
    	/* mcb
        if (isset($id)) {
            $this->p_id = $id;
        } else {
            return $this->p_id;
        }
        */
    }

  /**
   * TimeZone::name()
   *
   * @param mixed $name
   * @return
   */
    function name($name = null)
    {
        if (isset($name)) {
            $this->p_name = $name;
        } else {
            return $this->p_name;
        }
    }

  /**
   * TimeZone::description()
   *
   * @param mixed $description
   * @return
   */
    function description($description = null)
    {
        if (isset($description)) {
            $this->p_description = $description;
        } else {
            return $this->p_description;
        }
    }

  /**
   * TimeZone::isDstActive()
   *
   * @param mixed $isDstActive
   * @return
   */
    function isDstActive($isDstActive = null)
    {
        if (isset($isDstActive)) {
            $this->p_isDstActive = $isDstActive;
        } else {
            return $this->p_isDstActive;
        }
    }

  /**
   * TimeZone::stdAbbreviation()
   *
   * @param mixed $stdAbbreviation
   * @return
   */
    function stdAbbreviation($stdAbbreviation = null)
    {
        if (isset($stdAbbreviation)) {
            $this->p_stdAbbreviation = $stdAbbreviation;
        } else {
            return $this->p_stdAbbreviation;
        }
    }

  /**
   * TimeZone::stdDescription()
   *
   * @param mixed $stdDescription
   * @return
   */
    function stdDescription($stdDescription = null)
    {
        if (isset($stdDescription)) {
            $this->p_stdDescription = $stdDescription;
        } else {
            return $this->p_stdDescription;
        }
    }

  /**
   * TimeZone::stdOffset()
   *
   * @param mixed $stdOffset
   * @return
   */
    function stdOffset($stdOffset = null)
    {
        if (isset($stdOffset)) {
            $this->p_stdOffset = $stdOffset;
        } else {
            return $this->p_stdOffset;
        }
    }

  /**
   * TimeZone::stdMonth()
   *
   * @param mixed $stdMonth
   * @return
   */
    function stdMonth($stdMonth = null)
    {
        if (isset($stdMonth)) {
            $this->p_stdMonth = $stdMonth;
        } else {
            return $this->p_stdMonth;
        }
    }

  /**
   * TimeZone::stdWeek()
   *
   * @param mixed $stdWeek
   * @return
   */
    function stdWeek($stdWeek = null)
    {
        if (isset($stdWeek)) {
            $this->p_stdWeek = $stdWeek;
        } else {
            return $this->p_stdWeek;
        }
    }

  /**
   * TimeZone::stdDay()
   *
   * @param mixed $stdDay
   * @return
   */
    function stdDay($stdDay = null)
    {
        if (isset($stdDay)) {
            $this->p_stdDay = $stdDay;
        } else {
            return $this->p_stdDay;
        }
    }

  /**
   * TimeZone::stdHour()
   *
   * @param mixed $stdHour
   * @return
   */
    function stdHour($stdHour = null)
    {
        if (isset($stdHour)) {
            $this->p_stdHour = $stdHour;
        } else {
            return $this->p_stdHour;
        }
    }

  /**
   * TimeZone::dstAbbreviation()
   *
   * @param mixed $dstAbbreviation
   * @return
   */
    function dstAbbreviation($dstAbbreviation = null)
    {
        if (isset($dstAbbreviation)) {
            $this->p_dstAbbreviation = $dstAbbreviation;
        } else {
            return $this->p_dstAbbreviation;
        }
    }

  /**
   * TimeZone::dstDescription()
   *
   * @param mixed $dstDescription
   * @return
   */
    function dstDescription($dstDescription = null)
    {
        if (isset($dstDescription)) {
            $this->p_dstDescription = $dstDescription;
        } else {
            return $this->p_dstDescription;
        }
    }

  /**
   * TimeZone::dstOffset()
   *
   * @param mixed $dstOffset
   * @return
   */
    function dstOffset($dstOffset = null)
    {
        if (isset($dstOffset)) {
            $this->p_dstOffset = $dstOffset;
        } else {
            return $this->p_dstOffset;
        }
    }

  /**
   * TimeZone::dstMonth()
   *
   * @param mixed $dstMonth
   * @return
   */
    function dstMonth($dstMonth = null)
    {
        if (isset($dstMonth)) {
            $this->p_dstMonth = $dstMonth;
        } else {
            return $this->p_dstMonth;
        }
    }

  /**
   * TimeZone::dstWeek()
   *
   * @param mixed $dstWeek
   * @return
   */
    function dstWeek($dstWeek = null)
    {
        if (isset($dstWeek)) {
            $this->p_dstWeek = $dstWeek;
        } else {
            return $this->p_dstWeek;
        }
    }

  /**
   * TimeZone::dstDay()
   *
   * @param mixed $dstDay
   * @return
   */
    function dstDay($dstDay = null)
    {
        if (isset($dstDay)) {
            $this->p_dstDay = $dstDay;
        } else {
            return $this->p_dstDay;
        }
    }

  /**
   * TimeZone::dstHour()
   *
   * @param mixed $dstHour
   * @return
   */
    function dstHour($dstHour = null)
    {
        if (isset($dstHour)) {
            $this->p_dstHour = $dstHour;
        } else {
            return $this->p_dstHour;
        }
    }

  /**
   * TimeZone::createdBy()
   *
   * @param mixed $createdBy
   * @return
   */
    function createdBy($createdBy = null)
    {
        if (isset($createdBy)) {
            $this->p_createdBy = $createdBy;
        } else {
            return $this->p_createdBy;
        }
    }

  /**
   * TimeZone::createdDate()
   *
   * @param mixed $createdDate
   * @return
   */
    function createdDate($createdDate = null)
    {
        if (isset($createdDate)) {
            $this->p_createdDate = $createdDate;
        } else {
            return $this->p_createdDate;
        }
    }

  /**
   * TimeZone::updatedBy()
   *
   * @param mixed $updatedBy
   * @return
   */
    function updatedBy($updatedBy = null)
    {
        if (isset($updatedBy)) {
            $this->p_updatedBy = $updatedBy;
        } else {
            return $this->p_updatedBy;
        }
    }

  /**
   * TimeZone::updatedDate()
   *
   * @param mixed $updatedDate
   * @return
   */
    function updatedDate($updatedDate = null)
    {
        if (isset($updatedDate)) {
            $this->p_updatedDate = $updatedDate;
        } else {
            return $this->p_updatedDate;
        }
    }

    // Constructors...
  /**
   * TimeZone::__construct()
   *
   * @param mixed $timeZone
   * @return
   */
    function __construct($timeZone = null)
    {
        parent::__construct();

        //echo "In TimeZone __construct({$timeZone})...<br>\n";
        $this->InitQueries();

        if (!isset($timeZone)) {
            //echo "Parameter is not set...<br>\n";
            $this->Clear();
        } elseif (is_string($timeZone)) {
            //echo "Parameter is string...<br>\n";
            if ($this->isPersistent()) {
                $this->Load($timeZone);
            } else {
                $this->Clear(); 
                $this->p_name = $timeZone;
                if (strlen($timeZone)) $this->p_getBy = self::gbName;
            }
        } elseif (is_int($timeZone)) {
            //echo "Parameter is integer...<br>\n";
            if ($this->isPersistent()) {
                $this->Load($timeZone);
            } else {
                $this->Clear();
                $this->p_id = $timeZone;
                if ($timeZone) $this->p_getBy = self::gbPrimaryKey;
            }
        } elseif (is_object($timeZone)) {
            $this->Copy($timeZone);
        }
    }

    // Destructor...
  /**
   * TimeZone::__destruct()
   *
   * @return
   */
    function __destruct()
    {
        if ($this->saveOnDestroy()) $this->Put(true);

        parent::__destruct();
    }

    // Operators....
  /**
   * TimeZone::IsEqual()
   *
   * @param mixed $timeZone
   * @return
   */
    function IsEqual($timeZone)
    {
        return ($this->p_id == $timeZone->p_id &&
                $this->p_name == $timeZone->p_name &&
                $this->p_description == $timeZone->p_description &&
                $this->p_isDstActive == $timeZone->p_isDstActive &&
                $this->p_stdAbbreviation == $timeZone->p_stdAbbreviation &&
                $this->p_stdDescription == $timeZone->p_stdDescription &&
                $this->p_stdOffset == $timeZone->p_stdOffset &&
                $this->p_stdMonth == $timeZone->p_stdMonth &&
                $this->p_stdWeek == $timeZone->p_stdWeek &&
                $this->p_stdDay == $timeZone->p_stdDay &&
                $this->p_stdHour == $timeZone->p_stdHour &&
                $this->p_dstAbbreviation == $timeZone->p_dstAbbreviation &&
                $this->p_dstDescription == $timeZone->p_dstDescription &&
                $this->p_dstOffset == $timeZone->p_dstOffset &&
                $this->p_dstMonth == $timeZone->p_dstMonth &&
                $this->p_dstWeek == $timeZone->p_dstWeek &&
                $this->p_dstDay == $timeZone->p_dstDay &&
                $this->p_dstHour == $timeZone->p_dstHour);
    }

    // Methods...
  /**
   * TimeZone::ToLocalTime()
   *
   * @param mixed $utcTime
   * @return
   */
    function ToLocalTime($utcTime)
    {
        $localTime = new CrsDate($utcTime->DateAdd("i", $this->p_stdOffset));
			
        if ($this->IsDateDst($localTime->DateAdd("i", $this->p_dstOffset)))
        {
			$localTime->DateInc("i", $this->p_dstOffset);
		}
		
    
        return $localTime;
    }

  /**
   * TimeZone::ToUtcTime()
   *
   * @param mixed $localTime
   * @param bool $usesDst
   * @return
   */
    function ToUtcTime($localTime, $usesDst = true)
    {
        $offset = $this->p_stdOffset + (($this->IsDateDst($localTime) && $usesDst)?$this->p_dstOffset:0);
                
        return $localTime->DateAdd("i",  -$offset);
    }

  /**
   * TimeZone::IsDateDst()
   *
   * @param mixed $dateTime
   * @return
   */
    function IsDateDst($dateTime)
    {
        return (($this->StdToDstDate($dateTime->year())->asDate() <= $dateTime->asDate()) &&
                ($this->DstToStdDate($dateTime->year())->asDate() > $dateTime->asDate()));
    }

  /**
   * TimeZone::IsDstToStdDate()
   *
   * @param mixed $dateTime
   * @return
   */
    function IsDstToStdDate($dateTime)
    {
        return ($dateTime->asDate() == $this->DstToStdDate($dateTime->year())->asDate());
    }

  /**
   * TimeZone::IsStdToDstDate()
   *
   * @param mixed $dateTime
   * @return
   */
    function IsStdToDstDate($dateTime)
    {
        return ($dateTime->asDate() == $this->StdToDstDate($dateTime->year())->asDate());
    }

  /**
   * TimeZone::IsDstToStdTransition()
   *
   * @param mixed $dateTime
   * @return
   */
    function IsDstToStdTransition($dateTime)
    {
        return ($dateTime->datePart() == $this->DstToStdDate($dateTime->year())->datePart());
    }

  /**
   * TimeZone::IsStdToDstTransition()
   *
   * @param mixed $dateTime
   * @return
   */
    function IsStdToDstTransition($dateTime)
    {
        return ($dateTime->datePart() == $this->StdToDstDate($dateTime->year())->datePart());
    }
    
  /**
   * TimeZone::Clear()
   *
   * @return
   */
    function Clear()
    {
        $this->p_id = 0;
        $this->p_name = "";
        $this->p_description = "";
        $this->p_isDstActive = 0;
        $this->p_stdAbbreviation = "";
        $this->p_stdDescription = "";
        $this->p_stdOffset = 0;
        $this->p_stdMonth = 0;
        $this->p_stdWeek = 0;
        $this->p_stdDay = 0;
        $this->p_stdHour = 0;
        $this->p_dstAbbreviation = "";
        $this->p_dstDescription = "";
        $this->p_dstOffset = 0;
        $this->p_dstMonth = 0;
        $this->p_dstWeek = 0;
        $this->p_dstDay = 0;
        $this->p_dstHour = 0;
        $this->p_createdBy = 0;
        $this->p_createdDate = 0.0;
        $this->p_updatedBy = 0;
        $this->p_updatedDate = 0.0;
    
        $this->saveOnDestroy = false;
    }

  /**
   * TimeZone::Get()
   *
   * @param bool $isFullGet
   * @return
   */
    function Get($isFullGet = false)
    {
        //echo "In Get...<br>\n";
        switch ($this->p_getBy) {
        case self::gbPrimaryKey:
            //echo "Getting by primary key {$this->p_id}...<br>\n";
            $this->Load($this->p_id);
            break;
        case self::gbName:
            //echo "Getting by name {$this->p_name}...<br>\n";
            $this->Load($this->p_name);
            break;
        }
    }

  /**
   * TimeZone::GetUtcOffset()
   *
   * @param mixed $dateTime
   * @return
   */
    function GetUtcOffset($dateTime)
    {
        return ($this->IsDateDst($dateTime)?$this->p_stdOffset + $this->p_dstOffset:$this->p_stdOffset);
    }

  /**
   * TimeZone::Put()
   *
   * @param bool $isFullPut
   * @return
   */
    function Put($isFullPut=false)
    {
        if ($p_createdDate->asDate()) {
            // Update...
        } else {
            // Insert...
        }
    
        parent::Put();
    }

  /**
   * TimeZone::Copy()
   *
   * @param mixed $timeZone
   * @return
   */
    protected function Copy($timeZone)
    {
        $this->p_id = $timeZone->p_id;
        $this->p_name = $timeZone->p_name;
        $this->p_description = $timeZone->p_description;
        $this->p_isDstActive = $timeZone->p_isDstActive;
        $this->p_stdAbbreviation = $timeZone->p_stdAbbreviation;
        $this->p_stdDescription = $timeZone->p_stdDescription;
        $this->p_stdOffset = $timeZone->p_stdOffset;
        $this->p_stdMonth = $timeZone->p_stdMonth;
        $this->p_stdWeek = $timeZone->p_stdWeek;
        $this->p_stdDay = $timeZone->p_stdDay;

        $this->p_stdHour = $timeZone->p_stdHour;
        $this->p_dstAbbreviation = $timeZone->p_dstAbbreviation;
        $this->p_dstDescription = $timeZone->p_dstDescription;
        $this->p_dstOffset = $timeZone->p_dstOffset;
        $this->p_dstMonth = $timeZone->p_dstMonth;
        $this->p_dstWeek = $timeZone->p_dstWeek;
        $this->p_dstDay = $timeZone->p_dstDay;
        $this->p_dstHour = $timeZone->p_dstHour;
        $this->p_createdBy = $timeZone->p_createdBy;
        $this->p_createdDate = $timeZone->p_createdDate;
        $this->p_updatedBy = $timeZone->p_updatedBy;
        $this->p_updatedDate = $timeZone->p_updatedDate;
    
        $this->saveOnDestroy = false;
    }

  /**
   * TimeZone::InitQueries()
   *
   * @return
   */
    private function InitQueries()
    {
    }

//mcb    private function Load($id)
  /**
   * TimeZone::Load()
   *
   * @param mixed $id
   * @return
   */
    function Load($id)
    {
        $sql = "";

        if (is_integer($id)) {
            $sql = "select " .
                       "TimeZoneID, " .
                       "TimeZoneName, " .
                       "TimeZoneDescription, " .
                       "IsDstActive, " .
                       "StdAbbreviation, " .
                       "StdDescription, " .
                       "StdOffset, " .
                       "StdMonth, " .
                       "StdWeek, " .
                       "StdDay, " .
                       "StdHour, " .
                       "DstAbbreviation, " .
                       "DstDescription, " .
                       "DstOffset, " .
                       "DstMonth, " .
                       "DstWeek, " .
                       "DstDay, " .
                       "DstHour, " .
                       "CreatedBy, " .
                       "CreatedDate, " .
                       "UpdatedBy, " .
                       "UpdatedDate " .
                   "from " .
                       "t_timezones " .
                   "where " .
                       "TimeZoneID = {$id}";
        } else {
            $sql = "select " .
                       "TimeZoneID, " .
                       "TimeZoneName, " .
                       "TimeZoneDescription, " .
                       "IsDstActive, " .
                       "StdAbbreviation, " .
                       "StdDescription, " .
                       "StdOffset, " .
                       "StdMonth, " .
                       "StdWeek, " .
                       "StdDay, " .
                       "StdHour, " .
                       "DstAbbreviation, " .
                       "DstDescription, " .
                       "DstOffset, " .
                       "DstMonth, " .
                       "DstWeek, " .
                       "DstDay, " .
                       "DstHour, " .
                       "CreatedBy, " .
                       "CreatedDate, " .
                       "UpdatedBy, " .
                       "UpdatedDate " .
                   "from " .
                       "t_timezones " .
                   "where " .
                       "TimeZoneName = '{$id}'";
        }

        //echo "sql='{$sql}...<br>\n";
        $result = mysql_query($sql, $this->sqlConnection());

        if ($row = mysql_fetch_array($result)) {
            $this->p_id = $row["TimeZoneID"];
            $this->p_name = $row["TimeZoneName"];
            $this->p_description = $row["TimeZoneDescription"];
            $this->p_isDstActive = $row["IsDstActive"];
            $this->p_stdAbbreviation = $row["StdAbbreviation"];
            $this->p_stdDescription = $row["StdDescription"];
            $this->p_stdOffset = $row["StdOffset"];

            $this->p_stdMonth = $row["StdMonth"];
            $this->p_stdWeek = $row["StdWeek"];
            $this->p_stdDay = $row["StdDay"];
            $this->p_stdHour = $row["StdHour"];

            $this->p_dstAbbreviation = $row["DstAbbreviation"];
            $this->p_dstDescription = $row["DstDescription"];
            $this->p_dstOffset = $row["DstOffset"];
            $this->p_dstMonth = $row["DstMonth"];
            $this->p_dstWeek = $row["DstWeek"];
            $this->p_dstDay = $row["DstDay"];
            $this->p_dstHour = $row["DstHour"];
            $this->p_createdBy = $row["CreatedBy"];
            $this->p_createdDate = new CrsDate($row["CreatedDate"]);
            $this->p_updatedBy = $row["UpdatedBy"];
            $this->p_updatedDate = new CrsDate($row["UpdatedDate"]);
        } else {
            $this->Clear();
            
            if (is_integer($id)) {
                // throw
            } else {
                $this->p_name = $id;
            }
        }

    }

  /**
   * TimeZone::Bind()
   *
   * @param mixed $query
   * @return
   */
    protected function Bind($query)
    {
    }

  /**
   * TimeZone::StdToDstDate()
   *
   * @param mixed $year
   * @return
   */
    function StdToDstDate($year)
    {
        // First, find the first day of the month.
        $stdToDstDate = new CrsDate($this->p_dstMonth .
                                    "/01/" .
                                    $year .
                                    " " .
                                    $this->p_dstHour .
                                    ":00:00");

        // echo "StdToDstDate, Raw = '", $stdToDstDate->Format("m/d/Y H:i:s"), "<br>\n";
        // Now determine the day of the week this is.
        $dayOfWeek = date("w", $stdToDstDate->asDate());
    
        // Now get the positive offset to the DST day of the week.
        $dayOfWeek = $this->p_dstDay - $dayOfWeek;
        if ($dayOfWeek < 0) $dayOfWeek += 7;
    
        // Now move the date to match the DST day of the week, then to match the week of the month.
        $dayOffset = $dayOfWeek + 7 * ($this->p_dstWeek - 1);
        $stdToDstDate->DateInc("d", $dayOffset);
        // echo "StdToDstDate, Inc by {$dayOffset} days to DOW = '", $stdToDstDate->Format("m/d/Y H:i:s"), "<br>\n";
        if (date("m", $stdToDstDate->asDate()) != $this->p_dstMonth) $stdToDstDate->DateInc("d", -7);

        return $stdToDstDate;
    }

  /**
   * TimeZone::DstToStdDate()
   *
   * @param mixed $year
   * @return
   */
    function DstToStdDate($year)
    {
        // First, find the first day of the month.
        $dstToStdDate = new CrsDate($this->p_stdMonth .
                                    "/01/" .
                                    $year .
                                    " " .
                                    $this->p_stdHour .
                                    ":00:00");
    
        // Now determine the day of the week this is.
        $dayOfWeek = date("w", $dstToStdDate->asDate());
    
        // Now get the positive offset to the DST day of the week.
        $dayOfWeek = $this->p_stdDay - $dayOfWeek;
        if ($dayOfWeek < 0) $dayOfWeek += 7;
    
        // Now move the date to match the DST day of the week, then to match the week of the month.
        $dstToStdDate->DateInc("d", $dayOfWeek + 7 * ($this->p_stdWeek - 1));
        if (date("m", $dstToStdDate->asDate()) != $this->p_stdMonth) $dstToStdDate->DateInc("d", -7);
        
        return $dstToStdDate;
    }
}
?>
