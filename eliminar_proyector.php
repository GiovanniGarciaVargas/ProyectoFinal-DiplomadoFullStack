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

// Obtener el ID del equipo de proyección a eliminar desde la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    echo "ID no especificado.";
    exit();
}

// Eliminar el equipo de proyección de la base de datos
$sql = "DELETE FROM proyector WHERE eCodProyector = $id";

if ($conn->query($sql) === TRUE) {
    echo '<script>
            alert("Equipo de proyección eliminado correctamente.");
            window.location.href = "registrar-equipo-proyeccion.php";
          </script>';
} else {
    echo "Error al eliminar el equipo de proyección: " . $conn->error;
}

$conn->close();
?>
