<?php
namespace Gymsys\Controller;

use Gymsys\Core\BaseController;
use Gymsys\Core\Database;
class Login extends BaseController
{
  protected $database;
  protected $model;
  public function __construct(Database $database)
  {
    $this->database = $database;
    $modelClass = $this->getModel("login");
    $this->model = new $modelClass($database);
  }
  public function auth($requestData)
  {
    $response = $this->model->auth($requestData['id_usuario'], $requestData['password']);
    return $this->sendResponse(200, $response);
  }
}

// if (!is_file("modelo/" . $p . ".php")) {
//   echo "No existe el modelo.";
//   exit;
// }
// if (isset($_SESSION['id_usuario'])) {
//   header("location: .");
// }
// if (!empty($_POST)) {
//   session_destroy();
//   $o = new Login();
//   $accion = $_POST["accion"];
//   if ($accion == "login") {
//     $respuesta = $o->iniciar_sesion($_POST["id_usuario"], $_POST["password"]);
//     echo json_encode($respuesta);
//   }
//   exit;
// }
// if (is_file("vista/" . $p . ".php")) {
//   require_once ("vista/" . $p . ".php");
// } else {
//   require_once ("comunes/404.php");
// }