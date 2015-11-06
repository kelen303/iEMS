<?php

$DispatchTime = '2010-06-13 04:10:01';

$baseDispatch = date('Y-m-d H:',strtotime($DispatchTime));
$baseMinutes = date('i', strtotime($DispatchTime));
$minutes = date('s', strtotime($DispatchTime)) > 0 ? $baseMinutes + 1 : $baseMinutes;
$formattedDispatch = $baseDispatch.(ceil($minutes/5)*5);

print $formattedDispatch;

?>
