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

    public function test_listado_tipo_exitoso(): void
    {
        $this->db->method('query')->willReturn([['id_tipo_competencia' => 13, 'nombre' => 'Senior']]);
        $r = $this->tipoCompetencia->listadoTipos();
        $this->assertArrayHasKey('tipos', $r);
    }

    public function test_incluir_tipo_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls([], true);
        $r = $this->tipoCompetencia->incluirTipo(['nombre' => 'Sub23']);
        $this->assertArrayHasKey('mensaje', $r);
        $this->assertEquals('El tipo de competencia se registró exitosamente', $r['mensaje']);
    }

    public function test_incluir_tipo_invalido(): void
    {
        $this->expectException(\Throwable::class);
        $this->tipoCompetencia->incluirTipo(['nombre' => '']);
    }

    public function test_incluir_tipo_ya_existe(): void
    {
        $this->db->method('query')->willReturn(['id_tipo_competencia' => 13]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Ya existe este tipo de competencia","code":400}');
        $this->tipoCompetencia->incluirTipo(['nombre' => 'Sub23']);
    }

    public function test_modificar_tipo_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_tipo_competencia' => 13], [], true);
        $r = $this->tipoCompetencia->modificarTipo(['id_tipo' => $this->enc('13'), 'nombre' => 'Sub24']);
        $this->assertArrayHasKey('mensaje', $r);
        $this->assertEquals('El tipo de competencia se modificó exitosamente', $r['mensaje']);
    }

    public function test_modificar_tipo_invalido(): void
    {
        $this->expectException(\Throwable::class);
        $this->tipoCompetencia->modificarTipo(['id_tipo' => $this->enc('13'), 'nombre' => '']);
    }

    public function test_modificar_tipo_ya_existe(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_tipo_competencia' => 13], ['id_tipo_competencia' => 99]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Ya existe un tipo de competencia con este nombre","code":400}');
        $this->tipoCompetencia->modificarTipo(['id_tipo' => $this->enc('13'), 'nombre' => 'Senior']);
    }

    public function test_modificar_tipo_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\Throwable::class);
        $this->expectExceptionMessage('{"error":"El tipo de competencia ingresado no existe","code":404}');
        $this->tipoCompetencia->modificarTipo(['id_tipo' => $this->enc('123'), 'nombre' => 'Sub25']);
    }

    public function test_eliminar_tipo_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_tipo_competencia' => 13], true);
        $r = $this->tipoCompetencia->eliminarTipo(['id_tipo' => $this->enc('13')]);
        $this->assertArrayHasKey('mensaje', $r);
        $this->assertEquals('El tipo de competencia se eliminó exitosamente', $r['mensaje']);
    }

    public function test_eliminar_tipo_invalido(): void
    {
        $this->tipoCompetencia;
        $this->expectException(\Throwable::class);
        $this->tipoCompetencia->eliminarTipo(['id_tipo' => $this->enc('1/3')]);
    }

    public function test_eliminar_tipo_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\Throwable::class);
        $this->expectExceptionMessage('{"error":"Este tipo de competencia no existe","code":404}');
        $this->tipoCompetencia->eliminarTipo(['id_tipo' => $this->enc('13')]);
    }
}
