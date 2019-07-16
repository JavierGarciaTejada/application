<?php 

class ConexionesAFA 
{
	public static $_server;
	
	public static function switchConnection($param = "")
	{
		switch ($param) 
		{
			case "occidente":
				self::_connectOccidente();
				break;
			case "noreste":
				self::_connectNoreste();
				break;
			case "norte":
				self::_connectNorte();
				break;
			case "noroeste":
				self::_connectNoroeste();
				break;
			case "golfo":
				self::_connectGolfo();
				break;
			case "centro":
				self::_connectCentro();
				break;
			case "sureste":
				self::_connectSureste();
				break;
			case "metro":
				self::_connectMetro();
				breaK;
			case "ld":
				self::_connectLD();
				break;
			case "q825e":
				self::_connectQ825E();
				breaK;
			case "q825h":
				self::_connectQ825H();
				break;
			case "telnor":
				self::_connectTelnor();
				break;
		}
	}
	
	public static function _connectOccidente()
	{
		ConexionesAFA::$_server = "Occidente";
		Conexion::$connect = Conexion::connectionDinamic("10.205.51.55", "3306", "facturacion", "mysql", "batv-uwll-wepr", "utf8");
	}
	
	public static function _connectNoreste()
	{
		ConexionesAFA::$_server = "Noreste";
		Conexion::$connect = Conexion::connectionDinamic("10.205.51.50", "3308", "facturacion", "mysql", "batv-uwll-wepr", "utf8");
	}
	
	public static function _connectNorte()
	{
		ConexionesAFA::$_server = "Norte";
		Conexion::$connect = Conexion::connectionDinamic("10.205.51.50", "3309", "facturacion", "mysql", "batv-uwll-wepr", "utf8");
	}
	
	public static function _connectNoroeste()
	{
		ConexionesAFA::$_server = "Noroeste";
		Conexion::$connect = Conexion::connectionDinamic("10.205.51.50", "3307", "facturacion", "mysql", "batv-uwll-wepr", "utf8");
	}
	
	public static function _connectGolfo()
	{
		ConexionesAFA::$_server = "Golfo";
		Conexion::$connect = Conexion::connectionDinamic("10.205.51.51", "3307", "facturacion", "mysql", "batv-uwll-wepr", "utf8");
	}
	
	public static function _connectCentro()
	{
		ConexionesAFA::$_server = "Centro";
		Conexion::$connect = Conexion::connectionDinamic("10.205.51.51", "3308", "facturacion", "mysql", "batv-uwll-wepr", "utf8");
	}
	
	public static function _connectSureste()
	{
		ConexionesAFA::$_server = "Sureste";
		Conexion::$connect = Conexion::connectionDinamic("10.205.51.51", "3309", "facturacion", "mysql", "batv-uwll-wepr", "utf8");
	}
	
	public static function _connectMetro()
	{
		ConexionesAFA::$_server = "Metro";
		Conexion::$connect = Conexion::connectionDinamic("10.205.51.54", "3306", "facturacion", "mysql", "batv-uwll-wepr", "utf8");
	}
	
	public static function _connectLD()
	{
		ConexionesAFA::$_server = "LD";
		Conexion::$connect = Conexion::connectionDinamic("10.205.51.53", "3306", "facturacion", "mysql", "batv-uwll-wepr", "utf8");
	}
	
	public static function _connectQ825E()
	{
		ConexionesAFA::$_server = "Q825 Ericsson";
		Conexion::$connect = Conexion::connectionDinamic("10.205.51.57", "3306", "facturacion", "mysql", "batv-uwll-wepr", "utf8");
	}
	
	public static function _connectQ825H()
	{
		ConexionesAFA::$_server = "Q825 Huawei";
		Conexion::$connect = Conexion::connectionDinamic("10.205.51.56", "3306", "facturacion", "mysql", "batv-uwll-wepr", "utf8");
	}
	
	public static function _connectTelnor()
	{
		ConexionesAFA::$_server = "Telnor";
		Conexion::$connect = Conexion::connectionDinamic("147.15.80.51", "3306", "facturacion", "mysql", "t3ln0r", "utf8");
	}
}
