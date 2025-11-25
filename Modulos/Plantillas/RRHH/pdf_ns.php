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
$hoy = new DateTime();
if ($anoFiltro == '' || $semanaFiltro == '') {
    $diaHoy = (int)$hoy->format('N'); // 1=lunes ... 7=domingo
    if ($diaHoy <= 2) $hoy->modify('-1 week');
    $anoFiltro = $hoy->format('o');
    $semanaFiltro = $hoy->format('W');
}

// ================================
// 5. Consulta principal con filtros
// ================================
$fecha = new DateTime();
$fecha->setISODate($anoFiltro, $semanaFiltro);

// Mover del lunes al miércoles (+2 días)
$fecha->modify('+2 day');

$miercoles = $fecha->format('Y-m-d');

$where = "WHERE semana_laboral = '$miercoles'";
if (($TipoRol != 'ADMINISTRADOR' && $TipoRol != 'SUPERVISOR') && $Sede) {
    $where .= " AND id_sede = " . intval($Sede);
}
if ($deptoFiltro != '') $where .= " AND area = '".$Con->real_escape_string($deptoFiltro)."'";
if ($tipoFiltro != '') $where .= " AND clave LIKE '".$Con->real_escape_string($tipoFiltro)."%'";

$sql = "SELECT * FROM vw_nomina_semanal $where ORDER BY sede ASC, clave ASC";
$result = $Con->query($sql);

// ================================
// 6. Pivot
// ================================
$map = [
    'sede'                   => 'sede',
    'clave'                  => 'clave',
    'nombre_completo'        => 'nombre_completo',
    'area'                   => 'area',
    'total_dias'             => 'total_dias',
    'vino_domingo'           => 'vino_domingo',
    'total_horas_extra'      => 'total_horas_extra',
    'total_horas_trabajadas' => 'total_horas_trabajadas',
    'total_bonos'            => 'total_bonos',
    'total_destajos'         => 'total_destajos',
    'total_compensaciones'   => 'total_compensaciones'
];

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
    <td colspan="2" class="title" style="text-align:center; font-size:14px;">Nómina semanal</td>
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
<th>Nombre<br><span class="dia-num">(Departamento)</span></th>
<th>A</th>
<th>S/D</th>
<th>HE</th>
<th>HT</th>
<th>B</th>
<th>D</th>
<th>C</th>
</tr></thead><tbody>';

// ================================
// 10. Rellenar tabla con pivot
// ================================
if (!$result) {
    die("Error SQL: " . $Con->error . "<br>Consulta: " . $sql);
}

while ($row = $result->fetch_assoc()) {

    // Domingo = SI/NO
    $vinoDomingo = ($row['vino_domingo'] == 1)
        ? '<span style="color:red; font-weight:bold;">SI</span>'
        : 'NO';

    // Horas extra ≥ 0.5 en rojo
    $horasExtra = (float)$row['total_horas_extra'] >= 0.5
        ? '<span style="color:red; font-weight:bold;">' . $row['total_horas_extra'] . '</span>'
        : $row['total_horas_extra'];

    // Bonos / Destajos / Compensaciones > 0 en rojo
    $bonos = (float)$row['total_bonos'] > 0
        ? '<span style="color:red; font-weight:bold;">$' . $row['total_bonos'] . '</span>'
        : '$' . $row['total_bonos'];

    $destajos = (float)$row['total_destajos'] > 0
        ? '<span style="color:red; font-weight:bold;">$' . $row['total_destajos'] . '</span>'
        : '$' . $row['total_destajos'];

    $compensaciones = (float)$row['total_compensaciones'] > 0
        ? '<span style="color:red; font-weight:bold;">$' . $row['total_compensaciones'] . '</span>'
        : '$' . $row['total_compensaciones'];

    // Fila
    $html .= '<tr>';
    $html .= "<td>{$row['sede']}</td>";
    $html .= "<td>{$row['clave']}</td>";
    $html .= "<td><span class='nombre'>{$row['nombre_completo']}</span><br><span class='depto'>{$row['area']}</span></td>";
    $html .= "<td>{$row['total_dias']}</td>";
    $html .= "<td>{$vinoDomingo}</td>";
    $html .= "<td>{$horasExtra}</td>";
    $html .= "<td>{$row['total_horas_trabajadas']}</td>";
    $html .= "<td>{$bonos}</td>";
    $html .= "<td>{$destajos}</td>";
    $html .= "<td>{$compensaciones}</td>";
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
$dompdf->stream("nomina_semanal.pdf", ["Attachment" => false]);
?>
