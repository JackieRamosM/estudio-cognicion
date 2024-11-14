const canvas = document.getElementById('canvas');
let startPoint = null;

// Obtener todos los puntos (números y letras)
const points = document.querySelectorAll('.point');

points.forEach(point => {
    point.addEventListener('click', (event) => {
        if (startPoint) {
            // Si ya hay un punto de inicio, dibujar línea
            const endPoint = event.target;
            drawLine(startPoint, endPoint);
            startPoint.classList.remove('selected'); // Limpiar la selección
            startPoint = null; // Reiniciar el punto inicial
        } else {
            // Si no hay punto de inicio, establecer el punto
            startPoint = event.target;
            startPoint.classList.add('selected'); // Resaltar el punto inicial
        }
    });
});

// Función para dibujar líneas
function drawLine(start, end) {
    const line = document.createElement('div');
    line.className = 'line';

    // Calcular la longitud y el ángulo de la línea
    const rectStart = start.getBoundingClientRect();
    const rectEnd = end.getBoundingClientRect();
    
    // Obtenemos las posiciones del lienzo
    const canvasRect = canvas.getBoundingClientRect();
    
    // Calcular las posiciones de los centros de los círculos
    const startX = rectStart.left + rectStart.width / 2 - canvasRect.left;
    const startY = rectStart.top + rectStart.height / 2 - canvasRect.top;
    const endX = rectEnd.left + rectEnd.width / 2 - canvasRect.left;
    const endY = rectEnd.top + rectEnd.height / 2 - canvasRect.top;

    const length = Math.sqrt(Math.pow(endX - startX, 2) + Math.pow(endY - startY, 2));
    line.style.width = length + 'px';
    line.style.left = startX + 'px';
    line.style.top = startY + 'px';
    line.style.transform = `rotate(${Math.atan2(endY - startY, endX - startX) * 180 / Math.PI}deg)`;

    // Añadir la línea al canvas
    canvas.appendChild(line);
}

// Manejar el evento de envío
document.getElementById('submit-button').addEventListener('click', () => {
    alert('Envío procesado (lógica no implementada).');
});
