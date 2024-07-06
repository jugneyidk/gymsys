<?php
require_once ('modelo/datos.php');

class Atleta extends datos
{
    private $conexion;
    private $id_atleta, $nombres, $apellidos, $cedula, $genero, $fecha_nacimiento, $lugar_nacimiento, $peso, $altura, $tipo_atleta, $estado_civil, $telefono, $correo, $entrenador_asignado, $nombre_representante, $telefono_representante;

    public function __construct()
    {
        $this->conexion = $this->conecta(); // inicia la conexion a la db
    }

    public function incluir_atleta($nombres, $apellidos, $cedula, $genero, $fecha_nacimiento, $lugar_nacimiento, $peso, $altura, $tipo_atleta, $estado_civil, $telefono, $correo, $entrenador_asignado)
    {
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->cedula = $cedula;
        $this->genero = $genero;
        $this->fecha_nacimiento = $fecha_nacimiento;
        $this->lugar_nacimiento = $lugar_nacimiento;
        $this->peso = $peso;
        $this->altura = $altura;
        $this->tipo_atleta = $tipo_atleta;
        $this->estado_civil = $estado_civil;
        $this->telefono = $telefono;
        $this->correo = $correo;
        $this->entrenador_asignado = $entrenador_asignado;
        return $this->incluir();
    }

    public function modificar_atleta($nombres, $apellidos, $cedula, $genero, $fecha_nacimiento, $lugar_nacimiento, $peso, $altura, $tipo_atleta, $estado_civil, $telefono, $correo, $entrenador_asignado, $nombre_representante, $telefono_representante)
    {
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->cedula = $cedula;
        $this->genero = $genero;
        $this->fecha_nacimiento = $fecha_nacimiento;
        $this->lugar_nacimiento = $lugar_nacimiento;
        $this->peso = $peso;
        $this->altura = $altura;
        $this->tipo_atleta = $tipo_atleta;
        $this->estado_civil = $estado_civil;
        $this->telefono = $telefono;
        $this->correo = $correo;
        $this->entrenador_asignado = $entrenador_asignado;
        $this->nombre_representante = $nombre_representante;
        $this->telefono_representante = $telefono_representante;
        return $this->modificar();
    }

    public function listado_atleta()
    {
        return $this->listado();
    }

    private function incluir()
    {
        try {
            $consulta = "INSERT INTO atleta (cedula, id_entrenador, nombre, apellido, tipo_atleta, genero, fecha_nacimiento, lugar_nacimiento, estado_civil, peso, altura, telefono, correo_electronico) 
                         VALUES (:cedula, :id_entrenador, :nombre, :apellido, :tipo_atleta, :genero, :fecha_nacimiento, :lugar_nacimiento, :estado_civil, :peso, :altura, :telefono, :correo)";

            $valores = array(
                ':cedula' => $this->cedula,
                ':id_entrenador' => $this->entrenador_asignado,
                ':nombre' => $this->nombres,
                ':apellido' => $this->apellidos,
                ':tipo_atleta' => $this->tipo_atleta,
                ':genero' => $this->genero,
                ':fecha_nacimiento' => $this->fecha_nacimiento,
                ':lugar_nacimiento' => $this->lugar_nacimiento,
                ':estado_civil' => $this->estado_civil,
                ':peso' => $this->peso,
                ':altura' => $this->altura,
                ':telefono' => $this->telefono,
                ':correo' => $this->correo
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
    
    public function obtener_atleta($cedula) {
        try {
            $consulta = "SELECT * FROM atleta WHERE cedula = :cedula";
            $valores = array(':cedula' => $cedula);
    
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
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }
    
    private function modificar()
    {
        try {
            $consulta = "UPDATE atleta SET id_entrenador = :id_entrenador, nombre = :nombre, apellido = :apellido, tipo_atleta = :tipo_atleta, genero = :genero, fecha_nacimiento = :fecha_nacimiento, 
                         lugar_nacimiento = :lugar_nacimiento, estado_civil = :estado_civil, peso = :peso, altura = :altura, telefono = :telefono, correo_electronico = :correo 
                         WHERE cedula = :cedula";

            $valores = array(
                ':cedula' => $this->cedula,
                ':id_entrenador' => $this->entrenador_asignado,
                ':nombre' => $this->nombres,
                ':apellido' => $this->apellidos,
                ':tipo_atleta' => $this->tipo_atleta,
                ':genero' => $this->genero,
                ':fecha_nacimiento' => $this->fecha_nacimiento,
                ':lugar_nacimiento' => $this->lugar_nacimiento,
                ':estado_civil' => $this->estado_civil,
                ':peso' => $this->peso,
                ':altura' => $this->altura,
                ':telefono' => $this->telefono,
                ':correo' => $this->correo
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
            $consulta = "SELECT * FROM `atleta` ORDER BY cedula DESC";
            $con = $this->conexion->prepare($consulta);
            $con->execute();
            $respuesta = $con->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["devol"] = 'listado_atletas';
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
}
?>
