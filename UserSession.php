<?php

class MySession {
    private $sessionLifetime = 0;

    public function __construct() {
        $this->sessionLifetime = 3600;
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params($this->sessionLifetime, '/');
            session_start();
        }
    }

    public function setupAttributes($firstName, $lastName, $id) {
        $_SESSION['firstName'] = $firstName;
        $_SESSION['lastName'] = $lastName;
        $_SESSION['id'] = $id;
        $_SESSION['lastActivity'] = time();
    }

    public function firstName() {
        return isset($_SESSION['firstName']) ? $_SESSION['firstName'] : null;
    }

    public function lastName() {
        return isset($_SESSION['lastName']) ? $_SESSION['lastName'] : null;
    }

    public function userId() {
        return isset($_SESSION['id']) ? $_SESSION['id'] : null;
    }

    public function isValid() {
        if (!isset($_SESSION['lastActivity'])) {
            return false;
        }
        $timeSinceLastActivity = time() - $_SESSION['lastActivity'];
        if ($timeSinceLastActivity > $this->sessionLifetime) {
            return false;
        }
        $_SESSION['lastActivity'] = time();
        return true;
    }

    public function isActive() {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    public function setError( $errorString ) {
        $_SESSION['error_message'] = $errorString;
    }

    public function getError() {
        return isset($_SESSION['error_message']) ? $_SESSION['error_message'] : NULL; 
    }

    public function deleteError() {
        unset( $_SESSION['error_message'] );
    }

    public function destroySession() {
        session_unset();
        session_destroy();
    }
}
