<?php 

session_start();

$origenRaw = trim($_GET['origen']);
$origen = preg_replace('/\s+/', '+', $origenRaw);
$destinoRaw = trim($_GET['destino']);
$destino = preg_replace('/\s+/', '+', $destinoRaw);
$fecha = strtotime($_GET['fecha']);

$res = file_get_contents("http://blablacariw.herokuapp.com/travels?origen=" . $origen . "&destino=" . $destino);
$data = json_decode($res);
$viajes = array();

foreach ($data->data->viajes as $viaje){
    if (empty($fecha) || (!empty($fecha) && gmdate("d-m-Y", $fecha) === gmdate("d-m-Y", $viaje->fecha_salida))){
        array_push($viajes, $viaje);
    }
}

$_SESSION['viajes_encontrados'] = $viajes;
$count = count($viajes);

$espacios = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

if ($count == 0) {
    $msg = $espacios."No se han encontrado resultados.";
} else if ($count == 1) {
    $msg = $espacios."1 resultado encontrado.";
} else {
    $msg = $espacios.$count." resultados encontrados.";
}

$_SESSION['msgBusqueda'] = $msg; 

header('Location: ../../index.php');
?>