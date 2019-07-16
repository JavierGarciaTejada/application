<?php 

defined("PROJECTPATH") or die("Access error");
require_once(Config::$configuration->get('modelsFolder') . 'UsuariosDAO.php');

class UsuariosAFA
{
	private $_contrasena_rep;
	
	public function get_usuarios_afa()
	{
		require_once PROJECTPATH .'/libs/DataTables.php';
    DataTables::$recordsFiltered = UsuariosDAO::selectCountUsuariosWhere(DataTables::onlyWhere());
    DataTables::$recordsTotal = UsuariosDAO::selectCountUsuarios();
    DataTables::$data = UsuariosDAO::selectAllUsuarios(DataTables::whereLimit());
    usleep(500000);
    Funciones::imprimeJson(DataTables::resultDataTables());
	}
	
	public function inserta_usuario_afa()
	{
		$this->setDd();
		$this->setArea();
		$this->setIp();
		$this->setLogin();
		$this->setContrasena();
		$this->setContrasenaRepite();
		$this->setUsuario();
		$this->setExpediente();
		$this->setEmpresa();
		$this->setTelefono();
		$this->setValida_ip();
		$this->setReserva();
		$this->setFecha_alta();
		$this->setFecha_pwd();
		$this->setEstilo();
		$this->setReportes();
		$this->setDetalles();
		$this->setPerfil();
		$this->setNuevo();
		$this->setStatus();
		$this->setIntentos();
		$this->validaDd();
		$this->validaLogin();
		$this->validaConstrasena();
		$this->validaArea();
		$this->validaIp();
		$this->validaUsuario();
		$this->validaTelefono();
		$this->validaPerfil();
		$this->setPass();
		if(UsuariosDAO::insertUsuario() > 0)
		{
			$valid = true;
			$class = "alertify-success";
			$message = "Se inserto el usuario \"". UsuariosDAO::$_login ."\" satisfactoriamente.";
		}
		else 
		{
			$valid = false;
			$class = "alertify-danger";
			$message = "Ocurrio un error al insertar el usuario \"". UsuariosDAO::$_login ."\".";
		}
		sleep(1);
		Funciones::imprimeJson(
			array(
				'valid' => $valid,
				'clase' => $class,
				'message' => $message
			)
		);
	}
	
	public function elimina_usuario_afa()
	{
		$this->setId();
		$this->setLogin();
		if(UsuariosDAO::deleteUsuario() > 0)
		{
			$valid = true;
			$class = "alertify-success";
			$message = "Se elimino el usuario \"". UsuariosDAO::$_login ."\" satisfactoriamente.";
		}
		else 
		{
			$valid = false;
			$class = "alertify-danger";
			$message = "Ocurrio un error al eliminar el usuario \"". UsuariosDAO::$_login ."\".";
		}
		sleep(1);
		Funciones::imprimeJson(
			array(
				'valid' => $valid,
				'clase' => $class,
				'message' => $message
			)
		);
	}
	
	public function getDataUsuarioID($id = "")
	{
		UsuariosDAO::$_id = Funciones::GetValueVar((isset($id[0]) ? $id[0] : ""), "int");
		Funciones::imprimeJson(UsuariosDAO::selectAllUsuariosById());
	}
	
	public function actualiza_usuario_afa()
	{
		$this->setId();
		$this->setDd();
		$this->setArea();
		$this->setIp();
		$this->setLogin();
		$this->setUsuario();
		$this->setExpediente();
		$this->setEmpresa();
		$this->setTelefono();
		$this->setValida_ip();
		$this->setReserva();
		$this->setEstilo();
		$this->setReportes();
		$this->setDetalles();
		$this->setPerfil();
		$this->setNuevo();
		$this->setStatus();
		$this->setIntentos();
		$this->validaDd();
		$this->validaLogin();
		$this->validaArea();
		$this->validaIp();
		$this->validaUsuario();
		$this->validaTelefono();
		$this->validaPerfil();
		if(UsuariosDAO::updateUsuario() > 0)
		{
			$valid = true;
			$class = "alertify-success";
			$message = "Se actualiza el usuario \"". UsuariosDAO::$_login ."\" satisfactoriamente.";
		}
		else 
		{
			$valid = false;
			$class = "alertify-danger";
			$message = "Ocurrio un error al actualizar el usuario \"". UsuariosDAO::$_login ."\".";
		}
		sleep(1);
		Funciones::imprimeJson(
			array(
				'valid' => $valid,
				'clase' => $class,
				'message' => $message
			)
		);
	}
	
	public function actualiza_passwd()
	{
		$this->setId();
		$this->setLogin();
		$this->setContrasena();
		$this->setContrasenaRepite();
		$this->setFecha_pwd();
		$this->validaConstrasena();
		$this->setPass();
		if(UsuariosDAO::updateUsuarioPasswd() > 0)
		{
			$valid = true;
			$class = "alertify-success";
			$message = "Se cambio la contrasena del usuario \"". UsuariosDAO::$_login ."\" satisfactoriamente.";
		}
		else 
		{
			$valid = false;
			$class = "alertify-danger";
			$message = "Ocurrio un error al cambirar la contraseña del usuario \"". UsuariosDAO::$_login ."\".";
		}
		sleep(1);
		Funciones::imprimeJson(
			array(
				'valid' => $valid,
				'clase' => $class,
				'message' => $message
			)
		);
	}
	
	public function exists_login()
	{
		$this->setId();
		$this->setLogin();
		if(UsuariosDAO::selectExistsLogin() == UsuariosDAO::$_login)
		{
			$valid = false;
		}
		else 
		{
			$valid = true;
		}
		usleep(200000);
		Funciones::imprimeJson(array('valid' => $valid));
	}
	
	private function setId()
	{
		UsuariosDAO::$_id = isset($_POST['id']) ? Funciones::GetValueVar($_POST['id'], "int") : "";
	}
	
	private function setDd()
	{
		UsuariosDAO::$_dd = isset($_POST['dd']) ? Funciones::GetValueVar($_POST['dd'], "text") : "";
	}

	private function setArea()
	{
		UsuariosDAO::$_area = isset($_POST['area']) ? Funciones::GetValueVar($_POST['area'], "text") : "";
	}

	private function setIp()
	{
		UsuariosDAO::$_ip = isset($_POST['ip']) ? Funciones::GetValueVar($_POST['ip'], "text") : "";
	}

	private function setLogin()
	{
		UsuariosDAO::$_login = isset($_POST['login']) ? Funciones::GetValueVar($_POST['login'], "text") : "";
	}

	private function setContrasena()
	{
		UsuariosDAO::$_contrasena = isset($_POST['contrasena']) ? Funciones::GetValueVar($_POST['contrasena'], "text") : "";
	}

	private function setUsuario()
	{
		UsuariosDAO::$_usuario = isset($_POST['usuario']) ? Funciones::GetValueVar($_POST['usuario'], "text") : "";
	}

	private function setExpediente()
	{
		UsuariosDAO::$_expediente = isset($_POST['expediente']) ? Funciones::GetValueVar($_POST['expediente'], "text") : "";
	}

	private function setEmpresa()
	{
		UsuariosDAO::$_empresa = isset($_POST['empresa']) ? Funciones::GetValueVar($_POST['empresa'], "text") : "";
	}

	private function setTelefono()
	{
		UsuariosDAO::$_telefono = isset($_POST['telefono']) ? Funciones::GetValueVar($_POST['telefono'], "text") : "";
	}

	private function setValida_ip()
	{
		UsuariosDAO::$_valida_ip = !empty($_POST['valida_ip']) ? Funciones::GetValueVar($_POST['valida_ip'], "text") : "0";
	}

	private function setReserva()
	{
		UsuariosDAO::$_reserva = isset($_POST['reserva']) ? Funciones::GetValueVar($_POST['reserva'], "text") : "";
	}

	private function setFecha_alta()
	{
		UsuariosDAO::$_fecha_alta = isset($_POST['fecha_alta']) ? Funciones::GetValueVar($_POST['fecha_alta'], "text") : date('Y-m-d');
	}

	private function setFecha_pwd()
	{
		UsuariosDAO::$_fecha_pwd = isset($_POST['fecha_pwd']) ? Funciones::GetValueVar($_POST['fecha_pwd'], "text") : date('Y-m-d');
	}

	private function setEstilo()
	{
		UsuariosDAO::$_estilo = isset($_POST['estilo']) ? Funciones::GetValueVar($_POST['estilo'], "text") : "Compact";
	}

	private function setReportes()
	{
		UsuariosDAO::$_reportes = isset($_POST['reportes']) ? Funciones::GetValueVar($_POST['reportes'], "text") : 
			"np:01,03,04,06,07,08,0B,0C,FF,NP,TT-NP";
	}

	private function setDetalles()
	{
		UsuariosDAO::$_detalles = isset($_POST['detalles']) ? Funciones::GetValueVar($_POST['detalles'], "text") : 
			"cr:0,1,2,3,5,6,7,8,9,10,11,13,14,15,16,17,18,20,21,22-22/200";
	}

	private function setPerfil()
	{
		UsuariosDAO::$_perfil = isset($_POST['perfil']) ? Funciones::GetValueVar($_POST['perfil'], "text") : "";
	}

	private function setNuevo()
	{
		UsuariosDAO::$_nuevo = isset($_POST['nuevo']) ? Funciones::GetValueVar($_POST['nuevo'], "text") : "";
	}

	private function setStatus()
	{
		UsuariosDAO::$_status = isset($_POST['status']) ? Funciones::GetValueVar($_POST['status'], "text") : "Activo";
	}

	private function setIntentos()
	{
		UsuariosDAO::$_intentos = isset($_POST['intentos']) ? Funciones::GetValueVar($_POST['intentos'], "text") : 5;
	}
	
	private function setContrasenaRepite()
	{
		$this->_contrasena_rep = isset($_POST['passwd_confirm']) ? Funciones::GetValueVar($_POST['passwd_confirm'], "text") : "";
	}
	
	private function validaDd()
	{
		if(empty(UsuariosDAO::$_dd))
		{
			Funciones::imprimeJson(
				array(
					'valid' => false,
					'clase' => 'alertify-danger',
					'message' => 'Ingresa una dd, este campo no puede ir vacío.'
				)
			);
			die();
		}
		return true;
	}
	
	private function validaLogin()
	{
		if(empty(UsuariosDAO::$_login))
		{
			Funciones::imprimeJson(
				array(
					'valid' => false,
					'clase' => 'alertify-danger',
					'message' => 'Ingresa una login, este campo no puede ir vacío.'
				)
			);
			die();
		}
		return true;
	}
	
	private function validaConstrasena()
	{
		if(empty(UsuariosDAO::$_contrasena) || empty($this->_contrasena_rep))
		{
			Funciones::imprimeJson(
				array(
					'valid' => false,
					'clase' => 'alertify-danger',
					'message' => 'Ingresa una contraseña, este campo no puede ir vacío.'
				)
			);
			die();
		} 
		elseif(UsuariosDAO::$_contrasena != $this->_contrasena_rep)
		{
			Funciones::imprimeJson(
				array(
					'valid' => false,
					'clase' => 'alertify-danger',
					'message' => 'Rectifica la contraseña, no coincide el campo contraseña y repite contraseña.'
				)
			);
			die();
		}
		return true;
	}
	
	private function validaArea()
	{
		if(empty(UsuariosDAO::$_area))
		{
			Funciones::imprimeJson(
				array(
					'valid' => false,
					'clase' => 'alertify-danger',
					'message' => 'Ingresa una area, este campo no puede ir vacío.'
				)
			);
			die();
		}
		return true;
	}
	
	private function validaIp()
	{
		if(empty(UsuariosDAO::$_ip))
		{
			Funciones::imprimeJson(
				array(
					'valid' => false,
					'clase' => 'alertify-danger',
					'message' => 'Ingresa una ip, este campo no puede ir vacío.'
				)
			);
			die();
		}
		return true;
	}
	
	private function validaUsuario()
	{
		if(empty(UsuariosDAO::$_usuario))
		{
			Funciones::imprimeJson(
				array(
					'valid' => false,
					'clase' => 'alertify-danger',
					'message' => 'Ingresa un nombre de usuario, este campo no puede ir vacío.'
				)
			);
			die();
		}
		return true;
	}
	
	private function validaTelefono()
	{
		if(empty(UsuariosDAO::$_telefono))
		{
			Funciones::imprimeJson(
				array(
					'valid' => false,
					'clase' => 'alertify-danger',
					'message' => 'Ingresa un telefono, este campo no puede ir vacío.'
				)
			);
			die();
		}
		return true;
	}
	
	private function validaPerfil()
	{
		if(empty(UsuariosDAO::$_perfil))
		{
			Funciones::imprimeJson(
				array(
					'valid' => false,
					'clase' => 'alertify-danger',
					'message' => 'Ingresa un, este campo no puede ir vacío.'
				)
			);
			die();
		}
		return true;
	}
	
	private function setPass()
	{
		UsuariosDAO::$_contrasena = Funciones::GetValueVar(crypt($this->_contrasena_rep, 'iv'), "text");
	}
}
