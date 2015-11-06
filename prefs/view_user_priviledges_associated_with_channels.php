<?php require_once('../Connections/crsolutions.php'); ?>
<?php
$user_priviledges = "-1";
if (isset($_GET['UserObjectID'])) {
  $user_priviledges = (get_magic_quotes_gpc()) ? $_GET['UserObjectID'] : addslashes($_GET['UserObjectID']);
}
mysql_select_db($database_crsolutions, $crsolutions);
$query_priviledges = sprintf("SELECT up.userobjectid, up.userpreferenceid, pt.preferencetypedescription, pc.channeldescription FROM t_userpreferences up,  t_preferencetypes pt,  t_pointchannelpreferences pcp, t_pointchannels pc WHERE up.preferencetypeid = pt.preferencetypeid and up.userpreferenceid = pcp.userpreferenceid and pc.objectid = pcp.pointobjectid and pc.channelid = pcp.channelid and UserObjectID = %s ORDER BY pc.channeldescription", $user_priviledges);
$priviledges = mysql_query($query_priviledges, $crsolutions) or die(mysql_error());
$row_priviledges = mysql_fetch_assoc($priviledges);
$totalRows_priviledges = mysql_num_rows($priviledges);

$user_priv_list = "-1";
if (isset($_GET['UserObjectID'])) {
  $user_priv_list = (get_magic_quotes_gpc()) ? $_GET['UserObjectID'] : addslashes($_GET['UserObjectID']);
}
mysql_select_db($database_crsolutions, $crsolutions);
$query_priv_list = sprintf("SELECT ob.objectdescription, up.userpreferenceid, pt.preferencetypedescription FROM t_userpreferences up, t_objects ob, t_preferencetypes pt where up.userobjectid = ob.objectid and up.preferencetypeid = pt.preferencetypeid and UserObjectID = %s ORDER BY UserPreferenceID ASC", $user_priv_list);
$priv_list = mysql_query($query_priv_list, $crsolutions) or die(mysql_error());
$row_priv_list = mysql_fetch_assoc($priv_list);
$totalRows_priv_list = mysql_num_rows($priv_list);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>User Priviledges and Preferences</title>
<link href="../_dev/_template/crs_php.inc.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {
	font-size: 16;
	font-weight: bold;
	font-style: italic;
}
-->
</style>
</head>

<body>
<p class="style1"><?php echo $row_priv_list['objectdescription']; ?></p>
<p><em>This User Has The Following Priviledges: </em></p>
<table border="0">
  <tr>
    <td><strong>User</strong><strong>Preference ID </strong></td>
    <td width="436"><strong>Preference/Priviledge
Description</strong></td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_priv_list['userpreferenceid']; ?></td>
      <td><?php echo $row_priv_list['preferencetypedescription']; ?></td>
    </tr>
    <?php } while ($row_priv_list = mysql_fetch_assoc($priv_list)); ?>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p><em>This Users Priviledges Have Been Applied To The Following Channels As Below: </em></p>
<table width="1147" border="0">
  <tr>
    <td width="202"><strong>User ID Number<br />
    (Needed For Trouble shooting) </strong></td>
    <td width="193"><strong>Preference IDs<br />
    For This User </strong></td>
    <td width="230"><strong>Preference/Priviledge<br />
    Description</strong></td>
    <td width="504"><strong>Channel</strong></td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_priviledges['userobjectid']; ?></td>
      <td><?php echo $row_priviledges['userpreferenceid']; ?></td>
      <td><?php echo $row_priviledges['preferencetypedescription']; ?></td>
      <td><?php echo $row_priviledges['channeldescription']; ?></td>
    </tr>
    <?php } while ($row_priviledges = mysql_fetch_assoc($priviledges)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($priviledges);

mysql_free_result($priv_list);
?>
