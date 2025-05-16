<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\Validar;

class TipoCompetencia
{
   private Database $database;
   public function __construct(Database $database)
   {
      $this->database = $database;
   }
   public function listadoTipos(): array
   {
      return $this->_listadoTipos();
   }
   public function incluirTipo(array $datos): array
   {
      $keys = ['nombre'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validar("nombre_tipo", $arrayFiltrado['nombre']);
      return $this->_incluirTipo($arrayFiltrado['nombre']);
   }
   public function modificarTipo(array $datos): array
   {
      $keys = ['id_tipo', 'nombre'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::sanitizarYValidar($arrayFiltrado['id_tipo'], 'int');
      Validar::validar("nombre_tipo", $arrayFiltrado['nombre']);
      return $this->_modificarTipo($arrayFiltrado);
   }
   public function eliminarTipo(array $datos)
   {
      $keys = ['id_tipo'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      if (!Validar::sanitizarYValidar($arrayFiltrado['id_tipo'], "int")) {
         ExceptionHandler::throwException("El ID del tipo de competencia no es válido", 400, \InvalidArgumentException::class);
      }
      return $this->_eliminarTipo($arrayFiltrado['id_tipo']);
   }
   private function _listadoTipos(): array
   {
      $consulta = "SELECT * FROM tipo_competencia";
      $response = $this->database->query($consulta);
      $resultado["tipos"] = $response ?: [];
      return $resultado;
   }
   private function _incluirTipo(string $nombreTipo): array
   {
      $consulta = "SELECT id_tipo_competencia FROM tipo_competencia WHERE nombre = :id;";
      $existe = Validar::existe($this->database, $nombreTipo, $consulta);
      if ($existe) {
         ExceptionHandler::throwException("Ya existe este tipo de competencia", 400, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "INSERT INTO tipo_competencia (nombre) VALUES (:nombre)";
      $response = $this->database->query($consulta, [':nombre' => $nombreTipo]);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al incluir el tipo de competencia", 500, \Exception::class);
      }
      $this->database->commit();
      $resultado["mensaje"] = "El tipo de competencia se registró exitosamente";
      return $resultado;
   }
   public function _modificarTipo(array $datos): array
   {
      $consulta = "SELECT id_tipo_competencia FROM tipo_competencia WHERE id_tipo_competencia = :id;";
      $existe = Validar::existe($this->database, $datos['id_tipo'], $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("El tipo de competencia ingresado no existe", 404, \InvalidArgumentException::class);
      }
      $consulta = "SELECT id_tipo_competencia FROM tipo_competencia WHERE nombre = :id AND id_tipo_competencia != " . $datos['id_tipo'] . ";";
      $existe = Validar::existe($this->database, $datos['nombre'], $consulta);
      if ($existe) {
         ExceptionHandler::throwException("Ya existe un tipo de competencia con este nombre", 400, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "UPDATE tipo_competencia SET nombre = :nombre WHERE id_tipo_competencia = :id_tipo";
      $valores = [
         ':nombre' => $datos['nombre'],
         ':id_tipo' => $datos['id_tipo']
      ];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al modificar el tipo de competencia", 500, \Exception::class);
      }
      $this->database->commit();
      $respuesta["mensaje"] = "El tipo de competencia se modificó exitosamente";
      return $respuesta;
   }
   private function _eliminarTipo(int $idTipo): array
   {
      $consulta = "SELECT id_tipo_competencia FROM tipo_competencia WHERE id_tipo_competencia = :id;";
      $existe = Validar::existe($this->database, $idTipo, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("Este tipo de competencia no existe", 404, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "DELETE FROM tipo_competencia WHERE id_tipo_competencia = :id_tipo";
      $response = $this->database->query($consulta, [':id_tipo' => $idTipo]);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al eliminar el tipo de competencia", 500, \Exception::class);
      }
      $this->database->commit();
      $respuesta["mensaje"] = "El tipo de competencia se eliminó exitosamente";
      return $respuesta;
   }
}
