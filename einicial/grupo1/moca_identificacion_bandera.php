<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "estudio-cognicion";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'];

// Verificar si el correo se recibió correctamente
if (!$email) {
    echo json_encode(["status" => "error", "message" => "Correo no proporcionado"]);
    exit;
}

// Verificar si se debe actualizar el flag
if (isset($data['updateFlag']) && $data['updateFlag'] === true) {
    // Actualizar el valor del flag a 1
    $sql_update = "UPDATE consentimiento SET moca_identificacion_flag = 1 WHERE correo = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("s", $email);

    if ($stmt_update->execute()) {
        echo json_encode(["status" => "success", "message" => "Flag actualizado correctamente."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al actualizar el flag: " . $stmt_update->error]);
    }

    $stmt_update->close();
} else {
    // Solo verificar el valor del flag
    $sql_check = "SELECT moca_identificacion_flag FROM consentimiento WHERE correo = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->bind_result($flag);
    $stmt_check->fetch();

    // Verificar si el flag fue encontrado
    if ($flag !== null) {
        echo json_encode(["flag" => $flag]);
    } else {
        echo json_encode(["status" => "error", "message" => "No se encontró flag para el correo proporcionado"]);
    }

    $stmt_check->close();
}

$conn->close();
?>
