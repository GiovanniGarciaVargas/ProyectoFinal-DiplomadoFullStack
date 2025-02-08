<?php
session_start(); // Inicia la sesión
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $asignacionId = intval($_GET['id']); // Asegúrate de que el ID es un entero

    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "incidenciasump";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die(json_encode(["error" => "Conexión fallida: " . $conn->connect_error]));
    }

    $sql = "
    SELECT aa.eCodAsignacion, 
       a.tNombreAula, 
       p.eNumeroInventarioProyector, 
       aa.fk_eCodAireAcondicionado, 
       aa.fk_eCodAmplificador, 
       aa.fk_eCodPantallaProyeccion, 
       aa.fk_eCodMicrofono, 
       aa.fk_eCodEquipoComputo, 
       aa.fk_eCodRegulador, 
       aa.fk_eCodCamaraWeb, 
       aa.bEstadoAsignacion, 
       aa.fhFechaHoraCreacion, 
       aa.fhFechaHoraActualizacion, 
       air.eNumeroInventarioAire as aire, 
       amp.eNumeroInventarioAmplificador as amplificador, 
       pan.eNumeroInventarioPantalla as pantalla, 
       mic.eNumeroInventarioMicrofono as microfono, 
       comp.eNumeroInventarioEquipoComputo as equipo, 
       regu.eNumeroInventarioRegulador as regulador, 
       cam.eNumeroInventarioCamara as camara 
FROM asignacionaulas aa 
JOIN aula a ON aa.fk_eCodAula = a.eCodAula 
LEFT JOIN proyector p ON aa.fk_eCodProyector = p.eCodProyector 
LEFT JOIN aireacondicionado air ON aa.fk_eCodAireAcondicionado = air.eCodAireAcondicionado 
LEFT JOIN equipoaudio amp ON aa.fk_eCodAmplificador = amp.eCodAmplificador 
LEFT JOIN pantallaproyeccion pan ON aa.fk_eCodPantallaProyeccion = pan.eCodPantallaProyeccion
LEFT JOIN microfono mic ON aa.fk_eCodMicrofono = mic.eCodMicrofono 
LEFT JOIN equipocomputo comp ON aa.fk_eCodEquipoComputo = comp.eCodEquipoComputo 
LEFT JOIN regulador regu ON aa.fk_eCodRegulador = regu.eCodRegulador 
LEFT JOIN camaraweb cam ON aa.fk_eCodCamaraWeb = cam.eCodCamaraWeb 
WHERE aa.eCodAsignacion = $asignacionId";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $asignacion = $result->fetch_assoc();
        echo json_encode($asignacion);
    } else {
        echo json_encode(["error" => "No se encontró la asignación con ID $asignacionId"]);
    }

    $conn->close();
} else {
    //echo json_encode(["error" => "Solicitud no válida"]);
}
?>
