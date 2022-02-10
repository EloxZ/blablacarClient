<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = "https://blablacariw.herokuapp.com/travels/" . $_POST['id'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


    $data = array(
        "id_pasajeros" => [],
        "fecha_salida" => strtotime($_POST['fecha_salida']),
        "hora_salida" => strtotime($_POST['hora_salida']),
        "id_conductor" => trim($_POST['id_conductor']),
        "lugar_salida" => trim($_POST['lugar_salida']),
        "lugar_llegada" => trim($_POST['lugar_llegada']),
        "price" => intval($_POST['price']),
        "currency" => 'EUR'
    );


    $json = json_encode($data);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    $output = curl_exec($ch);
    $info = curl_getinfo($ch);
    $result = json_decode($output);
    $_SESSION['server_msg'] = $result->data->msg;

    header('Location: ../../perfil_usuario.php');
} else {
    $res = file_get_contents("https://blablacariw.herokuapp.com/travels/" . $_GET['id']);
    $data = json_decode($res);
    $resUsers = file_get_contents("https://blablacariw.herokuapp.com/users");
    $dataUsers = json_decode($resUsers);
    include "../../includes/header.php";
}
?>

<form action="edit_viaje.php" method="POST">
    <input value="<?php echo $data->data->viaje[0]->_id ?>" name="id" type="hidden">
    <?php
    if (!empty($data->data->viaje[0]->id_pasajeros)) {
        echo "Lista de pasajeros:";
        echo "<br>";
        foreach ($data->data->viaje[0]->id_pasajeros as $pasajero) {
            if (!empty($pasajero)) {
                $resAux = file_get_contents("https://blablacariw.herokuapp.com/users/" . $pasajero);
                $dataAux = json_decode($resAux);
    ?>
                <p> - <?php echo $dataAux->data->usuario[0]->nombre . " " . $dataAux->data->usuario[0]->apellido; ?></p> <br>
    <?php }
        }
    } else {
        echo "No hay pasajeros";
        echo "<br>";
    }
    ?>

    <input type="hidden" value="<?php echo $_SESSION['usuario']->_id ?>" name="id_conductor">
    <input type="text" value="<?php echo $data->data->viaje[0]->lugar_salida ?>" required name="lugar_salida">
    <input type="text" value="<?php echo $data->data->viaje[0]->lugar_llegada ?>" required name="lugar_llegada">
    <input type="date" value="<?php echo gmdate("Y-m-d", $data->data->viaje[0]->fecha_salida) ?>" min="<?php echo date("Y-m-d"); ?>" required name="fecha_salida">
    <input type="time" value="<?php echo gmdate("H:i", intval($data->data->viaje[0]->hora_salida)) ?>" required name="hora_salida">
    <input value="<?php echo $data->data->viaje[0]->price ?>" min="1" step="1" required name="price">
    <input type="submit" value="Editar">
</form>

<?php include '../../includes/footer.php' ?>