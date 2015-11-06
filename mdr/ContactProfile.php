<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
class ContactProfile extends CAO {

	private $p_ID;
	private $p_object;
	private $p_contactUse;
	private $p_priorityID;
	private $p_contactValue;
    private $p_isInactive; 
	private $p_createdDate;
	private $p_createdBy;
	private $p_updatedDate;
	private $p_updatedBy;

	function ID()
	{
		return $this->p_ID;
	}
	function object()
	{
		return $this->p_object;
	}
	function contactUse()
	{
		return $this->p_contactUse;
	}
	function priority($priority = null)
	{
        if (isset($priority))
        {
            $this->p_priority = clone $priority;
        }
        else
        {
		    return $this->p_priority;
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

	function __construct ($ID=0, $object=null, $contactUse=null, $priority=null, $contactValue=null, $isInactive=0, $createdDate=0, $createdBy=0, $updatedDate=0, $updatedBy=0)
	{
        parent::__construct();

		$this->p_ID = $ID;
		$this->p_object = ($object?clone $object:null);
		$this->p_contactUse = ($contactUse?clone $contactUse:null);
		$this->p_priority = ($priority?clone $priority:null);
		$this->p_contactValue = ($contactValue?clone $contactValue:null);
        $this->p_isInactive = $isInactive;
		$this->p_createdDate = ($createdDate?new CrsDate($createdDate):null);
		$this->p_createdBy = $createdBy;
		$this->p_updatedDate = ($updatedDate?new CrsDate($updatedDate):null);
		$this->p_updatedBy = $updatedBy;
	}

    function __destruct()
    {
        parent::__destruct();
    }

    function Delete()
    {
        $sql = "delete from t_contactprofiles where ContactProfileID={$this->p_ID}";
        //echo $sql, "<br>\n";

        $result = mysql_query($sql,$this->sqlMasterConnection());
    }

    function Get($id)
    {
		//MCB 2009.10.13 JS reworking to remove left join
		/*
        $sql = '
            select
              cp.ContactProfileID,
              cob.ObjectID CobObjectID,
              cob.ObjectTypeID CobObjectTypeID,
              cob.ObjectName CobObjectName,
              cob.ObjectDescription CobObjectDescription,
              cob.IsInactive CobIsInactive,
              cob.CreatedDate CobCreatedDate,
              cob.CreatedBy CobCreatedBy,
              cob.UpdatedDate CobUpdatedDate,
              cob.UpdatedBy CobUpdatedBy,
              p.PriorityID,
              p.PriorityName,
              p.PriorityDescription,
              p.PriorityNote,
              p.PriorityLevel,
              p.CreatedDate pCreatedDate,
              p.CreatedBy pCreatedBy,
              p.UpdatedDate pUpdatedDate,
              p.UpdatedBy pUpdatedBy,
              cp.isInactive CpIsInactive,
              cu.ContactUseID CuContactUseID,
              cu.ContactUseName CuContactUseName,
              cu.ContactUseDescription CuContactUseDescription,
              cu.ContactUseNote CuContactUseNote,
              cu.CreatedDate CuCreatedDate,
              cu.CreatedBy CuCreatedBy,
              cu.UpdatedDate CuUpdatedDate,
              cu.UpdatedBy CuUpdatedBy,
              cv.ContactValueID,
              cv.ContactValueTypeID,
              cv.ContactValueSubtypeID,
              cv.ContactOwnerID,
              cv.ContactValue,
              cv.IsInactive CvIsInactive,
              cv.CreatedDate CvCreatedDate,
              cv.CreatedBy CvCreatedBy,
              cv.UpdatedDate CvUpdatedDate,
              cv.UpdatedBy CvUpdatedBy,
              co.ContactOwnerID CoContactOwnerID,
              co.Name CoName,
              co.CreatedDate CoCreatedDate,
              co.CreatedBy CoCreatedBy,
              co.UpdatedDate CoUpdatedDate,
              co.UpdatedBy CoUpdatedBy,
              cp.CreatedDate CpCreatedDate,
              cp.CreatedBy CpCreatedBy,
              cp.UpdatedDate CpUpdatedDate,
              cp.UpdatedBy CpUpdatedBy
            from
              t_objects cob,
              t_contactuses cu,
              t_contactprofiles cp,
              t_contactvalues cv,
              t_priorities p
              left join t_contactowners co on co.ContactOwnerID = cv.ContactOwnerID
            where 
              cp.ContactProfileID = '.$id.' and 
              cv.ContactValueID = cp.ContactValueID and 
              cob.ObjectID = cp.ObjectID and 
              cu.ContactUseID = cp.ContactUseID and
              p.PriorityID = cp.PriorityID
        ';
		*/
		$sql = '
		select
		  cp.ContactProfileID,
		  cob.ObjectID CobObjectID,
		  cob.ObjectTypeID CobObjectTypeID,
		  cob.ObjectName CobObjectName,
		  cob.ObjectDescription CobObjectDescription,
		  cob.IsInactive CobIsInactive,
		  cob.CreatedDate CobCreatedDate,
		  cob.CreatedBy CobCreatedBy,
		  cob.UpdatedDate CobUpdatedDate,
		  cob.UpdatedBy CobUpdatedBy,
		  p.PriorityID,
		  p.PriorityName,
		  p.PriorityDescription,
		  p.PriorityNote,
		  p.PriorityLevel,
		  p.CreatedDate pCreatedDate,
		  p.CreatedBy pCreatedBy,
		  p.UpdatedDate pUpdatedDate,
		  p.UpdatedBy pUpdatedBy,
		  cp.isInactive CpIsInactive,
		  cu.ContactUseID CuContactUseID,
		  cu.ContactUseName CuContactUseName,
		  cu.ContactUseDescription CuContactUseDescription,
		  cu.ContactUseNote CuContactUseNote,
		  cu.CreatedDate CuCreatedDate,
		  cu.CreatedBy CuCreatedBy,
		  cu.UpdatedDate CuUpdatedDate,
		  cu.UpdatedBy CuUpdatedBy,
		  cv.ContactValueID,
		  cv.ContactValueTypeID,
		  cv.ContactValueSubtypeID,
		  cv.ContactOwnerID,
		  cv.ContactValue,
		  cv.IsInactive CvIsInactive,
		  cv.CreatedDate CvCreatedDate,
		  cv.CreatedBy CvCreatedBy,
		  cv.UpdatedDate CvUpdatedDate,
		  cv.UpdatedBy CvUpdatedBy,
		  co.ContactOwnerID CoContactOwnerID,
		  co.Name CoName,
		  co.CreatedDate CoCreatedDate,
		  co.CreatedBy CoCreatedBy,
		  co.UpdatedDate CoUpdatedDate,
		  co.UpdatedBy CoUpdatedBy,
		  cp.CreatedDate CpCreatedDate,
		  cp.CreatedBy CpCreatedBy,
		  cp.UpdatedDate CpUpdatedDate,
		  cp.UpdatedBy CpUpdatedBy
		from
		  t_objects cob,
		  t_contactuses cu,
		  t_contactprofiles cp,
		  t_priorities p,
		  t_contactvalues cv
		  left join t_contactowners co on co.ContactOwnerID = cv.ContactOwnerID
		where 
		  cp.ContactProfileID = '.$id.' and 
		  cv.ContactValueID = cp.ContactValueID and 
		  cob.ObjectID = cp.ObjectID and 
		  cu.ContactUseID = cp.ContactUseID and
		  p.PriorityID = cp.PriorityID
	';
        $result = mysql_query($sql,$this->sqlMasterConnection());

		if ($row = mysql_fetch_assoc($result))
		{
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

			$this->p_ID = $row['ContactProfileID'];
            $this->p_object = new Object($row['CobObjectID'],
                                         $row['CobObjectTypeID'],
                                         $row['CobObjectName'],
                                         $row['CobObjectDescription'],
                                         $row['CobIsInactive'],
                                         $row['CobCreatedDate'],
                                         $row['CobCreatedBy'],
                                         $row['CobUpdatedDate'],
                                         $row['CobUpdatedBy']);
            $this->p_contactUse = new ContactUse($row['CuContactUseID'],
                                                 $row['CuContactUseName'],
                                                 $row['CuContactUseDescription'],
                                                 $row['CuContactUseNote'],
                                                 $row['CuCreatedDate'],
                                                 $row['CuCreatedBy'],
                                                 $row['CuUpdatedDate'],
                                                 $row['CuUpdatedBy']);
            $this->p_priority = new Priority($row['PriorityID'],
                                             $row['PriorityName'],
                                             $row['PriorityDescription'],
                                             $row['PriorityNote'],
                                             $row['PriorityLevel'],
                                             $row['pCreatedDate'],
                                             $row['pCreatedBy'],
                                             $row['pUpdatedDate'],
                                             $row['pUpdatedBy']);
            $this->p_isInactive = $row['CpIsInactive'];
            $this->p_contactValue = new ContactValue($row['ContactValueID'],
                                                     $row['ContactValueTypeID'],
                                                     $row['ContactValueSubtypeID'],
                                                     $contactOwner,
                                                     $row['ContactValue'],
                                                     $row['CvIsInactive'],
                                                     $row['CvCreatedDate'],
                                                     $row['CvCreatedBy'],
                                                     $row['CvUpdatedDate'],
                                                     $row['CvUpdatedBy']);
            $this->p_createdDate = new CrsDate($row['CpCreatedDate']);
            $this->p_createdBy = $row['CpCreatedBy'];
            $this->p_updatedDate = new CrsDate($row['CpUpdatedDate']);
            $this->p_updatedBy = $row['CpUpdatedBy'];
        }
    }

    function Put($userID = null)
    {
        if (isset($userID)) $this->p_updatedBy = $userID;

        if ($this->p_ID)
        {
            $sql = "update t_contactprofiles set " .
                   "ContactValueID=" . $this->p_contactValue->ID() . 
                   ", PriorityID=" . $this->p_priority->ID() .
                   ", IsInactive=" . ($this->p_isInactive?1:0) .
                   ", UpdatedDate='" . date("Y-m-d H:i:s") . 
                   "', UpdatedBy=" . $this->p_updatedBy .
                   " where ContactProfileID = " . $this->p_ID;
        } 
        else 
        {
            $sql = 'insert into
                        t_contactprofiles
                    (ObjectID,
                     ContactUseID,
                     PriorityID,
                     IsInactive,
                     ContactValueID,
                     CreatedDate,
                     CreatedBy)
                    values
                    (' . $this->p_object->ID() . ',
                     ' . $this->p_contactUse->ID() . ',
                     ' . $this->p_priority->ID() . ',
                     ' . ($this->p_isInactive?1:0) . ',
                     ' . $this->p_contactValue->ID() . ',
                     \'' . date("Y-m-d H:i:s") . '\',
                     ' . $this->p_createdBy . ')';
        }

        //$this->preDebugger($sql);
        $result = mysql_query($sql,$this->sqlMasterConnection());

        if ($result && !$this->p_ID)
        {
            $sql = 'select ContactProfileID from t_contactprofiles where ContactUseID=' . $this->p_contactUse->ID() . ' and ContactValueID=' . $this->p_contactValue->ID();            
            $cvid = mysql_query($sql,$this->sqlMasterConnection());
            $row = mysql_fetch_assoc($cvid);
            $this->p_ID = $row['ContactProfileID'];
        }

        return $this->p_ID;
    }

    function Update($contactValue, $isInactive, $userID)
    {
        $this->p_contactValue = clone $contactValue;
        $this->p_isInactive = $isInactive;
        $this->p_updatedBy = $userID;

        return $this->Put();
    }
}
?>
