<?php

require_once('../models/conection.php');

$validator = array('success' => false, 'messages' => array());

$id = $_POST["id"];

// Cambiar la consulta SQL para marcar el registro como eliminado
$sql = 'UPDATE tbl_asistance_records SET is_deleted = 1 WHERE id = :id';
$stmt = $db->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);

// Ejecutar la consulta y manejar la respuesta
if ($stmt->execute()) {
    $validator['success'] = true;
    $validator['messages'] = "Registro eliminado con éxito."; // Mensaje de éxito
} else {
    $validator['messages'] = "Error al eliminar el registro."; // Mensaje de error
}

// Configurar el tipo de contenido y devolver la respuesta como JSON
header('Content-type: application/json; charset=utf-8');
echo json_encode($validator);
exit();