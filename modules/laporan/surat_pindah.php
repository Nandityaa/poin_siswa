<?php
// Menentukan path utama proyek agar mudah memanggil file lain
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menyertakan file konfigurasi database
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/layouts/header.php";

$query = mysqli_query($conn, "SELECT sk.no_surat, sk.tanggal_pembuatan_surat, s.nis, s.nama_siswa, t.tingkat, pk.program_keahlian, k.rombel
    FROM surat_keluar sk
    JOIN siswa s ON sk.nis = s.nis
    JOIN kelas k ON s.id_kelas = k.id_kelas
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat
    JOIN program_keahlian pk ON k.id_program_keahlian = pk.id_program_keahlian
    WHERE sk.jenis_surat = 'pindah'
    ORDER BY sk.tanggal_pembuatan_surat DESC");
?>

<center>
    <h2>Laporan Surat Pindah Siswa</h2>
    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 95%;">
        <thead>
            <tr style="background-color: #007bff; color: white;">
                <th>No</th>
                <th>Tanggal Dicetak</th>
                <th>No Surat</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas Sebelumnya</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if(mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) { ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= tanggal_indonesia($row['tanggal_pembuatan_surat']) ?></td>
                    <td><?= htmlspecialchars($row['no_surat']) ?></td>
                    <td><?= $row['nis'] ?></td>
                    <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                    <td><?= $row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel'] ?></td>
                </tr>
            <?php } 
            } else { ?>
                <tr><td colspan="6" style="text-align:center;">Belum ada data surat pindah diproses.</td></tr>
            <?php } ?>
        </tbody>
    </table>
</center>

<?php include ROOTPATH . "/layouts/footer.php"; ?>
