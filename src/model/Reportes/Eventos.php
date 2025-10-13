<?php

namespace Gymsys\Model\Reportes;

use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;

class Eventos
{
   public static function obtenerReporteEventos(Database $database, array $filtros): array
   {
      if (empty($filtros['fechaInicio']) || empty($filtros['fechaFin'])) {
         ExceptionHandler::throwException("Las fechas de inicio y fin son requeridas para el reporte de eventos", \RuntimeException::class, 500);
      }

      $consulta = "SELECT 
                     c.id_competencia AS id,
                     c.nombre,
                     tc.nombre         AS tipo_competencia,
                     sb.nombre         AS sub,
                     ct.nombre         AS categoria,
                     c.fecha_inicio    AS fecha
                  FROM competencia c
                  JOIN tipo_competencia tc 
                     ON c.tipo_competicion = tc.id_tipo_competencia
                  JOIN subs sb
                     ON c.subs = sb.id_sub
                  JOIN categorias ct
                     ON c.categoria = ct.id_categoria
                  WHERE c.fecha_inicio 
                     BETWEEN :fechaInicio 
                        AND :fechaFin";
      $valores = [
         ':fechaInicio' => $filtros['fechaInicio'],
         ':fechaFin' => $filtros['fechaFin']
      ];
      $result = $database->query($consulta, $valores);
      return $result ?: [];
   }
   public static function obtenerEstadisticasEventos(Database $database, array $filtros): array
   {
      $consulta = "SELECT
                     COUNT(*) AS total_eventos,
                     COALESCE(SUM(CASE WHEN rc.medalla_arranque IS NOT NULL THEN 1 ELSE 0 END), 0)
                     + COALESCE(SUM(CASE WHEN rc.medalla_envion IS NOT NULL THEN 1 ELSE 0 END), 0)
                     + COALESCE(SUM(CASE WHEN rc.medalla_total IS NOT NULL THEN 1 ELSE 0 END), 0)
                  AS total_medallas
                  FROM competencia c
                  LEFT JOIN resultado_competencia rc
                     ON c.id_competencia = rc.id_competencia
                  WHERE 1=1";
      $valores = [];

      if (!empty($filtros['fechaInicioEventos']) && !empty($filtros['fechaFinEventos'])) {
         $consulta .= " AND c.fecha_inicio BETWEEN :fechaInicioEventos AND :fechaFinEventos";
         $valores[':fechaInicioEventos'] = $filtros['fechaInicioEventos'];
         $valores[':fechaFinEventos'] = $filtros['fechaFinEventos'];
      }
      $response = $database->query($consulta, $valores, true);
      $resultado['resumen_estadistico'] = $response ?: [];
      return $resultado ?: [];
   }
}
