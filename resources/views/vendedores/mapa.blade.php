<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa del Vendedor</title>
    <script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDy9dRTRDBConVlFlejv-GBFnSKsabAFEU&callback=initMap">
    </script>
    <style>
        #map {
            height: 100vh;
            width: 100%;
        }
    </style>
</head>
<body>
    <div id="map"></div>

    <script>
        function initMap() {
            // Obtiene las coordenadas de los parámetros de la URL
            const urlParams = new URLSearchParams(window.location.search);
            const lat = parseFloat(urlParams.get('lat'));
            const lng = parseFloat(urlParams.get('lng'));
            const codvendedor = urlParams.get('codvendedor');
            const fecha = decodeURIComponent(urlParams.get('fecha'));

            if (!lat || !lng) {
                alert('Coordenadas no válidas');
                return;
            }
            // Inicializa el mapa centrado en las coordenadas
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: { lat, lng },
            });

            // Agrega un marcador en las coordenadas
            const marker = new google.maps.Marker({
                position: { lat: lat, lng: lng },
                map: map,
                title: `Vendedor: ${codvendedor}`, // Título al pasar el mouse sobre el marcador
            });

            // Crea la ventana de información con los detalles
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div>
                        <p><strong>Código de Vendedor:</strong> ${codvendedor}</p>
                        <p><strong>Latitud:</strong> ${lat}</p>
                        <p><strong>Longitud:</strong> ${lng}</p>
                        <p><strong>Fecha:</strong> ${fecha}</p>
                    </div>
                `,
            });

            // Abre automáticamente la ventana de información
            infoWindow.open({
                anchor: marker,
                map,
                shouldFocus: false,
            });
        }
    </script>
</body>
</html>
