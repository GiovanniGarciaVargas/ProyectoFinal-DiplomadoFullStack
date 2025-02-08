<?php
// Incluir archivos de cabecera y menú
include("header.php");
include("menu.php");
?>

<!-- Incluir hoja de estilo de Bootstrap Switch -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet">

<?php
// Configuración de la conexión a la base de datos
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

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $tMarcaPantalla = $_POST['tMarcaPantalla'];
    $tModeloPantalla = $_POST['tModeloPantalla'];
    $eNumeroInventarioPantalla = $_POST['eNumeroInventarioPantalla'];
    $eNumeroSeriePantalla = $_POST['eNumeroSeriePantalla'];
    $bTipoPantalla = $_POST['bTipoPantalla'];  // Tipo de pantalla (manual o automática)
    $bEstadoPantalla = isset($_POST['bEstadoPantalla']) ? 1 : 0;  // Estado activo o inactivo
    $fhFechaHoraCreacionPantalla = $_POST['fhFechaHoraCreacionPantalla'];
    $fhFechaHoraActualizacionPantalla = $_POST['fhFechaHoraActualizacionPantalla'];
    $eCodPantallaProyeccion = $_POST['eCodPantallaProyeccion'] ?? null;

    if ($eCodPantallaProyeccion) {
        // Actualizar registro existente
        $sql = "UPDATE pantallaproyeccion SET tMarcaPantalla=?, tModeloPantalla=?, eNumeroInventarioPantalla=?, 
                eNumeroSeriePantalla=?, bTipoPantalla=?, bEstadoPantalla=?, fhFechaHoraActualizacionPantalla=? 
                WHERE eCodPantallaProyeccion=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssissisi", $tMarcaPantalla, $tModeloPantalla, $eNumeroInventarioPantalla, 
                          $eNumeroSeriePantalla, $bTipoPantalla, $bEstadoPantalla, $fhFechaHoraActualizacionPantalla, 
                          $eCodPantallaProyeccion);
    } else {
        // Insertar nuevo registro
        $sql = "INSERT INTO pantallaproyeccion (tMarcaPantalla, tModeloPantalla, eNumeroInventarioPantalla, 
                eNumeroSeriePantalla, bTipoPantalla, bEstadoPantalla, fhFechaHoraCreacionPantalla, 
                fhFechaHoraActualizacionPantalla) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssississ", $tMarcaPantalla, $tModeloPantalla, $eNumeroInventarioPantalla, 
                          $eNumeroSeriePantalla, $bTipoPantalla, $bEstadoPantalla, $fhFechaHoraCreacionPantalla, 
                          $fhFechaHoraActualizacionPantalla);
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo '<script>
                $(document).ready(function() {
                    $("#successModalPantalla").modal("show");
                });
              </script>';
    } else {
        echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }

    // Cerrar la conexión y el statement
    $stmt->close();
}

// Procesar solicitud de eliminación
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM pantallaproyeccion WHERE eCodPantallaProyeccion=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo '<script>
                $(document).ready(function() {
                    alert("Registro eliminado correctamente");
                    window.location.href = "registrar-pantalla-proyeccion.php";
                });
              </script>';
    } else {
        echo "<p>Error al eliminar el registro: " . $conn->error . "</p>";
    }
    $stmt->close();
}
?>

<!-- Contenido HTML -->
<br />
<center>
    <div class="container">
        <center>
            <h2>Registro de Pantalla de Proyección</h2>
        </center>
        <div class="text-left mt-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModalPantalla">
                Agregar Pantalla de Proyección
            </button>
        </div>
        <?php
        // Consulta para mostrar las pantallas de proyección registradas
        $sql = "SELECT * FROM pantallaproyeccion";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo '<table id="tabla_pantallas" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>No. de Inventario</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>No. de Serie</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>';

            while ($row = $result->fetch_assoc()) {
                $estadoPantalla = $row['bEstadoPantalla'] ? 'Activo' : 'Inactivo';
                $tipoPantalla = $row['bTipoPantalla'] ? 'Automática' : 'Manual';
                $acciones = '<button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editModalPantalla" 
                             data-id="' . $row['eCodPantallaProyeccion'] . '" data-marca="' . $row['tMarcaPantalla'] . '"
                             data-modelo="' . $row['tModeloPantalla'] . '" data-inventario="' . $row['eNumeroInventarioPantalla'] . '"
                             data-serie="' . $row['eNumeroSeriePantalla'] . '" data-tipo="' . $row['bTipoPantalla'] . '"
                             data-estado="' . $row['bEstadoPantalla'] . '">
                                <i class="fas fa-edit"></i>
                             </button>
                             <a href="?delete_id=' . $row['eCodPantallaProyeccion'] . '" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Eliminar">
                                <i class="fas fa-trash-alt"></i>
                             </a>';
                             if ($row['bEstadoPantalla']) {
                                $acciones .= '<a href="cambiar_estado_pantalla.php?id=' . $row['eCodPantallaProyeccion'] . '" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Eliminar">
                                                <i class="fas fa-eye"></i>
                                              </a>';
                            } else {
                                $acciones .= '<a href="cambiar_estado_pantalla.php?id=' . $row['eCodPantallaProyeccion'] . '" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Activar">
                                                <i class="fas fa-eye-slash"></i>
                                              </a>';
                            }
                echo '<tr>
                        <td>' . $row['eCodPantallaProyeccion'] . '</td>
                        <td>' . $row['eNumeroInventarioPantalla'] . '</td>
                        <td>' . $row['tMarcaPantalla'] . '</td>
                        <td>' . $row['tModeloPantalla'] . '</td>
                        <td>' . $row['eNumeroSeriePantalla'] . '</td>
                        <td>' . $tipoPantalla . '</td>
                        <td>' . $estadoPantalla . '</td>
                        <td>' . $acciones . '</td>
                      </tr>';
            }

            echo '</tbody></table>';
        } else {
            echo "No se encontraron resultados";
        }

        // Cerrar conexión
        $conn->close();
        ?>
    </div>
</center>

<!-- Modal para Agregar Pantalla de Proyección -->
<div id="myModalPantalla" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Nueva Pantalla de Proyección</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="pantallaForm" method="POST" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tMarcaPantalla">Marca:</label>
                        <input type="text" class="form-control" id="tMarcaPantalla" name="tMarcaPantalla" required>
                    </div>
                    <div class="form-group">
                        <label for="tModeloPantalla">Modelo:</label>
                        <input type="text" class="form-control" id="tModeloPantalla" name="tModeloPantalla" required>
                    </div>
                    <div class="form-group">
                        <label for="eNumeroInventarioPantalla">Número de Inventario:</label>
                        <input type="number" class="form-control" id="eNumeroInventarioPantalla" name="eNumeroInventarioPantalla" required>
                    </div>
                    <div class="form-group">
                        <label for="eNumeroSeriePantalla">Número de Serie:</label>
                        <input type="text" class="form-control" id="eNumeroSeriePantalla" name="eNumeroSeriePantalla" required>
                    </div>
                    <div class="form-group">
                        <label for="bTipoPantalla">Tipo de Pantalla:</label>
                        <select class="form-control" id="bTipoPantalla" name="bTipoPantalla" required>
                            <option value="1">Manual</option>
                            <option value="0">Automática</option>
                        </select>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="bEstadoPantalla" name="bEstadoPantalla" value="1" checked>
                        <label class="form-check-label" for="bEstadoPantalla">Activo</label>
                    </div>
                    <input type="hidden" id="fhFechaHoraCreacionPantalla" name="fhFechaHoraCreacionPantalla" value="<?php echo date('Y-m-d H:i:s'); ?>">
                    <input type="hidden" id="fhFechaHoraActualizacionPantalla" name="fhFechaHoraActualizacionPantalla" value="<?php echo date('Y-m-d H:i:s'); ?>">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Editar Pantalla de Proyección -->
<div id="editModalPantalla" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Pantalla de Proyección</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editPantallaForm" method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" id="edit_eCodPantallaProyeccion" name="eCodPantallaProyeccion">
                    <div class="form-group">
                        <label for="edit_tMarcaPantalla">Marca:</label>
                        <input type="text" class="form-control" id="edit_tMarcaPantalla" name="tMarcaPantalla" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_tModeloPantalla">Modelo:</label>
                        <input type="text" class="form-control" id="edit_tModeloPantalla" name="tModeloPantalla" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_eNumeroInventarioPantalla">Número de Inventario:</label>
                        <input type="number" class="form-control" id="edit_eNumeroInventarioPantalla" name="eNumeroInventarioPantalla" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_eNumeroSeriePantalla">Número de Serie:</label>
                        <input type="text" class="form-control" id="edit_eNumeroSeriePantalla" name="eNumeroSeriePantalla" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_bTipoPantalla">Tipo de Pantalla:</label>
                        <select class="form-control" id="edit_bTipoPantalla" name="bTipoPantalla" required>
                            <option value="1">Manual</option>
                            <option value="0">Automática</option>
                        </select>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="edit_bEstadoPantalla" name="bEstadoPantalla" value="1">
                        <label class="form-check-label" for="edit_bEstadoPantalla">Activo</label>
                    </div>
                    <input type="hidden" id="edit_fhFechaHoraActualizacionPantalla" name="fhFechaHoraActualizacionPantalla" value="<?php echo date('Y-m-d H:i:s'); ?>">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Registro Exitoso -->
<div id="successModalPantalla" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Registro Exitoso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>Nueva pantalla de proyección creada exitosamente</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#editModalPantalla').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var marca = button.data('marca');
        var modelo = button.data('modelo');
        var inventario = button.data('inventario');
        var serie = button.data('serie');
        var tipo = button.data('tipo');
        var estado = button.data('estado');

        var modal = $(this);
        modal.find('#edit_eCodPantallaProyeccion').val(id);
        modal.find('#edit_tMarcaPantalla').val(marca);
        modal.find('#edit_tModeloPantalla').val(modelo);
        modal.find('#edit_eNumeroInventarioPantalla').val(inventario);
        modal.find('#edit_eNumeroSeriePantalla').val(serie);
        modal.find('#edit_bTipoPantalla').val(tipo);
        modal.find('#edit_bEstadoPantalla').prop('checked', estado);
    });
});
</script>

<?php include("footer.php"); ?>
