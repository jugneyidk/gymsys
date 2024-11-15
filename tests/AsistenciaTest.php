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
    public function testObtenerAsistenciasExitoso() // Caso 1
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
        // Verificar que la respuesta sea un mensaje de error
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertEquals("La fecha no es valida", $respuesta['mensaje']);
    }
    public function testGuardarAsistenciasExitoso() // Caso 1
    {
        $fecha = date('Y-m-d');
        $asistencias = '[{"id_atleta":"682815811","asistio":0,"comentario":""},{"id_atleta":"664568422","asistio":0,"comentario":""},{"id_atleta":"99389012","asistio":0,"comentario":""},{"id_atleta":"68281582","asistio":0,"comentario":""},{"id_atleta":"68281581","asistio":1,"comentario":""},{"id_atleta":"68281580","asistio":0,"comentario":""},{"id_atleta":"66456842","asistio":0,"comentario":""},{"id_atleta":"42342344","asistio":0,"comentario":"u i io a i u i i i au"},{"id_atleta":"42194292","asistio":1,"comentario":""},{"id_atleta":"24244444","asistio":1,"comentario":""},{"id_atleta":"23124144","asistio":0,"comentario":""},{"id_atleta":"9252463","asistio":0,"comentario":""},{"id_atleta":"7342825","asistio":0,"comentario":""},{"id_atleta":"6828158","asistio":1,"comentario":""},{"id_atleta":"6759472","asistio":0,"comentario":""},{"id_atleta":"3376883","asistio":0,"comentario":""},{"id_atleta":"3331917","asistio":0,"comentario":""},{"id_atleta":"2594894","asistio":0,"comentario":""},{"id_atleta":"1328547","asistio":0,"comentario":""}]';
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
        $this->assertEquals("Las asistencias no son validas", $respuesta['mensaje']);
    }
}