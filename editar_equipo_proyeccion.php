<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "incidenciasump";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del equipo de proyección a editar desde la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "ID no especificado.";
    exit();
}

// Obtener los datos actuales del equipo de proyección
$sql = "SELECT * FROM proyector WHERE eCodProyector = $id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $tMarcaProyector = $row['tMarcaProyector'];
    $tModeloProyector = $row['tModeloProyector'];
    $eNumeroInventarioProyector = $row['eNumeroInventarioProyector'];
    $eNumeroSerieProyector = $row['eNumeroSerieProyector'];
    $bEstadoProyector = $row['bEstadoProyector'];
} else {
    echo "No se encontraron resultados.";
    exit();
}

// Procesar el formulario de edición cuando se envíe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tMarcaProyector = $_POST['tMarcaProyector'];
    $tModeloProyector = $_POST['tModeloProyector'];
    $eNumeroInventarioProyector = $_POST['eNumeroInventarioProyector'];
    $eNumeroSerieProyector = $_POST['eNumeroSerieProyector'];
    $bEstadoProyector = isset($_POST['bEstadoProyector']) ? 1 : 0;

    $sql_update = "UPDATE proyector SET
                    tMarcaProyector = '$tMarcaProyector',
                    tModeloProyector = '$tModeloProyector',
                    eNumeroInventarioProyector = '$eNumeroInventarioProyector',
                    eNumeroSerieProyector = '$eNumeroSerieProyector',
                    bEstadoProyector = '$bEstadoProyector',
                    fhFechaHoraActualizacion = NOW()
                    WHERE eCodProyector = $id";

    if ($conn->query($sql_update) === TRUE) {
        echo '<script>
                alert("Equipo de proyección actualizado correctamente.");
                window.location.href = "registrar-equipo-proyeccion.php";
              </script>';
    } else {
        echo "Error al actualizar el equipo de proyección: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Equipo de Proyección</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="mb-4">Editar Equipo de Proyección</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="tMarcaProyector">Marca:</label>
                    <input type="text" class="form-control" id="tMarcaProyector" name="tMarcaProyector" value="<?php echo $tMarcaProyector; ?>" required>
                </div>
                <div class="form-group">
                    <label for="tModeloProyector">Modelo:</label>
                    <input type="text" class="form-control" id="tModeloProyector" name="tModeloProyector" value="<?php echo $tModeloProyector; ?>" required>
                </div>
                <div class="form-group">
                    <label for="eNumeroInventarioProyector">Número de Inventario:</label>
                    <input type="number" class="form-control" id="eNumeroInventarioProyector" name="eNumeroInventarioProyector" value="<?php echo $eNumeroInventarioProyector; ?>" required>
                </div>
                <div class="form-group">
                    <label for="eNumeroSerieProyector">Número de Serie:</label>
                    <input type="text" class="form-control" id="eNumeroSerieProyector" name="eNumeroSerieProyector" value="<?php echo $eNumeroSerieProyector; ?>" required>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="bEstadoProyector" name="bEstadoProyector" value="1" <?php if ($bEstadoProyector == 1) echo "checked"; ?>>
                    <label class="form-check-label" for="bEstadoProyector">Activo</label>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="ruta_a_tu_pagina.php" class="btn btn-secondary ml-2">Cancelar</a>
            </form>
        </div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
