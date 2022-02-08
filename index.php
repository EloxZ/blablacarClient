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
            $data = file_get_contents("https://blablacariw.herokuapp.com/users/" . $_SESSION['usuario']->_id);
            $email_conductor = json_decode($data)->data->usuario[0]->email;
        ?>
            <tr>
                <td><?php echo $email_conductor; ?></td>
                <td><?php echo gmdate("d-m-Y", $viaje->fecha_salida); ?></td>
                <td><?php echo gmdate("H:i", $viaje->hora_salida); ?></td>
                <td><?php echo $viaje->lugar_salida; ?></td>
                <td><?php echo $viaje->lugar_llegada; ?></td>
                <td><?php echo $viaje->price; ?>€</td>
                <form action="servicios/viaje/reservar_viaje.php" method="POST">
                    <input type="hidden" value="<?php echo $viaje->_id ?>" name="id">
                    <td><input type="submit" value="Reservar"></td>
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