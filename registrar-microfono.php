<?php include("header.php"); ?>
<?php include("menu.php"); ?>

<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "incidenciasump";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Manejo de solicitud para eliminar un equipo
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminarEquipoId'])) {
    $id = $_POST['eliminarEquipoId'];
    $sql = "DELETE FROM microfono WHERE eCodMicrofono=$id";

    if ($conn->query($sql) === TRUE) {
        echo '<script>
                $(document).ready(function() {
                    $("#successModalEliminar").modal("show");
                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                });
              </script>';
    } else {
        echo "<p>Error al eliminar el equipo: " . $conn->error . "</p>";
    }
}

// Manejo de solicitud para agregar o actualizar un equipo
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['eliminarEquipoId'])) {
    if (isset($_POST['eCodMicrofono']) && !empty($_POST['eCodMicrofono'])) {
        // Actualización de equipo
        $id = $_POST['eCodMicrofono'];
        $tMarcaMicrofono = $_POST['tMarcaMicrofono'];
        $tModeloMicrofono = $_POST['tModeloMicrofono'];
        $bTipoMicrofono = $_POST['bTipoMicrofono'];
        $eNumeroInventarioMicrofono = $_POST['eNumeroInventarioMicrofono'];
        $eNumeroSerieMicrofono = $_POST['eNumeroSerieMicrofono'];
        $bEstadoMicrofono = isset($_POST['bEstadoMicrofono']) ? 1 : 0;
        $fhFechaHoraActualizacionMicrofono = date('Y-m-d H:i:s');

        $sql = "UPDATE microfono SET 
                tMarcaMicrofono='$tMarcaMicrofono', 
                tModeloMicrofono='$tModeloMicrofono', 
                bTipoMicrofono='$bTipoMicrofono', 
                eNumeroInventarioMicrofono='$eNumeroInventarioMicrofono', 
                eNumeroSerieMicrofono='$eNumeroSerieMicrofono', 
                bEstadoMicrofono='$bEstadoMicrofono', 
                fhFechaHoraActualizacionMicrofono='$fhFechaHoraActualizacionMicrofono'
                WHERE eCodMicrofono=$id";

        if ($conn->query($sql) === TRUE) {
            echo '<script>
                    $(document).ready(function() {
                        $("#successModalEdicion").modal("show");
                        setTimeout(function(){
                            location.reload();
                        }, 1000);
                    });
                  </script>';
        } else {
            echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    } else {
        // Inserción de nuevo equipo
        $tMarcaMicrofono = $_POST['tMarcaMicrofono'];
        $tModeloMicrofono = $_POST['tModeloMicrofono'];
        $bTipoMicrofono = $_POST['bTipoMicrofono'];
        $eNumeroInventarioMicrofono = $_POST['eNumeroInventarioMicrofono'];
        $eNumeroSerieMicrofono = $_POST['eNumeroSerieMicrofono'];
        $bEstadoMicrofono = isset($_POST['bEstadoMicrofono']) ? 1 : 0;
        $fhFechaHoraCreacionMicrofono = date('Y-m-d H:i:s');
        $fhFechaHoraActualizacionMicrofono = date('Y-m-d H:i:s');

        $sql = "INSERT INTO microfono (tMarcaMicrofono, tModeloMicrofono, bTipoMicrofono, eNumeroInventarioMicrofono, 
                eNumeroSerieMicrofono, bEstadoMicrofono, fhFechaHoraCreacionMicrofono, 
                fhFechaHoraActualizacionMicrofono)
                VALUES ('$tMarcaMicrofono', '$tModeloMicrofono', '$bTipoMicrofono', '$eNumeroInventarioMicrofono', 
                '$eNumeroSerieMicrofono', '$bEstadoMicrofono', '$fhFechaHoraCreacionMicrofono', 
                '$fhFechaHoraActualizacionMicrofono')";

        if ($conn->query($sql) === TRUE) {
            echo '<script>
                    $(document).ready(function() {
                        $("#successModalMicrofono").modal("show");
                    });
                  </script>';
        } else {
            echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    }
}

// Manejo de solicitud para obtener datos de un equipo específico
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM microfono WHERE eCodMicrofono=$id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode([]);
    }
    exit;
}
?>
<br />
<center>
    <div class="container">
        <center>
            <h2>Registro de Micrófono</h2>
        </center>
        <div class="text-left mt-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModalMicrofono">
                Agregar Micrófono
            </button>
        </div>
        <?php
        $sql = "SELECT * FROM microfono";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo '<table id="tabla_microfonos" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>No. de Inventario</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Tipo</th>
                            <th>No. de serie</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>';
            while ($row = $result->fetch_assoc()) {
                $estadoMicrofono = $row['bEstadoMicrofono'] ? 'Activo' : 'Inactivo';
                $tipoMicrofono = $row['bTipoMicrofono'] == 'Alambrico' ? 'Alámbrico' : 'Inalámbrico';
                $acciones = '
                              <button type="button" class="btn btn-danger btn-sm eliminar-microfono" data-id="' . $row['eCodMicrofono'] . '" data-bs-toggle="tooltip" title="Eliminar">
                                <i class="fas fa-trash"></i>
                             </button>';

                if ($row['bEstadoMicrofono']) {
                    $acciones .= '<a href="cambiar_estado_microfono.php?id=' . $row['eCodMicrofono'] . '" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Desactivar">
                                    <i class="fas fa-eye"></i>
                                  </a>';
                } else {
                    $acciones .= '<a href="cambiar_estado_microfono.php?id=' . $row['eCodMicrofono'] . '" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Activar">
                                    <i class="fas fa-eye-slash"></i>
                                  </a>';
                }

                echo '<tr>
                        <td>' . $row['eCodMicrofono'] . '</td>
                        <td>' . $row['eNumeroInventarioMicrofono'] . '</td>
                        <td>' . $row['tMarcaMicrofono'] . '</td>
                        <td>' . $row['tModeloMicrofono'] . '</td>
                        <td>' . $tipoMicrofono . '</td>
                        <td>' . $row['eNumeroSerieMicrofono'] . '</td>
                        <td>' . $estadoMicrofono . '</td>
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

<!-- Modal para Agregar/Editar Micrófono -->
<div id="myModalMicrofono" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Agregar/Editar Micrófono</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_microfono" method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" id="eCodMicrofono" name="eCodMicrofono" value="">
                    <div class="form-group">
                        <label for="tMarcaMicrofono">Marca:</label>
                        <input type="text" class="form-control" id="tMarcaMicrofono" name="tMarcaMicrofono" required>
                    </div>
                    <div class="form-group">
                        <label for="tModeloMicrofono">Modelo:</label>
                        <input type="text" class="form-control" id="tModeloMicrofono" name="tModeloMicrofono" required>
                    </div>
                    <div class="form-group">
                        <label for="bTipoMicrofono">Tipo:</label>
                        <select class="form-control" id="bTipoMicrofono" name="bTipoMicrofono" required>
                            <option value="Alambrico">Alámbrico</option>
                            <option value="Inalambrico">Inalámbrico</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="eNumeroInventarioMicrofono">Número de Inventario:</label>
                        <input type="text" class="form-control" id="eNumeroInventarioMicrofono" name="eNumeroInventarioMicrofono" required>
                    </div>
                    <div class="form-group">
                        <label for="eNumeroSerieMicrofono">Número de Serie:</label>
                        <input type="text" class="form-control" id="eNumeroSerieMicrofono" name="eNumeroSerieMicrofono" required>
                    </div>
                    <div class="form-group">
                        <label for="bEstadoMicrofono">Estado:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="bEstadoMicrofonoCheckbox" name="bEstadoMicrofono">
                            <label class="form-check-label" for="bEstadoMicrofonoCheckbox">Activo</label>
                        </div>
                    </div>
                    <input type="hidden" id="fhFechaHoraCreacionMicrofono" name="fhFechaHoraCreacionMicrofono" value="<?php echo date('Y-m-d H:i:s'); ?>">
                    <input type="hidden" id="fhFechaHoraActualizacionMicrofono" name="fhFechaHoraActualizacionMicrofono" value="<?php echo date('Y-m-d H:i:s'); ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de éxito para creación -->
<div id="successModalMicrofono" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">¡Éxito!</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>El equipo se ha creado exitosamente.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de éxito para edición -->
<div id="successModalEdicion" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">¡Éxito!</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>El equipo se ha actualizado exitosamente.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de éxito para eliminación -->
<div id="successModalEliminar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">¡Éxito!</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>El equipo se ha eliminado exitosamente.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('[data-bs-toggle="tooltip"]').tooltip();
    $('#tabla_microfonos').DataTable();

    // Cargar datos de micrófono al modal de edición
    $('.editar-microfono').on('click', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '',
            type: 'GET',
            data: { id: id },
            success: function(response) {
                var data = JSON.parse(response);
                $('#eCodMicrofono').val(data.eCodMicrofono);
                $('#tMarcaMicrofono').val(data.tMarcaMicrofono);
                $('#tModeloMicrofono').val(data.tModeloMicrofono);
                $('#bTipoMicrofono').val(data.bTipoMicrofono);
                $('#eNumeroInventarioMicrofono').val(data.eNumeroInventarioMicrofono);
                $('#eNumeroSerieMicrofono').val(data.eNumeroSerieMicrofono);
                $('#bEstadoMicrofonoCheckbox').prop('checked', data.bEstadoMicrofono);
                $('#myModalMicrofono').modal('show');
            }
        });
    });

    // Envío del formulario de edición
    $('#form_microfono').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: '',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#myModalMicrofono').modal('hide');
                $('#successModalEdicion').modal('show');
                setTimeout(function(){
                    location.reload();
                }, 1000);
            }
        });
    });

    // Confirmación de eliminación
    $('.eliminar-microfono').on('click', function() {
        var id = $(this).data('id');
        if (confirm('¿Está seguro de que desea eliminar este equipo?')) {
            $.ajax({
                url: '',
                type: 'POST',
                data: { eliminarEquipoId: id },
                success: function(response) {
                    location.reload();
                }
            });
        }
    });
});
</script>

<?php include("footer.php"); ?>
