<?php
session_start();
if (isset($_SESSION['server_msg'])) {
    echo $_SESSION['server_msg'];
    unset($_SESSION['server_msg']);
}
error_reporting(E_ERROR | E_PARSE);

include 'includes/header.php';

?>

<div class="container">
    <form action="./servicios/viaje/buscar_viajes.php" method="GET">
        <div class="search__box">
            <input type="text" name="origen" placeholder="Origen">
            <input type="text" name="destino" placeholder="Destino">
            <input type="date" name="fecha">
            <input type="submit" value="Buscar">
        </div>
    </form>
</div>

<!-- Viajes encontrados -->
<?php
if (isset($_SESSION['msgBusqueda'])) {
    echo $_SESSION['msgBusqueda'];
    unset($_SESSION['msgBusqueda']);
}

if (isset($_SESSION['viajes_encontrados']) && !empty($_SESSION['viajes_encontrados'])) {
    $viajes = $_SESSION['viajes_encontrados'];
} else {
    unset($_SESSION['viajes_encontrados']);
    $res = file_get_contents("http://blablacariw.herokuapp.com/travels");
    $viajes = json_decode($res)->data->viajes;
} ?>
<section class="container">


    <table>
        <tr>
            <th>Conductor</th>
            <th>Fecha Salida</th>
            <th>Hora Salida</th>
            <th>Lugar Salida</th>
            <th>Lugar Llegada</th>
            <th>Precio</th>
        </tr>
        <?php
        foreach ($viajes as $viaje) {
            // Me traigo el nombre del conductor
            $data = file_get_contents("https://blablacariw.herokuapp.com/users/" . $viaje->id_conductor);
            var_dump($viaje->id_conductor);
            $nombre_conductor = json_decode($data)->data->usuarios[0]->nombre;
        ?>
            <tr>
                <td><?php echo $nombre_conductor; ?></td>
                <td><?php echo gmdate("d-m-Y", $viaje->fecha_salida); ?></td>
                <td><?php echo gmdate("H:i", $viaje->hora_salida); ?></td>
                <td><?php echo $viaje->lugar_salida; ?></td>
                <td><?php echo $viaje->lugar_llegada; ?></td>
                <td><?php echo $viaje->price; ?>€</td>
                <form action="servicios/viaje/detalles_viaje.php" method="GET">
                    <input type="hidden" value="<?php echo $viaje->_id ?>" name="id">
                    <td><input type="submit" value="Detalles"></td>
                </form>
            </tr>

        <?php
            unset($_SESSION['viajes_encontrados']);
        } ?>
    </table>
</section>

<!-- .Viajes encontrados -->

<?php

include 'includes/footer.php';

?>