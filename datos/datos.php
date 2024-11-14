<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "estudio-cognicion"; 

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consultar los datos necesarios de la tabla consentimiento
$sql = "SELECT * FROM respaldo_consentimiento order by fecha";
$result = $conn->query($sql);

$sql2 = "SELECT * FROM consentimiento_f order by fecha";
$result2 = $conn->query($sql2);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos de las pruebas</title>
    <link rel="stylesheet" href="style.css"> <!-- Puedes agregar tu CSS aquí -->
</head>
<body>
    <h1>Datos pretest</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Fecha/hora pretest</th>
                <th>Fecha/hora finalización</th>
                <th>Grupo</th>
                <th>Nombre completo</th>
                <th>Correo electrónico</th>
                <th>Consentimiento dado</th>
                <th>Comunidad ECOTEC</th>
                <th>Fecha de nacimiento</th>
                <th>Edad</th>
                <th>Sexo</th>
                <th>Ocupación</th>
                <th>Ciudad de residencia</th>
                <th>Puntaje Stroop fase 1</th>
                <th>Puntaje Stroop fase 2</th>
                <th>Puntaje Stroop fase 3</th>
                <th>Ingresado MoCA Identificación</th>
                <th>Puntaje MoCA Identificación</th> <!-- Nueva columna -->
                <th>Ingresado MoCA Memoria</th>
                <th>Puntaje MoCA Memoria</th> <!-- Nueva columna para memoria -->
                <th>Ingresado MoCA Atención números</th>
                <th>Puntaje MoCA Atención</th> <!-- Nueva columna para atención -->
                <th>Ingresado MoCA Atención numeros reverso</th>
                <th>Puntaje MoCA Atención Reverso</th> <!-- Nueva columna para atención reverso -->
                <th>Ingresado MoCA Atención Números resta100</th>
                <th>Ingresado MoCA Atención Letras encuentraA</th>
                <th>Puntaje MoCA Atención Letras encuentraA</th> <!-- Nueva columna para atención letras -->
                <th>Ingresado MoCA Abstracción (TREN-BICICLETA)</th>
                <th>Ingresado MoCA Abstracción (RELOJ-REGLA)</th>
                <th>Ingresado MoCA Recuerdo Diferido</th>
                <th>Puntaje MoCA Recuerdo Diferido</th> <!-- Nueva columna para recuerdo diferido -->
				<th>Ingresado MoCA Orientación Fecha</th>
                <th>Ingresado MoCA Orientación Ciudad</th>
                <th>Ingresado MoCA Orientación Lugar</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                    // Calcular la edad
                    $fecha_nacimiento = new DateTime($row['fecha_nacimiento']);
                    $fecha_consentimiento = new DateTime($row['fecha']); 
                    $edad = $fecha_consentimiento->diff($fecha_nacimiento);
                    $edad_formato = $edad->y . "a " . $edad->m . "m";

                    // Palabras correctas para identificación
                    $palabras_correctas_identificacion = ['caballo', 'rinoceronte', 'pato'];
                    $palabras_ingresadas_identificacion = explode(',', $row['moca_identificacion']);
                    $resultado_moca_identificacion = 0;

                    // Comparar palabras para identificación (insensible a mayúsculas)
                    for ($i = 0; $i < count($palabras_correctas_identificacion); $i++) {
                        if (isset($palabras_ingresadas_identificacion[$i]) && strtolower(trim($palabras_ingresadas_identificacion[$i])) === strtolower($palabras_correctas_identificacion[$i])) {
                            $resultado_moca_identificacion++;
                        }
                    }

                    // Palabras correctas para memoria
                    $palabras_correctas_memoria = ['rostro', 'seda', 'iglesia', 'clavel', 'rojo'];
                    $palabras_ingresadas_memoria = explode(',', strtolower($row['moca_memoria']));
                    $resultado_moca_memoria = 0;

                    // Comparar palabras para memoria (insensible a mayúsculas y sin importar el orden)
                    foreach ($palabras_correctas_memoria as $palabra_correcta) {
                        if (in_array(strtolower($palabra_correcta), $palabras_ingresadas_memoria)) {
                            $resultado_moca_memoria++;
                        }
                    }

                    // Validación para atención
                    $resultado_moca_atencion = (trim($row['moca_atencion']) === '21854') ? 1 : 0; // Verifica si es igual a '21854'
                    
                    // Validación para atención reverso
                    $resultado_moca_atencion_rev = (trim($row['moca_atencion_rev']) === '247') ? 1 : 0; // Verifica si es igual a '247'
                    
                    // Calcular puntaje para atención letras encuentraA
                    $entrada_atencion_letras = strtoupper(trim($row['moca_atencion_letras_A'])); // Convierte a mayúsculas y elimina espacios
                    $letras_ingresadas = count(array_filter(explode(',', $entrada_atencion_letras), fn($letter) => trim($letter) === 'A'));
                    $resultado_moca_atencion_letras = ($letras_ingresadas >= 9) ? 1 : 0; // 1 punto si hay 9 o 10 letras 'A', 0 en caso contrario
                    
                    // Calcular puntaje para recuerdo diferido
                    $palabras_correctas_recuerdo = $palabras_correctas_memoria;
                    $palabras_ingresadas_recuerdo = explode(',', strtolower($row['moca_recuerdo_diferido']));
                    $resultado_moca_recuerdo_diferido = 0;

                    // Comparar palabras para recuerdo diferido (insensible a mayúsculas y sin importar el orden)
                    foreach ($palabras_correctas_recuerdo as $palabra_correcta) {
                        if (in_array(strtolower($palabra_correcta), $palabras_ingresadas_recuerdo)) {
                            $resultado_moca_recuerdo_diferido++;
                        }
                    }
                    
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_fin']); ?></td>
                        <td><?php echo htmlspecialchars($row['grupo']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['correo']); ?></td>
                        <td><?php echo htmlspecialchars($row['consentimiento']); ?></td>
                        <td><?php echo htmlspecialchars($row['comunidad_ecotec']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_nacimiento']); ?></td>
                        <td class="puntaje"><?php echo $edad_formato; ?></td>
                        <td><?php echo htmlspecialchars($row['sexo']); ?></td>
                        <td><?php echo htmlspecialchars($row['rol']); ?></td>
                        <td><?php echo htmlspecialchars($row['ciudad_residencia']); ?></td>
                        <td class="puntaje"><?php echo htmlspecialchars($row['stroop_fase1']); ?></td>
                        <td class="puntaje"><?php echo htmlspecialchars($row['stroop_fase2']); ?></td>
                        <td class="puntaje"><?php echo htmlspecialchars($row['stroop_fase3']); ?></td>
                        <td><?php echo htmlspecialchars($row['moca_identificacion']); ?></td>
                        <td class="puntaje"><?php echo $resultado_moca_identificacion; ?></td> <!-- Mostrar el resultado de identificación -->
                        <td><?php echo htmlspecialchars($row['moca_memoria']); ?></td>
                        <td class="puntaje"><?php echo $resultado_moca_memoria; ?></td> <!-- Mostrar el resultado de memoria -->
                        <td><?php echo htmlspecialchars($row['moca_atencion']); ?></td>
                        <td class="puntaje"><?php echo $resultado_moca_atencion; ?></td> <!-- Mostrar el resultado de atención -->
                        <td><?php echo htmlspecialchars($row['moca_atencion_rev']); ?></td>
                        <td class="puntaje"><?php echo $resultado_moca_atencion_rev; ?></td> <!-- Mostrar el resultado de atención reverso -->
                        <td><?php echo htmlspecialchars($row['moca_atencion_numeros_100']); ?></td>
                        <td><?php echo htmlspecialchars($row['moca_atencion_letras_A']); ?></td>
                        <td class="puntaje"><?php echo $resultado_moca_atencion_letras; ?></td> <!-- Mostrar el resultado de atención letras -->
                        <td><?php echo htmlspecialchars($row['moca_abstraccion1']); ?></td>
                        <td><?php echo htmlspecialchars($row['moca_abstraccion2']); ?></td>
						<td><?php echo htmlspecialchars($row['moca_recuerdo_diferido']); ?></td>
                        <td class="puntaje"><?php echo $resultado_moca_recuerdo_diferido; ?></td> <!-- Mostrar el resultado de recuerdo diferido -->
                        <td><?php echo htmlspecialchars($row['moca_orientacion_fecha']); ?></td>
                        <td><?php echo htmlspecialchars($row['moca_orientacion_ciudad']); ?></td>
                        <td><?php echo htmlspecialchars($row['moca_orientacion_lugar']); ?></td>
                    </tr>

                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="30">No hay datos disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
	
	
	<h1>Datos postest</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Fecha/hora postest</th>
                <th>Fecha/hora finalización postest</th>
                <th>Nombre completo</th>
                <th>Correo electrónico</th>
                <th>Puntaje Stroop fase 1</th>
                <th>Puntaje Stroop fase 2</th>
                <th>Puntaje Stroop fase 3</th>
                <th>Ingresado MoCA Identificación</th>
                <th>Puntaje MoCA Identificación</th> <!-- Nueva columna -->
                <th>Ingresado MoCA Memoria</th>
                <th>Puntaje MoCA Memoria</th> <!-- Nueva columna para memoria -->
                <th>Ingresado MoCA Atención números</th>
                <th>Puntaje MoCA Atención</th> <!-- Nueva columna para atención -->
                <th>Ingresado MoCA Atención numeros reverso</th>
                <th>Puntaje MoCA Atención Reverso</th> <!-- Nueva columna para atención reverso -->
                <th>Ingresado MoCA Atención Números resta70</th>
                <th>Ingresado MoCA Atención Letras encuentraF</th>
                <th>Puntaje MoCA Atención Letras encuentraF</th> <!-- Nueva columna para atención letras -->
                <th>Ingresado MoCA Abstracción (GATO-PERRO)</th>
                <th>Ingresado MoCA Abstracción (CUCHARA-TENEDOR)</th>
                <th>Ingresado MoCA Recuerdo Diferido</th>
                <th>Puntaje MoCA Recuerdo Diferido</th> <!-- Nueva columna para recuerdo diferido -->
				<th>Ingresado MoCA Orientación Fecha</th>
                <th>Ingresado MoCA Orientación Ciudad</th>
                <th>Ingresado MoCA Orientación Lugar</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result2->num_rows > 0): ?>
                <?php while ($row = $result2->fetch_assoc()): ?>
                    <?php
                    

                    // Palabras correctas para identificación
                    $palabras_correctas_identificacion = ['LEON', 'CAMELLO', 'TIGRE'];
                    $palabras_ingresadas_identificacion = explode(',', $row['moca_identificacion']);
                    $resultado_moca_identificacion = 0;

                    // Comparar palabras para identificación (insensible a mayúsculas)
                    for ($i = 0; $i < count($palabras_correctas_identificacion); $i++) {
                        if (isset($palabras_ingresadas_identificacion[$i]) && strtolower(trim($palabras_ingresadas_identificacion[$i])) === strtolower($palabras_correctas_identificacion[$i])) {
                            $resultado_moca_identificacion++;
                        }
                    }

                    // Palabras correctas para memoria
                    $palabras_correctas_memoria = ['PIERNA', 'ALGODON', 'ESCUELA', 'TOMATE', 'BLANCO'];
                    $palabras_ingresadas_memoria = explode(',', strtolower($row['moca_memoria']));
                    $resultado_moca_memoria = 0;

                    // Comparar palabras para memoria (insensible a mayúsculas y sin importar el orden)
                    foreach ($palabras_correctas_memoria as $palabra_correcta) {
                        if (in_array(strtolower($palabra_correcta), $palabras_ingresadas_memoria)) {
                            $resultado_moca_memoria++;
                        }
                    }

                    // Validación para atención
                    $resultado_moca_atencion = (trim($row['moca_atencion']) === '38671') ? 1 : 0; // Verifica si es igual a '21854'
                    
                    // Validación para atención reverso
                    $resultado_moca_atencion_rev = (trim($row['moca_atencion_rev']) === '935') ? 1 : 0; // Verifica si es igual a '935'
                    
                    // Calcular puntaje para atención letras encuentraA
                    $entrada_atencion_letras = strtoupper(trim($row['moca_atencion_letras_A'])); // Convierte a mayúsculas y elimina espacios
                    $letras_ingresadas = count(array_filter(explode(',', $entrada_atencion_letras), fn($letter) => trim($letter) === 'F'));
                    $resultado_moca_atencion_letras = ($letras_ingresadas >= 2) ? 1 : 0; // 1 punto si hay 9 o 10 letras 'A', 0 en caso contrario
                    
                    // Calcular puntaje para recuerdo diferido
                    $palabras_correctas_recuerdo = $palabras_correctas_memoria;
                    $palabras_ingresadas_recuerdo = explode(',', strtolower($row['moca_recuerdo_diferido']));
                    $resultado_moca_recuerdo_diferido = 0;

                    // Comparar palabras para recuerdo diferido (insensible a mayúsculas y sin importar el orden)
                    foreach ($palabras_correctas_recuerdo as $palabra_correcta) {
                        if (in_array(strtolower($palabra_correcta), $palabras_ingresadas_recuerdo)) {
                            $resultado_moca_recuerdo_diferido++;
                        }
                    }
                    
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['fecha']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_fin']); ?></td>
                     
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['correo']); ?></td>
                       
                        <td class="puntaje"><?php echo htmlspecialchars($row['stroop_fase1']); ?></td>
                        <td class="puntaje"><?php echo htmlspecialchars($row['stroop_fase2']); ?></td>
                        <td class="puntaje"><?php echo htmlspecialchars($row['stroop_fase3']); ?></td>
                        <td><?php echo htmlspecialchars($row['moca_identificacion']); ?></td>
                        <td class="puntaje"><?php echo $resultado_moca_identificacion; ?></td> <!-- Mostrar el resultado de identificación -->
                        <td><?php echo htmlspecialchars($row['moca_memoria']); ?></td>
                        <td class="puntaje"><?php echo $resultado_moca_memoria; ?></td> <!-- Mostrar el resultado de memoria -->
                        <td><?php echo htmlspecialchars($row['moca_atencion']); ?></td>
                        <td class="puntaje"><?php echo $resultado_moca_atencion; ?></td> <!-- Mostrar el resultado de atención -->
                        <td><?php echo htmlspecialchars($row['moca_atencion_rev']); ?></td>
                        <td class="puntaje"><?php echo $resultado_moca_atencion_rev; ?></td> <!-- Mostrar el resultado de atención reverso -->
                        <td><?php echo htmlspecialchars($row['moca_atencion_numeros_100']); ?></td>
                        <td><?php echo htmlspecialchars($row['moca_atencion_letras_A']); ?></td>
                        <td class="puntaje"><?php echo $resultado_moca_atencion_letras; ?></td> <!-- Mostrar el resultado de atención letras -->
                        <td><?php echo htmlspecialchars($row['moca_abstraccion1']); ?></td>
                        <td><?php echo htmlspecialchars($row['moca_abstraccion2']); ?></td>
						<td><?php echo htmlspecialchars($row['moca_recuerdo_diferido']); ?></td>
                        <td class="puntaje"><?php echo $resultado_moca_recuerdo_diferido; ?></td> <!-- Mostrar el resultado de recuerdo diferido -->
                        <td><?php echo htmlspecialchars($row['moca_orientacion_fecha']); ?></td>
                        <td><?php echo htmlspecialchars($row['moca_orientacion_ciudad']); ?></td>
                        <td><?php echo htmlspecialchars($row['moca_orientacion_lugar']); ?></td>
                    </tr>

                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="30">No hay datos disponibles.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
	



    <?php
    // Cerrar la conexión
    $conn->close();
    ?>
</body>
</html>
