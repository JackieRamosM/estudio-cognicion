document.getElementById('submit-button').addEventListener('click', function() {
    const respuesta = document.getElementById('respuesta').value || ''; // Asegurar valor vacío si no hay respuesta
    const email = localStorage.getItem('email');

   // if (!respuesta) {
     //   alert("Por favor, escriba los números que escuchó.");
       // return;
    //}

    // Enviar los datos al servidor
    fetch('save_moca_atencion.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email, moca_atencion: respuesta })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
           // alert("Respuestas guardadas correctamente.");
            window.location.href = "moca_atencion2.html"; // Redirigir a la siguiente página
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Hubo un error al enviar los datos.");
    });
});
