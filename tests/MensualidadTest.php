<?php

	use PHPUnit\Framework\TestCase;

	class MensualidadTest extends TestCase{
		private $op;

		public function setUp():void{
			$this->op = new Mensualidad();
		}

		public function testIncluirMensualidad():void{

		$resp = $this->op->incluir_mensualidad("23134144","20","2023-10-10");

		$this->assertNotNull($resp);
		$this->assertIsArray($resp);

		}
	}

?>