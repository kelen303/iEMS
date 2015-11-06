<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
class EmailQueue extends CAO {

	private $p_ID;
	private $p_emailToAddress;
    private $p_emailFromAddress;
    private $p_emailFromName;
    private $p_emailReplyToAddress;
    private $p_emailSubject;
    private $p_emailBody;
    private $p_emailAttachments;
    private $p_sendAfter;
    private $p_sendBefore;
    private $p_isSent;
    private $p_messageIdentifier;
	private $p_createdDate;
	private $p_createdBy;
	private $p_updatedDate;
	private $p_updatedBy;

	function ID()
	{
		return $this->p_ID;
	}
	function emailToAddress()
	{
		return $this->p_emailToAddress;
    }
	function emailFromAddress()
	{
		return $this->p_emailFromAddress;
	}
	function emailFromName()
	{
		return $this->p_emailFromName;
	}
	function emailReplyToAddress()
	{
		return $this->p_emailReplyToAddress;
	}
	function emailSubject()
	{
		return $this->p_emailSubject;
	}
	function emailBody()
	{
		return $this->p_emailBody;
	}
	function emailAttachments()
	{
		return $this->p_emailAttachments;
	}
	function sendAfter()
	{
		return $this->p_sendAfter;
	}
	function sendBefore()
	{
		return $this->p_sendBefore;
	}
	function isSent()
	{
		return $this->p_isSent;
	}
	function messageIdentifier()
	{
		return $this->p_messageIdentifier;
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

	function __construct ($ID=0, $emailToAddress="", $emailFromAddress="", $emailFromName="", $emailReplyToAddress="", $emailSubject="", 
                          $emailBody="", $emailAttachments="", $sendAfter=0, $sendBefore=0, $isSent=0, $messageIdentifier="", 
                          $createdDate=0, $createdBy=0, $updatedDate=0, $updatedBy=0)
	{
        parent::__construct();

		$this->p_ID = $ID;
    	$this->p_emailToAddress = $emailToAddress;
        $this->p_emailFromAddress = $emailFromAddress;
        $this->p_emailFromName = $emailFromName;
        $this->p_emailReplyToAddress = $emailReplyToAddress;
        $this->p_emailSubject = $emailSubject;
        $this->p_emailBody = $emailBody;
        $this->p_emailAttachments = $emailAttachments;
        $this->p_sendAfter = ($sendAfter?new CrsDate($sendAfter):null);
        $this->p_sendBefore = ($sendBefore?new CrsDate($sendBefore):null);
        $this->p_isSent = $isSent;
        $this->p_messageIdentifier = $messageIdentifier;
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
        $sql = "select * from t_emailqueue where ";
        if (is_string($key)) 
        {
            $messageIdentifier = mysql_real_escape_string($key);
            $sql .= "MessageIdentifier = '{$messageIdentifier}'";
        } 
        elseif (is_int($key))
        {
            $sql .= "EmailQueueID = {$key}";
        } 
        elseif (is_object($key))
        {
            if ($key->ID())
            {
                $sql .= "EmailQueueID = " . $key->ID();
            }
            else
            {
                $messageIdentifier = mysql_real_escape_string($key->messageIdentifier());
                $sql .= "MessageIdentifier = '{$messageIdentifier}'";
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

    		$this->p_ID = $row["EmailQueueID"];
        	$this->p_emailToAddress = $row["EmailToAddress"];
            $this->p_emailFromAddress = $row["EmailFromAddress"];
            $this->p_emailFromName = $row["EmailFromName"];
            $this->p_emailReplyToAddress = $row["EmailReplyToAddress"];
            $this->p_emailSubject = $row["EmailSubject"];
            $this->p_emailBody = $row["EmailBody"];
            $this->p_emailAttachments = $row["EmailAttachments"];
            $this->p_sendAfter = new CrsDate($row["EmailAfter"]);
            $this->p_sendBefore = new CrsDate($row["EmailBefore"]);
            $this->p_isSent = $row["EmailSent"];
            $this->p_messageIdentifier = $row["MessageIdentifier"];
    		$this->p_createdDate = new CrsDate($row["CreatedDate"]);
    		$this->p_createdBy = $row["CreatedBy"];
    		$this->p_updatedDate = new CrsDate($row["UpdatedDate"]);
    		$this->p_updatedBy = $row["UpdatedBy"];
        }
        else
        {
    		$this->p_ID = 0;
        	$this->p_emailToAddress = "";
            $this->p_emailFromAddress = "";
            $this->p_emailFromName = "";
            $this->p_emailReplyToAddress = "";
            $this->p_emailSubject = "";
            $this->p_emailBody = "";
            $this->p_emailAttachments = "";
            $this->p_sendAfter = null;
            $this->p_sendBefore = null;
            $this->p_isSent = 0;
            $this->p_messageIdentifier = "";
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
            return true;
        } 
        else 
        {
            $sql =  "insert into " .
                        "t_emailqueue " .
                    "(EmailToAddress, " .
                     "EmailFromAddress, " .
                     "EmailFromName, " .
                     "EmailReplyToAddress, " .
                     "EmailSubject, " .
                     "EmailBody, " .
                     "EmailAttachments, " .
                     "SendAfter, " .
                     "SendBefore, " .
                     "IsSent, " .
                     "MessageIdentifier, " .
                     "CreatedDate, " .
                     "CreatedBy) " .
                    "values " .
                    "('" . mysql_real_escape_string($this->p_emailToAddress) . "', " .
                     "'" . mysql_real_escape_string($this->p_emailFromAddress) . "', " .
                     "'" . mysql_real_escape_string($this->p_emailFromName) . "', " .
                     "'" . mysql_real_escape_string($this->p_emailReplyToAddress) . "', " .
                     "'" . mysql_escape_string($this->p_emailSubject) . "', " .
                     "'" . mysql_escape_string($this->p_emailBody) . "', " .
                     "'" . mysql_real_escape_string($this->p_emailAttachments) . "', " .
                     "'" . (is_null($this->p_sendAfter)?"0000-00-00 00:00:00":$this->p_sendAfter->Format("Y-m-d H:i:s")) . "', " .
                     "'" . (is_null($this->p_sendBefore)?"0000-00-00 00:00:00":$this->p_sendBefore->Format("Y-m-d H:i:s")) . "', " .
                     $this->p_isSent . ", " .
                     "'" . $this->p_messageIdentifier . "', " .
                     "'" . date("Y-m-d H:i:s") . "', " .
                     $this->p_createdBy . ")";
        }

        //echo "sql=", $sql, "<br>\n";
        $result = mysql_query($sql, $this->sqlMasterConnection());

        if ($result && !$this->p_ID)
        {
            $sql = 'select EmailQueueID from t_emailqueue where MessageIdentifier=\'' . $this->p_messageIdentifier . '\'';
            $eqid = mysql_query($sql, $this->sqlMasterConnection());
            $row = mysql_fetch_assoc($eqid);
            $this->p_ID = $row['EmailQueueID'];
        }

        return $this->p_ID;
    }
}
?>
