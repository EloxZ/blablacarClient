<?php 

session_start();

echo "Hola";

$origen = trim($_GET['origen']);
$destino = trim($_GET['destino']);
$fecha = strtotime($_GET['fecha']);

echo $origen;
echo $destino;
echo $fecha;

$res = file_get_contents("http://blablacariw.herokuapp.com/travels?origen=" . $origen . "&destino=" . $destino);
$data = json_decode($res);
$viajes = array();

echo $res;
echo $data;

foreach ($data->viajes as $viaje){
    //if (!empty($fecha) && $fecha === $viaje->fecha_salida){
        array_push($viajes, $viaje);
    //}
}
var_dump($viajes);

$_SESSION['viajes_encontrados'] = $viajes;
header('Location: ../../index.php');
?>