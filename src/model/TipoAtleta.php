<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\Validar;

class TipoAtleta
{
   private Database $database;

   public function __construct(Database $database)
   {
      $this->database = $database;
   }
   public function listadoTipoAtletas(): array
   {
      return $this->_listadoTipoAtletas();
   }
   public function incluirTipoAtleta(array $datos): array
   {
      $keys = ['nombre_tipo_atleta', 'tipo_cobro'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::sanitizarYValidar($arrayFiltrado['tipo_cobro'], 'float');
      Validar::validar("nombre_tipo", $arrayFiltrado['nombre_tipo_atleta']);
      return $this->_incluirTipoAtleta($arrayFiltrado['nombre_tipo_atleta'], $arrayFiltrado['tipo_cobro']);
   }
   public function eliminarTipoAtleta(array $datos): array
   {
      $keys = ['id_tipo'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $idTipo = Cipher::aesDecrypt($arrayFiltrado['id_tipo']);
      Validar::sanitizarYValidar($idTipo, 'int');
      return $this->_eliminarTipoAtleta($idTipo);
   }
   public function _listadoTipoAtletas(): array
   {
      $consulta = "SELECT id_tipo_atleta, nombre_tipo_atleta FROM tipo_atleta";
      $response = $this->database->query($consulta);
      $resultado["tipos"] = $response ?: [];
      if (!empty($resultado["tipos"])) {
         Cipher::encriptarCampoArray($resultado["tipos"], "id_tipo_atleta");
         Cipher::crearHashArray($resultado["tipos"], "id_tipo_atleta", false);
      }
      return $resultado;
   }
   private function _incluirTipoAtleta(string $nombreTipoAtleta, float $tipoCobro): array
   {
      $consulta = "SELECT id_tipo_atleta FROM tipo_atleta WHERE nombre_tipo_atleta = :id;";
      $existe = Validar::existe($this->database, $nombreTipoAtleta, $consulta);
      if ($existe) {
         ExceptionHandler::throwException("El tipo de atleta ingresado ya existe", \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "INSERT INTO tipo_atleta (nombre_tipo_atleta, tipo_cobro) VALUES (:nombre_tipo_atleta, :tipo_cobro);";
      $valores = [
         ':nombre_tipo_atleta' => $nombreTipoAtleta,
         ':tipo_cobro' => $tipoCobro
      ];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("No se pudo ingresar el tipo de atleta", \Exception::class, 500);
      }
      $this->database->commit();
      $resultado["mensaje"] = "El tipo de atleta se agregó exitosamente";
      return $resultado;
   }

   private function _eliminarTipoAtleta(int $tipoAtleta): array
   {
      $consulta = "SELECT id_tipo_atleta FROM tipo_atleta WHERE id_tipo_atleta = :id;";
      $existe = Validar::existe($this->database, $tipoAtleta, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("El tipo de atleta introducido no existe", \InvalidArgumentException::class, 404);
      }
      $this->database->beginTransaction();
      $consulta = "DELETE FROM tipo_atleta WHERE id_tipo_atleta = :tipo_atleta;";
      $response = $this->database->query($consulta, [':tipo_atleta' => $tipoAtleta]);
      if (empty($response)) {
         ExceptionHandler::throwException("No se pudo eliminar el tipo de atleta", \Exception::class, 500);
      }
      $this->database->commit();
      $resultado["mensaje"] = "El tipo de atleta se ha eliminado exitosamente";
      return $resultado;
   }
}
