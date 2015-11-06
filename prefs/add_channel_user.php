<?php require_once('../Connections/crsolutions.php'); ?>
<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO t_pointchannelpreferences (UserPreferenceID, PointObjectID, ChannelID, CreatedDate, CreatedBy) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['UserPreferenceID'], "int"),
                       GetSQLValueString($_POST['PointObjectID'], "int"),
                       GetSQLValueString($_POST['ChannelID'], "int"),
                       GetSQLValueString($_POST['CreatedDate'], "date"),
                       GetSQLValueString($_POST['CreatedBy'], "int"));

  mysql_select_db($database_crsolutions, $crsolutions);
  $Result1 = mysql_query($insertSQL, $crsolutions) or die(mysql_error());

  $insertGoTo = "prefsbody.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_crsolutions, $crsolutions);
$query_userprefs = "SELECT * FROM t_userpreferences t1, t_objects t2 WHERE t1.preferencetypeid = '5' and t1.userobjectid = t2.objectid ORDER BY t2.objectdescription";
$userprefs = mysql_query($query_userprefs, $crsolutions) or die(mysql_error());
$row_userprefs = mysql_fetch_assoc($userprefs);
$totalRows_userprefs = mysql_num_rows($userprefs);

mysql_select_db($database_crsolutions, $crsolutions);
$query_unnattachedpoints = "SELECT t1.objectid, t1.channelid, t1.channeldescription FROM t_pointchannels t1 Left Join t_pointchannelpreferences t2  on t1.objectid = t2.pointobjectid WHERE t2.userpreferenceid is null and t1.isenabled ='1' order by t1.channeldescription";
$unnattachedpoints = mysql_query($query_unnattachedpoints, $crsolutions) or die(mysql_error());
$row_unnattachedpoints = mysql_fetch_assoc($unnattachedpoints);
$totalRows_unnattachedpoints = mysql_num_rows($unnattachedpoints);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Add Unsigned Channel To User Preference</title>
<link href="../_dev/_template/crs_php.inc.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p>&nbsp;</p>
<p>&nbsp;</p>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <p align="center">The only points that appear here are points that have yet to have ANY user assign a preference to. <br />
  You may need to make sure you have already made a user for this point before you continue.</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <table align="center">
    <tr valign="baseline">
      <td nowrap align="right">User To Attach :</td>
      <td><select name="UserPreferenceID" id="UserPreferenceID">
        <?php
do {  
?>
        <option value="<?php echo $row_userprefs['UserPreferenceID']?>"><?php echo $row_userprefs['ObjectDescription']?></option>
        <?php
} while ($row_userprefs = mysql_fetch_assoc($userprefs));
  $rows = mysql_num_rows($userprefs);
  if($rows > 0) {
      mysql_data_seek($userprefs, 0);
	  $row_userprefs = mysql_fetch_assoc($userprefs);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">      Point:</td>
      <td><select name="PointObjectID" id="PointObjectID">
        <?php
do {  
?>
        <option value="<?php echo $row_unnattachedpoints['objectid']?>"><?php echo $row_unnattachedpoints['channeldescription']?></option>
<?php
} while ($row_unnattachedpoints = mysql_fetch_assoc($unnattachedpoints));
  $rows = mysql_num_rows($unnattachedpoints);
  if($rows > 0) {
      mysql_data_seek($unnattachedpoints, 0);
	  $row_unnattachedpoints = mysql_fetch_assoc($unnattachedpoints);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">      Channel:</td>
      <td><select name="ChannelID" id="ChannelID">
        <?php
do {  
?>
        <option value="<?php echo $row_unnattachedpoints['channelid']?>"><?php echo $row_unnattachedpoints['channeldescription']?></option>
        <?php
} while ($row_unnattachedpoints = mysql_fetch_assoc($unnattachedpoints));
  $rows = mysql_num_rows($unnattachedpoints);
  if($rows > 0) {
      mysql_data_seek($unnattachedpoints, 0);
	  $row_unnattachedpoints = mysql_fetch_assoc($unnattachedpoints);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td colspan="2" align="right" nowrap>The "Point and Channel" options should match. If not, you're messing up! Don't ask why.
        <input type="hidden" name="CreatedDate" value="<?php echo date("Y-m-d");?> " size="32">        <input type="hidden" name="CreatedBy" value="0" size="32"></td>
    </tr>
    
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($userprefs);

mysql_free_result($unnattachedpoints);
?>