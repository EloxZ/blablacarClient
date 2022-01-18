<?php
    session_start();

    if (isset($_SESSION['server_msg'])) {
        echo $_SESSION['server_msg'];
        unset($_SESSION['server_msg']);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $url = 'https://blablacariw.herokuapp.com/travels/add';
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);

        $data = array(
            "id_pasajeros" => [],
            "id_conductor" => $_POST['id_conductor'],
            "nombre_conductor" => $_POST['nombre_conductor'],
            "fecha_salida" => strtotime($_POST['fecha_salida']),
            "hora_salida" => strtotime($_POST['hora_salida']),            
            "lugar_salida" => $_POST['lugar_salida'],
            "lugar_llegada" => $_POST['lugar_llegada'],
            "price" => $_POST['price'],
            "currency" => 'EUR'
        );

        if (in_array("", $data) or in_array(false, $data)){
            //Checkea si hay algun campo vacio
            $_SESSION['server_msg'] = "algun campo vacio";
            header('Location: crear_viaje.php');
        } else {
        
            $json = json_encode($data);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $output = curl_exec($ch);
            $info = curl_getinfo($ch);
            curl_close($ch); 
            $result = json_decode($output);
            
            $_SESSION['server_msg'] = $result->data->msg;
            
            header('Location: ../index.php');
        }
    }
    else {
        $res = file_get_contents("https://blablacariw.herokuapp.com/users/edit/".$_SESSION['usuario']->_id);
        $nombre = json_decode($res)->data->usuario[0]->nombre; 
        include "../includes/header.php";
    }
?>


<h1>Crear viaje</h1>

<form action="crear_viaje.php" method="POST">

    <input placeholder="fecha_salida" name="fecha_salida" require>
    <input placeholder="hora_salida" name="hora_salida" require>
    <input placeholder="lugar_salida" name="lugar_salida" require>
    <input placeholder="lugar_llegada" name="lugar_llegada" require>
    <input placeholder="precio (EUR)" name="price" require>
    <input type="hidden" value=<?php echo $_SESSION['usuario']->_id?> name="id_conductor">
    <input type="hidden" value=<?php echo $nombre?> name="nombre_conductor">
    <!-- <input type="hidden" name="id_pasajeros[]" value="61c0ef8108a00e29cc6f9b9c"> -->
    <input type="submit" value="Crear">
</form>


<a href="../index.php" class="btn btn-danger">Cancelar</a>