<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
/**
 * AnnualDepReporting
 *
 * @package IEMS 
 * @name Annual Dep Reporting
 * @author Kevin L. Keegan, Conservation Resource Solutions, Inc.
 * @copyright 2008
 * @version 2.0
 * @access public
 */
class AnnualDepReporting
{
    private $p_maximumValue;
    private $p_lastHour;
    private $p_values;
    private $p_labels;
    private $p_headings;
    private $p_firstDays;
    private $p_lastDays;
    private $p_lastMonth;
    private $p_firstMonth;
    private $p_year;

  /**
   * AnnualDepReporting::maximumValue()
   *
   * @return
   */
    function maximumValue()
    {
        if ($this->p_maximumValue == -1) {
            for ($inx=0; $inx<12; $inx++) {
                for ($iny=0; $inx<$this->p_lastDays[$inx]; $iny++) {
                    $this->p_maximumValue = $this->p_maximumValue < $this->p_values[$inx][$iny][0]?
                                             $this->p_values[$inx][$iny][0]:$this->p_maximumValue;
                }
            }
        }
    
        return $this->p_maximumValue;
    }

  /**
   * AnnualDepReporting::firstDays()
   *
   * @param mixed $month
   * @return
   */
    function firstDays($month)
    {
        return $this->p_firstDays[$month - 1];
    }

  /**
   * AnnualDepReporting::lastDays()
   *
   * @param mixed $month
   * @return
   */
    function lastDays($month) 
    {
        return $this->p_lastDays[$month - 1];
    }

  /**
   * AnnualDepReporting::lastMonth()
   *
   * @return
   */
    function lastMonth() 
    {
        return $this->p_lastMonth;
    }

  /**
   * AnnualDepReporting::firstMonth()
   *
   * @return
   */
    function firstMonth() 
    {
        return $this->p_firstMonth;
    }

  /**
   * AnnualDepReporting::values()
   *
   * @param mixed $month
   * @param mixed $day
   * @return
   */
    function values($month, $day) 
    {
        return $this->p_values[$month - 1][$day - 1][0];
    }

  /**
   * AnnualDepReporting::isDrawLimitExceeded()
   *
   * @param mixed $month
   * @param mixed $day
   * @return
   */
    function isDrawLimitExceeded($month, $day)
    {
        return $this->p_values[$month - 1][$day - 1][1];
    }

  /**
   * AnnualDepReporting::labels()
   *
   * @param mixed $day
   * @return
   */
    function labels($day)
    {
        return $this->p_labels[$day];
    }

  /**
   * AnnualDepReporting::headings()
   *
   * @param mixed $month
   * @return
   */
    function headings($month)
    {
        return $this->p_headings[$month - 1];
    }

  /**
   * AnnualDepReporting::year()
   *
   * @return
   */
    function year()
    {
        return $p_year;
    }

  /**
   * AnnualDepReporting::__construct()
   *
   * @param integer $year
   * @return
   */
    function __construct($year = 0)
    {
        $this->p_maximumValue = -1.00;
        $this->p_firstMonth = 99;
        $this->p_lastMonth = 0;

        if ($year) Init($year);
    }

  /**
   * AnnualDepReporting::Init()
   *
   * @param mixed $year
   * @return
   */
    function Init($year)
    {
        $this->p_year = $year;
        
        $soYear = "1/1/{$this->p_year}";
        
        $this->p_headings[0] = "January";
        $this->p_headings[1] = "February";
        $this->p_headings[2] = "March";
        $this->p_headings[3] = "April";
        $this->p_headings[4] = "May";
        $this->p_headings[5] = "June";
        $this->p_headings[6] = "July";
        $this->p_headings[7] = "August";
        $this->p_headings[8] = "September";
        $this->p_headings[9] = "October";
        $this->p_headings[10] = "November";
        $this->p_headings[11] = "December";

        for ($inx=0; $inx<12; $inx++) {
            $this->p_firstDays[$inx] = 99;
            $this->p_lastDays[$inx] = 0;
            for ($iny=0; $iny<31; $iny++) {
                $this->p_values[$inx][$iny][0] = 0.0;
                $this->p_values[$inx][$iny][1] = 0.0;
            }
        }
        
        for ($inx=0; $inx<31; $inx++) {
            $this->p_labels[$inx] = $inx + 1;
        }
    }

  /**
   * AnnualDepReporting::Correlate()
   *
   * @param mixed $month
   * @param mixed $day
   * @param mixed $value
   * @param mixed $isDrawLimitExceeded
   * @return
   */
    function Correlate($month, $day, $value, $isDrawLimitExceeded)
    {
        $this->p_firstMonth = $this->p_firstMonth > $month?$month:$this->p_firstMonth;
        $this->p_lastMonth = $this->p_lastMonth < $month?$month:$this->p_lastMonth;
        $this->p_lastDays[$month - 1] = $this->p_lastDays[$month - 1] < $day?$day:$this->p_lastDays[$month - 1]; 
        $this->p_firstDays[$month - 1] = $this->p_firstDays[$month - 1] > $day?$day:$this->p_firstDays[$month - 1];
        $this->p_values[$month - 1][$day - 1][0] = $value;
        $this->p_values[$month - 1][$day - 1][1] = $isDrawLimitExceeded;
        
        $this->p_maximumValue = -1.0;
    }    
}
?>
