<?php
require_once('modelo/datos.php');

class Entrenador extends datos
{
    private $conexion;
    private $id_entrenador, $nombres, $apellidos, $cedula, $genero, $fecha_nacimiento, $lugar_nacimiento, $estado_civil, $telefono, $correo_electronico, $grado_instruccion;

    public function __construct()
    {
        $this->conexion = $this->conecta(); // conex
    }

    public function incluir_entrenador($nombres, $apellidos, $cedula, $genero, $fecha_nacimiento, $lugar_nacimiento, $estado_civil, $telefono, $correo_electronico, $grado_instruccion)
    {
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->cedula = $cedula;
        $this->genero = $genero;
        $this->fecha_nacimiento = $fecha_nacimiento;
        $this->lugar_nacimiento = $lugar_nacimiento;
        $this->estado_civil = $estado_civil;
        $this->telefono = $telefono;
        $this->correo_electronico = $correo_electronico;
        $this->grado_instruccion = $grado_instruccion;
        return $this->incluir();
    }

    public function modificar_entrenador($nombres, $apellidos, $cedula, $genero, $fecha_nacimiento, $lugar_nacimiento, $estado_civil, $telefono, $correo_electronico, $grado_instruccion)
    {
        $this->nombres = $nombres;
        $this->apellidos = $apellidos;
        $this->cedula = $cedula;
        $this->genero = $genero;
        $this->fecha_nacimiento = $fecha_nacimiento;
        $this->lugar_nacimiento = $lugar_nacimiento;
        $this->estado_civil = $estado_civil;
        $this->telefono = $telefono;
        $this->correo_electronico = $correo_electronico;
        $this->grado_instruccion = $grado_instruccion;
        return $this->modificar();
    }

    public function obtener_entrenador($cedula)
    {
        try {
            $consulta = "SELECT * FROM entrenador WHERE cedula = :cedula";
            $valores = array(':cedula' => $cedula);

            $respuesta = $this->conexion->prepare($consulta);
            $respuesta->execute($valores);
            $entrenador = $respuesta->fetch(PDO::FETCH_ASSOC);
            
            if ($entrenador) {
                $resultado["ok"] = true;
                $resultado["entrenador"] = $entrenador;
            } else {
                $resultado["ok"] = false;
                $resultado["mensaje"] = "No se encontró el entrenador";
            }
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    public function listado_entrenador()
    {
        try {
            $consulta = "SELECT * FROM entrenador ORDER BY cedula DESC";
            $con = $this->conexion->prepare($consulta);
            $con->execute();
            $respuesta = $con->fetchAll(PDO::FETCH_ASSOC);
            $resultado["ok"] = true;
            $resultado["devol"] = 'listado_entrenadores';
            $resultado["respuesta"] = $respuesta;
        } catch (Exception $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    private function incluir()
    {
        try {
            $consulta = "INSERT INTO entrenador (cedula, nombres, apellidos, genero, fecha_nacimiento, lugar_nacimiento, estado_civil, telefono, correo_electronico, grado_instruccion) 
                         VALUES (:cedula, :nombres, :apellidos, :genero, :fecha_nacimiento, :lugar_nacimiento, :estado_civil, :telefono, :correo_electronico, :grado_instruccion)";

            $valores = array(
                ':cedula' => $this->cedula,
                ':nombres' => $this->nombres,
                ':apellidos' => $this->apellidos,
                ':genero' => $this->genero,
                ':fecha_nacimiento' => $this->fecha_nacimiento,
                ':lugar_nacimiento' => $this->lugar_nacimiento,
                ':estado_civil' => $this->estado_civil,
                ':telefono' => $this->telefono,
                ':correo_electronico' => $this->correo_electronico,
                ':grado_instruccion' => $this->grado_instruccion
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

    private function modificar()
    {
        try {
            $consulta = "UPDATE entrenador SET nombres = :nombres, apellidos = :apellidos, genero = :genero, fecha_nacimiento = :fecha_nacimiento, lugar_nacimiento = :lugar_nacimiento, estado_civil = :estado_civil, telefono = :telefono, correo_electronico = :correo_electronico, grado_instruccion = :grado_instruccion 
                         WHERE cedula = :cedula";

            $valores = array(
                ':cedula' => $this->cedula,
                ':nombres' => $this->nombres,
                ':apellidos' => $this->apellidos,
                ':genero' => $this->genero,
                ':fecha_nacimiento' => $this->fecha_nacimiento,
                ':lugar_nacimiento' => $this->lugar_nacimiento,
                ':estado_civil' => $this->estado_civil,
                ':telefono' => $this->telefono,
                ':correo_electronico' => $this->correo_electronico,
                ':grado_instruccion' => $this->grado_instruccion
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
