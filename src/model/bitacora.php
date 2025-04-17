<?php
class Bitacora extends datos
{
    private $conexion, $id_accion;
    public function __construct()
    {
        $this->conexion = $this->conecta();
    }
    public function listado_bitacora()
    {
        return $this->listado();
    }
    public function consultar_accion($id_accion)
    {
        $this->id_accion = filter_var($id_accion, FILTER_SANITIZE_NUMBER_INT);
        return $this->consultar();
    }
    private function listado()
    {
        try {
            $consulta = "SELECT id_accion, id_usuario, accion, modulo, usuario_modificado, fecha FROM `bitacora` ORDER BY id_accion DESC";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute();
            $listado = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["devol"] = "listado_bitacora";
            $resultado["respuesta"] = $listado;
        } catch (PDOException $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
        return $resultado;
    }

    private function consultar()
    {
        try {
            $consulta = "SELECT * FROM `bitacora` WHERE id_accion = :id_accion";
            $valores = array(':id_accion' => $this->id_accion);
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $accion = $respuesta->fetch(PDO::FETCH_ASSOC);
            if ($accion) {
                $resultado["ok"] = true;
                $resultado["respuesta"] = $accion;
            } else {
                $resultado["ok"] = false;
                $resultado["mensaje"] = "No se encontró la acción";
            }
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