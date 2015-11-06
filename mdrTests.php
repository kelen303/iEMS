<?php
define('APPLICATION', TRUE);
define('GROK', TRUE);
define('iEMS_PATH','../');

if(!defined('DSN'))
            require_once(iEMS_PATH.'Connections/crsolutions.php'); //doesn't pick up on dsn unless this is here -- remove this as soon as possible

require_once iEMS_PATH.'iEMSLoader.php';

$Loader= new iEMSLoader(false);

$User = $_SESSION['UserObject'];
        
$PointChannels = new PointChannels();
$PointChannels->Load($User->id(),$User->Domains(0)->id(),'','',true); 


if(isset($_GET['logout']))
{
    session_destroy();
    header('Location: '.$_SERVER['PHP_SELF']);

    $username = '';
    $password = '';

    $_SESSION['UserObject'] = '';
    $_SESSION['iemsName'] = '';
    $_SESSION['iemsID'] = '';
    $_SESSION['iemsDID'] = '';
}
else
{
       
?>
    <div style="background-color: #F1F1F1; padding: 10px; clear: both;">
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
            <table cellpadding="3" cellspacing="0">
                <tr>
                    <td>Username:</td><td><input type="text" name="username" value="" />
                </tr>
                <tr>
                    <td>Password:</td><td><input type="text" name="password" value="" />
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" value="Login" /></td>
                </tr>
            </table>
        </form>
    <a href="<?php echo $_SERVER['PHP_SELF'].'?logout'; ?>">Logout</a>
    </div>
    <hr />
<?php
    if(isset($_SESSION['UserObject']))
    {        
        $User = $_SESSION['UserObject'];
        
        $PointChannels = new PointChannels();
        $PointChannels->Load($User->id(),$User->Domains(0)->id(),'Resource','',true);     

        //$User->pointChannels()->Load($userId, $domainId, $pointType = '', $participationType = '');

        $Loader->preDebugger($PointChannels);
        
        //$domain = 'ameresco_domain';
        //$domain = $User->lseDomain()->name();
    
        //print $domain;
        
        //$Domain = new Domain();
        //$Domain->dump();
    
        
           
        

/* =====================================================================================================
   TEST AREA
   ===================================================================================================== */
/*
    $todayParts['month'] = 12;
    $todayParts['day'] = 26;
    $todayParts['year'] = 2010;

    $_SESSION['modelDateItems']['capDate'] = date("M-d-Y H:i:s", mktime(0, 0, 0, $todayParts['month'], $todayParts['day'], $todayParts['year']));     
    
    $_SESSION['modelDateItems']['month1'] = date('n');
    $_SESSION['modelDateItems']['month2'] = strtotime(capDate) <= $_SESSION['modelDateItems']['capDate'] ? date('n')-1 : date('n')+1;

    $_SESSION['modelDateItems']['year'] = date('Y');
*/
//    $Loader->preDebugger($_SESSION['modelDateItems']);
        /*
$pointTypes = 15;
                
$pointTypesClause = ' and pn.PointTypeID in ('.$pointTypes.')';

$sql = '
SELECT DISTINCT
    pc.*,
    pn.ReadTimeOffset,
    pn.ReadInterval,
    pn.PointTypeID,
    ptypes.PointTypeName,
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
    pat.ParticipationTypeDescription,
    pcppp.CommittedReduction,
    rp.PriceID RealTimePriceID,
    rp.PriceDescription RealTimePriceDescription,
    hp.PriceID HourlyPriceID,
    hp.PriceDescription HourlyPriceDescription
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
    mdr.t_pointtypes ptypes
WHERE
    got.ObjectTypeName = "Group" and
    go.ObjectTypeID = got.ObjectTypeID and
    dgox.ChildObjectID = go.ObjectID and
    dgox.ParentObjectID = 63 and
    ugox.ParentObjectID = dgox.ChildObjectID and
    ugox.ChildObjectID = 89 and
    gpx.ObjectID = ugox.ParentObjectID and
    dpx.ObjectID = dgox.ParentObjectID and
    gpx.PrivilegeID = dpx.PrivilegeID and
    p.PrivilegeID = gpx.PrivilegeID and
    pt.PrivilegeTypeID = p.PrivilegeTypeID and
    pt.PrivilegeTypeName = "Read" and
    po.ObjectID = p.ObjectID and
    pn.ObjectID = po.ObjectID and
    t.TimeZoneID = pn.TimeZoneID and
    po.IsInactive = 0 and
    pn.IsEnabled = 1 and
    pc.ObjectID = pn.ObjectID and
    pc.IsEnabled = 1 and
    pc.IsPlotable = 1 and
    pzox.ChildObjectID = pn.ObjectID and
    zo.ObjectID = pzox.ParentObjectID and
    z.ObjectID = zo.ObjectID and
    pcppp.PointObjectID = p.ObjectID and
    pat.ParticipationTypeID = pcppp.ParticipationTypeID and
    pco.PriceComponentName = "LBMP" and
    pl.ZoneObjectID = z.ObjectID and
    rpt.PriceTypeName = "RealTimePrice" and
    rp.PriceTypeID = rpt.PriceTypeID and
    rp.PriceLocationID = pl.PriceLocationID and
    rp.PriceComponentID = pco.PriceComponentID and
    hpt.PriceTypeName = "HourlyPrice" and
    hp.PriceTypeID = hpt.PriceTypeID and
    hp.PriceLocationID = pl.PriceLocationID and
    hp.PriceComponentID = pco.PriceComponentID and
    ptypes.PointTypeID = pn.PointTypeID and
    ptypes.PointTypeName = "Resource"
ORDER BY
    pc.ChannelDescription
';

//preDebugger($sql,'red');

        $query = processQuery($sql,$mdrUser->sqlConnection(););

        if(!$query['error']) 
        {    
            if($query['records'] > 0)
            //   preDebugger($query['items']);
            print '<ul>';
            foreach($query['items'] as $pointObject)
            {
                print '<li>';
                print $pointObject->ChannelDescription.' ['.$pointObject->ObjectID.']';

                $sql2 = '
                    SELECT DISTINCT                    
                      rap.*,
                      o.ObjectDescription
                    FROM
                      t_resourceassetprofiles rap
                    JOIN
                      t_objects o
                    ON
                      rap.AssetObjectID = o.ObjectID
                    WHERE 
                        rap.ResourceObjectID = '.$pointObject->ObjectID.'
                    ORDER BY
                        o.ObjectDescription
                    ';
                $query2 = processQuery($sql2,$mdrUser->sqlConnection(););
                if($query2['records'] > 0)
                {
                    print '<ul>';
                    foreach($query2['items'] as $childPoint)
                    {
                        print '<li>';
                        print $childPoint->ObjectDescription;
                        print '</li>';
                    }
                    print '</ul>';
                }

               print '</li>';     
            }
            print '</ul>';
        }
*/
        /* 
         

        $PointChannels = new PointChannels();
        preDebugger( $mdrUser->id());
        preDebugger($mdrUser->Domains(0)->id());

        $PointChannels->Load($mdrUser->id(),$mdrUser->Domains(0)->id());

        preDebugger($PointChannels->length());

        for($inx = 0; $inx < $PointChannels->length(); $inx++)
        {
            preDebugger($PointChannels->item($inx));
        }
        */

/*
        $sql = '
            SELECT
                ResourceAssetProfileID
            FROM
                t_resourceassetprofiles rap
            WHERE 
                rap.AssetObjectID = 38 and
                rap.AssetChannelID = 1
        ';

        $query = processQuery($sql,$mdrUser->sqlConnection(););

        if(!$query['error']) 
        {    
            if($query['records'] > 0)
                preDebugger($query['items']);
        }
*/
?>
    <div style="
        width: 600px; 
        margin: 0 auto; 
        border: 1px orange dashed;
        padding: 20px;
        text-align: center;"><?php isset($query['message']) ? print $query['message'] : print ''; ?></div>
<?php

        

/* =====================================================================================================
   ===================================================================================================== */

    }
    else
    {
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        
        if($mdrUser->Login($username, $password)) 
        {
            $_SESSION['UserObject'] = $mdrUser;
            $_SESSION['iemsName'] = $username;
            $_SESSION['iemsID'] = $mdrUser->id();
            $_SESSION['iemsDID'] = $mdrUser->Domains(0)->id();
            $_SESSION['iemsPW'] = $password;       
        } 
    }
}     
    function processQuery($sql,$connection)
    {            
        $result = mysql_query($sql, $connection);

        if(!$result) 
        {
            $errno = mysql_errno($connection);
            $error = mysql_error($connection);
        
            return array(
                'error'=>true,
                'message'=>"Database Error ($errno): $error");
        }
        else
        {
            $records = mysql_affected_rows($connection);
            $return = array();

            while($row = mysql_fetch_object($result)) 
            {
                $return[] = $row;
            }

            return array(
                'error'=>false,
                'message'=>"Number of records involved: $records",
                'records'=>$records,
                'items'=>$return);
        } 


        //$inx = 0;
//        while ($row = mysql_fetch_array($result)) {
            /*
            $this->p_domains[$inx] = new Domain();
            $this->p_domains[$inx++]->Load($row["ObjectID"], $row["ObjectName"], $row["ObjectDescription"]); 
            */ 
            //echo "Domain ID='", $this->p_domains[$inx]->id(), "', Name='", $this->p_domains[$inx]->name(), "', Description='", $this->p_domains[$inx]->description(), "'...<br>\n";
        //}
        /*
        $pwSQL = '
            UPDATE 
                t_users 
            SET
                Password = "'.$newPassword.'"
            WHERE 
                ObjectID = '.$userId;

        $result = mysql_query($sql, $mdrUser->sqlConnection(););

        
        */ 
    }

    print '<hr />';
    print '<div style="background-color: #F1F1F1; padding: 30px;">';        
    print '<pre>POST ==================================================</pre>';
    $Loader->preDebugger($_POST);
    print '<pre>GET ===================================================</pre>';
    $Loader->preDebugger($_GET);
    print '<pre>SESSION ===============================================</pre>';
    if(isset($_SESSION))
    //preDebugger($_SESSION);
    print '</div>';
?>

