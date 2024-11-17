<?php
// Conexión a la base de datos
$host = "localhost";
$dbname = "Veterinaria";
$username = "root";
$password = "";

$mensaje = "";  // Variable para el mensaje de éxito

try {
    // Intentamos conectar a la base de datos
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si las claves están definidas en $_POST para agregar datos
    if (isset($_POST['nombre'], $_POST['direccion'], $_POST['telefono'], $_POST['codigoMascota'], $_POST['nombreMascota'], $_POST['chip'], $_POST['entidadPeruana'])) {
        
        // Obtener los datos enviados por POST
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $codigoMascota = $_POST['codigoMascota'];
        $nombreMascota = $_POST['nombreMascota'];
        $chip = $_POST['chip'];
        $entidadPeruana = $_POST['entidadPeruana'];

        // Verificar si los valores no están vacíos
        if (empty($nombre) || empty($direccion) || empty($telefono) || empty($codigoMascota) || empty($nombreMascota) || empty($chip) || empty($entidadPeruana)) {
            echo "Todos los campos son requeridos.";
            exit;
        }

        // Preparar la consulta SQL para insertar los datos
        $sql = "INSERT INTO clientesmascotas (nombre, direccion, telefono, codigoMascota, nombreMascota, chip, entidadPeruana)
                VALUES (:nombre, :direccion, :telefono, :codigoMascota, :nombreMascota, :chip, :entidadPeruana)";
        
        // Ejecutar la consulta
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':direccion' => $direccion,
            ':telefono' => $telefono,
            ':codigoMascota' => $codigoMascota,
            ':nombreMascota' => $nombreMascota,
            ':chip' => $chip,
            ':entidadPeruana' => $entidadPeruana
        ]);

        // Establecer el mensaje de éxito con el nombre agregado
        $mensaje = "Se agregó $nombre exitosamente.";

    } else {
        // Si faltan datos en el formulario
        echo "Faltan datos en el formulario.";
    }

    // Mostrar los datos en la tabla
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
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        /* Sidebar */
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
        /* Main content */
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
        /* Estilo para el mensaje de éxito */
        .success-message {
            background-color: #3CD3A7; /* Verde menta */
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none;
            opacity: 1;
            transition: opacity 2s ease-out;
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
                    <!-- Botón redirige a formulario.php -->
                    <a href="formulario.php">
                        <button class="btn">Agregar</button>
                    </a>
                </div>
            </div>
        </div>

        <!-- Mostrar el mensaje de éxito -->
        <?php if ($mensaje): ?>
            <div class="success-message" id="successMessage"><?php echo $mensaje; ?></div>
        <?php endif; ?>

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
            <tbody>
                <?php foreach ($clientesMascotas as $cliente): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['direccion']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['codigoMascota']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['nombreMascota']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['chip']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['entidadPeruana']); ?></td>
                        <td class="<?php echo $cliente['estado'] == 'activo' ? 'estado-activo' : 'estado-inactivo'; ?>"><?php echo htmlspecialchars($cliente['estado']); ?></td>
                        <td><a href="eliminar.php?id=<?php echo $cliente['id']; ?>"><button class="btn btn-eliminar">Eliminar</button></a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Si hay un mensaje de éxito, lo desvanecemos después de 3 segundos
        window.onload = function() {
            var message = document.getElementById('successMessage');
            if (message) {
                setTimeout(function() {
                    message.style.opacity = 0;
                }, 3000); // Después de 3 segundos, inicia la transición de opacidad
            }
        };
    </script>
</body>
</html>
