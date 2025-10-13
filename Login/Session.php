<?php

class UserSession{

    public function _construct(){
        session_start();
    }

    public function setCurrentUser($User){
        $_SESSION['User'] = $User;
    }

    public function getCurrentUser(){
        return $_SESSION['User'];
    }

    public function closeSession(){
        session_unset();
        session_destroy();
    }
}

?>