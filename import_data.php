<?php
session_start();
require_once "library/koneksi.php";  // Menghubungkan dengan file koneksi database
require_once 'library/check_access.php';  // Pastikan file check_access.php ada di path yang sesuai

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: form_login.php");
    exit();
}

// Cek apakah pengguna memiliki akses
check_access(['admin']);

// Jika form di-submit, lakukan pemrosesan file
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file_csv'])) {
    $fileName = $_FILES['file_csv']['name'];
    $fileTmpName = $_FILES['file_csv']['tmp_name'];
    $fileType = $_FILES['file_csv']['type'];
    $fileSize = $_FILES['file_csv']['size'];

    // Pastikan file adalah CSV
    if ($fileType != 'text/csv' && $fileType != 'application/vnd.ms-excel') {
        echo "Hanya file CSV yang diperbolehkan.";
        exit();
    }

    // Cek ukuran file (misal maksimal 5MB)
    if ($fileSize > 5 * 1024 * 1024) {
        echo "File terlalu besar. Maksimal ukuran file adalah 5MB.";
        exit();
    }

    // Baca isi file CSV
    $file = fopen($fileTmpName, 'r');
    if ($file === false) {
        echo "Gagal membuka file.";
        exit();
    }

    // Melewati header CSV jika ada
    fgetcsv($file); 

    // Mulai memproses data CSV
    while (($data = fgetcsv($file)) !== FALSE) {
        // Asumsi data CSV terdiri dari ID, lokasi, luas, jenis
        $id = mysqli_real_escape_string($conn, $data[0]);
        $lokasi = mysqli_real_escape_string($conn, $data[1]);
        $luas = mysqli_real_escape_string($conn, $data[2]);
        $jenis = mysqli_real_escape_string($conn, $data[3]);

        // Query untuk memasukkan data ke tabel kebun
        $query = "INSERT INTO kebun (id, lokasi, luas, jenis) VALUES ('$id', '$lokasi', '$luas', '$jenis')";
        if (!mysqli_query($conn, $query)) {
            echo "Error: " . mysqli_error($conn);
            exit();
        }
    }

    fclose($file);
    echo "Data kebun berhasil diimpor!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Data Kebun</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            color: #333;
            text-align: center;
        }

        h2 {
            font-size: 28px;
            color: #007bff;
            margin-top: 30px;
        }

        .container {
            width: 80%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .container label {
            font-size: 18px;
            color: #333;
            margin-bottom: 10px;
            display: block;
        }

        .container input[type="file"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            margin-bottom: 20px;
            font-size: 16px;
            background-color: #f9f9f9;
        }

        .container button {
            background-color: #007bff;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }

        .container button:hover {
            background-color: #0056b3;
        }

        .container p {
            margin-top: 20px;
            font-size: 16px;
        }

        .container a {
            color: #007bff;
            text-decoration: none;
        }

        .container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Import Data Kebun Sawit</h2>
        <form action="import_data.php" method="POST" enctype="multipart/form-data">
            <label for="file_csv">Pilih File CSV:</label>
            <input type="file" name="file_csv" id="file_csv" required>

            <button type="submit">Impor Data</button>
        </form>

        <p><a href="kebun.php">Kembali ke Daftar Kebun</a></p>
    </div>

</body>
</html>
