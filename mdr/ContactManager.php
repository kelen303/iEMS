<?php
if(!defined('GROK')){header('HTTP/1.0 404 not found'); exit; }
//
//===========================================================================
//
class ContactManager extends CAO {

    private $p_associatedPoints;

    private $p_uniqueProfiles;
    private $p_contactProfiles;
	private $p_contactUses;
	private $p_priorities;
    private $p_contactValues;
    private $p_contactValueSubtypes;
    private $p_contactValueTypes;
    private $p_databaseMonitors;

    private $p_hasPriorityOneEmail;
    private $p_hasPriorityOnePhone;
    private $p_hasPriorityTwoEmail;
    private $p_hasPriorityTwoPhone;

    private $p_contactReport = array();

	private $p_domainID;

	private $p_userID;

    function associatedPoints()
    {
        return $this->p_associatedPoints;
    }

	function uniqueProfiles()
	{
		return $this->p_uniqueProfiles;
	}

	function contactProfiles()
	{
		return $this->p_contactProfiles;
	}

	function contactUses()
	{
		return $this->p_contactUses;

	function priorities()
	{
		return $this->p_priorities;
	}
	}

	function contactValueSubtypes()
	{
		return $this->$p_contactValueSubtypes;
	}

	function contactValueTypes()
	{
		return $this->$p_contactValueTypes;
	}

    function databaseMonitors()
    {
        return $this->p_databaseMonitors;
    }

    function hasPriorityOneEmail()
    {
        return $this->p_hasPriorityOneEmail;
    }

    function hasPriorityTwoEmail()
    {
        return $this->p_hasPriorityTwoEmail;
    }

    function hasPriorityOnePhone()
    {
        return $this->p_hasPriorityOnePhone;
    }

    function hasPriorityTwoPhone()
    {
        return $this->p_hasPriorityTwoPhone;
    }

	function __construct($domainID, $userID)
	{
        parent::__construct();

		$this->p_domainID = $domainID;
		$this->p_userID = $userID;
	}

    function __destruct()
    {
        parent::__destruct();
    }

	function GetUniqueProfiles($all = true)
	{
		$sql = '
			select distinct
				cpo.*
			from
				t_objects do,
				t_objecttypes dot,
				t_actorprivilegexrefs apx,
				t_privileges pr,
				t_pointcontactprofiles pcp,
				t_objects cpo
			where
				do.ObjectID = '.$this->p_domainID.' and
				dot.ObjectTypeID = do.ObjectTypeID and
				dot.ObjectTypeName = \'Domain\' and
				apx.ObjectID = do.ObjectID and
				pr.PrivilegeID = apx.PrivilegeID and
				pcp.PointObjectID = pr.ObjectID and
				cpo.ObjectID = pcp.ContactObjectID and
                cpo.IsInactive = 0
			order by
				cpo.ObjectDescription
		';

        //print $sql;
		$result = mysql_query($sql, $this->sqlMasterConnection());

		$inx = 0;
        $this->p_uniqueProfiles = array();
		while($row = mysql_fetch_assoc($result))
		{
                $this->p_uniqueProfiles[$inx++] = new Object(
                    $row['ObjectID'],
                    $row['ObjectTypeID'],
                    $row['ObjectName'],
                    $row['ObjectDescription'],
                    $row['IsInactive'],
                    $row['CreatedDate'],
                    $row['CreatedBy'],
                    $row['UpdatedDate'],
                    $row['UpdatedBy'],
                    $this->hasAssociatedPoints($row['ObjectID']));
		}

        return $this->p_uniqueProfiles;
	}

    function hasAssociatedPoints($ObjectID)
    {
        foreach($this->GetAssociatedPoints($ObjectID) as $pointObject) {
            if($pointObject->description())
            {
                return true;
            }
        }
        return false;
    }

	function GetContactUses()
	{
		$sql = '
			select distinct
			  cu.*
			from
			  t_objects do,
			  t_objecttypes dot,
			  t_actorprivilegexrefs apx,
			  t_privileges pr,
			  t_pointcontactprofiles pcp,
			  t_contactprofiles cp,
			  t_contactuses cu
			where
			  do.ObjectID = '.$this->p_domainID.' and
			  dot.ObjectTypeID = do.ObjectTypeID and
			  dot.ObjectTypeName = \'Domain\' and
			  apx.ObjectID = do.ObjectID and
			  pr.PrivilegeID = apx.PrivilegeID and
			  pcp.PointObjectID = pr.ObjectID and
			  cp.ObjectID = pcp.ContactObjectID and
			  cu.ContactUseID = cp.ContactUseID
		';

		$result = mysql_query($sql,$this->sqlMasterConnection());

		$inx = 0;
        $this->p_contactUses = array();
		while($row = mysql_fetch_assoc($result))
		{
			$this->p_contactUses[$inx++] = new ContactUse($row['ContactUseID'],
                                            		      $row['ContactUseName'],
                                            			  $row['ContactUseDescription'],
                                                          $row['ContactUseNote'],
                                                          $row['CreatedDate'],
                                                          $row['CreatedBy'],
                                                          $row['UpdatedDate'],
                                                          $row['UpdatedBy']);
		}

        return $this->p_contactUses;
	}

	function GetPriorities()
	{
		$sql = '
    			select
	    		  *
		    	from
			      t_priorities
		       ';

		$result = mysql_query($sql,$this->sqlMasterConnection());

		$inx = 0;
        $this->p_priorities = array();
		while($row = mysql_fetch_assoc($result))
		{
			$this->p_priorities[$inx++] = new Priority($row['PriorityID'],
                                            		      $row['PriorityName'],
                                            			  $row['PriorityDescription'],
                                                          $row['PriorityNote'],
                                                          $row['PriorityLevel'],
                                                          $row['CreatedDate'],
                                                          $row['CreatedBy'],
                                                          $row['UpdatedDate'],
                                                          $row['UpdatedBy']);
		}

        return $this->p_priorities;
	}

	function GetAssociatedPoints($contactObjectID)
	{
        $sql = '
            select distinct
                pc.*,
                pcppp.RetirementDate
            from
                t_pointcontactprofiles pcp,
                t_pointchannels pc,
                t_pointchannelprogramparticipationprofiles pcppp
            where
                pcp.ContactObjectID = '.$contactObjectID.'
                AND pc.ObjectID = pcp.PointObjectID
                AND pc.IsEnabled = 1
                AND pcppp.PointObjectID = pc.ObjectID
                AND pcppp.ChannelID = pc.ChannelID
                AND pcppp.IsEnabled = 1
                AND pcppp.IsSendable = 1
                AND pcppp.IsReadyToRespond = 1
            order by
                pc.ChannelDescription
            ';

        //$this->preDebugger($sql);

		$result = mysql_query($sql,$this->sqlMasterConnection());

		$inx = 0;
        unset($this->p_associatedPoints);
        if(mysql_num_rows($result) > 0)
        {
            while($row = mysql_fetch_assoc($result))
            {
                //$this->preDebugger($row,'purple');
                $this->p_associatedPoints[$inx++] = new Object(
                    $row['ObjectID'],
                    $row['ChannelID'],
                    $row['ChannelName'],
                    $row['ChannelDescription'],
                    $row['AssetIdentifier'],
                    $row['CreatedDate'],
                    $row['CreatedBy'],
                    $row['UpdatedDate'],
                    $row['UpdatedBy']);
            }

        }
        else
        {
            $this->p_associatedPoints[$inx++] = new Object();
        }

        return $this->p_associatedPoints;
	}

    function GetContactValueSubtypes()
    {
        $sql = 'select * from t_contactvaluesubtypes';

        $result = mysql_query($sql,$this->sqlMasterConnection());


		$inx = 0;
		while($row = mysql_fetch_assoc($result))
		{
			$this->p_contactValueSubtypes[$row['ContactValueSubtypeID']] = new Type(
				$row['ContactValueSubtypeID'],
				$row['ContactValueSubtypeName'],
				$row['ContactValueSubtypeDescription'],
				$row['ContactValueSubtypeNote'],
				$row['CreatedDate'],
				$row['CreatedBy'],
				$row['UpdatedDate'],
				$row['UpdatedBy']);
		}

        return $this->p_contactValueSubtypes;
    }

    function GetContactValueTypes()
    {
        $sql = 'select * from t_contactvaluetypes';

        $result = mysql_query($sql,$this->sqlMasterConnection());


		$inx = 0;
		while($row = mysql_fetch_assoc($result))
		{
			$this->p_contactValueTypes[$row['ContactValueTypeID']] = new Type(
				$row['ContactValueTypeID'],
				$row['ContactValueTypeName'],
				$row['ContactValueTypeDescription'],
				$row['ContactValueTypeNote'],
				$row['CreatedDate'],
				$row['CreatedBy'],
				$row['UpdatedDate'],
				$row['UpdatedBy']);
		}

        return $this->p_contactValueTypes;
    }

    function GetContactProfiles($contactObject, $contactUse)
    {
        $this->p_hasPriorityOneEmail = false;
        $this->p_hasPriorityTwoEmail = false;
        $this->p_hasPriorityOnePhone = false;
        $this->p_hasPriorityTwoPhone = false;

        $sql = '
    		select
    		  cp.ContactProfileID,
    		  cp.ObjectID,
    		  p.PriorityID,
    		  p.PriorityName,
    		  p.PriorityDescription,
    		  p.PriorityNote,
    		  p.PriorityLevel,
    		  p.CreatedDate pCreatedDate,
    		  p.CreatedBy pCreatedBy,
    		  p.UpdatedDate pUpdatedDate,
    		  p.UpdatedBy pUpdatedBy,
    		  cp.ContactUseID,
    		  cp.IsInactive CpIsInactive,
    		  cv.ContactValueID,
    		  cv.ContactValueTypeID,
    		  cvt.ContactValueTypeName,
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
    		  t_contactprofiles cp,
    		  t_contactvaluetypes cvt,
     		  t_priorities p,
    		  t_contactvalues cv
    		  left join t_contactowners co on co.ContactOwnerID = cv.ContactOwnerID
    		where
    		  cp.ObjectID = '.$contactObject->ID().' and
    		  cp.ContactUseID = '.$contactUse->ID().' and
    		  cv.ContactValueID = cp.ContactValueID and
    		  cvt.ContactValueTypeID = cv.ContactValueTypeID and
    		  p.PriorityID = cp.PriorityID
    		order by
    		  CoName,
    		  ContactValueTypeID,
    		  ContactValueSubTypeID,
    		  ContactValue
    	';
        $result = mysql_query($sql,$this->sqlMasterConnection());
        //$this->preDebugger($sql);
		$inx = 0;
        $this->p_contactProfiles = array();
		while($row = mysql_fetch_assoc($result))
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

			$this->p_contactProfiles[$inx++] = new ContactProfile($row['ContactProfileID'],
                                                                  $contactObject,
                                                                  $contactUse,
                                                                  new Priority($row['PriorityID'],
                                                                               $row['PriorityName'],
                                                                               $row['PriorityDescription'],
                                                                               $row['PriorityNote'],
                                                                               $row['PriorityLevel'],
                                                                               $row['pCreatedDate'],
                                                                               $row['pCreatedBy'],
                                                                               $row['pUpdatedDate'],
                                                                               $row['pUpdatedBy']),
                                                                  new ContactValue($row['ContactValueID'],
                                                                                   $row['ContactValueTypeID'],
                                                                                   $row['ContactValueSubtypeID'],
                                                                                   $contactOwner,
                                                                                   $row['ContactValue'],
                                                                                   $row['CvIsInactive'],
                                                                                   $row['CvCreatedDate'],
                                                                                   $row['CvCreatedBy'],
                                                                                   $row['CvUpdatedDate'],
                                                                                   $row['CvUpdatedBy']),
                                                                  $row['CpIsInactive'],
                                                                  $row['CpCreatedDate'],
                                                                  $row['CpCreatedBy'],
                                                                  $row['CpUpdatedDate'],
                                                                  $row['CpUpdatedBy']);
            if ($row['ContactValueTypeName'] == "email")
            {
                $this->p_hasPriorityOneEmail = $this->p_hasPriorityOneEmail || ($row['PriorityLevel'] == 1);
                $this->p_hasPriorityTwoEmail = $this->p_hasPriorityTwoEmail || ($row['PriorityLevel'] == 2);
            }
            elseif ($row['ContactValueTypeName'] == "phone")
            {
                $this->p_hasPriorityOnePhone = $this->p_hasPriorityOnePhone || ($row['PriorityLevel'] == 1);
                $this->p_hasPriorityTwoPhone = $this->p_hasPriorityTwoPhone || ($row['PriorityLevel'] == 2);
            }
        }
        //$this->preDebugger($this->p_contactProfiles );
        return $this->p_contactProfiles;
    }

    function GetContactValues($contactObjectID)
    {
        $sql = '
            select distinct
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
              t_contactprofiles cp,
              t_contactvalues cv
              left join t_contactowners co on co.ContactOwnerID = cv.ContactOwnerID
            where
              cp.ObjectID = '.$contactObjectID.' and
              cv.ContactValueID = cp.ContactValueID
        ';

        $result = mysql_query($sql,$this->sqlMasterConnection());


		$inx = 0;
        $this->p_contactValues = array();
		while($row = mysql_fetch_assoc($result))
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

			$this->p_contactValues[$inx++] = new ContactValue(
				$row['ContactValueID'],
				$row['ContactValueTypeID'],
				$row['ContactValueSubtypeID'],
                $contactOwner,
                $row['ContactValue'],
                $row['IsInactive'],
				$row['CreatedDate'],
				$row['CreatedBy'],
				$row['UpdatedDate'],
				$row['UpdatedBy']);
		}

        return $this->p_contactValues;
    }

    function DeleteContactValue($id)
    {
        $sql = "delete from t_contactprofiles where ContactValueID={$id}";
        //echo $sql, "<br>\n";
        $result = mysql_query($sql,$this->sqlMasterConnection());

        $sql = "delete from t_contactvalues where ContactValueID={$id}";
        //echo $sql, "<br>\n";
        $result = mysql_query($sql,$this->sqlMasterConnection());
    }

    function GetDatabaseMonitors()
    {
        $sql = "select
                  cv.ContactValue
                from
                  t_objects ldo,
                  t_objecttypes ldot,
                  t_objects mdo,
                  t_objecttypes mdot,
                  t_objectxrefs ldx,
                  t_objectxrefs lmx,
                  t_objectxrefs mdx,
                  t_objectxrefs cmx,
                  t_contactprofiles cp,
                  t_contactuses cu,
                  t_contactvalues cv,
                  t_contactvaluetypes cvt,
                  t_priorities p
                where
                  ldx.ChildObjectID = {$this->p_domainID} and
                  ldo.ObjectID = ldx.ParentObjectID and
                  ldot.ObjectTypeID = ldo.ObjectTypeID and
                  ldot.ObjectTypeName = 'domain' and
                  lmx.ChildObjectID = ldx.ParentObjectID and
                  mdx.ChildObjectID = lmx.ParentObjectID and
                  mdx.ParentObjectID = mdx.ChildObjectID and
                  mdo.ObjectID = mdx.ParentObjectID and
                  mdot.ObjectTypeID = mdo.ObjectTypeID and
                  mdot.ObjectTypeName = 'domain' and
                  cmx.ParentObjectID = mdo.ObjectID and
                  cp.ObjectID = cmx.ChildObjectID and
                  cp.IsInactive = 0 and
                  cu.ContactUseID = cp.ContactUseID and
                  cu.ContactUseName = 'database_monitoring' and
                  cv.ContactValueID = cp.ContactValueID and
                  cvt.ContactValueTypeID = cv.ContactValueTypeID and
                  cvt.ContactValueTypeName = 'email' and
                  cv.IsInactive = 0 and
                  p.PriorityID = cp.PriorityID
                order by
                  p.PriorityLevel";

        $result = mysql_query($sql,$this->sqlMasterConnection());

        $this->p_databaseMonitors = "";
		while($row = mysql_fetch_assoc($result))
        {
            $this->p_databaseMonitors .= $row['ContactValue'] . ", ";
        }

        if (strlen($this->p_databaseMonitors)) $this->p_databaseMonitors = substr($this->p_databaseMonitors, 0, strlen($this->p_databaseMonitors)-2);

        if (!strlen($this->p_databaseMonitors)) {
            $emailQueue = new EmailQueue(0, "kkeegan@crsolutions.us", "iEMS@crsolutions.us", "iEMS at CRS, Inc.", "donotreply@crsolutions.us",
                                         "CRS_ERROR: iEMS Contact Management Error", "No Database Monitor email addresses found for domain {$this->p_domainID}\n\n" .
                                         "sql=\n{$sql}", "", 0, 0, 0, "IEMS." . date("Y-m-d.His"), 0, 1);
            $emailQueue->Put();
        }

        return $this->p_databaseMonitors;
    }

    function GetContactReport($programId)
    {

        $sql = "select distinct
                    cpo.ObjectDescription \"Profile\",
                    co.Name,
                    cu.ContactUseDescription \"Use\",
                    cvt.ContactValueTypeDescription \"Type\",
                    cvs.ContactValueSubtypeDescription \"Location\",
                    p.PriorityLevel,
                    cv.ContactValue,
                    if(cv.IsInactive=1, 'Inactive for All Uses', if(cp.IsInactive=1, 'Inactive for This Use', 'Active')) \"Status\"
                from
                    mdr.t_objects do,
                    mdr.t_pointchannelprogramparticipationprofiles pcppp,
                    mdr.t_participationtypes pat,
                    mdr.t_objecttypes dot,
                    mdr.t_actorprivilegexrefs apx,
                    mdr.t_privileges pr,
                    mdr.t_pointcontactprofiles pcp,
                    mdr.t_contactprofiles cp,
                    mdr.t_objects cpo,
                    mdr.t_contactuses cu,
                    mdr.t_priorities p,
                    mdr.t_contactvaluetypes cvt,
                    mdr.t_contactvaluesubtypes cvs,
                    mdr.t_contactvalues cv
                    left join mdr.t_contactowners co on co.ContactOwnerID = cv.ContactOwnerID
                where
                    do.ObjectID = {$this->p_domainID} and
                    dot.ObjectTypeID = do.ObjectTypeID and
                    dot.ObjectTypeName = 'Domain' and
                    apx.ObjectID = do.ObjectID and
                    pr.PrivilegeID = apx.PrivilegeID and
                    pcp.PointObjectID = pr.ObjectID and
                    pcppp.PointObjectID = pcp.PointObjectID and
                    cp.ObjectID = pcp.ContactObjectID and
                    cu.ContactUseID = cp.ContactUseID and
                    p.PriorityID = cp.PriorityID and
                    cpo.ObjectID = cp.ObjectID and
                    cpo.IsInactive = 0 and
                    cv.ContactValueID = cp.ContactValueID and
                    cvt.ContactValueTypeID = cv.ContactValueTypeID and
                    cvs.ContactValueSubtypeID = cv.ContactValueSubtypeID
                    and pat.ParticipationTypeID = pcppp.ParticipationTypeID
                    and pat.ParticipationTypeID = $programId
                order by
                    cpo.ObjectDescription,
                    co.Name,
                    cu.ContactUseDescription,
                    cvt.ContactValueTypeDescription,
                    cvs.ContactValueSubtypeDescription,
                    cv.ContactValue ";
//$this->preDebugger($sql);
        /* old query:
        $sql = "select distinct
                    cpo.ObjectDescription \"Profile\",
                    co.Name,
            		cu.ContactUseDescription \"Use\",
                    cvt.ContactValueTypeDescription \"Type\",
                    cvs.ContactValueSubtypeDescription \"Location\",
                    p.PriorityLevel,
                    cv.ContactValue,
                    if(cv.IsInactive=1, 'Inactive for All Uses', if(cp.IsInactive=1, 'Inactive for This Use', 'Active')) \"Status\"
                from
                    t_objects do,
                    t_objecttypes dot,
                    t_actorprivilegexrefs apx,
                    t_privileges pr,
                    t_pointcontactprofiles pcp,
                    t_contactprofiles cp,
                    t_objects cpo,
                    t_contactuses cu,
                    t_priorities p,
                    t_contactvaluetypes cvt,
                    t_contactvaluesubtypes cvs,
                    t_contactvalues cv
                    left join t_contactowners co on co.ContactOwnerID = cv.ContactOwnerID
                where
                    do.ObjectID = {$this->p_domainID} and
                    dot.ObjectTypeID = do.ObjectTypeID and
                    dot.ObjectTypeName = 'Domain' and
                    apx.ObjectID = do.ObjectID and
                    pr.PrivilegeID = apx.PrivilegeID and
                    pcp.PointObjectID = pr.ObjectID and
                    cp.ObjectID = pcp.ContactObjectID and
                    cu.ContactUseID = cp.ContactUseID and
                    p.PriorityID = cp.PriorityID and
                    cpo.ObjectID = cp.ObjectID and
                    cpo.IsInactive = 0 and
                    cv.ContactValueID = cp.ContactValueID and
                    cvt.ContactValueTypeID = cv.ContactValueTypeID and
                    cvs.ContactValueSubtypeID = cv.ContactValueSubtypeID
                order by
                    cpo.ObjectDescription,
                    co.Name,
                    cu.ContactUseDescription,
                    cvt.ContactValueTypeDescription,
                    cvs.ContactValueSubtypeDescription,
                    cv.ContactValue";
*/
        $result = mysql_query($sql,$this->sqlMasterConnection());
        //$this->preDebugger($sql);
        //$this->preDebugger(mysql_num_rows($result));
        $inx = 0;
		while($row = mysql_fetch_assoc($result))
        {
            //$this->preDebugger($row);
            $contactReportLineItem = new ContactReportLineItem($row['Profile'],
                                                               $row['Name'],
                                                               $row['Use'],
                                                               $row['Type'],
                                                               $row['Location'],
                                                               $row['PriorityLevel'],
                                                               $row['ContactValue'],
                                                               $row['Status']);
            $this->p_contactReport[$inx++] = $contactReportLineItem;
        }

        return $this->p_contactReport;
    }
}
?>
