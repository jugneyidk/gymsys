<?php
use PHPUnit\Framework\TestCase;

class RolespermisosTest extends TestCase
{
    private $rolespermisos;

    protected function setUp(): void
    {
        $this->rolespermisos = new Roles();
    }
    public function testConsultarRolExitoso() // Caso 1
    {
        $respuesta = $this->rolespermisos->consultar_rol("30");

        // Verificar que la respuesta sea exitosa y devuelva los detalles del rol
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta["ok"]);
        $this->assertIsArray($respuesta["permisos"]);
    }
    public function testConsultarRolNoValido() // Caso 1
    {
        $respuesta = $this->rolespermisos->consultar_rol("30s3.h");

        // Verificar que la respuesta sea un error
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta["ok"]);
        $this->assertEquals("No se encontró el rol", $respuesta["mensaje"]);
    }
    public function testIncluirRolExitoso() // Caso 1
    {
        $permisos = [
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
        $respuesta = $this->rolespermisos->incluir_rol("Administrador", $permisos);
        // Verificar que la respuesta sea exitosa
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta["ok"]);
    }
    public function testIncluirRolYaExiste() // Caso 1
    {
        $permisos = [
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
        $respuesta = $this->rolespermisos->incluir_rol("Administrador", $permisos);
        // Verificar que la respuesta sea que el rol ya existe
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta["ok"]);
        $this->assertEquals("Ya existe un rol con este nombre", $respuesta["mensaje"]);
    }
    public function testIncluirRolNoValido() // Caso 1
    {
        $permisos = [
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
        $respuesta = $this->rolespermisos->incluir_rol("Administrador12-", $permisos);
        // Verificar que la respuesta sea que los datos no son validos
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta["ok"]);
        $this->assertEquals("Solo letras y espacios (3-50 caracteres)", $respuesta["mensaje"]);
    }
    public function testEliminarRolExitoso() // Caso 1
    {
        $respuesta = $this->rolespermisos->eliminar_rol("49");
        // Verificar que la respuesta sea que el rol se eliminó
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta["ok"]);
    }
    public function testEliminarRolNoExiste() // Caso 1
    {
        $respuesta = $this->rolespermisos->eliminar_rol("3213");
        // Verificar que la respuesta sea que el rol no existe
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta["ok"]);
        $this->assertEquals("No existe este rol", $respuesta["mensaje"]);
    }
    public function testEliminarRolNoValido() // Caso 1
    {
        $respuesta = $this->rolespermisos->eliminar_rol("3sad.23");
        // Verificar que la respuesta sea que el rol no existe
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta["ok"]);
        $this->assertEquals("No existe este rol", $respuesta["mensaje"]);
    }
    public function testListadoRoles() // Caso 1
    {
        $respuesta = $this->rolespermisos->listado_roles();
        // Verificar que la respuesta sea un array con los roles
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta["ok"]);
        $this->assertIsArray($respuesta["roles"]);
    }
    public function testModificarRolExitoso() // Caso 1
    {
        $permisos = [
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
        $respuesta = $this->rolespermisos->modificar_rol("45", "Rol Modificable", $permisos);
        // Verificar que la respuesta sea exitosa
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta["ok"]);
    }
    public function testModificarRolNoExiste() // Caso 1
    {
        $permisos = [
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
        ];
        $respuesta = $this->rolespermisos->modificar_rol("38", "Rol Modificable", $permisos);
        // Verificar que la respuesta sea que no existe este rol
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta["ok"]);
        $this->assertEquals("No existe este rol", $respuesta["mensaje"]);
    }
    public function testModificarRolNoValido() // Caso 1
    {
        $permisos = [
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
        ];
        $respuesta = $this->rolespermisos->modificar_rol("45", "Rol2-Modificable", $permisos);
        // Verificar que la respuesta sea que no existe este rol
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta["ok"]);
        $this->assertEquals("Solo letras y espacios (3-50 caracteres)", $respuesta["mensaje"]);
    }
}