<?php

header('Content-Type: text/html; charset=utf-8');

require('fpdf/fpdf.php');
require('clases/conexion/conexion.php');

// Obtener la conexión utilizando un método estático
$conn = conexion::obtenerConexion();

// Verifica si la conexión está establecida
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Creación del objeto PDF
$pdf = new FPDF();
$pdf->AddPage('P'); // Establecer la orientación a horizontal

// Configuración de fuente y color
$pdf->SetFont('Arial','B',12);
$pdf->SetTextColor(0,0,255);










// Consulta a la tabla pacientes
$sql_usuarios = "SELECT * FROM usuarios";
$connexion = new conexion();
$result_usuarios = $connexion->obtenerDatos($sql_usuarios);

// Mostrar tabla de pacientes
$pdf->Cell(0,10,'Tabla de usuarios',0,1,'C');
$pdf->Ln();

$pdf->Cell(30,10,'UsuarioId',1);
$pdf->Cell(50,10,'Usuario',1);
$pdf->Cell(50,10,'Estado',1);
$pdf->Ln();

foreach ($result_usuarios as $row) {
    $pdf->Cell(30,10,$row['UsuarioId'],1);
    $pdf->Cell(50,10,$row['Usuario'],1);
    $pdf->Cell(50,10,$row['Estado'],1);
    $pdf->Ln();
}





$pdf->AddPage('L'); // Establecer la orientación a horizontal



// Consulta a la tabla pacientes$pdf->Ln();

$sql_pacientes = "SELECT * FROM pacientes";

$connexion = new conexion();


$result_pacientes = $connexion->obtenerDatos($sql_pacientes);

// Mostrar tabla de pacientes
$pdf->Cell(0,10,'Tabla de pacientes',0,1,'C');
$pdf->Ln();

$pdf->Cell(30,10,'ID',1);
$pdf->Cell(50,10,'Nombre',1);
$pdf->Cell(50,10,'Correo',1);
$pdf->Cell(50,10,'Fecha de Nacimiento',1);
$pdf->Cell(50,10,'Direccion',1); // Nuevo campo: Dirección
$pdf->Cell(20,10,'CodigoP',1); // Nuevo campo: Código Postal
$pdf->Cell(30,10,'Telefono',1); // Nuevo campo: Teléfono
$pdf->Ln();


foreach ($result_pacientes as $row) {
    $pdf->Cell(30,10,$row['PacienteId'],1);
    $pdf->Cell(50,10,$row['Nombre'],1);
    $pdf->Cell(50,10,$row['Correo'],1);
    $pdf->Cell(50,10,$row['FechaNacimiento'],1);
    $pdf->Cell(50,10,$row['Direccion'],1); // Nuevo campo: Dirección
    $pdf->Cell(20,10,$row['CodigoP'],1); // Nuevo campo: Código Postal
    $pdf->Cell(30,10,$row['Telefono'],1); // Nuevo campo: Teléfono
    $pdf->Ln();

}







$pdf->AddPage('L'); // Establecer la orientación a horizontal






// Consulta a la tabla citas
$sql_citas = "SELECT * FROM citas";

$result_citas = $connexion->obtenerDatos($sql_citas);

// Mostrar tabla de citas
$pdf->Cell(0,10,'Tabla de Citas',0,1,'C');

$pdf->Cell(30,10,'ID',1);
$pdf->Cell(40,10,'Paciente ID',1);
$pdf->Cell(30,10,'Fecha',1);
$pdf->Cell(30,10,'Hora Inicio',1);
$pdf->Cell(30,10,'Hora Fin',1);
$pdf->Cell(30,10,'Estado',1);
$pdf->Cell(60,10,'Motivo',1);
$pdf->Ln();

foreach ($result_citas as $row) {
    $pdf->Cell(30,10,$row['CitaId'],1);
    $pdf->Cell(40,10,$row['PacienteId'],1);
    $pdf->Cell(30,10,$row['Fecha'],1);
    $pdf->Cell(30,10,$row['HoraInicio'],1);
    // Verificar si 'HoraFin' está definido antes de imprimirlo
    if (isset($row['HoraFin'])) {
        $pdf->Cell(30,10,$row['HoraFin'],1);
    } else {
        $pdf->Cell(30,10,'N/A',1); // Si 'HoraFin' no está definido, imprime 'N/A'
    }
    $pdf->Cell(30,10,$row['Estado'],1);
    $pdf->Cell(60,10,$row['Motivo'],1);
    $pdf->Ln();
}

// Salida del PDF
$pdf->Output();
