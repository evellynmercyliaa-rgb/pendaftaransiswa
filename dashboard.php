<?php 
session_start();

// Proteksi Akses Login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

// Hitung Statistik Agregat untuk Widget Card
$total_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tb_siswa"))['total'];
$total_jurusan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tb_jurusan"))['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sistem Pendaftaran Siswa</title>
    <style>
        :root {
            --bg-body: #f8fafc;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --card-bg: #ffffff;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }
        
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: var(--bg-body); color: var(--text-dark); display: flex; min-height: 100vh; }

        /* SIDEBAR STYLING */
        .sidebar { width: 250px; background-color: var(--sidebar-bg); color: #fff; padding: 20px 15px; display: flex; flex-direction: column; }
        .sidebar .brand { font-size: 16px; font-weight: bold; padding-bottom: 20px; border-bottom: 1px solid #334155; color: #38bdf8; text-transform: uppercase; letter-spacing: 0.5px; }
        .sidebar .nav-menu { margin-top: 20px; list-style: none; }
        .sidebar .nav-item { margin-bottom: 5px; }
        .sidebar .nav-link { display: block; padding: 10px 14px; color: #94a3b8; text-decoration: none; border-radius: 6px; font-size: 14px; transition: 0.25s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: var(--sidebar-hover); color: #ffffff; font-weight: 500; }

        /* MAIN CONTENT AREA */
        .main-content { flex: 1; padding: 25px 30px; }

        /* TOPBAR / HEADER */
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .topbar h1 { font-size: 22px; color: var(--text-dark); }
        .user-profile { font-size: 13px; color: var(--text-muted); background: white; padding: 6px 14px; border-radius: 20px; border: 1px solid var(--border); display: flex; gap: 10px; align-items: center; }

        /* WIDGET KARTU STATISTIK */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 25px; }
        .stat-card { background: var(--card-bg); padding: 20px; border-radius: 10px; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .stat-card p { font-size: 13px; color: var(--text-muted); }
        .stat-card h2 { font-size: 28px; color: var(--text-dark); font-weight: 500; margin-top: 5px; }

        /* PANEL & TABLE STYLING */
        .panel { background: var(--card-bg); border-radius: 10px; border: 1px solid var(--border); padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); margin-bottom: 25px; }
        .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; }
        .panel-title { font-size: 16px; font-weight: 600; color: var(--text-dark); }

        /* BUTTONS & INPUTS */
        .btn { padding: 8px 16px; border: none; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-block; transition: 0.25s; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-danger { background: #ef4444; color: white; }
        .btn-danger:hover { background: #dc2626; }
        .btn-blue { background: #0284c7; color: white; }
        .btn-secondary { background: #e2e8f0; color: var(--text-dark); }

        /* FILTER & FORM GRID */
        .filter-row { display: flex; gap: 10px; margin-bottom: 15px; }
        .form-control { width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 13px; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 13px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid var(--border); }
        th { background: #f1f5f9; color: #475569; font-weight: 600; }
        .img-thumb { width: 42px; height: 50px; object-fit: cover; border-radius: 4px; }
    </style>
</head>
<body>

<!-- SIDEBAR NAVIGATION DI DASHBOARD.PHP -->
<aside class="sidebar">
    <div class="brand">SMK Digital App</div>
    <ul class="nav-menu">
        <li class="nav-item"><a href="dashboard.php" class="nav-link active">Dashboard Utama</a></li>
        <li class="nav-item"><a href="index.php" class="nav-link">Pendaftaran Siswa</a></li>
        <li class="nav-item"><a href="jurusan.php" class="nav-link">Master Data Jurusan</a></li>
    </ul>
</aside>

<!-- MAIN CONTENT AREA -->
<main class="main-content">
    <!-- TOPBAR -->
    <header class="topbar">
        <h1>Dashboard Pendaftaran Siswa Baru</h1>
        <div class="user-profile">
            <span>Petugas: <strong><?php echo $_SESSION['username'] ?? 'Administrator'; ?></strong></span>
            <a href="logout.php" class="btn btn-danger" style="padding: 4px 10px; font-size: 11px;" onclick="return confirm('Yakin ingin logout?')">Logout</a>
        </div>
    </header>

    <!-- STATS WIDGET CARDS -->
    <section class="stats-grid">
        <div class="stat-card">
            <p>Total Siswa Terdaftar</p>
            <h2><?php echo $total_siswa; ?></h2>
        </div>
        <div class="stat-card">
            <p>Jumlah Jurusan Tersedia</p>
            <h2><?php echo $total_jurusan; ?></h2>
        </div>
    </section>

    <!-- PANEL DATA SISWA -->
    <section class="panel">
        <div class="panel-header">
            <div class="panel-title">Daftar Calon Siswa Baru</div>
            <!-- TOMBOL DIUBAH DARI BUTTON KE LINK KE INDEX.PHP -->
            <a href="index.php" class="btn btn-primary">+ Tambah Siswa Baru</a>
        </div>

        <!-- FILTER & SEARCH FORM -->
        <form method="GET" class="filter-row">
            <input type="text" name="keyword" class="form-control" placeholder="Cari NIS atau Nama..." value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
            <select name="filter_jurusan" class="form-control" style="max-width: 200px;">
                <option value="">Semua Jurusan</option>
                <?php
                $j_query = mysqli_query($koneksi, "SELECT * FROM tb_jurusan ORDER BY nama_jurusan ASC");
                while($j_row = mysqli_fetch_assoc($j_query)) {
                    $selected = (isset($_GET['filter_jurusan']) && $_GET['filter_jurusan'] == $j_row['id_jurusan']) ? 'selected' : '';
                    echo "<option value='{$j_row['id_jurusan']}' $selected>{$j_row['nama_jurusan']}</option>";
                }
                ?>
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
            <a href="dashboard.php" class="btn btn-secondary" style="background: #f1f5f9;">Reset</a>
        </form>

        <!-- TABEL DATA -->
        <table>
            <thead>
                <tr>
                    <th>Pasfoto</th>
                    <th>NIS</th>
                    <th>Nama Lengkap</th>
                    <th>Pilihan Jurusan</th>
                    <th>Alasan Masuk</th>
                    <th style="width: 160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
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
                $result = mysqli_query($koneksi, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td><img src='uploads/" . htmlspecialchars($row['foto']) . "' class='img-thumb'></td>
                            <td><strong>" . htmlspecialchars($row['nis']) . "</strong></td>
                            <td>" . htmlspecialchars($row['nama']) . "</td>
                            <td>" . htmlspecialchars($row['nama_jurusan']) . "</td>
                            <td>" . htmlspecialchars($row['alasan']) . "</td>
                            <td>
                                <a href='cetak.php?id={$row['id']}' target='_blank' class='btn btn-blue' style='padding: 5px 10px;'>Cetak</a>
                                <a href='hapus.php?id={$row['id']}' class='btn btn-danger' style='padding: 5px 10px;' onclick='return confirm(\"Hapus data siswa ini?\")'>Hapus</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center; color: #94a3b8; padding: 20px;'>Data siswa tidak ditemukan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</main>

</body>
</html>