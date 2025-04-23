<?php

namespace Gymsys\Model;

use Gymsys\Core\Database;
use Gymsys\Utils\ExceptionHandler;

class Rolespermisos
{
   private $database;
   private static $routes;
   private $cedula, $id_rol, $nombre, $centrenadores, $rentrenadores, $uentrenadores, $dentrenadores, $catletas, $ratletas, $uatletas, $datletas, $crolespermisos, $rrolespermisos, $urolespermisos, $drolespermisos, $casistencias, $rasistencias, $uasistencias, $dasistencias, $ceventos, $reventos, $ueventos, $deventos, $cmensualidad, $rmensualidad, $umensualidad, $dmensualidad, $cwada, $rwada, $uwada, $dwada, $creportes, $rreportes, $ureportes, $dreportes, $rbitacora;

   public function __construct(Database $database)
   {
      $this->database = $database;
   }
   public static function obtenerPermisosModulo(string $modulo, Database $database): array
   {
      self::$routes = require dirname(__DIR__) . "/core/routes.php"; 
      if (empty($_SESSION['rol']) && empty(self::$routes[$modulo]['public'])) {
         ExceptionHandler::throwException("Acceso no autorizado", 403, \UnexpectedValueException::class);
      }
      $idRol = $_SESSION['rol'];
      $consulta = "SELECT m.id_modulo, m.nombre, p.crear, p.leer, p.actualizar, p.eliminar FROM permisos p
                            INNER JOIN modulos m ON p.modulo = m.id_modulo
                            WHERE p.id_rol = :id_rol AND m.nombre = :modulo;";
      $valores = [':id_rol' => $idRol, ':modulo' => $modulo];
      $permisos = $database->query($consulta, $valores, true);
      if (empty($permisos)) {
         $permisos["leer"] = 0;
      }
      return $permisos;
   }
   public static function obtenerPermisosNav(Database $database): array|bool
   {
      $id_rol = $_SESSION['rol'];
      $consulta = "SELECT m.id_modulo, m.nombre, p.leer FROM permisos p
                            INNER JOIN modulos m ON p.modulo = m.id_modulo
                            WHERE p.id_rol = :id_rol;";
      $valores = [':id_rol' => $id_rol];
      $respuesta = $database->query($consulta, $valores);
      return $respuesta;
   }
   public function consultar_rol($id_rol)
   {
      $this->id_rol = filter_var($id_rol, FILTER_SANITIZE_NUMBER_INT);
      return $this->consultar();
   }

   public function consultar_rol_usuario($cedula)
   {
      $validacion = Validar::validar("cedula", $cedula);
      if (!$validacion["ok"]) {
         return $validacion;
      }
      $this->cedula = $cedula;
      return $this->consultar_usuario();
   }
   public function asignar_rol($cedula, $id_rol)
   {
      $validacion = Validar::validar("cedula", $cedula);
      if (!$validacion["ok"]) {
         return $validacion;
      }
      $this->id_rol = filter_var($id_rol, FILTER_SANITIZE_NUMBER_INT);
      $this->cedula = $cedula;
      return $this->asignar();
   }
   public function incluir_rol($nombre_rol, $valores)
   {
      $validacion = Validar::validar("nombre_rol", $nombre_rol);
      if (!$validacion["ok"]) {
         return $validacion;
      }
      $this->nombre = trim($nombre_rol);
      foreach ($valores as $atributo => $valor) {
         if (property_exists($this, $atributo)) {
            $this->$atributo = $valor;
         }
      }
      return $this->incluir();
   }
   public function modificar_rol($id_rol, $nombre_rol, $valores)
   {
      $validacion = Validar::validar("nombre_rol", $nombre_rol);
      if (!$validacion["ok"]) {
         return $validacion;
      }
      $this->id_rol = filter_var($id_rol, FILTER_SANITIZE_NUMBER_INT);
      $this->nombre = trim($nombre_rol);
      foreach ($valores as $atributo => $valor) {
         if (property_exists($this, $atributo) && ($valor == 1 || $valor == 0)) {
            $this->$atributo = $valor;
         }
      }
      return $this->modificar();
   }

   public function eliminar_rol($id_rol)
   {
      $this->id_rol = filter_var($id_rol, FILTER_SANITIZE_NUMBER_INT);
      return $this->eliminar();
   }

   private function incluir()
   {
      try {
         $consulta = "SELECT id_rol FROM roles WHERE nombre = ?;";
         $existe = Validar::existe($this->conexion, $this->nombre, $consulta);
         if ($existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "Ya existe un rol con este nombre";
            return $resultado;
         }
         $this->conexion->beginTransaction();
         $consulta = "
                INSERT INTO roles (nombre)
                VALUES (:nombre);
            ";
         $valores = array(
            ':nombre' => $this->nombre,
         );
         $respuesta = $this->conexion->prepare($consulta);
         $respuesta->execute($valores);
         $id_rol = $this->conexion->lastInsertId();
         $consulta_permisos = "
            INSERT INTO permisos (id_rol, modulo, crear, leer, actualizar, eliminar)
            VALUES 
            (:id_rol, :moduloentrenadores, :centrenadores, :rentrenadores, :uentrenadores, :dentrenadores),
            (:id_rol, :moduloatletas, :catletas, :ratletas, :uatletas, :datletas),
            (:id_rol, :modulorolespermisos, :crolespermisos, :rrolespermisos, :urolespermisos, :drolespermisos),
            (:id_rol, :moduloasistencias, :casistencias, :rasistencias, :uasistencias, :dasistencias),
            (:id_rol, :moduloeventos, :ceventos, :reventos, :ueventos, :deventos),
            (:id_rol, :modulomensualidad, :cmensualidad, :rmensualidad, :umensualidad, :dmensualidad),
            (:id_rol, :modulowada, :cwada, :rwada, :uwada, :dwada),
            (:id_rol, :moduloreportes, :creportes, :rreportes, 0, 0),
            (:id_rol, :modulobitacora, 0, :rbitacora, 0, 0);
            ";
         $valores_permisos = array(
            ':id_rol' => $id_rol,
            ':moduloentrenadores' => 1,
            ':centrenadores' => $this->centrenadores,
            ':rentrenadores' => $this->rentrenadores,
            ':uentrenadores' => $this->uentrenadores,
            ':dentrenadores' => $this->dentrenadores,
            ':moduloatletas' => 2,
            ':catletas' => $this->catletas,
            ':ratletas' => $this->ratletas,
            ':uatletas' => $this->uatletas,
            ':datletas' => $this->datletas,
            ':modulorolespermisos' => 3,
            ':crolespermisos' => $this->crolespermisos,
            ':rrolespermisos' => $this->rrolespermisos,
            ':urolespermisos' => $this->urolespermisos,
            ':drolespermisos' => $this->drolespermisos,
            ':moduloasistencias' => 4,
            ':casistencias' => $this->casistencias,
            ':rasistencias' => $this->rasistencias,
            ':uasistencias' => $this->uasistencias,
            ':dasistencias' => $this->dasistencias,
            ':moduloeventos' => 5,
            ':ceventos' => $this->ceventos,
            ':reventos' => $this->reventos,
            ':ueventos' => $this->ueventos,
            ':deventos' => $this->deventos,
            ':modulomensualidad' => 6,
            ':cmensualidad' => $this->cmensualidad,
            ':rmensualidad' => $this->rmensualidad,
            ':umensualidad' => $this->umensualidad,
            ':dmensualidad' => $this->dmensualidad,
            ':modulowada' => 7,
            ':cwada' => $this->cwada,
            ':rwada' => $this->rwada,
            ':uwada' => $this->uwada,
            ':dwada' => $this->dwada,
            ':moduloreportes' => 8,
            ':creportes' => (int) ($this->creportes == 1 || $this->rreportes == 1),
            ':rreportes' => (int) ($this->creportes == 1 || $this->rreportes == 1),
            ':modulobitacora' => 9,
            ':rbitacora' => $this->rbitacora,
         );
         $respuesta = $this->conexion->prepare($consulta_permisos);
         $respuesta->execute($valores_permisos);
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

   private function consultar()
   {
      try {
         $consulta = "SELECT 
                    r.nombre AS nombre_rol, 
                    m.id_modulo, 
                    p.crear, 
                    p.leer, 
                    p.actualizar, 
                    p.eliminar, 
                    m.nombre AS nombre_modulo
                FROM roles r
                LEFT JOIN permisos p ON p.id_rol = r.id_rol
                LEFT JOIN modulos m ON m.id_modulo = p.modulo
                WHERE r.id_rol = :id_rol;";
         $valores = array(':id_rol' => $this->id_rol);
         $respuesta = $this->conexion->prepare($consulta);
         $respuesta->execute($valores);
         $rol = $respuesta->fetchAll(PDO::FETCH_ASSOC);
         if ($rol) {
            $resultado["ok"] = true;
            $resultado["permisos"] = $rol;
         } else {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "No se encontró el rol";
         }
      } catch (PDOException $e) {
         $resultado["ok"] = false;
         $resultado["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $resultado;
   }
   private function consultar_usuario()
   {
      try {
         $consulta = "SELECT 
                    r.nombre AS nombre_rol, 
                    u.nombre, 
                    u.apellido, 
                    ur.id_rol
                FROM usuarios u
                INNER JOIN usuarios_roles ur ON ur.id_usuario = u.cedula
                INNER JOIN roles r ON r.id_rol = ur.id_rol
                WHERE u.cedula = :cedula;";
         $valores = array(':cedula' => $this->cedula);
         $respuesta = $this->conexion->prepare($consulta);
         $respuesta->execute($valores);
         $usuario = $respuesta->fetch(PDO::FETCH_ASSOC);
         if ($usuario) {
            $resultado["ok"] = true;
            $resultado["usuario"] = $usuario;
         } else {
            $resultado["ok"] = false;
         }
      } catch (PDOException $e) {
         $resultado["ok"] = false;
         $resultado["mensaje"] = $e->getMessage();
      }
      $this->desconecta();
      return $resultado;
   }
   private function modificar()
   {
      try {
         $consulta = "SELECT id_rol FROM roles WHERE id_rol = ?;";
         $existe = Validar::existe($this->conexion, $this->id_rol, $consulta);
         if (!$existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "No existe este rol";
            return $resultado;
         }
         $this->conexion->beginTransaction();
         $consulta = "
            UPDATE roles SET nombre = :nombre
            WHERE id_rol = :id_rol;
            UPDATE permisos
            SET 
                crear = CASE modulo
                    WHEN :moduloentrenadores THEN :centrenadores
                    WHEN :moduloatletas THEN :catletas
                    WHEN :modulorolespermisos THEN :crolespermisos
                    WHEN :moduloasistencias THEN :casistencias
                    WHEN :moduloeventos THEN :ceventos
                    WHEN :modulomensualidad THEN :cmensualidad
                    WHEN :modulowada THEN :cwada
                    WHEN :moduloreportes THEN :creportes
                    WHEN :modulobitacora THEN 0
                END,
                leer = CASE modulo
                    WHEN :moduloentrenadores THEN :rentrenadores
                    WHEN :moduloatletas THEN :ratletas
                    WHEN :modulorolespermisos THEN :rrolespermisos
                    WHEN :moduloasistencias THEN :rasistencias
                    WHEN :moduloeventos THEN :reventos
                    WHEN :modulomensualidad THEN :rmensualidad
                    WHEN :modulowada THEN :rwada
                    WHEN :moduloreportes THEN :rreportes
                    WHEN :modulobitacora THEN :rbitacora
                END,
                actualizar = CASE modulo
                    WHEN :moduloentrenadores THEN :uentrenadores
                    WHEN :moduloatletas THEN :uatletas
                    WHEN :modulorolespermisos THEN :urolespermisos
                    WHEN :moduloasistencias THEN :uasistencias
                    WHEN :moduloeventos THEN :ueventos
                    WHEN :modulomensualidad THEN :umensualidad
                    WHEN :modulowada THEN :uwada
                    WHEN :moduloreportes THEN 0
                    WHEN :modulobitacora THEN 0
                END,
                eliminar = CASE modulo
                    WHEN :moduloentrenadores THEN :dentrenadores
                    WHEN :moduloatletas THEN :datletas
                    WHEN :modulorolespermisos THEN :drolespermisos
                    WHEN :moduloasistencias THEN :dasistencias
                    WHEN :moduloeventos THEN :deventos
                    WHEN :modulomensualidad THEN :dmensualidad
                    WHEN :modulowada THEN :dwada
                    WHEN :moduloreportes THEN 0
                    WHEN :modulobitacora THEN 0
                END
            WHERE id_rol = :id_rol;
            ";
         $valores_permisos = array(
            ':nombre' => $this->nombre,
            ':id_rol' => $this->id_rol,
            ':moduloentrenadores' => 1,
            ':centrenadores' => $this->centrenadores,
            ':rentrenadores' => $this->rentrenadores,
            ':uentrenadores' => $this->uentrenadores,
            ':dentrenadores' => $this->dentrenadores,
            ':moduloatletas' => 2,
            ':catletas' => $this->catletas,
            ':ratletas' => $this->ratletas,
            ':uatletas' => $this->uatletas,
            ':datletas' => $this->datletas,
            ':modulorolespermisos' => 3,
            ':crolespermisos' => $this->crolespermisos,
            ':rrolespermisos' => $this->rrolespermisos,
            ':urolespermisos' => $this->urolespermisos,
            ':drolespermisos' => $this->drolespermisos,
            ':moduloasistencias' => 4,
            ':casistencias' => $this->casistencias,
            ':rasistencias' => $this->rasistencias,
            ':uasistencias' => $this->uasistencias,
            ':dasistencias' => $this->dasistencias,
            ':moduloeventos' => 5,
            ':ceventos' => $this->ceventos,
            ':reventos' => $this->reventos,
            ':ueventos' => $this->ueventos,
            ':deventos' => $this->deventos,
            ':modulomensualidad' => 6,
            ':cmensualidad' => $this->cmensualidad,
            ':rmensualidad' => $this->rmensualidad,
            ':umensualidad' => $this->umensualidad,
            ':dmensualidad' => $this->dmensualidad,
            ':modulowada' => 7,
            ':cwada' => $this->cwada,
            ':rwada' => $this->rwada,
            ':uwada' => $this->uwada,
            ':dwada' => $this->dwada,
            ':moduloreportes' => 8,
            ':creportes' => (int) ($this->creportes == 1 || $this->rreportes == 1),
            ':rreportes' => (int) ($this->creportes == 1 || $this->rreportes == 1),
            ':modulobitacora' => 9,
            ':rbitacora' => $this->rbitacora,
         );
         $respuesta1 = $this->conexion->prepare($consulta);
         $respuesta1->execute($valores_permisos);
         $respuesta1->closeCursor();
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
   private function asignar()
   {
      try {
         $consulta = "SELECT cedula FROM usuarios WHERE cedula = ?;";
         $existe = Validar::existe($this->conexion, $this->cedula, $consulta);
         if (!$existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "No existe este usuario";
            return $resultado;
         }
         $consulta = "SELECT id_rol FROM roles WHERE id_rol = ?;";
         $existe = Validar::existe($this->conexion, $this->id_rol, $consulta);
         if (!$existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "No existe este rol";
            return $resultado;
         }
         $this->conexion->beginTransaction();
         $consulta = "UPDATE usuarios_roles 
                SET 
                    id_rol = :id_rol
                WHERE id_usuario = :cedula;";
         $valores = [":cedula" => $this->cedula, ":id_rol" => $this->id_rol];
         $respuesta1 = $this->conexion->prepare($consulta);
         $respuesta1->execute($valores);
         $respuesta1->closeCursor();
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
   private function eliminar()
   {
      try {
         $consulta = "SELECT id_rol FROM roles WHERE id_rol = ?;";
         $existe = Validar::existe($this->conexion, $this->id_rol, $consulta);
         if (!$existe["ok"]) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = "No existe este rol";
            return $resultado;
         }
         $this->conexion->beginTransaction();
         $consulta = "DELETE FROM roles WHERE id_rol = :id_rol;";
         $valores = array(':id_rol' => $this->id_rol);
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

   public function listado_roles()
   {
      try {
         $consulta = "SELECT * FROM roles ORDER BY id_rol DESC";
         $con = $this->conexion->prepare($consulta);
         $con->execute();
         $respuesta = $con->fetchAll(PDO::FETCH_ASSOC);
         $resultado["ok"] = true;
         $resultado["roles"] = $respuesta;
      } catch (PDOException $e) {
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
