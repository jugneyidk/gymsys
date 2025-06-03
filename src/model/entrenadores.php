<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\Validar;

class Entrenadores
{
   private Database $database;

   public function __construct(Database $database)
   {
      $this->database = $database;
   }

   public function incluirEntrenador(array $datos): array
   {
      $keys = ["nombres", "apellidos", "cedula", "genero", "fecha_nacimiento", "lugar_nacimiento", "estado_civil", "telefono", "correo_electronico", "grado_instruccion", "password"];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validarDatos($arrayFiltrado);
      return $this->_incluirEntrenador($arrayFiltrado);
   }

   public function modificarEntrenador(array $datos): array
   {
      $keys = ["nombres", "apellidos", "cedula", "cedula_original", "genero", "fecha_nacimiento", "lugar_nacimiento", "estado_civil", "telefono", "correo_electronico", "grado_instruccion"];
      if (!empty($datos['modificar_contraseña']) && !empty($datos['password'])) {
         $keys[] = "password";
      }
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $arrayFiltrado['cedula_original'] = Cipher::aesDecrypt($arrayFiltrado['cedula_original']);
      Validar::validarDatos($arrayFiltrado);
      return $this->_modificarEntrenador($arrayFiltrado);
   }

   public function obtenerEntrenador(array $datos): array
   {
      $keys = ["id"];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $cedula = Cipher::aesDecrypt($arrayFiltrado['id']);
      Validar::validar("cedula", $cedula);
      return $this->_obtenerEntrenador($cedula);
   }

   public function listadoEntrenadores(): array
   {
      return $this->_listadoEntrenadores();
   }

   public function eliminarEntrenador(array $datos): array
   {
      $keys = ["cedula"];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $cedula = Cipher::aesDecrypt($arrayFiltrado['cedula']);
      Validar::validar("cedula", $cedula);
      return $this->_eliminarEntrenador($cedula);
   }

   private function _obtenerEntrenador(string $cedula): array
   {
      $consulta = "SELECT cedula FROM entrenador WHERE cedula = :id;";
      $existe = Validar::existe($this->database, $cedula, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("El entrenador ingresado no existe", 404, \InvalidArgumentException::class);
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
                    e.grado_instruccion
                FROM entrenador e
                INNER JOIN {$_ENV['SECURE_DB']}.usuarios u ON e.cedula = u.cedula
                WHERE u.cedula = :cedula";
      $valores = [':cedula' => $cedula];
      $response['entrenador'] = $this->database->query($consulta, $valores, true);
      if (!empty($response['entrenador'])) {
         Cipher::encriptarCampoArray($response, "cedula");
      }
      return $response;
   }
   private function _eliminarEntrenador(string $cedula): array
   {
      $consulta = "SELECT cedula FROM entrenador WHERE cedula = :id;";
      $existe = Validar::existe($this->database, $cedula, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("El entrenador ingresado no existe", 404, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consultas = [
         "DELETE FROM {$_ENV['SECURE_DB']}.usuarios_roles WHERE id_usuario = :cedula;",
         "DELETE FROM entrenador WHERE cedula = :cedula;",
         "DELETE FROM {$_ENV['SECURE_DB']}.usuarios WHERE cedula = :cedula;"
      ];
      foreach ($consultas as $consulta) {
         $response = $this->database->query($consulta, [':cedula' => $cedula]);
         if (!$response) {
            ExceptionHandler::throwException("Ocurrió un error al eliminar el entrenador", 500, \RuntimeException::class);
         }
      }
      $this->database->commit();
      $resultado["mensaje"] = "El entrenador se eliminó correctamente";
      return $resultado;
   }
   private function _incluirEntrenador(array $datos): array
   {
      $consulta = "SELECT cedula FROM entrenador WHERE cedula = :id;";
      $existe = Validar::existe($this->database, $datos['cedula'], $consulta);
      if ($existe) {
         ExceptionHandler::throwException("El entrenador ya existe", 400, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $id_rol = 1;
      $token = 0;
      $valores =
         [
            ':cedula' => $datos['cedula'],
            ':nombre' => $datos['nombres'],
            ':apellido' => $datos['apellidos'],
            ':genero' => $datos['genero'],
            ':fecha_nacimiento' => $datos['fecha_nacimiento'],
            ':lugar_nacimiento' => $datos['lugar_nacimiento'],
            ':estado_civil' => $datos['estado_civil'],
            ':telefono' => $datos['telefono'],
            ':correo' => $datos['correo_electronico'],
            ':grado_instruccion' => $datos['grado_instruccion'],
            ':id_rol' => $id_rol,
            ':password' => password_hash($datos['password'], PASSWORD_DEFAULT),
            ':token' => $token
         ];
      $consultas = [
         "INSERT INTO {$_ENV['SECURE_DB']}.usuarios (cedula, nombre, apellido, genero, fecha_nacimiento, lugar_nacimiento, estado_civil, telefono, correo_electronico)
             VALUES (:cedula, :nombre, :apellido, :genero, :fecha_nacimiento, :lugar_nacimiento, :estado_civil, :telefono, :correo);",
         "INSERT INTO entrenador (cedula, grado_instruccion)
             VALUES (:cedula, :grado_instruccion);",
         "INSERT INTO {$_ENV['SECURE_DB']}.usuarios_roles (id_usuario, id_rol, password, token)
             VALUES (:cedula, :id_rol, :password, :token);"
      ];
      foreach ($consultas as $consulta) {
         $response = $this->database->query($consulta, $valores);
         if ($response === false) {
            ExceptionHandler::throwException("Ocurrió un error al incluir el entrenador", 500, \RuntimeException::class);
         }
      }
      $this->database->commit();
      $resultado["mensaje"] = "Entrenador agregado con éxito";
      return $resultado;
   }

   private function _modificarEntrenador(array $datos): array
   {
      $consulta = "SELECT cedula FROM entrenador WHERE cedula = :id;";
      $existe = Validar::existe($this->database, $datos['cedula_original'], $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("No existe el entrenador", 404, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "UPDATE {$_ENV['SECURE_DB']}.usuarios SET 
                  cedula = :cedula,
                  nombre = :nombre, 
                  apellido = :apellido, 
                  genero = :genero, 
                  fecha_nacimiento = :fecha_nacimiento, 
                  lugar_nacimiento = :lugar_nacimiento, 
                  estado_civil = :estado_civil, 
                  telefono = :telefono, 
                  correo_electronico = :correo 
               WHERE cedula = :cedula_original;
               UPDATE entrenador SET grado_instruccion = :grado_instruccion WHERE cedula = :cedula_original;";
      $valores = [
         ':cedula' => $datos['cedula'],
         ':cedula_original' => $datos['cedula_original'],
         ':nombre' => $datos['nombres'],
         ':apellido' => $datos['apellidos'],
         ':genero' => $datos['genero'],
         ':fecha_nacimiento' => $datos['fecha_nacimiento'],
         ':lugar_nacimiento' => $datos['lugar_nacimiento'],
         ':estado_civil' => $datos['estado_civil'],
         ':telefono' => $datos['telefono'],
         ':correo' => $datos['correo_electronico'],
         ':grado_instruccion' => $datos['grado_instruccion']
      ];
      $response = $this->database->query($consulta, $valores);
      if ($response === false) {
         ExceptionHandler::throwException("Ocurrió un error al modificar el entrenador", 500, \RuntimeException::class);
      }
      if (!empty($datos['password'])) {
         $consultaPassword = "UPDATE {$_ENV['SECURE_DB']}.usuarios_roles
                                 SET `password` = :password
                                 WHERE id_usuario = :cedula;";
         $valoresPassword = [
            ':cedula' => $datos['cedula'],
            ':password' => password_hash($datos['password'], PASSWORD_DEFAULT)
         ];
         $responseRoles = $this->database->query($consultaPassword, $valoresPassword);
         if (empty($responseRoles)) {
            ExceptionHandler::throwException("Ocurrió un error al modificar el entrenador", 500, \RuntimeException::class);
         }
      }
      $this->database->commit();
      $resultado["mensaje"] = "El entrenador se ha modificado exitosamente";
      return $resultado;
   }
   private function _listadoEntrenadores(): array
   {
      $consulta = "SELECT * FROM lista_entrenadores";
      $respuesta["entrenadores"] = $this->database->query($consulta) ?: [];
      if (!empty($respuesta["entrenadores"])) {
         Cipher::encriptarCampoArray($respuesta["entrenadores"], "cedula");
         Cipher::crearHashArray($respuesta["entrenadores"], "cedula");
      }
      return $respuesta;
   }
   public function listadoGradosInstruccion(): array
   {
      $consulta = "SELECT DISTINCT grado_instruccion FROM entrenador;";
      $response = $this->database->query($consulta);
      if (empty($response)) {
         ExceptionHandler::throwException("No se encontraron grados de instrucción", 404, \RuntimeException::class);
      }
      $resultado['grados'] = $response;
      return $resultado;
   }
}
