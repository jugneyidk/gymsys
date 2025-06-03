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