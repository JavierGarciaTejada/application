<?php 
header("Content-Type: text/html;charset=utf-8");

class PuestosDAO
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
		$sql = "SELECT id, ix, no FROM ad_pue ORDER BY no";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;
	}
	
}
