<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "incidenciasump";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$eCodAsignacion = $_GET['id']; // ID de la asignación que viene por GET

$sql = "SELECT bEstadoAsignacion FROM asignacionaulas WHERE eCodAsignacion = $eCodAsignacion";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $estadoActual = $row['bEstadoAsignacion'];

    // Invertir el estado actual
    $nuevoEstado = $estadoActual ? 0 : 1;

    // Actualizar el estado en la base de datos
    $sqlUpdate = "UPDATE asignacionaulas SET bEstadoAsignacion = $nuevoEstado WHERE eCodAsignacion = $eCodAsignacion";

    if ($conn->query($sqlUpdate) === TRUE) {
        if ($nuevoEstado == 1) {
            $mensaje = "Asignación activada exitosamente";
        } else {
            $mensaje = "Asignación desactivada exitosamente";
        }
        echo $mensaje;
    } else {
        echo "Error al actualizar el estado de la asignación: " . $conn->error;
    }
} else {
    echo "Asignación no encontrada";
}

$conn->close();

header("Location: asignar-elementos-aula.php");
exit();
?>
