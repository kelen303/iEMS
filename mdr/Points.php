<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
/**
 * Points
 *
 * @package IEMS 
 * @name Points
 * @author Kevin L. Keegan, Conservation Resource Solutions, Inc.
 * @copyright 2008
 * @version 2.0
 * @access public
 */
class Points extends CAO
{
    private $p_user;
    private $p_size;
    private $p_maximumValue;
    private $p_intervalSetBaseDate;
    private $p_date;
    private $p_dateSpan;
    
    private $aPoint;
    private $p_pointList = array();
    
  /**
   * Points::User()
   *
   * @return
   */
    function User()
    {
        return $this->p_user;
    }
    
  /**
   * Points::Size()
   *
   * @return
   */
    function Size()
    {
        return $this->p_size;
    }
    
  /**
   * Points::length()
   *
   * @return
   */
    function length()
    {
        return $this->p_size;
    }
    
  /**
   * Points::maximumValue()
   *
   * @return
   */
    function maximumValue()
    {
        return $this->p_maximumValue;
    }
    
  /**
   * Points::intervalSetBaseDate()
   *
   * @return
   */
    function intervalSetBaseDate()
    {
        return $this->p_date->Format("m/d/Y");
    }
    
  /**
   * Points::dateSpan()
   *
   * @return
   */
    function dateSpan()
    {
        return $this->p_dateSpan;
    }
    
  /**
   * Points::__construct()
   *
   * @param mixed $oUser
   * @param mixed $baseDate
   * @param mixed $dateSpan
   * @return
   */
    function __construct($oUser, $baseDate, $dateSpan)
    {
        parent::__construct();

        $this->p_user = clone $oUser;
        $this->p_date = new CrsDate($baseDate);
        $this->p_dateSpan = $dateSpan;

        $this->aPoint = new MeterPoint();

        $this->Refresh();
    }

    function __destruct()
    {
        parent::__destruct();
    }
    
  /**
   * Points::Refresh()
   *
   * @return
   */
    function Refresh()
    {
        $p_maximumValue = 0.0;
        $this->p_size = 0;
        
        $sql = "select distinct " .
                   "po.ObjectID " .
               "from " .
                   "t_objectxrefs dgox, " .
                   "t_objecttypes ot, " .
                   "t_objects o, " .
                   "t_objectxrefs ugox, " .
                   "t_actorprivilegexrefs gpx, " .
                   "t_actorprivilegexrefs dpx, " .
                   "t_privileges p, " .
                   "t_privilegetypes pt, " .
                   "t_objects po, " .
                   "t_groups g, " .
                   "t_grouptypes gt, " .
                   "t_points pn " .
               "where " .
                   "ot.ObjectTypeName = 'Group' and " .
                   "o.ObjectTypeID = ot.ObjectTypeID and " .
                   "g.ObjectID = o.ObjectID and " .
                   "gt.GroupTypeID = g.GroupTypeID and " .
                   "gt.GroupTypeName = 'Privilege' and " .
                   "dgox.ChildObjectID = o.ObjectID and " .
                   "dgox.ParentObjectID = " . $this->p_user->Domains(0)->id() . " and " .
                   "ugox.ParentObjectID = dgox.ChildObjectID and " .
                   "ugox.ChildObjectID = " . $this->p_user->id() . " and " .
                   "gpx.ObjectID = ugox.ParentObjectID and " .
                   "dpx.ObjectID = dgox.ParentObjectID and " .
                   "gpx.PrivilegeID = dpx.PrivilegeID and " .
                   "p.PrivilegeID = gpx.PrivilegeID and " .
                   "pt.PrivilegeTypeID = p.PrivilegeTypeID and " .
                   "po.ObjectID = p.ObjectID and " .
                   "pn.ObjectID = po.ObjectID and " .
                   "po.IsInactive = 0 and " .
                   "pn.IsEnabled = 1 " .
               "order by " .
                   "pn.IsAggregate desc, " .
                   "po.ObjectDescription";
        
        $result = mysql_query($sql, $this->sqlConnection());
        while ($row = mysql_fetch_array($result)) {
            $this->aPoint->Load($row["ObjectID"], $this->p_date, $this->p_dateSpan);
            $this->p_pointList[$this->p_size++] = clone $this->aPoint;
        }
    }
    
  /**
   * Points::RefreshChannels()
   *
   * @return
   */
    function RefreshChannels()
    {
        for ($inx=0; $inx<$this->p_size; $inx++) {
            $this->p_pointList[$inx]->RefreshPrices();
            $this->p_pointList[$inx]->pointChannels->Refresh();
        }
    }
    
  /**
   * Points::item()
   *
   * @param mixed $index
   * @return
   */
    function item($index)
    {    
        return $this->p_pointList[$index];
    }
}
?>
