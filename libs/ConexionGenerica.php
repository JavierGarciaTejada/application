<?php 

class ConexionGenerica
{
	
  public static $_type;
  public static $_host;
  public static $_port;
  public static $_base;
  public static $_user;
  public static $_pass;
  public static $_charset;
  private $_connect;
  private static $_instance;

  public static $config;
  
  public static $connect;
  public static $query;
  public static $result;
  public static $filas;
  public static $rowAffected;

  public function __construct() 
  {
    self::$config = Funciones::parseIniFileConf();
    self::$_type    = isset(self::$_type)     ? self::$_type    : self::$config['DB_TYPE'];
    self::$_host    = isset(self::$_host)     ? self::$_host    : self::$config['DB_HOST'];
    self::$_port    = isset(self::$_port)     ? self::$_port    : self::$config['DB_PORT'];
    self::$_base    = isset(self::$_base)     ? self::$_base    : self::$config['DB_NAME'];
    self::$_user    = isset(self::$_user)     ? self::$_user    : self::$config['DB_USER'];
    self::$_pass    = isset(self::$_pass)     ? self::$_pass    : self::$config['DB_PASS'];
    self::$_charset = isset(self::$_charset)  ? self::$_charset : self::$config['CHARSET']
    
    try 
    {
      $this->_connect = new PDO($this->_type .':host='. $this->_host .';port='. $this->_port .';dbname='. self::$_base, $this->_user, $this->_pass);
      $this->_connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->_connect->exec("SET CHARACTER SET ". $this->_charset);
    }
    catch(PDOException $e)
    {
      throw new Exception($e->getMessage());
      die();
    }
  }
  
  public function prepare($sql)
  {
    return $this->_connect->prepare($sql);
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
}
