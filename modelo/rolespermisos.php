<?php
class Roles extends datos
{
    private $conexion;
    private $id_rol, $nombre, $centrenadores, $rentrenadores, $uentrenadores, $dentrenadores, $catletas, $ratletas, $uatletas, $datletas, $crolespermisos, $rrolespermisos, $urolespermisos, $drolespermisos, $casistencias, $rasistencias, $uasistencias, $dasistencias, $ceventos, $reventos, $ueventos, $deventos, $cmensualidad, $rmensualidad, $umensualidad, $dmensualidad, $cwada, $rwada, $uwada, $dwada, $creportes, $rreportes, $ureportes, $dreportes, $rbitacora;

    public function __construct()
    {
        $this->conexion = $this->conecta();
    }
    public function consultar_rol($id_rol)
    {
        $this->id_rol = filter_var($id_rol, FILTER_SANITIZE_NUMBER_INT);
        return $this->consultar();
    }
    public function incluir_rol($nombre_rol, $valores)
    {
        $validacion = Validar::validar("nombre_rol", $nombre_rol);
        if (!$validacion["ok"]) {
            return $validacion;
        }
        $this->nombre = $nombre_rol;
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
        $this->nombre = $nombre_rol;
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
            (:id_rol, :moduloreportes, :creportes, :rreportes, :ureportes, :dreportes),
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
                ':creportes' => $this->creportes,
                ':rreportes' => $this->rreportes,
                ':ureportes' => $this->ureportes,
                ':dreportes' => $this->dreportes,
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
    private function modificar()
    {
        try {
            $consulta = "SELECT id_rol FROM roles WHERE id_rol = ?;";
            $existe = Validar::existe($this->conexion, $this->id_rol, $consulta);
            if (!$existe["ok"]) {
                $resultado["ok"] = false;
                $resultado["mensaje"] = "No existe un rol con este nombre";
                return $resultado;
            }
            $this->conexion->beginTransaction();
            $consulta = "
            UPDATE roles SET nombre = :nombre
            WHERE id_rol = :id_rol;
            INSERT INTO permisos (id_rol, modulo, crear, leer, actualizar, eliminar)
            VALUES 
            (:id_rol, :moduloentrenadores, :centrenadores, :rentrenadores, :uentrenadores, :dentrenadores),
            (:id_rol, :moduloatletas, :catletas, :ratletas, :uatletas, :datletas),
            (:id_rol, :modulorolespermisos, :crolespermisos, :rrolespermisos, :urolespermisos, :drolespermisos),
            (:id_rol, :moduloasistencias, :casistencias, :rasistencias, :uasistencias, :dasistencias),
            (:id_rol, :moduloeventos, :ceventos, :reventos, :ueventos, :deventos),
            (:id_rol, :modulomensualidad, :cmensualidad, :rmensualidad, :umensualidad, :dmensualidad),
            (:id_rol, :modulowada, :cwada, :rwada, :uwada, :dwada),
            (:id_rol, :moduloreportes, :creportes, :rreportes, :ureportes, :dreportes),
            (:id_rol, :modulobitacora, 0, :rbitacora, 0, 0)
            ON DUPLICATE KEY UPDATE
            crear = VALUES(crear),
            leer = VALUES(leer),
            actualizar = VALUES(actualizar),
            eliminar = VALUES(eliminar);
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
                ':creportes' => $this->creportes,
                ':rreportes' => $this->rreportes,
                ':ureportes' => $this->ureportes,
                ':dreportes' => $this->dreportes,
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
