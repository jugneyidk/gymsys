<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use Gymsys\Utils\Validar;
use Gymsys\Utils\ExceptionHandler;

class Asistencias
{
   private Database $database;
   public function __construct(Database $database)
   {
      $this->database = $database;
   }

   public function guardarAsistencias(array $datos)
   {
      $keys = ['fecha', 'asistencias'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validarFecha($arrayFiltrado['fecha']);
      $asistencias = json_decode($arrayFiltrado['asistencias'], true);
      Cipher::desencriptarCampoArray($asistencias, "id_atleta");
      Validar::validarAsistencias($asistencias);
      $registrado_por = defined('ID_USUARIO') ? ID_USUARIO : null;
      if (!$registrado_por) {
         ExceptionHandler::throwException("No se pudo identificar el usuario que registra", \RuntimeException::class);
      }
      return $this->_guardarAsistencias($asistencias, $arrayFiltrado['fecha'], $registrado_por);
   }
   public function obtenerAsistencias(array $datos): array
   {
      $keys = ['fecha'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validarFecha($arrayFiltrado['fecha']);
      return $this->_obtenerAsistencias($arrayFiltrado['fecha']);
   }
   public function eliminarAsistencias(array $datos): array
   {
      $keys = ['fecha'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validarFecha($arrayFiltrado['fecha']);
      return $this->_eliminarAsistencias($arrayFiltrado['fecha']);
   }
   private function _guardarAsistencias(array $asistencias, string $fecha, string $registrado_por): array
   {
      $this->database->beginTransaction();
      $numAsistencias = count($asistencias);
      $consultaNumAsistencias = $this->database->query("SET @num_asistencias = :num_asistencias", [':num_asistencias' => $numAsistencias]);
      
      $consulta = "INSERT INTO asistencias (
            id_atleta, 
            fecha, 
            estado_asistencia, 
            tipo_sesion, 
            rpe, 
            observaciones, 
            hora_entrada, 
            hora_salida, 
            registrado_por
         )
         VALUES (
            :id_atleta, 
            :fecha, 
            :estado_asistencia, 
            :tipo_sesion, 
            :rpe, 
            :observaciones, 
            :hora_entrada, 
            :hora_salida, 
            :registrado_por
         )
         ON DUPLICATE KEY UPDATE
            estado_asistencia = VALUES(estado_asistencia),
            tipo_sesion = VALUES(tipo_sesion),
            rpe = VALUES(rpe),
            observaciones = VALUES(observaciones),
            hora_entrada = VALUES(hora_entrada),
            hora_salida = VALUES(hora_salida),
            registrado_por = VALUES(registrado_por);";
            
      foreach ($asistencias as $asistencia) {
         $estado = $asistencia['estado_asistencia'] ?? 'ausente';
         $tipo_sesion = !empty($asistencia['tipo_sesion']) ? $asistencia['tipo_sesion'] : 'entrenamiento';
         $rpe = !empty($asistencia['rpe']) ? $asistencia['rpe'] : null;
         $observaciones = !empty($asistencia['observaciones']) ? $asistencia['observaciones'] : null;
         $hora_entrada = !empty($asistencia['hora_entrada']) ? $asistencia['hora_entrada'] : null;
         $hora_salida = !empty($asistencia['hora_salida']) ? $asistencia['hora_salida'] : null;
         
         if ($estado === 'presente' && empty($hora_entrada)) {
            ExceptionHandler::throwException(
               "El atleta con cédula {$asistencia['id_atleta']} está marcado como 'presente' pero no tiene hora de entrada",
               \InvalidArgumentException::class,
               400
            );
         }
         
         if ($rpe !== null && ($rpe < 1 || $rpe > 10)) {
            ExceptionHandler::throwException(
               "El RPE debe estar entre 1 y 10 para el atleta {$asistencia['id_atleta']}",
               \InvalidArgumentException::class,
               400
            );
         }
         
         $valores = [
            ':id_atleta' => $asistencia['id_atleta'],
            ':fecha' => $fecha,
            ':estado_asistencia' => $estado,
            ':tipo_sesion' => $tipo_sesion,
            ':rpe' => $rpe,
            ':observaciones' => $observaciones,
            ':hora_entrada' => $hora_entrada,
            ':hora_salida' => $hora_salida,
            ':registrado_por' => $registrado_por
         ];
         $response = $this->database->query($consulta, $valores);
         if (!$response) {
            ExceptionHandler::throwException("Ocurrió un error al incluir las asistencias", \RuntimeException::class);
         }
      }
      $this->database->commit();
      $resultado["mensaje"] = "Las asistencias se han modificado exitosamente";
      return $resultado;
   }

   private function _obtenerAsistencias(string $fecha): array
   {
      $consulta = "SELECT * FROM lista_asistencias a
                  WHERE a.fecha = :fecha";
      $response = $this->database->query($consulta, [':fecha' => $fecha]);
      $resultado["asistencias"] = $response ?: [];
      if (!empty($resultado["asistencias"])) {
         Cipher::encriptarCampoArray($resultado["asistencias"], "id_atleta");
      }
      return $resultado;
   }
   private function _eliminarAsistencias(string $fecha): array
   {
      $this->database->beginTransaction();
      $consulta = "DELETE 
                FROM asistencias
                WHERE fecha = :fecha;";
      $valores = [':fecha' => $fecha];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Hubo un error al eliminar las asistencias", \RuntimeException::class);
      }
      $this->database->commit();
      $resultado["mensaje"] = "Las asistencias del dia '{$fecha}' se eliminaron correctamente";
      return $resultado;
   }
}
