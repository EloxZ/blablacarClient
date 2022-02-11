<?php
if (!isset($_SESSION['token'])) {
    header('Location: /login.php');
} else if (!isset($_SESSION['admin'])) {
    header('Location: /index.php');
}

$dataUsers = file_get_contents("https://blablacariw.herokuapp.com/users");
$users = json_decode($dataUsers)->data->usuarios;

include './includes/header.php';

?>

<section class="container">
    <h1>Usuarios</h1>

    <table>
        <tr>
            <th></th>
            <th>Nombre</th>
            <th>Apellido</th>
        </tr>
        <?php
        foreach ($users as $usuario) { ?>

            <tr>
                <td><?php if (!isset($usuario->foto))
                        echo "<img src='https://acortar.link/mZkcJS' style='width:30px;height:30px;'?></td>";
                    else
                        echo "<img src='" . $usuario->foto . "' style='width:30px;height:30px;'?></td>"; ?>
                <td><?php echo $usuario->nombre; ?></td>
                <td><?php echo $usuario->apellido; ?></td>
                <form action="../servicios/usuario/delete.php" method="POST">
                    <input type="hidden" value="<?php echo $usuario->_id ?>" name="id">
                    <td><input type="submit" value="Eliminar"></td>
                </form>
                <form action="../servicios/usuario/edit.php" method="GET">
                    <input type="hidden" value="<?php echo $usuario->_id ?>" name="id">
                    <td><input type="submit" value="Editar"></td>
                </form>
            </tr>

        <?php } ?>
    </table>
</section>