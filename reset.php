<?php
include 'koneksi.php';

// 1. Perbaiki ukuran kolom password di database agar tidak memotong hash
mysqli_query($koneksi, "ALTER TABLE users MODIFY COLUMN password VARCHAR(255) NOT NULL");

// 2. Buat password hash baru yang valid untuk 'admin123'
$username = 'admin';
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

// 3. Reset data user admin
mysqli_query($koneksi, "DELETE FROM users WHERE username = '$username'");
$stmt = mysqli_prepare($koneksi, "INSERT INTO users (username, password) VALUES (?, ?)");
mysqli_stmt_bind_param($stmt, "ss", $username, $hash);

if (mysqli_stmt_execute($stmt)) {
    echo "<h2 style='color: green;'>DATABASE BERHASIL DI-RESET!</h2>";
    echo "<p>Sekarang kamu bisa login dengan data ini:</p>";
    echo "<b>Username:</b> admin<br>";
    echo "<b>Password:</b> admin123<br><br>";
    echo "<a href='login.php' style='padding: 10px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;'>Klik di sini untuk Login</a>";
} else {
    echo "Gagal reset: " . mysqli_error($koneksi);
}
?>