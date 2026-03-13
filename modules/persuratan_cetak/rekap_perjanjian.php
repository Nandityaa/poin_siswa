<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');
include ROOTPATH . "/config/config.php";

$query = mysqli_query($conn, "SELECT sk.jenis_surat, sk.tanggal_pembuatan_surat, s.nis, s.nama_siswa, t.tingkat, pk.program_keahlian, k.rombel
    FROM surat_keluar sk
    JOIN siswa s ON sk.nis = s.nis
    JOIN kelas k ON s.id_kelas = k.id_kelas
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat
    JOIN program_keahlian pk ON k.id_program_keahlian = pk.id_program_keahlian
    WHERE sk.jenis_surat IN ('perjanjian_siswa', 'perjanjian_ortu')
    ORDER BY sk.tanggal_pembuatan_surat DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Rekap Surat Perjanjian</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Times New Roman', Times, serif;
            background: #e9ecef;
            color: #000;
        }

        /* === WRAPPER SCREEN === */
        .screen-wrapper {
            max-width: 900px;
            margin: 30px auto;
        }

        /* === TOP BUTTONS (no print) === */
        .btn-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 16px;
        }
        .btn-kembali, .btn-cetak {
            padding: 10px 22px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            font-family: Arial, sans-serif;
            transition: all 0.2s;
        }
        .btn-kembali { background: #e2e8f0; color: #334155; }
        .btn-kembali:hover { background: #cbd5e1; }
        .btn-cetak { background: #007bff; color: white; }
        .btn-cetak:hover { background: #0056b3; }

        /* === DOCUMENT PAGE === */
        .dokumen {
            background: #fff;
            padding: 30px 36px 40px 36px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.12);
        }

        /* === KOP SURAT === */
        .kop {
            width: 100%;
            border-bottom: 4px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* === JUDUL REKAP === */
        .judul-rekap {
            text-align: center;
            margin-bottom: 20px;
        }
        .judul-rekap h2 {
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: underline;
            margin-bottom: 4px;
        }
        .judul-rekap p {
            font-size: 13px;
        }

        /* === TABLE === */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-top: 10px;
        }
        thead th {
            background: #1a2a3a;
            color: #fff;
            padding: 9px 10px;
            text-align: center;
            border: 1px solid #000;
            font-size: 12px;
        }
        tbody td {
            padding: 8px 10px;
            border: 1px solid #333;
            vertical-align: middle;
        }
        tbody tr:nth-child(even) td {
            background: #f8f9fa;
        }
        .td-no { text-align: center; width: 4%; }
        .td-tgl { text-align: center; width: 16%; }
        .td-jenis { text-align: center; width: 20%; }
        .td-nis { text-align: center; width: 10%; }
        .td-nama { text-align: left; }
        .td-kelas { text-align: center; width: 15%; }

        .badge-siswa {
            background: #dbeafe;
            color: #1d4ed8;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-ortu {
            background: #fce7f3;
            color: #9d174d;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }

        /* === TANDA TANGAN === */
        .ttd-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 40px;
        }
        .ttd-box {
            text-align: center;
            width: 220px;
        }
        .ttd-box p { font-size: 13px; margin-bottom: 60px; }
        .ttd-box .garis { border-top: 1px solid #000; padding-top: 4px; font-size: 13px; font-weight: 700; }

        /* === PRINT === */
        @page {
            size: A4;
            margin: 0;
        }
        @media print {
            body { background: white; }
            .no-print { display: none !important; }
            .screen-wrapper { margin: 0; max-width: 100%; }
            .dokumen { box-shadow: none; padding: 12mm 16mm 16mm 16mm; }
        }
    </style>
</head>
<body>
<div class="screen-wrapper">

    <!-- TOMBOL AKSI (tidak ikut dicetak) -->
    <div class="btn-bar no-print">
        <button class="btn-kembali" onclick="window.location.href='index.php'">Kembali</button>
        <button class="btn-cetak" onclick="window.print()">Cetak Dokumen</button>
    </div>

    <!-- DOKUMEN CETAK -->
    <div class="dokumen">

        <!-- KOP SURAT -->
        <div class="kop">
            <img src="/poin_siswa/assets/images/kop.jpg" alt="Kop Surat Sekolah">
        </div>

        <!-- JUDUL -->
        <div class="judul-rekap">
            <h2>Rekapitulasi Surat Perjanjian Siswa &amp; Orang Tua</h2>
            <p>Tahun Pelajaran <?= date('Y') ?>/<?= date('Y') + 1 ?></p>
        </div>

        <!-- TABEL DATA -->
        <table>
            <thead>
                <tr>
                    <th class="td-no">No</th>
                    <th class="td-tgl">Tanggal Dicetak</th>
                    <th class="td-jenis">Jenis Perjanjian</th>
                    <th class="td-nis">NIS</th>
                    <th class="td-nama">Nama Siswa</th>
                    <th class="td-kelas">Kelas</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($query && mysqli_num_rows($query) > 0) {
                    while ($row = mysqli_fetch_assoc($query)) {
                        $jenis_label = ($row['jenis_surat'] == 'perjanjian_siswa') ? 'Perjanjian Siswa' : 'Perjanjian Orang Tua';
                        $badge_class = ($row['jenis_surat'] == 'perjanjian_siswa') ? 'badge-siswa' : 'badge-ortu';
                        $kelas = $row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel'];
                ?>
                <tr>
                    <td class="td-no"><?= $no++ ?></td>
                    <td class="td-tgl"><?= tanggal_indonesia($row['tanggal_pembuatan_surat']) ?></td>
                    <td class="td-jenis"><span class="<?= $badge_class ?>"><?= $jenis_label ?></span></td>
                    <td class="td-nis"><?= htmlspecialchars($row['nis']) ?></td>
                    <td class="td-nama"><?= htmlspecialchars($row['nama_siswa']) ?></td>
                    <td class="td-kelas"><?= htmlspecialchars($kelas) ?></td>
                </tr>
                <?php
                    }
                } else { ?>
                <tr><td colspan="6" style="text-align:center; padding:20px; color:#6c757d;">Tidak ada data surat perjanjian.</td></tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- TANDA TANGAN -->
        <div class="ttd-section">
            <div class="ttd-box">
                <p><?= tanggal_indonesia(date('Y-m-d')) ?></p>
                <p>Waka Kesiswaan,</p>
                <div class="garis">
                    <?php
                    $waka = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = 'Waka Kesiswaan' AND aktif = 'Y' LIMIT 1"));
                    echo htmlspecialchars($waka['nama_pengguna'] ?? '____________________');
                    ?>
                </div>
            </div>
        </div>

    </div><!-- /dokumen -->
</div><!-- /screen-wrapper -->
</body>
</html>
