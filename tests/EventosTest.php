<?php
namespace Tests\Feature;

use Gymsys\Core\Database;
use Gymsys\Utils\Cipher;
use Gymsys\Model\Eventos;
use Gymsys\Model\Categorias;
use Gymsys\Model\Subs;
use Gymsys\Model\TipoCompetencia;
use PHPUnit\Framework\TestCase;

final class EventosTest extends TestCase
{
    private function enc(string $v): string { return Cipher::aesEncrypt($v); }
protected function setUp(): void
{
    // Clave de 32 chars para AES-256
    $key = '0123456789abcdef0123456789abcdef';
    $_ENV['AES_KEY'] = $key;
    putenv('AES_KEY='.$key);

    $_ENV['SECURE_DB'] = 'secure';
    putenv('SECURE_DB=secure');

    $this->db = $this->createMock(\Gymsys\Core\Database::class);
    $this->eventos = new \Gymsys\Model\Eventos($this->db);
}

    public function testIncluirEventoExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturnOnConsecutiveCalls([], true);
        $m = new Eventos($db);
        $r = $m->incluirEvento([
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

    public function testIncluirEventoNoValido(): void
    {
        $m = new Eventos($this->createMock(Database::class));
        $this->expectException(\Throwable::class);
        $m->incluirEvento([
            'nombre'=>'Campeonato.',
            'lugar_competencia'=>'CD Lara',
            'fecha_inicio'=>'',
            'fecha_fin'=>'final',
            'categoria'=>$this->enc('6'),
            'subs'=>$this->enc('6'),
            'tipo_competencia'=>$this->enc('9'),
        ]);
    }

    public function testIncluirEventoYaExiste(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn(['id_competencia'=>11]);
        $m = new Eventos($db);
        $this->expectException(\Throwable::class);
        $m->incluirEvento([
            'nombre'=>'Campeonato Nacional',
            'lugar_competencia'=>'Ciudad Deportiva Lara',
            'fecha_inicio'=>'2024-12-01',
            'fecha_fin'=>'2024-12-05',
            'categoria'=>$this->enc('6'),
            'subs'=>$this->enc('6'),
            'tipo_competencia'=>$this->enc('9'),
        ]);
    }

    public function testModificarEventoExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturnOnConsecutiveCalls(['id_competencia'=>11], [], true);
        $m = new Eventos($db);
        $r = $m->modificarEvento([
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

    public function testModificarEventoNoValido(): void
    {
        $m = new Eventos($this->createMock(Database::class));
        $this->expectException(\Throwable::class);
        $m->modificarEvento([
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

    public function testModificarEventoNoExiste(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn([]);
        $m = new Eventos($db);
        $this->expectException(\Throwable::class);
        $m->modificarEvento([
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

    public function testObtenerCompetenciaExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturnOnConsecutiveCalls(
            ['id_competencia'=>11],
            ['id_competencia'=>11,'subs'=>6,'categoria'=>6,'tipo_competicion'=>9,'nombre'=>'Campeonato','lugar_competencia'=>'CDL','fecha_inicio'=>'2024-12-01','fecha_fin'=>'2024-12-05']
        );
        $m = new Eventos($db);
        $r = $m->obtenerCompetencia(['id'=>$this->enc('11')]);
        $this->assertArrayHasKey('competencia', $r);
    }

    public function testObtenerCompetenciaNoValido(): void
    {
        $m = new Eventos($this->createMock(Database::class));
        $this->expectException(\Throwable::class);
        $m->obtenerCompetencia(['id'=>$this->enc('1e1')]);
    }

    public function testObtenerCompetenciaNoExiste(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn([]);
        $m = new Eventos($db);
        $this->expectException(\Throwable::class);
        $m->obtenerCompetencia(['id'=>$this->enc('11')]);
    }

    public function testListadoEventosExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn([['id_competencia'=>11,'subs'=>6,'categoria'=>6,'tipo_competicion'=>9]]);
        $m = new Eventos($db);
        $r = $m->listadoEventos();
        $this->assertArrayHasKey('eventos', $r);
    }

    public function testCerrarEventoExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturnOnConsecutiveCalls(['id_competencia'=>11], true);
        $m = new Eventos($db);
        $r = $m->cerrarEvento(['id_competencia'=>$this->enc('11')]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function testCerrarEventoNoValido(): void
    {
        $m = new Eventos($this->createMock(Database::class));
        $this->expectException(\Throwable::class);
        $m->cerrarEvento(['id_competencia'=>$this->enc('abc')]);
    }

    public function testCerrarEventoNoExiste(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn([]);
        $m = new Eventos($db);
        $this->expectException(\Throwable::class);
        $m->cerrarEvento(['id_competencia'=>$this->enc('11')]);
    }

    public function testListadoAtletasInscritosExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturnOnConsecutiveCalls(['id_competencia'=>11], [['id_atleta'=>5560233,'nombre'=>'Leo','apellido'=>'H']]);
        $m = new Eventos($db);
        $r = $m->listadoAtletasInscritos(['id_competencia'=>$this->enc('11')]);
        $this->assertArrayHasKey('atletas', $r);
    }

    public function testListadoAtletasInscritosNoValido(): void
    {
        $m = new Eventos($this->createMock(Database::class));
        $this->expectException(\Throwable::class);
        $m->listadoAtletasInscritos(['id_competencia'=>$this->enc('competencia')]);
    }

    public function testListadoAtletasInscritosNoExiste(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn([]);
        $m = new Eventos($db);
        $this->expectException(\Throwable::class);
        $m->listadoAtletasInscritos(['id_competencia'=>$this->enc('100')]);
    }

    public function testInscribirAtletasExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturnOnConsecutiveCalls(['id_competencia'=>11], true);
        $m = new Eventos($db);
        $lista = json_encode([$this->enc('5560233')]);
        $r = $m->inscribirAtletas(['id_competencia'=>$this->enc('11'),'atletas'=>$lista]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function testInscribirAtletasFormatoInvalido(): void
    {
        $m = new Eventos($this->createMock(Database::class));
        $this->expectException(\Throwable::class);
        $m->inscribirAtletas(['id_competencia'=>$this->enc('11'),'atletas'=>['no-json']]);
    }

    public function testInscribirAtletasCompetenciaNoExiste(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn([]);
        $m = new Eventos($db);
        $this->expectException(\Throwable::class);
        $m->inscribirAtletas(['id_competencia'=>$this->enc('11'),'atletas'=>json_encode([$this->enc('5560233')])]);
    }

    public function testRegistrarResultadosExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturnOnConsecutiveCalls(['id_competencia'=>11], true);
        $m = new Eventos($db);
        $r = $m->registrarResultados([
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

    public function testRegistrarResultadosNoExiste(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn([]);
        $m = new Eventos($db);
        $this->expectException(\Throwable::class);
        $m->registrarResultados([
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

    public function testModificarResultadosExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturnOnConsecutiveCalls(['id_competencia'=>11], true);
        $m = new Eventos($db);
        $r = $m->modificarResultados([
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

    public function testModificarResultadosNoExiste(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn([]);
        $m = new Eventos($db);
        $this->expectException(\Throwable::class);
        $m->modificarResultados([
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

    public function testListadoEventosAnterioresExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn([['id_competencia'=>7,'subs'=>6,'categoria'=>6,'tipo_competicion'=>9]]);
        $m = new Eventos($db);
        $r = $m->listadoEventosAnteriores();
        $this->assertArrayHasKey('eventos', $r);
    }

    public function testListadoAtletasDisponiblesExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturnOnConsecutiveCalls(
            ['peso_minimo'=>60,'peso_maximo'=>90,'edad_minima'=>15,'edad_maxima'=>30],
            [['id_atleta'=>5560233,'nombre'=>'Leo','apellido'=>'H','peso'=>75,'fecha_nacimiento'=>'2000-01-01']]
        );
        $m = new Eventos($db);
        $r = $m->listadoAtletasDisponibles(['id'=>$this->enc('11')]);
        $this->assertArrayHasKey('atletas', $r);
    }

    public function testCategoriasListadoExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn([['id_categoria'=>8,'nombre'=>'81M','peso_minimo'=>73,'peso_maximo'=>81.99]]);
        $m = new Categorias($db);
        $r = $m->listadoCategorias();
        $this->assertArrayHasKey('categorias', $r);
    }

    public function testIncluirCategoriaExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturnOnConsecutiveCalls([], true);
        $m = new Categorias($db);
        $r = $m->incluirCategoria(['nombre'=>'81M','pesoMinimo'=>73,'pesoMaximo'=>81.99]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function testIncluirCategoriaNoValido(): void
    {
        $m = new Categorias($this->createMock(Database::class));
        $this->expectException(\Throwable::class);
        $m->incluirCategoria(['nombre'=>'81M','pesoMinimo'=>85,'pesoMaximo'=>81.99]);
    }

    public function testIncluirCategoriaYaExiste(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn(['id_categoria'=>8]);
        $m = new Categorias($db);
        $this->expectException(\Throwable::class);
        $m->incluirCategoria(['nombre'=>'81M','pesoMinimo'=>75,'pesoMaximo'=>81.99]);
    }

    public function testModificarCategoriaExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturnOnConsecutiveCalls(['id_categoria'=>8], [], true);
        $m = new Categorias($db);
        $r = $m->modificarCategoria(['id_categoria'=>$this->enc('8'),'nombre'=>'81F','pesoMinimo'=>75,'pesoMaximo'=>81.99]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function testModificarCategoriaNoValido(): void
    {
        $m = new Categorias($this->createMock(Database::class));
        $this->expectException(\Throwable::class);
        $m->modificarCategoria(['id_categoria'=>$this->enc('8'),'nombre'=>'','pesoMinimo'=>75,'pesoMaximo'=>'']);
    }

    public function testModificarCategoriaNoExiste(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn([]);
        $m = new Categorias($db);
        $this->expectException(\Throwable::class);
        $m->modificarCategoria(['id_categoria'=>$this->enc('82'),'nombre'=>'81M','pesoMinimo'=>75,'pesoMaximo'=>80.99]);
    }

    public function testEliminarCategoriaExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturnOnConsecutiveCalls(['id_categoria'=>8], true);
        $m = new Categorias($db);
        $r = $m->eliminarCategoria(['id_categoria'=>$this->enc('8')]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function testEliminarCategoriaNoValido(): void
    {
        $m = new Categorias($this->createMock(Database::class));
        $this->expectException(\Throwable::class);
        $m->eliminarCategoria(['id_categoria'=>$this->enc('Categoria')]);
    }

    public function testEliminarCategoriaNoExiste(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn([]);
        $m = new Categorias($db);
        $this->expectException(\Throwable::class);
        $m->eliminarCategoria(['id_categoria'=>$this->enc('333')]);
    }

    public function testListadoSubsExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn([['id_sub'=>8,'nombre'=>'U20','edad_minima'=>15,'edad_maxima'=>20]]);
        $m = new Subs($db);
        $r = $m->listadoSubs();
        $this->assertArrayHasKey('subs', $r);
    }

    public function testIncluirSubExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturnOnConsecutiveCalls([], true);
        $m = new Subs($db);
        $r = $m->incluirSub(['nombre'=>'U20','edadMinima'=>15,'edadMaxima'=>20]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function testIncluirSubNoValido(): void
    {
        $m = new Subs($this->createMock(Database::class));
        $this->expectException(\Throwable::class);
        $m->incluirSub(['nombre'=>'U20','edadMinima'=>'','edadMaxima'=>20]);
    }

    public function testIncluirSubYaExiste(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn(['id_sub'=>8]);
        $m = new Subs($db);
        $this->expectException(\Throwable::class);
        $m->incluirSub(['nombre'=>'U20','edadMinima'=>15,'edadMaxima'=>20]);
    }

    public function testModificarSubExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturnOnConsecutiveCalls(['id_sub'=>8], [], true);
        $m = new Subs($db);
        $r = $m->modificarSub(['id_sub'=>$this->enc('8'),'nombre'=>'U20','edadMinima'=>16,'edadMaxima'=>20]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function testModificarSubNoValido(): void
    {
        $m = new Subs($this->createMock(Database::class));
        $this->expectException(\Throwable::class);
        $m->modificarSub(['id_sub'=>$this->enc('8'),'nombre'=>'U20','edadMinima'=>'menor','edadMaxima'=>20]);
    }

    public function testModificarSubNoExiste(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn([]);
        $m = new Subs($db);
        the:
        $this->expectException(\Throwable::class);
        $m->modificarSub(['id_sub'=>$this->enc('83'),'nombre'=>'U20','edadMinima'=>15,'edadMaxima'=>20]);
    }

    public function testModificarSubYaExiste(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturnOnConsecutiveCalls(['id_sub'=>8], ['id_sub'=>9]);
        $m = new Subs($db);
        $this->expectException(\Throwable::class);
        $m->modificarSub(['id_sub'=>$this->enc('8'),'nombre'=>'U15','edadMinima'=>15,'edadMaxima'=>20]);
    }

    public function testEliminarSubExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturnOnConsecutiveCalls(['id_sub'=>8], true);
        $m = new Subs($db);
        $r = $m->eliminarSub(['id_sub'=>$this->enc('8')]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function testEliminarSubNoValido(): void
    {
        $m = new Subs($this->createMock(Database::class));
        $this->expectException(\Throwable::class);
        $m->eliminarSub(['id_sub'=>$this->enc('categoria')]);
    }

    public function testEliminarSubNoExiste(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn([]);
        $m = new Subs($db);
        $this->expectException(\Throwable::class);
        $m->eliminarSub(['id_sub'=>$this->enc('100')]);
    }

    public function testListadoTipoExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn([['id_tipo_competencia'=>13,'nombre'=>'Senior']]);
        $m = new TipoCompetencia($db);
        $r = $m->listadoTipos();
        $this->assertArrayHasKey('tipos', $r);
    }

    public function testIncluirTipoExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturnOnConsecutiveCalls([], true);
        $m = new TipoCompetencia($db);
        $r = $m->incluirTipo(['nombre'=>'Sub23']);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function testIncluirTipoNoValido(): void
    {
        $m = new TipoCompetencia($this->createMock(Database::class));
        $this->expectException(\Throwable::class);
        $m->incluirTipo(['nombre'=>'']);
    }

    public function testIncluirTipoYaExiste(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn(['id_tipo_competencia'=>13]);
        $m = new TipoCompetencia($db);
        $this->expectException(\Throwable::class);
        $m->incluirTipo(['nombre'=>'Sub23']);
    }

    public function testModificarTipoExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturnOnConsecutiveCalls(['id_tipo_competencia'=>13], [], true);
        $m = new TipoCompetencia($db);
        $r = $m->modificarTipo(['id_tipo'=>$this->enc('13'),'nombre'=>'Sub24']);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function testModificarTipoNoValido(): void
    {
        $m = new TipoCompetencia($this->createMock(Database::class));
        $this->expectException(\Throwable::class);
        $m->modificarTipo(['id_tipo'=>$this->enc('13'),'nombre'=>'']);
    }

    public function testModificarTipoYaExiste(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturnOnConsecutiveCalls(['id_tipo_competencia'=>13], ['id_tipo_competencia'=>99]);
        $m = new TipoCompetencia($db);
        $this->expectException(\Throwable::class);
        $m->modificarTipo(['id_tipo'=>$this->enc('13'),'nombre'=>'Senior']);
    }

    public function testModificarTipoNoExiste(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn([]);
        $m = new TipoCompetencia($db);
        $this->expectException(\Throwable::class);
        $m->modificarTipo(['id_tipo'=>$this->enc('123'),'nombre'=>'Sub25']);
    }

    public function testEliminarTipoExitoso(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturnOnConsecutiveCalls(['id_tipo_competencia'=>13], true);
        $m = new TipoCompetencia($db);
        $r = $m->eliminarTipo(['id_tipo'=>$this->enc('13')]);
        $this->assertArrayHasKey('mensaje', $r);
    }

    public function testEliminarTipoNoValido(): void
    {
        $m = new TipoCompetencia($this->createMock(Database::class));
        $this->expectException(\Throwable::class);
        $m->eliminarTipo(['id_tipo'=>$this->enc('1/3')]);
    }

    public function testEliminarTipoNoExiste(): void
    {
        $db = $this->createMock(Database::class);
        $db->method('query')->willReturn([]);
        $m = new TipoCompetencia($db);
        $this->expectException(\Throwable::class);
        $m->eliminarTipo(['id_tipo'=>$this->enc('13')]);
    }
}
