<?php include("header.php"); ?>
<?php include("menu.php"); ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet">
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "incidenciasump";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    $tNombrePlanta = $_POST['tNombrePlanta'];
    $bEstadoPlanta = isset($_POST['bEstadoPlanta']) ? 1 : 0;
    $fhFechaHoraCreacionPlanta = date('Y-m-d H:i:s');
    $fhFechaHoraActualizacionPlanta = date('Y-m-d H:i:s');

    $sql = "INSERT INTO plantaedificio (tNombrePlanta, bEstadoPlanta, fhFechaHoraCreacionPlanta, fhFechaHoraActualizacionPlanta)
            VALUES ('$tNombrePlanta', '$bEstadoPlanta', '$fhFechaHoraCreacionPlanta', '$fhFechaHoraActualizacionPlanta')";

    if ($conn->query($sql) === TRUE) {
        echo '<script>
                $(document).ready(function() {
                    $("#successModalPlanta").modal("show");
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
            <h2>Registro de Planta</h2>
        </center>
        <div class="text-left mt-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModalPlanta">
                Agregar Planta
            </button>
        </div>
        <?php
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        $sql = "SELECT * FROM plantaedificio";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo '<table id="tabla_plantas" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>';
            while ($row = $result->fetch_assoc()) {
                $estadoPlanta = $row['bEstadoPlanta'] ? 'Activo' : 'Inactivo';
                $acciones = '<!--<a href="editar_planta.php?id=' . $row['eCodPlanta'] . '" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Editar">
                                <i class="fas fa-edit"></i>
                             </a>-->';

                if ($row['bEstadoPlanta']) {
                    $acciones .= '<a href="cambiar_estado_planta.php?id=' . $row['eCodPlanta'] . '" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Desactivar">
                                    <i class="fas fa-trash-alt"></i>
                                  </a>';
                } else {
                    $acciones .= '<a href="cambiar_estado_planta.php?id=' . $row['eCodPlanta'] . '" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Activar">
                                    <i class="fas fa-check"></i>
                                  </a>';
                }
                echo '<tr>
                        <td>' . $row['eCodPlanta'] . '</td>
                        <td>' . $row['tNombrePlanta'] . '</td>
                        <td>' . $estadoPlanta . '</td>
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

<!-- Modal para Agregar Planta -->
<div id="myModalPlanta" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Nueva Planta</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="plantaForm" method="POST" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tNombrePlanta">Nombre:</label>
                        <input type="text" class="form-control" id="tNombrePlanta" name="tNombrePlanta" required>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="bEstadoPlanta" name="bEstadoPlanta" value="1" checked>
                        <label class="form-check-label" for="bEstadoPlanta"> Activo</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Mensaje de éxito -->
<div id="successModalPlanta" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="successModalLabelPlanta" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabelPlanta">Éxito</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>La planta se ha registrado exitosamente.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js"></script>
<script>
$(document).ready(function() {
    $("[data-bs-toggle='tooltip']").tooltip();
    $("[name='bEstadoPlanta']").bootstrapSwitch();
});
</script>
<?php include("footer.php"); ?>
