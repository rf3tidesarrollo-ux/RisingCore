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

   if ($TipoRol=="ADMINISTRADOR" || $Crear==true) {
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

    if (isset($_POST['Insertar'])) {
        $Sede=$_POST['Sede'];
        $Nombre=$_POST['Nombre'];
        $Permiso=$_POST['Permiso'];
        $FI=$_POST['FI'];
        $FF=$_POST['FF'];
        
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

        $ValidarFecha = new Val_Fecha($FF);
        $Retorno = $ValidarFecha -> setFecha($FF);
        $FFVal = $ValidarFecha -> getFecha();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Error5 = "La fecha final ingresada es incorrecta";
                $NumE += 1;
                break;
            case '3':
                $Error5 = "La fecha final no puede ir vacía";
                $NumE += 1;
                break;    
        }

        if ($FFVal < $FIVal) {
            $Precaucion1 = "La fecha final no puede ser menor a la inicial";
            $NumP += 1;
        }else{
            $Correcto += 1;
        }
        
        if ($Correcto==6) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $inicio = new DateTime($FIVal);
                $termino = new DateTime($FFVal);

                // Añadir un día extra a $termino si quieres incluir el último día
                $termino->modify('+1 day');

                $cont = 0;

                while ($inicio < $termino) {
                    $cont++;

                    // Formato completo de fecha y hora
                    $Registro = $inicio->format('Y-m-d H:i:s');

                    $stmt = $Con->prepare("INSERT INTO rh_check (sede, badge, registro_check, id_dptipo, id_dispositivo, hora_salida, hora_sabado, hora_domingo) VALUES (?, ?, ?, ?, 1, '00:00:00', '00:00:00', '00:00:00')");
                    $stmt->bind_param('issi', $SedeVal, $Nombre, $Registro, $Permiso);
                    $stmt->execute();
                    $stmt->close();

                    // Avanzar un día
                    $inicio->modify('+1 day');
                }
                
                $Limpiar = new Cleanner($Sede,$Nombre,$Permiso,$FI,$FF);
                $Sede = $Limpiar -> LimpiarSede();
                $Nombre = $Limpiar -> LimpiarNombre();
                $Permiso = $Limpiar -> LimpiarPermiso();
                $FI = $Limpiar -> LimpiarFI();
                $FF = $Limpiar -> LimpiarFF();
                
                session_start();
                $_SESSION['correcto'] = "Incidencia registrada";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
        }
    }

    include 'RegIncidencia.php';
    } else { header("Location: CatalogoLI.php"); }
?>