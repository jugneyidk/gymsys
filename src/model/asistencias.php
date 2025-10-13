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
      Validar::validarFechaMayorHoy($arrayFiltrado['fecha']);
      $asistencias = json_decode($arrayFiltrado['asistencias'], true);
      Cipher::desencriptarCampoArray($asistencias, "id_atleta");
      Validar::validarAsistencias($asistencias);
      return $this->_guardarAsistencias($asistencias, $arrayFiltrado['fecha']);
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
   private function _guardarAsistencias(array $asistencias, string $fecha): array
   {
      $this->database->beginTransaction();
      $numAsistencias = count($asistencias);
      $consultaNumAsistencias = $this->database->query("SET @num_asistencias = :num_asistencias", [':num_asistencias' => $numAsistencias]);
      $consulta = "INSERT INTO asistencias (id_atleta, fecha, asistio, comentario)
            VALUES 
                (:id_atleta, :fecha, :asistio, :comentario)
            ON DUPLICATE KEY UPDATE
                asistio = VALUES(asistio),
                comentario = VALUES(comentario);";
      foreach ($asistencias as $asistencia) {
         $valores = [
            ':id_atleta' => $asistencia['id_atleta'],
            ':asistio' => $asistencia['asistio'],
            ':fecha' => $fecha,
            ':comentario' => $asistencia['comentario']
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
