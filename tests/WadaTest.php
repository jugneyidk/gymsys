<?php

namespace Tests\Feature;

use Gymsys\Model\Wada;
use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class WadaTest extends TestCase
{
    private Wada $model;
    /** @var Database&MockObject */
    private $db;
    private string $atleta;

    protected function setUp(): void
    {
        $this->db = $this->createMock(Database::class);
        $this->model = new Wada($this->db);
        $this->atleta = Cipher::aesEncrypt('42342344');
    }

    private function normalize(string $sql): string
    {
        $s = strtolower(str_replace('`', '', $sql));
        $s = preg_replace('/\s+/', ' ', $s);
        return trim($s);
    }

    public function test_incluir_wada_exitoso(): void
    {
        $this->db->expects($this->once())->method('beginTransaction');
        $this->db->expects($this->once())->method('commit');

        $this->db->method('query')->willReturnCallback(function ($sql) {
            $s = $this->normalize((string)$sql);
            if (str_contains($s, 'select') && str_contains($s, ' from ') && str_contains($s, 'wada') && str_contains($s, 'select id_atleta')) {
                return false;
            }
            if (str_contains($s, 'insert into') && str_contains($s, 'wada')) {
                return true;
            }
            if (str_contains($s, ' from ') && str_contains($s, 'usuarios')) {
                return ['fecha_nacimiento' => '2000-01-01'];
            }
            if (str_contains($s, ' from ') && str_contains($s, 'atleta')) {
                return true;
            }
            return true;
        });

        $resp = $this->model->incluirWada([
            'atleta' => $this->atleta,
            'status' => true,
            'inscrito' => '2024-07-12',
            'ultima_actualizacion' => '2024-07-12',
            'vencimiento' => '2024-10-12'
        ]);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('mensaje', $resp);
        $this->assertEquals("La WADA se registró exitosamente", $resp['mensaje']);
    }

    public function test_incluir_wada_duplicada(): void
    {
        $this->db->method('query')->willReturnCallback(function ($sql) {
            $s = $this->normalize((string)$sql);
            if (str_contains($s, 'select') && str_contains($s, ' from ') && str_contains($s, 'wada') && str_contains($s, 'select id_atleta')) {
                return true;
            }
            if (str_contains($s, ' from ') && str_contains($s, 'usuarios')) {
                return ['fecha_nacimiento' => '2000-01-01'];
            }
            if (str_contains($s, ' from ') && str_contains($s, 'atleta')) {
                return true;
            }
            return true;
        });

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"La WADA para este atleta ya existe","code":400}');
        $this->model->incluirWada([
            'atleta' => $this->atleta,
            'status' => 0,
            'inscrito' => '2024-07-12',
            'ultima_actualizacion' => '2024-07-12',
            'vencimiento' => '2024-10-12'
        ]);
    }

    public function test_incluir_wada_invalida(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Los siguientes campos faltan: [\"status\"]","code":400}');
        $this->model->incluirWada([
            'atleta' => 'enc:BAD',
            'status' => '',
            'inscrito' => '2024-07-12',
            'ultima_actualizacion' => '2024-07-12',
            'vencimiento' => '2024-07-12'
        ]);
    }

    public function test_obtener_wada_exitoso(): void
    {
        $this->db->method('query')->willReturnCallback(function ($sql) {
            $s = $this->normalize((string)$sql);
            if (str_contains($s, 'select id_atleta from wada')) {
                return true;
            }
            if (str_contains($s, 'select * from wada')) {
                return [
                    'id_atleta' => '42342344',
                    'estado' => 1,
                    'inscrito' => '2024-07-12',
                    'ultima_actualizacion' => '2024-08-12',
                    'vencimiento' => '2024-11-12'
                ];
            }
            return [];
        });

        $resp = $this->model->obtenerWada(['id' => $this->atleta]);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('wada', $resp);
    }

    public function test_obtener_wada_no_existe(): void
    {
        $this->db->method('query')->willReturnCallback(function ($sql) {
            $s = $this->normalize((string)$sql);
            if (str_contains($s, 'select id_atleta from wada')) {
                return false;
            }
            return [];
        });

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"La WADA de este atleta no existe","code":400}');

        $this->model->obtenerWada(['id' => $this->atleta]);
    }

    public function test_obtener_wada_invalida(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('{"error":"La c\u00e9dula debe tener al menos 7 n\u00fameros","code":400}');
        $atleta = Cipher::aesEncrypt("prueba123");
        $this->model->obtenerWada(['id' => $atleta]);
    }

    public function test_modificar_wada_exitoso(): void
    {
        $this->db->expects($this->once())->method('beginTransaction');
        $this->db->expects($this->once())->method('commit');

        $this->db->method('query')->willReturnCallback(function ($sql) {
            $s = $this->normalize((string)$sql);
            if (str_contains($s, 'select id_atleta from wada')) {
                return true;
            }
            if (str_contains($s, 'update wada')) {
                return true;
            }
            if (str_contains($s, ' from ') && str_contains($s, 'usuarios')) {
                return ['fecha_nacimiento' => '2000-01-01'];
            }
            if (str_contains($s, ' from ') && str_contains($s, 'atleta')) {
                return true;
            }
            return true;
        });

        $resp = $this->model->modificarWada([
            'atleta' => $this->atleta,
            'status' => 1,
            'inscrito' => '2024-07-12',
            'ultima_actualizacion' => '2024-08-12',
            'vencimiento' => '2024-11-12'
        ]);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('mensaje', $resp);
        $this->assertEquals('La WADA se modificó exitosamente', $resp["mensaje"]);
    }

    public function test_modificar_wada_no_existe(): void
    {
        $this->db->method('query')->willReturnCallback(function ($sql) {
            $s = $this->normalize((string)$sql);
            if (str_contains($s, 'select id_atleta from wada')) {
                return false;
            }
            if (str_contains($s, ' from ') && str_contains($s, 'usuarios')) {
                return ['fecha_nacimiento' => '2000-01-01'];
            }
            if (str_contains($s, ' from ') && str_contains($s, 'atleta')) {
                return true;
            }
            return true;
        });

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"La WADA del atleta introducido no existe","code":404}');
        $this->model->modificarWada([
            'atleta' => $this->atleta,
            'status' => 1,
            'inscrito' => '2024-07-12',
            'ultima_actualizacion' => '2024-07-12',
            'vencimiento' => '2024-11-12'
        ]);
    }

    public function test_modificar_wada_invalida(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Los siguientes campos faltan: [\"status\",\"inscrito\"]","code":400}');
        $this->model->modificarWada([
            'atleta' => 'enc:342343324',
            'status' => '',
            'inscrito' => '',
            'ultima_actualizacion' => '2024-10-12',
            'vencimiento' => '2024-11-12'
        ]);
    }

    public function test_eliminar_wada_exitoso(): void
    {
        $this->db->expects($this->once())->method('beginTransaction');
        $this->db->expects($this->once())->method('commit');

        $this->db->method('query')->willReturnCallback(function ($sql) {
            $s = $this->normalize((string)$sql);
            if (str_contains($s, 'select id_atleta from wada')) {
                return true;
            }
            if (str_contains($s, 'delete from wada')) {
                return true;
            }
            return true;
        });

        $resp = $this->model->eliminarWada(['cedula' => $this->atleta]);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('mensaje', $resp);
        $this->assertEquals('La WADA se eliminó exitosamente', $resp["mensaje"]);
    }

    public function test_eliminar_wada_no_existe(): void
    {
        $this->db->method('query')->willReturnCallback(function ($sql) {
            $s = $this->normalize((string)$sql);
            if (str_contains($s, 'select id_atleta from wada')) {
                return false;
            }
            return true;
        });

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"La WADA del atleta introducido no existe","code":404}');
        $this->model->eliminarWada(['cedula' => $this->atleta]);
    }

    public function test_eliminar_wada_invalida(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('{"error":"La c\u00e9dula debe tener al menos 7 n\u00fameros","code":400}');
        $atleta = Cipher::aesEncrypt("prueba123");
        $this->model->eliminarWada(['cedula' => $atleta]);
    }
    public function test_incluir_wada_vacio(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Los siguientes campos faltan: [\"status\",\"inscrito\",\"ultima_actualizacion\",\"vencimiento\"]","code":400}');
        $this->model->incluirWada([
            'atleta' => Cipher::aesEncrypt(''),
            'status' => '',
            'inscrito' => '',
            'ultima_actualizacion' => '',
            'vencimiento' => ''
        ]);
    }

    public function test_obtener_wada_vacio(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('{"error":"La c\u00e9dula debe tener al menos 7 n\u00fameros","code":400}');
        $this->model->obtenerWada(['id' => Cipher::aesEncrypt('')]);
    }

    public function test_modificar_wada_vacio(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Los siguientes campos faltan: [\"status\",\"inscrito\",\"ultima_actualizacion\",\"vencimiento\"]","code":400}');
        $this->model->modificarWada([
            'atleta' => Cipher::aesEncrypt(''),
            'status' => '',
            'inscrito' => '',
            'ultima_actualizacion' => '',
            'vencimiento' => ''
        ]);
    }

    public function test_eliminar_wada_vacio(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Los siguientes campos faltan: [\"cedula\"]","code":400}');
        $this->model->eliminarWada(['id' => Cipher::aesEncrypt('')]);
    }

    public function test_listado_wada(): void
    {
        $this->db->method('query')->willReturn([]);
        $resp = $this->model->listadoWada();
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('wada', $resp);
        $this->assertIsArray($resp['wada']);
    }

    public function test_listado_por_vencer(): void
    {
        $this->db->method('query')->willReturn([]);
        $resp = $this->model->listadoPorVencer();
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('wadas', $resp);
        $this->assertIsArray($resp['wadas']);
    }
}
