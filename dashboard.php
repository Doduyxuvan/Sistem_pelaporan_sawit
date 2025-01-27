<?php
session_start();
require_once "library/koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: form_login.php");
    exit();
}

$level = $_SESSION['level'];
$username = $_SESSION['username'];

// Query untuk mendapatkan data kebun dan laporan
$queryKebun = "SELECT * FROM kebun";
$kebun = mysqli_query($conn, $queryKebun);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .sidenav {
            width: 250px;
            background-color: #007bff;
            color: white;
            padding-top: 20px;
            position: fixed;
            height: 100%;
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
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .dashboard-header h2 {
            color: #007bff;
        }

        .card {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-top: 30px;
        }

        .card .card-item {
            background-color: #28a745;
            padding: 20px;
            color: white;
            border-radius: 10px;
            width: 48%;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card .card-item:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card .card-item h3 {
            margin-bottom: 10px;
        }

        .card .card-item p {
            font-size: 18px;
        }

        .card-item img {
            width: 80%;
            margin-top: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .card {
                flex-direction: column;
                gap: 15px;
            }

            .card .card-item {
                width: 100%;
            }

            .main {
                margin-left: 0;
                width: 100%;
            }

            .sidenav {
                display: none;
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

    <div class="main">
        <div class="container">
            <div class="dashboard-header">
                <h2>Selamat Datang di Dashboard</h2>
                <?php
                if ($level == 'admin') {
                    echo "<h3>Welcome, $username</h3>";
                } elseif ($level == 'manager') {
                    echo "<h3>Welcome, $username</h3>";
                } else {
                    echo "<h3>Welcome, $username</h3>";
                }
                ?>
            </div>

            <div class="card">
                <div class="card-item">
                    <h3>Total Kebun</h3>
                    <p><?php echo mysqli_num_rows($kebun); ?></p>
                    <img src="sawit1.jpg" alt="Gambar Sawit"> <!-- Gambar Sawit -->
                </div>
                <div class="card-item">
                    <h3>Jumlah Laporan</h3>
                    <p>
                        <?php 
                        $queryLaporan = "SELECT COUNT(*) as total_laporan FROM laporan";
                        $laporanResult = mysqli_query($conn, $queryLaporan);
                        $laporanData = mysqli_fetch_assoc($laporanResult);
                        echo $laporanData['total_laporan']; 
                        ?>
                    </p>
                    <img src="sawit2.jpg" alt="Gambar Sawit"> <!-- Gambar Sawit -->
                </div>
            </div>

        </div>
    </div>
    
</body>
</html>
