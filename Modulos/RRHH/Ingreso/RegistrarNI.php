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
    $Horario = isset($_POST['Horarios']) ? $_POST['Horarios'] : '';
    $Fecha = isset($_POST['Fecha']) ? $_POST['Fecha'] : '';
    $Badge = "";

    for ($i=1; $i <= 9; $i++) {
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
                $FechaMin="2000/01/01";

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

    // class Val_Hora {
    //     public $Hora;

    //     function __construct($H){
    //         $this->Hora = $H;
    //     }

    //     public function getHora(){
    //         return $this->Hora;
    //     }

    //     function setHora($Hora){
    //         if (!empty($Hora)) {
    //             // Validar formato con regex HH:MM o HH:MM:SS
    //             if (preg_match('/^([01]\d|2[0-3]):[0-5]\d(:[0-5]\d)?$/', $Hora)) {
    //                 $HoraMin = "08:00"; // Hora mínima permitida
    //                 // Convertimos ambas a timestamp
    //                 $horaIngresada = strtotime($Hora);
    //                 $horaMinima = strtotime($HoraMin);

    //                 if ($horaIngresada >= $horaMinima) {
    //                     return 1; // Hora válida
    //                 } else {
    //                     return 2; // Hora anterior a la mínima
    //                 }
    //             } else {
    //                 return 2; // Formato incorrecto
    //             }
    //         } else {
    //             return 3; // Hora vacía
    //         }
    //     }
    // }

    class Cleanner{
        public $Limpiar;
        public $Sede;
        public $Nombre;
        public $ApellidoP;
        public $ApellidoM;
        public $Genero;
        public $Departamento;
        public $Tipo;
        public $Horario;
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

        public function LimpiarHorario(){
            return $this -> Tipo="Seleccione el tipo de horario:";
        }

        public function LimpiarFecha(){
            return $this -> Fecha="";
        }
    }

    if (isset($_POST['Insertar'])) {
        $Sede=$_POST['Sede'];
        $Nombre=$_POST['Nombre'];
        $AP=$_POST['AP'];
        $AM=$_POST['AM'];
        $Genero=$_POST['Genero'];
        $Departamento=$_POST['Departamento'];
        $Tipo=$_POST['Tipo'];
        $Horario=$_POST['Horarios'];
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

        if ($Horario == "0") {
            $Error8 = "Tienes que seleccionar un tipo de horario";
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
                $Error9 = "La fecha ingresada es incorrecta";
                $NumE += 1;
                break;
            case '3':
                $Error9 = "El campo de fecha no puede ir vacío";
                $NumE += 1;
                break;    
        }
        
        if ($Correcto==9) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $NombreCompleto = $NombreVal . " " . $ApellidoPVal . " " . $ApellidoMVal;

                $stmt = $Con->prepare("SELECT COUNT(id_personal) AS Suma FROM rh_personal");
                $stmt->execute();
                $resultSuma = $stmt->get_result();
                $Suma = $resultSuma->fetch_assoc()['Suma'];
                $Badge = str_pad($Suma + 1, 4, "0", STR_PAD_LEFT);
                $stmt->close();

                $stmt = $Con->prepare("INSERT INTO rh_personal (id_sede_pl, badge, nombre, apellido_p, apellido_m, id_genero_pl, id_te_pl, id_depto_pl, id_tipo_h, fecha_ingreso, fecha_registro, id_user_p, status_pl) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
                $stmt->bind_param('issssiiiissi', $SedeVal, $Badge, $NombreVal, $ApellidoPVal, $ApellidoMVal, $Genero, $Tipo, $Departamento, $Horario, $FechaVal, $FechaR, $ID);
                $stmt->execute();
                $stmt->close();

                require_once '../../../Librerias/zkteco/zklib/ZKLib.php';
                // Obtener dispositivos de la sede
                $stmt = $Con->prepare("SELECT ip, puerto FROM rh_dpbiometrico WHERE id_sede_dp = ?");
                $stmt->bind_param('i', $SedeVal);
                $stmt->execute();
                $dispositivos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                $stmt->close();

                // Preparar datos del empleado
                $user_id = $Badge;
                $trans = ['Á'=>'A','É'=>'E','Í'=>'I','Ó'=>'O','Ú'=>'U','Ñ'=>'N','Ü'=>'U'];
                $name = strtr(strtoupper($NombreCompleto), $trans);
                $name = preg_replace('/[^A-Z0-9 ]/', '', $name);
                $name = substr($name, 0, 24);
                if (empty($name)) { $name = "USUARIO{$user_id}"; }
                $password = "";
                $role = 0;

                // Función para verificar si el puerto está abierto (ping rápido)
                function puerto_abierto($host, $port, $timeout = 1) {
                    $conn = @fsockopen($host, $port, $errno, $errstr, $timeout);
                    if ($conn) { fclose($conn); return true; }
                    return false;
                }

                // Traer usuarios desde un dispositivo activo para calcular nextUID
                $nextUID = 1;
                foreach ($dispositivos as $disp) {
                    if (!puerto_abierto($disp['ip'], $disp['puerto'], 1)) continue;

                    $zk = new ZKLib($disp['ip'], $disp['puerto']);
                    if ($zk->connect()) {
                        $users = $zk->getUser();
                        if (!empty($users)) {
                            $nextUID = max(array_keys($users)) + 1;
                        }
                        $zk->disconnect();
                        break; // ya tenemos los usuarios, no necesitamos más
                    }
                }

                // Enviar usuario a todos los dispositivos
                foreach ($dispositivos as $disp) {
                    if (!puerto_abierto($disp['ip'], $disp['puerto'], 1)) {
                        error_log("❌ Dispositivo no responde: $disp[ip]:$disp[puerto]");
                        continue;
                    }

                    $zk = new ZKLib($disp['ip'], $disp['puerto']);
                    if ($zk->connect()) {
                        $zk->disableDevice();
                        $zk->setUser($nextUID, $user_id, $name, $password, $role);
                        $zk->enableDevice();
                        $zk->disconnect();
                        //echo "✅ Usuario $user_id enviado a $disp[ip]:$disp[puerto] con UID $nextUID<br>";
                    } else {
                        error_log("❌ No se pudo conectar a $disp[ip]:$disp[puerto]");
                    }
                }
                
                $Limpiar = new Cleanner($Sede,$Nombre,$AP,$AM,$Genero,$Departamento,$Tipo,$Fecha);
                $Sede = $Limpiar -> LimpiarSede();
                $Nombre = $Limpiar -> LimpiarNombre();
                $AP = $Limpiar -> LimpiarApellidoP();
                $AM = $Limpiar -> LimpiarApellidoM();
                $Genero = $Limpiar -> LimpiarGenero();
                $Departamento = $Limpiar -> LimpiarDepartamento();
                $Tipo = $Limpiar -> LimpiarTipo();
                $Fecha = $Limpiar -> LimpiarFecha();
                
                session_start();
                $_SESSION['correcto'] = "Nuevo ingreso registrado";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
        }
    }

    include 'RegIngreso.php';
    } else { header("Location: CatalogoNI.php"); }
?>