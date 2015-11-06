<?php
    session_start();
    session_regenerate_id();
    session_destroy();    
    $_SESSION = array();
    if(isset($_COOKIE['dsn'])) {
        setcookie("dsn", "", time()-3600);
        $redirfile = 'loginops.php';
    }
    else
    {
        $redirfile = 'login.php';
    }
    
    header('Location:'.$redirfile);
?>

