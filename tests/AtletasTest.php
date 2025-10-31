<?php

namespace Tests\Feature;

use Gymsys\Model\Atletas;
use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class AtletasTest extends TestCase
{
    private Atletas $model;
    private Database|MockObject $db;

    protected function setUp(): void
    {
        $this->db = $this->createMock(Database::class);
        $this->model = new Atletas($this->db);
    }

    public function test_incluir_atleta_exitoso(): void
    {
        $this->db->expects($this->once())->method('beginTransaction');
        $this->db->expects($this->once())->method('commit');
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            false,
            true,
            true
        );

        $resp = $this->model->incluirAtleta([
            'cedula' => '5560233',
            'nombres' => 'Alejandro',
            'apellidos' => 'Martinez',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1990-01-01',
            'lugar_nacimiento' => 'Ciudad',
            'estado_civil' => 'Soltero',
            'telefono' => '04265538456',
            'correo_electronico' => 'aleale@example.com',
            'peso' => '62',
            'altura' => '181',
            'entrenador_asignado' => Cipher::aesEncrypt('22222222'),
            'tipo_atleta' => Cipher::aesEncrypt('1'),
            'password' => 'Password123$'
        ]);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('mensaje', $resp);
        $this->assertEquals("Atleta incluido con éxito", $resp["mensaje"]);
    }

    public function test_incluir_atleta_duplicado(): void
    {
        $this->db->method('query')->willReturn(true);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El atleta ingresado ya existe","code":400}');
        $this->model->incluirAtleta([
            'cedula' => '5560233',
            'nombres' => 'Alejandro',
            'apellidos' => 'Martinez',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1990-01-01',
            'lugar_nacimiento' => 'Ciudad',
            'estado_civil' => 'Soltero',
            'telefono' => '04265538456',
            'correo_electronico' => 'aleale@example.com',
            'peso' => '62',
            'altura' => '181',
            'entrenador_asignado' => Cipher::aesEncrypt('22222222'),
            'tipo_atleta' => Cipher::aesEncrypt('1'),
            'password' => 'Password123$'
        ]);
    }

    public function test_incluir_atleta_invalido(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->model->incluirAtleta([
            'cedula' => '5560233a.',
            'nombres' => 'Alejandro22',
            'apellidos' => 'Martinez--',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1990-01-44',
            'lugar_nacimiento' => 'Ciudad',
            'estado_civil' => '',
            'telefono' => '0426-5538456',
            'correo_electronico' => 'aleale@example.com',
            'peso' => 'full',
            'altura' => '181',
            'entrenador_asignado' => Cipher::aesEncrypt('22222222'),
            'tipo_atleta' => Cipher::aesEncrypt('1'),
            'password' => 'contra1122'
        ]);
    }

    public function test_incluir_atleta_vacio(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Los siguientes campos faltan: [\"nombres\",\"apellidos\",\"cedula\",\"genero\",\"fecha_nacimiento\",\"lugar_nacimiento\",\"peso\",\"altura\",\"tipo_atleta\",\"estado_civil\",\"telefono\",\"correo_electronico\",\"entrenador_asignado\",\"password\"]","code":400}');
        $this->model->incluirAtleta([
            'cedula' => '',
            'nombres' => '',
            'apellidos' => '',
            'genero' => '',
            'fecha_nacimiento' => '',
            'lugar_nacimiento' => '',
            'estado_civil' => '',
            'telefono' => '',
            'correo_electronico' => '',
            'peso' => '',
            'altura' => '',
            'entrenador_asignado' => '',
            'tipo_atleta' => '',
            'password' => ''
        ]);
    }
    public function test_obtener_atleta_exitoso(): void
    {
        $enc = Cipher::aesEncrypt('1328547');

        $this->db->method('query')->willReturnOnConsecutiveCalls(
            true,
            [
                'cedula' => '1328547',
                'nombre' => 'Leoleo',
                'apellido' => 'Herrera',
                'genero' => 'Masculino',
                'fecha_nacimiento' => '1990-01-01',
                'lugar_nacimiento' => 'Ciudad',
                'estado_civil' => 'Soltero',
                'telefono' => '04265538456',
                'correo_electronico' => 'leoleole@example.com',
                'id_tipo_atleta' => '1',
                'peso' => '62',
                'altura' => '178',
                'entrenador' => '22222222',
                'cedula_representante' => null,
                'nombre_representante' => null,
                'telefono_representante' => null,
                'parentesco_representante' => null
            ]
        );

        $resp = $this->model->obtenerAtleta(['id' => $enc]);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('atleta', $resp);
        $this->assertIsArray($resp['atleta']);
    }

    public function test_obtener_atleta_no_existe(): void
    {
        $enc = Cipher::aesEncrypt('13285472');
        $this->db->method('query')->willReturn(false);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"No existe el atleta introducido","code":404}');
        $this->model->obtenerAtleta(['id' => $enc]);
    }
    public function test_obtener_atleta_invalido(): void
    {
        $enc = Cipher::aesEncrypt('132854-$72');
        $this->db->method('query')->willReturn(false);

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('{"error":"La c\u00e9dula debe tener al menos 7 n\u00fameros","code":400}');
        $this->model->obtenerAtleta(['id' => $enc]);
    }

    public function test_obtener_atleta_vacio(): void
    {
        $enc = Cipher::aesEncrypt('');
        $this->db->method('query')->willReturn(false);

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('{"error":"La c\u00e9dula debe tener al menos 7 n\u00fameros","code":400}');
        $this->model->obtenerAtleta(['id' => $enc]);
    }

    public function test_modificar_atleta_exitoso(): void
    {
        $this->db->method('query')->willReturn(true);
        $resp = $this->model->modificarAtleta([
            'cedula' => '13285427',
            'nombres' => 'Leoleo',
            'apellidos' => 'Herrera',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1990-01-01',
            'lugar_nacimiento' => 'Ciudad',
            'estado_civil' => 'Soltero',
            'telefono' => '04265538456',
            'correo_electronico' => 'leoleole@example.com',
            'peso' => '62',
            'altura' => '178',
            'entrenador_asignado' => Cipher::aesEncrypt('22222222'),
            'tipo_atleta' => Cipher::aesEncrypt('1'),
            'modificar_contraseña' => '1',
            'password' => 'Password123$'
        ]);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('mensaje', $resp);
        $this->assertEquals("El atleta se ha modificado exitosamente", $resp["mensaje"]);
    }
    public function test_modificar_atleta_no_existe(): void
    {
        $this->db->method('query')->willReturn(false);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"No existe ningun atleta con esta cedula","code":404}');
        $resp = $this->model->modificarAtleta([
            'cedula' => '13285427',
            'nombres' => 'Leoleo',
            'apellidos' => 'Herrera',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1990-01-01',
            'lugar_nacimiento' => 'Ciudad',
            'estado_civil' => 'Soltero',
            'telefono' => '04265538456',
            'correo_electronico' => 'leoleole@example.com',
            'peso' => '62',
            'altura' => '178',
            'entrenador_asignado' => Cipher::aesEncrypt('22222222'),
            'tipo_atleta' => Cipher::aesEncrypt('1'),
            'modificar_contraseña' => '1',
            'password' => 'Password123$'
        ]);
    }

    public function test_modificar_atleta_invalido(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->model->modificarAtleta([
            'cedula' => 'V-13285427',
            'nombres' => 'Leoleo22',
            'apellidos' => 'Herreras.',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1990-01-01',
            'lugar_nacimiento' => 'Ciudad',
            'estado_civil' => 'Soltero',
            'telefono' => '042655322',
            'correo_electronico' => 'leoleole@example',
            'peso' => '',
            'altura' => 'alto',
            'entrenador_asignado' => Cipher::aesEncrypt('22222222'),
            'tipo_atleta' => Cipher::aesEncrypt('1'),
            'modificar_contraseña' => '1',
            'password' => 'Password12'
        ]);
    }

    public function test_modificar_atleta_vacio(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Los siguientes campos faltan: [\"nombres\",\"cedula\",\"genero\",\"fecha_nacimiento\",\"lugar_nacimiento\",\"peso\",\"altura\",\"estado_civil\",\"telefono\",\"correo_electronico\"]","code":400}');
        $this->model->modificarAtleta([
            'cedula' => '',
            'nombres' => '',
            'apellidos' => '.',
            'genero' => '',
            'fecha_nacimiento' => '',
            'lugar_nacimiento' => '',
            'estado_civil' => '',
            'telefono' => '',
            'correo_electronico' => '',
            'peso' => '',
            'altura' => '',
            'entrenador_asignado' => Cipher::aesEncrypt(''),
            'tipo_atleta' => Cipher::aesEncrypt(''),
            'modificar_contraseña' => '',
            'password' => ''
        ]);
    }

    public function test_eliminar_atleta_exitoso(): void
    {
        $enc = Cipher::aesEncrypt('5560233');

        $this->db->expects($this->once())->method('beginTransaction');
        $this->db->expects($this->once())->method('commit');
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            true,
            true,
            true
        );

        $resp = $this->model->eliminarAtleta(['cedula' => $enc]);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('mensaje', $resp);
        $this->assertEquals("El atleta se ha eliminado exitosamente", $resp['mensaje']);
    }

    public function test_eliminar_atleta_no_existe(): void
    {
        $enc = Cipher::aesEncrypt('55602332');
        $this->db->method('query')->willReturn(false);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El atleta introducido no existe","code":404}');
        $this->model->eliminarAtleta(['cedula' => $enc]);
    }
    public function test_eliminar_atleta_invalido(): void
    {
        $enc = Cipher::aesEncrypt('5560dd$2');
        $this->db->method('query')->willReturn(false);

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('{"error":"La c\u00e9dula debe tener al menos 7 n\u00fameros","code":400}');
        $this->model->eliminarAtleta(['cedula' => $enc]);
    }

    public function test_eliminar_atleta_vacio(): void
    {
        $enc = Cipher::aesEncrypt('');
        $this->db->method('query')->willReturn(false);

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('{"error":"La c\u00e9dula debe tener al menos 7 n\u00fameros","code":400}');
        $this->model->eliminarAtleta(['cedula' => $enc]);
    }
    public function test_listado_atletas(): void
    {
        $this->db->method('query')->willReturn([]);
        $resp = $this->model->listadoAtletas();
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('atletas', $resp);
    }
}
