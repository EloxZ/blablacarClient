<?php
if (!isset($_SESSION['usuario'])) {
    header('Location: /login.php');
} else if (!isset($_SESSION['admin'])) {
    header('Location: /index.php');
}
$dataUsers = file_get_contents("https://blablacariw.herokuapp.com/users");
$users = json_decode($dataUsers)->data->usuarios;

$dataViajes = file_get_contents("https://blablacariw.herokuapp.com/travels");
$viajes = json_decode($dataViajes)->data->viajes;

include './includes/header.php';
?>

<section>
    <div class="container">
        <div class="admin__info-box">
            <div class="admin__info-card" onclick="window.location.href='./users.php'">
                <span>Total de usuarios</span>
                <p><?php echo count($users) ?></p>
            </div>
            <div class="admin__info-card" onclick="window.location.href='./viajes.php'">
                <span>Total de viajes</span>
                <p><?php echo count($viajes) ?></p>
            </div>
        </div>
    </div>
</section>
</body>

</html>