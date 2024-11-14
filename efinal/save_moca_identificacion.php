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

// Obtener los datos enviados desde el formulario (JSON)
$data = json_decode(file_get_contents("php://input"), true);

// Verificar si el contenido JSON fue recibido correctamente
if (!$data) {
    echo json_encode(["status" => "error", "message" => "No se recibieron datos JSON."]);
    exit;
}

// Guardar los valores recibidos
$email = isset($data['email']) ? $data['email'] : null;
$moca_identificacion = isset($data['moca_identificacion']) ? $data['moca_identificacion'] : null;

// Validar que el correo y la identificación no estén vacíos
if (!$email || !$moca_identificacion) {
    echo json_encode(["status" => "error", "message" => "Faltan datos (email o identificación)"]);
    exit;
}

// Verificar si el correo ya existe en la tabla consentimiento
$sql_check = "SELECT correo FROM consentimiento_f WHERE correo = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    // Si el correo existe, actualizar la columna moca_identificacion
    $sql_update = "UPDATE consentimiento_f SET moca_identificacion = ? WHERE correo = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ss", $moca_identificacion, $email);

    if ($stmt_update->execute()) {
        echo json_encode(["status" => "success", "message" => "Datos actualizados correctamente."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al actualizar los datos: " . $stmt_update->error]);
    }

    $stmt_update->close();
} else {
    echo json_encode(["status" => "error", "message" => "Correo no encontrado en la base de datos."]);
}

$stmt_check->close();
$conn->close();
?>
