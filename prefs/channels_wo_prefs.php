<?php require_once('../Connections/crsolutions.php'); ?>
<?php
mysql_select_db($database_crsolutions, $crsolutions);
$query_channels_wo_prefs = "SELECT * FROM t_pointchannels t1 Left Join t_pointchannelpreferences t2  on t1.objectid = t2.pointobjectid where t2.userpreferenceid is null and isenabled ='1'";
$channels_wo_prefs = mysql_query($query_channels_wo_prefs, $crsolutions) or die(mysql_error());
$row_channels_wo_prefs = mysql_fetch_assoc($channels_wo_prefs);
$totalRows_channels_wo_prefs = mysql_num_rows($channels_wo_prefs);
?>
<title>Point Channels Without Preferences</title>
<link href="../_dev/_template/crs_php.inc.css" rel="stylesheet" type="text/css">
</head>

<body>
<p>Object Id's Displayed to help you decide if a point is actually supposed to be retired.<br>
  High Objectid numbers are most recently added points. Low numbers probably are supposed<br>
  to be turned off and Bret or Todd would probably know best.
</p>
<table width="635" height="49" border="0">
  <tr>
    <td width="403"><strong>Point Channels Without Preferences </strong></td>
    <td width="222">Object ID </td>
  </tr>
  <?php do { ?>
    <tr>
      <td width="403"><?php echo $row_channels_wo_prefs['ChannelDescription']; ?></td>
      <td><?php echo $row_channels_wo_prefs['ObjectID']; ?></td>
    </tr>
    <?php } while ($row_channels_wo_prefs = mysql_fetch_assoc($channels_wo_prefs)); ?>
</table>
</body>
</html><?php
mysql_free_result($channels_wo_prefs);
?>
