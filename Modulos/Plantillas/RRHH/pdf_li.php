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
// 3. Última semana si no hay filtro y calcular miércoles → martes
// ================================
$hoy = new DateTime();

// Si no hay filtro, usamos la lógica miércoles → martes
if ($anoFiltro == '' || $semanaFiltro == '') {
    $diaHoy = (int)$hoy->format('N'); // 1=lunes ... 7=domingo

    // Lunes o martes todavía pertenecen a la semana anterior
    if ($diaHoy <= 2) {
        $hoy->modify('-1 week');
    }

    $anoFiltro = $hoy->format('o');
    $semanaFiltro = $hoy->format('W');
}

// ================================
// 4. Calcular rango de semana miércoles → martes
// ================================
$dto = new DateTime();
$dto->setISODate($anoFiltro, $semanaFiltro);
$inicioSemana = clone $dto;
$inicioSemana->modify('+2 days'); // miércoles
$finSemana = clone $inicioSemana;
$finSemana->modify('+6 days'); // martes siguiente

$diasNombres = [
    'monday'    => 'Lunes',
    'tuesday'   => 'Martes',
    'wednesday' => 'Miércoles',
    'thursday'  => 'Jueves',
    'friday'    => 'Viernes',
    'saturday'  => 'Sábado',
    'sunday'    => 'Domingo'
];

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
// 5. Consulta principal con filtros
// ================================
$inicioSQL = $diasSemana[0]['fecha'];
$finSQL    = $diasSemana[6]['fecha'];

$where = "WHERE dia BETWEEN '$inicioSQL' AND '$finSQL'";
if ($deptoFiltro != '') $where .= " AND departamento = '".$Con->real_escape_string($deptoFiltro)."'";
if ($tipoFiltro != '') $where .= " AND empleado LIKE '".$Con->real_escape_string($tipoFiltro)."%'";

$sql = "SELECT * FROM vw_asistencia $where ORDER BY empleado ASC, dia ASC";
$result = $Con->query($sql);

// ================================
// 6. Calcular horas extra reales
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
while ($row = $result->fetch_assoc()) {
    $id = $row['empleado'];
    if (!isset($empleadosSemana[$id])) $empleadosSemana[$id] = [];

    $fechaCheck = $row['dia'];
    $diaNum = (int)date('N', strtotime($fechaCheck));
    $horaSalidaCampo = 'hora_salida';

    if ($diaNum === 6 && !empty($row['hora_sabado'])) $horaSalidaCampo = 'hora_sabado';
    elseif ($diaNum === 7 && !empty($row['hora_domingo'])) $horaSalidaCampo = 'hora_domingo';

    $horaSalidaProg = isset($row[$horaSalidaCampo]) && !empty($row[$horaSalidaCampo])
        ? DateTime::createFromFormat('Y-m-d H:i', $fechaCheck . ' ' . substr($row[$horaSalidaCampo], 0, 5))
        : null;

    $horaSalidaReal = isset($row['salida']) && !empty($row['salida'])
        ? DateTime::createFromFormat('Y-m-d H:i', $fechaCheck . ' ' . substr($row['salida'], 0, 5))
        : null;

    $horas_extra = 0.0;
    if ($horaSalidaProg && $horaSalidaReal) {
        $diffSegundos = $horaSalidaReal->getTimestamp() - $horaSalidaProg->getTimestamp();
        if ($diffSegundos > 0) $horas_extra = $diffSegundos / 3600;
    }

    $empleadosSemana[$id][strtolower(date('l', strtotime($row['dia'])))] = [
        'datos'       => $row,
        'horas_extra' => $horas_extra
    ];
}

// ================================
// 7. Crear pivot manteniendo horas reales
// ================================
$pivot = [];
foreach ($empleadosSemana as $id => $dias) {
    $firstDia = array_key_first($dias);
    $pivot[$id] = [
        'codigo_s'     => $dias[$firstDia]['datos']['codigo_s'],
        'empleado'     => $id,
        'nombre'       => $dias[$firstDia]['datos']['nombre_completo'],
        'departamento' => $dias[$firstDia]['datos']['departamento'],
        'lunes' => '', 'martes' => '', 'miercoles' => '',
        'jueves'=> '', 'viernes'=> '', 'sabado' => '', 'domingo' => ''
    ];

    foreach ($map as $diaEng => $diaEsp) {
        if (!isset($dias[$diaEng]) || !$dias[$diaEng]['datos']['entrada']) {
            $pivot[$id][$diaEsp] = "";
            continue;
        }

        $entrada = !empty($dias[$diaEng]['datos']['entrada']) ? date("H:i", strtotime($dias[$diaEng]['datos']['entrada'])) : '';
        $salidaReal = '';
        if (!empty($dias[$diaEng]['datos']['salida'])) {
            $salidaReal = date("H:i", strtotime($dias[$diaEng]['datos']['salida']));
        } else {
            $progHoraStr = $dias[$diaEng]['datos']['hora_salida'];
            $diaNum = (int)date('N', strtotime($dias[$diaEng]['datos']['dia']));
            if ($diaNum === 6 && !empty($dias[$diaEng]['datos']['hora_sabado'])) {
                $progHoraStr = $dias[$diaEng]['datos']['hora_sabado'];
            } elseif ($diaNum === 7 && !empty($dias[$diaEng]['datos']['hora_domingo'])) {
                $progHoraStr = $dias[$diaEng]['datos']['hora_domingo'];
            }
            $salidaReal = $progHoraStr;
        }
        $heReal = isset($dias[$diaEng]['horas_extra']) ? (int) floor($dias[$diaEng]['horas_extra']) : 0;
        $heTexto = $heReal > 0 ? "<br><small style='color:#d9534f;'>HE: {$heReal} hrs</small>" : "";
        $pivot[$id][$diaEsp] = $entrada . ($salidaReal ? "<br>" . $salidaReal : '') . $heTexto;
    }
}

// ================================
// 8. Construir HTML
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

<table>
<thead>
<tr>
<th>Sede</th>
<th>Empleado</th>
<th>Nombre<br><span class="dia-num">(Departamento)</span></th>';

foreach ($diasSemana as $d) $html .= "<th>{$d['nombre']}<br><span class='dia-num'>{$d['numero']}</span></th>";
$html .= '</tr></thead><tbody>';

$ordenDias = ['miercoles','jueves','viernes','sabado','domingo','lunes','martes'];
foreach ($pivot as $row) {
    $html .= '<tr>';
    $html .= "<td>{$row['codigo_s']}</td>";
    $html .= "<td>{$row['empleado']}</td>";
    $html .= "<td><span class='nombre'>{$row['nombre']}</span><br><span class='depto'>{$row['departamento']}</span></td>";
    foreach ($ordenDias as $d) $html .= "<td>{$row[$d]}</td>";
    $html .= '</tr>';
}

$html .= '</tbody></table>';

// ================================
// 9. Generar PDF
// ================================
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_asistencia.pdf", ["Attachment" => false]);
?>
