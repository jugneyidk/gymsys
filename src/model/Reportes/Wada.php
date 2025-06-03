<?php

namespace Gymsys\Model\Reportes;

use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;

class Wada
{
   public static function obtenerReporteWada(Database $database, array $filtros): array
   {
      $consulta = "SELECT w.id_atleta AS cedula, 
                     CONCAT(u.nombre, ' ', u.apellido) AS nombre,
                     CASE WHEN w.estado = 1 THEN 'Cumple' ELSE 'No Cumple' END AS cumple_requisitos,
                     w.vencimiento AS fecha,
                     w.ultima_actualizacion AS ultima_actualizacion,
                     CASE 
                        WHEN w.vencimiento < CURDATE() THEN 'Vencido'
                        WHEN w.vencimiento <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'Por vencer'
                        ELSE 'Vigente'
                     END AS estado_actual
                  FROM wada w
                  INNER JOIN atleta a ON w.id_atleta = a.cedula
                  INNER JOIN {$_ENV['SECURE_DB']}.usuarios u ON a.cedula = u.cedula
                  WHERE 1=1";
      $valores = [];

      if (!empty($filtros['fechaInicio']) && !empty($filtros['fechaFin'])) {
         $consulta .= " AND w.vencimiento BETWEEN :fechaInicio AND :fechaFin";
         $valores[':fechaInicio'] = $filtros['fechaInicio'];
         $valores[':fechaFin'] = $filtros['fechaFin'];
      }

      $result = $database->query($consulta, $valores);
      if (!$result) {
         ExceptionHandler::throwException("No se encontraron registros WADA con los filtros especificados", 404, \RuntimeException::class);
      }
      return $result;
   }

   public static function obtenerEstadisticasWada(Database $database, array $filtros): array
   {
      $consulta = "SELECT 
                     COUNT(*) AS total_wada,
                     COUNT(CASE WHEN w.estado = 1 THEN 1 ELSE NULL END) AS atletas_cumplen,
                     COUNT(CASE WHEN w.estado = 0 THEN 1 ELSE NULL END) AS atletas_no_cumplen
                  FROM wada w
                  INNER JOIN atleta a ON w.id_atleta = a.cedula
                  INNER JOIN {$_ENV['SECURE_DB']}.usuarios u ON a.cedula = u.cedula
                  WHERE 1=1";
      $valores = [];
      if (!empty($filtros['fechaInicio']) && !empty($filtros['fechaFin'])) {
         $consulta .= " AND w.vencimiento BETWEEN :fechaInicio AND :fechaFin";
         $valores[':fechaInicio'] = $filtros['fechaInicio'];
         $valores[':fechaFin'] = $filtros['fechaFin'];
      }
      $response = $database->query($consulta, $valores, true);
      $resultado['resumen_estadistico'] = $response ?: [];
      return $resultado;
   }
}
