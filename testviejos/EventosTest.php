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
      $lugar_competencia = "Ciudad Deportiva Lara";
      $fecha_inicio = "2024-12-01";
      $fecha_fin = "2024-12-05";
      $categoria = "6";
      $subs = "6";
      $tipo_competencia = "9";
      $datos = [
         "nombre" => $nombre,
         "lugar_competencia" => $lugar_competencia,
         "fecha_inicio" => $fecha_inicio,
         "fecha_fin" => $fecha_fin,
         "categoria" => $categoria,
         "subs" => $subs,
         "tipo_competencia" => $tipo_competencia,
      ];
      $respuesta = $this->eventos->incluir_evento($datos);
      // Validamos que la respuesta sea exitosa
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertTrue($respuesta['ok']);
   }
   public function testIncluirEventoNoValido()
   {
      $nombre = "Campeonato Nacional.";
      $lugar_competencia = "Ciudad Deportiva Lara";
      $fecha_inicio = "";
      $fecha_fin = "final";
      $categoria = "";
      $subs = "6";
      $tipo_competencia = "9";
      $datos = [
         "nombre" => $nombre,
         "lugar_competencia" => $lugar_competencia,
         "fecha_inicio" => $fecha_inicio,
         "fecha_fin" => $fecha_fin,
         "categoria" => $categoria,
         "subs" => $subs,
         "tipo_competencia" => $tipo_competencia,
      ];
      $respuesta = $this->eventos->incluir_evento($datos);
      // Validamos que la respuesta sea que no es valido
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("El nombre del evento debe ser letras y/o números (entre 3 y 100 caracteres)", $respuesta['mensaje']);
   }
   public function testIncluirEventoYaExiste()
   {
      $nombre = "Campeonato Nacional";
      $lugar_competencia = "Ciudad Deportiva Lara";
      $fecha_inicio = "2024-12-01";
      $fecha_fin = "2024-12-05";
      $categoria = "6";
      $subs = "6";
      $tipo_competencia = "9";
      $datos = [
         "nombre" => $nombre,
         "lugar_competencia" => $lugar_competencia,
         "fecha_inicio" => $fecha_inicio,
         "fecha_fin" => $fecha_fin,
         "categoria" => $categoria,
         "subs" => $subs,
         "tipo_competencia" => $tipo_competencia,
      ];
      $respuesta = $this->eventos->incluir_evento($datos);
      // Validamos que la respuesta sea que ya existe la competencia
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("Ya existe una competencia con este nombre", $respuesta['mensaje']);
   }
   public function testModificarCompetenciaExitoso()
   {
      $id_competencia = "11";
      $nombre = "Campeonato Nacional";
      $lugar_competencia = "Ciudad Deportiva Lara";
      $fecha_inicio = "2024-12-01";
      $fecha_fin = "2024-12-05";
      $categoria = "6";
      $subs = "6";
      $tipo_competencia = "9";
      $datos = [
         "id_competencia" => $id_competencia,
         "nombre" => $nombre,
         "lugar_competencia" => $lugar_competencia,
         "fecha_inicio" => $fecha_inicio,
         "fecha_fin" => $fecha_fin,
         "categoria" => $categoria,
         "subs" => $subs,
         "tipo_competencia" => $tipo_competencia,
      ];
      $respuesta = $this->eventos->modificarCompetencia($id_competencia, $nombre, $lugar_competencia, $fecha_inicio, $fecha_fin, $categoria, $subs, $tipo_competencia);
      // Validamos que la respuesta sea exitosa
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertTrue($respuesta['ok']);
   }
   public function testModificarCompetenciaNoValido()
   {
      $id_competencia = "11";
      $nombre = "Campeonato Nacional";
      $lugar_competencia = "";
      $fecha_inicio = "2023-12-01";
      $fecha_fin = "2024-12-";
      $categoria = "Junior";
      $subs = "6";
      $tipo_competencia = "9";
      $datos = [
         "id_competencia" => $id_competencia,
         "nombre" => $nombre,
         "lugar_competencia" => $lugar_competencia,
         "fecha_inicio" => $fecha_inicio,
         "fecha_fin" => $fecha_fin,
         "categoria" => $categoria,
         "subs" => $subs,
         "tipo_competencia" => $tipo_competencia,
      ];
      $respuesta = $this->eventos->modificarCompetencia($id_competencia, $nombre, $lugar_competencia, $fecha_inicio, $fecha_fin, $categoria, $subs, $tipo_competencia);
      // Validamos que la respuesta sea incorrecta
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("La ubicación debe ser letras y/o números (entre 3 y 100 caracteres)", $respuesta['mensaje']);
   }
   public function testModificarCompetenciaNoExiste()
   {
      $id_competencia = "1";
      $nombre = "Campeonato Nacional";
      $lugar_competencia = "Ciudad Deportiva Lara";
      $fecha_inicio = "2024-12-01";
      $fecha_fin = "2024-12-05";
      $categoria = "6";
      $subs = "6";
      $tipo_competencia = "9";
      $datos = [
         "id_competencia" => $id_competencia,
         "nombre" => $nombre,
         "lugar_competencia" => $lugar_competencia,
         "fecha_inicio" => $fecha_inicio,
         "fecha_fin" => $fecha_fin,
         "categoria" => $categoria,
         "subs" => $subs,
         "tipo_competencia" => $tipo_competencia,
      ];
      $respuesta = $this->eventos->modificarCompetencia($id_competencia, $nombre, $lugar_competencia, $fecha_inicio, $fecha_fin, $categoria, $subs, $tipo_competencia);
      // Validamos que la respuesta sea incorrecta
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("Esta competencia no existe", $respuesta['mensaje']);
   }
   public function testObtenerCompetenciaExitoso()
   {
      $id_competencia = "11";
      $respuesta = $this->eventos->obtenerCompetencia($id_competencia);
      // Validamos que la respuesta sea exitosa
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertTrue($respuesta['ok']);
      $this->assertIsArray($respuesta['respuesta']);
   }
   public function testObtenerCompetenciaNoValido()
   {
      $id_competencia = "1e1";
      $respuesta = $this->eventos->obtenerCompetencia($id_competencia);
      // Validamos que la respuesta sea incorrecta
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("El ID de la competencia no es válido", $respuesta['mensaje']);
   }
   public function testObtenerCompetenciaNoExiste()
   {
      $id_competencia = "1";
      $respuesta = $this->eventos->obtenerCompetencia($id_competencia);
      // Validamos que la respuesta sea que no existe
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("Esta competencia no existe", $respuesta['mensaje']);
   }
   public function testListadoCompetenciaExitoso()
   {
      $respuesta = $this->eventos->listado_eventos();
      // Validamos que la respuesta sea un array de eventos
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertTrue($respuesta['ok']);
      $this->assertIsArray($respuesta['respuesta']);
   }
   public function testListadoCategoriasExitoso()
   {
      $respuesta = $this->eventos->listado_categoria();
      // Validamos que la respuesta sea un array de categorias
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertTrue($respuesta['ok']);
      $this->assertIsArray($respuesta['respuesta']);
   }
   public function testIncluirCategoriaExitoso()
   {
      $nombre = "81M";
      $pesoMinimo = "73";
      $pesoMaximo = "81.99";
      $respuesta = $this->eventos->incluir_categoria($nombre, $pesoMinimo, $pesoMaximo);
      // Validamos que se agregue el registro
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertTrue($respuesta['ok']);
   }
   public function testIncluirCategoriaNoValido()
   {
      $nombre = "81M";
      $pesoMinimo = "85";
      $pesoMaximo = "81.99";
      $respuesta = $this->eventos->incluir_categoria($nombre, $pesoMinimo, $pesoMaximo);
      // Validamos que no se pueda agregar
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("El peso máximo no puede ser menor o igual al peso mínimo", $respuesta['mensaje']);
   }
   public function testIncluirCategoriaYaExiste()
   {
      $nombre = "81M";
      $pesoMinimo = "75";
      $pesoMaximo = "81.99";
      $respuesta = $this->eventos->incluir_categoria($nombre, $pesoMinimo, $pesoMaximo);
      // Validamos que no se pueda agregar porque ya existe
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("Ya existe una categoria con este nombre", $respuesta['mensaje']);
   }
   public function testModificarCategoriaExitoso()
   {
      $id = "8";
      $nombre = "81F";
      $pesoMinimo = "75";
      $pesoMaximo = "81.99";
      $respuesta = $this->eventos->modificar_categoria($id, $nombre, $pesoMinimo, $pesoMaximo);
      // Validamos que se modifique el registro
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertTrue($respuesta['ok']);
   }
   public function testModificarCategoriaNoValido()
   {
      $id = "8";
      $nombre = "";
      $pesoMinimo = "75";
      $pesoMaximo = "";
      $respuesta = $this->eventos->modificar_categoria($id, $nombre, $pesoMinimo, $pesoMaximo);
      // Validamos que se retorne error
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("El nombre de la categoria debe ser letras y/o números (entre 3 y 50 caracteres)", $respuesta['mensaje']);
   }
   public function testModificarCategoriaNoExiste()
   {
      $id = "82";
      $nombre = "81M";
      $pesoMinimo = "75";
      $pesoMaximo = "80.99";
      $respuesta = $this->eventos->modificar_categoria($id, $nombre, $pesoMinimo, $pesoMaximo);
      // Validamos que se retorne que no existe
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("No existe esta categoria", $respuesta['mensaje']);
   }
   public function testEliminarCategoriaExitoso()
   {
      $id = "8";
      $respuesta = $this->eventos->eliminar_categoria($id);
      // Validamos que se retorne que se eliminó exitosamente
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertTrue($respuesta['ok']);
   }
   public function testEliminarCategoriaNoValido()
   {
      $id = "Categoria";
      $respuesta = $this->eventos->eliminar_categoria($id);
      // Validamos que se retorne que la categoria no es valida
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("La categoria no es un valor válido", $respuesta['mensaje']);
   }
   public function testEliminarCategoriaNoExiste()
   {
      $id = "333";
      $respuesta = $this->eventos->eliminar_categoria($id);
      // Validamos que se retorne que la categoria no existe
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("La categoria no existe", $respuesta['mensaje']);
   }
   public function testListadoSubsExitoso()
   {
      $respuesta = $this->eventos->listado_subs();
      // Validamos que se retorne que se eliminó exitosamente
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertTrue($respuesta['ok']);
      $this->assertIsArray($respuesta['respuesta']);
   }
   public function testIncluirSubExitoso()
   {
      $nombre = "U20";
      $edadMinima = "15";
      $edadMaxima = "20";
      $respuesta = $this->eventos->incluir_subs($nombre, $edadMinima, $edadMaxima);
      // Validamos que se retorne que se incluyó exitosamente
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertTrue($respuesta['ok']);
   }
   public function testIncluirSubNoValido()
   {
      $nombre = "U20";
      $edadMinima = "";
      $edadMaxima = "20";
      $respuesta = $this->eventos->incluir_subs($nombre, $edadMinima, $edadMaxima);
      // Validamos que se retorne que los datos no son válidos
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("La edad mínima no es un valor válido", $respuesta['mensaje']);
   }
   public function testIncluirSubYaExiste()
   {
      $nombre = "U20";
      $edadMinima = "15";
      $edadMaxima = "20";
      $respuesta = $this->eventos->incluir_subs($nombre, $edadMinima, $edadMaxima);
      // Validamos que se retorne que ya existe
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("Ya existe una sub con este nombre", $respuesta['mensaje']);
   }
   public function testModificarSubExitoso()
   {
      $id = "8";
      $nombre = "U20";
      $edadMinima = "16";
      $edadMaxima = "20";
      $respuesta = $this->eventos->modificar_sub($id, $nombre, $edadMinima, $edadMaxima);
      // Validamos que se retorne que se modificó la sub 
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertTrue($respuesta['ok']);
   }
   public function testModificarSubNoValido()
   {
      $id = "8";
      $nombre = "U20";
      $edadMinima = "menor";
      $edadMaxima = "20";
      $respuesta = $this->eventos->modificar_sub($id, $nombre, $edadMinima, $edadMaxima);
      // Validamos que se retorne que no es valido
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("La edad mínima no es un valor válido", $respuesta['mensaje']);
   }
   public function testModificarSubNoExiste()
   {
      $id = "83";
      $nombre = "U20";
      $edadMinima = "15";
      $edadMaxima = "20";
      $respuesta = $this->eventos->modificar_sub($id, $nombre, $edadMinima, $edadMaxima);
      // Validamos que se retorne que no existe esta sub
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("Esta sub no existe", $respuesta['mensaje']);
   }
   public function testModificarSubYaExiste()
   {
      $id = "8";
      $nombre = "U15";
      $edadMinima = "15";
      $edadMaxima = "20";
      $respuesta = $this->eventos->modificar_sub($id, $nombre, $edadMinima, $edadMaxima);
      // Validamos que se retorne que ya existe esta sub
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("Ya existe una sub con este nombre", $respuesta['mensaje']);
   }
   public function testEliminarSubExitoso()
   {
      $id = "8";
      $respuesta = $this->eventos->eliminar_sub($id);
      // Validamos que se retorne se eliminó exitosamente
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertTrue($respuesta['ok']);
   }
   public function testEliminarSubNoValido()
   {
      $id = "categoria";
      $respuesta = $this->eventos->eliminar_sub($id);
      // Validamos que se retorne que no es valida la id
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("El ID de la sub no es válido", $respuesta['mensaje']);
   }
   public function testEliminarSubNoExiste()
   {
      $id = "100";
      $respuesta = $this->eventos->eliminar_sub($id);
      // Validamos que se retorne que no existe la sub
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("Esta sub no existe", $respuesta['mensaje']);
   }
   public function testListadoTipoExitoso()
   {
      $respuesta = $this->eventos->listado_tipo();
      // Validamos que se retorne un array de tipos
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertTrue($respuesta['ok']);
      $this->assertIsArray($respuesta["respuesta"]);
   }
   public function testIncluirTipoExitoso()
   {
      $nombre = "Sub23";
      $respuesta = $this->eventos->incluir_tipo($nombre);
      // Validamos que se retorne que se incluyó el registro
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertTrue($respuesta['ok']);
   }
   public function testIncluirTipoNoValido()
   {
      $nombre = "";
      $respuesta = $this->eventos->incluir_tipo($nombre);
      // Validamos que se retorne que el nombre no es valido
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("El nombre del tipo de evento debe ser letras y/o números (entre 3 y 50 caracteres)", $respuesta['mensaje']);
   }
   public function testIncluirTipoYaExiste()
   {
      $nombre = "Sub23";
      $respuesta = $this->eventos->incluir_tipo($nombre);
      // Validamos que se retorne que el nombre ya existe
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("Ya existe este tipo de competencia", $respuesta['mensaje']);
   }
   public function testModificarTipoExitoso()
   {
      $id = "13";
      $nombre = "Sub24";
      $respuesta = $this->eventos->modificar_tipo($id, $nombre);
      // Validamos que se retorne que se modificó exitosamente
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertTrue($respuesta['ok']);
   }
   public function testModificarTipoNoValido()
   {
      $id = "13";
      $nombre = "Sub24";
      $respuesta = $this->eventos->modificar_tipo($id, $nombre);
      // Validamos que se retorne que el nombre no es valido
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("El nombre del tipo de evento debe ser letras y/o números (entre 3 y 50 caracteres)", $respuesta['mensaje']);
   }
   public function testModificarTipoYaExiste()
   {
      $id = "13";
      $nombre = "Senior";
      $respuesta = $this->eventos->modificar_tipo($id, $nombre);
      // Validamos que se retorne que el nombre ya existe
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("Ya existe un tipo de competencia con este nombre", $respuesta['mensaje']);
   }
   public function testModificarTipoNoExiste()
   {
      $id = "123";
      $nombre = "Sub25";
      $respuesta = $this->eventos->modificar_tipo($id, $nombre);
      // Validamos que se retorne que el tipo no existe
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("Este tipo de competencia no existe", $respuesta['mensaje']);
   }
   public function testEliminarTipoExitoso()
   {
      $id = "13";
      $respuesta = $this->eventos->eliminar_tipo($id,);
      // Validamos que se retorne que el tipo se eliminó
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertTrue($respuesta['ok']);
   }
   public function testEliminarTipoNoValido()
   {
      $id = "1/3";
      $respuesta = $this->eventos->eliminar_tipo($id,);
      // Validamos que se retorne que el id no es valido
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("El ID del tipo de competencia no es válido", $respuesta['mensaje']);
   }
   public function testEliminarTipoNoExiste()
   {
      $id = "13";
      $respuesta = $this->eventos->eliminar_tipo($id,);
      // Validamos que se retorne que el tipo no existe
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("Este tipo de competencia no existe", $respuesta['mensaje']);
   }
   public function testListadoAtletaInscritosExitoso()
   {
      $id = "11";
      $respuesta = $this->eventos->listado_atletas_inscritos($id,);
      // Validamos que se retorne el array de atletas inscritos
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertTrue($respuesta['ok']);
      $this->assertIsArray($respuesta["respuesta"]);
   }
   public function testListadoAtletaInscritosNoValido()
   {
      $id = "competencia";
      $respuesta = $this->eventos->listado_atletas_inscritos($id,);
      // Validamos que se retorne el array de atletas inscritos
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("El ID de la competencia no es válido", $respuesta["mensaje"]);
   }
   public function testListadoAtletaInscritosNoExiste()
   {
      $id = "100";
      $respuesta = $this->eventos->listado_atletas_inscritos($id,);
      // Validamos que se retorne el array de atletas inscritos
      $this->assertNotNull($respuesta);
      $this->assertIsArray($respuesta);
      $this->assertFalse($respuesta['ok']);
      $this->assertEquals("No existe esta competencia", $respuesta["mensaje"]);
   }
}
