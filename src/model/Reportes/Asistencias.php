<?php

namespace Gymsys\Model\Reportes;

use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;

class Asistencias
{
   public static function obtenerReporteAsistencias(Database $database, array $filtros): array
   {
      if (empty($filtros['fechaInicio']) || empty($filtros['fechaFin'])) {
         ExceptionHandler::throwException("Las fechas de inicio y fin son requeridas para el reporte de asistencias", \InvalidArgumentException::class, 404);
      }

      $consulta = "SELECT CONCAT(u.nombre, ' ', u.apellido) AS nombre,
                     CASE WHEN a.asistio = 1 THEN 'Sí' ELSE 'No' END AS asistio,
                     a.fecha AS fecha
                  FROM asistencias a
                  INNER JOIN atleta at ON a.id_atleta = at.cedula
                  INNER JOIN {$_ENV['SECURE_DB']}.usuarios u ON at.cedula = u.cedula
                  WHERE a.fecha BETWEEN :fechaInicio AND :fechaFin";

      $valores = [
         ':fechaInicio' => $filtros['fechaInicio'],
         ':fechaFin' => $filtros['fechaFin']
      ];

      $response = $database->query($consulta, $valores);
      if (!$response) {
         ExceptionHandler::throwException("No se encontraron asistencias en el período especificado", \RuntimeException::class, 500);
      }
      return $response;
   }
   public static function obtenerEstadisticasAsistencias(Database $database, array $filtros): array
   {
      $consulta = "SELECT 
                     COUNT(*) AS total_asistencias,
                     COUNT(DISTINCT a.id_atleta) AS atletas_presentes
                  FROM asistencias a
                  INNER JOIN atleta at ON a.id_atleta = at.cedula
                  INNER JOIN {$_ENV['SECURE_DB']}.usuarios u ON at.cedula = u.cedula
                  WHERE a.asistio = 1";
      $valores = [];

      if (!empty($filtros['fechaInicio']) && !empty($filtros['fechaFin'])) {
         $consulta .= " AND a.fecha BETWEEN :fechaInicio AND :fechaFin";
         $valores[':fechaInicio'] = $filtros['fechaInicio'];
         $valores[':fechaFin'] = $filtros['fechaFin'];
      }
      $response = $database->query($consulta, $valores, true);
      $resultado['resumen_estadistico'] = $response ?: [];
      return $resultado;
   }
}
