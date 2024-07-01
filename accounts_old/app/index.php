<?php
session_start ();
define ( 'DS', DIRECTORY_SEPARATOR );
define ( 'ROOT', dirname ( dirname ( __FILE__ ) ) );

if ( isset ( $_GET[ 'url' ] ) )
{
    $url = $_GET[ 'url' ];
}

require_once (ROOT . DS . 'config' . DS . 'config.php');
require_once (ROOT . DS . 'app' . DS . 'library' . DS . 'router.php');
require_once (ROOT . DS . 'app' . DS . 'library' . DS . 'shared.php');
?>