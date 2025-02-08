<?php include("header.php"); ?>
<?php include("menu.php"); ?>
<?php
session_start(); // Inicia la sesión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "incidenciasump";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener valores del formulario
    $fk_eCodProyector = $_POST['fk_eCodProyector'];
    $fk_eCodAireAcondicionado = $_POST['fk_eCodAireAcondicionado'];
    $fk_eCodAmplificador = $_POST['fk_eCodAmplificador'];
    $fk_eCodPantallaProyeccion = $_POST['fk_eCodPantallaProyeccion'];
    $fk_eCodAula = $_POST['fk_eCodAula'];
    $fk_eCodMicrofono = $_POST['fk_eCodMicrofono'];
    $fk_eCodEquipoComputo = $_POST['fk_eCodEquipoComputo'];
    $fk_eCodRegulador = $_POST['fk_eCodRegulador'];
    $fk_eCodCamaraWeb = $_POST['fk_eCodCamaraWeb'];
    $fk_eCodUsuario = $_SESSION['eCodUsuario']; // Obtener eCodUsuario de la sesión
    $bEstadoAsignacion = isset($_POST['bEstadoAsignacion']) ? 1 : 0;
    $fhFechaHoraCreacion = date('Y-m-d H:i:s');
    $fhFechaHoraActualizacion = date('Y-m-d H:i:s');

    // Crear la consulta SQL para insertar en asignacionaulas
    $sql = "INSERT INTO asignacionaulas (
                fk_eCodProyector,
                fk_eCodAireAcondicionado,
                fk_eCodAmplificador,
                fk_eCodPantallaProyeccion,
                fk_eCodAula,
                fk_eCodMicrofono,
                fk_eCodEquipoComputo,
                fk_eCodRegulador,
                fk_eCodCamaraWeb,
                fk_eCodUsuario,
                bEstadoAsignacion,
                fhFechaHoraCreacion,
                fhFechaHoraActualizacion
            ) VALUES (
                '$fk_eCodProyector',
                '$fk_eCodAireAcondicionado',
                '$fk_eCodAmplificador',
                '$fk_eCodPantallaProyeccion',
                '$fk_eCodAula',
                '$fk_eCodMicrofono',
                '$fk_eCodEquipoComputo',
                '$fk_eCodRegulador',
                '$fk_eCodCamaraWeb',
                '$fk_eCodUsuario',
                '$bEstadoAsignacion',
                '$fhFechaHoraCreacion',
                '$fhFechaHoraActualizacion'
            )";

    if ($conn->query($sql) === TRUE) {
        echo '<script>
                $(document).ready(function() {
                    $("#successModalAsignacion").modal("show");
                });
              </script>';
    } else {
        echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
    $conn->close();
}
?>
<br />
<center>
    <div class="container">
        <center>
            <h2>Registro de Asignación de Aulas</h2>
        </center>
        <div class="text-left mt-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModalAsignacion">
                Asignar Aula
            </button>
        </div>
        <?php
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        $sql = "SELECT aa.*, u.*, a.tNombreAula 
                FROM asignacionaulas aa
                JOIN usuarios u ON aa.fk_eCodUsuario = u.eCodUsuario
                JOIN aula a ON aa.fk_eCodAula = a.eCodAula";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo '<table id="tabla_asignaciones" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Aula</th>
                            <th>Estado</th>
                            <th>Registrado Por</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>';
            while ($row = $result->fetch_assoc()) {
                $estadoAsignacion = $row['bEstadoAsignacion'] ? 'Activo' : 'Inactivo';
                $acciones = '<a href="editar_asignacion.php?id=' . $row['eCodAsignacion'] . '" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Editar">
                                <i class="fas fa-edit"></i>
                             </a>';
                
                $acciones .= '<button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detalleModal" data-id="' . $row['eCodAsignacion'] . '">
                                <i class="fas fa-eye"></i>
                             </button>';

                if ($row['bEstadoAsignacion']) {
                    $acciones .= '<a href="cambiar_estado_asignacion.php?id=' . $row['eCodAsignacion'] . '" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Desactivar">
                                    <i class="fas fa-trash-alt"></i>
                                  </a>';
                } else {
                    $acciones .= '<a href="cambiar_estado_asignacion.php?id=' . $row['eCodAsignacion'] . '" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Activar">
                                    <i class="fas fa-check"></i>
                                  </a>';
                }
                echo '<tr>
                        <td>' . $row['eCodAsignacion'] . '</td>
                        <td>' . $row['tNombreAula'] . '</td>
                        <td>' . $estadoAsignacion . '</td>
                        <td>' . $row['tNombreUsuario'] . ' ' . $row['tApellidoPaterno'] . ' ' . $row['tApellidoMaterno'] . '</td>
                        <td>' . $acciones . '</td>
                      </tr>';
            }
            echo '</tbody></table>';
        } else {
            echo "No se encontraron resultados";
        }
        $conn->close();
        ?>
    </div>
</center>

<!-- Modal para Asignar Aula -->
<div id="myModalAsignacion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Nueva Asignación de Aula</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="asignacionAulaForm" method="POST" action="">
                <div class="modal-body">
                    <!-- Aquí los campos del formulario -->
                    <div class="form-group">
                        <label for="fk_eCodAula">Salón de Clases:</label>
                        <select class="form-control" id="fk_eCodAula" name="fk_eCodAula" required>
                            <option value="">Selecciona un aula</option>
                            <option value="0">No Aplica</option>
                            <?php
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Conexión fallida: " . $conn->connect_error);
                            }
                            $sql = "SELECT * FROM aula";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['eCodAula'] . '">' . $row['tNombreAula'] . '</option>';
                                }
                            }
                            $conn->close();
                            ?>
                        </select>
                    </div>

                    <!-- Resto de los campos del formulario -->
                    <div class="form-group">
                        <label for="fk_eCodEquipoComputo">Equipo de Cómputo:</label>
                        <select class="form-control" id="fk_eCodEquipoComputo" name="fk_eCodEquipoComputo" required>
                            <option value="">Selecciona un equipo de cómputo</option>
                            <option value="0">No Aplica</option>
                            <?php
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Conexión fallida: " . $conn->connect_error);
                            }
                            $sql = "SELECT * FROM equipocomputo";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['eCodEquipoComputo'] . '">' . $row['eNumeroInventarioEquipoComputo'] . '</option>';
                                }
                            }
                            $conn->close();
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fk_eCodProyector">Proyector:</label>
                        <select class="form-control" id="fk_eCodProyector" name="fk_eCodProyector" required>
                            <option value="">Selecciona un proyector</option>
                            <option value="0">No Aplica</option>
                            <?php
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Conexión fallida: " . $conn->connect_error);
                            }
                            $sql = "SELECT * FROM proyector";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['eCodProyector'] . '">' . $row['eNumeroInventarioProyector'] . '</option>';
                                }
                            }
                            $conn->close();
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fk_eCodPantallaProyeccion">Pantalla de Proyección:</label>
                        <select class="form-control" id="fk_eCodPantallaProyeccion" name="fk_eCodPantallaProyeccion" required>
                            <option value="">Selecciona una pantalla de proyección</option>
                            <option value="0">No Aplica</option>
                            <?php
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Conexión fallida: " . $conn->connect_error);
                            }
                            $sql = "SELECT * FROM pantallaproyeccion";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['eCodPantallaProyeccion'] . '">' . $row['eNumeroInventarioPantalla'] . '</option>';
                                }
                            }
                            $conn->close();
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fk_eCodAmplificador">Amplificador:</label>
                        <select class="form-control" id="fk_eCodAmplificador" name="fk_eCodAmplificador" required>
                            <option value="">Selecciona un amplificador</option>
                            <option value="0">No Aplica</option>
                            <?php
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Conexión fallida: " . $conn->connect_error);
                            }
                            $sql = "SELECT * FROM equipoaudio";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['eCodAmplificador'] . '">' . $row['eNumeroInventarioAmplificador'] . '</option>';
                                }
                            }
                            $conn->close();
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fk_eCodMicrofono">Micrófono:</label>
                        <select class="form-control" id="fk_eCodMicrofono" name="fk_eCodMicrofono" required>
                            <option value="">Selecciona un micrófono</option>
                            <option value="0">No Aplica</option>
                            <?php
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Conexión fallida: " . $conn->connect_error);
                            }
                            $sql = "SELECT * FROM microfono";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['eCodMicrofono'] . '">' . $row['eNumeroInventarioMicrofono'] . '</option>';
                                }
                            }
                            $conn->close();
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fk_eCodCamaraWeb">Cámara Web:</label>
                        <select class="form-control" id="fk_eCodCamaraWeb" name="fk_eCodCamaraWeb" required>
                            <option value="">Selecciona una cámara web</option>
                            <option value="0">No Aplica</option>
                            <?php
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Conexión fallida: " . $conn->connect_error);
                            }
                            $sql = "SELECT * FROM camaraweb";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['eCodCamaraWeb'] . '">' . $row['eNumeroInventarioCamara'] . '</option>';
                                }
                            }
                            $conn->close();
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fk_eCodAireAcondicionado">Aire Acondicionado:</label>
                        <select class="form-control" id="fk_eCodAireAcondicionado" name="fk_eCodAireAcondicionado" required>
                            <option value="">Selecciona un aire acondicionado</option>
                            <option value="0">No Aplica</option>
                            <?php
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Conexión fallida: " . $conn->connect_error);
                            }
                            $sql = "SELECT * FROM aireacondicionado";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['eCodAireAcondicionado'] . '">' . $row['eNumeroInventarioAire'] . '</option>';
                                }
                            }
                            $conn->close();
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fk_eCodRegulador">Regulador:</label>
                        <select class="form-control" id="fk_eCodRegulador" name="fk_eCodRegulador" required>
                            <option value="">Selecciona un regulador</option>
                            <option value="0">No Aplica</option>
                            <?php
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Conexión fallida: " . $conn->connect_error);
                            }
                            $sql = "SELECT * FROM regulador";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['eCodRegulador'] . '">' . $row['eNumeroInventarioRegulador'] . '</option>';
                                }
                            }
                            $conn->close();
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="bEstadoAsignacion">Habilitado para su uso</label>
                        <input type="checkbox" id="bEstadoAsignacion" name="bEstadoAsignacion">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para ver más detalles -->
<div id="detalleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="detalleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detalleModalLabel">Detalles de la Asignación</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detalleContenido">
                <!-- Contenido cargado por AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $(document).on('click', '.btn-primary', function() {
        var asignacionId = $(this).data('id');
        $.ajax({
            url: 'obtener_asignacion.php',
            method: 'GET',
            data: { id: asignacionId },
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    alert(response.error);
                } else {
                    $('#detalleContenido').html(`
                        <p><strong>Id:</strong> ${response.eCodAsignacion}</p>
                        <p><strong>Aula:</strong> ${response.tNombreAula}</p>
                        <p><strong>Proyector:</strong> ${response.eNumeroInventarioProyector}</p>
                        <p><strong>Aire Acondicionado:</strong> ${response.aire}</p>
                        <p><strong>Amplificador:</strong> ${response.amplificador}</p>
                        <p><strong>Pantalla de Proyección:</strong> ${response.pantalla}</p>
                        <p><strong>Micrófono:</strong> ${response.microfono}</p>
                        <p><strong>Equipo de Cómputo:</strong> ${response.equipo}</p>
                        <p><strong>Regulador:</strong> ${response.regulador}</p>
                        <p><strong>Cámara Web:</strong> ${response.camara}</p>
                    `);
                    $('#detalleModal').modal('show');
                }
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
               // alert('Error - ' + errorMessage);
            }
        });
    });
});
</script>



<?php include("footer.php"); ?>
