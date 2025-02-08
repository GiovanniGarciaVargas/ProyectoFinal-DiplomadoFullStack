<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "incidenciasump";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$eCodEquipoComputo = $_GET['id'];

$sql = "SELECT bEstadoEquipoComputo FROM equipocomputo WHERE eCodEquipoComputo = $eCodEquipoComputo";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $estadoActual = $row['bEstadoEquipoComputo'];

    if ($estadoActual) {
        $sql = "UPDATE equipocomputo SET bEstadoEquipoComputo = 0 WHERE eCodEquipoComputo = $eCodEquipoComputo";
        $mensaje = "Equipo de computo desactivado exitosamente";
    } else {
        $sql = "UPDATE equipocomputo SET bEstadoEquipoComputo = 1 WHERE eCodEquipoComputo = $eCodEquipoComputo";
        $mensaje = "Equipo de computo activado exitosamente";
    }

    if ($conn->query($sql) === TRUE) {
        echo $mensaje;
    } else {
        echo "Error al actualizar el estado del equipo de computo: " . $conn->error;
    }
} else {
    echo "Equipo de computo no encontrado";
}

$conn->close();

header("Location: registrar-equipo-computo.php");
exit();
?>
