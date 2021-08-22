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
    // form telah disubmit, proses data

    // ambil semua nilai form
    $nama               = htmlentities(strip_tags(trim($_POST["nama"])));
    $no_ktp             = htmlentities(strip_tags(trim($_POST["no_ktp"])));
    $email              = htmlentities(strip_tags(trim($_POST["email"])));
    $umur               = htmlentities(strip_tags(trim($_POST["umur"])));
    $jenis_kelamin      = htmlentities(strip_tags(trim($_POST["jenis_kelamin"])));
    $alamat             = htmlentities(strip_tags(trim($_POST["alamat"])));
    $tujuan_kunjungan   = htmlentities(strip_tags(trim($_POST["tujuan_kunjungan"])));


    // siapkan variabel untuk menampung pesan error
    $pesan_error="";

    // cek apakah "nama" sudah diisi atau tidak
    if (empty($nama)) {
        $pesan_error .= "Nama belum diisi <br>";
    }

    // cek apakah "no ktp" sudah diisi atau tidak
    if (empty($no_ktp)) {
      $pesan_error .= "No KTP belum diisi <br>";
    }
    // No ktp harus angka dengan 16 digit
    elseif (!preg_match("/^[0-9]{16}$/",$no_ktp) ) {
      $pesan_error .= "NO KTP harus berupa 16 digit angka <br>";
    }

    // cek ke database, apakah sudah ada nomor No KTP yang sama
    // filter data $no_ktp
    $no_ktp = mysqli_real_escape_string($koneksi,$no_ktp);
    $query = "SELECT * FROM tamu WHERE no_ktp='$no_ktp'";
    $hasil_query = mysqli_query($koneksi, $query);

    // cek jumlah record (baris), jika ada, $no_ktp tidak bisa diproses
    $jumlah_data = mysqli_num_rows($hasil_query);
     if ($jumlah_data >= 1 ) {
       $pesan_error .= "No KTP yang sama sudah digunakan <br>";
    }

    // cek apakah "email" sudah diisi atau tidak
    if (empty($email)) {
      $pesan_error .= "email belum diisi <br>";
    }

    // cek apakah "umur" sudah diisi atau tidak
    if (empty($umur)) {
      $pesan_error .= "Umur belum diisi <br>";
    }
    // Umur harus angka dengan maksimal 2 digit
    elseif (!preg_match("/^[0-9]{2}$/",$umur) ) {
        $pesan_error .= "Umur harus berupa 2 digit angka <br>";
      }

    // siapkan variabel untuk menggenerate pilihan jenis kelamin
    $select_pria=""; $select_wanita=""; 

    switch($jenis_kelamin) {
     case "Pria" : $select_pria = "selected";  break;
     case "Wanita" : $select_wanita = "selected";  break;
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
    if ($pesan_error === "") {

      // filter semua data
      $nama             = mysqli_real_escape_string($koneksi,$nama);
      $no_ktp           = mysqli_real_escape_string($koneksi,$no_ktp);
      $email            = mysqli_real_escape_string($koneksi,$email);
      $umur             = mysqli_real_escape_string($koneksi,$umur);
      $jenis_kelamin    = mysqli_real_escape_string($koneksi,$jenis_kelamin);
      $alamat           = mysqli_real_escape_string($koneksi,$alamat);
      $tujuan_kunjungan = mysqli_real_escape_string($koneksi,$tujuan_kunjungan);


      //buat dan jalankan query INSERT
      $query = "INSERT INTO tamu VALUES ";
      $query .= "('$nama', '$no_ktp', '$email', ";
      $query .= "'$umur','$jenis_kelamin','$alamat','$tujuan_kunjungan')";

      $result = mysqli_query($koneksi, $query);

      //periksa hasil query
      if($result) {
      // INSERT berhasil, redirect ke tampil_tamu.php + pesan
        $pesan = "Tamu dengan nama = \"<b>$nama</b>\" sudah berhasil di tambah";
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
    // form belum disubmit atau halaman ini tampil untuk pertama kali
    // berikan nilai awal untuk semua isian form
    $pesan_error      = "";
    $nama             = "";
    $no_ktp           = "";
    $email            = "";
    $umur             = "";
    $select_pria      = "selected";
    $select_wanita    = ""; 
    $alamat           = "";
    $tujuan_kunjungan = "";
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
<h2>Tambah Data Tamu</h2>
<?php
  // tampilkan error jika ada
  if ($pesan_error !== "") {
      echo "<div class=\"error\">$pesan_error</div>";
  }
?>
<form id="form_tamu" action="tambah_tamu.php" method="post">
<fieldset>
<legend>Tamu Baru</legend>
  <p>
    <label for="no_ktp">No KTP : </label>
    <input type="text" name="no_ktp" id="no_ktp" value="<?php echo $no_ktp ?>"
    placeholder="Contoh: 1234567891234567">
    (16 digit angka)
  </p>
  <p>
    <label for="nama">Nama : </label>
    <input type="text" name="nama" id="nama" value="<?php echo $nama ?>">
  </p>
  <p>
    <label for="email">Email : </label>
    <input type="text" name="email" id="email"
    value="<?php echo $email ?>">
  </p>
  <p>
    <label for="umur">Umur : </label>
    <input type="text" name="umur" id="umur" value="<?php echo $umur ?>"
    placeholder="Contoh : 23">
    (2 digit angka)
  </p>
  <p>
    <label for="jenis_kelamin" >Jenis Kelamin : </label>
      <select name="jenis_kelamin" id="jenis_kelamin">
        <option value="Pria" <?php echo $select_pria ?>>
        Pria </option>
        <option value="Wanita" <?php echo $select_wanita ?>>
        Wanita</option>
      </select>
  </p>
  <p>
    <label for="alamat">Alamat : </label>
    <input type="text" name="alamat" id="alamat" value="<?php echo $alamat ?>">
  </p>
  <p>
    <label for="tujuan_kunjungan">Tujuan Kunjungan : </label>
    <input type="text" name="tujuan_kunjungan" id="tujuan_kunjungan" value="<?php echo $tujuan_kunjungan ?>">
  </p>

</fieldset>
  <br>
  <p>
    <input type="submit" name="submit" value="Tambah Data">
  </p>
</form>

</div>

</body>
</html>
<?php
  // tutup koneksi dengan database mysql
  mysqli_close($koneksi);
?>
