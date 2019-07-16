<?php 
header("Content-Type: text/html;charset=utf-8");

class ResultadosDAO
{
	public static $_connect_instance;
	public static $_fecha_ini;
	public static $_fecha_fin;
	//NdU8S5KgrCus8jXa
	private static function _connectDBInstance()
	{
		Conexion::$connect = Conexion::connectionDinamic("localhost", "3306", "evaluaciones", "root", "jonas", "utf8");
		Conexion::$connect->query("SET NAMES 'utf8'");
	}

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

		$sql = "SELECT id, ix, no, cl FROM ad_res";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;

	}
	
}
