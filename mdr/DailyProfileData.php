<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
/**
 * DailyProfileData
 *
 * @package IEMS 
 * @name Daily Profile Data
 * @author Kevin L. Keegan, Conservation Resource Solutions, Inc.
 * @copyright 2008
 * @version 2.0
 * @access public
 */
class DailyProfileData extends CAO
{
    private $p_maximumValue;
    private $p_firstDay;
    private $p_lastDay;
    private $p_length;
    private $p_values;
    private $p_labels;
    private $p_startOfMonth;
    private $p_endOfMonth;
    
  /**
   * DailyProfileData::maximumValue()
   *
   * @return
   */
    function maximumValue()
    {
        if ($this->p_maximumValue == -1) {
            for ($inx=$this->p_firstDay - 1; $inx<$this->p_lastDay; $inx++) {
                $this->p_maximumValue = $this->p_maximumValue < $this->p_values[$inx]?$this->p_values[$inx]:$this->p_maximumValue;
            }
        }
        
        return $this->p_maximumValue;
    }
    
  /**
   * DailyProfileData::firstDay()
   *
   * @return
   */
    function firstDay()
    {
        return $this->p_firstDay;
    }
    
  /**
   * DailyProfileData::lastDay()
   *
   * @return
   */
    function lastDay()
    {
        return $this->p_lastDay;
    }
    
  /**
   * DailyProfileData::length()
   *
   * @return
   */
    function length()
    {
        return $this->p_length;
    }
    
  /**
   * DailyProfileData::values()
   *
   * @param mixed $day
   * @return
   */
    function values($day)
    {
        return $this->p_values[$day - 1];
    }
    
  /**
   * DailyProfileData::labels()
   *
   * @param mixed $day
   * @return
   */
    function labels($day)
    {
        return $this->p_labels[$day - 1];
    }
    
  /**
   * DailyProfileData::startOfMonth()
   *
   * @return
   */
    function startOfMonth()
    {
        return $this->p_startOfMonth;
    }
    
  /**
   * DailyProfileData::endOfMonth()
   *
   * @return
   */
    function endOfMonth()
    {
        return $this->p_endOfMonth;
    }
    
  /**
   * DailyProfileData::__construct()
   *
   * @param integer $soMonth
   * @param integer $eoMonth
   * @return
   */
    function __construct($soMonth = 0, $eoMonth = 0)
    {
		parent::__construct();
		
        $this->p_maximumValue = -1.0;
        $this->p_lastDay = 0;
        $this->p_firstDay = 32;
            
        if ($soMonth && $eoMonth) $this->Init($soMonth, $eoMonth);
    }

    function __destruct()
    {
        parent::__destruct();
    }
    
  /**
   * DailyProfileData::Init()
   *
   * @param mixed $soMonth
   * @param mixed $eoMonth
   * @return
   */
    function Init($soMonth, $eoMonth)
    {
        $this->p_startOfMonth = $soMonth;
        $this->p_endOfMonth = $eoMonth;
        $this->p_length = date("d", $this->p_endOfMonth) + 0;

        $month = date("m", $this->p_startOfMonth) + 0;
        $year = date("Y", $this->p_startOfMonth);
            
        for ($inx=1; $inx<$this->p_length+1; $inx++) {
            $this->p_values[$inx] = 0.0;
            $this->p_labels[$inx] = date("m/d/Y", mktime(0, 0, 0, $month, $inx, $year));
        }
    }
    
  /**
   * DailyProfileData::SetValue()
   *
   * @param mixed $day
   * @param mixed $value
   * @return
   */
    function SetValue($day, $value)
    {
        $this->p_values[$day - 1] = $this->p_values[$day - 1] + $value;
        if ($this->p_firstDay > $day) $this->p_firstDay = $day;
        if ($this->p_lastDay < $day) $this->p_lastDay = $day;
        $this->p_maximumValue = -1.0;
    }
}
?>
