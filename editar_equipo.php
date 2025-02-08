<?php
$servername = "localhost";  
$username = "root";         
$password = "root";          
$dbname = "incidenciasump";  
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$id = $_GET['id'];

$sql = "SELECT * FROM equipocomputo WHERE eCodEquipoComputo=$id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $marca = $row['tMarcaEquipoComputo'];
    $modelo = $row['tModeloEquipoComputo'];
    $numInventario = $row['eNumeroInventarioEquipoComputo'];
    $numSerieGabinete = $row['eNumeroSerieGabineteEquipoComputo'];
    $numSerieMouse = $row['eNumeroSerieMouseEquipoComputo'];
    $numSerieTeclado = $row['eNumeroSerieTecladoEquipoComputo'];
    $estado = $row['bEstadoEquipoComputo'];
?>
<div class="modal-body">
    <div class="form-group">
        <label for="tMarcaEquipoComputo">Marca:</label>
        <input type="text" class="form-control" id="tMarcaEquipoComputo" name="tMarcaEquipoComputo" value="<?php echo $marca; ?>" required>
    </div>
    <div class="form-group">
        <label for="tModeloEquipoComputo">Modelo:</label>
        <input type="text" class="form-control" id="tModeloEquipoComputo" name="tModeloEquipoComputo" value="<?php echo $modelo; ?>" required>
    </div>
    <div class="form-group">
        <label for="eNumeroInventarioEquipoComputo">Número de Inventario:</label>
        <input type="number" class="form-control" id="eNumeroInventarioEquipoComputo" name="eNumeroInventarioEquipoComputo" value="<?php echo $numInventario; ?>" required>
    </div>
    <div class="form-group">
        <label for="eNumeroSerieGabineteEquipoComputo">Número de Serie del Gabinete:</label>
        <input type="text" class="form-control" id="eNumeroSerieGabineteEquipoComputo" name="eNumeroSerieGabineteEquipoComputo" value="<?php echo $numSerieGabinete; ?>" required>
    </div>
    <div class="form-group">
        <label for="eNumeroSerieMouseEquipoComputo">Número de Serie del Mouse:</label>
        <input type="text" class="form-control" id="eNumeroSerieMouseEquipoComputo" name="eNumeroSerieMouseEquipoComputo" value="<?php echo $numSerieMouse; ?>" required>
    </div>
    <div class="form-group">
        <label for="eNumeroSerieTecladoEquipoComputo">Número de Serie del Teclado:</label>
        <input type="text" class="form-control" id="eNumeroSerieTecladoEquipoComputo" name="eNumeroSerieTecladoEquipoComputo" value="<?php echo $numSerieTeclado; ?>" required>
    </div>
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="bEstadoEquipoComputo" name="bEstadoEquipoComputo" value="1" <?php echo $estado ? 'checked' : ''; ?>>
        <label class="form-check-label" for="bEstadoEquipoComputo">Activo</label>
    </div>
    <input type="hidden" id="fhFechaHoraActualizacionEquipoComputo" name="fhFechaHoraActualizacionEquipoComputo" value="<?php echo date('Y-m-d H:i:s'); ?>">
    <input type="hidden" id="eCodEquipoComputo" name="eCodEquipoComputo" value="<?php echo $id; ?>">
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
</div>
<?php
}
$conn->close();
?>
