<?php
    include_once '../../Conexion/BD.php';
    $RutaCS = "../../Sesion/Cerrar.php";
    $RutaSC = "../../index.php";
    // include_once "../../Sesion/validar_sesion.php";
    // $Pagina=basename(__FILE__);
    // Historial($Pagina,$Con);

    $FechaR=date("Y-m-d");
    $HoraR=date("H:i:s");
    $Usuario="prueba";
    $Rol="ADMINISTRADOR";

    if ($Rol!="USUARIO") {
    $NumE=0;
    $NumI=0;
    $NumP=0;
    $Finalizado="";
    $Correcto=0;
    $Nave = isset($_POST['Nave']) ? $_POST['Nave'] : '';
    $Variedad = isset($_POST['Variedad']) ? $_POST['Variedad'] : '';
    $Tipo = isset($_POST['Tipo']) ? $_POST['Tipo'] : '';
    $Color = isset($_POST['Color']) ? $_POST['Color'] : '';
    $Superficie = isset($_POST['Superficie']) ? $_POST['Superficie'] : '';
    $Ciclo = isset($_POST['Ciclo']) ? $_POST['Ciclo'] : '';
    $Codigo = "";
    $Sede = "";
    $Invernadero = "";
    echo $Nave;
    echo $Variedad;
    echo $Tipo;
    echo $Color;
    echo $Ciclo;

    for ($i=1; $i <= 6; $i++) {
        ${"Error".$i}="";
    }

    for ($i=1; $i <= 2; $i++) {
        ${"Precaucion".$i}="";
    }

    class Val_Superficie {
        public $Superficie;
    
        function __Construct($S){
            $this -> Superficie = $S;
        }
    
        public function getSuperficie(){
            return $this -> Superficie;
        }
    
        public function setSuperficie($Superficie){
            $this -> Superficie = $Superficie;
            
            if (!empty($Superficie)) {
                if (is_numeric($Superficie)){
                    if ($Superficie > 0 && $Superficie <= 9999999) {
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

    class Val_Variedad {
        public $Variedad;
    
        function __Construct($V){
            $this -> Variedad = $V;
        }
    
        public function getVariedad(){
            return $this -> Variedad;
        }
    
        public function setVariedad($Variedad){
            $this -> Variedad = $Variedad;
            
            if (!empty($Variedad)) {
                $Variedad=filter_var($Variedad, FILTER_SANITIZE_SPECIAL_CHARS);
                
                if (!preg_match('/^[A-ZÑ0-9\s]*$/', $Variedad)){
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
        public $Nave;
        public $Variedad;
        public $Tipo;
        public $Color;
        public $Superficie;
        public $Ciclo;

        function __Construct($L){
            $this -> Limpiar = $L;
        }
        
        public function LimpiarSuperficie(){
            return $this -> Superficie="";
        }

        public function LimpiarVariedad(){
            return $this -> Variedad="";
        }

        public function LimpiarTipo(){
            return $this -> Tipo="";
        }

        public function LimpiarColor(){
            return $this -> Color="Seleccione el código:";
        }

        public function LimpiarCiclo(){
            return $this -> Ciclo="Seleccione el carro:";
        }

        public function LimpiarNave(){
            return $this -> Nave="Seleccione la nave:";
        }
        
    }
    

    if (isset($_POST['Insertar'])) {
        $Nave=$_POST['Nave'];
        $Variedad=$_POST['Variedad'];
        $Tipo=$_POST['Tipo'];
        $Color=$_POST['Color'];
        $Superficie=$_POST['Superficie'];
        $Ciclo=$_POST['Ciclo'];

        if ($Nave == "Seleccione la nave:") {
            $Error1 = "Tienes que seleccionar una nave";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        $ValidarVariedad = new Val_Variedad($Variedad);
        $Retorno = $ValidarVariedad -> setVariedad($Variedad);
        $VariedadVal = $ValidarVariedad -> getVariedad();
        
        switch ($Retorno) {
            case '1':
                $Precaucion1 = "El campo de variedad solo lleva letras y números";
                $NumP += 1;
                break;
            case '2':
                $Correcto += 1;
                break;
            case '3':
                $Error2 = "El campo de variedad no puede ir vacío";
                $NumE += 1;
                break;    
        }

        if ($Tipo == "Seleccione el tipo:") {
            $Error3 = "Tienes que seleccionar un tipo";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Color == "Seleccione el color:") {
            $Error4 = "Tienes que seleccionar un color";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        $ValidarSuperficie = new Val_Superficie($Superficie);
        $Retorno = $ValidarSuperficie -> setSuperficie($Superficie);
        $SuperficieVal = $ValidarSuperficie -> getSuperficie();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Precaucion2 = "la superficie deben ser mayor a 0";
                $NumP += 1;
                break;
            case '3':
                $Precaucion2 = "Tienes que ingresar solo números en el campo de superficie";
                $NumP += 1;
                break;
            case '4':
                $Error5 = "El campo de superficie no puede ir vacío";
                $NumE += 1;
                break;  
        }

        if ($Ciclo == "Seleccione el ciclo:") {
            $Error6 = "Tienes que seleccionar un ciclo";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Correcto==6) {
            $stmt = $Con->prepare("SELECT 
                                (SELECT id_sede_i FROM invernaderos WHERE id_invernadero = ?) AS sede,
                                (SELECT abreviatura FROM variedades WHERE id_nombre_v = ?) AS abreviatura,
                                (SELECT invernadero FROM invernaderos WHERE id_invernadero = ?) AS nave;");
            $stmt->bind_param("ii",$Sede,$Invernadero);
            $stmt->execute();
            $Registro = $stmt->get_result();
            $NumCol=$Registro->num_rows;

            if ($NumCol>0) {
                while ($Reg = $Registro->fetch_assoc()){
                        $Sede = $Reg['sede'];
                        $Invernadero = $Reg['nave'];
                        $Abreviatura = $Reg['abreviatura'];
                    }
                    $stmt->close();
            }

            $Codigo = $Sede . $Invernadero . $Abreviatura;

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $stmt = $Con->prepare("INSERT INTO registro_empaque (id_codigo_r, id_presentacion_r, folio_r, id_tipo_caja , id_tipo_tarima, id_tipo_carro, p_bruto, p_taraje, p_neto, cantidad_caja, usuario_r, fecha_r, hora_r, activo_r, tipo_registro, id_tipo_merma, no_serie_r, semana_r) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('iisiiidddisssisiss', $Codigo, $Presentacion, $FolioVal, $Caja, $Tarima, $Carro, $KilosB, $KilosT, $KilosN, $NoCaja, $Usuario, $FechaR, $HoraR, $Activo, $Clase, $Clasificacion, $NoSerieVal, $SemanaR);
                $stmt->execute();
                $stmt->close();
                $Limpiar = new Cleanner($Folio,$KilosB,$NoCaja,$Codigo,$Carro,$Tarima,$Caja,$Clasificacion,$Tipo);
                $Folio = $Limpiar -> LimpiarFolio();
                $KilosB = $Limpiar -> LimpiarKilosB();
                $NoCaja = $Limpiar -> LimpiarNoCaja();
                $Codigo = $Limpiar -> LimpiarCodigo();
                $Carro = $Limpiar -> LimpiarCarro();
                $Tarima = $Limpiar -> LimpiarTarima();
                $Caja = $Limpiar -> LimpiarCaja();
                $Clasificacion = $Limpiar -> LimpiarClasificacion();
                $Tipo = $Limpiar -> LimpiarTipo();
                $Finalizado = "Se hizo el registro correctamente";
            }
            
        }

    }

    include 'RegMerma.php';
    } else { header("Location: ../Registro_empaque/CatalogoR.php"); }
?>