<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "incidenciasump";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$eCodProyector = $_GET['id'];

$sql = "SELECT bEstadoProyector FROM proyector WHERE eCodProyector = $eCodProyector";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $estadoActual = $row['bEstadoProyector'];

    if ($estadoActual) {
        $sql = "UPDATE proyector SET bEstadoProyector = 0 WHERE eCodProyector = $eCodProyector";
        $mensaje = "Proyector desactivado exitosamente";
    } else {
        $sql = "UPDATE proyector SET bEstadoProyector = 1 WHERE eCodProyector = $eCodProyector";
        $mensaje = "Proyector activado exitosamente";
    }

    if ($conn->query($sql) === TRUE) {
        echo $mensaje;
    } else {
        echo "Error al actualizar el estado del proyector: " . $conn->error;
    }
} else {
    echo "Planta no encontrada";
}

$conn->close();

header("Location: registrar-equipo-proyeccion.php");
exit();
?>
