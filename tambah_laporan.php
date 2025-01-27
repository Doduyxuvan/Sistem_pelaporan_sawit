<?php
session_start();
require_once "library/koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: form_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_kebun = $_POST['id_kebun'];
    $tanggal = $_POST['tanggal'];
    $hasil = $_POST['hasil'];

    // Query untuk menambahkan laporan ke database
    $query = "INSERT INTO laporan (id_kebun, tanggal, hasil) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isd", $id_kebun, $tanggal, $hasil);
    if ($stmt->execute()) {
        echo "<p>Laporan berhasil ditambahkan.</p>";
        echo "<p><a href='laporan.php'>Kembali ke Daftar Laporan</a></p>";
    } else {
        echo "<p>Gagal menambahkan laporan.</p>";
    }
}

// Query untuk mengambil data kebun (untuk dropdown)
$queryKebun = "SELECT * FROM kebun";
$kebunResult = mysqli_query($conn, $queryKebun);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Laporan</title>
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

        select, input[type="date"], input[type="number"] {
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
        <h2>Tambah Laporan Hasil Panen</h2>

        <form action="tambah_laporan.php" method="POST">
            <label for="id_kebun">Pilih Kebun:</label>
            <select name="id_kebun" id="id_kebun" required>
                <?php while ($kebun = mysqli_fetch_assoc($kebunResult)) { ?>
                    <option value="<?php echo $kebun['id']; ?>"><?php echo $kebun['lokasi']; ?></option>
                <?php } ?>
            </select>

            <label for="tanggal">Tanggal Laporan:</label>
            <input type="date" name="tanggal" id="tanggal" required>

            <label for="hasil">Hasil Panen (Ton):</label>
            <input type="number" step="0.01" name="hasil" id="hasil" required>

            <button type="submit">Tambah Laporan</button>
        </form>

        <a href="laporan.php" class="back-link">Kembali ke Daftar Laporan</a>
    </div>

</body>
</html>
