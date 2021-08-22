<?php
  // periksa apakah user sudah login, cek kehadiran session name
  // jika tidak ada, redirect ke login.php
  session_start();
  if (!isset($_SESSION["nama"])) {
     header("Location: login.php");
  }

  // buka koneksi dengan MySQL
  include("connection.php");
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
  // tampilkan pesan jika ada
  if ((isset($_GET["pesan"]))) {
      echo "<div class=\"pesan\">{$_GET["pesan"]}</div>";
  }
?>
 <table border="1">
  <tr>
  <th>Nama</th>
  <th>No KTP</th>
  <th>Email</th>
  <th>Umur</th>
  <th>Jenis Kelamin</th>
  <th>Alamat</th>
  <th>Tujuan Kunjungan</th>
  <th></th>
  </tr>
  <?php
  // buat query untuk menampilkan seluruh data tabel tamu
  $query = "SELECT * FROM tamu ORDER BY nama ASC";
  $result = mysqli_query($koneksi, $query);

  if(!$result){
      die ("Query Error: ".mysqli_errno($koneksi).
           " - ".mysqli_error($koneksi));
  }

  //buat perulangan untuk element tabel dari data tamu
  while($data = mysqli_fetch_assoc($result))
  {
    echo "<tr>";
    echo "<td>$data[nama]</td>";
    echo "<td>$data[no_ktp]</td>";
    echo "<td>$data[email]</td>";
    echo "<td>$data[umur]</td>";
    echo "<td>$data[jenis_kelamin]</td>";
    echo "<td>$data[alamat]</td>";
    echo "<td>$data[tujuan_kunjungan]</td>";
    echo "<td>";
    ?>
      <form action="form_edit_tamu.php" method="post" >
      <input type="hidden" name="no_ktp" value="<?php echo "$data[no_ktp]"; ?>" >
      <input type="submit" name="submit" value="Edit" >
      </form>
    <?php
    echo "</td>";
    echo "</tr>";
  }

  // bebaskan memory
  mysqli_free_result($result);

  // tutup koneksi dengan database mysql
  mysqli_close($koneksi);
  ?>
  </table>
</div>
</body>
</html>
