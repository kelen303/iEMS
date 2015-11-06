<?php
/*  ===============================================================================
    --------------------------------login.ajax.php---------------------------------
                                         v 1.0                                    
                                                                               
    Handles authentication with the iEMS 2.2 system.  Called via XHR request and
    loads the elements which are needed by iEMS to authenticate a user and on
    successful authentication, establish the user object and miscellaneous session
    variables required by the application.
                                                                               
    Created by: Marian C. Buford                                                 
                Conservation Resource Solutions, Inc. (CRS) 
    Created on: 05.30.2010 
    License:    Proprietary
    Copyright:  2010 Conservation Resource Solutions, Inc.  All rights reserved.
                                                                               
    =============================================================================== */

/*  ===============================================================================
        iEMS-specific php, 
    =============================================================================== */
    
    define('APPLICATION', TRUE);                            // legacy
    define('GROK', TRUE);                                   // iEMS 3.0 'cause we spliced some of that work in for 2.2
    define('iEMS_PATH', '');                                // this is where the root of the site is; defined in all php pages.
    
    require_once iEMS_PATH.'Connections/crsolutions.php';   // in iEMS 3.0, connections get loaded by iEMSLoader
    
    require_once iEMS_PATH.'iEMSLoader.php';                // this contains some tidbits like preDebugger() which will 
                                                            // <pre> wrap and print_r out any data or variable passed in.
                                                            // In iEMS 3.0 Loader also takes care of lazy-loading in php
                                                            // objects asthey are needed so we don't need to load everything
                                                            // all at once so that we can maintain sub-second logins no matter
                                                            // how big the client.
    
    $Loader = new iEMSLoader(false);                        // true|false indicates whether we will send troubleshooting 
                                                            //  output to iEMS' log/
    $User = new User();                                     // instantiate the iEMS User object. Capital camel case naming for 
                                                            // objects, lower camel case for functions & variables.
    
    $destination = 'index.php';

    if($User->Login($_POST['username'], $_POST['password'])) 
    {
        $_SESSION['UserObject'] = $User;
        $_SESSION['iemsName'] = $_POST['username'];
        $_SESSION['iemsID'] = $User->id();
        $_SESSION['iemsDID'] = $User->Domains(0)->id();
        $_SESSION['iemsPW'] = $_POST['password'];       // I don't think we use this anymore, in iEMS 3.0 we definitely
                                                        // do not, but for now, we'll continue to do so just in case.

        print 'dojo.byId("loginResponse").innerHTML = \'<strong>Welcome iEMS 2.2</strong><br />If you are not redirected in a few seconds, please <a href="'.$destination.'">click here to proceed.</a>\';'."\n";            
        print 'window.location ="'.$destination.'";';

    }
    else
    {
        print 'dojo.byId("loginResponse").innerHTML = "There was a problem with login. Please retype your username and password and try again. If the problem persists contact your Demand Response Provider.";';            
    }
    
    //troubleshooting helpers:
    //$Loader->preDebugger($_POST);
    //$Loader->preDebugger($_SESSION,'#980000');
?>
