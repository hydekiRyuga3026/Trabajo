<?php
// Definir las variables de conexión a la base de datos
$host = 'localhost'; // Cambiar por tu host
$dbname = 'veterinaria'; // Cambiar por tu nombre de base de datos
$username = 'root'; // Cambiar por tu nombre de usuario
$password = ''; // Cambiar por tu contraseña

try {
    // Conexión a la base de datos con manejo de excepciones
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si los datos existen en el arreglo $_POST
    if (!isset($_POST['nombre'], $_POST['direccion'], $_POST['telefono'], $_POST['codigo_mascota'], $_POST['cliente_id'])) {
        die("Faltan datos en el formulario.");
    }

    // Sanitizar y validar los datos del cliente
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $direccion = filter_var($_POST['direccion'], FILTER_SANITIZE_STRING);
    $telefono = filter_var($_POST['telefono'], FILTER_SANITIZE_STRING);
    $codigo_mascota = filter_var($_POST['codigo_mascota'], FILTER_SANITIZE_STRING);
    $cliente_id = filter_var($_POST['cliente_id'], FILTER_VALIDATE_INT);

    if (!$cliente_id) {
        die("ID de cliente no válido.");
    }

    // Actualizar datos del cliente
    $sql_cliente = "UPDATE clientes SET nombre = ?, direccion = ?, telefono_1 = ?, codigo_mascota = ? WHERE id = ?";
    $stmt_cliente = $pdo->prepare($sql_cliente);
    if (!$stmt_cliente->execute([$nombre, $direccion, $telefono, $codigo_mascota, $cliente_id])) {
        die("Error al actualizar los datos del cliente.");
    }

    // Validar y sanitizar los datos de las mascotas
    if (isset($_POST['mascotas']) && is_array($_POST['mascotas'])) {
        foreach ($_POST['mascotas'] as $mascota_id => $mascota_data) {
            // Sanitizar los datos de cada mascota
            $mascota_nombre = filter_var($mascota_data['nombre_mascota'], FILTER_SANITIZE_STRING);
            $mascota_chip = filter_var($mascota_data['chip'], FILTER_SANITIZE_STRING);
            $entidad_peruana = filter_var($mascota_data['entidad_peruana'], FILTER_SANITIZE_STRING);
            $estado = filter_var($mascota_data['estado'], FILTER_SANITIZE_STRING);

            // Validar ID de la mascota
            $mascota_id = filter_var($mascota_id, FILTER_VALIDATE_INT);
            if (!$mascota_id) {
                die("ID de mascota no válido.");
            }

            // Actualizar datos de la mascota
            $sql_mascota = "UPDATE mascotas SET nombre_mascota = ?, chip = ?, entidad_peruana = ?, estado = ? WHERE id = ?";
            $stmt_mascota = $pdo->prepare($sql_mascota);
            if (!$stmt_mascota->execute([$mascota_nombre, $mascota_chip, $entidad_peruana, $estado, $mascota_id])) {
                die("Error al actualizar los datos de la mascota con ID $mascota_id.");
            }
        }
    } else {
        die("Datos de las mascotas no válidos.");
    }

    // Redirigir a la página de listado después de la actualización
    header("Location: listar.php");
    exit;

} catch (PDOException $e) {
    // Manejo de excepciones de la base de datos
    die("Error de conexión o consulta a la base de datos: " . $e->getMessage());
}
?>
