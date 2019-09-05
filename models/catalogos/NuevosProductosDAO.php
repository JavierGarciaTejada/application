<?php 
header("Content-Type: text/html;charset=utf-8");

class NuevosProductosDAO
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
		$sql = "SELECT id, ix, no, cl FROM ad_prd WHERE sx = 0 ORDER BY no";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;
	}
	
}
