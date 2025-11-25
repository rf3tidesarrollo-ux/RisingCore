<?php
    include_once '../../../Conexion/BD.php';
    $RutaCS = "../../../Login/Cerrar.php";
    $RutaSC = "../../../index.php";
    include_once "../../../Login/validar_sesion.php";
    // $Pagina=basename(__FILE__);
    // Historial($Pagina,$Con);
    $Ver = TienePermiso($_SESSION['ID'], "RRHH/Incentivos", 1, $Con);
    $Crear = TienePermiso($_SESSION['ID'], "RRHH/Incentivos", 2, $Con);
    $Editar = TienePermiso($_SESSION['ID'], "RRHH/Incentivos", 3, $Con);
    $Eliminar = TienePermiso($_SESSION['ID'], "RRHH/Incentivos", 4, $Con);

    $FechaR=date("Y-m-d");

   if ($TipoRol=="ADMINISTRADOR" || $Editar==true) {
    $NumE=0;
    $NumI=0;
    $NumP=0;
    $Finalizado="";
    $Correcto=0;
    $Sede = isset($_POST['Sede']) ? $_POST['Sede'] : '';
    $Departamento = isset($_POST['Departamento']) ? $_POST['Departamento'] : '';
    $Nombre = isset($_POST['Nombre']) ? $_POST['Nombre'] : '';
    $Incentivo = isset($_POST['Incentivo']) ? $_POST['Incentivo'] : '';
    $Fecha = isset($_POST['Fecha']) ? $_POST['Fecha'] : '';
    $Cantidad = isset($_POST['Cantidad']) ? $_POST['Cantidad'] : '';
    $Motivo = isset($_POST['Motivo']) ? $_POST['Motivo'] : '';
    $Numero = isset($_POST['Numero']) ? $_POST['Numero'] : '';

    for ($i=1; $i <= 7; $i++) {
        ${"Error".$i}="";
    }

    for ($i=1; $i <= 3; $i++) { 
        ${"Precaucion".$i}="";
    }

    for ($i=1; $i <= 1; $i++) { 
        ${"Informacion".$i}="";
    }

    class Val_Motivo {
        public $Motivo;
    
        function __Construct($M){
            $this -> Motivo = $M;
        }
    
        public function getMotivo(){
            return $this -> Motivo;
        }
    
        public function setMotivo($Motivo){
            $this -> Motivo = $Motivo;
            
            if (!empty($Motivo)) {
                $Motivo=filter_var($Motivo, FILTER_SANITIZE_SPECIAL_CHARS);
                
                if (!preg_match('/^[A-ZÁÉÍÓÚÑ0-9.,\s]*$/', $Motivo)){
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

    class Val_Numero {
        public $Numero;
    
        function __Construct($N){
            $this -> Numero = $N;
        }
    
        public function getNumero(){
            return $this -> Numero;
        }
    
        public function setNumero($Numero){
            $this -> Numero = $Numero;
            
            if (!empty($Numero)) {
                if (is_numeric($Numero)){
                    if ($Numero > 0 && $Numero <= 9999999) {
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
        public $Sede;
        public $Nombre;
        public $Incentivo;
        public $Fecha;
        public $Cantidad;
        public $Motivo;
        public $Numero;

        function __Construct($L){
            $this -> Limpiar = $L;
        }

        public function LimpiarTexto(){
            return $this -> Texto="";
        }

        public function LimpiarNumero(){
            return $this -> Numero="";
        }

        public function LimpiarSede(){
            return $this -> Sede="Seleccione la sede:";
        }

        public function LimpiarNombre(){
            return $this -> Nombre="Seleccione el nombre";
        }

        public function LimpiarIncentivo(){
            return $this -> Incentivo="Seleccione el tipo de incentivo:";
        }
    }

    if (isset($_POST['Modificar'])) {
        $idIncentivo=$_POST['id'];
        $Sede=$_POST['Sede'];
        $Nombre=$_POST['Nombre'];
        $Incentivo=$_POST['Incentivo'];
        $Fecha=$_POST['Fecha'];
        $Cantidad=$_POST['Cantidad'];
        $Motivo=$_POST['Motivo'];
        $Numero=$_POST['Numero'];
        
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
            $stmt = $Con->prepare("SELECT id_personal AS id FROM rh_personal WHERE badge = ?");
            $stmt->bind_param('s', $Nombre);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            $idPersonal = $row['id'] ?? 0;
            $stmt->close();

            if ($idPersonal == 0) {
                $Error2 = "Ocurrio un error con el badge del empleado";
                $NumE += 1;
            }else{
                $Correcto += 1;
            }
        }
       
        if ($Incentivo == "0") {
            $Error3 = "Tienes que seleccionar un tipo de permiso";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        $ValidarFecha = new Val_Fecha($Fecha);
        $Retorno = $ValidarFecha -> setFecha($Fecha);
        $FechaVal = $ValidarFecha -> getFecha();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Error4 = "La fecha ingresada es incorrecta";
                $NumE += 1;
                break;
            case '3':
                $Error4 = "La fecha no puede ir vacía";
                $NumE += 1;
                break;    
        }

        $ValidarNumero = new Val_Numero($Cantidad);
        $Retorno = $ValidarNumero -> setNumero($Cantidad);
        $CantidadVal = $ValidarNumero -> getNumero();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Precaucion1 = "La cantidad debe ser mayor a 0";
                $NumP += 1;
                break;
            case '3':
                $Precaucion1 = "Tienes que ingresar solo números en la cantidad";
                $NumP += 1;
                break;
            case '4':
                $Error5 = "La cantidad no puede ir vacía";
                $NumE += 1;
                break;    
        }

        $ValidarMotivo = new Val_Motivo($Motivo);
        $Retorno = $ValidarMotivo -> setMotivo($Motivo);
        $MotivoVal = $ValidarMotivo -> getMotivo();
        
        switch ($Retorno) {
            case '1':
                $Precaucion2 = "El motivo solo lleva mayúsculas y letras";
                $NumP += 1;
                break;
            case '2':
                $Correcto += 1;
                break;
            case '3':
                $Error6 = "El motivo no puede ir vacío";
                $NumE += 1;
                break;    
        }

        $ValidarNumero = new Val_Numero($Numero);
        $Retorno = $ValidarNumero -> setNumero($Numero);
        $NumeroVal = $ValidarNumero -> getNumero();

        switch ($Retorno) {
            case '1':
                if ($NumeroVal > 5) {
                    $Informacion1 = "No puede registrar mas de 5 incentivos al mismo tiempo";
                }else{
                    $Correcto += 1;
                }
                break;
            case '2':
                $Precaucion3 = "El número de incentivos debe ser mayor a 0";
                $NumP += 1;
                break;
            case '3':
                $Precaucion3 = "Tienes que ingresar solo números en el incentivo";
                $NumP += 1;
                break;
            case '4':
                $Error7 = "El número de incentivos no puede ir vacío";
                $NumE += 1;
                break;    
        }

        if ($Correcto==7) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $stmt = $Con->prepare("UPDATE rh_incentivos SET id_sede_i=?, id_personal_i=?, id_incentivo_i=?, fecha_i=?, fecha_inc=?, hora_inc=?, cantidad=?, motivo=?, id_user_i=?, estado_i=1 WHERE id_incentivo=?");
                $stmt->bind_param('iiisssisii', $SedeVal, $idPersonal, $Incentivo, $FechaVal, $FechaR, $HoraR, $CantidadVal, $Motivo, $ID, $idIncentivo);
                $stmt->execute();
                $stmt->close();
                
                $Limpiar = new Cleanner($Sede,$Nombre,$Incentivo,$Fecha,$Cantidad,$Motivo,$Numero);
                $Sede = $Limpiar -> LimpiarSede();
                $Nombre = $Limpiar -> LimpiarNombre();
                $Incentivo = $Limpiar -> LimpiarIncentivo();
                $Fecha = $Limpiar -> LimpiarTexto();
                $Cantidad = $Limpiar -> LimpiarNumero();
                $Motivo = $Limpiar -> LimpiarTexto();
                $Numero = $Limpiar -> LimpiarNumero();

                session_start();
                $_SESSION['correcto'] = "Incentivo actualizado";
                header("Location: EditarIC.php?id=" . $idIncentivo);
                exit();
            }
        }
    }

    if (empty($_GET['id'])) {
        if (!empty($_POST)) {
            $ID=$_POST['id'];
        }else{
            header('Location: CatalogoIC.php');
        }
        $stmt = $Con->prepare("SELECT * FROM rh_incentivos i JOIN rh_personal p ON i.id_personal_i = p.id_personal JOIN sedes s ON i.id_sede_i = s.id_sede WHERE id_incentivo=?");
        $stmt->bind_param("i",$ID);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $ID=$Reg['id_incentivo'];
                    $Sede=$Reg['codigo_s'];
                    $Departamento=$Reg['id_depto_pl'];
                    $Nombre=$Reg['badge'];
                    $Incentivo=$Reg['id_incentivo_i'];
                    $Fecha=$Reg['fecha_i'];
                    $Cantidad=$Reg['cantidad'];
                    $Motivo=$Reg['motivo'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoIC.php');
            }
    }else{
        $ID=$_GET['id'];
        $stmt = $Con->prepare("SELECT * FROM rh_incentivos i JOIN rh_personal p ON i.id_personal_i = p.id_personal JOIN sedes s ON i.id_sede_i = s.id_sede WHERE id_incentivo=?");
        $stmt->bind_param("i",$ID);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $ID=$Reg['id_incentivo'];
                    $Sede=$Reg['codigo_s'];
                    $Departamento=$Reg['id_depto_pl'];
                    $Nombre=$Reg['badge'];
                    $Incentivo=$Reg['id_incentivo_i'];
                    $Fecha=$Reg['fecha_i'];
                    $Cantidad=$Reg['cantidad'];
                    $Motivo=$Reg['motivo'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoIC.php');
            }
    }

    include 'EditIncentivos.php';
    } else { header("Location: CatalogoIC.php"); }
?>