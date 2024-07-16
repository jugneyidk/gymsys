<?php
require_once('modelo/datos.php');

class WADA extends datos // Nombre de la clase del modelo
{
    private $conexion, $id_atleta, $estado, $inscrito, $ultima_actualizacion, $vencimiento; // Todas las variables que se usarán que vienen del formulario (no tocar la conexión)
    
    public function __construct()
    {
        $this->conexion = $this->conecta(); // Inicia la conexión a la DB
    }

    public function incluir_wada($id_atleta, $estado, $inscrito, $ultima_actualizacion, $vencimiento) // Función pública que hace set a los atributos y llama a la función privada
    {
        $this->id_atleta = $id_atleta;
        $this->estado = $estado;
        $this->inscrito = $inscrito;
        $this->ultima_actualizacion = $ultima_actualizacion;
        $this->vencimiento = $vencimiento;
        return $this->incluir();
    }

    public function modificar_wada($id_atleta, $estado, $inscrito, $ultima_actualizacion, $vencimiento)
    {
        $this->id_atleta = $id_atleta;
        $this->estado = $estado;
        $this->inscrito = $inscrito;
        $this->ultima_actualizacion = $ultima_actualizacion;
        $this->vencimiento = $vencimiento;
        return $this->modificar();
    }

    public function obtener_wada($id_atleta)
    {
        $this->id_atleta = $id_atleta;
        return $this->obtener();
    }

    public function eliminar_wada($id_atleta)
    {
        $this->id_atleta = $id_atleta;
        return $this->eliminar();
    }

    private function incluir()
    {
        try {
            $consulta = "INSERT INTO wada (id_atleta, estado, inscrito, ultima_actualizacion, vencimiento) 
                         VALUES (:id_atleta, :estado, :inscrito, :ultima_actualizacion, :vencimiento)";
            $valores = array(
                ':id_atleta' => $this->id_atleta,
                ':estado' => $this->estado,
                ':inscrito' => $this->inscrito,
                ':ultima_actualizacion' => $this->ultima_actualizacion,
                ':vencimiento' => $this->vencimiento
            );
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $resultado["ok"] = true;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    private function modificar()
    {
        try {
            $consulta = "UPDATE wada SET estado = :estado, inscrito = :inscrito, ultima_actualizacion = :ultima_actualizacion, vencimiento = :vencimiento 
                         WHERE id_atleta = :id_atleta";
            $valores = array(
                ':id_atleta' => $this->id_atleta,
                ':estado' => $this->estado,
                ':inscrito' => $this->inscrito,
                ':ultima_actualizacion' => $this->ultima_actualizacion,
                ':vencimiento' => $this->vencimiento
            );
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $resultado["ok"] = true;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    private function obtener()
    {
        try {
            $consulta = "SELECT * FROM wada WHERE id_atleta = :id_atleta";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute([':id_atleta' => $this->id_atleta]);
            $respuesta = $respuesta->fetch(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["wada"] = $respuesta;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    private function eliminar()
    {
        try {
            $consulta = "DELETE FROM wada WHERE id_atleta = :id_atleta";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute([':id_atleta' => $this->id_atleta]);
            $resultado["ok"] = true;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    public function listado_wada()
    {
        return $this->listado();
    }

    private function listado()
    {
        try {
            $consulta = "SELECT * FROM wada ORDER BY id_atleta DESC";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute();
            $respuesta = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["respuesta"] = $respuesta;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
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

?>