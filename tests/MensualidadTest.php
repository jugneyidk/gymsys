<?php

namespace Tests\Feature;

use Gymsys\Model\Mensualidad;
use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class MensualidadTest extends TestCase
{
    private Mensualidad $model;
    private Database|MockObject $db;
    private string $encCedula;
    private string $encIdMensualidad;

    protected function setUp(): void
    {
        $this->db = $this->createMock(Database::class);
        $this->model = new Mensualidad($this->db);
        $this->encCedula = Cipher::aesEncrypt('23124144');
        $this->encIdMensualidad = Cipher::aesEncrypt('171');
    }

    public function test_incluir_mensualidad_exitoso(): void
    {
        $this->db->expects($this->once())->method('beginTransaction');
        $this->db->expects($this->once())->method('commit');
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            [],
            true
        );

        $resp = $this->model->incluirMensualidad([
            'id_atleta' => $this->encCedula,
            'monto' => '20',
            'fecha' => '2024-10-10',
            'detalles' => 'Pago en caja'
        ]);

        $this->assertIsArray($resp);
        $this->assertArrayHasKey('mensaje', $resp);
        $this->assertEquals('La mensualidad se agrego correctamente', $resp['mensaje']);
    }

    public function test_incluir_mensualidad_duplicada(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            ['x' => 1]
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Ya existe una mensualidad para este atleta en ese mes","code":400}');
        $this->model->incluirMensualidad([
            'id_atleta' => $this->encCedula,
            'monto' => '20',
            'fecha' => '2024-10-10',
            'detalles' => 'Pago en caja'
        ]);
    }

    public function test_incluir_mensualidad_invalida(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"La fecha introducida no es v\u00e1lida","code":400}');
        $this->model->incluirMensualidad([
            'id_atleta' => $this->encCedula,
            'monto' => '20',
            'fecha' => '2024-32-10',
            'detalles' => 'Pago en caja'
        ]);
    }

    public function test_eliminar_mensualidad_exitoso(): void
    {
        $this->db->expects($this->once())->method('beginTransaction');
        $this->db->expects($this->once())->method('commit');
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            true,
            true
        );

        $resp = $this->model->eliminarMensualidad(['id' => $this->encIdMensualidad]);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('mensaje', $resp);
        $this->assertEquals('La mensualidad se eliminó exitosamente', $resp['mensaje']);
    }

    public function test_eliminar_mensualidad_no_existe(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(false);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"La mensualidad ingresada no existe","code":404}');
        $this->model->eliminarMensualidad(['id' => $this->encIdMensualidad]);
    }

    public function test_eliminar_mensualidad_invalida(): void
    {
        $bad = Cipher::aesEncrypt('mensualidad');
        $this->expectException(\Throwable::class);
        $this->model->eliminarMensualidad(['id' => $bad]);
    }

    public function test_listado_mensualidades(): void
    {
        $this->db->method('query')->willReturn([]);
        $resp = $this->model->listadoMensualidades();
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('mensualidades', $resp);
    }

    public function test_listado_deudores(): void
    {
        $this->db->method('query')->willReturn([]);
        $resp = $this->model->listadoDeudores();
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('deudores', $resp);
    }
}
