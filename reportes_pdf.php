<?php
require_once('lib/dompdf/vendor/autoload.php');
require_once('modelo/reportes.php');

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('isPhpEnabled', true); 
$dompdf = new Dompdf($options);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tipoReporte'])) {
        $tipoReporte = htmlspecialchars($_POST['tipoReporte']);
        $filtros = [
            'edadMin' => isset($_POST['edadMin']) ? htmlspecialchars($_POST['edadMin']) : null,
            'edadMax' => isset($_POST['edadMax']) ? htmlspecialchars($_POST['edadMax']) : null,
            'genero' => isset($_POST['genero']) ? htmlspecialchars($_POST['genero']) : null,
            'tipoAtleta' => isset($_POST['tipoAtleta']) ? htmlspecialchars($_POST['tipoAtleta']) : null,
            'pesoMin' => isset($_POST['pesoMin']) ? htmlspecialchars($_POST['pesoMin']) : null,
            'pesoMax' => isset($_POST['pesoMax']) ? htmlspecialchars($_POST['pesoMax']) : null,
            'edadMinEntrenador' => isset($_POST['edadMinEntrenador']) ? htmlspecialchars($_POST['edadMinEntrenador']) : null,
            'edadMaxEntrenador' => isset($_POST['edadMaxEntrenador']) ? htmlspecialchars($_POST['edadMaxEntrenador']) : null,
            'gradoInstruccion' => isset($_POST['gradoInstruccion']) ? htmlspecialchars($_POST['gradoInstruccion']) : null,
            'fechaInicioEventos' => isset($_POST['fechaInicioEventos']) ? htmlspecialchars($_POST['fechaInicioEventos']) : null,
            'fechaFinEventos' => isset($_POST['fechaFinEventos']) ? htmlspecialchars($_POST['fechaFinEventos']) : null,
            'fechaInicioMensualidades' => isset($_POST['fechaInicioMensualidades']) ? htmlspecialchars($_POST['fechaInicioMensualidades']) : null,
            'fechaFinMensualidades' => isset($_POST['fechaFinMensualidades']) ? htmlspecialchars($_POST['fechaFinMensualidades']) : null,
        ];

        $reporte = new Reporte();
        $resultados = $reporte->obtener_reportes($tipoReporte, $filtros);

        if ($resultados['ok']) {
            $estadisticas = $reporte->obtenerEstadisticas($tipoReporte, $filtros);
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

            $labels = [];
            $data = [];

            if ($estadisticas['ok']) {
                $html .= '<h2>Estadísticas</h2>';
                $html .= '<ul>';

                foreach ($estadisticas['estadisticas'] as $clave => $valor) {
                    $html .= '<li>' . ucfirst(str_replace('_', ' ', $clave)) . ': ' . htmlspecialchars($valor) . '</li>';
                    $labels[] = ucfirst(str_replace('_', ' ', $clave)); 
                    $data[] = $valor;  
                }

                $html .= '</ul>';

                $chartUrl = 'https://quickchart.io/chart?c={type:\'bar\',data:{labels:[' . implode(',', array_map(function ($label) {
                    return '\'' . $label . '\'';
                }, $labels)) . '],datasets:[{label:\'Estadísticas\',data:[' . implode(',', $data) . ']}]}}';

                $html .= '<img src="' . $chartUrl . '" alt="Estadísticas del reporte" style="width:100%; max-width:600px; margin-top:20px;">';
          
            }

            $html .= '<h2>Datos</h2>';
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
} else {
    echo 'Método no permitido';
}
