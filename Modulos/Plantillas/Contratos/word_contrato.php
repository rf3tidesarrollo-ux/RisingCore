<?php
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";
require_once '../../../Librerias/PHPWord/src/PhpWord/Autoloader.php';

\PhpOffice\PhpWord\Autoloader::register();
use PhpOffice\PhpWord\TemplateProcessor;

// Datos de ejemplo
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
$fecha_i = mb_strtoupper(strftime('%e de %B del %Y', $fecha_ts), 'UTF-8');
$fecha_nc = strtotime($datosBD['fecha_n']);
$fecha_n = mb_strtoupper(strftime('%e de %B del %Y', $fecha_nc), 'UTF-8');
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

// Generar Word dinámico
$template = new TemplateProcessor($plantilla);
foreach ($datos as $key => $value) {
    $template->setValue($key, $value);
}

// Preparar descarga directa
header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="Contrato_'.$datos['NOMBRE'].'.docx"');
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');

// Guardar el archivo en output directamente
$template->saveAs('php://output');
exit;
