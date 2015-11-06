<?php
 /**
 * setPrefs.ajax.php
 *
 * @package IEMS
 * @name Set Preferences [Zip]
 * @author Marian C. Buford, Rearview Enterprises, Inc.
 * @copyright Copyright Conservation Resource Solutions, Inc. 2008.
 * @version 2.0
 * @access public
 *
 * @abstract Generates the form for the Weather Zip Preference functionality.  This should be integrated with setPrefs.inc.php at some point, and then deprecated.
 *
 *
 * @uses Connections/crsolutions.php, clsControlPanel.inc.php
 *
 */
define('APPLICATION', TRUE);

require_once '../Connections/crsolutions.php';
$connection = $mdrUser->sqlConnection(); //passing the return from the connection doc into a generic name
require_once 'clsControlPanel.inc.php';

$oControlPanel = new controlPanel;
/*
if($_GET['action'] == 'zip')
{
    $sql = '
        UPDATE
            t_contacts,
            t_contacttypes
        SET
            t_contacts.ContactValue = "'.$_POST['zipCode'].'"
        WHERE
            t_contacts.ObjectID = '.$_POST['userID'].' and
            t_contacts.ContactTypeID = t_contacttypes.ContactTypeID and
            t_contacttypes.ContactTypeName = "c_address"
    ';

    mysql_query($sql, $connection);
}

if($_GET['action'] == 'refreshWeather')
{
    require_once 'weather.inc.php';
    $weather = new weather;
    $weatherString = $weather->Load($_POST['zipCode']);

    print '<pre>';
    print $weatherString;

    print rtrim($weatherString,'Error');
}
*/		
?>

