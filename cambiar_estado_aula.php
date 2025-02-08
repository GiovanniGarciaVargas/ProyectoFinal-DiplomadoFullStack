<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "incidenciasump";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$eCodAula = $_GET['id'];

$sql = "SELECT bEstadoAula FROM aula WHERE eCodAula = $eCodAula";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $estadoActual = $row['bEstadoAula'];

    if ($estadoActual) {
        $sql = "UPDATE aula SET bEstadoAula = 0 WHERE eCodAula = $eCodAula";
        $mensaje = "Aula desactivada exitosamente";
    } else {
        $sql = "UPDATE aula SET bEstadoAula = 1 WHERE eCodAula = $eCodAula";
        $mensaje = "Aula activada exitosamente";
    }

    if ($conn->query($sql) === TRUE) {
        echo $mensaje;
    } else {
        echo "Error al actualizar el estado del aula: " . $conn->error;
    }
} else {
    echo "Aula no encontrada";
}

$conn->close();

header("Location: registrar-aula.php");
exit();
?>
