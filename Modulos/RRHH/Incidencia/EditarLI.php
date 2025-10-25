<?php
    include_once '../../../Conexion/BD.php';
    $RutaCS = "../../../Login/Cerrar.php";
    $RutaSC = "../../../index.php";
    include_once "../../../Login/validar_sesion.php";
    // $Pagina=basename(__FILE__);
    // Historial($Pagina,$Con);
    $Ver = TienePermiso($_SESSION['ID'], "RRHH/Incidencia", 1, $Con);
    $Crear = TienePermiso($_SESSION['ID'], "RRHH/Incidencia", 2, $Con);
    $Editar = TienePermiso($_SESSION['ID'], "RRHH/Incidencia", 3, $Con);
    $Eliminar = TienePermiso($_SESSION['ID'], "RRHH/Incidencia", 4, $Con);

    $FechaR=date("Y-m-d");

   if ($TipoRol=="ADMINISTRADOR" || $Editar==true) {
    $NumE=0;
    $NumI=0;
    $NumP=0;
    $Finalizado="";
    $Correcto=0;
    $Sede = isset($_POST['Sede']) ? $_POST['Sede'] : '';
    $Nombre = isset($_POST['Nombre']) ? $_POST['Nombre'] : '';
    $FI = isset($_POST['FI']) ? $_POST['FI'] : '';
    $FF = isset($_POST['FF']) ? $_POST['FF'] : '';
    $Motivo = isset($_POST['Motivo']) ? $_POST['Motivo'] : '';
    $Departamento = isset($_POST['Departamento']) ? $_POST['Departamento'] : '';

    for ($i=1; $i <= 5; $i++) {
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
        public $Nombre;
        public $Permiso;
        public $FI;
        public $FF;

        function __Construct($L){
            $this -> Limpiar = $L;
        }

        public function LimpiarSede(){
            return $this -> Sede="Seleccione la sede:";
        }

        public function LimpiarNombre(){
            return $this -> Nombre="Seleccione el nombre";
        }

        public function LimpiarPermiso(){
            return $this -> Permiso="Seleccione el tipo de permiso:";
        }

        public function LimpiarFI(){
            return $this -> FI="";
        }

        public function LimpiarFF(){
            return $this -> FF="";
        }
    }

    if (isset($_POST['Modificar'])) {
        $idIncidencia=$_POST['id'];
        $Sede=$_POST['Sede'];
        $Nombre=$_POST['Nombre'];
        $Permiso=$_POST['Permiso'];
        $FI=$_POST['FI'];
        
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
       
        if ($Permiso == "0") {
            $Error3 = "Tienes que seleccionar un tipo de permiso";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        $ValidarFecha = new Val_Fecha($FI);
        $Retorno = $ValidarFecha -> setFecha($FI);
        $FIVal = $ValidarFecha -> getFecha();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Error4 = "La fecha inicial ingresada es incorrecta";
                $NumE += 1;
                break;
            case '3':
                $Error4 = "La fecha inicial no puede ir vacía";
                $NumE += 1;
                break;    
        }

        if ($Correcto==4) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $stmt = $Con->prepare("UPDATE rh_check SET sede=?, badge=?, registro_check=?, id_dptipo=? WHERE id_check=?");
                $stmt->bind_param("issii", $SedeVal, $Nombre, $FI, $Permiso, $idIncidencia);
                $stmt->execute();
                $stmt->close();
                
                $Limpiar = new Cleanner($Sede,$Nombre,$Permiso,$FI);
                $Sede = $Limpiar -> LimpiarSede();
                $Nombre = $Limpiar -> LimpiarNombre();
                $Permiso = $Limpiar -> LimpiarPermiso();
                $FI = $Limpiar -> LimpiarFI();

                session_start();
                $_SESSION['correcto'] = "Permiso actualizado";
                header("Location: EditarLI.php?id=" . $idIncidencia);
                exit();
            }
        }
    }

    if (empty($_GET['id'])) {
        if (!empty($_POST)) {
            $ID=$_POST['id'];
        }else{
            header('Location: CatalogoCI.php');
        }
        $stmt = $Con->prepare("SELECT c.id_check, s.codigo_s, c.badge, c.id_dptipo, DATE(c.registro_check) AS registro, p.id_depto_pl FROM rh_check c JOIN rh_personal p ON c.badge = p.badge JOIN sedes s ON p.id_sede_pl = s.id_sede WHERE id_check=?");
        $stmt->bind_param("i",$ID);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $ID=$Reg['id_check'];
                    $Sede=$Reg['codigo_s'];
                    $Departamento=$Reg['id_depto_pl'];
                    $Nombre=$Reg['badge'];
                    $Permiso=$Reg['id_dptipo'];
                    $FI=$Reg['registro'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoCI.php');
            }
    }else{
        $ID=$_GET['id'];
        $stmt = $Con->prepare("SELECT c.id_check, s.codigo_s, c.badge, c.id_dptipo, DATE(c.registro_check) AS registro, p.id_depto_pl FROM rh_check c JOIN rh_personal p ON c.badge = p.badge JOIN sedes s ON p.id_sede_pl = s.id_sede WHERE id_check=?");
        $stmt->bind_param("i",$ID);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $ID=$Reg['id_check'];
                    $Sede=$Reg['codigo_s'];
                    $Departamento=$Reg['id_depto_pl'];
                    $Nombre=$Reg['badge'];
                    $Permiso=$Reg['id_dptipo'];
                    $FI=$Reg['registro'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoCI.php');
            }
    }

    include 'EditIncidencia.php';
    } else { header("Location: CatalogoCI.php"); }
?>