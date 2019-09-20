<?php 

class FuncionesEvaluacion
{
  
  private static $object;

  public static $ids = array();
  public static $general = array();


  public static function RemitenteGerencia($gerenciaIX){

    $direcciones = array(
        '119056262939066' => array(
            'to' => array('smuratal@telmex.com'),
            'cp' => array('jvgomez@telmexomsasi.com','rmerlos@telmex.com', 'ovguerre@telmex.com', 'rjimener@telmex.com', 'uireyes@telmex.com','bpruiz@telmexomsasi.com','momercad@telmexomsasi.com'),
            'bc' => array('fgtejada@telmex.com', 'ovgarcia@telmexomsasi.com')
        ),
        '119056262939067' => array(
            // 'to' => array('fgtejada@telmex.com'),
            // 'cp' => array(),
            // 'bc' => array()
            'to' => array('rdvences@telmexomsasi.com'),
            'cp' => array('jcmagana@serviciostmx.com', 'cablanco@telmexomsasi.com', 'ovgarcia@telmexomsasi.com', 'zvazquep@telmex.com','bpruiz@telmexomsasi.com','momercad@telmexomsasi.com'),
            'bc' => array('fgtejada@telmex.com')
        ),
        '119056262939068' => array(
            'to' => array('bpruiz@telmexomsasi.com'),
            'cp' => array('vfflores@telmex.com', 'llvalenc@telmexomsasi.com', 'ccampa@telmex.com', 'ealcantz@telmexomsasi.com', 'avgalvan@telmex.com','bpruiz@telmexomsasi.com','momercad@telmexomsasi.com'),
            'bc' => array('fgtejada@telmex.com', 'ovgarcia@telmexomsasi.com')
        ),
        '119056262939069' => array(
            'to' => array('juanmv@telmex.com'),
            'cp' => array('dcarranz@telmex.com','jmdelaro@telmex.com','jglarios@telmex.com','mpgodine@telmex.com','bpruiz@telmexomsasi.com','momercad@telmexomsasi.com'),
            'bc' => array('fgtejada@telmex.com', 'ovgarcia@telmexomsasi.com') 
        ),
        '119056262939070' => array(
            'to' => array('CHERNANJ@telmexomsasi.com'),
            'cp' => array('bpruiz@telmexomsasi.com','momercad@telmexomsasi.com'),
            'bc' => array('fgtejada@telmex.com', 'ovgarcia@telmexomsasi.com')
        ),
        '119056262939071' => array(
            'to' => array('msilva@telmexomsasi.com'),
            'cp' => array('ehpale@telmex.com', 'apmancil@telmexomsasi.com', 'cmendezl@telmex.com','momercad@telmexomsasi.com','bpruiz@telmexomsasi.com'),
            'bc' => array('fgtejada@telmex.com', 'ovgarcia@telmexomsasi.com')
        ),
        '119056262939072' => array(
            'to' => array('bpruiz@telmexomsasi.com'),
            'cp' => array('vfflores@telmex.com', 'llvalenc@telmexomsasi.com', 'ccampa@telmex.com', 'ealcantz@telmexomsasi.com', 'avgalvan@telmex.com','momercad@telmexomsasi.com','bpruiz@telmexomsasi.com'),
            'bc' => array('fgtejada@telmex.com', 'ovgarcia@telmexomsasi.com')
        )// ,'jczenten@telmexomsasi.com'
    );

    $d = ( array_key_exists($gerenciaIX, $direcciones) ) ? $direcciones[$gerenciaIX] : array();
    return $d;

  }

  public static function asignacionFechas($evaluaciones){

    $etapasValidas = array('Liberada', 'Cancelada');
    foreach ($evaluaciones['data'] as $key => $item) {

      //$evaluaciones['data'][$key]['desc_eval'] = utf8_decode( $item['no'] );

      //FECHAS DIAS TRANSCURRIDOS
      $fechaI = $item['fs'];
      $fechaF = $item['fl'];

      //FECHAS DILACION
      $fechaDilI = $item['fo'];
      $fechaDilF = $item['fl'];
      $liberada = true;

      //SI NO ES LIBERADA O CANCELADA LAS FECHAS CAMBIAN
      if( !in_array($item['etapa'], $etapasValidas) ){

        $hoy = date('Y-m-d');
        $fechaF = $hoy;
        $fechaDilI = $hoy;
        $fechaDilF = $item['fo'];
        $liberada = false;

      }

      //$diasDiferencia = Funciones::calculaDiasEntreFechas($fechaI, $fechaF);
      $diasDiferencia = self::calculaDiferenciaFechas($fechaI, $fechaF);
      $periodo = self::calculaPeriodo($diasDiferencia);

      //$diasDifDilacion = Funciones::calculaDiasEntreFechas($fechaDilI, $fechaDilF);
      $diasDifDilacion = self::calculaDiferenciaFechas($fechaDilI, $fechaDilF);
      $dilacion = self::calculaDilacion( $diasDifDilacion, $liberada );

      //$evaluaciones['data'][$key]['no'] = utf8_decode( $item['no'] );
      $evaluaciones['data'][$key]['dif'] = $diasDiferencia;
      $evaluaciones['data'][$key]['periodo'] = $periodo['rango'];
      $evaluaciones['data'][$key]['color_periodo'] = $periodo['color'];

      $evaluaciones['data'][$key]['dilacion'] = abs($dilacion['dias']);
      $evaluaciones['data'][$key]['dilacion_desc'] = $dilacion['no'];
      $evaluaciones['data'][$key]['color_dilacion'] = $dilacion['co'];

    }

    return $evaluaciones;
      
  }

  public static function IntervaloProductoNuevo($evaluaciones){

    $nuevo = array();
    $nuevo['1-10'] = 0;
    $nuevo['11-20'] = 0;
    $nuevo['21-30'] = 0;
    $nuevo['30'] = 0;

    self::$general['nuevo-1-10'] = array();
    self::$general['nuevo-11-20'] = array();
    self::$general['nuevo-21-30'] = array();
    self::$general['nuevo-30'] = array();
    self::$general['nuevo-total'] = array();

    foreach ($evaluaciones['data'] as $key => $item) {

      if( (int)$item['pd'] == 1 ){

        if( !in_array($item['id'], self::$ids) ){

          self::$ids[] = $item['id'];
          self::$general['nuevo-total'][] = $item['id'];
          if( (int)$item['dif'] <= 10 ){
            $nuevo['1-10'] += 1;
            self::$general['nuevo-1-10'][] = $item['id'];
            // self::$general['total-1'][] = array( $item['id'], 30);
          }else if( (int)$item['dif'] <= 20 ){
            $nuevo['11-20'] += 1;
            self::$general['nuevo-11-20'][] = $item['id'];
            // self::$general['total-2'][] = array( $item['id'], 30);
          }else if( (int)$item['dif'] <= 30 ){
            $nuevo['21-30'] += 1;
            self::$general['nuevo-21-30'][] = $item['id'];
            // self::$general['total-3'][] = array( $item['id'], 30);
          }else if( (int)$item['dif'] > 30 ){
            $nuevo['30'] += 1;
            self::$general['nuevo-30'][] = $item['id'];
            // self::$general['total-4'][] = array( $item['id'], 30);
          }

        }

        

      }

    }

    return $nuevo;

  }

  public static function IntervaloEspeciales($evaluaciones){

    $especial = array();
    $especial['especiales-1-5'] = 0;

    self::$general['especiales-1-5'] = array();
    foreach ($evaluaciones['data'] as $key => $item) {
      if( $item['especial'] == '1' ){
        if( !in_array($item['id'], self::$ids) ){
          self::$ids[] = $item['id'];
          self::$general['especiales-1-5'][] = $item['id'];
          $especial['especiales-1-5'] += 1;
        }
      }
    }

    return $especial;

  }


  public static function IntervaloDocumentosFoas($evaluaciones){

    $docs = array();
    $docs['1-5'] = 0;
    $docs['6-10'] = 0;
    $docs['11-15'] = 0;
    $docs['15'] = 0;
    self::$general['docs-1-5'] = array();
    self::$general['docs-6-10'] = array();
    self::$general['docs-11-15'] = array();
    self::$general['docs-15'] = array();
    self::$general['docs-total'] = array();
    // self::$general['total'] = array();

    foreach ($evaluaciones['data'] as $key => $item) {

      if( $item['ts'] == '719056262954145' || $item['ts'] == '719056262954162' ){

        if( !in_array($item['id'], self::$ids) ){

          self::$ids[] = $item['id'];
          self::$general['docs-total'][] = $item['id'];

          if( (int)$item['dif'] <= 5 ){
            $docs['1-5'] += 1;
            self::$general['docs-1-5'][] = $item['id'];
            self::$general['total-1'][] = array( $item['id'], 15);
          }else if( (int)$item['dif'] <= 10 ){
            $docs['6-10'] += 1;
            self::$general['docs-6-10'][] = $item['id'];
            self::$general['total-2'][] = array( $item['id'], 15);
          }else if( (int)$item['dif'] <= 15 ){
            $docs['11-15'] += 1;
            self::$general['docs-11-15'][] = $item['id'];
            self::$general['total-3'][] = array( $item['id'], 15);
          }else if( (int)$item['dif'] > 15 ){
            $docs['15'] += 1;
            self::$general['docs-15'][] = $item['id'];
            self::$general['total-4'][] = array( $item['id'], 15);
          }

        }

        

      }

    }

    return $docs;

  }


  public static function IntervaloFuncionalidadConcepto($evaluaciones){

    $funcionalidad = array();
    $funcionalidad['1-10'] = 0;
    $funcionalidad['11-20'] = 0;
    $funcionalidad['21-30'] = 0;
    $funcionalidad['30'] = 0;

    self::$general['func-1-10'] = array();
    self::$general['func-11-20'] = array();
    self::$general['func-21-30'] = array();
    self::$general['func-30'] = array();
    self::$general['func-total'] = array();

    foreach ($evaluaciones['data'] as $key => $item) {

      if( $item['ts'] == '719056262954156' || $item['ts'] == '719056262954161' ){

        if( !in_array($item['id'], self::$ids) ){

          self::$ids[] = $item['id'];
          self::$general['func-total'][] = $item['id'];
          if( (int)$item['dif'] <= 10 ){
            $funcionalidad['1-10'] += 1;
            self::$general['func-1-10'][] = $item['id'];
            self::$general['total-1'][] = array( $item['id'], 30);
          }else if( (int)$item['dif'] <= 20 ){
            $funcionalidad['11-20'] += 1;
            self::$general['func-11-20'][] = $item['id'];
            self::$general['total-2'][] = array( $item['id'], 30);
          }else if( (int)$item['dif'] <= 30 ){
            $funcionalidad['21-30'] += 1;
            self::$general['func-21-30'][] = $item['id'];
            self::$general['total-3'][] = array( $item['id'], 30);
          }else if( (int)$item['dif'] > 30 ){
            $funcionalidad['30'] += 1;
            self::$general['func-30'][] = $item['id'];
            self::$general['total-4'][] = array( $item['id'], 30);
          }

        }

        

      }

    }

    return $funcionalidad;

  }


  public static function IntervaloEspecificacion($evaluaciones){

    $espec = array();
    $espec['nue']['1-15'] = 0;
    $espec['nue']['16-30'] = 0;
    $espec['nue']['31-45'] = 0;
    $espec['nue']['45'] = 0;

    $espec['act']['1-10'] = 0;
    $espec['act']['11-20'] = 0;
    $espec['act']['21-30'] = 0;
    $espec['act']['30'] = 0;


    self::$general['espec-nue-1-15'] = array();
    self::$general['espec-nue-16-30'] = array();
    self::$general['espec-nue-31-45'] = array();
    self::$general['espec-nue-45'] = array();
    self::$general['espec-nue-total'] = array();

    self::$general['espec-act-1-10'] = array();
    self::$general['espec-act-11-20'] = array();
    self::$general['espec-act-21-30'] = array();
    self::$general['espec-act-30'] = array();
    self::$general['espec-act-total'] = array();


    foreach ($evaluaciones['data'] as $key => $item) {

      if( $item['ts'] == '719056262954164' ){ 

        if( !in_array($item['id'], self::$ids) ){
         
          self::$ids[] = $item['id'];
          if( $item['nu'] == '219056265840691' ){

            self::$general['espec-nue-total'][] = $item['id'];

            if( (int)$item['dif'] <= 15 ){
              $espec['nue']['1-15'] += 1;
               self::$general['espec-nue-1-15'][] = $item['id'];
               self::$general['total-1'][] = array( $item['id'], 45);
            }else if( (int)$item['dif'] <= 30 ){
              $espec['nue']['16-30'] += 1;
               self::$general['espec-nue-16-30'][] = $item['id'];
               self::$general['total-2'][] = array( $item['id'], 45);
            }else if( (int)$item['dif'] <= 45 ){
              $espec['nue']['31-45'] += 1;
               self::$general['espec-nue-31-45'][] = $item['id'];
               self::$general['total-3'][] = array( $item['id'], 45);
            }else if( (int)$item['dif'] > 45 ){
              $espec['nue']['45'] += 1;
               self::$general['espec-nue-45'][] = $item['id'];
               self::$general['total-4'][] = array( $item['id'], 45);
            }

          }else{

            self::$general['espec-act-total'][] = $item['id'];

            if( (int)$item['dif'] <= 10 ){
              $espec['act']['1-10'] += 1;
               self::$general['espec-act-1-10'][] = $item['id'];
               self::$general['total-1'][] = array( $item['id'], 30);
            }else if( (int)$item['dif'] <= 20 ){
              $espec['act']['11-20'] += 1;
               self::$general['espec-act-11-20'][] = $item['id'];
               self::$general['total-2'][] = array( $item['id'], 30);
            }else if( (int)$item['dif'] <= 30 ){
              $espec['act']['21-30'] += 1;
               self::$general['espec-act-21-30'][] = $item['id'];
               self::$general['total-3'][] = array( $item['id'], 30);
            }else if( (int)$item['dif'] > 30 ){
              $espec['act']['30'] += 1;
               self::$general['espec-act-30'][] = $item['id'];
               self::$general['total-4'][] = array( $item['id'], 30);
            }

          }

        }

      }

    }

    return $espec;

  }


  public static function IntervaloCaracterizacion($evaluaciones){


    $auth = array('719056262954159', '719056262954154', '719056262954153', '719056262954157', '719056262954155', '719056262954158');
    $noAuth = array('319056265848491', '319056265848509', '319056265848510');

    $caract = array();
    $caract['nue']['1-15'] = 0;
    $caract['nue']['16-30'] = 0;
    $caract['nue']['31-45'] = 0;
    $caract['nue']['45'] = 0;

    $caract['act']['1-10'] = 0;
    $caract['act']['11-20'] = 0;
    $caract['act']['21-30'] = 0;
    $caract['act']['30'] = 0;

    self::$general['caract-nue-1-15'] = array();
    self::$general['caract-nue-16-30'] = array();
    self::$general['caract-nue-31-45'] = array();
    self::$general['caract-nue-45'] = array();
    self::$general['caract-nue-total'] = array();

    self::$general['caract-act-1-10'] = array();
    self::$general['caract-act-11-20'] = array();
    self::$general['caract-act-21-30'] = array();
    self::$general['caract-act-30'] = array();
    self::$general['caract-act-total'] = array();

    foreach ($evaluaciones['data'] as $key => $item) {

      if( in_array($item['ts'], $auth) && !in_array($item['te'], $noAuth)  ){

        if( !in_array($item['id'], self::$ids) ){
          self::$ids[] = $item['id'];

          if( $item['nu'] == '219056265840691' ){
            self::$general['caract-nue-total'][] = $item['id'];

            if( (int)$item['dif'] <= 15 ){
              $caract['nue']['1-15'] += 1;
              self::$general['caract-nue-1-15'][] = $item['id'];
              self::$general['total-1'][] = array( $item['id'], 45);
            }else if( (int)$item['dif'] <= 30 ){
              $caract['nue']['16-30'] += 1;
              self::$general['caract-nue-16-30'][] = $item['id'];
              self::$general['total-2'][] = array( $item['id'], 45);
            }else if( (int)$item['dif'] <= 45 ){
              $caract['nue']['31-45'] += 1;
              self::$general['caract-nue-31-45'][] = $item['id'];
              self::$general['total-3'][] = array( $item['id'], 45);
            }else if( (int)$item['dif'] > 45 ){
              $caract['nue']['45'] += 1;
              self::$general['caract-nue-45'][] = $item['id'];
              self::$general['total-4'][] = array( $item['id'], 45);
            }

          }else{

            self::$general['caract-act-total'][] = $item['id'];

            if( (int)$item['dif'] <= 10 ){
              $caract['act']['1-10'] += 1;
              self::$general['caract-act-1-10'][] = $item['id'];
              self::$general['total-1'][] = array( $item['id'], 30);
            }else if( (int)$item['dif'] <= 20 ){
              $caract['act']['11-20'] += 1;
              self::$general['caract-act-11-20'][] = $item['id'];
              self::$general['total-2'][] = array( $item['id'], 30);
            }else if( (int)$item['dif'] <= 30 ){
              $caract['act']['21-30'] += 1;
              self::$general['caract-act-21-30'][] = $item['id'];
              self::$general['total-3'][] = array( $item['id'], 30);
            }else if( (int)$item['dif'] > 30 ){
              $caract['act']['30'] += 1;
              self::$general['caract-act-30'][] = $item['id'];
              self::$general['total-4'][] = array( $item['id'], 30);
            }

          }

        }

        

      }

    }

    return $caract;

  }


  public static function IntervaloMateriales($evaluaciones){

    $mat = array();
    $mat['local']['1-5'] = 0;
    $mat['local']['6-10'] = 0;
    $mat['local']['11-15'] = 0;
    $mat['local']['15'] = 0;

    $mat['cidec']['1-15'] = 0;
    $mat['cidec']['16-30'] = 0;
    $mat['cidec']['31-45'] = 0;
    $mat['cidec']['45'] = 0;

    self::$general['materiales-local-1-5'] = array();
    self::$general['materiales-local-6-10'] = array();
    self::$general['materiales-local-11-15'] = array();
    self::$general['materiales-local-15'] = array();
    self::$general['materiales-local-total'] = array();

    self::$general['materiales-cidec-1-15'] = array();
    self::$general['materiales-cidec-16-30'] = array();
    self::$general['materiales-cidec-31-45'] = array();
    self::$general['materiales-cidec-45'] = array();
    self::$general['materiales-cidec-total'] = array();

    foreach ($evaluaciones['data'] as $key => $item) {

      if( $item['me'] == '831264706543203' && $item['ts'] != '719056262954164' ){

        if( !in_array($item['id'], self::$ids) ){

          self::$ids[] = $item['id'];

          //SI NO VA CIDEC
          if( $item['nu'] == '219056265840694' ){

            self::$general['materiales-local-total'][] = $item['id'];

            if( (int)$item['dif'] <= 5 ){
              $mat['local']['1-5'] += 1;
              self::$general['materiales-local-1-5'][] = $item['id'];
              self::$general['total-1'][] = array( $item['id'], 15);
            }else if( (int)$item['dif'] <= 10 ){
              $mat['local']['6-10'] += 1;
              self::$general['materiales-local-6-10'][] = $item['id'];
              self::$general['total-2'][] = array( $item['id'], 15);
            }else if( (int)$item['dif'] <= 15 ){
              $mat['local']['11-15'] += 1;
              self::$general['materiales-local-11-15'][] = $item['id'];
              self::$general['total-3'][] = array( $item['id'], 15);
            }else if( (int)$item['dif'] > 15 ){
              $mat['local']['15'] += 1;
              self::$general['materiales-local-15'][] = $item['id'];
              self::$general['total-4'][] = array( $item['id'], 15);
            }

          }else{

            self::$general['materiales-cidec-total'][] = $item['id'];

            if( (int)$item['dif'] <= 15 ){
              $mat['cidec']['1-15'] += 1;
              self::$general['materiales-cidec-1-15'][] = $item['id'];
              self::$general['total-1'][] = array( $item['id'], 45);
            }else if( (int)$item['dif'] <= 30 ){
              $mat['cidec']['16-30'] += 1;
              self::$general['materiales-cidec-16-30'][] = $item['id'];
              self::$general['total-2'][] = array( $item['id'], 45);
            }else if( (int)$item['dif'] <= 45 ){
              $mat['cidec']['31-45'] += 1;
              self::$general['materiales-cidec-31-45'][] = $item['id'];
              self::$general['total-3'][] = array( $item['id'], 45);
            }else if( (int)$item['dif'] > 45 ){
              $mat['cidec']['45'] += 1;
              self::$general['materiales-cidec-45'][] = $item['id'];
              self::$general['total-4'][] = array( $item['id'], 45);
            }

          }

        }

        

      }

    }

    return $mat;

  }


  public static function IntervaloEquipos($evaluaciones){
    //ACCESS POINT, MODEM, ONT
    $auth = array('319056265848491', '319056265848509', '319056265848510');
    $equipos = array();
    $equipos['nue']['1-15'] = 0;
    $equipos['nue']['16-30'] = 0;
    $equipos['nue']['31-45'] = 0;
    $equipos['nue']['45'] = 0;

    $equipos['act']['1-10'] = 0;
    $equipos['act']['11-20'] = 0;
    $equipos['act']['21-30'] = 0;
    $equipos['act']['30'] = 0;

    self::$general['equipos-nue-1-15'] = array();
    self::$general['equipos-nue-16-30'] = array();
    self::$general['equipos-nue-31-45'] = array();
    self::$general['equipos-nue-45'] = array();
    self::$general['equipos-nue-total'] = array();

    self::$general['equipos-act-1-10'] = array();
    self::$general['equipos-act-11-20'] = array();
    self::$general['equipos-act-21-30'] = array();
    self::$general['equipos-act-30'] = array();
    self::$general['equipos-act-total'] = array();

    foreach ($evaluaciones['data'] as $key => $item) {

      if( in_array($item['te'], $auth) ){

        if( !in_array($item['id'], self::$ids) ){
          self::$ids[] = $item['id'];

          //SI NO VA CIDEC
          if( $item['nu'] == '219056265840691' ){
            self::$general['equipos-nue-total'][] = $item['id'];

            if( (int)$item['dif'] <= 15 ){
              $equipos['nue']['1-15'] += 1;
              self::$general['equipos-nue-1-15'][] = $item['id'];
              self::$general['total-1'][] = array( $item['id'], 45);
            }else if( (int)$item['dif'] <= 30 ){
              $equipos['nue']['16-30'] += 1;
              self::$general['equipos-nue-16-30'][] = $item['id'];
              self::$general['total-2'][] = array( $item['id'], 45);
            }else if( (int)$item['dif'] <= 45 ){
              $equipos['nue']['31-45'] += 1;
              self::$general['equipos-nue-31-45'][] = $item['id'];
              self::$general['total-3'][] = array( $item['id'], 45);
            }else if( (int)$item['dif'] > 45 ){
              $equipos['nue']['45'] += 1;
              self::$general['equipos-nue-45'][] = $item['id'];
              self::$general['total-4'][] = array( $item['id'], 45);
            }

          }else{

            self::$general['equipos-act-total'][] = $item['id'];

            if( (int)$item['dif'] <= 10 ){
              $equipos['act']['1-10'] += 1;
              self::$general['equipos-act-1-10'][] = $item['id'];
              self::$general['total-1'][] = array( $item['id'], 30);
            }else if( (int)$item['dif'] <= 20 ){
              $equipos['act']['11-20'] += 1;
              self::$general['equipos-act-11-20'][] = $item['id'];
              self::$general['total-2'][] = array( $item['id'], 30);
            }else if( (int)$item['dif'] <= 30 ){
              $equipos['act']['21-30'] += 1;
              self::$general['equipos-act-21-30'][] = $item['id'];
              self::$general['total-3'][] = array( $item['id'], 30);
            }else if( (int)$item['dif'] > 30 ){
              $equipos['act']['30'] += 1;
              self::$general['equipos-act-30'][] = $item['id'];
              self::$general['total-4'][] = array( $item['id'], 30);
            }

          }

        }

        

      }

    }

    return $equipos;

  }


  public static function calculaPeriodo($diasDiferencia){

    $periodo['rango'] = "";
    $periodo['color'] = "";

    if( $diasDiferencia <= 0 ) return $periodo;

    $rangos = array( 
      15  => array('<= 15', '#33FF58'), 
      30  => array('<= 30', '#00CC00'),
      60  => array('31 - 60', '#AACC00'),
      90  => array('61 - 90', '#CCCC00'),
      120 => array('91 - 120', '#CCAA00'),
      999 => array('> 120', '#CC0000'),
    );

    foreach ($rangos as $per => $valores) {
      if( $diasDiferencia <= $per ){
        $periodo['rango'] = $valores[0];
        $periodo['color'] = $valores[1];
        break;
      }
    }

    return $periodo;

  }

  public static function calculaDilacion($diasDiferencia, $liberada){

    $dilacion['dias'] = "";
    $dilacion['no'] = "-";
    $dilacion['co'] = "#DEDEDE";

    if( $diasDiferencia === false ) return $dilacion;

    $dilacion['dias'] = $diasDiferencia;

    if( $liberada ){

      if($diasDiferencia <= 0){
                $dilacion['no']=" Liberado a tiempo ";
                $dilacion['co']="#DDFFDD";
            }else{
                $dilacion['no']=" Liberado con retraso ";
                $dilacion['co']="#FFDDDD";
            }

    }else{

      if($diasDiferencia >= 1){
                if ($diasDiferencia >= 1 && $diasDiferencia < 7 ){
                    $dilacion['no']="Por entregar en";
                    $dilacion['co']="#FFAA55";
                }elseif (($diasDiferencia >=7 && $diasDiferencia <15 )) {
                    $dilacion['no']="Te quedan";
                    $dilacion['co']="#FFffaa";
                }elseif (($diasDiferencia >=15)) {
                    $dilacion['no']=" A tiempo ";
                    $dilacion['co']="#aaffaa";
                }else{
                    $dilacion['no']=" Por entregar ";
                    $dilacion['co']="#aaffaa";
                }
            }else{
                if ($diasDiferencia ==0){
                    $dilacion['no']=" Entregar hoy ";
                    $dilacion['co']="#FFFF00";
                }else{
                    $dilacion['no']=" Retrasado ";
                    $dilacion['co']="#CC0000";
                }
            }

    }
    
    return $dilacion;

  }

  public static function calculaDiferenciaFechas($fechaI, $fechaF){

      $fffngh=explode(' ',trim($fechaF));
      $ffingh=explode(' ',trim($fechaI));

      $fffn=explode('-',$fffngh[0]);
      $ffin=explode('-',$ffingh[0]);

      if ($fffn == "0000-00-00" && $ffin == "0000-00-00" ){
        $diasDiferencia=0;
          $DiasHabiles=0;
          $DiasNOHabiles=0;
          return $diasDiferencia;
      }

        //defino fecha 1
        $ano1 = $ffin[0];
        $mes1 = $ffin[1];
        $dia1 = $ffin[2];

        //defino fecha 2
        $ano2 = $fffn[0];
        $mes2 = $fffn[1];
        $dia2 = $fffn[2];

        $timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1);
        $timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2);

        $segundos_diferencia = $timestamp2-$timestamp1 ;
        $diasDiferencia = $segundos_diferencia / (60 * 60 * 24);
        $diasDiferencia = floor($diasDiferencia);

        // $DiasHabiles=0;
        // $DiasNOHabiles=0;
        // if ($diasDiferencia<0){
        //     for ($aa=0;$aa<=($diasDiferencia*-1);$aa++){
        //         $diaSEM=date("N", mktime(0,0,0,$mes2,$dia2+$aa,$ano2));
        //         if ($diaSEM>=1 && $diaSEM<=5){
        //             $DiasHabiles--;
        //         }else{
        //             $DiasNOHabiles--;
        //         }
        //     }
        // }else{
        //     for ($aa=0;$aa<=$diasDiferencia;$aa++){
        //         $diaSEM=date("N", mktime(0,0,0,$mes1,$dia1+$aa,$ano1));
        //         if ($diaSEM>=1 && $diaSEM<=5){
        //             $DiasHabiles++;
        //         }else{
        //             $DiasNOHabiles++;
        //         }
        //     }
        // }
      
      return $diasDiferencia;

  }

}
