<?php
namespace Tests\Feature;

use Gymsys\Core\Database;
use Gymsys\Model\Rolespermisos;
use Gymsys\Utils\Cipher;
use PHPUnit\Framework\TestCase;

final class RolespermisosTest extends TestCase
{
 private Rolespermisos $model;
    private Database $db;

    public static function setUpBeforeClass(): void
    {
        $_ENV['ENVIRONMENT'] = 'testing';
        $_ENV['SECURE_DB']   = 'secure_db';
        $_ENV['AES_KEY']     = '0123456789abcdef0123456789abcdef'; // 32 chars
        $_ENV['JWT_SECRET']  = 'jwt_secret_for_tests_only';
        $_ENV['JWT_REFRESH_SECRET'] = 'jwt_refresh_secret_for_tests_only';

        if (!defined('ID_USUARIO')) {
            define('ID_USUARIO', '22222222');
        }
    }

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
    private function enc(string $v): string
    {
        return Cipher::aesEncrypt($v);
    }

    private function permisosBase(): array
    {
        return [
            "centrenadores" => 1, "rentrenadores" => 0, "uentrenadores" => 1, "dentrenadores" => 0,
            "catletas" => 0, "ratletas" => 0, "uatletas" => 0, "datletas" => 1,
            "crolespermisos" => 0, "rrolespermisos" => 1, "urolespermisos" => 1, "drolespermisos" => 1,
            "casistencias" => 1, "rasistencias" => 1, "uasistencias" => 1, "dasistencias" => 1,
            "ceventos" => 0, "reventos" => 0, "ueventos" => 0, "deventos" => 0,
            "cmensualidad" => 1, "rmensualidad" => 1, "umensualidad" => 1, "dmensualidad" => 1,
            "cwada" => 0, "rwada" => 0, "uwada" => 0, "dwada" => 0,
            "creportes" => 1, "rreportes" => 1,
            "rbitacora" => 1,
        ];
    }

    public function test_consultar_rol_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            true,
            [
                [
                    'nombre_rol' => 'Admin', 'id_modulo' => 3,
                    'crear' => 1, 'leer' => 1, 'actualizar' => 1, 'eliminar' => 1,
                    'nombre_modulo' => 'rolespermisos'
                ],
                [
                    'nombre_rol' => 'Admin', 'id_modulo' => 5,
                    'crear' => 1, 'leer' => 1, 'actualizar' => 0, 'eliminar' => 0,
                    'nombre_modulo' => 'eventos'
                ]
            ]
        );

        $resp = $this->model->obtenerRol(['id' => $this->enc('30')]);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('rol', $resp);
        $this->assertIsArray($resp['rol']);
        $this->assertCount(2, $resp['rol']);
    }

    public function test_consultar_rol_invalido(): void
    {
        $this->expectException(\Throwable::class);
        $this->model->obtenerRol(['id' => $this->enc('xx-yy')]);
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
    }

    public function test_incluir_rol_ya_existe(): void
    {
        $this->db->method('query')->willReturn(true);
        $this->expectException(\Throwable::class);
        $data = ['nombre_rol' => 'Administrador'] + $this->permisosBase();
        $this->model->incluirRol($data);
    }

    public function test_incluir_rol_nombre_invalido(): void
    {
        $this->expectException(\Throwable::class);
        $data = ['nombre_rol' => 'Admin$-123'] + $this->permisosBase();
        $this->model->incluirRol($data);
    }

    public function test_eliminar_rol_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            true,
            true
        );
        $resp = $this->model->eliminarRol(['id_rol' => $this->enc('49')]);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('mensaje', $resp);
    }

    public function test_eliminar_rol_no_existe(): void
    {
        $this->db->method('query')->willReturn(false);
        $this->expectException(\Throwable::class);
        $this->model->eliminarRol(['id_rol' => $this->enc('3213')]);
    }

    public function test_eliminar_rol_invalido(): void
    {
        $this->expectException(\Throwable::class);
        $this->model->eliminarRol(['id_rol' => $this->enc('3sad.23')]);
    }

    public function test_listado_roles_exitoso(): void
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

    public function test_modificar_rol_exitoso(): void
    {
        $this->db->method('query')->willReturn(true);
        $data = ['id_rol' => $this->enc('45'), 'nombre_rol' => 'Rol Modificable'] + $this->permisosBase();
        $resp = $this->model->modificarRol($data);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('mensaje', $resp);
    }

    public function test_modificar_rol_no_existe(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(false);
        $this->expectException(\Throwable::class);
        $data = ['id_rol' => $this->enc('38'), 'nombre_rol' => 'Rol Modificable'] + $this->permisosBase();
        $this->model->modificarRol($data);
    }

    public function test_modificar_rol_nombre_invalido(): void
    {
        $this->expectException(\Throwable::class);
        $data = ['id_rol' => $this->enc('45'), 'nombre_rol' => 'Rol2-Modificable'] + $this->permisosBase();
        $this->model->modificarRol($data);
    }

    public function test_asignar_rol_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            true,
            true,
            true
        );
        $resp = $this->model->asignarRol(['cedula' => '1328547', 'id_rol_asignar' => $this->enc('45')]);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('mensaje', $resp);
    }

    public function test_asignar_rol_no_valido(): void
    {
        $this->expectException(\Throwable::class);
        $this->model->asignarRol(['cedula' => '133', 'id_rol_asignar' => $this->enc('')]);
    }

    public function test_asignar_rol_no_existe(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            true,
            false
        );
        $this->expectException(\Throwable::class);
        $this->model->asignarRol(['cedula' => '1328547', 'id_rol_asignar' => $this->enc('863')]);
    }

    public function test_consultar_rol_usuario_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            true,
            ['nombre_rol' => 'Admin', 'nombre' => 'Juan', 'apellido' => 'Pérez', 'id_rol' => 3]
        );
        $resp = $this->model->obtenerRolUsuario(['id' => '1328547']);
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('rol', $resp);
        $this->assertIsArray($resp['rol']);
    }

    public function test_consultar_rol_usuario_no_existe(): void
    {
        $this->db->method('query')->willReturn(false);
        $this->expectException(\Throwable::class);
        $this->model->obtenerRolUsuario(['id' => '1343547']);
    }

    public function test_consultar_rol_usuario_no_valido(): void
    {
        $this->expectException(\Throwable::class);
        $this->model->obtenerRolUsuario(['id' => '1435s47']);
    }
}
