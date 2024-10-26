<?php
$hostname = "127.0.0.1";
$dbname = "db_asistance";
$username = "admin";
$pw = "admin";


try {
	$db = new PDO('mysql:host=' . $hostname . ';dbname=' . $dbname, $username, $pw);
	$db->setAttribute(PDO::ATTR_TIMEOUT, 600); // 600 segundos (10 minutos)
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Opcional: para manejar errores
} catch (PDOException $ex) {
	echo "Error al conectar a la base de datos: " . $ex->getMessage() . "\n";
	exit;
}
