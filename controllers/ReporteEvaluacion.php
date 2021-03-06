<?php 
error_reporting(0);
defined("PROJECTPATH") or die("Access error");
date_default_timezone_set("America/Mexico_City");
require_once Config::$configuration->get('modelsFolder') . 'EvaluacionesDAO.php';


class ReporteEvaluacion
{
	const PATH_EVALUACIONES = "ReporteEvaluacion/";

	public $subdirecciones = array(
		'Ll' => "Explotación/José Luis Baños Barrera",
		'Lp' => "Ingeniería/Víctor Hugo Guzmán León",
		'S' => "Desarrollo Tecnológico/Valerio Torres Tovar",
		'Ta' => "Compras/Consuelo Gómez Colín",
		#'Ta' => "Compras/Miguel Ángel Gómez Chibli",
		'To' => "Compras/Consuelo Gómez Colín",
		'Lx' => "Explotación/José Luis Baños Barrera"
	);

	
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
			'title' => 'Reporte Evaluaciones En Proceso',
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
				'js/reportes/evaluaciones.js'
			)
		);
		$this->view->show("reportes/evaluaciones.phtml", $arr);
	}

	public function EvaluacionesGeneral(){
		$data = Funciones::getDataGet();
		$where = Funciones::generaFiltroSql($data['filtros']);
		$evaluaciones = EvaluacionesDAO::EvaluacionesPerfil($where, "ORDER BY a.pd DESC");

		$ind = 1;
		$band = true;
		foreach ($evaluaciones['data'] as $key => $value) {

			// if( empty($value['pd']) && $band === true ){
			// 	$ind = 1;
			// 	$band = false;
			// }

			$siglas = substr($value['s_cliente'], 0, 2);
			$evaluaciones['data'][$key]['indice'] = $ind;
			$evaluaciones['data'][$key]['solicitante_sub'] = $this->subdirecciones[$siglas];
			$ind++;

		}

		Funciones::imprimeJson($evaluaciones);
	}

	public function EvaluacionesProceso(){

		$data = Funciones::getDataGet();
		$where = Funciones::generaFiltroSql($data['filtros']);
		$evaluaciones = EvaluacionesDAO::EvaluacionesPerfil($where);

		$resultado = array();
		$nuevo = 0;
		$existente = 0;
		$resultado['total_general'] = count($evaluaciones['data']);

		foreach ($evaluaciones['data'] as $key => $value) {

			//$value['mercado'] == "Materiales" &&
			if( $value['solicitud'] == "Especificación" )
				$value['mercado'] = "Especificación";

			$desc = trim( $value['tec_equipo'] );// ." ". trim($value['proveedor']);
			
			if( (int)$value['pd'] == 1 ){
				$nuevo++;
				$resultado['table']['nuevo'][ $value['mercado'] ]['data'][] = $value;
				$resultado['table']['nuevo'][$value['mercado']]['desc'][ $desc ][] = $value['el'];
			}else{
				$existente++;
				$resultado['table']['existente'][ $value['mercado'] ]['data'][] = $value;
				$resultado['table']['existente'][$value['mercado']]['desc'][ $desc ][] = $value['el'];
			}

		}

		$resultado['total_nuevo'] = $nuevo;
		$resultado['total_existente'] = $existente;

		Funciones::imprimeJson($resultado);

	}

	public function EvaluacionesSolicitante(){

		$data = Funciones::getDataGet();
		$where = Funciones::generaFiltroSql($data['filtros']);
		$evaluaciones = EvaluacionesDAO::EvaluacionesPerfil($where);

		$resultado = array();
		$resultado['total_general'] = count($evaluaciones['data']);
		$resultado['solicitantes'] = array();

		foreach ($evaluaciones['data'] as $key => $value) {

			$siglas = substr($value['s_cliente'], 0, 2);
			$resultado['table'][ $this->subdirecciones[$siglas] ][ $value['proyecto_asociado'] ][ $value['mercado'] ][] = $value['el'];
			$resultado['solicitantes'][ $this->subdirecciones[$siglas] ]['total'][] = $value['el'];
			$row = $value['proyecto_asociado'] ."_". $value['mercado'];
			if( !in_array($row, $resultado['solicitantes'][ $this->subdirecciones[$siglas] ]['rowspan']) )
				$resultado['solicitantes'][ $this->subdirecciones[$siglas] ]['rowspan'][] = $row;

		}

		Funciones::imprimeJson($resultado);

	}

	public function ExistenteSolicitante(){

		$data = Funciones::getDataGet();
		$where = Funciones::generaFiltroSql($data['filtros']);
		$evaluaciones = EvaluacionesDAO::EvaluacionesPerfil($where);

		$resultado = array();
		$resultado['total_general'] = count($evaluaciones['data']);
		$resultado['solicitantes'] = array();

		foreach ($evaluaciones['data'] as $key => $value) {

			$siglas = substr($value['s_cliente'], 0, 2);
			$resultado['table'][ $this->subdirecciones[$siglas] ][ $value['prod_ex'] ][] = $value['el'];
			$resultado['totales'][ $this->subdirecciones[$siglas] ]++;
			$resultado['solicitantes'][ $this->subdirecciones[$siglas] ]['total'][] = $value['el'];
			$row = $value['prod_ex'] ."_". $value['subprod_ex'];
			if( !in_array($row, $resultado['solicitantes'][ $this->subdirecciones[$siglas] ]['rowspan']) )
				$resultado['solicitantes'][ $this->subdirecciones[$siglas] ]['rowspan'][] = $row;

		}

		Funciones::imprimeJson($resultado);

	}


}
