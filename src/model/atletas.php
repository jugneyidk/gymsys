<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\Validar;
use Gymsys\Model\Representantes;

class Atletas
{
   private Database $database;

   public function __construct(Database $database)
   {
      $this->database = $database;
   }

   public function incluirAtleta(array $datos): array
   {
      $keys = ["nombres", "apellidos", "cedula", "genero", "fecha_nacimiento", "lugar_nacimiento", "peso", "altura", "tipo_atleta", "estado_civil", "telefono", "correo_electronico", "entrenador_asignado", "password"];
      if (!empty($datos['fecha_nacimiento'])) {
         Validar::validarFecha($datos['fecha_nacimiento']);
         $hoy = new \DateTime();
         $nacimiento = new \DateTime($datos['fecha_nacimiento']);
         $edad = $hoy->diff($nacimiento)->y;
         if ($edad < 18) {
            array_push($keys, "nombre_representante", "cedula_representante", "telefono_representante", "parentesco_representante");
         }
      }
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $arrayFiltrado['entrenador_asignado'] = Cipher::aesDecrypt($arrayFiltrado['entrenador_asignado']);
      $arrayFiltrado['tipo_atleta'] = Cipher::aesDecrypt($arrayFiltrado['tipo_atleta']);
      Validar::validarDatos($arrayFiltrado);
      return $this->_incluirAtleta($arrayFiltrado);
   }
   private function _incluirAtleta(array $datos): array
   {
      $consulta = "SELECT cedula FROM atleta WHERE cedula = :id;";
      $existe = Validar::existe($this->database, $datos['cedula'], $consulta);
      if ($existe) {
         ExceptionHandler::throwException("El atleta ingresado ya existe", 400, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      if (!empty($datos['cedula_representante'])) {
         $rep =  new Representantes($this->database);
         $representante = [
            'cedula' => $datos['cedula_representante'],
            'nombre' => $datos['nombre_representante'] ?: null,
            'telefono' => $datos['telefono_representante'] ?: null,
            'parentesco' => $datos['parentesco_representante'] ?: null
         ];
         $existeRepresentante = $rep->incluirRepresentante($representante);
      }
      $consultaUsuario = "INSERT INTO {$_ENV['SECURE_DB']}.usuarios (cedula, nombre, apellido, genero, fecha_nacimiento, lugar_nacimiento, estado_civil, telefono, correo_electronico)
            VALUES (:cedula, :nombre, :apellido, :genero, :fecha_nacimiento, :lugar_nacimiento, :estado_civil, :telefono, :correo_electronico);";
      $valores = [
         ':cedula' => $datos['cedula'],
         ':nombre' => $datos['nombres'],
         ':apellido' => $datos['apellidos'],
         ':genero' => $datos['genero'],
         ':fecha_nacimiento' => $datos['fecha_nacimiento'],
         ':lugar_nacimiento' => $datos['lugar_nacimiento'],
         ':estado_civil' => $datos['estado_civil'],
         ':telefono' => $datos['telefono'],
         ':correo_electronico' => $datos['correo_electronico']
      ];
      $response = $this->database->query($consultaUsuario, $valores);
      if (empty($response)) ExceptionHandler::throwException("Ocurrió un error al incluir el usuario", 500, \Exception::class);
      $consultaAtleta = "INSERT INTO atleta (cedula, entrenador, tipo_atleta, peso, altura, representante)
            VALUES (:cedula, :id_entrenador, :tipo_atleta, :peso, :altura, :representante);";
      $valoresAtleta = [
         ':cedula' => $datos['cedula'],
         ':id_entrenador' => $datos['entrenador_asignado'],
         ':tipo_atleta' => $datos['tipo_atleta'],
         ':peso' => $datos['peso'],
         ':altura' => $datos['altura'],
         ':representante' => $existeRepresentante ? $representante['cedula'] : null,
      ];
      $response = $this->database->query($consultaAtleta, $valoresAtleta);
      if (empty($response)) ExceptionHandler::throwException("Ocurrió un error al incluir el atleta", 500, \Exception::class);
      $this->database->commit();
      $resultado["mensaje"] = "Atleta incluido con éxito";
      return $resultado;
   }

   public function obtenerAtleta(array $datos): array
   {
      $keys = ['id'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $cedula = Cipher::aesDecrypt($arrayFiltrado['id']);
      Validar::validar("cedula", $cedula);
      return $this->_obtenerAtleta($cedula);
   }

   public function modificarAtleta(array $datos): array
   {
      $keys = ["nombres", "apellidos", "cedula", "genero", "fecha_nacimiento", "lugar_nacimiento", "peso", "altura", "tipo_atleta", "estado_civil", "telefono", "correo_electronico", "entrenador_asignado"];
      if (!empty($datos['fecha_nacimiento'])) {
         Validar::validarFecha($datos['fecha_nacimiento']);
         $hoy = new \DateTime();
         $nacimiento = new \DateTime($datos['fecha_nacimiento']);
         $edad = $hoy->diff($nacimiento)->y;
         if ($edad < 18) {
            array_push($keys, "nombre_representante", "cedula_representante", "telefono_representante", "parentesco_representante");
         }
      }
      if (!empty($datos['modificar_contraseña']) && !empty($datos['password'])) {
         $keys[] = "password";
      }
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $arrayFiltrado['entrenador_asignado'] = Cipher::aesDecrypt($arrayFiltrado['entrenador_asignado']);
      $arrayFiltrado['tipo_atleta'] = Cipher::aesDecrypt($arrayFiltrado['tipo_atleta']);
      Validar::validarDatos($arrayFiltrado);
      return $this->_modificarAtleta($arrayFiltrado);
   }

   public function eliminarAtleta(array $datos): array
   {
      $keys = ['cedula'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $cedula = Cipher::aesDecrypt($arrayFiltrado['cedula']);
      Validar::validar("cedula", $cedula);
      return $this->_eliminarAtleta($cedula);
   }

   public function listadoAtletas(): array
   {
      return $this->_listadoAtletas();
   }
   private function _listadoAtletas(): array
   {
      $consulta = "SELECT * FROM lista_atletas";
      $response = $this->database->query($consulta);
      $resultado["atletas"] = $response ?: [];
      if (!empty($resultado["atletas"])) {
         Cipher::encriptarCampoArray($resultado["atletas"], "cedula");
         Cipher::encriptarCampoArray($resultado["atletas"], "tipo_atleta", false);
         Cipher::crearHashArray($resultado["atletas"], "cedula");
      }
      return $resultado;
   }

   private function _obtenerAtleta(string $cedula): array
   {
      $consulta = "SELECT cedula FROM atleta WHERE cedula = :id;";
      $existe = Validar::existe($this->database, $cedula, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("No existe el atleta introducido", 404, \InvalidArgumentException::class);
      }
      $consulta = "SELECT u.cedula, 
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
                    r.cedula AS cedula_representante,
                    r.nombre_completo AS nombre_representante,
                    r.telefono AS telefono_representante,
                    r.parentesco AS parentesco_representante
                FROM atleta a
                INNER JOIN {$_ENV['SECURE_DB']}.usuarios u ON a.cedula = u.cedula
                LEFT JOIN representantes r ON a.representante = r.cedula
                WHERE u.cedula = :cedula";
      $valores = [':cedula' => $cedula];
      $response = $this->database->query($consulta, $valores, true);
      $resultado['atleta'] = $response;
      if (!empty($resultado['atleta'])) {
         Cipher::encriptarCampoArray($resultado, "entrenador");
         Cipher::crearHashArray($resultado, "entrenador", false);
         Cipher::encriptarCampoArray($resultado, "id_tipo_atleta");
         Cipher::crearHashArray($resultado, "id_tipo_atleta", false);
      }
      return $resultado;
   }

   private function _modificarAtleta(array $datos): array
   {
      $consulta = "SELECT cedula FROM atleta WHERE cedula = :id;";
      $existe = Validar::existe($this->database, $datos['cedula'], $consulta);
      if (!$existe) {
         $resultado["ok"] = false;
         $resultado["mensaje"] = "No existe ningun atleta con esta cedula";
         return $resultado;
      }
      if (!empty($datos['cedula_representante'])) {
         $representante = [
            'cedula' => $datos['cedula_representante'],
            'nombre' => $datos['nombre_representante'] ?: null,
            'telefono' => $datos['telefono_representante'] ?: null,
            'parentesco' => $datos['parentesco_representante'] ?: null
         ];
         $rep = new Representantes($this->database);
         $existeRepresentante = $rep->incluirRepresentante($representante);
      }
      $this->database->beginTransaction();
      $consulta = "UPDATE {$_ENV['SECURE_DB']}.usuarios 
                     SET 
                        nombre = :nombre, 
                        apellido = :apellido, 
                        genero = :genero, 
                        fecha_nacimiento = :fecha_nacimiento, 
                        lugar_nacimiento = :lugar_nacimiento, 
                        estado_civil = :estado_civil, 
                        telefono = :telefono, 
                        correo_electronico = :correo_electronico 
                     WHERE cedula = :cedula;        
                     UPDATE atleta 
                     SET 
                        entrenador = :id_entrenador, 
                        tipo_atleta = :tipo_atleta, 
                        peso = :peso, 
                        altura = :altura,
                        representante = :representante
                     WHERE cedula = :cedula;";
      $valores = [
         ':cedula' => $datos['cedula'],
         ':nombre' => $datos['nombres'],
         ':apellido' => $datos['apellidos'],
         ':genero' => $datos['genero'],
         ':fecha_nacimiento' => $datos['fecha_nacimiento'],
         ':lugar_nacimiento' => $datos['lugar_nacimiento'],
         ':estado_civil' => $datos['estado_civil'],
         ':telefono' => $datos['telefono'],
         ':correo_electronico' => $datos['correo_electronico'],
         ':id_entrenador' => $datos['entrenador_asignado'],
         ':tipo_atleta' => $datos['tipo_atleta'],
         ':peso' => $datos['peso'],
         ':altura' => $datos['altura'],
         ':representante' => !empty($existeRepresentante) ? $representante['cedula'] : null
      ];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("No se pudo modificar el atleta", 500, \Exception::class);
      }
      if ($datos['password'] !== null) {
         $consultaPassword = "UPDATE {$_ENV['SECURE_DB']}.usuarios_roles
                    SET password = :password
                    WHERE id_usuario = :cedula;";
         $valoresPassword = [
            ':cedula' => $datos['cedula'],
            ':password' => password_hash($datos['password'], PASSWORD_DEFAULT)
         ];
         $response = $this->database->query($consultaPassword, $valoresPassword);
         if (empty($response)) {
            ExceptionHandler::throwException("No se pudo modificar el atleta", 500, \Exception::class);
         }
      }
      $this->database->commit();
      $resultado["mensaje"] = "El atleta se ha modificado exitosamente";
      return $resultado;
   }

   private function _eliminarAtleta(string $cedula): array
   {
      $consulta = "SELECT cedula FROM atleta WHERE cedula = :id;";
      $existe = Validar::existe($this->database, $cedula, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("El atleta introducido no existe", 404, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consultas = [
         "DELETE FROM atleta WHERE cedula = :cedula",
         "DELETE FROM {$_ENV['SECURE_DB']}.usuarios WHERE cedula = :cedula;"
      ];
      foreach ($consultas as $consulta) {
         $response = $this->database->query($consulta, [':cedula' => $cedula]);
         if (empty($response)) {
            ExceptionHandler::throwException("Ocurrió un error al eliminar el atleta", 500, \RuntimeException::class);
         }
      }
      $this->database->commit();
      $resultado['mensaje'] = "El atleta se ha eliminado exitosamente";
      return $resultado;
   }
}
