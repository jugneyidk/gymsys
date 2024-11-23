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

    public function obtener_ultimas_notificaciones()
    {
        try {
            $consulta = "SELECT 
                    id,
                    titulo,
                    mensaje,
                    leida,
                    objetivo,
                    fecha_creacion
                FROM notificaciones n
                INNER JOIN usuarios u ON n.id_usuario = u.cedula
                WHERE n.id_usuario = :id_usuario 
                ORDER BY id DESC
                LIMIT 3;";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute([":id_usuario" => $_SESSION["id_usuario"]]);
            $respuesta = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            foreach ($respuesta as $clave => $notificacion) {
                $respuesta[$clave]["fecha_corta"] = $this->calcular_tiempo_fecha($notificacion["fecha_creacion"]);
            }
        } catch (PDOException $e) {
            $respuesta = $e->getMessage();
        }
        $this->desconecta();
        return $respuesta;
    }

    private function calcular_tiempo_fecha($fecha_creacion)
    {
        $ahora = new DateTime();
        $fecha = new DateTime($fecha_creacion);
        $diferencia = $ahora->getTimestamp() - $fecha->getTimestamp(); // Diferencia en segundos
        if ($diferencia < 60) {
            // Menos de 1 minuto
            return "Hace $diferencia segundos";
        } elseif ($diferencia < 3600) {
            // Menos de 1 hora
            $minutos = floor($diferencia / 60);
            return "Hace $minutos minuto" . ($minutos > 1 ? "s" : "");
        } elseif ($diferencia < 86400) {
            // Menos de 1 día
            $horas = floor($diferencia / 3600);
            return "Hace $horas hora" . ($horas > 1 ? "s" : "");
        } elseif ($diferencia < 7 * 86400) {
            // Menos de 1 semana
            $dias = floor($diferencia / 86400);
            return "Hace $dias día" . ($dias > 1 ? "s" : "");
        } else {
            // Fecha completa para más de una semana
            return $fecha->format('d/m/Y'); // Cambia el formato según tus necesidades
        }
    }

    public function obtener_ultimas_acciones()
    {
        try {
            $consulta = "SELECT b.accion, b.fecha, u.nombre, u.apellido, b.modulo 
                         FROM bitacora b
                         JOIN usuarios u ON b.id_usuario = u.cedula
                         ORDER BY b.fecha DESC LIMIT 3";
            $resultado = $this->conexion->query($consulta);
            $resultado->execute();
            $respuesta = $resultado->fetchAll(PDO::FETCH_ASSOC);
            foreach ($respuesta as $clave => $accion) {
                $respuesta[$clave]["fecha_corta"] = $this->calcular_tiempo_fecha($accion["fecha"]);
            }
        } catch (PDOException $e) {
            $respuesta = $e->getMessage();
        }
        $this->desconecta();
        return $respuesta;
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
