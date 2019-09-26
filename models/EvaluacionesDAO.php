<?php 
header("Content-Type: text/html;charset=utf-8");

class EvaluacionesDAO
{
	public static $_connect_instance;
	public static $_fecha_ini;
	public static $_fecha_fin;
	//NdU8S5KgrCus8jXa
	private static function _connectDBInstance()
	{
		Conexion::$connect = Conexion::connectionDinamic("localhost", "3306", "evaluaciones", "root", "jonas", "utf8");
		Conexion::$connect->query("SET NAMES 'utf8'");
	}

	public static function executeQuery($sql){

		try 
		{
			Conexion::$connect = new Conexion();
			Conexion::$query = $sql;
			Conexion::$result = Conexion::$connect->prepare(Conexion::$query);
			Conexion::$result->execute();
			return Conexion::$result->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(Exception $e)
		{
			die("Error al ejecutar consulta $sql. Error! : ". $e->getMessage());
		}

	}

	public static function BitacoraEvaluacion($data){

		try{

			$sql = "INSERT INTO bi_sol SET ix = :ix, fx = :fx, mv = :mv, id_usr = :id_usr";

			Conexion::$connect = new Conexion();

			Conexion::$query = $sql;
			Conexion::$prepare = Conexion::$connect->prepare(Conexion::$query);

			$fecha = date('Y-m-d H:i:s');
			$usuario = Session::getSession("id");

			Conexion::$prepare->bindParam(':ix', $data['ix']);
			Conexion::$prepare->bindParam(':fx', $fecha);
			Conexion::$prepare->bindParam(':mv', $data['mv']);
			Conexion::$prepare->bindParam(':id_usr', $usuario);

			$result = Conexion::$prepare->execute();
			Log::Save($result, 'BITACORA', $sql, 'evaluaciones/', $data);
			return $result;

		}catch( Exception $e ){
			Log::Save("error", 'BITACORA', $e->getMessage(), 'evaluaciones/', $data);
			die("Error al editar evaluación. Error! : ". $e->getMessage());
		}

	}

	public static function EvaluacionesEtapaCount($etapa){
		$sql = "SELECT COUNT(id) total FROM so_sol WHERE et = $etapa";
		$total['data'] = self::executeQuery($sql);
		$total['sql'] = $sql;
		return $total;
	}

	public static function EvaluacionesPerfilWhereCount($where = "", $ig)
	{
		try{

			$sql = " SELECT COUNT(0) AS total FROM so_sol WHERE ig = '". $ig ."' ". $where;
			Conexion::$query = $sql;
			Conexion::$connect = new Conexion();

			Conexion::$result = Conexion::$connect->prepare(Conexion::$query);
			Conexion::$result->execute();
			Conexion::$filas = Conexion::$result->fetchColumn();
			return Conexion::$filas;

		}catch( Exception $e ){
			die("Error al obtener el total filtrado evaluaciones. Error! : ". $e->getMessage());
		}

	}

	public static function EvaluacionesPerfilCount($ig = "")
	{
		try{

			$sql = " SELECT COUNT(0) AS total FROM so_sol  WHERE ig = '". $ig ."'";
			Conexion::$query = $sql;
			Conexion::$connect = new Conexion();

			Conexion::$result = Conexion::$connect->prepare(Conexion::$query);
			Conexion::$result->execute();
			Conexion::$filas = Conexion::$result->fetchColumn();
			return Conexion::$filas;

		}catch( Exception $e ){
			die("Error al obtener el total evaluaciones. Error! : ". $e->getMessage());
		}
	}
	
	public static function EvaluacionesPerfil($filtro = "", $order = "")
	{

		$hoy = date('Y-m-d');
		$where = empty($filtro) ? "" : "WHERE $filtro";
		$orderby = empty($order) ? "ORDER BY a.id DESC" : $order;

		$sql = "SELECT 
		a.*,
		if(a.fs = '0000-00-00 00:00:00', '', a.fs) f_sol,
		if(a.fo = '0000-00-00 00:00:00', '', a.fo) f_com,
		if(a.fl = '0000-00-00 00:00:00', '', a.fl) f_lib,
		if(a.fc = '0000-00-00 00:00:00', '', a.fc) f_can,
		b.cl s_cliente,
		b.no g_cliente,
		c.no prioridad,
		c.cl color_prioridad,
		d.cl s_lab,
		d.no a_laboratorio,
		e.no proveedor,
		f.cl color_etapa,
		f.no etapa,
		f.id id_etapa,
		g.no tipo_solicitud,
		CONVERT(g.no USING utf8) solicitud,
		h.no resultado,
		h.cl color_resultado,
		h.ds icon_resultado,
		i.cl siglas_sg,
		CONCAT(i.no,' ',i.ap,' ',i.am ) subgerente,
		(SELECT CONCAT(no,' ',ap,' ',am ) FROM si_usr WHERE ix = a.ig) ingeniero,
		j.cl color_nuevo,
		j.no nuevo,
		k.no tec_equipo,
		l.no proyecto_asociado,
		m.no mercado,
		DATEDIFF(NOW(),fs) dias_t,
		n.no producto,
		(SELECT no FROM ad_prd_cat WHERE id = a.pe) prod_ex,
		(SELECT no FROM ad_prd_cat WHERE id = a.spe) subprod_ex
		FROM so_sol a 
		LEFT JOIN ad_gcl b ON a.ac = b.ix 
		LEFT JOIN ad_pri c ON a.pr = c.ix 
		LEFT JOIN ad_alb d ON a.al = d.ix 
		LEFT JOIN ad_pro e ON a.pv = e.ix 
		LEFT JOIN ad_eta f ON a.et = f.ix 
		LEFT JOIN ad_tps g ON a.ts = g.ix 
		LEFT JOIN ad_res h ON a.re = h.ix 
		LEFT JOIN si_usr i ON a.sg = i.ix 
		LEFT JOIN ad_nue j ON a.nu = j.ix 
		LEFT JOIN ad_teq k ON a.te = k.ix 
		LEFT JOIN ad_pra l ON a.pa = l.ix 
		LEFT JOIN ad_mer m ON a.me = m.ix 
		LEFT JOIN ad_prd n ON a.pd = n.id $where $orderby";

		$filas['data'] = self::executeQuery($sql); //Conexion::$result->fetchAll(PDO::FETCH_ASSOC);
		$filas['sql'] = $sql;
		return $filas;

	}

	public static function SiglasCliente(){

		$sql = "SELECT id, ix, cl, no, gcl FROM ad_gcl WHERE gcl <> ''";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;

	}

	public static function Proveedores(){

		$sql = "SELECT id, ix, no FROM ad_pro";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;

	}

	public static function TipoEvaluacion(){

		$sql = "SELECT ix, no FROM ad_tps WHERE sx = 0 ORDER BY no";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;

	}

	public static function Prioridad(){

		$sql = "SELECT ix, no FROM ad_pri WHERE sx = 0 ORDER BY no";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;

	}

	public static function RegistrarEvaluacion($data){

		try{

			$sql = "INSERT INTO so_sol (ix, fx, el, cl, no, ts, al, ac, pr, pv, fo, fs, ux, rx, et, sx, re, sg, ig, nu, te, pa, me, gr) VALUES (:ix, :fx, :el, :cl, :no, :ts, :al, :ac, :pr, :pv, :fo, :fs, :ux, :rx, :et, :sx, :re, :sg, :ig, :nu, :te, :pa, :me, :gr) ";
			Conexion::$connect = new Conexion();

			Conexion::$query = $sql;
			Conexion::$prepare = Conexion::$connect->prepare(Conexion::$query);
			Conexion::$prepare->bindParam(':ix', $data['ix']);
			Conexion::$prepare->bindParam(':fx', $data['fx']);

			Conexion::$prepare->bindParam(':el', $data['el']);
			Conexion::$prepare->bindParam(':cl', $data['cl']);
			Conexion::$prepare->bindParam(':no', $data['no']);
			Conexion::$prepare->bindParam(':ts', $data['ts']);
			Conexion::$prepare->bindParam(':al', $data['al']);
			Conexion::$prepare->bindParam(':ac', $data['ac']);
			Conexion::$prepare->bindParam(':pr', $data['pr']);
			Conexion::$prepare->bindParam(':pv', $data['pv']);
			Conexion::$prepare->bindParam(':fo', $data['fo']);
			Conexion::$prepare->bindParam(':fs', $data['fs']);

			Conexion::$prepare->bindParam(':ux', $data['ux']);
			Conexion::$prepare->bindParam(':rx', $data['rx']);
			Conexion::$prepare->bindParam(':et', $data['et']);
			Conexion::$prepare->bindParam(':sx', $data['sx']);
			Conexion::$prepare->bindParam(':re', $data['re']);
			Conexion::$prepare->bindParam(':sg', $data['sg']);
			Conexion::$prepare->bindParam(':ig', $data['ig']);
			Conexion::$prepare->bindParam(':nu', $data['nu']);
			Conexion::$prepare->bindParam(':te', $data['te']);
			Conexion::$prepare->bindParam(':pa', $data['pa']);
			Conexion::$prepare->bindParam(':me', $data['me']);
			Conexion::$prepare->bindParam(':gr', $data['gr']);

			$result = Conexion::$prepare->execute();
			self::BitacoraEvaluacion(array('mv' => 'registro', 'ix' => $data['ix']));
			Log::Save($result, 'REGISTRO', $sql, 'evaluaciones/', $data);
			return $result;

		}catch( Exception $e ){
			Log::Save("error", 'REGISTRO', $e->getMessage(), 'evaluaciones/', $data);
			die("Error al registrar evaluación. Error! : ". $e->getMessage());
		}

	}

	public static function EditarEvaluacion($data){

		try{

			$sql = "UPDATE so_sol SET al = :al, no = :no, ac = :ac, pv = :pv, ts = :ts, pr = :pr, fs = :fs WHERE ix = :ix";

			Conexion::$connect = new Conexion();

			Conexion::$query = $sql;
			Conexion::$prepare = Conexion::$connect->prepare(Conexion::$query);
			

			Conexion::$prepare->bindParam(':al', $data['al']);
			Conexion::$prepare->bindParam(':no', $data['no']);
			Conexion::$prepare->bindParam(':ac', $data['ac']);
			Conexion::$prepare->bindParam(':pv', $data['pv']);
			Conexion::$prepare->bindParam(':ts', $data['ts']);
			Conexion::$prepare->bindParam(':pr', $data['pr']);
			Conexion::$prepare->bindParam(':fs', $data['fs']);
			Conexion::$prepare->bindParam(':ix', $data['ix']);

			$result = Conexion::$prepare->execute();
			self::BitacoraEvaluacion(array('mv' => 'modificacion', 'ix' => $data['ix']));
			Log::Save($result, 'MODIFICACION', $sql, 'evaluaciones/', $data);
			return $result;

		}catch( Exception $e ){
			Log::Save("error", 'MODIFICACION', $e->getMessage(), 'evaluaciones/', $data);
			die("Error al editar evaluación. Error! : ". $e->getMessage());
		}

	}

	public static function EditarEvaluacionProceso($data){

		try{

			$sql = "UPDATE so_sol 
			SET al = :al, no = :no, ac = :ac, pv = :pv, ts = :ts, pr = :pr, fs = :fs, ig = :ig, me = :me, nu = :nu, pa = :pa, sg = :sg, te = :te, dl = :dl, ft = :ft, pd = :pd, ob = :ob, pe = :pe, spe = :spe WHERE ix = :ix";

			Conexion::$connect = new Conexion();
			Conexion::$query = $sql;
			Conexion::$prepare = Conexion::$connect->prepare(Conexion::$query);
			

			Conexion::$prepare->bindParam(':al', $data['al']);
			Conexion::$prepare->bindParam(':no', $data['no']);
			Conexion::$prepare->bindParam(':ac', $data['ac']);
			Conexion::$prepare->bindParam(':pv', $data['pv']);
			Conexion::$prepare->bindParam(':ts', $data['ts']);
			Conexion::$prepare->bindParam(':pr', $data['pr']);
			Conexion::$prepare->bindParam(':fs', $data['fs']);
			Conexion::$prepare->bindParam(':ix', $data['ix']);

			Conexion::$prepare->bindParam(':sg', $data['sg']);
			Conexion::$prepare->bindParam(':ig', $data['ig']);
			Conexion::$prepare->bindParam(':nu', $data['nu']);
			Conexion::$prepare->bindParam(':te', $data['te']);
			Conexion::$prepare->bindParam(':pa', $data['pa']);
			Conexion::$prepare->bindParam(':me', $data['me']);

			Conexion::$prepare->bindParam(':dl', $data['dl']);
			Conexion::$prepare->bindParam(':ft', $data['ft']);

			Conexion::$prepare->bindParam(':pd', $data['pd']);
			Conexion::$prepare->bindParam(':ob', $data['ob']);

			Conexion::$prepare->bindParam(':pe', $data['pe']);
			Conexion::$prepare->bindParam(':spe', $data['spe']);

			$result = Conexion::$prepare->execute();
			self::BitacoraEvaluacion(array('mv' => 'modificacion_proceso', 'ix' => $data['ix']));
			Log::Save($result, 'MODIFICACION_PROCESO', $sql, 'evaluaciones/', $data);
			return $result;

		}catch( Exception $e ){
			Log::Save("error", 'MODIFICACION_PROCESO', $e->getMessage(), 'evaluaciones/', $data);
			die("Error al editar evaluación en proceso. Error! : ". $e->getMessage());
		}

	}


	public static function EditarEvaluacionLiberada($data){

		try{

			$sql = "UPDATE so_sol SET pa = :pa, me = :me, ts = :ts, te = :te WHERE ix = :ix";

			Conexion::$connect = new Conexion();
			Conexion::$query = $sql;
			Conexion::$prepare = Conexion::$connect->prepare(Conexion::$query);
			

			Conexion::$prepare->bindParam(':pa', $data['pa']);
			Conexion::$prepare->bindParam(':me', $data['me']);
			Conexion::$prepare->bindParam(':ts', $data['ts']);
			Conexion::$prepare->bindParam(':te', $data['te']);

			Conexion::$prepare->bindParam(':ix', $data['ix']);

			$result = Conexion::$prepare->execute();
			self::BitacoraEvaluacion(array('mv' => 'modificacion_liberada', 'ix' => $data['ix']));
			Log::Save($result, 'MODIFICACION_LIBERADA', $sql, 'evaluaciones/', $data);
			return $result;

		}catch( Exception $e ){
			Log::Save("error", 'MODIFICACION_LIBERADA', $e->getMessage(), 'evaluaciones/', $data);
			die("Error al editar evaluación LIBERADA. Error! : ". $e->getMessage());
		}

	}


	public static function AceptarEvaluacion($data){

		try{

			$sql = "UPDATE so_sol SET pv = :pv, ts = :ts, pr = :pr, fo = :fo, sg = :sg, ig = :ig, te = :te, nu = :nu, pa = :pa, me = :me, et = :et, fa = :fa, ft = :ft, dl = :dl, pe = :pe, spe = :spe WHERE id = :id";
			Conexion::$connect = new Conexion();

			Conexion::$query = $sql;
			Conexion::$prepare = Conexion::$connect->prepare(Conexion::$query);

			Conexion::$prepare->bindParam(':pv', $data['pv']);
			Conexion::$prepare->bindParam(':ts', $data['ts']);
			Conexion::$prepare->bindParam(':pr', $data['pr']);
			Conexion::$prepare->bindParam(':fo', $data['fo']);
			Conexion::$prepare->bindParam(':sg', $data['sg']);
			Conexion::$prepare->bindParam(':ig', $data['ig']);
			Conexion::$prepare->bindParam(':te', $data['te']);
			Conexion::$prepare->bindParam(':nu', $data['nu']);
			Conexion::$prepare->bindParam(':pa', $data['pa']);
			Conexion::$prepare->bindParam(':me', $data['me']);
			Conexion::$prepare->bindParam(':ft', $data['ft']);
			Conexion::$prepare->bindParam(':dl', $data['dl']);
			Conexion::$prepare->bindParam(':pe', $data['pe']);
			Conexion::$prepare->bindParam(':spe', $data['spe']);
			Conexion::$prepare->bindParam(':et', $data['et']);
			Conexion::$prepare->bindParam(':fa', $data['fa']);
			Conexion::$prepare->bindParam(':id', $data['id']);

			$result = Conexion::$prepare->execute();
			self::BitacoraEvaluacion(array('mv' => 'aceptacion', 'ix' => $data['ix']));
			Log::Save($result, 'ACEPTACION', $sql, 'evaluaciones/', $data);
			return $result;

		}catch( Exception $e ){
			Log::Save("error", 'ACEPTACION', $e->getMessage(), 'evaluaciones/', $data);
			die("Error al aceptar evaluación. Error! : ". $e->getMessage());
		}

	}

	public static function RechazarEvaluacion($data){

		try{

			$sql = "UPDATE so_sol SET mr = :mr, et = :et, fr = :fr, fa = :fa WHERE id = :id";
			Conexion::$connect = new Conexion();

			Conexion::$query = $sql;
			Conexion::$prepare = Conexion::$connect->prepare(Conexion::$query);

			Conexion::$prepare->bindParam(':mr', $data['mr']);
			Conexion::$prepare->bindParam(':et', $data['et']);
			Conexion::$prepare->bindParam(':fr', $data['fr']);
			Conexion::$prepare->bindParam(':fa', $data['fa']);
			Conexion::$prepare->bindParam(':id', $data['id']);

			$result = Conexion::$prepare->execute();
			self::BitacoraEvaluacion(array('mv' => 'rechazo', 'ix' => $data['ix']));
			Log::Save($result, 'RECHAZO', $sql, 'evaluaciones/', $data);
			return $result;

		}catch( Exception $e ){
			Log::Save("error", 'RECHAZO', $e->getMessage(), 'evaluaciones/', $data);
			die("Error al rechazar evaluación. Error! : ". $e->getMessage());
		}

	}

	public static function ReenviarEvaluacion($data){

		try{

			$sql = "UPDATE so_sol SET et = :et, fa = :fa, fs = :fs, fo = :fo WHERE id = :id";
			Conexion::$connect = new Conexion();

			Conexion::$query = $sql;
			Conexion::$prepare = Conexion::$connect->prepare(Conexion::$query);

			Conexion::$prepare->bindParam(':et', $data['et']);
			Conexion::$prepare->bindParam(':fa', $data['fa']);
			Conexion::$prepare->bindParam(':fs', $data['fs']);
			Conexion::$prepare->bindParam(':fo', $data['fo']);
			Conexion::$prepare->bindParam(':id', $data['id']);

			$result = Conexion::$prepare->execute();
			self::BitacoraEvaluacion(array('mv' => 'reenvio', 'ix' => $data['ix']));
			Log::Save($result, 'REENVIO', $sql, 'evaluaciones/', $data);
			return $result;

		}catch( Exception $e ){
			Log::Save("error", 'REENVIO', $e->getMessage(), 'evaluaciones/', $data);
			die("Error al reenviar evaluación. Error! : ". $e->getMessage());
		}

	}


	public static function LiberarEvaluacion($data){

		try{

			$sql = "UPDATE so_sol SET et = :et, fa = :fa, rl = :rl, re = :re, fl = :fl WHERE id = :id";
			Conexion::$connect = new Conexion();

			Conexion::$query = $sql;
			Conexion::$prepare = Conexion::$connect->prepare(Conexion::$query);

			Conexion::$prepare->bindParam(':et', $data['et']);
			Conexion::$prepare->bindParam(':fa', $data['fa']);
			Conexion::$prepare->bindParam(':rl', $data['rl']);
			Conexion::$prepare->bindParam(':re', $data['re']);
			Conexion::$prepare->bindParam(':fl', $data['fl']);
			Conexion::$prepare->bindParam(':id', $data['id']);

			$result = Conexion::$prepare->execute();
			self::BitacoraEvaluacion(array('mv' => 'liberacion', 'ix' => $data['ix']));
			Log::Save($result, 'LIBERACION', $sql, 'evaluaciones/', $data);
			return $result;

		}catch( Exception $e ){
			Log::Save("error", 'LIBERACION', $e->getMessage(), 'evaluaciones/', $data);
			die("Error al liberar evaluación. Error! : ". $e->getMessage());
		}

	}

	public static function CancelarEvaluacion($data){

		try{

			$sql = "UPDATE so_sol SET et = :et, fa = :fa, rl = :rl, fc = :fc, mc = :mc WHERE id = :id";
			Conexion::$connect = new Conexion();

			Conexion::$query = $sql;
			Conexion::$prepare = Conexion::$connect->prepare(Conexion::$query);

			Conexion::$prepare->bindParam(':et', $data['et']);
			Conexion::$prepare->bindParam(':fa', $data['fa']);
			Conexion::$prepare->bindParam(':rl', $data['rl']);
			Conexion::$prepare->bindParam(':fc', $data['fc']);
			Conexion::$prepare->bindParam(':mc', $data['mc']);
			Conexion::$prepare->bindParam(':id', $data['id']);

			$result = Conexion::$prepare->execute();
			self::BitacoraEvaluacion(array('mv' => 'cancelacion', 'ix' => $data['ix']));
			Log::Save($result, 'CANCELACION', $sql, 'evaluaciones/', $data);
			return $result;

		}catch( Exception $e ){
			Log::Save("error", 'CANCELACION', $e->getMessage(), 'evaluaciones/', $data);
			die("Error al cancelar evaluación. Error! : ". $e->getMessage());
		}

	}


	public static function Anexos($data){

		try{

			$sql = "SELECT * FROM si_doc WHERE rx = :id";
			Conexion::$connect = new Conexion();
			Conexion::$query = $sql;
			Conexion::$prepare = Conexion::$connect->prepare(Conexion::$query);
			Conexion::$prepare->bindParam(':id', $data['id']);

			Conexion::$prepare->execute();
			return Conexion::$prepare->fetchAll(PDO::FETCH_ASSOC);

		}catch( Exception $e ){
			die("Error al obtener anexos de evaluación. Error! : ". $e->getMessage());
		}

	}


	public static function RegistrarAnexo($data){

		try{

			$sql = "INSERT INTO si_doc (fx, rx, no, no_generado, tp) VALUES (:fx, :rx, :no, :no_generado, :tp) ";
			Conexion::$connect = new Conexion();

			Conexion::$query = $sql;
			Conexion::$prepare = Conexion::$connect->prepare(Conexion::$query);
			Conexion::$prepare->bindParam(':fx', $data['fx']);
			Conexion::$prepare->bindParam(':rx', $data['rx']);
			Conexion::$prepare->bindParam(':no', $data['no']);
			Conexion::$prepare->bindParam(':no_generado', $data['no_generado']);
			Conexion::$prepare->bindParam(':tp', $data['tp']);

			$result = Conexion::$prepare->execute();
			return $result;

		}catch( Exception $e ){
			die("Error al registrar anexo. Error! : ". $e->getMessage());
		}

	}


	
}
