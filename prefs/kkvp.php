<?php
//require_once('../Connections/crsolutions.php');

//mysql_select_db($database_crsolutions, $crsolutions);
//$query_prefs_by_user = "select    uo.ObjectDescription \"User\",    do.ObjectDescription \"Domain\",    pc.ChannelDescription from    t_users u,    t_objects uo,    t_groups g,    t_objects go,    t_grouptypes gt,    t_objectxrefs ugox,    t_objects do,    t_objecttypes dot,    t_objectxrefs duox,    t_objectxrefs dgox,    t_actorprivilegexrefs dapx,    t_actorprivilegexrefs gapx,    t_privileges pr,    t_pointchannels pc where uo.ObjectID = u.ObjectID and    ugox.ChildObjectID = uo.ObjectID and    g.ObjectID = ugox.ParentObjectID and    gt.GroupTypeID = g.GroupTypeID and    gt.GroupTypeName = 'Privilege' and    go.ObjectID = g.ObjectID and    go.ObjectName  = 'facility_operators' and    dgox.ChildObjectID = go.ObjectID and    do.ObjectID = dgox.ParentObjectID and    dot.ObjectTypeID = do.ObjectTypeID and    dot.ObjectTypeName = 'Domain' and    duox.ChildObjectID = uo.ObjectID and    duox.ParentObjectID = do.ObjectID and    dapx.ObjectID = do.ObjectID and    gapx.ObjectID = go.ObjectID and    dapx.PrivilegeID = gapx.PrivilegeID and    pr.PrivilegeID = dapx.PrivilegeID and    pc.ObjectID = pr.ObjectID and    pc.IsEnabled = 1 order by    \"User\",     \"Domain\",     \"ChannelDescription\"";
//$prefs_by_user = mysql_query($query_prefs_by_user, $crsolutions) or die(mysql_error());
//$row_prefs_by_user = mysql_fetch_assoc($prefs_by_user);
//$totalRows_prefs_by_user = mysql_num_rows($prefs_by_user);
?>
<html>
    <head>
        <title>View Preferences</title>
    </head>
    
    <body>
        <table border="0">
            <tr>
                <td>Cell</td>
            </tr>
        </table>
    </body>
</html>

