<?php
include 'koneksi.php';

$username = "admin";
$password_plain = "admin123"; // Password baru yang ingin kamu pakai
$password_hash = password_hash($password_plain, PASSWORD_DEFAULT);

// Hapus akun lama jika ada, lalu buat baru
mysqli_query($koneksi, "DELETE FROM users WHERE username = '$username'");

$stmt = mysqli_prepare($koneksi, "INSERT INTO users (username, password) VALUES (?, ?)");
mysqli_stmt_bind_param($stmt, "ss", $username, $password_hash);

if (mysqli_stmt_execute($stmt)) {
    echo "<h3>Akun Admin Berhasil Di-reset!</h3>";
    echo "<b>Username:</b> " . $username . "<br>";
    echo "<b>Password:</b> " . $password_plain . "<br><br>";
    echo "<a href='login.php'>Klik di sini untuk Login</a>";
} else {
    echo "Gagal membuat akun: " . mysqli_error($koneksi);
}
?>