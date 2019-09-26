<?php 

defined("PROJECTPATH") or die("Access error");
date_default_timezone_set("America/Mexico_City");
require_once Config::$configuration->get('modelsFolder') . 'EvaluacionesDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'UsuariosDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'catalogos/AreasLaboratorioDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'catalogos/ResultadosDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'catalogos/NuevosDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'catalogos/NuevosProductosDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'catalogos/ProductoExistenteDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'catalogos/TecnologiaEquipoDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'catalogos/ProyectoAsociadoDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'catalogos/MercadoDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'catalogos/ProveedoresDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'catalogos/TipoSolicitudDAO.php';
require_once Config::$configuration->get('modelsFolder') . 'catalogos/RechazoCancelacionDAO.php';

class Evaluaciones
{
	const PATH_EVALUACIONES = "evaluaciones/";

	
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
				'js/evaluaciones.js'
			)
		);
		$this->view->show("evaluaciones/index.phtml", $arr);
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
			$al = $value['al'];
			$tbody = " <tr>
						<td  align='center' border='1'>".$value['el']."</td>  
						<td  align='center'>".$value['cl']."</td>
						<td  align='center'>".$value['no']."</td>
						<td  align='center'>".$value['a_laboratorio']."</td>
						<td  align='center'>".$value['s_cliente']."</td>
						<td  align='center'>".$value['proveedor']."</td>
						<td  align='center'>".$value['tipo_solicitud']."</td>
						<td  align='center' style='background-color:".$value['color_prioridad'].";'>".$value['prioridad']."</td>
						<td  align='center'>".$value['fs']." </td>
					</tr>";
		}

		
		$values['BODY'] = $tbody;
	    $plantilla = Funciones::getPlantillaEmail('plantilla_registro.html', $values);

		$subject = " Nuevo registro de solicitud de evaluación - ".$el;
		$body = $plantilla;
		$nameMail = "Sistema Automático de Administración de Evaluaciones";
		// $al = "119056262939067"; //PRUEBA PARA ENVIAR CORREO
		$directorio = FuncionesEvaluacion::RemitenteGerencia($al); // array('javigar31@gmail.com', 'fgtejada@telmex.com');
		$mail = Funciones::sendMailEvaluciones($subject, $body, $nameMail, $directorio['to'], $directorio['cp'], $directorio['bc']);
		Funciones::imprimeJson($mail);
	}

	public function getEvaluacionesJson(){
		$anio = "2019";
		$filtro = Perfil::FiltroListadoEvaluaciones($anio);
		$evaluaciones = EvaluacionesDAO::EvaluacionesPerfil($filtro);
		$evaluaciones = FuncionesEvaluacion::asignacionFechas($evaluaciones);
		// $evaluaciones = $this->asignacionTiempos($evaluaciones);
		// $evaluaciones = FuncionesEvaluacion::peridoEspecificacion($evaluaciones);
		Funciones::imprimeJson($evaluaciones);
	}

	public function asignacionTiempos($evaluaciones){

		$anio = "2019";
		$filtro = Perfil::FiltroListadoEvaluaciones($anio);
		$evaluaciones = EvaluacionesDAO::EvaluacionesPerfil($filtro);

		foreach ($evaluaciones['data'] as $key => $value) {
			
			if( strpos($value['fs'], '2019') !== false && ( $value['etapa'] == "En Proceso" || $value['etapa'] == "Nueva Solicitud" ) ){
				$dl = $this->asignaTiempoLiberacion($value);
				$ft = date('Y-m-d', strtotime( $value['fs']."+ $dl days"  ) );
				if( $dl > 0 )
					echo "UPDATE so_sol SET ft = '$ft', dl = $dl WHERE id = ".$value['id']."; #".$value['el']."<br>";
			}
			
		}
		// return $evaluaciones;
		// Funciones::imprimeJson($evaluaciones);
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
		$proveedores = ProveedoresDAO::All();
		Funciones::imprimeJson($proveedores);
	}

	public function getTipoEval(){
		$TipoEvaluaciones = TipoSolicitudDAO::All();
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

	public function getNuevosProductos(){
		$nuevosProductos = NuevosProductosDAO::All();
		Funciones::imprimeJson($nuevosProductos);
	}

	public function getProductoExistente(){
		$productoExistente = ProductoExistenteDAO::Producto();
		Funciones::imprimeJson($productoExistente);
	}

	public function getSubproductoExistente(){
		$data = Funciones::getDataGet();
		$subproductoExistente = ProductoExistenteDAO::Subproducto($data['rx']);
		Funciones::imprimeJson($subproductoExistente);
	}

	public function getUsuariosGerencia(){
		$filtro = "";// Perfil::FiltroUsuarioGerencia();
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

	public function getMotivoRechazo(){
		$data = Funciones::getDataGet();
		$filtros = Funciones::generaFiltroSql($data['filtros']);
		$rechazo = RechazoCancelacionDAO::All($filtros);
		Funciones::imprimeJson($rechazo);
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
		$diasLimite = $this->asignaTiempoLiberacion($values);

		$values['dl'] = $diasLimite;
		$values['ft'] = date('Y-m-d', strtotime( $values['fs']."+ $diasLimite days"  ) );

		$values['pe'] = ( isset($values['pe']) ) ? $values['pe'] : 0 ;
		$values['spe'] = ( isset($values['spe']) ) ? $values['spe'] : 0 ;
		
		$update = EvaluacionesDAO::EditarEvaluacionProceso($values);
		Funciones::imprimeJson($update);

	}

	public function editarEvaluacionLiberada(){

		$data = Funciones::getDataPost();
		$e = array();
		$l = array();
		parse_str($data['evaluacion'], $e);
		parse_str($data['lab'], $l);

		$values = array_merge($e, $l);
		$diasLimite = $this->asignaTiempoLiberacion($values);
		$values['dl'] = $diasLimite;
		$values['ft'] = date('Y-m-d', strtotime( $values['fs']."+ $diasLimite days"  ) );
		
		$update = EvaluacionesDAO::EditarEvaluacionLiberada($values);
		Funciones::imprimeJson($update);

	}

	public function aceptarEvaluacion(){

		$data = Funciones::getDataPost();
		//DATOS POR DEFECTO AL ACEPTAR EVALUACIO
		$data['et'] = "619056264933549";
		$data['fa'] = date('Y-m-d H:m:i');
		$diasLimite = $this->asignaTiempoLiberacion($data);
		$data['dl'] = $diasLimite;
		//$data['ft'] = date('Y-m-d', strtotime( date('Y-m-d')."+ $diasLimite days"  ) );
		$data['ft'] = date('Y-m-d', strtotime( $data['fs']."+ $diasLimite days"  ) );
		$update = EvaluacionesDAO::AceptarEvaluacion($data);

		$response = array('estatus' => $update, 'mensaje' => 'Fecha estimada de liberación: '.$data['ft'] );
		Funciones::imprimeJson($response);

	}

	public function asignaTiempoLiberacion($data){

		$modemOntAp = array('319056265848491', '319056265848509', '319056265848510');

		$tipoSolicitud = $data['ts'];
		$diasLimite = 0;
		$equipo = false;

		switch ($tipoSolicitud) {
			//documento
			case '719056262954145': $diasLimite = 15; break;
			//foas
			case '719056262954162': $diasLimite = 15; break;
			//prueba de concepto
			case '719056262954161': $diasLimite = 30; break;
			//especificaciones
			case '719056262954164': $diasLimite = $this->ProyectoNuevo($data, "219056265840691", 90, 30); break;
			//Caracterizaciones, Interoperabilidad, Equipos, Plataformas, Servicio, Software
			case '719056262954153': 
				if( !in_array($data['te'], $modemOntAp) ) $diasLimite = $this->ProyectoNuevo($data, "219056265840691", 60, 30); 
				else $equipo = true;
				break;
			case '719056262954154': 
				if( !in_array($data['te'], $modemOntAp) ) $diasLimite = $this->ProyectoNuevo($data, "219056265840691", 60, 30); 
				else $equipo = true;
				break;
			case '719056262954155': 
				if( !in_array($data['te'], $modemOntAp) ) $diasLimite = $this->ProyectoNuevo($data, "219056265840691", 60, 30); 
				else $equipo = true;
				break;
			case '719056262954157': 
				if( !in_array($data['te'], $modemOntAp) ) $diasLimite = $this->ProyectoNuevo($data, "219056265840691", 60, 30); 
				else $equipo = true;
				break;
			case '719056262954158': 
				if( !in_array($data['te'], $modemOntAp) ) $diasLimite = $this->ProyectoNuevo($data, "219056265840691", 60, 30); 
				else $equipo = true;
				break;
			case '719056262954159': 
				if( !in_array($data['te'], $modemOntAp) ) $diasLimite = $this->ProyectoNuevo($data, "219056265840691", 60, 30); 
				else $equipo = true;
				break;
			//especificaciones
			case '719056262954164': $diasLimite = $this->ProyectoNuevo($data, "219056265840691", 90, 30); break;
			
			default:
				//materiales
				if( $data['me'] == '831264706543203' ){
					$diasLimite = $this->ProyectoNuevo($data, "219056265840694", 15, 60); break;
				}
		}

		if( $equipo ){ //equipos
			$diasLimite = $this->ProyectoNuevo($data, "219056265840691", 45, 30);
		}

		//SE CONSIDERAN LOS DIAS HABILES SOLAMENTE
		// if( $diasLimite > 0 )
		// 	$diasLimite = $diasLimite + (($diasLimite / 5) * 2);

		return $diasLimite;

	}

	public function ProyectoNuevo($data, $nu, $limiteSI, $limiteNO){

		$limite = $limiteNO;
		if( $data['nu'] == $nu )
			$limite = $limiteSI;

		return $limite;
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

	public function cargaAnexosEvaluacion(){

		$result = array();
		$result['estatus'] = false;
		$result['nombreGenerado'] = null;
		$result['mensaje'] = "El archivo no pudo ser almacenado";

		//comprobamos que sea una petición ajax
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
		{

			$file = $_FILES['archivo']['name'];
			$exp = explode(".", $file);

			//genera un nombre de archivo para evitar caracteres especiales
			$nombreEvaluacion = "evaluacion_". date('Ymd_his') .".". end($exp);
			$result['nombreGenerado'] = $nombreEvaluacion;
			$result['nombre'] = $file;
			$ruta = Config::$configuration->get('anexos_path') ."evaluaciones/" . $nombreEvaluacion;

			//comprobamos si el archivo ha subido
			if ($file && move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta))
			{
				sleep(1);
				$result['estatus'] = true;
				$result['extension'] = end($exp);
				$result['mensaje'] = "Archivo subido correctamente";
			}

		}else{
			throw new Exception("Error Processing Request", 1);
		}

		Funciones::imprimeJson($result);

	}

	public function getAnexos(){

		$data = Funciones::getDataGet();
		$anexos = EvaluacionesDAO::Anexos($data);
		Funciones::imprimeJson($anexos);

	}

	public function registaAnexo(){

		$data = Funciones::getDataPost();
		$data['fx'] = date('Y-m-d h:i:s');
		$insert = EvaluacionesDAO::RegistrarAnexo($data);
		Funciones::imprimeJson($insert);

	}


}
