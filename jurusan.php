<?php 
session_start();

// Proteksi Akses Login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

// Tambah Jurusan
if (isset($_POST['tambah'])) {
    $nama_jurusan = mysqli_real_escape_string($koneksi, $_POST['nama_jurusan']);
    mysqli_query($koneksi, "INSERT INTO tb_jurusan (nama_jurusan) VALUES ('$nama_jurusan')");
    header("Location: jurusan.php");
    exit();
}

// Hapus Jurusan
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM tb_jurusan WHERE id_jurusan='$id'");
    header("Location: jurusan.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Data Jurusan - Admin Dashboard</title>
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

        /* PANEL & TABLE STYLING */
        .panel { background: var(--card-bg); border-radius: 10px; border: 1px solid var(--border); padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .panel-title { font-size: 16px; font-weight: 600; color: var(--text-dark); margin-bottom: 15px; }

        /* FORM & BUTTONS */
        .form-row { display: flex; gap: 10px; margin-bottom: 20px; }
        .form-control { width: 100%; padding: 8px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 13px; }
        
        .btn { padding: 8px 16px; border: none; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-block; transition: 0.25s; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-danger { background: #ef4444; color: white; }
        .btn-danger:hover { background: #dc2626; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 13px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid var(--border); }
        th { background: #f1f5f9; color: #475569; font-weight: 600; }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="brand">SMK Digital App</div>
    <ul class="nav-menu">
        <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard Utama</a></li>
        <li class="nav-item"><a href="index.php" class="nav-link">Pendaftaran Siswa</a></li>
        <li class="nav-item"><a href="jurusan.php" class="nav-link active">Master Data Jurusan</a></li>
    </ul>
</aside>

<!-- MAIN CONTENT AREA -->
<main class="main-content">
    <!-- TOPBAR -->
    <header class="topbar">
        <h1>Master Data Jurusan</h1>
        <div class="user-profile">
            <span>Petugas: <strong><?php echo $_SESSION['username'] ?? 'Administrator'; ?></strong></span>
            <a href="logout.php" class="btn btn-danger" style="padding: 4px 10px; font-size: 11px;" onclick="return confirm('Yakin ingin logout?')">Logout</a>
        </div>
    </header>

    <!-- PANEL KELOLA JURUSAN -->
    <section class="panel">
        <div class="panel-title">Tambah & Kelola Jurusan</div>
        
        <form method="POST" class="form-row">
            <input type="text" name="nama_jurusan" class="form-control" placeholder="Nama Jurusan Baru (contoh: Rekayasa Perangkat Lunak)" required>
            <button type="submit" name="tambah" class="btn btn-primary" style="white-space: nowrap;">+ Tambah Jurusan</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Nama Jurusan</th>
                    <th style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q = mysqli_query($koneksi, "SELECT * FROM tb_jurusan ORDER BY id_jurusan DESC");
                if (mysqli_num_rows($q) > 0) {
                    while ($r = mysqli_fetch_assoc($q)) {
                        echo "<tr>
                            <td><strong>{$r['id_jurusan']}</strong></td>
                            <td>{$r['nama_jurusan']}</td>
                            <td>
                                <a href='jurusan.php?hapus={$r['id_jurusan']}' class='btn btn-danger' style='padding: 5px 10px;' onclick='return confirm(\"Hapus jurusan ini?\")'>Hapus</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' style='text-align:center; color: #94a3b8; padding: 20px;'>Belum ada data jurusan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</main>

</body>
</html>