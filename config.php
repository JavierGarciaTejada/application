<?php

Config::$configuration = Config::singleton();

Config::$configuration->set('file_config', PROJECTPATH .'/libs/config/env.ini');
Config::$configuration->set('anexos_path', PROJECTPATH .'/anexos/');

Config::$configuration->set('controllersFolder', PROJECTPATH .'/controllers/');
Config::$configuration->set('modelsFolder', PROJECTPATH .'/models/');
Config::$configuration->set('viewsFolder', PROJECTPATH .'/views/');
Config::$configuration->set('filesFolder', PROJECTPATH . '/media/files/');
//servidor host (HTTP_HOST)
Config::$configuration->set('host' , (isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : "http") ."://". $_SERVER['HTTP_HOST']);
//directorio host (DOCUMENT ROOT apache)
Config::$configuration->set('host_path', ((strlen(dirname($_SERVER['SCRIPT_NAME'])) > 1) ? dirname($_SERVER['SCRIPT_NAME']) : '') );
Config::$configuration->set('server_path', Config::$configuration->get('host') . Config::$configuration->get('host_path') );
//carpeta media para los estilos y js, imagenes etc...
Config::$configuration->set('media', Config::$configuration->get('server_path') .'/media/');
Config::$configuration->set('index', 'login');//clase de inicio

/*Configuracion para logs*/
Config::$configuration->set('log_path', PROJECTPATH . '/libs/logs/');

/*Configuracion para vistas*/

Config::$configuration->set('pathlayout', Config::$configuration->get('viewsFolder') .'layouts/');
Config::$configuration->set('layout', 'layout.phtml');
Config::$configuration->set('layout_jquery_ui', 'layout_jqueryui.phtml');
Config::$configuration->set('layoutlogin', Config::$configuration->get('pathlayout') .'login.phtml');
Config::$configuration->set('ruta_login', '/index.php/login/');
Config::$configuration->set('slogan', 'SAAE');
Config::$configuration->set('img_logo', Config::$configuration->get('media') .'images/telmex.svg');
Config::$configuration->set('logo', 'TELMEX');

// Cargar librerias PHP

Config::$configuration->set('path_libraries', PROJECTPATH . '/libraries/');
//Config::$configuration->set('phpMailer', PROJECTPATH .'/libraries/phpmailer_2.0.2//class.phpmailer.php');
//Config::$configuration->set('phpMailerSmtp', PROJECTPATH .'/libraries/phpmailer_2.0.2/class.smtp.php');
Config::$configuration->set('phpMailer', PROJECTPATH .'/libraries/PHPMailer-master/src/PHPMailer.php');
Config::$configuration->set('phpMailerSmtp', PROJECTPATH .'/libraries/PHPMailer-master/src/SMTP.php');
Config::$configuration->set('nomad_mimemail', PROJECTPATH .'/libraries/nomad_mimemail/nomad_mimemail.inc.php');
