<?php 
    session_start();
    $user = (array) $_SESSION['usuario'];

    $resTravel = file_get_contents("https://blablacariw.herokuapp.com/travels/".$_GET['id']);
    $dataTravel = json_decode($resTravel);
    $viaje = $dataTravel->data->viaje[0];
    
    //var_dump($viaje);
    //var_dump($viaje->id_conductor);

    $resConductor = file_get_contents("https://blablacariw.herokuapp.com/users/".$viaje->id_conductor);
    $dataConductor = json_decode($resConductor);
    $conductor = $dataConductor->data->usuario[0];

    $resConversaciones = file_get_contents("https://blablacariw.herokuapp.com/conversations/".$user['_id']);
    $dataConversaciones = json_decode($resConversaciones);

    //var_dump($dataConductor);
    //var_dump($dataConversaciones->data);

    include "../../includes/header.php";
    error_reporting(E_ERROR | E_PARSE);

?>
<section class="container">
<h1>Detalles del viaje</h1>
<h3>Trayecto: <?php echo $viaje->lugar_salida?> - <?php echo $viaje->lugar_llegada?></h3>
<h3>Conductor: <?php echo $conductor->nombre?> <?php echo $conductor->apellido?> (<?php echo $conductor->email?>)</h3>
<?php if (!isset($conductor->foto) || $conductor->foto === "")
    echo "<img src='https://e7.pngegg.com/pngimages/759/54/png-clipart-gray-vehicle-art-volkswagen-beetle-car-drawing-front-compact-car-volkswagen.png' style='width:50px;height:50px;'>";
    else
    echo "<img src='".$conductor->foto."' style='width:500px;height:500px;'>";
?>
<h3>Fecha: <?php echo gmdate("d-m-Y", $viaje->fecha_salida);?></h3>
<h3>Hora de salida: <?php gmdate("H:i", $viaje->hora_salida); ?></h3>
<h3>Precio: <?php echo $viaje->price; echo $viaje->currency?></h3>

<?php
    if($viaje->id_conductor != $user['_id']){
?>
<form action="reservar_viaje.php" method="POST">
    <input type="hidden" value="<?php echo $viaje->_id ?>" name="id">
    <td><input type="submit" value="Reservar"></td>
</form>

<?php } ?>

<?php
    //var_dump(in_array($conductor, $dataConversaciones->data->usuarios));
    if($conductor->_id != $user['_id']){ ?>
    <h3>Contactar con el conductor: </h3>
    <?php
    if (in_array($conductor, $dataConversaciones->data->usuarios)) { ?>
            <form action="../mensajeria/ver_conversacion.php" method="GET">
                <input type="hidden" value="<?php echo $conductor->_id?>" name="id_ajeno">
                <input type="hidden" value="<?php echo $user['_id']?>" name="id_local">
                <input type="submit" value="Ver conversaci??n con el conductor">
            </form>

        <?php } else { ?>
            <form action="../mensajeria/crear_conversacion.php" method="POST">
                <input type="hidden" value="<?php echo $conductor->_id?>" name="select">
                <input type="hidden" value="<?php echo $user['_id']?>" name="id_local">
                <input type="submit" value="Empezar conversaci??n con el conductor">
            </form>
        <?php }}
    ?>
</section>