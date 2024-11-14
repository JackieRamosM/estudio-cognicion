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

// Obtener los datos enviados desde el formulario
$email = $_POST['email'];
$aciertos = $_POST['aciertos'];

// Actualizar la columna 'stroop_fase2' con los aciertos
$sql = "UPDATE consentimiento_f SET stroop_fase2 = ? WHERE correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $aciertos, $email);

if ($stmt->execute()) {
    echo "Aciertos guardados correctamente.";
} else {
    echo "Error al guardar los aciertos: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
