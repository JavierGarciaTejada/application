<?php 
header("Content-Type: text/html;charset=utf-8");

class InventarioEquiposDAO
{

	private static function _connectDBInstance()
	{
		Conexion::$connect = Conexion::connectionDinamic("localhost", "3306", "evaluacion", "root", "jonas", "utf8");
		Conexion::$connect->query("SET NAMES 'utf8'");
	}

	public static function executeQuery($sql){

		try 
		{
			Conexion::$connect = new Conexion();
			// self::_connectDBInstance();
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

	public static function Red(){
		$sql = "SELECT * FROM ct_inv_red WHERE sx = 0";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;
	}

	public static function Equipos(){
		$sql = "SELECT * FROM ct_inv_equ WHERE sx = 0";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;
	}

	public static function Proveedores(){
		$sql = "SELECT * FROM ct_inv_pro WHERE sx = 0";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;
	}

	public static function Tecnologia(){
		$sql = "SELECT * FROM ct_inv_tec WHERE sx = 0";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;
	}

	public static function InventarioGeneral(){

		$sql = "SELECT *,ROUND((porcentaje_eq_act * 100),2) porcentaje_act FROM inv_equipos_red";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;

	}

	public static function InventarioRed(){

		$sql = "SELECT red, equipo, FORMAT(SUM( cant_eq_inst_red ), 0) conteo FROM inv_equipos_red GROUP BY equipo ORDER BY red,equipo";
		$inventario = self::executeQuery($sql);
		foreach ($inventario as $key => $value) {
			$filas['data'][$value['red']][] = $value;
		}
		$filas['sql'] = $sql;
		return $filas;

	}

	public static function InventarioRedTotales(){

		$sql = "SELECT red, equipo, SUM( cant_eq_inst_red ) total FROM inv_equipos_red WHERE red IS NOT NULL GROUP BY red ORDER BY red,equipo";
		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;

	}

	public static function RegistraInventario($data){

		try{

			$sql = "INSERT INTO inv_equipos_red SET
			gerencia = :gerencia,
			equipo = :equipo,
			tecnologia = :tecnologia,
			proveedor = :proveedor,
			modelo_equipo = :modelo_equipo,
			gestor = :gestor,
			version_gestor = :version_gestor,
			version_eq_prod = :version_eq_prod,
			cant_eq_ver_prod = :cant_eq_ver_prod,
			ultima_ver_lib = :ultima_ver_lib,
			cant_eq_act_ult_ver = :cant_eq_act_ult_ver,
			porcentaje_eq_act = :porcentaje_eq_act,
			cant_eq_inst_red = :cant_eq_inst_red,
			prob_sol_ult_ver = :prob_sol_ult_ver,
			lineas = :lineas,
			usuarios = :usuarios,
			troncales = :troncales,
			plataforma = :plataforma,
			ciclo = :ciclo,
			estatus_por_contrato = :estatus_por_contrato,
			vigencia = :vigencia,
			estatus_del_contrato = :estatus_del_contrato";

			Conexion::$connect = new Conexion();
			Conexion::$query = $sql;
			Conexion::$prepare = Conexion::$connect->prepare(Conexion::$query);

			Conexion::$prepare->bindParam(':gerencia', $data['gerencia']);
			Conexion::$prepare->bindParam(':equipo', $data['equipo']);
			Conexion::$prepare->bindParam(':tecnologia', $data['tecnologia']);
			Conexion::$prepare->bindParam(':proveedor', $data['proveedor']);
			Conexion::$prepare->bindParam(':modelo_equipo', $data['modelo_equipo']);
			Conexion::$prepare->bindParam(':gestor', $data['gestor']);
			Conexion::$prepare->bindParam(':version_gestor', $data['version_gestor']);
			Conexion::$prepare->bindParam(':version_eq_prod', $data['version_eq_prod']);
			Conexion::$prepare->bindParam(':cant_eq_ver_prod', $data['cant_eq_ver_prod']);
			Conexion::$prepare->bindParam(':ultima_ver_lib', $data['ultima_ver_lib']);
			Conexion::$prepare->bindParam(':cant_eq_act_ult_ver', $data['cant_eq_act_ult_ver']);
			Conexion::$prepare->bindParam(':porcentaje_eq_act', $data['porcentaje_eq_act']);
			Conexion::$prepare->bindParam(':cant_eq_inst_red', $data['cant_eq_inst_red']);
			Conexion::$prepare->bindParam(':prob_sol_ult_ver', $data['prob_sol_ult_ver']);
			Conexion::$prepare->bindParam(':lineas', $data['lineas']);
			Conexion::$prepare->bindParam(':usuarios', $data['usuarios']);
			Conexion::$prepare->bindParam(':troncales', $data['troncales']);


			Conexion::$prepare->bindParam(':plataforma', $data['plataforma']);
			Conexion::$prepare->bindParam(':ciclo', $data['ciclo']);
			Conexion::$prepare->bindParam(':estatus_por_contrato', $data['estatus_por_contrato']);
			Conexion::$prepare->bindParam(':vigencia', $data['vigencia']);
			Conexion::$prepare->bindParam(':estatus_del_contrato', $data['estatus_del_contrato']);

			$result = Conexion::$prepare->execute();

			return $result;

		}catch( Exception $e ){
			die("Error al registrar. Error! : ". $e->getMessage());
		}

	}

	public static function ModificarInventario($data){

		try{

			$sql = "UPDATE inv_equipos_red SET
			gerencia = :gerencia,
			equipo = :equipo,
			tecnologia = :tecnologia,
			proveedor = :proveedor,
			modelo_equipo = :modelo_equipo,
			gestor = :gestor,
			version_gestor = :version_gestor,
			version_eq_prod = :version_eq_prod,
			cant_eq_ver_prod = :cant_eq_ver_prod,
			ultima_ver_lib = :ultima_ver_lib,
			cant_eq_act_ult_ver = :cant_eq_act_ult_ver,
			porcentaje_eq_act = :porcentaje_eq_act,
			cant_eq_inst_red = :cant_eq_inst_red,
			prob_sol_ult_ver = :prob_sol_ult_ver,
			lineas = :lineas,
			usuarios = :usuarios,
			troncales = :troncales,
			plataforma = :plataforma,
			ciclo = :ciclo,
			estatus_por_contrato = :estatus_por_contrato,
			vigencia = :vigencia,
			estatus_del_contrato = :estatus_del_contrato WHERE id = :id";

			Conexion::$connect = new Conexion();
			Conexion::$query = $sql;
			Conexion::$prepare = Conexion::$connect->prepare(Conexion::$query);

			Conexion::$prepare->bindParam(':gerencia', $data['gerencia']);
			Conexion::$prepare->bindParam(':equipo', $data['equipo']);
			Conexion::$prepare->bindParam(':tecnologia', $data['tecnologia']);
			Conexion::$prepare->bindParam(':proveedor', $data['proveedor']);
			Conexion::$prepare->bindParam(':modelo_equipo', $data['modelo_equipo']);
			Conexion::$prepare->bindParam(':gestor', $data['gestor']);
			Conexion::$prepare->bindParam(':version_gestor', $data['version_gestor']);
			Conexion::$prepare->bindParam(':version_eq_prod', $data['version_eq_prod']);
			Conexion::$prepare->bindParam(':cant_eq_ver_prod', $data['cant_eq_ver_prod']);
			Conexion::$prepare->bindParam(':ultima_ver_lib', $data['ultima_ver_lib']);
			Conexion::$prepare->bindParam(':cant_eq_act_ult_ver', $data['cant_eq_act_ult_ver']);
			Conexion::$prepare->bindParam(':porcentaje_eq_act', $data['porcentaje_eq_act']);
			Conexion::$prepare->bindParam(':cant_eq_inst_red', $data['cant_eq_inst_red']);
			Conexion::$prepare->bindParam(':prob_sol_ult_ver', $data['prob_sol_ult_ver']);
			Conexion::$prepare->bindParam(':lineas', $data['lineas']);
			Conexion::$prepare->bindParam(':usuarios', $data['usuarios']);
			Conexion::$prepare->bindParam(':troncales', $data['troncales']);

			Conexion::$prepare->bindParam(':plataforma', $data['plataforma']);
			Conexion::$prepare->bindParam(':ciclo', $data['ciclo']);
			Conexion::$prepare->bindParam(':estatus_por_contrato', $data['estatus_por_contrato']);
			Conexion::$prepare->bindParam(':vigencia', $data['vigencia']);
			Conexion::$prepare->bindParam(':estatus_del_contrato', $data['estatus_del_contrato']);

			Conexion::$prepare->bindParam(':id', $data['id']);

			$result = Conexion::$prepare->execute();
			return $result;

		}catch( Exception $e ){
			die("Error al registrar. Error! : ". $e->getMessage());
		}

	}

	
}
