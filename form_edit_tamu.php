<?php
  // periksa apakah user sudah login, cek kehadiran session name
  // jika tidak ada, redirect ke login.php
  session_start();
  if (!isset($_SESSION["nama"])) {
     header("Location: login.php");
  }

  // buka koneksi dengan MySQL
  include("connection.php");

  // cek apakah form telah di submit
  if (isset($_POST["submit"])) {
    // form telah disubmit, cek apakah berasal dari edit_tamu.php
    // atau update data dari form_edit_tamu.php

    if ($_POST["submit"]=="Edit") {
      //nilai form berasal dari halaman edit_tamu.php

      // ambil nilai no ktp
      $no_ktp = htmlentities(strip_tags(trim($_POST["no_ktp"])));
      // filter data
      $no_ktp = mysqli_real_escape_string($koneksi,$no_ktp);

      // ambil semua data dari database untuk menjadi nilai awal form
      $query = "SELECT * FROM tamu WHERE no_ktp='$no_ktp'";
      $result = mysqli_query($koneksi, $query);

      if(!$result){
        die ("Query Error: ".mysqli_errno($koneksi).
             " - ".mysqli_error($koneksi));
      }

      // tidak perlu pakai perulangan while, karena hanya ada 1 record
      $data = mysqli_fetch_assoc($result);

      $nama             = $data["nama"];
      $email            = $data["email"];
      $umur             = $data["umur"];
      $jenis_kelamin    = $data["jenis_kelamin"];
      $alamat           = $data["alamat"];
      $tujuan_kunjungan = $data["tujuan_kunjungan"];

    // bebaskan memory
    mysqli_free_result($result);
    }

    else if ($_POST["submit"]=="Update Data") {
      // nilai form berasal dari halaman form_edit_tamu.php
      // ambil semua nilai form
      $nama             = htmlentities(strip_tags(trim($_POST["nama"])));
      $no_ktp           = htmlentities(strip_tags(trim($_POST["no_ktp"])));
      $email            = htmlentities(strip_tags(trim($_POST["email"])));
      $umur             = htmlentities(strip_tags(trim($_POST["umur"])));
      $jenis_kelamin    = htmlentities(strip_tags(trim($_POST["jenis_kelamin"])));
      $alamat           = htmlentities(strip_tags(trim($_POST["alamat"])));
      $tujuan_kunjungan = htmlentities(strip_tags(trim($_POST["tujuan_kunjungan"])));
    }

    // proses validasi form
    // siapkan variabel untuk menampung pesan error
    $pesan_error="";

    // cek apakah "nama" sudah diisi atau tidak
    if (empty($nama)) {
        $pesan_error .= "Nama belum diisi <br>";
    }


    // cek apakah "no ktp" sudah diisi atau tidak
    if (empty($no_ktp)) {
      $pesan_error .= "NO KTP belum diisi <br>";
    }
    // no ktp harus angka dengan 16 digit
    elseif (!preg_match("/^[0-9]{16}$/",$no_ktp) ) {
      $pesan_error .= "NO KTP harus berupa 16 digit angka <br>";
    }


    // cek apakah "email" sudah diisi atau tidak
    if (empty($email)) {
      $pesan_error .= "Email belum diisi <br>";
    }

    // cek apakah "Umur" sudah diisi atau tidak
    if (empty($umur)) {
      $pesan_error .= "Umur belum diisi <br>";
    }

    // siapkan variabel untuk menggenerate pilihan jenis kelamin
    $select_pria=""; $select_wanita="";

    switch($jenis_kelamin) {
     case "Pria" : $select_pria = "selected";  break;
     case "Wanita"  : $select_wanita = "selected";  break;
    }

    // cek apakah "Alamat" sudah diisi atau tidak
    if (empty($alamat)) {
        $pesan_error .= "Alamat belum diisi <br>";
    }

    // cek apakah "Tujuan Kunjungan" sudah diisi atau tidak
    if (empty($tujuan_kunjungan)) {
        $pesan_error .= "Tujuan Kunjungan belum diisi <br>";
    }


    // jika tidak ada error, input ke database
    if (($pesan_error === "") AND ($_POST["submit"]=="Update Data")) {

      // buka koneksi dengan MySQL
      include("connection.php");

      // filter semua data
      $nama             = mysqli_real_escape_string($koneksi,$nama);
      $no_ktp           = mysqli_real_escape_string($koneksi,$no_ktp );
      $email            = mysqli_real_escape_string($koneksi,$email);
      $umur             = mysqli_real_escape_string($koneksi,$umur);
      $jenis_kelamin    = mysqli_real_escape_string($koneksi,$jenis_kelamin);
      $alamat           = mysqli_real_escape_string($koneksi,$alamat);
      $tujuan_kunjungan = mysqli_real_escape_string($koneksi,$tujuan_kunjungan);


      //buat dan jalankan query UPDATE
      $query  = "UPDATE tamu SET ";
      $query .= "nama = '$nama', email = '$email', ";
      $query .= "umur = '$umur', jenis_kelamin='$jenis_kelamin', ";
      $query .= "alamat = '$alamat', tujuan_kunjungan='$tujuan_kunjungan' ";
      $query .= "WHERE no_ktp = '$no_ktp'";

      $result = mysqli_query($koneksi, $query);

      //periksa hasil query
      if($result) {
      // INSERT berhasil, redirect ke tampil_tamu.php + pesan
        $pesan = "Tamu dengan nama = \"<b>$nama</b>\" sudah berhasil di update";
        $pesan = urlencode($pesan);
        header("Location: tampil_tamu.php?pesan={$pesan}");
      }
      else {
      die ("Query gagal dijalankan: ".mysqli_errno($koneksi).
           " - ".mysqli_error($koneksi));
      }
    }
  }
  else {
    // form diakses secara langsung!
    // redirect ke edit_tamu.php
    header("Location: edit_tamu.php");
  }

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Aplikasi Buku Tamu</title>
  <link href="style.css" rel="stylesheet" >
  <link rel="icon" href="icon.png" type="image/png" >
</head>
<body>
<div class="container">
<div id="header">
  <h1 id="logo">Aplikasi <span>Buku Tamu</span></h1>
  <p id="tanggal"><?php echo date("d M Y"); ?></p>
</div>
<hr>
  <nav>
  <ul>
    <li><a href="tampil_tamu.php">Tampil</a></li>
    <li><a href="tambah_tamu.php">Tambah</a>
    <li><a href="edit_tamu.php">Edit</a>
    <li><a href="hapus_tamu.php">Hapus</a></li>
    <li><a href="logout.php">Logout</a>
  </ul>
  </nav>
  <form id="search" action="tampil_tamu.php" method="get">
    <p>
      <label for="nama">Nama : </label>
      <input type="text" name="nama" id="nama" placeholder="search..." >
      <input type="submit" name="submit" value="Search">
    </p>
  </form>
<h2>Edit Data Tamu</h2>
<?php
  // tampilkan error jika ada
  if ($pesan_error !== "") {
      echo "<div class=\"error\">$pesan_error</div>";
  }
?>
<form id="form_mahasiswa" action="form_edit_tamu.php" method="post">
<fieldset>
<legend>Tamu Baru</legend>
  <p>
    <label for="nama">Nama : </label>
    <input type="text" name="nama" id="nama" value="<?php echo $nama ?>">
  </p>
  <p>
    <label for="no_ktp">No KTP : </label>
    <input type="text" name="no_ktp" id="no_ktp" value="<?php echo $no_ktp ?>" readonly>
    (tidak bisa diubah di menu edit)
  </p>
  <p>
    <label for="email">Email : </label>
    <input type="text" name="email" id="email" value="<?php echo $email ?>">
  </p>
  <p>
    <label for="umur">Umur : </label>
    <input type="text" name="umur" id="umur" value="<?php echo $umur ?>">
  </p>
  <p>
    <label for="jenis_kelamin" >Jenis Kelamin : </label>
      <select name="jenis_kelamin" id="jenis_kelamin">
        <option value="Pria" <?php echo $select_pria ?>>
        Pria</option>
        <option value="Wanita" <?php echo $select_wanita ?>>
        Wanita</option>
      </select>
  </p>
  <p>
    <label for="alamat">Alamat : </label>
    <input type="text" name="alamat" id="alamat" value="<?php echo $alamat ?>">
  </p>
  <p >
    <label for="tujuan_kunjungan">Tujuan Kunjungan : </label>
    <input type="text" name="tujuan_kunjungan" id="tujuan_kunjungan" value="<?php echo $tujuan_kunjungan ?>">
  </p>

</fieldset>
  <br>
  <p>
    <input type="submit" name="submit" value="Update Data">
  </p>
</form>

</div>

</body>
</html>
<?php
  // tutup koneksi dengan database mysql
  mysqli_close($koneksi);
?>
