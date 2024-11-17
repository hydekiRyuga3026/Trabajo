<?php
include 'conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta para eliminar
    $sql = "DELETE FROM clientes_mascotas WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Registro eliminado con éxito.";
        header("Location: clientes_mascotas.php");
    } else {
        echo "Error al eliminar: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
} else {
    echo "ID no proporcionado.";
}
?>
