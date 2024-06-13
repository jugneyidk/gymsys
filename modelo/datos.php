<?php 
class datos{
    // DATOS DE LA DB
	private $ip = "localhost";
    private $bd = "fvlp";
    private $usuario = "root";
    private $contrasena = "";
    // FUNCION PARA ESTABLECER CONEXION
    function conecta(){
        $pdo = new PDO("mysql:host=".$this->ip.";dbname=".$this->bd."",$this->usuario,$this->contrasena);
         $pdo->exec("set names utf8");
         return $pdo;
         }        
}      
?>