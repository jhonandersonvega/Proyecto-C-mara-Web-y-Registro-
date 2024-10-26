<?php

require_once('../models/conection.php');

$validator = array('success' => false, 'messages' => array());

if (!empty($_FILES["archivo"]["name"])) {
    $fileName = basename($_FILES["archivo"]["name"]);
    $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
    
    // Tipos de archivos permitidos
    $allowTypes = array('jpg', 'png', 'jpeg');

    if (in_array($fileType, $allowTypes)) {
        
        // Verificar que se hayan enviado todos los datos requeridos
        if (!empty($_POST["base64image"]) && !empty($_POST["nombre"]) && !empty($_POST["apellido"]) && 
            !empty($_POST["fnac"]) && !empty($_POST["email"]) && !empty($_POST["dni"]) && 
            !empty($_POST["sexo"]) && !empty($_POST["firma"])) {

            $base64image = $_POST["base64image"];
            $nombre = $_POST["nombre"];
            $apellido = $_POST["apellido"];
            $fnac = $_POST["fnac"];
            $email = $_POST["email"];
            $dni = $_POST["dni"];
            $sexo = $_POST["sexo"];
            $firma = $_POST["firma"]; // Firma recibida en base64

            // Consulta SQL para insertar los datos
            $sql = 'INSERT INTO tbl_asistance_records (dni, nombre, apellido, fnac, email, sexo, foto, signature) 
                    VALUES (:dni, :nombre, :apellido, :fnac, :email, :sexo, :foto, :signature)';
            $stmt = $db->prepare($sql);
            
            // Vinculamos los parámetros
            $stmt->bindValue(':dni', $dni, PDO::PARAM_STR);
            $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindValue(':apellido', $apellido, PDO::PARAM_STR);
            $stmt->bindValue(':fnac', $fnac, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':sexo', $sexo, PDO::PARAM_STR);
            $stmt->bindValue(':foto', $base64image, PDO::PARAM_STR); // Imagen en base64
            $stmt->bindValue(':signature', $firma, PDO::PARAM_STR); // Firma en base64

            // Ejecutar la consulta y verificar el resultado
            if ($stmt->execute()) {
                $validator['success'] = true;
                $validator['messages'] = "DATOS GUARDADOS";
            } else {
                $validator['messages'] = "ERROR AL GUARDAR DATOS";
            }
        } else {
            $validator['messages'] = 'TODOS LOS CAMPOS SON REQUERIDOS';
        }
    } else {
        $validator['messages'] = 'SOLO SE PERMITEN FORMATOS JPG, PNG Y JPEG.';
    }
} else {
    $validator['messages'] = "NO SE HA SUBIDO NINGUNA IMAGEN";
}

// Devolver la respuesta en formato JSON
header('Content-type: application/json; charset=utf-8');
echo json_encode($validator);
exit();
