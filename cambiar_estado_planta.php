<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "incidenciasump";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$eCodPlanta = $_GET['id'];

$sql = "SELECT bEstadoPlanta FROM plantaedificio WHERE eCodPlanta = $eCodPlanta";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $estadoActual = $row['bEstadoPlanta'];

    if ($estadoActual) {
        $sql = "UPDATE plantaedificio SET bEstadoPlanta = 0 WHERE eCodPlanta = $eCodPlanta";
        $mensaje = "Planta desactivada exitosamente";
    } else {
        $sql = "UPDATE plantaedificio SET bEstadoPlanta = 1 WHERE eCodPlanta = $eCodPlanta";
        $mensaje = "Planta activada exitosamente";
    }

    if ($conn->query($sql) === TRUE) {
        echo $mensaje;
    } else {
        echo "Error al actualizar el estado de la planta: " . $conn->error;
    }
} else {
    echo "Planta no encontrada";
}

$conn->close();

header("Location: registrar-piso.php");
exit();
?>
