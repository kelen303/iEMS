<?php
/**
 * crsolutions connections
 *
 * @package IEMS
 * @name CRSolutions Connections
 * @author Marian C. Buford, Rearview Enterprises, Inc.
 * @copyright Copyright Conservation Resource Solutions, Inc. 2008.
 * @version 2.0
 * @access public
 * @abstract mdr.php uses a different connection schema than the rest of the site.  This could most certainly stand some cleanup.
 * {@source}
 */

if(isset($_POST['dsn']))
{
    define('DSN', $_POST['dsn']);
    setcookie('dsn', $_POST['dsn'], time()+3600);
}
elseif(isset($_COOKIE['dsn']))
{
    define('DSN', $_COOKIE['dsn']);
}
else
{
    define('DSN', 'crs-master');
}

define('USERNAME','root');
define('PASSWORD','fc3582');
define('DATABASE','mdr');

define('DSN_M', 'crs-master');
define('USERNAME_M','root');
define('PASSWORD_M','fc3582');
define('DATABASE_M','mdr');
?>
