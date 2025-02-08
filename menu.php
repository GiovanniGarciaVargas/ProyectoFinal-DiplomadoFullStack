<?php include("header.php"); ?>
<meta charset="UTF-8">

<nav class="main-menu">
    <div class="logo-details">
        <img src="https://ump.edu.mx/wp-content/uploads/2023/03/LOGO-CIRCULAR-1024x769.png" alt="Logo" class="img-fluid logo-toggle">
    </div>
    <ul>
        <li>
            <a href="registrar-usuario.php">
                <i class="fa fa-user-circle"></i>
                <span class="nav-text">Usuarios</span>
            </a>
        </li>
        <li>
            <a href="registrar-puestos.php">
                <i class="fa fa-black-tie"></i>
                <span class="nav-text">Puestos</span>
            </a>
        </li>
        <li>
            <a href="registrar-equipo-computo.php">
                <i class="fa fa-desktop"></i>
                <span class="nav-text">Equipo de Cómputo</span>
            </a>
        </li>
        <li>
            <a href="registrar-equipo-audio.php">
                <i class="fa fa-volume-up"></i>
                <span class="nav-text">Equipo de Audio</span>
            </a>
        </li>
        <li>
            <a href="registrar-pantalla-proyeccion.php">
                <i class="fa fa-cc-discover"></i>
                <span class="nav-text">Pantalla de Proyección</span>
            </a>
        </li>
        <li>
            <a href="registrar-microfono.php">
                <i class="fa fa-microphone"></i>
                <span class="nav-text">Micrófono</span>
            </a>
        </li>
        <li>
            <a href="registrar-equipo-proyeccion.php">
                <i class="fa fa-cc-discover"></i>
                <span class="nav-text">Proyectores</span>
            </a>
        </li>
        <li>
            <a href="registrar-aire-acondicionado.php">
                <i class="fa fa-snowflake-o"></i>
                <span class="nav-text">Aire Acondicionado</span>
            </a>
        </li>
        <li>
            <a href="registrar-camara-web.php">
                <i class="fa fa-camera"></i>
                <span class="nav-text">Cámara Web</span>
            </a>
        </li>
        <li>
            <a href="registrar-regulador.php">
                <i class="fa fa-bolt"></i>
                <span class="nav-text">Regulador</span>
            </a>
        </li>
        <li>
            <a href="registrar-piso.php">
                <i class="fa fa-home"></i>
                <span class="nav-text">Pisos</span>
            </a>
        </li>
        <li>
            <a href="registrar-aula.php">
                <i class="fa fa-universal-access"></i>
                <span class="nav-text">Aulas</span>
            </a>
        </li>
        <li>
            <a href="asignar-elementos-aula.php">
                <i class="fa fa-object-group fa-2x"></i>
                <span class="nav-text">Asignar elementos a aulas</span>
            </a>
        </li>
        <li>
            <a href="registrar-incidencias.php">
                <i class="fa fa-bar-chart fa-2x"></i>
                <span class="nav-text">Reportar Incidencias</span>
            </a>
        </li>
        <li>
            <a href="#">
                <i class="fa fa-pie-chart fa-2x"></i>
                <span class="nav-text">Visualizar Reportes Generales</span>
            </a>
        </li>
    </ul>

    <ul class="logout">
        <li>
            <a href="#">
                <i class="fa fa-power-off fa-2x" onclick="document.getElementById('logout-form').submit();"></i>
                <span class="nav-text" onclick="document.getElementById('logout-form').submit();">Cerrar sesión</span>
                <form id="logout-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" style="display: none;">
                    <input type="hidden" name="cerrar_sesion">
                </form>
            </a>
        </li>
    </ul>
</nav>

<div class="container-fluid">
    <br />
    <div class="text-right">
        <h2>Bienvenido: <?php echo $nombre . " " . $apellidoPaterno . " " . $apellidoMaterno; ?></h2>
    </div>
</div>
<?php include("footer.php"); ?>
