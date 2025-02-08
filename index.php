<?php
session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "incidenciasump";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['pass'];

    $sql = "SELECT eCodUsuario, tContrasena, tNombreUsuario, tApellidoPaterno, tApellidoMaterno, fk_eCodPuesto, bEstadoUsuario
            FROM usuarios
            WHERE tCorreoUsuario = ?";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . $conn->error);
    }

    $stmt->bind_param("s", $inputUsername);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($eCodUsuario, $hashedPassword, $tNombreUsuario, $tApellidoPaterno, $tApellidoMaterno, $fk_eCodPuesto, $bEstadoUsuario);
        $stmt->fetch();

        if ($bEstadoUsuario == 1) {
            if (password_verify($inputPassword, $hashedPassword)) {
                $_SESSION['eCodUsuario'] = $eCodUsuario; // Agregando el eCodUsuario a la sesión
                $_SESSION['username'] = $inputUsername;
                $_SESSION['nombre'] = $tNombreUsuario;
                $_SESSION['apellidoPaterno'] = $tApellidoPaterno;
                $_SESSION['apellidoMaterno'] = $tApellidoMaterno;
                $_SESSION['puesto'] = $fk_eCodPuesto;

                header("Location: principal.php");
                exit;
            } else {
                echo "<p>Contraseña incorrecta</p>";
            }
        } else {
            echo "<p>Usuario inactivo. Por favor, contacte al administrador.</p>";
        }
    } else {
        echo "<p>Usuario no encontrado</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include("header.php"); ?>

    <div class="limiter">
        <div class="container-login100" style="background-image: url('https://ump.edu.mx/wp-content/uploads/2023/03/LOGO-CIRCULAR-1024x769.png');">
            <div class="wrap-login100">
                <form class="login100-form validate-form" method="POST" action="">
                    <span class="login100-form-logo">
                        <i class="zmdi zmdi-landscape"></i>
                    </span>

                    <span class="login100-form-title p-b-34 p-t-27">
                        Inicio de Sesión
                    </span>

                    <div class="wrap-input100 validate-input" data-validate="Enter username">
                        <input class="input100" type="text" name="username" placeholder="Username" required>
                        <span class="focus-input100" data-placeholder="&#xf207;"></span>
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <input class="input100" type="password" name="pass" id="password" placeholder="Password" required>
                        <span class="focus-input100" data-placeholder="&#xf191;"></span>
                        <span class="btn-show-pass">
                            <i class="fas fa-eye" id="togglePassword"></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button type="submit" class="login100-form-btn">
                            Login
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>
