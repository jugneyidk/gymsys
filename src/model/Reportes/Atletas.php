<?php

namespace Gymsys\Model\Reportes;

use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;

class Atletas
{
   public static function obtenerReporteAtletas(Database $database, array $filtros): array
   {
      $consulta = "SELECT a.cedula AS cedula, 
                        CONCAT(u.nombre, ' ', u.apellido) AS nombre, 
                        u.genero AS genero,
                        a.peso AS peso,
                        a.altura AS altura,
                        u.fecha_nacimiento AS fecha
                     FROM atleta a
                     INNER JOIN {$_ENV['SECURE_DB']}.usuarios u ON a.cedula = u.cedula
                     WHERE 1=1";
      $valores = [];

      if (!empty($filtros['edadMin'])) {
         $consulta .= " AND TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) >= :edadMin";
         $valores[':edadMin'] = $filtros['edadMin'];
      }
      if (!empty($filtros['edadMax'])) {
         $consulta .= " AND TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) <= :edadMax";
         $valores[':edadMax'] = $filtros['edadMax'];
      }
      if (!empty($filtros['genero'])) {
         $consulta .= " AND u.genero = :genero";
         $valores[':genero'] = $filtros['genero'];
      }
      if (!empty($filtros['pesoMin'])) {
         $consulta .= " AND a.peso >= :pesoMin";
         $valores[':pesoMin'] = $filtros['pesoMin'];
      }
      if (!empty($filtros['pesoMax'])) {
         $consulta .= " AND a.peso <= :pesoMax";
         $valores[':pesoMax'] = $filtros['pesoMax'];
      }

      $response = $database->query($consulta, $valores);
      return $response ?: [];
   }
   public static function obtenerEstadisticasAtletas(Database $database, array $filtros): array
   {
      $consulta = "SELECT 
                     COUNT(*) AS total_atletas, 
                     ROUND(AVG(a.peso),2) AS peso_promedio,
                     MAX(a.peso) AS peso_maximo,
                     MIN(a.peso) AS peso_minimo,
                     ROUND(AVG(TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE())),2) AS edad_promedio 
                  FROM atleta a
                  INNER JOIN {$_ENV['SECURE_DB']}.usuarios u ON a.cedula = u.cedula
                  WHERE 1=1";
      $valores = [];

      if (!empty($filtros['edadMin'])) {
         $consulta .= " AND TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) >= :edadMin";
         $valores[':edadMin'] = $filtros['edadMin'];
      }
      if (!empty($filtros['edadMax'])) {
         $consulta .= " AND TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) <= :edadMax";
         $valores[':edadMax'] = $filtros['edadMax'];
      }
      if (!empty($filtros['genero'])) {
         $consulta .= " AND u.genero = :genero";
         $valores[':genero'] = $filtros['genero'];
      }
      $response = $database->query($consulta, $valores, true);
      $resultado['resumen_estadistico'] = $response ?: [];
      return $resultado;
   }
   public static function obtenerReporteIndividual(Database $database, string $cedula): array
   {
      $consultaExistencia = "SELECT u.cedula 
                            FROM atleta a 
                            INNER JOIN {$_ENV['SECURE_DB']}.usuarios u ON a.cedula = u.cedula 
                            WHERE a.cedula = :cedula";

      $existe = $database->query($consultaExistencia, [":cedula" => $cedula], false);
      if (empty($existe)) {
         ExceptionHandler::throwException("No se encontró el atleta con la cédula especificada", 404, \InvalidArgumentException::class);
      }

      // Si el atleta existe, obtenemos su información detallada
      $consulta = "SELECT 
                     u.nombre, 
                     u.apellido,
                     u.cedula, 
                     u.genero,
                     u.fecha_nacimiento,
                     a.peso, 
                     a.altura,
                     ta.nombre_tipo_atleta as tipo_atleta,
                     TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) AS edad
                  FROM atleta a
                  INNER JOIN {$_ENV['SECURE_DB']}.usuarios u ON a.cedula = u.cedula
                  LEFT JOIN tipo_atleta ta ON a.tipo_atleta = ta.id_tipo_atleta
                  WHERE a.cedula = :cedula";

      $response = $database->query($consulta, [":cedula" => $cedula]);
      if (empty($response)) {
         ExceptionHandler::throwException("Error al obtener la información del atleta", 500, \RuntimeException::class);
      }

      return $response;
   }
   public static function obtenerEstadisticasAtleta(Database $database, string $cedula, array $filtros): array
   {
      $estadisticas = [];

      // Estadísticas de asistencias
      if (in_array('asistencias', $filtros)) {
         $consultaAsistencias = "SELECT 
            COUNT(*) as total_asistencias,
            SUM(asistio = 1) as asistencias_cumplidas,
            ROUND((SUM(asistio = 1) / COUNT(*)) * 100, 2) as porcentaje_asistencia,
            MAX(fecha) as ultima_asistencia
         FROM asistencias 
         WHERE id_atleta = :cedula 
         AND fecha >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)";

         $estadisticas['asistencias'] = $database->query($consultaAsistencias, [':cedula' => $cedula], true);
      }

      // Estadísticas de competencias
      if (in_array('campeonatos', $filtros)) {
         $consultaCompetencias = "SELECT 
            COUNT(DISTINCT rc.id_competencia) as total_competencias,
            COUNT(CASE WHEN rc.medalla_total != 'ninguna' THEN 1 END) as total_medallas,
            COUNT(CASE WHEN rc.medalla_total = 'oro' THEN 1 END) as medallas_oro,
            COUNT(CASE WHEN rc.medalla_total = 'plata' THEN 1 END) as medallas_plata,
            COUNT(CASE WHEN rc.medalla_total = 'bronce' THEN 1 END) as medallas_bronce,
            MAX(c.fecha_fin) as ultima_competencia,
            ROUND(AVG(rc.total), 2) as promedio_total,
            MAX(rc.total) as mejor_marca
         FROM resultado_competencia rc
         INNER JOIN competencia c ON rc.id_competencia = c.id_competencia
         WHERE rc.id_atleta = :cedula";

         $estadisticas['competencias'] = $database->query($consultaCompetencias, [':cedula' => $cedula], true);
      }

      // Estadísticas de mensualidades
      if (in_array('mensualidades', $filtros)) {
         $consultaMensualidades = "SELECT 
            COUNT(*) as total_pagos,
            SUM(monto) as total_pagado,
            ROUND(AVG(monto), 2) as promedio_pago,
            MAX(fecha) as ultimo_pago
         FROM mensualidades 
         WHERE id_atleta = :cedula";

         $estadisticas['mensualidades'] = $database->query($consultaMensualidades, [':cedula' => $cedula], true);
      }

      // Estadísticas WADA
      if (in_array('wada', $filtros)) {
         $consultaWADA = "SELECT 
            inscrito,
            vencimiento,
            ultima_actualizacion,
            estado,
            CASE 
               WHEN vencimiento < CURDATE() THEN 'Vencido'
               WHEN vencimiento <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 'Por vencer'
               ELSE 'Vigente'
            END as estado_actual,
            DATEDIFF(vencimiento, CURDATE()) as dias_restantes
         FROM wada 
         WHERE id_atleta = :cedula";

         $estadisticas['wada'] = $database->query($consultaWADA, [':cedula' => $cedula], true);
      }

      return $estadisticas;
   }
}
