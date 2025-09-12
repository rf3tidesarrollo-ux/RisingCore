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

    if (isset($_POST['Insertar'])) {
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
                
                $stmtInsertMezcla = $Con->prepare("INSERT INTO mezclas (folio_m, id_sede_m, id_cliente_m, cajas_t, kilos_t, fecha_m, hora_m) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmtInsertMezcla->bind_param("siiidss", $Folio, $Sede, $Cliente, $CajasT, $KilosT, $FechaM, $HoraM);
                $stmtInsertMezcla->execute();
                $idMezcla = $stmtInsertMezcla->insert_id;

                // Insertar todos los lotes a tabla final
                $stmtInsert = $Con->prepare("INSERT INTO mezcla_lotes (id_mezcla_l, id_lote_l, cajas_m, kilos_m, fecha_m, hora_m) VALUES (?, ?, ?, ?, ?, ?)");
                while ($row = $result->fetch_assoc()) {
                    $stmtInsert->bind_param("iiidss", $idMezcla, $row['id_lote'], $row['cajas_m'], $row['kilos_m'], $FechaM, $HoraM);
                    $stmtInsert->execute();
                }

                $stmtDel = $Con->prepare("DELETE FROM mezcla_lotes_temp WHERE usuario_id = ?");
                $stmtDel->bind_param("i", $ID);
                $stmtDel->execute();

                $pdf_nombre = generarPDFMezcla($id_mezcla, $Con);

                // 4. Guardar ruta PDF en tabla mezcla (suponiendo que tienes campo pdf_ruta)
                $stmt = $Con->prepare("UPDATE mezclas SET pdf_ruta = ? WHERE id_mezcla = ?");
                $stmt->bind_param("si", $pdf_nombre, $id_mezcla);
                $stmt->execute();
                $stmt->close();

                $Limpiar = new Cleanner($Sede,$Cliente,$Folio,$CajasT,$KilosT);
                $Sede = $Limpiar -> LimpiarSede();
                $Cliente = $Limpiar -> LimpiarCliente();
                $Folio = $Limpiar -> LimpiarFolio();
                $CajasT = $Limpiar -> LimpiarCajasT();
                $KilosT = $Limpiar -> LimpiarKilosT();

                session_start();
                $_SESSION['correcto'] = "Se hizo el registro correctamente";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
        }
    }

    include 'RegMezcla.php';
    } else { header("Location: CatalogoMz.php"); }
?>