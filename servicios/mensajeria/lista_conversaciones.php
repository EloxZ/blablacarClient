<?php
    session_start();
    $res = file_get_contents("https://blablacariw.herokuapp.com/conversations/".$_GET['id']);
    $data = json_decode($res);
    
?>

<head><link rel="stylesheet" href="../css/styles.css"></head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<h1>Conversaciones</h1>

<table>
        <tr>
            <th>Usuario</th>
            <th></th>
        </tr>
            <?php 
                foreach ($data->data->usuarios as $usuario){ ?>                
                    <tr>
                        <td><?php echo $usuario->email; ?></td>
                        <form action="ver_conversacion.php" method="GET">
                            <input type="hidden" value="<?php echo $usuario->_id?>" name="id_ajeno">
                            <input type="hidden" value="<?php echo $_GET['id']?>" name="id_local">
                            <th><input type="submit" value="Ver conversación"></th>
                        </form>
                    </tr>
                
            <?php } ?>
    </table>
    
    <form action="crear_conversacion.php" method="POST">
        <select id="select" name="select">
                <?php foreach ($data->data->notusuarios as $notusuario){ ?>
                    <option value="<?php echo $notusuario->_id?>"><?php echo $notusuario->email ?></option>
                <?php } ?>
        </select>
        
        
        <input type="hidden" value="<?php echo $_GET['id']?>" name="id_local">
        <input type="submit" value="Nueva Conversacion">
    </form>
