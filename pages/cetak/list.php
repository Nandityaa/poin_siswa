<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Menyertakan tampilan header (bagian atas halaman)
include ROOTPATH . "/includes/header.php";
?>

<style>
    .surat-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: bold;
        color: #333;
        background: #fff;
        border: 2px solid #007bff;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        margin-bottom: 10px;
    }
    .surat-btn:hover {
        background: #007bff;
        color: #fff;
        box-shadow: 0 4px 12px rgba(0,123,255,0.3);
        transform: translateY(-2px);
    }
    .surat-btn:hover svg path,
    .surat-btn:hover svg circle {
        stroke: white;
        fill: white;
    }
    .surat-btn svg {
        width: 20px;
        height: 16px;
    }
</style>

<center>
    <h2>Pilih Jenis Surat</h2>

    <button class="surat-btn" onclick="window.location.href='add_perjanjian_siswa.php'">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 92 75"><path stroke-width="5" stroke="black" d="M12 37.5H80C85.2467 37.5 89.5 41.7533 89.5 47V69C89.5 70.933 87.933 72.5 86 72.5H6C4.067 72.5 2.5 70.933 2.5 69V47C2.5 41.7533 6.75329 37.5 12 37.5Z"></path><mask fill="white" id="m1"><path d="M12 12C12 5.37258 17.3726 0 24 0H57C70.2548 0 81 10.7452 81 24V29H12V12Z"></path></mask><path mask="url(#m1)" fill="black" d="M7 12C7 2.61116 14.6112 -5 24 -5H57C73.0163 -5 86 7.98374 86 24H76C76 13.5066 67.4934 5 57 5H24C20.134 5 17 8.13401 17 12H7ZM81 29H12H81ZM7 29V12C7 2.61116 14.6112 -5 24 -5V5C20.134 5 17 8.13401 17 12V29H7ZM57 -5C73.0163 -5 86 7.98374 86 24V29H76V24C76 13.5066 67.4934 5 57 5V-5Z"></path><circle fill="black" r="3" cy="49" cx="78"></circle></svg>
        Cetak Perjanjian Siswa
    </button><br>

    <button class="surat-btn" onclick="window.location.href='add_panggilan_ortu.php'">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 92 75"><path stroke-width="5" stroke="black" d="M12 37.5H80C85.2467 37.5 89.5 41.7533 89.5 47V69C89.5 70.933 87.933 72.5 86 72.5H6C4.067 72.5 2.5 70.933 2.5 69V47C2.5 41.7533 6.75329 37.5 12 37.5Z"></path><mask fill="white" id="m2"><path d="M12 12C12 5.37258 17.3726 0 24 0H57C70.2548 0 81 10.7452 81 24V29H12V12Z"></path></mask><path mask="url(#m2)" fill="black" d="M7 12C7 2.61116 14.6112 -5 24 -5H57C73.0163 -5 86 7.98374 86 24H76C76 13.5066 67.4934 5 57 5H24C20.134 5 17 8.13401 17 12H7ZM81 29H12H81ZM7 29V12C7 2.61116 14.6112 -5 24 -5V5C20.134 5 17 8.13401 17 12V29H7ZM57 -5C73.0163 -5 86 7.98374 86 24V29H76V24C76 13.5066 67.4934 5 57 5V-5Z"></path><circle fill="black" r="3" cy="49" cx="78"></circle></svg>
        Cetak Surat Panggilan Orang Tua
    </button><br>

    <button class="surat-btn" onclick="window.location.href='add_perjanjian_ortu.php'">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 92 75"><path stroke-width="5" stroke="black" d="M12 37.5H80C85.2467 37.5 89.5 41.7533 89.5 47V69C89.5 70.933 87.933 72.5 86 72.5H6C4.067 72.5 2.5 70.933 2.5 69V47C2.5 41.7533 6.75329 37.5 12 37.5Z"></path><mask fill="white" id="m3"><path d="M12 12C12 5.37258 17.3726 0 24 0H57C70.2548 0 81 10.7452 81 24V29H12V12Z"></path></mask><path mask="url(#m3)" fill="black" d="M7 12C7 2.61116 14.6112 -5 24 -5H57C73.0163 -5 86 7.98374 86 24H76C76 13.5066 67.4934 5 57 5H24C20.134 5 17 8.13401 17 12H7ZM81 29H12H81ZM7 29V12C7 2.61116 14.6112 -5 24 -5V5C20.134 5 17 8.13401 17 12V29H7ZM57 -5C73.0163 -5 86 7.98374 86 24V29H76V24C76 13.5066 67.4934 5 57 5V-5Z"></path><circle fill="black" r="3" cy="49" cx="78"></circle></svg>
        Cetak Perjanjian Orang Tua
    </button><br>

    <button class="surat-btn" onclick="window.location.href='add_pindah_sekolah.php'">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 92 75"><path stroke-width="5" stroke="black" d="M12 37.5H80C85.2467 37.5 89.5 41.7533 89.5 47V69C89.5 70.933 87.933 72.5 86 72.5H6C4.067 72.5 2.5 70.933 2.5 69V47C2.5 41.7533 6.75329 37.5 12 37.5Z"></path><mask fill="white" id="m4"><path d="M12 12C12 5.37258 17.3726 0 24 0H57C70.2548 0 81 10.7452 81 24V29H12V12Z"></path></mask><path mask="url(#m4)" fill="black" d="M7 12C7 2.61116 14.6112 -5 24 -5H57C73.0163 -5 86 7.98374 86 24H76C76 13.5066 67.4934 5 57 5H24C20.134 5 17 8.13401 17 12H7ZM81 29H12H81ZM7 29V12C7 2.61116 14.6112 -5 24 -5V5C20.134 5 17 8.13401 17 12V29H7ZM57 -5C73.0163 -5 86 7.98374 86 24V29H76V24C76 13.5066 67.4934 5 57 5V-5Z"></path><circle fill="black" r="3" cy="49" cx="78"></circle></svg>
        Cetak Surat Pindah Sekolah
    </button>

</center>

<?php
// Menyertakan bagian footer (penutup halaman)
include "../../includes/footer.php";
?>
