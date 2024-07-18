<?php
require_once('modelo/datos.php');

class WADA extends datos
{
    private $conexion, $id_atleta, $estado, $inscrito, $ultima_actualizacion, $vencimiento;

    public function __construct()
    {
        $this->conexion = $this->conecta();
    }

    public function incluir_wada($id_atleta, $estado, $inscrito, $ultima_actualizacion, $vencimiento)
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

    public function listado_atletas()
    {
        try {
            $consulta = "
                SELECT 
                    u.cedula, 
                    u.nombre, 
                    u.apellido, 
                    u.genero, 
                    u.fecha_nacimiento, 
                    u.lugar_nacimiento, 
                    u.estado_civil, 
                    u.telefono, 
                    u.correo_electronico, 
                    a.tipo_atleta, 
                    a.peso, 
                    a.altura, 
                    a.entrenador 
                FROM atleta a
                INNER JOIN usuarios u ON a.cedula = u.cedula
                ORDER BY u.cedula DESC
            ";
            $con = $this->conexion->prepare($consulta);
            $con->execute();
            $respuesta = $con->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["devol"] = 'listado_atletas';
            $resultado["respuesta"] = $respuesta;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    public function obtener_proximos_vencer() {
        try {
            $consulta = "SELECT * FROM wada WHERE vencimiento <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) ORDER BY vencimiento ASC";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute();
            $registros = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["respuesta"] = $registros;
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
