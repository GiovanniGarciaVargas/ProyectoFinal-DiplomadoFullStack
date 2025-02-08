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
    $tMarcaRegulador = $_POST['tMarcaRegulador'];
    $tModeloRegulador = $_POST['tModeloRegulador'];
    $eNumeroInventarioRegulador = $_POST['eNumeroInventarioRegulador'];
    $eNumeroSerieInventario = $_POST['eNumeroSerieInventario'];
    $bEstadoRegulador = isset($_POST['bEstadoRegulador']) ? 1 : 0;
    $fhFechaHoraCreacionRegulador = date('Y-m-d H:i:s');
    $fhFechaHoraActualizacionRegulador = date('Y-m-d H:i:s');

    $sql = "INSERT INTO regulador (tMarcaRegulador, tModeloRegulador, eNumeroInventarioRegulador, 
            eNumeroSerieInventario, bEstadoRegulador, fhFechaHoraCreacionRegulador, 
            fhFechaHoraActualizacionRegulador)
            VALUES ('$tMarcaRegulador', '$tModeloRegulador', '$eNumeroInventarioRegulador', 
            '$eNumeroSerieInventario', '$bEstadoRegulador', '$fhFechaHoraCreacionRegulador', 
            '$fhFechaHoraActualizacionRegulador')";

    if ($conn->query($sql) === TRUE) {
        echo '<script>
                $(document).ready(function() {
                    $("#successModalRegulador").modal("show");
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
            <h2>Registro de Regulador</h2>
        </center>
        <div class="text-left mt-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModalRegulador">
                Agregar Regulador
            </button>
        </div>
        <?php
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        $sql = "SELECT * FROM regulador";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo '<table id="tabla_reguladores" class="table table-striped table-bordered" styWle="width:100%">
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
                $estadoRegulador = $row['bEstadoRegulador'] ? 'Activo' : 'Inactivo';
                $acciones = '<!--<a href="editar_regulador.php?id=' . $row['eCodRegulador'] . '" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Editar">
                                <i class="fas fa-edit"></i>
                             </a>-->';

                if ($row['bEstadoRegulador']) {
                    $acciones .= '<a href="cambiar_estado_regulador.php?id=' . $row['eCodRegulador'] . '" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Desactivar">
                                    <i class="fas fa-trash-alt"></i>
                                  </a>';
                } else {
                    $acciones .= '<a href="cambiar_estado_regulador.php?id=' . $row['eCodRegulador'] . '" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Activar">
                                    <i class="fas fa-check"></i>
                                  </a>';
                }
                echo '<tr>
                        <td>' . $row['eCodRegulador'] . '</td>
                        <td>' . $row['eNumeroInventarioRegulador'] . '</td>
                        <td>' . $row['tMarcaRegulador'] . '</td>
                        <td>' . $row['tModeloRegulador'] . '</td>
                        <td>' . $row['eNumeroSerieInventario'] . '</td>
                        <td>' . $estadoRegulador . '</td>
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

<!-- Modal para Agregar Regulador -->
<div id="myModalRegulador" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Nuevo Regulador</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="reguladorForm" method="POST" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tMarcaRegulador">Marca:</label>
                        <input type="text" class="form-control" id="tMarcaRegulador" name="tMarcaRegulador" required>
                    </div>
                    <div class="form-group">
                        <label for="tModeloRegulador">Modelo:</label>
                        <input type="text" class="form-control" id="tModeloRegulador" name="tModeloRegulador" required>
                    </div>
                    <div class="form-group">
                        <label for="eNumeroInventarioRegulador">Número de Inventario:</label>
                        <input type="number" class="form-control" id="eNumeroInventarioRegulador" name="eNumeroInventarioRegulador" required>
                    </div>
                    <div class="form-group">
                        <label for="eNumeroSerieInventario">Número de Serie:</label>
                        <input type="text" class="form-control" id="eNumeroSerieInventario" name="eNumeroSerieInventario" required>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="bEstadoRegulador" name="bEstadoRegulador" value="1" checked>
                        <label class="form-check-label" for="bEstadoRegulador">Estado Activo</label>
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
<div id="successModalRegulador" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="successModalLabelRegulador" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabelRegulador">Éxito</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>El regulador se ha registrado exitosamente.</p>
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
    $("[name='bEstadoRegulador']").bootstrapSwitch();
});
</script>
<?php include("footer.php"); ?>
