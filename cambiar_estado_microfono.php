<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "incidenciasump";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$eCodMicrofono = $_GET['id'];

$sql = "SELECT bEstadoMicrofono FROM microfono WHERE eCodMicrofono = $eCodMicrofono";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $estadoActual = $row['bEstadoMicrofono'];

    if ($estadoActual) {
        $sql = "UPDATE microfono SET bEstadoMicrofono = 0 WHERE eCodMicrofono = $eCodMicrofono";
        $mensaje = "Micrófono desactivado exitosamente";
    } else {
        $sql = "UPDATE microfono SET bEstadoMicrofono = 1 WHERE eCodMicrofono = $eCodMicrofono";
        $mensaje = "Micrófono activado exitosamente";
    }

    if ($conn->query($sql) === TRUE) {
        echo $mensaje;
    } else {
        echo "Error al actualizar el estado del micrófono: " . $conn->error;
    }
} else {
    echo "Micrófono no encontrado";
}

$conn->close();

header("Location: registrar-microfono.php");
exit();
?>