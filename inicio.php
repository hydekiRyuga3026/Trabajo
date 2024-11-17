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

    // Mostrar mensaje de éxito si se redirige desde formulario.php
    if (isset($_GET['success']) && $_GET['success'] == 'true') {
        echo "<div style='background-color: #3CD3A7; color: white; padding: 10px;'>Datos agregados exitosamente.</div><br>";
    }

    // Actualizar el estado cuando se cambia
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['estado'])) {
        $id = $_POST['id'];
        $estado = $_POST['estado'];

        // Actualizar el estado en la base de datos
        $sql = "UPDATE clientesmascotas SET estado = :estado WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':estado' => $estado, ':id' => $id]);
        
        // Redirigir para reflejar el cambio
        header("Location: inicio.php");
        exit;
    }

    // Obtener los datos de la base de datos
    $stmt = $pdo->query("SELECT * FROM clientesmascotas");
    $clientesMascotas = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // En caso de error, enviar un mensaje de error
    echo "error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QVet Interface</title>
    <style>
        /* Aquí se mantienen los mismos estilos */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .sidebar {
            width: 250px;
            background-color: #4A90E2; /* Azul medio */
            color: #fff;
            height: 100vh;
            position: fixed;
            padding: 20px 0;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 15px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .sidebar ul li:hover {
            background-color: #5DA6E8; /* Azul un poco más claro */
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #e0e0e0;
            padding: 10px 20px;
            border-bottom: 1px solid #ccc;
        }
        .search-box {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .search-box input, .search-box select {
            padding: 5px;
            font-size: 1rem;
        }
        .search-box button {
            padding: 5px 10px;
            background-color: #3CD3A7; /* Verde menta */
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .search-box button:hover {
            background-color: #34B890; /* Verde menta más oscuro para el hover */
        }
        .result-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .result-table th, .result-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .result-table th {
            background-color: #4A90E2; /* Azul medio */
            color: #fff;
        }
        .estado-activo {
            color: green;
            font-weight: bold;
        }
        .estado-inactivo {
            color: red;
            font-weight: bold;
        }
        .btn {
            padding: 5px 10px;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-eliminar {
            background-color: #FF5E57; /* Rojo */
        }
        .btn-eliminar:hover {
            background-color: #E34B4A; /* Rojo oscuro */
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>QVet</h2>
        <ul>
            <li>Clientes / Mascotas</li>
            <li>Artículos / Servicios</li>
            <li>Ventas mostrador</li>
            <li>Cobrar deuda</li>
            <li>Edición de documentos</li>
            <li>Sala de espera</li>
            <li>Agenda</li>
            <li>Caja</li>
        </ul>
    </div>
    <div class="main-content">
        <div class="header">
            <h3>Clientes / Mascotas</h3>
            <div class="search-box">
                <input type="text" placeholder="Nombre">
                <input type="text" placeholder="Código">
                <select>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
                <button onclick="listar()">Buscar</button>
                <div class="action-buttons">
                    <a href="formulario.php">
                        <button class="btn">Agregar</button>
                    </a>
                </div>
            </div>
        </div>
        <table class="result-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Código de mascota</th>
                    <th>Nombre Mascota</th>
                    <th>Chip</th>
                    <th>Entidad Peruana</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-resultados">
                <?php foreach ($clientesMascotas as $cliente): ?>
                    <tr>
                        <td><?php echo $cliente['nombre']; ?></td>
                        <td><?php echo $cliente['direccion']; ?></td>
                        <td><?php echo $cliente['telefono']; ?></td>
                        <td><?php echo $cliente['codigoMascota']; ?></td>
                        <td><?php echo $cliente['nombreMascota']; ?></td>
                        <td><?php echo $cliente['chip']; ?></td>
                        <td><?php echo $cliente['entidadPeruana']; ?></td>
                        <td>
                            <form action="inicio.php" method="POST">
                                <select name="estado" onchange="this.form.submit()">
                                    <option value="activo" <?php echo $cliente['estado'] == 'activo' ? 'selected' : ''; ?>>Activo</option>
                                    <option value="inactivo" <?php echo $cliente['estado'] == 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                                </select>
                                <input type="hidden" name="id" value="<?php echo $cliente['id']; ?>">
                            </form>
                        </td>
                        <td><a href="eliminar.php?id=<?php echo $cliente['id']; ?>"><button class="btn btn-eliminar">Eliminar</button></a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
