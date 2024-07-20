<?php

	if(is_file('modelo/datos.php')){
		require_once('modelo/datos.php');
	}else{
		$respuesta[mensaje]="error";
		return $respuesta;
	}

	class Categorias extends datos
	{

		//Atributos

		private $conexion;
		private $idCategoria;		//idCategoria
		private $nombre;			//nombre
		private $pesoMinimo;		//pesoMinimo
		private $pesoMaximo;		//pesoMaximo
		

		// Get's

		public function get_idCategoria()
		{	return $this->idCategoria;	}
		public function get_nombre()
		{	return $this->nombre;	}
		public function get_pesoMinimo()
		{	return $this->pesoMinimo;	}
		public function get_pesoMaximo()
		{	return $this->pesoMaximo;	}

		// Set's

		public function set_idCategoria($value){
			$this->idCategoria = $value;
		}
		public function set_nombre($value){
			$this->nombre = $value;
		}
		public function set_pesoMinimo($value){
			$this->pesoMinimo = $value;
		}
		public function set_pesoMaximo($value){
			$this->pesoMaximo = $value;
		}

		public function __construct(){

			$this->conexion = $this->conecta();

		}


		// consulta

		public function consultar(){
			try
			{
			$sql = " SELECT * FROM categorias ;";
			$res = $this->conexion->query($sql);
			$res = $res->fetchAll(PDO::FETCH_ASSOC);
				$resultado["ok"]=true;
				$resultado["respuesta"]=$res;
			}catch(Exception $e){
				$resultado["ok"]= false;
				$resultado["respuesta"]=$e;
			}
			return $resultado;

		}
		public function getAll(){
			$respuesta = array(

				"idCategoria" => $this->idCategoria,
				"nombre" => $this->nombre,
				"pesoMinimo" => $this->pesoMinimo,
				"pesoMaximo" => $this->pesoMaximo

			);
			return $respuesta;
		}

		public function setAll($value){
			
				$this->nombre = $value["nombre"];
				$this->pesoMinimo = $value["pesoMinimo"];
				$this->pesoMaximo= $value["pesoMaximo"];
			
		}

		// Metodo Insertar

		public function insertar(){

			try{
				
				$sql = "INSERT INTO categorias VALUES (DEFAULT,:nombre,:pesoMinimo,:pesoMaximo)";
				$valores = array(
					':nombre' => $this->nombre,
					':pesoMinimo' => $this->pesoMinimo,
					':pesoMaximo' => $this->pesoMaximo
				);
			
				$res = $this->conexion->prepare($sql);
				$res->execute($valores);
				$resultado["ok"] = true;
			}catch(exception $e){
				$resultado["ok"] =false;
				$resultado["mensaje"] = $e;
				echo $e;
			}
			return $resultado;
		}

		// Metodo Modificar

		public function modificar(){

			try{
				$sql = "UPDATE categorias SET 
						nombre = :nombre,
						peso_minimo = :pesoMinimo,
						peso_maximo = :pesoMaximo
					WHERE id_categoria = :idCategoria";
				$valores = array(
					':nombre' => $this->nombre,
					':pesoMinimo' => $this->pesoMinimo,
					':pesoMaximo' => $this->pesoMaximo,
					':idCategoria' => $this->idCategoria
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
				$sql = "DELETE FROM categorias WHERE id_categorias = :idCategorias";
				$val = array( ':idCategorias' => $this->idCategoria );
				
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