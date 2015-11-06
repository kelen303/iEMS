<?php
if(!defined('iEMS_VERSION'))
{
    define('APPLICATION', TRUE);
    define('GROK', TRUE);
    define('iEMS_PATH', '');
    require_once iEMS_PATH.'Connections/crsolutions.php';  //in 3, connections get loaded by iEMSLoader
    require_once iEMS_PATH.'iEMSLoader.php'; 
    
    $Loader = new iEMSLoader(false);
    $User = new User();
}

if(!empty($_SESSION['iemsID']))
{
    $User = $_SESSION['UserObject'];    
}
else
{
    if(isset($_GET['u']) && isset($_GET['p']))
    {
        if($User->Login($_GET['u'], $_GET['p']))
        {
            $_SESSION['UserObject'] = $User;
            $_SESSION['iemsID'] = $User->id();
            $_SESSION['iemsName'] = $_GET['u'];
            $_SESSION['iemsPW'] =  $_GET['p'];   //this goes away in 3.0 thank goodness.
            $_SESSION['iemsID'] = $User->id();
            $_SESSION['iemsDID'] = $User->Domains(0)->id();
        }
        else
        {
            header('HTTP/1.0 404 not found'); 
            exit;
        }
    }
    else
    {
        header('HTTP/1.0 404 not found'); 
        exit;
    }
}
$pointType = isset($_GET['ptype']) ? $_GET['ptype'] : null;
$participationType = isset($_GET['parttype']) ? $_GET['parttype'] : null;
$siftByResource = (isset($_GET['sift']) && $_GET['sift'] == 'false') ? false : true;
$rapMonth = isset($_GET['month']) ? $_GET['month'] : null;
$rapYear = isset($_GET['year']) ? $_GET['year'] : null;


$PointChannels = new PointChannels();
$PointChannels->Load($User->id(),$User->Domains(0)->id(),$pointType,$participationType,$siftByResource,$rapMonth,$rapYear); 

$encodeType = isset($_GET['o']) ? $_GET['o'] : 'xml';
        
if(isset($_GET['help']))
{
    help();
}
else
{
    switch($encodeType)
    {
        case 'json':
            require_once(iEMS_PATH.'js/thejekels/JSON.php');
            $json = new Services_JSON();            
            print $json->encode(stackForJSON($PointChannels));
            break;    
        case 'array':
            $User->preDebugger($User->PointChannels(), 'blue');
            break;
        default:
            header ('Content-Type:text/xml; charset=ISO-8859-1'); 
            print stackForXML($PointChannels);
            break;        
    }
}

/*  =======================================================================================
    FUNCTION stackForJSON()
    ======================================================================================= */    
    function stackForJSON($PointChannels)
    {
        $resourceArray['identifier'] = 'id';
        $resourceArray['label'] = 'name';
    
        if($PointChannels->resources())
        {
            $inx = 0;
            foreach($PointChannels->resources() as $resourceID=>$attrib)
            {    
                $iny = 0;
            
                $resourceArray['items'][$inx]['id'] = $resourceID;
                $resourceArray['items'][$inx]['name'] = $attrib['description'];
                $resourceArray['items'][$inx]['identifier'] = $attrib['identifier'];
                $resourceArray['items'][$inx]['type'] = 'resource';
                
                foreach($attrib['assets'] as $assetID=>$assetArray)
                {        
                    $resourceArray['items'][$inx]['children'][$iny]['id'] = $resourceID.':'.$assetArray['id'].':'.$assetArray['channelId']; //this has to be exceedingly unique otherwise collisions occur with dojo grid
                    $resourceArray['items'][$inx]['children'][$iny]['objectid'] = $assetArray['id'];
                    $resourceArray['items'][$inx]['children'][$iny]['channel'] = $assetArray['channelId'];
                    $resourceArray['items'][$inx]['children'][$iny]['name'] = $assetArray['description'];                    
                    $resourceArray['items'][$inx]['children'][$iny]['identifier'] = $assetArray['assetIdentifier'];
                    $resourceArray['items'][$inx]['children'][$iny]['type'] = 'asset';
                    $iny++;
                } 
                $inx++;
            }
        }
        else
        {
            $resourceArray['items'][0]['id'] = 00;
            $resourceArray['items'][0]['name'] = 'No Resources';
            $resourceArray['items'][0]['type'] = 'resource';
        }
    
        return $resourceArray;
    }
    
/*  =======================================================================================
    FUNCTION stackForXML()
    ======================================================================================= */    
    function stackForXML($PointChannels)
    {
        if($PointChannels->resources())
        {
            print '<pointchannels>';
            foreach($PointChannels->resources() as $resourceID=>$attrib)
            {
                print '<resource>';
                print '<id>'.$resourceID.'</id>';
                print '<identifier><![CDATA['.$attrib['identifier'].']]></identifier>';
                print '<description><![CDATA['.$attrib['description'].']]></description>';
                print '<assets>';
                foreach($attrib['assets'] as $assetID=>$assetArray)
                {
                    print '<asset>';
                    print '<id>'.$assetArray['id'].'</id>';
                    print '<channel>'.$assetArray['channelId'].'</channel>';
                    print '<identifier><![CDATA['.$assetArray['assetIdentifier'].']]></identifier>';
                    print '<description><![CDATA['.$assetArray['description'].']]></description>';
                    print '<program>';
                    print '<programid>'.$assetArray['programId'].'</programid>';
                    print '<programdescription><![CDATA['.$assetArray['programDescription'].']]></programdescription>';
                    print '</program>';
                    print '</asset>';
                }
                print '</assets>';
                print '</resource>';
            }
            print '</pointchannels>';
        }
        else
        {
            return '<error>No XML Data Available</error>';
        }
        
    }


    function help()
    {
        print '<div style="margin-left: 100px; margin-right: 100px;">';
        print '<h3>pointchannels.store.php assistance</h3>';
        print '<div style="margin: 30px;">';
        print ' 
            <p>First, an attempt is made to use an existing iEMS authenticated session. If that fails, the routine checks for username and password params in the GET.</p>
            <p>This will not override existing session.  If you need to use this for a user other than the currently authenticated credentials, you must either use iEMS to log out, or dump any
            instances of your browser. If no authentication, then 404 is thrown, so no helpful feedback for any uninvited wanderers.</p>

            <p style="color: #980000;">This is not for customer user consumption.  Period.</p>

            <p>The default is for simple xml-encoded output.</p>
                </p>Params are:
                    <ul>
                        <li>u= <span style="font-style: italic;">username</span></li>
                        <li>p= <span style="font-style: italic;">password</span></li>
                        <li>o=
                                <ul style="list-style-type:square;">
                                    <li>array *</li>
                                    <li>json</li>
                                    <li>xml</li>    
                                </ul>
                        </li>
                        <li>help</li>                        
                    </ul>
                * This is a full PointChannels object dump.
            </p>

            <p>Example 1 -- Authenticate and return json-encoded data:<div style="font-style: italic; color: navy; margin: 15px;">http://stage.crsolutions.us/pointchannels.store.php?u=bob&p=joebob&o=json</div></p>
            <p>Example 2 -- Get help using already authenticated session:<div style="font-style: italic; color: navy; margin: 15px;">http://stage.crsolutions.us/pointchannels.store.php?help</div></p>
            <p>Example 3 -- Output PointChannels object array using already authenticated session:<div style="font-style: italic; color: navy; margin: 15px;">http://stage.crsolutions.us/pointchannels.store.php?o=array</div></p>
            <p>Example 4 -- Logout:<div style="font-style: italic; color: navy; margin: 15px;">http://stage.crsolutions.us/logout.php</div></p>

            <p>Usage Examples: -- <div style="font-style: italic; color: navy; margin: 15px;">http://stage.crsolutions.us/pointchannels.tests.php</div><br />
                The usage example does not reset the php session so will not override previously established session credentials.
            </p>

            <p>Other encoded output types can be made available if requested.  Other structural variants can also be made available by request.  Those currently available are structured for use by various sections of the iEMS products.</p>            
            ';
        print '</div>';
        print '</div>';
        exit;
    }
?>
