<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
class Priority extends CAO {

	private $p_ID;
	private $p_name;
	private $p_description;
	private $p_note;
    private $p_level;
	private $p_createdDate;
	private $p_createdBy;
	private $p_updatedDate;
	private $p_updatedBy;

	function ID()
	{
		return $this->p_ID;
	}
	function name()
	{
		return $this->p_name;
	}
	function description()
	{
		return $this->p_description;
	}
	function note()
	{
		return $this->p_note;
	}
	function level()
	{
		return $this->p_level;
	}
	function createdDate()
	{
		return $this->p_createdDate;
	}
	function createdBy()
	{
		return $this->p_createdBy;
	}
	function updatedDate()
	{
		return $this->p_updatedDate;
	}
	function updatedBy()
	{
		return $this->p_updatedBy;
	}

	function __construct ($ID=0, $name="", $description="", $note="", $level=0, $createdDate=0, $createdBy=0, $updatedDate=0, $updatedBy=0)
	{
        parent::__construct();

		$this->p_ID = $ID;
		$this->p_name = $name;
		$this->p_description = $description;
		$this->p_note = $note;
        $this->p_level = $level;
		$this->p_createdDate = ($createdDate?new CrsDate($createdDate):null);
		$this->p_createdBy = $createdBy;
		$this->p_updatedDate = ($updatedDate?new CrsDate($updatedDate):null);
		$this->p_updatedBy = $updatedBy;
	}

    function __destruct()
    {
        parent::__destruct();
    }

    function Get($key)
    {
        $sql = "select * from t_priorities where ";

        if (is_string($key)) 
        {
            $value = mysql_real_escape_string($key);
            $sql .= "PriorityName = '{$value}'";
        } 
        elseif (is_int($key))
        {
            $sql .= "PriorityID = {$key}";
        } 
        elseif (is_object($key))
        {
            if ($key->ID())
            {
                $sql .= "PriorityID = " . $key->ID();
            }
            else
            {
                $value = mysql_real_escape_string($key->name());
                $sql .= "PriorityName = '{$value}'";
            }
        }
        else
        {
            // Bad bombing...
        }

        //echo $sql,"<br>";
        $result = mysql_query($sql,$this->sqlConnection());

        if ($result)
		{
            $row = mysql_fetch_assoc($result);

            $this->p_ID = $row['PriorityID'];
    		$this->p_name = $row['PriorityName'];
    		$this->p_description = $row['PriorityDescription'];
    		$this->p_note = $row['PriorityNote'];
    		$this->p_level = $row['PriorityLevel'];
    		$this->p_createdDate = new CrsDate($row['CreatedDate']);
    		$this->p_createdBy = $row['CreatedBy'];
    		$this->p_updatedDate = new CrsDate($row['UpdatedDate']);
    		$this->p_updatedBy = $row['UpdatedBy'];
        }

        return $this->p_ID;
    }
}
?>
