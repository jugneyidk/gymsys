<?php
require_once("./comunes/regex.php");
class Validar extends datos
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
        if (!is_array($asistencias)) {
            return false;
        }
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
    public static function validar_fechas_wada($conexion, $cedula, $inscripcion, $ultima_actualizacion, $vencimiento)
    {
        $consulta = "SELECT fecha_nacimiento FROM usuarios WHERE cedula = :cedula";
        $con = $conexion->prepare($consulta);
        $con->execute([":cedula" => $cedula]);
        $fecha_nacimiento = $con->fetchColumn();
        if (!$fecha_nacimiento) {
            return ["ok" => false, "mensaje" => "El atleta no existe"];
        }
        foreach ([$inscripcion, $ultima_actualizacion, $fecha_nacimiento, $vencimiento] as $fecha) {
            if (!self::validar_fecha($fecha)) {
                return ["ok" => false, "mensaje" => "Las fechas no son válidas"];
            }
        }
        // Convertir las fechas a objetos DateTime para hacer las comparaciones
        $fecha_nacimiento_obj = new DateTime($fecha_nacimiento);
        $fecha_inscripcion_obj = new DateTime($inscripcion);
        $ultima_actualizacion_obj = new DateTime($ultima_actualizacion);
        $fecha_vencimiento_obj = new DateTime($vencimiento);
        // Validar que la inscripción se pueda realizar a partir de los 15 años
        $edad_minima = 15;
        $fecha_nacimiento_obj->modify("+$edad_minima years");
        if ($fecha_inscripcion_obj < $fecha_nacimiento_obj) {
            $mensaje_error = 'La inscripción no es válida: el atleta debe tener al menos 15 años';
            return ["ok" => false, "mensaje" => $mensaje_error];
        }
        // Calcular la fecha de vencimiento (un trimestre después de la última actualización)
        $fecha_vencimiento_esperada = clone $ultima_actualizacion_obj;
        $fecha_vencimiento_esperada->modify('+3 months');
        // Validar si la fecha de vencimiento es correcta
        if ($fecha_inscripcion_obj > $ultima_actualizacion_obj) {
            $mensaje_error = 'La inscripción no es válida: la fecha de inscripción no puede ser posterior a la ultima actualización';
            return ["ok" => false, "mensaje" => $mensaje_error];
        }
        if ($fecha_vencimiento_esperada > $fecha_vencimiento_obj) {
            $mensaje_error = 'El vencimiento no es válido: la fecha de vencimiento debe ser al menos un trimestre después de la última actualización';
            return ["ok" => false, "mensaje" => $mensaje_error];
        }
        if ($fecha_inscripcion_obj > $fecha_vencimiento_esperada) {
            $mensaje_error = 'La inscripción no es válida: la fecha de inscripción no puede ser posterior al vencimiento (trimestre después de la última actualización)';
            return ["ok" => false, "mensaje" => $mensaje_error];
        }
        return ["ok" => true];
    }
}
