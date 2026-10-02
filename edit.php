<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';


$id = $_GET['id'] ?? '';

// Perbaikan typo FORM -> FROM
$query = mysqli_query($koneksi, "SELECT * FROM tb_siswa WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>   
    <meta charset="UTF-8">   
    <title>Edit Data Siswa</title>   
    <style>       
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            background-color: #f4f4f9; 
        }       
        .container { 
            max-width: 600px; 
            margin: auto; 
            background: white; 
            padding: 20px; 
            border-radius: 8px; 
            box-shadow: 0 0 10px rgba(0,0,0,0.1); 
        }       
        .form-group { 
            margin-bottom: 15px; 
        }       
        label { 
            display: block; 
            margin-bottom: 5px; 
            font-weight: bold; 
        }       
        input[type="text"], textarea, select { 
            width: 100%; 
            padding: 8px; 
            box-sizing: border-box; 
        }       
        .btn { 
            padding: 10px 15px; 
            border: none; 
            border-radius: 4px; 
            color: white; 
            cursor: pointer; 
            text-decoration: none; 
            display: inline-block; 
        }       
        .btn-blue { 
            background-color: #007bff; 
        }       
        .btn-secondary { 
            background-color: #6c757d; 
        }   
    </style> 
</head> 
<body>

<div class="container">
    <h2>Edit Data Siswa</h2>
    <form action="update.php" method="POST">
        <!-- Input Hidden menyimpan Primary Key ID -->
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
        
        <div class="form-group">
            <label>NIS:</label>
            <input type="text" name="nis" value="<?php echo $data['nis']; ?>" required>
        </div>
        
        <div class="form-group">
            <label>Nama Lengkap:</label>
            <input type="text" name="nama" value="<?php echo $data['nama']; ?>" required>
        </div>
        
        <div class="form-group">
            <label>Jurusan:</label>
            <select name="jurusan">
                <option value="RPL" <?php if($data['jurusan'] == 'RPL') echo 'selected'; ?>>Rekayasa Perangkat Lunak</option>
                <option value="TKJ" <?php if($data['jurusan'] == 'TKJ') echo 'selected'; ?>>Teknik Komputer & Jaringan</option>
                <option value="MM"  <?php if($data['jurusan'] == 'MM') echo 'selected'; ?>>Multimedia</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Alasan Masuk:</label>
            <textarea name="alasan" rows="3"><?php echo $data['alasan']; ?></textarea>
        </div>
        
        <button type="submit" class="btn btn-blue">Simpan Perubahan</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

</body>
</html>