<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');
include ROOTPATH . "/config/config.php";

// RBAC: Hanya untuk admin_bk, wakasek, kepsek
$allowed_roles = ['admin_bk', 'wakasek', 'kepsek'];
if (!isset($_COOKIE['role']) || !in_array($_COOKIE['role'], $allowed_roles)) {
    echo "<script>window.location.href='/poin_siswa/modules/dashboard/index.php';</script>";
    exit;
}

// Ambil semua siswa yang punya surat perjanjian
$query = mysqli_query($conn, "SELECT DISTINCT s.nis, s.nama_siswa, t.tingkat, pk.program_keahlian, k.rombel
    FROM surat_keluar sk
    JOIN siswa s ON sk.nis = s.nis
    JOIN kelas k ON s.id_kelas = k.id_kelas
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat
    JOIN program_keahlian pk ON k.id_program_keahlian = pk.id_program_keahlian
    WHERE sk.jenis_surat IN ('perjanjian_siswa', 'perjanjian_ortu')
    ORDER BY t.tingkat, pk.program_keahlian, k.rombel, s.nama_siswa");

function getSuratSiswa($conn, $nis) {
    $result = mysqli_query($conn, "SELECT jenis_surat, tanggal_pembuatan_surat
        FROM surat_keluar WHERE nis = '$nis' AND jenis_surat IN ('perjanjian_siswa','perjanjian_ortu') 
        ORDER BY tanggal_pembuatan_surat ASC");
    $data = [];
    if ($result) while ($r = mysqli_fetch_assoc($result)) $data[] = $r;
    return $data;
}

// Ambil nama Waka & Guru BK
$waka = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = 'Waka Kesiswaan' AND aktif = 'Y' LIMIT 1"));
$bk = [];
foreach (['Guru BK X','Guru BK XI','Guru BK XII'] as $jab) {
    $r = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = '$jab' AND aktif = 'Y' LIMIT 1"));
    if ($r) $bk[] = $r['nama_pengguna'];
}
$bk_count = max(count($bk), 2);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Surat Perjanjian Siswa</title>
    <style>
        @page { size: A4; margin: 0; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; color: #000; background: #9ca3af; }
        .page-wrap { max-width: 210mm; margin: 20px auto; }
        .toolbar { display: flex; gap: 10px; margin-bottom: 12px; font-family: sans-serif; }
        .toolbar button { padding: 8px 18px; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; }
        .tb-back { background: #e5e7eb; color: #333; }
        .tb-back:hover { background: #d1d5db; }
        .tb-print { background: #2563eb; color: white; }
        .tb-print:hover { background: #1d4ed8; }
        .paper { background: white; width: 210mm; min-height: 297mm; padding: 14mm 18mm 18mm 18mm; box-shadow: 0 4px 20px rgba(0,0,0,0.25); }
        .doc-title { text-align: center; margin-bottom: 16px; }
        .doc-title h2 { font-size: 14pt; text-transform: uppercase; letter-spacing: 1px; }
        .tbl { width: 100%; border-collapse: collapse; font-size: 11pt; }
        .tbl th, .tbl td { border: 1px solid #000; padding: 5px 7px; vertical-align: middle; }
        .tbl thead th { text-align: center; font-weight: bold; font-size: 10pt; }
        .c { text-align: center; }
        .ttd-tbl { width: 100%; border-collapse: collapse; margin-top: 28px; }
        .ttd-tbl td { border: none; vertical-align: top; padding: 2px 4px; font-size: 11pt; }
        .ttd-gap { height: 65px; }
        .ttd-name { font-weight: bold; text-decoration: underline; }
        @media print {
            body { background: white; }
            .no-print { display: none !important; }
            .page-wrap { margin: 0; max-width: 100%; }
            .paper { box-shadow: none; width: 100%; padding: 0; }
        }
    </style>
</head>
<body>
<div class="page-wrap">
    <div class="toolbar no-print">
        <button class="tb-back" onclick="window.location.href='/poin_siswa/modules/dashboard/index.php'">← Kembali</button>
        <button class="tb-print" onclick="window.print()">🖨 Cetak</button>
    </div>
    <div class="paper">

        <div class="doc-title"><h2>Rekap Surat Perjanjian Siswa</h2></div>

        <table class="tbl">
            <thead>
                <tr>
                    <th style="width:5%">No</th>
                    <th style="width:22%">Nama Siswa</th>
                    <th style="width:12%">Kelas</th>
                    <th style="width:9%">Kode Siswa</th>
                    <th style="width:10%">Perjanjian Ke-</th>
                    <th style="width:20%">Tanggal</th>
                    <th style="width:22%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
<?php
$no = 1;
$found = false;
if ($query && mysqli_num_rows($query) > 0) {
    while ($siswa = mysqli_fetch_assoc($query)) {
        $found = true;
        $kelas = $siswa['tingkat'] . ' ' . $siswa['program_keahlian'] . ' ' . $siswa['rombel'];
        $surats = getSuratSiswa($conn, $siswa['nis']);
        $max_rows = max(3, count($surats));
        for ($i = 0; $i < $max_rows; $i++) {
            echo "<tr>";
            if ($i == 0) {
                echo "<td class='c' rowspan='$max_rows'>$no</td>";
                echo "<td rowspan='$max_rows'>" . htmlspecialchars($siswa['nama_siswa']) . "</td>";
                echo "<td class='c' rowspan='$max_rows'>" . htmlspecialchars($kelas) . "</td>";
                echo "<td class='c' rowspan='$max_rows'>" . htmlspecialchars($siswa['nis']) . "</td>";
            }
            echo "<td class='c'>" . ($i + 1) . "</td>";
            if (isset($surats[$i])) {
                echo "<td class='c'>" . tanggal_indonesia($surats[$i]['tanggal_pembuatan_surat']) . "</td>";
                $ket = ($surats[$i]['jenis_surat'] == 'perjanjian_siswa') ? 'Perjanjian Siswa' : 'Perjanjian Ortu';
                echo "<td class='c'>$ket</td>";
            } else {
                echo "<td></td><td></td>";
            }
            echo "</tr>";
        }
        $no++;
    }
}
if (!$found) {
    echo "<tr><td colspan='7' class='c' style='padding:16px; font-style:italic; color:#888;'>Tidak ada data surat perjanjian.</td></tr>";
}
?>
            </tbody>
        </table>

        <!-- TTD: pakai tabel agar rapi & sejajar -->
        <table class="ttd-tbl">
            <!-- Baris 1: Label atas -->
            <tr>
                <td style="width:35%">
                    Diketahui,<br>Wakil Kesiswaan
                </td>
                <td colspan="<?= $bk_count ?>" style="text-align:right">
                    Denpasar, ............................ <?= date('Y') ?>
                </td>
            </tr>
            <!-- Baris 2: Sub-label Guru BK -->
            <tr>
                <td></td>
                <?php if (!empty($bk)) { foreach ($bk as $idx => $nb) { ?>
                <td style="text-align:center">Guru BK <?= $idx + 1 ?></td>
                <?php } } else { ?>
                <td style="text-align:center">Guru BK 1</td>
                <td style="text-align:center">Guru BK 2</td>
                <?php } ?>
            </tr>
            <!-- Baris 3: Spasi tanda tangan -->
            <tr class="ttd-gap"><td></td><?php for($i=0;$i<$bk_count;$i++) echo "<td></td>"; ?></tr>
            <!-- Baris 4: Nama -->
            <tr>
                <td>
                    <span class="ttd-name"><?= htmlspecialchars($waka['nama_pengguna'] ?? '............................') ?></span>
                </td>
                <?php if (!empty($bk)) { foreach ($bk as $nb) { ?>
                <td style="text-align:center"><span class="ttd-name"><?= htmlspecialchars($nb) ?></span></td>
                <?php } } else { ?>
                <td style="text-align:center"><span class="ttd-name">............................</span></td>
                <td style="text-align:center"><span class="ttd-name">............................</span></td>
                <?php } ?>
            </tr>
        </table>

    </div>
</div>
</body>
</html>
