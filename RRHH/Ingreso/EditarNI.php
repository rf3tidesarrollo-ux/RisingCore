<?php
    include_once '../../../Conexion/BD.php';
    $RutaCS = "../../../Login/Cerrar.php";
    $RutaSC = "../../../index.php";
    include_once "../../../Login/validar_sesion.php";
    // $Pagina=basename(__FILE__);
    // Historial($Pagina,$Con);
    $Ver = TienePermiso($_SESSION['ID'], "RRHH/Ingreso", 1, $Con);
    $Crear = TienePermiso($_SESSION['ID'], "RRHH/Ingreso", 2, $Con);
    $Editar = TienePermiso($_SESSION['ID'], "RRHH/Ingreso", 3, $Con);
    $Eliminar = TienePermiso($_SESSION['ID'], "RRHH/Ingreso", 4, $Con);

    $FechaR=date("Y-m-d");

   if ($TipoRol=="ADMINISTRADOR" || $Crear==true) {
    $NumE=0;
    $NumI=0;
    $NumP=0;
    $Finalizado="";
    $Correcto=0;
    $Sede = isset($_POST['Sede']) ? $_POST['Sede'] : '';
    $Nombre = isset($_POST['Nombre']) ? $_POST['Nombre'] : '';
    $AM = isset($_POST['AM']) ? $_POST['AM'] : '';
    $AP = isset($_POST['AP']) ? $_POST['AP'] : '';
    $Genero = isset($_POST['Genero']) ? $_POST['Genero'] : '';
    $Departamento = isset($_POST['Departamento']) ? $_POST['Departamento'] : '';
    $Tipo = isset($_POST['Tipo']) ? $_POST['Tipo'] : '';
    $Fecha = isset($_POST['Fecha']) ? $_POST['Fecha'] : '';
    $Badge = "";

    for ($i=1; $i <= 8; $i++) {
        ${"Error".$i}="";
    }

    for ($i=1; $i <= 3; $i++) { 
        ${"Precaucion".$i}="";
    }

    class Val_Nombre {
        public $Nombre;
    
        function __Construct($N){
            $this -> Nombre = $N;
        }
    
        public function getNombre(){
            return $this -> Nombre;
        }
    
        public function setNombre($Nombre){
            $this -> Nombre = $Nombre;
            
            if (!empty($Nombre)) {
                $Nombre=filter_var($Nombre, FILTER_SANITIZE_SPECIAL_CHARS);
                
                if (!preg_match('/^[A-ZÁÉÍÓÚÑ.\s]*$/', $Nombre)){
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

    class Val_ApellidoP {
        public $ApellidoP;
    
        function __Construct($AP){
            $this -> ApellidoP = $AP;
        }
    
        public function getApellidoP(){
            return $this -> ApellidoP;
        }
    
        public function setApellidoP($ApellidoP){
            $this -> ApellidoP = $ApellidoP;
            
            if (!empty($ApellidoP)) {
                $ApellidoP=filter_var($ApellidoP, FILTER_SANITIZE_SPECIAL_CHARS);
                
                if (!preg_match('/^[A-ZÁÉÍÓÚÜÑ.\s]*$/', $ApellidoP)){
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

    class Val_ApellidoM {
        public $ApellidoM;
    
        function __Construct($AM){
            $this -> ApellidoM = $AM;
        }
    
        public function getApellidoM(){
            return $this -> ApellidoM;
        }
    
        public function setApellidoM($ApellidoM){
            $this -> ApellidoM = $ApellidoM;
            
            if (!empty($ApellidoM)) {
                $ApellidoM=filter_var($ApellidoM, FILTER_SANITIZE_SPECIAL_CHARS);
                
                if (!preg_match('/^[A-ZÁÉÍÓÚÜÑ.\s]*$/', $ApellidoM)){
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

    class Val_Fecha {
        public $Fecha;
    
        function __Construct($F){
            $this -> Fecha = $F;
        }
    
        public function getFecha(){
            return $this -> Fecha;
        }
    
        function setFecha($Fecha){
            if (!empty($Fecha)) {
                $Valores = explode('-', $Fecha);
                $FechaMin="2020/01/01";

                if (strtotime($Fecha) > strtotime($FechaMin)) {
                    if(count($Valores) == 3){
                        $Valor = 1;
                        return $Valor;
                    }else{
                        $Valor = 2;
                        return $Valor;
                    }
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

    class Cleanner{
        public $Limpiar;
        public $Sede;
        public $Nombre;
        public $ApellidoP;
        public $ApellidoM;
        public $Genero;
        public $Departamento;
        public $Tipo;
        public $Fecha;

        function __Construct($L){
            $this -> Limpiar = $L;
        }

        public function LimpiarSede(){
            return $this -> Sede="Seleccione la sede:";
        }

        public function LimpiarNombre(){
            return $this -> Nombre="";
        }

        public function LimpiarApellidoP(){
            return $this -> ApellidoP="";
        }

        public function LimpiarApellidoM(){
            return $this -> ApellidoM="";
        }

        public function LimpiarGenero(){
            return $this -> Genero="Seleccione el género:";
        }

        public function LimpiarDepartamento(){
            return $this -> Departamento="Seleccione el departamento:";
        }

        public function LimpiarTipo(){
            return $this -> Tipo="Seleccione el tipo de empleado:";
        }

        public function LimpiarFecha(){
            return $this -> Fecha="";
        }
    }

    if (isset($_POST['Modificar'])) {
        $idPersonal=$_POST['id'];
        $Sede=$_POST['Sede'];
        $Nombre=$_POST['Nombre'];
        $AP=$_POST['AP'];
        $AM=$_POST['AM'];
        $Genero=$_POST['Genero'];
        $Departamento=$_POST['Departamento'];
        $Tipo=$_POST['Tipo'];
        $Fecha=$_POST['Fecha'];
        
        if ($Sede == "0") {
            $Error1 = "Tienes que seleccionar una sede";
            $NumE += 1;
        }else{
            switch ($Sede) {
                case 'RF1':
                    $SedeVal=1;
                    break;
                case 'RF2':
                    $SedeVal=2;
                    break;
                case 'RF3':
                    $SedeVal=3;
                    break;
            }
            $Correcto += 1;
        }

        $ValidarNombre = new Val_Nombre($Nombre);
        $Retorno = $ValidarNombre -> setNombre($Nombre);
        $NombreVal = $ValidarNombre -> getNombre();
        
        switch ($Retorno) {
            case '1':
                $Precaucion1 = "El nombre solo lleva mayúsculas y letras";
                $NumP += 1;
                break;
            case '2':
                $Correcto += 1;
                break;
            case '3':
                $Error2 = "El campo de nombre no puede ir vacío";
                $NumE += 1;
                break;    
        }
        
        $ValidarApellidoP = new Val_ApellidoP($AP);
        $Retorno = $ValidarApellidoP -> setApellidoP($AP);
        $ApellidoPVal = $ValidarApellidoP -> getApellidoP();
        
        switch ($Retorno) {
            case '1':
                $Precaucion2 = "El apellido paterno solo lleva mayúsculas y letras";
                $NumP += 1;
                break;
            case '2':
                $Correcto += 1;
                break;
            case '3':
                $Error3 = "El apellido paterno no puede ir vacío";
                $NumE += 1;
                break;    
        }

        $ValidarApellidoM = new Val_ApellidoM($AM);
        $Retorno = $ValidarApellidoM -> setApellidoM($AM);
        $ApellidoMVal = $ValidarApellidoM -> getApellidoM();
        
        switch ($Retorno) {
            case '1':
                $Precaucion3 = "El apellido materno solo lleva mayúsculas y letras";
                $NumP += 1;
                break;
            case '2':
                $Correcto += 1;
                break;
            case '3':
                $Error4 = "El apellido materno no puede ir vacío";
                $NumE += 1;
                break;    
        }

        if ($Genero == "0") {
            $Error5 = "Tienes que seleccionar un género";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }
       
        if ($Departamento == "0") {
            $Error6 = "Tienes que seleccionar un departamento";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Tipo == "0") {
            $Error7 = "Tienes que seleccionar un tipo de empleado";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        $ValidarFecha = new Val_Fecha($Fecha);
        $Retorno = $ValidarFecha -> setFecha($Fecha);
        $FechaVal = $ValidarFecha -> getFecha();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Error8 = "La fecha ingresada es incorrecta";
                $NumE += 1;
                break;
            case '3':
                $Error8 = "El campo de fecha no puede ir vacío";
                $NumE += 1;
                break;    
        }

        if ($Correcto==8) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $stmt = $Con->prepare("UPDATE rh_personal SET id_sede_pl=?, nombre=?, apellido_p=?, apellido_m=?, id_genero_pl=?, id_te_pl=?, id_depto_pl=?, fecha_ingreso=?, id_user_p=? WHERE id_personal=?");
                $stmt->bind_param("isssiiisii", $SedeVal, $NombreVal, $ApellidoPVal, $ApellidoMVal, $Genero, $Tipo, $Departamento, $FechaVal, $ID, $idPersonal);
                $stmt->execute();
                $stmt->close();
                
                $Limpiar = new Cleanner($Sede,$Nombre,$ApellidoP,$ApellidoM,$Genero,$Tipo,$Departamento,$Fecha);
                $Sede = $Limpiar -> LimpiarSede();
                $Nombre = $Limpiar -> LimpiarNombre();
                $AP = $Limpiar -> LimpiarApellidoP();
                $AM = $Limpiar -> LimpiarApellidoM();
                $Genero = $Limpiar -> LimpiarGenero();
                $Tipo = $Limpiar -> LimpiarTipo();
                $Departamento = $Limpiar -> LimpiarDepartamento();
                $Fecha = $Limpiar -> LimpiarFecha();

                session_start();
                $_SESSION['correcto'] = "Personal actualizado";
                header("Location: EditarNI.php?id=" . $idPersonal);
                exit();
            }
        }
    }

    if (empty($_GET['id'])) {
        if (!empty($_POST)) {
            $ID=$_POST['id'];
        }else{
            header('Location: CatalogoNI.php');
        }
        $stmt = $Con->prepare("SELECT * FROM rh_personal p JOIN sedes s ON p.id_sede_pl = s.id_sede WHERE id_personal=?");
        $stmt->bind_param("i",$ID);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $ID=$Reg['id_personal'];
                    $Sede=$Reg['codigo_s'];
                    $Nombre=$Reg['nombre'];
                    $AP=$Reg['apellido_p'];
                    $AM=$Reg['apellido_m'];
                    $Genero = $Reg['id_genero_pl'];
                    $Tipo=$Reg['id_te_pl'];
                    $Departamento=$Reg['id_depto_pl'];
                    $Fecha=$Reg['fecha_ingreso'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoNI.php');
            }
    }else{
        $ID=$_GET['id'];
        $stmt = $Con->prepare("SELECT * FROM rh_personal p JOIN sedes s ON p.id_sede_pl = s.id_sede WHERE id_personal=?");
        $stmt->bind_param("i",$ID);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $ID=$Reg['id_personal'];
                    $Sede=$Reg['codigo_s'];
                    $Nombre=$Reg['nombre'];
                    $AP=$Reg['apellido_p'];
                    $AM=$Reg['apellido_m'];
                    $Genero = $Reg['id_genero_pl'];
                    $Tipo=$Reg['id_te_pl'];
                    $Departamento=$Reg['id_depto_pl'];
                    $Fecha=$Reg['fecha_ingreso'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoNI.php');
            }
    }

    include 'EditIngreso.php';
    } else { header("Location: CatalogoNI.php"); }
?>