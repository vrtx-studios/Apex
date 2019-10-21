<?php

define( 'SITE_TITLE', 'Apex2.0' );

// Database details
define( 'DB_ENGINE', 'mysql' ); // postgresql, sqlite, mysql
define( 'DB_PATH', NULL ); // Only used if using sqlite
define( 'DB_PORT', '3306' );
define( 'DB_HOST', 'localhost' );
define( 'DB_USER', 'root' );
define( 'DB_PASS', '' );
define( 'DB_NAME', 'apex_db' );

define( 'PATH_ROOT', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR . '..' );
define( 'PATH_FUNCTIONS', PATH_ROOT . DIRECTORY_SEPARATOR . 'Functions' . DIRECTORY_SEPARATOR );
define( 'PATH_LOCALE', PATH_ROOT . DIRECTORY_SEPARATOR . 'Locale' );
define( 'PATH_LOG', PATH_ROOT . DIRECTORY_SEPARATOR . "Logs" . DIRECTORY_SEPARATOR );
define( 'PATH_TEMPLATE' , PATH_ROOT . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR );

// Localization
define( 'DEFAULT_TIMEZONE', 'Europe/Stockholm' );
// Dynamic Localization
$GLOBALS['default_langId'] = 1;
$GLOBALS['default_lang'] = 'sv_SE';
define( 'default_encoding', 'UTF-8' );