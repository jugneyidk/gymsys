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
    if (isset($_POST['tipoReporte']) && $_POST['tipoReporte'] === 'reporteIndividualAtleta') {
        $idAtleta = $_POST['idAtleta']; 

        $reporte = new Reporte();
        $resultado = $reporte->obtener_reporte_individual(['idAtleta' => $idAtleta]);

        if ($resultado['ok']) {
            $reporteData = $resultado['reporte'];
            $html = "
                <h1>Reporte de Atleta Individual</h1>
                <h2>{$reporteData['nombre']}</h2>
                <p><strong>Cédula:</strong> {$reporteData['cedula']}</p>
                <p><strong>Peso:</strong> {$reporteData['peso']} kg</p>
                <p><strong>Altura:</strong> {$reporteData['altura']} cm</p>
                <p><strong>Edad:</strong> {$reporteData['promedio_edad']} años</p>
                <p><strong>Total de Competiciones:</strong> {$reporteData['total_competiciones']}</p>
            ";
            
            // Crear el PDF usando Dompdf
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->render();
            $dompdf->stream("reporte_individual_atleta.pdf");
        }
    }else if (isset($_POST['tipoReporte'])) {
        $tipoReporte = htmlspecialchars($_POST['tipoReporte']);
        $filtros = [
            'edadMin' => $_POST['edadMin'] ?? null,
            'edadMax' => $_POST['edadMax'] ?? null,
            'genero' => $_POST['genero'] ?? null,
            'tipoAtleta' => $_POST['tipoAtleta'] ?? null,
            'pesoMin' => $_POST['pesoMin'] ?? null,
            'pesoMax' => $_POST['pesoMax'] ?? null,
            'edadMinEntrenador' => $_POST['edadMinEntrenador'] ?? null,
            'edadMaxEntrenador' => $_POST['edadMaxEntrenador'] ?? null,
            'gradoInstruccion' => $_POST['gradoInstruccion'] ?? null,
            'fechaInicioEventos' => $_POST['fechaInicioEventos'] ?? null,
            'fechaFinEventos' => $_POST['fechaFinEventos'] ?? null,
            'fechaInicioMensualidades' => $_POST['fechaInicioMensualidades'] ?? null,
            'fechaFinMensualidades' => $_POST['fechaFinMensualidades'] ?? null,
        ];

        $reporte = new Reporte();
        $resultados = $reporte->obtener_reportes($tipoReporte, $filtros);

        if ($resultados['ok']) {
            $estadisticas = $reporte->obtenerEstadisticas($tipoReporte, $filtros);

            $html = '
                <style>
                    body { font-family: "Arial", sans-serif; margin: 40px; color: #333; }
                    h1 { text-align: center; font-size: 24px; margin-bottom: 20px; color: #0056b3; }
                    h2 { font-size: 20px; margin: 30px 0 15px; color: #333; border-bottom: 2px solid #0056b3; padding-bottom: 5px; }
                    .seccion { margin: 30px 0; }
                    .estadisticas { padding: 15px; background-color: #f9f9f9; border-radius: 8px; margin-bottom: 20px; }
                    ul { font-size: 14px; line-height: 1.6; color: #444; margin: 0; padding-left: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 14px; }
                    th { background-color: #f2f2f2; color: #333; font-weight: bold; }
                    tr:nth-child(even) { background-color: #f9f9f9; }
                    tr:hover { background-color: #e6f7ff; }
                    img { display: block; max-width: 100%; height: auto; margin: 20px auto; }
                </style>
            ';

            $html .= '<h1>Reporte de ' . ucfirst($tipoReporte) . '</h1>';

            if ($estadisticas['ok']) {
                $html .= '<div class="seccion">';
                $html .= '<h2>Estadísticas Generales</h2>';
                $html .= '<div class="estadisticas"><ul>';

                foreach ($estadisticas['estadisticas'] as $clave => $valor) {
                    $html .= '<li><strong>' . ucfirst(str_replace('_', ' ', $clave)) . ':</strong> ' . htmlspecialchars($valor) . '</li>';
                }

                $html .= '</ul></div></div>';
            }

            $html .= '<div class="seccion">';
            $html .= '<h2>Datos Detallados</h2>';
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
            $html .= '</div>';

            if ($estadisticas['ok']) {
                $chartData = [
                    'type' => 'bar',
                    'data' => [
                        'labels' => array_keys($estadisticas['estadisticas']),
                        'datasets' => [
                            [
                                'label' => 'Estadísticas',
                                'data' => array_values($estadisticas['estadisticas']),
                                'backgroundColor' => 'rgba(75, 192, 192, 0.5)',
                                'borderColor' => 'rgba(75, 192, 192, 1)',
                                'borderWidth' => 1
                            ]
                        ]
                    ]
                ];

                $chartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($chartData));
                $html .= '<div class="seccion">';
                $html .= '<h2>Gráfico de Estadísticas</h2>';
                $html .= '<img src="' . $chartUrl . '" alt="Gráfico de Estadísticas">';
                $html .= '</div>';
            }

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream('Reporte_' . $tipoReporte . '.pdf', ['Attachment' => 0]);
        } else {
            echo 'No se encontraron reportes.';
        }
    } else {
        echo 'Parámetros incompletos.';
    }
} else {
    echo 'Método no permitido.';
}
