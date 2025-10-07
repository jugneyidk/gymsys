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
   private const CACHE_DIR = __DIR__ . '/../../cache/permisos';
   private const CACHE_FILE = 'permisos_cache.txt';

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
   private static function verificarDirectorioCache(): void
   {
      if (!file_exists(self::CACHE_DIR)) {
         mkdir(self::CACHE_DIR, 0755, true);
      }
   }

   private static function obtenerDirectorioCache(): string
   {
      return self::CACHE_DIR . '/' . self::CACHE_FILE;
   }

   private static function obtenerClaveCache(?int $idRol, ?string $modulo = null): string
   {
      if ($idRol === null) {
         ExceptionHandler::throwException("ID de rol no ingresado", 403, \InvalidArgumentException::class);
      }
      
      $key = 'rol_' . $idRol;
      if ($modulo !== null) {
         $key .= '_' . strtolower($modulo);
      }
      
      return $key;
   }

   private static function obtenerPermisosCache(string $key): ?array
   {
      $cacheFile = self::obtenerDirectorioCache();
      if (!file_exists($cacheFile)) {
         return null;
      }
      
      $cachedData = @file_get_contents($cacheFile);
      if ($cachedData === false) {
         return null;
      }
      
      $data = json_decode($cachedData, true);
      return $data[$key] ?? null;
   }

   private static function guardarPermisosCache(string $key, array $data): void
   {
      self::verificarDirectorioCache();
      $cacheFile = self::obtenerDirectorioCache();
      
      $cachedData = [];
      if (file_exists($cacheFile)) {
         $cachedContent = @file_get_contents($cacheFile);
         if ($cachedContent !== false) {
            $cachedData = json_decode($cachedContent, true) ?: [];
         }
      }
      
      $cachedData[$key] = $data;
      file_put_contents($cacheFile, json_encode($cachedData), LOCK_EX);
   }

   public static function limpiarPermisosCache(): void
   {
      $cacheFile = self::obtenerDirectorioCache();
      if (file_exists($cacheFile)) {
         unlink($cacheFile);
      }
   }

   public static function obtenerPermisosModulo(string $modulo, Database $database): array
   {
      // Normalizar el nombre del módulo a minúsculas
      $modulo = strtolower($modulo);
      
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
            return ['leer' => 1];
         }
         ExceptionHandler::throwException(
            "Acceso no autorizado",
            403,
            \UnexpectedValueException::class
         );
      }
      
      // Obtener todos los permisos del rol desde la caché o la base de datos
      $permisosRol = self::obtenerTodosLosPermisosRol($idRol, $database);
      
      // Devolver solo los permisos del módulo solicitado
      return $permisosRol[$modulo] ?? ['leer' => 0];
   }
   
   private static function obtenerTodosLosPermisosRol(int $idRol, Database $database): array
   {
      $cacheKey = self::obtenerClaveCache($idRol);
      $cached = self::obtenerPermisosCache($cacheKey);
      
      if ($cached !== null) {
         return $cached;
      }
      
      // Si no está en caché, obtener todos los permisos del rol de la base de datos
      $sql = "SELECT
                  m.nombre as modulo,
                  p.crear,
                  p.leer,
                  p.actualizar,
                  p.eliminar
            FROM {$_ENV['SECURE_DB']}.permisos p
            INNER JOIN {$_ENV['SECURE_DB']}.modulos m ON p.modulo = m.id_modulo
            WHERE p.id_rol = :id_rol";
      
      $permisos = $database->query($sql, [':id_rol' => $idRol]);
      
      // Organizar los permisos por módulo
      $permisosOrganizados = [];
      foreach ($permisos as $permiso) {
         $modulo = strtolower($permiso['modulo']);
         unset($permiso['modulo']);
         $permisosOrganizados[$modulo] = $permiso;
      }
      
      // Guardar en caché todos los permisos del rol
      self::guardarPermisosCache($cacheKey, $permisosOrganizados);
      
      return $permisosOrganizados;
   }

   public static function obtenerPermisosNav(Database $database): array|bool
   {
      if (!defined('ID_USUARIO') || empty(ID_USUARIO)) {
         return [];
      }
      
      $cacheKey = 'nav_' . ID_USUARIO;
      $cached = self::obtenerPermisosCache($cacheKey);
      if ($cached !== null) {
         return $cached;
      }
      
      // Obtener el ID del rol del usuario
      $consultaRol = "SELECT ur.id_rol, r.nombre as nombre_rol 
                     FROM {$_ENV['SECURE_DB']}.usuarios_roles ur
                     JOIN {$_ENV['SECURE_DB']}.roles r ON r.id_rol = ur.id_rol
                     WHERE ur.id_usuario = :id_usuario LIMIT 1;";
      
      $rolInfo = $database->query($consultaRol, [":id_usuario" => ID_USUARIO], true);
      
      if (empty($rolInfo) || !isset($rolInfo['id_rol'])) {
         return [];
      }
      
      $idRol = $rolInfo['id_rol'];
      
      // Obtener todos los permisos del rol (usará la caché si está disponible)
      $permisosRol = self::obtenerTodosLosPermisosRol($idRol, $database);
      
      // Formatear los permisos para la navegación
      $permisosNav = [];
      foreach ($permisosRol as $modulo => $permiso) {
         $permisosNav[] = [
            'nombre' => $modulo,
            'leer' => $permiso['leer'] ?? 0
         ];
      }
      
      // Obtener información del usuario
      $consultaUsuario = "SELECT CONCAT(nombre, ' ', apellido) as nombre 
                         FROM {$_ENV['SECURE_DB']}.usuarios 
                         WHERE cedula = :id_usuario;";
      
      $usuario = $database->query($consultaUsuario, [':id_usuario' => ID_USUARIO], true);
      
      $resultado = [
         ...$permisosNav,
         'usuario' => [
            'nombre' => $usuario['nombre'] ?? 'Usuario',
            'rol' => $rolInfo['nombre_rol']
         ]
      ];
      
      // Guardar en caché
      self::guardarPermisosCache($cacheKey, $resultado);
      
      return $resultado;
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
      $resultado = $this->_asignarRol($idRol, $arrayFiltrado['cedula']);
      self::limpiarPermisosCache();
      return $resultado;
   }
   public function incluirRol(array $datos): array
   {
      Validar::validar("nombre_rol", $datos['nombre_rol']);
      $arrayPermisos = $this->arrayValoresPermisos($datos);
      $resultado = $this->_incluirRol($datos['nombre_rol'], $arrayPermisos);
      return $resultado;
   }
   public function modificarRol(array $datos): array
   {
      Validar::validar("nombre_rol", $datos['nombre_rol']);
      $arrayPermisos = $this->arrayValoresPermisos($datos);
      $idRol = Cipher::aesDecrypt($datos['id_rol']);
      Validar::sanitizarYValidar($idRol, 'int');
      Validar::sanitizarYValidar($datos['nombre_rol'], 'string');
      $resultado = $this->_modificarRol($idRol, $datos['nombre_rol'], $arrayPermisos);
      self::limpiarPermisosCache();
      return $resultado;
   }

   public function eliminarRol(array $datos): array
   {
      $keys = ['id_rol'];
      $arrayFiltrado = Validar::validarArray($datos, $keys);
      $idRolDesencriptado = Cipher::aesDecrypt($arrayFiltrado['id_rol']);
      Validar::sanitizarYValidar($idRolDesencriptado, 'int');
      $resultado = $this->_eliminarRol($idRolDesencriptado);
      self::limpiarPermisosCache();
      return $resultado;
   }

   private function _incluirRol(string $nombre, array $permisos): array
   { 
      $consulta = "SELECT id_rol FROM {$_ENV['SECURE_DB']}.roles WHERE nombre = :id;";
      $existe = Validar::existe($this->database, $nombre, $consulta);
      if ($existe) {
         ExceptionHandler::throwException("Ya existe un rol con el nombre introducido", 400, \InvalidArgumentException::class);
      }
      $this->database->beginTransaction();
      $consulta = "INSERT INTO {$_ENV['SECURE_DB']}.roles (nombre)
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
