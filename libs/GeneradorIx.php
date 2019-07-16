<?php
/**
 * Esta clase genera identificadores IX para las tablas de BD
 */
class GeneradorIx
{
	
	public static function xAKN($str, $prefix = null, $entropy = null){ 

	    $x88000 = $str;
	    $x88001 = $prefix;
	    $x88002 = $entropy;

	    switch (strtolower(trim($x88000))){
	        case "cnk":
	            if (empty($x88001)){
	                $x89500="".xAKN("TKN")."".xAKN("FKN")."0".self::TMXach(RAND(0,9999),4,"0");
	            }else{
	                $x89500="".$x88001['S']['TKN']."".$x88001['S']['TKA']."0".self::TMXach($x88002,4,"0");
	            }
	        break;
	        case "skn":
	            if (empty($x88001)){
	                $x89500="".xAKN("TKN")."".xAKN("FKN")."1".self::TMXach(RAND(0,9999),4,"0");
	            }else{
	                $x89500="".$x88001['S']['TKN']."".$x88001['S']['TKA']."1".self::TMXach($x88002,4,"0");
	            }
	        break;
	        case "xtkn":case "tkn":
	            $x89500=chr(rand(97,122)).date("i").date("s").rand(0,9).date("d").chr(rand(97,122)).date("H").date("y");
	            $x89500.=chr(rand(48,57)).date("m").chr(rand(97,122)).date("s").chr(rand(97,122));
	        break;
	        case "xnkn":case "nkn":
	            $x89500=RAND(1,9).self::TMXach(RAND(0,date('B')),3,RAND(0,9)).date('B').self::TMXach(RAND(0,date('B')),3,RAND(0,9));
	        break;
	        case "xdkn":case "dkn":/* Random - Año - Dia - i - s  */
	            $x89500=RAND(1,9).date('y').self::TMXach(date('z'),3,'0').date('i').date('s').self::TMXach(RAND(0,date('B')),3,RAND(0,9));
	            $x89500.=self::TMXach(RAND(0,99),2,RAND(0,9));
	        break;
	        case "xikn":case "ikn":/* 6 CHars */
	            $x89500=RAND(1,9).date('s').self::TMXach(RAND(0,date('B')),3,RAND(0,9));
	        break;
	        case "xfkn":case "fkn":/* 5 CHars */
	            $x89501=explode(" ", "s".microtime() );
	            $x89502=explode(".", $x89501[0]);
	            $x89500=rand(1,9).substr($x89502[1],2,4);
	        break;
	        default:
	            $x89500=uniqid($x88001,$x88002);
	        break;
	    }
	    return $x89500;
	}

	public static function TMXach($original, $longitud, $char){ 

		$x88000 = $original;
		$x88001 = $longitud;
		$x88002 = $char;
		$x89501 = null;

	    $x89500=$x88001-strlen($x88000);
	    for($m=0;$m<$x89500;$m++){  
	    	$x89501.=$x88002; 
	    }

	    $res = $x89501.=$x88000;

	    return $res;
	}
	
}