<?php 

class Conexion
{
	private $_type;
	private $_host;
	private $_port;
	public static $_base;
	private $_user;
	private $_pass;
	private static $_connect;
	private static $_instance;
	public static $config;
	public static $connect;
	public static $query;
	public static $result;
	public static $prepare;
	public static $filas;
	public static $rowAffected;

	public function __construct()
	{
		try
		{
			$this->parseIniFileConf();
			$this->_type = isset(self::$config['type']) ? self::$config['type'] : '';
			$this->_host = isset(self::$config['host']) ? self::$config['host'] : '';
			$this->_port = isset(self::$config['port']) ? self::$config['port'] : '';
			self::$_base = isset(self::$_base) ? self::$_base : self::$config['db'];
			$this->_user = isset(self::$config['user']) ? self::$config['user'] : '';
			$this->_pass = isset(self::$config['pass']) ? self::$config['pass'] : '';

			self::$_connect = new PDO(
					$this->_type .':host='. $this->_host .';port='. $this->_port .';dbname='. self::$_base, 
					$this->_user, 
					$this->_pass,
					array(
						PDO::MYSQL_ATTR_LOCAL_INFILE => 1
					)
			);
			self::$_connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->executeSetVars();
		}
		catch(PDOException $e)
		{
			throw new Exception("Error en la conexion!: ". $e->getMessage());
			die();
		}
	}

	public function prepare($sql)
	{
		return self::$_connect->prepare($sql);
	}

	public static function instance()
	{
		if( !isset(self::$_instance) )
		{
			$class = __CLASS__;
			self::$_instance = new $class;
		}
		return self::$_instance;
	}

	public function __clone()
	{
		trigger_error("La clonacion de este objeto no esta permitida", E_USER_ERROR);
	}

	public static function connectionDinamic($host = '', $port = '', $base = '', $user = '', $pass = '', $charset = '', $type = '')
	{
		try
		{
			self::parseIniFileConf();
			$_type = empty($type) ? self::$config['type'] : '';
			$_host = empty($host) ? self::$config['host'] : $host;
			$_port = empty($port) ? self::$config['port'] : $port;
			$_base = empty($base) ? self::$config['db'] : $base;
			$_user = empty($user) ? self::$config['user'] : $user;
			$_pass = empty($pass) ? self::$config['pass'] : $pass;

			self::$_connect = new PDO($_type .':host='. $_host .';port='. $_port .';dbname='. $_base, $_user, $_pass, array(PDO::MYSQL_ATTR_LOCAL_INFILE => 1));
			self::$_connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			self::executeSetVars();
			return self::$_connect;
		}
		catch(PDOException $e)
		{
			throw new Exception("Error en la conexion dinamica. !: error!. ". $e->getMessage());
		}
	}

	private static function executeSetVars()
	{
		try
		{
			empty(self::$config['charset']) ? '' : self::$_connect->exec("SET CHARACTER SET ". self::$config['charset']);
			empty(self::$config['interactive_timeout']) ? '' : self::$_connect->exec("SET interactive_timeout = ". self::$config['interactive_timeout']);
			empty(self::$config['wait_timeout']) ? '' : self::$_connect->exec("SET wait_timeout = ". self::$config['wait_timeout']);
		}
		catch(PDOException $e)
		{
			throw new Exception("Error al setear variables de mysql. Error!: ". $e->getMessage());
		}
	}

	private static function parseIniFileConf()
	{
		if( !file_exists(Config::$configuration->get('file_config_db')) )
		{
			die("Archivo de configuracion de base de datos no existe.");
		}
		self::$config = parse_ini_file(Config::$configuration->get('file_config_db'));
	}
}
