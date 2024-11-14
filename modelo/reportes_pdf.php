<?php
require_once('lib/dompdf/autoload.inc.php');

use Dompdf\Dompdf;

if (isset($_GET['tipoReporte']) && isset($_GET['fechaInicio']) && isset($_GET['fechaFin'])) {
    $tipoReporte = htmlspecialchars($_GET['tipoReporte']);
    $fechaInicio = htmlspecialchars($_GET['fechaInicio']);
    $fechaFin = htmlspecialchars($_GET['fechaFin']);

    $fechaInicioValida = DateTime::createFromFormat('Y-m-d', $fechaInicio) !== false;
    $fechaFinValida = DateTime::createFromFormat('Y-m-d', $fechaFin) !== false;

    if (!$fechaInicioValida || !$fechaFinValida) {
        echo 'Formato de fecha inválido';
        exit;
    }

    $reporte = new Reporte();
    $resultados = $reporte->obtener_reportes($tipoReporte, $fechaInicio, $fechaFin);

    if ($resultados['ok']) {
        $html = '<style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    h1 { text-align: center; margin-bottom: 20px; }
                    p { font-size: 14px; margin: 5px 0; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    tr:nth-child(even) { background-color: #f9f9f9; }
                    tr:hover { background-color: #f1f1f1; }
                </style>';

        $html .= '<h1>Reporte de ' . ucfirst($tipoReporte) . '</h1>';
        $html .= '<p>Desde: ' . $fechaInicio . ' | Hasta: ' . $fechaFin . '</p>';
        $html .= '<table>';
        $html .= '<thead><tr><th>ID</th><th>Nombre</th><th>Detalles</th><th>Fecha</th></tr></thead>';
        $html .= '<tbody>';

        foreach ($resultados['reportes'] as $reporte) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($reporte['id']) . '</td>';
            $html .= '<td>' . htmlspecialchars($reporte['nombre']) . '</td>';
            $html .= '<td>' . htmlspecialchars($reporte['detalles']) . '</td>';
            $html .= '<td>' . htmlspecialchars($reporte['fecha']) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('Reporte_' . $tipoReporte . '.pdf', ['Attachment' => 0]);
    } else {
        echo 'No se encontraron reportes';
    }
} else {
    echo 'Parámetros incompletos';
}
?>
