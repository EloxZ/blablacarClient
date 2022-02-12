<?php
    session_start();

    $points = array();
    $msg = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($_POST['boton'] === '1') {
            $autonomia = $_POST['qa'];
            $res = file_get_contents("https://blablacariw.herokuapp.com/incidencia/autonomia/".$autonomia);
            $data = json_decode($res);
            if (isset($data->features) && !empty($data->features)) {
                $msg = "Incidencias en autonomia ".$autonomia;
                $points = $data->features;
            } else {
                $msg = "No se han encontrado incidencias para esta autonomia.";
            }
        } else if ($_POST['boton'] === '2') {
            $provincia = $_POST['qp'];
            $res = file_get_contents("https://blablacariw.herokuapp.com/incidencia/provincia/".$provincia);
            $data = json_decode($res);
            if(isset($data->features) && !empty($data->features)) {
                $msg = "Incidencias en provincia ".$provincia;
                $points = $data->features;
            } else {
                $msg = "No se han encontrado incidencias para esta provincia.";
            }
        } else if ($_POST['boton'] === '3') {
            $carretera = $_POST['qc'];
            $res = file_get_contents("https://blablacariw.herokuapp.com/incidencia/carretera/".$carretera);
            $data = json_decode($res);
            if(isset($data->features) && !empty($data->features)) {
                $msg = "Incidencias en carretera ".$carretera;
                $points = $data->features;
            } else {
                $msg = "No se han encontrado incidencias para esta carretera";
            }
        }

    } 
?>
<head>
    <title>Búsqueda de incidencias</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="/css/map.css" type="text/css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
   <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>
</head>
<body>
    <div id="topleft">
        Buscar incidencia por autonomia: <br />
        <form action="incidencias.php" method="POST">
            <input type="text" name="qa" size="50" value="">
            <input type="hidden" name="boton" value="1">
            <input type="submit" value="Search">
        </form>
    </div>
    <div id="topmiddle">
        Buscar incidencia por provincia: <br />
        <form action="incidencias.php" method="POST">
            <input type="text" name="qp" size="50" value="">
            <input type="hidden" name="boton" value="2">
            <input type="submit" value="Search">
        </form>
    </div>
    <div id="topright">
        Buscar incidencia por carretera: <br />
        <form action="incidencias.php" method="POST">
            <input type="text" name="qc" size="50" value="">
            <input type="hidden" name="boton" value="3">
            <input type="submit" value="Search">
        </form>
    </div>
    <div style="margin-top: -20px">
        <?php echo $msg; ?>
    </div>
    <div id="map">
        <script type="text/javascript">
            var map = L.map('map').setView([40,-3], 7);
            var tiles = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                maxZoom: 18,
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
                    'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                id: 'mapbox/streets-v11',
                tileSize: 512,
                zoomOffset: -1
            }).addTo(map);
            <?php
                if (!($points == null)) {
                    foreach($points as $point) {
                        ?> 
                            var marker = L.marker([<?php echo $point->geometry->y; ?>, <?php echo $point->geometry->x; ?>]).addTo(map);
                            marker.bindPopup("<b>Incidencia en Lat: <?php echo $point->geometry->y; ?>, Long: <?php echo $point->geometry->x; ?></b><br>Autonomia: <?php echo $point->attributes->autonomia; ?><br>Población: <?php echo $point->attributes->poblacion; ?><br>Carretera: <?php echo $point->attributes->carretera; ?><br>Causa: <?php echo $point->attributes->causa; ?><br>Fecha: <?php echo $point->attributes->fechahora_; ?><br>Nivel: <?php echo $point->attributes->nivel; ?><br>Tipo: <?php echo $point->attributes->tipo; ?><br>Sentido: <?php echo $point->attributes->sentido; ?><br>Actualizado: <?php echo $point->attributes->actualizad; ?>");
                        <?php
                    }
                }
            ?>

        </script>
        <noscript>
                <h2>Javascript not found</h2>
                <p>This application requires Javascript. Please enable it to view the map.</p>
        </noscript>
    </div>
</body>