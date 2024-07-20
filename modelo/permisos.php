<?php
require_once ('modelo/datos.php');
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
            $this->conexion->beginTransaction();
            $id_rol = $_SESSION['rol'];
            $modulo = $_GET['p'];
            $consulta = "SELECT m.id_modulo, m.nombre, p.crear, p.leer, p.actualizar, p.eliminar FROM permisos p
                            INNER JOIN modulos m ON p.modulo = m.id_modulo
                            WHERE p.id_rol = :id_rol AND m.nombre = :modulo;";
            $valores = array(':id_rol' => $id_rol, ':modulo' => $modulo);
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $permisos = $respuesta->fetch(PDO::FETCH_ASSOC);
            $this->conexion->commit();
            return $permisos;
        } catch (Exception $e) {

        }
    }
    public function permisos_nav()
    {
        try {
            $this->conexion->beginTransaction();
            $id_rol = $_SESSION['rol'];
            $consulta = "SELECT m.id_modulo, m.nombre, p.leer FROM permisos p
                            INNER JOIN modulos m ON p.modulo = m.id_modulo
                            WHERE p.id_rol = :id_rol;";
            $valores = array(':id_rol' => $id_rol);
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $permisos = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            $this->conexion->commit();
            return $permisos;
        } catch (Exception $e) {

        }
    }


}