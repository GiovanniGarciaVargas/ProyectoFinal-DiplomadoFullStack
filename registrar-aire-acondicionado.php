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
    $tMarcaAireAcondicionado = $_POST['tMarcaAireAcondicionado'];
    $tModeloAireAcondicionado = $_POST['tModeloAireAcondicionado'];
    $eNumeroInventarioAire = $_POST['eNumeroInventarioAire'];
    $eNumeroSerieAire = $_POST['eNumeroSerieAire'];
    $bEstadoAire = isset($_POST['bEstadoAire']) ? 1 : 0;
    $fhFechaHoraCreacionAire = date('Y-m-d H:i:s');
    $fhFechaHoraActualizacionAire = date('Y-m-d H:i:s');

    $sql = "INSERT INTO aireacondicionado (tMarcaAireAcondicionado, tModeloAireAcondicionado, eNumeroInventarioAire, 
            eNumeroSerieAire, bEstadoAire, fhFechaHoraCreacionAire, 
            fhFechaHoraActualizacionAire)
            VALUES ('$tMarcaAireAcondicionado', '$tModeloAireAcondicionado', '$eNumeroInventarioAire', 
            '$eNumeroSerieAire', '$bEstadoAire', '$fhFechaHoraCreacionAire', 
            '$fhFechaHoraActualizacionAire')";

    if ($conn->query($sql) === TRUE) {
        echo '<script>
                $(document).ready(function() {
                    $("#successModalAire").modal("show");
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
            <h2>Registro de Aire Acondicionado</h2>
        </center>
        <div class="text-left mt-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModalAire">
                Agregar Aire Acondicionado
            </button>
        </div>
        <?php
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        $sql = "SELECT * FROM aireacondicionado";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo '<table id="tabla_aires" class="table table-striped table-bordered" style="width:100%">
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
                $estadoAire = $row['bEstadoAire'] ? 'Activo' : 'Inactivo';
                $acciones = '<!--<a href="editar_aire.php?id=' . $row['eCodAireAcondicionado'] . '" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Editar">
                                <i class="fas fa-edit"></i>
                             </a>-->';

                if ($row['bEstadoAire']) {
                    $acciones .= '<a href="cambiar_estado_aire.php?id=' . $row['eCodAireAcondicionado'] . '" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Desactivar">
                                    <i class="fas fa-trash-alt"></i>
                                  </a>';
                } else {
                    $acciones .= '<a href="cambiar_estado_aire.php?id=' . $row['eCodAireAcondicionado'] . '" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Activar">
                                    <i class="fas fa-check"></i>
                                  </a>';
                }
                echo '<tr>
                        <td>' . $row['eCodAireAcondicionado'] . '</td>
                        <td>' . $row['eNumeroInventarioAire'] . '</td>
                        <td>' . $row['tMarcaAireAcondicionado'] . '</td>
                        <td>' . $row['tModeloAireAcondicionado'] . '</td>
                        <td>' . $row['eNumeroSerieAire'] . '</td>
                        <td>' . $estadoAire . '</td>
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

<!-- Modal para Agregar Aire Acondicionado -->
<div id="myModalAire" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Nuevo Aire Acondicionado</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="aireForm" method="POST" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tMarcaAireAcondicionado">Marca:</label>
                        <input type="text" class="form-control" id="tMarcaAireAcondicionado" name="tMarcaAireAcondicionado" required>
                    </div>
                    <div class="form-group">
                        <label for="tModeloAireAcondicionado">Modelo:</label>
                        <input type="text" class="form-control" id="tModeloAireAcondicionado" name="tModeloAireAcondicionado" required>
                    </div>
                    <div class="form-group">
                        <label for="eNumeroInventarioAire">Número de Inventario:</label>
                        <input type="number" class="form-control" id="eNumeroInventarioAire" name="eNumeroInventarioAire" required>
                    </div>
                    <div class="form-group">
                        <label for="eNumeroSerieAire">Número de Serie:</label>
                        <input type="text" class="form-control" id="eNumeroSerieAire" name="eNumeroSerieAire" required>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="bEstadoAire" name="bEstadoAire" value="1" checked>
                        <label class="form-check-label" for="bEstadoAire">Activo</label>
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
<div id="successModalAire" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="successModalLabelAire" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabelAire">Éxito</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>El aire acondicionado se ha registrado exitosamente.</p>
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
    $("[name='bEstadoAire']").bootstrapSwitch();
});
</script>
<?php include("footer.php"); ?>
