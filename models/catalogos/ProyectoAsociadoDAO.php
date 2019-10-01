<?php 
header("Content-Type: text/html;charset=utf-8");

class ProyectoAsociadoDAO
{

	public static function executeQuery($sql){

		try 
		{
			Conexion::$connect = new Conexion();
			Conexion::$query = $sql;
			Conexion::$result = Conexion::$connect->prepare(Conexion::$query);
			Conexion::$result->execute();
			return Conexion::$result->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(Exception $e)
		{
			die("Error al ejecutar consulta $sql. Error! : ". $e->getMessage());
		}

	}

	public static function All(){
		$sql = "SELECT id, ix, no, cl FROM ad_pra ORDER BY no";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;
	}

	public static function Save($data){

		try{

			$sql = "INSERT INTO ad_pra SET ix = :ix, fx = :fx, no = :no";

			Conexion::$connect = new Conexion();
			Conexion::$query = $sql;
			Conexion::$prepare = Conexion::$connect->prepare(Conexion::$query);
			Conexion::$prepare->bindParam(':ix', $data['ix']);
			Conexion::$prepare->bindParam(':fx', $data['fx']);
			Conexion::$prepare->bindParam(':no', $data['no']);

			$result = Conexion::$prepare->execute();
			return $result;

		}catch( Exception $e ){
			die("Error al registrar Proyecto. Error! : ". $e->getMessage());
		}

	}

	public static function SaveModify($data){

		try{

			$sql = "UPDATE ad_pra SET no = :no, sx = :sx WHERE ix = :ix";

			Conexion::$connect = new Conexion();
			Conexion::$query = $sql;
			Conexion::$prepare = Conexion::$connect->prepare(Conexion::$query);
			Conexion::$prepare->bindParam(':no', $data['no']);
			Conexion::$prepare->bindParam(':sx', $data['sx']);
			Conexion::$prepare->bindParam(':ix', $data['ix']);

			$result = Conexion::$prepare->execute();
			return $result;

		}catch( Exception $e ){
			die("Error al modificar Proyecto. Error! : ". $e->getMessage());
		}

	}
	
}
