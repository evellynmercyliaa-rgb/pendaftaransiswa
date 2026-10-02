<?php
include 'koneksi.php';

if (mysqli_query($koneksi, $sql)) {
    header("Location: dashboard.php"); 
    exit();
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // 1. Ambil nama file foto dari database
    $q    = mysqli_query($koneksi, "SELECT foto FROM tb_siswa WHERE id = '$id'");
    $data = mysqli_fetch_assoc($q);

    if ($data) {
        $foto_lama = $data['foto'];

        // 2. Hapus file fisik foto jika ada
        if (!empty($foto_lama) && file_exists("uploads/" . $foto_lama)) {
            unlink("uploads/" . $foto_lama);
        }

        // 3. Hapus data dari database
        $sql = "DELETE FROM tb_siswa WHERE id = '$id'";
        if (mysqli_query($koneksi, $sql)) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    } else {
        echo "Data tidak ditemukan.";
    }
}
?>