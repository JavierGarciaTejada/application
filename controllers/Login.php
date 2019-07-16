<?php

defined("PROJECTPATH") or die("Access error");
require_once(Config::$configuration->get('modelsFolder') . 'UsuariosDAO.php');

class Login
{
	private $_data_user;
	
	public function __construct()
	{
		$this->view = new View();
	}
	
	public function index()
	{	
		//Session::validaSession();
		$this->view->layOut = Config::$configuration->get('pathlayout') ."/login.phtml";
		$this->view->show("/layouts/login.phtml");
	}
	
	public function adminAfa()
	{
		// $this->view->layOut = Config::$configuration->get('pathlayout') ."/login_admin_afa.phtml";
		// $this->view->show("/layouts/login_admin_afa.phtml");
	}

	public function loginStart()
	{
		try 
		{
			$this->setLogin();
			$this->setPasswd();
			$this->validateUserExists();
			//$this->validateUserActive();
			$this->validatePasswd();
			if($this->constructSession() === true)
			{
				sleep(1);
				Funciones::imprimeJson(
					array(
						"message" => "Bienvenido.",
						"valid" => true,
						"class" => "alertify-success",
						"href" => Config::$configuration->get('server_path') . "/index.php/home/"
					)
				);
				//UsuariosDAO::$_intentos = 5;
				//UsuariosDAO::updateIntentos();
			}
			else 
			{
				sleep(1);
				Funciones::imprimeJson(
					array(
						"message" => "Ocurrio un error al crear la session.",
						"valid" => false,
						"class" => "alertify-danger"
					)
				);
			}
		}
		catch(ErrException $e)
		{
			sleep(1);
			Funciones::imprimeJson(
				array(
					"message" => $e->getMessage(),
					"valid" => false,
					"class" => "alertify-danger"
				)
			);
		}
	}
	
	public function login_admin_afa()
	{
		try 
		{
			$this->setLogin();
			$this->setPasswd();
			$this->validateUserExists();
			//$this->validateUserActive();
			$this->validatePasswd();
			if($this->constructSession() === true)
			{
				sleep(1);
				Funciones::imprimeJson(
					array(
						"message" => "Bienvenido.",
						"valid" => true,
						"class" => "alertify-success",
						"href" => Config::$configuration->get('server_path') . "/index.php/adminafa/"
					)
				);
				//UsuariosDAO::$_intentos = 5;
				//UsuariosDAO::updateIntentos();
			}
			else 
			{
				sleep(1);
				Funciones::imprimeJson(
					array(
						"message" => "Ocurrio un error al crear la session.",
						"valid" => false,
						"class" => "alertify-danger"
					)
				);
			}
		}
		catch(ErrException $e)
		{
			sleep(1);
			Funciones::imprimeJson(
				array(
					"message" => $e->getMessage(),
					"valid" => false,
					"class" => "alertify-danger"
				)
			);
		}
	}
	
	public function close_session_afa_admin()
	{
		if( Session::destroySession() == false )
		{
			$mensaje = "Se cierra sesión satisfactoriamente.";
			$valid = true;
			$clase = "alertify-success";
			$href = Config::$configuration->get('server_path') ."/index.php/login";
		}
		else
		{
			$mensaje = "Ocurrio un error al cerrar la sesión.";
			$valid = false;
			$clase = "alertify-danger";
			$href = "";
		}
		Funciones::imprimeJson(array('mensaje' => $mensaje, 'valid' => $valid, 'clase' => $clase, 'redirecciona' => $href));
	}
	
	private function validateUserExists()
	{
		$this->_data_user = UsuariosDAO::selectUserLogin();
		if($this->_data_user === false || !is_array($this->_data_user))
		{
			throw new ErrException("El usuario no existe o es incorrecto, intente de nuevo.");
		}
		return true;
	}
	
	private function validateUserActive()
	{
		if($this->_data_user['status'] != 'Activo')
		{
			throw new ErrException("El usuario no esta activo, estatus \"". $this->_data_user['status'] ."\" contacta a un administrador.");
		}
		return true;
	}
	
	private function validatePasswd()
	{
		$valid_pass = $this->verifyPassword(UsuariosDAO::$_contrasena, $this->_data_user['ps']);
		UsuariosDAO::$_login = Funciones::GetValueVar($this->_data_user['ni'], 'text');
		/*if($this->_data_user['intentos'] <= 1)
		{
			UsuariosDAO::$_status = 'Bloqueado';
			UsuariosDAO::updateStatus();
			throw new ErrException("Se agoto el numero de intentos, tu cuenta se ha bloqueado, contacta a un administrador para desbloquear tu cuenta.");
		}
		else*/if($valid_pass === false)
		{
			//UsuariosDAO::$_intentos = Funciones::GetValueVar(($this->_data_user['intentos'] - 1), 'int');
			//UsuariosDAO::updateIntentos();
			// throw new ErrException( "La contraseña es incorrecta, número de intentos restantes \"".
			// (intval($this->_data_user['intentos']) - 1) ."\".");
			throw new ErrException( "La contraseña es incorrecta" );
		}
		return true;
	}
	
	private function verifyPassword($pass, $hash)
	{	
		//CAMBIAR EL ENCRIPTADO POR UN crypt SETEAR EL PASSWORD EN LA BASE DE DATOS

		$access = (sha1($pass) === $hash) ? true : false;
		return $access;
		// if($hash == crypt($pass, $hash))
		// {
		// 	return true;
		// }
		// else
		// {
		// 	return false;
		// }
	}
	
	private function constructSession()
	{
		//AQUI SE PODRIA SETEAR EL ROL DE CADA USUARIO PARA SABER QUE SE VA A MOSTRAR EN SU SESION
		Session::startSession();
		Session::setSession("name_session", "ROLE_USER");
		Session::setSession("id", (isset($this->_data_user['id']) ? $this->_data_user['id'] : ""));
		Session::setSession("ix", (isset($this->_data_user['ix']) ? $this->_data_user['ix'] : ""));
		Session::setSession("cl", (isset($this->_data_user['cl']) ? $this->_data_user['cl'] : ""));
		Session::setSession("ap", (isset($this->_data_user['ap']) ? $this->_data_user['ap'] : ""));
		Session::setSession("am", (isset($this->_data_user['am']) ? $this->_data_user['am'] : ""));
		Session::setSession("no", (isset($this->_data_user['no']) ? $this->_data_user['no'] : ""));
		Session::setSession("ni", (isset($this->_data_user['ni']) ? $this->_data_user['ni'] : ""));
		Session::setSession("lr", (isset($this->_data_user['lr']) ? $this->_data_user['lr'] : ""));
		Session::setSession("pu", (isset($this->_data_user['pu']) ? $this->_data_user['pu'] : ""));
		Session::setSession("role", (isset($this->_data_user['role']) ? $this->_data_user['role'] : ""));
		Session::setSession("puesto", (isset($this->_data_user['puesto']) ? $this->_data_user['puesto'] : ""));
		Session::setSession("gcl", (isset($this->_data_user['gcl']) ? $this->_data_user['gcl'] : ""));
		Session::setSession("siglas", (isset($this->_data_user['siglas']) ? $this->_data_user['siglas'] : ""));
		
		sleep(2);
		if(Session::$_session_stat === Session::SESSION_STARTED)
		{
			return true;
		}
		else
		{
			Session::destroySession();
			return false;
		}
	}
	
	private function setLogin()
	{
		// $user = urldecode(trim($_POST['usuario']));
		// if( strpos($user, '@') ){
		// 	$exp_user = explode("@", $user);
		// 	$user = $exp_user[0];
		// }
				
		UsuariosDAO::$_login = isset($_POST['usuario']) ? Funciones::GetValueVar($_POST['usuario'], "text") : "";
	}
	
	private function setPasswd()
	{
		UsuariosDAO::$_contrasena = isset($_POST['passwd']) ? Funciones::GetValueVar($_POST['passwd'], "text") : "";
	}
}
