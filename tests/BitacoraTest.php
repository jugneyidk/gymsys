<?php
namespace Tests\Feature;

use Gymsys\Core\Database;
use Gymsys\Model\Bitacora;
use Gymsys\Utils\Cipher;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

final class BitacoraTest extends TestCase
{
    private Bitacora $model;
    private Database|MockObject $db;

    public static function setUpBeforeClass(): void
    {
        $_ENV['ENVIRONMENT'] = 'testing';
        $_ENV['SECURE_DB']   = 'secure_db';
        $_ENV['AES_KEY']     = '0123456789abcdef0123456789abcdef';
    }

    protected function setUp(): void
    {
        $this->db = $this->createMock(Database::class);
        $this->model = new Bitacora($this->db);
    }

    public function test_listado_bitacora_exitoso(): void
    {
        $input = [
            'draw' => 1,
            'start' => 0,
            'length' => 10,
            'order' => [['column' => 0, 'dir' => 'desc']],
            'columns' => [['data' => 'fecha']],
            'search' => ['value' => ''],
        ];

        $this->db->method('query')->willReturnOnConsecutiveCalls(
            ['total' => 2],
            ['total' => 2],
            [
                [
                    'id_accion' => 23,
                    'id_usuario' => '22222222',
                    'accion' => 'CREAR',
                    'modulo' => 'eventos',
                    'registro_modificado' => 'competencia:11',
                    'fecha' => '2024-10-01 10:00:00',
                    'nombre_completo' => 'Diego Perez'
                ],
                [
                    'id_accion' => 24,
                    'id_usuario' => '33333333',
                    'accion' => 'ELIMINAR',
                    'modulo' => 'atletas',
                    'registro_modificado' => 'atleta:23124144',
                    'fecha' => '2024-10-01 11:00:00',
                    'nombre_completo' => 'Ana Lopez'
                ]
            ]
        );

        $resp = $this->model->listadoBitacora($input);

        $this->assertIsArray($resp);
        $this->assertSame(1, $resp['draw']);
        $this->assertSame(2, $resp['recordsTotal']);
        $this->assertSame(2, $resp['recordsFiltered']);
        $this->assertIsArray($resp['data']);
        $this->assertCount(2, $resp['data']);
        $this->assertArrayHasKey('id_accion', $resp['data'][0]);
        $this->assertIsString($resp['data'][0]['id_accion']);
        $this->assertNotEmpty($resp['data'][0]['id_accion']);
    }

    public function test_listado_bitacora_filtrado_exitoso(): void
    {
        $input = [
            'draw' => 2,
            'start' => 0,
            'length' => 5,
            'order' => [['column' => 0, 'dir' => 'asc']],
            'columns' => [['data' => 'fecha']],
            'search' => ['value' => 'CREAR'],
        ];

        $this->db->method('query')->willReturnOnConsecutiveCalls(
            ['total' => 10],
            ['total' => 1],
            [
                [
                    'id_accion' => 99,
                    'id_usuario' => '44444444',
                    'accion' => 'CREAR',
                    'modulo' => 'rolespermisos',
                    'registro_modificado' => 'rol:45',
                    'fecha' => '2024-09-15 08:30:00',
                    'nombre_completo' => 'Juan Gomez'
                ]
            ]
        );

        $resp = $this->model->listadoBitacora($input);

        $this->assertIsArray($resp);
        $this->assertSame(2, $resp['draw']);
        $this->assertSame(10, $resp['recordsTotal']);
        $this->assertSame(1, $resp['recordsFiltered']);
        $this->assertIsArray($resp['data']);
        $this->assertCount(1, $resp['data']);
        $this->assertSame('CREAR', $resp['data'][0]['accion']);
    }

    public function test_consultar_accion_exitoso(): void
    {
        $idPlano = '23';
        $idEnc = Cipher::aesEncrypt($idPlano);

        $this->db->method('query')->willReturnOnConsecutiveCalls(
            ['id_accion' => 23],
            [
                'id_accion' => 23,
                'id_usuario' => '22222222',
                'accion' => 'CREAR',
                'modulo' => 'eventos',
                'registro_modificado' => 'competencia:11',
                'fecha' => '2024-10-01 10:00:00'
            ]
        );

        $resp = $this->model->obtenerAccion(['id' => $idEnc]);

        $this->assertIsArray($resp);
        $this->assertArrayHasKey('accion', $resp);
        $this->assertSame(23, $resp['accion']['id_accion']);
        $this->assertSame('CREAR', $resp['accion']['accion']);
    }

    public function test_consultar_accion_no_existente(): void
    {
        $idEnc = Cipher::aesEncrypt('5');

        $this->db->method('query')->willReturn(false);

        $this->expectException(\Throwable::class);
        $this->expectExceptionMessage('{"error":"No existe la accion","code":404}');

        $this->model->obtenerAccion(['id' => $idEnc]);
    }
}
