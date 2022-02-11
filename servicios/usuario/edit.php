<?php
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('Location: /login.php');
    } else if (!isset($_SESSION['admin'])) {
        header('Location: /index.php');
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $url = 'https://blablacariw.herokuapp.com/users/'.$_POST['id'];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if ($_POST['modo'] === '1') {
            $data = array(
                "nombre" => $_POST['nombre'],
                "apellido" => $_POST['apellido'],            
                "email" => $_POST['email']
            );
        } else {
            $data = array(
                "foto" => $_POST['foto']
            );
        }

        $json = json_encode($data);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch); 
        $result = json_decode($output);
        
        $_SESSION['server_msg'] = $result->data->msg;
        
        header('Location: /admin/users.php');
    }
    else {
        $res = file_get_contents("https://blablacariw.herokuapp.com/users/".$_GET['id']);
        $data = json_decode($res); 
        include '../../includes/header.php';
    }
?>
<section class="container">
    <h1>Editar usuario</h1>

    <form action="edit.php" method="POST">
        <input value="<?php echo $data->data->usuario[0]->_id?>" name="id" type="hidden">
        <input value="<?php echo $data->data->usuario[0]->nombre?>" name="nombre" placeholder="Nombre">
        <input value="<?php echo $data->data->usuario[0]->apellido?>" name="apellido" placeholder="Apellido">
        <input value="<?php echo $data->data->usuario[0]->email?>" name="email" placeholder="E-mail">
        <input type="hidden" name="modo" value="1">
        <input type="submit" value="Editar">
    </form>

    <a href="../../admin/admin.php" class="btn btn-danger">Cancelar</a>
    <br/>
    <div class="box">
        <form enctype="multipart/form-data" action="../../funciones/enviar_imagen.php" method="POST">
            <h3>Subir imagen</h3>
            <input type="file" name="imagen" type="image/jpeg, image/jpg, image/png">
            <input value="<?php echo $data->data->usuario[0]->_id?>" id="id" name="id" type="hidden">
            <input type="submit" value="Enviar">
        </form>
    </div>
</section>

<?php include '../../includes/footer.php' ?>