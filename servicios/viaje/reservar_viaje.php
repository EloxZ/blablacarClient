<?php 
    session_start();
    $res = file_get_contents("https://blablacariw.herokuapp.com/travels/" . $_POST['id']);
    $data = json_decode($res);

    array_push($data->data->viaje[0]->id_pasajeros, $_SESSION['usuario']->_id);


    $url = "https://blablacariw.herokuapp.com/travels/" . $_POST['id'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $data = array(
        "id_pasajeros" => $data->data->viaje[0]->id_pasajeros,
        "fecha_salida" => $data->data->viaje[0]->fecha_salida,
        "hora_salida" => $data->data->viaje[0]->hora_salida,
        "id_conductor" => $data->data->viaje[0]->id_conductor,
        "lugar_salida" => $data->data->viaje[0]->lugar_salida,
        "lugar_llegada" => $data->data->viaje[0]->lugar_llegada,
        "price" => $data->data->viaje[0]->price,
        "currency" => 'EUR'
    );


    $json = json_encode($data);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    $output = curl_exec($ch);
    $info = curl_getinfo($ch);
    $result = json_decode($output);
    $_SESSION['server_msg'] = $result->data->msg;

    header('Location: ../../perfil_usuario.php');
?>