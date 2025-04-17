<?php
class PerfilAtleta extends datos
{
    private $conexion;
    private $cedula;

    public function __construct()
    {
        $this->conexion = $this->conecta();
    }

    public function obtener_atleta($cedula)
    {
        $validacion = Validar::validar("cedula", $cedula);
        if (!$validacion["ok"]) {
            return $validacion;
        }
        $this->cedula = $cedula;
        return $this->obtener();
    }

    private function obtener()
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
                    a.tipo_atleta AS id_tipo_atleta, 
                    a.peso, 
                    a.altura, 
                    a.entrenador, 
                    ue.nombre AS nombre_entrenador, 
                    CONCAT(ue.nombre, ' ', ue.apellido) AS nombre_entrenador
                FROM atleta a
                INNER JOIN usuarios u ON a.cedula = u.cedula
                LEFT JOIN usuarios ue ON a.entrenador = ue.cedula
                WHERE u.cedula = :cedula;";
            $valores = array(':cedula' => $this->cedula);
            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $atleta = $respuesta->fetch(PDO::FETCH_ASSOC);
            if ($atleta) {
                $resultado["ok"] = true;
                $resultado["atleta"] = $atleta;
            } else {
                $resultado["ok"] = false;
                $resultado["mensaje"] = "No se encontró el atleta";
            }
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
