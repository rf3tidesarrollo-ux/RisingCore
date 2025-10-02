<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
// $Pagina=basename(__FILE__);
// Historial($Pagina,$Con);
$Ver = TienePermiso($_SESSION['ID'], "Empaque/Pesaje", 1, $Con);
$Crear = TienePermiso($_SESSION['ID'], "Empaque/Pesaje", 2, $Con);
$Editar = TienePermiso($_SESSION['ID'], "Empaque/Pesaje", 3, $Con);
$Eliminar = TienePermiso($_SESSION['ID'], "Empaque/Pesaje", 4, $Con);

if ($TipoRol=="ADMINISTRADOR" || $Ver==true) {
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <link rel="shortcut icon" href="../../../Images/MiniLogo.png">
        <script src="https://code.jquery.com/jquery-3.7.1.js" type="text/javascript"></script>
        <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js" ></script>
        <link href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css" rel="stylesheet"/>
        <link href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.dataTables.css" rel="stylesheet"/>
        <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js" ></script>
        <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.dataTables.js" ></script>
        <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
        <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.colVis.min.js"></script>
        <script src="https://kit.fontawesome.com/367278d2a4.js" crossorigin="anonymous"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="../../../js/script.js"></script>
        <script src="../../../js/eliminar.js"></script>
        <link rel="stylesheet" href="DesignM.css">
        <title>Empaque: Pesaje: Inicio</title>
    </head>

    <body>
        <?php
        $modulo = 'Empaque';
        $Logo = "../../../";
        include('../../../Complementos/Header.php');
        ?>
        <main>
            <div class="tabla">
                <?php if ($TipoRol=="ADMINISTRADOR" || $Crear==true) { ?> <a title="Agregar" href="RegistrarA.php"><div id="wizard" class="btn-up"><i class="fa-solid fa-plus fa-2xl" style="color: #ffffff;"></i></div></a> <?php } ?>
            </div>
        </main>

        <?php include '../../../Complementos/Footer.php'; ?>
    </body>
    
</html>

<?php } else { header("Location: ../../../Complementos/Acceso.php"); }?>