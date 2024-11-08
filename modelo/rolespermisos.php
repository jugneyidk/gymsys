<?php
require_once('modelo/datos.php');
class Roles extends datos
{
    private $conexion;
    private $id_rol, $nombre, $centrenadores, $rentrenadores, $uentrenadores, $dentrenadores, $catletas, $ratletas, $uatletas, $datletas, $crolespermisos, $rrolespermisos, $urolespermisos, $drolespermisos, $casistencias, $rasistencias, $uasistencias, $dasistencias, $ceventos, $reventos, $ueventos, $deventos, $cmensualidad, $rmensualidad, $umensualidad, $dmensualidad, $cwada, $rwada, $uwada, $dwada, $creportes, $rreportes, $ureportes, $dreportes, $cbitacora, $rbitacora, $ubitacora, $dbitacora;

    public function __construct()
    {
        $this->conexion = $this->conecta();
    }
    public function consultar_rol($id_rol)
    {
        $this->id_rol = $id_rol;
        return $this->consultar();
    }
    public function incluir_rol($nombre, $valores)
    {
        $this->nombre = $nombre;
        foreach ($valores as $atributo => $valor) {
            $this->$atributo = $valor;
        }
        return $this->incluir();
    }
    public function modificar_rol($id_rol, $nombre, $valores)
    {
        $this->id_rol = $id_rol;
        $this->nombre = $nombre;
        foreach ($valores as $atributo => $valor) {
            $this->$atributo = $valor;
        }
        return $this->modificar();
    }
    public function eliminar_rol($id_rol)
    {
        $this->id_rol = $id_rol;
        return $this->eliminar();
    }

    private function incluir()
    {
        try {
            $consulta = "SELECT id_rol FROM roles WHERE id_rol = ?;";
            $existe = Validar::existe($this->conexion, $this->id_rol,$consulta);
            if ($existe["ok"]) {
                $resultado["ok"] = false;
                $resultado["mensaje"] = $existe["mensaje"];
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
            INSERT INTO permisos (id_rol,modulo,crear,leer,actualizar,eliminar)
            VALUES (:id_rol,:moduloentrenadores,:centrenadores,:rentrenadores,:uentrenadores,:dentrenadores);
            INSERT INTO permisos (id_rol,modulo,crear,leer,actualizar,eliminar)
            VALUES (:id_rol,:moduloatletas,:catletas,:ratletas,:uatletas,:datletas);
            INSERT INTO permisos (id_rol,modulo,crear,leer,actualizar,eliminar)
            VALUES (:id_rol,:modulorolespermisos,:crolespermisos,:rrolespermisos,:urolespermisos,:drolespermisos);
            INSERT INTO permisos (id_rol,modulo,crear,leer,actualizar,eliminar)
            VALUES (:id_rol,:moduloasistencias,:casistencias,:rasistencias,:uasistencias,:dasistencias);
            INSERT INTO permisos (id_rol,modulo,crear,leer,actualizar,eliminar)
            VALUES (:id_rol,:moduloeventos,:ceventos,:reventos,:ueventos,:deventos);
            INSERT INTO permisos (id_rol,modulo,crear,leer,actualizar,eliminar)
            VALUES (:id_rol,:modulomensualidad,:cmensualidad,:rmensualidad,:umensualidad,:dmensualidad);
            INSERT INTO permisos (id_rol,modulo,crear,leer,actualizar,eliminar)
            VALUES (:id_rol,:modulowada,:cwada,:rwada,:uwada,:dwada);
            INSERT INTO permisos (id_rol,modulo,crear,leer,actualizar,eliminar)
            VALUES (:id_rol,:moduloreportes,:creportes,:rreportes,:ureportes,:dreportes);
            INSERT INTO permisos (id_rol,modulo,crear,leer,actualizar,eliminar)
            VALUES (:id_rol,:modulobitacora,:cbitacora,:rbitacora,:ubitacora,:dbitacora);
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
                ':cbitacora' => $this->cbitacora,
                ':rbitacora' => $this->rbitacora,
                ':ubitacora' => $this->ubitacora,
                ':dbitacora' => $this->dbitacora,
            );
            $respuesta = $this->conexion->prepare($consulta_permisos);
            $respuesta->execute($valores_permisos);
            $respuesta->closeCursor();
            $this->conexion->commit();
            $resultado["ok"] = true;
        } catch (Exception $e) {
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
            $this->conexion->beginTransaction();
            $consulta = "
                SELECT r.nombre AS nombre_rol, m.id_modulo, p.crear, p.leer, p.actualizar, p.eliminar, m.nombre AS nombre_modulo
                FROM roles r
                INNER JOIN permisos p ON p.id_rol = r.id_rol
                INNER JOIN modulos m ON m.id_modulo = p.modulo
                WHERE r.id_rol = :id_rol;
            ";
            $valores = array(':id_rol' => $this->id_rol);
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $rol = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            $respuesta->closeCursor();
            $this->conexion->commit();
            if ($rol) {
                $resultado["ok"] = true;
                $resultado["devol"] = 'consultar_rol';
                $resultado["permisos"] = $rol;
            } else {
                $resultado["ok"] = false;
                $resultado["mensaje"] = "No se encontró el rol";
            }
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
        return $resultado;
    }
    private function modificar()
    {
        try {
            $this->conexion->beginTransaction();
            $consulta = "
            UPDATE roles SET nombre = :nombre
            WHERE id_rol = :id_rol;
            UPDATE permisos SET crear = :centrenadores, leer = :rentrenadores, actualizar = :uentrenadores, eliminar = :dentrenadores
            WHERE id_rol = :id_rol AND modulo = :moduloentrenadores;
            UPDATE permisos SET crear = :catletas, leer = :ratletas, actualizar = :uatletas, eliminar = :datletas
            WHERE id_rol = :id_rol AND modulo = :moduloatletas;
            UPDATE permisos SET crear = :crolespermisos, leer = :rrolespermisos, actualizar = :urolespermisos, eliminar = :drolespermisos
            WHERE id_rol = :id_rol AND modulo = :modulorolespermisos;
            UPDATE permisos SET crear = :casistencias, leer = :rasistencias, actualizar = :uasistencias, eliminar = :dasistencias
            WHERE id_rol = :id_rol AND modulo = :moduloasistencias;
            UPDATE permisos SET crear = :ceventos, leer = :reventos, actualizar = :ueventos, eliminar = :deventos
            WHERE id_rol = :id_rol AND modulo = :moduloeventos;
            UPDATE permisos SET crear = :cmensualidad, leer = :rmensualidad, actualizar = :umensualidad, eliminar = :dmensualidad
            WHERE id_rol = :id_rol AND modulo = :modulomensualidad;
            UPDATE permisos SET crear = :cwada, leer = :rwada, actualizar = :uwada, eliminar = :dwada
            WHERE id_rol = :id_rol AND modulo = :modulowada;
            UPDATE permisos SET crear = :creportes, leer = :rreportes, actualizar = :ureportes, eliminar = :dreportes
            WHERE id_rol = :id_rol AND modulo = :moduloreportes;
            UPDATE permisos SET crear = :cbitacora, leer = :rbitacora, actualizar = :ubitacora, eliminar = :dbitacora
            WHERE id_rol = :id_rol AND modulo = :modulobitacora;
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
                ':cbitacora' => $this->cbitacora,
                ':rbitacora' => $this->rbitacora,
                ':ubitacora' => $this->ubitacora,
                ':dbitacora' => $this->dbitacora,
            );
            $respuesta1 = $this->conexion->prepare($consulta);
            $respuesta1->execute($valores_permisos);
            $respuesta1->closeCursor();
            $this->conexion->commit();
            $resultado["ok"] = true;
        } catch (Exception $e) {
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
            $this->conexion->beginTransaction();
            $consulta = "
                DELETE FROM roles WHERE id_rol = :id_rol;
            ";
            $valores = array(':id_rol' => $this->id_rol);
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $respuesta->closeCursor();
            $this->conexion->commit();
            $resultado["ok"] = true;
        } catch (Exception $e) {
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
            $this->conexion->beginTransaction();
            $consulta = "
                SELECT *                
                FROM roles                
                ORDER BY id_rol DESC
            ";
            $con = $this->conexion->prepare($consulta);
            $con->execute();
            $respuesta = $con->fetchAll(PDO::FETCH_ASSOC);
            $con->closeCursor();
            $this->conexion->commit();
            $resultado["ok"] = true;
            $resultado["devol"] = 'listado_roles';
            $resultado["roles"] = $respuesta;
        } catch (Exception $e) {
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
