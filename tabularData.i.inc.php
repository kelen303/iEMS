<?php 
/**
 * tabularData.i.inc.php
 *
 * @package IEMS
 * @name Tabular Data IFrame
 * @author Marian C. Buford, Rearview Enterprises, Inc.
 * @copyright Copyright Conservation Resource Solutions, Inc. 2008.
 * @version 2.0
 * @access public
 * @deprecated 
 * @abstract Leave this in place until we are sure that it is no longer in use.
 *
 *
 * @uses tabularData.inc.php
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

$height = 3000;

?>

<?php echo $printButton ?>
<iframe name="tableFrame" scrolling="no" frameborder="0" allowtransparency="true" src="tabularData.inc.php?<?php echo $queryString; ?>" <?php echo $sizeString; ?> ></iframe>
