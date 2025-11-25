<?php
    include_once '../../../Conexion/BD.php';
    $RutaCS = "../../../Login/Cerrar.php";
    $RutaSC = "../../../index.php";
    include_once "../../../Login/validar_sesion.php";
    // $Pagina=basename(__FILE__);
    // Historial($Pagina,$Con);
    $Ver = TienePermiso($_SESSION['ID'], "RRHH/Personal", 1, $Con);
    $Crear = TienePermiso($_SESSION['ID'], "RRHH/Personal", 2, $Con);
    $Editar = TienePermiso($_SESSION['ID'], "RRHH/Personal", 3, $Con);
    $Eliminar = TienePermiso($_SESSION['ID'], "RRHH/Personal", 4, $Con);

   if ($TipoRol=="ADMINISTRADOR" || $Crear==true) {
    $NumE=0;
    $NumI=0;
    $NumP=0;
    $Finalizado="";
    $Correcto=0;
    $Sede = isset($_POST['Sede']) ? $_POST['Sede'] : '';
    $Departamento = isset($_POST['Departamento']) ? $_POST['Departamento'] : '';
    $Nombre = isset($_POST['Nombre']) ? $_POST['Nombre'] : '';
    $FechaN = isset($_POST['FechaN']) ? $_POST['FechaN'] : '';
    $Lugar = isset($_POST['Lugar']) ? $_POST['Lugar'] : '';
     $Clave = isset($_POST['Clave']) ? $_POST['Clave'] : '';
    $NSS = isset($_POST['NSS']) ? $_POST['NSS'] : '';
    $RFC = isset($_POST['RFC']) ? $_POST['RFC'] : '';
    $CURP = isset($_POST['CURP']) ? $_POST['CURP'] : '';
    $INE = isset($_POST['INE']) ? $_POST['INE'] : '';
    $CP = isset($_POST['CP']) ? $_POST['CP'] : '';
    $Rol = isset($_POST['Rol']) ? $_POST['Rol'] : '';
    $Puesto = isset($_POST['Puesto']) ? $_POST['Puesto'] : '';
    $Salario = isset($_POST['Salario']) ? $_POST['Salario'] : '';
    $Escolaridad = isset($_POST['Escolaridad']) ? $_POST['Escolaridad'] : '';
    $EstadoC = isset($_POST['EstadoC']) ? $_POST['EstadoC'] : '';
    $Municipio = isset($_POST['Municipio']) ? $_POST['Municipio'] : '';
    $Domicilio = isset($_POST['Domicilio']) ? $_POST['Domicilio'] : '';
    $Camino = isset($_POST['Ruta']) ? $_POST['Ruta'] : '';
    $Beneficiario = isset($_POST['Beneficiario']) ? $_POST['Beneficiario'] : '';
    $Parentesco = isset($_POST['Parentesco']) ? $_POST['Parentesco'] : '';
    $FechaTC = isset($_POST['FechaTC']) ? $_POST['FechaTC'] : '';

    for ($i=1; $i <= 22; $i++) {
        ${"Error".$i}="";
    }

    for ($i=1; $i <= 12; $i++) { 
        ${"Precaucion".$i}="";
    }

    for ($i=1; $i <= 1; $i++) { 
        ${"Informacion".$i}="";
    }

    class Val_Lugar {
        public $Lugar;
    
        function __Construct($L){
            $this -> Lugar = $L;
        }
    
        public function getLugar(){
            return $this -> Lugar;
        }
    
        public function setLugar($Lugar){
            $this -> Lugar = $Lugar;
            
            if (!empty($Lugar)) {
                $Lugar=filter_var($Lugar, FILTER_SANITIZE_SPECIAL_CHARS);
                
                if (!preg_match('/^[A-ZÁÉÍÓÚÑ.,\s]*$/', $Lugar)){
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

    class Val_NSS {
        public $NSS;
    
        function __Construct($N){
            $this -> NSS = $N;
        }
    
        public function getNSS(){
            return $this -> NSS;
        }
    
        public function setNSS($NSS){
            $this -> NSS = $NSS;
            
            if (!empty($NSS)) {
                if (is_numeric($NSS)){
                    if (strlen($NSS)==11) {
                        if ($NSS >= 00000000001 && $NSS <= 99999999999) {
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
            }else{
                    $Valor = 5;
                    return $Valor;
            }
        }
    }

    class Val_Codigo {
        public $Codigo;
    
        function __Construct($C){
            $this -> Codigo = $C;
        }
    
        public function getCodigo(){
            return $this -> Codigo;
        }
    
        public function setCodigo($Codigo){
            $this -> Codigo = $Codigo;
            
            if (!empty($Codigo)) {
                $Codigo=filter_var($Codigo, FILTER_SANITIZE_SPECIAL_CHARS);
                
                if (!preg_match('/^[A-Z0-9.\s]*$/', $Codigo)){
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

    class Val_Domicilio {
        public $Domicilio;
    
        function __Construct($D){
            $this -> Domicilio = $D;
        }
    
        public function getDomicilio(){
            return $this -> Domicilio;
        }
    
        public function setDomicilio($Domicilio){
            $this -> Domicilio = $Domicilio;
            
            if (!empty($Domicilio)) {
                $Domicilio=filter_var($Domicilio, FILTER_SANITIZE_SPECIAL_CHARS);
                
                if (!preg_match('/^[A-ZÁÉÍÓÚÜÑ0-9.,#\s]*$/', $Domicilio)){
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

    class Val_CP {
        public $CP;
    
        function __Construct($CP){
            $this -> CP = $CP;
        }
    
        public function getCP(){
            return $this -> CP;
        }
    
        public function setCP($CP){
            $this -> CP = $CP;
            
            if (!empty($CP)) {
                if (is_numeric($CP)){
                    if (strlen($CP)==5) {
                        if ($CP >= 10000 && $CP <= 99999) {
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
            }else{
                    $Valor = 5;
                    return $Valor;
            }
        }
    }

    class Val_Clave {
        public $Clave;
    
        function __Construct($C){
            $this -> Clave = $C;
        }
    
        public function getClave(){
            return $this -> Clave;
        }
    
        public function setClave($Clave){
            $this -> Clave = $Clave;
            
            if (!empty($Clave)) {
                if (is_numeric($Clave)){
                    if (strlen($Clave) >= 4 && strlen($Clave) <= 5) {
                        if ($Clave >= 0001 && $Clave <= 99999) {
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
            }else{
                    $Valor = 5;
                    return $Valor;
            }
        }
    }

    class Val_Salario {
        public $Salario;
    
        function __Construct($S){
            $this -> Salario = $S;
        }
    
        public function getSalario(){
            return $this -> Salario;
        }
    
        public function setSalario($Salario){
            $this -> Salario = $Salario;
            
            if (!empty($Salario)) {
                if (is_numeric($Salario)){
                    if ($Salario > 0 && $Salario <= 9999999999) {
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

                if(count($Valores) == 3){
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
        public $Sede;
        public $Departamento;
        public $Nombre;
        public $FechaN;
        public $Lugar;
        public $Clave;
        public $NSS;
        public $RFC;
        public $CURP;
        public $INE;
        public $CP;
        public $Rol;
        public $Puesto;
        public $Salario;
        public $Escolaridad;
        public $EstadoC;
        public $Municipio;
        public $Domicilio;
        public $Camino;
        public $Beneficiario;
        public $Parentesco;
        public $FechaTC;

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
            return $this -> Nombre="";
        }

        public function LimpiarFechaN(){
            return $this -> FechaN="";
        }

        public function LimpiarLugar(){
            return $this -> Lugar="";
        }

        public function LimpiarClave(){
            return $this -> Clave="";
        }

        public function LimpiarNSS(){
            return $this -> NSS="";
        }

        public function LimpiarRFC(){
            return $this -> RFC="";
        }

        public function LimpiarCURP(){
            return $this -> CURP="";
        }

        public function LimpiarINE(){
            return $this -> INE="";
        }

        public function LimpiarCP(){
            return $this -> CP="";
        }

        public function LimpiarRol(){
            return $this -> Genero="Seleccione el rol:";
        }

        public function LimpiarPuesto(){
            return $this -> Puesto="";
        }

        public function LimpiarSalario(){
            return $this -> Salario="";
        }

        public function LimpiarEscolaridad(){
            return $this -> Departamento="Seleccione la escolaridad:";
        }

        public function LimpiarEstadoC(){
            return $this -> Tipo="Seleccione el estado civil:";
        }

        public function LimpiarMunicipio(){
            return $this -> TipoP="Seleccione el municipio:";
        }

        public function LimpiarDomicilio(){
            return $this -> Domicilio="";
        }

        public function LimpiarCamino(){
            return $this -> Camino="";
        }

        public function LimpiarBeneficiario(){
            return $this -> Beneficiario="";
        }

        public function LimpiarParentesco(){
            return $this -> Parentesco="";
        }

        public function LimpiarFechaTC(){
            return $this -> FechaTC="";
        }
    }

    if (isset($_POST['Modificar'])) {
        $idPersonal=$_POST['id'];
        $Sede=$_POST['Sede'];
        $Departamento=$_POST['Departamento'];
        $Nombre=$_POST['Nombre'];
        $FechaN=$_POST['FechaN'];
        $Lugar=$_POST['Lugar'];
        $Clave=$_POST['Clave'];
        $NSS=$_POST['NSS'];
        $RFC=$_POST['RFC'];
        $CURP=$_POST['CURP'];
        $INE=$_POST['INE'];
        $CP=$_POST['CP'];
        $Rol=$_POST['Rol'];
        $Puesto=$_POST['Puesto'];
        $Salario=$_POST['Salario'];
        $Escolaridad=$_POST['Escolaridad'];
        $EstadoC=$_POST['EstadoC'];
        $Municipio=$_POST['Municipio'];
        $Domicilio=$_POST['Domicilio'];
        $Camino=$_POST['Ruta'];
        $Beneficiario=$_POST['Beneficiario'];
        $Parentesco=$_POST['Parentesco'];
        $FechaTC=$_POST['FechaTC'];
        
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

        if ($Departamento == "0") {
            $Error2 = "Tienes que seleccionar un departamento";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Nombre == "0") {
            $Error3 = "Tienes que seleccionar a alguien del personal";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }
        
        $ValidarFecha = new Val_Fecha($FechaN);
        $Retorno = $ValidarFecha -> setFecha($FechaN);
        $FechaNVal = $ValidarFecha -> getFecha();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Error4 = "La fecha de nacimiento ingresada es incorrecta";
                $NumE += 1;
                break;
            case '3':
                $Error4 = "La fecha de nacimiento no puede ir vacío";
                $NumE += 1;
                break;    
        }

        $ValidarLugar = new Val_Lugar($Lugar);
        $Retorno = $ValidarLugar -> setLugar($Lugar);
        $LugarVal = $ValidarLugar -> getLugar();
        
        switch ($Retorno) {
            case '1':
                $Precaucion1 = "El lugar solo lleva mayúsculas y letras";
                $NumP += 1;
                break;
            case '2':
                $Correcto += 1;
                break;
            case '3':
                $Error5 = "El lugar no puede ir vacío";
                $NumE += 1;
                break;    
        }

        $ValidarClave = new Val_Clave($Clave);
        $Retorno = $ValidarClave -> setClave($Clave);
        $ClaveVal = $ValidarClave -> getClave();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Precaucion12 = "La clave debe estar entre 00001 y 99999";
                $NumP += 1;
                break;
            case '3':
                $Precaucion12 = "La clave debe ser de 4-5 dígitos";
                $NumP += 1;
                break;
            case '4':
                $Precaucion12 = "Tienes que ingresar solo números en la clave";
                $NumP += 1;
                break;
            case '5':
                $Error22 = "La clave no puede ir vacío";
                $NumE += 1;
                break;   
        }

        $ValidarNSS = new Val_NSS($NSS);
        $Retorno = $ValidarNSS -> setNSS($NSS);
        $NSSVal = $ValidarNSS -> getNSS();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Precaucion2 = "El NSS debe estar entre 00000000001 y 99999999999";
                $NumP += 1;
                break;
            case '3':
                $Precaucion2 = "El NSS debe ser de 11 dígitos";
                $NumP += 1;
                break;
            case '4':
                $Precaucion2 = "Tienes que ingresar solo números en el NSS";
                $NumP += 1;
                break;
            case '5':
                $Error6 = "El NSS no puede ir vacío";
                $NumE += 1;
                break;   
        }

        $ValidarCodigo = new Val_Codigo($RFC);
        $Retorno = $ValidarCodigo -> setCodigo($RFC);
        $RFCVal = $ValidarCodigo -> getCodigo();
        
        switch ($Retorno) {
            case '1':
                $Precaucion3 = "El RFC solo lleva mayúsculas, números y letras";
                $NumP += 1;
                break;
            case '2':
                if (strlen($RFCVal) == 13) {
                    $Correcto += 1;
                    break;
                }else{
                    $Precaucion3 = "El RFC tiene que tener 13 caracteres";
                    $NumE += 1;
                    break; 
                }
            case '3':
                $Error7 = "El RFC no puede ir vacío";
                $NumE += 1;
                break;    
        }

        $ValidarCodigo = new Val_Codigo($CURP);
        $Retorno = $ValidarCodigo -> setCodigo($CURP);
        $CURPVal = $ValidarCodigo -> getCodigo();
        
        switch ($Retorno) {
            case '1':
                $Precaucion4 = "La curp solo lleva mayúsculas, números y letras";
                $NumP += 1;
                break;
            case '2':
                if (strlen($CURPVal) == 18) {
                    $Correcto += 1;
                    break;
                }else{
                    $Precaucion4 = "La curp tiene que tener 18 caracteres";
                    $NumE += 1;
                    break; 
                }
            case '3':
                $Error8 = "La curp no puede ir vacío";
                $NumE += 1;
                break;    
        }

        $ValidarCodigo = new Val_Codigo($INE);
        $Retorno = $ValidarCodigo -> setCodigo($INE);
        $INEVal = $ValidarCodigo -> getCodigo();
        
        switch ($Retorno) {
            case '1':
                $Precaucion5 = "La INE solo lleva mayúsculas, números y letras";
                $NumP += 1;
                break;
            case '2':
                if (strlen($INEVal) == 18) {
                    $Correcto += 1;
                    break;
                }else{
                    $Precaucion5 = "La INE tiene que tener 18 caracteres";
                    $NumE += 1;
                    break; 
                }
            case '3':
                $INE = "";
                $Correcto += 1;
                break;     
        }

        $ValidarCP = new Val_CP($CP);
        $Retorno = $ValidarCP -> setCP($CP);
        $CPVal = $ValidarCP -> getCP();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Precaucion6 = "El código postal debe estar entre 10000 y 99999";
                $NumP += 1;
                break;
            case '3':
                $Precaucion6 = "El código postal debe ser de 5 dígitos";
                $NumP += 1;
                break;
            case '4':
                $Precaucion6 = "Tienes que ingresar solo números en el código postal";
                $NumP += 1;
                break;
            case '5':
                $Error10 = "El código postal no puede ir vacío";
                $NumE += 1;
                break;   
        }

        if ($Rol == "0") {
            $Error11 = "Tienes que seleccionar un rol";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        $ValidarLugar = new Val_Lugar($Puesto);
        $Retorno = $ValidarLugar -> setLugar($Puesto);
        $PuestoVal = $ValidarLugar -> getLugar();
        
        switch ($Retorno) {
            case '1':
                $Precaucion7 = "El puesto solo lleva mayúsculas y letras";
                $NumP += 1;
                break;
            case '2':
                $Correcto += 1;
                break;
            case '3':
                $Error12 = "El puesto no puede ir vacío";
                $NumE += 1;
                break;    
        }

        $ValidarSalario = new Val_Salario($Salario);
        $Retorno = $ValidarSalario -> setSalario($Salario);
        $SalarioVal = $ValidarSalario -> getSalario();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Precaucion8 = "El salario debe ser mayor a 0";
                $NumP += 1;
                break;
            case '3':
                $Precaucion8 = "Tienes que ingresar solo números en el salario";
                $NumP += 1;
                break;
            case '4':
                $Error13 = "El salario no puede ir vacío";
                $NumE += 1;
                break;    
        }

        if ($Escolaridad == "0") {
            $Error14 = "Tienes que seleccionar una escolaridad";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($EstadoC == "0") {
            $Error15 = "Tienes que seleccionar un estado civil";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Municipio == "0") {
            $Error16 = "Tienes que seleccionar un municipio";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        $ValidarDomicilio = new Val_Domicilio($Domicilio);
        $Retorno = $ValidarDomicilio -> setDomicilio($Domicilio);
        $DomicilioVal = $ValidarDomicilio -> getDomicilio();
        
        switch ($Retorno) {
            case '1':
                $Precaucion9 = "El domicilio solo lleva mayúsculas y letras";
                $NumP += 1;
                break;
            case '2':
                $Correcto += 1;
                break;
            case '3':
                $Error17 = "El domicilio no puede ir vacío";
                $NumE += 1;
                break;    
        }

        if ($Camino == "0") {
            $Error18 = "Tienes que seleccionar una ruta";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        $ValidarLugar = new Val_Lugar($Beneficiario);
        $Retorno = $ValidarLugar -> setLugar($Beneficiario);
        $BeneficiarioVal = $ValidarLugar -> getLugar();
        
        switch ($Retorno) {
            case '1':
                $Precaucion10 = "El beneficiario solo lleva mayúsculas y letras";
                $NumP += 1;
                break;
            case '2':
                $Correcto += 1;
                break;
            case '3':
                $Error19 = "El beneficiario no puede ir vacío";
                $NumE += 1;
                break;    
        }

        $ValidarLugar = new Val_Lugar($Parentesco);
        $Retorno = $ValidarLugar -> setLugar($Parentesco);
        $ParentescoVal = $ValidarLugar -> getLugar();
        
        switch ($Retorno) {
            case '1':
                $Precaucion11 = "El parentesco solo lleva mayúsculas y letras";
                $NumP += 1;
                break;
            case '2':
                $Correcto += 1;
                break;
            case '3':
                $Error20 = "El parentesco no puede ir vacío";
                $NumE += 1;
                break;    
        }
        
        $ValidarFecha = new Val_Fecha($FechaTC);
        $Retorno = $ValidarFecha -> setFecha($FechaTC);
        $FechaTCVal = $ValidarFecha -> getFecha();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Error21 = "El termino de contrato ingresado es incorrecto";
                $NumE += 1;
                break;
            case '3':
                $Error21 = "El termino de contrato no puede ir vacío";
                $NumE += 1;
                break;    
        }

        if ($Correcto==22) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $ClaveV = str_pad($ClaveVal, 4, "0", STR_PAD_LEFT);

                $stmt = $Con->prepare("UPDATE rh_personal_completo SET id_registro_p=?, clave_nom=?, fecha_nacimiento=?, lugar_nacimiento=?, nss=?, rfc=?, curp=?, ine=?, codigo_postal=?, rol_p=?, puesto_p=?, salario_diario=?, id_escolaridad_p=?, id_estado_p=?, id_municipio_p=?, domicilio=?, id_ruta_p=?, beneficiario=?, parentesco=?, terminacion_contrato=? WHERE id_completo_p=?");
                $stmt->bind_param('issssssssssdiiisisssi', $Nombre, $ClaveV, $FechaNVal, $LugarVal, $NSSVal, $RFCVal, $CURPVal, $INEVal, $CPVal, $Rol, $PuestoVal, $SalarioVal, $Escolaridad, $EstadoC, $Municipio, $DomicilioVal, $Camino, $BeneficiarioVal, $ParentescoVal, $FechaTCVal, $idPersonal);
                $stmt->execute();
                $stmt->close();
                
                $Limpiar = new Cleanner($Sede,$Nombre,$AP,$AM,$Genero,$Departamento,$Tipo,$TipoP,$Fecha);
                $Sede = $Limpiar -> LimpiarSede();
                $Departamento = $Limpiar -> LimpiarDepartamento();
                $Nombre = $Limpiar -> LimpiarNombre();
                $FechaN = $Limpiar -> LimpiarFechaN();
                $Lugar = $Limpiar -> LimpiarLugar();
                $Clave = $Limpiar -> LimpiarClave();
                $NSS = $Limpiar -> LimpiarNSS();
                $RFC = $Limpiar -> LimpiarRFC();
                $CURP = $Limpiar -> LimpiarCURP();
                $INE = $Limpiar -> LimpiarINE();
                $CP = $Limpiar -> LimpiarCP();
                $Rol = $Limpiar -> LimpiarRol();
                $Puesto = $Limpiar -> LimpiarPuesto();
                $Salario = $Limpiar -> LimpiarSalario();
                $Escolaridad = $Limpiar -> LimpiarEscolaridad();
                $EstadoC = $Limpiar -> LimpiarEstadoC();
                $Municipio = $Limpiar -> LimpiarMunicipio();
                $Domicilio = $Limpiar -> LimpiarDomicilio();
                $Camino = $Limpiar -> LimpiarCamino();
                $Beneficiario = $Limpiar -> LimpiarBeneficiario();
                $Parentesco = $Limpiar -> LimpiarParentesco();
                $FechaTC = $Limpiar -> LimpiarFechaTC();

                session_start();
                $_SESSION['correcto'] = "Datos del personal actualizado";
                header("Location: EditarPR.php?id=" . $idPersonal);
                exit();
            }
        }
    }

    if (empty($_GET['id'])) {
        if (!empty($_POST)) {
            $ID=$_POST['id'];
        }else{
            header('Location: CatalogoPR.php');
        }
        $stmt = $Con->prepare("SELECT * FROM rh_personal_completo pc JOIN rh_personal p ON pc.id_registro_p = p.id_personal JOIN sedes s ON p.id_sede_pl = s.id_sede WHERE id_completo_p=?");
        $stmt->bind_param("i",$ID);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $ID=$Reg['id_completo_p'];
                    $Sede=$Reg['codigo_s'];
                    $Departamento=$Reg['id_depto_pl'];
                    $Nombre=$Reg['id_registro_p'];
                    $FechaN=$Reg['fecha_nacimiento'];
                    $Lugar=$Reg['lugar_nacimiento'];
                    $Clave=$Reg['clave_nom'];
                    $NSS = $Reg['nss'];
                    $RFC=$Reg['rfc'];
                    $CURP=$Reg['curp'];
                    $INE=$Reg['ine'];
                    $CP=$Reg['codigo_postal'];
                    $Rol=$Reg['rol_p'];
                    $Puesto=$Reg['puesto_p'];
                    $Salario=$Reg['salario_diario'];
                    $Escolaridad = $Reg['id_escolaridad_p'];
                    $EstadoC=$Reg['id_estado_p'];
                    $Municipio=$Reg['id_municipio_p'];
                    $Domicilio=$Reg['domicilio'];
                    $Camino=$Reg['id_ruta_p'];
                    $Beneficiario=$Reg['beneficiario'];
                    $Parentesco=$Reg['parentesco'];
                    $FechaTC=$Reg['terminacion_contrato'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoPR.php');
            }
    }else{
        $ID=$_GET['id'];
        $stmt = $Con->prepare("SELECT * FROM rh_personal_completo pc JOIN rh_personal p ON pc.id_registro_p = p.id_personal JOIN sedes s ON p.id_sede_pl = s.id_sede WHERE id_completo_p=?");
        $stmt->bind_param("i",$ID);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $ID=$Reg['id_completo_p'];
                    $Sede=$Reg['codigo_s'];
                    $Departamento=$Reg['id_depto_pl'];
                    $Nombre=$Reg['id_registro_p'];
                    $FechaN=$Reg['fecha_nacimiento'];
                    $Lugar=$Reg['lugar_nacimiento'];
                    $Clave=$Reg['clave_nom'];
                    $NSS = $Reg['nss'];
                    $RFC=$Reg['rfc'];
                    $CURP=$Reg['curp'];
                    $INE=$Reg['ine'];
                    $CP=$Reg['codigo_postal'];
                    $Rol=$Reg['rol_p'];
                    $Puesto=$Reg['puesto_p'];
                    $Salario=$Reg['salario_diario'];
                    $Escolaridad = $Reg['id_escolaridad_p'];
                    $EstadoC=$Reg['id_estado_p'];
                    $Municipio=$Reg['id_municipio_p'];
                    $Domicilio=$Reg['domicilio'];
                    $Camino=$Reg['id_ruta_p'];
                    $Beneficiario=$Reg['beneficiario'];
                    $Parentesco=$Reg['parentesco'];
                    $FechaTC=$Reg['terminacion_contrato'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoPR.php');
            }
    }

    include 'EditPersonal.php';
    } else { header("Location: CatalogoPR.php"); }
?>