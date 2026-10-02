<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_siswa";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . myqli_connect_error());
}
?>