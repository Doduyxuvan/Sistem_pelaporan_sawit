<?php
session_start();
require_once "library/koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: form_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Query untuk mendapatkan data laporan berdasarkan ID
    $query = "SELECT * FROM laporan WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $laporan = $result->fetch_assoc();
}

// Query untuk mengambil data kebun (untuk dropdown)
$queryKebun = "SELECT * FROM kebun";
$kebunResult = mysqli_query($conn, $queryKebun);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kebun = $_POST['id_kebun'];
    $tanggal = $_POST['tanggal'];
    $hasil = $_POST['hasil'];

    // Query untuk memperbarui data laporan
    $query = "UPDATE laporan SET id_kebun = ?, tanggal = ?, hasil = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isdi", $id_kebun, $tanggal, $hasil, $id);
    if ($stmt->execute()) {
        echo "<p>Laporan berhasil diperbarui.</p>";
        echo "<p><a href='laporan.php'>Kembali ke Daftar Laporan</a></p>";
    } else {
        echo "<p>Gagal memperbarui laporan.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Laporan</title>
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
    <h2>Edit Laporan Hasil Panen</h2>
    <div class="container">
        <form action="edit_laporan.php?id=<?php echo $laporan['id']; ?>" method="POST">
            <label for="id_kebun">Pilih Kebun:</label>
            <select name="id_kebun" id="id_kebun" required>
                <?php while ($kebun = mysqli_fetch_assoc($kebunResult)) { ?>
                    <option value="<?php echo $kebun['id']; ?>" <?php if ($kebun['id'] == $laporan['id_kebun']) echo 'selected'; ?>>
                        <?php echo $kebun['lokasi']; ?>
                    </option>
                <?php } ?>
            </select>

            <label for="tanggal">Tanggal Laporan:</label>
            <input type="date" name="tanggal" id="tanggal" value="<?php echo $laporan['tanggal']; ?>" required>

            <label for="hasil">Hasil Panen (Ton):</label>
            <input type="number" step="0.01" name="hasil" id="hasil" value="<?php echo $laporan['hasil']; ?>" required>

            <button type="submit">Perbarui Laporan</button>
        </form>
        <a href="laporan.php">Kembali ke Daftar Laporan</a>
    </div>
</body>
</html>
