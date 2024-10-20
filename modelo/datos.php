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
            if (isset($_SESSION["id_usuario"])) {
                $usuario_actual = $_SESSION["id_usuario"];
                $this->pdo->exec("SET @usuario_actual = '$usuario_actual';");
            }
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (PDOException $e) {
            return 'Error de conexión: ' . $e->getMessage();
        }
    }

    public function desconecta()
    {
        if ($this->pdo !== null) {
            $this->pdo = null;
        }
    }
}
?>