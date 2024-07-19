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

    public function listar_atletas()
    {
        try {
            $consulta = "SELECT cedula, nombre, apellido FROM usuarios WHERE cedula IN (SELECT cedula FROM atleta)";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute();
            $resultado["ok"] = true;
            $resultado["respuesta"] = $respuesta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    private function incluir()
    {
        try {
            // Inserción de asistencia con datos iniciales
            $consulta = "INSERT INTO asistencias (id_atleta, fecha, asistio, comentario) VALUES ('', :fecha, 0, '')";
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

            // Eliminar asistencias existentes para la fecha dada
            $consultaEliminar = "DELETE FROM asistencias WHERE fecha = :fecha";
            $valoresEliminar = array(':fecha' => $this->fecha);
            $respuestaEliminar = $this->conexion->prepare($consultaEliminar);
            $respuestaEliminar->execute($valoresEliminar);

            // Insertar nuevas asistencias
            $consultaInsertar = "INSERT INTO asistencias (id_atleta, fecha, asistio, comentario) VALUES (:id_atleta, :fecha, :asistio, :comentario)";
            $respuestaInsertar = $this->conexion->prepare($consultaInsertar);

            foreach ($this->atletas_asistencia as $asistencia) {
                $respuestaInsertar->execute(array(
                    ':id_atleta' => $asistencia['id_atleta'],
                    ':fecha' => $this->fecha,
                    ':asistio' => $asistencia['asistio'],
                    ':comentario' => $asistencia['comentario']
                ));
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
            $consulta = "SELECT fecha, COUNT(id_atleta) as total_asistentes FROM asistencias GROUP BY fecha ORDER BY fecha DESC";
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
