<?php
session_start();
require_once "library/koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: form_login.php");
    exit();
}

// Query untuk mendapatkan data perawatan
$queryPerawatan = "SELECT perawatan.id, kebun.lokasi, perawatan.jenis_perawatan, perawatan.tanggal 
                   FROM perawatan 
                   JOIN kebun ON perawatan.id_kebun = kebun.id";

// Eksekusi query
$perawatanResult = mysqli_query($conn, $queryPerawatan);

// Cek apakah query berhasil
if (!$perawatanResult) {
    // Menangani kesalahan query
    echo "Error: " . mysqli_error($conn);
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Perawatan Kebun</title>
    <!-- Link to Google Fonts for a modern font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <!-- Link to Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
/* General Body Styling */
body {
    font-family: 'Roboto', sans-serif;
    background-color: #f4f7fc;
    margin: 0;
    padding: 0;
    color: #333;
}

h2 {
    text-align: center;
    margin-top: 40px;
    color: #333;
    font-size: 32px;
}

/* Styling the Add Button */
.add-button {
    text-decoration: none;
    color: #fff;
    background-color: #28a745;
    padding: 10px 20px;
    border-radius: 5px;
    margin-top: 20px;
    display: block;
    width: 200px;
    margin: 20px auto; /* Memastikan tombol berada di tengah */
    text-align: center;
    transition: background-color 0.3s;
}

.add-button:hover {
    background-color: #218838; /* Warna hijau lebih gelap */
}

/* Styling the table */
table {
    width: 80%;
    margin: 0 auto;
    margin-top: 30px;
    border-collapse: collapse;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    background-color: #fff;
}

th, td {
    padding: 12px;
    text-align: center;
    border: 1px solid #ddd;
}

th {
    background-color: #007bff;
    color: white;
}

td {
    background-color: #f9f9f9;
}

/* Styling the action buttons */
td a {
    text-decoration: none;
    color: #007bff;
    padding: 5px 10px;
    border-radius: 5px;
    transition: background-color 0.3s, transform 0.2s;
}

td a:hover {
    background-color: #0056b3;
    color: white;
    transform: scale(1.05); /* Efek zoom saat hover */
}

td a:first-child {
    margin-right: 10px;
}

/* Styling for mobile view */
@media (max-width: 768px) {
    table {
        width: 100%;
    }

    h2 {
        font-size: 24px;
    }

    .add-button {
        width: 100%; /* Tombol tambah menggunakan lebar penuh */
        text-align: center;
        margin: 10px 0;
    }

    /* Konten utama */
    .container {
        margin-left: 0; /* Menghilangkan margin kiri untuk tampilan kecil */
    }

    .sidenav {
        width: 100%;
        height: auto;
        position: relative; /* Mengubah posisi menjadi relatif */
    }

    /* Form pencarian responsif */
    .search-container input {
        width: 100%; /* Membuat input pencarian lebih lebar di perangkat kecil */
        margin-right: 0;
        margin-bottom: 10px; /* Menambahkan margin bawah */
    }

    .search-container button {
        width: 100%; /* Tombol mencari menggunakan lebar penuh */
    }
}

/* Styling untuk Form Pencarian */
.search-container {
    text-align: center;
    margin-top: 30px;
}

.search-container form {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%; /* Pastikan form menggunakan lebar penuh */
}

.search-container input {
    padding: 10px;
    width: 250px;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-right: 10px;
    font-size: 16px;
}

.search-container button {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

.search-container button:hover {
    background-color: #0056b3;
    transform: scale(1.05); /* Efek zoom saat hover */
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
    <h2>Daftar Perawatan Kebun</h2>
    
<!-- Formulir pencarian -->
<div class="search-container">
    <form action="search.php" method="GET">
        <input type="text" name="search" placeholder="Cari perawatan..." />
        <button type="submit"><i class="fas fa-search"></i>Cari</button>
    </form>
</div>

<!-- Tombol untuk menambah perawatan (di bawah tombol Cari) -->
<a href="tambah_perawatan.php" class="add-button">Tambah Perawatan</a>


    <!-- Tabel Daftar Perawatan -->
    <table>
        <thead>
            <tr>
                <th>ID Perawatan</th>
                <th>Lokasi Kebun</th>
                <th>Jenis Perawatan</th>
                <th>Tanggal Perawatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($perawatan = mysqli_fetch_assoc($perawatanResult)) { ?>
                <tr>
                    <td><?php echo $perawatan['id']; ?></td>
                    <td><?php echo $perawatan['lokasi']; ?></td>
                    <td><?php echo $perawatan['jenis_perawatan']; ?></td>
                    <td><?php echo $perawatan['tanggal']; ?></td>
                    <td>
    <a href="edit_perawatan.php?id=<?php echo $perawatan['id']; ?>" class="text-success">
        <i class="bi bi-pencil-square"></i> Edit
    </a> |
    <a href="hapus_perawatan.php?id=<?php echo $perawatan['id']; ?>" 
       class="text-danger" 
       onclick="return confirm('Apakah Anda yakin ingin menghapus perawatan ini?')">
        <i class="bi bi-trash"></i> Hapus
    </a>
</td>

                </tr>
            <?php } ?>
            
        </tbody>
    </table>
    </div>
</body>
</html>
