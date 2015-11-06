<?php
 /**
 * setEmail.inc.php
 *
 * @package IEMS
 * @name E-Mail
 * @author Marian C. Buford, Rearview Enterprises, Inc.
 * @copyright Copyright Conservation Resource Solutions, Inc. 2008.
 * @version 2.0
 * @access public
 *
 * @abstract Generates the page for the E-Mail Preference functionality.
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
</head>


<?php


$userID = $_REQUEST['userID'];
$username = $_REQUEST['username'];
$emailForm = '
	<div id="setEmailContainer" style="margin-top: 55px;">
		<form id="emailForm_id" class="emailForm" action="setEmail.inc.php" method="POST">
			<table cellpadding="5" cellspacing="0" border="0">
				<tr>
					<td>Current Email:</td><td><input type="text" class="validate[\'required\',\'email\']" name="curEmail" value="" /></td>
				</tr>
	                 <td><label for="newEmail">New Email:</label></td>  
	                 <td><input id="newEmail" class="validate[\'required\',\'email\']" name="newEmail" type="text" /></td>  
	             </tr>  
	             <tr>  
	                 <td><label for="newEmailConfirm">Confirm Email</label></td>  
	                 <td><input id="newEmailConfirm" class="validate[\'confirm[newEmail]\',\'email\'] name="newEmailConfirm" type="text" /></td>  
	             </tr>  
			</table>
			<br />
			<input type="hidden" name="userID" value="'.$userID.'" />
			<input type="hidden" name="username" value="'.$username.'" />
			<div style="text-align: center"><input type="submit"  id="submitEmail" name="submitEmail" value="Set Email" style="color: #FFFFFF; background-color: #FE944F; border: 1px solid #FF6701; cursor: pointer;"/>
			</div>
		</form>
	</div>
';


	if(isset($_POST['newEmail']) && $_POST['newEmail'] != '')
	{
		$chkSQL = '
			SELECT
				c.ContactValue
			FROM
				t_contacts c
			LEFT JOIN
				t_contacttypes ct
			ON
				c.ContactTypeID = ct.ContactTypeID
			WHERE
				c.ObjectID = '.$_POST['userID'].' and
				ct.ContactTypeName = "email1"
			';

		$result = mysql_query($chkSQL, $master_connection);
		$row = mysql_fetch_assoc($result);
		
		if(trim($_POST['curEmail']) == $row['ContactValue'])
		{
			$emailSQL = '
				UPDATE
					t_contacts,
					t_contacttypes
				SET
					ContactValue = "'.trim($_POST['newEmail']).'"
				WHERE 
					t_contacts.ObjectID = '.$_POST['userID'].' and
					t_contacts.ContactTypeID = t_contacttypes.ContactTypeID and
					t_contacttypes.ContactTypeName = "email1"
					';
			$result = mysql_query($emailSQL, $master_connection);
            $_SESSION['primaryEmailAddress'] = $_POST['newEmail'];
			print '
				<strong>Primary Email has been successfully changed to '.$_SESSION['primaryEmailAddress'].'.</strong>
				<br />
				<form action="../index.php" target="_parent" method="POST">
				<input type="hidden" name="username" value="'.$username.'">
				<input type="submit" value="continue" style="color: #FFFFFF; background-color: #FE944F; border: 1px solid #FF6701; cursor: pointer;"/>
				</form>
			';

            
			
		}
		else
		{
			print '<div class="error">Current Email is not correct.</div>'.$emailForm;
			
		}
		
	}
	else
	{
		print $emailForm;
	}
?>
<script type="text/javascript">
window.addEvent('domready', function() {
        var myCheck = new FormCheck('emailForm_id', {
            display : {
                scrollToFirst : false
            },
            alerts : {
                required : 'This field is required.',
                email : 'This must be a valid Email address.'
            }
        })

    });
  
</script>
</body>
</html>
