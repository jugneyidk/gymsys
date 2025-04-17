<?php
class Notificaciones extends datos
{
    private $conexion, $id_usuario, $id_notificacion, $pagina;

    public function __construct()
    {
        $this->conexion = $this->conecta();
    }
    public function obtener_notificaciones($id_usuario)
    {
        $validacion = Validar::validar("cedula", $id_usuario);
        if (!$validacion["ok"]) {
            return ["ok" => false, "mensaje", "El usuario no es valido"];
        }
        $this->id_usuario = $id_usuario;
        return $this->obtener();
    }

    public function marcar_leida($id_notificacion)
    {
        $this->id_notificacion = filter_var($id_notificacion, FILTER_SANITIZE_NUMBER_INT);
        return $this->guardar();
    }
    public function marcar_todo_leido($id_usuario)
    {
        $validacion = Validar::validar("cedula", $id_usuario);
        if (!$validacion["ok"]) {
            return ["ok" => false, "mensaje", "El usuario no es valido"];
        }
        $this->id_usuario = $id_usuario;
        return $this->todo_leido();
    }
    public function ver_todas_notificaciones($id_usuario, $pagina)
    {
        $validacion = Validar::validar("cedula", $id_usuario);
        if (!$validacion["ok"]) {
            return ["ok" => false, "mensaje", "El usuario no es valido"];
        }
        $this->id_usuario = $id_usuario;
        $this->pagina = (int) filter_var($pagina, FILTER_SANITIZE_NUMBER_INT);
        return $this->todas_notificaciones();
    }

    private function guardar()
    {
        try {
            $consulta = "SELECT id FROM notificaciones WHERE id = ?;";
            $existe = Validar::existe($this->conexion, $this->id_notificacion, $consulta);
            if (!$existe["ok"]) {
                $resultado["ok"] = false;
                $resultado["mensaje"] = "Esta notificación no existe";
                return $resultado;
            }
            $this->conexion->beginTransaction();
            $consulta = "UPDATE notificaciones SET leida = 1
            WHERE id = :id_notificacion;
            ";
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute([':id_notificacion' => $this->id_notificacion]);
            $stmt->closeCursor();
            $this->conexion->commit();
            $resultado["ok"] = true;
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
        return $resultado;
    }
    private function todo_leido()
    {
        try {
            $consulta = "SELECT id FROM notificaciones WHERE id_usuario = ?;";
            $existe = Validar::existe($this->conexion, $this->id_usuario, $consulta);
            if (!$existe["ok"]) {
                $resultado["ok"] = false;
                $resultado["mensaje"] = "Este usuario no tiene notificaciones";
                return $resultado;
            }
            $this->conexion->beginTransaction();
            $consulta = "UPDATE notificaciones SET leida = 1
            WHERE id_usuario = :id_usuario;
            ";
            $stmt = $this->conexion->prepare($consulta);
            $stmt->execute([':id_usuario' => $this->id_usuario]);
            $stmt->closeCursor();
            $this->conexion->commit();
            $resultado["ok"] = true;
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
        return $resultado;
    }

    private function obtener()
    {
        try {
            $consulta = "
                SELECT 
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
                LIMIT 4;";
            $con = $this->conexion->prepare($consulta);
            $con->execute([':id_usuario' => $_SESSION["id_usuario"]]);
            $respuesta = $con->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["notificaciones"] = $respuesta;
        } catch (PDOException $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
        return $resultado;
    }
    private function todas_notificaciones()
    {
        try {
            $limite = 10;
            $diferencia = ($this->pagina - 1) * $limite;
            $consulta = "
                SELECT 
                    id,
                    titulo,
                    mensaje,
                    leida,
                    objetivo,
                    fecha_creacion
                FROM notificaciones n
                WHERE n.id_usuario = :id_usuario 
                ORDER BY id DESC
                LIMIT :limite
                OFFSET :diferencia;";
            $con = $this->conexion->prepare($consulta);
            $con->bindValue(':id_usuario', $this->id_usuario);
            $con->bindValue(':limite', $limite, PDO::PARAM_INT);
            $con->bindValue(':diferencia', $diferencia, PDO::PARAM_INT);
            $con->execute();
            $respuesta = $con->fetchAll(PDO::FETCH_ASSOC);
            $notificaciones_totales = "SELECT COUNT(*) AS total FROM notificaciones WHERE id_usuario = :id_usuario";
            $total_respuesta = $this->conexion->prepare($notificaciones_totales);
            $total_respuesta->bindValue(':id_usuario', $this->id_usuario);
            $total_respuesta->execute();
            $total_notificaciones = $total_respuesta->fetchColumn();
            $ver_mas = ($this->pagina * $limite) < $total_notificaciones;
            $resultado["ok"] = true;
            $resultado["notificaciones"] = $respuesta;
            $resultado["ver_mas"] = $ver_mas;
        } catch (PDOException $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
        return $resultado;
    }

    public function crear_notificaciones()
    {
        try {

            $notificaciones_creadas = 0;
            $notificaciones_wada = $this->crear_notificaciones_wada();
            $notificaciones_mensualidad = $this->crear_notificaciones_mensualidad();
            if (is_int($notificaciones_wada)) {
                $notificaciones_creadas += $notificaciones_wada;
            }
            if (is_int($notificaciones_mensualidad)) {
                $notificaciones_creadas += $notificaciones_mensualidad;
            }
            $resultado["ok"] = true;
            $resultado["notificaciones_creadas"] = $notificaciones_creadas ?? 0;
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
        return $resultado;
    }
    private function crear_notificaciones_wada()
    {
        try {
            // Consulta para obtener los atletas cuyo vencimiento de WADA está entre hoy y los próximos 30 días
            $consulta = "SELECT u.cedula, 
                                u.nombre, 
                                u.apellido, 
                                w.vencimiento,
                                a.entrenador
                        FROM atleta a
                        INNER JOIN usuarios u ON a.cedula = u.cedula
                        INNER JOIN wada w ON w.id_atleta = u.cedula
                        WHERE w.vencimiento >= CURDATE() 
                        AND w.vencimiento <= DATE_ADD(CURDATE(), INTERVAL 31 DAY)
                        ORDER BY w.vencimiento DESC;";

            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute();
            $respuesta = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            $notificaciones_creadas = 0;
            if (count($respuesta) > 0) {
                $this->conexion->beginTransaction();
                $notificaciones_creadas = 0;
                foreach ($respuesta as $valor) {
                    // Calcula la diferencia en días entre el vencimiento y la fecha actual
                    $fecha_vencimiento = new DateTime($valor['vencimiento']);
                    $fecha_hoy = new DateTime();
                    $diferencia_dias = $fecha_hoy->diff($fecha_vencimiento)->days;
                    // Verificar si la diferencia es 30, 15, 7 o 1 dias
                    if (in_array($diferencia_dias, [30, 15, 7, 1])) {
                        // Verifica si ya existe la notificacion para este dia
                        $consulta_existente = "SELECT COUNT(*) 
                                              FROM notificaciones 
                                              WHERE id_usuario = :id_usuario 
                                              AND objetivo = 'wada' 
                                              AND mensaje LIKE :mensaje";
                        $stmt_existente = $this->conexion->prepare($consulta_existente);
                        $mensaje = "La WADA del atleta {$valor['nombre']} {$valor['apellido']} se vencerá en {$diferencia_dias} día" . ($diferencia_dias > 1 ? "s" : "");
                        $stmt_existente->execute([
                            ':id_usuario' => $valor["entrenador"],
                            ':mensaje' => $mensaje
                        ]);
                        $existe = $stmt_existente->fetchColumn();
                        if ($existe == 0) {
                            // Inserta la notificación solo si la WADA vence en 30, 15, 7 o 1 día
                            $consulta = "INSERT INTO notificaciones(id_usuario, titulo, mensaje, objetivo)
                                        VALUES (:id_usuario, :titulo, :mensaje, :objetivo)";
                            $stmt = $this->conexion->prepare($consulta);
                            $mensaje = "La WADA del atleta {$valor['nombre']} {$valor['apellido']} se vencerá en {$diferencia_dias} día" . ($diferencia_dias > 1 ? "s" : "");
                            $valores = [
                                ":id_usuario" => $valor["entrenador"],
                                ":titulo" => "Una WADA vencerá pronto",
                                ":mensaje" => $mensaje,
                                ":objetivo" => "wada",
                            ];
                            $stmt->execute($valores);
                            $stmt->closeCursor();
                            $notificaciones_creadas++;
                        }
                    }
                    if ($fecha_hoy->format('Y-m-d') === $fecha_vencimiento->format('Y-m-d')) {
                        // Verifica si ya existe la notificacion de vencida
                        $consulta_existente = "SELECT COUNT(*) 
                                              FROM notificaciones 
                                              WHERE id_usuario = :id_usuario 
                                              AND objetivo = 'wada' 
                                              AND mensaje LIKE :mensaje";
                        $stmt_existente = $this->conexion->prepare($consulta_existente);
                        $mensaje = "La WADA del atleta {$valor['nombre']} {$valor['apellido']} se venció";
                        $stmt_existente->execute([
                            ':id_usuario' => $valor["entrenador"],
                            ':mensaje' => $mensaje
                        ]);
                        $existe = $stmt_existente->fetchColumn();
                        if ($existe == 0) {
                            $consulta = "INSERT INTO notificaciones(id_usuario, titulo, mensaje, objetivo)
                                VALUES (:id_usuario, :titulo, :mensaje, :objetivo)";
                            $stmt = $this->conexion->prepare($consulta);
                            $valores = [
                                ":id_usuario" => $valor["entrenador"],
                                ":titulo" => "La WADA ha vencido hoy",
                                ":mensaje" => $mensaje,
                                ":objetivo" => "wada",
                            ];
                            $stmt->execute($valores);
                            $stmt->closeCursor();
                            $notificaciones_creadas++;
                        }
                    }
                }
            }
            $this->conexion->commit();
            return $notificaciones_creadas;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
    private function crear_notificaciones_mensualidad()
    {
        try {
            $notificaciones_creadas = 0;
            // Consulta para obtener la cantidad de atletas que deben la mensualidad en el mes actual
            $consulta = "SELECT COUNT(u.cedula) AS cantidad_deudores
                    FROM atleta a
                    INNER JOIN usuarios u ON a.cedula = u.cedula
                    INNER JOIN tipo_atleta t ON a.tipo_atleta = t.id_tipo_atleta
                    LEFT JOIN mensualidades m ON a.cedula = m.id_atleta 
                    AND m.fecha >= DATE_FORMAT(NOW(), '%Y-%m-01') 
                    AND m.fecha <= LAST_DAY(NOW())
                    WHERE m.id_atleta IS NULL";

            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute();
            $cantidad_deudores = $respuesta->fetchColumn();
            if ($cantidad_deudores > 0) {
                $this->conexion->beginTransaction();
                $mensaje = "Hay {$cantidad_deudores} atleta" . ($cantidad_deudores > 1 ? "s" : "") . " que debe" . ($cantidad_deudores > 1 ? "n" : "") . " la mensualidad este mes";
                // Verificar si ya existe una notificación reciente (dentro de los últimos 3 días)
                $consulta_existente = "SELECT COUNT(*) 
                                   FROM notificaciones 
                                   WHERE objetivo = 'mensualidad' 
                                   AND mensaje LIKE :mensaje 
                                   AND fecha_creacion >= CURDATE() - INTERVAL 3 DAY";

                $stmt_existente = $this->conexion->prepare($consulta_existente);
                $stmt_existente->execute([
                    ':mensaje' => $mensaje
                ]);
                $existe = $stmt_existente->fetchColumn();
                if ($existe == 0) {
                    $entrenadores = "SELECT cedula
                    FROM entrenador WHERE 1;";
                    $lista_entrenadores = $this->conexion->prepare($entrenadores);
                    $lista_entrenadores->execute();
                    $lista_entrenadores = $lista_entrenadores->fetchAll(PDO::FETCH_ASSOC);
                    print_r($lista_entrenadores);
                    foreach ($lista_entrenadores as $entrenador) {
                        $consulta = "INSERT INTO notificaciones(id_usuario, titulo, mensaje, objetivo)
                                VALUES (:id_usuario, :titulo, :mensaje, :objetivo)";
                        $stmt = $this->conexion->prepare($consulta);
                        $valores = [
                            ":id_usuario" => $entrenador["cedula"],
                            ":titulo" => "Atletas con mensualidad pendiente",
                            ":mensaje" => $mensaje,
                            ":objetivo" => "mensualidad",
                        ];
                        $stmt->execute($valores);
                        $stmt->closeCursor();
                        $notificaciones_creadas = 1;
                    }
                }
                $this->conexion->commit();
            }
            return $notificaciones_creadas;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

}

