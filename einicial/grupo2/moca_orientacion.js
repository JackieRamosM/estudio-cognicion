document.getElementById('submit-button').addEventListener('click', function() {
    const date = document.getElementById('date').value || ''; // Reemplazar con vacío si no hay valor
    const city = document.getElementById('city').value || '';
    const place = document.getElementById('place').value || '';

    const email = localStorage.getItem('email');
    
    // Crear el valor que se va a guardar en la base de datos
    const moca_orientacion = { date, city, place };

    // Enviar los datos al servidor
    fetch('save_moca_orientacion.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email, moca_orientacion })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("Respuestas guardadas correctamente.");
            window.location.href = "agradecimiento.html"; // Redirigir a la siguiente página si se guarda correctamente
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Hubo un error al enviar los datos.");
    });
});
