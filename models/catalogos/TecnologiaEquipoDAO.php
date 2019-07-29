<?php 
header("Content-Type: text/html;charset=utf-8");

class TecnologiaEquipoDAO
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

		$sql = "SELECT id, ix, no, cl FROM ad_teq WHERE sx = 0 ORDER BY no";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;

	}

	public static function Save($data){

		try{

			$sql = "INSERT INTO ad_teq SET ix = :ix, fx = :fx, no = :no";

			Conexion::$connect = new Conexion();
			Conexion::$query = $sql;
			Conexion::$prepare = Conexion::$connect->prepare(Conexion::$query);
			Conexion::$prepare->bindParam(':ix', $data['ix']);
			Conexion::$prepare->bindParam(':fx', $data['fx']);
			Conexion::$prepare->bindParam(':no', $data['no']);

			$result = Conexion::$prepare->execute();
			return $result;

		}catch( Exception $e ){
			die("Error al registrar tecnología. Error! : ". $e->getMessage());
		}

	}

	public static function SaveModify($data){

		try{

			$sql = "UPDATE ad_teq SET no = :no, sx = :sx WHERE ix = :ix";

			Conexion::$connect = new Conexion();
			Conexion::$query = $sql;
			Conexion::$prepare = Conexion::$connect->prepare(Conexion::$query);
			Conexion::$prepare->bindParam(':no', $data['no']);
			Conexion::$prepare->bindParam(':sx', $data['sx']);
			Conexion::$prepare->bindParam(':ix', $data['ix']);

			$result = Conexion::$prepare->execute();
			return $result;

		}catch( Exception $e ){
			die("Error al modificar tecnología. Error! : ". $e->getMessage());
		}

	}
	
}
