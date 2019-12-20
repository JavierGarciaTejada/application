<?php 
error_reporting(0);
defined("PROJECTPATH") or die("Access error");
date_default_timezone_set("America/Mexico_City");
require_once Config::$configuration->get('modelsFolder') . 'inventarios/InventarioEquiposDAO.php';


class InventarioEquipos
{
	const PATH_INVENTARIO = "InventarioEquipos/";
	
	public function __construct()
	{
		$this->view = new View();
		Config::$configuration->set('ruta_login', '/index.php/login/');
		//Session::$_name_session = "ADMINAFA";
		Session::validaSession();
		$autorization = array('Administrador', 'Evaluador', 'Autorizador', 'Solicitante', 'Consultante');
		if(! in_array(Session::getSession("role"), $autorization) ) 
		{
			$arr = array('title' => 'Sin Privilegios');
			$this->view->show("no_privileges.html", $arr);
			die();
		}
	}
	
	public function index()
	{
		// $enProceso = $this->Intervalos();
		$arr = array(
			'title' => 'Inventario Equipos',
			// 'evaluaciones' => $enProceso,
			'css' => array(
				'librerias/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.min.css',
				'librerias/DataTables/Buttons-1.5.6/css/buttons.bootstrap.min.css',
				'librerias/Bootstrap/bootstrapvalidator-master/dist/css/bootstrapValidator.min.css',
				'librerias/jquery-ui-1.12.1/jquery-ui.css',
				'css/inventarios/inventarioEquipos.css'
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
				'librerias/jquery-ui-1.12.1/jquery-ui.js',
				'librerias/moment/moment.js',
				'js/validaciones/inventario-validation.js',
				'js/inventarios/inventarioEquipos.js'
			)
		);
		$this->view->show("inventarios/InventarioEquipos.phtml", $arr);
	}

	public function getRed(){
		$red = InventarioEquiposDAO::Red();
		Funciones::imprimeJson($red);
	}
	public function getEquipos(){
		$equipos = InventarioEquiposDAO::Equipos();
		Funciones::imprimeJson($equipos);
	}
	public function getProveedores(){
		$proveedor = InventarioEquiposDAO::Proveedores();
		Funciones::imprimeJson($proveedor);
	}

	public function getInventarioGeneral(){
		$inventario = InventarioEquiposDAO::InventarioGeneral();
		Funciones::imprimeJson($inventario);
	}

	public function registraInventario(){
		$data = Funciones::getDataPost();
		$inventario = InventarioEquiposDAO::RegistraInventario($data);
		Funciones::imprimeJson($inventario);
	}

	public function modificarInventario(){
		$data = Funciones::getDataPost();
		$inventario = InventarioEquiposDAO::ModificarInventario($data);
		Funciones::imprimeJson($inventario);
	}

	public function getTecnologia(){
		$tecnologia = InventarioEquiposDAO::Tecnologia();
		Funciones::imprimeJson($tecnologia);
	}

}
