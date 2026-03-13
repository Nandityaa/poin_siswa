<?php
// Menentukan path utama proyek agar mudah memanggil file lain
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menyertakan file konfigurasi database
include ROOTPATH . "/config/config.php";

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    
    if ($jenis == 'Siswa') {
        mysqli_query($conn, "UPDATE perjanjian_siswa SET status = '$new_status' WHERE id_perjanjian_siswa = '$id'");
    } else {
        mysqli_query($conn, "UPDATE perjanjian_orang_tua SET status = '$new_status' WHERE id_perjanjian_ortu = '$id'");
    }
    
    // Redirect via PHP header before any output
    header("Location: surat_perjanjian.php");
    exit;
}

include ROOTPATH . "/layouts/header.php";

// Fetch gabungan dari perjanjian_siswa dan perjanjian_orang_tua
$query = mysqli_query($conn, "
    SELECT 
        'Siswa' AS jenis_perjanjian,
        ps.id_perjanjian_siswa AS id_perjanjian,
        ps.tanggal,
        ps.status,
        s.nis, 
        s.nama_siswa, 
        t.tingkat, 
        pk.program_keahlian, 
        k.rombel
    FROM perjanjian_siswa ps
    JOIN pelanggaran_siswa pel ON ps.id_pelanggaran_siswa = pel.id_pelanggaran_siswa
    JOIN siswa s ON pel.nis = s.nis
    JOIN kelas k ON s.id_kelas = k.id_kelas
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat
    JOIN program_keahlian pk ON k.id_program_keahlian = pk.id_program_keahlian
    
    UNION ALL
    
    SELECT 
        'Orang Tua' AS jenis_perjanjian,
        po.id_perjanjian_ortu AS id_perjanjian,
        po.tanggal,
        po.status,
        s.nis, 
        s.nama_siswa, 
        t.tingkat, 
        pk.program_keahlian, 
        k.rombel
    FROM perjanjian_orang_tua po
    JOIN pelanggaran_siswa pel ON po.id_pelanggaran_siswa = pel.id_pelanggaran_siswa
    JOIN siswa s ON pel.nis = s.nis
    JOIN kelas k ON s.id_kelas = k.id_kelas
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat
    JOIN program_keahlian pk ON k.id_program_keahlian = pk.id_program_keahlian
    
    ORDER BY tanggal DESC
");
?>

<center>
    <h2>Laporan Surat Perjanjian</h2>
    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 95%;">
        <thead>
            <tr style="background-color: #007bff; color: white;">
                <th>No</th>
                <th>Tanggal</th>
                <th>Jenis Perjanjian</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if(mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) { 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= date('d-m-Y H:i', strtotime($row['tanggal'])) ?></td>
                    <td>Perjanjian <?= $row['jenis_perjanjian'] ?></td>
                    <td><?= $row['nis'] ?></td>
                    <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                    <td><?= $row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel'] ?></td>
                    <td>
                        <span style="font-weight:bold; color: <?= $row['status'] == 'Masih Proses' ? 'red' : 'green' ?>;">
                            <?= $row['status'] ?>
                        </span>
                    </td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $row['id_perjanjian'] ?>">
                            <input type="hidden" name="jenis" value="<?= $row['jenis_perjanjian'] ?>">
                            <select name="status" onchange="this.form.submit()" style="padding:5px;">
                                <option value="Masih Proses" <?= $row['status'] == 'Masih Proses' ? 'selected' : '' ?>>Masih Proses</option>
                                <option value="Selesai" <?= $row['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                            </select>
                            <input type="hidden" name="update_status" value="1">
                        </form>
                    </td>
                </tr>
            <?php } 
            } else { ?>
                <tr><td colspan="8" style="text-align:center;">Belum ada data surat perjanjian diproses.</td></tr>
            <?php } ?>
        </tbody>
    </table>
</center>

<?php include ROOTPATH . "/layouts/footer.php"; ?>
