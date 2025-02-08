</body>	
 <!-- Modal de Agregar Puesto -->
 <!-- Modal para Agregar Puesto -->
 <div id="myModalPuestos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Nuevo Puesto</h5>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="puestoForm" method="POST" action="">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="tNombrePuesto">Nombre del Puesto:</label>
                            <input type="text" class="form-control" id="tNombrePuesto" name="tNombrePuesto" required>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="bEstadoPuesto" name="bEstadoPuesto" value="1" checked>
                            <label class="form-check-label" for="bEstadoPuesto">Activo</label>
                        </div>
                        <input type="hidden" id="fhFechaHoraCreacionPuesto" name="fhFechaHoraCreacionPuesto" value="<?php echo date('Y-m-d H:i:s'); ?>">
                        <input type="hidden" id="fhFechaHoraActualizacionPuesto" name="fhFechaHoraActualizacionPuesto" value="<?php echo date('Y-m-d H:i:s'); ?>">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal de Registro Exitoso -->
    <div id="successModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Registro Exitoso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p>Nuevo puesto creado exitosamente</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

<!--- aquí concluye puestos -->
<!---- USUARIOS ---->


<div id="myModal" class="modal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userForm" method="POST">
                    <div class="mb-3">
                        <label for="tNombreUsuario" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" id="tNombreUsuario" name="tNombreUsuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="tApellidoPaterno" class="form-label">Apellido Paterno:</label>
                        <input type="text" class="form-control" id="tApellidoPaterno" name="tApellidoPaterno" required>
                    </div>
                    <div class="mb-3">
                        <label for="tApellidoMaterno" class="form-label">Apellido Materno:</label>
                        <input type="text" class="form-control" id="tApellidoMaterno" name="tApellidoMaterno" required>
                    </div>
                    <div class="mb-3">
                        <label for="tCorreoUsuario" class="form-label">Correo Electrónico:</label>
                        <input type="email" class="form-control" id="tCorreoUsuario" name="tCorreoUsuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="tContrasena" class="form-label">Contraseña:</label>
                        <input type="password" class="form-control" id="tContrasena" name="tContrasena" required>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="bEstadoUsuario" name="bEstadoUsuario">
                        <label class="form-check-label" for="bEstadoUsuario">Estado</label>
                    </div>
                    <div class="mb-3">
                        <label for="fk_eCodPuesto" class="form-label">Puesto:</label>
                        <select class="form-select" id="fk_eCodPuesto" name="fk_eCodPuesto" required>
                            <?php
                            $servername = "localhost";
                            $username = "root";
                            $password = "root";
                            $dbname = "incidenciasump";
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("La conexión falló: " . $conn->connect_error);
                            }
                            $sql = "SELECT eCodPuesto, tNombrePuesto FROM puesto WHERE bEstadoPuesto = 1 ORDER BY tNombrePuesto DESC";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    $eCodPuesto = $row['eCodPuesto'];
                                    $tNombrePuesto = $row['tNombrePuesto'];
                                    echo "<option value='$eCodPuesto'>$tNombrePuesto</option>";
                                }
                            } else {
                                echo "<option value='' disabled selected>No hay puestos disponibles</option>";
                            }
                            $conn->close();
                            ?>
                        </select>
                    </div>
                    <input type="hidden" id="fhFechaHoraCreacionUsuario" name="fhFechaHoraCreacionUsuario">
                    <input type="hidden" id="fhFechaHoraActualizacionUsuario" name="fhFechaHoraActualizacionUsuario">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Registro exitoso</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>Nuevo registro creado exitosamente</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!--- aquí concluye usuarios -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var modal = document.getElementById("myModal");
    var btn = document.getElementById("openModal");
    var span = document.getElementsByClassName("close")[0];
    btn.onclick = function() {
        modal.style.display = "block";
    }
    span.onclick = function() {
        modal.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    document.getElementById('userForm').onsubmit = function(event) {
        var fechaHoraActual = new Date().toISOString().slice(0, 19).replace('T', ' ');
        document.getElementById('fhFechaHoraCreacionUsuario').value = fechaHoraActual;
        document.getElementById('fhFechaHoraActualizacionUsuario').value = fechaHoraActual;
    }
</script>
<script src="./vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="./vendor/animsition/js/animsition.min.js"></script>
<script src="./vendor/bootstrap/js/popper.js"></script>
<script src="./vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="./vendor/select2/select2.min.js"></script>
<script src="./vendor/daterangepicker/moment.min.js"></script>
<script src="./vendor/daterangepicker/daterangepicker.js"></script>
<script src="./vendor/countdowntime/countdowntime.js"></script>
<script src="./js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    togglePassword.addEventListener('click', function (e) {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
    });
</script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

<script>
        $(document).ready(function() {
            $('#tabla_usuarios').DataTable();
            
            $('#tabla_puestos').DataTable();
            $('#tabla_equipo').DataTable();
            $('#tabla_equipos').DataTable();
            $('#tabla_pantallas').DataTable();
            $('#tabla_microfonos').DataTable();
            $('#tabla_aires').DataTable();
            $('#tabla_camaras').DataTable();
            $('#tabla_reguladores').DataTable();
            $('#tabla_plantas').DataTable();
            $('#tabla_aulas').DataTable();
            $('#tabla_asignaciones').DataTable();
            $('#tabla_incidencias').DataTable();
        });

</script>

</html>