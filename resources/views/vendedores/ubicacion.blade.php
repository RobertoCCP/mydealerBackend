<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de Vendedores</title>
    <style>
        #map {
            height: 100vh;
            /* Altura completa */
            width: 100%;
            /* Ancho completo */
        }
    </style>
</head>

<body>
<h1>Mapa de Vendedores</h1>
<div id="map"></div>

<!-- Script de Google Maps -->
<script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDy9dRTRDBConVlFlejv-GBFnSKsabAFEU&callback=initMap">
</script>

<script>
    async function initMap() {

        // Obtener las ubicaciones desde la API
        const response = await fetch('/api/vendedor/coordenadas'); // Cambia a tu endpoint real
        const data = await response.json();

        if (data.mensaje) {

            const ubicaciones = data.datos;
            let centro = {
                lat: parseFloat(ubicaciones[0].latitud),
                lng: parseFloat(ubicaciones[0].longitud)
            };
            // Centrar el mapa (puedes ajustar el centro según tu preferencia)
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 13,
                center: centro,
            });
            // Recorrer las ubicaciones y crear un marcador para cada una
            ubicaciones.forEach((ubicacion) => {
                const marker = new google.maps.Marker({
                    position: {
                        lat: parseFloat(ubicacion.latitud),//TODO: Cambiar centros de latitud y longitud
                        lng: parseFloat(ubicacion.longitud)
                    },
                    map: map,
                    title: `Vendedor: ${ubicacion.codvendedor}`, // Título del marcador
                });

                // Crear ventana de información
                const infoWindow = new google.maps.InfoWindow({
                    content: `<h3>Vendedor: ${ubicacion.codvendedor}</h3>
                        <p>Latitud: ${ubicacion.latitud}</p>
                        <p>Longitud: ${ubicacion.longitud}</p>
                        <p>bateria: ${ubicacion.bateria}</p>`,
                });

                // Agregar evento de clic al marcador para mostrar InfoWindow
                marker.addListener('click', () => {
                    infoWindow.open(map, marker);
                });
            });

        } else {
            alert('No se pudieron obtener las coordenadas.');
        }
    }
</script>
</body>

</html>
