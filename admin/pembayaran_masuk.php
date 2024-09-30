<?php
// Mulai sesi
// session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['loggedin'])) {
    // header('Location: login.php');
    // exit;
}

// Koneksi ke database
// $host = 'localhost';
// $db = 'nama_database';
// $user = 'nama_pengguna';
// $pass = 'kata_sandi';

// $conn = new mysqli($host, $user, $pass, $db);

include '../config/koneksi.php';
// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['id_pesan'])) {
    $id_pesan = $_GET['id_pesan'];
    $sql = "UPDATE pesanan SET pembayaran = '1' WHERE id_pesan = '$id_pesan'";
    $result = $conn->query($sql);
    if ($result) {
        echo "<script>alert('Pembayaran berhasil dikonfirmasi')</script>";
        // redirect = "pembayaran_masuk.php";
        header('Location: ' . 'admin.php?halaman=bukti_bayar');
    } else {
        echo "<script>alert('Pembayaran gagal dikonfirmasi')</script>";
    }
}

// Ambil data pembayaran masuk
$sql = "SELECT * FROM pesanan WHERE konfirm = '1'";
$result = $conn->query($sql);
// var_dump($result);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Masuk</title>
    <link rel="stylesheet" href="path/to/your/css/style.css">
</head>

<body>
    <h1>Daftar Pembayaran Masuk</h1>
    <table border="1">
        <tr>
            <th>ID Pembayaran</th>
            <th>Nama Pemesan</th>
            <th>Jumlah</th>
            <th>Tanggal Pembayaran</th>
            <th>Bukti bayar</th>
            <th></th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['invoice'] . "</td>";
                echo "<td>" . $row['nama'] . "</td>";
                echo "<td>" . $row['qty'] . "</td>";
                echo "<td>" . $row['tgl_pembayaran'] . "</td>";
                echo "<td><a href='/" . $row['respons'] . "'>Lihat</a></td>";
                if ($row['pembayaran'] == 1) {
                    echo "<td>Sudah dikonfirmasi</td>";
                } else {
                    // echo "<td>Belum dikonfirmasi</td>";
                    echo "<td><a href='pembayaran_masuk.php?id_pesan=" . $row['id_pesan'] . "'>Konfirmasi</a></td>";
                }
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Tidak ada data pembayaran masuk</td></tr>";
        }
        ?>
    </table>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
</body>

</html>
<?php
$conn->close();
?>