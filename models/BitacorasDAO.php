<?php 

include_once Config::$configuration->get('modelsFolder') . 'ConexionesAFA.php';

class BitacorasDAO extends ConexionesAFA
{
	public static $_connect_instance;
	public static $_fecha_ini;
	public static $_fecha_fin;
	
	private static function _connectDBInstance()
	{
		Conexion::$connect = Conexion::connectionDinamic("localhost", "3306", "usuarios_afa", "mysql", "batv-uwll-wepr", "utf8");
	}
	
	public static function consultaUsuarios()
	{
		try 
		{
			Conexion::$connect = new Conexion();
			Conexion::$query = "SELECT '". ConexionesAFA::$_server ."' AS servidor, dd, area, ip, login, usuario, expediente, empresa, ".
				"telefono, valida_ip, reserva, fecha_alta, fecha_pwd, estilo, reportes, detalles, perfil, nuevo, status, intentos, ".
				"fec_ult_ses ".
				"FROM usuarios ";
				/*"LEFT JOIN (SELECT login, MAX(fecha) AS fecha_ultimo_acceso ".
				"FROM log ".
				"GROUP BY login ORDER BY fecha DESC) AS acceso ".
				"ON usuarios.login = acceso.login";*/
			Conexion::$result = Conexion::$connect->prepare(Conexion::$query);
			Conexion::$result->execute();
			Conexion::$filas = Conexion::$result->fetchAll(PDO::FETCH_ASSOC);
			return Conexion::$filas;
		}
		catch(Exception $e)
		{
			die("Error al consultar usuarios. Error! : ". $e->getMessage());
		}
	}
	
	public static function consultaLogs()
	{
		try 
		{
			self::switchConnection(self::$_connect_instance);
			Conexion::$query = "SELECT '". ConexionesAFA::$_server ."' AS servidor, fecha, ip, login, query, filtros ".
				"FROM log WHERE fecha BETWEEN '". self::$_fecha_ini .".00:00:00' AND '". self::$_fecha_fin ." 23:59:59'";
			Conexion::$result = Conexion::$connect->prepare(Conexion::$query);
			Conexion::$result->execute();
			Conexion::$filas = Conexion::$result->fetchAll(PDO::FETCH_ASSOC);
			return Conexion::$filas;
		}
		catch(Exception $e)
		{
			die("Error al consultar usuarios. Error! : ". $e->getMessage());
		}
	}
}
