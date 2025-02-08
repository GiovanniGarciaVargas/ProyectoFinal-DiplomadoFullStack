<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "incidenciasump";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$eCodCamaraWeb = $_GET['id'];

$sql = "SELECT bEstadoCamara FROM camaraweb WHERE eCodCamaraWeb = $eCodCamaraWeb";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $estadoActual = $row['bEstadoCamara'];

    if ($estadoActual) {
        $sql = "UPDATE camaraweb SET bEstadoCamara = 0 WHERE eCodCamaraWeb = $eCodCamaraWeb";
        $mensaje = "Cámara web desactivada exitosamente";
    } else {
        $sql = "UPDATE camaraweb SET bEstadoCamara = 1 WHERE eCodCamaraWeb = $eCodCamaraWeb";
        $mensaje = "Cámara web activada exitosamente";
    }

    if ($conn->query($sql) === TRUE) {
        echo $mensaje;
    } else {
        echo "Error al actualizar el estado de la cámara web: " . $conn->error;
    }
} else {
    echo "Cámara web no encontrada";
}

$conn->close();

header("Location: registrar-camara-web.php");
exit();
?>
