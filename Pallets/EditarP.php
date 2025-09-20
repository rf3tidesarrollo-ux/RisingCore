<?php
    include_once '../../../Conexion/BD.php';
    $RutaCS = "../../../Login/Cerrar.php";
    $RutaSC = "../../../index.php";
    include_once "../../../Login/validar_sesion.php";
    // $Pagina=basename(__FILE__);
    // Historial($Pagina,$Con);
    $Ver = TienePermiso($_SESSION['ID'], "Empaque/Mezcla", 1, $Con);
    $Crear = TienePermiso($_SESSION['ID'], "Empaque/Mezcla", 2, $Con);
    $Editar = TienePermiso($_SESSION['ID'], "Empaque/Mezcla", 3, $Con);
    $Eliminar = TienePermiso($_SESSION['ID'], "Empaque/Mezcla", 4, $Con);

    $FechaM=date("Y-m-d");
    $HoraM=date("H:i:s");

   if ($TipoRol=="ADMINISTRADOR" || $Crear==true) {
    $NumE=0;
    $NumI=0;
    $NumP=0;
    $Finalizado="";
    $Correcto=0;
    $Sede = isset($_POST['Sede']) ? $_POST['Sede'] : '';
    $Cliente = $_POST['Clientes'] ?? $_GET['Clientes'] ?? 0;
    $Folio = isset($_POST['Folio']) ? $_POST['Folio'] : '';
    $CajasT = isset($_POST['CajasT']) ? $_POST['CajasT'] : '';
    $KilosT = isset($_POST['KilosT']) ? $_POST['KilosT'] : '';
    $Variedad = isset($_POST['Variedad']) ? $_POST['Variedad'] : '';
    $Lote = isset($_POST['Lotes']) ? $_POST['Lotes'] : '';
    $CajasA = isset($_POST['CajasA']) ? $_POST['CajasA'] : '';
    $Activo=1;

    for ($i=1; $i <= 6; $i++) {
        ${"Error".$i}="";
    }

    for ($i=1; $i <= 1; $i++) { 
        ${"Precaucion".$i}="";
    }

    class Cleanner{
        public $Limpiar;
        public $Sede;
        public $Cliente;
        public $Folio;
        public $CajasT;
        public $KilosT;

        function __Construct($L){
            $this -> Limpiar = $L;
        }

        public function LimpiarSede(){
            return $this -> Sede="Seleccione la sede:";
        }

        public function LimpiarCliente(){
            return $this -> Cliente="Seleccione el cliente:";
        }
        
        public function LimpiarFolio(){
            return $this -> Folio="";
        }

        public function LimpiarCajasT(){
            return $this -> CajasT="";
        }
        
        public function LimpiarKilosT(){
            return $this -> KilosT="";
        }
    }

    if (isset($_POST['Modificar'])) {
        $idMezcla=$_POST['id'];
        $Sede=$_POST['Sede'];
        $Cliente=$_POST['Clientes'];
        $CajasT=$_POST['CajasT'];
        $KilosT=$_POST['KilosT'];
        $Folio=$_POST['Folio'];

        if ($Sede == "0") {
            $Error1 = "Tienes que seleccionar una sede";
            $NumE += 1;
        }else{
            switch ($Sede) {
                case 'RF1':
                    $Sede=1;
                    break;
                case 'RF2':
                    $Sede=2;
                    break;
                case 'RF3':
                    $Sede=3;
                    break;
            }
            $Correcto += 1;
        }

        if ($Cliente == "0") {
            $Error2 = "Tienes que seleccionar un cliente";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($CajasT == "" || $KilosT == "") {
            $Error3 = "No has agregado ningún lote";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Correcto==3) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $stmt = $Con->prepare("SELECT id_lote, cajas_m, kilos_m FROM mezcla_lotes_temp WHERE usuario_id = ?");
                $stmt->bind_param("i", $ID);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 0) {
                    $Error4 = "No hay ningún lote registrado";
                    $NumE += 1;
                    exit;
                }

                $LotesActuales = [];
                $stmt = $Con->prepare("SELECT id_lote_l, cajas_m, kilos_m FROM mezcla_lotes WHERE id_mezcla_l = ?");
                $stmt->bind_param("i", $idMezcla);
                $stmt->execute();
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    $LotesActuales[$row['id_lote_l']] = ['cajas' => $row['cajas_m'], 'kilos' => $row['kilos_m']];
                }
                $stmt->close();

                $LotesNuevos = [];
                $stmt = $Con->prepare("SELECT id_lote, cajas_m, kilos_m FROM mezcla_lotes_temp WHERE usuario_id = ?");
                $stmt->bind_param("i", $ID);
                $stmt->execute();
                $resTemp = $stmt->get_result();
                while ($row = $resTemp->fetch_assoc()) {
                    $LotesNuevos[$row['id_lote']] = ['cajas' => $row['cajas_m'], 'kilos' => $row['kilos_m']];
                }
                $stmt->close();

                foreach ($LotesNuevos as $idLote => $nuevo) {
                    if (isset($LotesActuales[$idLote])) {
                        // Ya existía, revisamos si cambió
                        $actual = $LotesActuales[$idLote];
                        if ($actual['cajas'] != $nuevo['cajas'] || $actual['kilos'] != $nuevo['kilos']) {
                            // Actualiza si cambió
                            $stmt = $Con->prepare("UPDATE mezcla_lotes SET cajas_m=?, kilos_m=?, fecha_m=?, hora_m=? 
                                                WHERE id_mezcla_l=? AND id_lote_l=?");
                            $stmt->bind_param("ddssii", $nuevo['cajas'], $nuevo['kilos'], $FechaM, $HoraM, $idMezcla, $idLote);
                            $stmt->execute();
                            $stmt->close();
                        }
                    } else {
                        // No existía, insertamos
                        $stmt = $Con->prepare("INSERT INTO mezcla_lotes (id_mezcla_l, id_lote_l, cajas_m, kilos_m, fecha_m, hora_m) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("iiddss", $idMezcla, $idLote, $nuevo['cajas'], $nuevo['kilos'], $FechaM, $HoraM);
                        $stmt->execute();
                        $stmt->close();

                        $stmtUpdate = $Con->prepare("UPDATE registro_empaque SET kilos_dis = kilos_dis - ?, cajas_dis = cajas_dis - ? WHERE id_registro_r = ?");
                        $stmtUpdate->bind_param("dii", $nuevo['kilos'], $nuevo['cajas'], $idLote);
                        $stmtUpdate->execute();
                        $stmtUpdate->close();
                    }
                }

                foreach ($LotesActuales as $idLote => $actual) {
                    if (!isset($LotesNuevos[$idLote])) {
                        // Obtener kilos y cajas antes de borrar
                        $stmtGet = $Con->prepare("SELECT kilos_m, cajas_m FROM mezcla_lotes WHERE id_mezcla_l = ? AND id_lote_l = ?");
                        $stmtGet->bind_param("ii", $idMezcla, $idLote);
                        $stmtGet->execute();
                        $resultGet = $stmtGet->get_result();
                        $row = $resultGet->fetch_assoc();
                        $kilos_m = $row['kilos_m'];
                        $cajas_m = $row['cajas_m'];
                        $stmtGet->close();

                        // Sumar esos kilos y cajas disponibles en registro_empaque
                        $stmtUpdate = $Con->prepare("UPDATE registro_empaque SET kilos_dis = kilos_dis + ?, cajas_dis = cajas_dis + ? WHERE id_registro_r = ?");
                        $stmtUpdate->bind_param("dii", $kilos_m, $cajas_m, $idLote);
                        $stmtUpdate->execute();
                        $stmtUpdate->close();

                        // Restar esos kilos y cajas en la mezcla
                        $stmtUpdate = $Con->prepare("UPDATE mezclas SET kilos_t = kilos_t - ?, cajas_t = cajas_t - ? WHERE id_mezcla = ?");
                        $stmtUpdate->bind_param("dii", $kilos_m, $cajas_m, $idMezcla);
                        $stmtUpdate->execute();
                        $stmtUpdate->close();

                        // Ahora eliminar de mezcla_lotes
                        $stmtDel = $Con->prepare("DELETE FROM mezcla_lotes WHERE id_mezcla_l = ? AND id_lote_l = ?");
                        $stmtDel->bind_param("ii", $idMezcla, $idLote);
                        $stmtDel->execute();
                        $stmtDel->close();
                    }
                }

                $stmt = $Con->prepare("SELECT folio_m, id_cliente_m, id_sede_m FROM mezclas WHERE id_mezcla=?");
                $stmt->bind_param("i", $idMezcla);
                $stmt->execute();
                $resultGet = $stmt->get_result();
                $row = $resultGet->fetch_assoc();
                $FolioA = $row['folio_m'];
                $ClienteA = $row['id_cliente_m'];
                $SedeA = $row['id_sede_m'];
                $stmt->close();   

                if ($Sede == $SedeA && $Cliente == $ClienteA) {
                    $FolioVal = $FolioA;
                }else{
                    $FolioVal = $Folio;
                }

                $stmtInsertMezcla = $Con->prepare("UPDATE mezclas SET folio_m=?, id_sede_m=?, id_cliente_m=?, cajas_t=?, kilos_t=?, fecha_m=?, hora_m=?, id_usuario_m=? WHERE id_mezcla=?");
                $stmtInsertMezcla->bind_param("siiidssii", $FolioVal, $Sede, $Cliente, $CajasT, $KilosT, $FechaM, $HoraM, $ID, $idMezcla);
                $stmtInsertMezcla->execute();

                $stmtDel = $Con->prepare("DELETE FROM mezcla_lotes_temp WHERE usuario_id = ?");
                $stmtDel->bind_param("i", $ID);
                if ($stmtDel->execute()) {
                    $_SESSION['idMezcla'] = $idMezcla;
                } 
                $stmtDel->close();
                
                $Limpiar = new Cleanner($Sede,$Cliente,$Folio,$CajasT,$KilosT);
                $Sede = $Limpiar -> LimpiarSede();
                $Cliente = $Limpiar -> LimpiarCliente();
                $Folio = $Limpiar -> LimpiarFolio();
                $CajasT = $Limpiar -> LimpiarCajasT();
                $KilosT = $Limpiar -> LimpiarKilosT();

                session_start();
                $_SESSION['correcto'] = "El registro se actualizo correctamente";
                header("Location: EditarMz.php?id=" . $idMezcla);
                exit();
            }
        }
    }

    if (empty($_GET['id'])) {
        if (!empty($_POST)) {
            $IDM=$_POST['id'];
        }else{
            header('Location: CatalogoMz.php');
        }
        $stmt = $Con->prepare("SELECT * FROM mezclas m
                            JOIN sedes s ON m.id_sede_m = s.id_sede
                            JOIN clientes c ON m.id_cliente_m = c.id_cliente
                            WHERE id_mezcla=?");
        $stmt->bind_param("i",$IDM);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $IDM = $Reg['id_mezcla'];
                    $Sede=$Reg['codigo_s'];
                    $Cliente=$Reg['id_cliente_m'];
                    $Folio=$Reg['folio_m'];
                    $CajasT=$Reg['cajas_t'];
                    $KilosT=$Reg['kilos_t'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoMz.php');
            }
    }else{
        $IDM=$_GET['id'];
        $stmt = $Con->prepare("SELECT * FROM mezclas m
                            JOIN sedes s ON m.id_sede_m = s.id_sede
                            JOIN clientes c ON m.id_cliente_m = c.id_cliente
                            WHERE id_mezcla=?");
        $stmt->bind_param("i",$IDM);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $IDM = $Reg['id_mezcla'];
                    $Sede=$Reg['codigo_s'];
                    $Cliente=$Reg['id_cliente_m'];
                    $Folio=$Reg['folio_m'];
                    $CajasT=$Reg['cajas_t'];
                    $KilosT=$Reg['kilos_t'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoMz.php');
            }
    }

    include 'EditMezcla.php';
    } else { header("Location: CatalogoMz.php"); }
?>