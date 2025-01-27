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

// Menampilkan dashboard berdasarkan level user
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Link to Google Fonts for modern font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
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

        .dashboard-menu {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .dashboard-menu a {
            text-decoration: none;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s;
        }

        .dashboard-menu a:hover {
            background-color: #0056b3;
        }

        .card {
            display: flex;
            justify-content: space-around;
            gap: 20px;
            margin-top: 30px;
        }

        .card .card-item {
            background-color: #28a745;
            padding: 20px;
            color: white;
            border-radius: 5px;
            width: 30%;
            text-align: center;
        }

        .card .card-item h3 {
            margin-bottom: 10px;
        }

        .card .card-item p {
            font-size: 18px;
        }

        /* Responsif */
        @media (max-width: 768px) {
            .dashboard-menu {
                flex-direction: column;
            }

            .card {
                flex-direction: column;
                gap: 15px;
            }

            .card .card-item {
                width: 80%;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="dashboard-header">
            <h2>Selamat Datang di Dashboard</h2>
            <?php
            if ($level == 'admin') {
                echo "<h3>Welcome Admin, $username</h3>";
            } elseif ($level == 'manager') {
                echo "<h3>Welcome Manager, $username</h3>";
            } else {
                echo "<h3>Welcome Petugas, $username</h3>";
            }
            ?>
        </div>

        <div class="dashboard-menu">
            <a href="kebun.php">Manajemen Kebun</a>
            <a href="laporan.php">Manajemen Laporan</a>
            <a href="logout.php">Logout</a>
        </div>

        <div class="card">
            <div class="card-item">
                <h3>Total Kebun</h3>
                <p><?php echo mysqli_num_rows($kebun); ?></p>
            </div>
            <div class="card-item">
                <h3>Jumlah Laporan</h3>
                <p>
                    <?php 
                    // Query untuk jumlah laporan
                    $queryLaporan = "SELECT COUNT(*) as total_laporan FROM laporan";
                    $laporanResult = mysqli_query($conn, $queryLaporan);
                    $laporanData = mysqli_fetch_assoc($laporanResult);
                    echo $laporanData['total_laporan']; 
                    ?>
                </p>
            </div>
        </div>

    </div>
</body>
</html>
