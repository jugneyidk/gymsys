<?php
class datos
{
    // DATOS DE LA DB
    private $ip = "localhost";
    private $bd = "gymsys";
    private $usuario = "root";
    private $contrasena = "";
    // FUNCION PARA ESTABLECER CONEXION
    public function conecta()
    {
        try {
            $pdo = new PDO("mysql:host=" . $this->ip . ";dbname=" . $this->bd . "", $this->usuario, $this->contrasena);
            $pdo->exec("set names utf8");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function validar_conexion($pdo)
    {
        if (!($pdo instanceof PDO)) {
            throw new Exception($pdo, 1);
        }

    }
}
?>