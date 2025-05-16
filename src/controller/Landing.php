<?php
namespace Gymsys\Controller;
use Gymsys\Core\BaseController;
class Landing extends BaseController
{
  private $database;
  public function __construct($database)
  {
    $this->database = $database;
  }
}
// if (is_file("vista/" . $p . ".php")) {
//   require_once("modelo/permisos.php");
//   if (isset($_SESSION['rol'])) {
//     // $permisos_o = new Permisos();
//     // $permisos = $permisos_o->chequear_permisos();
//     header("Location: .");
//   }
//   require_once("vista/" . $p . ".php");
// } else {
//   require_once("comunes/404.php");
// }