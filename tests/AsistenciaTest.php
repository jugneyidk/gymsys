<?php
use PHPUnit\Framework\TestCase;

class AsistenciaTest extends TestCase
{
    private $asistencia;

    protected function setUp(): void
    {
        $this->asistencia = new Asistencia();
    }

    public function testObtenerAtletas() // Caso 1
    {
        $respuesta = $this->asistencia->obtener_atletas();
        // Verificar que la respuesta sea exitosa y devuelva un array con la lista de atletas para las asistencias
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
        $this->assertIsArray($respuesta['atletas']);
    }
    public function testObtenerAsistencias() // Caso 1
    {
        $fecha = date('Y-m-d');
        $respuesta = $this->asistencia->obtener_asistencias($fecha);
        // Verificar que la respuesta sea exitosa y devuelva un array con la lista de asistencias del dia
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
        $this->assertIsArray($respuesta['asistencias']);
    }
    public function testObtenerAsistenciasNoValido() // Caso 1
    {
        $fecha = "2024-32-144";
        $respuesta = $this->asistencia->obtener_asistencias($fecha);
        // Verificar que la respuesta sea exitosa y devuelva un array con la lista de asistencias del dia
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertEquals("La fecha no es valida", $respuesta['mensaje']);
    }
    public function testGuardarAsistencias() // Caso 1
    {
        $fecha = date('Y-m-d');
        $asistencias = '[{"id_atleta":42194292,"asistio":1,"comentario":""},{"id_atleta":664568422,"asistio":1,"comentario":""},{"id_atleta":66456842,"asistio":0,"comentario":""},{"id_atleta":682815811,"asistio":1,"comentario":""},{"id_atleta":68281582,"asistio":0,"comentario":""},{"id_atleta":68281581,"asistio":0,"comentario":""},{"id_atleta":68281580,"asistio":0,"comentario":""},{"id_atleta":42342344,"asistio":0,"comentario":""},{"id_atleta":24244444,"asistio":0,"comentario":""},{"id_atleta":23124144,"asistio":0,"comentario":""}]';
        $respuesta = $this->asistencia->guardar_asistencias($fecha, $asistencias);
        // Verificar que la respuesta sea exitosa y guarde la lista de asistencias
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
    }
    public function testGuardarAsistenciasNoValido() // Caso 1
    {
        $fecha = date('Y-m-d');
        $asistencias = 'asistencias';
        $respuesta = $this->asistencia->guardar_asistencias($fecha, $asistencias);
        // Verificar que la respuesta sea un error
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertEquals("No hay asistencias", $respuesta['mensaje']);
    }
}