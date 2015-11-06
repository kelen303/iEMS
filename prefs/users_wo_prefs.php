<?php require_once('../Connections/crsolutions.php'); ?>
<?php
mysql_select_db($database_crsolutions, $crsolutions);
$query_uers_wo_prefs = "SELECT * FROM t_objects t1 Left Join t_userpreferences t2 on t1.objectid = t2.userobjectid where t1.objecttypeid = '1'  and preferencetypeid is null order by objectdescription";
$uers_wo_prefs = mysql_query($query_uers_wo_prefs, $crsolutions) or die(mysql_error());
$row_uers_wo_prefs = mysql_fetch_assoc($uers_wo_prefs);
$totalRows_uers_wo_prefs = mysql_num_rows($uers_wo_prefs);
?>
<title>View Users With No Preferences</title>
<link href="../_dev/_template/crs_php.inc.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="517" border="0">
  <tr>
    <td width="280"><strong>User Description </strong></td>
    <td width="221"><div align="center"><strong>Username</strong></div></td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_uers_wo_prefs['ObjectDescription']; ?></td>
      <td><div align="center"><?php echo $row_uers_wo_prefs['ObjectName']; ?></div></td>
    </tr>
    <?php } while ($row_uers_wo_prefs = mysql_fetch_assoc($uers_wo_prefs)); ?>
</table>
</body>
</html><?php
mysql_free_result($uers_wo_prefs);
?>
