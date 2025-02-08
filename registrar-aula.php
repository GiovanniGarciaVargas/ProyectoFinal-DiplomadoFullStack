<?php include("header.php"); ?>
<?php include("menu.php"); ?>
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
    $fk_eCodPiso = $_POST['fk_eCodPiso'];
    $tNombreAula = $_POST['tNombreAula'];
    $bEstadoAula = isset($_POST['bEstadoAula']) ? 1 : 0;
    $fhFechaHoraCreacionAula = date('Y-m-d H:i:s');
    $fhFechaHoraActualizacionAula = date('Y-m-d H:i:s');

    $sql = "INSERT INTO aula (fk_eCodPiso, tNombreAula, bEstadoAula, fhFechaHoraCreacionAula, fhFechaHoraActualizacionAula)
            VALUES ('$fk_eCodPiso', '$tNombreAula', '$bEstadoAula', '$fhFechaHoraCreacionAula', '$fhFechaHoraActualizacionAula')";

    if ($conn->query($sql) === TRUE) {
        echo '<script>
                $(document).ready(function() {
                    $("#successModalAula").modal("show");
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
            <h2>Registro de Aula</h2>
        </center>
        <div class="text-left mt-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModalAula">
                Agregar Aula
            </button>
        </div>
        <?php
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        $sql = "SELECT a.*, p.tNombrePlanta FROM aula a
                INNER JOIN plantaedificio p ON a.fk_eCodPiso = p.eCodPlanta";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo '<table id="tabla_aulas" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Aula</th>
                            <th>Planta</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>';
            while ($row = $result->fetch_assoc()) {
                $estadoAula = $row['bEstadoAula'] ? 'Activo' : 'Inactivo';
                $acciones = '<!--<a href="editar_aula.php?id=' . $row['eCodAula'] . '" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Editar">
                                <i class="fas fa-edit"></i>
                             </a>
                             <a href="editar_aula.php?id=' . $row['eCodAula'] . '" class="btn btn-suc btn-sm" data-bs-toggle="tooltip" title="Editar">
                                <i class="fas fa-edit"></i>
                             </a>-->
                             ';

                if ($row['bEstadoAula']) {
                    $acciones .= '<a href="cambiar_estado_aula.php?id=' . $row['eCodAula'] . '" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Desactivar">
                                    <i class="fas fa-trash-alt"></i>
                                  </a>';
                } else {
                    $acciones .= '<a href="cambiar_estado_aula.php?id=' . $row['eCodAula'] . '" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Activar">
                                    <i class="fas fa-check"></i>
                                  </a>';
                }
                echo '<tr>
                        <td>' . $row['eCodAula'] . '</td>
                        <td>' . $row['tNombreAula'] . '</td>
                        <td>' . $row['tNombrePlanta'] . '</td>
                        <td>' . $estadoAula . '</td>
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

<!-- Modal para Agregar Aula -->
<div id="myModalAula" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Nueva Aula</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="aulaForm" method="POST" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="fk_eCodPiso">Planta:</label>
                        <select class="form-control" id="fk_eCodPiso" name="fk_eCodPiso" required>
                            <option value="">Selecciona una planta</option>
                            <?php
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Conexión fallida: " . $conn->connect_error);
                            }
                            $sql = "SELECT * FROM plantaedificio";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['eCodPlanta'] . '">' . $row['tNombrePlanta'] . '</option>';
                                }
                            }
                            $conn->close();
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="eNumeroAula">Aula</label>
                        <input type="text" class="form-control" id="tNombreAula" name="tNombreAula" required>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="bEstadoAula" name="bEstadoAula" value="1" checked>
                        <label class="form-check-label" for="bEstadoAula">Estado Activo</label>
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
<div id="successModalAula" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="successModalLabelAula" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabelAula">Éxito</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>El aula se ha registrado exitosamente.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
