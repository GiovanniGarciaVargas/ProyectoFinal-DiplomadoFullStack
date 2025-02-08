<?php
include("header.php");
include("menu.php");
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet">

<br />
<center>
    <div class="container">
        <center>
            <h2>Registro de Equipo de Audio</h2>
        </center>
        <div class="text-left mt-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModalEquipos">
                Agregar Equipo de Audio
            </button>
        </div>

        <?php
        // Datos de conexión a la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "incidenciasump";

        // Crear conexión
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Comprobar conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Insertar datos si el formulario ha sido enviado
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'insertar') {
            // Recoger datos del formulario
            $marca = $_POST['tMarcaAmplificador'];
            $modelo = $_POST['tModeloAmplificador'];
            $numeroInventario = $_POST['eNumeroInventarioAmplificador'];
            $numeroSerie = $_POST['eNumeroSerieAmplificador'];
            $estado = isset($_POST['bEstadoAmplificador']) ? 1 : 0;
            $fechaCreacion = $_POST['fhFechaHoraCreacionAmplificador'];
            $fechaActualizacion = $_POST['fhFechaHoraActualizacionAmplificador'];

            // Consulta de inserción
            $sqlInsert = "INSERT INTO equipoaudio (tMarcaAmplificador, tModeloAmplificador, eNumeroInventarioAmplificador, eNumeroSerieAmplificador, bEstadoAmplificador, fhFechaHoraCreacionAmplificador, fhFechaHoraActualizacionAmplificador)
                          VALUES ('$marca', '$modelo', '$numeroInventario', '$numeroSerie', '$estado', '$fechaCreacion', '$fechaActualizacion')";

            // Ejecutar la consulta y comprobar si se ha insertado correctamente
            if ($conn->query($sqlInsert) === TRUE) {
                echo '<script>
                        $(document).ready(function(){
                            $("#successModalAudio").modal("show");
                        });
                      </script>';
            } else {
                echo "Error al insertar los datos: " . $conn->error;
            }
        }

        // Eliminar un equipo si se recibe la solicitud de eliminar
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'eliminar') {
            $idEliminar = $_POST['idEliminar'];

            $sqlEliminar = "DELETE FROM equipoaudio WHERE eCodAmplificador = '$idEliminar'";

            if ($conn->query($sqlEliminar) === TRUE) {
                echo '<script>
                        $(document).ready(function(){
                            $("#successModalEliminar").modal("show");
                        });
                      </script>';
            } else {
                echo "Error al eliminar el equipo: " . $conn->error;
            }
        }

        // Mostrar los equipos ya registrados
        $sql = "SELECT * FROM equipoaudio";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo '<table id="tabla_equipo" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>No. de Inventario</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>No. de serie</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>';
                    while ($row = $result->fetch_assoc()) {
                        $acciones = '';
                    
                        if ($row['bEstadoAmplificador']) {
                            $acciones .= '<a href="cambiar_estado_audio.php?id=' . $row['eCodAmplificador'] . '" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Eliminar">
                                            <i class="fas fa-eye"></i>
                                          </a>';
                        } else {
                            $acciones .= '<a href="cambiar_estado_audio.php?id=' . $row['eCodAmplificador'] . '" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Activar">
                                            <i class="fas fa-eye-slash"></i>
                                          </a>';
                        }
                    
                        $acciones .= '<button class="btn btn-danger btn-sm eliminar-equipo" data-id="' . $row['eCodAmplificador'] . '" data-bs-toggle="tooltip" title="Eliminar" data-bs-toggle="modal" data-bs-target="#confirmarEliminarModal">
                                        <i class="fas fa-trash-alt"></i>
                                      </button>';

                echo '<tr>
                        <td>' . $row['eCodAmplificador'] . '</td>
                        <td>' . $row['eNumeroInventarioAmplificador'] . '</td>
                        <td>' . $row['tMarcaAmplificador'] . '</td>
                        <td>' . $row['tModeloAmplificador'] . '</td>
                        <td>' . $row['eNumeroSerieAmplificador'] . '</td>
                        <td>' . ($row['bEstadoAmplificador'] ? "Activo" : "Inactivo") . '</td>
                        <td>' . $acciones . '</td>
                      </tr>';
            }
            echo '</tbody></table>';
        } else {
            echo "No se encontraron resultados";
        }

        // Cerrar la conexión
        $conn->close();
        ?>
    </div>
</center>

<!-- Modal para Agregar Equipo de Audio -->
<div id="myModalEquipos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Nuevo Equipo de Audio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="equipoForm" method="POST" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tMarcaAmplificador">Marca:</label>
                        <input type="text" class="form-control" id="tMarcaAmplificador" name="tMarcaAmplificador" required>
                    </div>
                    <div class="form-group">
                        <label for="tModeloAmplificador">Modelo:</label>
                        <input type="text" class="form-control" id="tModeloAmplificador" name="tModeloAmplificador" required>
                    </div>
                    <div class="form-group">
                        <label for="eNumeroInventarioAmplificador">Número de Inventario:</label>
                        <input type="number" class="form-control" id="eNumeroInventarioAmplificador" name="eNumeroInventarioAmplificador" required>
                    </div>
                    <div class="form-group">
                        <label for="eNumeroSerieAmplificador">Número de Serie:</label>
                        <input type="text" class="form-control" id="eNumeroSerieAmplificador" name="eNumeroSerieAmplificador" required>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="bEstadoAmplificador" name="bEstadoAmplificador" value="1" checked>
                        <label class="form-check-label" for="bEstadoAmplificador">Activo</label>
                    </div>
                    <input type="hidden" id="fhFechaHoraCreacionAmplificador" name="fhFechaHoraCreacionAmplificador" value="<?php echo date('Y-m-d H:i:s'); ?>">
                    <input type="hidden" id="fhFechaHoraActualizacionAmplificador" name="fhFechaHoraActualizacionAmplificador" value="<?php echo date('Y-m-d H:i:s'); ?>">
                    <input type="hidden" name="accion" value="insertar">
                </div>
                <div class="modal-footer">               
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Eliminación -->
<div id="confirmarEliminarModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar este equipo de audio?</p>
            </div>
            <div class="modal-footer">
                <form id="eliminarForm" method="POST" action="">
                    <input type="hidden" id="idEliminar" name="idEliminar" value="">
                    <input type="hidden" name="accion" value="eliminar">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Éxito al Eliminar -->
<div id="successModalEliminar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Éxito</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>El equipo de audio ha sido eliminado exitosamente.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js"></script>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();

        // Pasar el ID del equipo al modal de eliminación
        $('.eliminar-equipo').on('click', function () {
            var id = $(this).data('id');
            $('#idEliminar').val(id);
            $('#confirmarEliminarModal').modal('show');
        });
    });
</script>

