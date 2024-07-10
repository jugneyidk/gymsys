<?php

	if(is_file('modelo/atletas.php')){
		require_once('modelo/atletas.php');
	}else{
		$respuesta[mensaje]="error";
		return $respuesta;
	}

	class eventos extends atleta{

		private $idCompetencia;			//id_competencia;							
		private $tipo_competencia;		//tipo_competencia;								
		private $nombre;							//nombre;			
		private $categoria;						//categoria;				
		private $subs;									//subs;	
		private $lugar_competencia;	//lugar_competencia;									
		private $fecha_inicio;					//fecha_inicio;					
		private $fecha_fin;							//fecha_fin;			

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
				$con = $con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
				$res = $con->query($sql);
				$res = $res->fetchAll(PDO::FETCH_ASSOC);
				$resultado["ok"]=true;
				$resultado["respuesta"]=$res;
			}catch(Exception $e){
				$resultado["ok"]= false;
				$resultado["respuesta"]=$e;
			}
			return $respuesta;

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
				$con = parent::conectar();
				$res = $con->prepare($sql);
				$res->execute();
				$resultado["ok"] = true;			
			}catch(exception $e){
				$resultado["ok"] =false;
				$resultado["mensaje"] = $e;
			}
			return $resultado;
		}	


	}

?>