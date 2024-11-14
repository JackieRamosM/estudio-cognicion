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

// Establecer la zona horaria a GMT-5
date_default_timezone_set('America/Bogota');

// Obtener los datos enviados desde el formulario (JSON)
$data = json_decode(file_get_contents("php://input"), true);

// Guardar los valores
$email = $data['email'];
$date = $data['moca_orientacion']['date'];
$city = $data['moca_orientacion']['city'];
$place = $data['moca_orientacion']['place'];
$fecha_fin = date('Y-m-d H:i:s');

// Verificar si el correo ya existe en la tabla consentimiento
$sql_check = "SELECT correo FROM consentimiento WHERE correo = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    // Si el correo existe, actualizar la información
    $sql_update = "UPDATE consentimiento SET moca_orientacion_fecha = ?, moca_orientacion_ciudad = ?, moca_orientacion_lugar = ?, fecha_fin = ? WHERE correo = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssss", $date, $city, $place, $fecha_fin, $email);

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
