<?php
  // buat koneksi dengan database mysql
  $dbhost = "localhost";
  $dbuser = "root";
  $dbpass = "";
  $koneksi   = mysqli_connect($dbhost,$dbuser,$dbpass);

  //periksa koneksi, tampilkan pesan kesalahan jika gagal
  if(!$koneksi){
    die ("Koneksi dengan database gagal: ".mysqli_connect_errno().
         " - ".mysqli_connect_error());
  }

  //buat database buku_tamu jika belum ada
  $query = "CREATE DATABASE IF NOT EXISTS buku_tamu";
  $result = mysqli_query($koneksi, $query);

  if(!$result){
    die ("Query Error: ".mysqli_errno($koneksi).
         " - ".mysqli_error($koneksi));
  }
  else {
    echo "Database <b>'buku_tamu'</b> berhasil dibuat... <br>";
  }

  //pilih database buku_tamu
  $result = mysqli_select_db($koneksi, "buku_tamu");

  if(!$result){
    die ("Query Error: ".mysqli_errno($koneksi).
         " - ".mysqli_error($koneksi));
  }
  else {
    echo "Database <b>'buku_tamu'</b> berhasil dipilih... <br>";
  }

  // cek apakah tabel tamu sudah ada. jika ada, hapus tabel
  $query = "DROP TABLE IF EXISTS tamu";
  $hasil_query = mysqli_query($koneksi, $query);

  if(!$hasil_query){
    die ("Query Error: ".mysqli_errno($koneksi).
         " - ".mysqli_error($koneksi));
  }
  else {
    echo "Tabel <b>'tamu'</b> berhasil dihapus... <br>";
  }

  // buat query untuk CREATE tabel tamu
  $query  = "CREATE TABLE tamu (nama VARCHAR(100), no_ktp CHAR(16), ";
  $query .= "email VARCHAR(30), umur CHAR(2), ";
  $query .= "jenis_kelamin VARCHAR(10), alamat VARCHAR(100), ";
  $query .= "tujuan_kunjungan VARCHAR(100), PRIMARY KEY (no_ktp))";

  $hasil_query = mysqli_query($koneksi, $query);

  if(!$hasil_query){
      die ("Query Error: ".mysqli_errno($koneksi).
           " - ".mysqli_error($koneksi));
  }
  else {
    echo "Tabel <b>'tamu'</b> berhasil dibuat... <br>";
  }

  // buat query untuk INSERT data ke tabel tamu
  $query  = "INSERT INTO tamu VALUES ";
  $query .= "('ivan', '1807070507960003', 'ivan@gmail.com', '25', ";
  $query .= "'Pria', 'Lampung', 'Interview Kerja'), ";
  $query .= "('Asep', '1234567899874561', 'asep@gmail.com', '24', ";
  $query .= "'Pria', 'Sukabumi', 'Interview Kerja'),";
  $query .= "('Rina', '9999999999999999', 'rina@gmail.com', '25', ";
  $query .= "'Wanita', 'Jakarta', 'Interview Kerja')";

  $hasil_query = mysqli_query($koneksi, $query);

  if(!$hasil_query){
      die ("Query Error: ".mysqli_errno($koneksi).
           " - ".mysqli_error($koneksi));
  }
  else {
    echo "Tabel <b>'tamu'</b> berhasil diisi... <br>";
  }

  // cek apakah tabel admin sudah ada. jika ada, hapus tabel
  $query = "DROP TABLE IF EXISTS admin";
  $hasil_query = mysqli_query($koneksi, $query);

  if(!$hasil_query){
    die ("Query Error: ".mysqli_errno($koneksi).
         " - ".mysqli_error($koneksi));
  }
  else {
    echo "Tabel <b>'admin'</b> berhasil dihapus... <br>";
  }

  // buat query untuk CREATE tabel admin
  $query  = "CREATE TABLE admin (username VARCHAR(50), password CHAR(40))";
  $hasil_query = mysqli_query($koneksi, $query);

  if(!$hasil_query){
      die ("Query Error: ".mysqli_errno($koneksi).
           " - ".mysqli_error($koneksi));
  }
  else {
    echo "Tabel <b>'admin'</b> berhasil dibuat... <br>";
  }

  // buat username dan password untuk admin
  $username = "admin12";
  $password = sha1("adminku");

  // buat query untuk INSERT data ke tabel admin
  $query  = "INSERT INTO admin VALUES ('$username','$password')";

  $hasil_query = mysqli_query($koneksi, $query);

  if(!$hasil_query){
      die ("Query Error: ".mysqli_errno($koneksi).
           " - ".mysqli_error($koneksi));
  }
  else {
    echo "Tabel <b>'admin'</b> berhasil diisi... <br>";
  }

  // tutup koneksi dengan database mysql
  mysqli_close($koneksi);
?>
