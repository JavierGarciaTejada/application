<?php

class Session 
{
	private static $sessionId;
	private static $session;
	
	public static $usuario;
	public static $dirDiv;
	public static $perfil;
	public static $nomPerfil;
	public static $nombre;
	public static $ip;
	public static $nombreUno;
	public static $ddSiatel;
	public static $empresa;
	public static $area;
	public static $dirCorreo;
	public static $areaResp;
	
	public static $_session = false;

	private static function startSession()
	{
		session_start();
	}
	
	private static function setSessionId()
	{
		self::$sessionId = session_id();
	}
	
	public static function destroySession()
	{
		self::startSession();
		//*self::setSession();
		session_unset();
		self::$usuario = NULL;
		self::$dirDiv = NULL;
		self::$perfil = NULL;
		self::$nomPerfil = NULL;
		self::$nombre = NULL;
		self::$ip = NULL;
		self::$nombreUno = NULL;
		self::$ddSiatel = NULL;
		self::$empresa = NULL;
		self::$area = NULL;
		self::$dirCorreo = NULL;
		self::$areaResp = NULL;
		unset($_SESSION);
		session_destroy();
		
		self::$session = isset($_SESSION) ? $_SESSION : '';
		
		if( self::$session == "" )
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	private static function setSession()
	{
		self::$usuario = isset(self::$session['usr']) ? self::$session['usr'] : "";
		self::$dirDiv = isset(self::$session['dirdiv']) ? self::$session['dirdiv'] : "";
		self::$perfil = isset(self::$session['perfil']) ? self::$session['perfil'] : "";
		self::$nomPerfil = isset(self::$session['nperfil']) ? self::$session['nperfil'] : "";
		self::$nombre = isset(self::$session['nombre']) ? self::$session['nombre'] : "";
		self::$ip = isset(self::$session['ip']) ? self::$session['ip'] : "";
		self::$nombreUno = isset(self::$session['nombre1']) ? self::$session['nombre1'] : "";
		self::$ddSiatel = isset(self::$session['dd_siatel']) ? self::$session['dd_siatel'] : "";
		self::$empresa = isset(self::$session['empresa']) ? self::$session['empresa'] : "";
		self::$area = isset(self::$session['area']) ? self::$session['area'] : "";
		self::$dirCorreo = isset(self::$session['email']) ? self::$session['email'] : "";
		self::$areaResp = isset(self::$session['arearesp']) ? self::$session['arearesp'] : "";
	}
	
	private static function redireccionaLogin()
	{
		header("Location: ". Config::$configuration->get('server_path') . Config::$configuration->get('ruta_login'));
	}

	public static function validaSession()
	{
		Session::$_session = true;
		self::startSession();
		self::setSessionId();
		self::$session = isset($_SESSION) ? $_SESSION : '';

		if( is_array(self::$session) && !empty(self::$session) && self::$sessionId != "" )
		{
			self::setSession();
			if( self::$usuario == "" || self::$perfil == "" || self::$nomPerfil == "" )
			{
				self::redireccionaLogin();
			}
			true;
		}
		else 
		{
			self::redireccionaLogin();
		}
	}
	
	public static function datosSession()
	{
		self::startSession();
		self::$session = isset($_SESSION) ? $_SESSION : '';
		self::setSession();

		if( self::$usuario != "" && self::$perfil != "" && self::$nombre != "" )
		{
			$ses = array(
						"session" => true,
						"usuario" => self::$usuario,
						"division" => self::$dirDiv,
						"perfil" => self::$perfil,
						"nombre_perfil" => self::$nomPerfil,
						"nombre" => self::$nombre,
						"ip" => self::$ip,
						"nombre_uno" => self::$nombreUno,
						"div_siatel" => self::$ddSiatel,
						"empresa" => self::$empresa,
						"area" => self::$area,
						"correo" => self::$dirCorreo,
						"area_resp" => self::$areaResp,
			);
		}
		else
		{
			$ses = array("session" => false);
		}
		return $ses;
	}
	
	public static function setSessions($name = "", $value = "")
	{
		if(!isset($_SESSION[$name]))
		{
			$_SESSION[$name] = $value;
		}
	}
}
