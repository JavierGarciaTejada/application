<?php

class Config
{
	public static $configuration;
  private static $instance;
  private $vars;
 
  private function __construct()
  {
    $this->vars = array();
  }
 
  //Con set vamos guardando nuestras variables.
  public function set($name, $value)
  {
    if(strlen($name) > 0 && !empty($name) && !empty($value))
    {
			$this->vars[$name] = isset($value) ? $value : "No se define un valor";
    }
  }
 
  //Con get('nombre_de_la_variable') recuperamos un valor.
  public function get($name)
  {
    if(isset($this->vars[$name]))
    {
      return $this->vars[$name];
    }
    else 
    {
			return "No existe el valor.";
    }
  }
 
  public static function singleton()
  {
    if (!isset(self::$instance)) 
    {
      $c = __CLASS__;
      self::$instance = new $c;
    }
    return self::$instance;
  }
}
