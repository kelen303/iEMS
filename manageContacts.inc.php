<?php
 /**
 * manageContacts.inc.php
 *
 * @package IEMS
 * @name Contact Manager
 * @author Marian C. Buford, Rearview Enterprises, Inc.
 * @copyright Copyright Conservation Resource Solutions, Inc. 2008.
 * @version 2.0
 * @access public
 *
 */

//Sets flag which is checked by objects to limit them to being called from a page
//with this flag set. Objects will not run without this flag.

if (!defined('APPLICATION'))
{
    define('APPLICATION', TRUE);
    define('GROK', TRUE);
    define('iEMS_PATH','');

    require_once iEMS_PATH.'Connections/crsolutions.php';

    require_once iEMS_PATH.'iEMSLoader.php';
    $Loader = new iEMSLoader(); //arg: bool(true|false) enables/disables logging to iemslog.txt
    							//to watch log live, from command-line: tail logpath/logfilename -f
}


if(isset($_GET['userID']))
{
	$userID = $_GET['userID'];
}
else
{
	$userID = '';
}
if(isset($_GET['domainID']))
{
	$domainID = $_GET['domainID'];
}
else
{
	$domainID = '';
}

if(isset($_GET['update']))
{
    $userObject = clone $_SESSION['UserObject'];

    $ownerName = $_GET['ownerName'];
    $oldOwnerName = $_GET['oldOwnerName'];

    $contactValueID = $_GET['contactValueId'] + 0;
    $contactValue = $_GET['contactValue'];
    $oldContactValue = $_GET['oldContactValue'];

    $contactValueType = $_GET['contactValueType'] + 0;
    $oldContactValueType = $_GET['oldContactValueType'] + 0;
    $cvType = new ContactValueType();
    $cvtId = $cvType->Get($oldContactValueType);

    $contactValueSubtype = $_GET['contactValueSubtype'] + 0;
    $oldContactValueSubtype = $_GET['oldContactValueSubtype'] + 0;
    $cvsType = new ContactValueSubtype();
    $cvstId = $cvsType->Get($oldContactValueSubtype);

    $newPriority = $_GET['priority'] + 0;
    $oldPriority = $_GET['oldPriority'] + 0;

    $newCvStatus = $_GET['cvStatus'];
    $oldCvStatus = $_GET['oldCvStatus'];

    //echo '$oldCvStatus=\'', $oldCvStatus, "'<br>\n";
    //echo '$newCvStatus=\'', $newCvStatus, "'<br>\n";

    $contactProfileObjectID = $_GET['profileObjectId'] + 0;
    $contactProfileID = $_GET['profileId'] + 0;
    $contactUseID = $_GET['contactUseId'] + 0;

    $contactManager = new ContactManager($domainID, $userID);

    $contactProfile = new ContactProfile();
    $contactProfile->Get($contactProfileID);

    $emailBody = "";

    //echo "newCvStatus={$newCvStatus}, oldCvStatus={$oldCvStatus}, " . strcmp($newCvStatus, $oldCvStatus) . "<br>\n";

    if ($newCvStatus == "DeleteFromAll")
    {
        // We need to delete any reference to this contact value ID
        // regardless of contact profile or use.

        $contactManager->DeleteContactValue($contactValueID);

        $emailBody = "User " . $userObject->fullName() .
                     " of " . $userObject->Domains(0)->description() .
                     " deleted the " . $cvsType->Description() . " " . $cvType->Description() . " contact of " . $oldContactValue .
                     " for all uses and all contact profiles at " . date("H:i:s") .
                     " on " . date("l, F j, Y");
    }
    elseif ($newCvStatus == "DeleteFromUse")
    {
        // We need to delete the reference to this contact value ID
        // from this contact profile for this use.
        $contactProfile->Delete();

        $emailBody = "User " . $userObject->fullName() .
                     " of " . $userObject->Domains(0)->description() .
                     " deleted the " . $cvsType->Description() . " " . $cvType->Description() . " contact of " . $oldContactValue .
                     " for the contact use of " . $contactProfile->contactUse()->description() .
                     " at " . date("H:i:s") .
                     " on " . date("l, F j, Y") .
                     ".\n\nThe affected contact profile is " . $contactProfile->object()->description();
    }
    elseif ($newCvStatus != $oldCvStatus)
    {
        if ($newCvStatus == "InactiveForAll")
        {
            // We need to inactivate the contact value record for this
            // contact value ID.
            $contactValue = new ContactValue();
            $contactValue->Get($contactValueID);

            $contactValue->isInactive(true);
            $contactValue->Put($userID);

            $emailBody = "User " . $userObject->fullName() .
                         " of " . $userObject->Domains(0)->description() .
                         " inactivated the " . $cvsType->Description() . " " . $cvType->Description() . " contact of " . $oldContactValue .
                         " for all uses and all contact profiles at " . date("H:i:s") .
                         " on " . date("l, F j, Y");
        }
        elseif ($newCvStatus == "InactiveForUse")
        {
            // We need to inactivate the contact profile record for this
            // this use for this contact value ID.
            $contactProfile->isInactive(true);
            $contactProfile->Put($userID);

            $emailBody = "User " . $userObject->fullName() .
                         " of " . $userObject->Domains(0)->description() .
                         " inactivated the " . $cvsType->Description() . " " . $cvType->Description() . " contact of " . $oldContactValue .
                         " for the contact use of " . $contactProfile->contactUse()->description() .
                         " at " . date("H:i:s") .
                         " on " . date("l, F j, Y") .
                         ".\n\nThe affected contact profile is " . $contactProfile->object()->description();
        }
        else
        {

            // We need to activate this contact value.
            $contactValue = new ContactValue();
            $contactValue->Get($contactValueID);

            $contactValue->isInactive(false);
            $contactValue->Put($userID);

            $contactProfile->isInactive(false);
            $contactProfile->Put($userID);

            $emailBody = "User " . $userObject->fullName() .
                         " of " . $userObject->Domains(0)->description() .
                         " activated the " . $cvsType->Description() . " " . $cvType->Description() . " contact of " . $oldContactValue .
                         " for the contact use of " . $contactProfile->contactUse()->description() .
                         " at " . date("H:i:s") .
                         " on " . date("l, F j, Y") .
                         ".\n\nThe affected contact profile is " . $contactProfile->object()->description() .
                         " and may have affected other uses and contact profiles as well.";

            //$contactProfile->preDebugger($contactProfile->isInactive());
            //print $emailBody;
        }
    }
    else
    {
        // We are changing any or all of the contact owner name, the
        // contact value type, the contact value subtype, the contact
        // value, and/or the priority.
        $changeCount = 0;
        $isContactValueRecordChanged = false;
        $isContactProfileRecordChanged = false;
        $contactValueBody = '';

        $oldCv = new ContactValue();
        $oldCv->Get($oldContactValue);

        if ($oldContactValue != $contactValue)
        {
            // The contact value is changing.  The new contact value may
            // represent an entirely new contact value or it may be an
            // existing contact value...
            $newCv = new ContactValue();
            $newCv->Get($contactValue);

            if ($newCv->ID())
            {
                // This is an existing contact value.  We will update the contact profile
                // record with the ID of this contact value.
                $contactProfile->Update($newCv, $userID);
            }
            else
            {
                // The user is changing the contact value to one that does not exist in the
                // database.  In this case, we simply change the value on the contact value
                // record.
                $newCv = new ContactValue($oldCv->ID(), $oldCv->contactValueTypeID(), $oldCv->contactValueSubtypeID(),
                                          $oldCv->contactOwner(), $contactValue, $oldCv->isInactive(), $oldCv->createdDate(),
                                          $oldCv->createdBy(), $oldCv->updatedDate(), $oldCv->updatedBy());
            }

            $changeCount++;
            $contactValueBody = "\to changed the contact value from '" . $oldContactValue . "' to '" . $contactValue . "'";
            //echo $contactValueBody . "<br>\n";
        }
        else
        {
            $newCv = clone $oldCv;
        }

        $owner = new ContactOwner();
        $ownerBody = "";
        if (strtolower($oldOwnerName) != strtolower($ownerName))
        {
        // There has been a change from the old owner name of this contact...
            if (!$oldOwnerName)
            {
                // There was no old owner name, so we are adding an owner name to this contact.
                // This may be a new name or an existing name.  If it is a new name, we will
                // insert it into the database.
                $ownerBody = "\to assigned the owner name '{$ownerName}'";
            }
            else
            {
                // There was an old owner name, so we are changing this name to another name.  This
                // other name may or may not exist in the database.
                $ownerBody = "\to changed the owner name from '{$oldOwnerName}' to '{$ownerName}'";
            }

            $changeCount++;

            $ownerID = $owner->Get($ownerName);
            if (!$ownerID)
            {
                $owner = new ContactOwner(0, $ownerName, 0, $userID);
                $ownerID = $owner->Put();
            }

            $newCv->contactOwner($owner);
        }
        elseif ($ownerName != $oldOwnerName)
        {
            // We are correcting the spelling of the existing owner name...
            $changeCount++;
            $ownerBody = "\to corrected the owner name of '{$oldOwnerName}' to '{$ownerName}'";
            echo $ownerBody . "<br>\n";

            $ownerID = $owner->Get($oldOwnerName);
            $ownerID = $owner->Update($ownerName, $userID);
        }
        else
        {
            // The user did not change the owner name, so lookup the existing owner.
            $ownerID = $owner->Get($oldOwnerName);
        }

        $contactValueTypeBody = "";
        if ($oldContactValueType != $contactValueType)
        {
            // The user cchanged the contact type...
            $old = $cvType->Description();
            $cvtId = $cvType->Get($contactValueType);

            $newCv->contactValueTypeID($contactValueType);

            $changeCount++;
            $contactValueTypeBody = "\to changed the contact type from '" . $old . "' to '" . $cvType->Description() . "'";
            //echo $contactValueTypeBody . "<br>\n";
        }

        $contactValueSubtypeBody = "";
        if ($oldContactValueSubtype != $contactValueSubtype)
        {
            // The user cchanged the contact type...
            $old = $cvsType->Description();
            $cvstId = $cvsType->Get($contactValueSubtype);

            $newCv->contactValueSubtypeID($contactValueSubtype);

            $changeCount++;
            $contactValueSubtypeBody = "\to changed the contact subtype from '" . $old . "' to '" . $cvsType->Description() . "'";
            //echo $contactValueSubtypeBody . "<br>\n";
        }

        $contactPriorityBody = "";
        if ($oldPriority != $newPriority)
        {
            $oldPriority = $contactProfile->priority()->level();

            $priority = new Priority();
            $priority->Get($newPriority);

            $contactProfile->priority($priority);
            $contactProfile->Put($userID);

            $changeCount++;
            $contactPriorityBody = "\to changed the contact priority from '" . $oldPriority . "' to '" . $priority->level() . "'";
            //echo $contactPriorityBody . "<br>\n";
        }

        if ($changeCount)
        {
            $newCv->Put($userID);

            $emailBody = "User " . $userObject->fullName() .
                         " of " . $userObject->Domains(0)->description() .
                         " has made the following update" . ($changeCount == 1?"":"s") .
                         " to the " . $contactProfile->contactUse()->description() . " " . $cvsType->Description() . " " . $cvType->Description() .
                         " contact of " . $oldContactValue . "\n\n" .
                         (strlen($contactValueBody)?$contactValueBody . "\n":"") .
                         (strlen($ownerBody)?$ownerBody . "\n":"") .
                         (strlen($contactValueTypeBody)?$contactValueTypeBody . "\n":"") .
                         (strlen($contactValueSubtypeBody)?$contactValueSubtypeBody . "\n":"") .
                         (strlen($contactPriorityBody)?$contactPriorityBody . "\n":"") . "\n" .
                         "at " . date("H:i:s") .
                         " on " . date("l, F j, Y") . ".\n\nThe affected contact profile is " .
                         $contactProfile->object()->description();
        }
    }

    if (strlen($emailBody))
    {
        $emailToAddress = $contactManager->GetDatabaseMonitors();
        $emailFromAddress = "iems@crsolutions.us";
        $emailFromName = "iEMS at CRS, Inc.";
        $emailReplyToAddress = "donotreply@crsolutions.us";
        $emailSubject = "iEMS: Contact Management Updates for " . $contactProfile->object()->description() ;
        $emailAttachments = "";
        $messageIdentifier = "iEMS" . "." .
                             $userID . "." .
                             $domainID . "." .
                             date("Ymd.His");

        //echo "emailToAddress='{$emailToAddress}'<br>\nemailBody='{$emailBody}'<br>\nmessageIdentifier='$messageIdentifier'<br>\n";
        $emailQueue = new EmailQueue(0, $emailToAddress, $emailFromAddress, $emailFromName, $emailReplyToAddress, $emailSubject, $emailBody, "", 0, 0, 0, $messageIdentifier, 0, $userID);
        $emailQueue->Put();

        print viewProfiles($userID, $domainID, null, $emailBody);
    }
    else
    {
        print viewProfiles($userID, $domainID);
    }
}
elseif (isset($_GET['add']))
{
    $ownerName = $_GET['ownerName'];
    $cvTypeID = $_GET['CvType'] + 0;
    $cvSubtypeID = $_GET['CvSubtype'] + 0;
    $contactValue = $_GET['contactValue'];
    $contactProfileObjectID = $_GET['profileObjectId'] + 0;
    $contactUseID = $_GET['contactUseId'] + 0;
    $priorityID = $_GET['priority'] + 0;
    $dpoEmail = $_GET['dpoEmail'] + 0;
    $dptEmail = $_GET['dptEmail'] + 0;
    $dpoPhone = $_GET['dpoPhone'] + 0;
    $dptPhone = $_GET['dptPhone'] + 0;

    // We have the particulars.  We need to verify that the owner name is new.  If
    // it is not, we will retrieve the OwnerNameID from the database to be used with
    // the contact value.
    $contactManager = new ContactManager($domainID,$userID);

    $cvTypes = $contactManager->GetContactValueTypes();
    $priority = new Priority();
    $priority->Get($priorityID);
    /*
    echo '$dpoEmail=\'',$dpoEmail,"'<br>\n";
    echo '$cvTypes[$cvTypeID]->name()=\'', $cvTypes[$cvTypeID]->name(), "'<br>\n";
    echo '$priority->level()=\'', $priority->level(), "'<br>\n";
    */

    // mcb 2010.06.03
    // need to identify to which profile the error message belongs.

    if ($dpoEmail &&
        ($cvTypes[$cvTypeID]->name() == "email") &&
        ($priority->level() == 1))
    {
        $errorMessage[$contactProfileObjectID] = '<div class="error" style="width: 700px;">You cannot add a primary email contact to this profile before lowering the priority of the existing primary email contact.</div><br />' . "\n";
        print viewProfiles($userID, $domainID, $errorMessage);
    }
    elseif ($dptEmail &&
            ($cvTypes[$cvTypeID]->name() == "email") &&
            ($priority->level() == 2))
    {
        $errorMessage[$contactProfileObjectID] = '<div class="error" style="width: 700px;">You cannot add a secondary email contact to this profile before lowering the priority of the existing secondary email contact.</div><br />' . "\n";
        print viewProfiles($userID, $domainID, $errorMessage);
    }
    elseif ($dpoPhone &&
            ($cvTypes[$cvTypeID]->name() == "phone") &&
            ($priority->level() == 1))
    {
        $errorMessage[$contactProfileObjectID] = '<div class="error" style="width: 700px;">You cannot add a primary phone contact to this profile before lowering the priority of the existing primary phone contact.</div><br />' . "\n";
        print viewProfiles($userID, $domainID, $errorMessage);
    }
    elseif ($dptPhone &&
            ($cvTypes[$cvTypeID]->name() == "phone") &&
            ($priority->level() == 2))
    {
        $errorMessage[$contactProfileObjectID] = '<div class="error" style="width: 700px;">You cannot add a secondary phone contact to this profile before lowering the priority of the existing secondary phone contact.</div><br />' . "\n";
        print viewProfiles($userID, $domainID, $errorMessage);
    }
    else
    {
        // We have an owner name, so we will look it up or create it.
        $owner = new ContactOwner();
        $ownerID = $owner->Get($ownerName);
        if (!$ownerID)
        {
            //echo 'this is a new owner<br />';
            // This owner name is not in the database.  We will insert this name.
            $owner = new ContactOwner(0, $ownerName, 0, $userID);
            $ownerID = $owner->Put();
        }
        /*
        else
        {
            echo 'this is an existing owner -- '.$ownerID.'<br />';
        }
        */

        // Now we must verify that the contact value is new.  If it is not, we will retrieve the
        // ContactValueID from the database to be used with the contact profile.
        $value = new ContactValue();
        $contactValueID = $value->Get($contactValue);
        if (!$contactValueID)
        {
            //echo 'this is a new contact value<br />';
            // This contact value is not in the database.  We will insert this value.
            $value = new ContactValue(0, $cvTypeID, $cvSubtypeID, $owner, $contactValue, 0, $userID);
            $contactValueID = $value->Put();
        }
        elseif ($value->contactOwner()->ID() != $ownerID)
        {
            //echo 'contact value exists<br />';
            $value->Update($owner, $cvTypeID, $cvSubtypeID, $contactValue, $value->isInactive(), $userID);
        }

        // Get the contact profile object record...
        $object = new Object();
        $objectID = $object->Get($contactProfileObjectID);
        //print "Contact Profile Object='{$objectID}'<br>";

        // Get the use record...
        $contactUse = new ContactUse();
        $contactUseID = $contactUse->Get($contactUseID);
        //print "Contact Use Object='{$contactUseID}'<br>";

        // At this point, we should be ready to insert the new t_contactprofiles record...
        $contactProfile = new ContactProfile(0, $object, $contactUse, $priority, $value, 0, $userID);
        $thisID = $contactProfile->Put();
/*
        print $thisID;
        print '<pre>';
        print_r($contactProfile);
        print '</pre>';


        print "OwnerName='{$ownerName}', OwnerID='{$ownerID}'<br>";
        print "ContactValue='{$contactValue}', ContactValueID='{$contactValueID}'<br>";
        print "New Contact Profile ID='{$thisID}'<br>";
    */

        $userObject = new Object();
        $userObject->Get($userID);
        $domainObject = new Object();
        $domainObject->Get($domainID);

        $userObject = clone $_SESSION['UserObject'];

        $cvType = $cvTypes[$cvTypeID]->description();

        $emailToAddress = $contactManager->GetDatabaseMonitors();
        $emailFromAddress = "iems@crsolutions.us";
        $emailFromName = "iEMS at CRS, Inc.";
        $emailReplyToAddress = "donotreply@crsolutions.us";
        $emailSubject = "iEMS: Contact Management Updates for " . $contactProfile->object()->description() ;
        $emailBody = "User " . $userObject->fullName() . " of " . $userObject->Domains(0)->description() . " added the " . $contactUse->description() . " " . $cvType . " contact of " .
                     $contactValue . " for owner of " . $ownerName . " with a priority of " . $priority->level() . " to the contact profile " . $object->description() . " at " . date("H:i:s") . " on " . date("l, F j, Y") . ".";
        $emailAttachments = "";
        $messageIdentifier = "iEMS" . "." . $userID . "." . $domainID . "." . date("Ymd.His");

        //echo "emailToAddress='{$emailToAddress}'<br>\nemailBody='{$emailBody}'<br>\nmessageIdentifier='$messageIdentifier'<br>\n";
        $emailQueue = new EmailQueue(0, $emailToAddress, $emailFromAddress, $emailFromName, $emailReplyToAddress, $emailSubject, $emailBody, "", 0, 0, 0, $messageIdentifier, 0, $userID);
        $emailQueue->Put();

        print viewProfiles($userID, $domainID, null, $emailBody);
    }
}

function viewProfiles($userID, $domainID, $errorMessage = null, $alertMessage = null)
{
    if (isset($_POST['ContactUse']))
    {
        $contactUse = $_POST['ContactUse'];
        $basicProfiles = $_POST['basicProfiles'];

        $_SESSION['ContactUse'] = $contactUse;
        $_SESSION['basicProfiles'] = $basicProfiles;
    }
    else
    {
        $contactUse = $_SESSION['ContactUse'];
        $basicProfiles = $_SESSION['basicProfiles'];
    }

    $vpSpin = 0;
    if (isset($_SESSION['VpSpin']))
    {
        $vpSpin = $_SESSION['VpSpin'];
        $vpSpin++;
    }

    $_SESSION['VpSpin'] = $vpSpin;

    $contactManager = new ContactManager($domainID, $userID);

    $priorities = $contactManager->GetPriorities();
    $contactUses = $contactManager->GetContactUses();
    $uniqueProfiles = $contactManager->GetUniqueProfiles();
    $contactValueTypes = $contactManager->GetContactValueTypes();
    $contactValueSubtypes = $contactManager->GetContactValueSubtypes();

    $prioritySelect = "";
    $priorityOne = null;
    $priorityTwo = null;
    foreach ($priorities as $inx=>$priority) {
        $prioritySelect .= "<option value=\"" . $priority->ID() . "\">" . humanPriority($priority->description()) . "</option>\n";
        if ($priority->level() == 1) $priorityOne = clone $priority;
        if ($priority->level() == 2) $priorityTwo = clone $priority;
    }

    $cvtSelect = "";
    $cvtEmail = null;
    $cvtPhone = null;
    foreach ($contactValueTypes as $inx=>$cvTypes) {
        $cvtSelect .= "<option value=\"" . $cvTypes->ID() . "\">" . $cvTypes->description() . "</option>\n";
        if ($cvTypes->Name() == "email") $cvtEmail = clone $cvTypes;
        if ($cvTypes->Name() == "phone") $cvtPhone = clone $cvTypes;
    }

    $cvsSelect = "";
    foreach ($contactValueSubtypes as $inx=>$cvSubtypes) {
        $cvsSelect .= "<option value=\"" . $cvSubtypes->ID() . "\">" . $cvSubtypes->description() . "</option>\n";
    }

    foreach ($contactUses as $inx=>$cu) {
        if ($contactUse == $cu->name()) {
            $contactUse = clone $cu;
            break;
        }
    }

    $form = '';

    $btnIndex = 0;

    //preDebugger($basicProfiles);

    foreach ($basicProfiles as $iny=>$state) {
        $uniqueProfile = clone $uniqueProfiles[$iny];
        $upID = $uniqueProfile->ID();

        $form .= "<input type=\"hidden\" name=\"ContactUse\" value=\"" . $contactUse->ID() . "\"/>";


//mcb 2010.06.03
// if valuable, this can display the error on top of the form set
        //if (is_array($errorMessage) && array_key_exists($uniqueProfile->ID(), $errorMessage)) $form .= $errorMessage[$uniqueProfile->ID()];

        $form .= '<table cellpadding="5" cellspacing="0" border="0"></thead><tr><th colspan="8" align="center">' . $uniqueProfile->description() . ' for ' . $contactUse->description() . " Notifications</th></tr>\n";
        $form .= "<tr align=\"left\">
            <th style=\"border-bottom: 1px solid #FFFFFF;\">&nbsp;</th>
			<th style=\"border-bottom: 1px solid #FFFFFF;\">Owner</th>
			<th style=\"border-bottom: 1px solid #FFFFFF;\">Type</th>
			<th style=\"border-bottom: 1px solid #FFFFFF;\">Location</th>
			<th style=\"border-bottom: 1px solid #FFFFFF;\">Value</th>
			<th style=\"border-bottom: 1px solid #FFFFFF;\">Priority</th>
			<th style=\"border-bottom: 1px solid #FFFFFF;\">Status</th>
			<th style=\"border-bottom: 1px solid #FFFFFF;\">&nbsp;</th></tr></thead>\n";
        $form .= "<tbody>\n";

        $contactProfiles = $contactManager->GetContactProfiles($uniqueProfiles[$iny], $contactUse);
        $availablePriority = 0;
        $lineCount = 0;
        for ($inx=0; $inx<count($contactProfiles); $inx++)
        {
            $lineCount++;
            $ownerName = $contactProfiles[$inx]->contactValue()->contactOwner()->name();
            //print $ownerName;
            $availablePriority = max($availablePriority, $contactProfiles[$inx]->priority()->level());

            $coID = (strlen($ownerName)?$contactProfiles[$inx]->contactValue()->contactOwner()->ID():-$inx);
            $cpID = $contactProfiles[$inx]->ID();
            $cvID = $contactProfiles[$inx]->contactValue()->ID();

            $isInactiveForAll = $contactProfiles[$inx]->contactValue()->isInactive();
            if ($isInactiveForAll) {
                $isActive = false;
                $isInactiveForUse = false;
            } else {
                $isInactiveForUse = $contactProfiles[$inx]->isInactive();
                $isActive = !$isInactiveForUse;
            }

            $coKey = "[" . $vpSpin . "][" . $upID . "][" . $coID . "]";
            $idKey = "[" . $vpSpin . "][" . $upID . "][" . $cvID . "]";

            $form .= //"<form id=\"updateContactForm_" . $vpSpin . "_" . $upID . "_" . $cvID . "\" method=POST action=\"#\" onsubmit=\"return validate('". $contactValueTypes[$contactProfiles[$inx]->contactValue()->contactValueTypeID()]->description() ."', $('ContactValue[" . $vpSpin . "][" . $upID . "][" . $cvID . "]'))\"><tr align=\"left\">" .
            			"<tr align=\"left\">" .
                        "<td class=\"cmTable\">" .
                        $lineCount .
                        "</td>\n" .
                         "<td class=\"cmTable\">" .
                            "<input class=\"cmForm\" name=\"OwnerName" . $coKey . "\" id=\"OwnerName" . $coKey . "\" type=\"text\" size=\"20\" value=\"" . $ownerName . "\" \/>" .
                            "<input class=\"cmForm\" name=\"OldOwnerName" . $coKey . "\" id=\"OldOwnerName" . $coKey . "\" type=\"hidden\" value=\"" . $ownerName . "\" \/>" .
                         "</td>\n" .
                         "<td class=\"cmTable\">\n" .
                            "<select name=\"ContactValueType" . $idKey . "\" id=\"ContactValueType" . $idKey . "\">";
                            foreach ($contactValueTypes as $iny=>$cvType) {
                                $form .= "<option value=\"" . $cvType->ID() . "\"" . ($contactProfiles[$inx]->contactValue()->contactValueTypeID() == $cvType->ID()?" selected>":">") . $cvType->description() . "</option>\n";
                            }
                   $form .= "</select>\n" .
                            "<input name=\"OldContactValueType" . $idKey . "\" id=\"OldContactValueType" . $idKey . "\" type=\"hidden\" value=\"" .$contactProfiles[$inx]->contactValue()->contactValueTypeID() . "\"/>" .
                         "</td>\n" .
                         "<td class=\"cmTable\">\n" .
                            "<select name=\"ContactValueSubtype" . $idKey . "\" id=\"ContactValueSubtype" . $idKey . "\">";
                            foreach ($contactValueSubtypes as $iny=>$cvSubtype) {
                                $form .= "<option value=\"" . $cvSubtype->ID() . "\"" . ($contactProfiles[$inx]->contactValue()->contactValueSubtypeID() == $cvSubtype->ID()?" selected>":">") . $cvSubtype->description() . "</option>\n";
                            }
                   $form .= "</select>\n" .
                            "<input name=\"OldContactValueSubtype" . $idKey . "\" id=\"OldContactValueSubtype" . $idKey . "\" type=\"hidden\" value=\"" .$contactProfiles[$inx]->contactValue()->contactValueSubtypeID() . "\"/>" .
                         "</td>\n" .

                         "<td class=\"cmTable\">\n" .
                            "<input name=\"ContactValue" . $idKey . "\" id=\"ContactValue" . $idKey . "\" type=\"text\" size=\"30\" value=\"" . $contactProfiles[$inx]->contactValue()->contactValue() . "\" \>" .
                            "<input name=\"OldContactValue" . $idKey . "\" id=\"OldContactValue[" . $vpSpin . "][" . $upID . "][" . $cvID . "]\" type=\"hidden\" value=\"" . $contactProfiles[$inx]->contactValue()->contactValue() . "\" \/>" .
                         "</td>\n" .
                         "<td class=\"cmTable\">\n" .
                            "<select name=\"Priority" . $idKey . "\" id=\"Priority" . $idKey . "\">";
                            foreach ($priorities as $iny=>$priority) {
                                if (($contactProfiles[$inx]->contactValue()->contactValueTypeID() == $cvtEmail->ID()) &&
                                    ($contactManager->hasPriorityOneEmail() &&
                                     ($contactProfiles[$inx]->priority()->ID() != $priorityOne->ID()) &&
                                     ($priority->ID() == $priorityOne->ID())))
                                {
                                    continue;
                                }
                                elseif (($contactProfiles[$inx]->contactValue()->contactValueTypeID() == $cvtEmail->ID()) &&
                                        ($contactManager->hasPriorityTwoEmail() &&
                                         ($contactProfiles[$inx]->priority()->ID() != $priorityTwo->ID()) &&
                                         ($priority->ID() == $priorityTwo->ID())))
                                {
                                    continue;
                                }
                                elseif (($contactProfiles[$inx]->contactValue()->contactValueTypeID() == $cvtPhone->ID()) &&
                                        ($contactManager->hasPriorityOnePhone() &&
                                         ($contactProfiles[$inx]->priority()->ID() != $priorityOne->ID()) &&
                                         ($priority->ID() == $priorityOne->ID())))
                                {
                                    continue;
                                }
                                elseif (($contactProfiles[$inx]->contactValue()->contactValueTypeID() == $cvtPhone->ID()) &&
                                        ($contactManager->hasPriorityTwoPhone() &&
                                         ($contactProfiles[$inx]->priority()->ID() != $priorityTwo->ID()) &&
                                         ($priority->ID() == $priorityTwo->ID())))
                                {
                                    continue;
                                }
                                else
                                {
                                    $form .= "<option value=\"" . $priority->ID() . "\"" . ($contactProfiles[$inx]->priority()->ID() == $priority->ID()?" selected>":">") . humanPriority($priority->description()) . "</option>\n";
                                }
                            }
                   $form .= "</select>\n" .
                         "<input name=\"OldPriority" . $idKey . "\" id=\"OldPriority" . $idKey . "\" type=\"hidden\" value=\"" . $contactProfiles[$inx]->priority()->ID() ."\"/>" .
                         "</td>\n" .
                         "<td class=\"cmTable\">\n" .
                            "<select name=\"CvStatus" . $idKey . "\" id=\"CvStatus" . $idKey . "\"><option value=\"Active\"" . ($isActive?">":" selected>") . "Active</option>\n" .
                                                                                                  "<option value=\"InactiveForUse\"" . ($isInactiveForUse?" selected>":">") . "Inactive for Use</option>\n" .
                                                                                                  "<option value=\"InactiveForAll\"" . ($isInactiveForAll?" selected>":">") . "Inactive for All</option>\n" .
                                                                                                  "<option value=\"DeleteFromUse\">Delete from Use</option>\n" .
                                                                                                  "<option value=\"DeleteFromAll\">Delete from All</option>\n" .
                            "</select>" .
                            "<input name=\"OldCvStatus" . $idKey . "\" id=\"OldCvStatus" . $idKey . "\" type=\"hidden\" value=\"" . ($isActive?"Active":($isInactiveForUse?"InactiveForUse":"InactiveForAll")) . "\"\/>" .
                         "</td>\n" .
                         "<td class=\"cmTable\">
                            <input type=\"submit\" id=\"updateContactValue.$btnIndex\" name=\"updateContactValue[" . $upID . "]\" value=\"Update\" class=\"cpButton\" onClick=\"if(validate('Update','". $contactValueTypes[$contactProfiles[$inx]->contactValue()->contactValueTypeID()]->description() ."', $('ContactValue" . $idKey . "'))){processContactUpdates(" . $vpSpin . ", " . $upID . ", " . $cpID . ", " . $coID . ", " . $cvID . ", " . $contactUse->ID() . ",this.id);}\" />
                            </td>\n" .
                          "<td id=\"updateMessage.$btnIndex\" width=\"20\" onClick=\"javascript:dojo.byId(this.id).innerHTML = '';\"></td>" .
                         //"<td class=\"cmTable\"><input type=\"submit\" id=\"updateContactValue\" name=\"updateContactValue[" . $upID . "]\" id=\"updateContactValue[" . $upID . "]\" value=\"Update\" class=\"cpButton\" onClick=\"processContactUpdates(" . $vpSpin . ", " . $upID . ", " . $cpID . ", " . $coID . ", " . $cvID . ", " . $contactUse->ID() . ")\" /></td>\n" .
                         //"<td class=\"cmTable\"><input type=\"submit\" id=\"updateContactValue\" name=\"updateContactValue[" . $upID . "]\" value=\"Update\" class=\"cpButton\"  /></td>\n" .
                     //"</tr></form>\n";
                     "</tr>\n";

                   $btnIndex++;
        }
        $form .= "<tr><td colspan=\"7\">&nbsp;</td></tr>";

// mcb 2010.06.03
        if (is_array($errorMessage) && array_key_exists($uniqueProfile->ID(), $errorMessage)) $form .= '<tr><td colspan="7">' . $errorMessage[$uniqueProfile->ID()] . '</td></tr>';

        $form .= "<tr align=\"left\">" .
                    "<td>&nbsp;</td>" .
                   "<td class=\"cmTable\"><input name=\"OwnerName[" . $vpSpin . "][" . $upID . "]\" id=\"OwnerName[" . $vpSpin . "][" . $upID . "]\" type=\"text\" size=\"20\"></td>\n" .
                   "<td class=\"cmTable\"><select class=\"cmForm\" name=\"CvType[" . $vpSpin . "][" . $upID . "]\" id=\"CvType[" . $vpSpin . "][" . $upID . "]\">" . $cvtSelect . "</select></td>\n" .
                   "<td class=\"cmTable\"><select class=\"cmForm\" name=\"CvSubtype[" . $vpSpin . "][" . $upID . "]\" id=\"CvSubtype[" . $vpSpin . "][" . $upID . "]\">" . $cvsSelect . "</select></td>\n" .
                   "<td class=\"cmTable\"><input class=\"cmForm\" name=\"ContactValue[" . $vpSpin . "][" . $upID . "]\" id=\"ContactValue[" . $vpSpin . "][" . $upID . "]\" type=\"text\" size=\"30\"></td>\n" .
                   "<td class=\"cmTable\">\n" .
                        "<select name=\"Priority[" . $vpSpin . "][" . $upID . "]\" id=\"Priority[" . $vpSpin . "][" . $upID . "]\">" . $prioritySelect . "</select>\n" .
                        "<input name=\"DenyPriorityOneEmail[" . $vpSpin . "][" . $upID . "]\" id=\"DenyPriorityOneEmail[" . $vpSpin . "][" . $upID . "]\" type=\"hidden\" value=\"" . ($contactManager->hasPriorityOneEmail()?"1":"0") ."\"/>\n" .
                        "<input name=\"DenyPriorityTwoEmail[" . $vpSpin . "][" . $upID . "]\" id=\"DenyPriorityTwoEmail[" . $vpSpin . "][" . $upID . "]\" type=\"hidden\" value=\"" . ($contactManager->hasPriorityTwoEmail()?"1":"0") ."\"/>\n" .
                        "<input name=\"DenyPriorityOnePhone[" . $vpSpin . "][" . $upID . "]\" id=\"DenyPriorityOnePhone[" . $vpSpin . "][" . $upID . "]\" type=\"hidden\" value=\"" . ($contactManager->hasPriorityOnePhone()?"1":"0") ."\"/>\n" .
                        "<input name=\"DenyPriorityTwoPhone[" . $vpSpin . "][" . $upID . "]\" id=\"DenyPriorityTwoPhone[" . $vpSpin . "][" . $upID . "]\" type=\"hidden\" value=\"" . ($contactManager->hasPriorityTwoPhone()?"1":"0") ."\"/>\n" .
                   "</td>\n" .
                   "<td  class=\"cmTable\" align=\"center\"><input type=\"submit\" id=\"newContactValue\" name=\"newContactValue[" . $vpSpin . "][" . $upID . "]\" value=\"Add\" class=\"cpButton\" onClick=\"if(validate('Add','CvType[" . $vpSpin . "][" . $upID . "]', $('ContactValue[" . $vpSpin . "][" . $upID . "]'))){processContactAdditions(" . $vpSpin . ", " . $upID . ", " . $contactUse->ID() . ")}\" /></td>\n" .
                   //"<td  class=\"cmTable\" align=\"center\"><input type=\"submit\" id=\"newContactValue[" . $vpSpin . "][" . $upID . "]\" name=\"newContactValue[" . $vpSpin . "][" . $upID . "]\" value=\"Add\" class=\"cpButton\" onClick=\"processContactAdditions(" . $vpSpin . ", " . $upID . ", " . $contactUse->ID() . ")\" /></td>\n" .
                   "<td class=\"cmTable\">&nbsp;</td>\n" .
                 "</tr>\n";

        $form .= "</tbody></table><br>\n";


        $associatedPoints = $contactManager->GetAssociatedPoints($upID);
        //$contactManager->preDebugger($associatedPoints);

        $formItems = '';
        for ($inx=0; $inx<count($associatedPoints); $inx++) {
            if($associatedPoints[$inx]->description())
            {
                $activeString = $associatedPoints[$inx]->isInactive() ? '[Inactive]' : '';
                $formItems .= "<li>" . $associatedPoints[$inx]->description() . " : Asset " . $associatedPoints[$inx]->assetIdentifier() . " " . $activeString . "</li>\n";
            }
        }

        $form .= "<div style=\"text-align: left;\">The above contacts are associated with the following points:<br><br><ul>\n";

        $form .= $formItems == '' ? 'None' : $formItems;

        $form .= "</ul></div>";

        if($formItems == '')
        {
            $noAssociation =  '<div style="text-align: center;"><span style="color: red; font-weight: bold;">The '.$uniqueProfile->description().' is not associated with any ISO-NE Registered Assets</span></div>';
        }
        else
        {
            $noAssociation = null;
        }
        $form = $noAssociation.$form;
    }

	return $form;
}

function viewContactReport($userID, $domainID, $CSVFlag, $programId,$includeInactive)
{
    //print $includeInactive ? 'inactive included' : 'inactive excluded';

    $userObject = clone $_SESSION['UserObject'];

    $contactManager = new ContactManager($domainID, $userID);

    $programString['csv'] = '';
    $programString['standard'] = '';

    if($programId != '')
    {
        $programList = $_SESSION['UserObject']->PointChannels()->participationTypeList();
        if(array_key_exists($programId,$programList))
        {
            $programString['csv'] = $programList[$programId];
            $programString['standard'] = '<strong>' . $programList[$programId] . '</strong><br />';
        }
    }

    $contactReport = $contactManager->GetContactReport($programId);
    $limit = count($contactReport);

    $pointString = '';

    if ($limit) 
    {
    	if($CSVFlag === true)
        {
                $viewContactReport = 'Contact Summary Report for ' .
                                    $userObject->localDomain()->description() . "\n" .
                                    $programString['csv'] . "\n" .
                                    'Profile,' .
                                    'Name,' .
                                    'Use,' .
                                    'Type,' .
                                    'Location,' .
                                    'Priority,' .
                                    'Contact,' .
                                    'Status' . "\n";
                for ($inx=0; $inx<$limit; $inx++) {
                    if($contactReport[$inx]->status() == 'Active' || ($contactReport[$inx]->status() != 'Active' && $includeInactive))
                    {
                    $viewContactReport .= $contactReport[$inx]->profile() . ',' .
                                      (strlen($contactReport[$inx]->name())?'"'.$contactReport[$inx]->name().'"':'') . ',' .
                                      $contactReport[$inx]->contactUse() . ',' .
                                      $contactReport[$inx]->type() . ',' .
                                      $contactReport[$inx]->location() . ',' .
                                      $contactReport[$inx]->priorityLevel() . ',' .
                                      $contactReport[$inx]->contactValue() . ',' .
                                      $contactReport[$inx]->status() . "\n";
                }
            }
        }
        else
        {
    		$viewContactReport = '<table align="right" cellpadding="0" cellspacing="0" border="0">'."\n";
			$viewContactReport .= '<tr>'."\n";
			$viewContactReport .= '<td class="export"><a href="#" id="exportTableTip" onClick="processBasicCSVExportWithProgram(\''.rtrim($pointString,',').'\',\''.$domainID.'\',\''.$programId.'\');" ><img src="_template/images/blank.gif" height="31" width="31" border="0" /><a/></td>'."\n";
			$viewContactReport .= '</tr>'."\n";
			$viewContactReport .= '</table>'."\n";

            $viewContactReport .= '<div>' .
                                '<div style="text-align: center; margin-bottom: 10px;">' .
                                    'Contact Summary Report<br />' .
                                    '<strong>'. $userObject->localDomain()->description() . '</strong><br/>' .
                                    $programString['standard'] .
                                '</div>' .
                                '<div style="margin-left: 30px;">' .
                                '<table class="sortable" border="0" cellspacing="0" cellpadding="5">' .
                                    '<thead>' .
                                        '<tr> ' .
                                            '<th style="border-bottom: 2px solid; border-top: 2px solid; border-left: 2px solid; font-size: 12px;">&nbsp;</th>' .
                                            '<th style="border-bottom: 2px solid; border-top: 2px solid; font-size: 12px; border-left: 1px solid;">Profile</th>' .
                                            '<th style="border-bottom: 2px solid; border-top: 2px solid; font-size: 12px; border-left: 1px solid;">Name</th>' .
                                            '<th style="border-bottom: 2px solid; border-top: 2px solid; font-size: 12px; border-left: 1px solid;">Use</th>' .
                                            '<th style="border-bottom: 2px solid; border-top: 2px solid; font-size: 12px; border-left: 1px solid;">Type</th>' .
                                            '<th style="border-bottom: 2px solid; border-top: 2px solid; font-size: 12px; border-left: 1px solid;">Location</th>' .
                                            '<th style="border-bottom: 2px solid; border-top: 2px solid; font-size: 12px; border-left: 1px solid;">Priority</th>' .
                                            '<th style="border-bottom: 2px solid; border-top: 2px solid; font-size: 12px; border-left: 1px solid;">Contact</th>' .
                                            '<th style="border-bottom: 2px solid; border-top: 2px solid; border-right: 2px solid; font-size: 12px; border-left: 1px solid;">Status</th></tr>' .
                                    '</thead>' .
                                    '<tbody>';
            $recordNumber = 1;
            for ($inx=0; $inx<$limit; $inx++) {
                
                if($contactReport[$inx]->status() == 'Active' || ($contactReport[$inx]->status() != 'Active' && $includeInactive))
                {
                    $viewContactReport .= '<tr><td style="border-bottom: 1px solid; border-left: 1px solid; font-size: 12px;" align="right">'.$recordNumber++.'</td>' .
                                          '<td style="border-bottom: 1px solid; border-left: 1px solid; font-size: 12px;" align="left">' . $contactReport[$inx]->profile() . '</td>' .
                                          '<td style="border-bottom: 1px solid; border-left: 1px solid; font-size: 12px;" align="left">' . (strlen($contactReport[$inx]->name())?$contactReport[$inx]->name():'&nbsp;') . '</td>' .
                                          '<td style="border-bottom: 1px solid; border-left: 1px solid; font-size: 12px;" align="left">' . $contactReport[$inx]->contactUse() . '</td>' .
                                          '<td style="border-bottom: 1px solid; border-left: 1px solid; font-size: 12px;" align="left">' . $contactReport[$inx]->type() . '</td>' .
                                          '<td style="border-bottom: 1px solid; border-left: 1px solid; font-size: 12px;" align="left">' . $contactReport[$inx]->location() . '</td>' .
                                          '<td style="border-bottom: 1px solid; border-left: 1px solid; font-size: 12px;" align="right">' . $contactReport[$inx]->priorityLevel() . '</td>' .
                                          '<td style="border-bottom: 1px solid; border-left: 1px solid; font-size: 12px;" align="left">' . $contactReport[$inx]->contactValue() . '</td>' .
                                          '<td style="border-bottom: 1px solid; border-left: 1px solid; border-right: 1px solid; font-size: 12px;" align="left">' . $contactReport[$inx]->status() . '</td></tr>';
                }
            }

            $viewContactReport .= '</tbody></table></div></div>';
        }
    } else {
        $viewContactReport = '<div class="error" style="width: 700px;">There are no contacts available to report upon.</div>' . "\n";
    }

    return $viewContactReport;
}



function humanPriority($value)
{

    $humanArray['Primary'] = '1st';
    $humanArray['Secondary'] = '2nd';
    $humanArray['Tertiary'] = '3rd';
    $humanArray['Quaternary'] = '4th';
    $humanArray['Quinary'] = '5th';
    $humanArray['Senary'] = '6th';
    $humanArray['Septenary'] = '7th';
    $humanArray['Octonary'] = '8th';
    $humanArray['Nonary'] = '9th';
    $humanArray['Denary'] = '10th';

    return $humanArray[$value];
}

function preDebugger($data,$color='purple')
{
    print '<pre style="color: '.$color.';">';
    print_r($data);
    print '</pre>';
}
?>
