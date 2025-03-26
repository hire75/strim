<?php
// Lista de estaciones con sus URLs
$estaciones = [
    "Tapachula" => "https://s2.mexside.net/8168/stream",
    "Mazatlán" => "https://s2.mexside.net/8166/stream",
    "Test2" => "https://s2.mexside.net/8228/stream",
    "Coatzacoalcos" => "https://s2.mexside.net/8200/stream",
    "Colima" => "https://s2.mexside.net/8164/stream",
    "CDMX" => "https://s2.mexside.net/8160/stream",
    "Comarca Lagunera" => "https://s2.mexside.net/8130/stream",
    "Culiacán" => "https://s2.mexside.net/8094/stream",
    "Durango" => "https://s2.mexside.net/8128/stream",
    "La Paz" => "https://s2.mexside.net/8088/stream",
    "Medida" => "https://s2.mexside.net/8138/stream",
    "Campeche" => "https://s2.mexside.net/8132/stream",
    "Chihuahua" => "https://s2.mexside.net/8140/stream",
    "Villahermosa" => "https://s2.mexside.net/8134/stream",
    "Acapulco" => "https://s2.mexside.net/8136/stream",

];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reproductor de Radio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        audio {
            width: 80%;
            max-width: 600px;
            margin: 20px auto;
        }
        .en-linea {
            color: green;
            font-weight: bold;
        }
        .fuera-de-linea {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Reproductor de Radio</h1>
    <p id="estacion-actual">Cargando...</p>
    <audio id="reproductor" controls autoplay></audio>

    <script>
        // Convertir el array de PHP a un objeto JSON
        const estaciones = <?php echo json_encode($estaciones); ?>;
        const duracion = 30 * 1000; // 120 segundos en milisegundos
        let indiceActual = 0;
        let intervalo;

        function cambiarEstacion() {
            const nombres = Object.keys(estaciones);
            const urls = Object.values(estaciones);

            // Detener el ciclo si ya no hay más estaciones
            if (indiceActual >= nombres.length) {
                indiceActual = 0; // Volver al inicio si se llega al final
            }

            // Obtener la estación actual
            const nombreActual = nombres[indiceActual];
            const urlActual = urls[indiceActual];

            // Actualizar el reproductor
            const reproductor = document.getElementById("reproductor");
            reproductor.src = urlActual;

            // Mostrar el nombre de la estación mientras se carga
            const estacionActualElement = document.getElementById("estacion-actual");
            estacionActualElement.textContent = `Cargando: ${nombreActual}...`;
            estacionActualElement.className = ""; // Limpiar clase

            // Intentar reproducir la estación
            reproductor.play().then(() => {
                // Si la reproducción es exitosa, marcar como en línea
                estacionActualElement.textContent = `Reproduciendo: ${nombreActual}`;
                estacionActualElement.className = "en-linea";
            }).catch((error) => {
                // Si hay un error, marcar como fuera de línea
                estacionActualElement.textContent = `Error: No se pudo reproducir ${nombreActual}`;
                estacionActualElement.className = "fuera-de-linea";

                // Detener el ciclo
                clearInterval(intervalo);
                console.error(`Error al reproducir ${nombreActual}:`, error);
            });

            // Incrementar el índice para la próxima estación
            indiceActual++;
        }

        // Iniciar el ciclo
        intervalo = setInterval(cambiarEstacion, duracion);

        // Cambiar la primera estación inmediatamente
        cambiarEstacion();
    </script>
</body>
</html>
