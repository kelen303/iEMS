<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
class ContactOwner extends CAO {

	private $p_ID;
	private $p_name;
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

	function __construct ($ID=0, $name="", $createdDate=0, $createdBy=0, $updatedDate=0, $updatedBy=0)
	{
        parent::__construct();

		$this->p_ID = $ID;
		$this->p_name = $name;
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
        $sql = "select * from t_contactowners where ";
        if (is_string($key)) 
        {
            $name = mysql_real_escape_string($key);
            $sql .= "Name = '{$name}'";
        } 
        elseif (is_int($key))
        {
            $sql .= "ContactOwnerID = {$key}";
        } 
        elseif (is_object($key))
        {
            if ($key->ID())
            {
                $sql .= "ContactOwnerID = " . $key->ID();
            }
            else
            {
                $name = mysql_real_escape_string($key->name());
                $sql .= "Name = '{$name}'";
            }
        }
        else
        {
            // Bad bombing...
        }


        $result = mysql_query($sql,$this->sqlMasterConnection());

        if ($result) 
        {
            $row = mysql_fetch_assoc($result);

    		$this->p_ID = $row["ContactOwnerID"];
    		$this->p_name = $row["Name"];
    		$this->p_createdDate = new CrsDate($row["CreatedDate"]);
    		$this->p_createdBy = $row["CreatedBy"];
    		$this->p_updatedDate = new CrsDate($row["UpdatedDate"]);
    		$this->p_updatedBy = $row["UpdatedBy"];
        }
        else
        {
    		$this->p_ID = 0;
    		$this->p_name = "";
    		$this->p_createdDate = null;
    		$this->p_createdBy = 0;
    		$this->p_updatedDate = null;
    		$this->p_updatedBy = 0;
        }

        return $this->p_ID;
    }

    function Put()
    {
        if ($this->p_ID)
        {
            $sql = 'update t_contactowners set ' .
                    'Name = ' . $this->p_name . ', ' .
                    'UpdatedDate = \'' . date("Y-m-d H:i:s") . '\', ' .
                    'UpdatedBy = ' . $this->p_updatedBy;
        } 
        else 
        {
            $sql = 'insert into
                        t_contactowners
                    (Name,
                     CreatedDate,
                     CreatedBy)
                    values
                    (\'' . mysql_real_escape_string($this->p_name) . '\',
                     \'' . date("Y-m-d H:i:s") . '\',
                     ' . $this->p_createdBy . ')';
        }

        $result = mysql_query($sql,$this->sqlMasterConnection());


        if ($result && !$this->p_ID)
        {
            $sql = 'select ContactOwnerID from t_contactowners where Name=\'' . mysql_real_escape_string($this->p_name) . '\'';
            $coid = mysql_query($sql,$this->sqlMasterConnection());
            $row = mysql_fetch_assoc($coid);
            $this->p_ID = $row['ContactOwnerID'];
        }

        return $this->p_ID;
    }

    function Update($name, $userID)
    {
        $this->p_name = $name;
        $this->p_updatedBy = $userID;

        return $this->Put();
    }
}
?>
