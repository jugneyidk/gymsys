<?php
require_once('lib/dompdf/vendor/autoload.php');
require_once('modelo/reportes.php');

use Dompdf\Dompdf;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipoReporte = $_POST['tipoReporte'];
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
        'periodoMensualidades' => $_POST['periodoMensualidades'] ?? null,
        'periodoWada' => $_POST['periodoWada'] ?? null,
        'periodoEventos' => $_POST['periodoEventos'] ?? null,
        'periodoAsistencias' => $_POST['periodoAsistencias'] ?? null,
    ];

    $reporte = new Reporte();
    $resultados = $reporte->obtener_reportes($tipoReporte, $filtros);

    if ($resultados['ok']) {
        $reportes = $resultados['reportes'];

        $html = '<h1>Reporte de ' . ucfirst($tipoReporte) . '</h1>';
        $html .= '<table border="1" style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr>';
        foreach (array_keys($reportes[0]) as $columna) {
            $html .= '<th style="padding: 8px;">' . $columna . '</th>';
        }
        $html .= '    </tr>
                    </thead>
                    <tbody>';
        foreach ($reportes as $fila) {
            $html .= '<tr>';
            foreach ($fila as $columna) {
                $html .= '<td style="padding: 8px;">' . $columna . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '  </tbody>
                  </table>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('reporte.pdf', ['Attachment' => 1]);
    }     else {
        echo "Error: " . $resultados['mensaje'];
    }
} else {
    echo "Método no permitido";
}
?>
