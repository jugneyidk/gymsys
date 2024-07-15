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

		// consulta

		public function consultar(){
			try
			{
			$sql = " SELECT * FROM categorias ;";
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
				$sql = "INSERT INTO categorias VALUES DEFAULT,:nombre,:pesoMinimo,:pesoMaximo";
				$valores = array(
					':nombre' => $this->nombre,
					':pesoMinimo' => $this->pesoMinimo,
					':pesoMaximo' => $this->pesoMaximo
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
				$sql = "DELETE FROM categorias WHERE id_categorias = :idCategorias";
				$val = array( ':idCategorias' => $this->idCategoria );
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