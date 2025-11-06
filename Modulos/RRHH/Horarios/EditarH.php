<?php
    include_once '../../../Conexion/BD.php';
    $RutaCS = "../../../Login/Cerrar.php";
    $RutaSC = "../../../index.php";
    include_once "../../../Login/validar_sesion.php";
    // $Pagina=basename(__FILE__);
    // Historial($Pagina,$Con);
    $Ver = TienePermiso($_SESSION['ID'], "RRHH/Horarios", 1, $Con);
    $Crear = TienePermiso($_SESSION['ID'], "RRHH/Horarios", 2, $Con);
    $Editar = TienePermiso($_SESSION['ID'], "RRHH/Horarios", 3, $Con);
    $Eliminar = TienePermiso($_SESSION['ID'], "RRHH/Horarios", 4, $Con);

   if ($TipoRol=="ADMINISTRADOR" || $Editar==true) {
    $NumE=0;
    $NumI=0;
    $NumP=0;
    $Finalizado="";
    $Correcto=0;
    $Sede = isset($_POST['Sede']) ? $_POST['Sede'] : '';
    $Nombre = isset($_POST['Nombre']) ? $_POST['Nombre'] : '';
    $HoraE = isset($_POST['HoraE']) ? $_POST['HoraE'] : '';
    $HoraS = isset($_POST['HoraS']) ? $_POST['HoraS'] : '';
    $HoraSE = isset($_POST['HoraSE']) ? $_POST['HoraSE'] : '';
    $HoraSS = isset($_POST['HoraSS']) ? $_POST['HoraSS'] : '';
    $HoraDE = isset($_POST['HoraDE']) ? $_POST['HoraDE'] : '';
    $HoraDS = isset($_POST['HoraDS']) ? $_POST['HoraDS'] : '';

    for ($i=1; $i <= 11; $i++) {
        ${"Error".$i}="";
    }

    for ($i=1; $i <= 7; $i++) { 
        ${"Precaucion".$i}="";
    }

    for ($i=1; $i <= 1; $i++) { 
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

    class Val_Hora {
        public $Hora;

        function __construct($H){
            $this->Hora = $H;
        }

        public function getHora(){
            return $this->Hora;
        }

        function setHora($Hora){
            if (!empty($Hora)) {
                // Validar formato con regex HH:MM o HH:MM:SS
                if (preg_match('/^([01]\d|2[0-3]):[0-5]\d(:[0-5]\d)?$/', $Hora)) {
                    return 1; // Hora válida
                    
                } else {
                    return 2; // Formato incorrecto
                }
            } else {
                return 3; // Hora vacía
            }
        }
    }

    class Cleanner{
        public $Limpiar;
        public $Sede;
        public $Nombre;
        public $HoraE;
        public $HoraS;
        public $HoraSE;
        public $HoraSS;
        public $HoraDE;
        public $HoraDS;

        function __Construct($L){
            $this -> Limpiar = $L;
        }

        public function LimpiarSede(){
            return $this -> Sede="Seleccione la sede:";
        }

        public function LimpiarNombre(){
            return $this -> Nombre="";
        }

        public function LimpiarHora(){
            return $this -> Hora="";
        }
    }

    if (isset($_POST['Modificar'])) {
        $idHorario=$_POST['id'];
        $Sede=$_POST['Sede'];
        $Nombre=$_POST['Nombre'];
        $HoraE=$_POST['HoraE'];
        $HoraS=$_POST['HoraS'];
        $HoraSE=$_POST['HoraSE'];
        $HoraSS=$_POST['HoraSS'];
        $HoraDE=$_POST['HoraDE'];
        $HoraDS=$_POST['HoraDS'];
        
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
                $Precaucion1 = "El nombre del horario solo lleva mayúsculas y letras";
                $NumP += 1;
                break;
            case '2':
                $Correcto += 1;
                break;
            case '3':
                $Error2 = "El nombre del horario no puede ir vacío";
                $NumE += 1;
                break;    
        }
        
        $ValidarHora = new Val_Hora($HoraE);
        $Retorno = $ValidarHora -> setHora($HoraE);
        $HoraEVal = $ValidarHora -> getHora();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Error3 = "La hora de entrada es incorrecta";
                $NumE += 1;
                break;
            case '3':
                $Error3 = "La hora de entrada no puede ir vacía";
                $NumE += 1;
                break;    
        }

        $ValidarHora = new Val_Hora($HoraS);
        $Retorno = $ValidarHora -> setHora($HoraS);
        $HoraSVal = $ValidarHora -> getHora();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Error4 = "La hora de salida es incorrecta";
                $NumE += 1;
                break;
            case '3':
                $Error4 = "La hora de salida no puede ir vacía";
                $NumE += 1;
                break;    
        }

        $ValidarHora = new Val_Hora($HoraSE);
        $Retorno = $ValidarHora -> setHora($HoraSE);
        $HoraSEVal = $ValidarHora -> getHora();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Error5 = "La hora de entrada del sábado es incorrecta";
                $NumE += 1;
                break;
            case '3':
                $Error5 = "La hora de entrada del sábado no puede ir vacía";
                $NumE += 1;
                break;    
        }

        $ValidarHora = new Val_Hora($HoraSS);
        $Retorno = $ValidarHora -> setHora($HoraSS);
        $HoraSSVal = $ValidarHora -> getHora();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Error6 = "La hora de salida del sábado es incorrecta";
                $NumE += 1;
                break;
            case '3':
                $Error6 = "La hora de salida del sábado no puede ir vacía";
                $NumE += 1;
                break;    
        }

        $ValidarHora = new Val_Hora($HoraDE);
        $Retorno = $ValidarHora -> setHora($HoraDE);
        $HoraDEVal = $ValidarHora -> getHora();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Error7 = "La hora de entrada del domingo es incorrecta";
                $NumE += 1;
                break;
            case '3':
                $Error7 = "La hora de entrada del domingo no puede ir vacía";
                $NumE += 1;
                break;    
        }

        $ValidarHora = new Val_Hora($HoraDS);
        $Retorno = $ValidarHora -> setHora($HoraDS);
        $HoraDSVal = $ValidarHora -> getHora();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Error8 = "La hora de salida del domingo es incorrecta";
                $NumE += 1;
                break;
            case '3':
                $Error8 = "La hora de salida del domingo no puede ir vacía";
                $NumE += 1;
                break;    
        }

        if ($Correcto==8) {
            if ($HoraE > $HoraS) {
                $Error9 = "La hora de entrada debe ser menor a la de salida";
                $NumE += 1;
            }else{
                $Correcto += 1;
            }

            if ($HoraSE > $HoraSS) {
                $Error10 = "La hora de entrada del sábado debe ser menor a la de salida";
                $NumE += 1;
            }else{
                $Correcto += 1;
            }

            if ($HoraDE > $HoraDS) {
                $Error11 = "La hora de entrada del domingo debe ser menor a la de salida";
                $NumE += 1;
            }else{
                $Correcto += 1;
            }
        }
        
        if ($Correcto==11) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $stmt = $Con->prepare("UPDATE rh_tipos_horarios SET id_sede_h=?, tipo_h=?, hora_entrada=?, hora_salida=?, hora_sabado_e=?, hora_sabado=?, hora_domingo_e=?, hora_domingo=? WHERE id_thorario=?");
                $stmt->bind_param("isssssssi", $SedeVal, $NombreVal, $HoraE, $HoraS, $HoraSE, $HoraSS, $HoraDE, $HoraDS, $idHorario);
                $stmt->execute();
                $stmt->close();
                
                $Limpiar = new Cleanner($Sede,$Nombre,$HoraE,$HoraS,$HoraSE,$HoraSS,$HoraDE,$HoraDS);
                $Sede = $Limpiar -> LimpiarSede();
                $Nombre = $Limpiar -> LimpiarNombre();
                $HoraE = $Limpiar -> LimpiarHora();
                $HoraS = $Limpiar -> LimpiarHora();
                $HoraSE = $Limpiar -> LimpiarHora();
                $HoraSS = $Limpiar -> LimpiarHora();
                $HoraDE = $Limpiar -> LimpiarHora();
                $HoraDS = $Limpiar -> LimpiarHora();

                session_start();
                $_SESSION['correcto'] = "Horario actualizado";
                header("Location: EditarH.php?id=" . $idHorario);
                exit();
            }
        }
    }

    if (empty($_GET['id'])) {
        if (!empty($_POST)) {
            $ID=$_POST['id'];
        }else{
            header('Location: CatalogoH.php');
        }
        $stmt = $Con->prepare("SELECT * FROM rh_tipos_horarios h JOIN sedes s ON h.id_sede_h = s.id_sede WHERE id_thorario=?");
        $stmt->bind_param("i",$ID);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $ID=$Reg['id_thorario'];
                    $Sede=$Reg['codigo_s'];
                    $Nombre=$Reg['tipo_h'];
                    $HoraE=$Reg['hora_entrada'];
                    $HoraS=$Reg['hora_salida'];
                    $HoraSE = $Reg['hora_sabado_e'];
                    $HoraSS=$Reg['hora_sabado'];
                    $HoraDE=$Reg['hora_domingo_e'];
                    $HoraDS=$Reg['hora_domingo'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoH.php');
            }
    }else{
        $ID=$_GET['id'];
        $stmt = $Con->prepare("SELECT * FROM rh_tipos_horarios h JOIN sedes s ON h.id_sede_h = s.id_sede WHERE id_thorario=?");
        $stmt->bind_param("i",$ID);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $ID=$Reg['id_thorario'];
                    $Sede=$Reg['codigo_s'];
                    $Nombre=$Reg['tipo_h'];
                    $HoraE=$Reg['hora_entrada'];
                    $HoraS=$Reg['hora_salida'];
                    $HoraSE = $Reg['hora_sabado_e'];
                    $HoraSS=$Reg['hora_sabado'];
                    $HoraDE=$Reg['hora_domingo_e'];
                    $HoraDS=$Reg['hora_domingo'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoH.php');
            }
    }

    include 'EditHorarios.php';
    } else { header("Location: CatalogoH.php"); }
?>