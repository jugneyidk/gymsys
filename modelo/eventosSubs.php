<?php

	if(is_file('modelo/datos.php')){
		require_once('modelo/datos.php');
	}else{
		$respuesta[mensaje]="error";
		return $respuesta;
	}

	class Subs extends datos
	{

		//Atributos

		private $conexion;
		private $idSubs;		//idSubs
		private $nombre;		//nombre
		private $edadMinima;	//edadMinima
		private $edadMaxima;	//edadMaxima

		// Get's

		public function get_idSubs()
		{	return $this->idSubs;	}
		public function get_nombre()
		{	return $this->nombre;	}
		public function get_edadMinima()
		{	return $this->edadMinima;	}
		public function get_edadMaxima()
		{	return $this->edadMaxima;	}

		// Set's

		public function set_idSubs($values)
		{	$this->idSubs = $values;	}
		public function set_nombre($values)
		{	$this->nombre = $values;	}
		public function set_edadMinima($values)
		{	$this->edadMinima = $values;	}
		public function set_edadMaxima($values)
		{	$this->edadMaxima = $values;	}

		public function __construct(){

			$this->conexion = $this->conecta();

		}

		public function getAll($value){
			$respuesta = array(

				"idSubs" => $this->idCategoria,
				"nombre" => $this->nombre,
				"edadMinimo" => $this->pesoMinimo,
				"edadMaximo" => $this->pesoMaximo

			);
			return $respuesta;
		}

		public function setAll($value)
		{
				print_r($value);	
				$this->nombre = $value["nombre"];
				$this->edadMinima = $value["edadMinimo"];
				$this->edadMaxima= $value["edadMaximo"];
			
		}

		// consulta

		public function consultar(){
			try
			{
			$sql = " SELECT * FROM subs ;";
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

		public function insertar(){

			try{
				$sql = "INSERT INTO subs VALUES (DEFAULT,:nombre,:edadMinima,:edadMaxima)";
				$valores = array(
					':nombre' => $this->nombre,
					':edadMinima' => $this->edadMinima,
					':edadMaxima' => $this->edadMaxima
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

		public function modificar(){

			try{
				$sql = "UPDATE subs SET 
						nombre = :nombre,
						edad_minima = :edadMinima,
						edad_maxima = :edadMaxima
					WHERE id_sub = :idSubs";
				$valores = array(
					':nombre' => $this->nombre,
					':edadMinima' => $this->edadMinima,
					':edadMaxima' => $this->edadMaxima,
					':idSubs' => $this->idSubs
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

		public function eliminar(){

			try{
				$sql = "DELETE FROM subs WHERE id_sub = :idSubs";
				$val = array( ':idSubs' => $this->idSubs );
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