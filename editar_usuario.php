<?php
$servername = "localhost";  
$username = "root";         
$password = "root";          
$dbname = "incidenciasump";  
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eCodUsuario = $_POST['eCodUsuario'];
    $tNombreUsuario = $_POST['tNombreUsuario'];
    $tApellidoPaterno = $_POST['tApellidoPaterno'];
    $tApellidoMaterno = $_POST['tApellidoMaterno'];
    $tCorreoUsuario = $_POST['tCorreoUsuario'];
    $tContrasena = !empty($_POST['tContrasena']) ? password_hash($_POST['tContrasena'], PASSWORD_DEFAULT) : null;
    $bEstadoUsuario = isset($_POST['bEstadoUsuario']) ? 1 : 0;
    $fk_eCodPuesto = $_POST['fk_eCodPuesto'];
    $fhFechaHoraActualizacionUsuario = date('Y-m-d H:i:s'); // Actualizar con la fecha actual

    if ($tContrasena) {
        $sql = "UPDATE usuarios SET 
                tNombreUsuario = '$tNombreUsuario', 
                tApellidoPaterno = '$tApellidoPaterno', 
                tApellidoMaterno = '$tApellidoMaterno', 
                tCorreoUsuario = '$tCorreoUsuario', 
                tContrasena = '$tContrasena', 
                bEstadoUsuario = '$bEstadoUsuario', 
                fk_eCodPuesto = '$fk_eCodPuesto', 
                fhFechaHoraActualizacionUsuario = '$fhFechaHoraActualizacionUsuario'
                WHERE eCodUsuario = '$eCodUsuario'";
    } else {
        $sql = "UPDATE usuarios SET 
                tNombreUsuario = '$tNombreUsuario', 
                tApellidoPaterno = '$tApellidoPaterno', 
                tApellidoMaterno = '$tApellidoMaterno', 
                tCorreoUsuario = '$tCorreoUsuario', 
                bEstadoUsuario = '$bEstadoUsuario', 
                fk_eCodPuesto = '$fk_eCodPuesto', 
                fhFechaHoraActualizacionUsuario = '$fhFechaHoraActualizacionUsuario'
                WHERE eCodUsuario = '$eCodUsuario'";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Usuario actualizado correctamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
