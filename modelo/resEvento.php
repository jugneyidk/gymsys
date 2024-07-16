<?php

	if(is_file('modelo/atletas.php')){
		require_once('modelo/atletas.php');
	}else{
		$respuesta[mensaje]="error";
		return $respuesta;}
	

	class ResEvento extends Atleta

	{


		private $idCompetencia;		//idCompetencia
		private $idAtleta;			//idAtleta
		private	$arranque;			//arranque
		private $envion;			//envion
		private $medallaArranque;	//medallaArranque
		private	$medallaEnvion;		//medallaEnvion
		private $medallaTotal;		//medallaTotal
		private $total;				//total

		public function __construct(){
			parent::__construct();
		}

		// Get's

		public function get_idCompetencia()
		{	return $this->idCompetencia;   }
		public function get_idAtleta()
		{	return $this->idAtleta;   }
		public function get_arranque()
		{	return $this->arranque;   }
		public function get_envion()
		{	return $this->envion;   }
		public function get_medallaArranque()
		{	return $this->medallaArranque;   }
		public function get_medallaEnvion()
		{	return $this->medallaEnvion;   }
		public function get_medallaTotal()
		{	return $this->medallaTotal;   }
		public function get_total()
		{	return $this->total;   }

		// Set's

		public function set_idCompetencia($value)
		{	$this->idCompetencia = $value;	}
		public function set_idAtleta($value)
		{	$this->idAtleta = $value;	}
		public function set_arranque($value)
		{	$this->arranque = $value;	}
		public function set_envion($value)
		{	$this->envion = $value;	}
		public function set_medallaArranque($value)
		{	$this->medallaArranque = $value;	}
		public function set_medallaEnvion($value)
		{	$this->medallaEnvion = $value;	}
		public function set_medallaTotal($value)
		{	$this->medallaTotal = $value;	}
		public function set_total($value)
		{	$this->total = $value;	}

		// insertar

		public function insertar(){

			try{
				$sql = "INSERT INTO resultado_competencia VALUES 
				:id_competencia,
				:id_atleta,
				:arranque,
				:envion,
				:medalla_arranque,
				:medalla_envion,
				:medalla_total,
				:total;
				";
				$valores = array(
					':id_competencia' => $this->idCompetencia ,
					':id_atleta' => $this->idAtleta ,
					':arranque' => $this->arranque ,
					':envion' => $this->envion ,
					':medalla_arranque' => $this->medallaArranque ,
					':medalla_envion' => $this->medallaEnvion ,
					':medalla_total' => $this->medallaTotal ,
					':total' => $this->total
				);
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

		// consultar Completo por atleta

		public function consultarResultado()
		{

			try{
				$sql = "SELECT * FROM resultado_competencia WHERE id_competencia = :idCompetencia; AND id_atleta = :id_atleta";
				$valores = array(':idCompetencia' => $this->idCompetencia ,
					':id_atleta' => $this->idAtleta);
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

		// Metodo Modificar

		public function modificarEvento(){

			try{
				$sql = "UPDATE resultado_competencia SET 
					id_competencia = :idCompetencia,
					id_atleta = :idAtleta,
					arranque = :arranque,
					envion = :envion,
					medallaArranque = :medallaArranque,
					medallaEnvion = :medallaEnvion,
					medallaTotal = :medallaTotal,
					total = :total	
					WHERE id_competencia = :id_competencia";
				$valores = array(
					':idCompetencia' => $this->idCompetencia,
					':idAtleta' => $this->idAtleta,
					':arranque' => $this->arranque,
					':envion' => $this->envion,
					':medallaArranque' => $this->medallaArranque,
					':medallaEnvion' => $this->medallaEnvion,
					':medallaTotal' => $this->medallaTotal,
					':total' => $this->total
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
				$sql = "DELETE FROM resultado_competencia WHERE id_competencia = :idCompetencia AND id_atleta = :idAtleta";
				$datos = array(
					':idCompetencia' => $this->idCompetencia,
					':idAtleta' => $this->idAtleta
				);
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

		

	}

?>