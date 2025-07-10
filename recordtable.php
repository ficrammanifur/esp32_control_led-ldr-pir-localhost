<?php
// Informasi koneksi database
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "esp32";

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mendapatkan data
$sql = "SELECT * FROM sensor_ldr_pir";
$result = $conn->query($sql);

// Periksa apakah query berhasil
if ($result === false) {
    die("Query error: " . $conn->error);
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>RECORDTABLE Tugas IoT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="icon" href="data:,">
    <style>
        html {
            font-family: Arial, sans-serif;
            display: inline-block;
            text-align: center;
        }

        body {
            margin: 0;
            background-color: #121212; /* Warna latar belakang gelap */
            color: #ffffff; /* Warna teks putih */
        }

        .topnav {
            overflow: hidden;
            background-color: #1e1e1e;
            color: white;
            font-size: 1.2rem;
            padding: 10px 0;
        }

        .content {
            padding: 20px;
            max-width: 900px;
            margin: 0 auto; /* Memusatkan konten */
        }

        .card {
            background-color: #2c2c2c; /* Warna gelap untuk kartu */
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        }

        .card.header {
            background-color: #1e1e1e;
            color: white;
            border-radius: 12px;
            margin-bottom: 20px;
            padding: 10px;
        }

        /* Tabel */
        table {
            width: 100%;
            table-layout: auto; /* Kolom menyesuaikan isi */
            margin: 20px 0;
            border-collapse: collapse;
            background-color: #1e1e1e; /* Latar belakang gelap tabel */
            color: #ffffff; /* Teks putih */
            text-align: center;
        }

        th, td {
            padding: 10px;
            border: 1px solid #444; /* Warna gelap untuk garis tabel */
        }

        th {
            background-color: #333; /* Header tabel lebih gelap */
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #2a2a2a; /* Baris genap dengan warna abu-abu gelap */
        }

        tr:hover {
            background-color: #444; /* Warna saat hover */
        }

        button {
            background-color: #2196F3;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        button:hover {
            background-color: #1976D2;
        }
    </style>
</head>

<body>
<div class="topnav">
    <h3></h3>
</div>

<div class="content">
    <div class="card">
        <div class="card header">
            <h3 style="font-size: 1rem;">DATA SENSOR</h3>
        </div>
        <?php
        // Menampilkan data atau pesan jika tidak ada data
        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>LDR Value</th>
                        <th>LDR Status</th>
                        <th>PIR Status</th>
                        <th>Created At</th>
                    </tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['id'] . "</td>
                        <td>" . $row['ldr_value'] . "</td>
                        <td>" . $row['ldr_status'] . "</td>
                        <td>" . $row['pir_status'] . "</td>
                        <td>" . $row['created_at'] . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Tidak ada data yang ditemukan.</p>";
        }
        ?>
    </div>
</div>

<div class="content">
    <div class="card">
        <div class="card header">
            <h3 style="font-size: 0.7rem;">TERAKHIR DIPERBARUI</h3>
            <button onclick="window.open('recordtable.php', '_blank');">Buka Tabel Rekam</button>
        </div>
    </div>
</div>
</body>
</html>

<?php
// Menutup koneksi
$conn->close();
?>
