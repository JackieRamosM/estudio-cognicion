const selectedLetters = [];

// Maneja el clic en las letras
document.querySelectorAll('.letter').forEach(letter => {
    letter.addEventListener('click', function() {
        const letterValue = this.dataset.letter;

        // Cambiar el color al hacer clic
        if (this.style.color === 'blue') {
            this.style.color = ''; // Revertir el color
            const index = selectedLetters.lastIndexOf(letterValue);
            if (index > -1) {
                selectedLetters.splice(index, 1); // Eliminar la letra de la selección
            }
        } else {
            this.style.color = 'blue'; // Cambiar el color a azul
            selectedLetters.push(letterValue); // Agregar la letra a la selección
        }
    });
});

// Maneja el clic en el botón de enviar
document.getElementById('submit-button').addEventListener('click', function() {
    const email = localStorage.getItem('email');
    const letrasSeleccionadas = selectedLetters.length > 0 ? selectedLetters.join(',') : ''; // Enviar vacío si no hay letras seleccionadas

    fetch('save_moca_atencion4.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({ email, moca_atencion_letras: letrasSeleccionadas })
	})
	.then(response => {
		return response.text(); // Cambiar a text para verificar la respuesta
	})
	.then(text => {
		//console.log(text); // Imprimir la respuesta para depurar
		return JSON.parse(text); // Intentar parsear a JSON
	})
	.then(data => {
		if (data.status === "success") {
			alert("Respuestas guardadas correctamente.");
			window.location.href = "moca_abstraccion.html"; // Redirigir a la siguiente página
		} else {
			alert("Error: " + data.message);
		}
	})
	.catch(error => {
		console.error('Error al enviar los datos:', error);
		alert("Hubo un error al enviar los datos.");
	});

});
