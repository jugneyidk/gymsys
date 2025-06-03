<?php

declare(strict_types=1);

function generarReporteIndividualAtleta($datosReporte, $datosEstadisticas): string
{
   $html = '';
   $datosAtleta = $datosReporte['reportes'][0] ?? null;
   $estadisticas = $datosEstadisticas ?? null;

   $html .= generarSeccionDatosPersonales($datosAtleta);
   // Sección de estadísticas
   if (!empty($datosEstadisticas['asistencias'])) {
      $html .= generarSeccionAsistencias($datosEstadisticas['asistencias']);
   }
   if (!empty($datosEstadisticas['competencias'])) {
      $html .= generarSeccionCompetencias($datosEstadisticas['competencias']);
   }
   if (isset($estadisticas['mensualidades'])) {
      $html .= generarSeccionMensualidades($estadisticas['mensualidades']);
   }
   if (isset($estadisticas['wada'])) {
      $html .= generarSeccionWADA($estadisticas['wada']);
   }
   return $html;
}
function generarSeccionDatosPersonales($datosAtleta): string
{
   return "<div class='seccion'>
            <h2>Información Personal</h2>
            <div class='datos-principales ficha-atleta'>
                <div class='dato'>
                    <strong>Cédula</strong>
                    <span>{$datosAtleta['cedula']}</span>
                </div>
                <div class='dato'>
                    <strong>Nombre Completo</strong>
                    <span>{$datosAtleta['nombre']} {$datosAtleta['apellido']}</span>
                </div>
                <div class='dato'>
                    <strong>Edad</strong>
                    <span>{$datosAtleta['edad']} años</span>
                </div>
                <div class='dato'>
                    <strong>Género</strong>
                    <span>{$datosAtleta['genero']}</span>
                </div>
                <div class='dato'>
                    <strong>Peso</strong>
                    <span>{$datosAtleta['peso']} kg</span>
                </div>
                <div class='dato'>
                    <strong>Altura</strong>
                    <span>{$datosAtleta['altura']} cm</span>
                </div>
                <div class='dato'>
                    <strong>Tipo de Atleta</strong>
                    <span>{$datosAtleta['tipo_atleta']}</span>
                </div>
            </div>
          </div>";
}

function generarSeccionAsistencias(array $asistencias): string
{
   if ($asistencias['total_asistencias'] <= 0) {
      return "<div class='seccion'>
               <h2>Registro de Asistencias</h2>
               <p>No se encontraron asistencias registradas para este atleta.</p>
            </div>";
   }
   $html = "<div class='seccion'>
            <h2>Registro de Asistencias</h2>
            <div class='datos-principales'>
                <div class='dato'>
                    <strong>Total Asistencias registradas</strong>
                    <span>{$asistencias['total_asistencias']}</span>
                </div>
                <div class='dato'>
                    <strong>Asistencias Cumplidas</strong>
                    <span>{$asistencias['asistencias_cumplidas']}</span>
                </div>
                <div class='dato'>
                    <strong>Porcentaje de Asistencia</strong>
                    <span>{$asistencias['porcentaje_asistencia']}%</span>
                </div>
                <div class='dato'>
                    <strong>Última Asistencia</strong>
                    <span>" . date('d/m/Y', strtotime($asistencias['ultima_asistencia'])) . "</span>
                </div>
            </div>
         </div>";

   // Gráfico de Asistencias
   $asistenciasData = [
      'type' => 'doughnut',
      'data' => [
         'labels' => ['Asistencias Cumplidas', 'Faltas'],
         'datasets' => [[
            'data' => [
               $asistencias['asistencias_cumplidas'],
               $asistencias['total_asistencias'] - $asistencias['asistencias_cumplidas']
            ],
            'backgroundColor' => [
               'rgba(0, 86, 179, 0.7)',
               'rgba(220, 53, 69, 0.7)'
            ]
         ]]
      ],
      'options' => [
         'plugins' => [
            'title' => [
               'display' => true,
               'text' => 'Asistencias vs Faltas'
            ],
            'datalabels' => [
               'color' => '#FFFFFF',
               'font' => [
                  'weight' => 'bold',
                  'size' => 14
               ]
            ]
         ]
      ]
   ];
   $asistenciasChartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($asistenciasData)) . '&w=400&h=200';
   $html .= '<div class="seccion">
             <h2>Análisis de Asistencia</h2>
             <div class="graficos">
                <img src="' . $asistenciasChartUrl . '" alt="Gráfico de Asistencias">
             </div>
    </div>';

   return $html;
}

function generarSeccionCompetencias(array $competencias): string
{
   if ($competencias['total_competencias'] <= 0) {
      return "<div class='seccion'>
               <h2>Historial de Competencias</h2>
               <p>No se encontraron competencias registradas para este atleta.</p>
            </div>";
   }
   $html = "<div class='seccion'>
            <h2>Historial de Competencias</h2>
            <div class='datos-principales'>
                <div class='dato'>
                    <strong>Total Competencias</strong>
                    <span>{$competencias['total_competencias']}</span>
                </div>
                <div class='dato'>
                    <strong>Total Medallas</strong>
                    <span>{$competencias['total_medallas']}</span>
                </div>
                <div class='dato'>
                    <strong>Medallas de Oro</strong>
                    <span>{$competencias['medallas_oro']}</span>
                </div>
                <div class='dato'>
                    <strong>Medallas de Plata</strong>
                    <span>{$competencias['medallas_plata']}</span>
                </div>
                <div class='dato'>
                    <strong>Medallas de Bronce</strong>
                    <span>{$competencias['medallas_bronce']}</span>
                </div>
                <div class='dato'>
                    <strong>Mejor Marca</strong>
                    <span>{$competencias['mejor_marca']} kg</span>
                </div>
                <div class='dato'>
                    <strong>Promedio Total</strong>
                    <span>{$competencias['promedio_total']} kg</span>
                </div>
            </div>
         </div>";

   // Gráficos de competencias
   $medallasData = [
      'type' => 'bar',
      'data' => [
         'labels' => ['Oro', 'Plata', 'Bronce'],
         'datasets' => [[
            'label' => 'Medallas Obtenidas',
            'data' => [
               $competencias['medallas_oro'],
               $competencias['medallas_plata'],
               $competencias['medallas_bronce']
            ],
            'backgroundColor' => [
               'rgba(255, 193, 7, 0.7)',  // Oro
               'rgba(158, 158, 158, 0.7)', // Plata
               'rgba(205, 127, 50, 0.7)'   // Bronce
            ]
         ]]
      ],
      'options' => [
         'plugins' => [
            'title' => [
               'display' => true,
               'text' => 'Distribución de Medallas'
            ]
         ],
         'scales' => [
            'y' => [
               'beginAtZero' => true
            ]
         ]
      ]
   ];

   $rendimientoData = [
      'type' => 'line',
      'data' => [
         'labels' => ['Mejor Marca', 'Promedio'],
         'datasets' => [[
            'label' => 'Rendimiento (kg)',
            'data' => [
               $competencias['mejor_marca'],
               $competencias['promedio_total']
            ],
            'fill' => false,
            'borderColor' => 'rgba(0, 86, 179, 1)',
            'tension' => 0.1
         ]]
      ],
      'options' => [
         'plugins' => [
            'title' => [
               'display' => true,
               'text' => 'Rendimiento en Competencias'
            ]
         ]
      ]
   ];

   $medallasChartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($medallasData)) . '&w=400&h=200';
   $rendimientoChartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($rendimientoData)) . '&w=400&h=200';

   $html .= '<div class="seccion">
             <h2>Gráficos de competencias</h2>
             <div class="graficos">
                <img src="' . $medallasChartUrl . '" alt="Gráfico de Medallas">
             </div>
             <div class="graficos">
                <img src="' . $rendimientoChartUrl . '" alt="Gráfico de Rendimiento">
             </div>
             </div>';

   return $html;
}

function generarSeccionMensualidades(array $mensualidades): string
{
   if ($mensualidades['total_pagos'] <= 0) {
      return "<div class='seccion'>
               <h2>Mensualidades</h2>
               <p>No se encontraron pagos de mensualidades para este atleta.</p>
            </div>";
   }
   return "<div class='seccion'>
            <h2>Control de Mensualidades</h2>
            <div class='datos-principales'>
                <div class='dato'>
                    <strong>Total Pagos</strong>
                    <span>{$mensualidades['total_pagos']}</span>
                </div>
                <div class='dato'>
                    <strong>Total Pagado</strong>
                    <span>\${$mensualidades['total_pagado']}</span>
                </div>
                <div class='dato'>
                    <strong>Promedio de Pago</strong>
                    <span>\${$mensualidades['promedio_pago']}</span>
                </div>
                <div class='dato'>
                    <strong>Último Pago</strong>
                    <span>" . date('d/m/Y', strtotime($mensualidades['ultimo_pago'])) . "</span>
                </div>
            </div>
         </div>";
}

function generarSeccionWADA(array|bool $wada): string
{
   if (!$wada) {
      return "<div class='seccion'>
               <h2>Estado WADA</h2>
               <p>No se encontraron datos de WADA para este atleta.</p>
            </div>";
   }
   $estadoColor = match ($wada['estado_actual']) {
      'Vigente' => '#28a745',
      'Por vencer' => '#ffc107',
      'Vencido' => '#dc3545',
      default => ''
   };
   return "<div class='seccion'>
            <h2>Estado WADA</h2>
            <div class='datos-principales'>
                <div class='dato'>
                    <strong>Estado Actual</strong>
                    <span style='color: {$estadoColor};'>{$wada['estado_actual']}</span>
                </div>
                <div class='dato'>
                    <strong>Fecha de Inscripción</strong>
                    <span>" . date('d/m/Y', strtotime($wada['inscrito'])) . "</span>
                </div>
                <div class='dato'>
                    <strong>Fecha de Vencimiento</strong>
                    <span>" . date('d/m/Y', strtotime($wada['vencimiento'])) . "</span>
                </div>
                <div class='dato'>
                    <strong>Última Actualización</strong>
                    <span>" . date('d/m/Y', strtotime($wada['ultima_actualizacion'])) . "</span>
                </div>
                <div class='dato'>
                    <strong>Días Restantes</strong>
                    <span>{$wada['dias_restantes']} días</span>
                </div>
            </div>
         </div>";
}
