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
  $insertSQL = sprintf("INSERT INTO t_userpreferences (UserPreferenceID, PreferenceTypeID, UserObjectID, CreatedDate, CreatedBy) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['UserPreferenceID'], "int"),
                       GetSQLValueString($_POST['PreferenceTypeID'], "int"),
                       GetSQLValueString($_POST['UserObjectID'], "int"),
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
$query_userswithoutprefs = "SELECT t_objects.* FROM t_objects,    t_users LEFT JOIN t_userpreferences ON t_users.ObjectID = t_userpreferences.UserObjectID and   t_userpreferences.PreferenceTypeID=2 WHERE t_userpreferences.UserObjectID IS NULL and    t_objects.ObjectID = t_users.ObjectID ORDER BY ObjectDescription";
$userswithoutprefs = mysql_query($query_userswithoutprefs, $crsolutions) or die(mysql_error());
$row_userswithoutprefs = mysql_fetch_assoc($userswithoutprefs);
$totalRows_userswithoutprefs = mysql_num_rows($userswithoutprefs);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Setup User To Have Prefs</title>
<link href="../_dev/_template/crs_php.inc.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p align="center">All this does is setup a user to have basic preferences. You must still go and add point channels after doing this. Without doing this, the user will nto appear in any drop downs to add preferences. </p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <table align="center">
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">User:</td>
      <td><select name="UserObjectID" id="UserObjectID">
        <?php
do {  
?>
        <option value="<?php echo $row_userswithoutprefs['ObjectID']?>"><?php echo $row_userswithoutprefs['ObjectDescription']?></option>
        <?php
} while ($row_userswithoutprefs = mysql_fetch_assoc($userswithoutprefs));
  $rows = mysql_num_rows($userswithoutprefs);
  if($rows > 0) {
      mysql_data_seek($userswithoutprefs, 0);
	  $row_userswithoutprefs = mysql_fetch_assoc($userswithoutprefs);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record"></td>
    </tr>
  </table>
  <input type="hidden" name="PreferenceTypeID" value="2">
  <input type="hidden" name="CreatedDate" value=<?php echo date("Y-m-d");?> >
  <input type="hidden" name="CreatedBy" value="0">
  <input type="hidden" name="MM_insert" value="form1">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($userswithoutprefs);
?>
