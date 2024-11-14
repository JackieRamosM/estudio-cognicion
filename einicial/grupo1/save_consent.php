<?php
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
$nombre = isset($data['name']) ? $data['name'] : null;
$correo = isset($data['email']) ? $data['email'] : null;
$consentimiento = isset($data['consent']) ? $data['consent'] : null;
$comunidad_ecotec = isset($data['communityEcotec']) ? $data['communityEcotec'] : null;
$fecha_nacimiento = isset($data['birthdate']) ? $data['birthdate'] : null;
$sexo = isset($data['gender']) ? $data['gender'] : null;
$rol = isset($data['role']) ? $data['role'] : null;
$ciudad_residencia = isset($data['city']) ? $data['city'] : null;

// Agregar el grupo "GRUPOCONTROL"
$grupo = "GRUPOCONTROL";

// Verificar si el correo ya existe
$sql_check = "SELECT correo FROM consentimiento WHERE correo = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $correo);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Este correo ya está registrado."]);
    $stmt_check->close();
    $conn->close();
    exit();
}
$stmt_check->close();

// Si el correo no existe, proceder con la inserción
$sql_insert = "INSERT INTO consentimiento (nombre, correo, consentimiento, comunidad_ecotec, fecha_nacimiento, sexo, rol, ciudad_residencia, grupo)
               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("sssssssss", $nombre, $correo, $consentimiento, $comunidad_ecotec, $fecha_nacimiento, $sexo, $rol, $ciudad_residencia, $grupo);

if ($stmt_insert->execute()) {
    echo json_encode(["status" => "success", "message" => "Datos guardados correctamente."]);
} else {
    echo json_encode(["status" => "error", "message" => "Error al guardar los datos: " . $stmt_insert->error]);
}

// Cerrar la conexión
$stmt_insert->close();
$conn->close();
?>
