<?php
class datos
{
    private $pdo = null;
    private $ip = "localhost";
    private $bd = "gymsys";
    private $usuario = "root";
    private $contrasena = "";
    public function conecta()
    {
        try {
            $this->pdo = new PDO("mysql:host=" . $this->ip . ";dbname=" . $this->bd . "", $this->usuario, $this->contrasena);
            $this->pdo->exec("SET names utf8;");
            // $_SESSION["id_usuario"] = $_SESSION["id_usuario"] ?? "22222222";
            if (isset($_SESSION["id_usuario"])) {
                $usuario_actual = $_SESSION["id_usuario"];
                $this->pdo->exec("SET @usuario_actual = '$usuario_actual';");
            }
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (PDOException $e) {
            return null;
        }
    }

    protected function verificarConexion()
    {
        if ($this->pdo === null) {
            throw new Exception("Error: No se pudo establecer la conexión a la base de datos.");
        }
    }
    public function desconecta()
    {
        if ($this->pdo !== null) {
            $this->pdo = null;
        }
    }
}
