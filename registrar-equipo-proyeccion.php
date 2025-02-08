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
    $tMarcaProyector = $_POST['tMarcaProyector'];
    $tModeloProyector = $_POST['tModeloProyector'];
    $eNumeroInventarioProyector = $_POST['eNumeroInventarioProyector'];
    $eNumeroSerieProyector = $_POST['eNumeroSerieProyector'];
    $bEstadoProyector = isset($_POST['bEstadoProyector']) ? 1 : 0;
    $fhFechaHoraCreacion = $_POST['fhFechaHoraCreacion'];
    $fhFechaHoraActualizacion = $_POST['fhFechaHoraActualizacion'];

    $sql = "INSERT INTO proyector (tMarcaProyector, tModeloProyector, eNumeroInventarioProyector, 
            eNumeroSerieProyector, bEstadoProyector, fhFechaHoraCreacion, 
            fhFechaHoraActualizacion)
            VALUES ('$tMarcaProyector', '$tModeloProyector', '$eNumeroInventarioProyector', 
            '$eNumeroSerieProyector', '$bEstadoProyector', '$fhFechaHoraCreacion', 
            '$fhFechaHoraActualizacion')";

    if ($conn->query($sql) === TRUE) {
        echo '<script>
                $(document).ready(function() {
                    $("#successModalProyector").modal("show");
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
            <h2>Registro de Equipo de Proyección</h2>
        </center>
        <div class="text-left mt-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModalEquipos">
                Agregar Equipo de Proyección
            </button>
        </div>
        <?php
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        $sql = "SELECT * FROM proyector";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo '<table id="tabla_equipos" class="table table-striped table-bordered" style="width:100%">
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
                $estadoEquipo = $row['bEstadoProyector'] ? 'Activo' : 'Inactivo';
                $acciones = '<!--<a href="editar_equipo_proyeccion.php?id=' . $row['eCodProyector'] . '" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Editar">
                                <i class="fas fa-edit"></i>
                             </a>-->
                             <a href="eliminar_proyector.php?id=' . $row['eCodProyector'] . '" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                  </a>';

                if ($row['bEstadoProyector']) {
                    $acciones .= '<a href="cambiar_estado_proyector.php?id=' . $row['eCodProyector'] . '" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Eliminar">
                                    <i class="fas fa-eye"></i>
                                  </a>';
                } else {
                    $acciones .= '<a href="cambiar_estado_proyector.php?id=' . $row['eCodProyector'] . '" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Activar">
                                    <i class="fas fa-eye-slash"></i>
                                  </a>';
                }
                echo '<tr>
                        <td>' . $row['eCodProyector'] . '</td>
                        <td>' . $row['eNumeroInventarioProyector'] . '</td>
                        <td>' . $row['tMarcaProyector'] . '</td>
                        <td>' . $row['tModeloProyector'] . '</td>
                        <td>' . $row['eNumeroSerieProyector'] . '</td>
                        <td>' . $estadoEquipo . '</td>
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

<!-- Modal para Agregar Equipo de Proyección -->
<div id="myModalEquipos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Nuevo Equipo de Proyección</h5>
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="equipoForm" method="POST" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tMarcaProyector">Marca:</label>
                        <input type="text" class="form-control" id="tMarcaProyector" name="tMarcaProyector" required>
                    </div>
                    <div class="form-group">
                        <label for="tModeloProyector">Modelo:</label>
                        <input type="text" class="form-control" id="tModeloProyector" name="tModeloProyector" required>
                    </div>
                    <div class="form-group">
                        <label for="eNumeroInventarioProyector">Número de Inventario:</label>
                        <input type="number" class="form-control" id="eNumeroInventarioProyector" name="eNumeroInventarioProyector" required>
                    </div>
                    <div class="form-group">
                        <label for="eNumeroSerieProyector">Número de Serie:</label>
                        <input type="text" class="form-control" id="eNumeroSerieProyector" name="eNumeroSerieProyector" required>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="bEstadoProyector" name="bEstadoProyector" value="1" checked>
                        <label class="form-check-label" for="bEstadoProyector">Activo</label>
                    </div>
                    <input type="hidden" id="fhFechaHoraCreacion" name="fhFechaHoraCreacion" value="<?php echo date('Y-m-d H:i:s'); ?>">
                    <input type="hidden" id="fhFechaHoraActualizacion" name="fhFechaHoraActualizacion" value="<?php echo date('Y-m-d H:i:s'); ?>">
                </div>
                <div class="modal-footer">               
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Registro Exitoso -->
<div id="successModalProyector" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Registro Exitoso</h5>
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p>Nuevo equipo de proyección creado exitosamente</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js"></script>
<?php include("footer.php"); ?>
