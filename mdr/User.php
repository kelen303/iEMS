<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
/**
 * User
 *
 * @package IEMS 
 * @name User
 * @author Kevin L. Keegan, Conservation Resource Solutions, Inc.
 * @copyright 2008
 * @version 2.0
 * @access public
 */
class User extends CAO
{
    private $p_id;
    private $p_userName;
    private $p_fullName;
    private $p_isUserChangeable;
    private $p_isExpiring;
    private $p_expirationDate;
    private $p_expirationWarnings;
    private $p_timeZoneID;
    private $p_primaryEmailAddress;
    private $p_primaryPhoneNumber;
    private $p_primaryZipCode;

    private $p_lseDomain;
    
    private $p_domains = array();
    
    private $p_privileges;
    private $p_preferences;
    private $p_pointChannels;

    private $p_priceSummary;

    
  /**
   * User::id()
   *
   * @return
   */
    function id()
    {
        return $this->p_id;
    }
    
  /**
   * User::userName()
   *
   * @return
   */
    function userName()
    {
        return $this->p_userName;
    }
    
  /**
   * User::fullName()
   *
   * @return
   */
    function fullName()
    {
        return $this->p_fullName;
    }
    
  /**
   * User::isUserChangeable()
   *
   * @return
   */
    function isUserChangeable()
    {
        return $this->p_isUserChangeable;
    }
    
  /**
   * User::isExpiring()
   *
   * @return
   */
    function isExpiring()
    {
        return $this->p_isExpiring;
    }
    
  /**
   * User::expirationWarnings()
   *
   * @return
   */
    function expirationWarnings()
    {
        return $this->p_expirationWarnings;
    }
    
  /**
   * User::expirationDate()
   *
   * @return
   */
    function expirationDate()
    {
        return $this->p_expirationDate;
    }
    
  /**
   * User::timeZoneID()
   *
   * @return
   */
    function timeZoneID()
    {
        return $this->p_timeZoneID;
    }
    
  /**
   * User::privilegeCount()
   *
   * @return
   */
    function privilegeCount()
    {
        return $this->p_privileges->count();
    }
    
  /**
   * User::primaryEmailAddress()
   *
   * @return
   */
    function primaryEmailAddress()
    {
        return $this->p_primaryEmailAddress;
    }
    
  /**
   * User::primaryPhoneNumber()
   *
   * @return
   */
    function primaryPhoneNumber()
    {
        return $this->p_primaryPhoneNumber;
    }
    
  /**
   * User::primaryZipCode()
   *
   * @return
   */
    function primaryZipCode()
    {
        return $this->p_primaryZipCode;
    }

    function localDomain()
    {
        return $this->p_domains[0];
    }

    function lseDomain()
    {
        return $this->p_lseDomain;
    }

    function pointChannels()
    {
        return $this->p_pointChannels;
    }

    function isLseUser()
    {
        return $this->p_isLseUser;
    }

    function programList()
    {
        return $this->p_participationTypeList;
    }


  /**
   * User::__construct()
   *
   * @return
   */
    function __construct()
    {
        parent::__construct();

        $this->p_id = 0;
        $this->p_userMame= "";
        $this->p_fullName = "";
        $this->p_isUserChangeable = false;
        $this->p_isExpiring = false;
        $this->p_expirationDate = 0;
        $this->p_expirationWarnings = 0;
        $this->p_timeZoneID = 0;
        $this->p_primaryEmailAddress = "";
        $this->p_primaryPhoneNumber = "";
        $this->p_primaryZipCode = "";
    }

    function __destruct()
    {
        parent::__destruct();
    }
    
  /**
   * User::userData()
   *
   * @param mixed $userName
   * @return
   */
  function userData($userName) //replaces Login
    {
        $sql = "select " .
                    "o.ObjectID, " .
                    "o.ObjectDescription, " .
                    "u.IsUserChangeable, " .
                    "u.IsExpiring, " .
                    "u.ExpirationDate, " .
                    "u.ExpirationWarnings, " .
                    "u.TimeZoneID, " .
                    "ec.ContactValue PrimaryEmailAddress, " .
                    "pc.ContactValue PrimaryPhoneNumber, " .
                    "zc.ContactValue PrimaryZipCode " .
                 "from " .
                    "t_objects o, " .
                    "t_users u, " .
                    "t_contacts ec, " .
                    "t_contacttypes ect, " .
                    "t_contacts pc, " .
                    "t_contacttypes pct, " .
                    "t_contacts zc, " .
                    "t_contacttypes zct " .
                 "where " .
                    "o.ObjectName = '{$userName}' and " .
                    "u.ObjectID = o.ObjectID and " .
                    "ec.ObjectID = o.ObjectID and " .
                    "ec.ContactTypeID = ect.ContactTypeID and " .
                    "ect.ContactTypeName = 'email1' and " .
                    "pc.ObjectID = o.ObjectID and " .
                    "pc.ContactTypeID = pct.ContactTypeID and " .
                    "pct.ContactTypeName = 'PrimaryPhone' and " .
                    "zc.ObjectID = o.ObjectID and " .
                    "zc.ContactTypeID = zct.ContactTypeID and " .
                    "zct.ContactTypeName = 'c_address'";


        $result = mysql_query($sql, $this->sqlConnection());
        if ($row = mysql_fetch_array($result)) {
            $this->p_id = $row["ObjectID"];
            $this->p_userName = $userName;
            $this->p_fullName = $row["ObjectDescription"];
            $this->p_isUserChangeable = $row["IsUserChangeable"];
            $this->p_isExpiring = $row["IsExpiring"];
            if (isset($row["ExpirationDate"])) $this->p_expirationDate = new CrsDate($row["ExpirationDate"]);
            $this->p_expirationWarnings = $row["ExpirationWarnings"];
            $this->p_timeZoneID = $row["TimeZoneID"];
            $this->p_primaryEmailAddress = $row["PrimaryEmailAddress"];
            $this->p_primaryPhoneNumber = $row["PrimaryPhoneNumber"];
            $this->p_primaryZipCode = $row["PrimaryZipCode"];

            //echo "fullName='{$this->p_fullName}'...<br>\n";
            
            $sql = "select " .
                       "o.ObjectID, " .
                       "o.ObjectName, " .
                       "o.ObjectDescription " .
                   "from " .
                       "t_objects o, " .
                       "t_objectxrefs ox, " .
                       "t_objecttypes ot " .
                   "where " .
                       "ot.ObjectTypeName = 'Domain' and " .
                       "o.ObjectTypeID = ot.ObjectTypeID and " .
                       "ox.ParentObjectID = o.ObjectID and " .
                       "ox.ChildObjectID = {$this->p_id}";
    

            $result = mysql_query($sql, $this->sqlConnection());

            $inx = 0;
            while ($row = mysql_fetch_array($result)) {
                $this->p_domains[++$inx] = new Domain();
                $this->p_domains[$inx]->Load($row["ObjectID"], $row["ObjectName"], $row["ObjectDescription"]);
                //echo "Domain ID='", $this->p_domains[$inx]->id(), "', Name='", $this->p_domains[$inx]->name(), "', Description='", $this->p_domains[$inx]->description(), "'...<br>\n";
            }
            
            if ($inx) {
                $this->p_privileges = new Privileges();
                $this->p_privileges->Load($this->p_id, $this->p_domains[0]->id());

                $this->p_pointChannels = new PointChannels();
                $this->p_pointChannels->Load($this->p_id, $this->p_domains[0]->id());

                //for ($inx=0; $inx<$this->pointChannels()->length(); $inx) {
                //    error_log( "pointChannel='" . $this->pointChannels()->item($inx)->objectId() . "', '" . $this->pointChannels()->item($inx)->channelId() . "'<br>\n", 3, "/var/log/httpd/my_log");
                //    $objectId = $this->pointChannels()->item($inx)->objectId();
                //    $channelId = $this->pointChannels()->item($inx)->channelId();
                //    error_log( "pointChannel='" . $this->pointChannels()->pointChannel($objectId, $channelId)->objectId() . "', '" . $this->pointChannels()->pointChannel($objectId, $channelId)->channelId() . "'<br>\n", 3, "/var/log/httpd/my_log");
                //}

            }
            
            return true;
        } else {
            return false;
        }
		
    }  
	
  /**
   * User::Login()
   *
   * @param mixed $userName
   * @param mixed $password
   * @return
   */
    function login($userName, $password)
    {
        // replaced 2010.06.08
        
        $sql = "
            select
               o.ObjectID,
               o.ObjectDescription,
               u.IsUserChangeable,
               u.IsExpiring,
               u.ExpirationDate,
               u.ExpirationWarnings,
               u.TimeZoneID,
               if(mdo.ObjectID = ldo.ObjectID, 1, 0) 'isLseUser',
               ec.ContactValue PrimaryEmailAddress,
               pc.ContactValue PrimaryPhoneNumber,
               zc.ContactValue PrimaryZipCode,
               mdo.ObjectID LseID,
               mdo.ObjectName LseName,
               mdo.ObjectDescription LseDescription,
               d.LogoPath LseLogoPath,
               d.EventSummaryLink LseEventSummaryLink,
               d.Tag LseTag,
               d.EnrollingParticipantIdentifier LseEnrollingParticipantIdentifier
            from
               t_objects o,
               t_users u,
               t_contacts ec,
               t_contacttypes ect,
               t_contacts pc,
               t_contacttypes pct,
               t_contacts zc,
               t_contacttypes zct,
               t_objectxrefs ulox,
               t_objects ldo,
               t_objecttypes ldot,
               t_objectxrefs mlox,
               t_objectxrefs mmox,
               t_domains d,
               t_objects mdo
            where
               o.ObjectName = '{$userName}' and
               u.Password = '{$password}' and
               u.ObjectID = o.ObjectID and
               ec.ObjectID = o.ObjectID and
               ec.ContactTypeID = ect.ContactTypeID and
               ect.ContactTypeName = 'email1' and
               pc.ObjectID = o.ObjectID and
               pc.ContactTypeID = pct.ContactTypeID and
               pct.ContactTypeName = 'PrimaryPhone' and
               zc.ObjectID = o.ObjectID and
               zc.ContactTypeID = zct.ContactTypeID and
               zct.ContactTypeName = 'c_address' and
               ulox.ChildObjectID = u.ObjectID and
               ldo.ObjectID = ulox.ParentObjectID and
               ldot.ObjectTypeID = ldo.ObjectTypeID and
               ldot.ObjectTypeName = 'Domain' and
               mlox.ChildObjectID = ulox.ParentObjectID and
               mmox.ChildObjectID = mlox.ParentObjectID and
               mmox.ParentObjectID = mmox.ChildObjectID and
               d.ObjectID = mmox.ParentObjectID and
               mdo.ObjectID = d.ObjectID
            ";

        /*
        $sql = "select " .
                    "o.ObjectID, " .
                    "o.ObjectDescription, " .
                    "u.IsUserChangeable, " .
                    "u.IsExpiring, " .
                    "u.ExpirationDate, " .
                    "u.ExpirationWarnings, " .
                    "u.TimeZoneID, " .
                    "ec.ContactValue PrimaryEmailAddress, " .
                    "pc.ContactValue PrimaryPhoneNumber, " .
                    "zc.ContactValue PrimaryZipCode, " .
                    "mdo.ObjectID LseID, " .
                    "mdo.ObjectName LseName, " .
                    "mdo.ObjectDescription LseDescription, " .
                    "d.LogoPath LseLogoPath, " .
                    "d.EventSummaryLink LseEventSummaryLink, " .
                    "d.Tag LseTag, " .
                    "d.EnrollingParticipantIdentifier LseEnrollingParticipantIdentifier " .
                 "from " .
                    "t_objects o, " .
                    "t_users u, " .
                    "t_contacts ec, " .
                    "t_contacttypes ect, " .
                    "t_contacts pc, " .
                    "t_contacttypes pct, " .
                    "t_contacts zc, " .
                    "t_contacttypes zct, " .
                    "t_objectxrefs ulox, " .
                    "t_objects ldo, " .
                    "t_objecttypes ldot, " .
                    "t_objectxrefs mlox, " .
                    "t_objectxrefs mmox, " .
                    "t_domains d, " .
                    "t_objects mdo " .
                 "where " .
                    "o.ObjectName = '{$userName}' and " .
                    "u.Password = '{$password}' and " .
                    "u.ObjectID = o.ObjectID and " .
                    "ec.ObjectID = o.ObjectID and " .
                    "ec.ContactTypeID = ect.ContactTypeID and " .
                    "ect.ContactTypeName = 'email1' and " .
                    "pc.ObjectID = o.ObjectID and " .
                    "pc.ContactTypeID = pct.ContactTypeID and " .
                    "pct.ContactTypeName = 'PrimaryPhone' and " .
                    "zc.ObjectID = o.ObjectID and " .
                    "zc.ContactTypeID = zct.ContactTypeID and " .
                    "zct.ContactTypeName = 'c_address' and " .
                    "ulox.ChildObjectID = u.ObjectID and " .
                    "ldo.ObjectID = ulox.ParentObjectID and " .
                    "ldot.ObjectTypeID = ldo.ObjectTypeID and " .
                    "ldot.ObjectTypeName = 'Domain' and " .
                    "mlox.ChildObjectID = ulox.ParentObjectID and " .
                    "mmox.ChildObjectID = mlox.ParentObjectID and " .
                    "mmox.ParentObjectID = mmox.ChildObjectID and " .
                    "d.ObjectID = mmox.ParentObjectID and " .
                    "mdo.ObjectID = d.ObjectID";
        */
        //echo "User->Login: sql='" . $sql . "'<br>\n";

        $result = mysql_query($sql, $this->sqlConnection());

        if ($row = mysql_fetch_array($result)) {
            $this->p_id = $row["ObjectID"];
            $this->p_userName = $userName;            
            $this->p_fullName = $row["ObjectDescription"];
            $this->p_isUserChangeable = $row["IsUserChangeable"];
            $this->p_isExpiring = $row["IsExpiring"];
            if (isset($row["ExpirationDate"])) $this->p_expirationDate = new CrsDate($row["ExpirationDate"]);
            $this->p_expirationWarnings = $row["ExpirationWarnings"];
            $this->p_timeZoneID = $row["TimeZoneID"];
            $this->p_primaryEmailAddress = $row["PrimaryEmailAddress"];
            $this->p_primaryPhoneNumber = $row["PrimaryPhoneNumber"];
            $this->p_primaryZipCode = $row["PrimaryZipCode"];         
            $this->p_isLseUser = $row["isLseUser"];   

            $this->p_lseDomain = new Domain($row['LseID'],
                                            $row['LseName'],
                                            $row['LseDescription'],
                                            $row['LseLogoPath'],
                                            $row['LseEventSummaryLink'],
                                            $row['LseTag'],
                                            $row['LseEnrollingParticipantIdentifier']);
          
            $sql = "select " .
                       "o.ObjectID, " .
                       "o.ObjectName, " .
                       "o.ObjectDescription " .
                   "from " .
                       "t_objects o, " .
                       "t_objectxrefs ox, " .
                       "t_objecttypes ot " .
                   "where " .
                       "ot.ObjectTypeName = 'Domain' and " .
                       "o.ObjectTypeID = ot.ObjectTypeID and " .
                       "ox.ParentObjectID = o.ObjectID and " .
                       "ox.ChildObjectID = {$this->p_id}";
    
            $result = mysql_query($sql, $this->sqlConnection());

            $inx = 0;
            while ($row = mysql_fetch_array($result)) {
                $this->p_domains[$inx] = new Domain();
                $this->p_domains[$inx++]->Load($row["ObjectID"], $row["ObjectName"], $row["ObjectDescription"]);
                //echo "Domain ID='", $this->p_domains[$inx]->id(), "', Name='", $this->p_domains[$inx]->name(), "', Description='", $this->p_domains[$inx]->description(), "'...<br>\n";
            }
            

            if ($inx) {
                $this->p_privileges = new Privileges();
                $this->p_privileges->Load($this->p_id, $this->p_domains[0]->id());

                $this->p_pointChannels = new PointChannels();
                $this->p_pointChannels->Load($this->p_id, $this->p_domains[0]->id());
            }

            $this->p_preferences = new Preferences();
            $this->p_preferences->Load($this->p_id);
         
            
            return true;
        } else {
            return false;
        }
    }  
	  
    
  /**
   * User::HasPrivilege()
   *
   * @param mixed $privilege
   * @return
   */
    function HasPrivilege($privilege)
    {
        return $this->p_privileges->HasPrivilege($privilege);
    }

  /**
   * User::privilegeCount()
   *
   * @return
   */
    function privileges()
    {
        return $this->p_privileges;
    }

  /**
   * User::HasPreference()
   *
   * @param mixed $preference
   * @return
   */
    function HasPreference($preference)
    {
        return $this->p_preferences->HasPreference($preference);
    }
    
  /**
   * User::Domains()
   *
   * @param mixed $index
   * @return
   */
    function Domains($index)
    {    
        return $this->p_domains[$index];
    }
	
    function Dump()
    {
        return $this->p_privileges->Dump() .
               $this->p_preferences->Dump();
    }

/*  ===============================================================================
    FUNCTION : refreshPrefs()
    =============================================================================== */

    function refreshPrefs()
    {
        $this->p_preferences = new Preferences();
        $this->p_preferences->Load($this->p_id);
    } // refreshPrefs()

/*  ===============================================================================
    FUNCTION : toggleRetired()
    =============================================================================== */
    function toggleRetired()
    {
        $this->refreshPrefs();
        
        if($this->HasPreference('HideRetiredPointChannels')) 
        {
            return $this->p_preferences->showRetired($this->p_id);
        }
        else
        {
            return $this->p_preferences->hideRetired($this->p_id);
        }

    } // toggleHideRetired

/**
    An reference load of the prices, with no intervals, just
    summary info and using current time stamp info (no
    historical) at the point of refresh.
    **/

    function refreshSummaryPrices()
    {
        $sql_prices = 'SELECT
                    *
                FROM
                    t_prices
                ';

        $query_prices = $this->sqlConnection()->query($sql_prices);
        if ($query_prices && $query_prices->num_rows()) {
            foreach($query_prices->result() as $row)
            {
                $sql_maxInterval = '
                        ';
                $query_maxInterval = $this->sqlConnection()->query($sql_maxInterval);
                if ($query_maxInterval && $query_maxInterval->num_rows()) 
                {
                    foreach($query_maxInterval->result() as $row)
                    {

                    }
                }
            }
        }
        /*
         * $sql = "";

        if ($this->p_displayPriceId) {
            $sql = "select " .
                      "max(IntervalValue) MaximumIntervalValue " .
                   "from " .
                      "t_priceintervals " .
                   "where " .
                      "PriceID = {$this->p_displayPriceId} and " .
                      "IntervalDate between '" . $this->p_baseDate->DateAdd("i", 5)->Format("Y-m-d H:i:s") . "' and '" . $this->p_baseDate->DateAdd("d", $this->p_dateSpan)->Format("Y-m-d H:i:s") . "'";
    
//            echo "sql='{$sql}...<br>\n";
            $result = mysql_query($sql, $this->sqlConnection());
            if ($row = mysql_fetch_array($result)) {
                if (!isset($row["MaximumIntervalValue"])) {
                    $this->p_maximumDailyDisplayPrice = 0.00;
                } else {
                    $this->p_maximumDailyDisplayPrice = $row["MaximumIntervalValue"];
                }
            }
            
            $sql = "select " .
                      "IntervalDate " .
                   "from " .
                      "t_priceintervals " .
                   "where " .
                      "PriceID = {$this->p_displayPriceId} and " .
                      "IntervalDate between '" . $this->p_baseDate->DateAdd("i", 5)->Format("Y-m-d H:i:s") . "' and '" . $this->p_baseDate->DateAdd("d", $this->p_dateSpan)->Format("Y-m-d H:i:s") . "' and " .
                      "IntervalValue = {$this->p_maximumDailyDisplayPrice}";
    
//            echo "sql='{$sql}...<br>\n";
            $result = mysql_query($sql, $this->sqlConnection());

            if ($row = mysql_fetch_array($result)) {

                if (!isset($row["IntervalDate"])) {
                    $this->p_maximumDailyDisplayPriceDate = $this->p_baseDate;
                } else {
                    $this->p_maximumDailyDisplayPriceDate = $row["IntervalDate"];
                }
            }
            
            $sql = "select " .
                     "max(IntervalDate) CurrentIntervalDate " .
                  "from " .
                     "t_priceintervals " .
                  "where " .
                     "PriceID = {$this->p_displayPriceId}";
    
            //echo "sql='{$sql}...<br>\n";
            $result = mysql_query($sql, $this->sqlConnection());
            if ($row = mysql_fetch_array($result)) {
                if (!isset($row["CurrentIntervalDate"])) {
                    $this->p_currentDisplayPriceDate = $this->p_baseDate;
                    $this->p_currentDisplayPrice = 0.000;
                } else {
                    $this->p_currentDisplayPriceDate = new CrsDate($row["CurrentIntervalDate"]);
                    $sql = "select " .
                             "IntervalValue " .
                          "from " .
                             "t_priceintervals " .
                          "where " .
                             "PriceID = {$this->p_displayPriceId} and " .
                             "IntervalDate = '" . $this->p_currentDisplayPriceDate->Format("Y-m-d H:i:s") . "'";
                         
                    //echo "sql='{$sql}...<br>\n";
                    $result = mysql_query($sql, $this->sqlConnection());
                    if ($row = mysql_fetch_array($result)) {
                        if (!isset($row["IntervalValue"])) {
                            $this->p_currentDisplayPrice = 0.000;
                        } else {
                            $this->p_currentDisplayPrice = $row["IntervalValue"];
                        }
                    }
                }
            }
        } 
        **/
        
    } // initSummaryPrices()
}
?>

