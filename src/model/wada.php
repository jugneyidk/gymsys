<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
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
      Validar::validar("cedula", $arrayFiltrado['atleta']);
      Validar::validarFechasWada($this->database, $arrayFiltrado['atleta'], $arrayFiltrado['inscrito'], $arrayFiltrado['ultima_actualizacion'], $arrayFiltrado['vencimiento']);
      Validar::validar("bool", $arrayFiltrado['status']);
      return $this->_incluirWada($arrayFiltrado['atleta'], $arrayFiltrado['status'], $arrayFiltrado['inscrito'], $arrayFiltrado['ultima_actualizacion'], $arrayFiltrado['vencimiento']);
   }

   public function modificarWada(array $datos): array
   {

      $keys = ['atleta', 'status', 'inscrito', 'ultima_actualizacion', 'vencimiento'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validar("cedula", $arrayFiltrado['atleta']);
      Validar::validarFechasWada($this->database, $arrayFiltrado['atleta'], $arrayFiltrado['inscrito'], $arrayFiltrado['ultima_actualizacion'], $arrayFiltrado['vencimiento']);
      Validar::validar("bool", $arrayFiltrado['status']);
      return $this->_modificarWada($arrayFiltrado['atleta'], $arrayFiltrado['status'], $arrayFiltrado['inscrito'], $arrayFiltrado['ultima_actualizacion'], $arrayFiltrado['vencimiento']);
   }

   public function obtenerWada(array $datos): array
   {
      $keys = ['id'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validar("cedula", $arrayFiltrado['id']);
      return $this->_obtenerWada($arrayFiltrado['id']);
   }

   public function eliminarWada(array $datos): array
   {
      $keys = ['cedula'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validar("cedula", $arrayFiltrado['cedula']);
      return $this->_eliminarWada($arrayFiltrado['cedula']);
   }

   public function listadoWada()
   {
      return $this->_listadoWada();
   }

   private function _incluirWada(string $cedula, bool $estado, string $inscrito, string $ultimaActualizacion, string $vencimiento): array
   {
      $consulta = "SELECT id_atleta FROM wada WHERE id_atleta = :id;";
      $existe = Validar::existe($this->database, $cedula, $consulta);
      if ($existe) {
         ExceptionHandler::throwException("La WADA para este atleta ya existe", 400, \InvalidArgumentException::class);
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
         ExceptionHandler::throwException("Ocurrió un error al incluir la WADA", 500, \Exception::class);
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
         ExceptionHandler::throwException("La WADA del atleta introducido no existe", 404, \InvalidArgumentException::class);
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
         ExceptionHandler::throwException("Ocurrió un error al modificar la WADA", 500, \Exception::class);
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
         ExceptionHandler::throwException("La WADA de este atleta no existe", 400, \InvalidArgumentException::class);
      }
      $consulta = "SELECT * FROM wada WHERE id_atleta = :id_atleta";
      $response = $this->database->query($consulta, [":id_atleta" => $cedula], true);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al obtener la WADA", 500, \Exception::class);
      }
      $resultado["wada"] = $response;
      return $resultado;
   }

   private function _eliminarWada(string $cedula): array
   {
      $consulta = "SELECT id_atleta FROM wada WHERE id_atleta = :id;";
      $existe = Validar::existe($this->database, $cedula, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("La WADA del atleta introducido no existe", 404, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "DELETE FROM wada WHERE id_atleta = :id_atleta";
      $response = $this->database->query($consulta, [':id_atleta' => $cedula]);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al eliminar la WADA", 500, \Exception::class);
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
      return $resultado;
   }
}
