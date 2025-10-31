<?php

namespace Tests\Feature;

use Exception;
use Gymsys\Model\Login;
use Gymsys\Core\Database;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class LoginTest extends TestCase
{
    private Login $model;
    private MockObject $db;

    protected function setUp(): void
    {
        $this->db = $this->createMock(Database::class);
        $this->model = new Login($this->db);
    }

    public function test_login_exitoso_web(): void
    {
        $_SERVER['HTTP_X_CLIENT_TYPE'] = 'web';
        $hash = password_hash('Diego123*', PASSWORD_DEFAULT);
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            ['id_rol' => 2, 'password' => $hash],
            true
        );

        $resp = $this->model->authUsuario(
            '22222222',
            'Diego123*'
        );
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('auth', $resp);
        $this->assertTrue($resp['auth']);
        $this->assertArrayHasKey('accessToken', $resp);
        $this->assertArrayNotHasKey('refreshToken', $resp);
    }

    public function test_login_exitoso_movil(): void
    {
        $_SERVER['HTTP_X_CLIENT_TYPE'] = 'mobile';
        $hash = password_hash('Diego123*', PASSWORD_DEFAULT);
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            ['id_rol' => 2, 'password' => $hash],
            true
        );

        $resp = $this->model->authUsuario('22222222', 'Diego123*');
        $this->assertIsArray($resp);
        $this->assertArrayHasKey('auth', $resp);
        $this->assertTrue($resp['auth']);
        $this->assertArrayHasKey('accessToken', $resp);
        $this->assertArrayHasKey('refreshToken', $resp);
    }

    public function test_login_incorrecto(): void
    {
        $hash = password_hash('Diego123*', PASSWORD_DEFAULT);
        $this->db->method('query')->willReturn(['id_rol' => 2, 'password' => $hash]);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('{"error":"Los datos de usuario ingresado son incorrectos","code":400}');
        $this->model->authUsuario('22222222', 'Diego123*s');
    }
    
    public function test_login_no_valido(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('{"error":"La c\u00e9dula debe tener al menos 7 n\u00fameros","code":400}');
        $this->model->authUsuario('22223', '-die go12..3');
    }
    public function test_login_vacio(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('{"error":"La c\u00e9dula debe tener al menos 7 n\u00fameros","code":400}');
        $this->model->authUsuario('', '');
    }
}
