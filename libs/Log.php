<?php


class Log
{
	
	public static function Save($tipo, $metodo, $descripcion, $directorio, $datos = "" ){

		$file = Config::$configuration->get('log_path') . $directorio . date('Y-m') . '.log';

		$message = new stdClass();
		$message->date = date('Y-m-d H:i:s');
		$message->nick = Session::getSession("ni");
		$message->type = $tipo;
		$message->meth = $metodo;
		$message->desc = $descripcion;
		$message->data = $datos;

		$json = json_encode($message, JSON_PRETTY_PRINT);

		error_log( $json . ",", 3, $file);
		error_log( "\xA", 3, $file);
 
	}

}