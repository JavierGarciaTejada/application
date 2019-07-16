<?php 

defined("PROJECTPATH") or die("Access error");
require_once Config::$configuration->get('modelsFolder') . 'BitacorasDAO.php';

class Bitacoras
{
	const PATH_BITACORAS = "bitacoras/";
	private $_file_user;
	private $_file_bitacoras;
	private $_instances = array(
		"occidente", "noreste", "norte", "noroeste", "golfo", "centro", "sureste", "metro", "ld", "q825e", "q825h", "telnor"
	);
	
	public function __construct()
	{
		$this->view = new View();
		Config::$configuration->set('ruta_login', '/index.php/login/adminafa/');
		Session::$_name_session = "ADMINAFA";
		Session::validaSession();
		if(Session::getSession("perfil") != "administrador") 
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
			'title' => 'Bitacoras AFA',
			'css' => array(
				'librerias/jquery-ui-1.12.1/jquery-ui.min.css',
				'librerias/Bootstrap/bootstrapvalidator-master/dist/css/bootstrapValidator.min.css',
				'css/bitacoras.css'
			),
			'js' => array(
				'librerias/jquery-ui-1.12.1/jquery-ui.min.js',
				'librerias/Bootstrap/bootstrapvalidator-master/dist/js/bootstrapValidator.min.js',
				'js/bitacoras.js'
			)
		);
		$this->view->show("bitacoras_view.phtml", $arr);
	}
	
	public function exporta_bitacoras()
	{
		$this->_file_bitacoras = "bitacoras_". date('dmY') .".xlsx";
		$this->setConnectInstance();
		$this->setFechaIni();
		$this->setFechaFin();
		if(BitacorasDAO::$_connect_instance == "_ALL")
		{
			$filas = array();
			foreach($this->_instances as $val)
			{
				BitacorasDAO::$_connect_instance = $val;
				$rows = BitacorasDAO::consultaLogs();
				$filas = array_merge($filas, $rows);
			}
		}
		else 
		{
			$filas = BitacorasDAO::consultaLogs();
		}
		$this->writeFileBitacoras($filas);
	}
	
	public function exporta_usuarios()
	{
		$this->_file_user = "usuarios_". date('dmY') .".xlsx";
		//$this->setConnectInstance();
		/*if(BitacorasDAO::$_connect_instance == "_ALL")
		{
			$filas = array();
			foreach($this->_instances as $val)
			{
				BitacorasDAO::$_connect_instance = $val;
				$rows = BitacorasDAO::consultaUsuarios();
				$filas = array_merge($filas, $rows);
			}
		}
		else 
		{*/
			$filas = BitacorasDAO::consultaUsuarios();
		//}
		$this->writeFileUsuarios($filas);
	}
	
	private function writeFileUsuarios($filas)
	{
		try 
		{
			require_once Config::$configuration->get('path_libraries') . 'PHPExcel-1.8/Classes/PHPExcel.php';
			$objPHPExcel = new PHPExcel();
			$l = 2;
			for($i = 0; $i < sizeof($filas); $i++)
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $l, ($i + 1));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $l, $filas[$i]['dd']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $l, $filas[$i]['area']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $l, $filas[$i]['ip']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $l, $filas[$i]['login']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $l, $filas[$i]['usuario']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $l, $filas[$i]['expediente']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $l, $filas[$i]['empresa']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $l, $filas[$i]['telefono']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("J" . $l, $filas[$i]['valida_ip']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("K" . $l, $filas[$i]['reserva']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L" . $l, $filas[$i]['fecha_alta']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("M" . $l, $filas[$i]['fecha_pwd']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("N" . $l, $filas[$i]['estilo']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("O" . $l, $filas[$i]['reportes']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P" . $l, $filas[$i]['detalles']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("Q" . $l, $filas[$i]['perfil']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("R" . $l, $filas[$i]['status']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("S" . $l, $filas[$i]['fec_ult_ses']);
				$l++;
			}
			
			//$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", "SERVIDOR");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", "DD");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C1", "Area");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D1", "IP");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E1", "LOGIN");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("F1", "USUARIO");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G1", "EXPEDIENTE");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H1", "EMPRESA");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("I1", "TELEFONO");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("J1", "VALIDA IP");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("K1", "RESERVA");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L1", "FECHA ALTA");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("M1", "FECHA CONTRASEÑA");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("N1", "ESTILO");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("O1", "REPORTES");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("P1", "DETALLES");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("Q1", "PERFIL");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("R1", "ESTATUS");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("S1", "FECHA ULTIMO ACCESO");
			
			$objPHPExcel->getActiveSheet()->getStyle('B1:S1')->applyFromArray(
				array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'ffe000')))
			);
			
			//$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
			
			$objPHPExcel->getActiveSheet()->setTitle('USUARIOS');
			$objPHPExcel->setActiveSheetIndex(0);
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename='. $this->_file_user);
			header('Cache-Control: max-age=0');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
			header('Cache-Control: cache, must-revalidate');
			header('Pragma: public');
			
			sleep(2);
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
			exit();
		}
		catch(Exception $e)
		{
			echo "Ocurrio un error al procesar el archivo de usaurios. Error! : ". $e->getMessage();
		}
	}
	
	private function writeFileBitacoras($filas)
	{
		try 
		{
			require_once Config::$configuration->get('path_libraries') . 'PHPExcel-1.8/Classes/PHPExcel.php';
			$objPHPExcel = new PHPExcel();
			$l = 2;
			for($i = 0; $i < sizeof($filas); $i++)
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $l, $filas[$i]['servidor']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $l, $filas[$i]['fecha']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $l, $filas[$i]['ip']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $l, $filas[$i]['login']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $l, $filas[$i]['query']);
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $l, $filas[$i]['filtros']);
				$l++;
			}
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", "SERVIDOR");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", "FECHA");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C1", "IP");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D1", "LOGIN");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E1", "QUERY");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("F1", "FILTROS");
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray(
				array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'ffe000')))
			);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename='. $this->_file_bitacoras);
			header('Cache-Control: max-age=0');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
			header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
			header('Cache-Control: cache, must-revalidate');
			header('Pragma: public');
			
			sleep(2);
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save('php://output');
			exit();
		}
		catch(Exception $e)
		{
			echo "Ocurrio un error al procesar el archivo de usaurios. Error! : ". $e->getMessage();
		}
	}
	
	private function setFechaIni()
	{
		BitacorasDAO::$_fecha_ini = isset($_POST['fec_ini']) ? Funciones::GetValueVar($_POST['fec_ini'], "text") : "";
	}
	
	private function setFechaFin()
	{
		BitacorasDAO::$_fecha_fin = isset($_POST['fec_fin']) ? Funciones::GetValueVar($_POST['fec_fin'], "text") : "";
	}
	
	private function setConnectInstance()
	{
		BitacorasDAO::$_connect_instance = isset($_POST['connect_instance']) ? Funciones::GetValueVar($_POST['connect_instance'], "text") : "";
	}
}
