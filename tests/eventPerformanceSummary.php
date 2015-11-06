<?php
    $username = 'mbuford';
    $password = 'mbuford';
    $evtDate = '07-22-2011';
    //$evtDate = '07-27-2010'; // pre dispatch date
    
    $csv = true;

    define('GROK', TRUE);
    define('APPLICATION', TRUE);
    define('iEMS_PATH','../');

    
    require_once iEMS_PATH.'Connections/crsolutions.php';
    
    require_once iEMS_PATH.'mdr/CAO.php';
    require_once iEMS_PATH.'mdr/User.php';
    require_once iEMS_PATH.'mdr/CRSDate.php';
    require_once iEMS_PATH.'mdr/Domain.php';
    require_once iEMS_PATH.'mdr/Privileges.php';
    require_once iEMS_PATH.'mdr/PointChannels.php';
    require_once iEMS_PATH.'mdr/UnitOfMeasure.php';
    require_once iEMS_PATH.'mdr/PointChannel.php';
    require_once iEMS_PATH.'mdr/MeterPoint.php';
    require_once iEMS_PATH.'mdr/PointType.php';
    require_once iEMS_PATH.'mdr/TimeZone.php';
    require_once iEMS_PATH.'mdr/Preferences.php';
    require_once iEMS_PATH.'mdr/EvtPerfSummary.php';
    require_once iEMS_PATH.'mdr/EvtPerfSummaryLineItem.php';
    require_once iEMS_PATH.'displayEventSummary.inc.php';
    
    $oUser = new User();
	$oUser->Login($username, $password);

    $_SESSION['UserObject'] = $oUser;

    $_SESSION['evtSummarySections'][0] =  'Real_Time_Demand_Response_detail';
    $_SESSION['evtSummarySections'][1] =  'Ameresco_Domain_Resource_12691_program_resource_detail';
    $_SESSION['evtSummarySections'][2] =  'Dispatched_Resource_17334_--_RTDR_50093_Western_MA_(7517)_program_resource_detail';
    $_SESSION['evtSummarySections'][3] =  'Dispatched_Resource_37889_--_RTDR_50092_Eastern_CT_(7500)_-_2_program_resource_detail';
    $_SESSION['evtSummarySections'][4] =  'Dispatched_Resource_37890_--_RTDR_50092_Northern_CT_(7501)_-_2_program_resource_detail';
    $_SESSION['evtSummarySections'][5] =  'Dispatched_Resource_37891_--_RTDR_50092_Norwalk_-_Stamford_(7502)_-_2_program_resource_detail';
    $_SESSION['evtSummarySections'][6] =  'Dispatched_Resource_37892_--_RTDR_50092_Western_CT_(7503)_-_2_program_resource_detail';
    $_SESSION['evtSummarySections'][7] =  'Energy_Curtailment_Specialists_Domain_Resource_37945_program_resource_detail';
    $_SESSION['evtSummarySections'][8] =  'Energy_Curtailment_Specialists_Domain_Resource_37976_program_resource_detail';
    $_SESSION['evtSummarySections'][9] =  'Energy_Curtailment_Specialists_Domain_Resource_37978_program_resource_detail';
    $_SESSION['evtSummarySections'][10] =  'RTDR_181_Bangor_Hydro_(7504)_program_resource_detail';
    $_SESSION['evtSummarySections'][11] =  'RTDR_181_Eastern_CT_(7500)_program_resource_detail';
    $_SESSION['evtSummarySections'][12] =  'RTDR_181_Maine_(7505)_program_resource_detail';
    $_SESSION['evtSummarySections'][13] =  'RTDR_181_Northern_CT_(7501)_program_resource_detail';
    $_SESSION['evtSummarySections'][14] =  'RTDR_181_Norwalk_-_Stamford_(7502)_program_resource_detail';
    $_SESSION['evtSummarySections'][15] =  'RTDR_181_Western_CT_(7503)_program_resource_detail';
    $_SESSION['evtSummarySections'][16] =  'United_Illuminating_Domain_Resource_37857_program_resource_detail';
    $_SESSION['evtSummarySections'][17] =  'United_Illuminating_Domain_Resource_37858_program_resource_detail';
    $_SESSION['evtSummarySections'][18] =  'United_Illuminating_Domain_Resource_37859_program_resource_detail';
    $_SESSION['evtSummarySections'][19] =  'United_Illuminating_Domain_Resource_37860_program_resource_detail';
    $_SESSION['evtSummarySections'][20] =  'United_Illuminating_Domain_Resource_37861_program_resource_detail';
    $_SESSION['evtSummarySections'][21] =  'United_Illuminating_Domain_Resource_37863_program_resource_detail';
    $_SESSION['evtSummarySections'][22] =  'United_Illuminating_Domain_Resource_37864_program_resource_detail';
    $_SESSION['evtSummarySections'][23] =  'United_Illuminating_Domain_Resource_37865_program_resource_detail';
    $_SESSION['evtSummarySections'][24] =  'United_Illuminating_Domain_Resource_37866_program_resource_detail';
    $_SESSION['evtSummarySections'][25] =  'United_Illuminating_Domain_Resource_37867_program_resource_detail';
    $_SESSION['evtSummarySections'][26] =  'United_Illuminating_Domain_Resource_37868_program_resource_detail';


    $meterSummaries = viewEventSummary($oUser->lseDomain()->id(), $evtDate, $csv)


?>
<style>th {text-align: right; padding-right: 10px;} </style>
<h3>Event Performance Summary Test</h3>
<table>
    <tr><th>Username: </th><td><?php echo $username; ?></td></tr>
    <tr><th>Domain: </th><td><?php echo $oUser->lseDomain()->id(); ?></td></tr>
    <tr><th>Event Date: </th><td><?php echo $evtDate; ?></td></tr>
</table>
<hr />
<?php if($csv) print '<pre>'; ?>
<?php print $meterSummaries; ?>
<?php $oUser->preDebugger($_SESSION['evtSummarySections']); ?>

