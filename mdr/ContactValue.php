<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
class ContactValue extends CAO {

	private $p_ID;
	private $p_contactValueTypeID;
	private $p_contactValueSubtypeID;
	private $p_contactOwner;
	private $p_contactValue;
	private $p_isInactive;
	private $p_createdDate;
	private $p_createdBy;
	private $p_updatedDate;
	private $p_updatedBy;

    private $p_isAltered;

	function ID()
	{
		return $this->p_ID;
	}
	function contactValueTypeID($contactValueTypeID = null)
	{
        if (isset($contactValueTypeID)) 
        {
            $this->p_contactValueTypeID = $contactValueTypeID;
            $p_isAltered = true;
        }
        else
        {
		    return $this->p_contactValueTypeID;
        }
	}
	function contactValueSubtypeID($contactValueSubtypeID = null)
	{
        if (isset($contactValueSubtypeID))
        {
            $this->p_contactValueSubtypeID = $contactValueSubtypeID;
            $p_isAltered = true;
        }
        else
        {
		    return $this->p_contactValueSubtypeID;
        }
	}
	function contactOwner($contactOwner = null)
	{
        if (isset($contactOwner))
        {
            $this->p_contactOwner = clone $contactOwner;
            $p_isAltered = true;
        }
        else
        {
		    return $this->p_contactOwner;
        }
	}
	function contactValue()
	{
		return $this->p_contactValue;
	}
	function isInactive($isInactive = null)
	{
        if (isset($isInactive))
        {
            $this->p_isInactive = $isInactive;
            $p_isAltered = true;
        }
        else
        {
		    return $this->p_isInactive;
        }
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

    function isAltered()
    {
        return $this->p_isAltered;
    }

	function __construct ($ID=0, $contactValueTypeID=0, $contactValueSubtypeID=0, $contactOwner=null, 
                          $contactValue="", $isInactive=false, $createdDate=0, $createdBy=0, $updatedDate=0, $updatedBy=0)
	{
        parent::__construct();

		$this->p_ID = $ID;
		$this->p_contactValueTypeID = $contactValueTypeID;
		$this->p_contactValueSubtypeID = $contactValueSubtypeID;
		$this->p_contactOwner = ($contactOwner?clone $contactOwner:null);
		$this->p_contactValue = $contactValue;
		$this->p_isInactive = $isInactive;
		$this->p_createdDate = ($createdDate?new CrsDate($createdDate):null);
		$this->p_createdBy = $createdBy;
		$this->p_updatedDate = ($updatedDate?new CrsDate($updatedDate):null);

		$this->p_updatedBy = $updatedBy;

        $this->p_isAltered = ($this->p_ID > 0) ||
                             ($this->p_contactValueTypeID > 0) ||
                             ($this->p_contactValueSubtypeID > 0) ||
                             isset($this->p_contactOwner) ||
                             ($this->p_contactValue > "");
	}

    function __destruct()
    {
        parent::__destruct();
    }

    function Update($contactOwner, $contactValueTypeID, $contactValueSubtypeID, $contactValue, $isInactive, $userId)
    {
        $this->p_contactOwner = clone $contactOwner;
        $this->p_contactValueTypeID = $contactValueTypeID;
        $this->p_contactValueSubtypeID = $contactValueSubtypeID;
        $this->p_contactValue = $contactValue;
        $this->p_isInactive = $isInactive;
        $this->p_updatedBy = $userId;

        $this->Put();
    }

    function Get($key)
    {
		//MCB 2009.10.13 JS reworking to remove left join
		//not sure if this is being used at all: 'where '. . . is that syntactically even possible?
		//and directly following is an else statement that says something about bad bombing -- so I 
		//suspect this is not in use at this time -- discretionary fix.
		//MCB 2009.10.14 Will rework later, if necessary -- want to leave alone for now in order to not tip the apple cart.
        $sql = 'select distinct
                  cv.ContactValueID,
                  cv.ContactValueTypeID,
                  cv.ContactValueSubtypeID,
                  cv.ContactOwnerID,
                  cv.ContactValue,
                  cv.IsInactive,
                  cv.CreatedDate,
                  cv.CreatedBy,
                  cv.UpdatedDate,
                  cv.UpdatedBy,
                  co.ContactOwnerID CoContactOwnerID,
                  co.Name CoName,
                  co.CreatedDate CoCreatedDate,
                  co.CreatedBy CoCreatedBy,
                  co.UpdatedDate CoUpdatedDate,
                  co.UpdatedBy CoUpdatedBy
                from
                  t_contactvalues cv
                  left join t_contactowners co on co.ContactOwnerID = cv.ContactOwnerID
                where ';

        if (is_string($key)) 
        {
            $value = mysql_real_escape_string($key);
            $sql .= "ContactValue = '{$value}'";
        } 
        elseif (is_int($key))
        {
            $sql .= "ContactValueID = {$key}";
        } 
        elseif (is_object($key))
        {
            if ($key->ID())
            {
                $sql .= "ContactValueID = " . $key->ID();
            }
            else
            {
                $value = mysql_real_escape_string($key->ContactValue());
                $sql .= "Name = '{$value}'";
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

            //$this->preDebugger($row);

            if ($row['CoContactOwnerID'] == 0) 
            {
                $contactOwner = new ContactOwner(0,
                                                 '',
                                 				 0,
                                				 0,
                                				 0,
                                				 0);
            }
            else
            {
                $contactOwner = new ContactOwner($row['CoContactOwnerID'],
                                                 $row['CoName'],
                                 				 $row['CoCreatedDate'],
                                				 $row['CoCreatedBy'],
                                				 $row['CoUpdatedDate'],
                                				 $row['CoUpdatedBy']);
            }

			$this->p_ID = $row['ContactValueID'];
			$this->p_contactValueTypeID = $row['ContactValueTypeID'];
            $this->p_contactValueSubtypeID = $row['ContactValueSubtypeID'];
            $this->p_contactOwner = clone $contactOwner;
            $this->p_contactValue = $row['ContactValue'];
            $this->p_isInactive = $row['IsInactive'];
            $this->p_createdDate = new CrsDate($row['CreatedDate']);
            $this->p_createdBy = $row['CreatedBy'];
            $this->p_updatedDate = new CrsDate($row['UpdatedDate']);
            $this->p_updatedBy = $row['UpdatedBy'];
		} 
        else
        {
			$this->p_ID = 0;
			$this->p_contactValueTypeID = 0;
            $this->p_contactValueSubtypeID = 0;
            $this->p_contactOwner = null;
            $this->p_contactValue = "";
            $this->p_isInactive = false;
            $this->p_createdDate = null;
            $this->p_createdBy = 0;
            $this->p_updatedDate = null;
            $this->p_updatedBy = 0;
        }

        $this->p_isAltered = false;

        return $this->p_ID;
    }

    function Put($userID = null)
    {
        if (isset($userID)) $this->p_updatedBy = $userID;

        if ($this->p_ID)
        {
            $sql = 'update 
                        t_contactvalues 
                    set 
                        ContactOwnerID=' . $this->p_contactOwner->ID() . ',
                        ContactValue=\'' . mysql_real_escape_string($this->p_contactValue) . '\', 
                        ContactValueTypeID=' . $this->p_contactValueTypeID . ',
                        ContactValueSubtypeID=' . $this->p_contactValueSubtypeID . ',
                        IsInactive=' . ($this->p_isInactive?1:0) . ',
                        UpdatedDate=\'' . date("Y-m-d H:i:s") . '\',
                        UpdatedBy=' . $this->p_updatedBy . '
                    where
                        ContactValueID=' . $this->p_ID;
        } 
        else 
        {
            $createdDate = new CrsDate(date('Y-m-d H:i:s'));

            $sql = 'insert into
                        t_contactvalues
                    (ContactValueTypeID,
                     ContactValueSubtypeID,
                     ContactOwnerID,
                     ContactValue,
                     IsInactive,
                     CreatedDate,
                     CreatedBy)
                    values
                    (' . $this->p_contactValueTypeID . ',
                     ' . $this->p_contactValueSubtypeID . ',
                     ' . $this->p_contactOwner->ID() . ',
                     \'' . mysql_real_escape_string($this->p_contactValue) . '\',
                     ' . ($this->p_isInactive?1:0) . ',
                     \'' . date("Y-m-d H:i:s") . '\',
                     ' . $this->p_createdBy . ')';
        }

        $result = mysql_query($sql,$this->sqlMasterConnection());
        //print '<pre>';
        //print $sql.'<br />';

        //print mysql_error($this->sqlMasterConnection());
        //print '</pre>';

        if ($result && !$this->p_ID)
        {
            $sql = 'select ContactValueID from t_contactvalues where ContactValue=\'' . mysql_real_escape_string($this->p_contactValue) . '\'';
            $cvid = mysql_query($sql,$this->sqlMasterConnection());
            $row = mysql_fetch_assoc($cvid);
            $this->p_ID = $row['ContactValueID'];
        }

        $this->p_isAltered = false;

        return $this->p_ID;
    }
}
?>
