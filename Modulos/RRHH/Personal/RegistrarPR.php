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
    $TipoP = isset($_POST['TipoPago']) ? $_POST['TipoPago'] : '';
    $Horario = isset($_POST['Horarios']) ? $_POST['Horarios'] : '';
    $Fecha = isset($_POST['Fecha']) ? $_POST['Fecha'] : '';
    $Badge = "";

    for ($i=1; $i <= 10; $i++) {
        ${"Error".$i}="";
    }

    for ($i=1; $i <= 3; $i++) { 
        ${"Precaucion".$i}="";
    }

    for ($i=1; $i <= 5; $i++) { 
        ${"Informacion".$i}="";
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
        public $TipoP;
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

        public function LimpiarPago(){
            return $this -> TipoP="Seleccione el tipo de pago:";
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
        $TipoP=$_POST['TipoPago'];
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

        if ($TipoP == "0") {
            $Error8 = "Tienes que seleccionar un tipo de pago";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Horario == "0") {
            $Error9 = "Tienes que seleccionar un tipo de horario";
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
                $Error10 = "La fecha ingresada es incorrecta";
                $NumE += 1;
                break;
            case '3':
                $Error10 = "El campo de fecha no puede ir vacío";
                $NumE += 1;
                break;    
        }

        if ($Correcto===10) {
require_once '../../../Librerias/zkteco/zklib/ZKLib.php';
include_once '../../../Conexion/BD.php';

date_default_timezone_set('America/Mexico_City');
$logFile = __DIR__ . '/log.txt'; // Archivo de log

function logMessage($msg) {
    global $logFile;
    $timestamp = date('[Y-m-d H:i:s]');
    file_put_contents($logFile, "$timestamp $msg\n", FILE_APPEND);
}

// =============================
// 1️⃣ Calcular nuevo badge
// =============================
$stmt = $Con->prepare("SELECT MAX(CAST(badge AS UNSIGNED)) AS Ultimo FROM rh_personal WHERE badge REGEXP '^[0-9]+$'");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

$Ultimo = $row['Ultimo'] ?? 0;
$Badge = ($Ultimo == 0) ? '1001' : str_pad($Ultimo + 1, 4, "0", STR_PAD_LEFT);

$user_id  = $Badge;
$name     = $Badge; // badge como nombre
$password = "";
$role     = 0;

// =============================
// 2️⃣ Obtener dispositivos de la sede
// =============================
$stmt = $Con->prepare("SELECT ip, puerto, dispositivo FROM rh_dpbiometrico WHERE id_sede_dp = ? AND id_dpbiometrico=2");
$stmt->bind_param('i', $SedeVal);
$stmt->execute();
$dispositivos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// =============================
// 3️⃣ Función ping rápido
// =============================
function puerto_abierto($host, $port, $timeout = 1) {
    $conn = @fsockopen($host, $port, $errno, $errstr, $timeout);
    if ($conn) { fclose($conn); return true; }
    return false;
}

// =============================
// 4️⃣ Enviar usuario a cada dispositivo
// =============================
$total = 0;
foreach ($dispositivos as $disp) {
    if (!puerto_abierto($disp['ip'], $disp['puerto'])) {
        logMessage("❌ Dispositivo {$disp['dispositivo']} ({$disp['ip']}:{$disp['puerto']}) no responde.");
        continue;
    }

    $zk = new ZKLib($disp['ip'], $disp['puerto']);
    if (!$zk->connect()) {
        logMessage("❌ No se pudo conectar a {$disp['dispositivo']} ({$disp['ip']}:{$disp['puerto']}).");
        continue;
    }

    // Calcular nextUID individual por dispositivo
    $users = $zk->getUser();
    $nextUID = !empty($users) ? max(array_keys($users)) + 1 : 1;

    $zk->disableDevice();
    $zk->setUser($Badge, $user_id, $name, $password, $role);
    $Correcto += 1;
    $zk->enableDevice();
    $zk->disconnect();

    logMessage("✅ Usuario $user_id ($name) enviado a {$disp['dispositivo']} ({$disp['ip']}:{$disp['puerto']}) con UID $nextUID");
    $total++;
}

logMessage("🚀 Proceso finalizado. Total de dispositivos actualizados: $total");

        }
        
        if ($Correcto>=11) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $stmt = $Con->prepare("SELECT MAX(CAST(badge AS UNSIGNED)) AS Ultimo FROM rh_personal WHERE badge REGEXP '^[0-9]+$'");
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();

                $Ultimo = $row['Ultimo'] ?? 0;
                $Badge = str_pad($Ultimo + 1, 4, "0", STR_PAD_LEFT);
                $stmt->close();

                $stmt = $Con->prepare("INSERT INTO rh_personal (id_sede_pl, badge, nombre, apellido_p, apellido_m, id_genero_pl, id_te_pl, id_depto_pl, tipo_pago, id_tipo_h, fecha_ingreso, fecha_registro, id_user_p, status_pl) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
                $stmt->bind_param('issssiiisissi', $SedeVal, $Badge, $NombreVal, $ApellidoPVal, $ApellidoMVal, $Genero, $Tipo, $Departamento, $TipoP, $Horario, $FechaVal, $FechaR, $ID);
                $stmt->execute();
                $stmt->close();
                
                $Limpiar = new Cleanner($Sede,$Nombre,$AP,$AM,$Genero,$Departamento,$Tipo,$TipoP,$Fecha);
                $Sede = $Limpiar -> LimpiarSede();
                $Nombre = $Limpiar -> LimpiarNombre();
                $AP = $Limpiar -> LimpiarApellidoP();
                $AM = $Limpiar -> LimpiarApellidoM();
                $Genero = $Limpiar -> LimpiarGenero();
                $Departamento = $Limpiar -> LimpiarDepartamento();
                $Tipo = $Limpiar -> LimpiarTipo();
                $TipoP = $Limpiar -> LimpiarPago();
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