<?php
require_once('SQL_Livres.php');
require_once('UserSession.php');


$bdd=(new SQL_Livres());
$sessionUser = ( new MySession() );


if ( isset($_POST['action']) && $_POST['action'] !== '' ) {
    if ( $_POST['action'] === 'byAuthor' && $_POST['arg'] !== '' ) {
        echo $bdd->queryAuthorsBooksFromDebName($_POST['arg']);
        return;
    } else if ($_POST['action'] === 'byTitle' && $_POST['arg'] !== '') {
        echo $bdd->queryBooksWorksFromTitle($_POST['arg']);
        return;
    } else if ($_POST['action'] === 'register') {
        if ( $bdd->isClient( $_POST['first-name'], $_POST['last-name'] ) === 0 ) {
            echo $bdd->register( $_POST['last-name'], $_POST['first-name'], $_POST['addr'], $_POST['cp'], $_POST['city'], $_POST['country'] );
            $sessionUser->setupAttributes($_POST['first-name'], $_POST['last-name'], $bdd->getIdClient($_POST['first-name'], $_POST['last-name']) );
            return;
        } else {
            $sessionUser->setError( "Profil déjà enregistré. Veuillez vous connecter." );
            echo 0;
        }
        return;
    } else if ($_POST['action'] == 'login' && $_POST['firstName'] !== '' && $_POST['lastName'] ) {
        $nbClient = $bdd->isClient( $_POST['firstName'], $_POST['lastName'] );
        if ( $nbClient == 1 ) {
            $sessionUser->setupAttributes($_POST['firstName'], $_POST['lastName'], $bdd->getIdClient($_POST['firstName'], $_POST['lastName']) );
        } else {
            $sessionUser->setError( "Profil non enregistré. Veuillez ressayer ou vous enregistrer." );
        }
        echo $nbClient;
        return;
    } else if ( $_POST['action'] == 'addToCart' && $_POST['codeExemplaire'] !== '' ) {
        $bdd->addToCart( $sessionUser->userId() ,$_POST['codeExemplaire'] );
        return;
    } else if ( $_POST['action'] == 'getCart' ) {
        echo $bdd->getCartElement( $sessionUser->userId() );
        return;
    } else if ( $_POST['action'] == 'toCommand' ) {
        $bdd->uploadCommand( $sessionUser->userId() );
        return;
    } else if ( $_POST['action'] == 'moveToLoginPage' ) {
        header('Location: login/login.php');
        $sessionUser->destroySession();
        //unset( $_COOKIE['seen']);      don't works 
        setcookie( 'seen', true, time() );
        exit();
    } else if ( $_POST['action'] == 'EmptyCart' ) {
        $bdd->deleteCart( $sessionUser->userId() );
        return;
    }
     
    else {
        echo 'error 1';
    }
} else {
    echo 'error 2';
}