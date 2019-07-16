<?php

defined("PROJECTPATH") or die("Access error");
/**
 *Clase que retorna los datos de la sesión 
 */
class DataSession
{
	
	
	public static function getSessionJson()
	{
		$var_sess = Session::datosSession();
		$var_arr = array_merge(array('session' => Session::$_session_stat), $var_sess);
		Funciones::imprimeJson($var_arr);	
	}


}