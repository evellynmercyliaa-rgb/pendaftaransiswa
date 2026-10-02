<?php 
session_start();

// Proteksi Akses Login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Siswa Baru - db_siswa</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; margin: 20px; background: #f4f4f9; color: #333; }
        .container { max-width: 950px; margin: auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; }
        .header h2 { margin: 0; font-size: 22px; color: #1e293b; }
        .nav-btns { display: flex; gap: 8px; }
        
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 14px; }
        input[type="text"], textarea, select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        
        .btn { padding: 8px 14px; border: none; border-radius: 4px; color: white; cursor: pointer; text-decoration: none; font-size: 13px; display: inline-block; }
        .btn-green { background-color: #28a745; }
        .btn-green:hover { background-color: #218838; }
        .btn-blue { background-color: #007bff; }
        .btn-blue:hover { background-color: #0069d9; }
        .btn-danger { background-color: #dc3545; }
        .btn-danger:hover { background-color: #c82333; }
        .btn-purple { background-color: #6f42c1; }
        .btn-purple:hover { background-color: #5a32a3; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #1e293b; color: white; font-size: 14px; }
        td { font-size: 13px; vertical-align: middle; }
        
        .img-thumb { width: 50px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; }
        .filter-box { background: #e2e8f0; padding: 15px; border-radius: 6px; margin-bottom: 20px; display: flex; gap: 10px; align-items: center; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Form Pendaftaran Siswa Baru</h2>
        <!-- TOMBOL NAVIGASI & LOGOUT -->
        <div class="nav-btns">
            <a href="dashboard.php" class="btn" style="background: #2563eb; color: white;">&laquo; Ke Dashboard Admin</a>
            <a href="jurusan.php" class="btn btn-purple">Kelola Master Jurusan &raquo;</a>
            <a href="logout.php" class="btn btn-danger" onclick="return confirm('Yakin ingin keluar dari sistem?')">Logout</a>
        </div>
    </div>

    <!-- FORM PENDAFTARAN (UPLOAD PASFOTO) -->
    <form action="simpan.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>NIS:</label>
            <input type="text" name="nis" placeholder="Masukkan NIS..." required>
        </div>
        <div class="form-group">
            <label>Nama Lengkap:</label>
            <input type="text" name="nama" placeholder="Masukkan Nama Lengkap..." required>
        </div>
        <div class="form-group">
            <label>Pilih Jurusan:</label>
            <select name="id_jurusan" required>
                <option value="">-- Pilih Jurusan --</option>
                <?php
                $jur = mysqli_query($koneksi, "SELECT * FROM tb_jurusan ORDER BY nama_jurusan ASC");
                while ($j = mysqli_fetch_assoc($jur)) {
                    echo "<option value='{$j['id_jurusan']}'>{$j['nama_jurusan']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Upload Pasfoto (JPG/PNG, Max 2MB):</label>
            <input type="file" name="foto" accept="image/*" required>
        </div>
        <div class="form-group">
            <label>Alasan Masuk:</label>
            <textarea name="alasan" rows="3" placeholder="Tuliskan alasan memilih jurusan..." required></textarea>
        </div>
        <button type="submit" class="btn btn-green">Daftar Sekarang</button>
    </form>

    <hr style="margin: 30px 0; border: 0; border-top: 1px solid #ddd;">

    <h3>Daftar Siswa Terdaftar</h3>

    <!-- FORM FILTER & PENCARIAN -->
    <form method="GET" class="filter-box">
        <input type="text" name="keyword" placeholder="Cari NIS / Nama..." value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>" style="flex: 2;">
        <select name="filter_jurusan" style="flex: 1;">
            <option value="">Semua Jurusan</option>
            <?php
            $jur2 = mysqli_query($koneksi, "SELECT * FROM tb_jurusan ORDER BY nama_jurusan ASC");
            while ($j2 = mysqli_fetch_assoc($jur2)) {
                $selected = (isset($_GET['filter_jurusan']) && $_GET['filter_jurusan'] == $j2['id_jurusan']) ? 'selected' : '';
                echo "<option value='{$j2['id_jurusan']}' $selected>{$j2['nama_jurusan']}</option>";
            }
            ?>
        </select>
        <button type="submit" class="btn btn-blue">Cari & Filter</button>
        <a href="index.php" class="btn btn-danger">Reset</a>
    </form>

    <!-- TABEL DATA REAL-TIME -->
    <table>
        <thead>
            <tr>
                <th>Pasfoto</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>Jurusan</th>
                <th>Alasan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // QUERY DENGAN JOIN & FILTER
            $sql = "SELECT tb_siswa.*, tb_jurusan.nama_jurusan 
                    FROM tb_siswa 
                    JOIN tb_jurusan ON tb_siswa.id_jurusan = tb_jurusan.id_jurusan 
                    WHERE 1=1";

            if (isset($_GET['keyword']) && $_GET['keyword'] != '') {
                $kw = mysqli_real_escape_string($koneksi, $_GET['keyword']);
                $sql .= " AND (tb_siswa.nama LIKE '%$kw%' OR tb_siswa.nis LIKE '%$kw%')";
            }

            if (isset($_GET['filter_jurusan']) && $_GET['filter_jurusan'] != '') {
                $fj = mysqli_real_escape_string($koneksi, $_GET['filter_jurusan']);
                $sql .= " AND tb_siswa.id_jurusan = '$fj'";
            }

            $sql .= " ORDER BY tb_siswa.id DESC";
            $query = mysqli_query($koneksi, $sql);

            if ($query && mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) {
                    echo "<tr>
                        <td style='text-align:center;'>
                            <img src='uploads/" . htmlspecialchars($row['foto']) . "' class='img-thumb' alt='Foto'>
                        </td>
                        <td>" . htmlspecialchars($row['nis']) . "</td>
                        <td>" . htmlspecialchars($row['nama']) . "</td>
                        <td>" . htmlspecialchars($row['nama_jurusan']) . "</td>
                        <td>" . htmlspecialchars($row['alasan']) . "</td>
                        <td>
                            <a href='cetak.php?id={$row['id']}' target='_blank' class='btn btn-blue'>Cetak Kartu</a>
                            <a href='hapus.php?id={$row['id']}' class='btn btn-danger' onclick='return confirm(\"Hapus data ini?\")'>Hapus</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center; color: #777;'>Belum ada data siswa terdaftar. Silakan isi form di atas.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>