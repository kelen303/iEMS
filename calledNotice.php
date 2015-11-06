<?php    
    if(isset($_POST['fetchEvents']) && isset($_POST['evtBaseDate']))
    {
       $dateParts = explode('-',$_POST['evtBaseDate']);
       $dateToCheck = $dateParts[2].'-'.$dateParts[0].'-'.$dateParts[1];
    }
    elseif(isset($_POST['fetchEventSummary']) && isset($_POST['evtSummaryDate']))
    {
       $dateParts = explode('-',$_POST['evtSummaryDate']);
       $dateToCheck = $dateParts[2].'-'.$dateParts[0].'-'.$dateParts[1];
    }
    else
    {
        $dateToCheck = date('Y-m-d');
    };

    //$mdrUser->preDebugger($_POST);

    //$dateToCheck = '2010-06-14';

    $sql = '
        select
          pc.ChannelDescription,
          pc.AssetIdentifier
        from
          t_notificationpointchannels npc,
          t_pointchannels pc,
          t_points p,
          t_pointtypes pt,
          t_privileges pri,
          t_actorprivilegexrefs apx
        where
          pt.PointTypeName = \'Resource\' and
          p.PointTypeID = pt.PointTypeID and
          pc.ObjectID = p.ObjectID and
          npc.ObjectID = pc.ObjectID and
          npc.ChannelID = pc.ChannelID and
          npc.DispatchTime between \''.$dateToCheck.'\' and date_add(\''.$dateToCheck.'\', INTERVAL \'23:59:59\' HOUR_SECOND) and
          pri.ObjectID = npc.ObjectID and
          apx.PrivilegeID = pri.PrivilegeID and
          apx.ObjectID = ' .$mdrUser->lseDomain()->id()
        ;

    $result = $mdrUser->processQuery($sql,$connection,'select');

    if($result['records'] > 0) {
        //$User->preDebugger($result, '#980000');
        print '<div style="width: 650px; margin-bottom: 20px;"><table cellpadding="3" cellspacing="0" border="0" align="center" width=400px;>';
        print '<tr><td colspan="2" style="border-bottom: 2px solid; text-align: left;"><strong>Resources Dispatched On '.date('m/d/Y',strtotime($dateToCheck)).'</strong></td></tr>';
        foreach($result['items'] as $inx=>$asset)
        {
            print '<tr><td style="text-align: left;">'.$asset->AssetIdentifier.'</td>';
            print '<td style="text-align: left;">'.$asset->ChannelDescription.'</td></tr>';
        }
        print '</table></div>';
    }
?>
