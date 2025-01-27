<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Transmisiones en Vivo</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Mis Transmisiones en Vivo</h1>
    <div class="container">
        <!-- Caja 1 -->
        <div class="iframe-container">
            <input type="text" id="url1" placeholder="Ingresa el enlace del video">
            <span class="error-message" id="error1">URL no válida. Usa enlaces compatibles (YouTube, Facebook, Twitter).</span>
            <div class="iframe-wrapper" id="iframe-wrapper1"></div>
            <span class="platform-name" id="platform1"></span>
            <button class="refresh-button" onclick="refreshIframe(1)">Refrescar</button>
        </div>
        <!-- Repite para más cajas si es necesario -->
    </div>

    <script src="scripts.js"></script>
    <script>
        // Cargar el script de Twitter
        const twitterScript = document.createElement('script');
        twitterScript.src = "https://platform.twitter.com/widgets.js";
        twitterScript.async = true;
        document.body.appendChild(twitterScript);

        // Función para validar y cargar el video
        async function validateUrl(inputId) {
            const inputElement = document.getElementById(`url${inputId}`);
            const errorMessage = document.getElementById(`error${inputId}`);
            const iframeWrapper = document.getElementById(`iframe-wrapper${inputId}`);
            const platformName = document.getElementById(`platform${inputId}`);
            const videoUrl = inputElement.value.trim();

            // Enviar la URL al servidor para validación
            const response = await fetch('process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ url: videoUrl }),
            });
            const data = await response.json();

            if (data.valid) {
                errorMessage.style.display = "none";
                iframeWrapper.innerHTML = data.embedCode;
                platformName.textContent = data.platform;

                // Cargar el widget de Twitter si es necesario
                if (data.platform === "Twitter" && window.twttr) {
                    window.twttr.widgets.load(iframeWrapper);
                }
            } else {
                errorMessage.style.display = "block";
                iframeWrapper.innerHTML = "";
                platformName.textContent = "";
            }
        }

        // Función para refrescar el iframe
        function refreshIframe(inputId) {
            const iframeWrapper = document.getElementById(`iframe-wrapper${inputId}`);
            if (iframeWrapper.innerHTML) {
                iframeWrapper.innerHTML = iframeWrapper.innerHTML;
                if (window.twttr) {
                    window.twttr.widgets.load(iframeWrapper);
                }
            }
        }
    </script>
</body>
</html>
