<?php
/**
 * setTelephone.i.inc.php
 *
 * @package IEMS
 * @name Telephpone IFrame
 * @author Marian C. Buford, Rearview Enterprises, Inc.
 * @copyright Copyright Conservation Resource Solutions, Inc. 2008.
 * @version 2.0
 * @access public
 *
 * @abstract A simple IFrame file for handling the Telephone Preference functionality.
 *
 *
 * @uses includes/setTelephone.inc.php
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

<iframe allowTransparency="true" frameborder="0" src="includes/setTelephone.inc.php?<?php echo $queryString; ?>" width="700" height="350" ></iframe>