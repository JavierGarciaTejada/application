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
			self::$config = Funciones::parseIniFileConf();
			$this->_type = isset(self::$config['DB_TYPE']) ? self::$config['DB_TYPE'] : '';
			$this->_host = isset(self::$config['DB_HOST']) ? self::$config['DB_HOST'] : '';
			$this->_port = isset(self::$config['DB_PORT']) ? self::$config['DB_PORT'] : '';
			self::$_base = isset(self::$_base) ? self::$_base : self::$config['DB_NAME'];
			$this->_user = isset(self::$config['DB_USER']) ? self::$config['DB_USER'] : '';
			$this->_pass = isset(self::$config['DB_PASS']) ? self::$config['DB_PASS'] : '';

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
			self::$config = Funciones::parseIniFileConf();
			$_type = empty($type) ? self::$config['DB_TYPE'] : '';
			$_host = empty($host) ? self::$config['DB_HOST'] : $host;
			$_port = empty($port) ? self::$config['DB_PORT'] : $port;
			$_base = empty($base) ? self::$config['DB_NAME'] : $base;
			$_user = empty($user) ? self::$config['DB_USER'] : $user;
			$_pass = empty($pass) ? self::$config['DB_PASS'] : $pass;

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
			self::$_connect->exec("SET GLOBAL sql_mode = 'NO_ENGINE_SUBSTITUTION'");
			empty(self::$config['CHARSET']) ? '' : self::$_connect->exec("SET CHARACTER SET ". self::$config['CHARSET']);
			empty(self::$config['INTERACTIVE_TIMEOUT']) ? '' : self::$_connect->exec("SET interactive_timeout = ". self::$config['INTERACTIVE_TIMEOUT']);
			empty(self::$config['WAIT_TIMEOUT']) ? '' : self::$_connect->exec("SET wait_timeout = ". self::$config['WAIT_TIMEOUT']);
		}
		catch(PDOException $e)
		{
			throw new Exception("Error al setear variables de mysql. Error!: ". $e->getMessage());
		}
	}
}
