<?php
session_start();
$nombre = $_SESSION['usuario']->usuario;
$apellido = $_SESSION['usuario']->apellido;
$email = $_SESSION['usuario']->email;

$url = 'https://blablacariw.herokuapp.com/users';
    
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, true);

$data = array(
    "nombre" => $nombre,
    "apellido" => $apellido,            
    "email" => $email
);

$json = json_encode($data);

curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

$output = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch); 
$result = json_decode($output);

unset($_SESSION['google_login']);

header('Location: ../index.php');