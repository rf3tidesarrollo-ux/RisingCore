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
    $SemanaR=date("Y-W");
    $Activo=1;

   if ($TipoRol=="ADMINISTRADOR" || $Crear==true) {
    $NumE=0;
    $NumI=0;
    $NumP=0;
    $Finalizado="";
    $Correcto=0;
    $Sede = "";
    $Tipo = "";
    $Codigo = "";
    $Carro = "";
    $Tarima = "";
    $NoTarima = "";
    $Caja = "";
    $NoCaja = "";
    $KilosB = "";
    $Fecha = "";
    $Presentacion = "";
    $Clasificacion = "";
    $CodigoR = "";

    for ($i=1; $i <= 14; $i++) {
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
                    if ($NoCaja > 0 && $NoCaja <= 999) {
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
        public $Fecha;
        public $KilosB;
        public $NoCaja;
        public $Codigo;
        public $Carro;
        public $Tarima;
        public $Caja;
        public $Presentacion;
        public $Clasificacion;
        public $Tipo;
        public $Sede;
        public $NoTarima;

        function __Construct($L){
            $this -> Limpiar = $L;
        }
        
        public function LimpiarFecha(){
            return $this -> Fecha="";
        }

        public function LimpiarKilosB(){
            return $this -> KilosB="";
        }

        public function LimpiarNoCaja(){
            return $this -> NoCaja="";
        }

        public function LimpiarCodigo(){
            return $this -> Codigo="Seleccione el código:";
        }

        public function LimpiarCarro(){
            return $this -> Carro="Seleccione el carro:";
        }

        public function LimpiarTarima(){
            return $this -> Tarima="Seleccione la tarima:";
        }

        public function LimpiarCaja(){
            return $this -> Caja="Seleccione la caja:";
        }

        public function LimpiarPresentacion(){
            return $this -> Presentacion="Seleccione la presentación:";
        }

        public function LimpiarClasificacion(){
            return $this -> Clasificacion="Seleccione la clasificación:";
        }

        public function LimpiarTipo(){
            return $this -> Tipo="Seleccione el tipo de merma:";
        }
        
        public function LimpiarSede(){
            return $this -> Sede="Seleccione la sede:";
        }

        public function LimpiarNoTarima(){
            return $this -> NoTarima="";
        }  
    }

    if (isset($_POST['Modificar'])) {
        $id=$_POST['id'];
        $Codigo=$_POST['Codigo'];
        $Carro=$_POST['Carro'];
        $Tarima=$_POST['Tarima'];
        $Caja=$_POST['Cajas'];
        $NoCaja=$_POST['NoCajas'];
        $KilosB=$_POST['KilosB'];
        $Clasificacion=$_POST['Clasificacion'];
        $Tipo=$_POST['TipoRegistro'];
        $Sede=$_POST['Sede'];
        $NoTarima=$_POST['NoTarima'];
        $Fecha=$_POST['Fecha'];
        $Presentacion=$_POST['Presentacion'];

        if ($Sede == "0") {
            $Error1 = "Tienes que seleccionar una sede";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Tipo == "0") {
            $Error2 = "Tienes que seleccionar un tipo de merma";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Codigo == "0") {
            if ($Tipo == "PRODUCCIÓN") {
                $Error3 = "Tienes que seleccionar una variedad";
                $NumE += 1;
            } else {
                $Codigo = NULL;
                $Correcto += 1;
            }
        }else{
            $Correcto += 1;
        }

        if ($Presentacion == "Seleccione la presentación:") {
            if ($Tipo == "PRODUCCIÓN") {
                $Error13 = "Tienes que seleccionar una presentación";
                $NumE += 1;
            } else {
                $Presentacion = NULL;
                $Correcto += 1;
            }
        }else{
            $Correcto += 1;
        }
        
        if ($Clasificacion == "0") {
            $Error4 = "Tienes que seleccionar una clasificación";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Carro == "Seleccione el carro:") {
            $Error5 = "Tienes que seleccionar un carro";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Tarima == "Seleccione la tarima:") {
            $Error6 = "Tienes que seleccionar una tarima";
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
                    $Error7 = "La cantidad de tarimas no puede ir vacío";
                    $NumE += 1;
                    break; 
                }
        }

        if ($Caja == "Seleccione la caja:") {
            $Error8 = "Tienes que seleccionar una caja";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        $ValidarNoCaja = new Val_NoCaja($NoCaja);
        $Retorno = $ValidarNoCaja -> setNoCaja($NoCaja);
        $NoCajaVal = $ValidarNoCaja -> getNoCaja();

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
                $Error9 = "El campo de cajas no puede ir vacío";
                $NumE += 1;
                break;   
        }

        $ValidarKilosB = new Val_KilosB($KilosB);
        $Retorno = $ValidarKilosB -> setKilosB($KilosB);
        $KilosBVal = $ValidarKilosB -> getKilosB();

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
                $Error10 = "El campo de kilos no puede ir vacío";
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
                $Error11 = "La fecha ingresada es incorrecta";
                $NumE += 1;
                break;
            case '3':
                $Error11 = "El campo de fecha no puede ir vacío";
                $NumE += 1;
                break;    
        }

        if ($FechaR!=null && $HoraR!=null && $SemanaR!=null) {
            $Correcto += 1;
        }else{
            $Error12 = "Error con la fecha, hora o semana";
            $NumE += 1;
        }
        
        if ($Correcto==13) {
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

        if ($Correcto==14) {
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

            switch ($Sede) {
                case 'RF1':
                    $Sede = 1;
                    break;
                case 'RF2':
                    $Sede = 2;
                    break;
                case 'RF3':
                    $Sede = 3;
                    break;
                default:
                    $Sede = "RF";
                    break;
            }

            if ($CodigoR=="") {
                switch ($Tipo) {
                    case 'PRODUCCIÓN':
                        $CodigoR = $Sede . '-' . "PRD";
                        break;
                    case 'EMPAQUE-NACIONAL':
                        $CodigoR = $Sede. '-' . "EPN";
                        break;
                    case 'EMPAQUE-MERMA':
                        $CodigoR = $Sede. '-' . "EPM";
                        break;
                    case 'MERMA':
                        $CodigoR = $Sede. '-' . "MER";
                        break;
                }
            }
            
            $CodigoBase = $CodigoR . "-" . $Fecha;

            $stmt = $Con->prepare("SELECT no_serie_m FROM registro_merma WHERE fecha_m = ? AND no_serie_m LIKE CONCAT(?, '%') ORDER BY no_serie_m DESC LIMIT 1");
            $stmt->bind_param("ss", $FechaR, $CodigoBase);
            $stmt->execute();
            $Registro = $stmt->get_result();

            if ($Registro->num_rows > 0) {
                $Reg = $Registro->fetch_assoc();
                $UltimoCodigo = $Reg['no_serie_m'];
                $Numero = (int)substr($UltimoCodigo, -3);
                $NNS = str_pad($Numero + 1, 3, "0", STR_PAD_LEFT);
                $NoSerieVal = $CodigoBase . "-" . $NNS;
            } else {
                $NoSerieVal = $CodigoBase . "-001";
            }

            $stmt->close();

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $stmt = $Con->prepare("UPDATE registro_merma SET id_codigo_m=?, id_presentacion_m=?, id_clasificacion=?, id_tipo_caja=?, id_tipo_tarima=?, id_tipo_carro=?, p_bruto=?, p_taraje=?, p_neto=?, cantidad_caja=?, cantidad_tarima=?, usuario_m=?, fecha_reg=?, fecha_m=?, hora_m=?, activo_m=?, kilos_dis=?, cajas_dis=?, no_serie_m=?, semana_m=? WHERE id_registro_m=?");
                $stmt->bind_param('iiiiiidddiiisssidissi', $Codigo, $Presentacion, $Clasificacion, $Caja, $Tarima, $Carro, $KilosB, $KilosT, $KilosN, $NoCajaVal, $NoTarima, $ID, $FechaVal, $FechaR, $HoraR, $Activo, $KilosN, $NoCajaVal, $NoSerieVal, $SemanaR, $id);
                $stmt->execute();
                $stmt->close();

                $Limpiar = new Cleanner($Fecha,$KilosB,$NoCaja,$NoTarima,$Codigo,$Carro,$Tarima,$Caja,$Sede,$Clasificacion,$Tipo);
                $Fecha = $Limpiar -> LimpiarFecha();
                $KilosB = $Limpiar -> LimpiarKilosB();
                $NoCaja = $Limpiar -> LimpiarNoCaja();
                $Codigo = $Limpiar -> LimpiarCodigo();
                $Carro = $Limpiar -> LimpiarCarro();
                $Tarima = $Limpiar -> LimpiarTarima();
                $NoTarima = $Limpiar -> LimpiarNoTarima();
                $Caja = $Limpiar -> LimpiarCaja();
                $Sede = $Limpiar -> LimpiarSede();
                $Presentacion = $Limpiar -> LimpiarPresentacion();
                $Clasificacion = $Limpiar -> LimpiarClasificacion();
                $Tipo = $Limpiar -> LimpiarTipo();
                
                session_start();
                $_SESSION['correcto'] = "Se hizo el registro correctamente";
                header("Location: EditarM.php?id=" . $id);
                exit();
            }
        }
    }

    if (empty($_GET['id'])) {
        if (!empty($_POST)) {
            $ID=$_POST['id'];
        }else{
            header('Location: CatalogoM.php');
        }
        $stmt = $Con->prepare("SELECT * FROM registro_merma 
                            LEFT JOIN tipo_variaciones ON registro_merma.id_codigo_m = tipo_variaciones.id_variedad
                            LEFT JOIN invernaderos ON tipo_variaciones.id_modulo_v = invernaderos.id_invernadero
                            LEFT JOIN sedes ON invernaderos.id_sede_i = sedes.id_sede
                            LEFT JOIN clasificacion_merma ON registro_merma.id_clasificacion = clasificacion_merma.id_merma 
                            WHERE id_registro_m=?");
        $stmt->bind_param("i",$ID);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $ID=$Reg['id_registro_m'];
                    $Sede=$Reg['codigo_s'];
                    $Presentacion=$Reg['id_presentacion_m'];
                    $Tipo=$Reg['tipo_merma'];
                    if ($Tipo=="PRODUCCIÓN") {
                        $Sede=$Reg['codigo_s'];
                    }else{
                        $NS = $Reg['no_serie_m'];
                        $PRT = explode('-', $NS);
                        $S = $PRT[0];

                        switch ($S) {
                            case '1':
                                $Sede = "RF1";
                                break;
                            case '2':
                                $Sede = "RF2";
                                break;
                            case '3':
                                $Sede = "RF3";
                                break;
                            default:
                                $Sede = "RF";
                                break;
                        }
                    }
                    $Tipo=$Reg['tipo_merma'];
                    $Codigo=$Reg['id_variedad'];
                    $Clasificacion=$Reg['id_merma'];
                    $Carro=$Reg['id_tipo_carro'];
                    $Tarima=$Reg['id_tipo_tarima'];
                    $NoTarima=$Reg['cantidad_tarima'];
                    $Caja=$Reg['id_tipo_caja'];
                    $NoCaja=$Reg['cantidad_caja'];
                    $KilosB=$Reg['p_bruto'];
                    $Fecha=$Reg['fecha_reg'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoM.php');
            }
    }else{
        $ID=$_GET['id'];
        $stmt = $Con->prepare("SELECT * FROM registro_merma 
                            LEFT JOIN tipo_variaciones ON registro_merma.id_codigo_m = tipo_variaciones.id_variedad
                            LEFT JOIN invernaderos ON tipo_variaciones.id_modulo_v = invernaderos.id_invernadero
                            LEFT JOIN sedes ON invernaderos.id_sede_i = sedes.id_sede
                            LEFT JOIN clasificacion_merma ON registro_merma.id_clasificacion = clasificacion_merma.id_merma 
                            WHERE id_registro_m=?");
        $stmt->bind_param("i",$ID);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $ID=$Reg['id_registro_m'];
                    $Presentacion=$Reg['id_presentacion_m'];
                    $Tipo=$Reg['tipo_merma'];
                    if ($Tipo=="PRODUCCIÓN") {
                        $Sede=$Reg['codigo_s'];
                    }else{
                        $NS = $Reg['no_serie_m'];
                        $PRT = explode('-', $NS);
                        $S = $PRT[0];

                        switch ($S) {
                            case '1':
                                $Sede = "RF1";
                                break;
                            case '2':
                                $Sede = "RF2";
                                break;
                            case '3':
                                $Sede = "RF3";
                                break;
                            default:
                                $Sede = "RF";
                                break;
                        }
                    }
                    $Codigo=$Reg['id_variedad'];
                    $Clasificacion=$Reg['id_merma'];
                    $Carro=$Reg['id_tipo_carro'];
                    $Tarima=$Reg['id_tipo_tarima'];
                    $NoTarima=$Reg['cantidad_tarima'];
                    $Caja=$Reg['id_tipo_caja'];
                    $NoCaja=$Reg['cantidad_caja'];
                    $KilosB=$Reg['p_bruto'];
                    $Fecha=$Reg['fecha_reg'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoM.php');
            }
    }

    include 'EditMerma.php';
    } else { header("Location: CatalogoM.php"); }
?>