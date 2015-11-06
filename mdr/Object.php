<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
class Object extends CAO {

	private $p_ID;
	private $p_typeID;
	private $p_name;
	private $p_description;
    private $p_assetIdentifier;
	private $p_isInactive;
	private $p_createdDate;
	private $p_createdBy;
	private $p_updatedDate;
	private $p_updatedBy;

	function ID()
	{
		return $this->p_ID;
	}
	function typeID()
	{
		return $this->p_typeID;
	}
	function name()
	{
		return $this->p_name;
	}
	function description()
	{
		return $this->p_description;
	}
    function assetIdentifier()
	{
		return $this->p_assetIdentifier;
	}
	function isInactive()
	{
		return $this->p_isInactive;
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

    function flag()
    {
        return $this->p_flag;
    }

	function __construct ($ID=0, $typeID=0, $name="", $description="", $assetIdentifier="", $createdDate=0, $createdBy=0, $updatedDate=0, $updatedBy=0, $flag=false)
	{
        parent::__construct();

		$this->p_ID = $ID;
		$this->p_typeID = $typeID;
		$this->p_name = $name;
		$this->p_description = $description;
        $this->p_assetIdentifier = $assetIdentifier;
		$this->p_createdDate = ($createdDate?new CrsDate($createdDate):null);
		$this->p_createdBy = $createdBy;
		$this->p_updatedDate = ($updatedDate?new CrsDate($updatedDate):null);
		$this->p_updatedBy = $updatedBy;
        $this->p_flag = $flag;
	}

    function __destruct()
    {
        parent::__destruct();
    }

    function Get($key)
    {
        $sql = "select * from t_objects where ";

        if (is_string($key))
        {
            $value = mysql_real_escape_string($key);
            $sql .= "ObjectName = '{$value}'";
        }
        elseif (is_int($key))
        {
            $sql .= "ObjectID = {$key}";
        }
        elseif (is_object($key))
        {
            if ($key->ID())
            {
                $sql .= "ObjectID = " . $key->ID();
            }
            else
            {
                $value = mysql_real_escape_string($key->name());
                $sql .= "ObjectName = '{$value}'";
            }
        }
        else
        {
            // Bad bombing...
        }

        $result = mysql_query($sql,$this->sqlConnection());

        if ($result)
		{
            $row = mysql_fetch_assoc($result);

            $this->p_ID = $row['ObjectID'];
    		$this->p_name = $row['ObjectName'];
    		$this->p_description = $row['ObjectDescription'];
            $this->p_assetIdentifier = $row['AssetIdentifier'];
    		$this->p_isInactive = $row['IsInactive'];
    		$this->p_createdDate = new CrsDate($row['CreatedDate']);
    		$this->p_createdBy = $row['CreatedBy'];
    		$this->p_updatedDate = new CrsDate($row['UpdatedDate']);
    		$this->p_updatedBy = $row['UpdatedBy'];
        }

        return $this->p_ID;
    }
}
?>
