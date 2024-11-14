let currentColorIndex = 0;
let colors = [];
let timer;
let timeLeft = 60; // Tiempo de 60 segundos
let aciertos = 0;

// Recuperar nombre y correo desde localStorage
const name = localStorage.getItem('name');
const email = localStorage.getItem('email');

// Verificar si el correo está disponible
if (!email) {
    alert("Error: No se encontró el correo del usuario.");
    window.location.href = "index.html"; // Redirigir si no se encuentra el correo
}

// Mostrar el nombre y el correo en la parte superior derecha
document.getElementById('user-info').innerText = `${name} (${email})`;

// Función para iniciar el cronómetro
function startTimer() {
    timer = setInterval(() => {
        timeLeft--;
        document.getElementById('timer').innerText = `Tiempo: ${timeLeft} segundos`;

        if (timeLeft <= 0) {
            clearInterval(timer);
            alert(`¡Tiempo terminado! Aciertos: ${aciertos}`);
            guardarAciertos(); // Guardar los aciertos en la base de datos
			window.location.href = "moca_instrucciones.html"; // Redirigir a las instrucciones del MoCA
        }
    }, 1000);
}

// Función para obtener los colores del servidor (PHP)
async function fetchColors() {
    try {
        const response = await fetch('stroop_test_p3.php');
        if (!response.ok) {
            throw new Error('Error al obtener los colores');
        }
        colors = await response.json();
        showNextColor(); // Mostrar el primer color
    } catch (error) {
        console.error(error);
    }
}

// Función para mostrar el TEXTO pintado con el color de la base de datos
function showNextColor() {
    if (currentColorIndex >= colors.length) {
        currentColorIndex = 0; // Reiniciar al primer color si se acaban los 100
    }

    const colorObj = colors[currentColorIndex];
    
    // Cambiar el texto y el color del texto
    const colorBox = document.getElementById('color-box');
    colorBox.innerText = colorObj.TEXTO; // Mostrar el texto
    colorBox.style.color = colorMap[colorObj.color.toUpperCase()]; // Aplicar el color mapeado
}

// Función para verificar si el botón presionado coincide con el color actual
function checkColor(clickedColor) {
    const currentColor = colors[currentColorIndex].color;

    if (clickedColor === currentColor) {
        aciertos++;
        currentColorIndex++; // Avanzar solo si es correcto
        showNextColor(); // Mostrar el siguiente color
    } 
}

// Guardar los aciertos en la base de datos
function guardarAciertos() {
    const formData = new FormData();
    formData.append('email', email);
    formData.append('aciertos', aciertos);

    fetch('save_stroop_p3.php', {
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

// Diccionario de colores en español a inglés
const colorMap = {
    'AZUL': 'blue',
    'ROJO': 'red',
    'VERDE': 'green',
    'AMARILLO': 'yellow'
};
