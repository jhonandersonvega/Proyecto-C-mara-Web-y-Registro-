<?php

require_once('../models/conection.php');

$data['data'] = [];

$sql = 'SELECT id, dni, nombre, apellido, fnac, email, sexo, signature, foto FROM tbl_asistance_records WHERE is_deleted IS NULL';
$sentencia = $db->query($sql);
$alumnos = $sentencia->fetchAll(PDO::FETCH_OBJ);

foreach ($alumnos as $alumno) {
    $fecha = $alumno->fnac;
    $dni = $alumno->dni;
    $nombre = $alumno->nombre;
    $apellido = $alumno->apellido;
    $email = $alumno->email;
    $sexo = $alumno->sexo;
    $signature = $alumno->signature;
    $foto = $alumno->foto;
    $id = $alumno->id;

    // Botones para las acciones
    $botonVerFoto = "<a type='button' class='btn btn-sm btn-info' onclick='verfoto(\"{$foto}\")' id='btnFoto_{$dni}'> Foto</a>";
    $acciones = "<div>"
        . "<a type='button' class='btn btn-sm btn-danger' onclick='eliminarRegistro({$id})'> Eliminar</a>"
        . "<a type='button' class='btn btn-sm btn-warning' onclick='editarRegistro(" . json_encode($alumno) . ")' id='btnEditar_{$id}'> Editar</a>"
        . "</div>";

    // Convierte la firma en una URL de imagen
    $signatureImg = '<img src="' . $signature . '" alt="Firma" style="width: 150px; height: auto;">';

    // Agregar los datos y botones al array
    $data['data'][] = array(
        $fecha,
        $dni,
        $nombre,
        $apellido,
        $email,
        $sexo,
        $signatureImg,
        $botonVerFoto,
        $acciones
    );
}

echo json_encode($data);
exit;
