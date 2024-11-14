document.getElementById('submit-button').addEventListener('click', function() {
    const word1 = document.getElementById('word_1').value || ''; // Reemplazar con vacío si no hay valor
    const word2 = document.getElementById('word_2').value || '';
    const word3 = document.getElementById('word_3').value || '';
    const word4 = document.getElementById('word_4').value || '';
    const word5 = document.getElementById('word_5').value || '';

    const email = localStorage.getItem('email');
    
    // Crear el valor que se va a guardar en la base de datos
    const moca_recuerdo_diferido = `${word1},${word2},${word3},${word4},${word5}`;

    // Enviar los datos al servidor
    fetch('save_moca_recuerdo_diferido.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email, moca_recuerdo_diferido })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("Respuestas guardadas correctamente.");
            window.location.href = "moca_orientacion.html"; // Redirigir a la siguiente página si se guarda correctamente
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Hubo un error al enviar los datos.");
    });
});
