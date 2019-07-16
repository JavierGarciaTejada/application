<?php 

defined("PROJECTPATH") or die("Access error");
require_once(Config::$configuration->get('modelsFolder') . 'UsuariosDAO.php');
require_once(Config::$configuration->get('modelsFolder') . 'catalogos/SiglasDAO.php');
require_once(Config::$configuration->get('modelsFolder') . 'catalogos/PuestosDAO.php');
require_once(Config::$configuration->get('modelsFolder') . 'catalogos/PerfilesDAO.php');

class Usuarios
{

	public function __construct()
	{
		$this->view = new View();
		Config::$configuration->set('ruta_login', '/index.php/login/');
		Session::validaSession();
		$autorization = array('Administrador');
		if(! in_array(Session::getSession("role"), $autorization) ) 
		{
			$arr = array( 'title' => 'Sin Privilegios' );
			$this->view->show("no_privileges.html", $arr);
			die();
		}
	}
	
	public function index()
	{
		$arr = array(
			'title' => 'Usuarios',
			'css' => array(
				'librerias/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.min.css',
				'librerias/DataTables/Buttons-1.5.6/css/buttons.bootstrap.min.css',
				'librerias/Bootstrap/bootstrapvalidator-master/dist/css/bootstrapValidator.min.css',
				'librerias/jquery-ui-1.12.1/jquery-ui.css'
			),
			'js' => array(
				'librerias/DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js',
				'librerias/DataTables/FixedHeader-3.1.4/js/dataTables.fixedHeader.js',
				'librerias/DataTables/DataTables-1.10.18/js/dataTables.bootstrap.min.js',
				'librerias/DataTables/Buttons-1.5.6/js/dataTables.buttons.min.js',
				'librerias/DataTables/Buttons-1.5.6/js/jszip.min.js',
				'librerias/DataTables/Buttons-1.5.6/js/pdfmake.min.js',
				'librerias/DataTables/Buttons-1.5.6/js/vfs_fonts.js',

				'librerias/DataTables/Buttons-1.5.6/js/buttons.html5.min.js',
				'librerias/DataTables/Buttons-1.5.6/js/buttons.print.min.js',

				'librerias/Bootstrap/bootstrapvalidator-master/dist/js/bootstrapValidator.min.js',
				// 'librerias/jquery-ui-1.12.1/jquery-ui.js',
				// 'librerias/moment/moment.js',
				'js/validaciones/usuarios-validation.js',
				'js/usuarios.js'
			)
		);
		$this->view->show("usuarios/usuarios.phtml", $arr);
	}

	public function siglas(){
		// $data = Funciones::getDataGet();
		// $filtros = Funciones::generaFiltroSql($data['filtros']);
		$siglas = SiglasDAO::all();
		Funciones::imprimeJson($siglas);
	}

	public function puestos(){
		$puestos = PuestosDAO::All();
		Funciones::imprimeJson($puestos);
	}

	public function perfiles(){
		$perfiles = PerfilesDAO::All();
		Funciones::imprimeJson($perfiles);
	}


	public function show(){
		$usuarios = UsuariosDAO::all();
		Funciones::imprimeJson($usuarios);
	}

	public function store(){
		$data = Funciones::getDataPost();

		$data['ix'] = GeneradorIx::xAKN('DKN');
		$data['fx'] = date('Y-m-d H:i:s');
		$data['ps'] = sha1('123');
		$insert = UsuariosDAO::Save($data);

		Funciones::imprimeJson($insert);
	}

	public function update(){
		$data = Funciones::getDataPost();
		$update = UsuariosDAO::Update($data);
		Funciones::imprimeJson($update);
	}

}
