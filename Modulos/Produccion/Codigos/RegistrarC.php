<?php
    include_once '../../../Conexion/BD.php';
    $RutaCS = "../../../Login/Cerrar.php";
    $RutaSC = "../../../index.php";
    include_once "../../../Login/validar_sesion.php";
    // $Pagina=basename(__FILE__);
    // Historial($Pagina,$Con);
    $Ver = TienePermiso($_SESSION['ID'], "Produccion/Codigos", 1, $Con);
    $Crear = TienePermiso($_SESSION['ID'], "Produccion/Codigos", 2, $Con);
    $Editar = TienePermiso($_SESSION['ID'], "Produccion/Codigos", 3, $Con);
    $Eliminar = TienePermiso($_SESSION['ID'], "Produccion/Codigos", 4, $Con);

    $HoraP=date("H:i:s");

   if ($TipoRol=="ADMINISTRADOR" || $Crear==true) {
    $NumE=0;
    $NumI=0;
    $NumP=0;
    $Finalizado="";
    $Correcto=0;
    $Sede = isset($_POST['TipoRegistro']) ? $_POST['TipoRegistro'] : '';
    $Nave = isset($_POST['Nave']) ? $_POST['Nave'] : '';
    $Variedad = isset($_POST['Variedad']) ? $_POST['Variedad'] : '';
    $Tipo = isset($_POST['Tipo']) ? $_POST['Tipo'] : '';
    $Color = isset($_POST['Color']) ? $_POST['Color'] : '';
    $Superficie = isset($_POST['Superficie']) ? $_POST['Superficie'] : '';
    $Ciclo = isset($_POST['Ciclo']) ? $_POST['Ciclo'] : '';
    $Presentacion = isset($_POST['Presentacion']) ? $_POST['Presentacion'] : '';
    $Codigo = "";
    $Sede = "";
    $Invernadero = "";
    $NaveSeleccionada = $_POST['Nave'] ?? '';

    for ($i=1; $i <= 8; $i++) {
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

    class Cleanner{
        public $Limpiar;
        public $Nave;
        public $Variedad;
        public $Tipo;
        public $Color;
        public $Superficie;
        public $Ciclo;
        public $Presentacion;
        public $Sede;

        function __Construct($L){
            $this -> Limpiar = $L;
        }
        
        public function LimpiarSuperficie(){
            return $this -> Superficie="";
        }

        public function LimpiarVariedad(){
            return $this -> Variedad="Seleccione la variedad:";
        }

        public function LimpiarTipo(){
            return $this -> Tipo="";
        }

        public function LimpiarColor(){
            return $this -> Color="Seleccione el código:";
        }

        public function LimpiarCiclo(){
            return $this -> Ciclo="Seleccione el ciclo:";
        }

        public function LimpiarNave(){
            return $this -> Nave="Seleccione la nave:";
        }
        
        public function LimpiarPresentacion(){
            return $this -> Presentacion="Seleccione la presentación:";
        }

        public function LimpiarSede(){
            return $this -> Sede="Seleccione la sede:";
        }
        
    }
    

    if (isset($_POST['Insertar'])) {
        $Nave=$_POST['Nave'];
        $Variedad=$_POST['Variedad'];
        $Tipo=$_POST['Tipo'];
        $Color=$_POST['Color'];
        $Superficie=$_POST['Superficie'];
        $Ciclo=$_POST['Ciclo'];
        $Presentacion=$_POST['Presentacion'];
        $Sede=$_POST['TipoRegistro'];

        if ($Sede == "0") {
            $Error1 = "Tienes que seleccionar una sede";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Nave == "0") {
            $Error2 = "Tienes que seleccionar una nave";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Variedad == "Seleccione la variedad:") {
            $Error3 = "Tienes que seleccionar una variedad";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Tipo == "0") {
            $Error4 = "Tienes que seleccionar un tipo";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Color == "0") {
            $Error5 = "Tienes que seleccionar un color";
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
                $Error6 = "El campo de superficie no puede ir vacío";
                $NumE += 1;
                break;  
        }

        if ($Presentacion == "Seleccione la presentación:") {
            $Error7 = "Tienes que seleccionar una presentación";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Ciclo == "Seleccione el ciclo:") {
            $Error8 = "Tienes que seleccionar un ciclo";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }
        
        if ($Correcto==8) {
            $stmt = $Con->prepare("SELECT 
                                (SELECT id_sede_i FROM invernaderos WHERE id_invernadero = ?) AS sede,
                                (SELECT abreviatura FROM variedades WHERE id_nombre_v = ?) AS abreviatura,
                                (SELECT invernadero FROM invernaderos WHERE id_invernadero = ?) AS nave;");
            $stmt->bind_param("iii",$Nave,$Variedad,$Nave);
            $stmt->execute();
            $Registro = $stmt->get_result();
            $NumCol=$Registro->num_rows;

            if ($NumCol>0) {
                while ($Reg = $Registro->fetch_assoc()){
                        $Sedes = $Reg['sede'];
                        $Invernadero = $Reg['nave'];
                        $Abreviatura = $Reg['abreviatura'];
                    }
                    $stmt->close();
            }

            $Codigo = $Sedes . "-" . $Invernadero . "-" . $Abreviatura;

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $stmt = $Con->prepare("INSERT INTO tipo_variaciones (codigo, id_nombre_v, tipo , color, superficie, id_presentacion_v, id_ciclo_v, id_modulo_v) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param('sissdiii', $Codigo, $Variedad, $Tipo, $Color, $Superficie, $Presentacion, $Ciclo, $Nave);
                $stmt->execute();
                $stmt->close();
                $Limpiar = new Cleanner($Variedad, $Tipo, $Color, $Superficie, $Presentacion, $Sede, $Ciclo, $Nave);
                $Variedad = $Limpiar -> LimpiarVariedad();
                $Tipo = $Limpiar -> LimpiarTipo();
                $Color = $Limpiar -> LimpiarColor();
                $Superficie = $Limpiar -> LimpiarSuperficie();
                $Presentacion = $Limpiar -> LimpiarPresentacion();
                $Sede = $Limpiar -> LimpiarSede();
                $Ciclo = $Limpiar -> LimpiarCiclo();
                $Nave = $Limpiar -> LimpiarNave();
                $Finalizado = "Se hizo el registro correctamente";
            }
            
        }

    }

    include 'RegCodigos.php';
    } else { header("Location: CatalogoC.php"); }
?>