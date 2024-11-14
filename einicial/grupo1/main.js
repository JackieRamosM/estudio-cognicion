document.getElementById('community-ecotec').addEventListener('change', function() {
    if (!this.checked) {
        alert("LO SENTIMOS, ESTE ESTUDIO ES EXCLUSIVO DE LA UNIVERSIDAD ECOTEC");
        document.getElementById('submit-button').disabled = true; // Desactivar el botón
    } else {
        document.getElementById('submit-button').disabled = false; // Activar el botón
    }
});

document.getElementById('submit-button').addEventListener('click', function() {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const communityEcotec = document.getElementById('community-ecotec').checked ? 'Sí' : 'No';
    const consent = document.getElementById('consent').checked ? 'Sí' : 'No';
    const birthdate = document.getElementById('birthdate').value;
    const gender = document.getElementById('gender').value;
    const role = document.getElementById('role').value;
    const city = document.getElementById('city').value;
	
	

    // Validaciones
    if (!communityEcotec) {
        alert("LO SENTIMOS, ESTE ESTUDIO ES EXCLUSIVO DE LA UNIVERSIDAD ECOTEC");
        return;
    }

    if (!consent) {
        alert("Debes aceptar el consentimiento.");
        return;
    }
	
	const emailPattern = /^(.*@est\.ecotec\.edu\.ec|.*@ecotec\.edu\.ec)$/;
    if (!emailPattern.test(email)) {
        alert("El correo electrónico debe ser @est.ecotec.edu.ec o @ecotec.edu.ec.");
        return;
    }

	// Guardar nombre y correo en localStorage
    localStorage.setItem('name', name);
    localStorage.setItem('email', email);

    // Enviar los datos al servidor
    fetch('save_consent.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ 
            name, 
            email, 
            consent, 
            communityEcotec, 
            birthdate, 
            gender, 
            role, 
            city 
        })
    })
    .then(response => {
        console.log('Raw response:', response); // Muestra la respuesta cruda
        return response.json(); // Intenta convertir la respuesta a JSON
    })
    .then(data => {
        console.log('Parsed JSON:', data); // Muestra la respuesta parseada
        if (data.status === "success") {
            window.location.href = "stroop_instrucciones.html"; // Redirigir si se guarda correctamente
        } else {
            alert(data.message); // Mostrar mensaje de error
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Hubo un error al enviar los datos.");
    });
});



/*
document.getElementById('submit-button').addEventListener('click', function() {
    const form = document.getElementById('consent-form');
    const formData = new FormData(form);
	const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;


    // Validar que el consentimiento esté marcado
    if (!document.getElementById('consent').checked) {
        alert("Debes aceptar el consentimiento.");
        return;
    }
	
	// Guardar nombre y correo en localStorage
    localStorage.setItem('name', name);
    localStorage.setItem('email', email);

    // Enviar los datos usando AJAX
    fetch('save_consent.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.redirected) {
            window.location.href = response.url; // Redirigir si el servidor lo indica
        } else {
            return response.text();
        }
    })
    .then(data => {
        if (data) {
            alert(data); // Mostrar el mensaje en caso de error (por ejemplo, si el correo ya está registrado)
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Hubo un error al enviar los datos.");
    });
});
*/