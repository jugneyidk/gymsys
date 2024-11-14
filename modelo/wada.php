<?php

class WADA extends datos
{
    private $conexion, $id_atleta, $estado, $inscrito, $ultima_actualizacion, $vencimiento;

    public function __construct()
    {
        $this->conexion = $this->conecta();
    }

    public function incluir_wada($id_atleta, $estado, $inscrito, $ultima_actualizacion, $vencimiento)
    {
        if (!Validar::validar("cedula", $id_atleta)["ok"]) {
            return ["ok" => false, "mensaje" => "La cedula del atleta no es valida"];
        }
        if (!Validar::validar("bool", $estado)["ok"]) {
            return ["ok" => false, "mensaje" => "El estado de la WADA no es valido"];
        }
        $validar_fechas = Validar::validar_fechas_wada($this->conexion, $id_atleta, $inscrito, $ultima_actualizacion, $vencimiento);
        if (!$validar_fechas["ok"]) {
            return ["ok" => false, "mensaje" => $validar_fechas["mensaje"]];
        }
        $this->id_atleta = $id_atleta;
        $this->estado = $estado;
        $this->inscrito = $inscrito;
        $this->ultima_actualizacion = $ultima_actualizacion;
        $this->vencimiento = $vencimiento;
        return $this->incluir();
    }

    public function modificar_wada($id_atleta, $estado, $inscrito, $ultima_actualizacion, $vencimiento)
    {
        if (!Validar::validar("cedula", $id_atleta)["ok"]) {
            return ["ok" => false, "mensaje" => "La cedula del atleta no es valida"];
        }
        if (!Validar::validar("bool", $estado)["ok"]) {
            return ["ok" => false, "mensaje" => "El estado de la WADA no es valido"];
        }
        $validar_fechas = Validar::validar_fechas_wada($this->conexion, $id_atleta, $inscrito, $ultima_actualizacion, $vencimiento);
        if (!$validar_fechas["ok"]) {
            return ["ok" => false, "mensaje" => $validar_fechas["mensaje"]];
        }
        $this->id_atleta = $id_atleta;
        $this->estado = $estado;
        $this->inscrito = $inscrito;
        $this->ultima_actualizacion = $ultima_actualizacion;
        $this->vencimiento = $vencimiento;
        return $this->modificar();
    }

    public function obtener_wada($id_atleta)
    {
        if (!Validar::validar("cedula", $id_atleta)) {
            return ["ok" => false, "mensaje" => "La cedula del atleta no es valida"];
        }
        $this->id_atleta = $id_atleta;
        return $this->obtener();
    }

    public function eliminar_wada($id_atleta)
    {
        if (!Validar::validar("cedula", $id_atleta)) {
            return ["ok" => false, "mensaje" => "La cedula del atleta no es valida"];
        }
        $this->id_atleta = $id_atleta;
        return $this->eliminar();
    }

    public function listado_wada()
    {
        return $this->listado();
    }

    private function incluir()
    {
        try {
            $consulta = "SELECT cedula FROM atleta WHERE cedula = ?;";
            $existe = Validar::existe($this->conexion, $this->id_atleta, $consulta);
            // Se verifica que el atleta exista para asignarle la WADA
            if (!$existe["ok"]) {
                $resultado["ok"] = false;
                $resultado["mensaje"] = "Este atleta no existe";
                return $resultado;
            }
            $consulta = "SELECT id_atleta FROM wada WHERE id_atleta = ?;";
            $existe = Validar::existe($this->conexion, $this->id_atleta, $consulta);
            // Se verifica que no exista la WADA para este atleta
            if ($existe["ok"]) {
                $resultado["ok"] = false;
                $resultado["mensaje"] = "Ya existe la WADA de este atleta";
                return $resultado;
            }
            $this->conexion->beginTransaction();
            $consulta = "INSERT INTO wada (id_atleta, estado, inscrito, ultima_actualizacion, vencimiento) 
                         VALUES (:id_atleta, :estado, :inscrito, :ultima_actualizacion, :vencimiento)";
            $valores = array(
                ':id_atleta' => $this->id_atleta,
                ':estado' => $this->estado,
                ':inscrito' => $this->inscrito,
                ':ultima_actualizacion' => $this->ultima_actualizacion,
                ':vencimiento' => $this->vencimiento
            );
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $respuesta->closeCursor();
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

    private function modificar()
    {
        try {
            $consulta = "SELECT id_atleta FROM wada WHERE id_atleta = ?;";
            $existe = Validar::existe($this->conexion, $this->id_atleta, $consulta);
            if (!$existe["ok"]) {
                $resultado["ok"] = false;
                $resultado["mensaje"] = "No existe la WADA de este atleta";
                return $resultado;
            }
            $this->conexion->beginTransaction();
            $consulta = "UPDATE wada SET estado = :estado, inscrito = :inscrito, ultima_actualizacion = :ultima_actualizacion, vencimiento = :vencimiento 
                         WHERE id_atleta = :id_atleta";
            $valores = array(
                ':id_atleta' => $this->id_atleta,
                ':estado' => $this->estado,
                ':inscrito' => $this->inscrito,
                ':ultima_actualizacion' => $this->ultima_actualizacion,
                ':vencimiento' => $this->vencimiento
            );
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $respuesta->closeCursor();
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
            $consulta = "SELECT id_atleta FROM wada WHERE id_atleta = ?;";
            $existe = Validar::existe($this->conexion, $this->id_atleta, $consulta);
            if (!$existe["ok"]) {
                $resultado["ok"] = false;
                $resultado["mensaje"] = "No existe la WADA de este atleta";
                return $resultado;
            }
            $consulta = "SELECT * FROM wada WHERE id_atleta = :id_atleta";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute([':id_atleta' => $this->id_atleta]);
            $respuesta = $respuesta->fetch(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["wada"] = $respuesta;
        } catch (PDOException $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
        return $resultado;
    }

    private function eliminar()
    {
        try {
            $consulta = "SELECT id_atleta FROM wada WHERE id_atleta = ?;";
            $existe = Validar::existe($this->conexion, $this->id_atleta, $consulta);
            if (!$existe["ok"]) {
                $resultado["ok"] = false;
                $resultado["mensaje"] = "La WADA del atleta ingresado no existe";
                return $resultado;
            }
            $this->conexion->beginTransaction();
            $consulta = "DELETE FROM wada WHERE id_atleta = :id_atleta";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute([':id_atleta' => $this->id_atleta]);
            $respuesta->closeCursor();
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

    private function listado()
    {
        try {
            $consulta = "SELECT 
                    u.cedula, 
                    u.nombre, 
                    u.apellido, 
                    w.estado,
                    w.inscrito,
                    w.vencimiento,
                    w.ultima_actualizacion
                FROM atleta a
                INNER JOIN usuarios u ON a.cedula = u.cedula
                INNER JOIN wada w ON w.id_atleta = u.cedula
                ORDER BY u.cedula DESC";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute();
            $respuesta = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["respuesta"] = $respuesta;
        } catch (PDOException $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
        return $resultado;
    }

    public function listado_atletas()
    {
        try {
            $consulta = "
                SELECT 
                    u.cedula, 
                    u.nombre, 
                    u.apellido, 
                    u.genero, 
                    u.fecha_nacimiento, 
                    u.lugar_nacimiento, 
                    u.estado_civil, 
                    u.telefono, 
                    u.correo_electronico, 
                    a.tipo_atleta, 
                    a.peso, 
                    a.altura, 
                    a.entrenador 
                FROM atleta a
                INNER JOIN usuarios u ON a.cedula = u.cedula
                ORDER BY u.cedula DESC
            ";
            $con = $this->conexion->prepare($consulta);
            $con->execute();
            $respuesta = $con->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["devol"] = 'listado_atletas';
            $resultado["respuesta"] = $respuesta;
        } catch (PDOException $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
        return $resultado;
    }

    public function obtener_proximos_vencer()
    {
        try {
            $consulta = "SELECT u.cedula, 
                u.nombre, 
                u.apellido, 
                w.vencimiento
            FROM atleta a
            INNER JOIN usuarios u ON a.cedula = u.cedula
            INNER JOIN wada w ON w.id_atleta = u.cedula
            WHERE w.vencimiento > CURDATE() 
            AND w.vencimiento <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
            ORDER BY w.vencimiento DESC;";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute();
            $registros = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["respuesta"] = $registros;
        } catch (PDOException $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
        return $resultado;
    }

    public function __get($propiedad)
    {
        return $this->$propiedad;
    }

    public function __set($propiedad, $valor)
    {
        $this->$propiedad = $valor;
        return $this;
    }
}
