<?php include("header.php"); ?>
<?php include("menu.php"); ?>
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

    if (isset($_POST['tNombrePuesto']) && !isset($_POST['eCodPuesto'])) {
        // Insertar nuevo puesto
        $tNombrePuesto = $_POST['tNombrePuesto'];
        $bEstadoPuesto = isset($_POST['bEstadoPuesto']) ? 1 : 0;
        $fhFechaHoraCreacionPuesto = $_POST['fhFechaHoraCreacionPuesto'];
        $fhFechaHoraActualizacionPuesto = $_POST['fhFechaHoraActualizacionPuesto'];
        $sql = "INSERT INTO puesto (tNombrePuesto, bEstadoPuesto, fhFechaHoraCreacionPuesto, fhFechaHoraActualizacionPuesto)
                VALUES ('$tNombrePuesto', '$bEstadoPuesto', '$fhFechaHoraCreacionPuesto', '$fhFechaHoraActualizacionPuesto')";
        if ($conn->query($sql) === TRUE) {
            echo "<p>Puesto registrado con éxito.</p>";
        } else {
            echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    } elseif (isset($_POST['delete_puesto_id'])) {
        // Eliminar puesto
        $puestoId = $_POST['delete_puesto_id'];
        $sql = "DELETE FROM puesto WHERE eCodPuesto = '$puestoId'";
        if ($conn->query($sql) === TRUE) {
            echo "<p>Puesto eliminado con éxito.</p>";
        } else {
            echo "<p>Error al eliminar puesto: " . $conn->error . "</p>";
        }
    } elseif (isset($_POST['eCodPuesto'])) {
        // Editar puesto existente
        $eCodPuesto = $_POST['eCodPuesto'];
        $tNombrePuesto = $_POST['tNombrePuesto'];
        $bEstadoPuesto = isset($_POST['bEstadoPuesto']) ? 1 : 0;
        $fhFechaHoraActualizacionPuesto = date('Y-m-d H:i:s');
        $sql = "UPDATE puesto SET tNombrePuesto='$tNombrePuesto', bEstadoPuesto='$bEstadoPuesto', fhFechaHoraActualizacionPuesto='$fhFechaHoraActualizacionPuesto' WHERE eCodPuesto='$eCodPuesto'";
        if ($conn->query($sql) === TRUE) {
            echo "<p>Puesto actualizado con éxito.</p>";
        } else {
            echo "<p>Error al actualizar puesto: " . $conn->error . "</p>";
        }
    }

    $conn->close();
}
?>
<br />
<center>
    <div class="container">
    <center>
            <h2>Registro de Puestos</h2>
        </center>
        <div class="text-left mt-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModalPuestos">
                Agregar Puesto
            </button>
        </div>
        <?php
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        $sql = "SELECT eCodPuesto, tNombrePuesto, bEstadoPuesto, fhFechaHoraCreacionPuesto, fhFechaHoraActualizacionPuesto FROM puesto";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo '<table id="tabla_puestos" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Código Puesto</th>
                            <th>Nombre</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>';
            while ($row = $result->fetch_assoc()) {
                $estadoPuesto = $row['bEstadoPuesto'] ? 'Activo' : 'Inactivo';
                $acciones = '<button class="btn btn-info btn-sm editar-puesto" data-id="' . $row['eCodPuesto'] . '" data-bs-toggle="tooltip" title="Editar">
                                <i class="fas fa-edit"></i>
                             </button>';
                
                if ($row['bEstadoPuesto']) {
                    $acciones .= '<a href="estado_puesto.php?id=' . $row['eCodPuesto'] . '" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Eliminar">
                                    <i class="fas fa-eye-slash"></i>
                                  </a>';
                } else {
                    $acciones .= '<a href="estado_puesto.php?id=' . $row['eCodPuesto'] . '" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Activar">
                                    <i class="fas fa-eye"></i>
                                  </a>';
                }

                $acciones .= '<button class="btn btn-danger btn-sm eliminar-puesto" data-id="' . $row['eCodPuesto'] . '" data-bs-toggle="tooltip" title="Eliminar definitivamente">
                                <i class="fas fa-trash"></i>
                              </button>';

                echo '<tr>
                        <td>' . $row['eCodPuesto'] . '</td>
                        <td>' . $row['tNombrePuesto'] . '</td>
                        <td>' . $estadoPuesto . '</td>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js"></script>

<!-- Modal para editar puesto -->
<div class="modal fade" id="editarPuestoModal" tabindex="-1" aria-labelledby="editarPuestoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarPuestoModalLabel">Editar Puesto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarPuesto">
                    <input type="hidden" id="editECodPuesto" name="eCodPuesto">
                    <div class="mb-3">
                        <label for="editTNombrePuesto" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="editTNombrePuesto" name="tNombrePuesto" required>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="editBEstadoPuesto" name="bEstadoPuesto">
                        <label class="form-check-label" for="editBEstadoPuesto">Estado</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Manejar el clic en el botón de editar
    $('.editar-puesto').on('click', function() {
        var puestoId = $(this).data('id');
        $.ajax({
            url: 'editar_puesto.php',
            type: 'GET',
            data: { id: puestoId },
            success: function(response) {
                var puesto = JSON.parse(response);
                $('#editECodPuesto').val(puesto.eCodPuesto);
                $('#editTNombrePuesto').val(puesto.tNombrePuesto);
                $('#editBEstadoPuesto').prop('checked', puesto.bEstadoPuesto == 1);
                $('#editarPuestoModal').modal('show');
            }
        });
    });

    // Manejar el envío del formulario de edición
    $('#formEditarPuesto').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                location.reload();
            }
        });
    });

    // Manejar el clic en el botón de eliminar
    $('.eliminar-puesto').on('click', function() {
        var puestoId = $(this).data('id');
        if (confirm('¿Estás seguro de que deseas eliminar este puesto?')) {
            $.ajax({
                url: '',
                type: 'POST',
                data: { delete_puesto_id: puestoId },
                success: function(response) {
                    location.reload();
                }
            });
        }
    });
});
</script>

<?php include("footer.php"); ?>
