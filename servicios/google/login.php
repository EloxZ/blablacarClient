<?php
require_once '../../vendor/autoload.php';
session_start();

$clienteID = '355043429392-p0keh6com6lldp10dkdificgl44f2unc.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUrl = 'http://blablacarclient.herokuapp.com/servicios/google/login.php';

// Nuevo cliente request a Google
$client = new Google_Client();
$client->setClientId($clienteID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUrl);
$client->addScope('profile');
$client->addScope('email');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    $gauth = new Google_Service_Oauth2($client);
    $google_info = $gauth->userinfo->get();

    // Comprobamos el token en la API
    $url = 'http://blablacariw.herokuapp.com/verify?email=' . $google_info->email."&nombre=".$google_info->givenName."&apellido=".$google_info->femilyName;

    // Hacemos un get con cabecera
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ' . $token['id_token']));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $output = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($output);

    if ($result->data->isVerified) {
        $original = array(
            "_id" => $result->data->usuario[0]->_id,
            "nombre" => $google_info->givenName,
            "apellido" => $google_info->familyName,
            "email" => $google_info->email
        );

        $usuario = new stdClass();

        foreach ($original as $key => $value) {
            $usuario->$key = $value;
        }

        // Almaceno en la sesión el login
        $_SESSION['token'] = $token;
        $_SESSION['usuario'] = $usuario;

        // Redirijo a index
        header('Location: /index.php');
    } else {
        $_SESSION['msg'] = 'Login denegadooo';
        header('Location: ../../login.php');
    }
} else {
    header('Location: ' . $client->createAuthUrl());
}
