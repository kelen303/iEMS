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

    $eol = "\n";
    $delm = ',';

    outputForCSV();
     
    if($_GET['contacts'] == 'true')
    {
        print '"'.'Contact by Resource Report for '.$User->fullName().'"'.$eol;    

        $contactManager = new ContactManager($User->lseDomain()->id(), $User->ID());
    
        $priorities = $contactManager->GetPriorities();
        $contactUses = $contactManager->GetContactUses();
        $contactValueTypes = $contactManager->GetContactValueTypes();
        $contactValueSubtypes = $contactManager->GetContactValueSubtypes();
        
        if($_GET['format'] != 'hierarchical')
        {            
            print 'Resource ID'.$delm.'Resource'.$delm.'Asset ID'.$delm.'Asset Description'.$delm.'Program'.$eol;            
        } 

    }
    else
    {
        print '"'.'Assets by Resource Report for '.$User->fullName().'"'.$eol;    

        if($_GET['format'] != 'hierarchical')
        {
            print 'Resource ID'.$delm.'Resource'.$delm.'Asset ID'.$delm.'Asset Description'.$delm.'Program'.$eol;            
        } 
    }

    foreach($PointChannels->resources() as $resourceObjectID=>$attrib)
    {   
        $resourceDesc = trim(str_replace($attrib['identifier'],"",$attrib['description']));

        if($_GET['format'] == 'hierarchical')
        {
            print $attrib['identifier'].$delm.'"'.$resourceDesc.'"'.$eol;
        }

        foreach($attrib['assets'] as $assetID=>$assetArray)
        { 
            if($_GET['format'] == 'hierarchical')
            {
                print $delm.$assetArray['assetIdentifier'].$delm.'"'.$assetArray['description'].'"'.$delm.'"'.$assetArray['programDescription'].'"'.$eol;
            }
            else
            {
                if($_GET['contacts'] == 'false')
                {
                    print '"'.$attrib['identifier'].'"'.$delm;
                    print '"'.$resourceDesc.'"'.$delm;                    
                    print '"'.$assetArray['assetIdentifier'].'"'.$delm;
                    print '"'.$assetArray['description'].'"'.$delm.'"'.$assetArray['programDescription'].'"'.$eol;
                }
            }
            
            
            if($_GET['contacts'] == 'true')
            {
                $sql = '
                    SELECT
                        ContactObjectID
                    FROM
                        t_pointcontactprofiles
                    WHERE
                        PointObjectID = '.$assetArray['id'].'
                    LIMIT 1
                    ';
    
                $result = $User->processQuery($sql,$User->sqlConnection(),'select');            
    
                if($result['records'] > 0)
                {
                    foreach($result['items'] as $contactObject)
                    {
                        foreach($contactUses as $contactUse)
                        {                        
                            $sql2 = '
                                SELECT
                                    cp.ContactProfileID,
                                    cp.ObjectID,
                                    p.PriorityID,
                                    p.PriorityName,
                                    p.PriorityDescription,
                                    p.PriorityNote,
                                    p.PriorityLevel,
                                    p.CreatedDate pCreatedDate,
                                    p.CreatedBy pCreatedBy,
                                    p.UpdatedDate pUpdatedDate,
                                    p.UpdatedBy pUpdatedBy,
                                    cp.ContactUseID,
                                    cp.IsInactive CpIsInactive,
                                    cv.ContactValueID,
                                    cv.ContactValueTypeID,
                                    cvt.ContactValueTypeName,
                                    cv.ContactValueSubtypeID,
                                    cv.ContactOwnerID,
                                    cv.ContactValue,
                                    cv.IsInactive CvIsInactive,
                                    cv.CreatedDate CvCreatedDate,
                                    cv.CreatedBy CvCreatedBy,
                                    cv.UpdatedDate CvUpdatedDate,
                                    cv.UpdatedBy CvUpdatedBy,
                                    co.ContactOwnerID CoContactOwnerID,
                                    co.Name CoName,
                                    co.CreatedDate CoCreatedDate,
                                    co.CreatedBy CoCreatedBy,
                                    co.UpdatedDate CoUpdatedDate,
                                    co.UpdatedBy CoUpdatedBy,
                                    cp.CreatedDate CpCreatedDate,
                                    cp.CreatedBy CpCreatedBy,
                                    cp.UpdatedDate CpUpdatedDate,
                                    cp.UpdatedBy CpUpdatedBy
                                FROM
                                    t_contactprofiles cp,
                                    t_contactvaluetypes cvt,
                                    t_priorities p,
                                    t_contactvalues cv
                                    left join t_contactowners co on co.ContactOwnerID = cv.ContactOwnerID
                                WHERE
                                    cp.ObjectID = '.$contactObject->ContactObjectID.' and
                                    cp.ContactUseID = '.$contactUse->ID().' and 
                                    cv.ContactValueID = cp.ContactValueID and
                                    cvt.ContactValueTypeID = cv.ContactValueTypeID and
                                    p.PriorityID = cp.PriorityID
                                ORDER BY                                
                                    CoName,
                                    ContactValueTypeID,
                                    ContactValueSubTypeID,
                                    ContactValue                                
                                ';

                            //$User->preDebugger($sql);
    
                            $result2 = $User->processQuery($sql2,$User->sqlConnection(),'select');
    
                            if($result2['records'] > 0)
                            {
                                foreach($result2['items'] as $contactLine)
                                {
                                    if($_GET['format'] == 'flat')
                                    {
                                        print '"'.$attrib['identifier'].'"'.$delm.'"'.$resourceDesc.'"'.$delm;
                                        print $assetArray['assetIdentifier'].$delm.'"'.$assetArray['description'].'"'.$delm.'"'.$assetArray['programDescription'].'"'.$delm;
                                    }
                                    else
                                    {
                                        print $delm.$delm;
                                        
                                        
                                    }
                                    print $contactUse->description().$delm;                                
                                    print '"'.$contactLine->CoName.'"'.$delm;
                                    print $contactValueTypes[$contactLine->ContactValueTypeID]->description().$delm;                                
                                    print $contactValueSubtypes[$contactLine->ContactValueSubtypeID]->description().$delm;
                                    print $contactLine->ContactValue.$delm;
                                    print humanPriority($contactLine->PriorityDescription).$eol;
                                }                            
                            }
                       }
                    }
                }   
            }
            
        }
    }


    function humanPriority($value)
    {
    
        $humanArray['Primary'] = '1. Primary';
        $humanArray['Secondary'] = '2. Secondary';
        $humanArray['Tertiary'] = '3. Tertiary';
        $humanArray['Quaternary'] = '4. Quaternary';
        $humanArray['Quinary'] = '5. Quinary';
        $humanArray['Senary'] = '6. Senary';                                    
        $humanArray['Septenary'] = '7. Septenary';
        $humanArray['Octonary'] = '8. Octonary';
        $humanArray['Nonary'] = '9. Nonary';
        $humanArray['Denary'] = '10. Denary';
        
        return $humanArray[$value];
    }

    function outputForCSV()
    {
        $savename = 'iEMS2_contacts_'.date('Y_m_d_H_i').'.csv';
        ini_set('zlib.output_compression','Off');
        
        header('Pragma: public');
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");                  // Date in the past   
        header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');     // HTTP/1.1
        header('Cache-Control: pre-check=0, post-check=0, max-age=0');    // HTTP/1.1
        header("Pragma: no-cache");
        header("Expires: 0");
        
        header('Content-Transfer-Encoding: none');
        header('Content-Type: text/css');
        header('Content-Type: application/vnd.ms-excel;');                 // This should work for IE & Opera
        header("Content-type: application/x-msexcel");                    // This should work for the rest
        header('Content-Disposition: attachment; filename="'.$savename.'"');
    }
?>


