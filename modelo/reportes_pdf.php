<?php
require_once('lib/dompdf/autoload.inc.php');
use Dompdf\Dompdf;
use Dompdf\Options;

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

            if ($tipoReporte == 'eventos') {
                $resultadosCompetencias = $reporte->obtener_resultados_competencias($filtros);
                if ($resultadosCompetencias['ok']) {
                    $html .= '<h2>Resultados de Competencias</h2>';
                    $html .= '<table>';
                    $html .= '<thead><tr><th>Evento</th><th>Atleta</th><th>Arranque</th><th>Envion</th><th>Total</th></tr></thead>';
                    $html .= '<tbody>';
                    foreach ($resultadosCompetencias['resultados'] as $resultado) {
                        $html .= '<tr>';
                        $html .= '<td>' . htmlspecialchars($resultado['nombreEvento']) . '</td>';
                        $html .= '<td>' . htmlspecialchars($resultado['nombreAtleta']) . '</td>';
                        $html .= '<td>' . htmlspecialchars($resultado['arranque']) . '</td>';
                        $html .= '<td>' . htmlspecialchars($resultado['envion']) . '</td>';
                        $html .= '<td>' . htmlspecialchars($resultado['total']) . '</td>';
                        $html .= '</tr>';
                    }
                    $html .= '</tbody></table>';

                    $chartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode([
                        'type' => 'bar',
                        'data' => [
                            'labels' => array_column($resultadosCompetencias['resultados'], 'nombreAtleta'),
                            'datasets' => [
                                [
                                    'label' => 'Total',
                                    'data' => array_column($resultadosCompetencias['resultados'], 'total'),
                                    'backgroundColor' => '#3e95cd',
                                ]
                            ]
                        ],
                        'options' => [
                            'title' => [
                                'display' => true,
                                'text' => 'Resultados Totales de Competencias'
                            ]
                        ]
                    ]));
                    $html .= '<h3>Gráfico de Resultados Totales</h3>';
                    $html .= '<img src="' . $chartUrl . '" alt="Gráfico de Resultados Totales">';
                }
            }

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);
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
