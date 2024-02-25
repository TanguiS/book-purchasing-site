<?php
    require_once('UserSession.php');
    $userSession = (new MySession() );
    if ( isset($_COOKIE["seen"]) && $userSession->isActive() ) {
        header('Location: bookSaler.php');
        exit;
    } else {
        header('Location: login/login.php');
        exit;
    }

?>