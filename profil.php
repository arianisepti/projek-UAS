<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
    <script>
</script>
</head>
<body>

    <!----------------------- Main Container -------------------------->

     <div class="container d-flex justify-content-center align-items-center min-vh-100">

    <!----------------------- Login Container -------------------------->

       <div class="row border rounded-5 p-3 shadow box-area" style="background-color : white">

    <!--------------------------- Left Box ----------------------------->

       <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background-image: url('img2/Photo/Interior-Material-Combinations.jpg'); background-size: cover; height: 100vh;">
       </div> 

    <!-------------------- ------ Right Box ---------------------------->
        
       
    <form class="col-md-6 right-box" id="form">
          <div class="row align-items-center">
                <div class="header-text mb-4">
                     <p>My Profile</p>
                </div>
                <div class="input-group mb-3">
                <input type="file" id="profilePicture" name="profilePicture" accept="image/*" placeholder="Photo">
                </div>
                <div class="input-group mb-3">
                    <input type="text" class="form-control form-control-lg bg-light fs-6" id="nama" name="nama" placeholder="Nama Lengkap">
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control form-control-lg bg-light fs-6" id="tempatlahir" name="tempatlahir" placeholder="Tempat kelahiran">
                </div>
                <div class="input-group mb-3">
                    <input type="date" class="form-control form-control-lg bg-light fs-6" id="tanggallahir" name="tanggallahir" placeholder="Tanggal kelahiran">
                </div>
                <div class="input-group mb-3">
                    <input type="text" class="form-control form-control-lg bg-light fs-6" id="nik" name="nik" placeholder="NIK">
                </div>
                <div class="input-group mb-3">
                    <input type="text" class="form-control form-control-lg bg-light fs-6" id="alamat" name="alamat" placeholder="Alamat Lengkap">
                </div>
                <div class="input-group mb-3">
                    <input type="email" class="form-control form-control-lg bg-light fs-6" id="email" name="email" placeholder="Email">
                </div>
                <div>
                    <select class="form-control form-control-lg bg-light fs-6" id="jk" name="jk" >
                        <option value="Perempuan">Perempuan</option>
                        <option value="Laki-laki">Laki-laki</option>
                    </select>
                </div>
                <div class="input-group mb-3">
                    <button class="btn btn-lg btn-primary w-100 fs-6" type="submit" name="submit">Submit</button>
                </div>
                <div class="input-group mb-3">
                    <button class="btn btn-lg btn-primary w-100 fs-6" type="reset" name="reset">Reset</button>
                </div>
          </div>
       </form> 

       <!-- Output yang dihasilkan -->
       <div class="col-md-6 right-box" id="output">
        <div class="row align-items-center">
        <div class="header-text mb-4">
                     <p>My Profile</p>
        </div>
        <div>
        <img id="outputProfilePicture" style="width:100px; position:absolute; top:0; left:0;" alt="Profil">
        </div>
        <p class="input-group mb-3" id="outNama"></p>
        <p class="input-group mb-3" id="outTL"></p>
        <p class="input-group mb-3" id="outTgl"></p>
        <p class="input-group mb-3" id="outJK"></p>
    </div>
    </div>

      </div>
    </div>

    <script>
        document.getElementById("form").addEventListener("submit", function(event) {
            event.preventDefault();
            // Mengambil nilai dari form
            var profilePicture = document.getElementById("profilePicture").files[0];
            var nama = document.getElementById("nama").value;
            var tempatlahir = document.getElementById("tempatlahir").value;
            var tanggallahir = document.getElementById("tanggallahir").value;
            var jeniskelamin = document.getElementById("jk").value;

             // Menampilkan foto profil (jika ada)
             if (profilePicture) {
                var outputProfilePicture = document.getElementById("outputProfilePicture");
                outputProfilePicture.src = URL.createObjectURL(profilePicture);
                outputProfilePicture.style.display = "block";
            } else {
                document.getElementById("outputProfilePicture").style.display = "none";
            }


            // Menampilkan output
            document.getElementById("outNama").innerText = "Nama: " + nama;
            document.getElementById("outTL").innerText = "Tempat Lahir: " + tempatlahir;
            document.getElementById("outTgl").innerText = "Tanggal Lahir: " + tanggallahir;
            document.getElementById("outJK").innerText = "Jenis Kelamin: " + jeniskelamin;

            // Menampilkan output div dan menyembunyikan form div
            document.getElementById("form").style.display = "none";
            document.getElementById("output").style.display = "block";

        });
        
    </script>

</body>