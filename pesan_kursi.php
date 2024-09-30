<?php

include 'config/koneksi.php';

$id_pesan  	= $_POST['id_pesan'];
$kode_kursi = $_POST['kode_kursi'];


$cek		= "SELECT * FROM kursi_pesanan WHERE id_pesan='$id_pesan'";
$cekkursi	= mysqli_query($conn, $cek);
$jumlah		= mysqli_num_rows($cekkursi);

$lihat		= "SELECT * FROM pesanan WHERE id_pesan='$id_pesan'";
$cekqty		= mysqli_query($conn, $lihat);
$qty		= mysqli_fetch_array($cekqty);


if ($jumlah < $qty['qty']) {
	// $id_kursi	 = mt_rand(1, 1000);
	$insert		 = "INSERT INTO kursi_pesanan (id_kursi, id_pesan, kode_kursi) VALUES ('0','$id_pesan','$kode_kursi')";
	// var_dump($insert);
	$simpan		 = mysqli_query($conn, $insert) or die(mysqli_error($conn));

	$updatekursi = "UPDATE kursi SET id_pesan='$id_pesan' WHERE kode_kursi='$kode_kursi'";
	$update		 = mysqli_query($conn, $updatekursi) or die(mysqli_error($conn));

	if ($update) {
		echo "sukses";
	} else {
		echo "gagal";
	}

	// if ($qty['qty'] - $jumlah != 0) {
	// 	echo $qty['qty'] - $jumlah;
	// }

	$update 	 = "UPDATE pesanan SET fixed='1' WHERE id_pesan='$id_pesan'";
	$updatepesan = mysqli_query($conn, $update) or die(mysqli_error($co));
} else {
	echo "full";
}
