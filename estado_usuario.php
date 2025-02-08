<?php
$servername = "localhost";  
$username = "root";         
$password = "root";          
$dbname = "incidenciasump";  

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del usuario
$eCodUsuario = $_GET['id'];

// Verificar el estado actual del usuario
$sql = "SELECT bEstadoUsuario FROM usuarios WHERE eCodUsuario = $eCodUsuario";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $estadoActual = $row['bEstadoUsuario'];

    // Determinar la nueva acción a realizar (activar o desactivar)
    if ($estadoActual) {
        // El usuario está activo, desactivarlo
        $sql = "UPDATE usuarios SET bEstadoUsuario = 0 WHERE eCodUsuario = $eCodUsuario";
        $mensaje = "Usuario desactivado exitosamente";
    } else {
        // El usuario está inactivo, activarlo
        $sql = "UPDATE usuarios SET bEstadoUsuario = 1 WHERE eCodUsuario = $eCodUsuario";
        $mensaje = "Usuario activado exitosamente";
    }

    // Ejecutar la actualización
    if ($conn->query($sql) === TRUE) {
        echo $mensaje;
    } else {
        echo "Error al actualizar el estado del usuario: " . $conn->error;
    }
} else {
    echo "Usuario no encontrado";
}

$conn->close();

// Redirigir de vuelta a la página de la tabla de usuarios
header("Location: registrar-usuario.php");
exit();
?>
