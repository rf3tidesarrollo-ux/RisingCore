<?php
    include_once '../../../Conexion/BD.php';
    $RutaCS = "../../../Login/Cerrar.php";
    $RutaSC = "../../../index.php";
    include_once "../../../Login/validar_sesion.php";
    // $Pagina=basename(__FILE__);
    // Historial($Pagina,$Con);
    $Ver = TienePermiso($_SESSION['ID'], "Compras/Requisiciones", 1, $Con);
    $Crear = TienePermiso($_SESSION['ID'], "Compras/Requisiciones", 2, $Con);
    $Editar = TienePermiso($_SESSION['ID'], "Compras/Requisiciones", 3, $Con);
    $Eliminar = TienePermiso($_SESSION['ID'], "Compras/Requisiciones", 4, $Con);
    
    $FechaR=date("Y-m-d");
    $HoraR=date("H:i:s");
    $diaSemana = date("N");

    $diaSemana = date("N"); // 1=Lunes ... 7=Domingo

    if ($TipoRol=="USUARIO" && $diaSemana != 3) { // Si NO es miércoles
        echo '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <link rel="shortcut icon" href="../../../Images/MiniLogo.png">
            <meta charset="UTF-8">
            <title>Acceso restringido</title>
            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        </head>
        <body>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    swal({
                        title: "Acceso restringido",
                        text: "Esta sección solo está disponible los días miércoles.",
                        icon: "warning",
                        button: "Regresar"
                    }).then(() => {
                        window.location.href = "Inicio.php"; // Cambia por la página a donde rediriges
                    });
                });
            </script>
        </body>
        </html>
        ';
        exit; // 🚫 Detiene la ejecución completamente
    }

   if ($TipoRol=="ADMINISTRADOR" || $Crear==true) {
    $NumE=0;
    $NumI=0;
    $NumP=0;
    $Finalizado="";
    $Correcto=0;
    $Sede = isset($_POST['Sede']) ? $_POST['Sede'] : '';
    $Departamento = isset($_POST['Departamento']) ? $_POST['Departamento'] : '';
    $Area = isset($_POST['Area']) ? $_POST['Area'] : '';
    $Folio = isset($_POST['Folio']) ? $_POST['Folio'] : '';
    $Producto = isset($_POST['Producto']) ? $_POST['Producto'] : '';
    $Justificacion = "";
    $Observacion = "";
    $Estado="PENDIENTE";

    for ($i=1; $i <= 4; $i++) {
        ${"Error".$i}="";
    }

    for ($i=1; $i <= 1; $i++) { 
        ${"Precaucion".$i}="";
    }

    for ($i=1; $i <= 1; $i++) { 
        ${"Informacion".$i}="";
    }

    class Cleanner{
        public $Limpiar;
        public $Sede;
        public $Departamento;
        public $Area;
        public $Folio;
        public $Producto;

        function __Construct($L){
            $this -> Limpiar = $L;
        }

        public function LimpiarSede(){
            return $this -> Sede="Seleccione la sede:";
        }

        public function LimpiarDepartamento(){
            return $this -> Departamento="Seleccione el departamento:";
        }

        public function LimpiarArea(){
            return $this -> Departamento="Seleccione el área:";
        }
        
        public function LimpiarFolio(){
            return $this -> Folio="";
        }

        public function LimpiarProducto(){
            return $this -> Producto="";
        }
    }

    if (isset($_POST['Insertar'])) {
        $Sede=$_POST['Sede'];
        $Departamento=$_POST['Departamento'];
        $Area=$_POST['Area'];
        $Folio=$_POST['Folio'];
        $Producto=$_POST['Producto'];

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

        if ($Area == "0") {
            $Error3 = "Tienes que seleccionar un área";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Producto == "") {
            $Error4 = "No has agregado ningún producto";
            $NumE += 1;
        }else{
            $Correcto += 1;
        }

        if ($Correcto==4) {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $stmt = $Con->prepare("SELECT * FROM cp_requisicion_temp WHERE id_usuario_t = ?");
                $stmt->bind_param("i", $ID);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows === 0) {
                    $Error4 = "No hay ningún producto registrado";
                    $NumE += 1;
                    exit;
                }
                
                $stmtInsertRequi = $Con->prepare("INSERT INTO cp_requisiciones (folio_req, id_sede_req, id_area_req, cant_producto, solicitante, fecha_req, hora_req, id_usuario_req, estado_req, activo_req) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
                $stmtInsertRequi->bind_param("siiisssis", $Folio, $SedeVal, $Area, $Producto, $Titular, $FechaR, $HoraR, $ID, $Estado);
                $stmtInsertRequi->execute();
                $idRequi = $stmtInsertRequi->insert_id;

                // Insertar todas las requisiciones a tabla final
                $stmtInsert = $Con->prepare("INSERT INTO cp_requisicion_pro (id_requi_p, folio_p, id_producto_p, cantidad_p, fecha_rp, fecha_p, hora_p, solicitante_p, prioridad_p, justificacion, observacion, estado_p, id_usuario_p) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                while ($row = $result->fetch_assoc()) {
                    $stmtInsert->bind_param("isiissssisssi", $idRequi, $row['folio_t'], $row['id_producto_t'], $row['cantidad_t'], $row['fecha_rt'], $row['fecha_t'], $row['hora_t'], $Titular, $row['prioridad_t'], $row['justificacion'], $row['observacion'], $row['estado_t'], $row['id_usuario_t']);
                    $stmtInsert->execute();
                    $stmtInsert->close();
                }

                $stmtDel = $Con->prepare("DELETE FROM cp_requisicion_temp WHERE id_usuario_t = ?");
                $stmtDel->bind_param("i", $ID);
                $stmtDel->execute();
                $stmtDel->close();

                $Limpiar = new Cleanner($Sede,$Departamento,$Area,$Folio,$Producto);
                $Sede = $Limpiar -> LimpiarSede();
                $Departamento = $Limpiar -> LimpiarDepartamento();
                $Area = $Limpiar -> LimpiarArea();
                $Folio = $Limpiar -> LimpiarFolio();
                $Producto = $Limpiar -> LimpiarProducto();

                session_start();
                $_SESSION['idRequi'] = $idRequi;
                $_SESSION['correcto'] = "Requisición registrada";
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            }
        }
    }

    include 'RegRequis.php';
    } else { header("Location: CatalogoRQ.php"); }

?>