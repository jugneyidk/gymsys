<?php
require_once ('modelo/datos.php');

class WADA extends datos // Nombre de la clase del modelo
{
    private $conexion, $id_atleta, $estado, $inscrito, $ultima_actualizacion, $vencimiento; // Todas las variables que se usarán que vienen del formulario (no tocar la conexión)
    
    public function __construct()
    {
        $this->conexion = $this->conecta(); // Inicia la conexión a la DB
    }

    public function incluir_wada($id_atleta, $estado, $inscrito, $ultima_actualizacion, $vencimiento) // Función pública que hace set a los atributos y llama a la función privada
    {
        $this->id_atleta = $id_atleta;
        $this->estado = $estado;
        $this->inscrito = $inscrito;
        $this->ultima_actualizacion = $ultima_actualizacion;
        $this->vencimiento = $vencimiento;
        return $this->incluir();
    }

    public function listado_wada() // Función pública que hace set a los atributos y llama a la función privada
    {
        return $this->listado();
    }

    private function incluir() // Función privada para incluir datos en la base de datos
    {
        try {
            $consulta = "INSERT INTO wada (id_atleta, estado, inscrito, ultima_actualizacion, vencimiento) 
                         VALUES (:id_atleta, :estado, :inscrito, :ultima_actualizacion, :vencimiento)"; // Consulta SQL parametrizada
            $valores = array(
                ':id_atleta' => $this->id_atleta, 
                ':estado' => $this->estado, 
                ':inscrito' => $this->inscrito, 
                ':ultima_actualizacion' => $this->ultima_actualizacion, 
                ':vencimiento' => $this->vencimiento
            ); // Arreglo con los parámetros que se enviarán en la consulta
            $respuesta = $this->conexion->prepare($consulta); // Se prepara la consulta
            $respuesta->execute($valores); // Se ejecuta la consulta preparada y se pasan los valores a la función execute
            $resultado["ok"] = true; // Se retorna OK true para saber en el js que la consulta salió bien
        } catch (Exception $e) {
            $resultado["ok"] = false; // Se retorna OK false para saber en el js que la consulta salió mal
            $resultado["mensaje"] = $e->getMessage(); // Se retorna el mensaje de error
        }
        return $resultado; // Se retorna el resultado
    }

    private function listado() // Función privada para listar datos de la base de datos
    {
        try {
            $consulta = "SELECT * FROM wada ORDER BY id DESC"; // Consulta SQL parametrizada
            $respuesta = $this->conexion->prepare($consulta); // Se prepara la consulta
            $respuesta->execute(); // Se ejecuta la consulta preparada
            $respuesta = $respuesta->fetchAll(PDO::FETCH_ASSOC); // Se hace un FETCH ALL para traer todos los registros que coincidan
            $resultado["ok"] = true; // Se retorna OK true para saber en el js que la consulta salió bien
            $resultado["respuesta"] = $respuesta;
        } catch (Exception $e) {
            $resultado["ok"] = false; // Se retorna OK false para saber en el js que la consulta salió mal
            $resultado["mensaje"] = $e->getMessage(); // Se retorna el mensaje de error
        }
        return $resultado; // Se retorna el resultado
    }

    public function __get($propiedad) // Función mágica para get
    {
        return $this->$propiedad;
    }

    public function __set($propiedad, $valor) // Función mágica para set
    {
        $this->$propiedad = $valor;
        return $this;
    }
}
?>
