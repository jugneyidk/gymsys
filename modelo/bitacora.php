<?php
require_once ('modelo/datos.php');
class Bitacora extends datos
{
    private $conexion, $id_usuario, $accion, $usuario_modificado, $valor_cambiado,$id_accion;
    public function __construct()
    {
        $this->conexion = $this->conecta();
    }
    public function incluir_bitacora($id_usuario, $accion, $usuario_modificado, $valor_cambiado)
    {
        $this->id_usuario = $id_usuario;
        $this->accion = $accion;
        if (isset($usuario_modificado)) {
            $this->usuario_modificado = $usuario_modificado;
            $this->valor_cambiado = $valor_cambiado;
        } else {
            $this->usuario_modificado = NULL;
            $this->valor_cambiado = NULL;
        }
        return $this->incluir();
    }
    public function listado_bitacora()
    {
        return $this->listado();
    }
    public function consultar_accion($id_accion)
    {
        $this->id_accion = $id_accion;
        return $this->consultar();
    }
    private function incluir()
    {
        try {
            $this->conexion->beginTransaction();
            $consulta = "INSERT INTO bitacora(id_usuario,accion,usuario_modificado,valor_cambiado) VALUES (:id_usuario,:accion,:usuario_modificado,:valor_cambiado)";
            $valores = array(':id_usuario' => $this->id_usuario, ':accion' => $this->accion, ':usuario_modificado' => $this->usuario_modificado, ':valor_cambiado' => $this->valor_cambiado);
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $this->conexion->commit();
            $resultado["ok"] = true;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e;
        }
        return $resultado;
    }
    private function listado()
    {
        try {
            $this->conexion->beginTransaction();
            $consulta = "SELECT id_accion, id_usuario, accion, modulo, usuario_modificado, fecha FROM `bitacora` ORDER BY id_accion DESC";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute();
            $listado = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            $respuesta->closeCursor();
            $this->conexion->commit();
            $resultado["ok"] = true;
            $resultado["devol"] = "listado_bitacora";
            $resultado["respuesta"] = $listado;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e;
        }
        $this->desconecta();
        return $resultado;
    }

    private function consultar()
    {
        try {
            $this->conexion->beginTransaction();
            $consulta = "SELECT * FROM `bitacora` WHERE id_accion = :id_accion";
            $valores = array(':id_accion' => $this->id_accion);
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $accion = $respuesta->fetch(PDO::FETCH_ASSOC);
            $respuesta->closeCursor();
            $this->conexion->commit();
            $resultado["ok"] = true;
            $resultado["devol"] = "consultar_accion";
            $resultado["respuesta"] = $accion;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e;
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