<?php

class FrontController
{
	private static $controller;
	private static $controlName;
	private static $action;
	private static $url = '';
	
	private static function loadClassCore()
	{
	    require_once PROJECTPATH .'/libs/Conexion.php';
	    require_once PROJECTPATH .'/libs/View.php';
	    require_once PROJECTPATH .'/libs/Funciones.php';
	    require_once PROJECTPATH .'/libs/FuncionesEvaluacion.php';
	    // require_once PROJECTPATH .'/libs/Validaciones.php';
	    require_once PROJECTPATH .'/libs/Log.php';
	    require_once PROJECTPATH .'/libs/GeneradorIx.php';
	    require_once PROJECTPATH .'/libs/Session.php';
	    require_once PROJECTPATH .'/libs/Perfil.php';
	    require_once PROJECTPATH .'/libs/ErrException.php';
	}
	
	private static function renderConfig()
	{
		require_once PROJECTPATH .'/libs/Config.php';
		require_once PROJECTPATH .'/config.php';
	}
	
	private static function parseController()
	{
		self::$controller = isset($_REQUEST['controlador']) ? $_REQUEST['controlador'] : '';
		if(self::$controller == '')
		{
			if((isset(self::$url[0]) ? self::$url[0] : '') == '')
			{
				self::$controller = Config::$configuration->get('index');
				self::$controlName = self::$controller;
			}
			else
			{
				self::$controller = ucfirst(self::$url[0]);
				self::$controlName = self::$controller;
				unset(self::$url[0]);
			}
		}
		else 
		{
			self::$controller = ucfirst(self::$controller);
			self::$controlName = self::$controller;
		}
	}
	
	private static function parseAction()
	{
		self::$action = isset($_REQUEST['accion']) ? $_REQUEST['accion'] : '';
		if(self::$action == '' && (isset($_REQUEST['controlador']) ? $_REQUEST['controlador'] : '') == '')
		{
			if((isset(self::$url[1]) ? self::$url[1] : '') == '')
			{
				self::$action = 'index'; 
			}
			else
			{
				self::$action = self::$url[1];
				unset(self::$url[1]);
			}
			self::$controller;
		}
		if(self::$action == '')
		{
			self::$action = 'index';
		}
	}
	
	private static function friendlyURL()
	{
		$url = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
		if(!empty($url))
		{
			self::$url = explode( '/', filter_var(rtrim(ltrim($url, '/'), '/'), FILTER_SANITIZE_URL));
		}
	}
	
	private static function includeFileController()
	{
		if(!is_file(Config::$configuration->get('controllersFolder') . self::$controller .".php"))
		{
			self::findFileController();
		}
		elseif(is_file(Config::$configuration->get('controllersFolder'). self::$controller .".php"))
		{
			self::$controller = self::$controller .".php";
		}
		include_once(Config::$configuration->get('controllersFolder') . self::$controller);
	}
	
	private static function findFileController()
	{
		$file = '';
		$script = '';
		$files = glob(Config::$configuration->get('controllersFolder') .'*php', GLOB_BRACE);
		foreach($files as $key => $file)
		{
			$script = preg_replace('/.*\//', '', $file);
			if(stristr($script, self::$controller) == true)
			{
				self::$controller = $script;
				return true;
			}
		}
		self::$controller = Config::$configuration->get('index') .".php";
	}
	
	private static function isCallable()
	{
		if( is_callable(array(self::$controlName, self::$action)) === false )
		{
			self::$controller = Config::$configuration->get('index') .".php";
			self::$controlName = Config::$configuration->get('index');
			require_once Config::$configuration->get('controllersFolder') . self::$controller;
			self::$action = 'error404';
		}
	}
	
  public static function main()
  {
		self::renderConfig();
		self::loadClassCore();
		self::friendlyURL();
		self::parseController();
		self::parseAction();
		self::includeFileController();
		self::isCallable();
		$controller = self::$controlName;
		$action = self::$action;
		$obj = new $controller();
		$obj->$action(self::$url ? array_values(self::$url) : []);
  }
}
