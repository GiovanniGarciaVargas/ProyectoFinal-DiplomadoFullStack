<?php
include("header.php");
include("menu.php");
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet">

<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "incidenciasump";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Manejo de solicitud para actualizar o insertar un equipo
    if (isset($_POST['tMarcaEquipoComputo']) && isset($_POST['tModeloEquipoComputo'])) {
        $tMarcaEquipoComputo = $_POST['tMarcaEquipoComputo'];
        $tModeloEquipoComputo = $_POST['tModeloEquipoComputo'];
        $eNumeroInventarioEquipoComputo = $_POST['eNumeroInventarioEquipoComputo'];
        $eNumeroSerieGabineteEquipoComputo = $_POST['eNumeroSerieGabineteEquipoComputo'];
        $eNumeroSerieMouseEquipoComputo = $_POST['eNumeroSerieMouseEquipoComputo'];
        $eNumeroSerieTecladoEquipoComputo = $_POST['eNumeroSerieTecladoEquipoComputo'];
        $bEstadoEquipoComputo = isset($_POST['bEstadoEquipoComputo']) ? 1 : 0;
        $fhFechaHoraActualizacionEquipoComputo = $_POST['fhFechaHoraActualizacionEquipoComputo'];
        $id = $_POST['eCodEquipoComputo'];

        if ($id != '') {
            $sql = "UPDATE equipocomputo SET 
                    tMarcaEquipoComputo='$tMarcaEquipoComputo', 
                    tModeloEquipoComputo='$tModeloEquipoComputo', 
                    eNumeroInventarioEquipoComputo='$eNumeroInventarioEquipoComputo', 
                    eNumeroSerieGabineteEquipoComputo='$eNumeroSerieGabineteEquipoComputo', 
                    eNumeroSerieMouseEquipoComputo='$eNumeroSerieMouseEquipoComputo', 
                    eNumeroSerieTecladoEquipoComputo='$eNumeroSerieTecladoEquipoComputo', 
                    bEstadoEquipoComputo='$bEstadoEquipoComputo', 
                    fhFechaHoraActualizacionEquipoComputo='$fhFechaHoraActualizacionEquipoComputo' 
                    WHERE eCodEquipoComputo=$id";
        } else {
            $sql = "INSERT INTO equipocomputo 
                    (tMarcaEquipoComputo, tModeloEquipoComputo, eNumeroInventarioEquipoComputo, 
                    eNumeroSerieGabineteEquipoComputo, eNumeroSerieMouseEquipoComputo, 
                    eNumeroSerieTecladoEquipoComputo, bEstadoEquipoComputo, 
                    fhFechaHoraActualizacionEquipoComputo) 
                    VALUES 
                    ('$tMarcaEquipoComputo', '$tModeloEquipoComputo', '$eNumeroInventarioEquipoComputo', 
                    '$eNumeroSerieGabineteEquipoComputo', '$eNumeroSerieMouseEquipoComputo', 
                    '$eNumeroSerieTecladoEquipoComputo', '$bEstadoEquipoComputo', 
                    '$fhFechaHoraActualizacionEquipoComputo')";
        }

        if ($conn->query($sql) === TRUE) {
            echo '<script>
                    $(document).ready(function() {
                        $("#successModalComputo").modal("show");
                        setTimeout(function(){
                            
                        }, 1000);
                    });
                  </script>';
        } else {
            echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    }

    // Manejo de solicitud para eliminar un equipo
    if (isset($_POST['eliminarEquipoId'])) {
        $id = $_POST['eliminarEquipoId'];
        $sql = "DELETE FROM equipocomputo WHERE eCodEquipoComputo=$id";

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

    // Manejo de solicitud para activar o desactivar un equipo
    if (isset($_POST['activarEquipoId'])) {
        $id = $_POST['activarEquipoId'];
        $sql = "UPDATE equipocomputo SET bEstadoEquipoComputo = NOT bEstadoEquipoComputo WHERE eCodEquipoComputo=$id";

        if ($conn->query($sql) === TRUE) {
            echo '<script>
                    $(document).ready(function() {

                    });
                  </script>';
        } else {
            echo "<p>Error al cambiar el estado del equipo: " . $conn->error . "</p>";
        }
    }

    $conn->close();
}
?>

<br />
<center>
    <div class="container">
        <center>
            <h2>Registro de Equipo de Cómputo</h2>
        </center>
        <div class="text-left mt-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModalEquipos">
                Agregar Equipo de Computo
            </button>
        </div>
        <?php
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        $sql = "SELECT * FROM equipocomputo";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo '<table id="tabla_equipos" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>No. de Inventario</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>No. de serie del gabinete</th>
                            <th>No. de serie del teclado</th>
                            <th>No. de serie del mouse</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>';
            while ($row = $result->fetch_assoc()) {
                $estadoEquipo = $row['bEstadoEquipoComputo'] ? 'Activo' : 'Inactivo';
                $acciones = '<!--<button type="button" class="btn btn-info btn-sm editar-equipo" data-id="' . $row['eCodEquipoComputo'] . '" data-bs-toggle="tooltip" title="Editar">
                                <i class="fas fa-edit"></i>
                             </button>-->
                             <button type="button" class="btn btn-danger btn-sm eliminar-equipo" data-id="' . $row['eCodEquipoComputo'] . '" data-bs-toggle="tooltip" title="Eliminar">
                                <i class="fas fa-trash"></i>
                             </button>';

                if ($row['bEstadoEquipoComputo']) {
                    $acciones .= '<!--<button type="button" class="btn btn-warning btn-sm desactivar-equipo" data-id="' . $row['eCodEquipoComputo'] . '" data-bs-toggle="tooltip" title="Desactivar">
                                    <i class="fas fa-eye-slash"></i>
                                  </button>-->';
                } else {
                    $acciones .= '<!--<button type="button" class="btn btn-success btn-sm activar-equipo" data-id="' . $row['eCodEquipoComputo'] . '" data-bs-toggle="tooltip" title="Activar">
                                    <i class="fas fa-eye"></i>
                                  </button>-->';
                }

                echo '<tr>
                        <td>' . $row['eCodEquipoComputo'] . '</td>
                        <td>' . $row['eNumeroInventarioEquipoComputo'] . '</td>
                        <td>' . $row['tMarcaEquipoComputo'] . '</td>
                        <td>' . $row['tModeloEquipoComputo'] . '</td>
                        <td>' . $row['eNumeroSerieGabineteEquipoComputo'] . '</td>
                        <td>' . $row['eNumeroSerieTecladoEquipoComputo'] . '</td>
                        <td>' . $row['eNumeroSerieMouseEquipoComputo'] . '</td>
                        <td>' . $estadoEquipo . '</td>
                        <td>' . $acciones . '</td>
                      </tr>';
            }
            echo '</tbody>
                  </table>';
        } else {
            echo "<p>No se encontraron equipos de cómputo.</p>";
        }
        $conn->close();
        ?>
    </div>
</center>

<!-- Modal para agregar/editar equipo de cómputo -->
<div class="modal fade" id="myModalEquipos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Agregar Equipo de Cómputo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEquipo" method="POST" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tMarcaEquipoComputo">Marca:</label>
                        <input type="text" class="form-control" id="tMarcaEquipoComputo" name="tMarcaEquipoComputo" required>
                    </div>
                    <div class="form-group">
                        <label for="tModeloEquipoComputo">Modelo:</label>
                        <input type="text" class="form-control" id="tModeloEquipoComputo" name="tModeloEquipoComputo" required>
                    </div>
                    <div class="form-group">
                        <label for="eNumeroInventarioEquipoComputo">Número de Inventario:</label>
                        <input type="number" class="form-control" id="eNumeroInventarioEquipoComputo" name="eNumeroInventarioEquipoComputo" required>
                    </div>
                    <div class="form-group">
                        <label for="eNumeroSerieGabineteEquipoComputo">Número de Serie del Gabinete:</label>
                        <input type="text" class="form-control" id="eNumeroSerieGabineteEquipoComputo" name="eNumeroSerieGabineteEquipoComputo" required>
                    </div>
                    <div class="form-group">
                        <label for="eNumeroSerieMouseEquipoComputo">Número de Serie del Mouse:</label>
                        <input type="text" class="form-control" id="eNumeroSerieMouseEquipoComputo" name="eNumeroSerieMouseEquipoComputo" required>
                    </div>
                    <div class="form-group">
                        <label for="eNumeroSerieTecladoEquipoComputo">Número de Serie del Teclado:</label>
                        <input type="text" class="form-control" id="eNumeroSerieTecladoEquipoComputo" name="eNumeroSerieTecladoEquipoComputo" required>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="bEstadoEquipoComputo" name="bEstadoEquipoComputo" value="1" checked>
                        <label class="form-check-label" for="bEstadoEquipoComputo">Activo</label>
                    </div>
                    <input type="hidden" id="fhFechaHoraActualizacionEquipoComputo" name="fhFechaHoraActualizacionEquipoComputo" value="<?php echo date('Y-m-d H:i:s'); ?>">
                    <input type="hidden" id="eCodEquipoComputo" name="eCodEquipoComputo">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para confirmación de eliminación -->
<div class="modal fade" id="eliminarModal" tabindex="-1" role="dialog" aria-labelledby="eliminarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eliminarModalLabel">Confirmación de Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Está seguro que desea eliminar este equipo de cómputo?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmarEliminar">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Registro Exitoso -->
<div id="successModalComputo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Registro Exitoso</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>Equipo de cómputo guardado exitosamente</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Eliminación Exitosa -->
<div id="successModalEliminar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Eliminación Exitosa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>Equipo de cómputo eliminado exitosamente</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js"></script>
<script>
$(document).ready(function() {
    // Editar equipo de cómputo
    $(document).on("click", ".editar-equipo", function() {
        var equipoId = $(this).data('id');
        $.ajax({
            type: "GET",
            url: "editar_equipo.php",
            data: { id: equipoId },
            success: function(response) {
                $("#formEquipo").html(response);
                $("#myModalEquipos").modal("show");
            }
        });
    });

    // Eliminar equipo de cómputo
    $(document).on("click", ".eliminar-equipo", function() {
        var equipoId = $(this).data('id');
        $("#eliminarModal").modal("show");
        $("#confirmarEliminar").off().on("click", function() {
            $.ajax({
                type: "POST",
                url: "registrar-equipo-computo.php",
                data: { eliminarEquipoId: equipoId },
                success: function(response) {
                    $("#successModalEliminar").modal("show");
                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                }
            });
        });
    });

    // Activar/Desactivar equipo de cómputo
    $(document).on("click", ".activar-equipo, .desactivar-equipo", function() {
        var equipoId = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "registrar-equipo-computo.php",
            data: { activarEquipoId: equipoId },
            success: function(response) {
                location.reload();
            }
        });
    });
});
</script>
<?php include("footer.php"); ?>
