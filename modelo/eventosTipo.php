<?php

	if(is_file('modelo/datos.php')){
		require_once('modelo/datos.php');
	}else{
		$respuesta[mensaje]="error";
		return $respuesta;
	}

	class Tipo extends datos
	{

		//Atributos

		private $idTipoCompetencia;		//idTipoCompetencia
		private $nombre;				//nombre
		

		// Get's

		public function get_idTipoCompetencia()
		{	return $this->idTipoCompetencia;  }
		public function get_nombre()
		{	return $this->nombre;  }

		// Set's

		public function set_idTipoCompetencia($value)
		{	$this->idTipoCompetencia($value);	}
		public function set_nombre($value)
		{	$this->nombre($value);	}

		public function __construct(){

			$this->conexion = $this->conecta();

		}

		public function getAll($value){
			$respuesta = array(

				"idSubs" => $this->idTipoCompetencia,
				"nombre" => $this->nombre,

			);
			return $respuesta;
		}

		public function setAll($value){
			
				$this->idSubs = $value["idTipoCompetencia"];
				$this->nombre = $value["nombre"];
		}

		// consulta

		public function consultar(){
			try
			{
			$sql = " SELECT * FROM tipo_competencia ;";
			$res = $this->conexion->query($sql);
			$res = $res->fetchAll(PDO::FETCH_ASSOC);
				$resultado["ok"]=true;
				$resultado["respuesta"]=$res;
			}catch(Exception $e){
				$resultado["ok"]= false;
				$resultado["respuesta"]=$e;
			}
			return $respuesta;

		}

		// Metodo Insertar

		public function insertarEvento(){

			try{
				$sql = "INSERT INTO tipo_competencia VALUES DEFAULT,:nombre";
				$valores = array(
					':nombre' => $this->nombre
				);
				
				$res = $this->conexion->prepare($sql);
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
				$sql = "UPDATE tipo_competencia SET 
						nombre = :nombre
					WHERE id_tipo_competencia = :idTipoCompetencia";
				$valores = array(
					':nombre' => $this->nombre,
					':idTipoCompetencia' => $this->idTipoCompetencia
				);
				$res = $this->conexion->prepare($sql);
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
				$sql = "DELETE FROM tipo_competencia WHERE id_tipo_competencia = :idTipoCompetencia";
				$val = array( ':idTipoCompetencia' => $this->idTipoCompetencia );
				$res = $this->conexion->prepare($sql);
				$res->execute($val);
				$resultado["ok"] = true;			
			}catch(exception $e){
				$resultado["ok"] =false;
				$resultado["mensaje"] = $e;
			}
			return $resultado;
		}

	}

?>