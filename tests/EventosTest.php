<?php
use PHPUnit\Framework\TestCase;

class EventosTest extends TestCase
{
    private $eventos;

    protected function setUp(): void
    {
        $this->eventos = new Eventos();
    }

    public function testIncluirEventoExitoso()
    {
        $nombre = "Campeonato Nacional";
        $lugar_competencia = "Ciudad Deportiva";
        $fecha_inicio = "2024-12-01";
        $fecha_fin = "2024-12-05";
        $categoria = "Senior";
        $subs = "Sub20";
        $tipo_competencia = "Nacional";

        $respuesta = $this->eventos->incluir_evento(
            $nombre,
            $lugar_competencia,
            $fecha_inicio,
            $fecha_fin,
            $categoria,
            $subs,
            $tipo_competencia
        );

        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
    }

    public function testIncluirEventoFechasInvalidas()
    {
        $nombre = "Campeonato Nacional";
        $lugar_competencia = "Ciudad Deportiva";
        $fecha_inicio = "2024-12-10"; // Fecha inicio posterior a fecha fin
        $fecha_fin = "2024-12-05";
        $categoria = "Senior";
        $subs = "Sub 23";
        $tipo_competencia = "Nacional";

        $respuesta = $this->eventos->incluir_evento(
            $nombre,
            $lugar_competencia,
            $fecha_inicio,
            $fecha_fin,
            $categoria,
            $subs,
            $tipo_competencia
        );

        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertStringContainsString("fecha", $respuesta['mensaje']);
    }

    public function testListadoEventosExitoso()
    {
        $respuesta = $this->eventos->listado_eventos();

        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
        $this->assertIsArray($respuesta['respuesta']);
    }

    public function testCerrarEventoExitoso()
    {
        $id_competencia = 1; // Suponiendo que este ID existe en la base de datos
        $respuesta = $this->eventos->cerrar_evento($id_competencia);

        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
    }

    public function testCerrarEventoNoExistente()
    {
        $id_competencia = 9999; // ID que no existe
        $respuesta = $this->eventos->cerrar_evento($id_competencia);

        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertStringContainsString("no existe", $respuesta['mensaje']);
    }

    public function testModificarCompetenciaExitoso()
    {
        $id_competencia = 1;
        $nombre = "Torneo Modificado";
        $ubicacion = "Nuevo Estadio";
        $fecha_inicio = "2024-12-03";
        $fecha_fin = "2024-12-07";
        $categoria = "Junior";
        $subs = "Juveniles";
        $tipo_competencia = "Grupal";

        $respuesta = $this->eventos->modificarCompetencia(
            $id_competencia,
            $nombre,
            $ubicacion,
            $fecha_inicio,
            $fecha_fin,
            $categoria,
            $subs,
            $tipo_competencia
        );

        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
    }

    public function testModificarCompetenciaDatosInvalidos()
    {
        $id_competencia = 1;
        $nombre = ""; // Nombre vacío
        $ubicacion = "Nuevo Estadio";
        $fecha_inicio = "2024-12-03";
        $fecha_fin = "2024-12-07";
        $categoria = "Junior";
        $subs = "Juveniles";
        $tipo_competencia = "Grupal";

        $respuesta = $this->eventos->modificarCompetencia(
            $id_competencia,
            $nombre,
            $ubicacion,
            $fecha_inicio,
            $fecha_fin,
            $categoria,
            $subs,
            $tipo_competencia
        );

        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertStringContainsString("inválido", $respuesta['mensaje']);
    }

    public function testEliminarEventoExitoso()
    {
        $id_competencia = 2; // Suponiendo que este ID existe en la base de datos
        $respuesta = $this->eventos->eliminar_evento($id_competencia);

        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertTrue($respuesta['ok']);
    }

    public function testEliminarEventoNoExistente()
    {
        $id_competencia = 9999; // ID que no existe
        $respuesta = $this->eventos->eliminar_evento($id_competencia);

        $this->assertNotNull($respuesta);
        $this->assertIsArray($respuesta);
        $this->assertFalse($respuesta['ok']);
        $this->assertStringContainsString("no encontrado", $respuesta['mensaje']);
    }
}
