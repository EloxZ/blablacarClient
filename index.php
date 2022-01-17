<?php
    session_start();
    $res = file_get_contents("https://blablacariw.herokuapp.com//");
    $dataUsers = json_decode($res);
    $resViajes = file_get_contents("https://blablacariw.herokuapp.com//listaviajes");
    $dataViajes = json_decode($resViajes);

if (isset($_SESSION['server_msg'])) {
    echo $_SESSION['server_msg'];
    unset($_SESSION['server_msg']);
}
error_reporting(E_ERROR | E_PARSE);

if (isset($_SESSION['usuario'])) {
    $email = $_SESSION['usuario']->email;

    // Compruebo si el email existe en la BD
<<<<<<< HEAD
    $data = file_get_contents("https://blablacariw.herokuapp.com//findUserByEmail/" . $email);
=======
    $data = file_get_contents("https://blablacariw.herokuapp.com/findUserByEmail/" . $email);
>>>>>>> 46687286e0d79afa105bf5d92cf4dd17f2aac34d
    $user = json_decode($data);

    // Si existe -> me traigo su información y lo guardo
    if (!empty($user->data->usuarios)) {
        unset($_SESSION['google_login']);
        unset($user->data->usuarios[0]->password);
        $_SESSION['usuario'] = $user->data->usuarios[0];
    } else {
        // Si no existe -> lo inserto en la BD e inicializo sus valores
        //header('Location: /funciones/nuevo_usuario.php');
    }
    error_reporting(E_ERROR | E_PARSE);
}

include 'includes/header.php';

?>

<div class="container">
    <div class="search">
        <form action="lista_viajes.php" method="GET">
            <input type="text" name="origen" placeholder="Origen" required>
            <input type="text" name="destino" placeholder="Destino" required>
            <input type="date" name="fecha" required>
            <input type="time" name="hora" required>
            <input type="submit" value="Buscar">
        </form>
    </div>
</div>

    include 'includes/buscador_incidencias.php';
    
    include 'includes/mapa.php';
    
    if ($_SESSION['usuario']->admin != null){
        include 'includes/usuarios.php';
    }
    
    include 'includes/viajes.php';
    
    include 'includes/footer.php';
    
    include 'includes/footer.php';

?>