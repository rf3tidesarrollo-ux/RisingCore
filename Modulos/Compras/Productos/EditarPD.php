<?php
    include_once '../../../Conexion/BD.php';
    $RutaCS = "../../../Login/Cerrar.php";
    $RutaSC = "../../../index.php";
    include_once "../../../Login/validar_sesion.php";
    // $Pagina=basename(__FILE__);
    // Historial($Pagina,$Con);
    $Ver = TienePermiso($_SESSION['ID'], "Compras/Productos", 1, $Con);
    $Crear = TienePermiso($_SESSION['ID'], "Compras/Productos", 2, $Con);
    $Editar = TienePermiso($_SESSION['ID'], "Compras/Productos", 3, $Con);
    $Eliminar = TienePermiso($_SESSION['ID'], "Compras/Productos", 4, $Con);

    $FechaA=date("Y-m-d");
    $HoraA=date("H:i:s");

    if ($TipoRol=="ADMINISTRADOR" || $Crear==true) {
    $NumE=0;
    $NumI=0;
    $NumP=0;
    $Finalizado="";
    $Correcto=0;
    $Nombre = isset($_POST['Nombre']) ? $_POST['Nombre'] : '';
    $Unidad = isset($_POST['Unidad']) ? $_POST['Unidad'] : '';
    $Tipo = isset($_POST['Tipo']) ? $_POST['Tipo'] : '';
    $Existencias = isset($_POST['Existencias']) ? $_POST['Existencias'] : '';

    for ($i=1; $i <= 4; $i++) {
        ${"Error".$i}="";
    }

    for ($i=1; $i <= 2; $i++) { 
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
                
                if (!preg_match('/^[A-ZÁÉÍÓÚÑ0-9.\s]*$/', $Nombre)){
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

    class Val_Existencias {
        public $Existencias;
    
        function __Construct($E){
            $this -> Existencias = $E;
        }
    
        public function getExistencias(){
            return $this -> Existencias;
        }
    
        public function setExistencias($Existencias){
            $this -> Existencias = $Existencias;
            
            if (!empty($Existencias)) {
                if (is_numeric($Existencias)){
                    if ($Existencias > 0 && $Existencias <= 9999999) {
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
        public $Nombre;
        public $Unidad;
        public $Existencias;
        public $Tipo;

        function __Construct($L){
            $this -> Limpiar = $L;
        }

        public function LimpiarNombre(){
            return $this -> Nombre="";
        }

        public function LimpiarUnidad(){
            return $this -> Unidad="Selecciona un tipo de unidad:";
        }
        
        public function LimpiarTipo(){
            return $this -> Tipo="Selecciona un tipo de producto:";
        }

        public function LimpiarExistencias(){
            return $this -> Existencias="";
        }
    }

    if (isset($_POST['Modificar'])) {
        $idProducto=$_POST['id'];
        $Nombre=$_POST['Nombre'];
        $Unidad=$_POST['Unidad'];
        $Tipo=$_POST['Tipo'];
        $Existencias=$_POST['Existencias'];

        $ValidarNombre = new Val_Nombre($Nombre);
        $Retorno = $ValidarNombre -> setNombre($Nombre);
        $NombreVal = $ValidarNombre -> getNombre();
        
        switch ($Retorno) {
            case '1':
                $Precaucion1 = "El nombre del produycto solo lleva mayúsculas, letras y números";
                $NumP += 1;
                break;
            case '2':
                $Correcto += 1;
                break;
            case '3':
                $Error1 = "El nombre del producto no puede ir vacío";
                $NumE += 1;
                break;    
        }

        if ($Unidad == "0") {
            $Error2 = "Tienes que seleccionar un tipo de unidad";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Tipo == "0") {
            $Error3 = "Tienes que seleccionar un tipo de producto";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        $ValidarExistencias = new Val_Existencias($Existencias);
        $Retorno = $ValidarExistencias -> setExistencias($Existencias);
        $ExistenciasVal = $ValidarExistencias -> getExistencias();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Precaucion2 = "Las existencias deben ser mayor a 0";
                $NumP += 1;
                break;
            case '3':
                $Precaucion4 = "Tienes que ingresar solo números en las existencias";
                $NumP += 1;
                break;
            case '4':
                $Existencias='';
                $Correcto += 1;
                break;    
        }

        if ($Correcto==4) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $stmt = $Con->prepare("UPDATE cp_productos SET producto=?, unidad_p=?, id_tipo_p=?, existencias=?, fecha_a=?, hora_a=?, user_p=? WHERE id_producto=?");
                $stmt->bind_param("siiissii", $NombreVal, $Unidad, $Tipo, $ExistenciasVal, $FechaA, $HoraA, $ID, $idProducto);
                $stmt->execute();
                $stmt->close();
                
                $Limpiar = new Cleanner($Sede,$Nombre,$Unidad,$Tipo,$Existencias);
                $Nombre = $Limpiar -> LimpiarNombre();
                $Unidad = $Limpiar -> LimpiarUnidad();
                $Tipo = $Limpiar -> LimpiarTipo();
                $Existencias = $Limpiar -> LimpiarExistencias();

                session_start();
                $_SESSION['correcto'] = "Producto actualizado";
                header("Location: EditarPD.php?id=" . $idProducto);
                exit();
            }
        }
    }

    if (empty($_GET['id'])) {
        if (!empty($_POST)) {
            $ID=$_POST['id'];
        }else{
            header('Location: CatalogoPD.php');
        }
        $stmt = $Con->prepare("SELECT * FROM cp_productos p WHERE id_producto=?");
        $stmt->bind_param("i",$ID);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $ID=$Reg['id_producto'];
                    $Nombre=$Reg['producto'];
                    $Tipo=$Reg['id_tipo_p'];
                    $Unidad=$Reg['unidad_p'];
                    $Existencias=$Reg['existencias'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoPD.php');
            }
    }else{
        $ID=$_GET['id'];
        $stmt = $Con->prepare("SELECT * FROM cp_productos p WHERE id_producto=?");
        $stmt->bind_param("i",$ID);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $ID=$Reg['id_producto'];
                    $Nombre=$Reg['producto'];
                    $Tipo=$Reg['id_tipo_p'];
                    $Unidad=$Reg['unidad_p'];
                    $Existencias=$Reg['existencias'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoPD.php');
            }
    }

    include 'EditProducto.php';
    } else { header("Location: CatalogoPD.php"); }
?>