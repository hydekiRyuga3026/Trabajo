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

    // Verificar si se envió el formulario con los datos
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

        // Redirigir a inicio.php después de agregar los datos
        header("Location: inicio.php?success=true");
        exit;
    }
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
    <title>Agregar Cliente / Mascota</title>
    <style>
        body {
            background-color: #4A6C8C; /* Fondo azul intermedio */
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #ffffff; /* Fondo blanco del formulario */
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 700px; /* Caja más grande */
            max-width: 100%; /* No exceder el 100% del ancho de la pantalla */
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h3 {
            text-align: center;
            color: #1e3d58;
        }

        label {
            font-size: 16px;
            margin-bottom: 5px;
            display: block;
            color: #333;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            gap: 20px; /* Separación entre las columnas */
            width: 100%;
            margin-bottom: 15px;
        }

        .form-row div {
            width: 45%; /* Cada campo ocupará el 45% del contenedor */
        }

        .form-row input {
            width: 100%; /* Los campos de entrada ocuparán el 100% del contenedor */
        }

        input {
            padding: 12px; /* Aumentar el tamaño del padding */
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px; /* Aumentar el tamaño de la fuente */
        }

        button {
            padding: 12px 15px;
            background-color: #1e3d58;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #365c74;
        }

        .cancel-button {
            background-color: #f44336; /* Rojo para el botón cancelar */
            width: 220px; /* Hacer el botón "Cancelar" más ancho */
            padding: 15px 20px; /* Mayor altura en el botón */
        }

        .cancel-button:hover {
            background-color: #d32f2f;
        }

        .button-container {
            display: flex;
            justify-content: center; /* Centrar los botones */
            gap: 15px; /* Espacio entre los botones */
            width: 100%;
        }

        .add-button {
            width: 220px; /* Hacer el botón "Agregar" más ancho */
            padding: 15px 20px; /* Mayor altura en el botón */
        }

        /* Hacer que el formulario ocupe el 100% del ancho de la caja */
        form {
            width: 100%; /* El formulario ocupará el 100% del contenedor */
        }

    </style>
</head>
<body>
    <div class="form-container">
        <h3>Agregar Cliente / Mascota</h3>
        <form action="formulario.php" method="POST">
            <div class="form-row">
                <div>
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div>
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" required>
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" required>
                </div>
                <div>
                    <label for="codigoMascota">Código de Mascota:</label>
                    <input type="text" id="codigoMascota" name="codigoMascota" required>
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label for="nombreMascota">Nombre de la Mascota:</label>
                    <input type="text" id="nombreMascota" name="nombreMascota" required>
                </div>
                <div>
                    <label for="chip">Chip:</label>
                    <input type="text" id="chip" name="chip" required>
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label for="entidadPeruana">Entidad Peruana:</label>
                    <input type="text" id="entidadPeruana" name="entidadPeruana" required>
                </div>
            </div>

            <div class="button-container">
                <button type="submit" class="add-button">Agregar</button>
                <a href="inicio.php"><button type="button" class="cancel-button">Cancelar</button></a>
            </div>
        </form>
    </div>
</body>
</html>
