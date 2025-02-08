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

    if (isset($_POST['tNombreUsuario'])) {
        $tNombreUsuario = $_POST['tNombreUsuario'];
        $tApellidoPaterno = $_POST['tApellidoPaterno'];
        $tApellidoMaterno = $_POST['tApellidoMaterno'];
        $tCorreoUsuario = $_POST['tCorreoUsuario'];
        $tContrasena = password_hash($_POST['tContrasena'], PASSWORD_DEFAULT);
        $bEstadoUsuario = isset($_POST['bEstadoUsuario']) ? 1 : 0;
        $fk_eCodPuesto = $_POST['fk_eCodPuesto'];
        $fhFechaHoraCreacionUsuario = $_POST['fhFechaHoraCreacionUsuario'];
        $fhFechaHoraActualizacionUsuario = date('Y-m-d H:i:s');
        $sql = "INSERT INTO usuarios (tNombreUsuario, tApellidoPaterno, tApellidoMaterno, tCorreoUsuario, tContrasena, bEstadoUsuario, fk_eCodPuesto, fhFechaHoraCreacionUsuario, fhFechaHoraActualizacionUsuario)
                VALUES ('$tNombreUsuario', '$tApellidoPaterno', '$tApellidoMaterno', '$tCorreoUsuario', '$tContrasena', '$bEstadoUsuario', '$fk_eCodPuesto', '$fhFechaHoraCreacionUsuario', '$fhFechaHoraActualizacionUsuario')";
        if ($conn->query($sql) === TRUE) {
        } else {
            echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
    } elseif (isset($_POST['delete_user_id'])) {
        $userId = $_POST['delete_user_id'];
        $sql = "DELETE FROM usuarios WHERE eCodUsuario = '$userId'";
        if ($conn->query($sql) === TRUE) {
            echo "<p>Usuario eliminado con éxito.</p>";
        } else {
            echo "<p>Error al eliminar usuario: " . $conn->error . "</p>";
        }
    }

    $conn->close();
}
?>
<br />
<center>
    <div class="container">
        <center>
            <h2>Registro de Usuarios</h2>
        </center>
        <div class="text-left mt-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                Agregar Usuario
            </button>
        </div>
        <?php
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        $sql = "SELECT u.eCodUsuario, u.tNombreUsuario, u.tApellidoPaterno, u.tApellidoMaterno, u.tCorreoUsuario, u.bEstadoUsuario, p.tNombrePuesto, u.fhFechaHoraCreacionUsuario, u.fhFechaHoraActualizacionUsuario 
                FROM usuarios u
                JOIN puesto p ON u.fk_eCodPuesto = p.eCodPuesto";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo '<table id="tabla_usuarios" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Código Usuario</th>
                            <th>Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>Correo</th>
                            <th>Estado</th>
                            <th>Cargo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>';
            while ($row = $result->fetch_assoc()) {
                $estadoUsuario = $row['bEstadoUsuario'] ? 'Activo' : 'Inactivo';
                $acciones = '<button class="btn btn-info btn-sm editar-usuario" data-id="' . $row['eCodUsuario'] . '" data-bs-toggle="tooltip" title="Editar">
                                <i class="fas fa-edit"></i>
                             </button>';
                
                if ($row['bEstadoUsuario']) {
                    $acciones .= '<a href="estado_usuario.php?id=' . $row['eCodUsuario'] . '" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Eliminar">
                                    <i class="fas fa-eye-slash"></i>
                                  </a>';
                } else {
                    $acciones .= '<a href="estado_usuario.php?id=' . $row['eCodUsuario'] . '" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Activar">
                                    <i class="fas fa-eye"></i>
                                  </a>';
                }

                $acciones .= '<button class="btn btn-danger btn-sm eliminar-usuario" data-id="' . $row['eCodUsuario'] . '" data-bs-toggle="tooltip" title="Eliminar definitivamente">
                                <i class="fas fa-trash"></i>
                              </button>';

                echo '<tr>
                        <td>' . $row['eCodUsuario'] . '</td>
                        <td>' . $row['tNombreUsuario'] . '</td>
                        <td>' . $row['tApellidoPaterno'] . '</td>
                        <td>' . $row['tApellidoMaterno'] . '</td>
                        <td>' . $row['tCorreoUsuario'] . '</td>
                        <td>' . $estadoUsuario . '</td>
                        <td>' . $row['tNombrePuesto'] . '</td>
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

<!-- Modal para editar usuario -->
<div class="modal fade" id="editarUsuarioModal" tabindex="-1" aria-labelledby="editarUsuarioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarUsuarioModalLabel">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarUsuario">
                    <input type="hidden" id="editECodUsuario" name="eCodUsuario">
                    <div class="mb-3">
                        <label for="editTNombreUsuario" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="editTNombreUsuario" name="tNombreUsuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTApellidoPaterno" class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" id="editTApellidoPaterno" name="tApellidoPaterno" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTApellidoMaterno" class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" id="editTApellidoMaterno" name="tApellidoMaterno" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTCorreoUsuario" class="form-label">Correo</label>
                        <input type="email" class="form-control" id="editTCorreoUsuario" name="tCorreoUsuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="editTContrasena" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="editTContrasena" name="tContrasena">
                    </div>
                    <div class="mb-3">
                        <label for="editFkECodPuesto" class="form-label">Cargo</label>
                        <select class="form-select" id="editFkECodPuesto" name="fk_eCodPuesto">
                            <!-- Opciones de cargos se llenarán con AJAX -->
                        </select>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="editBEstadoUsuario" name="bEstadoUsuario">
                        <label class="form-check-label" for="editBEstadoUsuario">Estado</label>
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
    $('.editar-usuario').on('click', function() {
        var userId = $(this).data('id');
        $.ajax({
            url: 'obtener_usuario.php',
            type: 'GET',
            data: { id: userId },
            success: function(response) {
                var user = JSON.parse(response);
                $('#editECodUsuario').val(user.eCodUsuario);
                $('#editTNombreUsuario').val(user.tNombreUsuario);
                $('#editTApellidoPaterno').val(user.tApellidoPaterno);
                $('#editTApellidoMaterno').val(user.tApellidoMaterno);
                $('#editTCorreoUsuario').val(user.tCorreoUsuario);
                $('#editTContrasena').val(user.tContrasena);
                $('#editBEstadoUsuario').prop('checked', user.bEstadoUsuario == 1);

                // Cargar opciones de cargos
                $.ajax({
                    url: 'obtener_puesto.php',
                    type: 'GET',
                    success: function(response) {
                        var puestos = JSON.parse(response);
                        var opciones = '';
                        puestos.forEach(function(puesto) {
                            var selected = puesto.eCodPuesto == user.fk_eCodPuesto ? 'selected' : '';
                            opciones += '<option value="' + puesto.eCodPuesto + '" ' + selected + '>' + puesto.tNombrePuesto + '</option>';
                        });
                        $('#editFkECodPuesto').html(opciones);
                    }
                });

                $('#editarUsuarioModal').modal('show');
            }
        });
    });

    // Manejar el envío del formulario de edición
    $('#formEditarUsuario').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'editar_usuario.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                location.reload();
            }
        });
    });

    // Manejar el clic en el botón de eliminar
    $('.eliminar-usuario').on('click', function() {
        var userId = $(this).data('id');
        if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
            $.ajax({
                url: '',
                type: 'POST',
                data: { delete_user_id: userId },
                success: function(response) {
                    location.reload();
                }
            });
        }
    });
});
</script>
<?php include("footer.php"); ?>
