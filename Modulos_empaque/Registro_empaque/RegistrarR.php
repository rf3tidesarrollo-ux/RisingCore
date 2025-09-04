<?php
    include_once '../../Conexion/BD.php';
    $RutaCS = "../../Sesion/Cerrar.php";
    $RutaSC = "../../index.php";
    // include_once "../../Sesion/validar_sesion.php";
    // $Pagina=basename(__FILE__);
    // Historial($Pagina,$Con);

    $FechaR=date("Y-m-d");
    $HoraR=date("H:i:s");
    $SemanaR=date("Y-W");
    $Usuario="prueba";
    $Rol="ADMINISTRADOR";
    $Activo=1;

    if ($Rol!="USUARIO") {
    $NumE=0;
    $NumI=0;
    $NumP=0;
    $Finalizado="";
    $Correcto=0;
    $Sede = isset($_POST['Sede']) ? $_POST['Sede'] : '';
    $Codigo = isset($_POST['Codigo']) ? $_POST['Codigo'] : '';
    $Carro = isset($_POST['Carro']) ? $_POST['Carro'] : '';
    $Tarima = isset($_POST['Tarima']) ? $_POST['Tarima'] : '';
    $Caja = isset($_POST['Cajas']) ? $_POST['Cajas'] : '';
    $NoCaja = isset($_POST['NoCajas']) ? $_POST['NoCajas'] : '';
    $NoTarima = isset($_POST['NoTarima']) ? $_POST['NoTarima'] : '';
    $KilosB = isset($_POST['KilosB']) ? $_POST['KilosB'] : '';
    $Folio = isset($_POST['Folio']) ? $_POST['Folio'] : '';
    $Caja = isset($_POST['Cajas']) ? $_POST['Cajas'] : '';
    $Folio="";
    $NoSerie="";
    $Tipo="NORMAL";
    $Clasificacion=NULL;
    $CodigoR="";
    $VariedadSeleccionada = $_POST['Codigo'] ?? '';

    for ($i=1; $i <= 10; $i++) {
        ${"Error".$i}="";
    }

    for ($i=1; $i <= 5; $i++) { 
        ${"Precaucion".$i}="";
    }

    class Val_KilosB {
        public $KilosB;
    
        function __Construct($K){
            $this -> KilosB = $K;
        }
    
        public function getKilosB(){
            return $this -> KilosB;
        }
    
        public function setKilosB($KilosB){
            $this -> KilosB = $KilosB;
            
            if (!empty($KilosB)) {
                if (is_numeric($KilosB)){
                    if ($KilosB > 0 && $KilosB <= 999999999) {
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

    class Val_NoCaja {
        public $NoCaja;
    
        function __Construct($C){
            $this -> NoCaja = $C;
        }
    
        public function getNoCaja(){
            return $this -> NoCaja;
        }
    
        public function setNoCaja($NoCaja){
            $this -> NoCaja = $NoCaja;
            
            if (!empty($NoCaja)) {
                if (is_numeric($NoCaja)){
                    if ($NoCaja > 0 && $NoCaja <= 9999) {
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

    class Val_NoTarima {
        public $NoTarima;
    
        function __Construct($T){
            $this -> NoTarima = $T;
        }
    
        public function getNoTarima(){
            return $this -> NoTarima;
        }
    
        public function setNoTarima($NoTarima){
            $this -> NoTarima = $NoTarima;
            
            if (!empty($NoTarima)) {
                if (is_numeric($NoTarima)){
                    if ($NoTarima > 0 && $NoTarima <= 99) {
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

    class Val_Folio {
        public $Folio;
    
        function __Construct($F){
            $this -> Folio = $F;
        }
    
        public function getFolio(){
            return $this -> Folio;
        }
    
        public function setFolio($Folio){
            $this -> Folio = $Folio;
            
            if (!empty($Folio)) {
                $Folio=filter_var($Folio, FILTER_SANITIZE_SPECIAL_CHARS);
                
                if (!preg_match('/^[a-zñA-ZÑ0-9\s]*$/', $Folio)){
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

    class Cleanner{
        public $Limpiar;
        public $Folio;
        public $KilosB;
        public $NoCaja;
        public $NoTarima;
        public $Codigo;
        public $Carro;
        public $Tarima;
        public $Caja;
        public $Sede;

        function __Construct($L){
            $this -> Limpiar = $L;
        }
        
        public function LimpiarFolio(){
            return $this -> Folio="";
        }

        public function LimpiarKilosB(){
            return $this -> KilosB="";
        }

        public function LimpiarNoCaja(){
            return $this -> NoCaja="";
        }

        public function LimpiarNoTarima(){
            return $this -> NoTarima="";
        }

        public function LimpiarCodigo(){
            return $this -> Codigo="Seleccione la variedad:";
        }

        public function LimpiarCarro(){
            return $this -> Carro="Seleccione la traila:";
        }

        public function LimpiarTarima(){
            return $this -> Tarima="Seleccione la tarima:";
        }

        public function LimpiarCaja(){
            return $this -> Caja="Seleccione la caja:";
        }

        public function LimpiarSede(){
            return $this -> Sede="Seleccione la sede:";
        }
        
    }
    

    if (isset($_POST['Insertar'])) {
        $Codigo=$_POST['Codigo'];
        $Sede=$_POST['Sede'];
        $Carro=$_POST['Carro'];
        $Tarima=$_POST['Tarima'];
        $NoTarima=$_POST['NoTarima'];
        $Caja=$_POST['Cajas'];
        $NoCaja=$_POST['NoCajas'];
        $KilosB=$_POST['KilosB'];
        $Folio=$_POST['Folio'];

        if ($Sede == "0") {
            $Error1 = "Tienes que seleccionar una sede";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Codigo == "0") {
            $Error2 = "Tienes que seleccionar una variedad";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Carro == "Seleccione la traila:") {
            $Error3 = "Tienes que seleccionar una traila";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Tarima == "Seleccione la tarima:") {
            $Error4 = "Tienes que seleccionar una tarima";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        $ValidarNoTarima = new Val_NoTarima($NoTarima);
        $Retorno = $ValidarNoTarima -> setNoTarima($NoTarima);
        $NoTarimaVal = $ValidarNoTarima -> getNoTarima();

        switch ($Retorno) {
            case '1':
                if ($Tarima == "1") {
                    $Correcto += 1;
                    $NoTarima = 0;
                }else{
                    $Correcto += 1;
                    break; 
                }
                break;
            case '2':
                $Precaucion1 = "Las tarimas deben ser mayor a 0 y menor o igual a 99";
                $NumP += 1;
                break;
            case '3':
                $Precaucion1 = "Tienes que ingresar solo números en el campo de tarimas";
                $NumP += 1;
                break;
            case '4':
                if ($Tarima == "1") {
                    $Correcto += 1;
                    $NoTarima = 0;
                }else{
                    $Error5 = "La cantidad de tarimas no puede ir vacío";
                    $NumE += 1;
                    break; 
                }
        }

        if ($Caja == "Seleccione la caja:") {
            $Error6 = "Tienes que seleccionar una caja";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        $ValidarKilosB = new Val_KilosB($KilosB);
        $Retorno = $ValidarKilosB -> setKilosB($KilosB);
        $KilosBVal = $ValidarKilosB -> getKilosB();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Precaucion2 = "Los kilos deben ser mayor a 0";
                $NumP += 1;
                break;
            case '3':
                $Precaucion2 = "Tienes que ingresar solo números en el campo de kilos brutos";
                $NumP += 1;
                break;
            case '4':
                $Error7 = "El campo de kilos no puede ir vacío";
                $NumE += 1;
                break;  
        }

        $ValidarNoCaja = new Val_NoCaja($NoCaja);
        $Retorno = $ValidarNoCaja -> setNoCaja($NoCaja);
        $NoCajaVal = $ValidarNoCaja -> getNoCaja();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Precaucion3 = "Las cajas deben ser mayor a 0 y menor o igual a 999";
                $NumP += 1;
                break;
            case '3':
                $Precaucion3 = "Tienes que ingresar solo números en la cantidad de cajas";
                $NumP += 1;
                break;
            case '4':
                $Error8 = "La cantidad de cajas no puede ir vacío";
                $NumE += 1;
                break;   
        }

        $ValidarFolio = new Val_Folio($Folio);
        $Retorno = $ValidarFolio -> setFolio($Folio);
        $FolioVal = $ValidarFolio -> getFolio();
        
        switch ($Retorno) {
            case '1':
                $Precaucion4 = "El campo de folio solo lleva letras y números";
                $NumP += 1;
                break;
            case '2':
                $Correcto += 1;
                break;
            case '3':
                $Error9 = "El campo de folio no puede ir vacío";
                $NumE += 1;
                break;    
        }

        if ($FechaR!=null && $HoraR!=null && $SemanaR!=null) {
            $Correcto += 1;
        }else{
            $Error10 = "Error con la fecha, hora o semana";
            $NumE += 1;
        }

        if ($Correcto==10) {
            $stmt = $Con->prepare("SELECT 
                                (SELECT peso_caja FROM tipos_cajas WHERE id_caja = ?) AS cajas,
                                (SELECT peso_tarima FROM tipos_tarimas WHERE id_tarima = ?) AS tarimas,
                                (SELECT peso_carro FROM tipos_carros WHERE id_carro = ?) AS carros;");
            $stmt->bind_param("iii",$Caja,$Tarima,$Carro);
            $stmt->execute();
            $Registro = $stmt->get_result();
            $NumCol=$Registro->num_rows;

            if ($NumCol>0) {
                while ($Reg = $Registro->fetch_assoc()){
                        $PesoB = $Reg['cajas'];
                        $PesoT = $Reg['tarimas'];
                        $PesoC = $Reg['carros'];
                    }
                    $stmt->close();
            }
            
            $KilosT = ($PesoB * $NoCajaVal) + ($PesoT * $NoTarima) + ($PesoC * 1);
            $KilosN = $KilosBVal - $KilosT;
            
            if ($KilosN > 0) {
                $Correcto += 1;
            }else{
                $Precaucion5 = "El taraje supera la cantidad neta";
                $NumP += 1;
            }
        }

        if ($Correcto==11) {
            $Fecha = date("dmy");
            $stmt = $Con->prepare("SELECT codigo FROM tipo_variaciones WHERE id_variedad = ?");
            $stmt->bind_param("i",$Codigo);
            $stmt->execute();
            $Registro = $stmt->get_result();
            $NumCol=$Registro->num_rows;

            if ($NumCol>0) {
                while ($Reg = $Registro->fetch_assoc()){
                        $CodigoR = $Reg['codigo'];
                    }
                    $stmt->close();
            }

            $CodigoBase = $CodigoR . "-" . $Fecha;

            $stmt = $Con->prepare("SELECT no_serie_r FROM registro_empaque WHERE fecha_r = ? AND no_serie_r LIKE CONCAT(?, '%') ORDER BY no_serie_r DESC LIMIT 1");
            $stmt->bind_param("ss", $FechaR, $CodigoBase);
            $stmt->execute();
            $Registro = $stmt->get_result();

            if ($Registro->num_rows > 0) {
                $Reg = $Registro->fetch_assoc();
                $UltimoCodigo = $Reg['no_serie_r'];
                $Numero = (int)substr($UltimoCodigo, -3);
                $NNS = str_pad($Numero + 1, 3, "0", STR_PAD_LEFT);
                $NoSerieVal = $CodigoBase . "-" . $NNS;
            } else {
                $NoSerieVal = $CodigoBase . "-001";
            }

            $stmt->close();

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $stmt = $Con->prepare("INSERT INTO registro_empaque (id_codigo_r, folio_r, id_tipo_caja , id_tipo_tarima, id_tipo_carro, p_bruto, p_taraje, p_neto, cantidad_caja, cantidad_tarima, usuario_r, fecha_r, hora_r, activo_r, tipo_registro, id_tipo_merma, no_serie_r, semana_r) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('isiiidddiisssisiss', $Codigo, $FolioVal, $Caja, $Tarima, $Carro, $KilosB, $KilosT, $KilosN, $NoCaja, $NoTarima, $Usuario, $FechaR, $HoraR, $Activo, $Tipo, $Clasificacion, $NoSerieVal, $SemanaR);
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
                $Finalizado = "Se hizo el registro correctamente";
            }
        }
    }

    include 'RegRegistro.php';
    } else { header("Location: ../Registro_empaque/CatalogoR.php"); }
?>