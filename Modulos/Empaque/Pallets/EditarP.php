<?php
    include_once '../../../Conexion/BD.php';
    $RutaCS = "../../../Login/Cerrar.php";
    $RutaSC = "../../../index.php";
    include_once "../../../Login/validar_sesion.php";
    // $Pagina=basename(__FILE__);
    // Historial($Pagina,$Con);
    $Ver = TienePermiso($_SESSION['ID'], "Empaque/Pallets", 1, $Con);
    $Crear = TienePermiso($_SESSION['ID'], "Empaque/Pallets", 2, $Con);
    $Editar = TienePermiso($_SESSION['ID'], "Empaque/Pallets", 3, $Con);
    $Eliminar = TienePermiso($_SESSION['ID'], "Empaque/Pallets", 4, $Con);

    $FechaP=date("Y-m-d");
    $HoraP=date("H:i:s");

   if ($TipoRol=="ADMINISTRADOR" || $Crear==true) {
    $NumE=0;
    $NumI=0;
    $NumP=0;
    $Finalizado="";
    $Correcto=0;
    $Sede = isset($_POST['Sede']) ? $_POST['Sede'] : '';
    $Presentaciones = isset($_POST['Presentaciones']) ? $_POST['Presentaciones'] : '';
    $Linea = $_POST['Lineas'] ?? $_GET['Lineas'] ?? 0;
    $Embarque = isset($_POST['Embarques']) ? $_POST['Embarques'] : '';
    $Tarima = isset($_POST['Tarimas']) ? $_POST['Tarimas'] : '';
    $Fecha = isset($_POST['Fecha']) ? $_POST['Fecha'] : '';
    $FechaE = isset($_POST['FechaE']) ? $_POST['FechaE'] : '';
    $Mezclas = isset($_POST['Mezclas']) ?? $_POST['Mezclas'] ?? 0;
    $CajasT = isset($_POST['CajasT']) ? $_POST['CajasT'] : '';
    $Folio="";
    $CajasP=0;

    for ($i=1; $i <= 6; $i++) {
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

    class Val_FechaE {
        public $FechaE;
    
        function __Construct($E){
            $this -> FechaE = $E;
        }
    
        public function getFechaE(){
            return $this -> FechaE;
        }
        
        function setFechaE($FechaE,$Fecha){
            if (!empty($FechaE)) {
                $Valores = explode('-', $FechaE);
                if ($FechaE >= $Fecha) {
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
        public $Embarque;
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

        public function LimpiarEmbarque(){
            return $this -> Embarque="Seleccione el embarque:";
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

    if (isset($_POST['Modificar'])) {
        $idPallet=$_POST['id'];
        $Sede=$_POST['Sede'];
        $Presentaciones=$_POST['Presentaciones'];
        $Embarque=$_POST['Embarques'];
        $Tarima=$_POST['Tarimas'];
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

        if ($Embarque == "0") {
            $Error3 = "Tienes que seleccionar un embarque";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }
        
        if ($Tarima == "0") {
            $Error4 = "Tienes que seleccionar una tarima";
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
                $Error5 = "La fecha ingresada es incorrecta";
                $NumE += 1;
                break;
            case '3':
                $Error5 = "El campo de fecha de empaque no puede ir vacío";
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
                $Error6 = "La fecha ingresada es incorrecta";
                $NumE += 1;
                break;
            case '3':
                $Precaucion1 = "La fecha de envió no puede ser menor a la de empaque";
                $NumP += 1;
                break;
            case '4':
                $Error6 = "El campo de fecha de envió no puede ir vacío";
                $NumE += 1;
                break;    
        }

        if ($Correcto==6) {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $stmt = $Con->prepare("SELECT id_mezcla_t, cajas_t FROM pallet_mezclas_temp WHERE usuario_id = ?");
                $stmt->bind_param("i", $ID);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 0) {
                    $Precaucion2 = "No has agregado ninguna mezcla";
                    $NumP += 1;
                    exit;
                }

                $MezclasActuales = [];
                $stmt = $Con->prepare("SELECT id_mezcla_m, cajas_m, id_linea_m FROM pallet_mezclas WHERE id_pallet_m = ?");
                $stmt->bind_param("i", $idPallet);
                $stmt->execute();
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    $MezclasActuales[$row['id_mezcla_m']] = ['cajas' => $row['cajas_m'], 'linea' => $row['id_linea_m']];
                }
                $stmt->close();

                $MezclasNuevas = [];
                $stmt = $Con->prepare("SELECT id_mezcla_t, cajas_t, id_linea_t FROM pallet_mezclas_temp WHERE usuario_id = ?");
                $stmt->bind_param("i", $ID);
                $stmt->execute();
                $resTemp = $stmt->get_result();
                while ($row = $resTemp->fetch_assoc()) {
                    $MezclasNuevas[$row['id_mezcla_t']] = ['cajas' => $row['cajas_t'], 'linea' => $row['id_linea_t']];
                }
                $stmt->close();

                foreach ($MezclasNuevas as $idMezcla => $nuevo) {
                    if (isset($MezclasActuales[$idMezcla])) {
                        // Ya existía, revisamos si cambió
                        $actual = $MezclasActuales[$idMezcla];
                        if ($actual['cajas'] != $nuevo['cajas'] && $actual['linea'] != $nuevo['linea']) {
                            // Actualiza si cambió
                            $stmt = $Con->prepare("UPDATE pallet_mezclas SET cajas_m=?, id_linea_m=?, fecha_m=?, hora_m=? 
                                                WHERE id_pallet_m=? AND id_mezcla_m=?");
                            $stmt->bind_param("ddssii", $nuevo['cajas'], $nuevo['linea'], $FechaP, $HoraP, $idPallet, $idMezcla);
                            $stmt->execute();
                            $stmt->close();
                        }
                    } else {
                        // No existía, insertamos
                        $stmt = $Con->prepare("INSERT INTO pallet_mezclas (id_pallet_m, id_mezcla_m, cajas_m, id_linea_m, fecha_m, hora_m) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("iiiiss", $idPallet, $idMezcla, $nuevo['cajas'], $nuevo['linea'], $FechaP, $HoraP);
                        $stmt->execute();
                        $stmt->close();

                        $stmtUpdate = $Con->prepare("UPDATE mezclas SET estado_m = 1 WHERE id_mezcla = ?");
                        $stmtUpdate->bind_param("i", $idMezcla);
                        $stmtUpdate->execute();
                        $stmtUpdate->close();
                    }
                }

                foreach ($MezclasActuales as $idMezcla => $actual) {
                    if (!isset($MezclasNuevas[$idMezcla])) {
                        // Obtener cajas antes de borrar
                        $stmtUpdate = $Con->prepare("UPDATE mezclas SET estado_m = 0 WHERE id_mezcla = ?");
                        $stmtUpdate->bind_param("i", $idMezcla);
                        $stmtUpdate->execute();
                        $stmtUpdate->close();

                        // Ahora eliminar de pallet_mezclas
                        $stmtDel = $Con->prepare("DELETE FROM pallet_mezclas WHERE id_pallet_m = ? AND id_mezcla_m = ?");
                        $stmtDel->bind_param("ii", $idPallet, $idMezcla);
                        $stmtDel->execute();
                        $stmtDel->close();
                    }
                }
                
                $stmt = $Con->prepare("SELECT SUM(cajas_m) AS CajasP FROM pallet_mezclas WHERE id_pallet_m = ?");
                $stmt->bind_param("i", $idPallet);
                $stmt->execute();
                $resultSuma = $stmt->get_result();
                $CajasP = $resultSuma->fetch_assoc()['CajasP'];

                $stmtInsertMezcla = $Con->prepare("UPDATE pallets SET id_sede_p=?, id_presen_p=?, id_tarima_p=?, id_embarque_p=?, cajas_p=?, fecha_p=?, fecha_e=?, hora_p=?, id_usuario_p=? WHERE id_pallet=?");
                $stmtInsertMezcla->bind_param("iiisisssii", $SedeVal, $Presentaciones, $Tarima, $Embarque, $CajasP, $FechaVal, $FechaEVal, $HoraP, $ID, $idPallet);
                $stmtInsertMezcla->execute();
                $stmtInsertMezcla->close();
                
                $Limpiar = new Cleanner($Sede,$Presentaciones,$Embarque,$Tarima,$Fecha,$FechaE);
                $Sede = $Limpiar -> LimpiarSede();
                $Presentaciones = $Limpiar -> LimpiarPresentaciones();
                $Embarque = $Limpiar -> LimpiarEmbarque();
                $Tarima = $Limpiar -> LimpiarTarima();
                $Fecha = $Limpiar -> LimpiarFecha();
                $FechaE = $Limpiar -> LimpiarFechaE();

                $stmtDel = $Con->prepare("DELETE FROM pallet_mezclas_temp WHERE usuario_id = ?");
                $stmtDel->bind_param("i", $ID);
                if ($stmtDel->execute()) {
                    $_SESSION['idPallet'] = $idPallet;
                    $_SESSION['correcto'] = "Pallet actualizado";
                    header("Location: EditarP.php?id=" . $idPallet);
                    exit();
                } 
                $stmtDel->close();
            }
        }
    }

    if (empty($_GET['id'])) {
        if (!empty($_POST)) {
            $IDP=$_POST['id'];
        }else{
            header('Location: CatalogoP.php');
        }
        $stmt = $Con->prepare("SELECT * FROM pallets p
                            JOIN sedes s ON p.id_sede_p = s.id_sede
                            WHERE id_pallet=?");
        $stmt->bind_param("i",$IDP);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $IDP = $Reg['id_pallet'];
                    $Folio=$Reg['folio_p'];
                    $Sede=$Reg['codigo_s'];
                    $Presentaciones=$Reg['id_presen_p'];
                    $Embarque=$Reg['id_embarque_p'];
                    $Tarima=$Reg['id_tarima_p'];
                    $Fecha=$Reg['fecha_p'];
                    $FechaE=$Reg['fecha_e'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoP.php');
            }
    }else{
        $IDP=$_GET['id'];
        $stmt = $Con->prepare("SELECT * FROM pallets p
                            JOIN sedes s ON p.id_sede_p = s.id_sede
                            WHERE id_pallet=?");
        $stmt->bind_param("i",$IDP);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $IDP = $Reg['id_pallet'];
                    $Folio=$Reg['folio_p'];
                    $Sede=$Reg['codigo_s'];
                    $Presentaciones=$Reg['id_presen_p'];
                    $Embarque=$Reg['id_embarque_p'];
                    $Tarima=$Reg['id_tarima_p'];
                    $Fecha=$Reg['fecha_p'];
                    $FechaE=$Reg['fecha_e'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoP.php');
            }
    }

    include 'EditPallet.php';
    } else { header("Location: CatalogoP.php"); }
?>