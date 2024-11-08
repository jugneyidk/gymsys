<?php
use PHPUnit\Framework\TestCase;

class BitacoraTest extends TestCase
{
    private $bitacora;

    protected function setUp(): void
    {
        $this->bitacora = new Bitacora();
    }

    public function testListadoBitacora() // Caso 1
    {
        $respuesta = $this->bitacora->listado_bitacora();
        // Verificar que la respuesta sea exitosa y devuelva un array con la lista de bitacora
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
        $this->assertIsArray($respuesta['respuesta']);
    }
    public function testConsultarAccion() // Caso 1
    {
        $respuesta = $this->bitacora->consultar_accion("23");
        // Verificar que la respuesta sea exitosa y devuelva los detalles de una accion
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
        $this->assertIsArray($respuesta['respuesta']);
    }
    public function testConsultarAccionNoExistente() // Caso 1
    {
        $respuesta = $this->bitacora->consultar_accion("5");
        // Verificar que la respuesta sea que la accion no existe
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertEquals("No se encontró la acción", $respuesta['mensaje']);
    }
}