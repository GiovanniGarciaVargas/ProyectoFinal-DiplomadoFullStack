<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "incidenciasump";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $eCodPuesto = $_GET['id'];
    $sql = "SELECT * FROM puesto WHERE eCodPuesto = '$eCodPuesto'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $puesto = $result->fetch_assoc();
        echo json_encode($puesto);
    } else {
        echo json_encode(['error' => 'No se encontró el puesto']);
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eCodPuesto = $_POST['eCodPuesto'];
    $tNombrePuesto = $_POST['tNombrePuesto'];
    $bEstadoPuesto = isset($_POST['bEstadoPuesto']) ? 1 : 0;
    $fhFechaHoraActualizacionPuesto = date('Y-m-d H:i:s');

    $sql = "UPDATE puesto SET tNombrePuesto='$tNombrePuesto', bEstadoPuesto='$bEstadoPuesto', fhFechaHoraActualizacionPuesto='$fhFechaHoraActualizacionPuesto' WHERE eCodPuesto='$eCodPuesto'";

    if ($conn->query($sql) === TRUE) {
        echo "Puesto actualizado correctamente";
    } else {
        echo "Error al actualizar el puesto: " . $conn->error;
    }
}

$conn->close();
?>
