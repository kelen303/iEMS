<?php
if(!defined('GROK')) {header('HTTP/1.0 404 not found'); exit;}
//
//===========================================================================
//
/**
 * MeterPoint
 *
 * @package IEMS 
 * @name Meter Point
 * @author Kevin L. Keegan, Conservation Resource Solutions, Inc.
 * @copyright 2008
 * @version 3.0
 * @access 
 */
class MeterPoint extends CAO
{
    private $p_id;
    private $p_name;
    private $p_description;
    private $p_maximumChannelId;
    private $p_isInactive;
    private $p_assetIdentifier;
    private $p_serialNumber;
    private $p_timeZone;
    private $p_readTimeOffset;
    private $p_readInterval;
    private $p_isGenerator;
    private $p_isAggregate;
    private $p_isChecked;
    private $p_isAutoEventGenerated;
    private $p_isDemo;
    private $p_isEnabled;
    private $p_program;
    private $p_participationTypeDescription;
    private $p_committedReduction;
    private $p_createdBy;
    private $p_updatedBy;
    private $p_baseDate;
    private $p_dateSpan;
    private $p_zone;
    private $p_displayPriceId;
    private $p_settlementPriceId;
    private $p_displayPriceDescription;
    private $p_settlementPriceDescription;
    private $p_maximumDailyDisplayPrice;
    private $p_maximumDailyDisplayPriceDate;
    private $p_currentDisplayPrice;
    private $p_currentDisplayPriceDate;

    private $p_eventDates = array();
    
  /**
   * MeterPoint::id()
   *
   * @return
   */
    function id($id = null)
    {
        if (!isset($id)) {
            return $this->p_id;
        } else {
            $this->p_id = $id;
        }
    }
    
  /**
   * MeterPoint::name()
   *
   * @return
   */
    function name()
    {
        return $this->p_name;
    }
    
  /**
   * MeterPoint::description()
   *
   * @return
   */
    function description()
    {
        return $this->p_description;
    }
    
  /**
   * MeterPoint::maximumChannelId()
   *
   * @return
   */
    function maximumChannelId()
    {
        return $this->p_maximumChannelId;
    }
    
  /**
   * MeterPoint::isInactive()
   *
   * @return
   */
    function isInactive()
    {
        return $this->p_isInactive;
    }
    
  /**
   * MeterPoint::assetIdentifier()
   *
   * @return
   */
    function assetIdentifier()
    {
        return $this->p_assetIdentifier;
    }
    
  /**
   * MeterPoint::serialNumber()
   *
   * @return
   */
    function serialNumber()
    {
        return $this->p_serialNumber;
    }
    
  /**
   * MeterPoint::oTimeZone()
   *
   * @return
   */
    function timeZone($timeZone = null)
    {
        if (!isset($timeZone)) {
            return $this->p_timeZone;
        } else {
            $this->p_timeZone = clone $timeZone;
        }
    }
    
  /**
   * MeterPoint::readTimeOffset()
   *
   * @return
   */
    function readTimeOffset($readTimeOffset = null)
    {
        if (!isset($readTimeOffset)) {
            return $this->p_readTimeOffset;
        } else {
            $this->p_readTimeOffset = $readTimeOffset;
        }
    }
    
  /**
   * MeterPoint::readInterval()
   *
   * @return
   */
    function readInterval($readInterval = null)
    {
        if (!isset($readInterval)) {
            return $this->p_readInterval;
        } else {
            $this->p_readInterval = $readInterval;
        }
    }
    
  /**
   * MeterPoint::program()
   *
   * @return
   */
    function program()
    {
        return $this->p_program;
    }
    
  /**
   * MeterPoint::participationTypeDescription()
   *
   * @return
   */
    function participationTypeDescription($participationTypeDescription = null)
    {
        if (!isset($participationTypeDescription)) {
            return $this->p_participationTypeDescription;
        } else {
            $this->p_participationTypeDescription = $participationTypeDescription;   
        }
    }

  /**
   * MeterPoint::participationTypeId()
   *
   * @return
   */
    function participationTypeId($participationTypeId = null)
    {
        if (!isset($participationTypeId)) {
            return $this->p_participationTypeId;
        } else {
            $this->p_participationTypeId = $participationTypeId;   
        }
    }
        
  /**
   * MeterPoint::committedReduction()
   *
   * @return
   */
    function committedReduction($committedReduction = null)
    {
        if (!isset($committedReduction)) {
            return $this->p_committedReduction;
        } else {
            $this->p_committedReduction = $committedReduction;
        }
    }
    
  /**
   * MeterPoint::isGenerator()
   *
   * @return
   */
    function isGenerator()
    {
        return $this->p_isGenerator;
    }
    
  /**
   * MeterPoint::isAggregate()
   *
   * @return
   */
    function isAggregate()
    {
        return $this->p_isAggregate;
    }
    
  /**
   * MeterPoint::isChecked()
   *
   * @return
   */
    function isChecked()
    {
        return $this->p_isChecked;
    }
    
  /**
   * MeterPoint::isAutoEventGenerated()
   *
   * @return
   */
    function isAutoEventGenerated()
    {
        return $this->p_isAutoEventGenerated;
    }
    
  /**
   * MeterPoint::isDemo()
   *
   * @return
   */
    function isDemo()
    {
        return $this->p_isDemo;
    }
    
  /**
   * MeterPoint::isEnabled()
   *
   * @return
   */
    function isEnabled()
    {
        return $this->p_isEnabled;
    }
    
  /**
   * MeterPoint::createdBy()
   *
   * @return
   */
    function createdBy()
    {
        return $this->p_createdBy;
    }
    
  /**
   * MeterPoint::updatedBy()
   *
   * @return
   */
    function updatedBy()
    {
        return $this->p_updatedBy;
    }
    
  /**
   * MeterPoint::baseDate()
   *
   * @return
   */
    function baseDate($baseDate = null)
    {
        if (!isset($baseDate)) {
            return $this->p_baseDate->Format("m/d/Y");
        } else {
            $this->p_baseDate = clone $baseDate;
        }
    }
    
  /**
   * MeterPoint::dateSpan()
   *
   * @return
   */
    function dateSpan($dateSpan = null)
    {
        if (!isset($dateSpan)) {
            return $this->p_dateSpan;
        } else {
            $this->p_dateSpan = $dateSpan;
        }
    }
    
  /**
   * MeterPoint::zone()
   *
   * @return
   */
    function zone($zone = null)
    {
        if (!isset($zone)) {
            return $this->p_zone;
        } else {
            $this->p_zone = $zone;
        }
    }
    
  /**
   * MeterPoint::displayPriceId()
   *
   * @return
   */
    function displayPriceId($displayPriceId = null)
    {
        if (!isset($displayPriceId)) {
            return $this->p_displayPriceId;
        } else {
            $this->p_displayPriceId = $displayPriceId;
        }
    }
    
  /**
   * MeterPoint::displayPriceDescription()
   *
   * @return
   */
    function displayPriceDescription($displayPriceDescription = null)
    {
        if (!isset($displayPriceDescription)) {
            return $this->p_displayPriceDescription;
        } else {
            $this->p_displayPriceDescription = $displayPriceDescription;
        }
    }
    
  /**
   * MeterPoint::maximumDailyDisplayPrice()
   *
   * @return
   */
    function maximumDailyDisplayPrice()
    {
        return $this->p_maximumDailyDisplayPrice;
    }
    
  /**
   * MeterPoint::maximumDailyDisplayPriceDate()
   *
   * @return
   */
    function maximumDailyDisplayPriceDate()
    {
        return $this->p_maximumDailyDisplayPriceDate;
    }
    
  /**
   * MeterPoint::currentDisplayPrice()
   *
   * @return
   */
    function currentDisplayPrice()
    {
        return $this->p_currentDisplayPrice;
    }
    
  /**
   * MeterPoint::currentDisplayPriceDate()
   *
   * @return
   */
    function currentDisplayPriceDate()
    {
        return $this->p_currentDisplayPriceDate;
    }
    
  /**
   * MeterPoint::settlementPriceId()
   *
   * @return
   */
    function settlementPriceId($settlementPriceId = null)
    {
        if (!isset($settlementPriceId)) {
            return $this->p_settlementPriceId;
        } else {
            $this->p_settlementPriceId = $settlementPriceId;
        }
    }
    
  /**
   * MeterPoint::settlementPriceDescription()
   *
   * @return
   */
    function settlementPriceDescription($settlementPriceDescription = null)
    {
        if (!isset($settlementPriceDescription)) {
            return $this->p_settlementPriceDescription;
        } else {
            $this->p_settlementPriceDescription = $settlementPriceDescription;
        }
    }
    
  /**
   * MeterPoint::maximumDailySettlementPrice()
   *
   * @return
   */
    function maximumDailySettlementPrice()
    {
        return $this->p_maximumDailySettlementPrice;
    }
    
  /**
   * MeterPoint::currentSettlementPrice()
   *
   * @return
   */
    function currentSettlementPrice()
    {
        return $this->p_currentSettlementPrice;
    }
    
  /**
   * MeterPoint::pointChannels()
   *
   * @return
   */
    function pointChannels()
    {
        return $this->p_pointChannels;
    }

  /**
   * MeterPoint::eventDates()
   *
   * @return
   */
    function eventDates()
    {
        return $this->p_eventDates;
    }
    
  /**
   * MeterPoint::construct__()
   *
   * @return
   */
    function construct__()
    {
        parent::__construct();

        $this->p_id = 0;
        $this->p_name = "";
        $this->p_description = "";
        $this->p_maximumChannelId = 0;
        $this->p_isInactive = false;
        $this->p_assetIdentifier = "";
        $this->p_serialNumber = "";
        $this->p_timeZone = new TimeZone();
        $this->p_readTimeOffset = new TimeSpan("0.00:00:00.000");
        $this->p_readInterval = 0;
        $this->p_isGenerator = false;
        $this->p_isAggregate = false;
        $this->p_isChecked = false;
        $this->p_isAutoEventGenerated = false;
        $this->p_isDemo = false;
        $this->p_isEnabled = false;
        $this->p_createdBy = 0;
        $this->p_updatedBy = 0;
        $this->p_committedReduction = 0.000;
        $this->p_zone = "";
        $this->p_zoneId = 0;
        $this->p_displayPriceDescription = "";
        $this->p_maximumDailyDisplayPrice = 0.000;
        $this->p_currentDisplayPrice = 0.000;
        $this->p_settlementPriceDescription = "";
        $this->p_maximumDailySettlementPrice = 0.000;
        $this->p_currentSettlementPrice = 0.000;
    
        $this->p_lastRead = 0;
        $this->p_currentAverage = 0.000;
    }

    function __destruct()
    {
        parent::__destruct();
    }
    
  /**
   * MeterPoint::Load()
   *
   * @param mixed $pointId
   * @param mixed $baseDate
   * @param mixed $dateSpan
   * @return
   */
    function Load($pointId, $baseDate, $dateSpan)
    {
        $this->p_id = $pointId;
        $this->p_baseDate = clone $baseDate;
        $this->p_dateSpan = $dateSpan;
    
        /*
        print 'Load<br />';
        var_dump($this->sqlConnection());
        print '<hr />';
        */
        $this->Refresh();        
        $this->RefreshPrices();
        $this->RefreshEventDates();        
    }
    
  /**
   * MeterPoint::Refresh()
   *
   * @return
   */
    function Refresh()
    {
        $sql = "select " .
                     "o.ObjectID, " .
                     "o.ObjectName, " .
                     "o.ObjectDescription, " .
                     "MaximumChannelID, " .
                     "o.IsInactive, " .
                     "AssetIdentifier, " .
                     "SerialNumber, " .
                     "TimeZoneID, " .
                     "ReadTimeOffset, " .
                     "ReadInterval, " .
                     "IsGenerator, " .
                     "IsAggregate, " .
                     "IsChecked, " .
                     "IsAutoEventGenerated, " .
                     "IsDemo, " .
                     "p.IsEnabled, " .
                     "po.ObjectDescription Program, " .
                     "pt.ParticipationTypeDescription, " .
                     "pt.ParticipationTypeId, " .
                     "pcppp.CommittedReduction, " .
                     "zo.ObjectDescription Zone, " .
                     "z.DisplayPriceID, " .
                     "z.SettlementPriceID, " . 
                     "pd.PriceDescription DisplayPriceDescription, " .
                     "ps.PriceDescription SettlementPriceDescription, " .
                     "p.CreatedBy, " .
                     "p.UpdatedBy " .
                     "from " .
                         "t_objects o, " .
                         "t_points p, " .
                         "t_pointchannelprogramparticipationprofiles pcppp, " .
                         "t_objects po, " .
                         "t_participationtypes pt, " .
                         "t_objectxrefs ox, " .
                         "t_groups g, " .
                         "t_grouptypes gt, " .
                         "t_objects zo, " .
                         "t_zones z, " .
                         "t_prices pd, " .
                         "t_prices ps " .
                     "where " .
						 "pt.ParticipationTypeName not in ('Day_Ahead_Demand_Response') and " .
                         "o.ObjectID = {$this->p_id} and " .
                         "p.ObjectID = o.ObjectID and " .
                         "pcppp.PointObjectID = p.ObjectID and " .
                         "po.ObjectID = pcppp.ProgramObjectID and " .
                         "pt.ParticipationTypeID = pcppp.ParticipationTypeID and " .
                         "ox.ChildObjectID = pcppp.PointObjectID and " .
                         "g.ObjectID = ox.ParentObjectID and " .
                         "gt.GroupTypeName = 'Zone' and " .
                         "g.GroupTypeID = gt.GroupTypeID and " .
                         "zo.ObjectID = g.ObjectID and " .
                         "z.ObjectID = zo.ObjectID and " .
                         "pd.PriceID = z.DisplayPriceID and " .
                         "ps.PriceID = z.SettlementPriceID";
    
        //echo "sql='{$sql}...<br>\n";
        $result = mysql_query($sql, $this->sqlConnection());

        if ($row = mysql_fetch_array($result)) {
            $this->p_name = $row["ObjectName"];
            $this->p_description = $row["ObjectDescription"];
            $this->p_maximumChannelId = $row["MaximumChannelID"];
            $this->p_isInactive = $row["IsInactive"];
            $this->p_assetIdentifier = $row["AssetIdentifier"];
            $this->p_serialNumber = $row["SerialNumber"];
            $this->p_timeZone = new TimeZone((integer)$row["TimeZoneID"]);
            $this->p_timeZone->Get();
            $this->p_readTimeOffset = new TimeSpan($row["ReadTimeOffset"]);
            $this->p_readInterval = $row["ReadInterval"];
            $this->p_isGenerator = $row["IsGenerator"];
            $this->p_isAggregate = $row["IsAggregate"];
            $this->p_isChecked = $row["IsChecked"];
            $this->p_isAutoEventGenerated = $row["IsAutoEventGenerated"];
            $this->p_isDemo = $row["IsDemo"];
            $this->p_isEnabled = $row["IsEnabled"];
            $this->p_createdBy = $row["CreatedBy"];
            $this->p_updatedBy = $row["UpdatedBy"];
            $this->p_program = $row["Program"];
            $this->p_participationTypeId = $row["ParticipationTypeId"];
            $this->p_participationTypeDescription = $row["ParticipationTypeDescription"];
            $this->p_committedReduction = $row["CommittedReduction"];
            $this->p_zone = $row["Zone"];
            $this->p_displayPriceId = $row["DisplayPriceID"];
            $this->p_settlementPriceId = $row["SettlementPriceID"];
            $this->p_displayPriceDescription = $row["DisplayPriceDescription"];
            $this->p_settlementPriceDescription = $row["SettlementPriceDescription"];
        
            //$this->p_pointChannels->Load($this, $this->p_baseDate);


        }
    }
    
  /**
   * MeterPoint::RefreshPrices()
   *
   * @return
   */
    function RefreshPrices()
    {
        $sql = "";

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
            /*
            print 'Refresh Prices<br />';
            var_dump($this->sqlConnection());
			print '<hr />';
            */
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
    }

    function RefreshEventDates()
    {
        $sql = '
            SELECT distinct                
                StartDate,
                EndDate
            FROM
                t_notificationpointchannels npc,
                t_notifications n,
                t_fcmnotifications fn
            WHERE
                npc.ObjectID = ' . $this->p_id . ' and
                n.NotificationID = npc.NotificationID and
                fn.NotificationID = n.NotificationID
            ORDER BY
                StartDate DESC
        ';
		
		
        //$this->preDebugger($sql);        

        $result = mysql_query($sql, $this->sqlConnection());       
	
        $inx = 0;

        //$this->preDebugger(mysql_num_rows($result));

        if (mysql_numrows($result) > 0) {
            while ($row = mysql_fetch_array($result)) {
                //$this->preDebugger($row);
                $dateId = date('m-d-Y',strtotime($row['StartDate']));
                $this->p_eventDates[$dateId]['startDate'] = date('m-d-Y',strtotime($row['StartDate']));
                $this->p_eventDates[$dateId]['startTime'] = date('H:i:s',strtotime($row['StartDate']));
                $this->p_eventDates[$dateId]['endDate'] = date('m-d-Y',strtotime($row['EndDate']));
                $this->p_eventDates[$dateId]['endTime'] = date('H:i:s',strtotime($row['EndDate']));
                //$this->preDebugger($this->p_eventDates, 'red');                
                //$inx++;
            }
        }
        
    }

    function fetchEventParticulars( $pointId,
                                    $channelId,
                                    $startDate,
                                    $endDate    ) 
    {
        //$this->preDebugger($startDate);
        $sql = '
            SELECT npc.*, n.*, fn.*
            FROM
                t_fcmnotifications fn,
                t_notificationpointchannels npc,
                t_notifications n
            WHERE
                n.StartDate >= "'.$startDate.'" and
                n.StartDate <= "'.$endDate.'" and
                npc.NotificationID = n.NotificationID and
                npc.ObjectID = '.$pointId.' and
                npc.ChannelID = '.$channelId.' and
                fn.NotificationID = npc.NotificationID
        ';

        $sql = '
            SELECT npc.*, n.*, fn.*
            FROM
                t_fcmnotifications fn,
                t_notificationpointchannels npc,
                t_notifications n
            WHERE
                n.StartDate >= "'.$startDate.'" and
                n.StartDate <= "'.$endDate.'" and
                npc.NotificationID = n.NotificationID and
                npc.ObjectID = '.$pointId.' and
                npc.ChannelID = '.$channelId.' and
                fn.NotificationID = npc.NotificationID
            ORDER BY
                fn.NotificationID,
                fn.NotificationEmailID
            LIMIT
                1
            ';
				
           //$this->preDebugger($sql);
        $result = mysql_query($sql, $this->sqlConnection());

        if(mysql_num_rows($result) > 0) 
        {
            
            $row = mysql_fetch_object($result);

            //$this->preDebugger($row);

            $particulars['base'] = $row;

            $utcStartDate = gmdate('Y-m-d H:i:00',strtotime($row->StartDate));
            $utcEndDate = gmdate('Y-m-d H:i:00',strtotime($row->EndDate));


            //$this->preDebugger($utcStartDate);

            $FCASQL = '
                SELECT
                  if (p.IsNetAsset=1, sum(li.IntervalValue - bi.IntervalValue)*12.0/(count(*)-(pt.ResponseTime/p.ReadInterval)), if (pc.IsGenerator=0, sum(bi.IntervalValue - li.IntervalValue)*12.0/(count(*)-(pt.ResponseTime/p.ReadInterval)), sum(li.IntervalValue*12)/(count(*)-(pt.ResponseTime/p.ReadInterval)))) "Performance",
                  if (pcppp.CommittedReduction>0,round(if (p.IsNetAsset=1, sum(li.IntervalValue - bi.IntervalValue)*12.0/(count(*)-(pt.ResponseTime/p.ReadInterval)), if (pc.IsGenerator=0, sum(bi.IntervalValue - li.IntervalValue)*12.0/(count(*)-(pt.ResponseTime/p.ReadInterval)), sum(li.IntervalValue*12)/(count(*)-(pt.ResponseTime/p.ReadInterval))))/pcppp.CommittedReduction * 100.0), \'N/A\') "PCR"
                FROM
                  t_intervals li,
                  t_intervalsettypes list,
                  t_intervalsets liss,
                  t_intervals bi,
                  t_intervalsettypes bist,
                  t_intervalsets biss,
                  t_notifications n,
                  t_participationtypes pt,
                  t_pointchannels pc,
                  t_points p,
                  t_pointchannelprogramparticipationprofiles pcppp
                WHERE
                  pc.ObjectID = '.$pointId.' and
                  pc.ChannelID = '.$channelId.' and
                  n.NotificationID = '.$row->NotificationID.' and
                  liss.IntervalSetBaseDate = "'.$startDate.'" and
                  li.IntervalDate between date_add("'.$utcStartDate.'", INTERVAL 5 MINUTE) and "'.$utcEndDate.'" and
                  list.IntervalSetTypeName = "IntervalSet" and
                  biss.IntervalSetBaseDate = liss.IntervalSetBaseDate and
                  liss.IntervalSetTypeID = list.IntervalSetTypeID and
                
                  bist.IntervalSetTypeName = if(pc.IsGenerator=0,"AdjustedBaselineSet","BaselineSet") and
                  biss.IntervalSetTypeID = bist.IntervalSetTypeID and
                
                  liss.PointObjectID = pc.ObjectID and
                  liss.ChannelID = pc.ChannelID and
                  biss.PointObjectID = liss.PointObjectID and
                  biss.ChannelID = liss.ChannelID and
                
                  li.IntervalSetID = liss.IntervalSetID and
                  bi.IntervalSetID = biss.IntervalSetID and
                  bi.IntervalDate = li.IntervalDate and
                  p.ObjectID = pc.ObjectID and
                  pcppp.PointObjectID = p.ObjectID and
                  pcppp.ParticipationTypeID = pt.ParticipationTypeID
            ';
            //$this->preDebugger($FCASQL);
                      
            $FCAResult = mysql_query($FCASQL, $this->sqlConnection());

            if(mysql_num_rows($FCAResult) > 0) 
            {
                $inx = 0;
                $FCARow = mysql_fetch_object($FCAResult);
                
                    $particulars['FCA']['performance'] = $FCARow->Performance;
                    $particulars['FCA']['pcr'] = $FCARow->PCR;
                
                $inx++;
            }
            else
            {
                return false;
            }
            
            return $particulars;
            
        }
    }
}
?>

