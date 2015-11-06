<?php
/*  ===============================================================================
    ------------------------------------login.php----------------------------------
                                         v 1.0                                    
                                                                               
    Pre-loader for the login process.
                                                                               
    Created by: Marian C. Buford                                                 
                Conservation Resource Solutions, Inc. (CRS) 
    Created on: 06.16.2010
    License:    Proprietary
    Copyright:  2010 Conservation Resource Solutions, Inc.  All rights reserved.
 
    -------------------------------------Notes-------------------------------------
 
    This page doesn't make sense, until you need you get into a situation where
    you need to generate some php variables to be passed into the html file, or if
    the result of php calculations determines which html file will be called.
                                                                               
    =============================================================================== */   
    
    $responseString = '';             
    
    if(isset($_SESSION['UserObject'])) 
    {
        header('location: index.php');
    }
    else
    {
        if(isset($_POST['username']) && isset($_POST['password']))
        {
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

            if($User->Login($_POST['username'], $_POST['password']))
            {
                $responseString = '<strong>Welcome. You have successfully authenticated.</strong><br />If you are not redirected in a few seconds, please <a href="'.$destination.'">click here to proceed.</a>';

                $_SESSION['UserObject'] = $User;
                $_SESSION['iemsName'] = $_POST['username'];
                $_SESSION['iemsID'] = $User->id();
                $_SESSION['iemsDID'] = $User->Domains(0)->id();
                $_SESSION['iemsPW'] =  $_POST['password'];        
                $_SESSION['dsn'] = $_POST['dsn'];
                
                header('location: index.php');
            }
            else
            {
                $responseString = '<div style="color: #2B2B47;">
                    <p>There was a problem with login.</p>
                    <p>Please retype your username and password and try again.</p>
                    <p>If the problem persists contact your Demand Response Provider.</p>
                    </div>';            
            }

            /*  ===============================================================================
                TROUBLESHOOTING HELPERS
                =============================================================================== */
                //$Loader->preDebugger($_POST);
                //$Loader->preDebugger($_SESSION);
        }    
        
        if(!defined('DSN'))
        {
            if(isset($_POST['dsn'])) 
            { 
                define('DSN', $_POST['dsn']); 
            }
            elseif(isset($_COOKIE['dsn']))
            {
                define('DSN', $_COOKIE['dsn']); 
            }
            else
            { 
                define('DSN', '{pending login}'); 
            }
        }
        $server = ' :: '.strtoupper(php_uname('n')).' | '.strtoupper(DSN);    

        require_once('loginops.html');
    } 

/*  ------------------------------------login.php---------------------------------- */  

?>

