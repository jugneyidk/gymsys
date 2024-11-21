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
                LIMIT 5;";
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

}