let currentColorIndex = 0;
let colors = [];
let timer;
let timeLeft = 60; // Tiempo de 60 segundos
let aciertos = 0;

// Recuperar nombre y correo desde localStorage
const name = localStorage.getItem('name');
const email = localStorage.getItem('email');

// Recuperar el correo desde localStorage
//let email = localStorage.getItem('email');

if (!email) {
    alert("Error: No se encontró el correo del usuario.");
    window.location.href = "index.html"; // Redirigir si no se encuentra el correo
}

document.getElementById('user-info').innerText = `${name} (${email})`;	
// Guardar nombre y correo en localStorage

    localStorage.setItem('name', name);
    localStorage.setItem('email', email);

// Función para iniciar el cronómetro
function startTimer() {
    timer = setInterval(() => {
        timeLeft--;
        document.getElementById('timer').innerText = `Tiempo: ${timeLeft} segundos`;

        if (timeLeft <= 0) {
            clearInterval(timer);
            alert(`¡Tiempo terminado! Aciertos: ${aciertos}`);
            guardarAciertos(); // Guardar los aciertos en la base de datos
			
			 window.location.href = "stroop_fase2_instrucciones.html";
        }
    }, 1000);
}

// Función para obtener los colores del servidor (PHP)
async function fetchColors() {
    try {
        const response = await fetch('stroop_test.php');
        if (!response.ok) {
            throw new Error('Error al obtener los colores');
        }
        colors = await response.json();
        showNextColor(); // Mostrar el primer color
    } catch (error) {
        console.error(error);
    }
}

// Función para mostrar el siguiente color
function showNextColor() {
    if (currentColorIndex >= colors.length) {
        currentColorIndex = 0; // Reiniciar al primer color si se acaban los 100
    }

    const colorObj = colors[currentColorIndex];
    
    // Cambiar el texto y el color del texto
    const colorBox = document.getElementById('color-box');
    colorBox.innerText = colorObj.color; // Mostrar el nombre del color en español

    // Cambiar el color del texto usando el color mapeado
    const cssColor = colorMap[colorObj.color.toUpperCase()]; // Convertir el color a inglés
    if (cssColor) {
        colorBox.style.color = cssColor;  // Cambiar el color del texto
    } else {
        colorBox.style.color = 'black';  // Fallback si el color no se encuentra
    }
}


// Función para verificar si el botón presionado coincide con el color actual
// Función para verificar si el botón presionado coincide con el color actual
function checkColor(clickedColor) {
    const currentColor = colors[currentColorIndex].color;

    if (clickedColor === currentColor) {
        aciertos++;
		currentColorIndex++;
		showNextColor(); // Mostrar el siguiente color
    }

    
}



// Guardar los aciertos en la base de datos
function guardarAciertos() {
    const formData = new FormData();
    formData.append('email', email);
    formData.append('aciertos', aciertos);

    fetch('save_stroop.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log(data); // Mostrar respuesta del servidor
    })
    .catch(error => {
        console.error('Error al guardar los aciertos:', error);
    });
}

// Agregar eventos a los botones de color
document.querySelectorAll('.color-button').forEach(button => {
    button.addEventListener('click', (e) => {
        const clickedColor = e.target.getAttribute('data-color');
        checkColor(clickedColor);
    });
});

// Iniciar la prueba cuando la página cargue
window.onload = () => {
    fetchColors(); // Obtener los colores desde la base de datos
    startTimer();  // Iniciar el cronómetro
};



const colorMap = {
    'AZUL': 'blue',
    'ROJO': 'red',
    'VERDE': 'green',
    'AMARILLO': 'yellow'
};