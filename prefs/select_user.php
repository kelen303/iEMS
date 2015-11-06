<?php require_once('../Connections/crsolutions.php'); ?>
<?php
mysql_select_db($database_crsolutions, $crsolutions);
$query_user = "SELECT * FROM t_objects WHERE ObjectTypeID = 1 ORDER BY ObjectDescription ASC";
$user = mysql_query($query_user, $crsolutions) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../_dev/_template/crs_php.inc.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p>&nbsp;</p>
<p align="center">Select A User</p>
<form id="form1" name="form1" method="get" action="view_user_priviledges_associated_with_channels.php">
  <div align="center">
    <select name="UserObjectID" id="UserObjectID">
      <?php
do {  
?>
      <option value="<?php echo $row_user['ObjectID']?>"><?php echo $row_user['ObjectDescription']?></option>
      <?php
} while ($row_user = mysql_fetch_assoc($user));
  $rows = mysql_num_rows($user);
  if($rows > 0) {
      mysql_data_seek($user, 0);
	  $row_user = mysql_fetch_assoc($user);
  }
?>
    </select>
    <br />
    <input name="Submit" type="submit" id="Submit" value="Retrieve List" />
</div>
</form>
<p align="center">&nbsp; </p>
</body>
</html>
<?php
mysql_free_result($user);
?>
