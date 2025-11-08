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
        public $Linea;
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

    if (isset($_POST['Insertar'])) {
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

        $stmt = $Con->prepare("SELECT id_mezcla_t, cajas_t FROM pallet_mezclas_temp WHERE usuario_id = ?");
        $stmt->bind_param("i", $ID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $Precaucion2 = "No has agregado ninguna mezcla";
            $NumP += 1;
        }else{
            $Correcto += 1;
        }
        
        if ($Correcto==7) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $stmt = $Con->prepare("SELECT COUNT(DISTINCT p.id_pallet) AS Suma
                                        FROM pallets p
                                        LEFT JOIN pallet_mezclas pm ON p.id_pallet = pm.id_pallet_m
                                        LEFT JOIN mezclas m ON pm.id_mezcla_m = m.id_mezcla
                                        LEFT JOIN mezcla_lotes ml ON m.id_mezcla = ml.id_mezcla_l
                                        LEFT JOIN registro_empaque re ON ml.id_lote_l = re.id_registro_r
                                        LEFT JOIN tipo_variaciones tv ON re.id_codigo_r = tv.id_variedad
                                        LEFT JOIN ciclos c ON tv.id_ciclo_v = c.id_ciclo
                                        WHERE p.id_sede_p = ?
                                        AND c.activo_c = 1");
                $stmt->bind_param("i", $SedeVal);
                $stmt->execute();
                $resultSuma = $stmt->get_result();
                $Suma = $resultSuma->fetch_assoc()['Suma'];
                $Numero = str_pad($Suma + 1, 4, "0", STR_PAD_LEFT);
                $Folio = $Sede . "-" . $Numero;

                $stmt = $Con->prepare("SELECT SUM(cajas_t) AS CajasP FROM pallet_mezclas_temp WHERE usuario_id = ?");
                $stmt->bind_param("i", $ID);
                $stmt->execute();
                $resultSuma = $stmt->get_result();
                $CajasP = $resultSuma->fetch_assoc()['CajasP'];

                $stmtInsertMezcla = $Con->prepare("INSERT INTO pallets (folio_p, id_sede_p, fecha_c, fecha_p, hora_p, id_embarque_p, fecha_e, cajas_p, id_tarima_p, id_presen_p, id_usuario_p, mapeo, ubicacion, estado_p, activo_p) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, 0, 0, 1)");
                $stmtInsertMezcla->bind_param("sisssssisii", $Folio, $SedeVal, $FechaVal, $FechaVal, $HoraP, $Embarque, $FechaEVal, $CajasP, $Tarima, $Presentaciones, $ID);
                $stmtInsertMezcla->execute();
                $idPallet= $stmtInsertMezcla->insert_id;

                // VOLVER A OBTENER LAS MEZCLAS
                $stmtMezclas = $Con->prepare("SELECT id_mezcla_t, cajas_t, id_linea_t FROM pallet_mezclas_temp WHERE usuario_id = ?");
                $stmtMezclas->bind_param("i", $ID);
                $stmtMezclas->execute();
                $resultMezclas = $stmtMezclas->get_result();

                $stmtInsert = $Con->prepare("INSERT INTO pallet_mezclas ( id_pallet_m, id_mezcla_m, cajas_m, id_linea_m, fecha_m, hora_m) VALUES (?, ?, ?, ?, ?, ?)");
                $stmtUpdate = $Con->prepare("UPDATE mezclas SET estado_m = 1 WHERE id_mezcla = ?");
                $stmtUpdate2 = $Con->prepare("UPDATE embarques_pallets SET cajas_emt = cajas_emt + ?, kilos_emt = kilos_emt + ? WHERE id_embarque = ?");

                while ($row = $resultMezclas->fetch_assoc()) {
                    $stmtInsert->bind_param("iiiiss", $idPallet, $row['id_mezcla_t'], $row['cajas_t'], $row['id_linea_t'], $FechaVal, $HoraP);
                    $stmtInsert->execute();

                    $stmtUpdate->bind_param("i", $row['id_mezcla_t']);
                    $stmtUpdate->execute();

                    $stmtUpdate2->bind_param("idi", $row['cajas_t'], $row['kilos_emt'], $Embarque);
                    $stmtUpdate2->execute();
                }
                $stmtInsert->close();
                $stmtUpdate->close();

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
                    $_SESSION['correcto'] = "Pallet registrado";
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