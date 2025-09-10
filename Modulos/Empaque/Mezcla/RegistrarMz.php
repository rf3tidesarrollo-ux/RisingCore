<?php
    include_once '../../../Conexion/BD.php';
    $RutaCS = "../../../Login/Cerrar.php";
    $RutaSC = "../../../index.php";
    include_once "../../../Login/validar_sesion.php";
    // $Pagina=basename(__FILE__);
    // Historial($Pagina,$Con);
    $Ver = TienePermiso($_SESSION['ID'], "Empaque/Pesaje", 1, $Con);
    $Crear = TienePermiso($_SESSION['ID'], "Empaque/Pesaje", 2, $Con);
    $Editar = TienePermiso($_SESSION['ID'], "Empaque/Pesaje", 3, $Con);
    $Eliminar = TienePermiso($_SESSION['ID'], "Empaque/Pesaje", 4, $Con);

    $FechaR=date("Y-m-d");
    $HoraR=date("H:i:s");

   if ($TipoRol=="ADMINISTRADOR" || $Crear==true) {
    $NumE=0;
    $NumI=0;
    $NumP=0;
    $Finalizado="";
    $Correcto=0;
    $Sede = isset($_POST['Sede']) ? $_POST['Sede'] : '';
    $Cliente = $_POST['Clientes'] ?? $_GET['Clientes'] ?? 0;
    $Folio = isset($_POST['Folio']) ? $_POST['Folio'] : '';
    $CajasT = isset($_POST['CajasT']) ? $_POST['CajasT'] : '';
    $KilosT = isset($_POST['KilosT']) ? $_POST['KilosT'] : '';

    for ($i=1; $i <= 5; $i++) {
        ${"Error".$i}="";
    }

    for ($i=1; $i <= 1; $i++) { 
        ${"Precaucion".$i}="";
    }

    class Cleanner{
        public $Limpiar;
        public $Sede;
        public $Cliente;
        public $Folio;
        public $CajasT;
        public $KilosT;

        function __Construct($L){
            $this -> Limpiar = $L;
        }

        public function LimpiarSede(){
            return $this -> Sede="Seleccione la sede:";
        }

        public function LimpiarCliente(){
            return $this -> Cliente="Seleccione el cliente:";
        }
        
        public function LimpiarFolio(){
            return $this -> Folio="";
        }

        public function LimpiarCajasT(){
            return $this -> CajasT="";
        }
        
        public function LimpiarKilosT(){
            return $this -> KilosT="";
        }
    }
    

    if (isset($_POST['Insertar'])) {
        $Sede=$_POST['Sede'];
        $Cliente=$_POST['Clientes'];
        $CajasT=$_POST['CajasT'];
        $KilosT=$_POST['KilosT'];
        $Folio=$_POST['Folio'];

        if ($Sede == "0") {
            $Error1 = "Tienes que seleccionar una sede";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Cliente == "0") {
            $Error2 = "Tienes que seleccionar un cliente";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($CajasT == "") {
            $Error3 = "Las cajas no pueden ir vacias";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($KilosT == "") {
            $Error4 = "Los kilos no pueden ir vacios";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Correcto==4) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $stmt = $Con->prepare("INSERT INTO registro_empaque (id_codigo_r, id_presentacion_r, folio_r, id_tipo_caja , id_tipo_tarima, id_tipo_carro, p_bruto, p_taraje, p_neto, cantidad_caja, cantidad_tarima, usuario_r, fecha_r, hora_r, activo_r, kilos_dis, cajas_dis, no_serie_r, semana_r) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('iisiiidddiisssidiss', $Codigo, $Presentacion, $FolioVal, $Caja, $Tarima, $Carro, $KilosB, $KilosT, $KilosN, $NoCajaVal, $NoTarima, $Name, $FechaR, $HoraR, $Activo, $KilosN, $NoCajaVal, $NoSerieVal, $SemanaR);
                $stmt->execute();
                $stmt->close();
                $Limpiar = new Cleanner($Folio,$KilosB,$NoCaja,$NoTarima,$Codigo,$Carro,$Tarima,$Caja,$Sede);
                $Folio = $Limpiar -> LimpiarFolio();
                $KilosB = $Limpiar -> LimpiarKilosB();
                $NoCaja = $Limpiar -> LimpiarNoCaja();
                $Codigo = $Limpiar -> LimpiarCodigo();
                $Carro = $Limpiar -> LimpiarCarro();
                $Tarima = $Limpiar -> LimpiarTarima();
                $NoTarima = $Limpiar -> LimpiarNoTarima();
                $Caja = $Limpiar -> LimpiarCaja();
                $Sede = $Limpiar -> LimpiarSede();
                $Presentacion = $Limpiar -> LimpiarPresentacion();

                session_start();
                $_SESSION['correcto'] = "Se hizo el registro correctamente";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
        }
    }

    include 'RegMezcla.php';
    } else { header("Location: CatalogoMz.php"); }
?>