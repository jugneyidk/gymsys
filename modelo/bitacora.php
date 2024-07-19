<?php
require_once ('modelo/datos.php');
class Bitacora extends datos
{
    private $conexion, $id_usuario, $accion, $usuario_modificado, $valor_cambiado;
    public function __construct()
    {
        $this->conexion = $this->conecta();
    }
    public function incluir_bitacora($id_usuario, $accion, $usuario_modificado, $valor_cambiado)
    {
        $this->id_usuario = $id_usuario;
        $this->accion = $accion;
        if(isset($usuario_modificado)){
            $this->usuario_modificado = $usuario_modificado;
            $this->valor_cambiado = $valor_cambiado;
        } else{
            $this->usuario_modificado = NULL;
            $this->valor_cambiado = null;
        }        
        return $this->incluir();
    }
    public function listado_bitacora()
    {
        return $this->listado();
    }
    private function incluir()
    {
        try {
            $consulta = "INSERT INTO bitacora(id_usuario,accion,usuario_modificado,valor_cambiado) VALUES (:id_usuario,:accion,:usuario_modificado,:valor_cambiado)";
            $valores = array(':id_usuario' => $this->id_usuario, ':accion' => $this->accion, ':usuario_modificado' => $this->usuario_modificado, ':valor_cambiado' => $this->valor_cambiado);
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
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
            $consulta = "SELECT * FROM `bitacora` ORDER BY id_accion DESC";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute();
            $respuesta = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["respuesta"] = $respuesta;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e;
        }
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