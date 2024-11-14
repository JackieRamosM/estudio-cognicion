document.getElementById('submit-button').addEventListener('click', function() {
    // Obtener los valores de los campos de texto
    const animal1 = document.getElementById('animal1').value || ''; // Reemplazar con vacío si no hay valor
    const animal2 = document.getElementById('animal2').value || '';
    const animal3 = document.getElementById('animal3').value || '';

    const name = localStorage.getItem('name');
    const email = localStorage.getItem('email');
    
    // Crear el valor que se va a guardar en la base de datos
    const moca_identificacion = `${animal1},${animal2},${animal3}`;

    // Agregar log para verificar los datos antes de enviarlos
    console.log({ email, moca_identificacion });

    // Enviar los datos al servidor
    fetch('save_moca_identificacion.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email, moca_identificacion })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Respuesta del servidor:', data); // Ver respuesta del servidor
        if (data.status === "success") {
            alert("Respuestas guardadas correctamente.");
            window.location.href = "moca_memoria_instrucciones.html"; // Redirigir si se guardan correctamente
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Hubo un error al enviar los datos.");
    });
});
