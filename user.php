<?php
include 'koneksi.php';

// 1. Buat tabel users jika belum ada
$sql_tabel = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";
mysqli_query($koneksi, $sql_tabel);

// 2. Masukkan user admin baru
$username = "admin";
$password = password_hash("admin123", PASSWORD_DEFAULT);

// Hapus user lama jika ada bentrok
mysqli_query($koneksi, "DELETE FROM users WHERE username = '$username'");

$stmt = mysqli_prepare($koneksi, "INSERT INTO users (username, password) VALUES (?, ?)");
mysqli_stmt_bind_param($stmt, "ss", $username, $password);

if (mysqli_stmt_execute($stmt)) {
    echo "<h3>Tabel & Akun Admin Berhasil Dibuat!</h3>";
    echo "<b>Username:</b> admin<br>";
    echo "<b>Password:</b> admin123<br><br>";
    echo "<a href='login.php'>Langsung Coba Login</a>";
} else {
    echo "Gagal: " . mysqli_error($koneksi);
}
?>