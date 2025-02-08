<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "incidenciasump";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$eCodPuesto = $_GET['id'];

$sql = "SELECT bEstadoPuesto FROM puesto WHERE eCodPuesto = $eCodPuesto";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $estadoActual = $row['bEstadoPuesto'];

    if ($estadoActual) {
        $sql = "UPDATE puesto SET bEstadoPuesto = 0 WHERE eCodPuesto = $eCodPuesto";
        $mensaje = "Puesto desactivado exitosamente";
    } else {
        $sql = "UPDATE puesto SET bEstadoPuesto = 1 WHERE eCodPuesto = $eCodPuesto";
        $mensaje = "Puesto activado exitosamente";
    }

    if ($conn->query($sql) === TRUE) {
        echo $mensaje;
    } else {
        echo "Error al actualizar el estado del puesto: " . $conn->error;
    }
} else {
    echo "Puesto no encontrado";
}

$conn->close();

header("Location: registrar-puestos.php");
exit();
?>
