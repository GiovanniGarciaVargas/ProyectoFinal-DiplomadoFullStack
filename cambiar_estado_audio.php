<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "incidenciasump";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$eCodEquipoAudio = $_GET['id'];

$sql = "SELECT bEstadoAmplificador FROM equipoaudio WHERE eCodAmplificador = $eCodEquipoAudio";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $estadoActual = $row['bEstadoAmplificador'];

    if ($estadoActual) {
        $sql = "UPDATE equipoaudio SET bEstadoAmplificador = 0 WHERE eCodAmplificador = $eCodEquipoAudio";
        $mensaje = "Equipo de audio desactivado exitosamente";
    } else {
        $sql = "UPDATE equipoaudio SET bEstadoAmplificador = 1 WHERE eCodAmplificador = $eCodEquipoAudio";
        $mensaje = "Equipo de audio activado exitosamente";
    }

    if ($conn->query($sql) === TRUE) {
        echo $mensaje;
    } else {
        echo "Error al actualizar el estado del equipo de audio: " . $conn->error;
    }
} else {
    echo "Equipo de audio no encontrado";
}

$conn->close();

header("Location: registrar-equipo-audio.php");
exit();
?>