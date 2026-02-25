<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Menyertakan tampilan header (bagian atas halaman)
include ROOTPATH . "/includes/header.php";

// Query untuk menampilkan semua data pelanggaran siswa
$result = mysqli_query($conn, "SELECT ps.id_pelanggaran_siswa, ps.tanggal, ps.nis, s.nama_siswa, 
    t.tingkat, pk.program_keahlian, k.rombel, 
    jp.jenis, jp.poin, ps.keterangan 
    FROM pelanggaran_siswa ps 
    JOIN siswa s ON ps.nis = s.nis 
    JOIN kelas k ON s.id_kelas = k.id_kelas 
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat 
    JOIN program_keahlian pk ON k.id_program_keahlian = pk.id_program_keahlian 
    JOIN jenis_pelanggaran jp ON ps.id_jenis_pelanggaran = jp.id_jenis_pelanggaran 
    ORDER BY ps.tanggal DESC");
?>

<center>
    <h2>Laporan Pelanggaran Siswa</h2>

    <!-- Tombol untuk menambah entri pelanggaran baru -->
    <button class="btn-primary"><a href="/poin_siswa/pages/pelanggaran/add.php">+ Tambah Entri Pelanggaran</a></button>

    <br><br>

    <!-- Tabel data pelanggaran siswa -->
    <table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 95%;">
        <thead>
            <tr style="background-color: #007bff; color: white;">
                <th>No</th>
                <th>Tanggal</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Jenis Pelanggaran</th>
                <th>Poin</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) { 
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= date("d-m-Y H:i", strtotime($row['tanggal'])) ?></td>
                <td><?= $row['nis'] ?></td>
                <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                <td><?= $row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel'] ?></td>
                <td><?= htmlspecialchars($row['jenis']) ?></td>
                <td style="text-align:center; font-weight:bold; color:red;"><?= $row['poin'] ?></td>
                <td><?= htmlspecialchars($row['keterangan']) ?></td>
                <td>
                    <form action="/poin_siswa/process/pelanggaran_process.php" method="post" onsubmit="return confirm('Yakin ingin menghapus data pelanggaran ini?')">
                        <input type="hidden" name="id" value="<?= $row['id_pelanggaran_siswa'] ?>">
                        <input type="hidden" name="action" value="delete">
                        <button class="btn-danger" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</center>

<?php
// Menyertakan bagian footer (penutup halaman)
include ROOTPATH . "/includes/footer.php";
?>
