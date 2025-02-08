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
    $tMarcaCamaraWeb = $_POST['tMarcaCamaraWeb'];
    $tModeloCamaraWeb = $_POST['tModeloCamaraWeb'];
    $eNumeroInventarioCamara = $_POST['eNumeroInventarioCamara'];
    $eNumeroSerieCamara = $_POST['eNumeroSerieCamara'];
    $bEstadoCamara = isset($_POST['bEstadoCamara']) ? 1 : 0;
    $fhFechaHoraCreacionCamara = date('Y-m-d H:i:s');
    $fhFechaHoraActualizacionCamara = date('Y-m-d H:i:s');

    $sql = "INSERT INTO camaraweb (tMarcaCamaraWeb, tModeloCamaraWeb, eNumeroInventarioCamara, 
            eNumeroSerieCamara, bEstadoCamara, fhFechaHoraCreacionCamara, 
            fhFechaHoraActualizacionCamara)
            VALUES ('$tMarcaCamaraWeb', '$tModeloCamaraWeb', '$eNumeroInventarioCamara', 
            '$eNumeroSerieCamara', '$bEstadoCamara', '$fhFechaHoraCreacionCamara', 
            '$fhFechaHoraActualizacionCamara')";

    if ($conn->query($sql) === TRUE) {
        echo '<script>
                $(document).ready(function() {
                    $("#successModalCamara").modal("show");
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
            <h2>Registro de Cámara Web</h2>
        </center>
        <div class="text-left mt-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModalCamara">
                Agregar Cámara Web
            </button>
        </div>
        <?php
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        $sql = "SELECT * FROM camaraweb";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo '<table id="tabla_camaras" class="table table-striped table-bordered" style="width:100%">
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
                $estadoCamara = $row['bEstadoCamara'] ? 'Activo' : 'Inactivo';
                $acciones = '<!--<a href="editar_camara.php?id=' . $row['eCodCamaraWeb'] . '" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Editar">
                                <i class="fas fa-edit"></i>
                             </a>-->';

                if ($row['bEstadoCamara']) {
                    $acciones .= '<a href="cambiar_estado_camara.php?id=' . $row['eCodCamaraWeb'] . '" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Desactivar">
                                    <i class="fas fa-trash-alt"></i>
                                  </a>';
                } else {
                    $acciones .= '<a href="cambiar_estado_camara.php?id=' . $row['eCodCamaraWeb'] . '" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Activar">
                                    <i class="fas fa-check"></i>
                                  </a>';
                }
                echo '<tr>
                        <td>' . $row['eCodCamaraWeb'] . '</td>
                        <td>' . $row['eNumeroInventarioCamara'] . '</td>
                        <td>' . $row['tMarcaCamaraWeb'] . '</td>
                        <td>' . $row['tModeloCamaraWeb'] . '</td>
                        <td>' . $row['eNumeroSerieCamara'] . '</td>
                        <td>' . $estadoCamara . '</td>
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

<!-- Modal para Agregar Cámara Web -->
<div id="myModalCamara" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Nueva Cámara Web</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="camaraForm" method="POST" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tMarcaCamaraWeb">Marca:</label>
                        <input type="text" class="form-control" id="tMarcaCamaraWeb" name="tMarcaCamaraWeb" required>
                    </div>
                    <div class="form-group">
                        <label for="tModeloCamaraWeb">Modelo:</label>
                        <input type="text" class="form-control" id="tModeloCamaraWeb" name="tModeloCamaraWeb" required>
                    </div>
                    <div class="form-group">
                        <label for="eNumeroInventarioCamara">Número de Inventario:</label>
                        <input type="number" class="form-control" id="eNumeroInventarioCamara" name="eNumeroInventarioCamara" required>
                    </div>
                    <div class="form-group">
                        <label for="eNumeroSerieCamara">Número de Serie:</label>
                        <input type="text" class="form-control" id="eNumeroSerieCamara" name="eNumeroSerieCamara" required>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="bEstadoCamara" name="bEstadoCamara" value="1" checked>
                        <label class="form-check-label" for="bEstadoCamara"> Activo</label>
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
<div id="successModalCamara" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="successModalLabelCamara" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabelCamara">Éxito</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>La cámara web se ha registrado exitosamente.</p>
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
    $("[name='bEstadoCamara']").bootstrapSwitch();
});
</script>
<?php include("footer.php"); ?>
