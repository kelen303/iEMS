<?php

/** 
 *  
 * modified by mcb 09 apr 2010 
 * removed manipulation to mysql date format as 
 * we're ending correctly defined dates in. 
 *  
**/
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
class EventPerformanceSummary extends CAO {
    
    private $p_domainID;
    private $p_eventDate;

    private $p_reportProgram = array();
    private $p_reportZone = array();
    private $p_reportProgramDetails = array();
    private $p_reportZoneDetails = array();
    private $p_calledPrograms = array();
    private $p_calledZones = array();

    function calledPrograms() 
    {
        return $this->p_calledPrograms;
    }

    function calledZones()
    {
        return $this->p_calledZones;
    }

    function reportProgram()
    {
        return $this->p_reportProgram;
    }

    function reportZone()
    {
        return $this->p_reportZone;
    }

    function reportProgramDetails()
    {
        return $this->p_reportProgramDetails;
    }

    function reportZoneDetails()
    {
        return $this->p_reportZoneDetails;
    }

    function allInvolvedAssets()
    {
        return $this->p_allInvolvedAssets;
    }

    

    function __construct($domainID, $eventDate) {

        parent::__construct();

        $this->p_domainID = $domainID;
        $this->p_eventDate = $eventDate;

    }

    function __destruct() {

        parent::__destruct();

    }

    function Get() {
        
$dispatch = "
select
  pt.ParticipationTypeDescription \"Called Program\",
  zo.ObjectDescription \"Zone\",
  rap.ResourceObjectID \"Resource OID\",
  rap.ResourceChannelID \"Resource CID\",
  rpc.ChannelDescription \"Resource\",
  rpcppp.CommittedReduction \"Resource CR\",
  rap.AssetObjectID,
  rap.AssetChannelID,
  apc.ChannelDescription \"Asset\",
  apc.AssetIdentifier \"Asset ID\",
  pcppp.IsSendable \"isReportingAsset\",
  pcppp.CommittedReduction,
  b.AdjustmentValue \"Adjustment\",
  pi.IntervalDate \"PerformanceDate\",
  pi.IntervalValue \"PerformanceValue\",
  ppi.IntervalValue \"PercentagePerformanceValue\",
  ppiss.CreatedDate \"Created\",
  ppiss.UpdatedDate \"Updated\",
  npc.DoNotDispatch,
  npc.DispatchTime,
  npc.EffectiveTime,
  npc.RestorationTime,
  npc.IsDispatched,
  npc.IsRestored
from
  t_intervalsets piss,
  t_intervalsettypes pit,
  t_intervals pi,
  t_intervalsets ppiss,
  t_intervalsettypes ppit,
  t_intervals ppi,
  t_objects do,
  t_objecttypes dot,
  t_actorprivilegexrefs apx,
  t_privileges pri,
  t_resourceassetprofiles rap,
  t_pointchannels rpc,
  t_pointchannels apc,
  t_objectxrefs zpox,
  t_dispatchzones z,
  t_objects zo,
  t_pointchannelprogramparticipationprofiles pcppp,
  t_pointchannelprogramparticipationprofiles rpcppp,
  t_participationtypes pt,
  t_baselines b,
  t_intervalsets abiss,
  t_intervalsettypes aist,
  t_notifications n,
  t_notificationpointchannels npc
where
  -- find the domain involved and ensure that it is a domain...
  dot.ObjectTypeName = 'Domain' and
  do.ObjectTypeID = dot.ObjectTypeID and
  do.ObjectID = " . $this->p_domainID . " and

  -- Look-up the privileges owned by the domain...
  apx.ObjectID = do.ObjectID and
  pri.PrivilegeID = apx.PrivilegeID and

  -- Look-up the point channels owned by the domain that are in an event this date...
  npc.ObjectID = pri.ObjectID and
  n.NotificationID = npc.NotificationID and
  n.StartDate between '" . $this->p_eventDate . "' and date_add('" . $this->p_eventDate . "', INTERVAL \"23:59:59\" HOUR_SECOND) and

  -- Look-up the performance interval set for each point channel found above...
  pit.IntervalSetTypeName = 'PerformanceIntervalSet' and
  piss.IntervalSetTypeID = pit.IntervalsetTypeID and
  piss.IntervalSetBaseDate = date(n.StartDate) and
  piss.PointObjectID = npc.ObjectID and
  piss.ChannelID = npc.ChannelID and

  -- Look-up the performance intervals...
  pi.IntervalSetID = piss.IntervalSetID and

  -- Look-up the % performance interval set for each point channel found above...
  ppit.IntervalSetTypeName = 'PercentagePerformanceIntervalSet' and
  ppiss.IntervalSetTypeID = ppit.IntervalsetTypeID and
  ppiss.IntervalSetBaseDate = date(n.StartDate) and
  ppiss.PointObjectID = npc.ObjectID and
  ppiss.ChannelID = npc.ChannelID and

  -- Look-up the % performance intervals...
  ppi.IntervalSetID = ppiss.IntervalSetID and

  -- Synchronize the two sets of intervals...
  pi.IntervalDate = ppi.IntervalDate and

  -- Cross-reference the point channels to resources...
  rap.EffectiveYear = year(n.StartDate) and
  rap.EffectiveMonth = month(n.StartDate) and
  rap.AssetObjectID = npc.ObjectID and
  rap.AssetChannelID = npc.ChannelID and

  -- Look-up the asset identifier...
  apc.ObjectID = rap.AssetObjectID and
  apc.ChannelID = rap.AssetChannelID and
  apc.IsGenerator = 0 and

  -- Look-up the resource description...
  rpc.ObjectID = rap.ResourceObjectID and
  rpc.ChannelID = rap.ResourceChannelID and

  -- Cross-reference the zone...
  zpox.ChildObjectID = rap.AssetObjectID and
  z.ObjectID = zpox.ParentObjectID and
  zo.ObjectID = z.ObjectID and

  -- Cross-reference the participation type...
  pcppp.PointObjectID = rap.AssetObjectID and
  pcppp.ChannelID = rap.AssetChannelID and
  pt.ParticipationTypeID = pcppp.ParticipationTypeID and
  pt.ParticipationTypeName in ('Thirty_Minute_Demand_Response_9', 'Thirty_Minute_Demand_Response_12') and

   -- Lock-down the resource pcppp record....
   rpcppp.PointObjectID = rpc.ObjectID and
   rpcppp.ChannelID = rpc.ChannelID and
   rpcppp.ParticipationTypeID = pcppp.ParticipationTypeID and
   rpcppp.ProgramObjectID = pcppp.ProgramObjectID and

  -- Look-up the adjusment value...
  aist.IntervalSetTypeName = 'AdjustedBaselineSet' and
  abiss.IntervalSetTypeID = aist.IntervalSetTypeID and
  abiss.IntervalSetBaseDate = date(n.StartDate) and
  abiss.PointObjectID = rap.AssetObjectID and
  abiss.ChannelID = rap.AssetChannelID and
  b.IntervalSetID = abiss.IntervalSetID and

  -- Fetch the latest available performance intervals...
  pi.IntervalDate = piss.MaximumIntervalDate and
  ppi.IntervalDate = ppiss.MaximumIntervalDate

-- Break up the look-up by asset...
group by
  rap.AssetObjectID,
  rap.AssetChannelID

union
select
  pt.ParticipationTypeDescription \"Called Program\",
  zo.ObjectDescription \"Zone\",
  rap.ResourceObjectID \"Resource OID\",
  rap.ResourceChannelID \"Resource CID\",
  rpc.ChannelDescription \"Resource\",
  rpcppp.CommittedReduction \"Resource CR\",
  rap.AssetObjectID,
  rap.AssetChannelID,
  apc.ChannelDescription \"Asset\",
  apc.AssetIdentifier \"Asset ID\",
  pcppp.IsSendable \"isReportingAsset\",
  pcppp.CommittedReduction,
  0.0 \"Adjustment\",
  pi.IntervalDate \"PerformanceDate\",
  pi.IntervalValue \"PerformanceValue\",
  ppi.IntervalValue \"PercentagePerformanceValue\",
  ppiss.CreatedDate \"Created\",
  ppiss.UpdatedDate \"Updated\",
  npc.DoNotDispatch,
  npc.DispatchTime,
  npc.EffectiveTime,
  npc.RestorationTime,
  npc.IsDispatched,
  npc.IsRestored
from
  t_intervalsets piss,
  t_intervalsettypes pit,
  t_intervals pi,
  t_intervalsets ppiss,
  t_intervalsettypes ppit,
  t_intervals ppi,
  t_objects do,
  t_objecttypes dot,
  t_actorprivilegexrefs apx,
  t_privileges pri,
  t_resourceassetprofiles rap,
  t_pointchannels rpc,
  t_pointchannels apc,
  t_objectxrefs zpox,
  t_dispatchzones z,
  t_objects zo,
  t_pointchannelprogramparticipationprofiles pcppp,
  t_pointchannelprogramparticipationprofiles rpcppp,
  t_participationtypes pt,
  t_notifications n,
  t_notificationpointchannels npc
where
  -- find the domain involved and ensure that it is a domain...
  dot.ObjectTypeName = 'Domain' and
  do.ObjectTypeID = dot.ObjectTypeID and
  do.ObjectID = " . $this->p_domainID . " and

  -- Look-up the privileges owned by the domain...
  apx.ObjectID = do.ObjectID and
  pri.PrivilegeID = apx.PrivilegeID and

  -- Look-up the point channels owned by the domain that are in an event this date...
  npc.ObjectID = pri.ObjectID and
  n.NotificationID = npc.NotificationID and
  n.StartDate between '" . $this->p_eventDate . "' and date_add('" . $this->p_eventDate . "', INTERVAL \"23:59:59\" HOUR_SECOND) and

  -- Look-up the performance interval set for each point channel found above...
  pit.IntervalSetTypeName = 'PerformanceIntervalSet' and
  piss.IntervalSetTypeID = pit.IntervalsetTypeID and
  piss.IntervalSetBaseDate = date(n.StartDate) and
  piss.PointObjectID = npc.ObjectID and
  piss.ChannelID = npc.ChannelID and

  -- Look-up the performance intervals...
  pi.IntervalSetID = piss.IntervalSetID and

  -- Look-up the % performance interval set for each point channel found above...
  ppit.IntervalSetTypeName = 'PercentagePerformanceIntervalSet' and
  ppiss.IntervalSetTypeID = ppit.IntervalsetTypeID and
  ppiss.IntervalSetBaseDate = date(n.StartDate) and
  ppiss.PointObjectID = npc.ObjectID and
  ppiss.ChannelID = npc.ChannelID and

  -- Look-up the % performance intervals...
  ppi.IntervalSetID = ppiss.IntervalSetID and

  -- Synchronize the two sets of intervals...
  pi.IntervalDate = ppi.IntervalDate and

  -- Cross-reference the point channels to resources...
  rap.EffectiveYear = year(n.StartDate) and
  rap.EffectiveMonth = month(n.StartDate) and
  rap.AssetObjectID = npc.ObjectID and
  rap.AssetChannelID = npc.ChannelID and

  -- Look-up the asset identifier...
  apc.ObjectID = rap.AssetObjectID and
  apc.ChannelID = rap.AssetChannelID and
  apc.IsGenerator = 1 and

  -- Look-up the resource description...
  rpc.ObjectID = rap.ResourceObjectID and
  rpc.ChannelID = rap.ResourceChannelID and

  -- Cross-reference the zone...
  zpox.ChildObjectID = rap.AssetObjectID and
  z.ObjectID = zpox.ParentObjectID and
  zo.ObjectID = z.ObjectID and

  -- Cross-reference the participation type...
  pcppp.PointObjectID = rap.AssetObjectID and
  pcppp.ChannelID = rap.AssetChannelID and
  pt.ParticipationTypeID = pcppp.ParticipationTypeID and
  pt.ParticipationTypeName in ('Thirty_Minute_Demand_Response_9', 'Thirty_Minute_Demand_Response_12') and

   -- Lock-down the resource pcppp record....
   rpcppp.PointObjectID = rpc.ObjectID and
   rpcppp.ChannelID = rpc.ChannelID and
   rpcppp.ParticipationTypeID = pcppp.ParticipationTypeID and
   rpcppp.ProgramObjectID = pcppp.ProgramObjectID and


  -- Fetch the latest available performance intervals...
  pi.IntervalDate = piss.MaximumIntervalDate and
  ppi.IntervalDate = ppiss.MaximumIntervalDate

-- Break up the look-up by asset...
group by
  rap.AssetObjectID,
  rap.AssetChannelID

order by
  Resource,
  Asset
";
/**======================================================================== */
$nonDispatch = "
select
  pt.ParticipationTypeDescription \"Called Program\",
  zo.ObjectDescription \"Zone\",
  rap.ResourceObjectID \"Resource OID\",
  rap.ResourceChannelID \"Resource CID\",
  rpc.ChannelDescription \"Resource\",
  rpcppp.CommittedReduction \"Resource CR\",
  rap.AssetObjectID,
  rap.AssetChannelID,
  apc.ChannelDescription \"Asset\",
  apc.AssetIdentifier \"Asset ID\",
  pcppp.IsSendable \"isReportingAsset\",
  pcppp.CommittedReduction,
  b.AdjustmentValue \"Adjustment\",
  pi.IntervalDate \"PerformanceDate\",
  pi.IntervalValue \"PerformanceValue\",
  ppi.IntervalValue \"PercentagePerformanceValue\",
  ppiss.CreatedDate \"Created\",
  ppiss.UpdatedDate \"Updated\",
  npc.DoNotDispatch,
  npc.DispatchTime,
  npc.EffectiveTime,
  npc.RestorationTime,
  npc.IsDispatched,
  npc.IsRestored
from
  t_intervalsets piss,
  t_intervalsettypes pit,
  t_intervals pi,
  t_intervalsets ppiss,
  t_intervalsettypes ppit,
  t_intervals ppi,
  t_objects do,
  t_objecttypes dot,
  t_actorprivilegexrefs apx,
  t_privileges pri,
  t_resourceassetprofiles rap,
  t_pointchannels rpc,
  t_pointchannels apc,
  t_objectxrefs zpox,
  t_zones z,
  t_objects zo,
  t_pointchannelprogramparticipationprofiles pcppp,
  t_pointchannelprogramparticipationprofiles rpcppp,
  t_participationtypes pt,
  t_baselines b,
  t_intervalsets abiss,
  t_intervalsettypes aist,
  t_notifications n,
  t_notificationpointchannels npc
where
  -- find the domain involved and ensure that it is a domain...
  dot.ObjectTypeName = 'Domain' and
  do.ObjectTypeID = dot.ObjectTypeID and
  do.ObjectID = " . $this->p_domainID . " and

  -- Look-up the privileges owned by the domain...
  apx.ObjectID = do.ObjectID and
  pri.PrivilegeID = apx.PrivilegeID and

  -- Look-up the point channels owned by the domain that are in an event this date...
  npc.ObjectID = pri.ObjectID and
  n.NotificationID = npc.NotificationID and
  n.StartDate between '" . $this->p_eventDate . "' and date_add('" . $this->p_eventDate . "', INTERVAL \"23:59:59\" HOUR_SECOND) and

  -- Look-up the performance interval set for each point channel found above...
  pit.IntervalSetTypeName = 'PerformanceIntervalSet' and
  piss.IntervalSetTypeID = pit.IntervalsetTypeID and
  piss.IntervalSetBaseDate = date(n.StartDate) and
  piss.PointObjectID = npc.ObjectID and
  piss.ChannelID = npc.ChannelID and

  -- Look-up the performance intervals...
  pi.IntervalSetID = piss.IntervalSetID and

  -- Look-up the % performance interval set for each point channel found above...
  ppit.IntervalSetTypeName = 'PercentagePerformanceIntervalSet' and
  ppiss.IntervalSetTypeID = ppit.IntervalsetTypeID and
  ppiss.IntervalSetBaseDate = date(n.StartDate) and
  ppiss.PointObjectID = npc.ObjectID and
  ppiss.ChannelID = npc.ChannelID and

  -- Look-up the % performance intervals...
  ppi.IntervalSetID = ppiss.IntervalSetID and

  -- Synchronize the two sets of intervals...
  pi.IntervalDate = ppi.IntervalDate and

  -- Cross-reference the point channels to resources...
  rap.EffectiveYear = year(n.StartDate) and
  rap.EffectiveMonth = month(n.StartDate) and
  rap.AssetObjectID = npc.ObjectID and
  rap.AssetChannelID = npc.ChannelID and

  -- Look-up the asset identifier...
  apc.ObjectID = rap.AssetObjectID and
  apc.ChannelID = rap.AssetChannelID and
  apc.IsGenerator = 0 and

  -- Look-up the resource description...
  rpc.ObjectID = rap.ResourceObjectID and
  rpc.ChannelID = rap.ResourceChannelID and

  -- Cross-reference the zone...
  zpox.ChildObjectID = rap.AssetObjectID and
  z.ObjectID = zpox.ParentObjectID and
  zo.ObjectID = z.ObjectID and

  -- Cross-reference the participation type...
  pcppp.PointObjectID = rap.AssetObjectID and
  pcppp.ChannelID = rap.AssetChannelID and
  pt.ParticipationTypeID = pcppp.ParticipationTypeID and
  pt.ParticipationTypeName in ('Thirty_Minute_Demand_Response_9', 'Thirty_Minute_Demand_Response_12') and

   -- Lock-down the resource pcppp record....
   rpcppp.PointObjectID = rpc.ObjectID and
   rpcppp.ChannelID = rpc.ChannelID and
   rpcppp.ParticipationTypeID = pcppp.ParticipationTypeID and
   rpcppp.ProgramObjectID = pcppp.ProgramObjectID and


  -- Look-up the adjusment value...
  aist.IntervalSetTypeName = 'AdjustedBaselineSet' and
  abiss.IntervalSetTypeID = aist.IntervalSetTypeID and
  abiss.IntervalSetBaseDate = date(n.StartDate) and
  abiss.PointObjectID = rap.AssetObjectID and
  abiss.ChannelID = rap.AssetChannelID and
  b.IntervalSetID = abiss.IntervalSetID and

  -- Fetch the latest available performance intervals...
  pi.IntervalDate = piss.MaximumIntervalDate and
  ppi.IntervalDate = ppiss.MaximumIntervalDate

-- Break up the look-up by asset...
group by
  rap.AssetObjectID,
  rap.AssetChannelID

union
select
  pt.ParticipationTypeDescription \"Called Program\",
  zo.ObjectDescription \"Zone\",
  rap.ResourceObjectID \"Resource OID\",
  rap.ResourceChannelID \"Resource CID\",
  rpc.ChannelDescription \"Resource\",
  rpcppp.CommittedReduction \"Resource CR\",
  rap.AssetObjectID,
  rap.AssetChannelID,
  apc.ChannelDescription \"Asset\",
  apc.AssetIdentifier \"Asset ID\",
  pcppp.IsSendable \"isReportingAsset\",
  pcppp.CommittedReduction,
  0.0 \"Adjustment\",
  pi.IntervalDate \"PerformanceDate\",
  pi.IntervalValue \"PerformanceValue\",
  ppi.IntervalValue \"PercentagePerformanceValue\",
  ppiss.CreatedDate \"Created\",
  ppiss.UpdatedDate \"Updated\",
  npc.DoNotDispatch,
  npc.DispatchTime,
  npc.EffectiveTime,
  npc.RestorationTime,
  npc.IsDispatched,
  npc.IsRestored
from
  t_intervalsets piss,
  t_intervalsettypes pit,
  t_intervals pi,
  t_intervalsets ppiss,
  t_intervalsettypes ppit,
  t_intervals ppi,
  t_objects do,
  t_objecttypes dot,
  t_actorprivilegexrefs apx,
  t_privileges pri,
  t_resourceassetprofiles rap,
  t_pointchannels rpc,
  t_pointchannels apc,
  t_objectxrefs zpox,
  t_zones z,
  t_objects zo,
  t_pointchannelprogramparticipationprofiles pcppp,
  t_pointchannelprogramparticipationprofiles rpcppp,
  t_participationtypes pt,
  t_notifications n,
  t_notificationpointchannels npc
where
  -- find the domain involved and ensure that it is a domain...
  dot.ObjectTypeName = 'Domain' and
  do.ObjectTypeID = dot.ObjectTypeID and
  do.ObjectID = " . $this->p_domainID . " and

  -- Look-up the privileges owned by the domain...
  apx.ObjectID = do.ObjectID and
  pri.PrivilegeID = apx.PrivilegeID and

  -- Look-up the point channels owned by the domain that are in an event this date...
  npc.ObjectID = pri.ObjectID and
  n.NotificationID = npc.NotificationID and
  n.StartDate between '" . $this->p_eventDate . "' and date_add('" . $this->p_eventDate . "', INTERVAL \"23:59:59\" HOUR_SECOND) and

  -- Look-up the performance interval set for each point channel found above...
  pit.IntervalSetTypeName = 'PerformanceIntervalSet' and
  piss.IntervalSetTypeID = pit.IntervalsetTypeID and
  piss.IntervalSetBaseDate = date(n.StartDate) and
  piss.PointObjectID = npc.ObjectID and
  piss.ChannelID = npc.ChannelID and

  -- Look-up the performance intervals...
  pi.IntervalSetID = piss.IntervalSetID and

  -- Look-up the % performance interval set for each point channel found above...
  ppit.IntervalSetTypeName = 'PercentagePerformanceIntervalSet' and
  ppiss.IntervalSetTypeID = ppit.IntervalsetTypeID and
  ppiss.IntervalSetBaseDate = date(n.StartDate) and
  ppiss.PointObjectID = npc.ObjectID and
  ppiss.ChannelID = npc.ChannelID and

  -- Look-up the % performance intervals...
  ppi.IntervalSetID = ppiss.IntervalSetID and

  -- Synchronize the two sets of intervals...
  pi.IntervalDate = ppi.IntervalDate and

  -- Cross-reference the point channels to resources...
  rap.EffectiveYear = year(n.StartDate) and
  rap.EffectiveMonth = month(n.StartDate) and
  rap.AssetObjectID = npc.ObjectID and
  rap.AssetChannelID = npc.ChannelID and

  -- Look-up the asset identifier...
  apc.ObjectID = rap.AssetObjectID and
  apc.ChannelID = rap.AssetChannelID and
  apc.IsGenerator = 1 and

  -- Look-up the resource description...
  rpc.ObjectID = rap.ResourceObjectID and
  rpc.ChannelID = rap.ResourceChannelID and

  -- Cross-reference the zone...
  zpox.ChildObjectID = rap.AssetObjectID and
  z.ObjectID = zpox.ParentObjectID and
  zo.ObjectID = z.ObjectID and

  -- Cross-reference the participation type...
  pcppp.PointObjectID = rap.AssetObjectID and
  pcppp.ChannelID = rap.AssetChannelID and
  pt.ParticipationTypeID = pcppp.ParticipationTypeID and
  pt.ParticipationTypeName in ('Thirty_Minute_Demand_Response_9', 'Thirty_Minute_Demand_Response_12') and

   -- Lock-down the resource pcppp record....
   rpcppp.PointObjectID = rpc.ObjectID and
   rpcppp.ChannelID = rpc.ChannelID and
   rpcppp.ParticipationTypeID = pcppp.ParticipationTypeID and
   rpcppp.ProgramObjectID = pcppp.ProgramObjectID and

  -- Fetch the latest available performance intervals...
  pi.IntervalDate = piss.MaximumIntervalDate and
  ppi.IntervalDate = ppiss.MaximumIntervalDate

-- Break up the look-up by asset...
group by
  rap.AssetObjectID,
  rap.AssetChannelID

order by
  Resource,
  Asset
";

$sql = $this->p_eventDate < '2010-06-01' ? $nonDispatch : $dispatch;

if(defined('DEBUG')) $_SESSION['debugSQL'] = $sql;

$summarySql = "
    select
       pt.ParticipationTypeDescription \"Called Program\",
       zo.ObjectDescription \"Zone\",
       rap.ResourceObjectID \"Resource OID\",
       rap.ResourceChannelID \"Resource CID\",
       rpc.ChannelDescription \"Resource\",
       rpcppp.CommittedReduction \"Resource CR\",
       rap.AssetObjectID,
       rap.AssetChannelID,
       apc.ChannelDescription \"Asset\",
       apc.AssetIdentifier \"Asset ID\",
       pcppp.IsSendable \"isReportingAsset\",
       pcppp.CommittedReduction,
       npc.DoNotDispatch,
       npc.DispatchTime,
       npc.EffectiveTime,
       npc.RestorationTime,
       npc.IsDispatched,
       npc.IsRestored
    from
       t_objects do,
       t_objecttypes dot,
       t_actorprivilegexrefs apx,
       t_privileges pri,
       t_resourceassetprofiles rap,
       t_pointchannels rpc,
       t_pointchannels apc,
       t_objectxrefs zpox,
       t_dispatchzones z,
       t_objects zo,
       t_pointchannelprogramparticipationprofiles pcppp,
       t_pointchannelprogramparticipationprofiles rpcppp,
       t_participationtypes pt,
       t_notifications n,
       t_notificationpointchannels npc
    where
   -- find the domain involved and ensure that it is a domain...
       dot.ObjectTypeName = 'Domain' and
       do.ObjectTypeID = dot.ObjectTypeID and
       do.ObjectID = " . $this->p_domainID . " and
    
   -- Look-up the privileges owned by the domain...
       apx.ObjectID = do.ObjectID and
       pri.PrivilegeID = apx.PrivilegeID and
    
   -- Look-up the point channels owned by the domain that are in an event this date...
       npc.ObjectID = pri.ObjectID and
       n.NotificationID = npc.NotificationID and   
       n.StartDate between '" . $this->p_eventDate . "' and date_add('" . $this->p_eventDate . "', INTERVAL \"23:59:59\" HOUR_SECOND) and

   -- Cross-reference the point channels to resources...
       rap.EffectiveYear = year(n.StartDate) and
       rap.EffectiveMonth = month(n.StartDate) and
       rap.AssetObjectID = npc.ObjectID and
       rap.AssetChannelID = npc.ChannelID and
    
   -- Look-up the asset identifier...
       apc.ObjectID = rap.AssetObjectID and
       apc.ChannelID = rap.AssetChannelID and
    
   -- Look-up the resource description...
       rpc.ObjectID = rap.ResourceObjectID and
       rpc.ChannelID = rap.ResourceChannelID and
    
   -- Cross-reference the zone...
       zpox.ChildObjectID = rap.AssetObjectID and
       z.ObjectID = zpox.ParentObjectID and
       zo.ObjectID = z.ObjectID and
    
   -- Cross-reference the participation type...
       pcppp.PointObjectID = rap.AssetObjectID and
       pcppp.ChannelID = rap.AssetChannelID and
       pt.ParticipationTypeID = pcppp.ParticipationTypeID and
       pt.ParticipationTypeName in ('Thirty_Minute_Demand_Response_9','Thirty_Minute_Demand_Response_12') and
    
    -- Lock-down the resource pcppp record....
        rpcppp.PointObjectID = rpc.ObjectID and
        rpcppp.ChannelID = rpc.ChannelID and
        rpcppp.ParticipationTypeID = pcppp.ParticipationTypeID and
        rpcppp.ProgramObjectID = pcppp.ProgramObjectID
    group by
       rap.AssetObjectID,
       rap.AssetChannelID
    order by
       Resource,
       Asset
";

//$this->preDebugger($summarySql,'orange');
//$this->preDebugger($this->p_eventDate);
        $result = mysql_query($sql, $this->sqlConnection());
        $summaryResult = mysql_query($summarySql, $this->sqlConnection());


        $this->p_reportProgram = array();
        $this->p_reportZone = array();
        $this->p_reportProgramDetails = array();
        $this->p_reportZoneDetails = array();
        $this->p_calledPrograms = array();
        $this->p_calledZones = array();

        $calledZonesByProgram = array();

        $this->p_allInvolvedAssets['calledPrograms'] = array();
        $this->p_allInvolvedAssets['calledZones'] = array();

        $sInx = 0;
        while ($summaryRow =  mysql_fetch_array($summaryResult)) {
            // TODO: Integrate this into the EventPerformanceSummaryLineItem object
            //$this->preDebugger($summaryRow);
            /*
              epsReportZoneDetails
              calledZones*/            
            if(!in_array($summaryRow['Called Program'],$this->p_allInvolvedAssets['calledPrograms']))
                $this->p_allInvolvedAssets['calledPrograms'][] = $summaryRow['Called Program'];

            if(!in_array($summaryRow['Zone'],$this->p_allInvolvedAssets['calledZones'][$summaryRow['Called Program']]))
                $this->p_allInvolvedAssets['calledZones'][$summaryRow['Called Program']][]  = $summaryRow['Zone'];

            

            $this->p_allInvolvedAssets['reportProgramSummary'][$summaryRow['Called Program']][$summaryRow['Resource OID']][$summaryRow['Asset ID']] = $summaryRow['Asset'];
            //$involvedAssetsList['epsReportProgramDetails'][$calledProgram][$resourceObjectId] = array();
            //$involvedAssetsList['epsReportZoneDetails'][$calledProgram][$resourceObjectId] = array();

            $involvedLineItem = new EventPerformanceSummaryLineItem($summaryRow['Called Program'],
                                                                $summaryRow['Resource OID'],
                                                                $summaryRow['Resource CID'],
                                                                $summaryRow['Resource CR'],
                                                                $summaryRow['Resource'],
                                                                $summaryRow['Zone'],
                                                                $summaryRow['Asset'],
                                                                $summaryRow['Asset ID'],
                                                                $summaryRow['isReportingAsset'],
                                                                $summaryRow['CommittedReduction'],
                                                                null,
                                                                null,
                                                                null,
                                                                null,
                                                                null,
                                                                $summaryRow['DoNotDispatch'],
                                                                $summaryRow['DispatchTime'],
                                                                $summaryRow['EffectiveTime'],
                                                                $summaryRow['RestorationTime'],
                                                                $summaryRow['IsDispatched'],
                                                                $summaryRow['IsRestored']);
            
            $this->p_allInvolvedAssets['epsReportProgramDetails'][$involvedLineItem->calledProgram()][$involvedLineItem->resourceOID()][$involvedLineItem->assetID()] = clone $involvedLineItem;
            
            $this->p_allInvolvedAssets['epsReportZoneDetails'][$involvedLineItem->calledProgram()][$involvedLineItem->zone()][$involvedLineItem->resourceOID()][$involvedLineItem->assetID()] = clone $involvedLineItem;
        }
//$this->preDebugger($sql,'red');
        while ($row = mysql_fetch_array($result)) {
            
            $lineItem = new EventPerformanceSummaryLineItem($row['Called Program'],
                                                            $row['Resource OID'],
                                                            $row['Resource CID'],
                                                            $row['Resource CR'],
                                                            $row['Resource'],
                                                            $row['Zone'],
                                                            $row['Asset'],
                                                            $row['Asset ID'],
                                                            $row['isReportingAsset'],
                                                            $row['CommittedReduction'],
                                                            $row['Adjustment'] * 12,
                                                            $row['PerformanceValue'],
                                                            $row['PercentagePerformanceValue'],
                                                            $row['Created'],
                                                            $row['Updated'],
                                                            $row['DoNotDispatch'],
                                                            $row['DispatchTime'],
                                                            $row['EffectiveTime'],
                                                            $row['RestorationTime'],
                                                            $row['IsDispatched'],
                                                            $row['IsRestored']);

            

            if (array_key_exists($lineItem->calledProgram(), $this->p_calledZones)) {
                if ($lineItem->isReportingAsset()) $this->p_reportProgram[$lineItem->calledProgram()]->Accumulate($lineItem->committedReduction(),
                                                                                                                  $lineItem->peakDelta(),
                                                                                                                  $lineItem->fcmBaseline(),
                                                                                                                  $lineItem->fcmLoad(),
                                                                                                                  $lineItem->fcmDelta());
                $this->p_reportProgramDetails[$lineItem->calledProgram()][$lineItem->resourceOID()][$lineItem->assetID()] = clone $lineItem;

                if (array_key_exists($lineItem->calledProgram() . '.' . $lineItem->zone(), $calledZonesByProgram)) {
                    if ($lineItem->isReportingAsset()) $this->p_reportZone[$lineItem->calledProgram()][$lineItem->zone()]->Accumulate($lineItem->committedReduction(),
                                                                                                                                      $lineItem->peakDelta(),
                                                                                                                                      $lineItem->fcmBaseline(),
                                                                                                                                      $lineItem->fcmLoad(),
                                                                                                                                      $lineItem->fcmDelta());
                    $this->p_reportZoneDetails[$lineItem->calledProgram()][$lineItem->zone()][$lineItem->resourceOID()][$lineItem->assetID()] = clone $lineItem;
                } else {
                    $calledZonesByProgram[$lineItem->calledProgram() . '.' . $lineItem->zone()] = true;

                    $this->p_calledZones[$lineItem->calledProgram()][]  = $lineItem->zone();

                    $this->p_reportZone[$lineItem->calledProgram()][$lineItem->zone()] = clone $lineItem;
                    $this->p_reportZoneDetails[$lineItem->calledProgram()][$lineItem->zone()][$lineItem->resourceOID()][$lineItem->assetID()] = clone $lineItem;

                    if (!$lineItem->isReportingAsset()) {
                        $this->p_reportZone[$lineItem->calledProgram()][$lineItem->zone()]->committedReduction(0);
                        $this->p_reportZone[$lineItem->calledProgram()][$lineItem->zone()]->peakDelta(0);
                        $this->p_reportZone[$lineItem->calledProgram()][$lineItem->zone()]->peakPCR(0);
                        $this->p_reportZone[$lineItem->calledProgram()][$lineItem->zone()]->fcmBaseline(0);
                        $this->p_reportZone[$lineItem->calledProgram()][$lineItem->zone()]->fcmLoad(0);
                        $this->p_reportZone[$lineItem->calledProgram()][$lineItem->zone()]->fcmDelta(0);
                        $this->p_reportZone[$lineItem->calledProgram()][$lineItem->zone()]->fcmPCR(0);
                    }
                }
            } else {
                $this->p_calledPrograms[] = $lineItem->calledProgram();
                $calledZonesByProgram[$lineItem->calledProgram() . '.' . $lineItem->zone()] = true;

                $this->p_calledZones[$lineItem->calledProgram()][] = $lineItem->zone();

                $this->p_reportProgram[$lineItem->calledProgram()] = clone $lineItem;
                $this->p_reportProgramDetails[$lineItem->calledProgram()][$lineItem->resourceOID()][$lineItem->assetID()] = clone $lineItem;
                $this->p_reportZone[$lineItem->calledProgram()][$lineItem->zone()] = clone $lineItem;
                $this->p_reportZoneDetails[$lineItem->calledProgram()][$lineItem->zone()][$lineItem->resourceOID()][$lineItem->assetID()] = clone $lineItem;

                if (!$lineItem->isReportingAsset()) {
                    $this->p_reportProgram[$lineItem->calledProgram()]->committedReduction(0);
                    $this->p_reportProgram[$lineItem->calledProgram()]->peakDelta(0);
                    $this->p_reportProgram[$lineItem->calledProgram()]->peakPCR(0);
                    $this->p_reportProgram[$lineItem->calledProgram()]->fcmBaseline(0);
                    $this->p_reportProgram[$lineItem->calledProgram()]->fcmLoad(0);
                    $this->p_reportProgram[$lineItem->calledProgram()]->fcmDelta(0);
                    $this->p_reportProgram[$lineItem->calledProgram()]->fcmPCR(0);

                    $this->p_reportZone[$lineItem->calledProgram()][$lineItem->zone()]->committedReduction(0);
                    $this->p_reportZone[$lineItem->calledProgram()][$lineItem->zone()]->peakDelta(0);
                    $this->p_reportZone[$lineItem->calledProgram()][$lineItem->zone()]->peakPCR(0);
                    $this->p_reportZone[$lineItem->calledProgram()][$lineItem->zone()]->fcmBaseline(0);
                    $this->p_reportZone[$lineItem->calledProgram()][$lineItem->zone()]->fcmLoad(0);
                    $this->p_reportZone[$lineItem->calledProgram()][$lineItem->zone()]->fcmDelta(0);
                    $this->p_reportZone[$lineItem->calledProgram()][$lineItem->zone()]->fcmPCR(0);
                }
            }
        } // end while
    }
}

?>
