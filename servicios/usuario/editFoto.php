<?php
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('Location: /login.php');
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $url = 'https://blablacariw.herokuapp.com/users/'.$_POST['id'];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


        $data = array(
            "foto" => $_POST['foto']
        );


        $json = json_encode($data);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch); 
        $result = json_decode($output);
        
        $_SESSION['server_msg'] = $result->data->msg;
        
        header('Location: /perfil_usuario.php');
    }
    else {
        header('Location: /index.php');
    }
?>