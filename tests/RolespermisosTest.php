<?php

namespace Tests\Feature;

use Gymsys\Core\Database;
use Gymsys\Model\Rolespermisos;
use Gymsys\Utils\Cipher;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class RolespermisosTest extends TestCase
{
    private Rolespermisos $model;
    private Database|MockObject $db;

    protected function setUp(): void
    {
        Rolespermisos::limpiarPermisosCache();
        $this->db = $this->createMock(Database::class);
        $this->model = new Rolespermisos($this->db);
    }

    protected function tearDown(): void
    {
        Rolespermisos::limpiarPermisosCache();
    }

    private function permisosBase(): array
    {
        return [
            "centrenadores" => 1,
            "rentrenadores" => 0,
            "uentrenadores" => 1,
            "dentrenadores" => 0,
            "catletas" => 0,
            "ratletas" => 0,
            "uatletas" => 0,
            "datletas" => 1,
            "crolespermisos" => 0,
            "rrolespermisos" => 1,
            "urolespermisos" => 1,
            "drolespermisos" => 1,
            "casistencias" => 1,
            "rasistencias" => 1,
            "uasistencias" => 1,
            "dasistencias" => 1,
            "ceventos" => 0,
            "reventos" => 0,
            "ueventos" => 0,
            "deventos" => 0,
            "cmensualidad" => 1,
            "rmensualidad" => 1,
            "umensualidad" => 1,
            "dmensualidad" => 1,
            "cwada" => 0,
            "rwada" => 0,
            "uwada" => 0,
            "dwada" => 0,
            "creportes" => 1,
            "rreportes" => 1,
            "rbitacora" => 1,
        ];
    }

    public function test_consultar_rol_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            true,
            [
                [
                    'nombre_rol' => 'Admin',
                    'id_modulo' => 3,
                    'crear' => 1,
                    'leer' => 1,
                    'actualizar' => 1,
                    'eliminar' => 1,
                    'nombre_modulo' => 'rolespermisos'
                ],
                [
                    'nombre_rol' => 'Admin',
                    'id_modulo' => 5,
                    'crear' => 1,
                    'leer' => 1,
                    'actualizar' => 0,
                    'eliminar' => 0,
                    'nombre_modulo' => 'eventos'
                ]
            ]
        );

        $resp = $this->model->obtenerRol(['id' => Cipher::aesEncrypt('30')]);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('rol', $resp);
        $this->assertIsArray($resp['rol']);
        $this->assertCount(2, $resp['rol']);
    }

    public function test_consultar_rol_no_existe(): void
    {
        $this->db->method('query')->willReturn(false);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El rol ingresado no existe","code":404}');
        $this->model->obtenerRol(['id' => Cipher::aesEncrypt('999')]);
    }

    public function test_consultar_rol_invalido(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El ID de rol ingresado no es v\u00e1lido","code":400}');
        $this->model->obtenerRol(['id' => Cipher::aesEncrypt('$$$$')]);
    }

    public function test_incluir_rol_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            false,
            true,
            true
        );

        $data = ['nombre_rol' => 'Administrador'] + $this->permisosBase();
        $resp = $this->model->incluirRol($data);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('mensaje', $resp);
        $this->assertEquals('El rol se agregó exitosamente', $resp['mensaje']);
    }

    public function test_incluir_rol_ya_existe(): void
    {
        $this->db->method('query')->willReturn(true);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Ya existe un rol con el nombre introducido","code":400}');
        $data = ['nombre_rol' => 'Administrador'] + $this->permisosBase();
        $this->model->incluirRol($data);
    }

    public function test_incluir_rol_invalido(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('{"error":"Solo letras y espacios (3-50 caracteres)","code":400}');
        $data = ['nombre_rol' => ''] + $this->permisosBase();
        $this->model->incluirRol($data);
    }

    public function test_modificar_rol_exitoso(): void
    {
        $this->db->method('query')->willReturn(true);
        $data = ['id_rol' => Cipher::aesEncrypt('45'), 'nombre_rol' => 'Rol Modificable'] + $this->permisosBase();
        $resp = $this->model->modificarRol($data);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('mensaje', $resp);
        $this->assertEquals('El rol se modificó exitosamente', $resp['mensaje']);
    }

    public function test_modificar_rol_no_existe(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(false);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"No existe el rol introducido","code":404}');
        $data = ['id_rol' => Cipher::aesEncrypt('38'), 'nombre_rol' => 'Rol Modificable'] + $this->permisosBase();
        $this->model->modificarRol($data);
    }

    public function test_modificar_rol_invalido(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('{"error":"Solo letras y espacios (3-50 caracteres)","code":400}');
        $data = ['id_rol' => Cipher::aesEncrypt('45'), 'nombre_rol' => 'Rol2-$Modificable'] + $this->permisosBase();
        $this->model->modificarRol($data);
    }
    public function test_eliminar_rol_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            true,
            true
        );
        $resp = $this->model->eliminarRol(['id_rol' => Cipher::aesEncrypt('49')]);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('mensaje', $resp);
        $this->assertEquals('El rol fue eliminado exitosamente', $resp['mensaje']);
    }

    public function test_eliminar_rol_no_existe(): void
    {
        $this->db->method('query')->willReturn(false);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El rol introducido no existe","code":404}');
        $this->model->eliminarRol(['id_rol' => Cipher::aesEncrypt('3213')]);
    }

    public function test_eliminar_rol_invalido(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El valor ingresado no es un n\u00famero entero v\u00e1lido","code":400}');
        $this->model->eliminarRol(['id_rol' => Cipher::aesEncrypt('sdad')]);
    }

    public function test_obtener_rol_vacio(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El ID de rol ingresado no es v\u00e1lido","code":400}');
        $this->model->obtenerRol(['id' => Cipher::aesEncrypt('')]);
    }

    public function test_incluir_rol_vacio(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('{"error":"Solo letras y espacios (3-50 caracteres)","code":400}');
        $data = ['nombre_rol' => ''] + array_fill_keys(array_keys($this->permisosBase()), '');
        $this->model->incluirRol($data);
    }

    public function test_modificar_rol_vacio(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('{"error":"Solo letras y espacios (3-50 caracteres)","code":400}');
        $data = ['id_rol' => Cipher::aesEncrypt(''), 'nombre_rol' => ''] + array_fill_keys(array_keys($this->permisosBase()), '');
        $this->model->modificarRol($data);
    }

    public function test_eliminar_rol_vacio(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El valor ingresado no es un n\u00famero entero v\u00e1lido","code":400}');
        $this->model->eliminarRol(['id_rol' => Cipher::aesEncrypt('')]);
    }

    public function test_listado_roles(): void
    {
        $this->db->method('query')->willReturn([
            ['id_rol' => 1, 'nombre' => 'Admin'],
            ['id_rol' => 2, 'nombre' => 'Editor'],
        ]);
        $resp = $this->model->listadoRoles();
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('roles', $resp);
        $this->assertIsArray($resp['roles']);
        $this->assertCount(2, $resp['roles']);
    }


    public function test_asignar_rol_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            true,
            true,
            true
        );
        $resp = $this->model->asignarRol([
            'cedula' => '1328547',
            'id_rol_asignar' => Cipher::aesEncrypt('45')
        ]);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('mensaje', $resp);
        $this->assertEquals("El rol del usuario '1328547' fue cambiado exitosamente", $resp['mensaje']);
    }

    public function test_asignar_rol_no_existe(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            true,
            false
        );
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El rol ingresado no existe","code":404}');
        $this->model->asignarRol([
            'cedula' => '1328547',
            'id_rol_asignar' => Cipher::aesEncrypt('863')
        ]);
    }
    public function test_asignar_rol_usuario_no_existe(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            false,
            true
        );
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El usuario ingresado no existe","code":404}');
        $this->model->asignarRol([
            'cedula' => '13285475',
            'id_rol_asignar' => Cipher::aesEncrypt('3')
        ]);
    }
    public function test_asignar_rol_invalido(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El valor ingresado no es un n\u00famero entero v\u00e1lido","code":400}');
        $this->model->asignarRol([
            'cedula' => '133',
            'id_rol_asignar' => Cipher::aesEncrypt('')
        ]);
    }


    public function test_consultar_rol_usuario_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            true,
            [
                'nombre_rol' => 'Admin',
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'id_rol' => 3
            ]
        );
        $resp = $this->model->obtenerRolUsuario(['id' => '1328547']);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('rol', $resp);
        $this->assertIsArray($resp['rol']);
        $this->assertCount(5, $resp['rol']);
    }

    public function test_consultar_rol_usuario_no_existe(): void
    {
        $this->db->method('query')->willReturn(false);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"No existe el usuario introducido","code":404}');
        $this->model->obtenerRolUsuario(['id' => '1343547']);
    }

    public function test_consultar_rol_usuario_invalido(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('{"error":"La c\u00e9dula debe tener al menos 7 n\u00fameros","code":400}');
        $this->model->obtenerRolUsuario(['id' => '1435s47']);
    }
}
