<?php
require('fpdf/fpdf.php');

if (isset($_GET['eCodIncidencia'])) {
    $eCodIncidencia = $_GET['eCodIncidencia'];

    // Conectar a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "incidenciasump";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener detalles de la incidencia
    $sql = "SELECT incidencias.*, usuarios.tNombreUsuario, usuarios.tApellidoPaterno, usuarios.tApellidoMaterno, puesto.tNombrePuesto
            FROM incidencias 
            INNER JOIN usuarios ON incidencias.fk_eCodUsuarioRegistraIncidencia = usuarios.eCodUsuario 
            INNER JOIN puesto ON usuarios.fk_eCodPuesto = puesto.eCodPuesto
            WHERE eCodIncidencia = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $eCodIncidencia);
    $stmt->execute();
    $result = $stmt->get_result();
    $registro = $result->fetch_assoc();

    $conn->close();

    // Función para obtener el nombre completo del reportado
    function obtenerNombreCompleto($usuario) {
        return $usuario['tNombreUsuario'] . ' ' . $usuario['tApellidoPaterno'] . ' ' . $usuario['tApellidoMaterno'];
    }

    // Función para obtener el nombre del puesto del usuario
    function obtenerNombrePuesto($usuario) {
        return $usuario['tNombrePuesto'];
    }

    // Crear PDF
    class PDF extends FPDF {
        // Cabecera de página
        function Header() {
            // Logo en la esquina superior izquierda
            $this->Image('https://ump.edu.mx/wp-content/uploads/2023/03/LOGO-CIRCULAR-1024x769.png', 10, 10, 30);
            // Logo en la esquina superior derecha
            $this->Image('https://ump.edu.mx/wp-content/uploads/2023/03/LOGO-CIRCULAR-1024x769.png', 170, 10, 30);
            // Título centrado
            $this->SetFont('Arial', 'B', 15);
            $this->Cell(0, 10, utf8_decode('Universidad Multitécnica Profesional'), 0, 1, 'C');
            $this->Ln(5);
        }

        // Pie de página
        function Footer() {
            // Posición a 1.5 cm del final
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial', 'I', 8);
            // Número de página
            $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo(), 0, 0, 'C');
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();

    // Título centrado
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, utf8_decode('Detalles de la Incidencia'), 0, 1, 'C');
    $pdf->Ln(5);

    // Introducción
    $pdf->SetFont('Arial', '', 12);
    $pdf->MultiCell(0, 10, utf8_decode('A continuación se detallan las características y estado de la incidencia reportada en el sistema. Este reporte proporciona una visión detallada para facilitar la resolución del problema.'), 0, 'J');
    $pdf->Ln(5);

    // Información de la incidencia en tabla
    $pdf->Cell(40, 10, utf8_decode('Aula Reportada:'), 0, 0);
    $pdf->Cell(0, 10, utf8_decode($registro['fk_eCodAsignacion']), 0, 1);

    $pdf->Cell(40, 10, utf8_decode('Modalidad:'), 0, 0);
    $pdf->Cell(0, 10, utf8_decode($registro['tModalidadIncidencia']), 0, 1);

    $pdf->Cell(40, 10, utf8_decode('Descripción:'), 0, 0);
    $pdf->MultiCell(0, 10, utf8_decode($registro['tDescripcionIncidencia']), 0, 1);

    $pdf->Cell(40, 10, utf8_decode('Proyector:'), 0, 0);
    $pdf->Cell(0, 10, utf8_decode($registro['bEstadoProyector'] ? 'Funcional' : 'No funcional'), 0, 1);

    $pdf->Cell(40, 10, utf8_decode('Pantalla:'), 0, 0);
    $pdf->Cell(0, 10, utf8_decode($registro['bEstadoPantalla'] ? 'Funcional' : 'No funcional'), 0, 1);

    $pdf->Cell(40, 10, utf8_decode('Aire Acondicionado:'), 0, 0);
    $pdf->Cell(0, 10, utf8_decode($registro['bEstadoAire'] ? 'Funcional' : 'No funcional'), 0, 1);

    
    $pdf->Cell(40, 10, utf8_decode('Fecha de registro:'), 0, 0);
    $pdf->Cell(0, 10, utf8_decode($registro['fhFechaHoraRegistro']), 0, 1);

    $pdf->Cell(40, 10, utf8_decode('Reportado por:'), 0, 0);
    $pdf->Cell(0, 10, utf8_decode(obtenerNombreCompleto($registro) . ', ' . obtenerNombrePuesto($registro)), 0, 1);

    // Cierre y despedida
    $pdf->Ln(10);
    $pdf->MultiCell(0, 10, utf8_decode('Agradecemos su atención y colaboración en la resolución de esta incidencia. No dude en contactarnos para cualquier consulta adicional o para informar sobre cualquier cambio en el estado de la misma.'), 0, 'J');

    // Firma y nombre del usuario
    $pdf->Ln(10);
    $pdf->Cell(0, 10, utf8_decode('___________________________________'), 0, 1, 'C');
    $pdf->Cell(0, 10, utf8_decode(obtenerNombreCompleto($registro)), 0, 1, 'C');

    // Logo debajo de la firma
    $pdf->Image('https://ump.edu.mx/wp-content/uploads/2023/03/LOGO-CIRCULAR-1024x769.png', 75, null, 60);

    // Salida del PDF
    $pdf->Output('I', 'Detalles_Incidencia_' . $eCodIncidencia . '.pdf');
}
?>
