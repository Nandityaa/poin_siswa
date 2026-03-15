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

<style>
.sd-page {
    font-family: 'Inter', sans-serif;
    padding: 24px 0;
    max-width: 1200px;
    margin: 0 auto;
    animation: fadeIn 0.4s ease-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.sd-header { display: none; }
.slim-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 4px;
    margin-bottom: 20px;
    border-bottom: 1px solid #f1f5f9;
}
.slim-greeting h2 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #1a1a1a;
}
.slim-greeting p {
    margin: 0;
    font-size: 12px;
    color: #94a3b8;
}

.pts-pill {
    background: #f8fafc;
    border: 1px solid #f1f5f9;
    padding: 6px 14px;
    border-radius: 50px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}
.pts-pill .lbl { font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; }
.pts-pill .val { font-size: 16px; font-weight: 900; color: #1a1a1a; }

.sd-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 30px;
}

.sd-card {
    background: white;
    border-radius: 20px;
    padding: 28px;
    box-shadow: var(--shadow-lg);
    border: 1px solid rgba(0,0,0,0.02);
}
.sd-card-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--text);
    margin: 0 0 20px 0;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f1f5f9;
}

.sd-list { list-style: none; padding: 0; margin: 0; }
.sd-list li {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px dashed #f1f5f9;
    font-size: 14px;
}
.sd-list li:last-child { border-bottom: none; }
.sd-list .lbl { color: var(--text-light); font-weight: 500; }
.sd-list .val { color: var(--text); font-weight: 600; text-align: right; }

.sd-table-wrap {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    border: 1px solid rgba(0,0,0,0.05);
    overflow: hidden;
}
.sd-table { width: 100%; border-collapse: collapse; }
.sd-table th {
    background: #1a1a1a;
    padding: 16px 20px;
    font-size: 11px;
    font-weight: 600;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    text-align: left;
    border-bottom: none;
}
.sd-table td {
    padding: 18px 24px;
    font-size: 14px;
    color: #1a1a1a;
    border-bottom: 1px solid #f8fafc;
    vertical-align: middle;
}
.sd-table tr:nth-child(even) td { background: #fcfcfc; }
.sd-table tr:last-child td { border-bottom: none; }
.sd-table tr:hover td { background: #f1f5f9 !important; }

.badge-poin {
    padding: 4px 12px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 12px;
    display: inline-block;
}
.poin-b { background: #1a1a1a; color: #fff; }
.poin-s { background: #e2e8f0; color: #1a1a1a; }
.poin-r { background: #f1f5f9; color: #64748b; }


    .sd-page { padding: 8px 12px; background: #fff; }
    
    .slim-header {
        border: none;
        padding: 5px 0 10px 0;
        margin-bottom: 5px;
    }
    .slim-greeting h2 { font-size: 13px; font-weight: 800; }
    .slim-greeting p { font-size: 10px; }

    .pts-pill { padding: 4px 10px; }
    .pts-pill .lbl { font-size: 8px; }
    .pts-pill .val { font-size: 14px; }
</style>

<div class="sd-page">
    <?php if ($data) { ?>
    <!-- SLIM HEADER -->
    <div class="slim-header">
        <div class="slim-greeting">
            <h2>Halo, <?= htmlspecialchars($data['nama_siswa']) ?> 👋</h2>
            <p><?= htmlspecialchars($data['tingkat'] . ' ' . $data['program_keahlian'] . ' ' . $data['rombel']) ?></p>
        </div>
        <div class="pts-pill">
            <i class="fa-solid fa-award" style="font-size: 10px; opacity: 0.5;"></i>
            <span class="lbl">Total Poin</span>
            <span class="val"><?= $total_poin ?></span>
        </div>
    </div>

    <!-- DATA CARDS -->
    <div class="sd-grid">
        <!-- Card Biodata -->
        <div class="sd-card">
            <h3 class="sd-card-title"><i class="fa-solid fa-address-card" style="color:#000;"></i> Informasi Pribadi</h3>
            <ul class="sd-list">
                <li><span class="lbl">Kelas</span><span class="val"><?= htmlspecialchars($data['tingkat'] . ' ' . $data['program_keahlian'] . ' ' . $data['rombel']) ?></span></li>
                <li><span class="lbl">Jenis Kelamin</span><span class="val"><?= htmlspecialchars($data['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan') ?></span></li>
                <li><span class="lbl">Wali Kelas</span><span class="val"><?= htmlspecialchars($data['wali_kelas'] ?? '-') ?></span></li>
                <li><span class="lbl">Status</span><span class="val"><?= htmlspecialchars($data['status'] ?? 'Aktif') ?></span></li>
                <li><span class="lbl">Alamat</span><span class="val"><?= htmlspecialchars($data['alamat'] ?? '-') ?></span></li>
            </ul>
        </div>

        <!-- Card Ortu -->
        <div class="sd-card">
            <h3 class="sd-card-title"><i class="fa-solid fa-people-roof" style="color:#000;"></i> Data Orang Tua</h3>
            <ul class="sd-list">
                <?php if (!empty($data['ayah'])) { ?>
                <li><span class="lbl">Nama Ayah</span><span class="val"><?= htmlspecialchars($data['ayah']) ?></span></li>
                <li><span class="lbl">Telp Ayah</span><span class="val"><?= htmlspecialchars($data['no_telp_ayah'] ?? '-') ?></span></li>
                <?php } ?>
                <?php if (!empty($data['ibu'])) { ?>
                <li><span class="lbl">Nama Ibu</span><span class="val"><?= htmlspecialchars($data['ibu']) ?></span></li>
                <li><span class="lbl">Telp Ibu</span><span class="val"><?= htmlspecialchars($data['no_telp_ibu'] ?? '-') ?></span></li>
                <?php } ?>
                <?php if (!empty($data['wali'])) { ?>
                <li><span class="lbl">Nama Wali</span><span class="val"><?= htmlspecialchars($data['wali']) ?></span></li>
                <li><span class="lbl">Telp Wali</span><span class="val"><?= htmlspecialchars($data['no_telp_wali'] ?? '-') ?></span></li>
                <?php } ?>
                <?php if(empty($data['ayah']) && empty($data['ibu']) && empty($data['wali'])) { ?>
                <div style="text-align:center; padding: 20px; color: #94a3b8; font-style:italic;">Data belum dilengkapi.</div>
                <?php } ?>
            </ul>
        </div>
    </div>

    <!-- RIWAYAT PELANGGARAN -->
    <div class="riwayat-section">
        <div class="sd-card" style="padding:0; overflow:hidden; border:none; margin-top:0px; box-shadow: none; background: transparent;">
            <div style="padding: 12px 0; border-bottom: none;">
                <h3 class="sd-card-title" style="margin:0; padding:0; border:none;"><i class="fa-solid fa-clock-rotate-left" style="color:#000"></i> Riwayat Pelanggaran</h3>
            </div>
            <div class="table-responsive">
            <table class="sd-table">
                <thead>
                    <tr>
                        <th style="width:5%; text-align:center;">No</th>
                        <th style="width:15%">Tanggal</th>
                        <th style="width:35%">Pelanggaran</th>
                        <th style="width:10%; text-align:center;">Poin</th>
                        <th style="width:35%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (mysqli_num_rows($query_pelanggaran) > 0) {
                        while ($row = mysqli_fetch_assoc($query_pelanggaran)) { 
                            $poin = (int)$row['poin'];
                            
                            $chip = '';
                            if ($poin >= 50) $chip = 'poin-b';
                            else if ($poin >= 20) $chip = 'poin-s';
                            else $chip = 'poin-r';
                        ?>
                        <tr>
                            <td style="text-align:center; color: #64748b; font-weight: 500;"><?= $no++ ?></td>
                            <td class="date-cell">
                                <strong><?= date('d M Y', strtotime($row['tanggal'])) ?></strong>
                                <?= date('H:i', strtotime($row['tanggal'])) ?>
                            </td>
                            <td><span class="badge-jenis"><?= htmlspecialchars($row['jenis']) ?></span></td>
                            <td style="text-align:center;"><span class="badge-poin <?= $chip ?>">+<?= $poin ?></span></td>
                            <td style="color: #64748b; font-size: 13px;"><?= htmlspecialchars($row['keterangan'] ?? '-') ?></td>
                        </tr>
                    <?php }
                    } else { ?>
                        <tr>
                            <td colspan="5" style="text-align:center; padding: 48px; color: #94a3b8;">
                                <i class="fa-regular fa-face-smile-beam" style="font-size:48px; color:#10b981; margin-bottom:16px; display:block; opacity:0.8;"></i>
                                <strong style="font-size:16px; color:#475569; display:block; margin-bottom:4px;">Alhamdulillah, belum ada catatan pelanggaran.</strong>
                                Terus pertahankan akhlak dan kedisiplinan yang baik.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

    <?php } else { ?>
        <div style="text-align:center; padding: 100px 20px; color: #94a3b8;">
            <i class="fa-solid fa-triangle-exclamation" style="font-size: 48px; margin-bottom: 20px; opacity:0.3;"></i>
            <h3>Data tidak ditemukan</h3>
            <p>Silakan hubungi administrator jika ini adalah sebuah kesalahan.</p>
        </div>
    <?php } ?>
</div>

<?php
include ROOTPATH . "/layouts/footer.php";
?>
