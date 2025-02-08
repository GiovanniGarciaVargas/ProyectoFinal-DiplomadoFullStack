<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "incidenciasump";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$eCodRegulador = $_GET['id'];

$sql = "SELECT bEstadoRegulador FROM regulador WHERE eCodRegulador = $eCodRegulador";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $estadoActual = $row['bEstadoRegulador'];

    if ($estadoActual) {
        $sql = "UPDATE regulador SET bEstadoRegulador = 0 WHERE eCodRegulador = $eCodRegulador";
        $mensaje = "Regulador desactivado exitosamente";
    } else {
        $sql = "UPDATE regulador SET bEstadoRegulador = 1 WHERE eCodRegulador = $eCodRegulador";
        $mensaje = "Regulador activado exitosamente";
    }

    if ($conn->query($sql) === TRUE) {
        echo $mensaje;
    } else {
        echo "Error al actualizar el estado del regulador: " . $conn->error;
    }
} else {
    echo "Regulador no encontrado";
}

$conn->close();

header("Location: registrar-regulador.php");
exit();
?>
