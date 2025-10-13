<?php

namespace Gymsys\Model\Reportes;

use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;

class Entrenadores
{
   public static function obtenerReporteEntrenadores(Database $database, array $filtros): array
   {
      $consulta = "SELECT e.cedula AS cedula, 
                     CONCAT(u.nombre, ' ', u.apellido) AS nombre,
                     e.grado_instruccion AS grado_instruccion, 
                     u.fecha_nacimiento AS fecha
                  FROM entrenador e
                  INNER JOIN {$_ENV['SECURE_DB']}.usuarios u ON e.cedula = u.cedula
                  WHERE 1=1";
      $valores = [];

      if (!empty($filtros['edadMinEntrenador'])) {
         $consulta .= " AND TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) >= :edadMinEntrenador";
         $valores[':edadMinEntrenador'] = $filtros['edadMinEntrenador'];
      }
      if (!empty($filtros['edadMaxEntrenador'])) {
         $consulta .= " AND TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) <= :edadMaxEntrenador";
         $valores[':edadMaxEntrenador'] = $filtros['edadMaxEntrenador'];
      }
      if (!empty($filtros['gradoInstruccion'])) {
         $consulta .= " AND e.grado_instruccion = :gradoInstruccion";
         $valores[':gradoInstruccion'] = $filtros['gradoInstruccion'];
      }

      $result = $database->query($consulta, $valores);
      if (!$result) {
         ExceptionHandler::throwException("No se encontraron entrenadores con los filtros especificados", \RuntimeException::class, 500);
      }
      return $result;
   }

   public static function obtenerEstadisticasEntrenadores(Database $database, array $filtros): array
   {
      $consulta = "SELECT 
                COUNT(*) AS total_entrenadores,
                ROUND(AVG(TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE())),2) AS promedio_edad
            FROM entrenador e
            INNER JOIN {$_ENV['SECURE_DB']}.usuarios u ON e.cedula = u.cedula
            WHERE 1=1";
      $valores = [];

      if (!empty($filtros['edadMinEntrenador'])) {
         $consulta .= " AND TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) >= :edadMinEntrenador";
         $valores[':edadMinEntrenador'] = $filtros['edadMinEntrenador'];
      }
      if (!empty($filtros['edadMaxEntrenador'])) {
         $consulta .= " AND TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) <= :edadMaxEntrenador";
         $valores[':edadMaxEntrenador'] = $filtros['edadMaxEntrenador'];
      }
      $response = $database->query($consulta, $valores, true);
      $resultado['resumen_estadistico'] = $response ?: [];
      return $resultado;
   }
}
