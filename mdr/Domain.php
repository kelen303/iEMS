<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
/**
 * Domain
 *
 * @package IEMS 
 * @name Domain
 * @author Kevin L. Keegan, Conservation Resource Solutions, Inc.
 * @copyright 2008
 * @version 2.0
 * @access public
 */
class Domain extends CAO
{
    private $p_id;
    private $p_name;
    private $p_description;
    private $p_logoPath;
    private $p_eventSummaryLink;
    private $p_tag;
    private $p_enrollingParticipantIdentifier;

    private $p_tickerMessages;
    
    private $p_eventDates = array();

  /**
   * Domain::id()
   *
   * @return
   */
    function id()
    {
        return $this->p_id;
    }
    
  /**
   * Domain::name()
   *
   * @return
   */
    function name()
    {
        return $this->p_name;
    }
    
  /**
   * Domain::description()
   *
   * @return
   */
    function description()
    {
        return $this->p_description;
    }
    
  /**
   * Domain::description()
   *
   * @return
   */
    function logoPath()
    {
        return $this->p_logoPath;
    }
    
  /**
   * Domain::description()
   *
   * @return
   */
    function eventSummaryLink()
    {
        return $this->p_eventSummaryLink;
    }
    
  /**
   * Domain::description()
   *
   * @return
   */
    function tag()
    {
        return $this->p_tag;
    }
    
  /**
   * Domain::description()
   *
   * @return
   */
    function enrollingParticipantIdentifier()
    {
        return $this->p_enrollingParticipantIdentifier;
    }

  /**
   * Domain::tickerMessages()
   *
   * @return
   */
    function tickerMessages()
    {
        return $this->p_tickerMessages;
    }

    function eventDates()
    {
        return $this->p_eventDates;
    }
    
  /**
   * Domain::__construct()
   *
   * @param integer $id
   * @param string $name
   * @param string $description
   * @return
   */
    function __construct($id = -1, $name = "", $description = "", $logoPath = "", $eventSummaryLink = "", $tag = "", $enrollingParticipantIdentifier = "")
    {
        parent::__construct();

        $this->p_id = -1;
        $this->p_name = "";
        $this->p_description = "";

        if ($id > 0) $this->Load($id, $name, $description, $logoPath, $eventSummaryLink, $tag, $enrollingParticipantIdentifier);
    }

    function __destruct()
    {
        parent::__destruct();
    }
    
  /**
   * Domain::Load()
   *
   * @param mixed $id
   * @param mixed $name
   * @param mixed $description
   * @return
   */
    function Load($id, $name, $description, $logoPath = "", $eventSummaryLink = "", $tag = "", $enrollingParticipantIdentifier = "")
    {
        if ($this->p_id < 0) {
            $this->p_id = $id;
            $this->p_name = $name;
            $this->p_description = $description;
            $this->p_logoPath = $logoPath;
            $this->p_eventSummaryLink = $eventSummaryLink;
            $this->p_tag = $tag;
            $this->p_enrollingParticipantIdentifier = $enrollingParticipantIdentifier;
        }
        
        $this->Refresh();
    }

    function Refresh() {
		$sql = '
    			SELECT 
    				TickerMessage, 
    				Priority
    			FROM
    				t_tickermessages tm, 
    				t_objectxrefs dox, 
    				t_objectxrefs tox, 
    				t_objects o, 
    				t_objecttypes ot, 
    				t_objecttypes dot, 
    				t_objects do 
    			WHERE 
    				dox.ChildObjectID = ' . $this->p_id . ' and 
    				tox.ParentObjectID = dox.ParentObjectID and 
    				do.ObjectID = tox.ParentObjectID and 
    				dot.ObjectTypeID = do.ObjectTypeID and 
    				dot.ObjectTypeName = "Domain" and 
    				o.ObjectID = tox.ChildObjectID and 
    				o.ObjectTypeID = ot.ObjectTypeID and 
    				ot.ObjectTypeName = "TickerMessage" and 
    				tm.ObjectID = o.ObjectID and 
    				Now() between case tm.EffectiveDate when "0000-00-00 00:00:00" then date_sub(now(), INTERVAL 1 DAY) when null then date_sub(now(), INTERVAL 1 DAY)  else tm.EffectiveDate end and 
    				       case tm.ExpirationDate when "0000-00-00 00:00:00" then date_add(now(), INTERVAL 1 DAY) when null then date_add(now(), INTERVAL 1 DAY)  else tm.ExpirationDate end 
    			ORDER BY 
    				Priority
    		   ';
		

        //$this->preDebugger($sql);
		$result = mysql_query($sql, $this->sqlConnection());
		
		if(mysql_numrows($result) == 0) {
			$this->tickerMessages = array('TickerMessage'=>'', 'Priority'=>3);
		} else {
			$priorityCheck = array();
            $inx = 0;

            while($tickerRow = mysql_fetch_assoc($result)) {
                    $priorityCheck[$inx] = $tickerRow['Priority'];
                    $values[$inx++] = $tickerRow['TickerMessage'];
            }

            $iMinValue = min($priorityCheck);
            $arFlip = array_flip($priorityCheck);
            $iMinPosition = $arFlip[$iMinValue];
            
            $this->p_tickerMessages['Priority'] = $priorityCheck[$iMinPosition];
            $this->p_tickerMessages['TickerMessage'] = $values[$iMinPosition];
		}

        $sql = 'select distinct
                    left(n.StartDate, 10) "EventDate"
                from
                    mdr.t_participationtypes pt
                    ,mdr.t_notifications n
                    ,mdr.t_notificationtypes nt
                    ,mdr.t_notificationpointchannels npc
                    ,mdr.t_privileges pr
                    ,mdr.t_points p
                    ,mdr.t_actorprivilegexrefs apx
                    ,mdr.t_objects o
                    ,mdr.t_objecttypes ot
                  ,mdr.t_pointchannelprogramparticipationprofiles pcppp
                where
                    o.ObjectID =  '. $this->p_id .'
                    and ot.ObjectTypeID = o.ObjectTypeID
                    and ot.ObjectTypeName = \'Domain\'
                    and apx.ObjectID = o.ObjectID
                    and pr.PrivilegeID = apx.PrivilegeID
                    and p.ObjectID = pr.ObjectID
                    and p.IsVirtual = 0
                    and npc.ObjectID = p.ObjectID
                    and n.NotificationID = npc.NotificationID
                    and nt.NotificationTypeID = n.NotificationTypeID
                    and nt.NotificationTypeName in (\'Audit\', \'Emergency\')
                    and pt.ParticipationTypeID = pcppp.ParticipationTypeID
                    and pt.DoNotSendVoiceNotifications = 0
                    and pt.ParticipationTypeName in (\'Thirty_Minute_Demand_Response_9\',\'Thirty_Minute_Demand_Response_12\')
                order by
                    EventDate DESC;
            ';
		//$this->preDebugger($sql,'red');

		$result = mysql_query($sql, $this->sqlConnection());
        
        if($result && mysql_num_rows($result) > 0)
        {
            $this->p_eventDates = array();
            while ($row = mysql_fetch_assoc($result)) 
            {
                $this->p_eventDates[] = date('m-d-Y', strtotime($row['EventDate']));
            }            
        }
        
    }
    function dump()
    {
        $sql = 'SELECT *                  
                FROM
                  t_domains d,                  
                  t_objects o
                where
                  o.ObjectID = d.ObjectID and
                  o.IsInactive = 0
               ';
		
		$result = mysql_query($sql, $this->sqlConnection());
		

        while ($row = mysql_fetch_assoc($result)) 
		{
            print '<pre style="text-align: left;">';
            /*
            $logoString = str_replace('logos/','',$row['LogoPath']);
            $content = $logoString == 'iems.gif' ? '#175986' : '';
            $contentText = $logoString == 'iems.gif' ? '#FFFFFF' : '';
            $wygTitle = $logoString == 'iems.gif' ? '#175986' : '';
            $wygTitleText = $logoString == 'iems.gif' ? '#FFFFFF' : '';
            $buttons = $logoString == 'iems.gif' ? 'Blue' : '';

			
            print 'case \''.$row['ObjectName'].'\':<br />';
            print '&nbsp;&nbsp;&nbsp;&nbsp;$domainStyle[\'content\'] = \''.$content.'\';<br />';
            print '&nbsp;&nbsp;&nbsp;&nbsp;$domainStyle[\'contentText\'] = \''.$contentText.'\';<br />';
            print '&nbsp;&nbsp;&nbsp;&nbsp;$domainStyle[\'wygTitleDiv\'] = \''.$wygTitle.'\';<br />';
            print '&nbsp;&nbsp;&nbsp;&nbsp;$domainStyle[\'wygTitleText\'] = \''.$wygTitleText.'\';<br />';  
            print '&nbsp;&nbsp;&nbsp;&nbsp;$domainStyle[\'buttons\'] = \''.$buttons.'\';<br />';
            print '&nbsp;&nbsp;&nbsp;&nbsp;$domainStyle[\'logoFileName\'] = \''.$logoString.'\';<br />';
            print '&nbsp;&nbsp;&nbsp;&nbsp;break;';
                

            print 'ObjectID = '.$row['ObjectID'].'<br />';
            print 'ObjectName = '.$row['ObjectName'].'<br />';
            print 'ObjectDescription = '.$row['ObjectDescription'].'<br />';
            print 'LogoPath = '.$row['LogoPath'].'<br />';
            print_r($row);
            print '<hr />';
            print '</pre>';
*/
            /* 
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
            */

		}
    }
}
?>
