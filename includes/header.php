<?php
if(!isset($_COOKIE['username'])){
    echo "<script>alert('Anda harus login terlebih dahulu'); window.location.href = '/poin_siswa/login.php';</script>";
    exit;
}
?>

<!-- Menandakan bahwa ini adalah dokumen HTML5 -->
<!DOCTYPE html>

<!-- Tag utama pembungkus seluruh halaman, dengan bahasa Indonesia -->
<html lang="id">

<head>
    <!-- Mengatur karakter huruf agar teks tampil dengan benar -->
    <meta charset="UTF-8" />

    <!-- Judul halaman yang tampil di tab browser -->
    <title>Poin Pelanggaran Siswa SMK TI</title>

    <!-- Bagian untuk menulis style (CSS) -->
    <style>
    nav {
        background: #007bff;
        padding: 10px 0;
    }

    nav ul {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        gap: 20px;
        justify-content: center;
    }

    nav ul li {
        display: inline;
    }

    nav ul li a {
        color: #fff;
        text-decoration: none;
        font-weight: bold;
        padding: 8px 16px;
        border-radius: 4px;
        transition: background 0.2s;
    }

    nav ul li a:hover {
        background: #0056b3;
    }

    .scroll {
        overflow-x: scroll;
        max-height: 400px;
    }
    
    .lebar {
        width: 600px;
    }
    .dropdown {
        position: relative;
        display: inline-block;
    }
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #007bff;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
        margin-top: 7px;
    }
    .dropdown-content a {
        color: white;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }
    .dropdown-content a:hover {
        background-color: #0056b3;
    }
    .dropdown:hover .dropdown-content {
        display: block;
    }
    .logout{
        background-color: #fe4a4a !important;
    }
    .logout:hover{
        background-color: #d61a1aff !important;
    }
    @media print {
        @page {
            margin: 0;
        }
        .no-print {
            display: none !important;
        }
        main {
            all: unset;
        }
    }
    </style>
</head>

<body>
    <!-- Bagian header (bagian atas halaman) -->
    <header class="no-print">
        <!-- Judul utama halaman -->
        <h1>Aplikasi Poin Pelanggaran Siswa</h1>

        <!-- Navigasi menu utama -->
        <nav>
            <!-- Daftar menu navigasi -->
            <ul>
                <!-- Setiap item menu -->
                <li><a href="/poin_siswa/pages/dashboard.php">Dashboard</a></li>
                <!-- dropdown semua data-->
                <li class="dropdown">
                    <a href="#">Data</a>
                    <ul class="dropdown-content">
                        <li><a href="/poin_siswa/pages/guru/list.php">Data Guru</a></li>
                        <li><a href="/poin_siswa/pages/siswa/list.php">Data Siswa</a></li>
                        <li><a href="/poin_siswa/pages/jenis_pelanggaran/list.php">Data Jenis Pelanggaran</a></li>
                    </ul>
                </li>
                <li><a href="/poin_siswa/pages/pelanggaran/add.php">Entri Pelanggaran</a></li>
                <li><a href="/poin_siswa/pages/cetak/list.php">Cetak Surat</a></li>
                <li><a href="/poin_siswa/pages/laporan/list.php">Laporan</a></li>
                <li class="dropdown"> 
                    <a href="#"><?php echo $_COOKIE['nama'];?></a>
                    <ul class="dropdown-content">
                        <li><a href="/poin_siswa/process/profil_process.php?action=edit">Edit Profil</a></li>
                        <li><a class="logout" href="/poin_siswa/logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Bagian utama halaman, tempat isi konten ditampilkan -->
    <main>


    <!-- 
    💡 Penjelasan ringkas struktur HTML-nya:
	•	<!DOCTYPE html> → Menentukan dokumen ini memakai standar HTML5.
	•	<html lang="id"> → Bahasa halaman adalah bahasa Indonesia.
	•	<head> → Bagian kepala, berisi pengaturan halaman (judul, karakter, style).
	•	<body> → Bagian isi tampilan halaman.
	•	<header> → Bagian atas, biasanya berisi judul dan menu navigasi.
	•	<nav> → Area navigasi untuk berpindah ke halaman lain.
	•	<ul> dan <li> → Menyusun daftar menu.
	•	<main> → Area utama yang nanti berisi konten dari halaman lain. 
    -->