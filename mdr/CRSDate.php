<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
/**
 * CrsDate
 *
 * @package IEMS 
 * @name CRS Date
 * @author Kevin L. Keegan, Conservation Resource Solutions, Inc.
 * @copyright 2008
 * @version 2.0
 * @access public
 */
class CrsDate
{
    private $p_date;

    private $p_year;
    private $p_month;
    private $p_day;
    private $p_hour;
    private $p_minute;
    private $p_second;

  /**
   * CrsDate::__construct()
   *
   * @param mixed $date
   * @return
   */
    function __construct($date)
    {
        if (is_integer($date) || is_long($date)) {
            $this->p_date = $date;
            $this->DecomposeDate();
        } elseif (is_object($date)) {
            $this->Copy($date);
        } else {
            $time = "";
            $pos = strpos($date, " ");
            if ($pos) $time = substr($date, $pos+1, strlen($date) - $pos - 1);

            if (strpos($date, "-")) {
                $this->p_year = substr($date, 0, 4) + 0;
                $this->p_month = substr($date, 5, 2) + 0;
                $this->p_day = substr($date, 8, 2) + 0;
            } else {
                $this->p_year = substr($date, 6, 4) + 0;
                $this->p_month = substr($date, 0, 2) + 0;
                $this->p_day = substr($date, 3, 2) + 0;
            }

            if ($time) {
                $this->p_hour = substr($time, 0, 2) + 0;
                $this->p_minute = substr($time, 3, 2) + 0;
                $this->p_second = substr($time, 6, 2) + 0;
            } else {
                $this->p_hour = 0;
                $this->p_minute = 0;
                $this->p_second = 0;
            }

            $this->p_date = mktime($this->p_hour, $this->p_minute, $this->p_second,
                                   $this->p_month, $this->p_day, $this->p_year);
        }
    }

    //function __clone()
    //{
    //    //$this->Copy($that);
    //    $this->p_date = $that->p_date;
    //
    //    $this->p_year = $that->p_year;
    //    $this->p_month = $that->p_month;
    //    $this->p_day = $that->p_day;
    //    $this->p_hour = $that->p_hour;
    //    $this->p_minute = $that->p_minute;
    //    $this->p_second = $that->p_second;
    //}

  /**
   * CrsDate::year()
   *
   * @return
   */
    function year()
    {
        return $this->p_year;
    }

  /**
   * CrsDate::month()
   *
   * @return
   */
    function month()
    {
        return $this->p_month;
    }

  /**
   * CrsDate::day()
   *
   * @return
   */
    function day()
    {
        return $this->p_day;
    }

  /**
   * CrsDate::hour()
   *
   * @return
   */
    function hour()
    {
        return $this->p_hour;
    }

  /**
   * CrsDate::minute()
   *
   * @return
   */
    function minute()
    {
        return $this->p_minute;
    }

  /**
   * CrsDate::second()
   *
   * @return
   */
    function second()
    {
        return $this->p_second;
    }

  /**
   * CrsDate::asDate()
   *
   * @return
   */
    function asDate()
    {
        return $this->p_date;
    }

  /**
   * CrsDate::datePart()
   *
   * @return
   */
    function datePart()
    {
        return $this->p_date - $this->p_hour * 3600 - $this->p_minute * 60 - $this->p_second;
    }

  /**
   * CrsDate::timePart()
   *
   * @return
   */
    function timePart()
    {
        return $this->p_hour * 3600 + $this->p_minute * 60 + $this->p_second;
    }

  /**
   * CrsDate::DateAdd()
   *
   * @param mixed $datePart
   * @param mixed $value
   * @return
   */
    function DateAdd($datePart, $value)
    {
        $dateAdd = $this->p_date;

        //echo "p_hour='{$this->p_hour}', p_minute='{$this->p_minute}', p_second='{$this->p_second}'...<br>\n";
        //echo "p_month='{$this->p_month}', p_day='{$this->p_day}', p_year='{$this->p_year}'...<br>\n";

        switch (strtolower($datePart)) {
        case "s":
            $dateAdd = mktime($this->p_hour,
                              $this->p_minute,
                              $this->p_second + $value,
                              $this->p_month,
                              $this->p_day,
                              $this->p_year);
            break;
        case "i":
            $dateAdd = mktime($this->p_hour,
                              $this->p_minute + $value,
                              $this->p_second,
                              $this->p_month,
                              $this->p_day,
                              $this->p_year);
            break;
        case "h":
            $dateAdd = mktime($this->p_hour + $value,
                              $this->p_minute,
                              $this->p_second,
                              $this->p_month,
                              $this->p_day,
                              $this->p_year);
            break;
        case "d":
            $dateAdd = mktime($this->p_hour,
                              $this->p_minute,
                              $this->p_second,
                              $this->p_month,
                              $this->p_day + $value,
                              $this->p_year);
            break;
        case "m":
            $dateAdd = mktime($this->p_hour,
                              $this->p_minute,
                              $this->p_second,
                              $this->p_month + $value,
                              $this->p_day,
                              $this->p_year);
            break;
        case "y":
            $dateAdd = mktime($this->p_hour,
                              $this->p_minute,
                              $this->p_second,
                              $this->p_month,
                              $this->p_day,
                              $this->p_year + $value);
            break;
        }

        return new CrsDate($dateAdd);
    }

  /**
   * CrsDate::DateInc()
   *
   * @param mixed $datePart
   * @param mixed $value
   * @return
   */
    function DateInc($datePart, $value)
    {
        switch (strtolower($datePart)) {
        case "s":
            $this->p_date = mktime($this->p_hour,
                                   $this->p_minute,
                                   $this->p_second + $value,
                                   $this->p_month,
                                   $this->p_day,
                                   $this->p_year);
            break;
        case "i":
            $this->p_date = mktime($this->p_hour,
                                   $this->p_minute + $value,
                                   $this->p_second,
                                   $this->p_month,
                                   $this->p_day,
                                   $this->p_year);
            break;
        case "h":
            $this->p_date = mktime($this->p_hour + $value,
                                   $this->p_minute,
                                   $this->p_second,
                                   $this->p_month,
                                   $this->p_day,
                                   $this->p_year);
            break;
        case "d":
            $this->p_date = mktime($this->p_hour,
                                   $this->p_minute,
                                   $this->p_second,
                                   $this->p_month,
                                   $this->p_day + $value,
                                   $this->p_year);
            break;
        case "m":
            $this->p_date = mktime($this->p_hour,
                                   $this->p_minute,
                                   $this->p_second,
                                   $this->p_month + $value,
                                   $this->p_day,
                                   $this->p_year);
            break;
        case "y":
            $this->p_date = mktime($this->p_hour,
                                   $this->p_minute,
                                   $this->p_second,
                                   $this->p_month,
                                   $this->p_day,
                                   $this->p_year + $value);
            break;
        }

        $this->DecomposeDate();

        return $this;
    }

  /**
   * CrsDate::DateDiff()
   *
   * @param mixed $date
   * @return
   */
    function DateDiff($date)
    {
        $newDate = $this->p_date - $date->p_date;

        $timeSpan = new TimeSpan($newDate);
        //echo "this='", $this->Format("m/d/Y H:i:s"), "', date='", $date->Format("m/d/Y H:i:s"), "',...<br>\n";
        //echo "newDate='{$newDate}', DateDiff='{$timeSpan->ToString()}'...<br>\n";

        return $timeSpan;
    }

  /**
   * CrsDate::Format()
   *
   * @param mixed $format
   * @return
   */
    function Format($format)
    {
        return date($format, $this->p_date);
    }

  /**
   * CrsDate::Copy()
   *
   * @param mixed $date
   * @return
   */
    private function Copy($date)
    {
        $this->p_date = $date->p_date;
    
        $this->p_year = $date->p_year;
        $this->p_month = $date->p_month;
        $this->p_day = $date->p_day;
        $this->p_hour = $date->p_hour;
        $this->p_minute = $date->p_minute;
        $this->p_second = $date->p_second;
   }

  /**
   * CrsDate::DecomposeDate()
   *
   * @return
   */
    private function DecomposeDate()
    {
        $this->p_year = date("y", $this->p_date);
        $this->p_month = date("m", $this->p_date) + 0;
        $this->p_day = date("d", $this->p_date) + 0;
        $this->p_hour = date("H", $this->p_date) + 0;
        $this->p_minute = date("i", $this->p_date) + 0;
        $this->p_second = date("s", $this->p_date) + 0;
    }
}
?>
