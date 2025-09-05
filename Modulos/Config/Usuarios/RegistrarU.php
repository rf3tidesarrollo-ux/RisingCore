<?php
    include_once '../../../Conexion/BD.php';
    $RutaCS = "../../../Login/Cerrar.php";
    $RutaSC = "../../../index.php";
    include_once '../../../Login/validar_sesion.php';
    // $Pagina=basename(__FILE__);
    // Historial($Pagina,$Con);
    Permisos($_SESSION['ID'], $_SESSION['IDM'], 1, $Con);

    $FechaR=date("Y-m-d");
    $HoraR=date("H:i:s");
    $SemanaR=date("Y-W");
    $Activo=1;

    if ($Rol=="ADMINISTRADOR" || $PERMISO==true ) {
    $NumE=0;
    $NumI=0;
    $NumP=0;
    $Finalizado="";
    $Correcto=0;
    $User = isset($_POST['User']) ? $_POST['User'] : '';
    $Pass = isset($_POST['Pass']) ? $_POST['Pass'] : '';
    $Rol = isset($_POST['Rol']) ? $_POST['Rol'] : '';
    $Titular = isset($_POST['Titular']) ? $_POST['Titular'] : '';
    $Permisos = isset($_POST['permisos']) ? $_POST['permisos'] : '';
    $Estado=0;

    for ($i=1; $i <= 4; $i++) {
        ${"Error".$i}="";
    }

    for ($i=1; $i <= 3; $i++) { 
        ${"Precaucion".$i}="";
    }

    class Val_User {
        public $User;
    
        function __Construct($U){
            $this -> User = $U;
        }
    
        public function getUser(){
            return $this -> User;
        }
    
        public function setUser($User){
            $this -> User = $User;
            
            if (!empty($User)) {
                $User=filter_var($User, FILTER_SANITIZE_SPECIAL_CHARS);
                
                if (!preg_match('/^[A-ZÁÉÍÓÚÑa-záéíóúñ.0-9\-\s]*$/', $User)){
                    $Valor = 1;
                    return $Valor;
                }else{
                    $Valor = 2;
                    return $Valor;
                }
            }else{
                    $Valor = 3;
                    return $Valor;
            }
        }
    }

    class Val_Pass {
        public $Pass;
    
        function __Construct($P){
            $this -> Pass = $P;
        }
    
        public function getPass(){
            return $this -> Pass;
        }
    
        public function setPass($Pass){
            $this -> Pass = $Pass;
            
            if (!empty($Pass)) {
                $Pass=filter_var($Pass, FILTER_SANITIZE_SPECIAL_CHARS);
                
                if (!preg_match('/^[A-ZÁÉÍÓÚÑa-záéíóúñ.0-9\-\s]*$/', $Pass)){
                    $Valor = 1;
                    return $Valor;
                }else{
                    $Valor = 2;
                    return $Valor;
                }
            }else{
                    $Valor = 3;
                    return $Valor;
            }
        }
    }

    class Data{
        public function hash($password) {
            return password_hash($password, PASSWORD_DEFAULT, ['cost' => 15]);
        }
    }

    class Cleanner{
        public $Limpiar;
        public $User;
        public $Pass;
        public $Rol;
        public $Titular;
        

        function __Construct($L){
            $this -> Limpiar = $L;
        }

        public function LimpiarUser(){
            return $this -> User="";
        }

        public function LimpiarPass(){
            return $this -> Pass="";
        }

        public function LimpiarRol(){
            return $this -> Rol="Seleccione el rol:";
        }

        public function LimpiarTitular(){
            return $this -> Titular="Seleccione el titular:";
        }
    }
    

    if (isset($_POST['Insertar'])) {
        $User=$_POST['User'];
        $Pass=$_POST['Pass'];
        $Rol=$_POST['Rol'];
        $Titular=$_POST['Titular'];
        $Permisos = isset($_POST['permisos']) && is_array($_POST['permisos']) ? $_POST['permisos'] : [];

        $ValidarUser = new Val_User($User);
        $Retorno = $ValidarUser -> setUser($User);
        $UserVal = $ValidarUser -> getUser();
        
        switch ($Retorno) {
            case '1':
                $Precaucion1 = "El nombre de usuario no lleva carácteres especiales";
                $NumP += 1;
                break;
            case '2':
                $Correcto += 1;
                break;
            case '3':
                $Error1 = "El campo de usuario no puede ir vacío";
                $NumE += 1;
                break;    
        }

        $ValidarPass = new Val_Pass($Pass);
        $Retorno = $ValidarPass -> setPass($Pass);
        $PassVal = $ValidarPass -> getPass();
        
        switch ($Retorno) {
            case '1':
                $Precaucion2 = "La contraseña no lleva carácteres especiales";
                $NumP += 1;
                break;
            case '2':
                $get_hash = new Data($Pass);
                $Hash = $get_hash -> hash($Pass);
                $Correcto += 1;
                break;
            case '3':
                $Error2 = "El campo de contraseña no puede ir vacío";
                $NumE += 1;
                break;    
        }

        if ($Rol == "Seleccione el rol:") {
            $Error3 = "Tienes que seleccionar un rol";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Titular == "Seleccione el titular:") {
            $Error4 = "Tienes que seleccionar un titular";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

         if (!empty($Permisos)) {
                $Correcto += 1;
            } else {
                $Precaucion2 = "No se ha asignado ningún permiso al usuario";
                $NumP += 1;
            }

        if ($Correcto==5) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $stmt = $Con->prepare("INSERT INTO usuarios (username, password, id_cargo, id_rol, estado) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param('ssiii', $UserVal, $Hash, $Titular, $Rol, $Estado);
            $stmt->execute();
            $usuario_id = $stmt->insert_id;

            
            foreach ($Permisos as $modulo_id => $permiso_ids) {
                foreach ($permiso_ids as $permiso_id) {
                    $stmt = $Con->prepare("INSERT INTO permisos_usuarios (id_usuario_u, id_modulo_u, id_permisos_u) VALUES (?, ?, ?)");
                    $stmt->bind_param("iii", $usuario_id, $modulo_id, $permiso_id);
                    $stmt->execute();
                }
            }

            $stmt->close();

            $Limpiar = new Cleanner($User,$Pass,$Rol,$Titular);
            $User = $Limpiar -> LimpiarUser();
            $Pass = $Limpiar -> LimpiarPass();
            $Rol = $Limpiar -> LimpiarRol();
            $Titular = $Limpiar -> LimpiarTitular();
            $Finalizado = "Se hizo el registro correctamente";
            }
        }
    }

    include 'RegUsuarios.php';
    } else { header("Location: CatalogoU.php"); }
?>