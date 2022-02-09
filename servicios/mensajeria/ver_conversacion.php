<?php
    session_start();
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $url = 'https://blablacariw.herokuapp.com/messages';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);

        $aux = $_POST['from'];
        $aux2 = $_POST['to'];

        $data = array(
            "from" => $aux,
            "to" => $aux2,
            "texto" => $_POST['msgTexto'],            
            "conversacion" => $_POST['id_conversacion'],
        );

        $json = json_encode($data);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));      

        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        $result = json_decode($output);

        $_SESSION['server_msg'] = $result->data->msg;
        header('Location: ver_conversacion.php?id_ajeno='.$aux2.'&id_local='.$aux);
    } else{
        $string = "https://blablacariw.herokuapp.com/conversations/messages?id1=".$_GET['id_local']."&id2=".$_GET['id_ajeno'];
        //var_dump($string);
        $res = file_get_contents("https://blablacariw.herokuapp.com/conversation/messages?id1=".$_GET['id_local']."&id2=".$_GET['id_ajeno']);
        $data = json_decode($res);
        $resUser = file_get_contents("https://blablacariw.herokuapp.com/users/".$_GET['id_ajeno']);
        $dataUser = json_decode($resUser);
        //var_dump($dataUser);
    }
    include "../../includes/header.php";
?>


<h1 align="center">Conversación con <?php echo $dataUser->data->usuario[0]->email;?></h1>

<table style="width:60%" align="center">
    
            <?php 
                foreach ($data->data->mensajes as $mensaje){ ?>                
                    <tr>
                        <?php
                            if($mensaje->from == $_GET['id_ajeno']){
                            ?>
                            <td style="text-align: left;padding-right:10px"><?php echo $mensaje->texto ?></td>
                            <?php } else { ?>
                            <td style="text-align: right;padding-left:10px" ><?php echo $mensaje->texto ?></td>    
                            <?php } ?>
                    </tr>
                
            <?php } ?>
    </table>
    <form action="ver_conversacion.php" method="POST" align="center">
                <input type="text" id="msgTexto" name="msgTexto">
                <input type="hidden" value="<?php echo $_GET['id_local']?>" name="from">
                <input type="hidden" value="<?php echo $_GET['id_ajeno']?>" name="to">
                <input type="hidden" value="<?php echo $data->data->conversacion[0]->_id?>" name="id_conversacion">
                <th><input type="submit" value="Enviar Mensaje"></th>
    </form>