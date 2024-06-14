<?php
require_once('modelo/datos.php');

class Asistencia extends datos
{
    private $conexion, $fecha, $atletas_asistencia;

    public function __construct()
    {
        $this->conexion = $this->conecta(); // Inicia la conexión a la DB
    }

    public function crear_asistencia($fecha)
    {
        $this->fecha = $fecha;
        return $this->incluir();
    }

    public function guardar_asistencia($fecha, $asistencias)
    {
        $this->fecha = $fecha;
        $this->atletas_asistencia = $asistencias;
        return $this->guardar();
    }

    public function listado_asistencias()
    {
        return $this->listado();
    }

    private function incluir()
    {
        try {
            $consulta = "INSERT INTO asistencia (fecha) VALUES (:fecha)";
            $valores = array(':fecha' => $this->fecha);

            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $resultado["ok"] = true;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    private function guardar()
    {
        try {
            $this->conexion->beginTransaction();
            $consulta = "DELETE FROM asistencia_atleta WHERE fecha = :fecha";
            $valores = array(':fecha' => $this->fecha);
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);

            $consulta = "INSERT INTO asistencia_atleta (fecha, id_atleta) VALUES (:fecha, :id_atleta)";
            $respuesta = $this->conexion->prepare($consulta);

            foreach ($this->atletas_asistencia as $asistencia) {
                $respuesta->execute(array(':fecha' => $this->fecha, ':id_atleta' => $asistencia));
            }

            $this->conexion->commit();
            $resultado["ok"] = true;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    private function listado()
    {
        try {
            $consulta = "SELECT fecha, COUNT(id_atleta) as total_asistentes FROM asistencia_atleta GROUP BY fecha ORDER BY fecha DESC";
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
}
?>
