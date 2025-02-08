<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "incidenciasump";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$eCodPantallaProyeccion = $_GET['id'];

$sql = "SELECT bEstadoPantalla FROM pantallaproyeccion WHERE eCodPantallaProyeccion = $eCodPantallaProyeccion";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $estadoActual = $row['bEstadoPantalla'];

    if ($estadoActual) {
        $sql = "UPDATE pantallaproyeccion SET bEstadoPantalla = 0 WHERE eCodPantallaProyeccion = $eCodPantallaProyeccion";
        $mensaje = "Pantalla de proyección desactivada exitosamente";
    } else {
        $sql = "UPDATE pantallaproyeccion SET bEstadoPantalla = 1 WHERE eCodPantallaProyeccion = $eCodPantallaProyeccion";
        $mensaje = "Pantalla de proyección activada exitosamente";
    }

    if ($conn->query($sql) === TRUE) {
        echo $mensaje;
    } else {
        echo "Error al actualizar el estado de la pantalla de proyección: " . $conn->error;
    }
} else {
    echo "Pantalla de proyección no encontrada";
}

$conn->close();

header("Location: registrar-pantalla-proyeccion.php");
exit();
?>