<?php
include('compare.php');

$uuid = $_GET['uuid'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "igoy";
$conn = new mysqli($servername, $username, $password, $dbname);
$sql = "SELECT users.uuid,signature.signature  FROM users INNER JOIN qr ON qr.id_users = users.id INNER JOIN signature ON signature.id_users = users.id WHERE uuid='$uuid'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$image1 = $row['signature'];
$image2 = signature($_POST['match']);

$compareMachine = new compareImages($image1);
$image1Hash = $compareMachine->getHasString(); 
echo "Image 1: <img src='$image1'/><br/>";
echo 'Image 1 Hash :'.$image1Hash.'<br/>';
//$diff = $compareMachine->compareWith($image2); //easy
$image2Hash = $compareMachine->hasStringImage($image2); 
$diff = $compareMachine->compareHash($image2Hash); 

if ($diff < 1) {
    header('Location: /table.php?uuid='.$uuid);
}else{
    echo "<h1>No Match </h1><br/>";

    echo "Image 2: <img src='$image2'/><br/>";
    echo 'Image 2 Hash :'.$image2Hash.'<br/>';
    echo 'Different rates (image1 Vs image2): '.$diff;
}
function signature($signature){
    $folderPath = "match/";
  
    $image_parts = explode(";base64,", $signature);
        
    $image_type_aux = explode("image/", $image_parts[0]);
      
    $image_type = $image_type_aux[1];
      
    $image_base64 = base64_decode($image_parts[1]);
    $file = $folderPath . uniqid() . '.'.$image_type;
    file_put_contents($file, $image_base64);
    return $file;
}