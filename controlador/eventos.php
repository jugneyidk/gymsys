<?php


  if(isset($_GET["option"]))
  {
    // Ver que hago aqui
  }else{
    $_GET["option"] = "default";
  }

  switch ($_GET["option"]) {
    case 'registrar':
      // code...
      break;
    case 'consultar':
      // code...
      break;
    case 'modificar':
      // code...
      break;
    case 'eliminar':
      // code...
      break;
    
    case 'registrarSubs':
      // code...
      break;
    case 'consultarSubs':
      // code...
      break;
    case 'modificarSubs':
      // code...
      break;
    case 'eliminarSubs':
      // code...
      break;

    case 'registrarTipo':
      // code...
      break;
    case 'consultarTipo':
      // code...
      break;
    case 'modificarTipo':
      // code...
      break;
    case 'eliminarTipo':
      // code...
      break;
      
    case 'registrarCategoria':
      // code...
      break;
    case 'consultarCategoria':
      $respuesta = array(
        "respuesta" => "hola");
      return json_decode($respuesta);
      break;
    case 'modificarCategoria':
      // code...
      break;
    case 'eliminarCategoria':
      // code...
      break;
    

    case 'default':
      if(is_file("modelo/eventos.php")){
        require_once("modelo/eventos.php");
      }
        $objEvento = new Eventos();
        $res = $objEvento->consultaGeneral();
        
        if(is_file("vista/".$p.".php")){
          require_once("vista/".$p.".php"); 
        }
        else{
          require_once("comunes/404.php"); 
        }

      break;
  }

  /* Siguientes Cosas por hacer

  1. realizar la consulta al iniciar el modulo. (Abrir la pagina)
  2. reasignar los datos dentro de las consultas.
  2.1 Si no existe datos, pantalla que no existe registros.
  3, habilitar las opciones dentro del controlador.
  4. validar las entradas. (JS)
  
  999. encontrar errores


*/



?>