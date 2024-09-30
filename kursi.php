<?php

if (!$conn) {
  die("Koneksi database gagal: " . mysqli_connect_error());
} else {
  // echo "Koneksi Berhasil";
}

$id_pesan = $_GET['id_pesan'];
$tgl_berangkat = $_GET['tgl_berangkat'];

$query     = "SELECT * FROM pesanan WHERE id_pesan='$id_pesan'";
$cek       = mysqli_query($conn, $query) or die(mysqli_error($conn));
$data    = mysqli_fetch_array($cek);
var_dump($data);
$pesanan = $data;
$cek    = "SELECT * FROM kursi_pesanan WHERE id_pesan='$id_pesan'";
$cekkursi  = mysqli_query($conn, $cek);
$jumlah    = mysqli_num_rows($cekkursi);
$jumlah = $data['qty'] - $jumlah;
?>
<div class="row">
  <div>
    <h3>
      <center>
        Silahkan Pilih Kursi
      </center>
    </h3>
    <br><br>
  </div>
</div>
<div class="row">
  <div class="col-md-8">

    <?php
    //query get kurs
    // $tgl_berangkat = date($tgl_berangkat, 'Y-m-d');
    $tgl_berangkat = date('Y-m-d', strtotime($tgl_berangkat));
    // $query = "SELECT * FROM kursi WHERE id_bus = $data[id_bus] AND tgl_berangkat='$tgl_berangkat'";
    $query = "SELECT * FROM kursi WHERE id_bus = $data[id_bus] AND tgl_berangkat='$tgl_berangkat'";
    if (isset($data['id_bus'])) {
      $query = "SELECT * FROM kursi WHERE id_bus = {$data['id_bus']} AND tgl_berangkat='$tgl_berangkat'";
      // echo "Data bus ditemukan!";
    } else {
      echo "Data bus tidak ditemukan!";
    }
    // var_dump($query);
    $execute_q = mysqli_query($conn, $query);
    // var_dump($execute_q);

    if (mysqli_num_rows($execute_q) == 0) {
      echo "<p>Tidak ada kursi tersedia untuk bus ini pada tanggal tersebut.</p>";
    } else {
      if ($jumlah == 0) {
        echo "<p>Anda sudah memilih " . $data['qty'] . " kursi</p>";
      } else
        echo "<p>SIlahkan pilih " . $jumlah . " kursi yang tersedia</p>";
    }

    while ($data = mysqli_fetch_array($execute_q)) {
      if ($data['id_pesan'] == 0) {
        $kode_kursi = $data['kode_kursi'];
    ?>
        <input type="button" class="btn btn-success" value="<?php echo $data['kode_kursi'] ?>" onclick="pesan('<?php echo $kode_kursi ?>')">
      <?php
      } else { ?>
        <input type="button" class="btn btn-danger" value="<?php echo $data['kode_kursi'] ?>" disabled>
    <?php
      }
      if ($data['urutan'] % 2 == 0) {
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      }
      if ($data['urutan'] % 4 == 0) {
        echo "<br><br>";
      }
      if ($data['urutan'] == 40) {
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      }
      if ($data['urutan'] % 42 == 0) {
        echo "<br><br>";
      }
    }
    ?>
  </div>
  <div class="col-md-4">
    <br><br><br><br><br><br>
    Keterangan :
    <p></p>
    <br><br>
    <p><button class="btn btn-success"></button>&nbsp; : Kursi yang tersedia</p>
    <p></p>
    <p></p>
    <p><button class="btn btn-danger"></button>&nbsp;&nbsp; : Kursi yang terbooking</p>
  </div>
</div>

<div>
  KODE PEMESANAN : <?php echo $pesanan['invoice']; ?>
  <br>
  CEK PROSES PEMESANAN <a href="index.php?halaman=cek_proses&&kode=<?php echo $pesanan['invoice']; ?>">DISINI</a>
</div>

<!-- MODAL Jumlah Penumpang -->
<div class="container">
  <!-- Modal -->
  <div class="modal fade" id="jumlah" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
          <center><b>WARNING!</b></center>
        </div>
        <div class="modal-body">
          <center>
            Anda sudah memilih Jumlah kursi sesuai dengan Jumlah Penumpang yang anda pesan!
          </center>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- //MODAL Jumlah Penumpang -->

<!-- MODAL GAGAL MEMILIH KURSI -->
<div class="container">
  <!-- Modal -->
  <div class="modal fade" id="gagal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
          <center><b>WARNING!</b></center>
        </div>
        <div class="modal-body">
          <center>
            Anda gagal memilih kursi
          </center>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- MODAL GAGAL MEMILIH KURSI -->


<input type="hidden" name="id_pesan" value="<?php echo $id_pesan ?>" id="id_pesan">

<script type="text/javascript">
  var pesan = function(kode_kursi) {
    var id_pesan = document.getElementById('id_pesan').value;
    console.log('pesan => ', kode_kursi);
    console.log('id_pesan => ', id_pesan);
    var formData = {
      kode_kursi: kode_kursi,
      id_pesan: id_pesan
    };
    $.ajax({
      url: "pesan_kursi.php",
      type: "POST",
      data: formData,
      success: function(data, textStatus, jqXHR) {

        if (data == 'full') {
          console.log("PENUH");
          $("#jumlah").modal("show");
        } else if (data == 'gagal') {
          console.log("GAGAL");
          $("#gagal").modal("show");
        } else {
          console.log("BERHASIL");
          window.location.reload();
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {

      }
    });
  }
</script>