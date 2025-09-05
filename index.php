<?php
ob_start();
session_start();
include_once 'Login/User.php';
include_once 'Login/Session.php';

$UserSession = new UserSession();
$User = new User();

if(isset($_SESSION['User'])){
    $User->setUser($UserSession->getCurrentUser());
    include_once "Login/validar_roles.php";
}else if(isset($_POST['Usuario']) && isset($_POST['Password'])){
    $UserForm = $_POST['Usuario'];
    $PassForm = $_POST['Password'];
            if ($UserForm != "" && $PassForm != "") {
                if ($User->verify($PassForm,$UserForm)) {
                        $UserSession->setCurrentUser($UserForm);
                        $User->setUser($UserForm);
                        
                        include_once "Login/validar_roles.php";
                } else {
                    $ErrorLogin = "Nombre de usuario y/o password incorrecto";
                    include_once 'Login/Form.php';
                }    
            }else{
                $ErrorLogin = "Llene los campos de inicio de sesión";
                include_once 'Login/Form.php';
            }
}else{
    include_once 'Login/Form.php';
}
ob_end_flush();
?>