<?php
// Conexión a la base de datos MySQL
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "estudio-cognicion"; 

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener los 100 colores
$sql = "SELECT id, color FROM stroop_colores ORDER BY id ASC";
$result = $conn->query($sql);

$colores = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $colores[] = $row;
    }
}

// Devolver los colores como JSON
header('Content-Type: application/json');
echo json_encode($colores);

// Cerrar la conexión
$conn->close();
?>
