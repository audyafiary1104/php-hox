<?php
include "phpqrcode/qrlib.php"; 

function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}
function qr(){
    $tempdir = "image/";
    $codeContents = links().'/match.php?uuid='.gen_uuid();
    $namaFile= gen_uuid().".png";
    $level=QR_ECLEVEL_H;
    $UkuranPixel=10;
    
    $UkuranFrame=4;
    QRcode::png($codeContents, $tempdir.$namaFile, $level, $UkuranPixel, $UkuranFrame); 
    return $namaFile;
}
function signature($signature){
    $folderPath = "signature/";
  
    $image_parts = explode(";base64,", $signature);
        
    $image_type_aux = explode("image/", $image_parts[0]);
      
    $image_type = $image_type_aux[1];
      
    $image_base64 = base64_decode($image_parts[1]);
    $file = $folderPath . uniqid() . '.'.$image_type;
    file_put_contents($file, $image_base64);
    return $file;
}
function links(){
    if(isset($_SERVER['HTTPS']) &&  
            $_SERVER['HTTPS'] === 'on') 
    $link = "https"; 
    else
        $link = "http"; 

$link .= "://"; 
$link .= $_SERVER['HTTP_HOST']; 
return $link;
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "igoy";

if (isset($_POST['signature']) && isset($_POST['name']) && isset($_POST['nik'])) {
    $qr = qr();
    $uuid = gen_uuid();
    $name = $_POST['name'];
    $nik = $_POST['nik'];
    $public_key = gen_uuid();
    $private_key =gen_uuid();
    $signature = signature($_POST['signature']);

    $link = links().'/qr/'.gen_uuid();
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $users = "INSERT INTO users (name, nik,uuid,public_key,private_key)
    VALUES ('$name', '$nik', '$uuid','$public_key','$private_key')";
    $conn->query($users);
    $last_id = $conn->insert_id;
    $signature = "INSERT INTO signature (signature, id_users)
    VALUES ('$signature', '$last_id')";
    $qr = "INSERT INTO qr (qr, id_users)
    VALUES ('$qr', '$last_id')";
    $conn->query($qr);
    $conn->query($signature);
    header('Location: /private.php?uuid='.$uuid);
    exit;
}else{
    header('Location: /index.php?message=please fill all form');
    exit;
}