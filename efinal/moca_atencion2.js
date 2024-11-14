document.getElementById('submit-button').addEventListener('click', function() {
    const respuesta = document.getElementById('respuesta').value || ''; // Asegurar valor vacío si no hay respuesta
    const email = localStorage.getItem('email');

    // Verificar si la respuesta está vacía
   // if (!respuesta) {
     //   alert("Por favor, escriba los números que escuchó.");
       // return;
    //}

    // Enviar los datos al servidor
    fetch('save_moca_atencion2.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email, moca_atencion_rev: respuesta })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("Respuestas guardadas correctamente.");
            window.location.href = "moca_atencion3.html"; // Redirigir a la siguiente página
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Hubo un error al enviar los datos.");
    });
});

// Recuperar nombre y correo desde localStorage
const name = localStorage.getItem('name');
const email = localStorage.getItem('email');
if (name && email) {
    document.getElementById('user-info').innerText = `${name} (${email})`;
} else {
    alert("Error: No se encontraron los datos del usuario.");
    window.location.href = "index.html";
}

// Verificar el estado de reproducción del audio
fetch('moca_atencion_rep2.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({ email })
})
.then(response => response.json())
.then(data => {
    if (data.moca_atencion_rep2 === 1) {
        document.getElementById('audio').controls = false; // Desactivar controles si ya se reprodujo
        document.getElementById('instructions').innerText = "El audio ya ha sido reproducido.";
    }
})
.catch(error => {
    console.error('Error al verificar el estado de reproducción:', error);
    alert("Hubo un error al verificar el estado de reproducción.");
});

// Mostrar la caja de texto y el botón al terminar el audio
const audio = document.getElementById('audio');
audio.addEventListener('ended', () => {
    document.getElementById('input-section').style.display = 'block';
});

// Bloquear reproducción del audio más de una vez
audio.addEventListener('play', () => {
    if (document.getElementById('audio').controls === false) {
        audio.pause(); // Si ya se reprodujo, pausar
        alert("El audio ya ha sido reproducido.");
    } else {
        // Guardar el estado de reproducción en la base de datos
        fetch('save_moca_atencion2.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, moca_atencion_rep2: 1 })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                console.log("Estado de reproducción guardado.");
            } else {
                alert("Error al guardar el estado de reproducción: " + data.message);
            }
        })
        .catch(error => {
            console.error('Error al guardar el estado de reproducción:', error);
            alert("Hubo un error al guardar el estado de reproducción.");
        });

        audio.removeAttribute('controls'); // Eliminar controles después de que se haya reproducido
    }
});
