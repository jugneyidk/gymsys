<?php
class Mensualidad extends datos 
{
    private $conexion, $id_atleta, $monto, $fecha;

    public function __construct()
    {
        $this->conexion = $this->conecta(); 
    }

    public function incluir_mensualidad($id_atleta, $monto, $fecha)
    {
        $this->id_atleta = $id_atleta;
        $this->monto = $monto;
        $this->fecha = $fecha;
        return $this->incluir();
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
            $consulta = "INSERT INTO mensualidades (id_atleta, monto, fecha) 
                         VALUES (:id_atleta, :monto, :fecha)";
            $valores = array(
                ':id_atleta' => $this->id_atleta,
                ':monto' => $this->monto,
                ':fecha' => $this->fecha
            );

            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $resultado["ok"] = true;
            
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    private function listado() 
    {
        try {
            $consulta = "
                SELECT u.cedula, u.nombre, u.apellido, m.tipo, m.monto, m.fecha
                FROM mensualidades m
                INNER JOIN atleta a ON m.id_atleta = a.cedula
                INNER JOIN usuarios u ON a.cedula = u.cedula
                ORDER BY m.fecha DESC
            ";
            $con = $this->conexion->prepare($consulta);
            $con->execute();
            $respuesta = $con->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["respuesta"] = $respuesta;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    private function listado_deudores_privado()
    {
        try {
            $consulta = "
                SELECT u.cedula, u.nombre, u.apellido, a.tipo_atleta
                FROM atleta a
                INNER JOIN usuarios u ON a.cedula = u.cedula
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
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }
    

    private function listado_atletas_privado() 
    {
        try {
            $consulta = "
                SELECT u.cedula, u.nombre, u.apellido, a.tipo_atleta
                FROM atleta a
                INNER JOIN usuarios u ON a.cedula = u.cedula
                ORDER BY u.cedula DESC
            ";
            $con = $this->conexion->prepare($consulta);
            $con->execute();
            $respuesta = $con->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["respuesta"] = $respuesta;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
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
    public function __destruct()
    {
        $this->conexion = null;  // Esto cierra la conexión
    }
}
?>
