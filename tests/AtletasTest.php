<?php
use PHPUnit\Framework\TestCase;

class AtletasTest extends TestCase
{
    private $atleta;

    protected function setUp(): void
    {
        $this->atleta = new Atleta();
    }

    public function testIncluirAtletaExitoso() // Caso 1
    {
        $datosFormulario = [
            'cedula' => '5560233',
            'nombres' => 'Alejandro',
            'apellidos' => 'Martinez',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1990-01-01',
            'lugar_nacimiento' => 'Ciudad',
            'estado_civil' => 'Divorciado',
            'telefono' => '04265538456',
            'correo' => 'aleale@example.com',
            'peso' => '62',
            'altura' => '181',
            'entrenador_asignado' => '22222222',
            'tipo_atleta' => '1',
            'password' => 'Password123$'
        ];
        // Ejecutar el método incluir_atleta
        $respuesta = $this->atleta->incluir_atleta($datosFormulario);
        // Verificar que la respuesta sea exitosa
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
    }
    public function testIncluirAtletaYaExiste() // Caso 1
    {
        $datosFormulario = [
            'cedula' => '5560233',
            'nombres' => 'Alejandro',
            'apellidos' => 'Martinez',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1990-01-01',
            'lugar_nacimiento' => 'Ciudad',
            'estado_civil' => 'Divorciado',
            'telefono' => '04265538456',
            'correo' => 'aleale@example.com',
            'peso' => '62',
            'altura' => '181',
            'entrenador_asignado' => '22222222',
            'tipo_atleta' => '1',
            'password' => 'Password123$'
        ];
        // Ejecutar el método incluir_atleta
        $respuesta = $this->atleta->incluir_atleta($datosFormulario);
        // Verificar que la respuesta sea que ya existe
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertEquals("Ya existe un atleta con esta cedula", $respuesta['mensaje']);
    }
    public function testIncluirAtletaNoValido() // Caso 1
    {
        $datosFormulario = [
            'cedula' => '5560233a.',
            'nombres' => 'Alejandro22',
            'apellidos' => 'Martinez--',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1990-01-44',
            'lugar_nacimiento' => 'Ciudad',
            'estado_civil' => '',
            'telefono' => '0426-5538456',
            'correo' => 'aleale@example.com',
            'peso' => 'full',
            'altura' => '181',
            'entrenador_asignado' => '',
            'tipo_atleta' => '1',
            'password' => 'contra1122'
        ];
        // Ejecutar el método incluir_atleta
        $respuesta = $this->atleta->incluir_atleta($datosFormulario);
        // Verificar que la respuesta sea un array de los datos no validos
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertIsArray($respuesta['mensaje']);
    }
    public function testEliminarAtleta() // Caso 1
    {
        // Ejecutar el método eliminar_atleta
        $respuesta = $this->atleta->eliminar_atleta("5560233");
        // Verificar que la respuesta sea exitosa
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
    }
    public function testEliminarAtletaNoExiste() // Caso 1
    {
        // Ejecutar el método eliminar_atleta
        $respuesta = $this->atleta->eliminar_atleta("55602332");
        // Verificar la respuesta
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertEquals("No existe ningún atleta con esta cedula", $respuesta['mensaje']);
    }
    public function testEliminarAtletaNoValido() // Caso 1
    {
        // Ejecutar el método eliminar_atleta
        $respuesta = $this->atleta->eliminar_atleta("V-55602332");
        // Verificar que la respuesta sea no valida
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertEquals("La cédula debe tener al menos 7 números", $respuesta['mensaje']);
    }
    public function testObtenerAtleta()
    {
        $respuesta = $this->atleta->obtener_atleta("1328547");
        // Verificar que la respuesta indique que el atleta existe
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
        $this->assertIsArray($respuesta['atleta']);
    }
    public function testObtenerAtletaNoExiste()
    {
        $respuesta = $this->atleta->obtener_atleta("13285472");
        // Verificar que la respuesta indique que el atleta no existe
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertEquals("No se encontró el atleta", $respuesta['mensaje']);
    }
    public function testObtenerAtletaNoValido()
    {
        $respuesta = $this->atleta->obtener_atleta("V-13285472");
        // Verificar que la respuesta indique que el atleta no es valido
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertEquals("La cédula debe tener al menos 7 números", $respuesta['mensaje']);
    }
    public function testModificarAtleta() // Caso 1
    {
        $datosFormulario = [
            'cedula' => '1328547',
            'nombres' => 'Leoleo',
            'apellidos' => 'Herrera',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1990-01-01',
            'lugar_nacimiento' => 'Ciudad',
            'estado_civil' => 'Soltero',
            'telefono' => '04265538456',
            'correo' => 'leoleole@example.com',
            'peso' => '62',
            'altura' => '178',
            'entrenador_asignado' => '22222222',
            'tipo_atleta' => '1',
            'password' => 'Password123$'
        ];
        // Ejecutar el método modificar_atleta
        $respuesta = $this->atleta->modificar_atleta($datosFormulario);
        // Verificar que la respuesta sea que ya existe
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
    }
    public function testModificarAtletaNoExiste() // Caso 1
    {
        $datosFormulario = [
            'cedula' => '13285427',
            'nombres' => 'Leoleo',
            'apellidos' => 'Herrera',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1990-01-01',
            'lugar_nacimiento' => 'Ciudad',
            'estado_civil' => 'Soltero',
            'telefono' => '04265538456',
            'correo' => 'leoleole@example.com',
            'peso' => '62',
            'altura' => '178',
            'entrenador_asignado' => '22222222',
            'tipo_atleta' => '1',
            'password' => 'Password123$'
        ];
        // Ejecutar el método modificar_atleta
        $respuesta = $this->atleta->modificar_atleta($datosFormulario);
        // Verificar que la respuesta sea que no existe
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertEquals("No existe ningun atleta con esta cedula", $respuesta['mensaje']);
    }
    public function testModificarAtletaNoValido() // Caso 1
    {
        $datosFormulario = [
            'cedula' => 'V-13285427',
            'nombres' => 'Leoleo22',
            'apellidos' => 'Herreras.',
            'genero' => 'Masculino',
            'fecha_nacimiento' => '1990-01-01',
            'lugar_nacimiento' => 'Ciudad',
            'estado_civil' => 'Soltero',
            'telefono' => '042655322',
            'correo' => 'leoleole@example',
            'peso' => '',
            'altura' => 'alto',
            'entrenador_asignado' => '22222222',
            'tipo_atleta' => '1',
            'password' => 'Password12'
        ];
        // Ejecutar el método modificar_atleta
        $respuesta = $this->atleta->modificar_atleta($datosFormulario);
        // Verificar que la respuesta sea un array con los datos no validos
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertIsArray($respuesta['mensaje']);
    }
    public function testObtenerTiposDeAtleta()
    {
        $respuesta = $this->atleta->obtenerTiposAtleta();
        // Verificar que la respuesta devuelva un array con los tipos de atletas
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
        $this->assertIsArray($respuesta['tipos']);
    }
    public function testObtenerEntrenadores()
    {
        $respuesta = $this->atleta->obtenerEntrenadores();
        // Verificar que la respuesta devuelva un array con los entrenadores para ser asignados
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
        $this->assertIsArray($respuesta['entrenadores']);
    }
    public function testIncluirRepresentante()
    {
        $datosFormulario = [
            'cedula' => '15787522',
            'nombre' => 'Leonardo',
            'telefono' => 'Leonardo',
            'parentesco' => 'Leonardo',
        ];
        $respuesta = $this->atleta->incluirRepresentante($datosFormulario["cedula"],$datosFormulario["nombre"],$datosFormulario["telefono"],$datosFormulario["parentesco"]);
        // Verificar que se incluya el representante
        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
    }
}