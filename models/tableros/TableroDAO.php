<?php 
header("Content-Type: text/html;charset=utf-8");
//include_once Config::$configuration->get('modelsFolder') . 'ConexionesAFA.php';

class TableroDAO 
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

	public static function EvaluacionesLiberadasAnio($filtro = ""){

		$filtro = ( empty($filtro) ) ? "" : " AND ".$filtro;

		$sql = "SELECT COUNT(id) total FROM so_sol WHERE fl <> '0000-00-00 00:00:00' $filtro ";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;

	}

	public static function EvaluacionesLiberadasMes($filtro = ""){
		$filtro = ( empty($filtro) ) ? "" : " AND ".$filtro;

		$sql = "SELECT a.anio, a.anio_mes, a.cantidad_lib, lib.liberadas, can.canceladas FROM (
		(SELECT SUBSTR(fl, 1, 4) anio, SUBSTR(fl, 1, 7) anio_mes, count(id) cantidad_lib 
		 FROM so_sol WHERE fl <> '0000-00-00 00:00:00' $filtro GROUP BY SUBSTR(fl, 1, 7) ORDER BY SUBSTR(fl, 1, 7) ) a
		LEFT JOIN 
		( SELECT SUBSTR(fl, 1, 7) anio_mes, count(id) liberadas FROM so_sol WHERE et = '619056264933550' $filtro GROUP BY SUBSTR(fl, 1, 7) ) lib ON a.anio_mes = lib.anio_mes
		LEFT JOIN 
		( SELECT SUBSTR(fl, 1, 7) anio_mes, count(id) canceladas FROM so_sol WHERE et = '619056264933551' $filtro GROUP BY SUBSTR(fl, 1, 7) ) can ON lib.anio_mes = can.anio_mes
		)";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;

	}

	public static function EvaluacionesPeriodo($mayor_a, $p1, $p2, $p3, $filtro = ""){


		$filtro = ( empty($filtro) ) ? "" : " AND ".$filtro;
		$sql = "SELECT * FROM (
		(SELECT COUNT(id) 'total' FROM so_sol WHERE fl = '0000-00-00 00:00:00' AND (DATEDIFF(NOW(),fs) > $mayor_a AND DATEDIFF(NOW(),fs) <= $p3) $filtro ) total,
		( SELECT COUNT(id) 'p1' FROM so_sol WHERE fl = '0000-00-00 00:00:00' AND (DATEDIFF(NOW(),fs) > $mayor_a AND DATEDIFF(NOW(),fs) <= $p1) $filtro  ) p1,
		( SELECT COUNT(id) 'p2' FROM so_sol WHERE fl = '0000-00-00 00:00:00' AND ( DATEDIFF(NOW(),fs) > $p1 AND DATEDIFF(NOW(),fs) <= $p2) $filtro ) p2,
		( SELECT COUNT(id) 'p3' FROM so_sol WHERE fl = '0000-00-00 00:00:00' AND ( DATEDIFF(NOW(),fs) > $p2 AND DATEDIFF(NOW(),fs) <= $p3) $filtro ) p3
		)";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;

	}

	public static function EvaluacionesPeriodoNorma($mayor_a, $p1, $p2, $p3, $filtro = ""){

		$filtro = ( empty($filtro) ) ? "" : " AND ".$filtro;
		$sql = "SELECT *, $p1 mn, $p2 md, $p3 mx  FROM (
			( SELECT COUNT(id) 'total' FROM so_sol a WHERE a.dl = $p3 AND a.et = 619056264933549 $filtro ) total,
			( SELECT COUNT(id) 'p1' FROM so_sol a WHERE ( DATEDIFF(NOW(),fs) > $mayor_a AND DATEDIFF(NOW(),fs) <= $p1 ) AND a.dl = $p3 AND a.et = 619056264933549 $filtro ) p1,
			( SELECT COUNT(id) 'p2' FROM so_sol a WHERE ( DATEDIFF(NOW(),fs) > $p1 AND DATEDIFF(NOW(),fs) <= $p2 ) AND a.dl = $p3 AND a.et = 619056264933549 $filtro ) p2,
			( SELECT COUNT(id) 'p3' FROM so_sol a WHERE ( DATEDIFF(NOW(),fs) > $p2 AND DATEDIFF(NOW(),fs) <= $p3 ) AND a.dl = $p3 AND a.et = 619056264933549 $filtro ) p3
			)";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;

	}

	public static function EvaluacionesPeriodoUnico($mayor_a, $menor_a, $filtro = ""){

		$filtro = ( empty($filtro) ) ? "" : " AND ".$filtro;
		$sql = "SELECT * FROM so_sol WHERE fl = '0000-00-00 00:00:00' AND (DATEDIFF(NOW(),fs) > $mayor_a AND DATEDIFF(NOW(),fs) <= $menor_a) $filtro";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;

	}

	public static function EvaluacionesEnProceso($filtro = ""){

		$filtro = ( empty($filtro) ) ? "" : " AND ".$filtro;

		$sql = "SELECT id, ix, fl, fc, fs, fo, 'En Proceso' etapa FROM so_sol WHERE fl = '0000-00-00 00:00:00' $filtro ";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;

	}

	public static function EvaluacionesPorGerencia(){
		$sql = "SELECT al, count(a.id) cant, b.no, b.cl FROM so_sol a LEFT JOIN ad_alb b ON a.al = b.ix
		WHERE fl = '0000-00-00 00:00:00' GROUP BY al";

		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;
	}


	public static function EvaluacionesNuevas($filtro = ""){
		$filtro = ( empty($filtro) ) ? "" : " AND ".$filtro;
		$sql = "SELECT count(id) nuevas FROM so_sol WHERE MONTH(fs) = MONTH(CURRENT_DATE()) AND YEAR(fs) = YEAR(CURRENT_DATE()) $filtro ";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;
	}

	public static function EvaluacionesLiberadasPromedio($filtro = ""){
		$filtro = ( empty($filtro) ) ? "" : " AND ".$filtro;
		$sql = "SELECT REPLACE( SUBSTR( fs, 1, 7 ), '-', '' ) anio_mes_sol, REPLACE(SUBSTR( fl, 1, 7 ), '-', '' ) anio_mes_lib, DATEDIFF( fl, fs ) dias_liberacion, AVG(DATEDIFF( fl, fs )) promedio 
		FROM so_sol WHERE fl != '0000-00-00 00:00:00' $filtro
		GROUP BY SUBSTR( fl, 1, 7 ) ORDER BY fl ";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;
	}

	
}
