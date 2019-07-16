<?php 

defined("PROJECTPATH") or die("Access error");

class AdminAFA
{
	public function __construct()
	{
		$this->view = new View();
		Config::$configuration->set('ruta_login', '/index.php/login/adminafa/');
		Session::$_name_session = "ADMINAFA";
		Session::validaSession();
		if(Session::getSession("role") != "Administrador") 
		{
			$arr = array(
				'title' => 'Sin Privilegios'
			);
			$this->view->show("no_privileges.html", $arr);
			die();
		}
	}
	
	public function index()
	{
		// $arr = array(
		// 	'title' => 'Administrar AFA'
		// );
		// $this->view->show("admin_afa_index_view.phtml", $arr);
		$arr = array(
			'title' => 'Panel de inicio'
		);
		$this->view->show("index.phtml", $arr);
	}
	
	public function administrar_usuarios_afa()
	{
		$arr = array(
			'title' => 'Administrar Usuarios AFA',
			'css' => array(
				'librerias/DataTables/DataTables-1.10.16/media/css/dataTables.bootstrap.min.css',
				//'librerias/jquery-ui-1.12.1/jquery-ui.min.css',
				'librerias/Bootstrap/bootstrapvalidator-master/dist/css/bootstrapValidator.min.css',
				'css/admin_usuario_afa.css'
			),
			'js' => array(
				'librerias/DataTables/DataTables-1.10.16/media/js/jquery.dataTables.min.js',
				'librerias/DataTables/DataTables-1.10.16/media/js/dataTables.bootstrap.min.js',
				//'librerias/jquery-ui-1.12.1/jquery-ui.min.js',
				'librerias/Bootstrap/bootstrapvalidator-master/dist/js/bootstrapValidator.min.js',
				'js/admin_usuario_afa.js'
			)
		);
		$this->view->show("admin_usuarios_afa_view.phtml", $arr);
	}
}
