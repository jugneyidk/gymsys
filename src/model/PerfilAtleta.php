<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\Validar;

class PerfilAtleta
{
   private Database $database;

   public function __construct(Database $database)
   {
      $this->database = $database;
   }

   public function obtenerPerfilUsuario(array $datos): array
   {
      $keys = ["cedula"];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validar("cedula", $arrayFiltrado['cedula']);
      return $this->_obtenerPerfilUsuario($arrayFiltrado['cedula']);
   }

   private function _obtenerPerfilUsuario(string $cedula): array
   {
      $consulta = "SELECT cedula FROM atleta WHERE cedula = :id;";
      $existe = Validar::existe($this->database, $cedula, $consulta);
      if (empty($existe)) {
         ExceptionHandler::throwException("No existe el usuario introducido", 404, \InvalidArgumentException::class);
      }
      $consulta = "SELECT 
                        u.cedula, 
                        u.nombre, 
                        u.apellido, 
                        u.genero, 
                        u.fecha_nacimiento, 
                        u.lugar_nacimiento, 
                        u.estado_civil, 
                        u.telefono, 
                        u.correo_electronico, 
                        a.tipo_atleta AS id_tipo_atleta, 
                        a.peso, 
                        a.altura, 
                        a.entrenador, 
                        ue.nombre AS nombre_entrenador, 
                        CONCAT(ue.nombre, ' ', ue.apellido) AS nombre_entrenador
                     FROM atleta a
                     INNER JOIN usuarios u ON a.cedula = u.cedula
                     LEFT JOIN usuarios ue ON a.entrenador = ue.cedula
                     WHERE u.cedula = :cedula;";
      $valores = [':cedula' => $cedula];
      $response = $this->database->query($consulta, $valores, true);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al obtener el usuario", 500, \Exception::class);
      }
      $resultado["usuario"] = $response;
      return $resultado;
   }
}
