<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
/**
 * Privileges
 *
 * @package IEMS 
 * @name Privileges
 * @author Kevin L. Keegan, Conservation Resource Solutions, Inc.
 * @copyright 2008
 * @version 2.0
 * @access public
 */
class Privileges extends CAO
{
    private $p_userId;
    private $p_domainId;
    
    private $p_maxPrivilege;
    
    private $p_privilegeList = array();
    
  /**
   * Privileges::userId()
   *
   * @return
   */
    function userId()
    {
        $this->p_userId;
    }
    
  /**
   * Privileges::DomainID()
   *
   * @return
   */
    function DomainID()
    {
        return $this->p_domainId;
    }

  /**
   * Privileges::count()
   *
   * @return
   */
    function count()
    {
        return $this->p_maxPrivilege;
    }
    
  /**
   * Privileges::__construct()
   *
   * @return
   */
    function __construct()
    {
        parent::__construct();
        
        $this->p_userId = -1;
        $this->p_domainId = -1;
        $this->p_maxPrivilege = 0;
    }        

    function __destruct()
    {
        parent::__destruct();
    }

  /**
   * Privileges::Load()
   *
   * @param mixed $userId
   * @param mixed $domainId
   * @return
   */
    function Load($userId, $domainId)
    {
        if ($this->p_userId < 0) {
            $this->p_userId = $userId;
            $this->p_domainId = $domainId;
            
            $this->Refresh();
        }
    }
    
  /**
   * Privileges::Refresh()
   *
   * @return
   */
    function Refresh()
    {
        $inx = 0;
        $this->p_maxPrivilege = 0;

        $sql = "select
                   lower(concat(pt.PrivilegeTypeName, '.', o.ObjectName)) PrivilegeName
                from 
                   t_objects o,
                   t_privilegetypes pt,
                   t_privileges p,
                   t_actorprivilegexrefs upx,
                   t_actorprivilegexrefs dpx
               where
                   upx.ObjectID = {$this->p_userId} and
                   p.PrivilegeID = upx.PrivilegeID and
                   pt.PrivilegeTypeID = p.PrivilegeTypeID and
                   o.ObjectID = p.ObjectID and
                   dpx.ObjectID = {$this->p_domainId} and
                   dpx.PrivilegeID = upx.PrivilegeID
               order by
                   PrivilegeName";

        $inx = 0;

        $result = mysql_query($sql, $this->sqlConnection());
        //print $sql;
        while ($row = mysql_fetch_array($result)) {
            $this->p_privilegeList[$this->p_maxPrivilege++] = $row["PrivilegeName"];
            //echo "Have privilege '", $this->p_privilegeList[$this->p_maxPrivilege-1], "'...<br>\n";
        }

        $sql = "select distinct " .
                     "gpx.PrivilegeID, " .
                     "lower(concat(pt.PrivilegeTypeName, '.', po.ObjectName)) PrivilegeName " .
               "from " .
                     "t_objectxrefs dgox, " .
                     "t_objecttypes ot, " .
                     "t_objects o, " .
                     "t_objectxrefs ugox, " .
                     "t_actorprivilegexrefs gpx, " .
                     "t_actorprivilegexrefs dpx, " .
                     "t_privileges p, " .
                     "t_privilegetypes pt, " .
                     "t_objects po " .
               "where " .
                     "ot.ObjectTypeName = 'Group' and " .
                     "o.ObjectTypeID = ot.ObjectTypeID and " .
                     "dgox.ChildObjectID = o.ObjectID and " .
                     "dgox.ParentObjectID = {$this->p_domainId} and " .
                     "ugox.ParentObjectID = dgox.ChildObjectID and " .
                     "ugox.ChildObjectID = {$this->p_userId} and " .
                     "gpx.ObjectID = ugox.ParentObjectID and " .
                     "dpx.ObjectID = dgox.ParentObjectID and " .
                     "gpx.PrivilegeID = dpx.PrivilegeID and " .
                     "p.PrivilegeID = gpx.PrivilegeID and " .
                     "pt.PrivilegeTypeID = p.PrivilegeTypeID and " .
                     "po.ObjectID = p.ObjectID " .
               "order by " .
                     "PrivilegeName";

        $result = mysql_query($sql, $this->sqlConnection());
        while ($row = mysql_fetch_array($result)) {
            $this->p_privilegeList[$this->p_maxPrivilege++] = $row["PrivilegeName"];
            //echo "Have privilege '", $this->p_privilegeList[$this->p_maxPrivilege-1], "'...<br>\n";
        }

        sort($this->p_privilegeList, SORT_STRING);
    }
    
  /**
   * Privileges::HasPrivilege()
   *
   * @param mixed $privilege
   * @return
   */
    function HasPrivilege($privilege)
    {
        $hasPrivilege = false;
        $testPrivilege = strtolower($privilege);

        for ($inx=0; $inx<$this->p_maxPrivilege; $inx++) {
            if ($testPrivilege == $this->p_privilegeList[$inx]) {
                $hasPrivilege = true;

                break;
            } elseif ($this->p_privilegeList[$inx] > $testPrivilege) {
                break;
            }
        }

        return $hasPrivilege;
    }

  /**
   * Privileges::Dump()
   *
   * @param mixed
   * @return
   */
    function Dump()
    {
        $dump = '<table><thead><th><td colspan="2">Privileges</td></th><th><td>ID</td><td>Name</td></th></thead><tbody>';
        for ($inx=0; $inx<$this->p_maxPrivilege; $inx++) {
            $dump .= "<tr><td>{$inx}</td><td>{$this->p_privilegeList[$inx]}</td></tr>";
        }

        $dump .= "</tbody></table>";

        return $dump;
    }

  /**
   * Privileges::Insert()
   *
   * @param mixed $privilege
   * @return
   */
    private function Insert($privilege)
    {    
        for ($inx1=0; $inx<$this->p_maxPrivilege; $inx++) {
            if ($this->p_privilegeList[$inx1] > $privilege) {
                for ($inx2=$this->p_maxPrivilege-1; $inx2>$inx; $inx2--) {
                    $this->p_privilegeList[$inx2 + 1] = $this->p_privilegeList[$inx2];
                }
                
                $this->p_privilegeList[$inx1] = $privilege;
            }
        }
    }
    
  /**
   * Privileges::item()
   *
   * @param mixed $index
   * @return
   */
    function item($index)
    {
        $this->p_privilegeList[$index];
    }
    
  /**
   * Privileges::Size()
   *
   * @return
   */
    function Size()
    {
        return $this->p_maxPrivilege;
    }
}
?>
