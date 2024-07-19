<?php

	if(is_file('modelo/resEvento.php')){
		require_once('modelo/resEvento.php');
	}else{
		$respuesta[mensaje]="error";
		return $respuesta;
	}

	class Eventos extends ResEvento{

		private $idCompetencia;			//id_competencia;							
		private $tipo_competencia;		//tipo_competencia;								
		private $nombre;				//nombre;			
		private $categoria;				//categoria;				
		private $subs;					//subs;	
		private $lugar_competencia;		//lugar_competencia;
		private $fecha_inicio;			//fecha_inicio;					
		private $fecha_fin;				//fecha_fin;			

		public function __construct(){
			parent::__construct();
		}

		// Metodo Get's//

		public function get_idCompetencia(){
			return $this->idCompetencia;
		}
		public function get_tipo_competencia(){
			return $this->tipo_competencia;
		}
		public function get_nombre(){
			return $this->nombre;
		}
		public function get_categoria(){
			return $this->categoria;
		}
		public function get_subs(){
			return $this->subs;
		}
		public function get_lugar_competencia(){
			return $this->lugar_competencia;
		}
		public function get_fecha_inicio(){
			return $this->fecha_inicio;
		}
		public function get_fecha_fin(){
			return $this->fecha_fin;
		}

		// Metodo Set's

		public function set_idCompetencia($value){
			$this->idCompetencia = $value;
		}
		public function set_tipo_competencia($value){
			$this->tipo_competencia = $value;
		}
		public function set_nombre($value){
			$this->nombre = $value;
		}
		public function set_categoria($value){
			$this->categoria = $value;
		}
		public function set_subs($value){
			$this->subs = $value;
		}
		public function set_lugar_competencia($value){
			$this->lugar_competencia = $value;
		}
		public function set_fecha_inicio($value){
			$this->fecha_inicio = $value;
		}
		public function set_fecha_fin($value){
			$this->fecha_fin = $value;
		}

		// Metodo Consulta

		public function consultaGeneral(){

			try{
				$sql = "SELECT * FROM competencia ;";
				$con = parent::conecta();
				$res = $con->query($sql);
				$res = $res->fetchAll(PDO::FETCH_ASSOC);
				if(isset($res["id_competencia"]))
					{
					foreach ($res as $key => $value) {
					$this->idCompetencia = $key["id_competencia"];
					$this->tipo_competencia = $key["tipo_competencia"];
					$this->nombre = $key["nombre"];
					$this->categoria = $key["categoria"];
					$this->subs = $key["subs"];
					$this->lugar_competencia = $key["lugar_competencia"];
					$this->fecha_inicio = $key["fecha_inicio"];
					$this->fecha_fin = $key["fecha_fin"];
					}
				
				}
				$resultado["ok"]=true;
				$resultado["respuesta"]=$res;
			}catch(Exception $e){
				$resultado["ok"]= false;
				$resultado["respuesta"]=$e;
			}
			return $resultado;

		}

		// Metodo Consulta Evento

		public function consultaEvento(){

			try{
				$sql = "SELECT * FROM competencia WHERE id_competencia = :idCompetencia;";
				$valores = array(':idCompetencia' => $this->idCompetencia);
				$con = parent::conecta();
				$con = $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
				$res = $con->prepare($sql);
				$res = $con(execute);
				$res = $res->fetchAll(PDO::FETCH_ASSOC);
				$respuesta["ok"]= true;
				$respuesta["respuesta"] = $res;
			}catch(Exception $e){
				$respuesta["ok"] = false;
				$respuesta["respuesta"]= $e;
			}
				return $respuesta;
		}

		// Metodo Insertar

		public function insertarEvento(){

			try{
				$sql = "INSERT INTO competencia VALUES DEFAULT,:tipo_competencia,:nombre,:categoria,:subs,:lugar_competencia,:fecha_inicio,:fecha_fin;";
				$valores = array(
					':tipo_competencia' => $this->tipo_competencia,
					':nombre' => $this->nombre,
					':categoria' => $this->categoria,
					':subs' => $this->subs,
					':lugar_competencia' => $this->lugar_competencia,
					':fecha_inicio' => $this->fecha_inicio,
					':fecha_fin' => $this->fecha_fin
				);
				$con = parent::conectar();
				$con = $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
				$res = $con->prepare($sql);
				$res->execute($valores);
				$resultado["ok"] = true;			
			}catch(exception $e){
				$resultado["ok"] =false;
				$resultado["mensaje"] = $e;
			}
			return $resultado;
		}

		// Metodo Modificar

		public function modificarEvento(){

			try{
				$sql = "UPDATE  competencia SET 
						tipo_competencia = :tipo_competencia,
						nombre = :nombre,
						categoria = :categoria,
						subs = :subs,
						lugar_competencia = :lugar_competencia,
						fecha_inicio = :fecha_inicio,
						fecha_fin = :fecha_fin 
					WHERE id_competencia = :id_competencia";
				$valores = array(
					':id_competencia' => $this->idCompetencia,
					':tipo_competencia' => $this->tipo_competencia,
					':nombre' => $this->nombre,
					':categoria' => $this->categoria,
					':subs' => $this->subs,
					':lugar_competencia' => $this->lugar_competencia,
					':fecha_inicio' => $this->fecha_inicio,
					':fecha_fin' => $this->fecha_fin
				);
				$con = parent::conectar();
				$res = $con->prepare($sql);
				$res->execute($valores);
				$resultado["ok"] = true;			
			}catch(exception $e){
				$resultado["ok"] =false;
				$resultado["mensaje"] = $e;
			}
			return $resultado;
		}

		// Metodo Eliminar

		public function eliminarEvento(){

			try{
				$sql = "DELETE FROM competencia	WHERE id_competencia = :id_competencia";
				$datos = array(
					':id_competencia' => $this->idCompetencia);
				$con = parent::conectar();
				$res = $con->prepare($sql);
				$res->execute($datos);
				$resultado["ok"] = true;			
			}catch(exception $e){
				$resultado["ok"] =false;
				$resultado["mensaje"] = $e;
			}
			return $resultado;
		}	

		//metodos de Subs

		public function metodosSubs($method,$values)
		{
			if(is_file('modelo/eventosSubs.php')){
				require_once('modelo/eventosSubs.php');
			}else{
				$respuesta[mensaje]="error";
				return $respuesta;
			} 
			$objSubs = new Subs();
			switch ($method) {
				case 'Get':
					$datos = $objSubs->getAll();
					return $datos;
					break;
				
				case 'Set':
					$datos = $objSubs->setAll($values);
					break;

				case 'insertar':
					$datos = $objSubs->insertar();
					return $datos;
					break;

				case 'consultar':
					$datos = $objSubs->consultar();
					return $datos;
					break;

				case 'modificar':
					$datos = $objSubs->modificar();
					return $datos;
					break;

				case 'eliminar':
					$datos = $objSubs->eliminar();
					return $datos;
					break;	
			}
		}

		//metodo Categoria

		public function metodosCategoria($method,$values)
		{
			if(is_file('modelo/eventosCategoria.php')){
				require_once('modelo/eventosCategoria.php');
			}else{
				$respuesta[mensaje]="error";
				return $respuesta;
			} 
			$objCategoria = new Categorias();
			switch ($method) {
				case 'Get':
					$datos = $objCategoria->getAll();
					return $datos;
					break;
				
				case 'Set':
					$datos = $objCategoria->setAll($values);
					break;

				case 'insertar':
					$datos = $objCategoria->insertar();
					return $datos;
					break;

				case 'consultar':
					$datos = $objCategoria->consultar();
					return $datos;
					break;

				case 'modificar':
					$datos = $objCategoria->modificar();
					return $datos;
					break;

				case 'eliminar':
					$datos = $objCategoria->eliminar();
					return $datos;
					break;	
			}
		}

		//metodos TipoCompetencia


		public function metodosTipo($method,$values)
		{
			if(is_file('modelo/eventosTipo.php')){
				require_once('modelo/eventosTipo.php');
			}else{
				$respuesta[mensaje]="error";
				return $respuesta;
			} 
			$objTipo = new Tipo();
			switch ($method) {
				case 'Get':
					$datos = $objTipo->getAll();
					return $datos;
					break;
				
				case 'Set':
					$datos = $objTipo->setAll($values);
					break;

				case 'insertar':
					$datos = $objTipo->insertar();
					return $datos;
					break;

				case 'consultar':
					$datos = $objTipo->consultar();
					return $datos;
					break;

				case 'modificar':
					$datos = $objTipo->modificar();
					return $datos;
					break;

				case 'eliminar':
					$datos = $objTipo->eliminar();
					return $datos;
					break;	
			}
		}


	}

?>