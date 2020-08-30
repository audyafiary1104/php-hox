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

        $sql = "SELECT id, uuid,private_key,public_key FROM users WHERE uuid='$uuid'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
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
        <table class="table">
  <thead>
        <tr>
        <th scope="col">public key</th>
        <th scope="col">private key</th>
        </tr>
    </thead>
    <tbody>
    
        <tr>
        <td><?php echo $row["public_key"]?></td>
        <td><?php echo $row["private_key"]?></td>
        </tr>

    </tbody>
    </table>
        
    </div>
</body>
</html>