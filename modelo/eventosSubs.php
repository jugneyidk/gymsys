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

		// consulta

		public function consultar(){
			try
			{
			$sql = " SELECT * FROM subs ;";
			$con = parent::conectar();
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

		// Metodo Insertar

		public function insertarEvento(){

			try{
				$sql = "INSERT INTO subs VALUES DEFAULT,:nombre,:edadminima,:edadmaxima;";
				$valores = array(
					':nombre' => $this->nombre,
					':edadMinima' => $this->edadMinima,
					':edadMaxima' => $this->edadMaxima
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
				$sql = "DELETE FROM subs WHERE id_sub = :idSubs";
				$val = array( ':idSubs' => $this->idSubs );
				$con = parent::conectar();
				$res = $con->prepare($sql);
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