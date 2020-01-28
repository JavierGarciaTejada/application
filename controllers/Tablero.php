<?php 
error_reporting(E_ALL ^ E_NOTICE);
defined("PROJECTPATH") or die("Access error");
date_default_timezone_set("America/Mexico_City");
require_once Config::$configuration->get('modelsFolder') . 'tableros/TableroDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'EvaluacionesDAO.php';

class Tablero
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
			'title' => 'Tablero de Control',
			'css' => array(
				'librerias/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.min.css',
				'librerias/DataTables/Buttons-1.5.6/css/buttons.bootstrap.min.css',
				//'librerias/Bootstrap/bootstrapvalidator-master/dist/css/bootstrapValidator.min.css',
				'librerias/jquery-ui-1.12.1/jquery-ui.css',
				// 'librerias/Highcharts-7.1.2/code/css/highcharts.css',
				'css/tablero.css'
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
				'js/tablero.js'
			)

		);
		$this->view->show("tableros/tablero.phtml", $arr);
	}

	public function dataTablero(){

		$data = Funciones::getDataGet();
		$filtros = Funciones::generaFiltroSql($data['filtros']);

		$data = array();
		$data['liberadas'] = $this->liberadasMes($filtros);
		// $data['periodos'] = $this->periodo($filtros);
		$data['periodos'] = $this->periodoNorma($filtros);
		$data['acumulado'] = $this->acumulado($filtros);
		$data['promedios'] = $this->promedioAcumuladoTiempos($filtros);

		Funciones::imprimeJson($data);
	}

	public function dataTablaBasic(){
		$data = Funciones::getDataGet();
		$anterior = date('Y', strtotime('-1 year'));
		$gerencia = empty($data['gerencia']) ? "" : " AND al = '".$data['gerencia']."' ";
		$filtro = " YEAR(fl) >= '$anterior' $gerencia";

		$total = TableroDAO::EvaluacionesLiberadasAnio($filtro);
		$mensual = TableroDAO::EvaluacionesLiberadasMes($filtro);

		$mensual['data'] = array_reverse($mensual['data']);

		foreach ($mensual['data'] as $key => $value) {
			$porcentaje = ( (int)$value['cantidad_lib'] * 100 ) / (int)$total['data'][0]['total'];
			$mensual['data'][$key]['porcentaje'] = number_format($porcentaje, 2, '.', '');
		}

		// $mensual = array_reverse($mensual['data']);

		Funciones::imprimeJson($mensual);
	}

	public function dataTablaPrediccion(){
		$data = Funciones::getDataGet();
		$filtros = Funciones::generaFiltroSql($data['filtros']);

		$evaluaciones = TableroDAO::EvaluacionesEnProceso($filtros);
		$proyectado = array();

		foreach ($evaluaciones['data'] as $key => $value) {

			$dias = FuncionesEvaluacion::calculaDiferenciaFechas($value['fs'], date('Y-m-d h:i:s'));
			for ($i=0; $i <= 5; $i++) { 

				$diasProyectados = $dias + $i;

				if ($diasProyectados >=1 && $diasProyectados <=15 ){
					$periodo[$i]['1_15']++;
					$proyectado['ids']['1_15'][$value['id']] = $value['id'];
				}
				elseif ($diasProyectados >15 && $diasProyectados <=30 ){
					$periodo[$i]['16_30']++;
					$proyectado['ids']['16_30'][$value['id']] = $value['id'];
				}
				elseif (($diasProyectados >30 && $diasProyectados <=60 )) {
					$periodo[$i]['31_60']++;
					$proyectado['ids']['31_60'][$value['id']] = $value['id'];
				}
				elseif (($diasProyectados >60 && $diasProyectados <=90 )) {
					$periodo[$i]['61_90']++;
					$proyectado['ids']['61_90'][$value['id']] = $value['id'];
				}
				elseif (($diasProyectados >90 && $diasProyectados <=120 )) {
					$periodo[$i]['91_120']++;
					$proyectado['ids']['91_120'][$value['id']] = $value['id'];
				}

			}
			
		}

		foreach ($periodo as $key => $value) {
			$proyectado['cant']['1_15'][]  = isset($value['1_15'])  ? $value['1_15'] : 0;
			$proyectado['cant']['16_30'][] = isset($value['16_30']) ? $value['16_30'] : 0;
			$proyectado['cant']['31_60'][] = isset($value['31_60']) ? $value['31_60'] : 0;
			$proyectado['cant']['61_90'][] = isset($value['61_90']) ? $value['61_90'] : 0;
			$proyectado['cant']['91_120'][] = isset($value['91_120']) ? $value['91_120'] : 0;
		}

		$proyectado['cant'] = array_reverse($proyectado['cant']);

		Funciones::imprimeJson($proyectado);
	}

	public function liberadasMes($filtros = ""){

		$lib = TableroDAO::EvaluacionesLiberadasMes($filtros);
		$libMes = [];
		if( ! empty($lib['data']) ){
			//
			foreach ($lib['data'] as $key => $value) {
				$lib['grafica'][$value['anio']]['name'] = $value['anio'];
				$lib['grafica'][$value['anio']]['data'][] = (int)$value['cantidad_lib'];
			}

			foreach ($lib['grafica'] as $key => $value)
				$libMes[] = $value;
		}

		return $libMes;

	}

	public function periodo($filtros = ""){

		$periodos = array();

		$periodo1 = TableroDAO::EvaluacionesPeriodo(0, 5, 10, 15, $filtros);
		$periodos[] = $this->getProcentaje('evaluaciones de 1 a 15', array('de 1 a 5 días', 'de 6 a 10 días', 'de 11 a 15 días'), $periodo1['data'][0] );

		$periodo2 = TableroDAO::EvaluacionesPeriodo(15, 20, 25, 30, $filtros);
		$periodos[] = $this->getProcentaje('evaluaciones de 16 a 30', array('de 16 a 20 días', 'de 21 a 25 días', 'de 26 a 30 días'), $periodo2['data'][0] );

		$periodo3 = TableroDAO::EvaluacionesPeriodo(30, 40, 50, 60, $filtros);
		$periodos[] = $this->getProcentaje('evaluaciones de 31 a 60', array('de 31 a 40 días', 'de 41 a 50 días', 'de 51 a 60 días'), $periodo3['data'][0] );

		$periodo4 = TableroDAO::EvaluacionesPeriodo(60, 70, 80, 90, $filtros);
		$periodos[] = $this->getProcentaje('evaluaciones de 61 a 90', array('de 61 a 70 días', 'de 71 a 80 días', 'de 81 a 90 días'), $periodo4['data'][0] );

		$periodo5 = TableroDAO::EvaluacionesPeriodo(90, 100, 110, 120, $filtros);
		$periodos[] = $this->getProcentaje('evaluaciones de 91 a 120', array('de 91 a 100 días', 'de 101 a 110 días', 'de 111 a 120 días'), $periodo5['data'][0] );

		return $periodos;

	}

	public function periodoNorma($filtros = ""){

		$periodos = array();

		$periodo1 = TableroDAO::EvaluacionesPeriodoNorma(0, 5, 10, 15, $filtros);
		$r = array( array(0, 5), array(5, 10), array(10, 15) );
		$periodos[] = $this->getProcentaje('evaluaciones de 0 a 15', array('de 0 a 5 días', 'de 6 a 10 días', 'de 11 a 15 días'), $periodo1['data'][0], $r );

		$periodo2 = TableroDAO::EvaluacionesPeriodoNorma(0, 10, 20, 30, $filtros);
		$r = array( array(0, 10), array(10, 20), array(20, 30) );
		$periodos[] = $this->getProcentaje('evaluaciones de 0 a 30', array('de 0 a 10 días', 'de 11 a 20 días', 'de 21 a 30 días'), $periodo2['data'][0], $r );

		$periodo3 = TableroDAO::EvaluacionesPeriodoNorma(0, 15, 30, 45, $filtros);
		$r = array( array(0, 15), array(15, 30), array(30, 45) );
		$periodos[] = $this->getProcentaje('evaluaciones de 0 a 45', array('de 0 a 15 días', 'de 16 a 30 días', 'de 31 a 45 días'), $periodo3['data'][0], $r );

		$periodo4 = TableroDAO::EvaluacionesPeriodoNorma(0, 20, 40, 60, $filtros);
		$r = array( array(0, 20), array(20, 40), array(40, 60) );
		$periodos[] = $this->getProcentaje('evaluaciones de 0 a 60', array('de 0 a 20 días', 'de 21 a 40 días', 'de 41 a 60 días'), $periodo4['data'][0], $r );

		$periodo5 = TableroDAO::EvaluacionesPeriodoNorma(0, 30, 60, 90, $filtros);
		$r = array( array(0, 30), array(30, 60), array(60, 90) );
		$periodos[] = $this->getProcentaje('evaluaciones de 0 a 90', array('de 0 a 30 días', 'de 31 a 60 días', 'de 61 a 90 días'), $periodo5['data'][0], $r );

		return $periodos;

	}

	public function periodoUnico(){

		$data = Funciones::getDataGet();
		$rangos = $data['rangos'];
		$filtros = Funciones::generaFiltroSql($data['filtros']);

		$periodo = TableroDAO::EvaluacionesPeriodoUnico($rangos[0], $rangos[1], $filtros);

		Funciones::imprimeJson( $periodo );

	}

	public function getProcentaje($titulo, $nombres, $data, $rangos){
		$titulo = "<strong>".$data['total']."</strong> ".$titulo;
		$v = array('titulo' => $titulo, 'values' => array(), 'total' => intval($data['total']), 'maximo' => $data['mx'] );
		for ($i=0; $i < count($nombres); $i++) { 
			$text = ( (int)$data['p'.($i+1)] > 1 ) ? 'evaluaciones' : 'evaluación';
			$p = ($data['p'.($i+1)] * 100) == 0 ? 0 : ($data['p'.($i+1)] * 100) / $data['total'];
			$item = array( 'name' => $data['p'.($i+1)]." ". $text." ".$nombres[$i], "y" => $p , 'p' => $data['p'.($i+1)], 'desde' => $rangos[$i] );
			array_push($v['values'], $item);
		}

		return $v;
	}


	public function acumulado($filtros = ""){

		$liberadasMes = TableroDAO::EvaluacionesLiberadasMes($filtros);
		$a = array();
		$acumulado = array();
		$anioInicio = "2017";
		$acum = 0;

		foreach ($liberadasMes['data'] as $key => $value) {
			if( $anioInicio != $value['anio'] ){
				$anioInicio = $value['anio'];
				$acum = 0;
			}
			$acum += (int)$value['cantidad_lib'];
			$a[$value['anio']][] = $acum;
		}

		$i = 0;
		foreach ($a as $keya => $valuea) {
			$acumulado[$i]['name'] = $keya;
			$acumulado[$i]['data'] = $valuea;
			$i++;
		}

		return $acumulado;

	}

	public function promedioAcumuladoTiempos($filtros = ""){

		$evaluaciones = TableroDAO::EvaluacionesLiberadasPromedio();
		$promediosGraf = array();
		$promedioAcumulado = array();
		if ($evaluaciones['data'] != null){		
			foreach ($evaluaciones['data'] as $periodoProm => $valorProm) {
				$promediosGraf[$valorProm['anio_mes_lib']]['to'] = (int)$valorProm['promedio'];
			}
		}
		
		
		$a = array();
		foreach ($promediosGraf as $periodo => $perval) {
			$mes = (int)substr($periodo,-2);
			$ano = substr($periodo,0,4);
			$valorPromedio = ( ($mes-1) == 0 ) ? $perval['to'] : ($valorPromedio + $perval['to']) / 2;
			$a[ $ano ][] = $valorPromedio;
		}
		
		$i = 0;
		foreach ($a as $keya => $valuea) {
			$promedioAcumulado[$i]['name'] = $keya;
			$promedioAcumulado[$i]['data'] = $valuea;
			$i++;
		}

		return $promedioAcumulado;

	}

	public function evaluacionesGerencia(){

		$evaluaciones = TableroDAO::EvaluacionesPorGerencia();
		$proceso = TableroDAO::EvaluacionesEnProceso();
		$totalProceso = count($proceso['data']);

		$gerencias = array('titulo' => 'Evaluaciones en Proceso por Gerencia');

		foreach ($evaluaciones['data'] as $key => $value) {
			$gerencias['values'][$key]['name'] = $value['cl'];
			$gerencias['values'][$key]['desc'] = $value['no'];
			$gerencias['values'][$key]['ix'] = $value['al'];
			$p = ($value['cant'] * 100) == 0 ? 0 : ($value['cant'] * 100) / $totalProceso;
			$gerencias['values'][$key]['y'] = $p;
		}

		Funciones::imprimeJson( $gerencias );
	}

	public function presentacionGeneral(){
		$data = Funciones::getDataGet();
		$filtros = Funciones::generaFiltroSql($data['filtros']);

		$evaluaciones = EvaluacionesDAO::EvaluacionesPerfil($filtros);
		$data = array();
		$final = array();
		$final['total'] = count($evaluaciones['data']);
		$final['titulo'] = "Proveedores";

		foreach ($evaluaciones['data'] as $key => $value) {
			$data['proveedor'][$value['proveedor']]++;
			$data['solicitud'][$value['solicitud']]++;
			$data['cliente'][$value['g_cliente']]++;
			$data['mercado'][$value['mercado']]++;
		}

		foreach ($data as $k => $v) {
			foreach ($v as $key => $value) {
				$p = ($value * 100) == 0 ? 0 : ($value * 100) / $final['total'];
				$item = array( 'name' => ucfirst(mb_strtolower($key,'UTF-8')), 'y' => $p, 'cant' => $value );
				$final[$k]['values'][] = $item;
			}
		}

		Funciones::imprimeJson( $final );
	}

	public function solicitudesNuevas(){
		$data = Funciones::getDataGet();
		$filtros = Funciones::generaFiltroSql($data['filtros']);

		$nuevas = TableroDAO::EvaluacionesNuevas( $filtros );
		Funciones::imprimeJson($nuevas);
	}


	public function getListado(){
		$data = Funciones::getDataGet();
		$filtros = Funciones::generaFiltroSql($data['filtros']);
		$evaluaciones = EvaluacionesDAO::EvaluacionesPerfil($filtros, "ORDER BY DATEDIFF(NOW(),fs) DESC");
		Funciones::imprimeJson($evaluaciones);
	}


}
