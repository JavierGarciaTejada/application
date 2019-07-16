<?php 

class Validaciones
{
  
    public static function validaEmpty($values = array()){

        if( empty($values) ) return false;

        foreach ($values as $key => $value) {
            if( empty($value) )
                return false;
        }

    }
  

}
