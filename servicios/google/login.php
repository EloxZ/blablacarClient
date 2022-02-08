<?php
require_once '../../vendor/autoload.php';
session_start();

$clienteID = '355043429392-p0keh6com6lldp10dkdificgl44f2unc.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUrl = 'http://blablacariw.heroku.com/servicios/google/login.php';

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
    $url = 'http://blablacariw.herokuapp.com/users/verify/' . $google_info->email;

    // Hacemos un get con cabecera
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ' . $token['id_token']));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $output = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($output);

    //comprobamos si el usuario ya ha iniciado sesion anteriormente en el sistema
    $data = file_get_contents("https://blablacariw.herokuapp.com/users?email=" . $google_info->email);
    $user = json_decode($data);
    if (!isset($user->data->usuarios[0]->nombre)){
        //actualizamos el usuario para meterle nombre y apellidos
        $url = 'https://blablacariw.herokuapp.com/users/'.$user->data->usuarios[0]->_id;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        
        $data = array(
            "nombre" => $google_info->givenName,
            "apellido" => $google_info->familyName
        );
    

        $json = json_encode($data);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        
        $output = curl_exec($ch);
        curl_close($ch); 
    }

    if ($result->data->isVerified) {
        $original = array(
            "id" => $result->data->usuario[0]->_id,
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
