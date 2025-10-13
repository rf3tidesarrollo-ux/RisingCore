<?php
      include_once '../../../Conexion/BD.php';
    $RutaCS = "../../../Login/Cerrar.php";
    $RutaSC = "../../../index.php";
    include_once '../../../Login/validar_sesion.php';
    // $Pagina=basename(__FILE__);
    // Historial($Pagina,$Con);
    $Ver = TienePermiso($_SESSION['ID'], "Configuración/Usuarios", 1, $Con);
    $Crear = TienePermiso($_SESSION['ID'], "Configuración/Usuarios", 2, $Con);
    $Editar = TienePermiso($_SESSION['ID'], "Configuración/Usuarios", 3, $Con);
    $Eliminar = TienePermiso($_SESSION['ID'], "Configuración/Usuarios", 4, $Con);

    $FechaR=date("Y-m-d");
    $HoraR=date("H:i:s");
    $SemanaR=date("Y-W");
    
    if ($TipoRol=="ADMINISTRADOR" || $Crear==true) {
    $NumE=0;
    $NumI=0;
    $NumP=0;
    $Finalizado="";
    $Correcto=0;
    $User="";
    $Pass="";
    $Rol="";
    $Titular="";
    $Permisos="";
    $permisosSeleccionados = [];

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

    if (isset($_POST['Modificar'])) {
        $id=$_POST['id'];
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
                $stmt = $Con->prepare("UPDATE usuarios SET username=?, password=?, clave=?, id_cargo=?, id_rol=? WHERE id_usuario=?");
                $stmt->bind_param('sssiii', $UserVal, $Hash, $PassVal, $Titular, $Rol, $id);
                $stmt->execute();
                $stmt->close();

                //Borrar todos los permisos existentes del usuario
                $stmt = $Con->prepare("DELETE FROM permisos_usuarios WHERE id_usuario_u = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt->close();

                //Insertar solo los permisos seleccionados
                if (!empty($Permisos)) {
                    foreach ($Permisos as $modulo_id => $permiso_ids) {
                        foreach ($permiso_ids as $permiso_id) {
                            $stmt = $Con->prepare("INSERT INTO permisos_usuarios (id_usuario_u, id_modulo_u, id_permisos_u) VALUES (?, ?, ?)");
                            $stmt->bind_param("iii", $id, $modulo_id, $permiso_id);
                            $stmt->execute();
                            $stmt->close();
                        }
                    }
                }

                $Limpiar = new Cleanner($User,$Pass,$Rol,$Titular);
                $User = $Limpiar -> LimpiarUser();
                $Pass = $Limpiar -> LimpiarPass();
                $Rol = $Limpiar -> LimpiarRol();
                $Titular = $Limpiar -> LimpiarTitular();

                session_start();
                $_SESSION['correcto'] = "El registro se actualizo correctamente";
                header("Location: EditarU.php?id=" . $id);
                exit();
            }
        }
    }

    if (empty($_GET['id'])) {
        if (!empty($_POST)) {
            $ID=$_POST['id'];
        }else{
            header('Location: CatalogoU.php');
        }
        $stmt = $Con->prepare("SELECT * FROM usuarios WHERE usuarios.id_usuario=?");
        $stmt->bind_param("i",$ID);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $ID = $Reg['id_usuario'];
                    $User=$Reg['username'];
                    $Pass=$Reg['clave'];
                    $Titular=$Reg['id_cargo'];
                    $Rol=$Reg['id_rol'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoU.php');
            }
        
        // Obtener permisos actuales del usuario
        $stmt = $Con->prepare("SELECT id_modulo_u, id_permisos_u FROM permisos_usuarios WHERE id_usuario_u = ?");
        $stmt->bind_param("i", $ID);
        $stmt->execute();
        $permisos = $stmt->get_result();

        while ($row = $permisos->fetch_assoc()) {
            $modulo_id = $row['id_modulo_u'];
            $permiso_id = $row['id_permisos_u'];
            $permisosSeleccionados[$modulo_id][] = $permiso_id;
        }
        $stmt->close();

    }else{
        $ID=$_GET['id'];
        $stmt = $Con->prepare("SELECT * FROM usuarios WHERE usuarios.id_usuario=?");
        $stmt->bind_param("i",$ID);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $ID = $Reg['id_usuario'];
                    $User=$Reg['username'];
                    $Pass=$Reg['clave'];
                    $Titular=$Reg['id_cargo'];
                    $Rol=$Reg['id_rol'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoU.php');
            }
        
        // Obtener permisos actuales del usuario
        $stmt = $Con->prepare("SELECT id_modulo_u, id_permisos_u FROM permisos_usuarios WHERE id_usuario_u = ?");
        $stmt->bind_param("i", $ID);
        $stmt->execute();
        $permisos = $stmt->get_result();

        while ($row = $permisos->fetch_assoc()) {
            $modulo_id = $row['id_modulo_u'];
            $permiso_id = $row['id_permisos_u'];
            $permisosSeleccionados[$modulo_id][] = $permiso_id;
        }
        $stmt->close();
    }

    include 'EditUsuarios.php';
    } else { header("Location: CatalogoU.php"); }
?>