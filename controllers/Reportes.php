<?php 
error_reporting(0);
defined("PROJECTPATH") or die("Access error");
date_default_timezone_set("America/Mexico_City");
require_once Config::$configuration->get('modelsFolder') . 'ReportesDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'EvaluacionesDAO.php';


class Reportes
{
	const PATH_EVALUACIONES = "Reportes/";

	public $suma = array(0,0,0,0);

	
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
			'title' => 'Reporte Intervalos',
			// 'evaluaciones' => $enProceso,
			'css' => array(
				'librerias/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.min.css',
				'librerias/DataTables/Buttons-1.5.6/css/buttons.bootstrap.min.css',
				'librerias/Bootstrap/bootstrapvalidator-master/dist/css/bootstrapValidator.min.css',
				'librerias/jquery-ui-1.12.1/jquery-ui.css',
				'css/reportes/intervalos.css'
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
				'js/reportes/intervalos.js'
			)
		);
		$this->view->show("reportes/intervalos.phtml", $arr);
	}

	public function Intervalos(){

		$data = Funciones::getDataGet();
		$where = Funciones::generaFiltroSql($data['filtros']);

		// $where = " et = 619056264933549 AND especial = 0";
		$nuevas = EvaluacionesDAO::EvaluacionesEtapaCount('619056264933547');
		$evaluaciones = EvaluacionesDAO::EvaluacionesPerfil($where);
		$evaluaciones = FuncionesEvaluacion::asignacionFechas($evaluaciones);

		$intervalos = array();
		$intervalos['docs'] = FuncionesEvaluacion::IntervaloDocumentosFoas($evaluaciones);
		$this->sumaPorPeriodo($intervalos['docs']);

		$intervalos['func'] = FuncionesEvaluacion::IntervaloFuncionalidadConcepto($evaluaciones);
		$this->sumaPorPeriodo($intervalos['func']);

		$intervalos['espec'] = FuncionesEvaluacion::IntervaloEspecificacion($evaluaciones);
		$this->sumaPorPeriodo($intervalos['espec']['act']);
		$this->sumaPorPeriodo($intervalos['espec']['nue']);

		$intervalos['caract'] = FuncionesEvaluacion::IntervaloCaracterizacion($evaluaciones);
		$this->sumaPorPeriodo($intervalos['caract']['act']);
		$this->sumaPorPeriodo($intervalos['caract']['nue']);

		$intervalos['materiales'] = FuncionesEvaluacion::IntervaloMateriales($evaluaciones);
		$this->sumaPorPeriodo($intervalos['materiales']['cidec']);
		$this->sumaPorPeriodo($intervalos['materiales']['local']);

		$intervalos['equipos'] = FuncionesEvaluacion::IntervaloEquipos($evaluaciones);
		$this->sumaPorPeriodo($intervalos['equipos']['act']);
		$this->sumaPorPeriodo($intervalos['equipos']['nue']);

		$intervalos['sql'] = $evaluaciones['sql'];
		$intervalos['nuevas'] = $nuevas['data'][0];
		$intervalos['proceso']['total'] = array_sum($this->suma);
		$intervalos['totales'] = $this->suma;
		$intervalos['ids'] = FuncionesEvaluacion::$general;

		Funciones::imprimeJson($intervalos);
	}


	public function sumaPorPeriodo($periodo){
		$array = array_values($periodo);
		foreach ($array as $key => $value) {
			$this->suma[$key] += (int)$array[$key];
		}
	}


	public function IntervalosDetalle(){

		$data = Funciones::getDataGet();
		$where = " a.id IN (".implode(',', $data['ids']).")";

		$evaluaciones = EvaluacionesDAO::EvaluacionesPerfil($where);
		Funciones::imprimeJson($evaluaciones);

	}





}
