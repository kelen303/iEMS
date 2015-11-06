<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }

//
//===========================================================================
//
/**
 * TimeSpan
 *
 * @package IEMS 
 * @name Time Span
 * @author Kevin L. Keegan, Conservation Resource Solutions, Inc.
 * @copyright 2008
 * @version 2.0
 * @access public
 * 
 * @abstract This is the primary suite of classes for accessing the MDR database and gathering the various bits of information pertaining to meter points and users.
 */
class TimeSpan
{
  /**
   * TimeSpan::days()
   *
   * @return
   */
    function days()
    {
        return $this->p_negate * $this->p_days;
    }

  /**
   * TimeSpan::hours()
   *
   * @return
   */
    function hours()
    {
        return $this->p_negate * $this->p_hours;
    }

  /**
   * TimeSpan::minutes()
   *
   * @return
   */
    function minutes()
    {
        return $this->p_negate * $this->p_minutes;
    }

  /**
   * TimeSpan::seconds()
   *
   * @return
   */
    function seconds()
    {
        return $this->p_negate * $this->p_seconds;
    }

  /**
   * TimeSpan::fractions()
   *
   * @return
   */
    function fractions()
    {
        return $this->p_negate * $this->p_fractions;
    }

  /**
   * TimeSpan::totalDays()
   *
   * @return
   */
    function totalDays()
    {
        return $this->p_negate * $this->p_totalDays;
    }

  /**
   * TimeSpan::totalHours()
   *
   * @return
   */
    function totalHours()
    {
        return $this->p_negate * $this->p_totalHours;
    }

  /**
   * TimeSpan::totalMinutes()
   *
   * @return
   */
    function totalMinutes()
    {
        return $this->p_negate * $this->p_totalMinutes;
    }

  /**
   * TimeSpan::totalSeconds()
   *
   * @return
   */
    function totalSeconds()
    {
        return $this->p_negate * $this->p_totalSeconds;
    }

  /**
   * TimeSpan::totalFractions()
   *
   * @return
   */
    function totalFractions()
    {
        return $this->p_negate * $this->p_totalFractions;
    }

    // Constructors...
  /**
   * TimeSpan::__construct()
   *
   * @param string $timeSpan
   * @return
   */
    function __construct($timeSpan = "0.00:00:00.000")
    {
        if (is_object($timeSpan)) {
            $this->p_days = $timeSpan->p_days;
            $this->p_hours = $timeSpan->p_hours;
            $this->p_minutes = $timeSpan->p_minutes;
            $this->p_seconds = $timeSpan->p_seconds;
            $this->p_fractions = $timeSpan->p_fractions;
            $this->p_totalDays = $timeSpan->p_totalDays;
            $this->p_totalHours = $timeSpan->p_totalHours;
            $this->p_totalMinutes = $timeSpan->p_totalMinutes;
            $this->p_totalSeconds = $timeSpan->p_totalSeconds;
            $this->p_totalFractions = $timeSpan->p_totalFractions;
            $this->p_negate = $timeSpan->p_negate;
        } else {
            $this->Parse($timeSpan);
        }
    }

  /**
   * TimeSpan::Add()
   *
   * @param mixed $timeSpan
   * @return
   */
    function Add($timeSpan)
    {
        if (!is_object($timeSpan)) $timeSpan = new TimeSpan($timeSpan);

        $newTimeSpan = new TimeSpan();
        $carry = 0;

        $newTimeSpan->p_fractions = $this->p_fractions + $timeSpan->p_fractions;
        if ($newTimeSpan->p_fractions > 999) {
            $newTimeSpan->p_fractions -= 1000;
            $carry = 1;
        }
    
        $newTimeSpan->p_seconds = $this->p_seconds + $timeSpan->p_seconds + $carry;
        if ($newTimeSpan->p_seconds > 59) {
            $newTimeSpan->p_seconds -= 60;
            $carry =1;
        } else {
            $carry = 0;
        }
    
        $newTimeSpan->p_minutes = $this->p_minutes + $timeSpan->p_minutes + $carry;
        if ($newTimeSpan->p_minutes > 59) {
            $newTimeSpan->p_minutes -= 60;
            $carry =1;
        } else {
            $carry = 0;
        }
    
        $newTimeSpan->p_hours = $this->p_hours + $timeSpan->p_hours + $carry;
        if ($newTimeSpan->p_hours > 23) {
            $newTimeSpan->p_hours -= 24;
            $carry =1;
        } else {
            $carry = 0;
        }
    
        $newTimeSpan->p_days = $this->p_days + $timeSpan->p_days + $carry;
    
        $newTimeSpan->ComputeTotals();
    
        return $newTimeSpan;
    }

  /**
   * TimeSpan::Subtract()
   *
   * @param mixed $timeSpan
   * @return
   */
    function Subtract($timeSpan)
    {
        if (!is_object($timeSpan)) $timeSpan = new TimeSpan($timeSpan);

        $newTimeSpan = new TimeSpan();
        $carry = 0;
    
        $newTimeSpan->p_fractions = $this->p_fractions - $timeSpan->p_fractions;
        if ($newTimeSpan->p_fractions < 0) {
            $newTimeSpan->p_fractions += 1000;
            $carry = 1;
        }
    
        $newTimeSpan->p_seconds = $this->p_seconds - $timeSpan->p_seconds - $carry;
        if ($newTimeSpan->p_seconds < 0) {
            $newTimeSpan->p_seconds += 60;
            $carry = 1;
        } else {
            $carry = 0;
        }
    
        $newTimeSpan->p_minutes = $this->p_minutes - $timeSpan->p_minutes - $carry;
        if ($newTimeSpan->p_minutes < 0) {
            $newTimeSpan->p_minutes += 60;
            $carry = 1;
        } else {
            $carry = 0;
        }
    
        $newTimeSpan->p_hours = $this->p_hours - $timeSpan->p_hours - $carry;
        if ($newTimeSpan->p_hours < 0) {
            $newTimeSpan->p_hours += 24;
            $carry = 1;
        } else {
            $carry = 0;
        }
    
        $newTimeSpan->p_days = $this->p_days - $timeSpan->p_days - $carry;
    
        $newTimeSpan->ComputeTotals();
    
        return $newTimeSpan;
    }

  /**
   * TimeSpan::IsEqual()
   *
   * @param mixed $timeSpan
   * @return
   */
    function IsEqual($timeSpan)
    {
        return ($this->p_totalFractions == $timeSpan->p_totalFractions);
    }

  /**
   * TimeSpan::IsGreater()
   *
   * @param mixed $timeSpan
   * @return
   */
    function IsGreater($timeSpan)
    {
        return ($this->p_totalFractions > $timeSpan->p_totalFractions);
    }

  /**
   * TimeSpan::IsGreaterOrEqual()
   *
   * @param mixed $timeSpan
   * @return
   */
    function IsGreaterOrEqual($timeSpan)
    {
        return ($this->p_totalFractions >= $timeSpan->p_totalFractions);
    }

  /**
   * TimeSpan::IsLess()
   *
   * @param mixed $timeSpan
   * @return
   */
    function IsLess($timeSpan)
    {
        return ($this->p_totalFractions < $timeSpan->p_totalFractions);
    }

  /**
   * TimeSpan::IsLessOrEqual()
   *
   * @param mixed $timeSpan
   * @return
   */
    function IsLessOrEqual($timeSpan)
    {
        return ($this->p_totalFractions <= $timeSpan->p_totalFractions);
    }

    // Methods...
  /**
   * TimeSpan::AddDays()
   *
   * @param mixed $days
   * @return
   */
    function AddDays($days)
    {
        $this->p_days += days;
    
        $this->ComputeTotals();
    
        return $this;
    }

  /**
   * TimeSpan::AddHours()
   *
   * @param mixed $hours
   * @return
   */
    function AddHours($hours)
    {
        $t_hours = $this->hours() + $hours;
        if ($t_hours > 23) {
            $this->p_hours = $t_hours % 24;
            $this->p_days += $t_hours/24;
        } else if ($t_hours < 0) {
            $days = floor($t_hours/24.0);
            $this->p_hours = $t_hours - $days * 24;
            $this->p_days += $days;
        } else {
            $this->p_hours = $t_hours;
        }
    
        $this->ComputeTotals();
    
        return $this;
    }

  /**
   * TimeSpan::AddMinutes()
   *
   * @param mixed $minutes
   * @return
   */
    function AddMinutes($minutes)
    {
        $t_minutes = $this->minutes() + $minutes;
        if ($t_minutes > 59) {
            $this->p_minutes = $t_minutes % 60;
            $this->AddHours($t_minutes/60);
        } else if ($t_minutes < 0) {
            $hours = floor($t_minutes/60.0);
            $this->p_minutes = $t_minutes - $hours * 60;
            $this->AddHours($hours);
        } else {
            $this->p_minutes = $t_minutes;
            $this->ComputeTotals();
        }
    
        return $this;
    }

  /**
   * TimeSpan::AddSeconds()
   *
   * @param mixed $seconds
   * @return
   */
    function AddSeconds($seconds)
    {
        $t_seconds = $this->seconds() + $seconds;
        if ($t_seconds > 59) {
            $this->p_seconds = $t_seconds % 60;
            $this->AddMinutes($t_seconds/60);
        } else if ($t_seconds < 0) {
            $minutes = floor($t_seconds/60.0);
            $this->p_seconds = $t_seconds - $minutes * 60;
            $this->AddMinutes($minutes);
        } else {
            $this->p_seconds = $t_seconds;
            $this->ComputeTotals();
        }
    
        return $this;
    }

  /**
   * TimeSpan::AddFractions()
   *
   * @param mixed $fractions
   * @return
   */
    function AddFractions($fractions)
    {
        $t_fractions = $this->fractions() + $fractions;
        if ($t_fractions > 999) {
            $this->p_fractions = $t_fractions % 1000;
            $this->AddSeconds($t_fractions/1000);
        } else if ($t_fractions < 0) {
            $seconds = floor($t_fractions/1000.0);
            $this->p_fractions = $t_fractions - $seconds * 1000;
            $this->AddSeconds($seconds);
        } else {
            $this->p_fractions = $t_fractions;
            $this->ComputeTotals();
        }
    
        return $this;
    }

  /**
   * TimeSpan::FromDateTime()
   *
   * @param mixed $dateTime
   * @param bool $nearestSecond
   * @return
   */
    function FromDateTime($dateTime, $nearestSecond = true)
    {
        $this->Parse($dateTime);

        return $this;
    }

  /**
   * TimeSpan::FromDays()
   *
   * @param mixed $days
   * @return
   */
    function FromDays($days)
    {
        $this->Clear();

        if ($days < 0) {
            $this->p_days = abs($days);
            $this->p_negate = -1;
        } else {
            $this->p_days = $days;
            $this->p_negate = 1;
        }
    
        return $this;
    }

  /**
   * TimeSpan::FromHours()
   *
   * @param mixed $hours
   * @return
   */
    function FromHours($hours)
    {
        $this->Clear();
    
        return $this->AddHours($hours);
    }

  /**
   * TimeSpan::FromMinutes()
   *
   * @param mixed $minutes
   * @return
   */
    function FromMinutes($minutes)
    {
        $this->Clear();
    
        return $this->AddMinutes($seconds);
    }

  /**
   * TimeSpan::FromSeconds()
   *
   * @param mixed $seconds
   * @return
   */
    function FromSeconds($seconds)
    {
        $this->Clear();
    
        return $this->AddSeconds($seconds);
    }

  /**
   * TimeSpan::FromFractions()
   *
   * @param mixed $fractions
   * @return
   */
    function FromFractions($fractions)
    {
        $this->Clear();
    
        return $this->AddFractions($fractions);
    }

  /**
   * TimeSpan::FromString()
   *
   * @param mixed $string
   * @return
   */
    function FromString($string)
    {
        $this->Parse($string);
    }

  /**
   * TimeSpan::FromTime()
   *
   * @param mixed $format
   * @param mixed $time
   * @return
   */
    function FromTime($format, $time)
    {
        $this->Clear();
    
        $lowFormat = strtolower($format);
    
        // Find the location of each colon...
        $c1 = strpos($time, ":");
        $c2 = strpos($time, ":", $c1+1);
        if (!$c2) $c2 = strlen($time);
        //echo "c1='{$c1}', c2='{$c2}'...<br>\n";
    
        // Pick out the values...
        $v1 = (integer)substr($time, 0, $c1);
        $v2 = (integer)substr($time, $c1+1, $c2-$c1);
        $v3 = -1;
        if ($c2 < strlen($time)) $v3 = substr($time, $c2+1, strlen($time)); 
        //echo "v1='{$v1}', v2='{$v2}', v3='{$v3}'...<br>\n";
    
        // Check for hours...  They must be first in the string.
        if (strpos($lowFormat, "hh") === 0) $this->AddHours($v1);
    
        // Check for minutes.  These can be in either position 0 or 3.
        $pos = strpos($lowFormat, "mm");
        //echo "Position of mm='{$pos}'...<br>\n";
        if ($pos > 0) $this->AddMinutes($v2); else $this->AddMinutes($v1);
    
        // Check for seconds.  These can be in either position 3 or 6.
        $pos = strpos($lowFormat, "ss");
        //echo "Position of ss='{$pos}'...<br>\n";
        if ($pos) if ($v3 > 0) $this->AddSeconds($v3); else $this->AddSeconds($v2);
    
        return $this;
    }

  /**
   * TimeSpan::ToString()
   *
   * @param mixed $format
   * @return
   */
    function ToString($format = null)
    {
        $toString = "";
    
        if (!isset($format)) {
            //echo "format is NOT set...<br>\n";
            $toString = sprintf("%d.%02d:%02d:%02d.%03d",
                                $this->p_days,
                                $this->p_hours,
                                $this->p_minutes,
                                $this->p_seconds,
                                $this->p_fractions);
            if ($this->p_negate == -1) $toString = "-" . $toString;
        } else {

        }

        return $toString;
    }

    private $p_days;
    private $p_hours; 
    private $p_minutes;
    private $p_seconds; 
    private $p_fractions; 
    private $p_totalDays; 
    private $p_totalHours; 
    private $p_totalMinutes; 
    private $p_totalSeconds; 
    private $p_totalFractions;

    private $p_negate;

  /**
   * TimeSpan::Clear()
   *
   * @return
   */
    function Clear() 
    {
        $this->p_days = 0; 
        $this->p_hours = 0;
        $this->p_minutes = 0; 
        $this->p_seconds = 0;
        $this->p_fractions = 0; 
        $this->p_totalDays = 0.0;
        $this->p_totalHours = 0.0; 
        $this->p_totalMinutes = 0.0;
        $this->p_totalSeconds = 0.0; 
        $this->p_totalFractions = 0.0;
    
        $this->p_negate = 1;
    }

  /**
   * TimeSpan::__clone()
   *
   * @return
   */
    function __clone()
    {
        $this->p_days = $that->p_days;
        $this->p_hours = $that->p_hours;
        $this->p_minutes = $that->p_minutes;
        $this->p_seconds = $that->p_seconds;
        $this->p_fractions = $that->p_fractions;
        $this->p_totalDays = $that->p_totalDays;
        $this->p_totalHours = $that->p_totalHours;
        $this->p_totalMinutes = $that->p_totalMinutes;
        $this->p_totalSeconds = $that->p_totalSeconds;
        $this->p_totalFractions = $that->p_totalFractions;
        $this->p_negate = $that->p_negate;
    }

  /**
   * TimeSpan::Parse()
   *
   * @param mixed $timeSpan
   * @return
   */
    private function Parse($timeSpan)
    {
        $this->Clear();

        if (is_string($timeSpan)) {
            //echo "TimeSpan is string...<br>\n";
            $parseString = trim($timeSpan);
        
            // valid formats are: 00:00:00, 00:00:00.000, 0.00:00:00, 0.00:00:00.000 and
            // may include a leading minus sign.
            if (substr($parseString, 0, 1) == '-') {
                $this->p_negate = -1;
                $parseString = substr($parseString, 1, strlen($parseString)-1);
            } else {
                //echo "TimeSpan is positive...<br>\n";
                $this->p_negate = 1;
            }
        
            // Look for a decimal point and the first colon...
            $firstDecimal = strpos($parseString, ".");
            $firstColon = strpos($parseString, ":");
        
            //echo "firstDecimal position='{$firstDecimal}'...<br>\n";
            //echo "firstColon position='{$firstColon}'...<br>\n";
            
            if ($firstDecimal) {
                // We have at least one decimal point in the time span.
                if ($firstDecimal < $firstColon) {
                    //echo "firstDecimal comes before firstColon...<br>\n";
                    // We have a leading decimal, indicating days value is present.
                    $this->p_days = (integer)substr($parseString, 0, $firstDecimal);
                    $parseString = substr($parseString, $firstDecimal+1, strlen($parseString));
                    $firstColon = strpos($parseString, ":");
                    //echo "p_days='{$this->p_days}', parseString='{$parseString}', firstColon='{$firstColon}'...<br>\n";
                }
            }
        
            // We are now ready to parse the remainder of the time span.
            $this->p_hours = (integer)substr($parseString, 0, $firstColon);
            $parseString = substr($parseString, $firstColon+1, strlen($parseString));
            $firstColon = strpos($parseString, ":");
            //echo "p_hours='{$this->p_hours}', parseString='{$parseString}', firstColon='{$firstColon}'...<br>\n";
        
            $this->p_minutes = (integer)substr($parseString, 0, $firstColon);
            $parseString = substr($parseString, $firstColon+1, strlen($parseString));
            $firstDecimal = strpos($parseString, ".");
            //echo "p_minutes='{$this->p_minutes}', parseString='{$parseString}', firstDecimal='{$firstDecimal}'...<br>\n";
        
            if ($firstDecimal) {
                // There is a trailing decimal...
                //echo "There is a trailing decimal...<br>\n";
                $this->p_seconds = (integer)substr($parseString, 0, $firstDecimal);
                $this->p_fractions = (integer)substr($parseString, $firstDecimal+1, strlen($parseString));
                //echo "p_seconds='{$this->p_seconds}', p_fractions='{$this->p_fractions}'...<br>\n";
            } else {
                //echo "There is NO trailing decimal...<br>\n";
                $this->p_seconds = (integer)$parseString;
                $this->p_fractions = 0;
                //echo "p_seconds='{$this->p_seconds}', p_fractions='{$this->p_fractions}'...<br>\n";
            }
        } else {
            $parseValue = $timeSpan;
        
            $this->p_days = floor($parseValue/86400);
            $parseValue -= $this->p_days * 86400;
        
            if ($this->p_days < 0) {
                $this->p_days = abs($this->p_days);
                $this->p_negate = -1;
            } else {
                $this->p_negate = 1;
            }
        
            $this->p_hours = floor($parseValue/3600);
            $parseValue -= $this->p_hours * 3600;
        
            $this->p_minutes = floor($parseValue/60);
            $parseValue -= $this->p_minutes * 60;
        
            $this->p_seconds = $parseValue;
            $this->p_fractions = 0;
        }
        
        $this->ComputeTotals();
    }

  /**
   * TimeSpan::ComputeTotals()
   *
   * @return
   */
    private function ComputeTotals()
    {
        $this->p_totalDays = $this->p_days + $this->p_hours/24.0 + $this->p_minutes/1440.0 + $this->p_seconds/86400.0 + $this->p_fractions/86400000.0;
        $this->p_totalHours = $this->p_days * 24.0 + $this->p_hours + $this->p_minutes/60.0 + $this->p_seconds/3600.0 + $this->p_fractions/3600000.0;
        $this->p_totalMinutes = $this->p_days * 1440.0 + $this->p_hours * 60.0 + $this->p_minutes + $this->p_seconds/60.0 + $this->p_fractions/60000.0;
        $this->p_totalSeconds = $this->p_days * 86400.0 + $this->p_hours * 3600.0 + $this->p_minutes * 60.0 + $this->p_seconds + $this->p_fractions/1000.0;
        $this->p_totalFractions = (((($this->p_days * 24) + $this->p_hours) * 60 + $this->p_minutes) * 60 + $this->p_seconds) * 1000 + $this->p_fractions;
    }
}
?>