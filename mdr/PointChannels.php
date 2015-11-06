<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
/**
 * PointChannels
 *
 * @package IEMS 
 * @name Point Channels
 * @author Kevin L. Keegan, Conservation Resource Solutions, Inc.
 * @copyright 2008
 * @version 2.0
 * @access public
 */
class PointChannels extends CAO
{
    private $p_userId;
    private $p_domainId;

    private $p_pointChannel = array();
    private $p_meterPoint = array();
    private $p_pcMap = array();

    private $p_length;

    private $p_resources;

    private $p_participationTypeList; //mcb 2010.05.18
    
  /**
   * PointChannels::length()
   *
   * @return
   */
    function length()
    {
        return $this->p_length;
    }

    function item($index)
    {
        return $this->p_pointChannel[$index];
    }

    function pointChannel($objectId, $channelId)
    {
        return $this->p_pointChannel[$this->p_pcMap[$objectId][$channelId]];
    }

    function resources()
    {
        return $this->p_resources;
    }

    function meterPoint($index)
    {
        return $this->p_meterPoint[$index];
    }

    function participationPrograms()
    {
        return $this->p_participationPrograms;
    }

    function participationTypeList() //mcb 2010.05.18
    {
        return $this->p_participationTypeList;
    }

  /**
   * PointChannels::__construct()
   *
   * @return
   */
    function __construct()
    {
        parent::__construct();

        $this->p_length = 0;
    }

    function __destruct()
    {
        parent::__destruct();
    }
    
  /**
   * PointChannels::Load()
   *
   * @param mixed $userId
   * @param mixed $domainId
   * @return
   */
    function Load(
        $userId, 
        $domainId, 
        $pointType = '', 
        $participationType = '', 
        $siftByResource = false,
        $rapMonth = false,
        $rapYear = false)
    {
        //echo "In PointChannels->Load(...)...<br>\n";
        
        $participationTypeClause = $participationType != '' ? " and pat.ParticipationTypeID = $participationType " : '';
        $pointTypeClause = $pointType != '' ? " and ptypes.PointTypeName = '$pointType' " : " and not ptypes.PointTypeName = 'Resource' ";        
        $resourceSort = $siftByResource ? " rappc.ChannelDescription, " : '';
        $resourceClause = $siftByResource ? " and rap.AssetObjectID = pc.ObjectID\n and rap.AssetChannelID = pc.ChannelID\n and rap.ResourceObjectID = rappc.ObjectID and rpc.ObjectID = rap.ResourceObjectID \n " : '';        
        $resourceTable = $siftByResource ? ", mdr.t_resourceassetprofiles rap, mdr.t_pointchannels rappc, mdr.t_pointchannels rpc "  : '';
        $resourceFields = $siftByResource ? ", rappc.ChannelDescription ResourceDescription, rap.AssetObjectID,  rap.AssetChannelID, rap.ResourceObjectID, rpc.AssetIdentifier ResourceIdentifier" : '';
        $rapDateClause = $rapMonth ? " and rap.EffectiveMonth = '$rapMonth' and rap.EffectiveYear = '$rapYear'" : '';

        //print $rapDateClause;

        $this->p_userId = $userId;
        $this->p_domainId = $domainId;
         
        $sql = "
            SELECT DISTINCT
                pc.*,
                pn.ReadTimeOffset,
                pn.ReadInterval,
                pn.PointTypeID,
                pnt.PointTypeName,
                pn.IsNetAsset,
                t.TimeZoneID,
                t.TimeZoneName,
                t.TimeZoneDescription,
                t.IsDstActive,
                t.StdAbbreviation,
                t.StdDescription,
                t.StdOffset,
                t.StdMonth,
                t.StdWeek,
                t.StdDay,
                t.StdHour,
                t.DstAbbreviation,
                t.DstDescription,
                t.DstOffset,
                t.DstMonth,
                t.DstWeek,
                t.DstDay,
                t.DstHour,
                zo.ObjectDescription Zone,
                pat.ParticipationTypeID,
                pat.ParticipationTypeDescription,
                pcppp.CommittedReduction,
                if(pcppp.RetirementDate < '2010-06-01 00:00:00', null, pcppp.RetirementDate) RetirementDate, -- added 2011-12-19
                rp.PriceID RealTimePriceID,
                rp.PriceDescription RealTimePriceDescription,
                hp.PriceID HourlyPriceID,
                hp.PriceDescription HourlyPriceDescription $resourceFields
            FROM
                mdr.t_objectxrefs dgox,
                mdr.t_objecttypes got,
                mdr.t_objects go,
                mdr.t_objectxrefs ugox,
                mdr.t_actorprivilegexrefs gpx,
                mdr.t_actorprivilegexrefs dpx,
                mdr.t_privileges p,
                mdr.t_privilegetypes pt,
                mdr.t_points pn,
                mdr.t_pointtypes pnt,
                mdr.t_timezones t,
                mdr.t_objects po,
                mdr.t_pointchannels pc,
                mdr.t_objectxrefs pzox,
                mdr.t_objects zo,
                mdr.t_zones z,
                mdr.t_pointchannelprogramparticipationprofiles pcppp,
                mdr.t_participationtypes pat,
                mdr.t_pricelocations pl,
                mdr.t_pricecomponents pco,
                mdr.t_pricetypes rpt,
                mdr.t_pricetypes hpt,
                mdr.t_prices rp,
                mdr.t_prices hp,
                mdr.t_pointtypes ptypes $resourceTable
            WHERE
                got.ObjectTypeName = 'Group' and
                go.ObjectTypeID = got.ObjectTypeID and
                dgox.ChildObjectID = go.ObjectID and
                dgox.ParentObjectID = $this->p_domainId and
                ugox.ParentObjectID = dgox.ChildObjectID and
                ugox.ChildObjectID = $this->p_userId  and
                gpx.ObjectID = ugox.ParentObjectID and
                dpx.ObjectID = dgox.ParentObjectID and
                gpx.PrivilegeID = dpx.PrivilegeID and
                p.PrivilegeID = gpx.PrivilegeID and
                pt.PrivilegeTypeID = p.PrivilegeTypeID and
                pt.PrivilegeTypeName = 'Read' and
                po.ObjectID = p.ObjectID and
                pn.ObjectID = po.ObjectID and
                t.TimeZoneID = pn.TimeZoneID and
                po.IsInactive = 0 and
                pn.IsEnabled = 1 and
                pnt.PointTypeID = pn.PointTypeID and
                pc.ObjectID = pn.ObjectID and
                pc.IsEnabled = 1 and
                pc.IsPlotable = 1 and
                pzox.ChildObjectID = pn.ObjectID and
                zo.ObjectID = pzox.ParentObjectID and
                z.ObjectID = zo.ObjectID and                
                pcppp.PointObjectID = pc.ObjectID and
                pcppp.ChannelID = pc.ChannelID and
                pat.ParticipationTypeID = pcppp.ParticipationTypeID and
                pco.PriceComponentName = 'LBMP' and
                pl.ZoneObjectID = z.ObjectID and
                rpt.PriceTypeName = 'RealTimePrice' and
                rp.PriceTypeID = rpt.PriceTypeID and
                rp.PriceLocationID = pl.PriceLocationID and
                rp.PriceComponentID = pco.PriceComponentID and
                hpt.PriceTypeName = 'HourlyPrice' and
                hp.PriceTypeID = hpt.PriceTypeID and
                hp.PriceLocationID = pl.PriceLocationID and
                hp.PriceComponentID = pco.PriceComponentID and
                ptypes.PointTypeID = pn.PointTypeID $participationTypeClause $resourceClause $pointTypeClause $rapDateClause 
            ORDER BY
                $resourceSort
                pc.ChannelDescription
            ";
       
        //$this->preDebugger($sql);
        $result = mysql_query($sql, $this->sqlConnection());
        
        //echo mysql_num_rows($result);

        if(mysql_num_rows($result) > 0)
        {
            while ($row = mysql_fetch_array($result)) 
            {
                //$this->preDebugger($row,'red');
    
                if($siftByResource) //splitting this out because we just don't need all of this re-rendered just to get to the object id and name
                {
                    $this->p_resources[$row['ResourceObjectID']]['description'] = $row['ResourceDescription'];
                    $this->p_resources[$row['ResourceObjectID']]['identifier'] = $row['ResourceIdentifier'];
                    $this->p_resources[$row['ResourceObjectID']]['assets'][$row["AssetObjectID"].':'.$row["AssetChannelID"]]['id'] = $row["ObjectID"];
                    $this->p_resources[$row['ResourceObjectID']]['assets'][$row["AssetObjectID"].':'.$row["AssetChannelID"]]['channelId'] = $row["ChannelID"];
                    $this->p_resources[$row['ResourceObjectID']]['assets'][$row["AssetObjectID"].':'.$row["AssetChannelID"]]['description'] = $row["ChannelDescription"];
                    $this->p_resources[$row['ResourceObjectID']]['assets'][$row["AssetObjectID"].':'.$row["AssetChannelID"]]['programId'] = $row["ParticipationTypeID"];
                    $this->p_resources[$row['ResourceObjectID']]['assets'][$row["AssetObjectID"].':'.$row["AssetChannelID"]]['programDescription'] = $row["ParticipationTypeDescription"];
                    $this->p_resources[$row['ResourceObjectID']]['assets'][$row["AssetObjectID"].':'.$row["AssetChannelID"]]['assetIdentifier'] = $row["AssetIdentifier"];
                }
                else
                {
                    $inx = $this->p_length++;  //inx has no meaning in the new context -- just using it to hold things together right now.
                    
                    $uom = new UnitOfMeasure();
                    $uom->Load($row["UnitOfMeasureID"]);
        
                    $dluom = new UnitOfMeasure();
                    $dluom->Load($row["DrawLimitUnitOfMeasureID"]);
                    
                    $pointChannel = new PointChannel($row["ObjectID"],
                                                     $row["ChannelID"],
                                                     $row["ChannelName"],
                                                     $row["ChannelDescription"],
                                                     $uom,
                                                     $row["PulseConversionFactor"],
                                                     $row["DrawLimit"],
                                                     $dluom,
                                                     $row["IsGenerator"],
                                                     $row["AssetIdentifier"],
                                                     $row["RetirementDate"],
                                                     $row["ParticipationTypeID"],
                                                     $row["ParticipationTypeDescription"],
                                                     $row["CommittedReduction"]);
                                                     
                    
                        $this->p_pointChannel[$inx] = clone $pointChannel;
        
                    $this->p_pcMap[$pointChannel->objectId()][$pointChannel->channelId()] = $inx;
                    
                    //$this->preDebugger($this->p_pointChannel[$inx]);
        /*
                    print '<pre style="color: red;">';
                    echo "pointChannel='" . $this->p_pointChannel[$inx]->objectId() . "', '" . $this->p_pointChannel[$inx]->channelId() . "'<br>\n";
                    echo "index='" . $this->p_pcMap[$pointChannel->objectId()][$pointChannel->channelId()] . "'<br>\n";
                    print '</pre>';
        */
                    if (!isset($this->p_meterPoint[$pointChannel->objectId()])) 
                    {                
                        $this->p_meterPoint[$pointChannel->objectId()] = new MeterPoint();

                        $this->p_meterPoint[$pointChannel->objectId()]->id($pointChannel->objectId());
        
                        $this->p_meterPoint[$pointChannel->objectId()]->readTimeOffset($row["ReadTimeOffset"]);
                        $this->p_meterPoint[$pointChannel->objectId()]->readInterval($row["ReadInterval"]);

                        $pointType = new PointType($row["PointTypeID"], $row["PointTypeName"]);                        
                        $this->p_meterPoint[$pointChannel->objectId()]->pointType($pointType);
                        $this->p_meterPoint[$pointChannel->objectId()]->isNetAsset($row["IsNetAsset"]);
                        $this->p_meterPoint[$pointChannel->objectId()]->zone($row["Zone"]);
                        //mcb 2012-05-24 $this->p_meterPoint[$pointChannel->objectId()]->committedReduction($row["CommittedReduction"]);                    
                        //mcb 2012-05-23 $this->p_meterPoint[$pointChannel->objectId()]->participationTypeID($row["ParticipationTypeID"]);
                        //mcb 2012-05-23 $this->p_meterPoint[$pointChannel->objectId()]->participationTypeDescription($row["ParticipationTypeDescription"]);                    
                        $this->p_meterPoint[$pointChannel->objectId()]->displayPriceId($row["RealTimePriceID"]);
                        $this->p_meterPoint[$pointChannel->objectId()]->displayPriceDescription($row["RealTimePriceDescription"]);
                        $this->p_meterPoint[$pointChannel->objectId()]->settlementPriceId($row["HourlyPriceID"]);
                        $this->p_meterPoint[$pointChannel->objectId()]->settlementPriceDescription($row["HourlyPriceDescription"]); 
    
                        $this->p_participationTypeList[$row["ParticipationTypeID"]] = $row["ParticipationTypeDescription"];
        
                        $timeZone = new TimeZone();
                        $timeZone->id($row["TimeZoneID"]);
                        $timeZone->name($row["TimeZoneName"]);
                        $timeZone->description($row["TimeZoneDescription"]);
                        $timeZone->isDstActive($row["IsDstActive"]);
                        $timeZone->stdAbbreviation($row["StdAbbreviation"]);
                        $timeZone->stdDescription($row["StdDescription"]);
                        $timeZone->stdOffset($row["StdOffset"]);
                        $timeZone->stdMonth($row["StdMonth"]);
                        $timeZone->stdWeek($row["StdWeek"]);
                        $timeZone->stdDay($row["StdDay"]);
                        $timeZone->stdHour($row["StdHour"]);
                        $timeZone->dstAbbreviation($row["DstAbbreviation"]);
                        $timeZone->dstDescription($row["DstDescription"]);
                        $timeZone->dstOffset($row["DstOffset"]);
                        $timeZone->dstMonth($row["DstMonth"]);
                        $timeZone->dstWeek($row["DstWeek"]);
                        $timeZone->dstDay($row["DstDay"]);
                        $timeZone->dstHour($row["DstHour"]);
                        $this->p_meterPoint[$pointChannel->objectId()]->timeZone($timeZone);
        
                    }
        
                    $this->p_pointChannel[$inx]->meterPoint($this->p_meterPoint[$pointChannel->objectId()]);
                }
                //print_r($this->p_resources);
            }// end while
        }else{
            $this->p_resources = false;
        }//end if
    } // end load
}
?>
