<!DOCTYPE html>
<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username']) && !strpos($_SERVER['PHP_SELF'],'index.php')) {
  header("Location: index.php");
  exit();
}

// Mostrar los datos del usuario si está autenticado
if (isset($_SESSION['username'])) {
  $eCodUsuario = $_SESSION['eCodUsuario'];
  $nombre = $_SESSION['nombre'];
  $apellidoPaterno = $_SESSION['apellidoPaterno'];
  $apellidoMaterno = $_SESSION['apellidoMaterno'];
  $puesto = $_SESSION['puesto'];
}

// Cerrar sesión si se hace clic en el botón de cerrar sesión
if (isset($_GET['cerrar_sesion'])) {
  session_destroy();
  header("Location: index.php");
  exit();
}
?>
  <title>Sistema de Incidencias - UMP</title>
  
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
<link rel="icon" type="image/png" href="https://ump.edu.mx/wp-content/uploads/2023/03/LOGO-CIRCULAR-1024x769.png" sizes="32x32">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
  <link rel="stylesheet" type="text/css" href="css/sidebar.css">
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
<!--===============================================================================================-->
</head>

<body>
	
