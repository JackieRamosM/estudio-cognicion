let currentNumber = 100; // Inicia en 100
let contador = 0; // Contador de números ingresados
let numerosIngresados = []; // Lista para guardar los números ingresados

document.getElementById('submit-button').addEventListener('click', function() {
    const input = document.getElementById('numero').value;

    // Verificar si el input es un número válido
    if (isNaN(input) || input === '') {
        alert("Por favor, ingrese un número válido.");
        return;
    }

    // Reemplazar el número actual con el ingresado
    const nuevoNumero = parseFloat(input);

    // Agregar el número a la lista
    numerosIngresados.push(nuevoNumero);

    // Actualizar el número actual
    currentNumber = nuevoNumero;

    // Actualizar el texto de las instrucciones con el nuevo número
    document.getElementById('current-number').innerText = currentNumber;

    // Actualizar el contador
    contador++;
    document.getElementById('contador').innerText = `${contador}/5`;

    // Limpiar la caja de texto
    document.getElementById('numero').value = '';

    // Verificar si se ha alcanzado el límite de 5 números
    if (contador === 5) {
        // Enviar los datos al servidor
        const email = localStorage.getItem('email');
        const numeros = numerosIngresados.join(',');

        fetch('save_moca_atencion3.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, moca_atencion_numeros_100: numeros })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                alert("Respuestas guardadas correctamente.");
                window.location.href = "moca_atencion4.html"; // Redirigir a la siguiente página
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => {
            console.error('Error al enviar los datos:', error);
            alert("Hubo un error al enviar los datos.");
        });
    }
});
