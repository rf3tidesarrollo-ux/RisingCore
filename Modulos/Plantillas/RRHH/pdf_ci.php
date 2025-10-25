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
// 2. Filtros GET
// ================================
$anoFiltro     = $_GET['ano'] ?? '';
$semanaFiltro  = $_GET['semana'] ?? '';
$deptoFiltro   = $_GET['departamento'] ?? '';
$tipoFiltro    = $_GET['tipo'] ?? '';
$pago          = $_GET['pago'] ?? 'SEMANAL';

// ================================
// 3. Cálculo de semana
// ================================
$hoy = new DateTime();

if ($anoFiltro == '' || $semanaFiltro == '') {
    $diaHoy = (int)$hoy->format('N');
    if ($diaHoy <= 2) $hoy->modify('-1 week');
    $anoFiltro = $hoy->format('o');
    $semanaFiltro = $hoy->format('W');
}

$dto = new DateTime();
$dto->setISODate($anoFiltro, $semanaFiltro);

// ================================
// 4. Rango según tipo de pago
// ================================
if (strtoupper($pago) === 'QUINCENAL') {
    $inicioSemana = clone $dto;
    $inicioSemana->modify('monday this week');
    $finSemana = clone $inicioSemana;
    $finSemana->modify('+6 days');
    $ordenDias = ['lunes','martes','miercoles','jueves','viernes','sabado','domingo'];
} else {
    $inicioSemana = clone $dto;
    $inicioSemana->modify('+2 days'); // miércoles
    $finSemana = clone $inicioSemana;
    $finSemana->modify('+6 days'); // martes siguiente
    $ordenDias = ['miercoles','jueves','viernes','sabado','domingo','lunes','martes'];
}

$diasSemana = [];
$fechaTemp = clone $inicioSemana;
foreach ($ordenDias as $diaEsp) {
    $diasSemana[] = [
        'nombre' => ucfirst($diaEsp),
        'numero' => $fechaTemp->format('d'),
        'fecha'  => $fechaTemp->format('Y-m-d'),
        'clave'  => $diaEsp
    ];
    $fechaTemp->modify('+1 day');
}

// Fechas SQL
$inicioSQL = $diasSemana[0]['fecha'];
$finSQL    = $diasSemana[6]['fecha'];

// ================================
// 5. Consulta principal
// ================================
$where = "WHERE dia BETWEEN '$inicioSQL' AND '$finSQL'";
if ($deptoFiltro != '') $where .= " AND departamento = '".$Con->real_escape_string($deptoFiltro)."'";
if ($tipoFiltro != '')  $where .= " AND empleado LIKE '".$Con->real_escape_string($tipoFiltro)."%'";
if ($pago != '') $where .= " AND tipo_pago = '".$Con->real_escape_string($pago)."'";
$sql = "SELECT * FROM vw_incidencia $where ORDER BY codigo_s ASC, empleado ASC";
$result = $Con->query($sql);

// ================================
// 6. Mapear resultados a tabla pivot
// ================================
$pivot = [];
$map = [
    'monday'    => 'lunes',
    'tuesday'   => 'martes',
    'wednesday' => 'miercoles',
    'thursday'  => 'jueves',
    'friday'    => 'viernes',
    'saturday'  => 'sabado',
    'sunday'    => 'domingo'
];

while ($row = $result->fetch_assoc()) {
    $id = $row['empleado'] . '_' . $row['id_sede_pl'];
    if (!isset($pivot[$id])) {
        $pivot[$id] = [
            'codigo_s'     => $row['codigo_s'],
            'empleado'     => $row['empleado'],
            'nombre'       => $row['nombre_completo'],
            'departamento' => $row['departamento'],
            'lunes' => '', 'martes' => '', 'miercoles' => '',
            'jueves'=> '', 'viernes'=> '', 'sabado' => '', 'domingo' => ''
        ];
    }

    $diaSemana = strtolower(date('l', strtotime($row['dia'])));
    if (isset($map[$diaSemana])) {
        $col = $map[$diaSemana];
        $pivot[$id][$col] = $row['permisos_dia'] ?? '-';
    }
}

// ================================
// 7. Construcción del HTML
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
// 8. Generar PDF
// ================================
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('letter', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_incidencia.pdf", ["Attachment" => false]);
?>
