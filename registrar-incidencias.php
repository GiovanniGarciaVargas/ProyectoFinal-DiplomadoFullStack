<?php
session_start();
include("header.php");
include("menu.php");

// Función para obtener opciones de asignación desde la tabla asignacionaulas
function obtenerOpcionesAsignacion() {
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "incidenciasump";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    
    $sql = "SELECT eCodAsignacion FROM asignacionaulas";
    $result = $conn->query($sql);
    $options = '';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $options .= '<option value="' . $row['eCodAsignacion'] . '">' . $row['eCodAsignacion'] . '</option>';
        }
    }
    $conn->close();
    return $options;
}

// Manejo del formulario de registro de incidencias
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['registro_incidencia'])) {
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "incidenciasump";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    
    $fk_eCodAsignacion = $_POST['fk_eCodAsignacion'];
    $tDescripcionIncidencia = $_POST['tDescripcionIncidencia'];
    $bEstadoProyector = isset($_POST['bEstadoProyector']) ? 1 : 0;
    $bEstadoPantalla = isset($_POST['bEstadoPantalla']) ? 1 : 0;
    $bEstadoAire = isset($_POST['bEstadoAire']) ? 1 : 0;
    $tModalidadIncidencia = $_POST['tModalidadIncidencia'];
    $fhFechaHoraRegistro = date('Y-m-d H:i:s');
    $fk_eCodUsuario = $_SESSION['eCodUsuario'];

    // Manejo de subida de archivos
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $tEvidenciaIncidenciaEquipoComputo = basename($_FILES['tEvidenciaIncidenciaEquipoComputo']['name']);
    $tEvidenciaIncidenciaProyector = basename($_FILES['tEvidenciaIncidenciaProyector']['name']);
    $tEvidenciaIncidenciaAire = basename($_FILES['tEvidenciaIncidenciaAire']['name']);

    move_uploaded_file($_FILES['tEvidenciaIncidenciaEquipoComputo']['tmp_name'], $uploadDir . $tEvidenciaIncidenciaEquipoComputo);
    move_uploaded_file($_FILES['tEvidenciaIncidenciaProyector']['tmp_name'], $uploadDir . $tEvidenciaIncidenciaProyector);
    move_uploaded_file($_FILES['tEvidenciaIncidenciaAire']['tmp_name'], $uploadDir . $tEvidenciaIncidenciaAire);

    $sql = "INSERT INTO incidencias (fk_eCodAsignacion, tDescripcionIncidencia, bEstadoProyector, bEstadoPantalla, bEstadoAire, tModalidadIncidencia, tEvidenciaIncidenciaEquipoComputo, tEvidenciaIncidenciaProyector, tEvidenciaIncidenciaAire, fk_eCodUsuarioRegistraIncidencia, fhFechaHoraRegistro) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssssssis", $fk_eCodAsignacion, $tDescripcionIncidencia, $bEstadoProyector, $bEstadoPantalla, $bEstadoAire, $tModalidadIncidencia, $tEvidenciaIncidenciaEquipoComputo, $tEvidenciaIncidenciaProyector, $tEvidenciaIncidenciaAire, $fk_eCodUsuario, $fhFechaHoraRegistro);

    if ($stmt->execute()) {
        echo '<script>
                $(document).ready(function() {
                    $("#successModalIncidencia").modal("show");
                });
              </script>';
    } else {
        echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
    $stmt->close();
    $conn->close();
}
?>

<!-- Contenido principal -->
<div class="container mt-4 mb-4">
    <center>
        <h2>Registro de Incidencias</h2>
    </center>
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#myModalAire">
        Reportar Incidencias
    </button>
    <br /><br />
    
    <div class="container mt-5">
        <div class="row">
            <!-- Formulario para seleccionar fecha y generar PDF -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Generar Reporte por Fecha</h5>
                        <div class="mb-3">
                            <label for="fechaSeleccion" class="form-label">Seleccione Fecha:</label>
                            <input type="date" class="form-control" id="fechaSeleccion">
                        </div>
                        <button class="btn btn-danger btn-sm" id="generarPDFPorFecha">Generar Reporte</button>
                    </div>
                </div>
            </div>

            <!-- Formulario para seleccionar modalidad y generar PDF -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Generar Reporte por Modalidad</h5>
                        <div class="mb-3">
                            <label for="tModalidadSeleccion" class="form-label">Seleccione Modalidad:</label>
                            <select class="form-select" id="tModalidadSeleccion">
                                <option value="Escolarizado">Escolarizado</option>
                                <option value="Sabatino">Sabatino</option>
                            </select>
                        </div>
                        <button class="btn btn-danger btn-sm" id="generarPDF">Generar Reporte</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <br /><br />
    
    <?php
    // Mostrar tabla de incidencias registradas
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "incidenciasump";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    $sql = "SELECT incidencias.*, usuarios.* FROM incidencias INNER JOIN usuarios ON incidencias.fk_eCodUsuarioRegistraIncidencia = usuarios.eCodUsuario";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo '<table class="table-responsive table-striped" id="tabla_incidencias">
                <thead>
                    <tr>
                        <th>Aula Reportada</th>
                        <th>Modalidad</th>
                        <th>Evidencia Equipo de Cómputo</th>
                        <th>Evidencia Proyector</th>
                        <th>Evidencia Aire</th>
                        <th>Ver Detalles</th>
                        <th>Generar PDF</th>
                    </tr>
                </thead>
                <tbody>';
        while ($row = $result->fetch_assoc()) {
            $estadoProyector = $row['bEstadoProyector'] ? 'Funcional' : 'No funcional';
            $estadoPantalla = $row['bEstadoPantalla'] ? 'Funcional' : 'No funcional';
            $estadoAire = $row['bEstadoAire'] ? 'Funcional' : 'No funcional';
            echo '<tr>
                    <td>' . $row['fk_eCodAsignacion'] . '</td>
                    <td>' . $row['tModalidadIncidencia'] . '</td>
                    <td><button type="button" class="btn btn-primary btn-sm" onclick="verEvidencia(\'uploads/' . $row['tEvidenciaIncidenciaEquipoComputo'] . '\')"><i class="fa fa-eye"></i></button></td>
                    <td><button type="button" class="btn btn-primary btn-sm" onclick="verEvidencia(\'uploads/' . $row['tEvidenciaIncidenciaProyector'] . '\')"><i class="fa fa-eye"></i></button></td>
                    <td><button type="button" class="btn btn-primary btn-sm" onclick="verEvidencia(\'uploads/' . $row['tEvidenciaIncidenciaAire'] . '\')"><i class="fa fa-eye"></i></button></td>
                    <td><button type="button" class="btn btn-secondary btn-sm" onclick="verDetalles(' . htmlspecialchars(json_encode($row)) . ')"><i class="fa fa-info-circle"></i></button></td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="generarPDFRegistro(' . $row['eCodIncidencia'] . ')"><i class="fa fa-file"></i></button></td>
                </tr>';
        }
        echo '</tbody></table>';
    } else {
        echo "<p>No se encontraron incidencias registradas.</p>";
    }
    $conn->close();
    ?>
</div>

<!-- Modal de éxito -->
<div class="modal fade" id="successModalIncidencia" tabindex="-1" aria-labelledby="successModalIncidenciaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalIncidenciaLabel">Éxito</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Incidencia registrada correctamente.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para formulario de incidencias -->
<div class="modal fade" id="myModalAire" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Reporte de incidencias</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="">
                    <div class="form-group">
                        <label for="fk_eCodAsignacion">Aula:</label>
                        <select class="form-select" id="fk_eCodAsignacion" name="fk_eCodAsignacion" required>
                            <?php echo obtenerOpcionesAsignacion(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tDescripcionIncidencia">Descripción de la Incidencia:</label>
                        <textarea class="form-control" id="tDescripcionIncidencia" name="tDescripcionIncidencia" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Estado de los equipos:</label>
                        <div class="form-check">
                            <label class="form-check-label" for="bEstadoProyector">Proyector</label>
                            <select class="form-control" id="bEstadoProyector">
                                <option value=0>No Funcional</option>
                                <option value=1>Funcional</option>
                            </select>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label" for="bEstadoPantalla">Pantalla</label>
                            <select class="form-control" id="bEstadoPantalla">
                                <option value=0>No Funcional</option>
                                <option value=1>Funcional</option>
                            </select>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label" for="bEstadoAire">Aire Acondicionado</label>
                            <select class="form-control" id="bEstadoAire">
                                <option value=0>No Funcional</option>
                                <option value=1>Funcional</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tModalidadIncidencia">Modalidad:</label>
                        <select class="form-select" id="tModalidadIncidencia" name="tModalidadIncidencia" required>
                            <option value="Escolarizado">Escolarizado</option>
                            <option value="Sabatino">Sabatino</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tEvidenciaIncidenciaEquipoComputo">Evidencia Equipo de Cómputo:</label>
                        <input type="file" class="form-control-file" id="tEvidenciaIncidenciaEquipoComputo" name="tEvidenciaIncidenciaEquipoComputo">
                    </div>
                    <div class="form-group">
                        <label for="tEvidenciaIncidenciaProyector">Evidencia Proyector:</label>
                        <input type="file" class="form-control-file" id="tEvidenciaIncidenciaProyector" name="tEvidenciaIncidenciaProyector">
                    </div>
                    <div class="form-group">
                        <label for="tEvidenciaIncidenciaAire">Evidencia Aire Acondicionado:</label>
                        <input type="file" class="form-control-file" id="tEvidenciaIncidenciaAire" name="tEvidenciaIncidenciaAire">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm" name="registro_incidencia">Registrar Incidencia</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Enlace a DataTables CSS y JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>

<script>
$(document).ready(function() {
    $('#tabla_incidencias').DataTable();

    $('#generarPDF').click(function() {
        var modalidad = $('#tModalidadSeleccion').val();
        window.open('generarPDF.php?modalidad=' + modalidad, '_blank');
    });
});

function verEvidencia(url) {
    window.open(url, '_blank');
}

function verDetalles(registro) {
    var modalContent = `
        <div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detalleModalLabel">Detalles de la Incidencia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Aula Reportada:</strong> ${registro.fk_eCodAsignacion}</p>
                        <p><strong>Modalidad:</strong> ${registro.tModalidadIncidencia}</p>
                        <p><strong>Descripción:</strong> ${registro.tDescripcionIncidencia}</p>
                        <p><strong>Estado Proyector:</strong> ${registro.bEstadoProyector ? 'Funcional' : 'No funcional'}</p>
                        <p><strong>Estado Pantalla:</strong> ${registro.bEstadoPantalla ? 'Funcional' : 'No funcional'}</p>
                        <p><strong>Estado Aire:</strong> ${registro.bEstadoAire ? 'Funcional' : 'No funcional'}</p>
                        <p><strong>Reportado por:</strong> ${registro.tNombreUsuario} ${registro.tApellidoPaterno} ${registro.tApellidoMaterno}</p>
                        <p><strong>Fecha de registro: </strong> ${registro.fhFechaHoraRegistro}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>`;
    $('body').append(modalContent);
    $('#detalleModal').modal('show');
    $('#detalleModal').on('hidden.bs.modal', function () {
        $('#detalleModal').remove();
    });
}

function generarPDFRegistro(eCodIncidencia) {
    window.open('generar_pdf_registro.php?eCodIncidencia=' + eCodIncidencia, '_blank');
}

$('#generarPDFPorFecha').click(function() {
    var fecha = $('#fechaSeleccion').val();
    if (fecha) {
        window.open('generarPDFPorFecha.php?fecha=' + fecha, '_blank');
    } else {
        alert('Por favor seleccione una fecha.');
    }
});

</script>



<?php
include("footer.php");
?>