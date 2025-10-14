<?php

namespace Tests\Feature;

use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use Gymsys\Model\Categorias;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CategoriaTest extends TestCase
{
    private Categorias $categorias;
    private Database|MockObject $db;

    private function enc(string $v): string
    {
        return Cipher::aesEncrypt($v);
    }

    protected function setUp(): void
    {
        $this->db = $this->createMock(Database::class);
        $this->categorias = new Categorias($this->db);
    }

    public function test_categorias_listado_exitoso(): void
    {
        $this->db->method('query')->willReturn([['id_categoria' => 8, 'nombre' => '81M', 'peso_minimo' => 73, 'peso_maximo' => 81.99]]);
        $r = $this->categorias->listadoCategorias();
        $this->assertArrayHasKey('categorias', $r);
    }

    public function test_incluir_categoria_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls([], true);
        $r = $this->categorias->incluirCategoria(['nombre' => '81M', 'pesoMinimo' => 73, 'pesoMaximo' => 81.99]);
        $this->assertArrayHasKey('mensaje', $r);
        $this->assertEquals('La categoría se registró exitosamente', $r['mensaje']);
    }

    public function test_incluir_categoria_invalido(): void
    {
        $this->categorias;
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El peso m\u00e1ximo no puede ser menor o igual al peso m\u00ednimo","code":400}');
        $this->categorias->incluirCategoria(['nombre' => '81M', 'pesoMinimo' => 85, 'pesoMaximo' => 81.99]);
    }

    public function test_incluir_categoria_ya_existe(): void
    {
        $this->db->method('query')->willReturn(['id_categoria' => 8]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Ya existe una categoria con este nombre","code":404}');
        $this->categorias->incluirCategoria(['nombre' => '81M', 'pesoMinimo' => 75, 'pesoMaximo' => 81.99]);
    }

    public function test_modificar_categoria_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_categoria' => 8], [], true);
        $r = $this->categorias->modificarCategoria(['id_categoria' => $this->enc('8'), 'nombre' => '81F', 'pesoMinimo' => 75, 'pesoMaximo' => 81.99]);
        $this->assertArrayHasKey('mensaje', $r);
        $this->assertEquals('La categoría se modificó exitosamente', $r['mensaje']);
    }

    public function test_modificar_categoria_invalido(): void
    {
        $this->categorias;
        $this->expectException(\Throwable::class);
        $this->categorias->modificarCategoria(['id_categoria' => $this->enc('8'), 'nombre' => '', 'pesoMinimo' => 75, 'pesoMaximo' => '']);
    }

    public function test_modificar_categoria_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\Throwable::class);
        $this->expectExceptionMessage('{"error":"No existe la categoria ingresada","code":404}');
        $this->categorias->modificarCategoria(['id_categoria' => $this->enc('82'), 'nombre' => '81M', 'pesoMinimo' => 75, 'pesoMaximo' => 80.99]);
    }

    public function test_eliminar_categoria_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_categoria' => 8], true);
        $r = $this->categorias->eliminarCategoria(['id_categoria' => $this->enc('8')]);
        $this->assertArrayHasKey('mensaje', $r);
        $this->assertEquals('La categoria se eliminó exitosamente', $r['mensaje']);
    }

    public function test_eliminar_categoria_invalido(): void
    {
        $this->categorias;
        $this->expectException(\Throwable::class);
        $this->categorias->eliminarCategoria(['id_categoria' => $this->enc('Categoria')]);
    }

    public function test_eliminar_categoria_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\Throwable::class);
        $this->expectExceptionMessage('{"error":"La categoria ingresada no existe","code":404}');
        $this->categorias->eliminarCategoria(['id_categoria' => $this->enc('333')]);
    }
}
