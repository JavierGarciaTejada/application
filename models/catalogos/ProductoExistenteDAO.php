<?php 
header("Content-Type: text/html;charset=utf-8");

class ProductoExistenteDAO
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

	public static function Producto(){
		$sql = "SELECT id, no, cl FROM ad_prd_cat WHERE sx = 0 AND nv = 1 ORDER BY no";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;
	}

	public static function Subproducto($rx){
		$sql = "SELECT id, no, cl FROM ad_prd_cat WHERE sx = 0 AND nv = 2 AND rx = $rx ORDER BY no";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;
	}
	
}
