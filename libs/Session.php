<?php

class Session
{
	public static $_secret;
	public static $_name_session = "NO_NAME_SESSION";
	private static $_session_id;
	private static $_session;
	const SESSION_STARTED = true;
	const SESSION_NOT_STARTED = false;
	public static $_session_stat = self::SESSION_NOT_STARTED;
	
	public static function validaSession()
	{
		self::existsSession();
	}
	
	private static function sessionStart()
	{
		session_set_cookie_params(2592000, Config::$configuration->get('host_path') ."/");
		self::$_session_stat = session_start();
	}
	
	public static function startSession()
	{
		if(self::$_session_stat === self::SESSION_NOT_STARTED)
		{
			//session_set_cookie_params(Config::$_life_time, Config::$_path_host ."/");
			self::sessionStart();
			session_regenerate_id(true);
		}
		return self::$_session_stat;
	}
	
	public static function destroySession()
	{
		self::sessionStart();
		if(self::$_session_stat === self::SESSION_STARTED)
		{
			self::$_session_stat = !session_destroy();
			unset($_SESSION);
			session_unset();
			return self::$_session_stat;
		}
		return self::SESSION_NOT_STARTED;
	}
	
	private static function getIdSession()
	{
		self::$_session_id = session_id();
	}
	
	public static function setSession($name = "", $value = "")
	{
		if(!isset($_SESSION[$name]))
		{
			$_SESSION[$name] = $value;
		}
	}
	
	public static function getSession($name = "")
	{
		return (isset($_SESSION[$name]) ? $_SESSION[$name] : "");
	}
	
	private static function existsSession()
	{
		/*self::sessionStart();
		self::getIdSession();
		$cookie_valid = isset($_COOKIE[sha1('name_application')]) ? base64_decode($_COOKIE[sha1('name_application')]) : "";
		if(!isset(Config::$_secret))
		{
			die("Error!: Configura la palabra secreta");
		}
		elseif(!isset(Config::$_name_session))
		{
			die("Error!: Configura el nombre de session");
		}
		if(empty($_SESSION) || empty(self::$_session_id) || !isset($_COOKIE[sha1('name_application')]) || empty($_COOKIE[sha1('name_application')])
			|| $cookie_valid != Config::$_name_session
		)
		{
			View::renderLogin();
			exit();
		}
		self::$_session_stat = self::SESSION_STARTED;*/
		self::sessionStart();
		if(
			is_null(isset($_SESSION['name_session']) ? $_SESSION['name_session'] : NULL) && 
			(isset($_SESSION['name_session']) ? $_SESSION['name_session'] : NULL) != self::$_name_session
		)
		{
			header("Location: ". Config::$configuration->get('server_path') . Config::$configuration->get('ruta_login'));
		}
		elseif((isset($_SESSION['name_session']) ? $_SESSION['name_session'] : NULL) == self::$_name_session)
		{
			self::$_session_stat = self::SESSION_STARTED;
		}
	}
	
	public static function setCookies($name = "", $value = "", $time = "", $host = "/")
	{
		if(!empty($name))
		{
			setcookie(sha1($name), base64_encode($value), (time() + (empty($time) ? Config::$_life_time : (int)$time)), Config::$_path_host ."/");
		}
		else 
		{
			return false;
		}
	}
	
	public static function datosSession()
	{
		self::sessionStart();
		return (isset($_SESSION) ? $_SESSION : "");
	}
	
	private static function requestKeys()
	{
		self::$_secret = isset($_POST['secret']) ? $_POST['secret'] : '';
		self::$_name_session = isset($_POST['name_ses']) ? $_POST['name_ses'] : '';
	}
}
