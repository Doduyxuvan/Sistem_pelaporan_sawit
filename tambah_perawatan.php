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
    $jenis_perawatan = $_POST['jenis_perawatan'];
    $tanggal = $_POST['tanggal'];

    // Query untuk menambahkan perawatan ke database
    $query = "INSERT INTO perawatan (id_kebun, jenis_perawatan, tanggal) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iss", $id_kebun, $jenis_perawatan, $tanggal);
    if ($stmt->execute()) {
        echo "<p>Perawatan berhasil ditambahkan.</p>";
        echo "<p><a href='perawatan.php'>Kembali ke Daftar Perawatan</a></p>";
    } else {
        echo "<p>Gagal menambahkan perawatan.</p>";
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
    <title>Tambah Perawatan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        label {
            font-weight: bold;
            margin-bottom: 8px;
            display: inline-block;
            color: #555;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #5cb85c;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 18px;
        }

        button:hover {
            background-color: #4cae4c;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #337ab7;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Tambah Perawatan Kebun</h2>
    <div class="container">
        <form action="tambah_perawatan.php" method="POST">
            <label for="id_kebun">Pilih Kebun:</label>
            <select name="id_kebun" id="id_kebun" required>
                <?php while ($kebun = mysqli_fetch_assoc($kebunResult)) { ?>
                    <option value="<?php echo $kebun['id']; ?>"><?php echo $kebun['lokasi']; ?></option>
                <?php } ?>
            </select>

            <label for="jenis_perawatan">Jenis Perawatan:</label>
            <input type="text" name="jenis_perawatan" id="jenis_perawatan" required>

            <label for="tanggal">Tanggal Perawatan:</label>
            <input type="date" name="tanggal" id="tanggal" required>

            <button type="submit">Tambah Perawatan</button>
        </form>
        <a href="perawatan.php">Kembali ke Daftar Perawatan</a>
    </div>
</body>
</html>
