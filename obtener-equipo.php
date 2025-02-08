<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "incidenciasump";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "SELECT * FROM equipocomputo WHERE eCodEquipoComputo=$id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['error' => 'Equipo no encontrado']);
    }
}
$conn->close();
?>
