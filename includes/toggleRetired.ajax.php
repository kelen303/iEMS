<?php
define('APPLICATION', TRUE);
define('GROK', TRUE);
define('iEMS_PATH', '../');

require_once iEMS_PATH.'Connections/crsolutions.php';  //in 3, connections get loaded by iEMSLoader
require_once iEMS_PATH.'iEMSLoader.php'; 

$Loader = new iEMSLoader(false);

$oUser = $_SESSION['UserObject'];

$master_connection = $oUser->sqlMasterConnection();

$oUser->toggleRetired();
    
?>
