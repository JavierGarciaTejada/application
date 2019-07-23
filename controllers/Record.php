<?php 
error_reporting(E_ALL ^ E_NOTICE);
defined("PROJECTPATH") or die("Access error");
date_default_timezone_set("America/Mexico_City");
require_once Config::$configuration->get('modelsFolder') . 'EvaluacionesDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'tableros/RecordDAO.php';

class Record
{
	const PATH_EVALUACIONES = "tablero/";

	
	public function __construct()
	{
		$this->view = new View();
		Config::$configuration->set('ruta_login', '/index.php/login/');
		//Session::$_name_session = "ADMINAFA";
		Session::validaSession();
		$autorization = array('Administrador', 'Evaluador', 'Autorizador');
		if(! in_array(Session::getSession("role"), $autorization) ) 
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
		$arr = array(
			'title' => 'Tablero Record',
			'css' => array(
				'librerias/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.min.css',
				'librerias/DataTables/Buttons-1.5.6/css/buttons.bootstrap.min.css',
				//'librerias/Bootstrap/bootstrapvalidator-master/dist/css/bootstrapValidator.min.css',
				'librerias/jquery-ui-1.12.1/jquery-ui.css'
				,'librerias/Highcharts-7.1.2/code/css/highcharts.css'
				//,'css/tablero.css'
			),
			'js' => array(
				'librerias/DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js',
				'librerias/DataTables/DataTables-1.10.18/js/dataTables.bootstrap.min.js',
				// 'librerias/DataTables/Buttons-1.5.6/js/dataTables.buttons.min.js',
				// 'librerias/DataTables/Buttons-1.5.6/js/jszip.min.js',
				// 'librerias/DataTables/Buttons-1.5.6/js/pdfmake.min.js',
				// 'librerias/DataTables/Buttons-1.5.6/js/vfs_fonts.js',

				// 'librerias/DataTables/Buttons-1.5.6/js/buttons.html5.min.js',
				// 'librerias/DataTables/Buttons-1.5.6/js/buttons.print.min.js',

				//'librerias/Bootstrap/bootstrapvalidator-master/dist/js/bootstrapValidator.min.js',
				'librerias/jquery-ui-1.12.1/jquery-ui.js',
				'librerias/moment/moment.js',

				'librerias/Highcharts-7.1.2/code/highcharts.js',
				'librerias/Highcharts-7.1.2/code/highcharts-3d.js',
				'librerias/Highcharts-7.1.2/code/modules/cylinder.js',
				// 'librerias/Highcharts-7.1.2/code/modules/series-label.js',
				// 'librerias/Highcharts-7.1.2/code/modules/exporting.js',
				// 'librerias/Highcharts-7.1.2/code/modules/export-data.js',
				'js/record.js'
			)

		);
		$this->view->show("tableros/record.phtml", $arr);
	}


	public function getRecords(){

		$records = RecordDAO::RecordIngeniero();
		Funciones::imprimeJson($records);

	}

	public function getEvaluacionesIngeniero(){
		$data = Funciones::getDataGet();
		$filtros = Funciones::generaFiltroSql($data['filtros']);
		$evaluaciones = EvaluacionesDAO::EvaluacionesPerfil($filtros);
		Funciones::imprimeJson($evaluaciones);
	}

	public function getEvaluacionesIngenieroEtapa(){
		$data = Funciones::getDataGet();
		$filtros = Funciones::generaFiltroSql($data['filtros']);
		$evaluaciones = RecordDAO::EvaluacionesEtapa($filtros);
		Funciones::imprimeJson($evaluaciones);
	}

	public function EvaluacionesPromedioLiberacion(){
		$data = Funciones::getDataGet();
		$filtros = Funciones::generaFiltroSql($data['filtros']);
		$evaluaciones = RecordDAO::EvaluacionesPromedio($filtros);
		Funciones::imprimeJson($evaluaciones);
	}

}
