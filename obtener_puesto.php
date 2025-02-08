<?php
$servername = "localhost";  
$username = "root";         
$password = "root";          
$dbname = "incidenciasump";  
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT * FROM puesto";
$result = $conn->query($sql);
$puestos = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $puestos[] = $row;
    }
}

echo json_encode($puestos);

$conn->close();
?>
