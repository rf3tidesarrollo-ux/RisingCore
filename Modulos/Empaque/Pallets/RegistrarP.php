<?php
    include_once '../../../Conexion/BD.php';
    $RutaCS = "../../../Login/Cerrar.php";
    $RutaSC = "../../../index.php";
    include_once "../../../Login/validar_sesion.php";
    // $Pagina=basename(__FILE__);
    // Historial($Pagina,$Con);
    $Ver = TienePermiso($_SESSION['ID'], "Empaque/Pallet", 1, $Con);
    $Crear = TienePermiso($_SESSION['ID'], "Empaque/Pallet", 2, $Con);
    $Editar = TienePermiso($_SESSION['ID'], "Empaque/Pallet", 3, $Con);
    $Eliminar = TienePermiso($_SESSION['ID'], "Empaque/Pallet", 4, $Con);

    $HoraP=date("H:i:s");

   if ($TipoRol=="ADMINISTRADOR" || $Crear==true) {
    $NumE=0;
    $NumI=0;
    $NumP=0;
    $Finalizado="";
    $Correcto=0;
    $Sede = isset($_POST['Sede']) ? $_POST['Sede'] : '';
    $Presentaciones = isset($_POST['Presentaciones']) ? $_POST['Presentaciones'] : '';
    $Linea = $_POST['Linea'] ?? $_GET['Linea'] ?? 0;
    $Tipo = isset($_POST['Tipo']) ? $_POST['Tipo'] : '';
    $Tarima = isset($_POST['Tarima']) ? $_POST['Tarima'] : '';
    $Fecha = isset($_POST['Fecha']) ? $_POST['Fecha'] : '';
    $FechaE = isset($_POST['FechaE']) ? $_POST['FechaE'] : '';
    $Mezclas = isset($_POST['Mezclas']) ?? $_POST['Mezclas'] ?? 0;
    $CajasT = isset($_POST['CajasT']) ? $_POST['CajasT'] : '';
    $Folio="";
    $CajasP=0;

    for ($i=1; $i <= 10; $i++) {
        ${"Error".$i}="";
    }

    for ($i=1; $i <= 2; $i++) { 
        ${"Precaucion".$i}="";
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

    class Val_Fecha {
        public $FechaE;
    
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

    class Val_FechaE {
        public $FechaE;
    
        function __Construct($FE){
            $this -> FechaE = $FE;
        }
    
        public function getFechaE(){
            return $this -> FechaE;
        }
        
        function setFechaF($FechaE,$Fecha){
            if (!empty($FechaE)) {
                $Valores = explode('-', $FechaE);
                if ($FechaE < $Fecha) {
                    if(count($Valores) == 3){
                        $Valor = 1;
                        return $Valor;
                    }else{
                        $Valor = 2;
                        return $Valor;
                    }
                }else{
                    return $Valor = 3;
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
        public $Presentaciones;
        public $Linea;
        public $Tipo;
        public $Tarima;
        public $Fecha;
        public $FechaE;

        function __Construct($L){
            $this -> Limpiar = $L;
        }

        public function LimpiarSede(){
            return $this -> Sede="Seleccione la sede:";
        }

        public function LimpiarPresentaciones(){
            return $this -> Presentaciones="Seleccione la presentación:";
        }
        
        public function LimpiarLinea(){
            return $this -> Linea="Seleccione la línea:";
        }

        public function LimpiarTipo(){
            return $this -> Tipo="Seleccione el tipo:";
        }

        public function LimpiarTarima(){
            return $this -> Tarima="Seleccione la tarima:";
        }
        
        public function LimpiarFecha(){
            return $this -> Fecha="";
        }

        public function LimpiarFechaE(){
            return $this -> FechaE="";
        }
    }

    if (isset($_POST['Insertar'])) {
        $Sede=$_POST['Sede'];
        $Presentaciones=$_POST['Presentaciones'];
        $Linea=$_POST['Linea'];
        $Tipo=$_POST['Tipo'];
        $Tarima=$_POST['Tarima'];
        $Fecha=$_POST['Fecha'];
        $FechaE=$_POST['FechaE'];

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

        if ($Presentaciones == "0") {
            $Error2 = "Tienes que seleccionar una presentación";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Linea == "0") {
            $Error3 = "Tienes que seleccionar una línea";
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
        
        if ($Tarima == "0") {
            $Error5 = "Tienes que seleccionar una tarima";
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
                $Error6 = "La fecha ingresada es incorrecta";
                $NumE += 1;
                break;
            case '3':
                $Error6 = "El campo de fecha de empaque no puede ir vacío";
                $NumE += 1;
                break;    
        }

        $ValidarFechaE = new Val_FechaE($FechaE);
        $Retorno = $ValidarFechaE -> setFechaE($FechaE,$Fecha);
        $FechaEVal = $ValidarFechaE -> getFechaE();

        switch ($Retorno) {
            case '1':
                $Correcto += 1;
                break;
            case '2':
                $Error7 = "La fecha ingresada es incorrecta";
                $NumE += 1;
                break;
            case '3':
                $Error7 = "La fecha de envió no puede ser menor a la de empaque";
                $NumE += 1;
                break;
            case '4':
                $Precaucion1 = "El campo de fecha de envió no puede ir vacío";
                $NumP += 1;
                break;    
        }

        $stmt = $Con->prepare("SELECT id_pallet_temp FROM pallet_mezclas_temp WHERE usuario_id = ?");
        $stmt->bind_param("i", $ID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $Precaucion2 = "No has agregado ninguna mezcla";
            $NumP += 1;
        }else{
            $Correcto += 1;
        }

        if ($Correcto==8) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $Folio = $Sede . $idPallet;
                
                $stmt = $Con->prepare("SELECT SUM(cajas_t) AS CajasP FROM pallet_mezclas_temp WHERE usuario_id = ?");
                $stmt->bind_param("i", $ID);
                $stmt->execute();
                $resultSuma = $stmt->get_result();
                $CajasP = $resultSuma->fetch_assoc()['CajasP'];

                $stmtInsertMezcla = $Con->prepare("INSERT INTO pallets (folio_p, id_sede_p, fecha_c, fecha_p, hora_p, id_linea_p, tipo_t, fecha_e, cajas_p, id_tarima_p, id_presen_p, id_usuario_p, estado_p, activo_p) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 1)");
                $stmtInsertMezcla->bind_param("sisssiisissi", $Folio, $SedeVal, $FechaVal, $FechaVal, $HoraP, $Linea, $Tipo, $FechaEVal, $CajasP, $Tarima, $Presentaciones, $ID);
                $stmtInsertMezcla->execute();
                $idPallet= $stmtInsertMezcla->insert_id;

                // Insertar todos los lotes a tabla final
                $stmtInsert = $Con->prepare("INSERT INTO pallet_mezclas (id_pallets, id_pallet_m, id_mezcla_m, cajas_m, fecha_m, hora_m) VALUES (?, ?, ?, ?, ?, ?)");
                while ($row = $result->fetch_assoc()) {
                    $stmtInsert->bind_param("iiiss", $idPallet, $row['id_mezcla_m'], $row['cajas_m'], $FechaVal, $HoraP);
                    $stmtInsert->execute();

                    $stmtUpdate = $Con->prepare("UPDATE mezclas SET estado = 1 WHERE id_mezclas = ?");
                    $stmtUpdate->bind_param("i", $row['id_mezcla_m']);
                    $stmtUpdate->execute();
                    $stmtUpdate->close();
                }

                $Limpiar = new Cleanner($Sede,$Presentaciones,$Linea,$Tipo,$Tarima,$Fecha,$FechaE);
                $Sede = $Limpiar -> LimpiarSede();
                $Presentaciones = $Limpiar -> LimpiarPresentaciones();
                $Linea = $Limpiar -> LimpiarLinea();
                $Tipo = $Limpiar -> LimpiarTipo();
                $Tarima = $Limpiar -> LimpiarTarima();
                $Fecha = $Limpiar -> LimpiarFecha();
                $FechaE = $Limpiar -> LimpiarFechaE();

                $stmtDel = $Con->prepare("DELETE FROM pallet_mezclas_temp WHERE usuario_id = ?");
                $stmtDel->bind_param("i", $ID);
                if ($stmtDel->execute()) {
                    $_SESSION['idPallet'] = $idPallet;
                    $_SESSION['correcto'] = "Se hizo el registro correctamente";
                    header("Location: ".$_SERVER['PHP_SELF']);
                    exit();
                } 
                $stmtDel->close();
            }
        }
    }

    include 'RegPallet.php';
    } else { header("Location: CatalogoP.php"); }
?>