<?php
require_once("./comunes/regex.php");
class Validar
{
    private static $exp = REGEX;
    public static function validar($campo, $valor)
    {
        $respuesta = [
            "ok" => true,
            "mensaje" => ""
        ];
        if ($campo === "password" && $valor === null) {
            return $respuesta;
        }
        // $campo = str_replace('_modificar', '', $campo);
        if (isset(self::$exp[$campo])) {
            $regex = self::$exp[$campo]['regex'];
            $mensaje = self::$exp[$campo]['mensaje'];
            if (!preg_match($regex, $valor) || (strpos($campo, 'fecha') === true && !self::validar_fecha($valor))) {
                $respuesta["ok"] = false;
                $respuesta["mensaje"] = $mensaje;
            }
            return $respuesta;
        }
        throw new Exception("No se encontró expresion regular para el campo");
    }
    public static function validar_datos($datos)
    {
        $errores = [];
        foreach ($datos as $campo => $valor) {
            if ($campo === 'accion' || $campo === 'modificar_contraseña') {
                continue; // Salta a la siguiente iteración del bucle
            }
            if (strpos($campo, 'representante') === null) {
                continue; // Salta a la siguiente iteración del bucle
            }
            try {
                $resultado = self::validar($campo, $valor);
                if (!$resultado["ok"]) {
                    $errores[$campo] = $resultado['mensaje'];
                }
            } catch (Exception $e) {
                $errores[$campo] = $e->getMessage();
            }
        }
        return empty($errores) ? true : $errores;
    }

    public static function validar_fecha($fecha)
    {
        $valida = false;
        if (preg_match(self::$exp["fecha_nacimiento"]["regex"], $fecha)) {
            list($year, $month, $day) = explode('-', $fecha);
            if (checkdate($month, $day, $year)) {
                $valida = true;
            }
        }
        return $valida;
    }

    public static function existe($conexion, $id, $consulta)
    {
        try {
            $con = $conexion->prepare($consulta);
            $con->bindParam(1, $id);
            $con->execute();
            $respuesta = $con->fetch(PDO::FETCH_ASSOC);
            if (!$respuesta) {
                $resultado["ok"] = false;
            } else {
                $resultado["ok"] = true;
            }
        } catch (PDOException $e) {
            $resultado["ok"] = false;
            $resultado["mensaje"] = $e->getMessage();
        }
        return $resultado;
    }

    public static function validar_asistencias($asistencias)
    {
        foreach ($asistencias as $asistencia => $valor) {
            if (!preg_match(self::$exp["cedula"]["regex"], $valor["id_atleta"])) {
                return false;
            }
            if (gettype($valor["asistio"]) !== "integer" || $valor["asistio"] < 0 || $valor["asistio"] > 1) {
                return false;
            }
            if (!preg_match(self::$exp["detalles"]["regex"], $valor["comentario"])) {
                return false;
            }
        }
        return true;
    }

    public static function validar_fecha_mayor_que_hoy($fecha)
    {
        $fechaSeleccionada = new DateTime($fecha);
        $fechaActual = new DateTime();
        // Restablecer la hora a 00:00:00 para comparar solo la fecha
        $fechaActual->setTime(0, 0, 0);
        $fechaSeleccionada->setTime(0, 0, 0);
        return $fechaSeleccionada >= $fechaActual;
    }
}
