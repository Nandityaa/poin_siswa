<?php
// Menentukan lokasi root folder proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/poin_siswa');

// Menghubungkan ke file konfigurasi (koneksi database)
include ROOTPATH . "/config/config.php";

// Menyertakan tampilan header (bagian atas halaman)
include ROOTPATH . "/includes/header.php";

// Mengambil semua data kelas
$result = mysqli_query($conn, "SELECT id_kelas, tingkat, program_keahlian, rombel, nama_pengguna FROM kelas JOIN tingkat using(id_tingkat) JOIN program_keahlian using(id_program_keahlian) JOIN guru using(kode_guru) ORDER BY id_tingkat DESC, id_program_keahlian ASC, rombel ASC");
?>

<!-- Bagian tampilan daftar kelas -->
<center>
    <h2>Data Kelas</h2>

    <!-- Tombol untuk menuju halaman tambah kelas -->
    <button class="btn-primary"><a href="add.php">+ Tambah Data</a></button><br><br>

    <div class="scroll">
        <!-- Membuat tabel untuk menampilkan daftar kelas -->
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kelas</th>
                    <th>Wali Kelas</th>
                    <th>Guru BK</th>
                    <th colspan="2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Variabel untuk nomor urut
                $no = 1;

                // Menampilkan semua data kelas dari hasil query
                while ($row = mysqli_fetch_assoc($result)){ ?>
                <tr>
                    <!-- Menampilkan nomor urut -->
                    <td><?= $no++?></td>
                    <!-- Menampilkan data kelas -->
                    <td><?= htmlspecialchars($row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel'] ) ?></td>
                    <td><?= htmlspecialchars($row['nama_pengguna']) ?></td>
                    <td>
                        <?php
                        if( $row['tingkat'] == 'XII'){
                            $row2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = 'Guru BK XII'"));
                            echo htmlspecialchars($row2['nama_pengguna']);
                        }else if( $row['tingkat'] == 'XI'){
                            $row2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = 'Guru BK XI'"));
                            echo htmlspecialchars($row2['nama_pengguna']);
                        }else{
                            $row2 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_pengguna FROM guru WHERE jabatan = 'Guru BK X'"));
                            echo htmlspecialchars($row2['nama_pengguna']);
                        }
                        ?>
                    </td>
                    <td>
                        <button class="btn-warning"><a href="edit.php?id=<?= $row['id_kelas'] ?>">Edit</a></button>
                    </td>
                    <td>
                        <form action="/poin_siswa/process/kelas_process.php" method="post"
                            onsubmit="return confirm('Ingin Menghapus data <?= $row['tingkat'] . ' ' . $row['program_keahlian'] . ' ' . $row['rombel'] ?>?')">
                            <!-- Kirim id dan action ke file proses -->
                            <input type="hidden" name="id" value="<?= $row['id_kelas'] ?>">
                            <input type="hidden" name="action" value="delete">
                            <button class="btn-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</center>

<?php
// Menyertakan bagian footer (penutup halaman)
include "../../includes/footer.php";
?>
