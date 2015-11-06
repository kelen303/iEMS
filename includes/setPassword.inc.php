<?php
 /**
 * setPassword.inc.php
 *
 * @package IEMS
 * @name Password
 * @author Marian C. Buford, Rearview Enterprises, Inc.
 * @copyright Copyright Conservation Resource Solutions, Inc. 2008.
 * @version 2.0
 * @access public
 *
 * @abstract Generates the page for the Password Preference functionality.
 *
 *
 * @uses Connections/crsolutions.php
 *
 */
define('APPLICATION', TRUE);
define('GROK', TRUE);
define('iEMS_PATH','../');

require_once iEMS_PATH.'displayEventSummary.inc.php';    
require_once iEMS_PATH.'Connections/crsolutions.php';    
require_once iEMS_PATH.'includes/clsInterface.inc.php';   
require_once iEMS_PATH.'includes/clsControlPanel.inc.php'; 

require_once iEMS_PATH.'iEMSLoader.php';
$Loader = new iEMSLoader(false);    //arg: bool(true|false) enables/disables logging to iemslog.txt
                                    //to watch log live, from command-line: tail logpath/logfilename -f

$Prefs = new Preferences;

if (!isset($_SESSION)) session_start();

$mdrUser = new User();
$mdrUser = $_SESSION['UserObject'];

$connection = $mdrUser->sqlConnection(); //passing the return from the connection doc into a generic name
$master_connection = $mdrUser->sqlMasterConnection(); //passing the return from the connection doc into a generic name
?>
<html>
<body>
<head>
<link media="screen" type="text/css" href="../mootools/formcheck_1.4/theme/classic/formcheck.css" rel="stylesheet" />
<style>

.error {
	background-color: none;
	text-align: center;
	font-size: 12px;
	color: #FA6E10;
	vertical-align: top;
	border: 2px dotted #DDDDDD;
	padding: 10px;
}

body {
	background:  transparent;
	color: #FFFFFF;
}
</style>

<script type="text/javascript" src="../mootools/mootools-1.2-core.js"></script>
<script type="text/javascript" src="../mootools/mootools-1.2-more.js"></script>
<script type="text/javascript" src="../mootools/formcheck_1.4/formcheck.js"></script>



<?php
require_once '../Connections/crsolutions.php';

$userID = $_REQUEST['userID'];
$pwForm = '
	<div id="setPasswordContainer" style="margin-top: 35px;">
		<form id="passwordForm_id" class="passwordForm" action="setPassword.inc.php" method="POST">
			<table align="center" cellpadding="10" cellspacing="0" border="0">
				<tr>
					<td style="width: 120px;">
						<label>Current Password:</label><br />
					<input type="password" name="curPassword" class="validate[\'required\']" value="" /></td>
					<td style="width: 120px;">&nbsp;
					 &nbsp;</td>  
					 
				 </tr> 
				 <tr>  
					 <td style="width: 120px;">
						<label for="newPassword" >New Password:</label><br />
						<input id="newPassword" class="validate[\'required\']"  name="newPassword" type="password" />
					</td>  
					 <td style="width: 120px;">
						<label for="newPasswordConfirm" >Confirm Password:</label><br />
						<input id="newPasswordConfirm" class="validate[\'confirm[newPassword]\'] name="newPasswordConfirm" type="password" />
					</td>  
				 </tr>  
			</table>
			<input type="hidden" name="userID" value="'.$userID.'" />
			<br />
			<div style="text-align: center"><input type="submit" id="submitPassword" name="submitPassword" value="Set Password" style="color: #FFFFFF; background-color: #FE944F; border: 1px solid #FF6701; cursor: pointer;"/>
			</div>
		</form>
	</div>
';

	if(isset($_POST['newPassword']) && $_POST['newPassword'] != '')
	{
		$chkSQL = '
			SELECT * 
			FROM
				t_users
			WHERE ObjectID = '.$userID
		;
		$result = mysql_query($chkSQL, $master_connection);
		$row = mysql_fetch_assoc($result);
		
		if(strtolower($_POST['curPassword']) == strtolower($row['Password']))
		{			
			$pwSQL = '
				UPDATE 
					t_users 
				SET
					Password = "'.$_POST['newPassword'].'"
				WHERE 
					ObjectID = '.$userID;
			$result = mysql_query($pwSQL, $master_connection);
			print '<div class="error">Password has been successfully changed.</div>';
		}
		else
		{
			print '<div class="error">Current Password is not correct. Please try again.</div>'.$pwForm;
		}	
	}
	else
	{
		print $pwForm;
	}
?>
<script type="text/javascript">
window.addEvent('domready', function() {
        var myCheck = new FormCheck('passwordForm_id', {
            display : {
                scrollToFirst : false
            },
            alerts : {
                required : 'This field is required.<br/>Please enter a value.'
            }
        })

    });
  
</script>
</body>
</html>
