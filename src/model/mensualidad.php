<?php
class Mensualidad extends datos
{
    private $conexion, $id, $id_atleta, $monto, $fecha, $detalles;

    public function __construct()
    {
        $this->conexion = $this->conecta();
    }

    public function incluir_mensualidad($id_atleta, $monto, $fecha, $detalles)
    {
        if (!Validar::validar("cedula", $id_atleta)["ok"]) {
            return ["ok" => false, "mensaje" => "La cedula del atleta no es valida"];
        }
        if (!Validar::validar_fecha($fecha)) {
            return ["ok" => false, "mensaje" => "La fecha no es valida"];
        }
        if (!Validar::validar("detalles", $detalles)["ok"]) {
            return ["ok" => false, "mensaje" => "Solo letras, números y espacios (200 caracteres maximo)"];
        }
        if (!filter_var($monto, FILTER_VALIDATE_FLOAT)) {
            return ["ok" => false, "mensaje" => "El monto no es un numero valido"];
        }
        $this->id_atleta = $id_atleta;
        $this->monto = $monto;
        $this->fecha = $fecha;
        $this->detalles = trim($detalles);
        return $this->incluir();
    }
    public function eliminar_mensualidad($id)
    {
        if (!filter_var($id, FILTER_SANITIZE_NUMBER_INT)) {
            return ["ok" => false, "mensaje" => "La ID de mensualidad no es válida"];
        }
        $this->id = $id;
        return $this->eliminar();
    }

    public function listado_mensualidades()
    {
        return $this->listado();
    }

    public function listado_deudores()
    {
        return $this->listado_deudores_privado();
    }

    public function listado_atletas()
    {
        return $this->listado_atletas_privado();
    }

    private function incluir()
    {
        try {
            $this->conexion->beginTransaction();
            $consulta = "INSERT INTO mensualidades (id_atleta, monto, fecha, detalles) 
                         VALUES (:id_atleta, :monto, :fecha, :detalles)";
            $valores = array(
                ':id_atleta' => $this->id_atleta,
                ':monto' => $this->monto,
                ':fecha' => $this->fecha,
                ':detalles' => $this->detalles
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
    private function eliminar()
    {
        try {
            $consulta = "SELECT id_mensualidad FROM mensualidades WHERE id_mensualidad = ?;";
            $existe = Validar::existe($this->conexion, $this->id, $consulta);
            if (!$existe["ok"]) {
                $resultado["ok"] = false;
                $resultado["mensaje"] = "No existe esta mensualidad";
                return $resultado;
            }
            $this->conexion->beginTransaction();
            $consulta = "DELETE FROM mensualidades WHERE id_mensualidad = :id";
            $valores = array(
                ':id' => $this->id
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

    private function listado()
    {
        try {
            $consulta = "
                SELECT m.id_mensualidad, u.cedula, u.nombre, u.apellido, m.monto, m.fecha, m.detalles, t.nombre_tipo_atleta
                FROM mensualidades m
                INNER JOIN atleta a ON m.id_atleta = a.cedula
                INNER JOIN usuarios u ON a.cedula = u.cedula
                INNER JOIN tipo_atleta t ON a.tipo_atleta = t.id_tipo_atleta
                ORDER BY m.fecha DESC
            ";
            $con = $this->conexion->prepare($consulta);
            $con->execute();
            $respuesta = $con->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["respuesta"] = $respuesta;
        } catch (PDOException $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
        return $resultado;
    }

    private function listado_deudores_privado()
    {
        try {
            $consulta = "
                SELECT u.cedula, u.nombre, u.apellido, t.nombre_tipo_atleta, t.tipo_cobro
                FROM atleta a
                INNER JOIN usuarios u ON a.cedula = u.cedula
                INNER JOIN tipo_atleta t ON a.tipo_atleta = t.id_tipo_atleta
                LEFT JOIN mensualidades m ON a.cedula = m.id_atleta 
                  AND m.fecha >= DATE_FORMAT(NOW(), '%Y-%m-01') 
                  AND m.fecha <= LAST_DAY(NOW())
                WHERE m.id_atleta IS NULL 
                GROUP BY u.cedula
            ";
            $con = $this->conexion->prepare($consulta);
            $con->execute();
            $respuesta = $con->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["respuesta"] = $respuesta;
        } catch (PDOException $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        $this->desconecta();
        return $resultado;
    }


    private function listado_atletas_privado()
    {
        try {
            $consulta = "
                SELECT u.cedula, u.nombre, u.apellido, t.nombre_tipo_atleta, t.tipo_cobro
                FROM atleta a
                INNER JOIN usuarios u ON a.cedula = u.cedula
                INNER JOIN tipo_atleta t ON a.tipo_atleta = t.id_tipo_atleta
                ORDER BY u.cedula DESC
            ";
            $con = $this->conexion->prepare($consulta);
            $con->execute();
            $respuesta = $con->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["respuesta"] = $respuesta;
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