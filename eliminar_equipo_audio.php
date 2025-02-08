<?php
// Archivo: eliminar_equipo_audio.php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Conexión a la base de datos (pon aquí tus datos de conexión)
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "incidenciasump";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    
    // Sentencia SQL para eliminar el equipo de audio
    $sql = "DELETE FROM equipoaudio WHERE eCodAmplificador = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "Equipo de audio eliminado correctamente.";
    } else {
        echo "Error al eliminar el equipo de audio.";
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "Error: No se proporcionó un ID válido.";
}
?>
