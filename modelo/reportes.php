<?php
require_once('modelo/datos.php');

class Reporte extends datos
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = $this->conecta(); 
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
                    if (isset($filtros['edadMin']) && $filtros['edadMin'] !== '') {
                        $consulta .= " AND TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) >= :edadMin";
                        $valores[':edadMin'] = $filtros['edadMin'];
                    }
                    if (isset($filtros['edadMax']) && $filtros['edadMax'] !== '') {
                        $consulta .= " AND TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) <= :edadMax";
                        $valores[':edadMax'] = $filtros['edadMax'];
                    }
                    if (isset($filtros['genero']) && $filtros['genero'] !== '') {
                        $consulta .= " AND u.genero = :genero";
                        $valores[':genero'] = $filtros['genero'];
                    }
                    if (isset($filtros['tipoAtleta']) && $filtros['tipoAtleta'] !== '') {
                        $consulta .= " AND a.tipo_atleta = :tipoAtleta";
                        $valores[':tipoAtleta'] = $filtros['tipoAtleta'];
                    }
                    if (isset($filtros['pesoMin']) && $filtros['pesoMin'] !== '') {
                        $consulta .= " AND a.peso >= :pesoMin";
                        $valores[':pesoMin'] = $filtros['pesoMin'];
                    }
                    if (isset($filtros['pesoMax']) && $filtros['pesoMax'] !== '') {
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
                    if (isset($filtros['edadMinEntrenador']) && $filtros['edadMinEntrenador'] !== '') {
                        $consulta .= " AND TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) >= :edadMin";
                        $valores[':edadMin'] = $filtros['edadMinEntrenador'];
                    }
                    if (isset($filtros['edadMaxEntrenador']) && $filtros['edadMaxEntrenador'] !== '') {
                        $consulta .= " AND TIMESTAMPDIFF(YEAR, u.fecha_nacimiento, CURDATE()) <= :edadMax";
                        $valores[':edadMax'] = $filtros['edadMaxEntrenador'];
                    }
                    if (isset($filtros['gradoInstruccion']) && $filtros['gradoInstruccion'] !== '') {
                        $consulta .= " AND e.grado_instruccion = :gradoInstruccion";
                        $valores[':gradoInstruccion'] = $filtros['gradoInstruccion'];
                    }
                    break;
                case 'mensualidades':
                    $periodo = isset($filtros['periodoMensualidades']) ? $filtros['periodoMensualidades'] : 'mes';
                    $intervalo = $this->getIntervalo($periodo);
                    $consulta = "
                        SELECT m.id_mensualidad AS id, CONCAT(u.nombre, ' ', u.apellido) AS nombre, 'Mensualidad' AS detalles, m.fecha AS fecha
                        FROM mensualidades m
                        INNER JOIN atleta a ON m.id_atleta = a.cedula
                        INNER JOIN usuarios u ON a.cedula = u.cedula
                        WHERE m.fecha >= DATE_SUB(CURDATE(), INTERVAL $intervalo)
                        ORDER BY m.fecha DESC
                    ";
                    break;
                case 'wada':
                    $periodo = isset($filtros['periodoWada']) ? $filtros['periodoWada'] : 'mes';
                    $intervalo = $this->getIntervalo($periodo);
                    $consulta = "
                        SELECT w.id_atleta AS id, CONCAT(u.nombre, ' ', u.apellido) AS nombre, 'WADA' AS detalles, w.vencimiento AS fecha
                        FROM wada w
                        INNER JOIN atleta a ON w.id_atleta = a.cedula
                        INNER JOIN usuarios u ON a.cedula = u.cedula
                        WHERE w.vencimiento >= DATE_SUB(CURDATE(), INTERVAL $intervalo)
                        ORDER BY w.vencimiento DESC
                    ";
                    break;
                case 'eventos':
                    $periodo = isset($filtros['periodoEventos']) ? $filtros['periodoEventos'] : 'mes';
                    $intervalo = $this->getIntervalo($periodo);
                    $consulta = "
                        SELECT c.id_competencia AS id, c.nombre AS nombre, 'Evento' AS detalles, c.fecha_inicio AS fecha
                        FROM competencia c
                        WHERE c.fecha_inicio >= DATE_SUB(CURDATE(), INTERVAL $intervalo)
                        ORDER BY c.fecha_inicio DESC
                    ";
                    break;
                case 'asistencias':
                    $periodo = isset($filtros['periodoAsistencias']) ? $filtros['periodoAsistencias'] : 'mes';
                    $intervalo = $this->getIntervalo($periodo);
                    $consulta = "
                        SELECT a.id_atleta AS id, CONCAT(u.nombre, ' ', u.apellido) AS nombre, 'Asistencia' AS detalles, a.fecha AS fecha
                        FROM asistencias a
                        INNER JOIN atleta at ON a.id_atleta = at.cedula
                        INNER JOIN usuarios u ON at.cedula = u.cedula
                        WHERE a.fecha >= DATE_SUB(CURDATE(), INTERVAL $intervalo)
                        ORDER BY a.fecha DESC
                    ";
                    break;
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

    private function getIntervalo($periodo)
    {
        switch ($periodo) {
            case 'mes':
                return '1 MONTH';
            case 'trimestre':
                return '3 MONTH';
            case 'año':
                return '1 YEAR';
            case 'semana':
                return '1 WEEK';
            default:
                return '1 MONTH';
        }
    }
}
?>
