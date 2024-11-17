<?php
// Conexión a la base de datos
$host = "localhost";
$dbname = "Veterinaria";
$username = "root";
$password = "";

try {
    // Intentamos conectar a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obtener clientes y sus mascotas (actualizando los nombres de las columnas)
    $sql = "SELECT id as cliente_id, nombre as cliente_nombre, direccion, telefono, codigoMascota,
                   nombreMascota, chip, entidadPeruana, estado
            FROM clientesmascotas";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    // Recuperar todos los resultados
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // En caso de error en la conexión
    echo "Error en la conexión: " . $e->getMessage();
    exit; // Detenemos la ejecución si hay un error
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Clientes y Mascotas</title>
</head>
<body>
    <h2>Clientes y Mascotas</h2>
    
    <?php if (!empty($clientes)): ?>
        <table border="1">
            <tr>
                <th>ID Cliente</th>
                <th>Nombre Cliente</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Código de Mascota</th>
                <th>Nombre Mascota</th>
                <th>Chip</th>
                <th>Entidad Peruana</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?php echo htmlspecialchars($cliente['cliente_id']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['cliente_nombre']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['direccion']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['codigoMascota']); ?></td> <!-- Actualización aquí -->
                    <td><?php echo htmlspecialchars($cliente['nombreMascota']); ?></td> <!-- Actualización aquí -->
                    <td><?php echo htmlspecialchars($cliente['chip']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['entidadPeruana']); ?></td> <!-- Actualización aquí -->
                    <td><?php echo htmlspecialchars($cliente['estado']); ?></td>
                    <td>
                        <a href="editar.php?cliente_id=<?php echo $cliente['cliente_id']; ?>">Editar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No hay clientes registrados.</p>
    <?php endif; ?>
</body>
</html>
