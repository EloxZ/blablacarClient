<?php
session_start();
if (isset($_SESSION['usuario']) && isset($_SESSION['token'])) {
    $user = (array) $_SESSION['usuario'];
    $resViajes = file_get_contents("https://blablacariw.herokuapp.com/travels?driver=" . $user['_id']);
    $dataViajes = json_decode($resViajes);
    //var_dump($dataViajes);

    $resViajesRes = file_get_contents("https://blablacariw.herokuapp.com/travels?passenger=" . $user['_id']);
    $dataViajesRes = json_decode($resViajesRes);
    //var_dump($dataViajesRes);

    $resUser = file_get_contents("https://blablacariw.herokuapp.com/users/" . $user['_id']);
    $dataUser = json_decode($resUser)->data->usuario[0];

    include "./includes/header.php";
} else {
    header('Location: /login.php');
}
?>

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <title>BlablacarIW - Mi cuenta</title>
</head>

<!--- Datos usuario --->
<h2 style="margin-top:40px; margin-left:10px">Mis datos</h2>
<p style="margin-left:20px">Nombre: <?php echo $user['nombre'] . " " . $user['apellido']; ?>
<p>
<p style="margin-left:20px">Email: <?php echo $user['email']; ?>
<p>

    <!--- Foto de perfil --->
<h3 style="margin-top:40px; margin-left:10px">Mi coche</h3>
<?php if (isset($dataUser->foto) && ($dataUser->foto !== "")) {
    echo "<img src='" . $dataUser->foto . "' style='margin-left:20px' width='500' height='500'>";
} else {
    echo "<p style='margin-left:20px'>Añade la foto de tu coche para que los demás usuarios te puedan reconocer.</p>";
} ?>

<div class="box" style="margin-top:40px; margin-left:20px">
    <form enctype="multipart/form-data" action="/funciones/enviar_imagen_user.php" method="POST">
        <h3>Subir imagen</h3>
        <input type="file" name="imagen" type="image/jpeg, image/jpg, image/png">
        <input value="<?php echo $user['_id']?>" id="id" name="id" type="hidden">
        <input type="submit" value="Enviar">
    </form>
</div>


    <!--- Tabla de viajes como conductor --->
    <?php if (isset($dataViajes) && sizeof($dataViajes->data->viajes) > 0) { ?>
<h3 style="margin-top:40px; margin-left:10px">Mis viajes</h3>
<table>
    <tr>
        <th>Fecha Salida</th>
        <th>Hora Salida</th>
        <th>Lugar Salida</th>
        <th>Lugar Llegada</th>
        <th>Precio</th>
    </tr>
    <?php foreach ($dataViajes->data->viajes as $viaje) { ?>
        <tr>
            <td><?php echo gmdate("d-m-Y", $viaje->fecha_salida); ?></td>
            <td><?php echo gmdate("H:i", $viaje->hora_salida); ?></td>
            <td><?php echo $viaje->lugar_salida; ?></td>
            <td><?php echo $viaje->lugar_llegada; ?></td>
            <td><?php echo $viaje->price;
                echo $viaje->currency ?></td>
            <form action="./servicios/viaje/delete_viaje.php" method="POST">
                <input type="hidden" value="<?php echo $viaje->_id ?>" name="id">
                <th><input type="submit" value="Eliminar"></th>
            </form>
            <form action="./servicios/viaje/edit_viaje.php" method="GET">
                <input type="hidden" value="<?php echo $viaje->_id ?>" name="id">
                <th><input type="submit" value="Editar"></th>
            </form>
        </tr>
    <?php } ?>
</table>
<?php } else { ?> <h3 style="margin-top:40px; margin-left:10px">No tienes ningún viaje creado.</h3> <?php } ?>

<!--- Tabla de viajes como pasajero --->
<?php if (isset($dataViajesRes) && sizeof($dataViajesRes->data->viajes) > 0) { ?>
    <h3 style="margin-top:40px; margin-left:10px">Viajes reservados</h3>
    <table>
        <tr>
            <th>Coche</th>
            <th>Conductor</th>
            <th>Fecha Salida</th>
            <th>Hora Salida</th>
            <th>Lugar Salida</th>
            <th>Lugar Llegada</th>
        </tr>
        <?php foreach ($dataViajesRes->data->viajes as $viaje) { 
            
            // Me traigo el nombre del conductor
            $data = file_get_contents("https://blablacariw.herokuapp.com/users/" . $viaje->id_conductor);
            $nombre_conductor = json_decode($data)->data->usuario[0]->nombre;
            $foto = json_decode($data)->data->usuario[0]->foto;
            if (!isset($foto) || $foto === "") {
                $foto = "https://e7.pngegg.com/pngimages/759/54/png-clipart-gray-vehicle-art-volkswagen-beetle-car-drawing-front-compact-car-volkswagen.png";
            }
            ?>
            <tr>
                <td><?php echo "<img src='" . $foto . "' style='margin-left:20px' width='200' height='200'>"; ?></td>
                <td><?php echo $nombre_conductor; ?></td>
                <td><?php echo gmdate("d-m-Y", $viaje->fecha_salida); ?></td>
                <td><?php echo gmdate("H:i", $viaje->hora_salida); ?></td>
                <td><?php echo $viaje->lugar_salida; ?></td>
                <td><?php echo $viaje->lugar_llegada; ?></td>
            </tr>
        <?php } ?>
    </table>
<?php } else { ?> <h3 style="margin-top:40px; margin-left:10px">No tienes ningún viaje reservado.</h3> <?php }

?>

<!--- Boton a conversación --->

<form action="./servicios/mensajeria/lista_conversaciones.php" method="GET">
                <input type="hidden" value="<?php echo $user['_id']?>" name="id">
                <input type="submit" value="Tus conversaciones">
</form>