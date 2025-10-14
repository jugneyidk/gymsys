<?php

namespace Tests\Feature;

use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use Gymsys\Model\Eventos;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class EventosTest extends TestCase
{
    private Eventos $eventos;
    private Database|MockObject $db;

    private function enc(string $v): string
    {
        return Cipher::aesEncrypt($v);
    }

    protected function setUp(): void
    {
        $this->db = $this->createMock(Database::class);
        $this->eventos = new Eventos($this->db);
    }

    public function test_incluir_evento_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls([], true);
        $r = $this->eventos->incluirEvento([
            'nombre' => 'Campeonato Nacional',
            'lugar_competencia' => 'Ciudad Deportiva Lara',
            'fecha_inicio' => '2024-12-01',
            'fecha_fin' => '2024-12-05',
            'categoria' => $this->enc('6'),
            'subs' => $this->enc('6'),
            'tipo_competencia' => $this->enc('9'),
        ]);
        $this->assertIsArray($r);
        $this->assertArrayHasKey('mensaje', $r);
        $this->assertEquals('El evento se registró exitosamente', $r['mensaje']);
    }

    public function test_incluir_evento_no_valido(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Los siguientes campos faltan: [\"fecha_inicio\"]","code":400}');
        $this->eventos->incluirEvento([
            'nombre' => 'Campeonato.',
            'lugar_competencia' => 'CD Lara',
            'fecha_inicio' => '',
            'fecha_fin' => 'final',
            'categoria' => $this->enc('6'),
            'subs' => $this->enc('6'),
            'tipo_competencia' => $this->enc('9'),
        ]);
    }

    public function test_incluir_evento_ya_existe(): void
    {
        $this->db->method('query')->willReturn(['id_competencia' => 11]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Ya existe una competencia con el nombre introducido","code":400}');
        $this->eventos->incluirEvento([
            'nombre' => 'Campeonato Nacional',
            'lugar_competencia' => 'Ciudad Deportiva Lara',
            'fecha_inicio' => '2024-12-01',
            'fecha_fin' => '2024-12-05',
            'categoria' => $this->enc('6'),
            'subs' => $this->enc('6'),
            'tipo_competencia' => $this->enc('9'),
        ]);
    }

    public function test_modificar_evento_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_competencia' => 11], [], true);
        $r = $this->eventos->modificarEvento([
            'id_competencia' => $this->enc('11'),
            'nombre' => 'Campeonato Nacional',
            'lugar_competencia' => 'Ciudad Deportiva Lara',
            'fecha_inicio' => '2024-12-01',
            'fecha_fin' => '2024-12-05',
            'categoria' => $this->enc('6'),
            'subs' => $this->enc('6'),
            'tipo_competencia' => $this->enc('9'),
        ]);
        $this->assertIsArray($r);
        $this->assertArrayHasKey('mensaje', $r);
        $this->assertEquals('La competencia se modificó exitosamente', $r['mensaje']);
    }

    public function test_modificar_evento_invalido(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Los siguientes campos faltan: [\"lugar_competencia\"]","code":400}');
        $this->eventos->modificarEvento([
            'id_competencia' => $this->enc('11'),
            'nombre' => 'Campeonato Nacional',
            'lugar_competencia' => '',
            'fecha_inicio' => '2023-12-01',
            'fecha_fin' => '2024-12-',
            'categoria' => $this->enc('6'),
            'subs' => $this->enc('6'),
            'tipo_competencia' => $this->enc('9'),
        ]);
    }

    public function test_modificar_evento_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"La competencia ingresada no existe","code":404}');
        $this->eventos->modificarEvento([
            'id_competencia' => $this->enc('11'),
            'nombre' => 'Campeonato Nacional',
            'lugar_competencia' => 'Ciudad Deportiva Lara',
            'fecha_inicio' => '2024-12-01',
            'fecha_fin' => '2024-12-05',
            'categoria' => $this->enc('6'),
            'subs' => $this->enc('6'),
            'tipo_competencia' => $this->enc('9'),
        ]);
    }

    public function test_eliminar_evento_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_competencia' => 11], true);
        $r = $this->eventos->eliminarEvento(['id_competencia' => $this->enc('11')]);
        $this->assertIsArray($r);
        $this->assertArrayHasKey('mensaje', $r);
        $this->assertEquals('La competencia se eliminó exitosamente', $r['mensaje']);
    }

    public function test_eliminar_evento_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"La competencia ingresada no existe","code":404}');
        $this->eventos->eliminarEvento(['id_competencia' => $this->enc('999')]);
    }

    public function test_eliminar_evento_invalido(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El valor ingresado no es un n\u00famero entero v\u00e1lido","code":400}');
        $this->eventos->eliminarEvento(['id_competencia' => $this->enc('')]);
    }
    public function test_obtener_competencia_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            ['id_competencia' => 11],
            ['id_competencia' => 11, 'subs' => 6, 'categoria' => 6, 'tipo_competicion' => 9, 'nombre' => 'Campeonato', 'lugar_competencia' => 'CDL', 'fecha_inicio' => '2024-12-01', 'fecha_fin' => '2024-12-05']
        );
        $r = $this->eventos->obtenerCompetencia(['id' => $this->enc('11')]);
        $this->assertIsArray($r);
        $this->assertArrayHasKey('competencia', $r);
        $this->assertIsArray($r['competencia']);
    }

    public function test_obtener_competencia_invalido(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El valor ingresado no es un n\u00famero entero v\u00e1lido","code":400}');
        $this->eventos->obtenerCompetencia(['id' => $this->enc('asd%#')]);
    }

    public function test_obtener_competencia_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"La competencia ingresada no existe","code":404}');
        $this->eventos->obtenerCompetencia(['id' => $this->enc('11')]);
    }

    public function test_listado_eventos_activos(): void
    {
        $this->db->method('query')->willReturn([['id_competencia' => 11, 'subs' => 6, 'categoria' => 6, 'tipo_competicion' => 9]]);
        $r = $this->eventos->listadoEventos();
        $this->assertIsArray($r);
        $this->assertArrayHasKey('eventos', $r);
        $this->assertIsArray($r['eventos']);
    }

    public function test_cerrar_evento_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_competencia' => 11], true);
        $r = $this->eventos->cerrarEvento(['id_competencia' => $this->enc('11')]);
        $this->assertIsArray($r);
        $this->assertArrayHasKey('mensaje', $r);
        $this->assertEquals('El evento se cerró exitosamente', $r['mensaje']);
    }

    public function test_cerrar_evento_invalido(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El valor ingresado no es un n\u00famero entero v\u00e1lido","code":400}');
        $this->eventos->cerrarEvento(['id_competencia' => $this->enc('abc')]);
    }

    public function test_cerrar_evento_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"La competencia introducida no existe","code":404}');
        $this->eventos->cerrarEvento(['id_competencia' => $this->enc('11')]);
    }


    public function test_listado_atletas_inscritos_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_competencia' => 11], [['id_atleta' => 5560233, 'nombre' => 'Leo', 'apellido' => 'H']]);
        $r = $this->eventos->listadoAtletasInscritos(['id_competencia' => $this->enc('11')]);
        $this->assertIsArray($r);
        $this->assertArrayHasKey('atletas', $r);
        $this->assertIsArray($r['atletas']);
    }

    public function test_listado_atletas_inscritos_invalido(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El valor ingresado no es un n\u00famero entero v\u00e1lido","code":400}');
        $this->eventos->listadoAtletasInscritos(['id_competencia' => $this->enc('competencia')]);
    }

    public function test_listado_atletas_inscritos_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"No existe la competencia ingresada","code":404}');
        $this->eventos->listadoAtletasInscritos(['id_competencia' => $this->enc('100')]);
    }

    public function test_inscribir_atletas_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_competencia' => 11], true);
        $lista = json_encode([$this->enc('5560233')]);
        $r = $this->eventos->inscribirAtletas([
            'id_competencia' => $this->enc('11'),
            'atletas' => $lista
        ]);
        $this->assertIsArray($r);
        $this->assertArrayHasKey('mensaje', $r);
        $this->assertEquals('Los atletas se inscribieron exitosamente', $r['mensaje']);
    }

    public function test_inscribir_atletas_invalido(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El formato de los datos de atletas no es v\u00e1lido","code":400}');
        $this->eventos->inscribirAtletas([
            'id_competencia' => $this->enc('11'),
            'atletas' => ['no-json']
        ]);
    }

    public function test_inscribir_atletas_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Esta competencia no existe","code":400}');
        $this->eventos->inscribirAtletas([
            'id_competencia' => $this->enc('11'),
            'atletas' => json_encode([$this->enc('5560233')])
        ]);
    }

    public function test_registrar_resultados_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_competencia' => 11], true);
        $r = $this->eventos->registrarResultados([
            'id_competencia' => $this->enc('11'),
            'id_atleta' => $this->enc('5560233'),
            'arranque' => 100.5,
            'envion' => 120.0,
            'medalla_arranque' => 'oro',
            'medalla_envion' => 'plata',
            'medalla_total' => 'oro',
            'total' => 220.5
        ]);
        $this->assertIsArray($r);
        $this->assertArrayHasKey('mensaje', $r);
        $this->assertEquals('El resultado se registró exitosamente', $r['mensaje']);
    }

    public function test_registrar_resultados_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El atleta o competencia no existe","code":404}');
        $this->eventos->registrarResultados([
            'id_competencia' => $this->enc('11'),
            'id_atleta' => $this->enc('5560233'),
            'arranque' => 100.5,
            'envion' => 120.0,
            'medalla_arranque' => 'oro',
            'medalla_envion' => 'plata',
            'medalla_total' => 'oro',
            'total' => 220.5
        ]);
    }
    public function test_registrar_resultados_invalido(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Los siguientes campos faltan: [\"arranque\",\"envion\",\"medalla_arranque\"]","code":400}');
        $this->eventos->registrarResultados([
            'id_competencia' => $this->enc('$%$5'),
            'id_atleta' => $this->enc(''),
            'arranque' => '',
            'envion' => null,
            'medalla_arranque' => '',
            'medalla_envion' => '3',
            'medalla_total' => 'true',
            'total' => false
        ]);
    }

    public function test_modificar_resultados_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_competencia' => 11], true);
        $r = $this->eventos->modificarResultados([
            'id_competencia' => $this->enc('11'),
            'id_atleta' => $this->enc('5560233'),
            'arranque' => 101.0,
            'envion' => 121.0,
            'medalla_arranque' => 'plata',
            'medalla_envion' => 'oro',
            'medalla_total' => 'oro',
            'total' => 222.0
        ]);
        $this->assertIsArray($r);
        $this->assertArrayHasKey('mensaje', $r);
        $this->assertEquals('El resultado se modificó exitosamente', $r['mensaje']);
    }

    public function test_modificar_resultados_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El atleta o competencia no existe","code":404}');
        $this->eventos->modificarResultados([
            'id_competencia' => $this->enc('11'),
            'id_atleta' => $this->enc('5560233'),
            'arranque' => 101.0,
            'envion' => 121.0,
            'medalla_arranque' => 'plata',
            'medalla_envion' => 'oro',
            'medalla_total' => 'oro',
            'total' => 222.0
        ]);
    }
    public function test_modificar_resultados_invalido(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_competencia' => 11], true);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"Los siguientes campos faltan: [\"envion\",\"medalla_arranque\",\"medalla_envion\",\"medalla_total\"]","code":400}');
        $this->eventos->modificarResultados([
            'id_competencia' => $this->enc('gggg'),
            'id_atleta' => $this->enc('leoleo'),
            'arranque' => false,
            'envion' => null,
            'medalla_arranque' => '',
            'medalla_envion' => '',
            'medalla_total' => '',
            'total' => false
        ]);
    }

    public function test_listado_eventos_anteriores(): void
    {
        $this->db->method('query')->willReturn([['id_competencia' => 7, 'subs' => 6, 'categoria' => 6, 'tipo_competicion' => 9]]);
        $r = $this->eventos->listadoEventosAnteriores();
        $this->assertIsArray($r);
        $this->assertArrayHasKey('eventos', $r);
        $this->assertEquals(1, count($r['eventos']));
    }

    public function test_listado_atletas_disponibles_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(true,
            ['peso_minimo' => 60, 'peso_maximo' => 90, 'edad_minima' => 15, 'edad_maxima' => 30],
            [['id_atleta' => 5560233, 'nombre' => 'Leo', 'apellido' => 'H', 'peso' => 75, 'fecha_nacimiento' => '2000-01-01']]
        );
        $r = $this->eventos->listadoAtletasDisponibles(['id' => $this->enc('11')]);
        $this->assertIsArray($r);
        $this->assertArrayHasKey('atletas', $r);
        $this->assertEquals(1, count($r['atletas']));
    }
    public function test_listado_atletas_disponibles_invalido(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"El valor ingresado no es un n\u00famero entero v\u00e1lido","code":400}');
        $this->eventos->listadoAtletasDisponibles(['id' => $this->enc("")]);
    }
    public function test_listado_atletas_disponibles_no_existe(): void
    {
        $this->db->method('query')->willReturn(false);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('{"error":"La competencia ingresada no existe","code":404}');
        $this->eventos->listadoAtletasDisponibles(['id' => $this->enc('11')]);
    }
}
