<?php
// Activa la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir la clase FPDF y otras configuraciones necesarias
require('./fpdf/fpdf.php');

// Función para obtener el nombre del mes en español
function obtenerNombreMes($mes)
{
    $meses = array(
        '01' => 'enero',
        '02' => 'febrero',
        '03' => 'marzo',
        '04' => 'abril',
        '05' => 'mayo',
        '06' => 'junio',
        '07' => 'julio',
        '08' => 'agosto',
        '09' => 'septiembre',
        '10' => 'octubre',
        '11' => 'noviembre',
        '12' => 'diciembre'
    );
    return $meses[$mes];
}

try {
    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "incidenciasump";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Conexión fallida: " . $conn->connect_error);
    }

    // Iniciar sesión
    session_start();
    if (!isset($_SESSION['eCodUsuario'])) {
        throw new Exception("Usuario no autenticado.");
    }
    $eCodUsuario = $_SESSION['eCodUsuario'];

    // Obtener información del usuario
    $sql_usuario = "SELECT u.tNombreUsuario, u.tApellidoPaterno, u.tApellidoMaterno, p.tNombrePuesto 
                    FROM usuarios u
                    INNER JOIN puesto p ON u.fk_eCodPuesto = p.eCodPuesto
                    WHERE u.eCodUsuario = ?";
    $stmt_usuario = $conn->prepare($sql_usuario);
    $stmt_usuario->bind_param("i", $eCodUsuario);
    $stmt_usuario->execute();
    $result_usuario = $stmt_usuario->get_result();
    if ($result_usuario->num_rows == 0) {
        throw new Exception("Usuario no encontrado.");
    }
    $usuario = $result_usuario->fetch_assoc();
    $nombreUsuario = $usuario['tNombreUsuario'] . ' ' . $usuario['tApellidoPaterno'] . ' ' . $usuario['tApellidoMaterno'];
    $puestoUsuario = $usuario['tNombrePuesto'];

    // Obtener la fecha seleccionada (ejemplo, esta parte debería venir de tu filtro de calendario)
    if (!isset($_GET['fecha'])) {
        throw new Exception("Fecha no especificada.");
    }
    $fecha = $_GET['fecha'];  // Debes obtener la fecha seleccionada de tu filtro de calendario

    // Consultar incidencias según la fecha
    $sql = "SELECT i.fk_eCodAsignacion, i.tDescripcionIncidencia, CONCAT(u.tNombreUsuario, ' ', u.tApellidoPaterno, ' ', u.tApellidoMaterno) AS reportado_por
            FROM incidencias i
            INNER JOIN usuarios u ON i.fk_eCodUsuarioRegistraIncidencia = u.eCodUsuario
            WHERE DATE(fhFechaHoraRegistro) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $fecha);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        throw new Exception("No se encontraron incidencias para la fecha especificada.");
    }

    // Crear el PDF
    class PDF extends FPDF
    {
        // Cabecera de página
        function Header()
        {
            // Logo en la esquina superior izquierda
            $this->Image('https://ump.edu.mx/wp-content/uploads/2023/03/LOGO-CIRCULAR-1024x769.png', 10, 10, 30);
            // Título centrado
            $this->SetFont('Arial', 'B', 15);
            $this->Cell(0, 10, utf8_decode('Universidad Multitécnica Profesional'), 0, 1, 'C');
            $this->Ln(5);
        }

        // Pie de página
        function Footer()
        {
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
    $pdf->SetFont('Arial', '', 10);  // Ajuste del tamaño de la letra

    // Título centrado
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 10, utf8_decode('Reporte de Incidencias'), 0, 1, 'C');
    $pdf->Ln(10);

    // Fecha del reporte
    $fechaFormateada = date('d', strtotime($fecha)) . ' de ' . obtenerNombreMes(date('m', strtotime($fecha))) . ' del ' . date('Y', strtotime($fecha));
    $pdf->Cell(0, 10, utf8_decode('Manzanillo, Colima a ' . $fechaFormateada), 0, 1, 'R');
    $pdf->Ln(10);

    // Texto de presentación ampliado
    $pdf->MultiCell(0, 5, utf8_decode('A Quien Corresponda,

A continuación, se presenta el reporte de incidencias para la fecha '), 0, 'J');

    // Fecha seleccionada en rojo
    $pdf->SetTextColor(255, 0, 0); // Rojo
    $pdf->Cell(0, 10, utf8_decode($fechaFormateada), 0, 1, 'C');
    $pdf->SetTextColor(0); // Restaurar color negro

    $pdf->MultiCell(0, 10, utf8_decode('Este reporte tiene como finalidad proporcionar una visión detallada de las incidencias técnicas registradas en la fecha especificada. 
'), 0, 'J');
    $pdf->Ln(10);

    // Encabezados de la tabla
    $pdf->SetFillColor(200, 220, 255);
    $pdf->SetFont('Arial', 'B', 8);  // Ajuste del tamaño de la letra de la tabla
    $pdf->Cell(30, 10, utf8_decode('Aula'), 1, 0, 'C', true);
    $pdf->Cell(110, 10, utf8_decode('Descripción de la Incidencia'), 1, 0, 'C', true);
    $pdf->Cell(50, 10, utf8_decode('Reportado por'), 1, 1, 'C', true);

    // Datos de las incidencias
    $pdf->SetFont('Arial', '', 10);  // Ajuste del tamaño de la letra de los datos de la tabla
    while ($row = $result->fetch_assoc()) {
        $descripcionIncidencia = strlen($row['tDescripcionIncidencia']) > 80 ? substr($row['tDescripcionIncidencia'], 0, 77) . '...' : $row['tDescripcionIncidencia'];
        $pdf->Cell(30, 12, $row['fk_eCodAsignacion'], 1, 0, 'C');
        $pdf->Cell(110, 12, utf8_decode($descripcionIncidencia), 1, 0, 'L');
        $pdf->Cell(50, 12, utf8_decode($row['reportado_por']), 1, 1, 'L');
    }

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 10);  // Ajuste del tamaño de la letra de despedida
    $pdf->MultiCell(0, 10, utf8_decode('Agradezco su atención y quedo a disposición para cualquier aclaración adicional que pueda ser necesaria.
Por favor, no dude en ponerse en contacto para cualquier consulta adicional o para coordinar la resolución de los problemas técnicos reportados.'), 0, 'J');
    $pdf->Ln(10);

    // Espacio para firma y nombre del usuario
    $pdf->Cell(0, 10, utf8_decode('___________________________________'), 0, 1, 'C');
    $pdf->Cell(0, 10, utf8_decode($nombreUsuario), 0, 1, 'C');
    $pdf->Cell(0, 10, utf8_decode($puestoUsuario), 0, 1, 'C');

    // Imagen del logo centrada
    $pdf->Ln(0);
    $pdf->Image('https://ump.edu.mx/wp-content/uploads/2023/03/LOGO-CIRCULAR-1024x769.png', 75, null, 60);

    // Salida del PDF
    $pdf->Output('I', 'Reporte_Incidencias_' . $fecha . '.pdf');

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

$conn->close();
?>
