<?php

require_once('../models/conection.php');

$validator = array('success' => false, 'messages' => array());

$foto = $_POST["foto"];
$nombre = $_POST["nombre"];  
$apellido = $_POST["apellido"];
$fecha = $_POST["fecha"];
$email = $_POST["email"];
$dni = $_POST["dni"];
$sexo = $_POST["sexo"];
$firma = $_POST["firma"]; 

//$route_photo = "#" . $dni . ".jpg";
//$name_photo = "f_" . $dni . ".jpg";
//$file = fopen($route_photo, "w");

//if ($file) {
	//$fotos = fwrite($file, $foto);
	//fclose($file);

	$sql = 'INSERT INTO tbl_asistance_records (dni, nombre, apellido, fnac, email, sexo, foto, signature) VALUES (:dni, :nombre, :apellido, :fnac, :email, :sexo, :foto, :signature)';
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':dni', $dni, PDO::PARAM_STR);
	$stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
	$stmt->bindValue(':apellido', $apellido, PDO::PARAM_STR);
	$stmt->bindValue(':fnac', $fecha, PDO::PARAM_STR);
	$stmt->bindValue(':email', $email, PDO::PARAM_STR);
	$stmt->bindValue(':sexo', $sexo, PDO::PARAM_STR);
	$stmt->bindValue(':foto', $foto, PDO::PARAM_STR);
	$stmt->bindValue(':signature', $firma, PDO::PARAM_STR); // Guardar la firma base64 en la base de datos


	if ($stmt->execute()) {
		$validator['success'] = true;
		$validator['messages'] = "DATOS GUARDADOS";
	} else {
		$validator['messages'] = "ERROR AL GUARDAR DATOS";
	}
//} else {
//	$validator['messages'] = "ERROR AL GUARDAR LA FOTO";
//}

header('Content-type: application/json; charset=utf-8');
echo json_encode($validator);
exit();
