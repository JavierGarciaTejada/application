<?php

class Perfil
{

	public static function FiltroHomeRole(){

		$lr = Session::getSession('lr');
		$role = Session::getSession("role");

		$filtros = array();
		//ADMINISTRADOR NO TIENE FILTROS, VE TODO
		// if( $role === "Administrador" ) return $filtros;

		array_push($filtros, " rl = '$lr' ");
		$filtro = implode(' AND ', $filtros);

		return $filtro;
	}
	
	public static function FiltroListadoEvaluaciones($anio = ""){

		$ix 	= Session::getSession("ix");
		$role	= Session::getSession("role");
		$puesto	= Session::getSession("puesto");
		$siglas	= Session::getSession("siglas");
		$ixCli 	= Session::getSession("gcl");

		$filtros = array();
		if( ! empty($anio) )
			array_push($filtros, " a.fs like '$anio%' ");

		// if( ! empty($estatus) )
		// 	array_push($filtros, " a.et = $estatus ");

		//ADMINISTRADOR NO TIENE FILTROS, VE TODO
		if( $role === "Administrador" ) return implode(' AND ', $filtros);

		//SI ES CLIENTE
		if( ! empty($ixCli) ){
			array_push($filtros, " a.ac IN (SELECT ix FROM ad_gcl WHERE gcl = '$ixCli') ");
		}else{
			//SUBDIRECTOR LABORATORIO NO TIENE FILTROS, VE TODO
			if( $puesto === "Subdirector" )
				return $filtros;
			else 
				array_push($filtros, " a.al = (SELECT ix FROM ad_alb WHERE cl = '$siglas') ");
		}

		$filtro = implode(' AND ', $filtros);
		return $filtro;
	}


	public static function FiltroUsuarioGerencia(){

		$cl = Session::getSession("cl");
		$role = Session::getSession("role");
		$filtros = array();
		if( $role === "Administrador" ) return $filtros;

		array_push($filtros, " a.cl = '$cl' "); //SOLO DE LA GERENCIA
		// array_push($filtros, " lr IN ('919056264924931', '919056264924932') "); //SOLO SUBGERENTES E INGENIEROS

		$filtro = implode(' AND ', $filtros);
		return $filtro;
	}

	
	
}