<?php
session_start();
require_once "library/koneksi.php";
require_once 'library/check_access.php';  // Pastikan file check_access.php ada di path yang sesuai

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: form_login.php");
    exit();
}
check_access(['admin']);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $lokasi = $_POST['lokasi'];
    $luas = $_POST['luas'];
    $jenis = $_POST['jenis'];

    // Query untuk menambahkan kebun ke database
    $query = "INSERT INTO kebun (lokasi, luas, jenis) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $lokasi, $luas, $jenis);
    if ($stmt->execute()) {
        echo "<p>Kebun berhasil ditambahkan.</p>";
        echo "<p><a href='kebun.php'>Kembali ke Daftar Kebun</a></p>";
    } else {
        echo "<p>Gagal menambahkan kebun.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kebun</title>
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
            max-width: 800px;
            margin: 30px auto;
            background-color: white;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        label {
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
            color: #555;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
            display: block;
            text-decoration: none;
            color: #007bff;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        /* Responsif */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Tambah Kebun</h2>

        <form action="tambah_kebun.php" method="POST">
            <label for="lokasi">Lokasi:</label>
            <input type="text" name="lokasi" id="lokasi" required>

            <label for="luas">Luas (Ha):</label>
            <input type="text" name="luas" id="luas" required>

            <label for="jenis">Jenis:</label>
            <input type="text" name="jenis" id="jenis" required>

            <button type="submit">Tambah Kebun</button>
        </form>

        <a href="kebun.php" class="back-link">Kembali ke Daftar Kebun</a>
    </div>

</body>
</html>
