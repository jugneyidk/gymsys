<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\Validar;

class Subs
{
   private Database $database;
   public function __construct(Database $database)
   {
      $this->database = $database;
   }
   public function listadoSubs(): array
   {
      return $this->_listadoSubs();
   }
   public function incluirSub(array $datos): array
   {
      $keys = ['nombre', 'edadMinima', 'edadMaxima'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validar("nombre_sub", $arrayFiltrado['nombre']);
      Validar::sanitizarYValidar($arrayFiltrado['edadMinima'], 'int');
      Validar::sanitizarYValidar($arrayFiltrado['edadMaxima'], 'int');
      if ($arrayFiltrado['edadMaxima'] <= $arrayFiltrado['edadMinima']) {
         ExceptionHandler::throwException("La edad máxima no puede ser menor o igual a la edad mínima", 400, \InvalidArgumentException::class);
      }
      return $this->_incluirSub($arrayFiltrado);
   }
   public function modificarSub(array $datos): array
   {
      $keys = ['id_sub', 'nombre', 'edadMinima', 'edadMaxima'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $arrayFiltrado['id_sub'] = Cipher::aesDecrypt($arrayFiltrado['id_sub']);
      Validar::sanitizarYValidar($arrayFiltrado['id_sub'], 'int');
      Validar::sanitizarYValidar($arrayFiltrado['edadMinima'], 'int');
      Validar::sanitizarYValidar($arrayFiltrado['edadMaxima'], 'int');
      Validar::validar("nombre_sub", $arrayFiltrado['nombre']);
      if ($arrayFiltrado['edadMaxima'] <= $arrayFiltrado['edadMinima']) {
         ExceptionHandler::throwException("La edad máxima no puede ser menor o igual a la edad mínima", 400, \InvalidArgumentException::class);
      }
      return $this->_modificarSub($arrayFiltrado);
   }
   public function eliminarSub(array $datos): array
   {
      $keys = ['id_sub'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $idSub = Cipher::aesDecrypt($arrayFiltrado['id_sub']);
      Validar::sanitizarYValidar($idSub, 'int');
      return $this->_eliminarSub($idSub);
   }
   private function _listadoSubs(): array
   {
      $consulta = "SELECT * FROM subs";
      $response = $this->database->query($consulta);
      $resultado["subs"] = $response ?: [];
      if (!empty($resultado["subs"])) {
         Cipher::crearHashArray($resultado["subs"], "id_sub", true);
         Cipher::encriptarCampoArray($resultado["subs"], "id_sub", false);
      }
      return $resultado;
   }
   private function _incluirSub(array $datos): array
   {
      $consulta = "SELECT id_sub FROM subs WHERE nombre = :id;";
      $existe = Validar::existe($this->database, $datos['nombre'], $consulta);
      if ($existe) {
         ExceptionHandler::throwException("Ya existe una sub con este nombre", 400, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "INSERT INTO subs (nombre, edad_minima, edad_maxima) 
                     VALUES (:nombre, :edadMinima, :edadMaxima)";
      $valores = [
         ':nombre' => $datos['nombre'],
         ':edadMinima' => $datos['edadMinima'],
         ':edadMaxima' => $datos['edadMaxima']
      ];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al incluir la sub", 500, \Exception::class);
      }
      $this->database->commit();
      $respuesta["mensaje"] = "La sub se registró exitosamente";
      return $respuesta;
   }
   public function _modificarSub(array $datos): array
   {
      $consulta = "SELECT id_sub FROM subs WHERE id_sub = :id;";
      $existe = Validar::existe($this->database, $datos['id_sub'], $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("La sub ingresada no existe", 404, \InvalidArgumentException::class);
      }
      $consulta = "SELECT id_sub FROM subs WHERE nombre = :id AND id_sub != " . $datos['id_sub'] . ";";
      $existe = Validar::existe($this->database, $datos['nombre'], $consulta);
      if ($existe) {
         ExceptionHandler::throwException("Ya existe una sub con este nombre", 400, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "UPDATE subs 
                     SET nombre = :nombre, edad_minima = :edadMinima, edad_maxima = :edadMaxima 
                     WHERE id_sub = :id";
      $valores = [
         ':id' => $datos['id_sub'],
         ':nombre' => $datos['nombre'],
         ':edadMinima' => $datos['edadMinima'],
         ':edadMaxima' => $datos['edadMaxima']
      ];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al modificar la sub", 500, \Exception::class);
      }
      $this->database->commit();
      $respuesta["mensaje"] = "La sub se modificó exitosamente";
      return $respuesta;
   }
   private function _eliminarSub(int $idSub): array
   {
      $consulta = "SELECT id_sub FROM subs WHERE id_sub = :id;";
      $existe = Validar::existe($this->database, $idSub, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("La sub ingresada no existe", 404, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "DELETE FROM subs WHERE id_sub = :id";
      $response = $this->database->query($consulta, [':id' => $idSub]);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al eliminar la sub", 500, \Exception::class);
      }
      $this->database->commit();
      $respuesta["mensaje"] = "La sub se eliminó exitosamente";
      return $respuesta;
   }
}
