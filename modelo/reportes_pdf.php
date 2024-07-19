<?php
require 'vendor/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Obtener los datos del reporte desde la base de datos
    require_once("modelo/reportes.php");
    $reporte = new Reporte();

    // Obtener los parámetros de tipoReporte, fechaInicio y fechaFin desde la URL
    $tipoReporte = $_GET['tipoReporte'] ?? 'atletas';
    $fechaInicio = $_GET['fechaInicio'] ?? '2024-01-01';
    $fechaFin = $_GET['fechaFin'] ?? '2024-12-31';

    $datos = $reporte->obtener_reportes($tipoReporte, $fechaInicio, $fechaFin);

    // Crear contenido HTML para el PDF
    $html = '<h1>Reporte de ' . ucfirst($tipoReporte) . '</h1>';
    if ($datos['ok']) {
        $html .= '<table border="1" cellspacing="0" cellpadding="5">';
        $html .= '<thead><tr><th>ID</th><th>Nombre</th><th>Detalles</th><th>Fecha</th></tr></thead><tbody>';
        foreach ($datos['reportes'] as $reporte) {
            $html .= '<tr>';
            $html .= '<td>' . $reporte['id'] . '</td>';
            $html .= '<td>' . $reporte['nombre'] . '</td>';
            $html .= '<td>' . $reporte['detalles'] . '</td>';
            $html .= '<td>' . $reporte['fecha'] . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
    } else {
        $html .= '<p>No se encontraron reportes.</p>';
    }

    // Configuración de DOMPDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    // Enviar el PDF al navegador
    $dompdf->stream('reporte_' . $tipoReporte . '.pdf', array("Attachment" => false));
}
?>
