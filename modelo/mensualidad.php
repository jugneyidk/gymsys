<?php
require_once('modelo/datos.php');

class Mensualidad extends datos
{
    private $conexion, $id_mensualidad, $id_atleta, $tipo, $cobro, $pago, $fecha;

    public function __construct()
    {
        $this->conexion = $this->conecta(); // Inicia la conexión a la DB
    }

    public function incluir_mensualidad($id_atleta, $tipo, $cobro, $pago, $fecha)
    {
        $this->id_atleta = $id_atleta;
        $this->tipo = $tipo;
        $this->cobro = $cobro;
        $this->pago = $pago;
        $this->fecha = $fecha;
        return $this->incluir();
    }

    public function listado_mensualidad()
    {
        return $this->listado();
    }

    public function modificar_mensualidad($id_mensualidad, $id_atleta, $tipo, $cobro, $pago, $fecha)
    {
        $this->id_mensualidad = $id_mensualidad;
        $this->id_atleta = $id_atleta;
        $this->tipo = $tipo;
        $this->cobro = $cobro;
        $this->pago = $pago;
        $this->fecha = $fecha;
        return $this->modificar();
    }

    public function eliminar_mensualidad($id_mensualidad)
    {
        $this->id_mensualidad = $id_mensualidad;
        return $this->eliminar();
    }

    private function incluir()
    {
        try {
            $consulta = "INSERT INTO mensualidad (id_atleta, tipo, cobro, pago, fecha) 
                         VALUES (:id_atleta, :tipo, :cobro, :pago, :fecha)";
            $valores = array(
                ':id_atleta' => $this->id_atleta,
                ':tipo' => $this->tipo,
                ':cobro' => $this->cobro,
                ':pago' => $this->pago,
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
            $consulta = "SELECT * FROM mensualidad ORDER BY id_mensualidad DESC";
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute();
            $respuesta = $respuesta->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["respuesta"] = $respuesta;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    private function modificar()
    {
        try {
            $consulta = "UPDATE mensualidad SET id_atleta = :id_atleta, tipo = :tipo, cobro = :cobro, pago = :pago, fecha = :fecha                          WHERE id_mensualidad = :id_mensualidad";
            $valores = array(
                ':id_mensualidad' => $this->id_mensualidad,
                ':id_atleta' => $this->id_atleta,
                ':tipo' => $this->tipo,
                ':cobro' => $this->cobro,
                ':pago' => $this->pago,
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

    private function eliminar()
    {
        try {
            $consulta = "DELETE FROM mensualidad WHERE id_mensualidad = :id_mensualidad";
            $valores = array(':id_mensualidad' => $this->id_mensualidad);

            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $resultado["ok"] = true;
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
}
?>

                        
