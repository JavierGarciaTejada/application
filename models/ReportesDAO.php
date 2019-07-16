<?php 
header("Content-Type: text/html;charset=utf-8");

class ReportesDAO
{

	private static function _connectDBInstance()
	{
		Conexion::$connect = Conexion::connectionDinamic("localhost", "3306", "evaluacion", "root", "jonas", "utf8");
		Conexion::$connect->query("SET NAMES 'utf8'");
	}

	public static function executeQuery($sql){

		try 
		{
			// Conexion::$connect = new Conexion();
			self::_connectDBInstance();
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


	public static function TotalProcesoNuevas(){

		$sql = "SELECT * FROM so_sol WHERE et IN (619056264933549, 619056264933547)";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;

	}

	
}
