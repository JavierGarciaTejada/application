<?php 

defined("PROJECTPATH") or die("Access error");
date_default_timezone_set("America/Mexico_City");
require_once Config::$configuration->get('modelsFolder') . 'UsuariosDAO.php';

class Recuperacion
{
	const PATH_RESULTADOS = "Recuperacion/";

	
	public function __construct()
	{
		$this->view = new View();
		Config::$configuration->set('ruta_login', '/index.php/login/');
		Session::validaSession();
		$autorization = array('Administrador', 'Evaluador', 'Autorizador', 'Solicitante');
		if(! in_array(Session::getSession("role"), $autorization) ) 
		{
			$arr = array( 'title' => 'Sin Privilegios' );
			$this->view->show("no_privileges.html", $arr);
			die();
		}
	}
	
	public function index()
	{
		//$evaluaciones = EvaluacionesDAO::TotalEvaluacionesPorPerfil();
		$arr = array(
			'title' => 'Actualizar contraseña'
			,'css' => array(
			// 	'librerias/DataTables/DataTables-1.10.18/css/dataTables.bootstrap.min.css',
			// 	'librerias/DataTables/Buttons-1.5.6/css/buttons.bootstrap.min.css',
				'librerias/Bootstrap/bootstrapvalidator-master/dist/css/bootstrapValidator.min.css'
			// 	'librerias/jquery-ui-1.12.1/jquery-ui.css',
			// 	'css/evaluaciones.css'
			),
			'js' => array(
			// 	'librerias/DataTables/DataTables-1.10.18/js/jquery.dataTables.min.js',
			// 	'librerias/DataTables/DataTables-1.10.18/js/dataTables.bootstrap.min.js',
			// 	'librerias/DataTables/Buttons-1.5.6/js/dataTables.buttons.min.js',
			// 	'librerias/DataTables/Buttons-1.5.6/js/jszip.min.js',
			// 	'librerias/DataTables/Buttons-1.5.6/js/pdfmake.min.js',
			// 	'librerias/DataTables/Buttons-1.5.6/js/vfs_fonts.js',

			// 	'librerias/DataTables/Buttons-1.5.6/js/buttons.html5.min.js',
			// 	'librerias/DataTables/Buttons-1.5.6/js/buttons.print.min.js',

				'librerias/Bootstrap/bootstrapvalidator-master/dist/js/bootstrapValidator.min.js',
			// 	'librerias/jquery-ui-1.12.1/jquery-ui.js',
			// 	'librerias/moment/moment.js',
				'js/validaciones/recuperacion-validation.js',
				'js/recuperacion.js'
			)
		);
		$this->view->show("recuperacion/recuperacion.phtml", $arr);
	}

	public function actualizarPassword(){
		$data = Funciones::getDataPost();

		$id 	= $data['id'];
		$actual = $data['actual'];
		$nueva 	= $data['nueva'];
		$confirm= $data['confirmacion'];

		if( $nueva !== $confirm){
			Funciones::imprimeJson("Las contraseñas nuevas no coinciden");
			exit();
		}

		$filtro = " a.id = $id ";
		$usuario = UsuariosDAO::All($filtro);
		if( sha1($actual) === $usuario['data'][0]['ps'] ){
			$values = array('id' => $id, 'ps' => sha1($nueva));
			Funciones::imprimeJson( UsuariosDAO::UpdatePwd($values) );
		}else{
			Funciones::imprimeJson("La contraseña actual no es correcta, verifique");
		}

	}
	



}
