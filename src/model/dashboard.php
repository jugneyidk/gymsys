<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;

class Dashboard
{
   private Database $database;

   public function __construct(Database $database)
   {
      $this->database = $database;
   }
   public function obtenerDatosSistema(): array
   {
      return $this->_obtenerDatosSistema();
   }
   public function _obtenerDatosSistema(): array
   {
      $consulta = "SELECT * FROM estadisticas_dashboard;";
      $response = $this->database->query($consulta, uniqueFetch: true);
      if (empty($response)) {
         ExceptionHandler::throwException("No se encontraron estadisticas", 404, \InvalidArgumentException::class);
      }
      $resultado["estadisticas"] = $response ?: ["total_atletas" => 0, "total_entrenadores" => 0,  "total_wadas_pendientes" => 0, "total_deudores" => 0];
      return $resultado;
   }
   public function obtener_ultimos_atletas()
   {
      try {
         $consulta = "SELECT u.cedula, u.nombre, u.apellido, u.genero, u.fecha_nacimiento 
                         FROM usuarios u
                         JOIN atleta a ON u.cedula = a.cedula
                         ORDER BY u.cedula DESC LIMIT 2";
         $respuesta = $this->conexion->query($consulta);
         return $respuesta->fetchAll(PDO::FETCH_ASSOC);
      } catch (Exception $e) {
         return [];
      }
   }

   public function obtener_medallas_por_mes()
   {
      try {
         $consulta = "SELECT MONTH(fecha_competencia) as mes, COUNT(*) as total_medallas 
                         FROM competencias 
                         GROUP BY MONTH(fecha_competencia)";
         $respuesta = $this->conexion->query($consulta);
         $datos = $respuesta->fetchAll(PDO::FETCH_ASSOC);

         $labels = [];
         $medallas_por_mes = [];
         foreach ($datos as $dato) {
            $mes = $this->getNombreMes($dato['mes']);
            $labels[] = $mes;
            $medallas_por_mes[] = $dato['total_medallas'];
         }

         return ['labels' => $labels, 'medallas' => $medallas_por_mes];
      } catch (Exception $e) {
         return ['labels' => [], 'medallas' => []];
      }
   }

   public function obtener_progreso_semanal()
   {
      try {
         $consulta = "SELECT semana, SUM(progreso) as total_progreso 
                         FROM progreso_atletas 
                         GROUP BY semana";
         $respuesta = $this->conexion->query($consulta);
         $datos = $respuesta->fetchAll(PDO::FETCH_ASSOC);

         $labels = [];
         $progreso_semanal = [];
         foreach ($datos as $dato) {
            $labels[] = 'Semana ' . $dato['semana'];
            $progreso_semanal[] = $dato['total_progreso'];
         }

         return ['labels' => $labels, 'progreso' => $progreso_semanal];
      } catch (Exception $e) {
         return ['labels' => [], 'progreso' => []];
      }
   }

   private function getNombreMes(int $mes): string
   {
      if ($mes < 1 || $mes > 12) {
         ExceptionHandler::throwException("El mes debe estar entre 1 y 12", 400, \InvalidArgumentException::class);
      }
      $meses = [
         1 => 'Enero',
         2 => 'Febrero',
         3 => 'Marzo',
         4 => 'Abril',
         5 => 'Mayo',
         6 => 'Junio',
         7 => 'Julio',
         8 => 'Agosto',
         9 => 'Septiembre',
         10 => 'Octubre',
         11 => 'Noviembre',
         12 => 'Diciembre'
      ];
      return $meses[$mes];
   }
}
