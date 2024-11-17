<?php
include "conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono1 = $_POST['telefono1'];
    $codigoMascota = $_POST['codigoMascota'];
    $nombreMascota = $_POST['nombreMascota'];
    $chip = $_POST['chip'];
    $entidadPeruana = $_POST['entidadPeruana'];
    $estado = $_POST['estado'];

    $sql = "INSERT INTO ClientesMascotas (nombre, direccion, telefono1, codigoMascota, nombreMascota, chip, entidadPeruana, estado)
            VALUES ('$nombre', '$direccion', '$telefono1', '$codigoMascota', '$nombreMascota', '$chip', '$entidadPeruana', '$estado')";

    if ($conn->query($sql) === TRUE) {
        echo "Registro agregado correctamente";
    } else {
        echo "Error: " . $conn->error;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $result = $conn->query("SELECT * FROM ClientesMascotas");
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}

$conn->close();
?>
