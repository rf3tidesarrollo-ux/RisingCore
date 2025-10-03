<?php
require_once '../../../Librerias/dompdf/autoload.inc.php';
use Dompdf\Dompdf;

// Conexión a la base de datos
$host = "192.168.1.23";
$db   = "cecilia";
$user = "vega";
$pass = "123456";

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die("Conexión fallida: " . $mysqli->connect_error);
}

// Filtros
$ano = $_GET['ano'] ?? date('Y');         
$semana = $_GET['semana'] ?? date('W');   
$departamento = $_GET['departamento'] ?? 'Empaque';

// Calcular fechas de la semana (lunes a domingo)
$dto = new DateTime();
$dto->setISODate($ano, $semana); 
$week = [];
for ($i = 0; $i < 7; $i++) {
    $week[] = $dto->format('Y-m-d');
    $dto->modify('+1 day');
}

// Obtener empleados del departamento
$sqlEmpleados = "
    SELECT badge, tipo, empleado, nombre, area
    FROM tbl_personal
    WHERE area = ? AND estatus = 'Alta'
    ORDER BY nombre
";
$stmt = $mysqli->prepare($sqlEmpleados);
$stmt->bind_param("s", $departamento);
$stmt->execute();
$resultEmpleados = $stmt->get_result();
$empleados = $resultEmpleados->fetch_all(MYSQLI_ASSOC);

// Preparar listado de badges
$badges = array_column($empleados, 'badge');
$in  = "'" . implode("','", $badges) . "'";

// Traer todos los registros de la semana en una sola consulta
$sqlCheck = "
    SELECT badge, DATE(registro) as dia,
           MIN(registro) as entrada,
           MAX(registro) as salida
    FROM tbl_check
    WHERE badge IN ($in)
      AND registro BETWEEN '{$week[0]} 00:00:00' AND '{$week[6]} 23:59:59'
    GROUP BY badge, DATE(registro)
";
$resultCheck = $mysqli->query($sqlCheck);

// Mapear registros en memoria
$checadas = [];
while ($row = $resultCheck->fetch_assoc()) {
    $checadas[$row['badge']][$row['dia']] = [
        'entrada' => $row['entrada'],
        'salida'  => $row['salida']
    ];
}

// Construir la tabla HTML
$html = "
<h1 style='text-align:center;'>Asistencia Semana $semana del $ano</h1>
<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; width: 100%;'>
<tr style='background-color: #49df3cff; text-align:center;'>
    <th>No.</th>
    <th>Empleado</th>
    <th>Nombre</th>
    <th>Área</th>
    <th>Lunes</th>
    <th>Martes</th>
    <th>Miércoles</th>
    <th>Jueves</th>
    <th>Viernes</th>
    <th>Sábado</th>
    <th>Domingo</th>
</tr>";

$contador = 1;
foreach ($empleados as $emp) {
    $html .= "<tr>";
    $html .= "<td style='text-align:center'>{$contador}</td>";
    $html .= "<td style='text-align:center'>{$emp['empleado']}</td>";
    $html .= "<td>{$emp['nombre']}</td>";
    $html .= "<td style='text-align:center'>{$emp['area']}</td>"; // Nueva columna Área
    foreach ($week as $dia) {
        if (isset($checadas[$emp['badge']][$dia])) {
            $e = substr($checadas[$emp['badge']][$dia]['entrada'], 11, 5);
            $s = substr($checadas[$emp['badge']][$dia]['salida'], 11, 5);
            $html .= "<td style='text-align:center'>" . ($e != $s ? "$e - $s" : $e) . "</td>";
        } else {
            $html .= "<td style='text-align:center'>-</td>";
        }
    }
    $html .= "</tr>";
    $contador++;
}
$html .= "</table>";

// Generar PDF con Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("Asistencia_Semana_{$semana}_{$ano}.pdf", ["Attachment" => false]);

?>
