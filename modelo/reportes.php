<?php
require_once ('modelo/datos.php');

class Reporte extends datos
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = $this->conecta(); 
    }

    public function obtener_reportes($tipoReporte, $fechaInicio, $fechaFin)
    {
        try {
            $consulta = "";
            $valores = array(':fechaInicio' => $fechaInicio, ':fechaFin' => $fechaFin);

            if ($tipoReporte == 'atletas') {
                $consulta = "
                    SELECT a.cedula AS id, CONCAT(u.nombre, ' ', u.apellido) AS nombre, 'Atleta' AS detalles, u.fecha_nacimiento AS fecha
                    FROM atleta a
                    INNER JOIN usuarios u ON a.cedula = u.cedula
                    WHERE u.fecha_nacimiento BETWEEN :fechaInicio AND :fechaFin
                ";
            } elseif ($tipoReporte == 'entrenadores') {
                $consulta = "
                    SELECT e.cedula AS id, CONCAT(u.nombre, ' ', u.apellido) AS nombre, 'Entrenador' AS detalles, u.fecha_nacimiento AS fecha
                    FROM entrenador e
                    INNER JOIN usuarios u ON e.cedula = u.cedula
                    WHERE u.fecha_nacimiento BETWEEN :fechaInicio AND :fechaFin
                ";
            } elseif ($tipoReporte == 'eventos') {
                $consulta = "
                    SELECT c.id_competencia AS id, c.nombre AS nombre, 'Evento' AS detalles, c.fecha_inicio AS fecha
                    FROM competencia c
                    WHERE c.fecha_inicio BETWEEN :fechaInicio AND :fechaFin
                ";
            }

            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $reportes = $respuesta->fetchAll(PDO::FETCH_ASSOC);

            if ($reportes) {
                $resultado["ok"] = true;
                $resultado["reportes"] = $reportes;
            } else {
                $resultado["ok"] = false;
                $resultado["mensaje"] = "No se encontraron reportes";
            }
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }
}
?>
