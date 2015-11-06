<?php require_once('../Connections/crsolutions.php'); ?>
<?php
mysql_select_db($database_crsolutions, $crsolutions);
$query_Recordset1 = "SELECT * FROM t_pointchannels t1 Left Join t_pointchannelpreferences t2  on t1.objectid = t2.pointobjectid where t2.userpreferenceid is null";
$Recordset1 = mysql_query($query_Recordset1, $crsolutions) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>testjoin.php</title>
</head>

<body>
<table border="1">
  <tr>
    <td>ObjectID</td>
    <td>ChannelID</td>
    <td>ChannelName</td>
    <td>ChannelDescription</td>
    <td>AssetIdentifier</td>
    <td>RecorderID</td>
    <td>ForeignID</td>
    <td>UnitOfMeasureID</td>
    <td>PulseConversionFactor</td>
    <td>IntervalValueOffset</td>
    <td>DrawLimit</td>
    <td>DrawLimitUnitOfMeasureID</td>
    <td>FirstIntervalDate</td>
    <td>FirstIntervalValue</td>
    <td>LastIntervalDate</td>
    <td>LastIntervalValue</td>
    <td>LastIntervalSetID</td>
    <td>IsGenerator</td>
    <td>IsExportable</td>
    <td>IsPlotable</td>
    <td>IsEnabled</td>
    <td>CreatedBy</td>
    <td>CreatedDate</td>
    <td>UpdatedBy</td>
    <td>UpdatedDate</td>
    <td>UserPreferenceID</td>
    <td>PointObjectID</td>
    <td>ChannelID</td>
    <td>CreatedDate</td>
    <td>CreatedBy</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_Recordset1['ObjectID']; ?></td>
      <td><?php echo $row_Recordset1['ChannelID']; ?></td>
      <td><?php echo $row_Recordset1['ChannelName']; ?></td>
      <td><?php echo $row_Recordset1['ChannelDescription']; ?></td>
      <td><?php echo $row_Recordset1['AssetIdentifier']; ?></td>
      <td><?php echo $row_Recordset1['RecorderID']; ?></td>
      <td><?php echo $row_Recordset1['ForeignID']; ?></td>
      <td><?php echo $row_Recordset1['UnitOfMeasureID']; ?></td>
      <td><?php echo $row_Recordset1['PulseConversionFactor']; ?></td>
      <td><?php echo $row_Recordset1['IntervalValueOffset']; ?></td>
      <td><?php echo $row_Recordset1['DrawLimit']; ?></td>
      <td><?php echo $row_Recordset1['DrawLimitUnitOfMeasureID']; ?></td>
      <td><?php echo $row_Recordset1['FirstIntervalDate']; ?></td>
      <td><?php echo $row_Recordset1['FirstIntervalValue']; ?></td>
      <td><?php echo $row_Recordset1['LastIntervalDate']; ?></td>
      <td><?php echo $row_Recordset1['LastIntervalValue']; ?></td>
      <td><?php echo $row_Recordset1['LastIntervalSetID']; ?></td>
      <td><?php echo $row_Recordset1['IsGenerator']; ?></td>
      <td><?php echo $row_Recordset1['IsExportable']; ?></td>
      <td><?php echo $row_Recordset1['IsPlotable']; ?></td>
      <td><?php echo $row_Recordset1['IsEnabled']; ?></td>
      <td><?php echo $row_Recordset1['CreatedBy']; ?></td>
      <td><?php echo $row_Recordset1['CreatedDate']; ?></td>
      <td><?php echo $row_Recordset1['UpdatedBy']; ?></td>
      <td><?php echo $row_Recordset1['UpdatedDate']; ?></td>
      <td><?php echo $row_Recordset1['UserPreferenceID']; ?></td>
      <td><?php echo $row_Recordset1['PointObjectID']; ?></td>
      <td><?php echo $row_Recordset1['ChannelID']; ?></td>
      <td><?php echo $row_Recordset1['CreatedDate']; ?></td>
      <td><?php echo $row_Recordset1['CreatedBy']; ?></td>
    </tr>
    <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
