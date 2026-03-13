<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";


$nis = $_POST['nis'];
$no_surat = $_POST['no_surat'];
$pindah_ke = $_POST['pindah_ke'];
$alasan_pindah = $_POST['alasan_pindah'];
$nama_ortu = $_POST['nama_ortu'];
$alamat_ortu = $_POST['alamat'];

// Fetch data siswa
$query_siswa = mysqli_query($conn, "SELECT * FROM siswa
    JOIN ortu_wali USING(id_ortu_wali)
    JOIN kelas USING(id_kelas)
    JOIN tingkat USING(id_tingkat)
    JOIN program_keahlian USING(id_program_keahlian)
    JOIN guru USING(kode_guru) WHERE nis = '$nis'");
$row_siswa = mysqli_fetch_assoc($query_siswa);

// Fetch Waka Kesiswaan
$query_waka = mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = 'Waka Kesiswaan' AND aktif = 'Y'");
$row_waka = mysqli_fetch_assoc($query_waka);
$waka_kesiswaan = $row_waka['nama_pengguna'];

// Fetch Kepala Sekolah
$query_kepsek = mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = 'Kepala Sekolah' AND aktif = 'Y'");
$row_kepsek = mysqli_fetch_assoc($query_kepsek);
$kepala_sekolah = $row_kepsek ? $row_kepsek['nama_pengguna'] : '';

// Insert ke surat_keluar
$id_tingkat = $row_siswa['id_tingkat'];
$tgl_sekarang = date('Y-m-d');
$cek_surat = mysqli_query($conn, "SELECT no_surat FROM surat_keluar WHERE no_surat = '$no_surat'");
if(mysqli_num_rows($cek_surat) == 0) {
    mysqli_query($conn, "INSERT INTO surat_keluar (no_surat, id_tingkat, jenis_surat, nis, tanggal_pembuatan_surat) VALUES ('$no_surat', '$id_tingkat', 'pindah', '$nis', '$tgl_sekarang')");
}

// Menyertakan tampilan header
include ROOTPATH . "/layouts/header.php";
?>

<style>
    button {
        display: flex;
        height: 3em;
        align-items: center;
        justify-content: center;
        background-color: #eeeeee4b;
        border-radius: 3px;
        letter-spacing: 1px;
        transition: all 0.2s linear;
        cursor: pointer;
        border: none;
        background: #fff;
    }

    button > svg {
        margin-right: 5px;
        margin-left: 5px;
        font-size: 20px;
        transition: all 0.4s ease-in;
    }

    button:hover > svg {
        font-size: 1.2em;
        transform: translateX(-5px);
    }

    button:hover {
        box-shadow: 9px 9px 33px #d1d1d1, -9px -9px 33px #ffffff;
        transform: translateY(-2px);
    }

    /* animasi icon printer */
    .printer-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 100%;
    }

    .printer-container {
        height: 50%;
        width: 100%;
        display: flex;
        align-items: flex-end;
        justify-content: center;
    }

    .printer-container svg {
        width: 100%;
        height: auto;
        transform: translateY(4px);
    }

    .printer-page-wrapper {
        width: 100%;
        height: 50%;
        display: flex;
        align-items: flex-start;
        justify-content: center;
    }

    .printer-page {
        width: 70%;
        height: 10px;
        border: 1px solid black;
        background-color: white;
        transform: translateY(0px);
        transition: all 0.3s;
        transform-origin: top;
    }

    .print-btn:hover .printer-page {
        height: 16px;
    }
    /* animasi icon printer */

    .page {
        width: 210mm;
        min-height: 297mm;
        padding: 15mm;
        margin: 0 auto;
        background: white;
        font-family: 'Times New Roman', Times, serif;
        font-size: 11pt;
        line-height: 1.5;
    }

    .header {
        text-align: center;
        margin-bottom: 10px;
    }

    .title {
        text-align: center;
        font-weight: bold;
        font-size: 13pt;
        text-decoration: underline;
        margin-bottom: 5px;
    }

    .subtitle {
        text-align: center;
        font-size: 11pt;
        margin-bottom: 15px;
    }

    .content {
        text-align: justify;
    }

    .indent {
        margin-left: 40px;
        margin-bottom: 10px;
    }

    .form-row {
        display: flex;
        margin-bottom: 3px;
    }

    .label {
        width: 150px;
    }

    .separator {
        width: 20px;
        text-align: center;
    }

    .field {
        flex: 1;
        border-bottom: 1px dotted #000;
        min-height: 18px;
    }

    .statement {
        text-indent: 40px;
        margin-top: 10px;
    }

    .signature-section {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin-top: 30px;
    }

    .sig-block {
        width: 45%;
        text-align: center;
        margin-bottom: 20px;
    }

    .sig-right {
        text-align: center;
    }

    .sig-name {
        margin-top: 60px;
        border-top: 1px solid #000;
        display: inline-block;
        padding-top: 5px;
    }

    .sig-name-plain {
        margin-top: 60px;
        display: inline-block;
        padding-top: 5px;
    }

    @media print {
        @page {
            size: A4;
            margin: 0;
        }
        .no-print {
            display: none !important;
        }
        body {
            margin: 0;
            padding: 0;
        }
        .page {
            padding: 8mm 12mm;
            margin: 0 auto;
            width: 100%;
            min-height: auto;
            box-sizing: border-box;
        }
        nav, header, footer, main {
            all: unset;
        }
    }
</style>

<!-- tombol kembali -->
<center class="no-print">
    <div style="display: flex; justify-content: center; align-items: center; gap: 10px;">
        <form action="cetak.php" method="post" style="margin: 0;">
            <input type="text" name="nis" value="<?= $nis ?>" hidden>
            <button type="submit">
                <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1024 1024">
                    <path d="M874.690416 495.52477c0 11.2973-9.168824 20.466124-20.466124 20.466124l-604.773963 0 188.083679 188.083679c7.992021 7.992021 7.992021 20.947078 0 28.939099-4.001127 3.990894-9.240455 5.996574-14.46955 5.996574-5.239328 0-10.478655-1.995447-14.479783-5.996574l-223.00912-223.00912c-3.837398-3.837398-5.996574-9.046027-5.996574-14.46955 0-5.433756 2.159176-10.632151 5.996574-14.46955l223.019353-223.029586c7.992021-7.992021 20.957311-7.992021 28.949332 0 7.992021 8.002254 7.992021 20.957311 0 28.949332l-188.073446 188.073446 604.753497 0C865.521592 475.058646 874.690416 484.217237 874.690416 495.52477z"></path>
                </svg>
                <span>Kembali</span>
            </button>
        </form>

        <button class="print-btn" onclick="window.print()">
            <span class="printer-wrapper">
                <span class="printer-container">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 92 75">
                        <path stroke-width="5" stroke="black" d="M12 37.5H80C85.2467 37.5 89.5 41.7533 89.5 47V69C89.5 70.933 87.933 72.5 86 72.5H6C4.067 72.5 2.5 70.933 2.5 69V47C2.5 41.7533 6.75329 37.5 12 37.5Z"></path>
                        <mask fill="white" id="path-2-inside-1_30_7">
                            <path d="M12 12C12 5.37258 17.3726 0 24 0H57C70.2548 0 81 10.7452 81 24V29H12V12Z"></path>
                        </mask>
                        <path mask="url(#path-2-inside-1_30_7)" fill="black" d="M7 12C7 2.61116 14.6112 -5 24 -5H57C73.0163 -5 86 7.98374 86 24H76C76 13.5066 67.4934 5 57 5H24C20.134 5 17 8.13401 17 12H7ZM81 29H12H81ZM7 29V12C7 2.61116 14.6112 -5 24 -5V5C20.134 5 17 8.13401 17 12V29H7ZM57 -5C73.0163 -5 86 7.98374 86 24V29H76V24C76 13.5066 67.4934 5 57 5V-5Z"></path>
                        <circle fill="black" r="3" cy="49" cx="78"></circle>
                    </svg>
                </span>
                <span class="printer-page-wrapper"><span class="printer-page"></span></span>
            </span>
            <span>&nbsp;&nbsp;Cetak Lagi</span>
        </button>
    </div>
</center>

<div class="page">
    <!-- Header -->
    <div class="header">
        <img src="/poin_siswa/assets/images/kop.jpg" alt="kepala surat" width="100%">
    </div>

    <div class="title">SURAT KETERANGAN PINDAH SEKOLAH</div>
    <div class="subtitle">No. <?= htmlspecialchars($no_surat) ?>/ADM/SMK-TI-BG/<?= date("Y") ?></div>

    <div class="content">
        <p>Yang bertandatangan di bawah ini, Kepala SMK TI Bali Global Denpasar menerangkan bahwa :</p>

        <div class="indent">
            <div class="form-row">
                <div class="label">Nama Siswa</div>
                <div class="separator">:</div>
                <div class="field"><strong><?php echo $row_siswa['nama_siswa']; ?></strong></div>
            </div>
            <div class="form-row">
                <div class="label">NIS</div>
                <div class="separator">:</div>
                <div class="field"><?php echo $row_siswa['nis']; ?></div>
            </div>
            <div class="form-row">
                <div class="label">Kelas</div>
                <div class="separator">:</div>
                <div class="field"><?php echo $row_siswa['tingkat'] . ' ' . $row_siswa['program_keahlian'] . ' ' . $row_siswa['rombel'] ?></div>
            </div>
            <div class="form-row">
                <div class="label">Program Keahlian</div>
                <div class="separator">:</div>
                <div class="field"><?php echo $row_siswa['deskripsi']; ?></div>
            </div>
            <div class="form-row">
                <div class="label">Nama Orang Tua</div>
                <div class="separator">:</div>
                <div class="field"><?= htmlspecialchars($nama_ortu) ?></div>
            </div>
            <div class="form-row">
                <div class="label">Alamat Orang Tua</div>
                <div class="separator">:</div>
                <div class="field"><?= htmlspecialchars($alamat_ortu) ?></div>
            </div>
        </div>

        <p class="statement">
            Siswa tersebut diatas benar-benar siswa SMK TI Bali Global Denpasar dan atas permintaan orang tua/wali yang bersangkutan,
            bermaksud untuk pindah ke <strong><?= htmlspecialchars($pindah_ke) ?></strong>.
        </p>

        <p style="margin-left: 40px;">
            <strong>Alasan Pindah :</strong> <?= htmlspecialchars($alasan_pindah) ?>
        </p>

        <p class="statement">
            Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
        </p>
    </div>

    <div class="signature-section">
        <div class="sig-block">
            <div>Mengetahui,</div>
            <div>Orang Tua/Wali</div>
            <div class="sig-name-plain"><?= htmlspecialchars($nama_ortu) ?></div>
        </div>

        <div class="sig-block sig-right">
            <div>Denpasar, <?= tanggal_indonesia(date('Y-m-d')) ?></div>
            <div>Kepala Sekolah</div>
            <div class="sig-name"><?= htmlspecialchars($kepala_sekolah) ?></div>
        </div>
    </div>
</div>

<script>
window.onload = function() {
    window.print();
}
</script>

<?php
// Menyertakan bagian footer (penutup halaman)
include ROOTPATH . "/layouts/footer.php";
?>
