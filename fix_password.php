<?php
include 'koneksi.php';

// Generate hash baru yang valid untuk password 'admin123'
$username = 'admin';
$password_baru = 'admin123';
$hash_baru = password_hash($password_baru, PASSWORD_DEFAULT);

// Update langsung ke database
$query = "UPDATE users SET password = '$hash_baru' WHERE username = '$username'";

if (mysqli_query($koneksi, $query)) {
    echo "<h3>Password Admin Berhasil Perbaiki!</h3>";
    echo "<b>Username:</b> admin<br>";
    echo "<b>Password:</b> admin123<br><br>";
    echo "<a href='login.php'>Klik di sini untuk coba Login</a>";
} else {
    echo "Gagal mengupdate password: " . mysqli_error($koneksi);
}
?>