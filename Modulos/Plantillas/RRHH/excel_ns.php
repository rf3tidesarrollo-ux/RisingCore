<?php
require '../../../Librerias/PhpSpreadsheet/vendor/autoload.php';
include_once '../../../Conexion/BD.php';
include_once "../../../Login/validar_sesion.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// ================================
// Parámetros e imagen
// ================================
$imgPath = realpath('../../../Images/Rising_Logo.jpg');
$Fecha   = date("d/m/Y");

$anoFiltro     = $_GET['ano'] ?? '';
$semanaFiltro  = $_GET['semana'] ?? '';
$deptoFiltro   = $_GET['departamento'] ?? '';
$tipoFiltro    = $_GET['tipo'] ?? '';

$hoy = new DateTime();
if ($anoFiltro == '' || $semanaFiltro == '') {
    $diaHoy = (int)$hoy->format('N');
    if ($diaHoy <= 2) $hoy->modify('-1 week');
    $anoFiltro = $hoy->format('o');
    $semanaFiltro = $hoy->format('W');
}

$fecha = new DateTime();
$fecha->setISODate($anoFiltro, $semanaFiltro);
$fecha->modify('+2 day');
$miercoles = $fecha->format('Y-m-d');

// ================================
// Consulta
// ================================
$where = "WHERE semana_laboral = '$miercoles'";
if (($TipoRol != 'ADMINISTRADOR' && $TipoRol != 'SUPERVISOR') && $Sede) {
    $where .= " AND id_sede = " . intval($Sede);
}
if ($deptoFiltro != '') $where .= " AND area = '".$Con->real_escape_string($deptoFiltro)."'";
if ($tipoFiltro != '') $where .= " AND clave LIKE '".$Con->real_escape_string($tipoFiltro)."%'";

$sql = "SELECT * FROM vw_nomina_semanal $where ORDER BY sede ASC, clave ASC";
$data = $Con->query($sql);

// ================================
// Crear Excel
// ================================
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Nómina");

// Insertar logo
$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setPath($imgPath);
$drawing->setHeight(70);
$drawing->setCoordinates('B1');
$drawing->setWorksheet($sheet);

// Encabezados generales
$sheet->setCellValue('C2', "NÓMINA SEMANAL");
$sheet->setCellValue('E2', "Fecha: $Fecha");
$sheet->setCellValue('C3', "Departamento: " . ($deptoFiltro ?: 'Todos'));
$sheet->setCellValue('D3', "Semana: $semanaFiltro");
$sheet->setCellValue('D2', "Año: $anoFiltro");

// Títulos de columnas
$header = ['Sede', 'Empleado', 'Nombre', 'Área', 'Asistencias', 'S/D', 'Horas Extra', 'Horas Trabjadas', 'Bonos', 'Destajos', 'Compensaciones'];
$sheet->fromArray($header, null, 'A6');

// Estilo encabezado columnas
$sheet->getStyle('A6:K6')->getFont()->setBold(true);
$sheet->getStyle('A6:K6')->getFill()->setFillType('solid')
    ->getStartColor()->setARGB('FF07BB67');
$sheet->getStyle('A6:K6')->getFont()->getColor()->setARGB('FFFFFFFF');

// ================================
// Rellenar con datos
// ================================
$row = 7;
while ($r = $data->fetch_assoc()) {
    $sheet->setCellValue("A$row", $r['sede']);
    $sheet->setCellValue("B$row", $r['clave']);
    $sheet->setCellValue("C$row", $r['nombre_completo']);
    $sheet->setCellValue("D$row", $r['area']);
    $sheet->setCellValue("E$row", $r['total_dias']);

    // Columna F → vino_domingo
    $sheet->setCellValue("F$row", $r['vino_domingo'] == 1 ? "SI" : "NO");
    if ($r['vino_domingo'] == 1) {
        $sheet->getStyle("F$row")->getFont()->getColor()->setARGB('FFFF0000');
        $sheet->getStyle("F$row")->getFont()->setBold(true);
    }

    // Columna G → total_horas_extra
    $sheet->setCellValue("G$row", $r['total_horas_extra']);
    if ($r['total_horas_extra'] >= 0.5) {
        $sheet->getStyle("G$row")->getFont()->getColor()->setARGB('FFFF0000');
        $sheet->getStyle("G$row")->getFont()->setBold(true);
    }

    // Columna H → total_horas_trabajadas
    $sheet->setCellValue("H$row", $r['total_horas_trabajadas']);

    // Columna I → total_bonos
    $sheet->setCellValue("I$row", $r['total_bonos']);
    if ($r['total_bonos'] > 0) {
        $sheet->getStyle("I$row")->getFont()->getColor()->setARGB('FFFF0000');
        $sheet->getStyle("I$row")->getFont()->setBold(true);
    }

    // Columna J → total_destajos
    $sheet->setCellValue("J$row", $r['total_destajos']);
    if ($r['total_destajos'] > 0) {
        $sheet->getStyle("J$row")->getFont()->getColor()->setARGB('FFFF0000');
        $sheet->getStyle("J$row")->getFont()->setBold(true);
    }

    // Columna K → total_compensaciones
    $sheet->setCellValue("K$row", $r['total_compensaciones']);
    if ($r['total_compensaciones'] > 0) {
        $sheet->getStyle("K$row")->getFont()->getColor()->setARGB('FFFF0000');
        $sheet->getStyle("K$row")->getFont()->setBold(true);
    }

    $row++;
}

// Auto tamaño columnas
foreach (range('A', 'K') as $col)
    $sheet->getColumnDimension($col)->setAutoSize(true);

// Bordes
$sheet->getStyle('A6:K' . ($row - 1))
    ->getBorders()->getAllBorders()
    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

// ================================
// Descargar
// ================================
$filename = "Nomina_Semanal.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
$writer = new Xlsx($spreadsheet);
$writer->save("php://output");
exit;
