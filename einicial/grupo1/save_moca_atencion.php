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

// Obtener los datos enviados desde el formulario (JSON)
$data = json_decode(file_get_contents("php://input"), true);

// Guardar los valores
$email = $data['email'];
//$moca_atencion = $data['moca_atencion'];
$moca_atencion = isset($data['moca_atencion']) ? $data['moca_atencion'] : ''; // Asignar vacío si no se proporciona

// Verificar si el correo ya existe en la tabla consentimiento
$sql_check = "SELECT correo FROM consentimiento WHERE correo = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    // Si el correo existe, actualizar la columna moca_atencion y moca_atencion_rep1
    $sql_update = "UPDATE consentimiento SET moca_atencion = ?, moca_atencion_rep1 = 1 WHERE correo = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ss", $moca_atencion, $email);
    
    if ($stmt_update->execute()) {
        echo json_encode(["status" => "success", "message" => "Datos actualizados correctamente."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al actualizar los datos: " . $stmt_update->error]);
    }
} else {
    // Si el correo no existe, se puede manejar como un error o crear un nuevo registro
    echo json_encode(["status" => "error", "message" => "Correo no encontrado en la base de datos."]);
}

// Cerrar conexiones
$stmt_check->close();
$stmt_update->close();
$conn->close();
?>
