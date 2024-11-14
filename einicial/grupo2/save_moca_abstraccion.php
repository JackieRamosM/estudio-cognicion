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

// Guardar los valores
$email = isset($data['email']) ? $data['email'] : ''; // Asegurarse de que el correo esté definido
$moca_abstraccion1 = isset($data['moca_abstraccion1']) ? $data['moca_abstraccion1'] : ''; // Asegurarse de que el campo 1 esté definido
$moca_abstraccion2 = isset($data['moca_abstraccion2']) ? $data['moca_abstraccion2'] : ''; // Asegurarse de que el campo 2 esté definido

// Verificar si el correo ya existe en la tabla consentimiento
$sql_check = "SELECT correo FROM consentimiento WHERE correo = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    // Si el correo existe, actualizar las columnas moca_abstraccion1 y moca_abstraccion2
    $sql_update = "UPDATE consentimiento SET moca_abstraccion1 = ?, moca_abstraccion2 = ? WHERE correo = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sss", $moca_abstraccion1, $moca_abstraccion2, $email);

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
