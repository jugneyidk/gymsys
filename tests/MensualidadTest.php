<?php
namespace Tests\Feature;

use Gymsys\Model\Mensualidad;
use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use PHPUnit\Framework\TestCase;

final class MensualidadTest extends TestCase
{
    private Mensualidad $model;
    private Database $db;
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
    }

    public function test_incluir_mensualidad_duplicada(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            ['x' => 1]
        );

        $this->expectException(\Throwable::class);
        $this->model->incluirMensualidad([
            'id_atleta' => $this->encCedula,
            'monto' => '20',
            'fecha' => '2024-10-10',
            'detalles' => 'Pago en caja'
        ]);
    }

    public function test_incluir_mensualidad_invalida_fecha(): void
    {
        $this->expectException(\Throwable::class);
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
    }

    public function test_eliminar_mensualidad_no_existe(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(false);

        $this->expectException(\Throwable::class);
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
