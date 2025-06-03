<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

function enviarError($mensaje, $codigo = 400)
{
   header('Content-Type: application/json');
   http_response_code($codigo);
   echo json_encode(['error' => $mensaje]);
   exit;
}

try {
   if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      enviarError('Método no permitido', 405);
   }

   // Obtener json
   $input = file_get_contents('php://input');
   $datos = json_decode($input, true);

   if (json_last_error() !== JSON_ERROR_NONE) {
      enviarError('Error al decodificar JSON: ' . json_last_error_msg());
   }

   if (!isset($datos['tipoReporte'])) {
      enviarError('Tipo de reporte no especificado');
   }

   $tipoReporte = $datos['tipoReporte'];
   $datosReporte = $datos['datos'] ?? null;
   $datosEstadisticas = $datos['estadisticas'] ?? null;
   $archivoReporte = $tipoReporte === 'reporteIndividualAtleta' ? 'individual_atleta' : $tipoReporte;

   require_once dirname(__DIR__) . '/src/view/reportes/reporte_template.php';
   require_once dirname(__DIR__) . "/src/view/reportes/reporte_{$archivoReporte}.php";

   if (!$datosReporte) {
      enviarError('Datos del reporte no proporcionados');
   }

   $options = new Options();
   $options->set('isHtml5ParserEnabled', true);
   $options->set('isRemoteEnabled', true);
   $options->set('isPhpEnabled', true);
   $options->set('defaultMediaType', 'print');
   $dompdf = new Dompdf($options);

   // Generar título del reporte
   $tituloReporte = $tipoReporte === 'reporteIndividualAtleta'
      ? "Atleta - {$datosReporte['reportes'][0]['nombre']} {$datosReporte['reportes'][0]['apellido']}"
      : $tipoReporte;

   // Generar HTML del reporte
   $html = obtenerHeaderHTML($tituloReporte);

   switch ($tipoReporte) {
      case 'reporteIndividualAtleta':
         $html .= generarReporteIndividualAtleta($datosReporte, $datosEstadisticas);
         break;
      default:
         $html .= generarReporte($datosReporte, $datosEstadisticas);
   }

   $html .= obtenerFooterHTML();
   // echo $html;
   // Generar PDF
   $dompdf->loadHtml($html);
   $dompdf->setPaper('A4', 'portrait');
   $dompdf->render();
   $dompdf->stream("Reporte_{$tipoReporte}.pdf", ['Attachment' => 0]);
} catch (Exception $e) {
   enviarError('Error al generar el reporte: ' . $e->getMessage(), 500);
}
