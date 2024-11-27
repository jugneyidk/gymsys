<?php

use PHPUnit\Framework\TestCase;

class MensualidadTest extends TestCase
{
	private $op;
	public function setUp(): void
	{
		$this->op = new Mensualidad();
	}

	public function testIncluirMensualidadExitoso(): void
	{
		$respuesta = $this->op->incluir_mensualidad("23124144", "20", "2024-10-10", "");
		// El registro se agrega correctamente
		$this->assertNotNull($respuesta);
		$this->assertIsArray($respuesta);
		$this->assertTrue($respuesta["ok"]);
	}
	public function testIncluirMensualidadNoValido(): void
	{
		$respuesta = $this->op->incluir_mensualidad("23124144", "2sd0", "2024-10-10", "");
		// Devuelve un error de que no es valido
		$this->assertNotNull($respuesta);
		$this->assertIsArray($respuesta);
		$this->assertFalse($respuesta["ok"]);
		$this->assertEquals("El monto no es un numero valido", $respuesta['mensaje']);
	}
	public function testEliminarMensualidadExitoso(): void
	{
		$respuesta = $this->op->eliminar_mensualidad("171");
		// La respuesta es exitosa
		$this->assertNotNull($respuesta);
		$this->assertIsArray($respuesta);
		$this->assertTrue($respuesta["ok"]);
	}
	public function testEliminarMensualidadNoValido(): void
	{
		$respuesta = $this->op->eliminar_mensualidad("mensualidad");
		// Devuelve un error de que no es valido
		$this->assertNotNull($respuesta);
		$this->assertIsArray($respuesta);
		$this->assertFalse($respuesta["ok"]);
		$this->assertEquals("La ID de mensualidad no es válida", $respuesta['mensaje']);
	}
	public function testEliminarMensualidadNoExiste(): void
	{
		$respuesta = $this->op->eliminar_mensualidad("44");
		// Devuelve un error de que no existe
		$this->assertNotNull($respuesta);
		$this->assertIsArray($respuesta);
		$this->assertFalse($respuesta["ok"]);
		$this->assertEquals("No existe esta mensualidad", $respuesta['mensaje']);
	}
	public function testListadoMensualidades(): void
	{
		$respuesta = $this->op->listado_mensualidades();
		// Devuelve un arreglo de mensualidades
		$this->assertNotNull($respuesta);
		$this->assertIsArray($respuesta);
		$this->assertTrue($respuesta["ok"]);
		$this->assertIsArray($respuesta['respuesta']);
	}
	public function testListadoDeudores(): void
	{
		$respuesta = $this->op->listado_deudores();
		// Devuelve un arreglo de deudores
		$this->assertNotNull($respuesta);
		$this->assertIsArray($respuesta);
		$this->assertTrue($respuesta["ok"]);
		$this->assertIsArray($respuesta['respuesta']);
	}
	public function testListadoAtletas(): void
	{
		$respuesta = $this->op->listado_atletas();
		// Devuelve un arreglo de atletas
		$this->assertNotNull($respuesta);
		$this->assertIsArray($respuesta);
		$this->assertTrue($respuesta["ok"]);
		$this->assertIsArray($respuesta['respuesta']);
	}
}
