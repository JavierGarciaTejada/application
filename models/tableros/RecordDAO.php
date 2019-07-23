<?php 
header("Content-Type: text/html;charset=utf-8");

class RecordDAO
{

	private static function _connectDBInstance()
	{
		Conexion::$connect = Conexion::connectionDinamic("localhost", "3306", "evaluacion", "root", "jonas", "utf8");
		Conexion::$connect->query("SET NAMES 'utf8'");
	}

	public static function executeQuery($sql){

		try 
		{
			Conexion::$connect = new Conexion();
			// self::_connectDBInstance();
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


	public static function RecordIngeniero(){

		$sql = "SELECT a.ig, concat(b.ap,' ', b.am, ' ', b.no) nombre, c.cl, count(*) total 
		FROM so_sol a INNER JOIN si_usr b ON a.ig = b.ix INNER JOIN ad_sig c ON b.cl = c.ix GROUP BY a.ig ORDER BY b.ap";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;

	}

	public static function EvaluacionesEtapa($filtro = ""){

		$where = empty($filtro) ? "" : "WHERE $filtro";

		$sql = "SELECT a.ig, concat(b.ap,' ', b.am, ' ', b.no) 'desc', d.no name, count(*) y
		FROM so_sol a INNER JOIN si_usr b ON a.ig = b.ix INNER JOIN ad_sig c ON b.cl = c.ix INNER JOIN ad_eta d ON a.et = d.ix $where GROUP BY a.ig, et order by b.ap";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;

	}

	public static function EvaluacionesPromedio($filtro = ""){

		$where = empty($filtro) ? "" : "WHERE $filtro";

		$sql = "SELECT b.no name, AVG( DATEDIFF( if(fl = '0000-00-00 00:00:00', NOW(), fl), fs)) y FROM so_sol a INNER JOIN ad_tps b ON a.ts = b.ix $where GROUP BY ts";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;

	}

	
}
