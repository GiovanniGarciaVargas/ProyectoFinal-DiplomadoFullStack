<?php
// obtener-equipo-audio.php

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $id = $_GET["id"];

    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "incidenciasump";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Consulta SQL para obtener los datos del equipo de audio por su ID
    $sql = "SELECT * FROM equipoaudio WHERE eCodAmplificador = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontraron resultados
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Devolver los datos como un objeto JSON
        echo json_encode($row);
    } else {
        echo json_encode(array("error" => "No se encontró el equipo de audio"));
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(array("error" => "Parámetros incorrectos"));
}
?>
