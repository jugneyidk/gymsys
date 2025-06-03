<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Model\Reportes\Asistencias;
use Gymsys\Model\Reportes\Atletas;
use Gymsys\Model\Reportes\Entrenadores;
use Gymsys\Model\Reportes\Eventos;
use Gymsys\Model\Reportes\Mensualidades;
use Gymsys\Model\Reportes\Wada;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\Validar;

class Reportes
{
   private Database $database;

   public function __construct(Database $database)
   {
      $this->database = $database;
   }

   public function obtenerDatosEstadisticos(array $datos): array
   {
      $keys = ["tipo"];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      return $this->_obtenerDatosEstadisticos($arrayFiltrado["tipo"]);
   }
   private function _obtenerDatosEstadisticos(string $tipo): array
   {
      $consulta = match ($tipo) {
         'edadAtletas' => "SELECT 
                    CASE 
                        WHEN TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) BETWEEN 0 AND 18 THEN '0-18'
                        WHEN TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) BETWEEN 19 AND 30 THEN '19-30'
                        WHEN TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) BETWEEN 31 AND 45 THEN '31-45'
                        ELSE '46+' 
                    END AS rango_edad,
                    COUNT(*) AS cantidad
                FROM atleta a
                INNER JOIN {$_ENV['SECURE_DB']}.usuarios u ON a.cedula = u.cedula
                GROUP BY rango_edad",
         'generoAtletas' => "SELECT 
                    u.genero AS genero, 
                    COUNT(*) AS cantidad
                FROM atleta a
                INNER JOIN {$_ENV['SECURE_DB']}.usuarios u ON a.cedula = u.cedula
                GROUP BY u.genero",
         default => ExceptionHandler::throwException("Tipo de estadística no válido", 500, \InvalidArgumentException::class)
      };
      $response = $this->database->query($consulta);
      $resultado['estadisticas'] = $response ?: [];
      return $resultado;
   }

   public function obtenerProgresoAsistencias(): array
   {
      $consulta = "SELECT 
                     DATE_FORMAT(a.fecha, '%Y-%m') AS mes,
                     COUNT(*) AS total_asistencias,
                     COUNT(DISTINCT a.id_atleta) AS atletas_unicos
                  FROM asistencias a
                  WHERE a.asistio = 1
                  AND a.fecha >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                  GROUP BY DATE_FORMAT(a.fecha, '%Y-%m')
                  ORDER BY mes ASC";

      $response = $this->database->query($consulta);
      $resultado['asistencias'] = $response ?: [];
      return $resultado;
   }

   public function obtenerCumplimientoWADA(): array
   {
      $consulta = "SELECT
                     COUNT(*)                                                   AS total_atletas,
                     SUM(w.vencimiento > CURDATE() + INTERVAL 30 DAY)           AS vigentes,
                     SUM(w.vencimiento <= CURDATE())                            AS vencidas,
                     SUM(w.vencimiento > CURDATE()
                           AND w.vencimiento < CURDATE() + INTERVAL 30 DAY)       AS por_vencer
                     FROM wada w;";
      $response = $this->database->query($consulta, uniqueFetch: true);
      $resultado['wada'] = $response ?: [];
      return $resultado;
   }
   public function obtenerReportes(array $datos): array
   {
      $keys = ["tipoReporte"];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $filtros = [
         'edadMin' => $datos['edadMin'] ?? null,
         'edadMax' => $datos['edadMax'] ?? null,
         'genero' => $datos['genero'] ?? null,
         'tipoAtleta' => $datos['tipoAtleta'] ?? null,
         'pesoMin' => $datos['pesoMin'] ?? null,
         'pesoMax' => $datos['pesoMax'] ?? null,
         'edadMinEntrenador' => $datos['edadMinEntrenador'] ?? null,
         'edadMaxEntrenador' => $datos['edadMaxEntrenador'] ?? null,
         'gradoInstruccion' => $datos['gradoInstruccion'] ?? null,
         'fechaInicio' => $datos['fechaInicio'] ?? null,
         'fechaFin' => $datos['fechaFin'] ?? null,
      ];
      return $this->_obtenerReportes($arrayFiltrado["tipoReporte"], $filtros);
   }
   public function obtenerReporteIndividual(array $datos): array
   {
      $keys = ["cedulaAtleta"];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $datosReporte = $datos['datosReporte'] ?? [];
      $reportes = Atletas::obtenerReporteIndividual($this->database, $arrayFiltrado["cedulaAtleta"]);
      $estadisticas = Atletas::obtenerEstadisticasAtleta($this->database, $arrayFiltrado["cedulaAtleta"], $datosReporte);
      return ["reportes" => $reportes, "estadisticas" => $estadisticas];
   }

   private function _obtenerReportes(string $tipoReporte, array $filtros): array
   {
      $reporte = match ($tipoReporte) {
         'atletas' => Atletas::obtenerReporteAtletas($this->database, $filtros),
         'entrenadores' => Entrenadores::obtenerReporteEntrenadores($this->database, $filtros),
         'mensualidades' => Mensualidades::obtenerReporteMensualidades($this->database, $filtros),
         'wada' => Wada::obtenerReporteWada($this->database, $filtros),
         'eventos' => Eventos::obtenerReporteEventos($this->database, $filtros),
         'asistencias' => Asistencias::obtenerReporteAsistencias($this->database, $filtros),
         default => ExceptionHandler::throwException("Tipo de reporte no válido", 400, \InvalidArgumentException::class)
      };

      if (!$reporte) {
         ExceptionHandler::throwException("No se encontraron reportes", 404, \RuntimeException::class);
      }

      $estadisticas = match ($tipoReporte) {
         'atletas' => Atletas::obtenerEstadisticasAtletas($this->database, $filtros),
         'entrenadores' => Entrenadores::obtenerEstadisticasEntrenadores($this->database, $filtros),
         'mensualidades' => Mensualidades::obtenerEstadisticasMensualidades($this->database, $filtros),
         'wada' => Wada::obtenerEstadisticasWada($this->database, $filtros),
         'eventos' => Eventos::obtenerEstadisticasEventos($this->database, $filtros),
         'asistencias' => Asistencias::obtenerEstadisticasAsistencias($this->database, $filtros),
         default => ExceptionHandler::throwException("Tipo de Estadisticas no válido", 400, \InvalidArgumentException::class)
      };

      if (!$estadisticas) {
         ExceptionHandler::throwException("No se encontraron estadisticas", 404, \RuntimeException::class);
      }

      return ["reportes" => $reporte, "estadisticas" => $estadisticas];
   }
}
