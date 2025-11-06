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
$pago          = $_GET['pago'] ?? 'SEMANAL';

// ================================
// 3. Última semana si no hay filtro
// ================================
$hoy = new DateTime();
if ($anoFiltro == '' || $semanaFiltro == '') {
    $diaHoy = (int)$hoy->format('N'); // 1=lunes ... 7=domingo
    if ($diaHoy <= 2) $hoy->modify('-1 week');
    $anoFiltro = $hoy->format('o');
    $semanaFiltro = $hoy->format('W');
}

// ================================
// 4. Calcular rango de semana
// ================================
$dto = new DateTime();
$dto->setISODate($anoFiltro, $semanaFiltro);

if (strtoupper($pago) === 'QUINCENAL') {
    $inicioSemana = clone $dto;
    $inicioSemana->modify('monday this week');
    $finSemana = clone $inicioSemana;
    $finSemana->modify('+6 days');
    $ordenDiasPivot = ['lunes','martes','miercoles','jueves','viernes','sabado','domingo'];
} else {
    $inicioSemana = clone $dto;
    $inicioSemana->modify('+2 days'); // miércoles
    $finSemana = clone $inicioSemana;
    $finSemana->modify('+6 days'); // martes siguiente
    $ordenDiasPivot = ['miercoles','jueves','viernes','sabado','domingo','lunes','martes'];
}

// Generar $diasSemana dinámicamente
$diasSemana = [];
$fechaTemp = clone $inicioSemana;
foreach ($ordenDiasPivot as $diaEsp) {
    $diasSemana[] = [
        'nombre' => ucfirst($diaEsp),
        'numero' => $fechaTemp->format('d'),
        'fecha'  => $fechaTemp->format('Y-m-d'),
        'clave'  => $diaEsp
    ];
    $fechaTemp->modify('+1 day');
}

// ================================
// 5. Consulta principal con filtros
// ================================
$inicioSQL = $diasSemana[0]['fecha'];
$finSQL    = $diasSemana[6]['fecha'];

$where = "WHERE dia BETWEEN '$inicioSQL' AND '$finSQL'";
if (($TipoRol != 'ADMINISTRADOR' && $TipoRol != 'SUPERVISOR') && $Sede) {
    $where .= " AND id_sede_pl = " . intval($Sede);
}
if ($deptoFiltro != '') $where .= " AND departamento = '".$Con->real_escape_string($deptoFiltro)."'";
if ($tipoFiltro != '') $where .= " AND empleado LIKE '".$Con->real_escape_string($tipoFiltro)."%'";
if ($pago != '') $where .= " AND tipo_pago = '".$Con->real_escape_string($pago)."'";

$sql = "SELECT * FROM vw_asistencia $where ORDER BY codigo_s ASC, nombre_completo ASC";
$result = $Con->query($sql);

// ================================
// 6. Pivot y cálculo de horas extra
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
    $id = $row['empleado'] . '_' . $row['id_sede_pl'];
    if (!isset($empleadosSemana[$id])) $empleadosSemana[$id] = [];

    $fechaCheck = $row['dia'];
    $diaNum = (int)date('N', strtotime($fechaCheck));
    $horaSalidaCampo = 'hora_salida';
    if ($diaNum === 6 && !empty($row['hora_sabado'])) $horaSalidaCampo = 'hora_sabado';
    if ($diaNum === 7 && !empty($row['hora_domingo'])) $horaSalidaCampo = 'hora_domingo';

    $horaSalidaProg = isset($row[$horaSalidaCampo]) && !empty($row[$horaSalidaCampo])
        ? DateTime::createFromFormat('Y-m-d H:i', $fechaCheck . ' ' . substr($row[$horaSalidaCampo],0,5))
        : null;

    $horaSalidaReal = isset($row['salida']) && !empty($row['salida'])
        ? DateTime::createFromFormat('Y-m-d H:i', $fechaCheck . ' ' . substr($row['salida'],0,5))
        : null;

    $horas_extra = 0.0;
    if ($horaSalidaProg && $horaSalidaReal) {
        $diffSegundos = $horaSalidaReal->getTimestamp() - $horaSalidaProg->getTimestamp();
        if ($diffSegundos > 0) $horas_extra = $diffSegundos / 3600;
    }

    $empleadosSemana[$id][strtolower(date('l', strtotime($row['dia'])))] = [
        'datos' => $row,
        'horas_extra' => $horas_extra
    ];
}

// ================================
// 7. Redistribución HE (máx 3/día, 9/semana)
foreach ($empleadosSemana as $id => &$dias) {
    $diasHE = [];
    foreach ($map as $diaEng => $diaEsp) {
        $diasHE[$diaEsp] = isset($dias[$diaEng]) ? $dias[$diaEng]['horas_extra'] : 0;
        if ($diasHE[$diaEsp] > 3) $diasHE[$diaEsp] = 3;
    }
    $totalHE = array_sum($diasHE);
    while ($totalHE > 9) {
        arsort($diasHE);
        foreach ($diasHE as $dia => $he) {
            if ($totalHE <= 9) break;
            if ($he > 0) {
                $diasHE[$dia]--;
                $totalHE--;
            }
        }
    }
    foreach ($map as $diaEng => $diaEsp) {
        if (isset($dias[$diaEng])) $dias[$diaEng]['horas_extra'] = $diasHE[$diaEsp];
    }
}
unset($dias);

// ================================
// 8. Crear estructura pivot
// ================================
$pivot = [];
foreach ($empleadosSemana as $id => $dias) {
    $firstDia = array_key_first($dias);
    $pivot[$id] = [
        'codigo_s'     => $dias[$firstDia]['datos']['codigo_s'],
        'empleado'     => $dias[$firstDia]['datos']['empleado'],
        'nombre'       => $dias[$firstDia]['datos']['nombre_completo'],
        'departamento' => $dias[$firstDia]['datos']['departamento'],
        'lunes' => '', 'martes' => '', 'miercoles' => '',
        'jueves'=> '', 'viernes'=> '', 'sabado' => '', 'domingo' => ''
    ];

    foreach ($map as $diaEng => $diaEsp) {
        if (!isset($dias[$diaEng]) || !$dias[$diaEng]['datos']['entrada']) {
            $pivot[$id][$diaEsp] = '';
            continue;
        }

        $entrada = !empty($dias[$diaEng]['datos']['entrada']) ? date("H:i", strtotime($dias[$diaEng]['datos']['entrada'])) : '';
        $fechaCheck = $dias[$diaEng]['datos']['dia'];
        $diaNum = (int)date('N', strtotime($fechaCheck));
        $progHoraStr = $dias[$diaEng]['datos']['hora_salida'];
        if ($diaNum === 6 && !empty($dias[$diaEng]['datos']['hora_sabado'])) $progHoraStr = $dias[$diaEng]['datos']['hora_sabado'];
        if ($diaNum === 7 && !empty($dias[$diaEng]['datos']['hora_domingo'])) $progHoraStr = $dias[$diaEng]['datos']['hora_domingo'];

        $horaProg = DateTime::createFromFormat('Y-m-d H:i', $fechaCheck . ' ' . $progHoraStr);
        $horaSalidaReal = !empty($dias[$diaEng]['datos']['salida']) ? DateTime::createFromFormat('Y-m-d H:i', $fechaCheck . ' ' . substr($dias[$diaEng]['datos']['salida'],0,5)) : null;

        $heFinal = isset($dias[$diaEng]['horas_extra']) ? (int) round($dias[$diaEng]['horas_extra']) : 0;

        if (!$horaSalidaReal) {
            $horaSalidaMostrar = $horaProg->format('H:i');
        } elseif ($horaSalidaReal <= $horaProg) {
            $horaSalidaMostrar = $horaSalidaReal->format('H:i');
        } else {
            $dt = clone $horaProg;
            if ($heFinal > 0) $dt->modify("+{$heFinal} hours");
            $minutesReal = (int)$horaSalidaReal->format('i');
            $dt->setTime((int)$dt->format('H'), $minutesReal);
            $horaSalidaMostrar = $dt->format('H:i');
        }

        $heTexto = $heFinal > 0 ? "<br><small style='color:#d9534f;'>HE: {$heFinal} hrs</small>" : "";
        $pivot[$id][$diaEsp] = $entrada . ($horaSalidaMostrar ? "<br>" . $horaSalidaMostrar : '') . $heTexto;
    }
}

// ================================
// 9. Crear HTML
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

<div class="header">
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
</div>

<style>
.header { position: fixed; top: 0; left: 0; right: 0; }
body { margin-top: 100px; } /* altura de la cabecera */
</style>

<table>
<thead>
<br>
<tr>
<th>Sede</th>
<th>Empleado</th>
<th>Nombre<br><span class="dia-num">(Departamento)</span></th>';

foreach ($diasSemana as $d) $html .= "<th>{$d['nombre']}<br><span class='dia-num'>{$d['numero']}</span></th>";
$html .= '</tr></thead><tbody>';

// ================================
// 10. Rellenar tabla con pivot
// ================================
foreach ($pivot as $row) {
    $html .= '<tr>';
    $html .= "<td>{$row['codigo_s']}</td>";
    $html .= "<td>{$row['empleado']}</td>";
    $html .= "<td><span class='nombre'>{$row['nombre']}</span><br><span class='depto'>{$row['departamento']}</span></td>";
    foreach ($ordenDiasPivot as $d) {
        $html .= "<td>" . ($row[$d] ?? '') . "</td>";
    }
    $html .= '</tr>';
}
$html .= '</tbody></table>';

// ================================
// 11. Generar PDF
// ================================
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('letter', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_auditoria.pdf", ["Attachment" => false]);
?>
