<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use Gymsys\Utils\ExceptionHandler;
use Gymsys\Utils\Validar;

class Rolespermisos
{
   private Database $database;
   private static $routes;
   private array $modulos;

   public function __construct(Database $database)
   {
      $this->database = $database;
      $this->modulos = [
         'entrenadores' => 1,
         'atletas' => 2,
         'rolespermisos' => 3,
         'asistencias' => 4,
         'eventos' => 5,
         'mensualidad' => 6,
         'wada' => 7,
         'reportes' => 8,
         'bitacora' => 9,
      ];
   }
   public static function obtenerPermisosModulo(string $modulo, Database $database): array
   {
      self::$routes = require dirname(__DIR__) . "/core/routes.php";
      $esPublica = !empty(self::$routes[$modulo]['public']);
      $obj = new self($database);
      $idUsuario = defined('ID_USUARIO') && !empty(ID_USUARIO)
         ? ID_USUARIO
         : null;
      $idRol = null;
      if ($idUsuario) {
         $rolInfo = $obj->obtenerRolUsuario(['id' => $idUsuario]);
         $idRol = Cipher::aesDecrypt($rolInfo['rol']['id_rol']) ?? null;
      }
      if (empty($idRol)) {
         if ($esPublica) {
            // Ruta pública + sin usuario da permiso de sólo lectura
            return [
               'leer'      => 1,
            ];
         }
         // Ruta protegida + sin rol denega acceso
         ExceptionHandler::throwException(
            "Acceso no autorizado",
            403,
            \UnexpectedValueException::class
         );
      }
      $sql = "SELECT
                  m.id_modulo,
                  m.nombre,
                  p.crear,
                  p.leer,
                  p.actualizar,
                  p.eliminar
            FROM {$_ENV['SECURE_DB']}.permisos p
            INNER JOIN {$_ENV['SECURE_DB']}.modulos m ON p.modulo = m.id_modulo
            WHERE p.id_rol = :id_rol 
               AND m.nombre = :modulo;";
      $valores = [
         ':id_rol' => $idRol,
         ':modulo' => $modulo
      ];
      $permisos = $database->query($sql, $valores, true);
      if (empty($permisos)) {
         $permisos = [
            'leer'      => 0,
         ];
      }
      return $permisos;
   }

   public static function obtenerPermisosNav(Database $database): array|bool
   {
      $consultaRol = "SELECT p.id_rol FROM {$_ENV['SECURE_DB']}.permisos p
         INNER JOIN {$_ENV['SECURE_DB']}.usuarios_roles ur ON ur.id_rol = p.id_rol
         WHERE ur.id_usuario = :id_usuario LIMIT 1;";
      $idRol = $database->query($consultaRol, [":id_usuario" => ID_USUARIO], true)['id_rol'];
      $consulta = "SELECT m.id_modulo, m.nombre, p.leer FROM {$_ENV['SECURE_DB']}.permisos p
                            INNER JOIN {$_ENV['SECURE_DB']}.modulos m ON p.modulo = m.id_modulo
                            WHERE p.id_rol = :id_rol;";
      $valores = [':id_rol' => $idRol];
      $respuesta = $database->query($consulta, $valores);
      $consulta = "SELECT CONCAT(u.nombre, ' ', u.apellido) as nombre,
                     r.nombre as rol FROM {$_ENV['SECURE_DB']}.usuarios u
                     JOIN {$_ENV['SECURE_DB']}.roles r ON r.id_rol = :id_rol
                     WHERE u.cedula = :id_usuario;";
      $valores = [':id_rol' => $idRol, ':id_usuario' => ID_USUARIO];
      $respuestaUsuario = $database->query($consulta, $valores, true);
      $respuesta['usuario'] = $respuestaUsuario;
      return $respuesta;
   }
   public function obtenerRol(array $datos): array
   {
      $keys = ['id'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $idRol = Cipher::aesDecrypt($arrayFiltrado['id']);
      $id = filter_var($idRol, FILTER_SANITIZE_NUMBER_INT);
      if ($id === '' || $id === null) {
         ExceptionHandler::throwException("El ID de rol ingresado no es válido", 400, \InvalidArgumentException::class);
      }
      return $this->_obtenerRol((int) $id);
   }

   public function obtenerRolUsuario(array $datos): array
   {
      $keys = ['id'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      Validar::validar("cedula", $arrayFiltrado['id']);
      return $this->_obtenerRolUsuario($arrayFiltrado['id']);
   }
   public function asignarRol(array $datos): array
   {
      $keys = ['cedula', 'id_rol_asignar'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $idRol = Cipher::aesDecrypt($arrayFiltrado['id_rol_asignar']);
      Validar::sanitizarYValidar($idRol, 'int');
      Validar::validar("cedula", $arrayFiltrado['cedula']);
      return $this->_asignarRol($idRol, $arrayFiltrado['cedula']);
   }
   public function incluirRol(array $datos): array
   {
      Validar::validar("nombre_rol", $datos['nombre_rol']);
      $arrayPermisos = $this->arrayValoresPermisos($datos);
      return $this->_incluirRol($datos['nombre_rol'], $arrayPermisos);
   }
   public function modificarRol(array $datos): array
   {
      Validar::validar("nombre_rol", $datos['nombre_rol']);
      $arrayPermisos = $this->arrayValoresPermisos($datos);
      $idRol = Cipher::aesDecrypt($datos['id_rol']);
      Validar::sanitizarYValidar($idRol, 'int');
      Validar::sanitizarYValidar($datos['nombre_rol'], 'string');
      return $this->_modificarRol($idRol, $datos['nombre_rol'], $arrayPermisos);
   }

   public function eliminarRol(array $datos): array
   {
      $keys = ['id_rol'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $idRolDesencriptado = Cipher::aesDecrypt($arrayFiltrado['id_rol']);
      Validar::sanitizarYValidar($idRolDesencriptado, 'int');
      return $this->_eliminarRol($idRolDesencriptado);
   }

   private function _incluirRol(string $nombre, array $permisos): array
   {
      $consulta = "SELECT id_rol FROM {$_ENV['SECURE_DB']}.roles WHERE nombre = :id;";
      $existe = Validar::existe($this->database, $nombre, $consulta);
      if ($existe) {
         ExceptionHandler::throwException("Ya existe un rol con el nombre introducido", 400, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "INSERT INTO roles (nombre)
                VALUES (:nombre);";
      $valores = [':nombre' => $nombre];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al incluir el rol", 500, \Exception::class);
      }
      $idRol = $this->database->lastInsertId();
      $consultaPermisos = "INSERT INTO {$_ENV['SECURE_DB']}.permisos (id_rol, modulo, crear, leer, actualizar, eliminar)
            VALUES 
            (:id_rol, :moduloentrenadores, :centrenadores, :rentrenadores, :uentrenadores, :dentrenadores),
            (:id_rol, :moduloatletas, :catletas, :ratletas, :uatletas, :datletas),
            (:id_rol, :modulorolespermisos, :crolespermisos, :rrolespermisos, :urolespermisos, :drolespermisos),
            (:id_rol, :moduloasistencias, :casistencias, :rasistencias, :uasistencias, :dasistencias),
            (:id_rol, :moduloeventos, :ceventos, :reventos, :ueventos, :deventos),
            (:id_rol, :modulomensualidad, :cmensualidad, :rmensualidad, :umensualidad, :dmensualidad),
            (:id_rol, :modulowada, :cwada, :rwada, :uwada, :dwada),
            (:id_rol, :moduloreportes, :creportes, :rreportes, 0, 0),
            (:id_rol, :modulobitacora, 0, :rbitacora, 0, 0);";
      $permisos[':id_rol'] = $idRol;
      $response = $this->database->query($consultaPermisos, $permisos);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al incluir el rol", 500, \Exception::class);
      }
      $this->database->commit();
      $resultado["mensaje"] = "El rol se agregó exitosamente";
      return $resultado;
   }

   private function _obtenerRol(int $id): array
   {
      $consulta = "SELECT id_rol FROM {$_ENV['SECURE_DB']}.roles WHERE id_rol = :id;";
      $existe = Validar::existe($this->database, $id, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("El rol ingresado no existe", 404, \InvalidArgumentException::class);
      }
      $consulta = "SELECT 
                    r.nombre AS nombre_rol, 
                    m.id_modulo, 
                    p.crear, 
                    p.leer, 
                    p.actualizar, 
                    p.eliminar, 
                    m.nombre AS nombre_modulo
                FROM {$_ENV['SECURE_DB']}.roles r
                LEFT JOIN {$_ENV['SECURE_DB']}.permisos p ON p.id_rol = r.id_rol
                LEFT JOIN {$_ENV['SECURE_DB']}.modulos m ON m.id_modulo = p.modulo
                WHERE r.id_rol = :id_rol;";
      $valores = [':id_rol' => $id];
      $response = $this->database->query($consulta, $valores);
      if (!$response) {
         ExceptionHandler::throwException("Ocurrió un error al consultar el rol", 500, \Exception::class);
      }
      $resultado["rol"] = $response;
      return $resultado;
   }
   private function _obtenerRolUsuario(string $cedula): array
   {
      $consulta = "SELECT cedula FROM {$_ENV['SECURE_DB']}.usuarios WHERE cedula = :id;";
      $existe = Validar::existe($this->database, $cedula, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("No existe el usuario introducido", 404, \InvalidArgumentException::class);
      }
      $consulta = "SELECT 
                    r.nombre AS nombre_rol, 
                    u.nombre, 
                    u.apellido, 
                    ur.id_rol
                FROM {$_ENV['SECURE_DB']}.usuarios u
                INNER JOIN {$_ENV['SECURE_DB']}.usuarios_roles ur ON ur.id_usuario = u.cedula
                INNER JOIN {$_ENV['SECURE_DB']}.roles r ON r.id_rol = ur.id_rol
                WHERE u.cedula = :cedula;";
      $valores = [':cedula' => $cedula];
      $response = $this->database->query($consulta, $valores, true);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al consultar el rol", 500, \Exception::class);
      }
      $resultado["rol"] = $response ?: [];
      if (!empty($resultado["rol"])) {
         Cipher::crearHashArray($resultado, "id_rol");
         Cipher::encriptarCampoArray($resultado, "id_rol", false);
      }
      return $resultado;
   }
   private function _modificarRol(int $idRol, string $nombreRol, array $permisos): array
   {

      $consulta = "SELECT id_rol FROM {$_ENV['SECURE_DB']}.roles WHERE id_rol = :id;";
      $existe = Validar::existe($this->database, $idRol, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("No existe el rol introducido", 404, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "UPDATE {$_ENV['SECURE_DB']}.roles SET nombre = :nombre
            WHERE id_rol = :id_rol;";
      $valores = [':id_rol' => $idRol, ':nombre' => $nombreRol];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al actualizar el rol", 500, \Exception::class);
      }
      $consultaPermiso = "UPDATE {$_ENV['SECURE_DB']}.permisos
                           SET crear = :crear, leer = :leer, actualizar = :actualizar, eliminar = :eliminar
                           WHERE id_rol = :id_rol AND modulo = :modulo";
      foreach ($this->modulos as $moduloNombre => $moduloId) {
         $response = $this->database->query($consultaPermiso, [
            ':crear' => $permisos[":c$moduloNombre"] ?? 0,
            ':leer' => $permisos[":r$moduloNombre"] ?? 0,
            ':actualizar' => $permisos[":u$moduloNombre"] ?? 0,
            ':eliminar' => $permisos[":d$moduloNombre"] ?? 0,
            ':id_rol' => $idRol,
            ':modulo' => $moduloId,
         ]);
         if (empty($response)) {
            ExceptionHandler::throwException("Ocurrió un error al actualizar el rol", 500, \Exception::class);
         }
      }
      $this->database->commit();
      $resultado["mensaje"] = "El rol se modificó exitosamente";
      return $resultado;
   }
   private function _asignarRol(int $idRol, string $cedula): array
   {
      $consulta = "SELECT cedula FROM {$_ENV['SECURE_DB']}.usuarios WHERE cedula = :id;";
      $existe = Validar::existe($this->database, $cedula, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("El usuario ingresado no existe", 404, \InvalidArgumentException::class);
      }
      $consulta = "SELECT id_rol FROM {$_ENV['SECURE_DB']}.roles WHERE id_rol = :id;";
      $existe = Validar::existe($this->database, $idRol, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("El rol ingresado no existe", 404, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "UPDATE {$_ENV['SECURE_DB']}.usuarios_roles 
                SET id_rol = :id_rol
                WHERE id_usuario = :cedula;";
      $valores = [":cedula" => $cedula, ":id_rol" => $idRol];
      $response = $this->database->query($consulta, $valores);
      if (!$response) {
         ExceptionHandler::throwException("Ocurrió un error al asignar el rol al usuario", 500, \Exception::class);
      }
      $this->database->commit();
      $resultado["mensaje"] = "El rol del usuario '{$cedula}' fue cambiado exitosamente";
      return $resultado;
   }
   private function _eliminarRol(int $idRol): array
   {

      $consulta = "SELECT id_rol FROM {$_ENV['SECURE_DB']}.roles WHERE id_rol = :id;";
      $existe = Validar::existe($this->database, $idRol, $consulta);
      if (!$existe) {
         ExceptionHandler::throwException("El rol introducido no existe", 404, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "DELETE FROM {$_ENV['SECURE_DB']}.roles WHERE id_rol = :id_rol;";
      $valores = [':id_rol' => $idRol];
      $response = $this->database->query($consulta, $valores);
      if (empty($response)) {
         ExceptionHandler::throwException("Ocurrió un error al eliminar el rol", 500, \Exception::class);
      }
      $this->database->commit();
      $resultado["mensaje"] = "El rol fue eliminado exitosamente";
      return $resultado;
   }

   public function listadoRoles(): array
   {
      return $this->_listadoRoles();
   }
   private function _listadoRoles(): array
   {
      $consulta = "SELECT * FROM {$_ENV['SECURE_DB']}.roles;";
      $response = $this->database->query($consulta);
      $resultado["roles"] = $response ?: [];
      if (!empty($resultado["roles"])) {
         Cipher::crearHashArray($resultado["roles"], "id_rol");
         Cipher::encriptarCampoArray($resultado['roles'], "id_rol", false);
      }
      return $resultado;
   }

   private function arrayValoresPermisos(array $datos): array
   {
      foreach ($this->modulos as $nombre => $numeroModulo) {
         $valoresPermisos[":modulo$nombre"] = $numeroModulo;
         if ($nombre === 'reportes') {
            // Reportes solo se ve o se crea
            $valoresPermisos[":creportes"] = (isset($datos["c$nombre"]) && $datos["c$nombre"] == 1) ? 1 : 0;
            $valoresPermisos[":rreportes"] = (isset($datos["r$nombre"]) && $datos["r$nombre"] == 1) ? 1 : 0;
         } elseif ($nombre === 'bitacora') {
            // Bitácora solo tiene 'rbitacora'
            $valoresPermisos[":rbitacora"] = (int) $datos['rbitacora'] == 1 ?: 0;
         } else {
            // CRUD normal: create, read, update, delete
            $valoresPermisos[":c$nombre"] = (isset($datos["c$nombre"]) && $datos["c$nombre"] == 1) ? 1 : 0;
            $valoresPermisos[":r$nombre"] = (isset($datos["r$nombre"]) && $datos["r$nombre"] == 1) ? 1 : 0;
            $valoresPermisos[":u$nombre"] = (isset($datos["u$nombre"]) && $datos["u$nombre"] == 1) ? 1 : 0;
            $valoresPermisos[":d$nombre"] = (isset($datos["d$nombre"]) && $datos["d$nombre"] == 1) ? 1 : 0;
         }
      }
      return $valoresPermisos;
   }
}
