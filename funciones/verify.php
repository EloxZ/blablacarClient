<?php

function verify($token, $email)
{
    $url = 'http://blablacarclient.herokuapp.com/verify/' . $email;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: ' . $token['id_token']));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $output = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($output);

    return $result->data->isVerified;
}
