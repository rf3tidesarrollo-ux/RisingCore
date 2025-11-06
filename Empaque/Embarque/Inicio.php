<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
// $Pagina=basename(__FILE__);
// Historial($Pagina,$Con);
$Ver = TienePermiso($_SESSION['ID'], "Empaque/Embarque", 1, $Con);
$Crear = TienePermiso($_SESSION['ID'], "Empaque/Embarque", 2, $Con);
$Editar = TienePermiso($_SESSION['ID'], "Empaque/Embarque", 3, $Con);
$Eliminar = TienePermiso($_SESSION['ID'], "Empaque/Embarque", 4, $Con);

if ($TipoRol=="ADMINISTRADOR" || $Ver==true) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="shortcut icon" href="../../../Images/MiniLogo.png">
    <script src="https://kit.fontawesome.com/367278d2a4.js" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="../../../js/script.js"></script>
    <script src="../../../js/session.js"></script>
    <link rel="stylesheet" href="DesignE.css">
    <title>Embarque: Inicio</title>
</head>

    <body>
            <?php
            $basePath = "../";
            $Logo = "../../../";
            $modulo = 'Empaque';
            include('../../../Complementos/Header.php');
            ?>
        <main>
            <div style="display: flex; justify-content: center; align-items: center; height: 60vh; text-align: center;">
                <div style="background: linear-gradient(135deg, #07bb67, #07bb67); 
                            color: white; 
                            padding: 40px 60px; 
                            border-radius: 20px; 
                            box-shadow: 0 8px 20px rgba(0,0,0,0.2); 
                            font-family: 'Segoe UI', sans-serif;">
                    <h1 style="font-size: 2.5rem; margin-bottom: 15px;">¡BIENVENID@ <span style="color: #000000ff;"><?=$Titular?></span>!</h1>
                    <h2 style="font-size: 1.5rem; font-weight: 400;">A <b>EMBARQUE</b></h2>
                    <p style="margin-top: 20px; font-size: 1rem; opacity: 0.9;">
                    Nos alegra tenerte de vuelta.
                    </p>
                </div>
            </div>
        </main>

        <?php include '../../../Complementos/Footer.php'; ?>
                
    </body>
</html>

<?php } else { header("Location: ../../../Complementos/Acceso.php"); }?>