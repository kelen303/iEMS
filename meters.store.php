<?php
    if(!defined('APPLICATION'))
            define('APPLICATION', TRUE);
    if(!defined('GROK'))
            define('GROK', TRUE);
    if(!defined('iEMS_PATH'))
            define('iEMS_PATH', '');
    
    if(!defined('iEMS_VERSION'))
    {
        require_once iEMS_PATH.'Connections/crsolutions.php';  //in 3, connections get loaded by iEMSLoader
        require_once iEMS_PATH.'iEMSLoader.php'; 
    
        $Loader = new iEMSLoader(false);
        $mdrUser = new User();
    }
    
    require_once(iEMS_PATH.'js/thejekels/JSON.php');
    $json = new Services_JSON();


    if(!isset($_SESSION['UserObject']))
    {
        return null;
    }
    $User = $_SESSION['UserObject'];
    $program = isset($_GET['program']) ? $_GET['program'] : '';
    
    if(isset($_GET['month']))
    {
        $resourceDateArray = explode(':',$_GET['month']);
        $resourceMonth = $resourceDateArray[0];
        $resourceYear = $resourceDateArray[1];
    }
    else
    {
        $resourceMonth = '';
        $resourceYear = '';
    }
    //$Loader->preDebugger($resourceDateArray);
    //$Loader->preDebugger($resourceMonth);
    //$Loader->preDebugger($resourceYear);

    $PointChannels = new PointChannels();
    $PointChannels->Load($User->id(),$User->Domains(0)->id(),'',$program,true,$resourceMonth,$resourceYear); 
    
    //$Loader->preDebugger($PointChannels);   

    $encodeType = isset($_GET['type']) ? $_GET['type'] : '';
        
    switch($encodeType)
    {
        case 'json':
            header('Content-type: application/json');
            print $json->encode(stackForJSON($PointChannels));
            break;
        case 'xml':
            header ('Content-Type:text/xml'); 
            print stackForXML($PointChannels);
            break;
        default:
            //print '<pre>';
            //print_r(basicArray($PointChannels));
            //print '</pre>';
            break;
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
                $resourceArray['items'][$inx]['type'] = 'resource';
                
                foreach($attrib['assets'] as $assetID=>$assetArray)
                {        
                    $resourceArray['items'][$inx]['children'][$iny]['id'] = $resourceID.':'.$assetArray['id'].':'.$assetArray['channelId'];                                                                                                                               
                    $resourceArray['items'][$inx]['children'][$iny]['name'] = $assetArray['description'];
                    $resourceArray['items'][$inx]['children'][$iny]['type'] = 'asset';
                    $resourceArray['items'][$inx]['children'][$iny]['value'] = $resourceID.':'.$assetArray['id'].':'.$assetArray['channelId'];
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
        return '<error>No XML Configuration Available</error>';
    }

/*  =======================================================================================
    FUNCTION basicArray()
    ======================================================================================= */  
    function basicArray($PointChannels)
    {
        return $PointChannels;
    }

//print $json->encode($resourceArray);
?>

