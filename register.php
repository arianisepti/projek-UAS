<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uas";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Proses registrasi
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    // Query untuk menambahkan pengguna baru ke dalam tabel
    $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
    
    if ($conn->query($query) === TRUE) {
        echo  '<script> alert ("Registrasi berhasil"); document.location="login.php"; </script>';
    } else {
        echo "Error: " . $query . "<br>" . $conn->error;
    }
}


$conn->close();

 ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
    <a href=""></a>
    <style>

    </style>
</head>
<body>

    <!----------------------- Main Container -------------------------->

     <div class="container d-flex justify-content-center align-items-center min-vh-100">

    <!----------------------- Login Container -------------------------->

       <div class="row border rounded-5 p-3 shadow box-area" style="background-color : white">

    <!--------------------------- Left Box ----------------------------->

       <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background-image: url('img2/Photo/wp1846068.jpg'); background-size: cover; height: 100vh;">
           <img src="img2/Font Dan Symbol/p12.png" alt="deskripsi" style="max-width:70%; max-height:60vh; display:block; margin:0 auto;">
           <img src="img2/Font Dan Symbol/p1.png" alt="deskripsi" style="max-width:70%; max-height:60vh; display:block; margin:0 auto;">
       </div> 

    <!-------------------- ------ Right Box ---------------------------->
        
       <form class="col-md-6 right-box" action="register.php" method="post">
          <div class="row align-items-center">
                <div class="header-text mb-4">
                     <p>Enjoy All Our Facility</p>
                </div>
                <div class="input-group mb-3">
                    <input type="text" class="form-control form-control-lg bg-light fs-6" id="firstname" name="firstname" placeholder="Firstname">
                </div>
                <div class="input-group mb-3">
                    <input type="text" class="form-control form-control-lg bg-light fs-6" id="lastname" name="lastname" placeholder="Lastname">
                </div>
                <div class="input-group mb-3">
                    <input type="text" class="form-control form-control-lg bg-light fs-6" id="username" name="username" placeholder="Email">
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control form-control-lg bg-light fs-6" id="password" name="password" placeholder="Password">
                </div>
                <label class="mb-1">Position:
                <div class="input-group mb-3">
                    <select class="form-control form-control-lg bg-light fs-6" id="role" name="role" >
                        <option value="client">Client</option>
                        <option value="company">Company</option>
                    </select>
                </div>
                </label>
                <div class="input-group mb-3">
                    <button class="btn btn-lg btn-primary w-100 fs-6"type="submit" name="register">Register</button>
                </div>
                <div class="input-group mb-3">
                    <button class="btn btn-lg btn-light w-100 fs-6"><img src="img2/Logo/google.png" style="width:20px" class="me-2"><small>Sign In with Google</small></button>
                </div>
                
          </div>
       </form> 

      </div>
    </div>

</body>
