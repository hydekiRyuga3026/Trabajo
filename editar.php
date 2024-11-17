<?php
// Definir las variables de conexión a la base de datos
$host = 'localhost'; // Cambia esto si tu servidor no es local
$dbname = 'veterinaria'; // Cambia esto por el nombre de tu base de datos
$username = 'root'; // Cambia esto por tu nombre de usuario de MySQL
$password = ''; // Cambia esto por tu contraseña de MySQL, si es que tienes una

try {
    // Conexión a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configurar el manejo de errores

    // Verificar si el parámetro cliente_id está presente en la URL
    if (!isset($_GET['cliente_id'])) {
        die("ID de cliente no proporcionado.");
    }

    $cliente_id = $_GET['cliente_id'];

    // Cargar datos del cliente y sus mascotas
    $sql_cliente = "SELECT * FROM clientes WHERE id = ?";
    $sql_mascotas = "SELECT * FROM mascotas WHERE cliente_id = ?";
    $stmt_cliente = $pdo->prepare($sql_cliente);
    $stmt_mascotas = $pdo->prepare($sql_mascotas);

    $stmt_cliente->execute([$cliente_id]);
    $cliente = $stmt_cliente->fetch(PDO::FETCH_ASSOC);

    // Verificar si el cliente existe
    if (!$cliente) {
        die("Cliente no encontrado.");
    }

    $stmt_mascotas->execute([$cliente_id]);
    $mascotas = $stmt_mascotas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Manejo de excepciones de la base de datos
    die("Error de conexión o consulta a la base de datos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
</head>
<body>
    <h2>Editar Cliente y Mascotas</h2>
    <form action="actualizar.php" method="POST">
        <input type="hidden" name="cliente_id" value="<?php echo htmlspecialchars($cliente['id']); ?>">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($cliente['nombre']); ?>">
        <br>
        <label>Dirección:</label>
        <input type="text" name="direccion" value="<?php echo htmlspecialchars($cliente['direccion']); ?>">
        <br>
        <label>Teléfono:</label>
        <input type="text" name="telefono" value="<?php echo htmlspecialchars($cliente['telefono']); ?>">
        <br>
        <label>Código de Mascota:</label>
        <input type="text" name="codigo_mascota" value="<?php echo htmlspecialchars($cliente['codigo_mascota']); ?>">
        <br><br>

        <?php foreach ($mascotas as $mascota): ?>
            <h3>Mascota: <?php echo htmlspecialchars($mascota['nombre']); ?></h3>
            <input type="hidden" name="mascotas[<?php echo $mascota['id']; ?>][id]" value="<?php echo $mascota['id']; ?>">
            <label>Nombre Mascota:</label>
            <input type="text" name="mascotas[<?php echo $mascota['id']; ?>][nombre]" value="<?php echo htmlspecialchars($mascota['nombre']); ?>">
            <br>
            <label>Chip:</label>
            <input type="text" name="mascotas[<?php echo $mascota['id']; ?>][chip]" value="<?php echo htmlspecialchars($mascota['chip']); ?>">
            <br>
            <label>Entidad Peruana:</label>
            <input type="text" name="mascotas[<?php echo $mascota['id']; ?>][entidad_peruana]" value="<?php echo htmlspecialchars($mascota['entidad_peruana']); ?>">
            <br><br>
        <?php endforeach; ?>

        <button type="submit">Actualizar</button>
    </form>
</body>
</html>
