<?php
include 'koneksi.php';

$no = 1;
$query = mysqli_query($koneksi, "SELECT * FROM tb_siswa ORDER BY id DESC");
while ($row = mysqli_fetch_assoc($query)) {
    echo "<tr>
            <td>{$no}</td>
            <td>{$row['nis']}</td>
            <td>{$row['nama']}</td>
            <td>{$row['jurusan']}</td>
            <td>{$row['alasan']}</td>
            <td>
                <a href='edit.php?id={$row['id']}' class='btn btn-warning'>Edit</a>
                <a href='hapus.php?id={$row['id']}' class='btn btn-danger' onclick=\"return confirm('Hapus data ini?')\">Hapus</a>
            </td>
          </tr>";
    $no++;
}
?>