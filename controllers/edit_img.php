<?php

require_once('../models/conection.php');

$validator = array('success' => false, 'messages' => array());

// Verificar que se hayan enviado todos los datos requeridos (sin incluir la imagen)
if (
    !empty($_POST["nombre"]) && !empty($_POST["apellido"]) &&
    !empty($_POST["fnac"]) && !empty($_POST["email"]) && !empty($_POST["dni"]) &&
    !empty($_POST["sexo"])
) {
    $id = $_POST["id"];
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $fnac = $_POST["fnac"];
    $email = $_POST["email"];
    $dni = $_POST["dni"];
    $sexo = $_POST["sexo"];

    // Inicializar la consulta SQL
    $sql = 'UPDATE tbl_asistance_records SET dni = :dni, nombre = :nombre, apellido = :apellido, fnac = :fnac, email = :email, sexo = :sexo';
    
    // Solo incluir la imagen si se ha subido
    if (!empty($_FILES["archivo"]["name"])) {
        $fileName = basename($_FILES["archivo"]["name"]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

        // Tipos de archivos permitidos
        $allowTypes = array('jpg', 'png', 'jpeg');

        if (in_array($fileType, $allowTypes)) {
            $base64image = $_POST["base64image"];
            $sql .= ', foto = :foto'; // Agregar la imagen a la consulta
        } else {
            $validator['messages'] = 'SOLO SE PERMITEN FORMATOS JPG, PNG Y JPEG.';
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($validator);
            exit();
        }
    }
    // Agregar el ID a la consulta
    $sql .= ' WHERE id = :id';
    $stmt = $db->prepare($sql);

    // Vinculamos los parámetros
    $stmt->bindValue(':dni', $dni, PDO::PARAM_STR);
    $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindValue(':apellido', $apellido, PDO::PARAM_STR);
    $stmt->bindValue(':fnac', $fnac, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':sexo', $sexo, PDO::PARAM_STR);
    
    // Vincular la imagen solo si se ha subido
    if (!empty($_FILES["archivo"]["name"])) {
        $stmt->bindValue(':foto', $base64image, PDO::PARAM_STR); // Imagen en base64
    }
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    // Ejecutar la consulta y verificar el resultado
    if ($stmt->execute()) {
        $validator['success'] = true;
        $validator['messages'] = "DATOS GUARDADOS";
    } else {
        $validator['messages'] = "ERROR AL GUARDAR DATOS";
    }
} else {
    $validator['messages'] = 'TODOS LOS CAMPOS SON REQUERIDOS EXCEPTO LA IMAGEN';
}

// Devolver la respuesta en formato JSON
header('Content-type: application/json; charset=utf-8');
echo json_encode($validator);
exit();
