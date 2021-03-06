<?php 

defined("PROJECTPATH") or die("Access error");
date_default_timezone_set("America/Mexico_City");
require_once Config::$configuration->get('modelsFolder') . 'EvaluacionesDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'UsuariosDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'catalogos/AreasLaboratorioDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'catalogos/ResultadosDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'catalogos/NuevosDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'catalogos/TecnologiaEquipoDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'catalogos/ProyectoAsociadoDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'catalogos/MercadoDAO.php';


class EvaluacionesCliente
{
	const PATH_EVALUACIONES = "evaluacionescliente/";

	
	public function __construct()
	{
		$this->view = new View();
		Config::$configuration->set('ruta_login', '/index.php/login/');
		//Session::$_name_session = "ADMINAFA";
		Session::validaSession();
		$autorization = array('Administrador', 'Evaluador', 'Autorizador', 'Solicitante');
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
		//$evaluaciones = EvaluacionesDAO::TotalEvaluacionesPorPerfil();
		$arr = array(
			'title' => 'Evaluaciones',
			'css' => array(
				'librerias/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.min.css',
				'librerias/DataTables/Buttons-1.5.6/css/buttons.bootstrap.min.css',
				'librerias/Bootstrap/bootstrapvalidator-master/dist/css/bootstrapValidator.min.css',
				'librerias/jquery-ui-1.12.1/jquery-ui.css',
				'css/evaluaciones.css'
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
				'js/validaciones/evaluaciones-validation.js',
				'js/cliente/evaluaciones.js'
			)
		);
		$this->view->show("cliente/evaluaciones.phtml", $arr);
	}

	public function notificacionCorreo(){

		$data = Funciones::getDataPost();
		$filtro = Funciones::generaFiltroSql($data['filtros']);
		$evaluacion = EvaluacionesDAO::EvaluacionesPerfil($filtro);

		$values = array();
		$values['TITLE'] = "<strong>Nuevo registro de solicitud de evaluación <br>En espera de aceptaci&oacute;n por Laboratorio.</strong>";
		$values['HEADER'] = "Telmex - Sistema Autom&aacute;tico de Administraci&oacute;n de Evaluaciones (SAAE) &copy;";

		foreach ($evaluacion['data'] as $key => $value) {
			$el = $value['el'];
			$tbody = " <tr>
						<td  align='center' border='1'>".$value['el']."</td>  
						<td  align='center'>".$value['cl']."</td>
						<td  align='center'>".$value['no']."</td>
						<td  align='center'>".$value['a_laboratorio']."</td>
						<td  align='center'>".$value['s_cliente']."</td>
						<td  align='center'>".$value['proveedor']."</td>
						<td  align='center'>".$value['tipo_solicitud']."</td>
						<td  align='center'style='background-color:".$value['color_prioridad'].";'>".$value['prioridad']."</td>
						<td  align='center'>".$value['fs']." </td>
						<td  align='center'>".$value['fo']."</td>
					</tr>";
		}

		
		$values['BODY'] = $tbody;
	    $plantilla = Funciones::getPlantillaEmail('plantilla_correo_evaluacion.html', $values);

		$subject = " Nuevo registro de solicitud de evaluación - ".$el;
		$body = $plantilla;
		$nameMail = "Sistema Automático de Administración de Evaluaciones";
		$arrayAddress = array('javigar31@gmail.com', 'fgtejada@telmex.com');
		$mail = Funciones::sendMailEvaluciones($subject, $body, $nameMail, $arrayAddress);
		Funciones::imprimeJson($mail);
	}

	public function getEvaluacionesJson(){
		$filtro = Perfil::FiltroListadoEvaluaciones();
		$evaluaciones = EvaluacionesDAO::EvaluacionesPerfil($filtro);
		$evaluaciones = FuncionesEvaluacion::asignacionFechas($evaluaciones);
		Funciones::imprimeJson($evaluaciones);
	}

	public function getAreasLab(){
		$areas = AreasLaboratorioDAO::AreasLaboratorio();
		Funciones::imprimeJson($areas);
	}

	public function getResultados(){
		$resultados = ResultadosDAO::All();
		Funciones::imprimeJson($resultados);
	}

	public function getSiglasCli(){
		$siglas = EvaluacionesDAO::SiglasCliente();
		Funciones::imprimeJson($siglas);
	}

	public function getProveedores(){
		$proveedores = EvaluacionesDAO::Proveedores();
		Funciones::imprimeJson($proveedores);
	}

	public function getTipoEval(){
		$TipoEvaluaciones = EvaluacionesDAO::TipoEvaluacion();
		Funciones::imprimeJson($TipoEvaluaciones);
	}

	public function getPrioridad(){
		$prioridad = EvaluacionesDAO::Prioridad();
		Funciones::imprimeJson($prioridad);
	}

	public function getNuevos(){
		$nuevos = NuevosDAO::All();
		Funciones::imprimeJson($nuevos);
	}

	public function getUsuariosGerencia(){
		$filtro = Perfil::FiltroUsuarioGerencia();
		$all = UsuariosDAO::All($filtro);

		$usuarios = array();
		foreach ($all['data'] as $key => $value) 
			$usuarios[$value['puesto']][] = $value;

		Funciones::imprimeJson($usuarios);
	}

	public function getTecnologiaEquipo(){
		$tecEquipo = TecnologiaEquipoDAO::All();
		Funciones::imprimeJson($tecEquipo);
	}

	public function getProyectoAsociado(){
		$proyectoAsociado = ProyectoAsociadoDAO::All();
		Funciones::imprimeJson($proyectoAsociado);
	}

	public function getMercado(){
		$mercado = MercadoDAO::All();
		Funciones::imprimeJson($mercado);
	}


	public function registraEvaluacion(){

		$data = Funciones::getDataPost();

		//DATOS POR DEFECTO AL REGISTRAR NUEVA EVALUACION
		$data['ix'] = GeneradorIx::xAKN('DKN');
		$data['fx'] = date('Y-m-d h:i:s');
		$data['ux'] = "0";
		$data['rx'] = "0";
		$data['et'] = "619056264933547";
		$data['sx'] = "0";
		$data['re'] = "419056265844515";
		$data['sg'] = "919056264925405";
		$data['ig'] = "919056264925405";
		$data['nu'] = "219056265840690";
		$data['te'] = "319056265848526";
		$data['pa'] = "219056265865197";
		$data['me'] = "831264706543206";

		$insert = EvaluacionesDAO::RegistrarEvaluacion($data);
		Funciones::imprimeJson($insert);

	}

	public function editarEvaluacion(){

		$data = Funciones::getDataPost();
		$update = EvaluacionesDAO::EditarEvaluacion($data);
		Funciones::imprimeJson($update);

	}
	public function editarEvaluacionProceso(){

		$data = Funciones::getDataPost();
		$e = array();
		$l = array();
		parse_str($data['evaluacion'], $e);
		parse_str($data['lab'], $l);

		$values = array_merge($e, $l);
		
		$update = EvaluacionesDAO::EditarEvaluacionProceso($values);
		Funciones::imprimeJson($update);

	}

	public function aceptarEvaluacion(){

		$data = Funciones::getDataPost();
		//DATOS POR DEFECTO AL ACEPTAR EVALUACION
		$data['et'] = "619056264933549";
		$data['fa'] = date('Y-m-d H:m:i');
		$update = EvaluacionesDAO::AceptarEvaluacion($data);
		Funciones::imprimeJson($update);

	}

	public function rechazarEvaluacion(){

		$data = Funciones::getDataPost();
		//DATOS POR DEFECTO AL RECHAZAR EVALUACION
		$data['et'] = "619056264933548";
		$data['fa'] = date('Y-m-d H:m:i');
		$update = EvaluacionesDAO::RechazarEvaluacion($data);
		Funciones::imprimeJson($update);

	}

	public function reenviarEvaluacion(){

		$data = Funciones::getDataPost();
		//DATOS POR DEFECTO AL REENVIAR EVALUACION
		$data['et'] = "619056264933547";
		$data['fa'] = date('Y-m-d H:m:i');
		$data['fs'] = date('Y-m-d H:m:i');

		$update = EvaluacionesDAO::ReenviarEvaluacion($data);
		Funciones::imprimeJson($update);

	}

	public function liberarEvaluacion(){

		$data = Funciones::getDataPost();
		//DATOS POR DEFECTO AL LIBERAR EVALUACION
		$data['et'] = "619056264933550";
		$data['fa'] = date('Y-m-d H:m:i');

		$update = EvaluacionesDAO::LiberarEvaluacion($data);
		Funciones::imprimeJson($update);

	}

	public function cancelarEvaluacion(){

		$data = Funciones::getDataPost();
		//DATOS POR DEFECTO AL CANCELAR EVALUACION
		$data['et'] = "619056264933551";
		$data['fa'] = date('Y-m-d H:m:i');

		$update = EvaluacionesDAO::CancelarEvaluacion($data);
		Funciones::imprimeJson($update);

	}


}
