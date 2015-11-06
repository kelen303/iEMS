<?php require_once('../Connections/crsolutions.php'); ?>
<?php
mysql_select_db($database_crsolutions, $crsolutions);
$query_prettyprefs = "SELECT * FROM t_userpreferences t1, t_objects t2 WHERE t1.preferencetypeid = '5' and t1.userobjectid = t2.objectid ORDER BY t2.objectdescription";
$prettyprefs = mysql_query($query_prettyprefs, $crsolutions) or die(mysql_error());
$row_prettyprefs = mysql_fetch_assoc($prettyprefs);
$totalRows_prettyprefs = mysql_num_rows($prettyprefs);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>View Preferences</title>
</head>

<body>
<table border="1">
  <tr>
    <td>UserPreferenceID</td>
    <td>PreferenceTypeID</td>
    <td>UserObjectID</td>
    <td>Username</td>
    <td>User Desc. </td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_prettyprefs['UserPreferenceID']; ?></td>
      <td><?php echo $row_prettyprefs['PreferenceTypeID']; ?></td>
      <td><?php echo $row_prettyprefs['UserObjectID']; ?></td>
      <td><?php echo $row_prettyprefs['ObjectName']; ?></td>
      <td><?php echo $row_prettyprefs['ObjectDescription']; ?></td>
    </tr>
    <?php } while ($row_prettyprefs = mysql_fetch_assoc($prettyprefs)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($prettyprefs);
?>
