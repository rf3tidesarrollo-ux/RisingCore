<?php
    include_once '../../../Conexion/BD.php';
    $RutaCS = "../../../Login/Cerrar.php";
    $RutaSC = "../../../index.php";
    include_once "../../../Login/validar_sesion.php";
    // $Pagina=basename(__FILE__);
    // Historial($Pagina,$Con);
    $Ver = TienePermiso($_SESSION['ID'], "RRHH/Contrato", 1, $Con);
    $Crear = TienePermiso($_SESSION['ID'], "RRHH/Contrato", 2, $Con);
    $Editar = TienePermiso($_SESSION['ID'], "RRHH/Contrato", 3, $Con);
    $Eliminar = TienePermiso($_SESSION['ID'], "RRHH/Contrato", 4, $Con);

    $FechaR=date("Y-m-d");

   if ($TipoRol=="ADMINISTRADOR" || $Crear==true) {
    $NumE=0;
    $NumI=0;
    $NumP=0;
    $Finalizado="";
    $Correcto=0;
    $Sede = isset($_POST['Sede']) ? $_POST['Sede'] : '';
    $Nombre = isset($_POST['Nombre']) ? $_POST['Nombre'] : '';
    $Departamento = isset($_POST['Departamento']) ? $_POST['Departamento'] : '';
    $Contrato = isset($_POST['Contrato']) ? $_POST['Contrato'] : '';

    for ($i=1; $i <= 3; $i++) {
        ${"Error".$i}="";
    }

    for ($i=1; $i <= 1; $i++) { 
        ${"Precaucion".$i}="";
    }

    for ($i=1; $i <= 1; $i++) { 
        ${"Informacion".$i}="";
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
        public $Departamento;
        public $Nombre;
        public $Contrato;

        function __Construct($L){
            $this -> Limpiar = $L;
        }

        public function LimpiarSede(){
            return $this -> Sede="Seleccione la sede:";
        }

        public function LimpiarDepartamento(){
            return $this -> Departamento="Seleccione el departamento:";
        }

        public function LimpiarNombre(){
            return $this -> Nombre="Seleccione el nombre";
        }

        public function LimpiarContrato(){
            return $this -> Contrato="Seleccione el tipo de contrato:";
        }
    }

    if (isset($_POST['Insertar'])) {
        $Sede=$_POST['Sede'];
        $Nombre=$_POST['Nombre'];
        $Contrato=$_POST['Contrato'];
        
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

        if ($Nombre == "0") {
            $Error2 = "Tienes que seleccionar un nombre";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }
       
        if ($Contrato == "0") {
            $Error3 = "Tienes que seleccionar un tipo de contrato";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Correcto==3) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $stmt = $Con->prepare("INSERT INTO rh_contrato (sede, id_personal, badge, contrato) VALUES (?, ?, ?)");
                $stmt->bind_param('iiss', $SedeVal, $Nombre, $Badge, $Contrato);
                $stmt->execute();
                $stmt->close();
                
                $Limpiar = new Cleanner($Sede,$Departamento,$Nombre,$Contrato);
                $Sede = $Limpiar -> LimpiarSede();
                $Departamento = $Limpiar -> LimpiarDepartamento();
                $Nombre = $Limpiar -> LimpiarNombre();
                $Contrato = $Limpiar -> LimpiarContrato();
                
                session_start();
                $_SESSION['correcto'] = "Contrato realizado";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
        }
    }

    include 'RegContrato.php';
    } else { header("Location: CatalogoCC.php"); }
?>