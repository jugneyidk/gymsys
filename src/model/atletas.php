<?php

namespace Gymsys\Model;

use Exception;
use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\Validar;

class Atletas
{
   private Database $database;
   private $id_atleta, $nombres, $apellidos, $cedula, $genero, $fecha_nacimiento, $lugar_nacimiento, $peso, $altura, $tipo_atleta, $estado_civil, $telefono, $correo_electronico, $entrenador_asignado, $cedula_representante, $nombre_representante, $telefono_representante, $parentesco_representante, $password, $nombre_tipo_atleta, $tipo_cobro;

   public function __construct(Database $database)
   {
      $this->database = $database;
   }

   public function incluirAtleta(array $datos): array
   {
      $keys = ["nombres", "apellidos", "cedula", "genero", "fecha_nacimiento", "lugar_nacimiento", "peso", "altura", "tipo_atleta", "estado_civil", "telefono", "correo", "entrenador_asignado", "password"];
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
      if (!empty($datos['cedula_representante'])) {
         $rep = $this->existeRepresentante($datos['cedula_representante']);
         if (empty($rep)) {
            $resultadoRepresentante = $this->incluirRepresentante(
               $$datos['cedula_representante'],
               $datos['nombre_representante'],
               $datos['telefono_representante'],
               $datos['parentesco_representante']
            );
         }
      }
      $this->database->beginTransaction();
      $consulta = "INSERT INTO usuarios (cedula, nombre, apellido, genero, fecha_nacimiento, lugar_nacimiento, estado_civil, telefono, correo_electronico)
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
      $this->database->query($consulta, $valores);
      $consultaAtleta = "INSERT INTO atleta (cedula, entrenador, tipo_atleta, peso, altura, representante)
            VALUES (:cedula, :id_entrenador, :tipo_atleta, :peso, :altura, :representante);";
      $valoresAtleta = [
         ':cedula' => $this->cedula,
         ':id_entrenador' => $this->entrenador_asignado,
         ':tipo_atleta' => $this->tipo_atleta,
         ':peso' => $this->peso,
         ':altura' => $this->altura,
         ':representante' => $this->cedula_representante
      ];
      $this->database->query($consultaAtleta, $valoresAtleta);
      $this->database->commit();
      $resultado["mensaje"] = "Atleta incluido con éxito";
      return $resultado;
   }

   public function obtenerAtleta(array $datos)
   {
      $keys = ['id'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validar("cedula", $arrayFiltrado['id']);
      return $this->_obtenerAtleta($arrayFiltrado['id']);
   }

   public function modificar_atleta($datos)
   {
      $validacion = Validar::validar_datos($datos);
      if (is_array($validacion)) {
         $respuesta["ok"] = false;
         $respuesta["mensaje"] = $validacion;
         return $respuesta;
      }
      foreach ($datos as $campo => $valor) {
         if (property_exists($this, $campo)) {
            $this->$campo = $valor;
         }
      }
      return $this->modificar();
   }

   public function eliminarAtleta(array $datos): array
   {
      $keys = ['cedula'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validar("cedula", $arrayFiltrado['cedula']);
      return $this->_eliminarAtleta($arrayFiltrado['cedula']);
   }
   public function eliminar_tipo_atleta($id_tipo)
   {
      $this->tipo_atleta = filter_var($id_tipo, FILTER_SANITIZE_NUMBER_INT);
      return $this->eliminar_tipo();
   }
   public function listadoAtletas(): array
   {
      return $this->_listadoAtletas();
   }
   private function incluir()
   {
      try {
         $consulta = "SELECT cedula FROM atleta WHERE cedula = ?;";
         $existe = Validar::existe($this->conexion, $this->cedula, $consulta);
         if ($existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Ya existe un atleta con esta cédula";
            return $resultado;
         }

         if (!empty($this->cedula_representante)) {
            $rep = $this->existeRepresentante($this->cedula_representante);
            if ($rep) {
               if (!isset($_POST['asignar_representante_existente']) || $_POST['asignar_representante_existente'] != "true") {
                  $resultado["ok"] = false;
                  $resultado["mensaje"] = "El representante con cédula $this->cedula_representante ya existe con el nombre "
                     . $rep['nombre_completo'] . ". ¿Desea asignarlo?";
                  return $resultado;
               }
            } else {
               $resultadoRepresentante = $this->incluirRepresentante(
                  $this->cedula_representante,
                  $this->nombre_representante,
                  $this->telefono_representante,
                  $this->parentesco_representante
               );
               if (!$resultadoRepresentante['ok']) {
                  return ['ok' => false, 'mensaje' => $resultadoRepresentante['mensaje']];
               }
            }
         }


         $this->conexion->beginTransaction();
         $id_rol = 0;
         $token = 0;
         $consulta = "
                INSERT INTO usuarios (cedula, nombre, apellido, genero, fecha_nacimiento, lugar_nacimiento, estado_civil, telefono, correo_electronico)
                VALUES (:cedula, :nombre, :apellido, :genero, :fecha_nacimiento, :lugar_nacimiento, :estado_civil, :telefono, :correo_electronico);
    
                INSERT INTO atleta (cedula, entrenador, tipo_atleta, peso, altura, representante)
                VALUES (:cedula, :id_entrenador, :tipo_atleta, :peso, :altura, :representante);
    
                INSERT INTO usuarios_roles (id_usuario, id_rol, password, token)
                VALUES (:cedula, :id_rol, :password, :token);
            ";
         $valores = array(
            ':cedula' => $this->cedula,
            ':nombre' => $this->nombres,
            ':apellido' => $this->apellidos,
            ':genero' => $this->genero,
            ':fecha_nacimiento' => $this->fecha_nacimiento,
            ':lugar_nacimiento' => $this->lugar_nacimiento,
            ':estado_civil' => $this->estado_civil,
            ':telefono' => $this->telefono,
            ':correo_electronico' => $this->correo_electronico,
            ':id_entrenador' => $this->entrenador_asignado,
            ':tipo_atleta' => $this->tipo_atleta,
            ':peso' => $this->peso,
            ':altura' => $this->altura,
            ':id_rol' => $id_rol,
            ':password' => password_hash($this->password, PASSWORD_DEFAULT),
            ':token' => $token,
            ':representante' => $this->cedula_representante
         );
         $respuesta = $this->conexion->prepare($consulta);
         $respuesta->execute($valores);
         $respuesta->closeCursor();
         $this->conexion->commit();
         $resultado["ok"] = true;
      } catch (PDOException $e) {
         $this->conexion->rollBack();
         $resultado["ok"] = false;
         $resultado["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $resultado;
   }


   private function _listadoAtletas(): array
   {
      $consulta = "SELECT * FROM lista_atletas";
      $response = $this->database->query($consulta);
      $resultado["atletas"] = $response;
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
                    a.entrenador
                FROM atleta a
                INNER JOIN usuarios u ON a.cedula = u.cedula
                WHERE u.cedula = :cedula";
      $valores = [':cedula' => $cedula];
      $response = $this->database->query($consulta, $valores, true);
      $resultado['atleta'] = $response;
      return $resultado;
   }

   private function modificar()
   {
      try {
         $consulta = "SELECT cedula FROM atleta WHERE cedula = ?;";
         $existe = Validar::existe($this->conexion, $this->cedula, $consulta);
         if (!$existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "No existe ningun atleta con esta cedula";
            return $resultado;
         }
         if (!empty($this->cedula_representante)) {
            $resultadoRepresentante = $this->incluirRepresentante($this->cedula_representante, $this->nombre_representante, $this->telefono_representante, $this->parentesco_representante);
            if (!$resultadoRepresentante['ok']) {
               return ['ok' => false, 'mensaje' => $resultadoRepresentante['mensaje']];
            }
         }
         $this->conexion->beginTransaction();
         $consulta = "
                UPDATE usuarios 
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
                    altura = :altura 
                WHERE cedula = :cedula;
            ";

         $valores = array(
            ':cedula' => $this->cedula,
            ':nombre' => $this->nombres,
            ':apellido' => $this->apellidos,
            ':genero' => $this->genero,
            ':fecha_nacimiento' => $this->fecha_nacimiento,
            ':lugar_nacimiento' => $this->lugar_nacimiento,
            ':estado_civil' => $this->estado_civil,
            ':telefono' => $this->telefono,
            ':correo_electronico' => $this->correo_electronico,
            ':id_entrenador' => $this->entrenador_asignado,
            ':tipo_atleta' => $this->tipo_atleta,
            ':peso' => $this->peso,
            ':altura' => $this->altura
         );

         $respuesta1 = $this->conexion->prepare($consulta);
         $respuesta1->execute($valores);
         $respuesta1->closeCursor();

         if ($this->password !== null) {
            $consulta_password = "
                    UPDATE usuarios_roles
                    SET password = :password
                    WHERE id_usuario = :cedula;
                ";
            $valores_password = array(
               ':cedula' => $this->cedula,
               ':password' => password_hash($this->password, PASSWORD_DEFAULT)
            );
            $respuesta2 = $this->conexion->prepare($consulta_password);
            $respuesta2->execute($valores_password);
            $respuesta2->closeCursor();
         }
         $this->conexion->commit();
         $resultado["ok"] = true;
      } catch (PDOException $e) {
         $this->conexion->rollBack();
         $resultado["ok"] = false;
         $resultado["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
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
         "DELETE FROM usuarios_roles WHERE id_usuario = :cedula",
         "DELETE FROM atleta WHERE cedula = :cedula",
         "DELETE FROM usuarios WHERE cedula = :cedula;"
      ];
      foreach ($consultas as $consulta) {
         $response = $this->database->query($consulta, [':cedula' => $cedula]);
         if ($response === false) {
            $this->database->rollBack();
            ExceptionHandler::throwException("Ocurrió un error al eliminar el atleta", 500, \RuntimeException::class);
         }
      }
      $this->database->commit();
      $resultado['mensaje'] = "El atleta se ha eliminado exitosamente";
      return $resultado;
   }
   public function obtenerTiposAtleta()
   {
      try {
         $consulta = "SELECT id_tipo_atleta, nombre_tipo_atleta FROM tipo_atleta";
         $respuesta = $this->conexion->prepare($consulta);
         $respuesta->execute();
         $tiposAtleta = $respuesta->fetchAll(PDO::FETCH_ASSOC);
         $resultado["ok"] = true;
         $resultado["tipos"] = $tiposAtleta;
      } catch (PDOException $e) {
         $resultado["ok"] = false;
         $resultado["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $resultado;
   }
   private function existeRepresentante($cedula)
   {
      try {
         $consulta = "SELECT cedula, nombre_completo FROM representantes WHERE cedula = ?";
         $stmt = $this->conexion->prepare($consulta);
         $stmt->execute([$cedula]);
         return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve los datos si existe o false
      } catch (PDOException $e) {
         return false;
      }
   }

   public function incluirRepresentante($cedula, $nombreCompleto, $telefono, $parentesco)
   {
      try {
         $this->conexion->beginTransaction();
         $consulta = "INSERT INTO representantes (cedula, nombre_completo, telefono, parentesco) VALUES (:cedula, :nombreCompleto, :telefono, :parentesco)";
         // $consulta = "INSERT INTO representantes (cedula, nombre_completo, telefono, parentesco) 
         //     VALUES (:cedula, :nombreCompleto, :telefono, :parentesco)
         //     ON DUPLICATE KEY UPDATE 
         //     nombre_completo = VALUES(nombre_completo), 
         //     telefono = VALUES(telefono), 
         //     parentesco = VALUES(parentesco);";
         $valores = array(
            ':cedula' => $cedula,
            ':nombreCompleto' => $nombreCompleto,
            ':telefono' => $telefono,
            ':parentesco' => $parentesco
         );
         $respuesta = $this->conexion->prepare($consulta);
         $respuesta->execute($valores);
         $respuesta->closeCursor();
         $this->conexion->commit();
         $resultado["ok"] = true;
      } catch (PDOException $e) {
         $this->conexion->rollBack();
         $resultado["ok"] = false;
         $resultado["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $resultado;
   }
   public function registrarTipoAtleta($nombreTipoAtleta, $tipoCobro)
   {
      if (!filter_var($tipoCobro, FILTER_VALIDATE_FLOAT)) {
         return ["ok" => false, "mensaje" => "El tipo de cobro no es válido"];
      }
      $validacion = Validar::validar("nombre_tipo", $nombreTipoAtleta);
      if (!$validacion["ok"]) {
         return ["ok" => false, "mensaje" => "El nombre de tipo de atleta no es válido"];
      }
      $this->nombre_tipo_atleta = $nombreTipoAtleta;
      $this->tipo_cobro = $tipoCobro;
      return $this->registrar_tipo();
   }

   private function registrar_tipo()
   {
      try {
         $consulta = "SELECT id_tipo_atleta FROM tipo_atleta WHERE nombre_tipo_atleta = ?;";
         $existe = Validar::existe($this->conexion, $this->nombre_tipo_atleta, $consulta);
         if ($existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Ya existe un tipo de atleta con este nombre";
            return $resultado;
         }
         $this->conexion->beginTransaction();
         $consulta = "INSERT INTO tipo_atleta (nombre_tipo_atleta, tipo_cobro) VALUES (?, ?)";
         $respuesta = $this->conexion->prepare($consulta);
         $respuesta->execute([$this->nombre_tipo_atleta, $this->tipo_cobro]);
         $respuesta->closeCursor();
         $this->conexion->commit();
         $resultado["ok"] = true;
      } catch (PDOException $e) {
         $this->conexion->rollBack();
         $resultado["ok"] = false;
         $resultado["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $resultado;
   }
   private function eliminar_tipo()
   {
      try {
         $consulta = "SELECT id_tipo_atleta FROM tipo_atleta WHERE id_tipo_atleta = ?;";
         $existe = Validar::existe($this->conexion, $this->tipo_atleta, $consulta);
         if (!$existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "No existe ningún tipo de atleta con esta ID";
            return $resultado;
         }
         $this->conexion->beginTransaction();
         $consulta = "
                DELETE FROM tipo_atleta WHERE id_tipo_atleta = :tipo_atleta;
            ";
         $valores = array(':tipo_atleta' => $this->tipo_atleta);
         $respuesta = $this->conexion->prepare($consulta);
         $respuesta->execute($valores);
         $respuesta->closeCursor();
         $this->conexion->commit();
         $resultado["ok"] = true;
      } catch (PDOException $e) {
         $this->conexion->rollBack();
         $resultado["ok"] = false;
         $resultado["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $resultado;
   }

   public function __get($propiedad)
   {
      return $this->$propiedad;
   }

   public function __set($propiedad, $valor)
   {
      $this->$propiedad = $valor;
      return $this;
   }
}
