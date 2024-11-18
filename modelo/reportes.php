<?php
require_once("datos.php");

class Reporte extends datos
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = $this->conecta();
        if (!$this->conexion) {
            die("Error en la conexión a la base de datos");
        }
    }

    public function obtener_reportes($tipoReporte, $filtros)
    {
        try {
            $consulta = "";
            $valores = array();

            switch ($tipoReporte) {
                case 'atletas':
                    $consulta = "
                        SELECT a.cedula AS id, CONCAT(u.nombre, ' ', u.apellido) AS nombre, 'Atleta' AS detalles, u.fecha_nacimiento AS fecha
                        FROM atleta a
                        INNER JOIN usuarios u ON a.cedula = u.cedula
                        WHERE 1=1
                    ";
                    if (!empty($filtros['edadMin'])) {
                        $consulta .= " AND TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) >= :edadMin";
                        $valores[':edadMin'] = $filtros['edadMin'];
                    }
                    if (!empty($filtros['edadMax'])) {
                        $consulta .= " AND TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) <= :edadMax";
                        $valores[':edadMax'] = $filtros['edadMax'];
                    }
                    if (!empty($filtros['genero'])) {
                        $consulta .= " AND u.genero = :genero";
                        $valores[':genero'] = $filtros['genero'];
                    }
                    if (!empty($filtros['tipoAtleta'])) {
                        $consulta .= " AND a.tipo_atleta = :tipoAtleta";
                        $valores[':tipoAtleta'] = $filtros['tipoAtleta'];
                    }
                    if (!empty($filtros['pesoMin'])) {
                        $consulta .= " AND a.peso >= :pesoMin";
                        $valores[':pesoMin'] = $filtros['pesoMin'];
                    }
                    if (!empty($filtros['pesoMax'])) {
                        $consulta .= " AND a.peso <= :pesoMax";
                        $valores[':pesoMax'] = $filtros['pesoMax'];
                    }
                    break;

                case 'entrenadores':
                    $consulta = "
                        SELECT e.cedula AS id, CONCAT(u.nombre, ' ', u.apellido) AS nombre, 'Entrenador' AS detalles, u.fecha_nacimiento AS fecha
                        FROM entrenador e
                        INNER JOIN usuarios u ON e.cedula = u.cedula
                        WHERE 1=1
                    ";
                    if (!empty($filtros['edadMinEntrenador'])) {
                        $consulta .= " AND TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) >= :edadMin";
                        $valores[':edadMin'] = $filtros['edadMinEntrenador'];
                    }
                    if (!empty($filtros['edadMaxEntrenador'])) {
                        $consulta .= " AND TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) <= :edadMax";
                        $valores[':edadMax'] = $filtros['edadMaxEntrenador'];
                    }
                    if (!empty($filtros['gradoInstruccion'])) {
                        $consulta .= " AND e.grado_instruccion = :gradoInstruccion";
                        $valores[':gradoInstruccion'] = $filtros['gradoInstruccion'];
                    }
                    break;

                case 'mensualidades':
                    $consulta = "
                        SELECT m.id_mensualidad AS id, CONCAT(u.nombre, ' ', u.apellido) AS nombre, 'Mensualidad' AS detalles, m.fecha AS fecha
                        FROM mensualidades m
                        INNER JOIN atleta a ON m.id_atleta = a.cedula
                        INNER JOIN usuarios u ON a.cedula = u.cedula
                        WHERE 1=1
                    ";
                    if (!empty($filtros['fechaInicioMensualidades']) && !empty($filtros['fechaFinMensualidades'])) {
                        $consulta .= " AND m.fecha BETWEEN :fechaInicioMensualidades AND :fechaFinMensualidades";
                        $valores[':fechaInicioMensualidades'] = $filtros['fechaInicioMensualidades'];
                        $valores[':fechaFinMensualidades'] = $filtros['fechaFinMensualidades'];
                    }
                    break;

                case 'wada':
                    $consulta = "
                        SELECT w.id_atleta AS id, CONCAT(u.nombre, ' ', u.apellido) AS nombre, 'WADA' AS detalles, w.vencimiento AS fecha
                        FROM wada w
                        INNER JOIN atleta a ON w.id_atleta = a.cedula
                        INNER JOIN usuarios u ON a.cedula = u.cedula
                        WHERE 1=1
                    ";
                    if (!empty($filtros['fechaInicioMensualidades']) && !empty($filtros['fechaFinMensualidades'])) {
                        $consulta .= " AND w.vencimiento BETWEEN :fechaInicioMensualidades AND :fechaFinMensualidades";
                        $valores[':fechaInicioMensualidades'] = $filtros['fechaInicioMensualidades'];
                        $valores[':fechaFinMensualidades'] = $filtros['fechaFinMensualidades'];
                    }
                    break;

                case 'eventos':
                    $consulta = "
                        SELECT c.id_competencia AS id, c.nombre AS nombre, 'Evento' AS detalles, c.fecha_inicio AS fecha
                        FROM competencia c
                        WHERE 1=1
                    ";
                    if (!empty($filtros['fechaInicioEventos']) && !empty($filtros['fechaFinEventos'])) {
                        $consulta .= " AND c.fecha_inicio BETWEEN :fechaInicioEventos AND :fechaFinEventos";
                        $valores[':fechaInicioEventos'] = $filtros['fechaInicioEventos'];
                        $valores[':fechaFinEventos'] = $filtros['fechaFinEventos'];
                    }
                    break;

                case 'asistencias':
                    $consulta = "
                        SELECT a.id_atleta AS id, CONCAT(u.nombre, ' ', u.apellido) AS nombre, 'Asistencia' AS detalles, a.fecha AS fecha
                        FROM asistencias a
                        INNER JOIN atleta at ON a.id_atleta = at.cedula
                        INNER JOIN usuarios u ON at.cedula = u.cedula
                        WHERE 1=1
                    ";
                    if (!empty($filtros['fechaInicioMensualidades']) && !empty($filtros['fechaFinMensualidades'])) {
                        $consulta .= " AND a.fecha BETWEEN :fechaInicioMensualidades AND :fechaFinMensualidades";
                        $valores[':fechaInicioMensualidades'] = $filtros['fechaInicioMensualidades'];
                        $valores[':fechaFinMensualidades'] = $filtros['fechaFinMensualidades'];
                    }
                    break;
            }

            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $reportes = $respuesta->fetchAll(PDO::FETCH_ASSOC);

            if ($reportes) {
                return ["ok" => true, "reportes" => $reportes];
            } else {
                return ["ok" => false, "mensaje" => "No se encontraron reportes"];
            }
        } catch (Exception $e) {
            return ["ok" => false, "mensaje" => $e->getMessage()];
        }
    }

    public function obtener_resultados_competencias($filtros)
    {
        try {
            $consulta = "
                SELECT c.nombre AS nombreEvento, CONCAT(u.nombre, ' ', u.apellido) AS nombreAtleta, r.arranque, r.envion, r.total
                FROM resultado_competencia r
                INNER JOIN competencia c ON r.id_competencia = c.id_competencia
                INNER JOIN atleta a ON r.id_atleta = a.cedula
                INNER JOIN usuarios u ON a.cedula = u.cedula
                WHERE 1=1
            ";
            $valores = [];

            if (!empty($filtros['fechaInicioEventos']) && !empty($filtros['fechaFinEventos'])) {
                $consulta .= " AND c.fecha_inicio BETWEEN :fechaInicioEventos AND :fechaFinEventos";
                $valores[':fechaInicioEventos'] = $filtros['fechaInicioEventos'];
                $valores[':fechaFinEventos'] = $filtros['fechaFinEventos'];
            }

            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $resultados = $respuesta->fetchAll(PDO::FETCH_ASSOC);

            if ($resultados) {
                return ["ok" => true, "resultados" => $resultados];
            } else {
                return ["ok" => false, "mensaje" => "No se encontraron resultados de competencias"];
            }
        } catch (Exception $e) {
            return ["ok" => false, "mensaje" => $e->getMessage()];
        }
    }
}
