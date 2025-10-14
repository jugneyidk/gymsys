<?php

namespace Tests\Feature;

use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use Gymsys\Model\Subs;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class SubsTest extends TestCase
{
    private Subs $subs;
    private Database|MockObject $db;

    private function enc(string $v): string
    {
        return Cipher::aesEncrypt($v);
    }

    protected function setUp(): void
    {
        $this->db = $this->createMock(Database::class);
        $this->subs = new Subs($this->db);
    }

    public function test_incluir_sub_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls([], true);
        $r = $this->subs->incluirSub([
            'nombre' => 'U20',
            'edadMinima' => 15,
            'edadMaxima' => 20
        ]);
        $this->assertIsArray($r);
        $this->assertArrayHasKey('mensaje', $r);
        $this->assertEquals('La sub se registró exitosamente', $r['mensaje']);
    }
    public function test_incluir_sub_invalido(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Los siguientes campos faltan: [\"edadMinima\"]","code":400}');
        $this->subs->incluirSub([
            'nombre' => 'U20',
            'edadMinima' => '',
            'edadMaxima' => 20
        ]);
    }

    public function test_incluir_sub_duplicada(): void
    {
        $this->db->method('query')->willReturn(['id_sub' => 8]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Ya existe una sub con este nombre","code":400}');
        $this->subs->incluirSub([
            'nombre' => 'U20',
            'edadMinima' => 15,
            'edadMaxima' => 20
        ]);
    }
    public function test_incluir_sub_rango_invalido(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"La edad m\u00e1xima no puede ser menor o igual a la edad m\u00ednima","code":400}');
        $this->subs->incluirSub([
            'nombre' => 'sub13',
            'edadMinima' => 15,
            'edadMaxima' => 13
        ]);
    }

    public function test_modificar_sub_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_sub' => 8], [], true);
        $r = $this->subs->modificarSub([
            'id_sub' => $this->enc('8'),
            'nombre' => 'U20',
            'edadMinima' => 16,
            'edadMaxima' => 20
        ]);
        $this->assertIsArray($r);
        $this->assertArrayHasKey('mensaje', $r);
        $this->assertEquals('La sub se modificó exitosamente', $r['mensaje']);
    }

    public function test_modificar_sub_invalida(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El valor ingresado no es un n\u00famero entero v\u00e1lido","code":400}');
        $this->subs->modificarSub([
            'id_sub' => $this->enc('8'),
            'nombre' => 'U20',
            'edadMinima' => 'menor',
            'edadMaxima' => 20
        ]);
    }

    public function test_modificar_sub_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"La sub ingresada no existe","code":404}');
        $this->subs->modificarSub([
            'id_sub' => $this->enc('83'),
            'nombre' => 'U20',
            'edadMinima' => 15,
            'edadMaxima' => 20
        ]);
    }

    public function test_modificar_sub_duplicada(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_sub' => 8], ['id_sub' => 9]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Ya existe una sub con este nombre","code":400}');
        $this->subs->modificarSub([
            'id_sub' => $this->enc('8'),
            'nombre' => 'U15',
            'edadMinima' => 15,
            'edadMaxima' => 20
        ]);
    }

    public function test_modificar_sub_rango_invalido(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"La edad m\u00e1xima no puede ser menor o igual a la edad m\u00ednima","code":400}');
        $this->subs->modificarSub([
            'id_sub' => $this->enc('8'),
            'nombre' => 'U20',
            'edadMinima' => 23,
            'edadMaxima' => 20
        ]);
    }
    public function test_eliminar_sub_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_sub' => 8], true);
        $r = $this->subs->eliminarSub(['id_sub' => $this->enc('8')]);
        $this->assertIsArray($r);
        $this->assertArrayHasKey('mensaje', $r);
        $this->assertEquals('La sub se eliminó exitosamente', $r['mensaje']);
    }

    public function test_eliminar_sub_invalida(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El valor ingresado no es un n\u00famero entero v\u00e1lido","code":400}');
        $this->subs->eliminarSub(['id_sub' => $this->enc('categoria')]);
    }

    public function test_eliminar_sub_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"La sub ingresada no existe","code":404}');
        $this->subs->eliminarSub(['id_sub' => $this->enc('100')]);
    }
    public function test_listado_subs(): void
    {
        $this->db->method('query')->willReturn([['id_sub' => 8, 'nombre' => 'U20', 'edad_minima' => 15, 'edad_maxima' => 20]]);
        $r = $this->subs->listadoSubs();
        $this->assertIsArray($r);
        $this->assertArrayHasKey('subs', $r);
        $this->assertIsArray($r['subs']);
        $this->assertCount(1, $r['subs']);
    }
}
