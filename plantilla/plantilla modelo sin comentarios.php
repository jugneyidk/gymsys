<?php
require_once ('modelo/datos.php');
class Atleta extends datos 
{
    function incluir_atleta($id_atleta,$estado,$ultima_actualizacion) 
    {
        try {
            $db = new datos(); 
            $conexion = $db->conecta(); 
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $consulta = "INSERT FROM wada(id_atleta,estado,ultima_actualizacion) VALUES (:id_atleta,:estado,:ultima_actualizacion)"; 
            $valores = array(':id_atleta' => $id_atleta, ':estado' => $estado, ':ultima_actualizacion' => $ultima_actualizacion); 
            $respuesta = $conexion->prepare($consulta); 
            $respuesta->execute($valores);
            $resultado["ok"] = true;
        } catch (Exception $e) {
            $resultado["ok"] = false; 
            $resultado["mensaje"] = $e; 
        }
        return $resultado; 
    }
    function listado_XXXXX($formulario) 
    {
        try {
            $db = new datos(); 
            $conexion = $db->conecta();
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            $consulta = "SELECT * FROM `wada` WHERE id_atleta=:id_atleta"; 
            $respuesta = $conexion->prepare($consulta);
            $valores = array(':id_atleta' => $formulario["id_atleta"]); 
            $respuesta->execute($valores); 
            $respuesta = $respuesta->fetchAll(PDO::FETCH_ASSOC); 
            $resultado["ok"] = true;
            $resultado["respuesta"] = $respuesta;
        } catch (Exception $e) {
            $resultado["ok"] = false; 
            $resultado["mensaje"] = $e; 
        }
        return $resultado;
    }
}