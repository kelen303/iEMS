<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<!-- forcing ie8 into compatibility mode here -->
	<meta http-equiv="X-UA-Compatible" content="IE=7.5" >
</head>
<body>
	<?php 
	
	/**
	 * frmMagnify.i.inc.php
	 *
	 * @package IEMS
	 * @name Magnify IFrame
	 * @author Marian C. Buford, Rearview Enterprises, Inc.
	 * @copyright Copyright Conservation Resource Solutions, Inc. 2008.
	 * @version 2.0
	 * @access public
	 *
	 * @abstract A simple IFrame file for handling the Zoom functionality.
	 *
	 *
	 * @uses Connections/crsolutions.php, includes/clsInterface.inc.php
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
	
	$pointSet = explode(',',$_GET['ID']);
	$pointCount = count($pointSet);
	
	
	
	if($_GET['action'] == 'modalPrint'|| $_GET['action'] == 'printEvent')
	{
		
		$printButton = '
			<script type="text/javascript">
			function CheckIsIE()
				{
					if (navigator.appName.toUpperCase() == \'MICROSOFT INTERNET EXPLORER\') { return true;}
					else { return false; }
				}
			function PrintThisPage()
				{
					if (CheckIsIE() == true)
					{
						document.magnifyFrame.focus();
						document.magnifyFrame.print();
					}
					else
					{
						window.frames[\'magnifyFrame\'].focus();
						window.frames[\'magnifyFrame\'].print();
					}
				}
			</script>
			<div style="text-align: right; ">
			<button onClick="PrintThisPage()" class="defaultButton" style="background-color: #FF6701; border: 2px solid #000000;">Print</button>
			</div>
			';
		if($_GET['action'] == 'print')
		{
			$height = '2500';
		}
		else
		{
			if(isset($_GET['isTabular']) && $_GET['isTabular'] == 'true')
			{
				$height = 9500; //(288 * 32) + 300
			}
			else
			{
				$height = 550 + ($pointCount * 360);
			}
		}
			
		$sizeString = 'width="710" height="'.$height.'"';
	
	}
	else
	{
		$printButton = '';
		
		$sizeString = 'width="950" height="'.(510 + (($pointCount) * 30)).'"';
	}
	
	//<link rel="stylesheet" type="text/css" href="_template/crs_php_print.css" media="print">
	
	?>
	
	<?php echo $printButton ?>
	<iframe name="magnifyFrame" scrolling="no" frameborder="0" allowtransparency="true" src="frmMagnify.inc.php?<?php echo htmlspecialchars($queryString); ?>" <?php echo $sizeString; ?> ></iframe>
</body>
</html>
