<!-- Menandakan bahwa ini adalah dokumen HTML5 -->
<!DOCTYPE html>

<!-- Tag utama pembungkus seluruh halaman, dengan bahasa Indonesia -->
<html lang="id">

<head>
    <!-- Mengatur karakter huruf agar teks tampil dengan benar -->
    <meta charset="UTF-8" />

    <!-- Judul halaman yang tampil di tab browser -->
    <title>Poin Pelanggaran Siswa</title>

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
    </style>
</head>

<body>
    <!-- Bagian header (bagian atas halaman) -->
    <header>
        <!-- Judul utama halaman -->
        <h1>Aplikasi Poin Pelanggaran Siswa</h1>

        <!-- Navigasi menu utama -->
        <nav>
            <!-- Daftar menu navigasi -->
            <ul>
                <!-- Setiap item menu -->
                <li><a href="/poin_siswa/pages/dashboard.php">Dashboard</a></li>
                <li><a href="/poin_siswa/pages/siswa/list.php">Data Siswa</a></li>
                <li><a href="/poin_siswa/pages/pelanggaran/list.php">Data Pelanggaran</a></li>
                <li><a href="/poin_siswa/pages/pelanggaran/add.php">Entri Pelanggaran</a></li>
                <li><a href="/poin_siswa/pages/surat/list.php">Cetak Surat</a></li>
                <li><a href="/poin_siswa/pages/laporan/list.php">Laporan</a></li>
                <li><a href="/poin_siswa/pages/logout.php">Logout</a></li>
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