<?php

define('APPLICATION', TRUE);
define('GROK', TRUE);
define('iEMS_PATH','../../../');

if(!defined('DSN'))
            require_once(iEMS_PATH.'Connections/crsolutions.php'); //doesn't pick up on dsn unless this is here -- remove this as soon as possible

require_once iEMS_PATH.'iEMSLoader.php';

$Loader= new iEMSLoader(false);

$User = $_SESSION['UserObject'];

$_SESSION['formUsed'] = 'assetProfiler';

switch($_GET['action'])
{
    case 'final':
        finalModel();
        break;
    case 'upload':
        //uploadForm();
        break;
    default:        
        init();
        break;
}

/*  ===============================================================================
    FUNCTION : finalModel()
    =============================================================================== */
    function finalModel()
    {
        print '<h3 style="text-align: left;">This is what we\'ll send to the server:</h3>';
        print '<div style="text-align: left;">';
        print '<pre>';
        print_r($_POST);
        print '</pre>';
        
        print '<hr />';

        $xDRVo = '123456';
        $drLoad = '789456';

        print '
        <table>
            <tr>
                <td>some calculation returned:</td>
                <td>'.$xDRVo.' kW</td>
            </tr>
            <tr>
                <td>another calculation returned:</td>
                <td>'.$drLoad.' kW</td>
            </tr>
        </table>
        ';

        print '<hr />';
       print '</div>';
       
    }
/*  ===============================================================================
    FUNCTION : finalModel()
    =============================================================================== */
    function init()
    {    

?>
<style>
    .dijitInputField input
        {
            color: #000;
        }
        select
        {
            color: #000;
        }
    .assetInput { color: #000000; }
    
</style>
<div style="margin-top: 30px;"></div>
<form id="modellingForm"
    dojoType="dijit.form.Form"
    >
<ol>
    <li>Select resource groups or individual assets from the list on the Left.</li>
    <li>You may adjust the priority for all of the assets shown on the right by using the global priority selector or adjust each asset individually using the number spinner to the left of its name.</li>
    <li>Once the assets have been chosen and their priorities set, complete the rest of the form and click the Send button.  Remember that <strong>all</strong> fields are <strong>required</strong>.</li>
</ol>
<div
    style="cursor: pointer; text-decoration: underline;"
    onClick="javascript:dojo.style(dojo.byId('priorityKey'),'display','block');"
    >Priority Key:</div>
<div id="priorityKey"
        style="display:none;">
         0 = Do not call
         1 = Primary
         2 = Secondary
         3 = Call as last resort
         <div
            style="cursor: pointer; text-decoration: underline;"
            onClick="javascript:dojo.style(dojo.byId('priorityKey'),'display','none');"
            >[hide]</div>
    </div>
<hr />

    <table border="0" cellpadding="5" cellspacing="0">
        <tr>
            <td width="230">
                Global Priority:
            </td>
            <td>
               <select
                    name="globalPriority"
                    onChange="javascript:setAllPriorities(this.value);"
                    style="color: #000000;"
                    >
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select> 
            </td>
        </tr>
        <tr>
            <td>
                Selected Demand Reduction Value (DRV):
            </td>
            <td>
                <input name="drv"
                    style="width: 75px;"
                    /> kW
            </td>
        </tr>
        <tr>
            <td>
                Large/Small Asset Delineation (smAcutoff Input Value):
            </td>
            <td>
                <input name="smAcutoff"
                    style="width: 75px;"
                    /> kW
            </td>
        </tr>
        <tr>
            <td>
                Contiguous days:
            </td>
            <td>
                <select
                    name="modelDays"
                    style="width: 100px; color: #000000;"
                    >
                    <option value="0">0 Days</option>
                    <option value="1">1 Day</option>
                    <option value="2">2 Days</option>                            
                    <option value="3">3 Days</option>
                    <option value="4">4 Days</option>                            
                </select>  
            </td>
        </tr>
        </table>
           
        <div style="width: 100%; text-align: center;">
            <div id="goButton" 
                dojoType="dijit.form.Button"
                onClick="javascript:processModel();"
                style="color: #000000;"
                ><span style="width: 200px;">Send</span>
                </div>    
        </div>    
    <div id="checkStackMessage"></div>
    <div id="checkStackDiv"></div>
    <br /><br />
    <input type="hidden" name="formUsed" value="assetForm" />
</form>
<?php
    }
?>
