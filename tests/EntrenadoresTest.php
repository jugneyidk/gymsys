<?php

namespace Tests\Feature;

use Gymsys\Model\Entrenadores;
use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class EntrenadoresTest extends TestCase
{
    private Entrenadores $model;
    private Database|MockObject $db;

    protected function setUp(): void
    {
        $this->db = $this->createMock(Database::class);
        $this->model = new Entrenadores($this->db);
    }

    public function test_incluir_entrenador_exitoso(): void
    {
        $this->db->expects($this->once())->method('beginTransaction');
        $this->db->expects($this->once())->method('commit');
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            false,
            true,
            true,
            true
        );

        $resp = $this->model->incluirEntrenador([
            'cedula' => '3145612',
            'nombres' => 'Juan',
            'apellidos' => 'Perez',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1990-01-01',
            'lugar_nacimiento' => 'Ciudad',
            'estado_civil' => 'Soltero',
            'telefono' => '04122131233',
            'correo_electronico' => 'juan@example.com',
            'grado_instruccion' => 'Licenciatura',
            'password' => 'Password123$'
        ]);

        $this->assertIsArray($resp);
        $this->assertArrayHasKey('mensaje', $resp);
        $this->assertEquals("Entrenador agregado con éxito", $resp["mensaje"]);
    }

    public function test_incluir_entrenador_duplicado(): void
    {
        $this->db->method('query')->willReturn(true);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El entrenador ya existe","code":400}');
        $this->model->incluirEntrenador([
            'cedula' => '22222222',
            'nombres' => 'Juan',
            'apellidos' => 'Perez',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1990-01-01',
            'lugar_nacimiento' => 'Ciudad',
            'estado_civil' => 'Soltero',
            'telefono' => '04122131233',
            'correo_electronico' => 'juan@example.com',
            'grado_instruccion' => 'Licenciatura',
            'password' => 'Password123$'
        ]);
    }
    public function test_incluir_entrenador_invalido(): void
    {
        $this->db->method('query')->willReturn(true);

        $this->expectException(\InvalidArgumentException::class);
        $this->model->incluirEntrenador([
            'cedula' => '22222$22',
            'nombres' => 'Juan132',
            'apellidos' => '',
            'genero' => 'Indefinido',
            'fecha_nacimiento' => '1990-33-121',
            'lugar_nacimiento' => 'Ciudad',
            'estado_civil' => 'Soltero',
            'telefono' => '0412-2131233',
            'correo_electronico' => 'juan@example.',
            'grado_instruccion' => '1234',
            'password' => 'password123'
        ]);
    }

    public function test_obtener_entrenador_exitoso(): void
    {
        $enc = Cipher::aesEncrypt('22222222');

        $this->db->method('query')->willReturnOnConsecutiveCalls(
            true,
            [
                'cedula' => '22222222',
                'nombre' => 'Juan',
                'apellido' => 'Perez',
                'genero' => 'Masculino',
                'fecha_nacimiento' => '1990-01-01',
                'lugar_nacimiento' => 'Ciudad',
                'estado_civil' => 'Soltero',
                'telefono' => '04122131233',
                'correo_electronico' => 'juan@example.com',
                'grado_instruccion' => 'Licenciatura'
            ]
        );

        $resp = $this->model->obtenerEntrenador(['id' => $enc]);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('entrenador', $resp);
        $this->assertIsArray($resp['entrenador']);
    }

    public function test_obtener_entrenador_no_existe(): void
    {
        $enc = Cipher::aesEncrypt('1234567');
        $this->db->method('query')->willReturn(false);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El entrenador ingresado no existe","code":404}');
        $this->model->obtenerEntrenador(['id' => $enc]);
    }
    public function test_obtener_entrenador_invalido(): void
    {
        $enc = Cipher::aesEncrypt('12 34$$7');
        $this->db->method('query')->willReturn(false);

        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('{"error":"La c\u00e9dula debe tener al menos 7 n\u00fameros","code":400}');
        $this->model->obtenerEntrenador(['id' => $enc]);
    }

    public function test_modificar_entrenador_exitoso(): void
    {
        $cedOrigEnc = Cipher::aesEncrypt('8676719');

        $this->db->expects($this->once())->method('beginTransaction');
        $this->db->expects($this->once())->method('commit');
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            true,
            true
        );

        $resp = $this->model->modificarEntrenador([
            'cedula_original' => $cedOrigEnc,
            'cedula' => '8676719',
            'nombres' => 'Marcos',
            'apellidos' => 'Perez',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1990-01-04',
            'lugar_nacimiento' => 'Ciudad',
            'estado_civil' => 'Casado',
            'telefono' => '04122131222',
            'correo_electronico' => 'juanperez@example.com',
            'grado_instruccion' => 'Licenciatura'
        ]);

        $this->assertIsArray($resp);
        $this->assertArrayHasKey('mensaje', $resp);
        $this->assertEquals("El entrenador se ha modificado exitosamente", $resp["mensaje"]);
    }

    public function test_modificar_entrenador_no_existe(): void
    {
        $cedOrigEnc = Cipher::aesEncrypt('9999999');
        $this->db->method('query')->willReturn(false);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"No existe el entrenador","code":404}');
        $this->model->modificarEntrenador([
            'cedula_original' => $cedOrigEnc,
            'cedula' => '9999999',
            'nombres' => 'Guillermo',
            'apellidos' => 'Calal',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1990-01-01',
            'lugar_nacimiento' => 'Ciudad',
            'estado_civil' => 'Soltero',
            'telefono' => '04120000000',
            'correo_electronico' => 'x@y.com',
            'grado_instruccion' => 'Licenciatura'
        ]);
    }
    public function test_incluir_entrenador_vacio(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Los siguientes campos faltan: [\"nombres\",\"apellidos\",\"cedula\",\"genero\",\"fecha_nacimiento\",\"lugar_nacimiento\",\"estado_civil\",\"telefono\",\"correo_electronico\",\"grado_instruccion\",\"password\"]","code":400}');
        $this->model->incluirEntrenador([
            'cedula' => '',
            'nombres' => '',
            'apellidos' => '',
            'genero' => '',
            'fecha_nacimiento' => '',
            'lugar_nacimiento' => '',
            'estado_civil' => '',
            'telefono' => '',
            'correo_electronico' => '',
            'grado_instruccion' => '',
            'password' => ''
        ]);
    }

    public function test_modificar_entrenador_vacio(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Los siguientes campos faltan: [\"nombres\",\"apellidos\",\"cedula\",\"cedula_original\",\"genero\",\"fecha_nacimiento\",\"lugar_nacimiento\",\"estado_civil\",\"telefono\",\"correo_electronico\",\"grado_instruccion\"]","code":400}');
        $this->model->modificarEntrenador([
            'cedula_original' => '',
            'cedula' => '',
            'nombres' => '',
            'apellidos' => '',
            'genero' => '',
            'fecha_nacimiento' => '',
            'lugar_nacimiento' => '',
            'estado_civil' => '',
            'telefono' => '',
            'correo_electronico' => '',
            'grado_instruccion' => ''
        ]);
    }

    public function test_obtener_entrenador_vacio(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('{"error":"La c\u00e9dula debe tener al menos 7 n\u00fameros","code":400}');
        $this->model->obtenerEntrenador(['id' => Cipher::aesEncrypt('')]);
    }

    public function test_eliminar_entrenador_vacio(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Los siguientes campos faltan: [\"cedula\"]","code":400}');
        $this->model->eliminarEntrenador(['id' => Cipher::aesEncrypt('')]);
    }

    public function test_modificar_entrenador_invalido(): void
    {
        $cedOrigEnc = Cipher::aesEncrypt('9999999');
        $this->db->method('query')->willReturn(false);

        $this->expectException(\InvalidArgumentException::class);
        $this->model->modificarEntrenador([
            'cedula_original' => $cedOrigEnc,
            'cedula' => '9999999',
            'nombres' => '',
            'apellidos' => '\$%\$%',
            'genero' => 'Masculinos',
            'fecha_nacimiento' => '19902-01-01',
            'lugar_nacimiento' => 'Ciudad',
            'estado_civil' => 'Solteros',
            'telefono' => '0412-0000000',
            'correo_electronico' => 'x@y.com',
            'grado_instruccion' => 'Licenciatura'
        ]);
    }

    public function test_eliminar_entrenador_exitoso(): void
    {
        $enc = Cipher::aesEncrypt('3145612');

        $this->db->expects($this->once())->method('beginTransaction');
        $this->db->expects($this->once())->method('commit');
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            true,
            true,
            true,
            true
        );

        $resp = $this->model->eliminarEntrenador(['cedula' => $enc]);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('mensaje', $resp);
        $this->assertEquals("El entrenador se eliminó correctamente", $resp["mensaje"]);
    }

    public function test_eliminar_entrenador_no_existe(): void
    {
        $enc = Cipher::aesEncrypt('1234567');
        $this->db->method('query')->willReturn(false);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El entrenador ingresado no existe","code":404}');
        $this->model->eliminarEntrenador(['cedula' => $enc]);
    }

    public function test_listado_entrenadores(): void
    {
        $this->db->method('query')->willReturn([]);
        $resp = $this->model->listadoEntrenadores();
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('entrenadores', $resp);
    }

    public function test_listado_grados_instruccion_exitoso(): void
    {
        $this->db->method('query')->willReturn([
            ['grado_instruccion' => 'Licenciatura'],
            ['grado_instruccion' => 'TSU']
        ]);
        $resp = $this->model->listadoGradosInstruccion();
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('grados', $resp);
        $this->assertIsArray($resp['grados']);
    }

}
