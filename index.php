<?php

error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', 1);

spl_autoload_register(function ($class) {
	$dir = 'model/' . $class . '.php';
	if (file_exists($dir)) {
		require_once $dir;
	}
});


$connection = new Connection();
$index = new Index($connection->dbConnection);

$index->display();