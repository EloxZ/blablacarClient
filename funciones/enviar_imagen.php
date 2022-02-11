<?php
$file = $_FILES['imagen'];
// Si es una imagen continuamos, si no, mandamos el error :3
$id = $_POST['id'];
$file_size = $file['size'];

if (($file_size > 2*1024*1024)){      
    echo '<p>File too large. File must be less than 2 MB.</p>'; 
    
} else if($file['type'] == 'image/jpg' || $file['type'] == 'image/png' || $file['type'] == 'image/jpeg'){
    $filename= $file['tmp_name'];
    $client_id = "531facc897ea14b"; // AQUI SU CLIENT ID
    $handle = fopen($filename, "r");
    $data = fread($handle, filesize($filename));
    $pvars   = array('image' => base64_encode($data));
    $timeout = 30;
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);
    $out = curl_exec($curl);
    curl_close ($curl);
    $pms = json_decode($out,true);
    $url="";
    
    if( isset( $pms['data']['link'] ) ){
        $url=$pms['data']['link'];
     }

    if($url!=""){

     echo "<h2>¿Esta es la imagen que quieres subir?</h2>";
     echo "<img src='$url'/>";
     ?>
    <form action="../servicios/usuario/edit.php" method="POST">
        <input value="<?php echo $id?>" name="id" type="hidden">
        <input value="<?php echo $url?>" name="foto" type="hidden">
        <input type="hidden" name="modo" value="2">
        <input type="submit" value="Confirmar">
    </form>
    <form action="../servicios/usuario/edit.php" method="GET">
        <input type="hidden" value="<?php echo $id?>" name="id">
        <input type="submit" value="Cancelar">
    </form>
        <?php
    }else{
     echo $pms;
     echo "<h2>Ocurrió un problema :(</h2>";
     echo $pms['data']['error'];  
    } 
} else {
    header ("Location: ../");
}

?>
