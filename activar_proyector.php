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

// Obtener el ID del equipo de proyección a activar/desactivar desde la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "ID no especificado.";
    exit();
}

// Obtener el estado actual del equipo de proyección
$sql_estado = "SELECT bEstadoProyector FROM proyector WHERE eCodProyector = $id";
$result = $conn->query($sql_estado);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $estadoActual = $row['bEstadoProyector'];

    // Cambiar el estado
    $nuevoEstado = $estadoActual == 1 ? 0 : 1;
    $sql_update_estado = "UPDATE proyector SET bEstadoProyector = $nuevoEstado WHERE eCodProyector = $id";

    if ($conn->query($sql_update_estado) === TRUE) {
        echo '<script>
                alert("Estado del equipo de proyección actualizado correctamente.");
                window.location.href = "registrar-equipo-proyector.php";
              </script>';
    } else {
        echo "Error al actualizar el estado del equipo de proyección: " . $conn->error;
    }
} else {
    echo "No se encontró el equipo de proyección.";
}

$conn->close();
?>
