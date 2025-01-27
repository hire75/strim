<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Transmisiones en Vivo</title>
    <style>
        body {
            margin: 0;
            background-color: #121212;
            color: #ffffff;
            font-family: Arial, sans-serif;
        }
        .container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
            justify-content: center;
            max-width: 1200px;
            margin: auto;
        }
        .iframe-container {
            background-color: #1e1e1e;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        .iframe-container input {
            width: calc(100% - 20px);
            padding: 8px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #1e1e1e;
            color: #ffffff;
        }
        .iframe-container input:focus {
            outline: 2px solid #007bff;
        }
        .iframe-wrapper {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%; /* Relación de aspecto 16:9 */
        }
        .iframe-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 8px;
        }
        .error-message {
            color: #ff4d4d;
            font-size: 0.9em;
            text-align: center;
            display: none;
        }
        .platform-name {
            color: #007bff;
            font-size: 0.9em;
            font-weight: bold;
            text-align: center;
        }
        h1 {
            text-align: center;
            margin: 20px 0;
        }
        .refresh-button {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            padding: 8px 16px;
            cursor: pointer;
            font-size: 0.9em;
        }
        .refresh-button:hover {
            background-color: #005bb5;
        }
        .twitter-tweet {
            margin: auto;
        }
        .stream-not-supported {
            text-align: center;
            padding: 20px;
            background-color: #2c2c2c;
            border-radius: 10px;
            margin-top: 10px;
        }
        .stream-not-supported a {
            color: #007bff;
            text-decoration: none;
        }
        .stream-not-supported a:hover {
            text-decoration: underline;
        }
    </style>
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
                iframeWrapper.innerHTML = data.embedCode || "";
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

    <?php
    // Script PHP para procesar las URLs
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);
        $url = $data['url'];

        // Validar la URL
        $youtubePattern = '/^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)/';
        $facebookPattern = '/^(https?:\/\/)?(www\.)?facebook\.com\/.+\/videos\/.+/';
        $twitterPattern = '/^(https?:\/\/)?(www\.)?(twitter\.com|x\.com)\/.+\/status\/.+/';

        if (preg_match($youtubePattern, $url)) {
            $embedUrl = str_replace("watch?v=", "embed/", $url);
            echo json_encode([
                'valid' => true,
                'platform' => 'YouTube',
                'embedCode' => "<iframe src='$embedUrl' allowfullscreen></iframe>",
            ]);
        } elseif (preg_match($facebookPattern, $url)) {
            $embedUrl = "https://www.facebook.com/plugins/video.php?href=" . urlencode($url);
            echo json_encode([
                'valid' => true,
                'platform' => 'Facebook',
                'embedCode' => "<iframe src='$embedUrl' allowfullscreen></iframe>",
            ]);
        } elseif (preg_match($twitterPattern, $url)) {
            echo json_encode([
                'valid' => true,
                'platform' => 'Twitter',
                'embedCode' => "<blockquote class='twitter-tweet' data-lang='es' data-theme='dark'><a href='$url'></a></blockquote>",
            ]);
        } else {
            // Enlace no compatible
            echo json_encode([
                'valid' => false,
                'embedCode' => "<div class='stream-not-supported'><p>Este enlace no se puede incrustar. Por favor, visita el sitio original:</p><a href='$url' target='_blank'>Ver en sitio original</a></div>",
            ]);
        }
    }
    ?>
</body>
</html>
