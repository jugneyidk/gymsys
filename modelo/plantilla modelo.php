<?php
require_once ('modelo/datos.php');
class xxxxx extends datos // reemplazar nombre con el nombre que deberia llevar dependiendo del modulo
{
    private $conexion, $id_atleta, $estado, $ultima_actualizacion; // todas las variables que se usaran que vienen del formulario (no tocar la conexion)
    public function __construct()
    {
        $this->conexion = $this->conecta(); // inicia la conexion a la db
    }
    public function incluir_wada($id_atleta,$estado,$ultima_actualizacion){ // funcion publica que hace set a los atributos y llama a la funcion privada
        $this->id_atleta = $id_atleta;
        $this->estado = $estado;
        $this->ultima_actualizacion = $ultima_actualizacion;
        return $this->incluir();
    }
    public function listado_wada(){ // funcion publica que hace set a los atributos y llama a la funcion privada
        return $this->listado();
    }
    private function incluir() // reemplazar el nombre con que estan incluyendo
    {
        try {
            $consulta = "INSERT INTO wada(id_atleta,estado,ultima_actualizacion) VALUES (:id_atleta,:estado,:ultima_actualizacion)"; // la consulta sql parametrizada
            $valores = array(':id_atleta' => $this->id_atleta, ':estado' => $this->estado, ':ultima_actualizacion' => $this->ultima_actualizacion); // arreglo con los parametros que se enviaran en la consulta
            $respuesta = $this->conexion->prepare($consulta); // se prepara LA CONSULTA
            $respuesta->execute($valores); // se ejecuta la consulta PREPARADA y se pasa los VALORES a la funcion execute
            $resultado["ok"] = true; // se retorna OK true para saber en el js que la consulta salio bien
        } catch (Exception $e) {
            $resultado["ok"] = false; // se retorna OK false para saber en el js que la consulta salio mal
            $resultado["mensaje"] = $e; // se retorna el mensaje de error
        }
        return $resultado; // se retorna el resultado
    }
    private function listado() // reemplazar el nombre con que estan incluyendo
    {
        try {
            $consulta = "SELECT * FROM `wada` WHERE id_atleta=:id_atleta"; // la consulta sql parametrizada, se quita la seleccion de fila si es para listar todos
            $respuesta = $this->conexion->prepare($consulta); // se prepara LA CONSULTA
            // $valores = array(':id_atleta' => $formulario["id_atleta"]); // en este caso con valores si se va a seleccionar una sola fila
            $respuesta->execute(); // se ejecuta la consulta PREPARADA y se pasa los VALORES en caso que tenga a la funcion execute
            $respuesta = $respuesta->fetchAll(PDO::FETCH_ASSOC); // se hace un FETCH ALL para traer todos los registros que coincidan, si deberia ser solo 1 se hace solo FETCH
            $resultado["ok"] = true; // se retorna OK true para saber en el js que la consulta salio bien
            $resultado["respuesta"] = $respuesta;
        } catch (Exception $e) {
            $resultado["ok"] = false; // se retorna OK false para saber en el js que la consulta salio mal
            $resultado["mensaje"] = $e; // se retorna el mensaje de error
        }
        return $resultado; // se retorna el resultado
    }

    public function __get($propiedad) // no tocar, funcion magica para get
    {
        return $this->$propiedad;
    }
    public function __set($propiedad, $valor) // no tocar, funcion magica para set
    {
        $this->$propiedad = $valor;
        return $this;
    }
}