<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\Validar;

class Mensualidad
{
   private Database $database;

   public function __construct(Database $database)
   {
      $this->database = $database;
   }
   public function incluirMensualidad(array $datos): array
   {
      $keys = ['id_atleta', 'fecha', 'monto'];
      if (!empty($datos['detalles'])) {
         $keys[] = 'detalles';
      }
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validar("cedula", $arrayFiltrado['id_atleta']);
      Validar::validarFecha($arrayFiltrado['fecha']);
      Validar::validar("detalles", $arrayFiltrado['detalles']);
      if (!filter_var((float)$arrayFiltrado['monto'], FILTER_VALIDATE_FLOAT) && $arrayFiltrado['monto'] < 0) {
         ExceptionHandler::throwException("El monto no es válido", 400, \InvalidArgumentException::class);
      }
      return $this->_incluirMensualidad($arrayFiltrado);
   }
   public function eliminarMensualidad(array $datos): array
   {
      $id = filter_var($datos['id'], FILTER_SANITIZE_NUMBER_INT);
      if (empty($id)) {
         ExceptionHandler::throwException("El parametro 'id' es inválido", 400, \InvalidArgumentException::class);
      }
      return $this->_eliminarMensualidad($id);
   }

   public function listadoMensualidades(): array
   {
      return $this->_listadoMensualidades();
   }

   public function listadoDeudores(): array
   {
      return $this->_listadoDeudores();
   }

   private function _incluirMensualidad(array $datos): array
   {
      $this->database->beginTransaction();
      $existeValidacion = $this->existeMensualidadEnMes($datos);
      if (!empty($existeValidacion)) {
         ExceptionHandler::throwException("Ya existe una mensualidad para este atleta en ese mes", 400, \InvalidArgumentException::class);
      }
      $consulta = "INSERT INTO mensualidades (id_atleta, monto, fecha, detalles) 
                     VALUES (:id_atleta, :monto, :fecha, :detalles)";
      $valores = [
         ':id_atleta' => $datos['id_atleta'],
         ':monto' => (float) $datos['monto'],
         ':fecha' => $datos['fecha'],
         ':detalles' => $datos['detalles'] ?: null
      ];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrio un error al agregar la mensualidad", 500, \Exception::class);
      }
      $this->database->commit();
      $resultado["mensaje"] = "La mensualidad se agrego correctamente";
      return $resultado;
   }
   private function _eliminarMensualidad(int $id): array
   {
      $consulta = "SELECT id_mensualidad FROM mensualidades WHERE id_mensualidad = :id;";
      $existe = Validar::existe($this->database, $id, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("La mensualidad ingresada no existe", 404, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "DELETE FROM mensualidades WHERE id_mensualidad = :id";
      $valores = [':id' => $id];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al eliminar la mensualidad", 500, \Exception::class);
      }
      $this->database->commit();
      $resultado["mensaje"] = "La mensualidad se eliminó exitosamente";
      return $resultado;
   }

   private function _listadoMensualidades(): array
   {
      $consulta = "SELECT * FROM lista_mensualidades;";
      $response = $this->database->query($consulta);
      $resultado["mensualidades"] = $response ?: [];
      return $resultado;
   }

   private function _listadoDeudores(): array
   {
      $consulta = "SELECT * FROM lista_deudores;";
      $response = $this->database->query($consulta);
      $resultado["deudores"] = $response ?: [];
      return $resultado;
   }
   private function existeMensualidadEnMes(array $datos): bool
   {
      $consultaValidacion = "SELECT 1
                              FROM mensualidades
                              WHERE id_atleta = :id_atleta
                              AND MONTH(fecha) = MONTH(:fecha)
                              AND YEAR(fecha) = YEAR(:fecha)
                           LIMIT 1;";
      $responseValidar = $this->database->query($consultaValidacion, [':id_atleta' => $datos['id_atleta'], ':fecha' => $datos['fecha']], true);
      return (bool) $responseValidar;
   }
}
