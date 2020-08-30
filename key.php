<?php
if(isset($_POST['key'])){
    $key = $_POST['key'];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "igoy";
    $conn = new mysqli($servername, $username, $password, $dbname);
    $private_key = "SELECT id, uuid,private_key,public_key FROM users WHERE private_key='$key'";
    $public_key = "SELECT id, uuid,private_key,public_key FROM users WHERE public_key='$key'";
    $result = $conn->query($private_key);
    $results = $conn->query($public_key);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $uuid = $row["uuid"];
        header('Location: /table.php?uuid='.$uuid);
    }else if($results->num_rows > 0){
        $row = $results->fetch_assoc();
        $uuid = $row["uuid"];
        header('Location: /qr.php?uuid='.$uuid);
    }else{
        header('Location: /login.php');
    }
}else{
    header('Location: /login.php');

}
