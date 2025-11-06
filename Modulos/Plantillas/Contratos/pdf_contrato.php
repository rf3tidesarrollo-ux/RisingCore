<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
require_once '../../../Librerias/dompdf/autoload.inc.php';
require_once '../../../Librerias/PHPWord/src/PhpWord/Autoloader.php';

\PhpOffice\PhpWord\Autoloader::register();

use Dompdf\Dompdf;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;

ob_start();
$idEmpleado = $_GET['id'] ?? '0'; // O un parámetro GET/POST
$tipoContrato = $_GET['tc'] ?? '0'; // Tipo de contrato: 1 o 2

$stmt = $Con->prepare("SELECT * FROM vw_contratos WHERE id = ?");
$stmt->bind_param("i", $idEmpleado);
$stmt->execute();
$result = $stmt->get_result();
$datosBD = $result->fetch_assoc();
if (!$datosBD) {
    echo '<script>
            alert("Empleado no encontrado");
            window.history.back(); // Opcional: vuelve a la página anterior
          </script>';
    exit;
}

function numero_a_letras($numero) {
    $formatter = new NumberFormatter("es", NumberFormatter::SPELLOUT);
    
    // Parte entera
    $entero = floor($numero);
    $decimal = round(($numero - $entero) * 100);

    $letras = $formatter->format($entero);
    $letras = ucfirst($letras); // Primera letra mayúscula

    return "{$letras} pesos, {$decimal}/100 MN";
}

// Configurar el locale en español
setlocale(LC_TIME, 'es_ES.UTF-8'); // Linux
Windows: setlocale(LC_TIME, 'spanish');
date_default_timezone_set('America/Mexico_City');

// Formatear fechas
$fecha_ts = strtotime($datosBD['fecha_ingreso']);
$fecha_i = strftime('%e de %B del %Y', $fecha_ts);
$fecha_nc = strtotime($datosBD['fecha_n']);
$fecha_n = strftime('%e de %B del %Y', $fecha_nc);
if ($tipoContrato==1) {
    $fecha_v = strtotime('+1 month', $fecha_ts);
    $fecha_vencimiento = strftime('%e de %B del %Y', $fecha_v);
} elseif ($tipoContrato==2) {
    $fecha_v = strtotime('+3 months', $fecha_ts);
    $fecha_vencimiento = strftime('%e de %B del %Y', $fecha_v);
}
if ($tipoContrato==1 || $tipoContrato==3) {
    $salario_c = $datosBD['salario'];
    $salario_letras = numero_a_letras($salario_c);
}else{
    $salario_c = $datosBD['salario'] * 30;
}

$day = date('j'); // Día del mes sin ceros iniciales
$month = strftime('%B'); // Nombre del mes en español
$year = date('Y'); // Año actual
$fecha_formal = "A LOS $day DÍAS DEL MES DE " . mb_strtoupper($month, 'UTF-8') . " DEL $year";

$datos = [
    'NOMBRE' => $datosBD['nombre_completo'],
    'PUESTO' => $datosBD['puesto'],
    'FECHA' => $fecha_i ?? '',
    'NACIMIENTO' => $fecha_n,
    'SALARIO'=> '$' . number_format($salario_c, 2),
    'LETRAS' => $salario_letras,
    'EDAD' => $datosBD['edad'] ?? '',
    'GENERO' => $datosBD['genero'],
    'ESTADO' => $datosBD['estado'],
    'RFC' => $datosBD['rfc'],
    'INE' => $datosBD['ine'] ?? '',
    'CURP' => $datosBD['curp'],
    'DOMICILIO' => $datosBD['domicilio'],
    'ESCOLARIDAD' => $datosBD['escolaridad'] ?? '',
    'BENEFICIARIO' => $datosBD['beneficiario'],
    'PARENTESCO' => $datosBD['parentesco'],
    'DEPARTAMENTO' => $datosBD['departamento'] ?? '',
    'VENCIMIENTO' => $fecha_vencimiento ?? '',
    'NSS' => $datosBD['nss'] ?? '',
    'PAGO' => $datosBD['pago'],
    'DATE' => $fecha_formal ?? '',
];

// Plantilla
$plantilla =  "contrato_tipo{$tipoContrato}.docx";

if (!file_exists($plantilla)) {
    echo '<script>
            alert("No se encontró la plantilla del contrato.");
            window.history.back(); // Opcional: vuelve a la página anterior
          </script>';
    exit;
}

// Guardar temporalmente Word
$wordFile = tempnam(sys_get_temp_dir(), 'contrato_') . '.docx';
$template->saveAs($wordFile);

// Convertir Word a HTML
$phpWord = IOFactory::load($wordFile);
$htmlWriter = IOFactory::createWriter($phpWord, 'HTML');

ob_start();
$htmlWriter->save("php://output");
$html = ob_get_clean();

// Generar PDF con Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('letter', 'portrait');
$dompdf->render();

// Mostrar PDF en el navegador
$dompdf->stream("Contrato_{$datos['NOMBRE']}.pdf", [
    "Attachment" => false // false = se muestra en el navegador, true = descarga
]);

exit;
