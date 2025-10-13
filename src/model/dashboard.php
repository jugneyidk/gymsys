<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;

class Dashboard
{
   private Database $database;

   public function __construct(Database $database)
   {
      $this->database = $database;
   }
   public function obtenerDatosSistema(): array
   {
      return $this->_obtenerDatosSistema();
   }
   public function _obtenerDatosSistema(): array
   {
      $consulta = "SELECT * FROM estadisticas_dashboard;";
      $response = $this->database->query($consulta, uniqueFetch: true);
      if (empty($response)) {
         ExceptionHandler::throwException("No se encontraron estadisticas", \UnexpectedValueException::class, 403);
      }
      $resultado["estadisticas"] = $response ?: ["total_atletas" => 0, "total_entrenadores" => 0,  "total_wadas_pendientes" => 0, "total_deudores" => 0];
      return $resultado;
   }
}
