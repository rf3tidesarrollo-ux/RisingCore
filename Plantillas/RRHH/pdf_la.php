<?php
require_once '../../../Librerias/dompdf/autoload.inc.php';
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";

use Dompdf\Dompdf;

// ================================
// 1. Configuración inicial
// ================================
$imgPath = realpath('../../../Images/Rising_Logo.jpg');
$imgData = base64_encode(file_get_contents($imgPath));
$src     = 'data:image/jpeg;base64,' . $imgData;
$Fecha   = date("d/m/Y");
$User    = $_SESSION['nombre_completo'] ?? '---';

// ================================
// 2. Filtros
// ================================
$anoFiltro     = $_GET['ano'] ?? '';
$semanaFiltro  = $_GET['semana'] ?? '';
$deptoFiltro   = $_GET['departamento'] ?? '';
$tipoFiltro    = $_GET['tipo'] ?? '';

// ================================
// 3. Última semana si no hay filtro
// ================================
if ($anoFiltro == '' || $semanaFiltro == '') {
    $row = $Con->query("SELECT MAX(registro_check) as ultima FROM rh_check")->fetch_assoc();
    if ($row && $row['ultima']) {
        $ultima = new DateTime($row['ultima']);
        $anoFiltro = $ultima->format('o');
        $semanaFiltro = $ultima->format('W');
    } else {
        die("No hay registros para generar el PDF.");
    }
}

// ================================
// 4. Calcular semana de miércoles a martes
// ================================
$dto = new DateTime();
$dto->setISODate($anoFiltro, $semanaFiltro);

// Ajustar al miércoles de esa semana
$diaSemana = (int)$dto->format('N'); // 1=lunes ... 7=domingo
$inicioSemana = clone $dto;
$inicioSemana->modify('+'.(3-$diaSemana).' days'); // 3 = miércoles

$diasNombres = [
    'monday'    => 'Lunes',
    'tuesday'   => 'Martes',
    'wednesday' => 'Miércoles',
    'thursday'  => 'Jueves',
    'friday'    => 'Viernes',
    'saturday'  => 'Sábado',
    'sunday'    => 'Domingo'
];

// Array de días miércoles -> martes
$diasSemana = [];
for ($i = 0; $i < 7; $i++) {
    $fecha = (clone $inicioSemana)->modify("+$i day");
    $diasSemana[] = [
        'nombre' => $diasNombres[strtolower($fecha->format('l'))],
        'numero' => $fecha->format('d'),
        'fecha'  => $fecha->format('Y-m-d')
    ];
}

// ================================
// 5. Consulta principal
// ================================
$inicioSQL = $diasSemana[0]['fecha'];
$finSQL    = $diasSemana[6]['fecha'];

$where = "WHERE dia BETWEEN '$inicioSQL' AND '$finSQL'";
if ($deptoFiltro != '') $where .= " AND departamento = '".$Con->real_escape_string($deptoFiltro)."'";
if ($tipoFiltro != '') $where .= " AND tipo_empleado = '".$Con->real_escape_string($tipoFiltro)."'";

$sql = "SELECT * FROM vw_asistencia $where ORDER BY empleado ASC, dia ASC";
$result = $Con->query($sql);

// ================================
// 6. Pivotear registros
// ================================
$map = [
    'monday'    => 'lunes',
    'tuesday'   => 'martes',
    'wednesday' => 'miercoles',
    'thursday'  => 'jueves',
    'friday'    => 'viernes',
    'saturday'  => 'sabado',
    'sunday'    => 'domingo'
];

$empleadosSemana = [];
$result->data_seek(0);
while ($row = $result->fetch_assoc()) {
    $id = $row['empleado'];
    if (!isset($empleadosSemana[$id])) $empleadosSemana[$id] = [];
    $empleadosSemana[$id][strtolower(date('l', strtotime($row['dia'])))] = $row;
}

$pivot = [];
foreach ($empleadosSemana as $id => $dias) {
    $pivot[$id] = [
        'codigo_s'     => $dias[array_key_first($dias)]['codigo_s'],
        'empleado'     => $id,
        'nombre'       => $dias[array_key_first($dias)]['nombre_completo'],
        'departamento' => $dias[array_key_first($dias)]['departamento'],
        'lunes' => '', 'martes' => '', 'miercoles' => '',
        'jueves'=> '', 'viernes'=> '', 'sabado' => '', 'domingo' => ''
    ];

    foreach ($map as $diaEng => $diaEsp) {
        if (!isset($dias[$diaEng]) || !$dias[$diaEng]['entrada']) {
            $pivot[$id][$diaEsp] = "";
        } else {
            $entrada = date("H:i", strtotime($dias[$diaEng]['entrada']));
            $salida  = $dias[$diaEng]['salida'] ? date("H:i", strtotime($dias[$diaEng]['salida'])) : '';
            $pivot[$id][$diaEsp] = $entrada . ($salida ? "<br>" . $salida : '');
        }
    }
}

// ================================
// 7. Construir HTML con encabezado repetido
// ================================
$html = '
<style>
body { font-family: Arial, sans-serif; font-size: 11px; margin:0; padding:0; }
table { width: 100%; border-collapse: collapse; page-break-inside:auto; }
thead { display: table-header-group; }
th { background-color: #07bb67; color: white; font-size: 12px; text-align: center; padding: 5px; border: 1px solid #aaa; }
td { border: 1px solid #ccc; text-align: center; font-size: 10px; padding: 4px; vertical-align: middle; }
tbody tr:nth-child(odd) { background-color: #ffffff; }
tbody tr:nth-child(even) { background-color: #f2f2f2; }
.nombre { font-size: 11px; font-weight: bold; }
.depto { font-size: 9px; color: #07bb67; }
.dia-num { display: block; font-size: 9px; color: #e0ffe0; }
.header-general td { border: 1px solid #555; padding: 4px; font-size: 11px; }
.title { font-weight: bold; }
</style>

<!-- Encabezado general -->
<table class="header-general">
<tr>
    <td rowspan="3" style="width:90px; height:70px;"><img src="'.$src.'" width="90" height="70"/></td>
    <td colspan="2" class="title" style="text-align:center; font-size:14px;">Lista de Asistencia General</td>
    <td class="title" style="text-align:left;">Fecha: '.$Fecha.'</td>
</tr>
<tr>
    <td class="title" style="width:30%; text-align:left;">Departamento: '.($deptoFiltro ?: 'Todos').'</td>
    <td class="title" style="width:30%; text-align:left;">Semana: '.$semanaFiltro.'</td>
    <td class="title" style="width:40%; text-align:left;">Año: '.$anoFiltro.'</td>
</tr>
<tr>
    <td class="title" colspan="3">Rising Farms S.A.P.I de C.V</td>
</tr>
</table>
<br>

<!-- Tabla principal -->
<table>
<thead>
<tr>
<th>Sede</th>
<th>Empleado</th>
<th>Nombre<br><span class="dia-num">(Departamento)</span></th>';

// Encabezado días miércoles -> martes
foreach ($diasSemana as $d) {
    $html .= "<th>{$d['nombre']}<br><span class='dia-num'>{$d['numero']}</span></th>";
}

$html .= '</tr></thead><tbody>';

// Orden de días en fila
$ordenDias = ['miercoles','jueves','viernes','sabado','domingo','lunes','martes'];

foreach ($pivot as $row) {
    $html .= '<tr>';
    $html .= "<td>{$row['codigo_s']}</td>";
    $html .= "<td>{$row['empleado']}</td>";
    $html .= "<td><span class='nombre'>{$row['nombre']}</span><br><span class='depto'>{$row['departamento']}</span></td>";
    foreach ($ordenDias as $d) {
        $html .= "<td>{$row[$d]}</td>";
    }
    $html .= '</tr>';
}

$html .= '</tbody></table>';

// ================================
// 8. Generar PDF
// ================================
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_asistencia.pdf", ["Attachment" => false]);
