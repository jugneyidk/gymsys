<?php
class Dashboard extends datos
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = $this->conecta();
    }
    public function obtener_ultimos_atletas()
    {
        try {
            $consulta = "SELECT u.cedula, u.nombre, u.apellido, u.genero, u.fecha_nacimiento 
                         FROM usuarios u
                         JOIN atleta a ON u.cedula = a.cedula
                         ORDER BY u.cedula DESC LIMIT 2";  
            $respuesta = $this->conexion->query($consulta);
            return $respuesta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
 
    public function obtener_ultimas_acciones()
    {
        try {
            $consulta = "SELECT b.accion, b.fecha, u.nombre, u.apellido, b.modulo 
                         FROM bitacora b
                         JOIN usuarios u ON b.id_usuario = u.cedula
                         ORDER BY b.fecha DESC LIMIT 3"; 
            $respuesta = $this->conexion->query($consulta);
            return $respuesta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    public function total_atletas()
    {
        try {
            $consulta = "SELECT COUNT(*) as total FROM atleta";
            $respuesta = $this->conexion->query($consulta);
            $resultado = $respuesta->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'];
        } catch (Exception $e) {
            return 0;
        }
    }
 
    public function total_entrenadores()
    {
        try {
            $consulta = "SELECT COUNT(*) as total FROM entrenador";
            $respuesta = $this->conexion->query($consulta);
            $resultado = $respuesta->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'];
        } catch (Exception $e) {
            return 0;
        }
    }
 
    public function total_reportes()
    {
        try {
            $consulta = "SELECT COUNT(*) as total FROM bitacora WHERE MONTH(fecha) = MONTH(CURRENT_DATE())";
            $respuesta = $this->conexion->query($consulta);
            $resultado = $respuesta->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'];
        } catch (Exception $e) {
            return 0;
        }
    }
 
    public function total_wadas_pendientes()
    {
        try {
            $consulta = "SELECT COUNT(*) as total
            FROM atleta a
            INNER JOIN usuarios u ON a.cedula = u.cedula
            INNER JOIN wada w ON w.id_atleta = u.cedula
            WHERE w.vencimiento > CURDATE() 
            AND w.vencimiento <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
            ORDER BY w.vencimiento DESC;";
            $respuesta = $this->conexion->query($consulta);
            $resultado = $respuesta->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'];
        } catch (Exception $e) {
            return 0;
        }
    }
 
    public function obtener_medallas_por_mes()
    {
        try {
            $consulta = "SELECT MONTH(fecha_competencia) as mes, COUNT(*) as total_medallas 
                         FROM competencias 
                         GROUP BY MONTH(fecha_competencia)";
            $respuesta = $this->conexion->query($consulta);
            $datos = $respuesta->fetchAll(PDO::FETCH_ASSOC);
 
            $labels = [];
            $medallas_por_mes = [];
            foreach ($datos as $dato) {
                $mes = $this->getNombreMes($dato['mes']);  
                $labels[] = $mes;
                $medallas_por_mes[] = $dato['total_medallas'];
            }

            return ['labels' => $labels, 'medallas' => $medallas_por_mes];
        } catch (Exception $e) {
            return ['labels' => [], 'medallas' => []];
        }
    }
 
    public function obtener_progreso_semanal()
    {
        try {
            $consulta = "SELECT semana, SUM(progreso) as total_progreso 
                         FROM progreso_atletas 
                         GROUP BY semana";
            $respuesta = $this->conexion->query($consulta);
            $datos = $respuesta->fetchAll(PDO::FETCH_ASSOC);
 
            $labels = [];
            $progreso_semanal = [];
            foreach ($datos as $dato) {
                $labels[] = 'Semana ' . $dato['semana'];
                $progreso_semanal[] = $dato['total_progreso'];
            }

            return ['labels' => $labels, 'progreso' => $progreso_semanal];
        } catch (Exception $e) {
            return ['labels' => [], 'progreso' => []];
        }
    }
 
    private function getNombreMes($mes)
    {
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];
        return $meses[$mes];
    }
}
