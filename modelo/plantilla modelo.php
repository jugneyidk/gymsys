<?php
require_once ('modelo/datos.php');
class xxxxx extends datos // reemplazar nombre con el nombre que deberia llevar dependiendo del modulo
{
    function incluir_XXXXX($id_atleta,$estado,$ultima_actualizacion) // reemplazar el nombre con que estan incluyendo
    {
        try {
            $db = new datos(); // se instancia la clase de la base de datos
            $conexion = $db->conecta(); // se inicia la conexion de la db
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // se establecen los atributos para la conexion
            $consulta = "INSERT FROM wada(id_atleta,estado,ultima_actualizacion) VALUES (:id_atleta,:estado,:ultima_actualizacion)"; // la consulta sql parametrizada
            $valores = array(':id_atleta' => $id_atleta, ':estado' => $estado, ':ultima_actualizacion' => $ultima_actualizacion); // arreglo con los parametros que se enviaran en la consulta
            $respuesta = $conexion->prepare($consulta); // se prepara LA CONSULTA
            $respuesta->execute($valores); // se ejecuta la consulta PREPARADA y se pasa los VALORES a la funcion execute
            $resultado["ok"] = true; // se retorna OK true para saber en el js que la consulta salio bien
        } catch (Exception $e) {
            $resultado["ok"] = false; // se retorna OK false para saber en el js que la consulta salio mal
            $resultado["mensaje"] = $e; // se retorna el mensaje de error
        }
        return $resultado; // se retorna el resultado
    }
    function listado_XXXXX($formulario) // reemplazar el nombre con que estan incluyendo
    {
        try {
            $db = new datos(); // se instancia la clase de la base de datos
            $conexion = $db->conecta(); // se inicia la conexion de la db
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // se establecen los atributos para la conexion
            $consulta = "SELECT * FROM `wada` WHERE id_atleta=:id_atleta"; // la consulta sql parametrizada, se quita la seleccion de fila si es para listar todos
            $respuesta = $conexion->prepare($consulta); // se prepara LA CONSULTA
            $valores = array(':id_atleta' => $formulario["id_atleta"]); // en este caso con valores si se va a seleccionar una sola fila
            $respuesta->execute($valores); // se ejecuta la consulta PREPARADA y se pasa los VALORES en caso que tenga a la funcion execute
            $respuesta = $respuesta->fetchAll(PDO::FETCH_ASSOC); // se hace un FETCH ALL para traer todos los registros que coincidan, si deberia ser solo 1 se hace solo FETCH
            $resultado["ok"] = true; // se retorna OK true para saber en el js que la consulta salio bien
            $resultado["respuesta"] = $respuesta;
        } catch (Exception $e) {
            $resultado["ok"] = false; // se retorna OK false para saber en el js que la consulta salio mal
            $resultado["mensaje"] = $e; // se retorna el mensaje de error
        }
        return $resultado; // se retorna el resultado
    }
}