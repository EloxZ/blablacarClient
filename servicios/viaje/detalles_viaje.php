<?php 
    $resTravel = file_get_contents("https://blablacariw.herokuapp.com/travels/".$_GET['id']);
    $dataTravel = json_decode($resTravel);
    $viaje = $dataTravel->data->viaje[0];
    
    //var_dump($viaje);
    //var_dump($viaje->id_conductor);

    $resConductor = file_get_contents("https://blablacariw.herokuapp.com/users/".$viaje->id_conductor);
    $dataConductor = json_decode($resConductor);
    $conductor = $dataConductor->data->usuario[0];

    $resConversaciones = file_get_contents("https://blablacariw.herokuapp.com/conversaciones/".$_GET['id_local']);
    $dataConversaciones = json_decode($resConversaciones);

    //var_dump($dataConductor);

    error_reporting(E_ERROR | E_PARSE);

?>

<h1>Detalles del viaje</h1>
<h3>Trayecto: <?php echo $viaje->lugar_salida?> - <?php echo $viaje->lugar_llegada?></h3>
<h3>Conductor: <?php echo $conductor->nombre?> <?php echo $conductor->apellidos?> (<?php echo $conductor->email?>)</h3>
<?php if ($dataConductor->data->foto==null)
    echo "<img src='https://acortar.link/mZkcJS' style='width:30px;height:30px;'?></td>";
    else
    echo "<img src='".$usuario->foto."' style='width:30px;height:30px;'?></td>";
?>
<h3>Fecha: <?php echo gmdate("d-m-Y", $viaje->fecha_salida);?></h3>
<h3>Hora de salida: <?php gmdate("H:i", $viaje->hora_salida); ?></h3>
<h3>Precio: <?php echo $viaje->price; echo $viaje->currency?></h3>

<?php
    if($viaje->id_conductor != $conductor->_id){
?>
<form action="reservar_viaje.php" method="POST">
    <input type="hidden" value="<?php echo $viaje->_id ?>" name="id">
    <td><input type="submit" value="Reservar"></td>
</form>

<?php } ?>

<h3>Contactar con el conductor: </h3>
<?php
    if (in_array($conductor, $dataConversaciones->data->usuarios)) { ?>
            <form action="../mensajeria/ver_conversacion.php" method="GET">
                <input type="hidden" value="<?php echo $conductor->_id?>" name="id_ajeno">
                <input type="hidden" value="<?php echo $_GET['id_local']?>" name="id_local">
                <input type="submit" value="Ver conversación con el conductor">
            </form>

        <?php } else { ?>
            <form action="../mensajeria/crear_conversacion.php" method="POST">
                <input type="hidden" value="<?php echo $conductor->_id?>" name="select">
                <input type="hidden" value="<?php echo $_GET['id_local']?>" name="id_local">
                <input type="submit" value="Empezar conversación con el conductor">
            </form>
        <?php }
    ?>
