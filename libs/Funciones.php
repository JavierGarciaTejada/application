<?php 

class Funciones
{
  
  private static $object;

  public static function imprimeJson($json)
  {
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($json);
  }


  public static function sendMailEvaluciones($subject, $body, $nameMail, $arrayAddress, $arrayAddressCp = null, $arrayAddressBc = null){

    require_once(Config::$configuration->get('phpMailer'));
    require_once(Config::$configuration->get('phpMailerSmtp'));

    $mail = new PHPMailer(true); // Passing `true` enables exceptions                             
    try {
        //Server settings
        $mail->SMTPDebug = 4; // Enable verbose debug output                                 
        $mail->isSMTP(); //Set mailer to use SMTP                                      
        //$mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
        $mail->Host = '10.192.10.9';
        $mail->SMTPAuth = true; // Enable SMTP authentication
        //$mail->Username = 'saaeapi@gmail.com';
        $mail->Username = 'SAAE@telmexomsasi.com';
        //$mail->Password = 'Sa@3_2019';
        $mail->Password = 'TmX.11500337';
        // $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
        //$mail->Port = 587; // TCP port to connect to
        $mail->CharSet = 'UTF-8';

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        //$mail->setFrom('soplepr@gmail.com', 'Jonathan Bustos'); // Sender email and name
        $mail->setFrom('SAAE@telmexomsasi.com', $nameMail);
        foreach ($arrayAddress as $key => $value) {
            $mail->addAddress($value); // Reciver email
        }

        if( !empty($arrayAddressCp) ){
            foreach ($arrayAddressCp as $keyCp => $valueCp) {
                $mail->addCC($valueCp); // Copia email
            }
        }

        if( !empty($arrayAddressBc) ){
            foreach ($arrayAddressBc as $keyBc => $valueBc) {
                $mail->addBCC($valueBc); // Copia email
            }
        }

        //For Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');  // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg'); // You can specify the file name

        //Content
        $mail->isHTML(true);// Set email format to HTML                                  
        $mail->Subject = $subject; // Subject of the email
        $mail->Body    = $body;

        $mail->send();
        return "true";
    } catch (Exception $e) {
        return 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }

  }
  
  
  public static function enviaCorreoMIME($auth, $charset, $secure, $host, $port, $user, $pass, $from, $name, $subject, $altBody, $msgBody, $attachment, $address, $cc, $type, $bcc)
  {
    //echo "Generando mail";
    require_once(Config::$configuration->get('nomad_mimemail'));
    
    $mail = new nomad_mimemail();

    $mail->set_charset( (empty($charset) ? "UTF-8" : $charset) );
    $mail->mail_from = empty($from) ? 'SICETH <frida@telmexomsasi.com>' : $from;

    if( is_array($address) )
    {
      foreach ($address as $value)
      {
        $mail->add_to($value);
      }
    }
    elseif( !empty($address) )
    {
      $mail->set_to($address);
    }

    $mail->set_subject( (emptY($subject) ? "Asunto" : $subject) );

    if( $type == "text" )
    {
      $mail->set_text($msgBody);
    }
    else 
    {
      $mail->set_html($msgBody);
    }
    
    if( is_array($cc) )
    {
      foreach( $cc as $value )
      {
        $mail->add_cc($value);
      }
    }
    elseif( !empty($cc) )
    {
      $mail->set_cc($cc);
    }
    
    if( is_array($bcc) )
    {
      foreach ( $bcc as $value )
      {
        $mail->add_bcc($value);
      }
    }
    elseif( !empty($bcc) )
    {
      $mail->set_bcc($bcc);
    }

    $mail->set_smtp_host( (empty($host) ? 'TMXMAILHUB02.ad.intranet.telmex.com' : $host) );
    $mail->set_smtp_auth( (empty($user) ? '' : $user), (empty($pass) ? 'Frid@2017' : $pass) );

    ob_start();

    if( $mail->Send() )
    {
      return true;
    }
    else
    {
			$output = ob_get_clean();
			throw new ErrException($output);
      //return false;
    }
  }//close enviacorreo
  
  public static function getPlantillaEmail($file, $array)
  {
    $config = Config::singleton();
    $path = $config->get('pathlayout');
    $plantilla = file_get_contents($path . $file);
    $css = $config->get('media') .'librerias/Bootstrap/Bootstrap3.3.7/dist/css/bootstrap.min.css';
    $plantilla = str_replace('{CSS}', $css, $plantilla);
    
    foreach ($array as $key => $value) 
    {
      $plantilla = str_replace('{'. $key .'}', $value, $plantilla);    
    }
    return $plantilla;
  }

  public static function getDate()
  {
    date_default_timezone_set("America/Mexico_City");
    return $fecha = date('Y-m-d H:i:s');
  }
  
  public static function setObject($array)
  {
    self::$object = (object) $array;
    return self::$object;
  }
  
  public static function GetValueVar($value, $type) 
  {
    $value = get_magic_quotes_gpc() ? addslashes($value) : $value;
    $val = NULL;

    switch (strtoupper($type)) {
      case "TEXT":
        $val = ($value != "") ? trim(strip_tags(strval($value))) : NULL;
      break;
      case "INT":
        $val = ($value != "") ? intval($value) : NULL;
      break;
      case "BOOL":
        $val = ($value != "") ? ( (bool) $value) : NULL;
      break;
      case "FLOAT":
        $val = ($value != "") ? trim((float) $value) : NULL;
      break;
      case "HTML":
        $val = ($value != "") ? htmlentities($value) : NULL;
      breaK;
      case "URL":
        $val = ($value != "") ? rawurlencode($value) : NULL;
      break;
      default: 
        $val = "UNDEFINED";
      break;
    }
    
    return $val;
  }
  
  public static function microTimeFloat()
  {
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
  }
  
  public static function normaliza ($cadena)
  {
    $originales  = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðòóôõöøùúûýýþÿÉ';
    $modificadas = 'aaaaaaaceeeeiiiidoooooouuuuybsaaaaaaaceeeeiiiidoooooouuuyybye';
    $cadena = utf8_decode($cadena);
    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
    $cadena = strtoupper($cadena);
    return utf8_encode($cadena);
  }

  public static function calculaDiasEntreFechas($fechaI, $fechaF){

    if( $fechaI == "0000-00-00 00:00:00" || $fechaF == "0000-00-00 00:00:00" )
      return false;

    $datetime1 = new DateTime($fechaI);
    $datetime2 = new DateTime($fechaF);
    $interval = $datetime2->diff($datetime1);
    return $interval->days;
  }

  public static function getDataPost(){

    if( empty($_POST) )
      die("Error al obtener datos POST. Post sin data."); 

    return $_POST;

  }

  public static function getDataGet(){

    if( empty($_GET) )
      die("Error al obtener datos GET. Get sin data."); 

    return $_GET;

  }

  public static function generaFiltroSql($filtros){

    if( empty($filtros) ) return "";

    $fil = array();
    foreach ($filtros as $key => $value) {
      if( ! empty($filtros[$key]) ){

        if( $value[3] == "string" )
          $item = $value[0]." ".$value[1]." '".$value[2]."'";
        else
          $item = $value[0]." ".$value[1]." ".$value[2];
        array_push($fil, $item);

      }
      
    }

    return implode(" AND ", $fil);

  }


}
