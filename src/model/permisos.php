<?php
require_once('modelo/datos.php');
class Permisos extends datos
{
    private $conexion;
    public function __construct()
    {
        $this->conexion = $this->conecta();
    }
    public function chequear_permisos()
    {
        try {
            $this->verificarConexion();
            if (!isset($_SESSION['rol'])) {
                $resultado["leer"] = 0;
                return $resultado;
            }
            $this->conexion->beginTransaction();
            $id_rol = $_SESSION['rol'];
            $modulo = isset($_GET['p']) ? $_GET['p'] : "landing";
            $consulta = "SELECT m.id_modulo, m.nombre, p.crear, p.leer, p.actualizar, p.eliminar FROM permisos p
                            INNER JOIN modulos m ON p.modulo = m.id_modulo
                            WHERE p.id_rol = :id_rol AND m.nombre = :modulo;";
            $valores = array(':id_rol' => $id_rol, ':modulo' => $modulo);
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $resultado = $respuesta->fetch(PDO::FETCH_ASSOC);
            $this->conexion->commit();
        } catch (PDOException $e) {
            echo ($e->getMessage());
            exit;
        }
        $this->desconecta();
        return $resultado;

    }
    public function chequear_permisos_completos()
    {
        try {
            $this->verificarConexion();
            if (!isset($_SESSION['rol'])) {
                $respuesta["ok"] = false;
                return $respuesta;
            }
            $id_rol = $_SESSION['rol'];
            $consulta = "SELECT m.id_modulo, m.nombre, p.crear, p.leer, p.actualizar, p.eliminar FROM permisos p
                            INNER JOIN modulos m ON p.modulo = m.id_modulo
                            WHERE p.id_rol = :id_rol;";
            $valores = array(':id_rol' => $id_rol);
            $resultado = $this->conexion->prepare($consulta);
            $resultado->execute($valores);
            $resultado = $resultado->fetchAll(PDO::FETCH_ASSOC);
            $consulta = "SELECT r.nombre FROM roles r
                            WHERE r.id_rol = :id_rol;";
            $valores = array(':id_rol' => $id_rol);
            $nombre_rol = $this->conexion->prepare($consulta);
            $nombre_rol->execute($valores);
            $nombre_rol = $nombre_rol->fetchColumn();
            $respuesta["ok"] = true;
            $respuesta["permisos"] = $resultado;
            $respuesta["nombre_rol"] = $nombre_rol;
        } catch (PDOException $e) {
            $respuesta["ok"] = false;
            $respuesta["mensaje"] = $e->getMessage();
            return;
        }
        $this->desconecta();
        return $respuesta;
    }
    public function permisos_nav()
    {
        try {
            $id_rol = $_SESSION['rol'];
            $consulta = "SELECT m.id_modulo, m.nombre, p.leer FROM permisos p
                            INNER JOIN modulos m ON p.modulo = m.id_modulo
                            WHERE p.id_rol = :id_rol;";
            $valores = array(':id_rol' => $id_rol);
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $permisos = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            $this->desconecta();
            return $permisos;
        } catch (Exception $e) {
        }
    }
}
