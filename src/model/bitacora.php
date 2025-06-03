<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\Validar;

class Bitacora
{
   private Database $database;
   public function __construct(Database $database)
   {
      $this->database = $database;
   }
   public function listadoBitacora(array $datos): array
   {
      return $this->_listadoBitacora($datos);
   }
   public function obtenerAccion(array $datos): array
   {
      $keys = ['id'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $idAccion = Cipher::aesDecrypt($arrayFiltrado['id']);
      return $this->_obtenerAccion($idAccion);
   }
   private function _listadoBitacora(array $datos): array
   {
      $start = intval($datos['start'] ?? 0);
      $length = intval($datos['length'] ?? 0);
      if ($length < 1) {
         $length = 10;
      } elseif ($length > 100) {
         $length = 100;
      }
      $orderColumnIndex = $datos['order'][0]['column'] ?? 0;
      $orderBy = $datos['columns'][$orderColumnIndex]['data'] ?? 'fecha';
      $orderDir = $datos['order'][0]['dir'] ?? 'desc';
      $totalQuery = $this->database->query("SELECT COUNT(*) as total FROM {$_ENV['SECURE_DB']}.bitacora", uniqueFetch: true);
      $totalRegistros = $totalQuery['total'];

      $sql = "SELECT id_accion, id_usuario, accion, modulo, registro_modificado, fecha, CONCAT(u.nombre,' ', u.apellido) AS nombre_completo FROM ({$_ENV['SECURE_DB']}.bitacora JOIN {$_ENV['SECURE_DB']}.usuarios u ON bitacora.id_usuario = u.cedula) WHERE 1";
      $params = [];

      if (!empty($datos['search']['value'])) {
         $sql .= " AND (id_usuario LIKE :search OR accion LIKE :search OR modulo LIKE :search OR registro_modificado LIKE :search OR fecha LIKE :search)";
         $params[':search'] = "%" . $datos['search']['value'] . "%";
         // calcular registros filtrados
         $sqlFiltro = "SELECT COUNT(*) as total FROM {$_ENV['SECURE_DB']}.bitacora WHERE 1
         AND (id_usuario LIKE :search OR accion LIKE :search OR modulo LIKE :search OR registro_modificado LIKE :search OR fecha LIKE :search)";
         $filtroQuery = $this->database->query($sqlFiltro, $params, true);
         $registrosFiltrados = $filtroQuery['total'];
      } else {
         $registrosFiltrados = $this->database->query("SELECT COUNT(*) as total FROM {$_ENV['SECURE_DB']}.bitacora", uniqueFetch: true)['total'];
      }

      // agregar orden y límite
      $sql .= " ORDER BY $orderBy $orderDir LIMIT $start, $length";
      $response = $this->database->query($sql, $params) ?: [];
      if (!empty($response)) {
         Cipher::encriptarCampoArray($response, 'id_accion', false);
      }
      return [
         "draw" => intval($datos['draw']),
         "recordsTotal" => intval($totalRegistros),
         "recordsFiltered" => intval($registrosFiltrados),
         "data" => $response,
      ];
   }

   private function _obtenerAccion(int $idAccion): array
   {
      $consulta = "SELECT id_accion FROM {$_ENV['SECURE_DB']}.bitacora WHERE id_accion = :id";
      $existe = Validar::existe($this->database, $idAccion, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("No existe la accion", 404, \InvalidArgumentException::class);
      }
      $consulta = "SELECT * FROM {$_ENV['SECURE_DB']}.bitacora WHERE id_accion = :id_accion";
      $valores = [':id_accion' => $idAccion];
      $response = $this->database->query($consulta, $valores, true);
      $resultado['accion'] = $response;
      return $resultado;
   }
}
