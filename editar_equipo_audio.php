<?php
// editar_equipo_audio.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que se recibieron los datos del formulario
    if (isset($_POST["id"], $_POST["tMarcaAmplificador"], $_POST["tModeloAmplificador"], $_POST["eNumeroInventarioAmplificador"], $_POST["eNumeroSerieAmplificador"], $_POST["bEstadoAmplificador"])) {
        $id = $_POST["id"];
        $marca = $_POST["tMarcaAmplificador"];
        $modelo = $_POST["tModeloAmplificador"];
        $numInventario = $_POST["eNumeroInventarioAmplificador"];
        $numSerie = $_POST["eNumeroSerieAmplificador"];
        $estado = $_POST["bEstadoAmplificador"];

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

        // Consulta SQL para actualizar los datos del equipo de audio
        $sql = "UPDATE equipoaudio SET tMarcaAmplificador = ?, tModeloAmplificador = ?, eNumeroInventarioAmplificador = ?, eNumeroSerieAmplificador = ?, bEstadoAmplificador = ? WHERE eCodAmplificador = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiii", $marca, $modelo, $numInventario, $numSerie, $estado, $id);

        if ($stmt->execute()) {
            echo "¡Datos del equipo de audio actualizados correctamente!";
        } else {
            echo "Error al actualizar los datos del equipo de audio: " . $stmt->error;
        }

        // Cerrar la conexión
        $stmt->close();
        $conn->close();
    } else {
        echo "Parámetros incorrectos para actualizar el equipo de audio";
    }
} else {
    echo "Método de solicitud incorrecto para actualizar el equipo de audio";
}
?>
