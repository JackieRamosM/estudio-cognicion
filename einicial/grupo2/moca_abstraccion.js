document.getElementById('submit-button').addEventListener('click', function() {
    const similarity1 = document.getElementById('similarity1').value || ''; // Reemplazar con vacío si no hay valor
    const similarity2 = document.getElementById('similarity2').value || '';

    const email = localStorage.getItem('email');
    
    // Crear el objeto que se va a guardar en la base de datos
    const moca_abstraccion = {
        email,
        moca_abstraccion1: similarity1,
        moca_abstraccion2: similarity2
    };

    // Enviar los datos al servidor
    fetch('save_moca_abstraccion.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(moca_abstraccion)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("Respuestas guardadas correctamente.");
            window.location.href = "moca_recuerdo_diferido.html"; // Redirigir a la siguiente página si se guarda correctamente
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Hubo un error al enviar los datos.");
    });
});
