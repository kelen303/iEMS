<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
class PointType {

	private $p_ID;
	private $p_name;

	function ID()
	{
		return $this->p_ID;
	}

	function name()
	{
		return $this->p_name;
	}
	

	function __construct ($ID, $name)
	{
		$this->p_ID = $ID;
		$this->p_name = $name;
	}
    
}
?>
