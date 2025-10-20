<?php

namespace Tests\Feature;

use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use Gymsys\Model\TipoCompetencia;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class TipoCompetenciaTest extends TestCase
{
    private TipoCompetencia $tipoCompetencia;
    private Database|MockObject $db;

    private function enc(string $v): string
    {
        return Cipher::aesEncrypt($v);
    }

    protected function setUp(): void
    {
        $this->db = $this->createMock(Database::class);
        $this->tipoCompetencia = new TipoCompetencia($this->db);
    }


    public function test_incluir_tipo_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls([], true);
        $r = $this->tipoCompetencia->incluirTipo([
            'nombre' => 'Panamericano'
        ]);
        $this->assertIsArray($r);
        $this->assertArrayHasKey('mensaje', $r);
        $this->assertEquals('El tipo de competencia se registró exitosamente', $r['mensaje']);
    }

    public function test_incluir_tipo_duplicado(): void
    {
        $this->db->method('query')->willReturn(['id_tipo_competencia' => 13]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Ya existe este tipo de competencia","code":400}');
        $this->tipoCompetencia->incluirTipo([
            'nombre' => 'Panamericano'
        ]);
    }
    public function test_incluir_tipo_invalido(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Los siguientes campos faltan: [\"nombre\"]","code":400}');
        $this->tipoCompetencia->incluirTipo([
            'nombre' => ''
        ]);
    }


    public function test_modificar_tipo_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_tipo_competencia' => 13], [], true);
        $r = $this->tipoCompetencia->modificarTipo([
            'id_tipo' => $this->enc('13'),
            'nombre' => 'Nacional'
        ]);
        $this->assertArrayHasKey('mensaje', $r);
        $this->assertEquals('El tipo de competencia se modificó exitosamente', $r['mensaje']);
    }

    public function test_modificar_tipo_invalido(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('{"error":"El nombre del tipo de evento debe ser letras y\/o n\u00fameros (entre 3 y 50 caracteres)","code":400}');
        $this->tipoCompetencia->modificarTipo([
            'id_tipo' => $this->enc('13'),
            'nombre' => '$$$$'
        ]);
    }

    public function test_modificar_tipo_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El tipo de competencia ingresado no existe","code":404}');
        $this->tipoCompetencia->modificarTipo([
            'id_tipo' => $this->enc('123'),
            'nombre' => 'Nacional'
        ]);
    }

    public function test_modificar_tipo_duplicado(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_tipo_competencia' => 13], ['id_tipo_competencia' => 99]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Ya existe un tipo de competencia con este nombre","code":400}');
        $this->tipoCompetencia->modificarTipo([
            'id_tipo' => $this->enc('13'),
            'nombre' => 'Senior'
        ]);
    }


    public function test_eliminar_tipo_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_tipo_competencia' => 13], true);
        $r = $this->tipoCompetencia->eliminarTipo([
            'id_tipo' => $this->enc('13')
        ]);
        $this->assertIsArray($r);
        $this->assertArrayHasKey('mensaje', $r);
        $this->assertEquals('El tipo de competencia se eliminó exitosamente', $r['mensaje']);
    }

    public function test_eliminar_tipo_invalido(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Este tipo de competencia no existe","code":404}');
        $this->tipoCompetencia->eliminarTipo([
            'id_tipo' => $this->enc('1/3')
        ]);
    }

    public function test_eliminar_tipo_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Este tipo de competencia no existe","code":404}');
        $this->tipoCompetencia->eliminarTipo([
            'id_tipo' => $this->enc('13')
        ]);
    }
    public function test_listado_tipos(): void
    {
        $this->db->method('query')->willReturn([['id_tipo_competencia' => 13, 'nombre' => 'Senior']]);
        $r = $this->tipoCompetencia->listadoTipos();
        $this->assertIsArray($r);
        $this->assertArrayHasKey('tipos', $r);
        $this->assertIsArray($r['tipos']);
        $this->assertCount(1, $r['tipos']);
    }
}
