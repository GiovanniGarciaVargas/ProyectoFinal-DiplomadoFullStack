<?php include("header");
// Verificar si se ha proporcionado un ID válido a través de GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de pantalla de proyección no válido.");
}

$id = $_GET['id'];

// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "incidenciasump";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Preparar consulta SQL para obtener los datos de la pantalla de proyección seleccionada
$sql = "SELECT * FROM pantallaproyeccion WHERE eCodPantallaProyeccion = ?";
$stmt = $conn->prepare($sql);

// Vincular parámetro
$stmt->bind_param("i", $id);

// Ejecutar consulta
$stmt->execute();

// Obtener resultados
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Obtener datos de la pantalla de proyección
    $row = $result->fetch_assoc();
    $tMarcaPantalla = $row['tMarcaPantalla'];
    $tModeloPantalla = $row['tModeloPantalla'];
    $eNumeroInventarioPantalla = $row['eNumeroInventarioPantalla'];
    $eNumeroSeriePantalla = $row['eNumeroSeriePantalla'];
    $bTipoPantalla = $row['bTipoPantalla'];
    $bEstadoPantalla = $row['bEstadoPantalla'];
    $fhFechaHoraCreacionPantalla = $row['fhFechaHoraCreacionPantalla'];
    $fhFechaHoraActualizacionPantalla = $row['fhFechaHoraActualizacionPantalla'];

    // Convertir tipo de pantalla y estado a texto legible
    $tipoPantalla = $bTipoPantalla ? 'Automática' : 'Manual';
    $estadoPantalla = $bEstadoPantalla ? 'Activo' : 'Inactivo';
} else {
    die("No se encontró la pantalla de proyección con el ID proporcionado.");
}

// Cerrar conexión y statement
$stmt->close();
$conn->close();
?>

<!-- Formulario HTML para editar pantalla de proyección -->
<div class="modal fade" id="editarModalPantalla" tabindex="-1" aria-labelledby="editarModalPantallaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="actualizar_pantalla.php" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarModalPantallaLabel">Editar Pantalla de Proyección</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="eCodPantallaProyeccion" value="<?php echo $id; ?>">
                    <div class="mb-3">
                        <label for="edit_tMarcaPantalla" class="form-label">Marca</label>
                        <input type="text" class="form-control" id="edit_tMarcaPantalla" name="tMarcaPantalla" value="<?php echo $tMarcaPantalla; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_tModeloPantalla" class="form-label">Modelo</label>
                        <input type="text" class="form-control" id="edit_tModeloPantalla" name="tModeloPantalla" value="<?php echo $tModeloPantalla; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_eNumeroInventarioPantalla" class="form-label">No. de Inventario</label>
                        <input type="text" class="form-control" id="edit_eNumeroInventarioPantalla" name="eNumeroInventarioPantalla" value="<?php echo $eNumeroInventarioPantalla; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_eNumeroSeriePantalla" class="form-label">No. de Serie</label>
                        <input type="text" class="form-control" id="edit_eNumeroSeriePantalla" name="eNumeroSeriePantalla" value="<?php echo $eNumeroSeriePantalla; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_bTipoPantalla" class="form-label">Tipo de Pantalla</label>
                        <select class="form-select" id="edit_bTipoPantalla" name="bTipoPantalla" required>
                            <option value="0" <?php echo $bTipoPantalla == 0 ? 'selected' : ''; ?>>Manual</option>
                            <option value="1" <?php echo $bTipoPantalla == 1 ? 'selected' : ''; ?>>Automática</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_bEstadoPantalla" class="form-label">Estado de Pantalla</label>
                        <select class="form-select" id="edit_bEstadoPantalla" name="bEstadoPantalla" required>
                            <option value="0" <?php echo $bEstadoPantalla == 0 ? 'selected' : ''; ?>>Inactivo</option>
                            <option value="1" <?php echo $bEstadoPantalla == 1 ? 'selected' : ''; ?>>Activo</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_fhFechaHoraCreacionPantalla" class="form-label">Fecha y Hora de Creación</label>
                        <input type="text" class="form-control" id="edit_fhFechaHoraCreacionPantalla" name="fhFechaHoraCreacionPantalla" value="<?php echo $fhFechaHoraCreacionPantalla; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_fhFechaHoraActualizacionPantalla" class="form-label">Fecha y Hora de Actualización</label>
                        <input type="text" class="form-control" id="edit_fhFechaHoraActualizacionPantalla" name="fhFechaHoraActualizacionPantalla" value="<?php echo $fhFechaHoraActualizacionPantalla; ?>" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>