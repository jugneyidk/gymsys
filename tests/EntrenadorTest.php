<?php
use PHPUnit\Framework\TestCase;

class EntrenadorTest extends TestCase
{
    private $entrenadores;

    protected function setUp(): void
    {
        $this->entrenadores = new Entrenador();
    }

    public function testIncluirEntrenadorExitoso() // Caso 1
    {
        $datosFormulario = [
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
        ];

        // Ejecutar el método incluir_entrenador
        $respuesta = $this->entrenadores->incluir_entrenador($datosFormulario);
        // Verificar que la respuesta sea exitosa
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
    }

    public function testIncluirEntrenadorYaExiste() //Caso 1.5
    {
        $datosFormulario = [
            'cedula' => '22222222',
            'nombres' => 'Dolor deleniti non l',
            'apellidos' => 'Ad magnam qui repreh',
            'genero' => 'Masculino',
            'estado_civil' => 'Casado',
            'fecha_nacimiento' => '2018-12-01',
            'lugar_nacimiento' => 'Officiis ducimus co',
            'telefono' => '04721685737',
            'correo_electronico' => 'bifuliki@mailinator.com',
            'grado_instruccion' => 'Quia qui occaecat om',
            'password' => 'Pa$$0rd!'
        ];
        $respuesta = $this->entrenadores->incluir_entrenador($datosFormulario);
        // Verificar que la respuesta indique que el entrenador ya existe
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertEquals("Ya existe un entrenador con esta cedula", $respuesta['mensaje']);
    }
    public function testIncluirEntrenadorNoValido() //Caso 1.5
    {
        $datosFormulario = [
            'cedula' => '22222222',
            'nombres' => 'Dolor deleniti non l',
            'apellidos' => 'Ad magnam qui repreh',
            'genero' => 'Masculino',
            'estado_civil' => 'Casada',
            'fecha_nacimiento' => '2018-33-01',
            'lugar_nacimiento' => 'Officiis ducimus co',
            'telefono' => '0472a1685737',
            'correo_electronico' => 'bifuliki@mailinator.com',
            'grado_instruccion' => 'Quia qui occaecat om',
            'password' => 'Pa$$0rd!'
        ];
        $respuesta = $this->entrenadores->incluir_entrenador($datosFormulario);
        // Verificar que la respuesta indique que el entrenador no es válido
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertIsArray($respuesta['mensaje']);
    }
    public function testEliminarEntrenadorExitoso()
    {
        $respuesta = $this->entrenadores->eliminar_entrenador("3145612");
        // Verificar que la respuesta indique que el entrenador se eliminó
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
    }
    public function testEliminarEntrenadorNoExistente()
    {
        $respuesta = $this->entrenadores->eliminar_entrenador("31456122");
        // Verificar que la respuesta indique que el entrenador no existe
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertEquals("No existe ningún entrenador con esta cedula", $respuesta['mensaje']);
    }
    public function testEliminarEntrenadorNoValido()
    {
        $respuesta = $this->entrenadores->eliminar_entrenador("hola123");
        // Verificar que la respuesta indique que el entrenador no es válido
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertEquals("La cédula debe tener al menos 7 números", $respuesta['mensaje']);
    }
    public function testObtenerEntrenadorExitoso()
    {
        $respuesta = $this->entrenadores->obtener_entrenador("22222222");
        // Verificar que la respuesta indique que el entrenador existe
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
        $this->assertIsArray($respuesta['entrenador']);
    }
    public function testObtenerEntrenadorNoValido()
    {
        $respuesta = $this->entrenadores->obtener_entrenador("22222222a3");
        // Verificar que la respuesta indique que el entrenador no es válido
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertEquals("La cédula debe tener al menos 7 números", $respuesta['mensaje']);
    }
    public function testObtenerEntrenadorNoExistente()
    {
        $respuesta = $this->entrenadores->obtener_entrenador("12355643");
        // Verificar que la respuesta indique que el entrenador no existe
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertEquals("No se encontró el entrenador", $respuesta['mensaje']);
    }
    public function testModificarEntrenadorExitoso()
    {
        $datosFormulario = [
            'cedula' => '8676719',
            'nombres' => 'Marcos',
            'apellidos' => 'Perez',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1990-01-04',
            'lugar_nacimiento' => 'Ciudad',
            'estado_civil' => 'Casado',
            'telefono' => '04122131222',
            'correo_electronico' => 'juanperez@example.com',
            'grado_instruccion' => 'Licenciaturas',
            'password' => 'Password123$'
        ];
        $respuesta = $this->entrenadores->modificar_entrenador($datosFormulario);
        // Verificar que la respuesta indique que el entrenador se modificó
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
    }
    public function testModificarEntrenadorNoExistente()
    {
        $datosFormulario = [
            'cedula' => '867671229',
            'nombres' => 'Marcos',
            'apellidos' => 'Perez',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1990-01-04',
            'lugar_nacimiento' => 'Ciudad',
            'estado_civil' => 'Casado',
            'telefono' => '04122131222',
            'correo_electronico' => 'juanperez@example.com',
            'grado_instruccion' => 'Licenciaturas',
            'password' => 'Password123$'
        ];
        $respuesta = $this->entrenadores->modificar_entrenador($datosFormulario);
        // Verificar que la respuesta indique que el entrenador no existe
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertEquals("No existe ningún entrenador con esta cedula", $respuesta['mensaje']);
    }
    public function testModificarEntrenadorNoValido()
    {
        $datosFormulario = [
            'cedula' => '8676719',
            'nombres' => 'Marcos',
            'apellidos' => 'Perez',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1990-01-99',
            'lugar_nacimiento' => 'Ciudades234.',
            'estado_civil' => 'Casados',
            'telefono' => '04122131222',
            'correo_electronico' => 'juanperez@example.com',
            'grado_instruccion' => 'Licenciaturas',
            'password' => 'Password123$'
        ];
        $respuesta = $this->entrenadores->modificar_entrenador($datosFormulario);
        // Verificar que la respuesta indique que el entrenador no es valido
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertIsArray($respuesta['mensaje']);
    }
    public function testListadoEntrenadores()
    {
        $respuesta = $this->entrenadores->listado_entrenador();
        // Verificar que la respuesta devuelva un array de entrenadores
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
        $this->assertIsArray($respuesta['respuesta']);
    }
}