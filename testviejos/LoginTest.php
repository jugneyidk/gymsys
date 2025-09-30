<?php
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    private $login;

    protected function setUp(): void
    {
        $this->login = new Login();
    }

    public function testLoginExitoso() // Caso 1
    {
        $user = "22222222";
        $password = "Diego123*";
        $respuesta = $this->login->iniciar_sesion($user, $password);
        // Verificar que la respuesta sea exitosa e inicie la sesion
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta["ok"]);
    }
    public function testLoginIncorrecto() // Caso 1
    {
        $user = "22222222";
        $password = "Diego123*s";
        $respuesta = $this->login->iniciar_sesion($user, $password);
        // Verificar que la respuesta sea exitosa e inicie la sesion
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta["ok"]);
        $this->assertEquals("Los datos ingresados son incorrectos", $respuesta["mensaje"]);
    }
    public function testLoginNoValido() // Caso 1
    {
        $user = "22223";
        $password = "-die go12..3";
        $respuesta = $this->login->iniciar_sesion($user, $password);
        // Verificar que la respuesta sea exitosa e inicie la sesion
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta["ok"]);
        $this->assertEquals("La cédula debe tener al menos 7 números", $respuesta["mensaje"]);
    }

}