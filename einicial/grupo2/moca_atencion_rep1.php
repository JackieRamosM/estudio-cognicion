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
$email = $data['email'];

// Si se recibe la actualización del estado de reproducción
if (isset($data['moca_atencion_rep1'])) {
    $moca_atencion_rep1 = $data['moca_atencion_rep1'];

    // Verificar si el correo ya existe en la tabla consentimiento
    $sql_check = "SELECT correo FROM consentimiento WHERE correo = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // Si el correo existe, actualizar la columna moca_atencion_rep1
        $sql_update = "UPDATE consentimiento SET moca_atencion_rep1 = ? WHERE correo = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("is", $moca_atencion_rep1, $email);

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
} else {
    // Solo recuperar el estado de reproducción
    $sql_check_state = "SELECT moca_atencion_rep1 FROM consentimiento WHERE correo = ?";
    $stmt_check_state = $conn->prepare($sql_check_state);
    $stmt_check_state->bind_param("s", $email);
    $stmt_check_state->execute();
    $stmt_check_state->bind_result($moca_atencion_rep1_value);
    $stmt_check_state->fetch();

    echo json_encode(["moca_atencion_rep1" => $moca_atencion_rep1_value]);
}

$conn->close();
?>
