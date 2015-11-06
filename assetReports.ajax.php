<?php
define('APPLICATION', TRUE);
define('GROK', TRUE);
define('iEMS_PATH', '');
require_once iEMS_PATH.'Connections/crsolutions.php';  //in 3, connections get loaded by iEMSLoader
require_once iEMS_PATH.'iEMSLoader.php'; 

$Loader = new iEMSLoader(false);
$User = new User();

$User = $_SESSION['UserObject'];

$PointChannels = new PointChannels();
$PointChannels->Load($User->id(),$User->Domains(0)->id(),'',null,true,null,null); 
//$PointChannels->preDebugger($PointChannels);
    
    print '<table border="0" cellpadding="0" cellspacing="0">';
    
    print '<tr>';
    print '<td align="right">';

    if($_POST['assetReportFormat'] == 'hierarchical')
    {        
        print '<div style="width: 700px;">';
        print '<div class="export" style="width: 31px;">
                    <a href="assetReports.csv.php?format=hierarchical&contacts=false" id="exportTip" class="exportTip"><img src="_template/images/blank.gif" height="31" width="31" border="0" /><a/>
               </div>
               <div style="padding-top: 10px; text-align: right;">
                   <a href="#" style="padding: 3px;" onClick="dojo.style(\'dataReturn_res\', \'display\', \'block\');dojo.style(\'dataReturn_table\', \'display\', \'none\');" >Return to Chart</a>
               </div>
               ';
    }
    else
    {
        print '<div>';
        print '<div class="export" style="width: 31px;">
                    <a href="assetReports.csv.php?format=flat&contacts=false" id="exportTip" class="exportTip" ><img src="_template/images/blank.gif" height="31" width="31" border="0" /><a/>
               </div>
               <div style="padding-top: 10px; text-align: right;">
                   <a href="#" style="padding: 3px;" onClick="dojo.style(\'dataReturn_res\', \'display\', \'block\');dojo.style(\'dataReturn_table\', \'display\', \'none\');" >Return to Chart</a>
               </div>
            ';
    }

    print '</td>';
    print '</tr>';
    
    print '<tr>';
    print '<td style="text-align: center;">';

    print '<h3>Assets by Resource Report for '.$User->fullName().'</h3>';

    print '</td>';
    print '</tr>';
    
    print '<tr>';
    print '<td align="center">';

    print '<table border="0" cellpadding="5" cellspacing="0">';

    if($_POST['assetReportFormat'] == 'flat')
    {
        print '<tr>
            <td style="border-bottom: 1px solid; font-weight: bold;">Resource ID</td>
            <td style="border-bottom: 1px solid; font-weight: bold;">Resource Description</td>
            <td style="border-bottom: 1px solid; font-weight: bold;">Asset ID</td>
            <td style="border-bottom: 1px solid; font-weight: bold;">Asset Description</td>
         </tr>';
    }

    foreach($PointChannels->resources() as $resourceObjectID=>$attrib)
    {
        $resourceId = $attrib['identifier'];                

        $resourceDesc = trim(str_replace($attrib['identifier'],"",$attrib['description']));

        if($_POST['assetReportFormat'] == 'hierarchical')            
            print '<tr><td colspan="4" style="text-align: left;"><strong>'.$resourceId.' '.$resourceDesc.':</strong></td></tr>';        
                
        foreach($attrib['assets'] as $assetID=>$assetArray)
        { 
            if($_POST['assetReportFormat'] == 'flat')
            {  
                print '<tr><td>'.$resourceId.'</td><td>'.$resourceDesc.'</td>';                
                print '<td>'.$assetArray['assetIdentifier'].'</td>';
                print '<td>'.$assetArray['description'].'</td>';                
                print '</tr>';
            }
            else
            {
                print '<td>&nbsp;&nbsp;&nbsp;</td>';
                print '<td style="text-align: left;">'.$assetArray['assetIdentifier'].'</td>';
                print '<td style="text-align: left;">'.$assetArray['description'].'</td>';
                print '</tr>';
            }
            
        }
        
    }
    
    print '</table>';

    print '</div>';
    print '<td>';
    print '<tr>';
    print '</table>';
?>

