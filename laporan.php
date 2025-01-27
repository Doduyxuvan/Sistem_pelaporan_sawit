<?php
session_start();
require_once "library/koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: form_login.php");
    exit();
}

// Ambil kata kunci pencarian (search term) jika ada
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Query untuk mendapatkan data laporan dengan filter pencarian
$queryLaporan = "SELECT laporan.id, kebun.lokasi, laporan.tanggal, laporan.hasil 
                 FROM laporan 
                 JOIN kebun ON laporan.id_kebun = kebun.id 
                 WHERE kebun.lokasi LIKE '%$search%' OR laporan.hasil LIKE '%$search%'";
$laporanResult = mysqli_query($conn, $queryLaporan);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Laporan</title>
    <!-- Link to Google Fonts for a modern font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <!-- Link to Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
body {
    font-family: 'Roboto', sans-serif;
    background-color: #f4f7fc;
    margin: 0;
    padding: 0;
    display: flex;
}

/* Sidebar */
.sidenav {
    width: 250px;
    background-color: #007bff;
    color: white;
    padding-top: 20px;
    position: fixed;
    height: 100%;
    z-index: 10; /* Pastikan sidebar tidak menutupi konten utama */
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.sidenav a {
    padding: 10px 15px;
    display: block;
    color: white;
    text-decoration: none;
    font-weight: 500;
}

.sidenav a:hover {
    background-color: #0056b3;
}

/* Konten utama */
.container {
    margin-left: 250px; /* Memberikan ruang di kiri untuk sidebar */
    flex: 1; /* Konten mengambil sisa ruang */
    background-color: white;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-top: 0;
}

/* Heading */
h2 {
    text-align: center;
    color: #333;
}

/* Style untuk form pencarian */
.search-container {
    margin-bottom: 20px;
    text-align: center;
}

.search-container input {
    padding: 10px;
    width: 250px;
    border-radius: 5px;
    border: 1px solid #ddd;
}

.search-container button {
    padding: 10px;
    border: none;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.search-container button:hover {
    background-color: #0056b3;
}

/* Tabel */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 12px;
    text-align: center;
}

th {
    background-color: #007bff;
    color: white;
}

td {
    background-color: #f9f9f9;
}

/* Link */
a {
    text-decoration: none;
    color: #007bff;
    padding: 5px 10px;
    border: 1px solid #007bff;
    border-radius: 5px;
    transition: all 0.3s;
}

a:hover {
    background-color: #007bff;
    color: white;
}

/* Ganti tombol edit dan hapus menjadi ikon */
.btn-icon {
    font-size: 20px;
    padding: 5px;
    cursor: pointer;
}

.btn-edit {
    color: #28a745;
}

.btn-delete {
    color: #dc3545;
}

.btn-icon:hover {
    opacity: 0.7;
}

/* Tombol tambah */
.btn-add {
    display: inline-block;
    padding: 10px 20px;
    background-color: #28a745;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-bottom: 20px;
    transition: background-color 0.3s;
}

.btn-add:hover {
    background-color: #218838;
}

/* Responsive design */
@media (max-width: 768px) {
    table {
        width: 100%;
        font-size: 14px;
    }

    th, td {
        padding: 10px;
    }
}

    </style>
</head>
<body>
<div class="sidenav d-flex flex-column p-3 bg-primary text-white" style="width: 250px; height: 100vh;">
        <h2 class="text-center">
        <i class="bi bi-list"></i> Menu
    </h2>
    <nav class="nav flex-column">
    <?php
    // Cek apakah pengguna sudah login dan memiliki level yang sesuai
    if (isset($_SESSION['level'])) {
        // Hanya tampilkan link dashboard untuk admin
        if ($_SESSION['level'] === 'admin' || $_SESSION['level'] === 'manager') {
            echo '<a href="dashboard.php" class="nav-link text-white">
                    <i class="bi bi-house-door"></i> Dashboard
                  </a>';
        }
        // Hanya tampilkan link Manajemen Kebun untuk admin atau level lainnya yang sesuai
        if ($_SESSION['level'] === 'admin') {
            echo '<a href="kebun.php" class="nav-link text-white">
                    <i class="bi bi-tree"></i> Manajemen Kebun
                  </a>';
        }
        // Link untuk Manajemen Laporan, bisa diakses oleh admin dan manager
        if ($_SESSION['level'] === 'admin' || $_SESSION['level'] === 'manager') {
            echo '<a href="laporan.php" class="nav-link text-white">
                    <i class="bi bi-file-earmark-pdf"></i> Manajemen Laporan
                  </a>';
        }
        // Link untuk Perawatan bisa diakses oleh admin dan manager
        if ($_SESSION['level'] === 'admin' || $_SESSION['level'] === 'manager') {
            echo '<a href="perawatan.php" class="nav-link text-white">
                    <i class="bi bi-wrench"></i> Perawatan
                  </a>';
        }
        // Link untuk Import Data, hanya untuk admin
        if ($_SESSION['level'] === 'admin') {
            echo '<a href="import_data.php" class="nav-link text-white">
                    <i class="bi bi-cloud-upload"></i> Import Data Kebun
                  </a>';
        }
        // Link untuk Export Data bisa diakses oleh admin dan manager
        if ($_SESSION['level'] === 'admin') {
            echo '<a href="export_data.php" class="nav-link text-white">
                    <i class="bi bi-cloud-download"></i> Export
                  </a>';
        }
    }
    ?>
    <a href="logout.php" class="nav-link text-white">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>
</nav>

</div>
    <div class="container">
        <h2>Daftar Laporan Kebun</h2>

        <!-- Form pencarian -->
        <div class="search-container">
            <form method="POST" action="">
                <input type="text" name="search" placeholder="Cari lokasi atau hasil laporan" value="<?php echo $search; ?>">
                <button type="submit"><i class="fas fa-search"></i> Cari</button>
            </form>
        </div>

        <a href="tambah_laporan.php" class="btn-add">Tambah Laporan</a>
        
        <table>
            <thead>
                <tr>
                    <th>ID Laporan</th>
                    <th>Lokasi Kebun</th>
                    <th>Tanggal</th>
                    <th>Hasil (Ton)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Looping hasil query dan tampilkan data laporan
                while ($laporan = mysqli_fetch_assoc($laporanResult)) {
                ?>
                    <tr>
                        <td><?php echo $laporan['id']; ?></td>
                        <td><?php echo $laporan['lokasi']; ?></td>
                        <td><?php echo $laporan['tanggal']; ?></td>
                        <td><?php echo $laporan['hasil']; ?></td>
                        <td>
                            <!-- Mengganti tombol edit dan hapus dengan ikon -->
                            <a href="edit_laporan.php?id=<?php echo $laporan['id']; ?>" class="btn-icon btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="hapus_laporan.php?id=<?php echo $laporan['id']; ?>" class="btn-icon btn-delete" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
