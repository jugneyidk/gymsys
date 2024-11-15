<?php
use PHPUnit\Framework\TestCase;

class WADATest extends TestCase
{
    private $wada;

    protected function setUp(): void
    {
        $this->wada = new WADA();
    }
    public function testIncluirWada() // Caso 1
    {
        $id_atleta = "42342344";
        $estado = "0";
        $inscrito = "2024-07-12";
        $ultima_actualizacion = "2024-07-12";
        $vencimiento = "2024-10-12";
        $respuesta = $this->wada->incluir_wada($id_atleta, $estado, $inscrito, $ultima_actualizacion, $vencimiento);
        // Verificar que se registre exitosamente
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta["ok"]);
    }
    public function testIncluirWadaMenorDe15() // Caso 1
    {
        $id_atleta = "682815811";
        $estado = "0";
        $inscrito = "2024-07-12";
        $ultima_actualizacion = "2024-07-12";
        $vencimiento = "2024-11-12";
        $respuesta = $this->wada->incluir_wada($id_atleta, $estado, $inscrito, $ultima_actualizacion, $vencimiento);
        // Devolver que el atleta debe ser mayor de 15 años para aplicar por la WADA
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta["ok"]);
    }

    public function testIncluirWadaYaExiste() // Caso 1
    {
        $id_atleta = "42342344";
        $estado = "0";
        $inscrito = "2024-07-12";
        $ultima_actualizacion = "2024-07-12";
        $vencimiento = "2024-10-12";
        $respuesta = $this->wada->incluir_wada($id_atleta, $estado, $inscrito, $ultima_actualizacion, $vencimiento);
        // Verificar que la wada no se registre porque ya existe
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta["ok"]);
    }
    public function testIncluirWadaNoValido() // Caso 1
    {
        $id_atleta = "";
        $estado = "10";
        $inscrito = "2024-07-12";
        $ultima_actualizacion = "2024-07-12";
        $vencimiento = "2024-07-12";
        $respuesta = $this->wada->incluir_wada($id_atleta, $estado, $inscrito, $ultima_actualizacion, $vencimiento);
        // Verificar que la wada no se registre porque ya existe
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta["ok"]);
    }
    public function testObtenerWada() // Caso 1
    {
        $id_atleta = "42342344";
        $respuesta = $this->wada->obtener_wada($id_atleta);
        // Verificar que la wada existe y devuelva los detalles
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta["ok"]);
        $this->assertIsArray($respuesta["wada"]);
    }
    public function testObtenerWadaNoValido() // Caso 1
    {
        $id_atleta = "45545";
        $respuesta = $this->wada->obtener_wada($id_atleta);
        // Devolver que la wada no es valida
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta["ok"]);
    }
    public function testModificarWada() // Caso 1
    {
        $id_atleta = "42342344";
        $estado = "1";
        $inscrito = "2024-07-12";
        $ultima_actualizacion = "2024-08-12";
        $vencimiento = "2024-11-12";
        $respuesta = $this->wada->modificar_wada($id_atleta, $estado, $inscrito, $ultima_actualizacion, $vencimiento);
        // Verificar que se modifique exitosamente
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta["ok"]);
    }
    public function testModificarWadaNoExiste() // Caso 1
    {
        $id_atleta = "34234334";
        $estado = "0";
        $inscrito = "2024-07-12";
        $ultima_actualizacion = "2024-07-12";
        $vencimiento = "2024-11-12";
        $respuesta = $this->wada->modificar_wada($id_atleta, $estado, $inscrito, $ultima_actualizacion, $vencimiento);
        // Devolver que la wada no existe
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta["ok"]);
    }
    public function testModificarWadaNoValido() // Caso 1
    {
        $id_atleta = "342343324";
        $estado = "";
        $inscrito = "";
        $ultima_actualizacion = "2024-10-12";
        $vencimiento = "2024-11-12";
        $respuesta = $this->wada->modificar_wada($id_atleta, $estado, $inscrito, $ultima_actualizacion, $vencimiento);
        // Devolver que los datos no son validos
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta["ok"]);
    }
    public function testEliminarWada() // Caso 1
    {
        $id_atleta = "42342344";
        $respuesta = $this->wada->eliminar_wada($id_atleta);
        // Eliminar satisfactoriamente el registro
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta["ok"]);
    }
    public function testEliminarWadaNoExiste() // Caso 1
    {
        $id_atleta = "42342344";
        $respuesta = $this->wada->eliminar_wada($id_atleta);
        // Devolver que la wada no existe
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta["ok"]);
    }
    public function testEliminarWadaNoValido() // Caso 1
    {
        $id_atleta = "42342dd344";
        $respuesta = $this->wada->eliminar_wada($id_atleta);
        // Devolver que la cedula no es valida
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta["ok"]);
    }
    public function testListadoWada() // Caso 1
    {
        $respuesta = $this->wada->listado_wada();
        // Devolver el listado de wadas registradas
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta["ok"]);
        $this->assertIsArray($respuesta["respuesta"]);
    }
    public function testObtenerWadaPorVencer() // Caso 1
    {
        $respuesta = $this->wada->obtener_proximos_vencer();
        // Devolver el listado de wadas registradas con 30 dias o menos para vencer
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta["ok"]);
        $this->assertIsArray($respuesta["respuesta"]);
    }
}