<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  
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
      $sql = "SELECT users.name,users.nik,qr.qr,signature.signature  FROM users INNER JOIN qr ON qr.id_users = users.id INNER JOIN signature ON signature.id_users = users.id WHERE uuid='$uuid'";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        }else{
        header('Location: /index.php');
    }
    }else{
        header('Location: /index.php');

    }
  ?>
  

<table class="table">
  <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Nik</th>
      <th scope="col">Signature</th>
    </tr>
  </thead>
  <tbody>
 
    <tr>
      <td><?php echo $row["name"]?></td>
      <td><?php echo $row["nik"]?></td>
      <td><img src="<?php echo $row["signature"]?>"></td>
    </tr>

  </tbody>
</table>

</body>
</html>