<?php
// error_reporting(E_ALL ^ E_DEPRECATED);
/*Seteear al inicio de php la codificacion de caracteres*/
ini_set( 'default_charset', "utf-8" );

define("PROJECTPATH", dirname(__FILE__));
require PROJECTPATH .'/libs/FrontController.php';
FrontController::main();
