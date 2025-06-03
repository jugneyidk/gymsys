<?php

declare(strict_types=1);

function generarReporte($datosReporte, $datosEstadisticas): string
{
   $html = '';

   // Sección de estadísticas
   if ($datosEstadisticas) {
      $html .= generarSeccionEstadisticasGenerales($datosEstadisticas);
   }

   // Sección de datos detallados
   if (isset($datosReporte['reportes']) && !empty($datosReporte['reportes'])) {
      $html .= generarSeccionDatosDetallados($datosReporte['reportes']);
   }

   // Sección de gráficos
   if (isset($datosEstadisticas)) {
      $html .= generarGraficos($datosEstadisticas);
   }

   return $html;
}

function generarSeccionEstadisticasGenerales($datosEstadisticas): string
{
   $html = '<div class="seccion">';
   $html .= '<h2>Estadísticas Generales</h2>';
   $html .= '<div class="estadisticas">';
   $html .= '<ul>';

   $totalStats = count($datosEstadisticas);
   if ($totalStats >= 4) {
      $html .= '<div class="datos-principales">';
      foreach ($datosEstadisticas as $clave => $valor) {
         if (!is_array($valor) && !is_object($valor)) {
            $html .= '<div class="dato">
                    <strong>' . ucfirst(str_replace('_', ' ', $clave)) . '</strong>
                    <span>' . htmlspecialchars((string)$valor) . '</span>
                </div>';
         }
      }
      $html .= '</div>';
   } else {
      foreach ($datosEstadisticas['resumen_estadistico'] as $clave => $valor) {
         $html .= '<li><strong>' . ucfirst(str_replace('_', ' ', $clave)) . '</strong> 
                  <span class="dato-badge">' . htmlspecialchars((string)$valor) . '</span></li>';
      }
   }
   $html .= '</ul></div></div>';
   return $html;
}

function generarSeccionDatosDetallados($reportes): string
{
   $cabeceras = array_map(function ($cabecera) {
      return ucfirst(str_replace('_', ' ', $cabecera));
   }, array_keys($reportes[0]));

   $html = '<div class="seccion">';
   $html .= '<h2>Datos Detallados</h2>';
   $html .= '<table>';
   $html .= '<thead><tr>';

   foreach ($cabeceras as $cabecera) {
      $html .= '<th>' . htmlspecialchars($cabecera) . '</th>';
   }
   $html .= '</tr></thead>';
   $html .= '<tbody>';

   foreach ($reportes as $reporte) {
      $html .= '<tr>';
      foreach ($reporte as $valor) {
         $valor ??= '';
         $html .= '<td>' . htmlspecialchars((string) $valor) . '</td>';
      }
      $html .= '</tr>';
   }

   $html .= '</tbody></table></div>';
   return $html;
}

function generarGraficos(array $datosEstadisticas): string
{
   $html = '<div class="seccion">';
   $html .= '<h2>Gráficos de Estadísticas</h2>';
   $html .= '<div class="graficos d-flex flex-wrap gap-4">';

   // 1) Gráfico principal con valores escalares
   $scalarStats = array_filter(
      $datosEstadisticas['resumen_estadistico'],
      fn($v) => !is_array($v)
   );
   if (!empty($scalarStats)) {
      $html .= generarChartHtml(
         $scalarStats,
         'Resumen Estadístico'
      );
   }
   $html .= '</div>';

   $html .= '<div class="graficos d-flex flex-wrap gap-4">';

   // 2) Gráfico de mensualidades_por_mes si existe
   if (!empty($datosEstadisticas['mensualidades_por_mes']) && is_array($datosEstadisticas['mensualidades_por_mes'])) {
      $datosMensuales = $datosEstadisticas['mensualidades_por_mes'];
      $labels    = array_map(fn($item) => $item['mes'], $datosMensuales);
      $counts    = array_map(fn($item) => (int)$item['total_mensualidades'], $datosMensuales);
      $totals    = array_map(fn($item) => (float)$item['total_recaudado'], $datosMensuales);

      $datasets = [
         ['label' => 'Total de pagos', 'data' => $counts],
         ['label' => 'Total Recaudado',     'data' => $totals],
      ];

      $html .= generarMultiDatasetChartHtml(
         $labels,
         $datasets,
         'Mensualidades por mes'
      );
   }

   $html .= '</div></div>';
   return $html;
}
function generarChartHtml(array $stats, string $chartLabel, string $chartType = 'bar'): string
{
   $labels = array_map(
      fn($lbl) => ucfirst(str_replace('_', ' ', $lbl)),
      array_keys($stats)
   );
   $data   = array_values($stats);

   $chartConfig = [
      'type' => $chartType,
      'data' => [
         'labels'   => $labels,
         'datasets' => [[
            'label'           => $chartLabel,
            'data'            => $data,
            'backgroundColor' => 'rgba(0, 86, 179, 0.7)',
            'borderColor'     => 'rgba(0, 86, 179, 1)',
            'borderWidth'     => 1,
         ]],
      ],
   ];

   $chartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($chartConfig));

   return sprintf(
      '<div class="grafico-item text-center"><h3>%s</h3><img src="%s" alt="%s"></div>',
      htmlspecialchars($chartLabel, ENT_QUOTES),
      $chartUrl,
      htmlspecialchars($chartLabel, ENT_QUOTES)
   );
}
function generarMultiDatasetChartHtml(array $labels, array $datasetsConfig, string $chartLabel, string $chartType = 'bar'): string
{
   // Paleta de colores
   $palette = [
      'rgba(0, 86, 179, 0.7)',
      'rgba(0, 137, 123, 0.7)',
      'rgba(144, 202, 249, 0.7)',
      'rgba(128, 203, 196, 0.7)',
   ];

   $datasets = [];
   foreach ($datasetsConfig as $idx => $conf) {
      $color = $palette[$idx % count($palette)];
      $datasets[] = [
         'label'           => $conf['label'],
         'data'            => $conf['data'],
         'backgroundColor' => $color,
         'borderColor'     => str_replace('0.7', '1', $color),
         'borderWidth'     => 1,
      ];
   }

   $chartConfig = [
      'type' => $chartType,
      'data' => [
         'labels'   => $labels,
         'datasets' => $datasets,
      ],
   ];

   $chartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($chartConfig));

   return sprintf(
      '<div class="grafico-item text-center"><h3>%s</h3><img src="%s" alt="%s"></div>',
      htmlspecialchars($chartLabel, ENT_QUOTES),
      $chartUrl,
      htmlspecialchars($chartLabel, ENT_QUOTES)
   );
}
