<?php
session_start();
$dataViajes = file_get_contents("https://blablacariw.herokuapp.com/travels");
$viajes = json_decode($dataViajes)->data->viajes;

include './includes/header.php';

?>

<section class="container">
    <h1>Viajes</h1>

    <table>
        <tr>
            <th>Conductor</th>
            <th>Fecha Salida</th>
            <th>Hora Salida</th>
            <th>Lugar Salida</th>
            <th>Lugar Llegada</th>
        </tr>
        <?php
        foreach ($viajes as $viaje) {
            // Me traigo el nombre del conductor
            $data = file_get_contents("https://blablacariw.herokuapp.com/users/" . $viaje->id_conductor);
            $nombre_conductor = json_decode($data)->data->usuarios[0]->nombre;
        ?>
            <tr>
                <td><?php echo $nombre_conductor; ?></td>
                <td><?php echo gmdate("d-m-Y", $viaje->fecha_salida); ?></td>
                <td><?php echo gmdate("H:i", $viaje->hora_salida); ?></td>
                <td><?php echo $viaje->lugar_salida; ?></td>
                <td><?php echo $viaje->lugar_llegada; ?></td>
                <td><?php echo $viaje->price; ?></td>
                <form action="../servicios/viaje/delete_viaje.php" method="POST">
                    <input type="hidden" value="<?php echo $viaje->_id ?>" name="id">
                    <th><input type="submit" value="Eliminar"></th>
                </form>
                <form action="edit_viaje.php" method="GET">
                    <input type="hidden" value="<?php echo $viaje->_id ?>" name="id">
                    <th><input type="submit" value="Editar"></th>
                </form>
            </tr>

        <?php } ?>
    </table>
</section>