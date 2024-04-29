<?php 
$p = "landing"; 
session_start();
 if (!empty($_GET['p'])){
   $p = $_GET['p'];
  // if (!isset($_SESSION['id_usuario']) && ($p != "principal" && $p != "login" && $p != "consulta" && $p != "detallesdeuda")) {
   //  header("Location: ?p=login");
   //}
 }
 if(is_file("controlador/".$p.".php")){ 
    require_once("controlador/".$p.".php");
 }
 else{
   require_once("vista/404.php"); 
 }
?> 