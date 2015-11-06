<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
class Type {

	private $p_ID;
	private $p_name;
	private $p_description;
	private $p_note;
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

	function __construct ($ID, $name, $description, $note, $createdDate, $createdBy, $updatedDate, $updatedBy)
	{
		$this->p_ID = $ID;
		$this->p_name = $name;
		$this->p_description = $description;
		$this->p_note = $note;
		$this->p_createdDate = new CrsDate($createdDate);
		$this->p_createdBy = $createdBy;
		$this->p_updatedDate = new CrsDate($updatedDate);
		$this->p_updatedBy = $updatedBy;
	}

    function Get($table, $key)
    {
        $sql = "select * from t_contactuses where ";

        if (is_string($key)) 
        {
            $value = mysql_real_escape_string($key);
            $sql .= "ContactUseName = '{$value}'";
        } 
        elseif (is_int($key))
        {
            $sql .= "ContactUseID = {$key}";
        } 
        elseif (is_object($key))
        {
            if ($key->ID())
            {
                $sql .= "ContactUseID = " . $key->ID();
            }
            else
            {
                $value = mysql_real_escape_string($key->name());
                $sql .= "ContactUseName = '{$value}'";
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

            $this->p_ID = $row['ContactUseID'];
    		$this->p_name = $row['ContactUseName'];
    		$this->p_description = $row['ContactUseDescription'];
    		$this->p_note = $row['ContactUseNote'];
    		$this->p_createdDate = new CrsDate($row['CreatedDate']);
    		$this->p_createdBy = $row['CreatedBy'];
    		$this->p_updatedDate = new CrsDate($row['UpdatedDate']);
    		$this->p_updatedBy = $row['UpdatedBy'];
        }

        return $this->p_ID;
    }
}
?>
