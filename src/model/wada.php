<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\Validar;

class Wada
{
   private Database $database;

   public function __construct(Database $database)
   {
      $this->database = $database;
   }

   public function incluirWada(array $datos): array
   {
      $keys = ['atleta', 'status', 'inscrito', 'ultima_actualizacion', 'vencimiento'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $arrayFiltrado['atleta'] = Cipher::aesDecrypt($arrayFiltrado['atleta']);
      Validar::validar("cedula", $arrayFiltrado['atleta']);
      Validar::validarFechasWada($this->database, $arrayFiltrado['atleta'], $arrayFiltrado['inscrito'], $arrayFiltrado['ultima_actualizacion'], $arrayFiltrado['vencimiento']);
      Validar::validar("bool", $arrayFiltrado['status']);
      return $this->_incluirWada($arrayFiltrado['atleta'], $arrayFiltrado['status'], $arrayFiltrado['inscrito'], $arrayFiltrado['ultima_actualizacion'], $arrayFiltrado['vencimiento']);
   }

   public function modificarWada(array $datos): array
   {
      $keys = ['atleta', 'status', 'inscrito', 'ultima_actualizacion', 'vencimiento'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $arrayFiltrado['atleta'] = Cipher::aesDecrypt($arrayFiltrado['atleta']);
      Validar::validar("cedula", $arrayFiltrado['atleta']);
      Validar::validarFechasWada($this->database, $arrayFiltrado['atleta'], $arrayFiltrado['inscrito'], $arrayFiltrado['ultima_actualizacion'], $arrayFiltrado['vencimiento']);
      Validar::validar("bool", $arrayFiltrado['status']);
      return $this->_modificarWada($arrayFiltrado['atleta'], $arrayFiltrado['status'], $arrayFiltrado['inscrito'], $arrayFiltrado['ultima_actualizacion'], $arrayFiltrado['vencimiento']);
   }

   public function obtenerWada(array $datos): array
   {
      $keys = ['id'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $idAtleta = Cipher::aesDecrypt($arrayFiltrado['id']);
      Validar::validar("cedula", $idAtleta);
      return $this->_obtenerWada($idAtleta);
   }

   public function eliminarWada(array $datos): array
   {
      $keys = ['cedula'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $idAtleta = Cipher::aesDecrypt($arrayFiltrado['cedula']);
      Validar::validar("cedula", $idAtleta);
      return $this->_eliminarWada($idAtleta);
   }

   public function listadoWada(): array
   {
      return $this->_listadoWada();
   }

   private function _incluirWada(string $cedula, bool $estado, string $inscrito, string $ultimaActualizacion, string $vencimiento): array
   {
      $consulta = "SELECT id_atleta FROM wada WHERE id_atleta = :id;";
      $existe = Validar::existe($this->database, $cedula, $consulta);
      if ($existe) {
         ExceptionHandler::throwException("La WADA para este atleta ya existe", \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "INSERT INTO wada (id_atleta, estado, inscrito, ultima_actualizacion, vencimiento) 
                         VALUES (:id_atleta, :estado, :inscrito, :ultima_actualizacion, :vencimiento)";
      $valores = [
         ':id_atleta' => $cedula,
         ':estado' => $estado,
         ':inscrito' => $inscrito,
         ':ultima_actualizacion' => $ultimaActualizacion,
         ':vencimiento' => $vencimiento
      ];
      $response = $this->database->query($consulta, $valores);
      if (!$response) {
         ExceptionHandler::throwException("Ocurrió un error al incluir la WADA", \RuntimeException::class, 500);
      }
      $this->database->commit();
      $resultado["mensaje"] = "La WADA se registró exitosamente";
      return $resultado;
   }

   private function _modificarWada(string $cedula, bool $estado, string $inscrito, string $ultimaActualizacion, string $vencimiento): array
   {
      $consulta = "SELECT id_atleta FROM wada WHERE id_atleta = :id;";
      $existe = Validar::existe($this->database, $cedula, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("La WADA del atleta introducido no existe", \InvalidArgumentException::class, 404);
      }
      $this->database->beginTransaction();
      $consulta = "UPDATE wada SET estado = :estado, inscrito = :inscrito, ultima_actualizacion = :ultima_actualizacion, vencimiento = :vencimiento WHERE id_atleta = :id_atleta";
      $valores = [
         ':id_atleta' => $cedula,
         ':estado' => $estado,
         ':inscrito' => $inscrito,
         ':ultima_actualizacion' => $ultimaActualizacion,
         ':vencimiento' => $vencimiento
      ];
      $response = $this->database->query($consulta, $valores);
      if (!$response) {
         ExceptionHandler::throwException("Ocurrió un error al modificar la WADA", \RuntimeException::class, 500);
      }
      $this->database->commit();
      $resultado["mensaje"] = "La WADA se modificó exitosamente";
      return $resultado;
   }

   private function _obtenerWada(string $cedula): array
   {
      $consulta = "SELECT id_atleta FROM wada WHERE id_atleta = :id;";
      $existe = Validar::existe($this->database, $cedula, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("La WADA de este atleta no existe", \InvalidArgumentException::class);
      }
      $consulta = "SELECT * FROM wada WHERE id_atleta = :id_atleta";
      $response = $this->database->query($consulta, [":id_atleta" => $cedula], true);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al obtener la WADA", \RuntimeException::class, 500);
      }
      $resultado["wada"] = $response;
      Cipher::crearHashArray($resultado, "id_atleta");
      Cipher::encriptarCampoArray($resultado, "id_atleta", false);
      return $resultado;
   }

   private function _eliminarWada(string $cedula): array
   {
      $consulta = "SELECT id_atleta FROM wada WHERE id_atleta = :id;";
      $existe = Validar::existe($this->database, $cedula, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("La WADA del atleta introducido no existe", \InvalidArgumentException::class, 404);
      }
      $this->database->beginTransaction();
      $consulta = "DELETE FROM wada WHERE id_atleta = :id_atleta";
      $response = $this->database->query($consulta, [':id_atleta' => $cedula]);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al eliminar la WADA", \RuntimeException::class, 500);
      }
      $this->database->commit();
      $resultado['mensaje'] = "La WADA se eliminó exitosamente";
      return $resultado;
   }

   private function _listadoWada(): array
   {
      $consulta = "SELECT * FROM lista_wada;";
      $response = $this->database->query($consulta);
      $resultado["wada"] = $response ?: [];
      if (!empty($resultado["wada"])) {
         Cipher::encriptarCampoArray($resultado["wada"], "cedula", false);
      }
      return $resultado;
   }

   public function listadoPorVencer(): array
   {
      return $this->_listadoPorVencer();
   }
   private function _listadoPorVencer(): array
   {
      $consulta = "SELECT * FROM lista_wada_por_vencer;";
      $response = $this->database->query($consulta);
      $resultado["wadas"] = $response ?: [];
      if (!empty($resultado["wadas"])) {
         Cipher::encriptarCampoArray($resultado["wadas"], "cedula", false);
      }
      return $resultado;
   }
}
