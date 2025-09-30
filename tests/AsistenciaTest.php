<?php
namespace Tests\Feature;

use Gymsys\Model\Asistencias;
use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use PHPUnit\Framework\TestCase;

final class AsistenciasTest extends TestCase
{
    private Asistencias $model;
    private Database $db;

    protected function setUp(): void
    {
        $this->db = $this->createMock(Database::class);
        $this->model = new Asistencias($this->db);
    }

    public function test_guardar_asistencias_exitoso(): void
    {
        $this->db->expects($this->once())->method('beginTransaction');
        $this->db->expects($this->once())->method('commit');
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            true,
            true,
            true,
            true
        );

        $rows = [
            ['id_atleta' => Cipher::aesEncrypt('68281581'), 'asistio' => 1, 'comentario' => ''],
            ['id_atleta' => Cipher::aesEncrypt('42194292'), 'asistio' => 0, 'comentario' => ''],
            ['id_atleta' => Cipher::aesEncrypt('24244444'), 'asistio' => 1, 'comentario' => 'ok']
        ];

        $resp = $this->model->guardarAsistencias([
            'fecha' => '2024-10-10',
            'asistencias' => json_encode($rows)
        ]);

        $this->assertIsArray($resp);
        $this->assertArrayHasKey('mensaje', $resp);
    }

    public function test_guardar_asistencias_invalido_json(): void
    {
        $this->expectException(\Throwable::class);
        $this->model->guardarAsistencias([
            'fecha' => '2024-10-10',
            'asistencias' => 'asistencias'
        ]);
    }

    public function test_guardar_asistencias_fecha_futura(): void
    {
        $rows = [
            ['id_atleta' => Cipher::aesEncrypt('68281581'), 'asistio' => 1, 'comentario' => '']
        ];
        $this->expectException(\Throwable::class);
        $this->model->guardarAsistencias([
            'fecha' => '2999-01-01',
            'asistencias' => json_encode($rows)
        ]);
    }

    public function test_obtener_asistencias_exitoso(): void
    {
        $this->db->method('query')->willReturn([
            ['id_atleta' => '68281581', 'fecha' => '2024-10-10', 'asistio' => 1, 'comentario' => ''],
            ['id_atleta' => '42194292', 'fecha' => '2024-10-10', 'asistio' => 0, 'comentario' => '']
        ]);

        $resp = $this->model->obtenerAsistencias(['fecha' => '2024-10-10']);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('asistencias', $resp);
        $this->assertIsArray($resp['asistencias']);
    }

    public function test_eliminar_asistencias_exitoso(): void
    {
        $this->db->expects($this->once())->method('beginTransaction');
        $this->db->expects($this->once())->method('commit');
        $this->db->method('query')->willReturn(true);

        $resp = $this->model->eliminarAsistencias(['fecha' => '2024-10-10']);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('mensaje', $resp);
    }

    public function test_eliminar_asistencias_fecha_invalida(): void
    {
        $this->expectException(\Throwable::class);
        $this->model->eliminarAsistencias(['fecha' => '2024-32-144']);
    }

    public function test_guardar_asistencias_falla_insert(): void
    {
        $this->db->expects($this->once())->method('beginTransaction');
        $this->db->expects($this->never())->method('commit');
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            true,
            true,
            false
        );

        $rows = [
            ['id_atleta' => Cipher::aesEncrypt('68281581'), 'asistio' => 1, 'comentario' => ''],
            ['id_atleta' => Cipher::aesEncrypt('42194292'), 'asistio' => 0, 'comentario' => '']
        ];

        $this->expectException(\Throwable::class);
        $this->model->guardarAsistencias([
            'fecha' => '2024-10-10',
            'asistencias' => json_encode($rows)
        ]);
    }
}
