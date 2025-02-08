<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "incidenciasump";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Comprobar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id = $_POST['editar_eCodMicrofono'];
    $tMarcaMicrofono = $_POST['editar_tMarcaMicrofono'];
    $tModeloMicrofono = $_POST['editar_tModeloMicrofono'];
    $bTipoMicrofono = $_POST['editar_bTipoMicrofono'];
    $eNumeroInventarioMicrofono = $_POST['editar_eNumeroInventarioMicrofono'];
    $eNumeroSerieMicrofono = $_POST['editar_eNumeroSerieMicrofono'];
    $bEstadoMicrofono = isset($_POST['editar_bEstadoMicrofono']) ? 1 : 0;
    $fhFechaHoraActualizacionMicrofono = $_POST['editar_fhFechaHoraActualizacionMicrofono'];

    // Crear la consulta SQL para actualizar el registro
    $sql = "UPDATE microfono SET
                tMarcaMicrofono='$tMarcaMicrofono',
                tModeloMicrofono='$tModeloMicrofono',
                bTipoMicrofono='$bTipoMicrofono',
                eNumeroInventarioMicrofono='$eNumeroInventarioMicrofono',
                eNumeroSerieMicrofono='$eNumeroSerieMicrofono',
                bEstadoMicrofono='$bEstadoMicrofono',
                fhFechaHoraActualizacionMicrofono='$fhFechaHoraActualizacionMicrofono'
            WHERE eCodMicrofono=$id";

    // Ejecutar la consulta y comprobar si se ha actualizado correctamente
    if ($conn->query($sql) === TRUE) {
        echo '<script>
                $(document).ready(function() {
                    $("#successModalEditar").modal("show");
                    setTimeout(function(){
                        window.location.href = "index.php";
                    }, 1000);
                });
              </script>';
    } else {
        echo "<p>Error al actualizar el micrófono: " . $conn->error . "</p>";
    }
}

// Cerrar conexión
$conn->close();
?>
