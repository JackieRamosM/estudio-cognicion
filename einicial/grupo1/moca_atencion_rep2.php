<?php
// Mostrar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Obtener los datos enviados desde el cliente (JSON)
$data = json_decode(file_get_contents("php://input"), true);

// Obtener el correo electrónico del usuario
$email = $data['email'];

// Consulta para obtener el estado de reproducción del audio
$sql = "SELECT moca_atencion_rep2 FROM consentimiento WHERE correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($moca_atencion_rep2);
$stmt->fetch();

// Enviar el resultado como respuesta JSON
echo json_encode(["moca_atencion_rep2" => $moca_atencion_rep2]);

// Cerrar conexiones
$stmt->close();
$conn->close();
?>
