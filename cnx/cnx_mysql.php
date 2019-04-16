<?php
	

define('DB_SERVER', '192.168.1.20');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'admindues');
define('DB_DATABASE', 'comisiones');


	$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
	if ($db -> connect_errno) {
	  die( "No hay conexion: (" . $db -> mysqli_connect_errno() . ") " . $db -> mysqli_connect_error());
	}

?>