<?php 

class HomeDAO
{
	public static $id;
	public static $ix;
	public static $fx;
	public static $cl;
	public static $no;
	public static $ds;
	public static $rl;
	public static $co;

	
	public static function getHomeMenu($filtro = "")
	{
		$filtro = ( empty($filtro) ) ? "" : " AND ".$filtro;
		try 
		{
			Conexion::$connect = new Conexion();
			Conexion::$query = "SELECT id, ix, fx, cl, no, ds, rl, co, resource, tm FROM si_app WHERE sx = :sx $filtro";
			Conexion::$result = Conexion::$connect->prepare(Conexion::$query);
			Conexion::$result->bindValue(":sx", '0', PDO::PARAM_STR);
			Conexion::$result->execute();
			Conexion::$filas = Conexion::$result->fetchAll(PDO::FETCH_ASSOC);
			return Conexion::$filas;
		}
		catch(Exception $e)
		{
			die("Error al consultar menu. Error! : ". $e->getMessage());
		}
	}
	
	
}
