<?php
require_once('lib/dompdf/autoload.inc.php');

use Dompdf\Dompdf;

if (isset($_GET['tipoReporte']) && isset($_GET['fechaInicio']) && isset($_GET['fechaFin'])) {
    $tipoReporte = $_GET['tipoReporte'];
    $fechaInicio = $_GET['fechaInicio'];
    $fechaFin = $_GET['fechaFin'];

    $reporte = new Reporte();
    $resultados = $reporte->obtener_reportes($tipoReporte, $fechaInicio, $fechaFin);

    if ($resultados['ok']) {
        $html = '<h1>Reporte de ' . ucfirst($tipoReporte) . '</h1>';
        $html .= '<p>Desde: ' . $fechaInicio . ' Hasta: ' . $fechaFin . '</p>';
        $html .= '<table border="1" style="width:100%; border-collapse: collapse;">';
        $html .= '<thead><tr><th>ID</th><th>Nombre</th><th>Detalles</th><th>Fecha</th></tr></thead>';
        $html .= '<tbody>';
        
        foreach ($resultados['reportes'] as $reporte) {
            $html .= '<tr>';
            $html .= '<td>' . $reporte['id'] . '</td>';
            $html .= '<td>' . $reporte['nombre'] . '</td>';
            $html .= '<td>' . $reporte['detalles'] . '</td>';
            $html .= '<td>' . $reporte['fecha'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('Reporte_' . $tipoReporte . '.pdf');
    } else {
        echo 'No se encontraron reportes';
    }
} else {
    echo 'Parámetros incompletos';
}
?>
