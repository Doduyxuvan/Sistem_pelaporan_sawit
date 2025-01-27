<?php
session_start();
require_once "library/koneksi.php";
require_once 'library/check_access.php';  // Pastikan file check_access.php ada di path yang sesuai
// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: form_login.php");
    exit();
}

// Ambil kata kunci pencarian (search term) jika ada
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Query untuk mendapatkan data kebun dengan filter pencarian
$queryKebun = "SELECT * FROM kebun WHERE lokasi LIKE '%$search%' OR jenis LIKE '%$search%'";
$kebunResult = mysqli_query($conn, $queryKebun);

// Periksa apakah query berhasil
if (!$kebunResult) {
    die('Query gagal: ' . mysqli_error($conn));  // Tampilkan pesan error jika query gagal
}
check_access(['admin']);
// Memeriksa apakah user sudah login dan memiliki level yang sesuai
if (!isset($_SESSION['username']) || $_SESSION['level'] === 'manager') {
    // Jika user belum login atau levelnya manager, arahkan ke halaman lain
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kebun</title>
    <!-- Link to Google Fonts for a more modern font -->
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
    overflow-x: hidden; /* Menghindari scroll horizontal */
}

.container {
    width: 90%;
    margin: 20px auto;
    background-color: white;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    color: #333;
}

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

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    z-index: 10; /* Menambahkan z-index pada tabel */
    position: relative; /* Menambahkan position relative */
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

.sidenav {
    width: 250px;
    background-color: #007bff;
    color: white;
    padding-top: 20px;
    position: fixed;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 5; /* Sidebar berada di bawah konten utama */
    box-shadow: 2px 0px 5px rgba(0, 0, 0, 0.1); /* Menambahkan shadow untuk memberikan kedalaman */
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

.main {
    margin-left: 250px; /* Memberikan ruang untuk sidebar */
    padding: 20px;
    width: calc(100% - 250px); /* Mengatur lebar konten utama */
    box-sizing: border-box;
    z-index: 10; /* Menambahkan z-index untuk memastikan konten utama di atas sidebar */
    position: relative; /* Mengatur posisi konten utama */
}


        
    </style>
</head>
<body>

<div class="sidenav d-flex flex-column p-3 bg-primary text-white" style="width: 250px; height: 100vh;">
    <h2 class="text-center">
        <i class="bi bi-list"></i> Menu
    </h2>
    <nav class="nav flex-column">
        <a href="dashboard.php" class="nav-link text-white">
            <i class="bi bi-house-door"></i> Dashboard
        </a>
        <a href="kebun.php" class="nav-link text-white">
            <i class="bi bi-tree"></i> Manajemen Kebun
        </a>
        <a href="laporan.php" class="nav-link text-white">
        <i class="bi-file-earmark-pdf"></i> Manajemen Laporan
        </a>
        <a href="perawatan.php" class="nav-link text-white">
        <i class="bi bi-wrench"></i> Perawatan
        </a>
        <a href="import_data.php" class="nav-link text-white">
        <i class="bi bi-cloud-upload"></i> Import Data Kebun
        </a>
        <a href="export_data.php" class="nav-link text-white">
        <i class="bi bi-cloud-download"></i> Export
        </a>
        <a href="logout.php" class="nav-link text-white">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </nav>
</div>

<div class="main">
<div class="container">
    <h2>Daftar Kebun</h2>

    <!-- Form pencarian -->
    <div class="search-container">
        <form method="POST" action="">
            <input type="text" name="search" placeholder="Cari lokasi atau jenis kebun" value="<?php echo $search; ?>">
            <button type="submit"><i class="fas fa-search"></i> Cari</button>
        </form>
        
    </div>

    <a href="tambah_kebun.php" class="btn-add">Tambah Kebun</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Lokasi</th>
                <th>Luas (Ha)</th>
                <th>Jenis</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Looping hasil query dan tampilkan data kebun
            while ($kebun = mysqli_fetch_assoc($kebunResult)) {
            ?>
                <tr>
                    <td><?php echo $kebun['id']; ?></td>
                    <td><?php echo $kebun['lokasi']; ?></td>
                    <td><?php echo $kebun['luas']; ?></td>
                    <td><?php echo $kebun['jenis']; ?></td>
                    <td>
                        <!-- Mengganti tombol edit dan hapus dengan ikon -->
                        <a href="edit_kebun.php?id=<?php echo $kebun['id']; ?>" class="btn-icon btn-edit" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="hapus_kebun.php?id=<?php echo $kebun['id']; ?>" class="btn-icon btn-delete" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus kebun ini?')">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</div>

</body>
</html>
