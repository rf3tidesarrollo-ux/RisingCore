<?php
require_once('libs/tcpdf/tcpdf.php');

    function generarPDFMezcla($id_mezcla, $Con) {
        // Consulta datos de mezcla y partidas según id_mezcla
        $stmt = $Con->prepare("SELECT * FROM mezclas WHERE id_mezcla = ?");
        $stmt->bind_param("i", $id_mezcla);
        $stmt->execute();
        $mezcla = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        // Consulta partidas o detalles
        $stmt2 = $Con->prepare("SELECT ml.*, re.nave, v.nombre_variedad FROM mezcla_partidas ml INNER JOIN registro_empaque re ON ml.id_lote = re.id_registro_r INNER JOIN variedades v ON re.id_variedad = v.id_nombre_v WHERE ml.id_mezcla = ?");
        $stmt2->bind_param("i", $id_mezcla);
        $stmt2->execute();
        $partidas = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt2->close();

        // Crear PDF con TCPDF
        $pdf = new TCPDF();
        $pdf->AddPage();

        // Título
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Mezcla ID: '.$id_mezcla, 0, 1, 'C');

        // Información general
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 8, 'Fecha: ' . $mezcla['fecha'], 0, 1);
        $pdf->Cell(0, 8, 'Descripción: ' . $mezcla['descripcion'], 0, 1);

        // Tabla partidas
        $html = '<table border="1" cellpadding="5"><thead><tr><th>%</th><th>Nave</th><th>Variedad</th><th>Cajas</th><th>Kilos</th></tr></thead><tbody>';

        $total_kilos = 0;
        foreach ($partidas as $p) {
            $total_kilos += $p['kilos_m'];
        }

        foreach ($partidas as $p) {
            $porcentaje = round(($p['kilos_m'] / $total_kilos) * 100, 2);
            $html .= '<tr>
                <td align="center">'.$porcentaje.'%</td>
                <td align="center">'.$p['nave'].'</td>
                <td>'.$p['nombre_variedad'].'</td>
                <td align="right">'.$p['cajas_m'].'</td>
                <td align="right">'.number_format($p['kilos_m'], 2).'</td>
            </tr>';
        }

        $html .= '</tbody></table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        // Guardar PDF en carpeta, por ejemplo /pdfs/mezclas/
        $carpeta = __DIR__ . '/pdfs/mezclas/';
        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $nombreArchivo = 'mezcla_'.$id_mezcla.'_'.date('Ymd_His').'.pdf';
        $rutaArchivo = $carpeta . $nombreArchivo;

        $pdf->Output($rutaArchivo, 'F'); // Guarda el archivo

        return $nombreArchivo; // Retorna solo el nombre para guardar en DB
    }
?>