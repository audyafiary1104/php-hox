<!DOCTYPE html>
<html>
<head>
</head>

<body>
<?php
    if (isset($_GET['uuid'])) {
        $uuid = $_GET['uuid'];
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "igoy";
        $conn = new mysqli($servername, $username, $password, $dbname);

        $sql = "SELECT users.id, uuid,qr.qr FROM users INNER JOIN qr ON qr.id_users = users.id WHERE uuid='$uuid'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
          $qr = $row["qr"];
        }else{
        header('Location: /index.php');
        }
    }else{
        header('Location: /index.php');
        exit;
    }
?>
    <div align="center">
        <h2>Scan Your QR</h2>
       
        <?php
            $tempdir = 'image/';
            echo '<img src="'.$tempdir.$qr.'" />';  
            ?>
        <br>
    </div>
</body>
</html>