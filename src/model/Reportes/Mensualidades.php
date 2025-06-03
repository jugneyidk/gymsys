<?php

namespace Gymsys\Model\Reportes;

use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;

class Mensualidades
{
   public static function obtenerReporteMensualidades(Database $database, array $filtros): array
   {
      if (empty($filtros['fechaInicio']) || empty($filtros['fechaFin'])) {
         ExceptionHandler::throwException("Las fechas de inicio y fin son requeridas para el reporte de mensualidades", 400, \InvalidArgumentException::class);
      }

      $consulta = "SELECT
                     a.cedula AS cedula,
                     CONCAT(u.nombre, ' ', u.apellido) AS nombre,
                     FORMAT(m.monto, 2) AS monto,
                     DATE_FORMAT(m.fecha, '%d/%m/%Y') AS fecha
                  FROM mensualidades m
                  JOIN atleta a  ON m.id_atleta = a.cedula
                  JOIN {$_ENV['SECURE_DB']}.usuarios u ON a.cedula   = u.cedula
                  WHERE m.fecha BETWEEN :fechaInicio AND :fechaFin;";
      $valores = [
         ':fechaInicio' => $filtros['fechaInicio'],
         ':fechaFin' => $filtros['fechaFin']
      ];

      $response = $database->query($consulta, $valores);
      if (!$response) {
         ExceptionHandler::throwException("No se encontraron mensualidades en el período especificado", 404, \RuntimeException::class);
      }
      return $response;
   }
   public static function obtenerEstadisticasMensualidades(Database $database, array $filtros): array
   {
      $consulta = "SELECT
                     COUNT(*) AS total_mensualidades,
                     COUNT(DISTINCT m.id_atleta) AS atletas_unicos,
                     SUM(m.monto) AS total_recaudado,
                     ROUND(AVG(m.monto),2) AS monto_promedio
                  FROM mensualidades m
                  INNER JOIN atleta a ON m.id_atleta = a.cedula
                  INNER JOIN {$_ENV['SECURE_DB']}.usuarios u ON a.cedula = u.cedula
                  WHERE m.fecha BETWEEN :fechaInicio AND :fechaFin";
      $valores = [
         ":fechaInicio" => $filtros['fechaInicio'],
         ":fechaFin" => $filtros['fechaFin']
      ];
      $response = $database->query($consulta, $valores, true);
      if (empty($response)) {
         ExceptionHandler::throwException("No se encontraron mensualidades en el período especificado", 404, \RuntimeException::class);
      }
      $resultado['resumen_estadistico'] = $response;
      $consultaPorMes = "SELECT
                           DATE_FORMAT(m.fecha, '%m-%Y')      AS mes,
                           COUNT(*)                          AS total_mensualidades,
                           SUM(m.monto)                      AS total_recaudado
                        FROM mensualidades m
                        WHERE m.fecha BETWEEN :fechaInicio AND :fechaFin
                        GROUP BY mes
                        ORDER BY mes;";
      $responseMes = $database->query($consultaPorMes, $valores);
      $resultado['mensualidades_por_mes'] = $responseMes ?: [];
      return $resultado;
   }
}
