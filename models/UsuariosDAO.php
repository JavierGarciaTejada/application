<?php 

class UsuariosDAO
{

	public static $_id;
	public static $_dd;
	public static $_area;
	public static $_ip;
	public static $_login;
	public static $_contrasena;
	public static $_usuario;
	public static $_expediente;
	public static $_empresa;
	public static $_telefono;
	public static $_valida_ip;
	public static $_reserva;
	public static $_fecha_alta;
	public static $_fecha_pwd;
	public static $_estilo;
	public static $_reportes;
	public static $_detalles;
	public static $_perfil;
	public static $_nuevo;
	public static $_status;
	public static $_intentos;

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
	
	public static function selectUserLogin()
	{
		try 
		{
			Conexion::$connect = new Conexion();
			Conexion::$query = "SELECT a.id, a.ix, a.ps, a.cl, a.no, a.ap, a.am, a.ni, b.no role, a.lr, c.no puesto, a.pu, d.gcl, d.cl siglas
			 FROM si_usr a 
			INNER JOIN si_rol b ON a.lr = b.ix 
			INNER JOIN ad_pue c ON a.pu = c.ix 
			INNER JOIN ad_sig d ON a.cl = d.ix 
			WHERE a.ni like :login";
			Conexion::$result = Conexion::$connect->prepare(Conexion::$query);
			Conexion::$result->bindValue(":login", self::$_login . "%", PDO::PARAM_STR);
			Conexion::$result->execute();
			Conexion::$filas = Conexion::$result->fetch(PDO::FETCH_ASSOC);
			return Conexion::$filas;
		}
		catch(Exception $e)
		{
			die("Error al consultar usuario para login. Error! : ". $e->getMessage());
		}
	}

	
	public static function All($filtro = "")
	{
		$where = empty($filtro) ? "" : "WHERE $filtro";
		$sql = "SELECT a.id, a.ix, a.ps, a.cl, a.no, a.ap, a.am, CONCAT(a.ap, ' ', a.am, ' ', a.no) nombre, a.ni, b.no role, a.lr, c.no puesto, a.pu, d.gcl, d.cl siglas
		 FROM si_usr a 
		INNER JOIN si_rol b ON a.lr = b.ix 
		INNER JOIN ad_pue c ON a.pu = c.ix 
		INNER JOIN ad_sig d ON a.cl = d.ix 
		$where ORDER BY a.ap";

		$filas['data'] = self::executeQuery($sql);
		$filas['sql'] = $sql;
		return $filas;
	}

	public static function UpdatePwd($data){

		try{

			$sql = "UPDATE si_usr SET ps = :ps WHERE id = :id LIMIT 1";

			Conexion::$connect = new Conexion();

			Conexion::$query = $sql;
			Conexion::$prepare = Conexion::$connect->prepare(Conexion::$query);
			Conexion::$prepare->bindParam(':ps', $data['ps']);
			Conexion::$prepare->bindParam(':id', $data['id']);

			return Conexion::$prepare->execute();

		}catch( Exception $e ){
			die("Error al actualizar contraseña. Error! : ". $e->getMessage());
		}

	}

	public static function Save($data){

		try{

			$sql = "INSERT INTO si_usr 
			(ix, cl, fx, no, ap, am, ni, ps, lr, pu) 
			VALUES 
			(:ix, :cl, :fx, :no, :ap, :am, :ni, :ps, :lr, :pu) ";

			Conexion::$connect = new Conexion();

			Conexion::$query = $sql;
			Conexion::$prepare = Conexion::$connect->prepare(Conexion::$query);
			Conexion::$prepare->bindParam(':ix', $data['ix']);
			Conexion::$prepare->bindParam(':cl', $data['cl']);
			Conexion::$prepare->bindParam(':fx', $data['fx']);
			Conexion::$prepare->bindParam(':no', $data['no']);
			Conexion::$prepare->bindParam(':ap', $data['ap']);
			Conexion::$prepare->bindParam(':am', $data['am']);
			Conexion::$prepare->bindParam(':ni', $data['ni']);
			Conexion::$prepare->bindParam(':ps', $data['ps']);
			Conexion::$prepare->bindParam(':lr', $data['lr']);
			Conexion::$prepare->bindParam(':pu', $data['pu']);

			return Conexion::$prepare->execute();

		}catch( Exception $e ){
			die("Error al registrar usuario : ". $e->getMessage());
		}

	}

	public static function Update($data){

		try{

			$sql = "UPDATE si_usr SET cl = :cl, no = :no, ap = :ap, am = :am, ni = :ni, lr = :lr, pu = :pu WHERE id = :id";

			Conexion::$connect = new Conexion();

			Conexion::$query = $sql;
			Conexion::$prepare = Conexion::$connect->prepare(Conexion::$query);
			Conexion::$prepare->bindParam(':cl', $data['cl']);
			Conexion::$prepare->bindParam(':no', $data['no']);
			Conexion::$prepare->bindParam(':ap', $data['ap']);
			Conexion::$prepare->bindParam(':am', $data['am']);
			Conexion::$prepare->bindParam(':ni', $data['ni']);
			Conexion::$prepare->bindParam(':lr', $data['lr']);
			Conexion::$prepare->bindParam(':pu', $data['pu']);
			Conexion::$prepare->bindParam(':id', $data['id']);

			return Conexion::$prepare->execute();

		}catch( Exception $e ){
			die("Error al modificar usuario. Error! : ". $e->getMessage());
		}

	}


	
}
