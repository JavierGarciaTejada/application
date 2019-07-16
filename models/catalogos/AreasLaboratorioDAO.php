<?php 
header("Content-Type: text/html;charset=utf-8");
//include_once Config::$configuration->get('modelsFolder') . 'ConexionesAFA.php';

class AreasLaboratorioDAO 
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


	public static function AreasLaboratorio(){

		$sql = "SELECT id, ix, no, gr FROM ad_alb";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;

	}

	
}
