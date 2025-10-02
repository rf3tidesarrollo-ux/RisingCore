<?php
require_once '../../../Librerias/dompdf/autoload.inc.php';
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";

use Dompdf\Dompdf;

// Iniciar DOMPDF
$dompdf = new Dompdf();

// Capturar el HTML de la plantilla
ob_start();
$id = $_GET['id'] ?? '';
$sede = $_GET['Sede'] ?? '';
$folio = $_GET['Folio'] ?? '';
$presentacion = $_GET['Presentaciones'] ?? '';
$linea = $_GET['Lineas'] ?? '';
$tipo = $_GET['Tipo'] ?? '';
$tarima = $_GET['Tarima'] ?? '';
$fecha = $_GET['Fecha'] ?? '';
$fechaE = $_GET['FechaE'] ?? '';
$embarque_id = $_GET['embarque'] ?? '';

if ($id == 'empty') {
    include("mapa_cf_empty.php");
}else{
    include("mapa_cf_fill.php");
}

$html = ob_get_clean();

// Cargar HTML en Dompdf
$dompdf->loadHtml($html);

// Configurar tamaño y orientación
$dompdf->setPaper('letter', 'portrait');

// Renderizar PDF
$dompdf->render();

// Enviar al navegador
$dompdf->stream("pallet_" . date("Ymd_His") . ".pdf", ["Attachment" => false]);
// Si quieres forzar descarga, cambia Attachment => true

?>
