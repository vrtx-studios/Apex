<?php

require_once "../Config/cfBase.php";
require_once "../Core/clAutoloader.php";

// init the benchmark if environment is development and benchmark is set to true.

ini_set( 'display_errors', true );
ini_set( 'memory_limit', '256M' );
ini_set( 'magic_quotes_gpc', 0 );
ini_set( 'magic_quotes_runtime', 0 );
ini_set( 'magic_quotes_sybase', 0 );
ini_set( 'session.gc_maxlifetime', 3600 );

error_reporting( E_ALL );

session_start();

!isset( $_SESSION['locale'] ) ? $_SESSION['locale'] = $GLOBALS['default_lang'] : $_SESSION['locale'] = $_SESSION['locale'];
!isset( $_SESSION['langId'] ) ? $_SESSION['langId'] = $GLOBALS['default_langId'] : $_SESSION['langId'] = $_SESSION['langId'];
date_default_timezone_set( DEFAULT_TIMEZONE );

//global $oAutoloader;
$oAutoloader = new Core\clAutoloader();
$oAutoloader->register();
$oAutoloader->addNamespace("Core", "../Core");
$oAutoloader->addNamespace("Modules", "../Modules");

global $oRegistry;
$oRegistry = new Core\clRegistry();
$oRegistry::set( 'clAutoloader', $oAutoloader );

// Init bootstrap
$oApplication = new Core\bootstrap();
$oApplication->execute();