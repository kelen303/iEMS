<?php
/**
 * setPassword.i.inc.php
 *
 * @package IEMS
 * @name Password IFrame
 * @author Marian C. Buford, Rearview Enterprises, Inc.
 * @copyright Copyright Conservation Resource Solutions, Inc. 2008.
 * @version 2.0
 * @access public
 *
 * @abstract A simple IFrame file for handling the Password Preference functionality.
 *
 *
 * @uses includes/setPassword.inc.php
 *
 */
	$queryString = '';
	foreach($_GET as $key=>$value)
	{
		if($queryString != '')
		{
			$queryString .= '&';
		}
		$queryString .= $key.'='.$value;
	}
?>
<div style="padding-left: 100px">
<iframe allowTransparency="true" frameborder="0" src="includes/setPassword.inc.php?<?php echo $queryString; ?>" width="700" height="300" ></iframe>
</div>