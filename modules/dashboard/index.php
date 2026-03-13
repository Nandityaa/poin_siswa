<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');
include ROOTPATH . "/config/config.php";

$q_siswa = mysqli_query($conn, "SELECT COUNT(nis) AS total FROM siswa");
$tot_siswa = $q_siswa ? mysqli_fetch_assoc($q_siswa)['total'] : 0;

$q_pelanggaran = mysqli_query($conn, "SELECT COUNT(id_pelanggaran_siswa) AS total FROM pelanggaran_siswa WHERE MONTH(tanggal) = MONTH(CURRENT_DATE())");
$tot_pelanggaran = $q_pelanggaran ? mysqli_fetch_assoc($q_pelanggaran)['total'] : 0;

$q_surat = mysqli_query($conn, "SELECT COUNT(id_surat_keluar) AS total FROM surat_keluar");
$tot_surat = $q_surat ? mysqli_fetch_assoc($q_surat)['total'] : 0;

$q_proses = mysqli_query($conn, "SELECT COUNT(*) AS total FROM perjanjian_siswa WHERE status = 'Masih Proses'");
$tot_proses = $q_proses ? mysqli_fetch_assoc($q_proses)['total'] : 0;

$q_latest = mysqli_query($conn, "SELECT ps.tanggal, s.nama_siswa, jp.jenis, jp.poin, t.tingkat, pk.program_keahlian, k.rombel
    FROM pelanggaran_siswa ps
    JOIN siswa s ON ps.nis = s.nis
    JOIN jenis_pelanggaran jp ON ps.id_jenis_pelanggaran = jp.id_jenis_pelanggaran
    JOIN kelas k ON s.id_kelas = k.id_kelas
    JOIN tingkat t ON k.id_tingkat = t.id_tingkat
    JOIN program_keahlian pk ON k.id_program_keahlian = pk.id_program_keahlian
    ORDER BY ps.tanggal DESC LIMIT 7");

include ROOTPATH . "/layouts/header.php";
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

.db-page {
    font-family: 'Inter', sans-serif;
    padding: 32px;
    max-width: 1200px;
    margin: 0 auto;
}

.db-greeting {
    margin-bottom: 32px;
}
.db-greeting h2 {
    font-size: 28px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 6px 0;
}
.db-greeting p {
    font-size: 15px;
    color: #64748b;
    margin: 0;
}

/* --- STAT CARDS --- */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 36px;
}
.stat-card {
    border-radius: 16px;
    padding: 26px 24px;
    color: white;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 140px;
}
.stat-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 32px rgba(0,0,0,0.18);
}
.stat-card::before {
    content: "";
    position: absolute;
    width: 130px;
    height: 130px;
    border-radius: 50%;
    background: rgba(255,255,255,0.12);
    top: -30px;
    right: -30px;
}
.stat-card::after {
    content: "";
    position: absolute;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255,255,255,0.08);
    bottom: -20px;
    right: 30px;
}
.stat-card-1 { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.stat-card-2 { background: linear-gradient(135deg, #f43f5e, #be123c); }
.stat-card-3 { background: linear-gradient(135deg, #f59e0b, #d97706); }
.stat-card-4 { background: linear-gradient(135deg, #10b981, #059669); }

.stat-label {
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    opacity: 0.85;
}
.stat-number {
    font-size: 48px;
    font-weight: 800;
    line-height: 1;
    margin: 8px 0 4px 0;
}
.stat-sub {
    font-size: 12px;
    opacity: 0.75;
}
.stat-icon {
    position: absolute;
    right: 20px;
    top: 20px;
    opacity: 0.25;
    font-size: 48px;
}

/* --- RECENT TABLE --- */
.db-section-title {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.db-section-title span {
    font-size: 13px;
    font-weight: 500;
    color: #64748b;
    background: #f1f5f9;
    padding: 3px 10px;
    border-radius: 20px;
}
.db-table-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    overflow: hidden;
}
.db-table {
    width: 100%;
    border-collapse: collapse;
}
.db-table th {
    background: #f8fafc;
    color: #475569;
    padding: 14px 20px;
    font-size: 12px;
    font-weight: 700;
    text-align: left;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    border-bottom: 1px solid #e2e8f0;
}
.db-table td {
    padding: 15px 20px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 14px;
    color: #334155;
    vertical-align: middle;
}
.db-table tr:last-child td { border-bottom: none; }
.db-table tr:hover td { background: #f8fafc; }
.student-name-cell { font-weight: 600; color: #0f172a; }
.student-class { font-size: 12px; color: #94a3b8; margin-top: 2px; }
.poin-chip {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    display: inline-block;
}
.poin-low { background: #dcfce7; color: #166534; }
.poin-mid { background: #fef9c3; color: #854d0e; }
.poin-high { background: #fee2e2; color: #991b1b; }
.jenis-text { color: #475569; font-size: 13px; }
.date-cell { font-size: 13px; color: #94a3b8; }
.no-data { text-align:center; padding: 48px; color: #cbd5e1; font-style: italic; }

@media(max-width:900px) {
    .stat-grid { grid-template-columns: repeat(2, 1fr); }
    .db-page { padding: 16px; }
}
@media(max-width:500px) {
    .stat-grid { grid-template-columns: 1fr; }
}
</style>

<div class="db-page">

    <div class="db-greeting">
        <h2>Selamat Datang, <?= htmlspecialchars($_COOKIE['nama'] ?? 'Admin') ?></h2>
        <p><?= date('l, d F Y') ?> &mdash; Sistem Informasi Pelanggaran Siswa</p>
    </div>

    <div class="stat-grid">
        <div class="stat-card stat-card-1">
            <div class="stat-label">Total Siswa Aktif</div>
            <div class="stat-number"><?= $tot_siswa ?></div>
            <div class="stat-sub">Terdaftar dalam sistem</div>
            <div class="stat-icon">&#x1F393;</div>
        </div>
        <div class="stat-card stat-card-2">
            <div class="stat-label">Pelanggaran Bulan Ini</div>
            <div class="stat-number"><?= $tot_pelanggaran ?></div>
            <div class="stat-sub"><?= date('F Y') ?></div>
            <div class="stat-icon">&#x26A0;</div>
        </div>
        <div class="stat-card stat-card-3">
            <div class="stat-label">Total Surat Keluar</div>
            <div class="stat-number"><?= $tot_surat ?></div>
            <div class="stat-sub">Seluruh periode</div>
            <div class="stat-icon">&#x1F4C4;</div>
        </div>
        <div class="stat-card stat-card-4">
            <div class="stat-label">Perjanjian Proses</div>
            <div class="stat-number"><?= $tot_proses ?></div>
            <div class="stat-sub">Menunggu penyelesaian</div>
            <div class="stat-icon">&#x23F3;</div>
        </div>
    </div>

    <h3 class="db-section-title">
        Pelanggaran Terbaru <span>7 Terakhir</span>
    </h3>
    <div class="db-table-card">
        <table class="db-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Siswa</th>
                    <th>Jenis Pelanggaran</th>
                    <th>Poin</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $found = false;
                while ($row = mysqli_fetch_assoc($q_latest)):
                    $found = true;
                    $poin = (int)$row['poin'];
                    $chip = ($poin >= 50) ? 'poin-high' : (($poin >= 20) ? 'poin-mid' : 'poin-low');
                    $kelas = $row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel'];
                ?>
                <tr>
                    <td class="date-cell">
                        <?= date('d M Y', strtotime($row['tanggal'])) ?><br>
                        <small><?= date('H:i', strtotime($row['tanggal'])) ?></small>
                    </td>
                    <td>
                        <div class="student-name-cell"><?= htmlspecialchars($row['nama_siswa']) ?></div>
                        <div class="student-class"><?= htmlspecialchars($kelas) ?></div>
                    </td>
                    <td class="jenis-text"><?= htmlspecialchars($row['jenis']) ?></td>
                    <td><span class="poin-chip <?= $chip ?>"><?= $poin ?> poin</span></td>
                </tr>
                <?php endwhile;
                if (!$found): ?>
                <tr><td colspan="4" class="no-data">Belum ada pelanggaran tercatat.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include ROOTPATH . "/layouts/footer.php"; ?>