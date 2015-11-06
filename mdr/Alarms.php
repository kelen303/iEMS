<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
/**
 * Alarms
 *
 * @package IEMS 
 * @name Alarms
 * @author Kevin L. Keegan, Conservation Resource Solutions, Inc.
 * @copyright 2008
 * @version 2.0
 * @access public
 */
class Alarms extends CAO
{
    private $p_pointId;
    private $p_channelId;
    private $p_alarmThresholds;
    private $p_length;
    private $p_toString;
    
  /**
   * Alarms::pointId()
   *
   * @return
   */
    function pointId()
    {
        return $this->p_pointId;
    }
    
  /**
   * Alarms::channelId()
   *
   * @return
   */
    function channelId()
    {
        return $this->p_channelId;
    }
    
  /**
   * Alarms::alarmThreshold()
   *
   * @param mixed $index
   * @return
   */
    function alarmThreshold($index)
    {
        return $this->p_alarmThresholds[$index];
    }
    
  /**
   * Alarms::length()
   *
   * @return
   */
    function length()
    {
        return $this->p_length;
    }
    
  /**
   * Alarms::toString()
   *
   * @return
   */
    function toString()
    {
        $toString = "";
        for($index=0; $index<$this->p_length; $index++) {
            $toString = $toString . $this->p_alarmThresholds[$index] . ", ";
        }
        
        if ($toString > "") {
            $toString = substr($toString, 0, strlen($toString) - 2);
        } else {
            $toString = "(No Alarms Set)";
        }

        return $toString;
    }    
    
  /**
   * Alarms::__construct()
   *
   * @return
   */
    function __construct()
    {
        parent::__construct();

        $this->p_pointId = 0;
        $this->p_channelId = 0;
        $this->p_length = 0;
    }    

    function __destruct()
    {
        parent::__destruct();
    }
    
  /**
   * Alarms::Load()
   *
   * @param mixed $pointId
   * @param mixed $channelId
   * @return
   */
    function Load($pointId, $channelId)
    {
        $this->p_pointId = $pointId;
        $this->p_channelId = $channelId;
        $this->p_length = 0;

        $this->Refresh();
    }    
    
  /**
   * Alarms::Refresh()
   *
   * @return
   */
    function Refresh()
    {
        $sql = "select " .
                "AlarmThreshold " .
              "from " .
                "t_alarms a, " .
                "t_alarmpointchannels apc, " .
                "t_objects o " .
              "where " .
                "apc.PointObjectID = {$this->p_pointId} and " .
                "apc.ChannelID = {$this->p_channelId} and " .
                "a.ObjectID = apc.AlarmObjectID and " .
                "o.ObjectID = a.ObjectID and " .
                "o.IsInactive = 0 " .
              "order by " .
                "AlarmThreshold asc";
                
        $result = mysql_query($sql, $this->sqlConnection());
        while ($row = mysql_fetch_array($result)) {
            $this->p_alarmThresholds[$this->p_length++] = $row["AlarmThreshold"];
        }
    }
}

?>
