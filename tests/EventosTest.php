<?php

namespace Tests\Feature;

use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use Gymsys\Model\Eventos;
use Gymsys\Model\Categorias;
use Gymsys\Model\Subs;
use Gymsys\Model\TipoCompetencia;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class EventosTest extends TestCase
{
    private Eventos $eventos;
    private Categorias $categorias;
    private Subs $subs;
    private TipoCompetencia $tipoCompetencia;
    private Database|MockObject $db;

    private function enc(string $v): string
    {
        return Cipher::aesEncrypt($v);
    }

    protected function setUp(): void
    {
        // Clave de 32 chars para AES-256
        $key = '0123456789abcdef0123456789abcdef';
        $_ENV['AES_KEY'] = $key;
        putenv('AES_KEY=' . $key);

        $_ENV['SECURE_DB'] = 'secure';
        putenv('SECURE_DB=secure');

        $this->db = $this->createMock(Database::class);
        $this->eventos = new Eventos($this->db);
        $this->categorias = new Categorias($this->db);
        $this->subs = new Subs($this->db);
        $this->tipoCompetencia = new TipoCompetencia($this->db);
    }

    public function test_incluir_evento_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls([], true);
        $r = $this->eventos->incluirEvento([
            'nombre'=>'Campeonato Nacional',
            'lugar_competencia'=>'Ciudad Deportiva Lara',
            'fecha_inicio'=>'2024-12-01',
            'fecha_fin'=>'2024-12-05',
            'categoria'=>$this->enc('6'),
            'subs'=>$this->enc('6'),
            'tipo_competencia'=>$this->enc('9'),
        ]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function test_incluir_evento_no_valido(): void
    {
        $this->eventos;
        $this->expectException(\Throwable::class);
        $this->eventos->incluirEvento([
            'nombre'=>'Campeonato.',
            'lugar_competencia'=>'CD Lara',
            'fecha_inicio'=>'',
            'fecha_fin'=>'final',
            'categoria'=>$this->enc('6'),
            'subs'=>$this->enc('6'),
            'tipo_competencia'=>$this->enc('9'),
        ]);
    }

    public function test_incluir_evento_ya_existe(): void
    {
        $this->db->method('query')->willReturn(['id_competencia'=>11]);
        $this->expectException(\Throwable::class);
        $this->eventos->incluirEvento([
            'nombre'=>'Campeonato Nacional',
            'lugar_competencia'=>'Ciudad Deportiva Lara',
            'fecha_inicio'=>'2024-12-01',
            'fecha_fin'=>'2024-12-05',
            'categoria'=>$this->enc('6'),
            'subs'=>$this->enc('6'),
            'tipo_competencia'=>$this->enc('9'),
        ]);
    }

    public function test_modificar_evento_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_competencia'=>11], [], true);
        $r = $this->eventos->modificarEvento([
            'id_competencia'=>$this->enc('11'),
            'nombre'=>'Campeonato Nacional',
            'lugar_competencia'=>'Ciudad Deportiva Lara',
            'fecha_inicio'=>'2024-12-01',
            'fecha_fin'=>'2024-12-05',
            'categoria'=>$this->enc('6'),
            'subs'=>$this->enc('6'),
            'tipo_competencia'=>$this->enc('9'),
        ]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function test_modificar_evento_no_valido(): void
    {
        $this->eventos;
        $this->expectException(\Throwable::class);
        $this->eventos->modificarEvento([
            'id_competencia'=>$this->enc('11'),
            'nombre'=>'Campeonato Nacional',
            'lugar_competencia'=>'',
            'fecha_inicio'=>'2023-12-01',
            'fecha_fin'=>'2024-12-',
            'categoria'=>$this->enc('6'),
            'subs'=>$this->enc('6'),
            'tipo_competencia'=>$this->enc('9'),
        ]);
    }

    public function test_modificar_evento_no_exsite(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\Throwable::class);
        $this->eventos->modificarEvento([
            'id_competencia'=>$this->enc('11'),
            'nombre'=>'Campeonato Nacional',
            'lugar_competencia'=>'Ciudad Deportiva Lara',
            'fecha_inicio'=>'2024-12-01',
            'fecha_fin'=>'2024-12-05',
            'categoria'=>$this->enc('6'),
            'subs'=>$this->enc('6'),
            'tipo_competencia'=>$this->enc('9'),
        ]);
    }

    public function test_obtener_competencia_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            ['id_competencia'=>11],
            ['id_competencia'=>11,'subs'=>6,'categoria'=>6,'tipo_competicion'=>9,'nombre'=>'Campeonato','lugar_competencia'=>'CDL','fecha_inicio'=>'2024-12-01','fecha_fin'=>'2024-12-05']
        );
        $r = $this->eventos->obtenerCompetencia(['id'=>$this->enc('11')]);
        $this->assertArrayHasKey('competencia', $r);
    }

    public function test_obtener_competencia_no_valido(): void
    {
        $this->eventos;
        $this->expectException(\Throwable::class);
        $this->eventos->obtenerCompetencia(['id'=>$this->enc('1e1')]);
    }

    public function test_obtener_competencia_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\Throwable::class);
        $this->eventos->obtenerCompetencia(['id'=>$this->enc('11')]);
    }

    public function test_listado_eventos_exitoso(): void
    {
        $this->db->method('query')->willReturn([['id_competencia'=>11,'subs'=>6,'categoria'=>6,'tipo_competicion'=>9]]);
        $r = $this->eventos->listadoEventos();
        $this->assertArrayHasKey('eventos', $r);
    }

    public function test_cerrar_evento_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_competencia'=>11], true);
        $r = $this->eventos->cerrarEvento(['id_competencia'=>$this->enc('11')]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function test_cerrar_evento_no_valido(): void
    {
        $this->eventos;
        $this->expectException(\Throwable::class);
        $this->eventos->cerrarEvento(['id_competencia'=>$this->enc('abc')]);
    }

    public function test_cerrar_evento_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\Throwable::class);
        $this->eventos->cerrarEvento(['id_competencia'=>$this->enc('11')]);
    }

    public function test_listado_atletas_inscritos_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_competencia'=>11], [['id_atleta'=>5560233,'nombre'=>'Leo','apellido'=>'H']]);
        $r = $this->eventos->listadoAtletasInscritos(['id_competencia'=>$this->enc('11')]);
        $this->assertArrayHasKey('atletas', $r);
    }

    public function test_listado_atletas_inscritos_no_valido(): void
    {
        $this->eventos;
        $this->expectException(\Throwable::class);
        $this->eventos->listadoAtletasInscritos(['id_competencia'=>$this->enc('competencia')]);
    }

    public function test_listado_atletas_inscritos_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\Throwable::class);
        $this->eventos->listadoAtletasInscritos(['id_competencia'=>$this->enc('100')]);
    }

    public function test_inscribir_atletas_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_competencia'=>11], true);
        $lista = json_encode([$this->enc('5560233')]);
        $r = $this->eventos->inscribirAtletas(['id_competencia'=>$this->enc('11'),'atletas'=>$lista]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function test_inscribir_atletas_formato_invalido(): void
    {
        $this->eventos;
        $this->expectException(\Throwable::class);
        $this->eventos->inscribirAtletas(['id_competencia'=>$this->enc('11'),'atletas'=>['no-json']]);
    }

    public function test_inscribir_atletas_competencia_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\Throwable::class);
        $this->eventos->inscribirAtletas(['id_competencia'=>$this->enc('11'),'atletas'=>json_encode([$this->enc('5560233')])]);
    }

    public function test_registrar_resultados_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_competencia'=>11], true);
        $r = $this->eventos->registrarResultados([
            'id_competencia'=>$this->enc('11'),
            'id_atleta'=>$this->enc('5560233'),
            'arranque'=>100.5,
            'envion'=>120.0,
            'medalla_arranque'=>'oro',
            'medalla_envion'=>'plata',
            'medalla_total'=>'oro',
            'total'=>220.5
        ]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function test_registrar_resultados_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\Throwable::class);
        $this->eventos->registrarResultados([
            'id_competencia'=>$this->enc('11'),
            'id_atleta'=>$this->enc('5560233'),
            'arranque'=>100.5,
            'envion'=>120.0,
            'medalla_arranque'=>'oro',
            'medalla_envion'=>'plata',
            'medalla_total'=>'oro',
            'total'=>220.5
        ]);
    }

    public function test_modificar_resultados_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_competencia'=>11], true);
        $r = $this->eventos->modificarResultados([
            'id_competencia'=>$this->enc('11'),
            'id_atleta'=>$this->enc('5560233'),
            'arranque'=>101.0,
            'envion'=>121.0,
            'medalla_arranque'=>'plata',
            'medalla_envion'=>'oro',
            'medalla_total'=>'oro',
            'total'=>222.0
        ]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function test_modificar_resultados_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\Throwable::class);
        $this->eventos->modificarResultados([
            'id_competencia'=>$this->enc('11'),
            'id_atleta'=>$this->enc('5560233'),
            'arranque'=>101.0,
            'envion'=>121.0,
            'medalla_arranque'=>'plata',
            'medalla_envion'=>'oro',
            'medalla_total'=>'oro',
            'total'=>222.0
        ]);
    }

    public function test_listado_eventos_anteriores_exitoso(): void
    {
        $this->db->method('query')->willReturn([['id_competencia'=>7,'subs'=>6,'categoria'=>6,'tipo_competicion'=>9]]);
        $r = $this->eventos->listadoEventosAnteriores();
        $this->assertArrayHasKey('eventos', $r);
    }

    public function test_listado_atletas_disponibles_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(
            ['peso_minimo'=>60,'peso_maximo'=>90,'edad_minima'=>15,'edad_maxima'=>30],
            [['id_atleta'=>5560233,'nombre'=>'Leo','apellido'=>'H','peso'=>75,'fecha_nacimiento'=>'2000-01-01']]
        );
        $r = $this->eventos->listadoAtletasDisponibles(['id'=>$this->enc('11')]);
        $this->assertArrayHasKey('atletas', $r);
    }

    public function test_categorias_listado_exitoso(): void
    {
        $this->db->method('query')->willReturn([['id_categoria'=>8,'nombre'=>'81M','peso_minimo'=>73,'peso_maximo'=>81.99]]);
        $r = $this->categorias->listadoCategorias();
        $this->assertArrayHasKey('categorias', $r);
    }

    public function test_incluir_categoria_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls([], true);
        $r = $this->categorias->incluirCategoria(['nombre'=>'81M','pesoMinimo'=>73,'pesoMaximo'=>81.99]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function test_incluir_categoria_no_valido(): void
    {
        $this->categorias;
        $this->expectException(\Throwable::class);
        $this->eventos->incluirCategoria(['nombre'=>'81M','pesoMinimo'=>85,'pesoMaximo'=>81.99]);
    }

    public function test_incluir_categoria_ya_existe(): void
    {
        $this->db->method('query')->willReturn(['id_categoria'=>8]);
        $this->expectException(\Throwable::class);
        $this->eventos->incluirCategoria(['nombre'=>'81M','pesoMinimo'=>75,'pesoMaximo'=>81.99]);
    }

    public function test_modificar_categoria_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_categoria'=>8], [], true);
        $r = $this->categorias->modificarCategoria(['id_categoria'=>$this->enc('8'),'nombre'=>'81F','pesoMinimo'=>75,'pesoMaximo'=>81.99]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function test_modificar_categoria_no_valido(): void
    {
        $this->categorias;
        $this->expectException(\Throwable::class);
        $this->eventos->modificarCategoria(['id_categoria'=>$this->enc('8'),'nombre'=>'','pesoMinimo'=>75,'pesoMaximo'=>'']);
    }

    public function test_modificar_categoria_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\Throwable::class);
        $this->eventos->modificarCategoria(['id_categoria'=>$this->enc('82'),'nombre'=>'81M','pesoMinimo'=>75,'pesoMaximo'=>80.99]);
    }

    public function test_eliminar_categoria_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_categoria'=>8], true);
        $r = $this->categorias->eliminarCategoria(['id_categoria'=>$this->enc('8')]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function test_eliminar_categoria_no_valido(): void
    {
        $this->categorias;
        $this->expectException(\Throwable::class);
        $this->eventos->eliminarCategoria(['id_categoria'=>$this->enc('Categoria')]);
    }

    public function test_eliminar_categoria_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\Throwable::class);
        $this->categorias->eliminarCategoria(['id_categoria'=>$this->enc('333')]);
    }

    public function test_listado_subs_exitoso(): void
    {
        $this->db->method('query')->willReturn([['id_sub'=>8,'nombre'=>'U20','edad_minima'=>15,'edad_maxima'=>20]]);
        $r = $this->subs->listadoSubs();
        $this->assertArrayHasKey('subs', $r);
    }

    public function test_incluir_sub_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls([], true);
        $r = $this->subs->incluirSub(['nombre'=>'U20','edadMinima'=>15,'edadMaxima'=>20]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function test_incluir_sub_no_valido(): void
    {
        $this->subs;
        $this->expectException(\Throwable::class);
        $this->eventos->incluirSub(['nombre'=>'U20','edadMinima'=>'','edadMaxima'=>20]);
    }

    public function test_incluir_sub_ya_existe(): void
    {
        $this->db->method('query')->willReturn(['id_sub'=>8]);
        $this->expectException(\Throwable::class);
        $this->eventos->incluirSub(['nombre'=>'U20','edadMinima'=>15,'edadMaxima'=>20]);
    }

    public function test_modificar_sub_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_sub'=>8], [], true);
        $r = $this->subs->modificarSub(['id_sub'=>$this->enc('8'),'nombre'=>'U20','edadMinima'=>16,'edadMaxima'=>20]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function test_modificar_sub_no_valido(): void
    {
        $this->subs;
        $this->expectException(\Throwable::class);
        $this->eventos->modificarSub(['id_sub'=>$this->enc('8'),'nombre'=>'U20','edadMinima'=>'menor','edadMaxima'=>20]);
    }

    public function test_modificar_sub_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\Throwable::class);
        $this->eventos->modificarSub(['id_sub'=>$this->enc('83'),'nombre'=>'U20','edadMinima'=>15,'edadMaxima'=>20]);
    }

    public function test_modificar_sub_ya_existe(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_sub'=>8], ['id_sub'=>9]);
        $this->expectException(\Throwable::class);
        $this->eventos->modificarSub(['id_sub'=>$this->enc('8'),'nombre'=>'U15','edadMinima'=>15,'edadMaxima'=>20]);
    }

    public function test_eliminar_sub_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_sub'=>8], true);
        $r = $this->subs->eliminarSub(['id_sub'=>$this->enc('8')]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function test_eliminar_sub_no_valido(): void
    {
        $this->subs;
        $this->expectException(\Throwable::class);
        $this->eventos->eliminarSub(['id_sub'=>$this->enc('categoria')]);
    }

    public function test_eliminar_sub_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\Throwable::class);
        $this->eventos->eliminarSub(['id_sub'=>$this->enc('100')]);
    }

    public function test_listado_tipo_exitoso(): void
    {
        $this->db->method('query')->willReturn([['id_tipo_competencia'=>13,'nombre'=>'Senior']]);
        $r = $this->tipoCompetencia->listadoTipos();
        $this->assertArrayHasKey('tipos', $r);
    }

    public function test_incluir_tipo_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls([], true);
        $r = $this->tipoCompetencia->incluirTipo(['nombre'=>'Sub23']);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function test_incluir_tipo_no_valido(): void
    {
        $this->tipoCompetencia;
        $this->expectException(\Throwable::class);
        $this->eventos->incluirTipo(['nombre'=>'']);
    }

    public function test_incluir_tipo_ya_existe(): void
    {
        $this->db->method('query')->willReturn(['id_tipo_competencia'=>13]);
        $this->expectException(\Throwable::class);
        $this->eventos->incluirTipo(['nombre'=>'Sub23']);
    }

    public function test_modificar_tipo_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_tipo_competencia'=>13], [], true);
        $r = $this->tipoCompetencia->modificarTipo(['id_tipo'=>$this->enc('13'),'nombre'=>'Sub24']);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function test_modificar_tipo_no_valido(): void
    {
        $this->tipoCompetencia;
        $this->expectException(\Throwable::class);
        $this->eventos->modificarTipo(['id_tipo'=>$this->enc('13'),'nombre'=>'']);
    }

    public function test_modificar_tipo_ya_existe(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_tipo_competencia'=>13], ['id_tipo_competencia'=>99]);
        $this->expectException(\Throwable::class);
        $this->eventos->modificarTipo(['id_tipo'=>$this->enc('13'),'nombre'=>'Senior']);
    }

    public function test_modificar_tipo_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\Throwable::class);
        $this->eventos->modificarTipo(['id_tipo'=>$this->enc('123'),'nombre'=>'Sub25']);
    }

    public function test_eliminar_tipo_exitoso(): void
    {
        $this->db->method('query')->willReturnOnConsecutiveCalls(['id_tipo_competencia'=>13], true);
        $r = $this->tipoCompetencia->eliminarTipo(['id_tipo'=>$this->enc('13')]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function test_eliminar_tipo_no_valido(): void
    {
        $this->tipoCompetencia;
        $this->expectException(\Throwable::class);
        $this->eventos->eliminarTipo(['id_tipo'=>$this->enc('1/3')]);
    }

    public function test_eliminar_tipo_no_existe(): void
    {
        $this->db->method('query')->willReturn([]);
        $this->expectException(\Throwable::class);
        $this->eventos->eliminarTipo(['id_tipo'=>$this->enc('13')]);
    }
}

