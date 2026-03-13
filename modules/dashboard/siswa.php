<?php
// Menentukan path utama proyek agar mudah memanggil file lain
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menyertakan file konfigurasi database
include ROOTPATH . "/config/config.php";

include ROOTPATH . "/layouts/header.php";

// Ambil NIS dari cookie
$nis = isset($_COOKIE['username']) ? mysqli_real_escape_string($conn, $_COOKIE['username']) : '';

// Query data siswa lengkap
$query = mysqli_query($conn, "SELECT s.*, t.tingkat, pk.program_keahlian, k.rombel, g.nama_pengguna AS wali_kelas,
    ow.ayah, ow.ibu, ow.wali, ow.pekerjaan_ayah, ow.pekerjaan_ibu, ow.pekerjaan_wali,
    ow.no_telp_ayah, ow.no_telp_ibu, ow.no_telp_wali, ow.alamat_ayah, ow.alamat_ibu, ow.alamat_wali
    FROM siswa s
    LEFT JOIN kelas k ON s.id_kelas = k.id_kelas
    LEFT JOIN tingkat t ON k.id_tingkat = t.id_tingkat
    LEFT JOIN program_keahlian pk ON k.id_program_keahlian = pk.id_program_keahlian
    LEFT JOIN guru g ON k.kode_guru = g.kode_guru
    LEFT JOIN ortu_wali ow ON s.id_ortu_wali = ow.id_ortu_wali
    WHERE s.nis = '$nis'");
$data = mysqli_fetch_assoc($query);

// Query riwayat pelanggaran
$query_pelanggaran = mysqli_query($conn, "SELECT ps.tanggal, jp.jenis, jp.poin, ps.keterangan
    FROM pelanggaran_siswa ps
    JOIN jenis_pelanggaran jp ON ps.id_jenis_pelanggaran = jp.id_jenis_pelanggaran
    WHERE ps.nis = '$nis'
    ORDER BY ps.tanggal DESC");

// Hitung total poin
$query_total = mysqli_query($conn, "SELECT COALESCE(SUM(jp.poin), 0) AS total_poin
    FROM pelanggaran_siswa ps
    JOIN jenis_pelanggaran jp ON ps.id_jenis_pelanggaran = jp.id_jenis_pelanggaran
    WHERE ps.nis = '$nis'");
$total = mysqli_fetch_assoc($query_total);
$total_poin = $total['total_poin'];
?>

<center>
    <h2>Data Diri Siswa</h2>

    <?php if ($data) { ?>

    <h3>Informasi Pribadi</h3>
    <table cellpadding="10" border="1" cellspacing="0" style="border-collapse: collapse; width: 60%;">
        <tr>
            <td><b>NIS</b></td>
            <td><?= htmlspecialchars($data['nis']) ?></td>
        </tr>
        <tr>
            <td><b>Nama</b></td>
            <td><?= htmlspecialchars($data['nama_siswa']) ?></td>
        </tr>
        <tr>
            <td><b>Jenis Kelamin</b></td>
            <td><?= htmlspecialchars($data['jenis_kelamin']) ?></td>
        </tr>
        <tr>
            <td><b>Kelas</b></td>
            <td><?= $data['tingkat'] . ' ' . $data['program_keahlian'] . ' ' . $data['rombel'] ?></td>
        </tr>
        <tr>
            <td><b>Wali Kelas</b></td>
            <td><?= htmlspecialchars($data['wali_kelas'] ?? '-') ?></td>
        </tr>
        <tr>
            <td><b>Alamat</b></td>
            <td><?= htmlspecialchars($data['alamat'] ?? '-') ?></td>
        </tr>
        <tr>
            <td><b>Status</b></td>
            <td><?= htmlspecialchars($data['status']) ?></td>
        </tr>
    </table>

    <br>
    <h3>Data Orang Tua / Wali</h3>
    <table cellpadding="10" border="1" cellspacing="0" style="border-collapse: collapse; width: 60%;">
        <?php if (!empty($data['ayah'])) { ?>
        <tr>
            <td><b>Nama Ayah</b></td>
            <td><?= htmlspecialchars($data['ayah']) ?></td>
        </tr>
        <tr>
            <td><b>Pekerjaan Ayah</b></td>
            <td><?= htmlspecialchars($data['pekerjaan_ayah'] ?? '-') ?></td>
        </tr>
        <tr>
            <td><b>No Telp Ayah</b></td>
            <td><?= htmlspecialchars($data['no_telp_ayah'] ?? '-') ?></td>
        </tr>
        <?php } ?>
        <?php if (!empty($data['ibu'])) { ?>
        <tr>
            <td><b>Nama Ibu</b></td>
            <td><?= htmlspecialchars($data['ibu']) ?></td>
        </tr>
        <tr>
            <td><b>Pekerjaan Ibu</b></td>
            <td><?= htmlspecialchars($data['pekerjaan_ibu'] ?? '-') ?></td>
        </tr>
        <tr>
            <td><b>No Telp Ibu</b></td>
            <td><?= htmlspecialchars($data['no_telp_ibu'] ?? '-') ?></td>
        </tr>
        <?php } ?>
        <?php if (!empty($data['wali'])) { ?>
        <tr>
            <td><b>Nama Wali</b></td>
            <td><?= htmlspecialchars($data['wali']) ?></td>
        </tr>
        <tr>
            <td><b>Pekerjaan Wali</b></td>
            <td><?= htmlspecialchars($data['pekerjaan_wali'] ?? '-') ?></td>
        </tr>
        <tr>
            <td><b>No Telp Wali</b></td>
            <td><?= htmlspecialchars($data['no_telp_wali'] ?? '-') ?></td>
        </tr>
        <?php } ?>
        <?php if (empty($data['ayah']) && empty($data['ibu']) && empty($data['wali'])) { ?>
        <tr><td colspan="2" style="text-align:center;">Data orang tua belum diisi</td></tr>
        <?php } ?>
    </table>

    <br>
    <h3>Riwayat Pelanggaran</h3>
    <p>Total Poin Pelanggaran: <b style="color: <?= $total_poin > 0 ? 'red' : 'green' ?>; font-size: 18px;"><?= $total_poin ?></b></p>

    <table cellpadding="8" border="1" cellspacing="0" style="border-collapse: collapse; width: 70%;">
        <thead>
            <tr style="background-color: #007bff; color: white;">
                <th>No</th>
                <th>Tanggal</th>
                <th>Jenis Pelanggaran</th>
                <th>Poin</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if (mysqli_num_rows($query_pelanggaran) > 0) {
                while ($row = mysqli_fetch_assoc($query_pelanggaran)) { ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= tanggal_indonesia($row['tanggal']) ?></td>
                    <td><?= htmlspecialchars($row['jenis']) ?></td>
                    <td style="text-align:center; font-weight:bold; color:red;"><?= $row['poin'] ?></td>
                    <td><?= htmlspecialchars($row['keterangan']) ?></td>
                </tr>
            <?php }
            } else { ?>
                <tr><td colspan="5" style="text-align:center;">Tidak ada pelanggaran 👍</td></tr>
            <?php } ?>
        </tbody>
    </table>

    <?php } else { ?>
        <p>Data tidak ditemukan!</p>
    <?php } ?>
</center>

<?php
include ROOTPATH . "/layouts/footer.php";
?>
