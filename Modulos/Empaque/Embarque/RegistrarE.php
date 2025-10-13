<?php
    include_once '../../../Conexion/BD.php';
    $RutaCS = "../../../Login/Cerrar.php";
    $RutaSC = "../../../index.php";
    include_once "../../../Login/validar_sesion.php";
    // $Pagina=basename(__FILE__);
    // Historial($Pagina,$Con);
    $Ver = TienePermiso($_SESSION['ID'], "Empaque/Embarque", 1, $Con);
    $Crear = TienePermiso($_SESSION['ID'], "Empaque/Embarque", 2, $Con);
    $Editar = TienePermiso($_SESSION['ID'], "Empaque/Embarque", 3, $Con);
    $Eliminar = TienePermiso($_SESSION['ID'], "Empaque/Embarque", 4, $Con);

    $FechaE=date("Y-m-d");
    $HoraE=date("H:i:s");
    $SemanaE=date("Y-W");

   if ($TipoRol=="ADMINISTRADOR" || $Crear==true) {
    $NumE=0;
    $NumI=0;
    $NumP=0;
    $Finalizado="";
    $Correcto=0;
    $Sede = isset($_POST['Sede']) ? $_POST['Sede'] : '';
    $PO = isset($_POST['PO']) ? $_POST['PO'] : '';
    $Destino = isset($_POST['Destino']) ? $_POST['Destino'] : '';
    $CajasT = isset($_POST['CajasT']) ? $_POST['CajasT'] : '';
    $KilosT = isset($_POST['KilosT']) ? $_POST['KilosT'] : '';
    $Tipo = isset($_POST['Tipo']) ? $_POST['Tipo'] : '';
    $Fecha = isset($_POST['Fecha']) ? $_POST['Fecha'] : '';
    $FolioE = "";

    for ($i=1; $i <= 7; $i++) {
        ${"Error".$i}="";
    }

    for ($i=1; $i <= 3; $i++) { 
        ${"Precaucion".$i}="";
    }

    class Val_PO {
        public $PO;
    
        function __Construct($PO){
            $this -> PO = $PO;
        }
    
        public function getPO(){
            return $this -> PO;
        }
    
        public function setPO($PO){
            $this -> PO = $PO;
            
            if (!empty($PO)) {
                $PO=filter_var($PO, FILTER_SANITIZE_SPECIAL_CHARS);
                
                if (!preg_match('/^[0-9.\/s]*$/', $PO)){
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

    class Val_KilosT {
        public $KilosT;
    
        function __Construct($K){
            $this -> KilosT = $K;
        }
    
        public function getKilosT(){
            return $this -> KilosT;
        }
    
        public function setKilosT($KilosT){
            $this -> KilosT = $KilosT;
            
            if (!empty($KilosT)) {
                if (is_numeric($KilosT)){
                    if ($KilosT > 0 && $KilosT <= 999999999) {
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
            }else{
                    $Valor = 4;
                    return $Valor;
            }
        }
    }

    class Val_CajasT {
        public $CajasT;
    
        function __Construct($C){
            $this -> CajasT = $C;
        }
    
        public function getCajasT(){
            return $this -> CajasT;
        }
    
        public function setCajasT($CajasT){
            $this -> CajasT = $CajasT;
            
            if (!empty($CajasT)) {
                if (is_numeric($CajasT)){
                    if ($CajasT > 0 && $CajasT <= 999) {
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
            }else{
                    $Valor = 4;
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
                $FechaMin="2025/01/01";

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
        public $PO;
        public $Destino;
        public $KilosT;
        public $CajasT;
        public $Tipo;
        public $Fecha;

        function __Construct($L){
            $this -> Limpiar = $L;
        }

        public function LimpiarSede(){
            return $this -> Sede="Seleccione la sede:";
        }

        public function LimpiarPO(){
            return $this -> PO="Seleccione el código:";
        }

        public function LimpiarDestino(){
            return $this -> Destino="Seleccione el carro:";
        }

        public function LimpiarCajasT(){
            return $this -> CajasT="";
        }
        
        public function LimpiarKilosT(){
            return $this -> KilosT="";
        }

        public function LimpiarTipo(){
            return $this -> Tipo="";
        }

        public function LimpiarFecha(){
            return $this -> Fecha="";
        }
    }

    if (isset($_POST['Insertar'])) {
        $Sede=$_POST['Sede'];
        $PO=$_POST['PO'];
        $Destino=$_POST['Destino'];
        $CajasT=$_POST['CajasT'];
        $KilosT=$_POST['KilosT'];
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

        $ValidarPO = new Val_PO($PO);
        $Retorno = $ValidarPO -> setPO($PO);
        $POVal = $ValidarPO -> getPO();
        
        switch ($Retorno) {
            case '1':
                $Precaucion1 = "El PO solo lleva números";
                $NumP += 1;
                break;
            case '2':
                $Correcto += 1;
                break;
            case '3':
                $Error2 = "El campo de PO no puede ir vacío";
                $NumE += 1;
                break;    
        }

        if ($Destino == "0") {
            $Error3 = "Tienes que seleccionar un destino";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        $ValidarCajasT = new Val_CajasT($CajasT);
        $Retorno = $ValidarCajasT -> setCajasT($CajasT);
        $CajasTVal = $ValidarCajasT -> getCajasT();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Precaucion2 = "Las cajas deben ser mayor a 0";
                $NumP += 1;
                break;
            case '3':
                $Precaucion2 = "Tienes que ingresar solo números en el campo de cajas";
                $NumP += 1;
                break;
            case '4':
                $Error4 = "El campo de cajas no puede ir vacío";
                $NumE += 1;
                break;   
        }

        $ValidarKilosT = new Val_KilosT($KilosT);
        $Retorno = $ValidarKilosT -> setKilosT($KilosT);
        $KilosTVal = $ValidarKilosT -> getKilosT();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Precaucion3 = "Los kilos deben ser mayor a 0";
                $NumP += 1;
                break;
            case '3':
                $Precaucion3 = "Tienes que ingresar solo números en el campo de kilos brutos";
                $NumP += 1;
                break;
            case '4':
                $Error5 = "El campo de kilos no puede ir vacío";
                $NumE += 1;
                break;  
        }

        $ValidarFecha = new Val_Fecha($Fecha);
        $Retorno = $ValidarFecha -> setFecha($Fecha);
        $FechaVal = $ValidarFecha -> getFecha();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Error6 = "La fecha ingresada es incorrecta";
                $NumE += 1;
                break;
            case '3':
                $Error6 = "El campo de fecha no puede ir vacío";
                $NumE += 1;
                break;    
        }

        if ($Tipo == "") {
            $Error2 = "El tipo de descargo no puede ir vacío";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Correcto==7) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $stmt = $Con->prepare("SELECT COUNT(id_embarque) AS Suma FROM embarques_pallets WHERE id_Sede_em = ?");
                $stmt->bind_param("i", $SedeVal);
                $stmt->execute();
                $resultSuma = $stmt->get_result();
                $Suma = $resultSuma->fetch_assoc()['Suma'];
                $Numero = str_pad($Suma + 1, 4, "0", STR_PAD_LEFT);
                $Folio = $Sede . "-" . $Numero;
                $stmt->close();

                $stmt = $Con->prepare("INSERT INTO embarques_pallets (id_sede_em, folio_em, po_em, cajas_em, kilos_em , cajas_emt, kilos_emt, id_destino_em, fecha_em, hora_em, fecha_c_em, semana_em, usuario_id, estado_em, activo_em) VALUES (?, ?, ?, ?, ?, 0, 0, ?, ?, ?, ?, ?, ?, 0, 1)");
                $stmt->bind_param('isidiissssi', $SedeVal, $Folio, $POVal, $CajasTVal, $KilosTVal, $Destino, $FechaVal, $HoraE, $FechaE, $SemanaE, $ID);
                $stmt->execute();
                $stmt->close();
                
                $Limpiar = new Cleanner($Sede,$PO,$CajasT,$KilosT,$Destino,$Fecha,$Tipo);
                $Sede = $Limpiar -> LimpiarSede();
                $PO = $Limpiar -> LimpiarPO();
                $CajasT = $Limpiar -> LimpiarCajasT();
                $KilosT = $Limpiar -> LimpiarKilosT();
                $Tipo = $Limpiar -> LimpiarTipo();
                $Fecha = $Limpiar -> LimpiarFecha();
                
                session_start();
                $_SESSION['correcto'] = "Embarque registrado";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
        }
    }

    include 'RegEmbarque.php';
    } else { header("Location: CatalogoE.php"); }
?>