<?php

class Asistencia extends datos
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = $this->conecta();
    }

    public function obtener_atletas()
    {
        try {
            $consulta = "
                SELECT 
                    u.cedula, 
                    u.nombre, 
                    u.apellido
                FROM atleta a
                INNER JOIN usuarios u ON a.cedula = u.cedula
                ORDER BY u.cedula DESC
            ";
            $con = $this->conexion->prepare($consulta);
            $con->execute();
            $respuesta = $con->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["atletas"] = $respuesta;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    public function guardar_asistencias($fecha, $asistencias)
    {
        try {
            $asistencias = json_decode($asistencias, true);
            $this->conexion->beginTransaction();
            $num_asistencias = count($asistencias);
            $consulta = "IF @num_asistencias IS NULL THEN
            SET @num_asistencias = :num_asistencias;
            END IF;
            INSERT INTO asistencias (id_atleta, fecha, asistio, comentario)
            VALUES 
                (:id_atleta, :fecha, :asistio, :comentario)
            ON DUPLICATE KEY UPDATE
                asistio = VALUES(asistio),
                comentario = VALUES(comentario);
            ";
            $stmt = $this->conexion->prepare($consulta);
            foreach ($asistencias as $asistencia) {
                $stmt->execute([
                    ':num_asistencias' => $num_asistencias,
                    ':id_atleta' => $asistencia['id_atleta'],
                    ':asistio' => $asistencia['asistio'],
                    ':fecha' => $fecha,
                    ':comentario' => $asistencia['comentario']
                ]);

                $stmt->closeCursor();
            }
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

    public function obtener_asistencias($fecha)
    {
        try {
            $consulta = "
                SELECT 
                    a.id_atleta, 
                    u.nombre, 
                    u.apellido, 
                    a.asistio, 
                    a.comentario 
                FROM asistencias a
                INNER JOIN usuarios u ON a.id_atleta = u.cedula
                WHERE a.fecha = :fecha
            ";
            $con = $this->conexion->prepare($consulta);
            $con->execute([':fecha' => $fecha]);
            $respuesta = $con->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["asistencias"] = $respuesta;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }
}
?>