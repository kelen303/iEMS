<?php
if(!isset($_SESSION))
        session_start();
$serverFileIdentifier = $_SESSION['iemsID'] . '-' . $_SESSION['iemsDID'] . '-' . $_SESSION['iemsName'] . '-' . time() . '-';
print '<textarea>';
if($_SERVER['REQUEST_METHOD']=='POST') 
{
    $onServerFileName = 
  move_uploaded_file($_FILES["asset_file"]["tmp_name"], "../files/". $serverFileIdentifier . $_FILES["asset_file"]["name"]);
  print '<div style="width: 650px; text-align: left; overflow: auto;">';
  //echo $_FILES["asset_file"]["tmp_name"];
  //echo '<pre>';
  //echo var_dump($_FILES["asset_file"]);
  //echo '</pre>';
  if(!$_FILES["asset_file"]['error'])
  {
      echo "<p><strong>Your file uploaded successfully.<br />The first few characters of the file we recieved are displayed here:</strong></p><hr />";

      $handle = fopen("../files/". $serverFileIdentifier . $_FILES["asset_file"]["name"], "r");

      echo fread($handle,2500);
      echo ' [ . . . ]';

  }
  else
  {
      echo '<p>There was a problem with your upload. Please try again. If the issue persists, please contact the <a href="http://help.crsolutions.us" target="_blank">CRS Helpdesk</a>.</p>';
  }
  print '</div>';
}
print '</textarea>';
?>