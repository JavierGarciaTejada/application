<?php

defined("PROJECTPATH") or die("Access error");
class DataSession
{	
	public function getSessionJson()
	{
		$var_sess = Session::datosSession();
		$var_sess = ( is_array($var_sess) ) ? $var_sess : array();
		$var_arr = array_merge(array('session' => Session::$_session_stat), $var_sess);
		Funciones::imprimeJson($var_arr);	
	}
}