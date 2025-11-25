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

    if (isset($_POST['Modificar']) && $TipoRol=="USUARIO" && $diaSemana != 3) { // Si NO es miércoles
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

   if ($TipoRol=="ADMINISTRADOR" || $Editar==true) {
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

    if (isset($_POST['Modificar'])) {
        $idRequi=$_POST['id'];
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

                $LotesActuales = [];
                $stmt = $Con->prepare("SELECT * FROM cp_requisicion_pro WHERE id_requisicion_p = ?");
                $stmt->bind_param("i", $idRequi);
                $stmt->execute();
                $res = $stmt->get_result();
                while ($row = $res->fetch_assoc()) {
                    $LotesActuales[$row['id_producto_p']] = ['folio' => $row['folio_p'], 'producto' => $row['id_producto_p'], 'total' => $row['cantidad_p'], 'fecha_rp' => $row['fecha_rp'], 'fecha' => $row['fecha_p'], 'hora' => $row['hora_p'], 'solicitante' => $row['solicitante_p'], 'prioridad' => $row['prioridad_p'], 'justificacion' => $row['justificacion'], 'observacion' => $row['observacion'], 'estado_p' => $row['estado_p'], 'usuario' => $row['id_usuario_p']];
                }
                $stmt->close();

                $LotesNuevos = [];
                $stmt = $Con->prepare("SELECT * FROM cp_requisicion_temp WHERE id_usuario_t = ?");
                $stmt->bind_param("i", $ID);
                $stmt->execute();
                $resTemp = $stmt->get_result();
                while ($row = $resTemp->fetch_assoc()) {
                    $LotesNuevos[$row['id_producto_t']] = ['folio' => $row['folio_t'], 'producto' => $row['id_producto_t'], 'total' => $row['cantidad_t'], 'fecha_rt' => $row['fecha_rt'], 'fecha' => $row['fecha_t'], 'hora' => $row['hora_t'], 'prioridad' => $row['prioridad_t'], 'justificacion' => $row['justificacion'], 'observacion' => $row['observacion'], 'estado_t' => $row['estado_t'], 'usuario' => $row['id_usuario_t']];
                }
                $stmt->close();

                foreach ($LotesNuevos as $idTemp => $nuevo) {
                        $idProducto = $nuevo['producto'];
                    if (isset($LotesActuales[$idProducto])) {
                        // Ya existía, revisamos si cambió
                        $actual = $LotesActuales[$idTemp];
                        if ($actual['producto'] != $nuevo['producto'] || $actual['total'] != $nuevo['total'] || $actual['prioridad'] != $nuevo['prioridad'] || $actual['justificacion'] != $nuevo['justificacion']) {
                            // Actualiza si cambió
                            $stmt = $Con->prepare("UPDATE cp_requisicion_pro SET cantidad_p=?, fecha_rp=?, fecha_p=?, hora_p=?, prioridad=?, justificacion=?, estado_p=?, usuario=? WHERE id_requisicion_p=? AND id_producto_p=?");
                            $stmt->bind_param("isssissiii", $nuevo['total'], $nuevo['fecha_rt'], $FechaR, $HoraR, $nuevo['prioridad'], $nuevo['justificacion'], $Estado, $ID, $idRequi, $idProducto);
                            $stmt->execute();
                            $stmt->close();
                        }
                    } else {
                        // No existía, insertamos
                        $stmt = $Con->prepare("INSERT INTO cp_requisicion_pro (id_requi_p, folio_p, id_producto_p, cantidad_p, fecha_rp, fecha_p, hora_p, solicitante_p, prioridad_p, justificacion, observacion, estado_p, id_usuario_p) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");                   
                        $stmt->bind_param("isiissssisssi", $idRequi, $nuevo['folio'], $nuevo['producto'], $nuevo['total'], $nuevo['fecha_rt'], $nuevo['fecha'], $nuevo['hora'], $Titular, $nuevo['prioridad'], $nuevo['justificacion'], $nuevo['observacion'], $nuevo['estado_t'], $nuevo['usuario']);
                        $stmt->execute();
                        $stmt->close();

                        $stmtUpdate = $Con->prepare("UPDATE cp_requisiciones SET cant_producto = cant_producto + 1 WHERE id_requisicion = ?");
                        $stmtUpdate->bind_param("i", $idRequi);
                        $stmtUpdate->execute();
                        $stmtUpdate->close();
                    }
                }

                foreach ($LotesActuales as $idTemp => $actual) {
                    $idProducto = $actual['producto'];
                    if (!isset($LotesNuevos[$idProducto])) {
                        // Ahora eliminar de requis
                        $stmtDel = $Con->prepare("DELETE FROM cp_requisicion_pro WHERE id_requi_p = ? AND id_producto_p = ?");
                        $stmtDel->bind_param("ii", $idRequi, $idProducto);
                        $stmtDel->execute();
                        $stmtDel->close();

                        $stmtUpdate = $Con->prepare("UPDATE cp_requisiciones SET cant_producto = cant_producto - 1 WHERE id_requisicion = ?");
                        $stmtUpdate->bind_param("i", $idRequi);
                        $stmtUpdate->execute();
                        $stmtUpdate->close();
                    }
                }

                $stmt = $Con->prepare("SELECT folio_req, id_area_req, id_sede_req FROM cp_requisiciones WHERE id_requisicion=?");
                $stmt->bind_param("i", $idRequi);
                $stmt->execute();
                $resultGet = $stmt->get_result();
                $row = $resultGet->fetch_assoc();
                $FolioA = $row['folio_req'];
                $AreaA = $row['id_area_req'];
                $SedeA = $row['id_sede_req'];
                $stmt->close();   

                if ($Sede == $SedeA && $Cliente == $AreaA) {
                    $FolioVal = $FolioA;
                }else{
                    $FolioVal = $Folio;
                }

                $stmtInsertRequi = $Con->prepare("UPDATE cp_requisiciones SET folio_req=?, id_sede_req=?, id_area_req=?, cant_producto=?, solicitante=?, fecha_req=?, hora_req=?, id_usuario_req=?, estado_req=? WHERE id_requisicion=?");
                $stmtInsertRequi->bind_param("siiisssisi", $Folio, $SedeVal, $Area, $Producto, $Titular, $FechaR, $HoraR, $ID, $Estado, $idRequi);
                $stmtInsertRequi->execute();

                $stmtDel = $Con->prepare("DELETE FROM cp_requisicion_temp WHERE id_usuario_t = ?");
                $stmtDel->bind_param("i", $ID);
                if ($stmtDel->execute()) {
                    $_SESSION['idRequi'] = $idRequi;
                } 
                $stmtDel->close();
                
                $Limpiar = new Cleanner($Sede,$Departamento,$Area,$Folio,$Producto);
                $Sede = $Limpiar -> LimpiarSede();
                $Departamento = $Limpiar -> LimpiarDepartamento();
                $Area = $Limpiar -> LimpiarArea();
                $Folio = $Limpiar -> LimpiarFolio();
                $Producto = $Limpiar -> LimpiarProducto();

                session_start();
                $_SESSION['correcto'] = "Requisición actualizada";
                header("Location: EditarRQ.php?id=" . $idRequi);
                exit();
            }
        }
    }

    if (empty($_GET['id'])) {
        if (!empty($_POST)) {
            $IDR=$_POST['id'];
        }else{
            header('Location: CatalogoRQ.php');
        }
        $stmt = $Con->prepare("SELECT r.*, s.codigo_s, p.id_dep FROM cp_requisiciones r
                            JOIN sedes s ON r.id_sede_req = s.id_sede
                            JOIN rh_departamentos d ON  r.id_area_req = d.id_departamento
                            JOIN cp_departamentos p ON d.id_dep_d = p.id_dep
                            WHERE id_requisicion=?");
        $stmt->bind_param("i",$IDR);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $IDR = $Reg['id_requisicion'];
                    $Sede=$Reg['codigo_s'];
                    $Departamento=$Reg['id_dep'];
                    $Area=$Reg['id_area_req'];
                    $Folio=$Reg['folio_req'];
                    $Producto=$Reg['cant_producto'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoRQ.php');
            }
    }else{
        $IDR=$_GET['id'];
        $stmt = $Con->prepare("SELECT r.*, s.codigo_s, p.id_dep FROM cp_requisiciones r
                            JOIN sedes s ON r.id_sede_req = s.id_sede
                            JOIN rh_departamentos d ON  r.id_area_req = d.id_departamento
                            JOIN cp_departamentos p ON d.id_dep_d = p.id_dep
                            WHERE id_requisicion=?");
        $stmt->bind_param("i",$IDR);
        $stmt->execute();
        $Registro = $stmt->get_result();
        $NumCol=$Registro->num_rows;

        if ($NumCol>0) {
            while ($Reg = $Registro->fetch_assoc()){
                    $IDR = $Reg['id_requisicion'];
                    $Sede=$Reg['codigo_s'];
                    $Departamento=$Reg['id_dep'];
                    $Area=$Reg['id_area_req'];
                    $Folio=$Reg['folio_req'];
                    $Producto=$Reg['cant_producto'];
                }
                $stmt->close();
            }else{
                header('Location: CatalogoRQ.php');
            }
    }

    include 'EditRequis.php';
    } else { header("Location: CatalogoRQ.php"); }
?>