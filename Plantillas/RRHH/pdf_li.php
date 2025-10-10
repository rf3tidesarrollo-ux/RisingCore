<?php
require_once '../../../Librerias/dompdf/autoload.inc.php';
include_once '../../../Conexion/BD.php';
$RutaCS = "../../../Login/Cerrar.php";
$RutaSC = "../../../index.php";
include_once "../../../Login/validar_sesion.php";

use Dompdf\Dompdf;

// ================================
// 1. Recibir filtros
// ================================
$anoFiltro     = $_GET['ano'] ?? '';
$semanaFiltro  = $_GET['semana'] ?? '';
$deptoFiltro   = $_GET['departamento'] ?? '';
$tipoFiltro    = $_GET['tipo'] ?? '';

// ================================
// 2. Determinar última semana si no hay filtros
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
// 3. Calcular rango de la semana
// ================================
$dto = new DateTime();
$dto->setISODate($anoFiltro, $semanaFiltro);
$inicioSemana = $dto->format('Y-m-d');
$dto->modify('+6 days');
$finSemana = $dto->format('Y-m-d');

// ================================
// 4. Consulta principal
// ================================
$where = "WHERE dia BETWEEN '$inicioSemana' AND '$finSemana'";
if ($deptoFiltro != '') $where .= " AND departamento = '".$Con->real_escape_string($deptoFiltro)."'";
if ($tipoFiltro != '') $where .= " AND tipo_empleado = '".$Con->real_escape_string($tipoFiltro)."'";

$sql = "SELECT * FROM vw_asistencia $where ORDER BY empleado ASC, dia ASC";
$result = $Con->query($sql);

// ================================
// 5. Pivotear por días de la semana con control de horas
// ================================
$horas_semanales_max = 57; // 48 normales + 9 extras
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

// 1️⃣ Agrupar registros por empleado y día
$empleadosSemana = [];
$result->data_seek(0);
while($row = $result->fetch_assoc()) {
    $id = $row['empleado'];
    if (!isset($empleadosSemana[$id])) $empleadosSemana[$id] = [];
    $empleadosSemana[$id][strtolower(date('l', strtotime($row['dia'])))] = $row;
}

// 2️⃣ Iterar por cada empleado
foreach ($empleadosSemana as $id => $dias) {
    $pivot[$id] = [
        'codigo_s'       => $dias[array_key_first($dias)]['codigo_s'],
        'empleado'       => $id,
        'nombre_completo'=> $dias[array_key_first($dias)]['nombre_completo'],
        'departamento'   => $dias[array_key_first($dias)]['departamento'],
        'lunes' => '', 'martes' => '', 'miercoles' => '',
        'jueves'=> '', 'viernes'=> '', 'sabado' => '', 'domingo' => '',
        'total_normales' => 0,
        'total_extras'  => 0
    ];

    // 🔹 Determinar si habrá sábado trabajado
    $sabadoTrabajado = isset($dias['saturday']) && $dias['saturday']['entrada'];

    // 🔹 Iterar por cada día
    foreach ($map as $diaEng => $diaEsp) {
        if (!isset($dias[$diaEng]) || !$dias[$diaEng]['entrada']) {
            $pivot[$id][$diaEsp] = 'Sin entrada';
            continue;
        }

        $entrada = new DateTime($dias[$diaEng]['entrada']);
        $salida  = $dias[$diaEng]['salida'] ? new DateTime($dias[$diaEng]['salida']) : null;

        // 🔹 Calcular horas normales y extras
        if (!$salida) {
            // Domingo sin salida
            if ($diaEng == 'sunday') {
                $pivot[$id][$diaEsp] = $entrada->format('H:i') . " - Sin salida";
                $horas_normales = 0;
                $horas_extras = 0;
                $salida_ajustada = $entrada;
            } else {
                // Horas de jornada según sábado trabajado
                $horas_jornada = $sabadoTrabajado ? 8 : 9;

                // 1️⃣ Horas normales efectivas (restando media hora)
                $horas_normales = $horas_jornada - 0.5;
                $horas_extras = 0;

                // 2️⃣ Salida ajustada para PDF
                $salida_ajustada = (clone $entrada)->modify('+' . $horas_normales . ' hours');
            }
        } else {
            // Entrada y salida reales
            $diff = $entrada->diff($salida);
            $horas = $diff->h + ($diff->i / 60);

            $horas_normales = min($horas, 8);
            $horas_extras  = max(0, min($horas - 8, 3));

            // Salida ajustada para PDF = hora real de salida
            $salida_ajustada = $salida;
        }

        // 🔹 Ajustar total semanal máximo
        $total_acumulado = $pivot[$id]['total_normales'] + $pivot[$id]['total_extras'] + $horas_normales + $horas_extras;
        if ($total_acumulado > $horas_semanales_max) {
            $exceso = $total_acumulado - $horas_semanales_max;
            if ($horas_extras >= $exceso) {
                $horas_extras -= $exceso;
            } else {
                $horas_normales -= ($exceso - $horas_extras);
                $horas_extras = 0;
            }
        }

        $pivot[$id]['total_normales'] += $horas_normales;
        $pivot[$id]['total_extras']  += $horas_extras;

        // Guardar en pivot para PDF
        $pivot[$id][$diaEsp] = $entrada->format('H:i') . " - " . $salida_ajustada->format('H:i');
    }
}

// ================================
// 6. Construir HTML con totales
// ================================
$html = '<h2 style="text-align:center;">Reporte de Asistencia Semanal (con ajuste de horas)</h2>
<p>Semana: '.$semanaFiltro.' | Año: '.$anoFiltro.'<br>
Período: '.$inicioSemana.' - '.$finSemana.'<br>
Máximo semanal: 57h (48 normales + 9 extras)</p>
<table border="1" cellspacing="0" cellpadding="5" width="100%">
<thead>
<tr style="background:#f2f2f2;">
<th>Sede</th>
<th>Empleado</th>
<th>Nombre</th>
<th>Departamento</th>
<th>Lunes</th>
<th>Martes</th>
<th>Miércoles</th>
<th>Jueves</th>
<th>Viernes</th>
<th>Sábado</th>
<th>Domingo</th>
<th>Normales</th>
<th>Extras</th>
<th>Total</th>
</tr>
</thead>
<tbody>';

foreach ($pivot as $row) {
    $total_normales = number_format($row['total_normales'],1);
    $total_extras  = number_format($row['total_extras'],1);
    $total         = number_format($row['total_normales'] + $row['total_extras'],1);

    $html .= "<tr>
        <td>{$row['codigo_s']}</td>
        <td>{$row['empleado']}</td>
        <td>{$row['nombre_completo']}</td>
        <td>{$row['departamento']}</td>
        <td>{$row['lunes']}</td>
        <td>{$row['martes']}</td>
        <td>{$row['miercoles']}</td>
        <td>{$row['jueves']}</td>
        <td>{$row['viernes']}</td>
        <td>{$row['sabado']}</td>
        <td>{$row['domingo']}</td>
        <td style='text-align:center;'>{$total_normales}</td>
        <td style='text-align:center;'>{$total_extras}</td>
        <td style='text-align:center;'>{$total}</td>
    </tr>";
}

$html .= '</tbody></table>';

// ================================
// 7. Generar PDF
// ================================
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("reporte_asistencia.pdf", ["Attachment" => false]);
