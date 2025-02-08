<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "incidenciasump";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$eCodAireAcondicionado = $_GET['id'];

$sql = "SELECT bEstadoAire FROM aireacondicionado WHERE eCodAireAcondicionado = $eCodAireAcondicionado";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $estadoActual = $row['bEstadoAire'];

    if ($estadoActual) {
        $sql = "UPDATE aireacondicionado SET bEstadoAire = 0 WHERE eCodAireAcondicionado = $eCodAireAcondicionado";
        $mensaje = "Aire acondicionado desactivado exitosamente";
    } else {
        $sql = "UPDATE aireacondicionado SET bEstadoAire = 1 WHERE eCodAireAcondicionado = $eCodAireAcondicionado";
        $mensaje = "Aire acondicionado activado exitosamente";
    }

    if ($conn->query($sql) === TRUE) {
        echo $mensaje;
    } else {
        echo "Error al actualizar el estado del aire acondicionado: " . $conn->error;
    }
} else {
    echo "Aire acondicionado no encontrado";
}

$conn->close();

header("Location: registrar-aire-acondicionado.php");
exit();
?>
