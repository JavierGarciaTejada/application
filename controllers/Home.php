<?php 

defined("PROJECTPATH") or die("Access error");
require_once Config::$configuration->get('modelsFolder') . 'HomeDAO.php';

class Home
{
  
  public $view;
  
  function __construct()
  {
		$this->view = new View();
		Config::$configuration->set('ruta_login', '/index.php/login/');
		Session::$_name_session = "ADMINAFA";
		Session::validaSession();
		$autorization = array('Administrador', 'Evaluador', 'Autorizador', 'Solicitante', 'Consultante');
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
  	$filtro = Perfil::FiltroHomeRole();
	$options_menu = HomeDAO::getHomeMenu($filtro);
	$options = array();

	foreach ($options_menu as $key => $value) {
		$options[$value['tm']][] = $value;
	}

	$arr = array(
		'title' => 'Panel de inicio',
		'options' => $options,
		'css' => array(
			'css/home.css'
		),
		'js' => array(
			'js/home.js'
		)
	);
	$this->view->show("index.phtml", $arr);
  }
  
  public function error404()
  {
    $this->view->error404('error_404.html', array('title' => 'Pagina no encontrada'));
  }
  
  public function muestra_gratificacion()
  {
    $this->view->show('muestra_gratificacion.html', array('title' => 'Exitoso'));
  }
  
  public function login()
  {
		$this->view->viewLogin('');
  }
  
  public function datos_sesion()
  {
		$var_sess = Session::datosSession();
		$var_arr = array_merge(array('session' => Session::$_session_stat), $var_sess);
		Funciones::imprimeJson($var_arr);
  }
  
  public function cerrar_sesion()
  {
		$die = Session::destroySession();
		if( $die == true )
		{
			$mensaje = "Se cierra sesión satisfactoriamente.";
			$valid = true;
			$clase = "alertify-success";
			$href = Config::$configuration->get('server_path') ."?controlador=home&accion=login";
		}
		else
		{
			$mensaje = "Ocurrio un error al cerrar la sesión.";
			$valid = false;
			$clase = "alertify-danger";
			$href = "";
		}
		Funciones::imprimeJson(array('mensaje' => $mensaje, 'valid' => $valid, 'clase' => $clase, 'redirecciona' => $href));
  }
  
  public function reloj()
  {
		//2017-12-12 12:12:12
		//0123456789012345678
		$fecConv = array("fecha" => Funciones::getDate());
    echo $fecConv['fecha'];
  }
  
  public function relojServer()
  {
		$fecConv = Funciones::getDate();
		$dia = substr($fecConv, 8, 2);
		$mes = substr($fecConv, 5, 2);
		$year = substr($fecConv, 0, 4);
		$hora = substr($fecConv, 11, 2);
		$minuto = substr($fecConv, 14, 2);
		$seconds = substr($fecConv, 17, 2);
		$horas = array('12','01','02','03','04','05','06','07','08','09','10','11','12','01','02','03','04','05','06','07','08','09','10','11');
		$meses = array('','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');
		if( $hora > '11' ) 
		{
			$format = "PM";
		} else {
			$format = "AM";
		}
		$fecha = array(
			'date' => $dia .'-'. $meses[(integer)$mes] .'-'. $year,
			'format' => $format,
			'hour' => $horas[(integer)$hora],
			'minute' => $minuto,
			'seconds' => $seconds
		);
    Funciones::imprimeJson($fecha);
  }
}
