<?php

$host = "localhost";
$username = "root"; 
$password = ""; 
$database = "uas";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Simpan data pemesanan ke session
    $reservation = array(
        'nama' => $_POST['nama'],
        'tanggal_checkin' => $_POST['tanggal_checkin'],
        'tanggal_checkout' => $_POST['tanggal_checkout'],
        'status' => 'PENDING' // Status awal
    );

    // Simpan data pemesanan ke histori
    if (!isset($_SESSION['history'])) {
        $_SESSION['history'] = array();
    }
    $_SESSION['history'][] = $reservation;

    $message = "Pemesanan berhasil!";
}

if (isset($_GET['cancel'])) {
    $index = $_GET['cancel'];

    if (isset($_SESSION['history'][$index])) {
        // Ubah status pemesanan menjadi "DIBATALKAN"
        $_SESSION['history'][$index]['status'] = 'DIBATALKAN';
        $message = "Pemesanan berhasil dibatalkan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Reservation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h2 {
            color: #333;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h2>Form Pemesanan Hotel</h2>
    <?php if (isset($message)) : ?>
        <p class="success"><?php echo $message; ?></p>
    <?php endif; ?>
    <form action="index.php" method="post">
        <label for="nama">Nama:</label>
        <input type="text" name="nama" required><br>

        <label for="tanggal_checkin">Tanggal Check-in:</label>
        <input type="date" name="tanggal_checkin" required><br>

        <label for="tanggal_checkout">Tanggal Check-out:</label>
        <input type="date" name="tanggal_checkout" required><br>

        <input type="submit" value="Pesan">
    </form>

    <?php if (isset($_SESSION['history'])) : ?>
        <h2>Histori Pemesanan</h2>
        <table>
            <tr>
                <th>Nama</th>
                <th>Tanggal Check-in</th>
                <th>Tanggal Check-out</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($_SESSION['history'] as $index => $reservation) : ?>
                <tr>
                    <td><?php echo $reservation['nama']; ?></td>
                    <td><?php echo $reservation['tanggal_checkin']; ?></td>
                    <td><?php echo $reservation['tanggal_checkout']; ?></td>
                    <td><?php echo $reservation['status']; ?></td>
                    <td>
                        <?php if ($reservation['status'] == 'PENDING') : ?>
                            <a href="index.php?cancel=<?php echo $index; ?>">Batal</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p>Belum ada histori pemesanan.</p>
    <?php endif; ?>
</body>
</html>