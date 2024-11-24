<?php

require_once('../models/conection.php');

$validator = array('success' => false, 'messages' => array());

$id = $_POST["id"];
$foto = $_POST["foto"];
$nombre = $_POST["nombre"];
$apellido = $_POST["apellido"];
$fecha = $_POST["fecha"];
$email = $_POST["email"];
$dni = $_POST["dni"];
$sexo = $_POST["sexo"];


$sql = 'UPDATE tbl_asistance_records SET dni = :dni, nombre = :nombre, apellido = :apellido, fnac = :fnac, email = :email, sexo = :sexo, foto = :foto WHERE id = :id';
$stmt = $db->prepare($sql);
$stmt->bindValue(':dni', $dni, PDO::PARAM_STR);
$stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
$stmt->bindValue(':apellido', $apellido, PDO::PARAM_STR);
$stmt->bindValue(':fnac', $fecha, PDO::PARAM_STR);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->bindValue(':sexo', $sexo, PDO::PARAM_STR);
$stmt->bindValue(':foto', $foto, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);


if ($stmt->execute()) {
	$validator['success'] = true;
	$validator['messages'] = "DATOS GUARDADOS";
} else {
	$validator['messages'] = "ERROR AL GUARDAR DATOS";
}

header('Content-type: application/json; charset=utf-8');
echo json_encode($validator);
exit();
