<?php
// Konfigurasi database
$servername = "localhost";
$username = "root"; // Ganti sesuai dengan username MySQL Anda
$password = ""; // Ganti dengan password MySQL Anda
$dbname = "esp32";

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data terbaru
$sql = "SELECT * FROM sensor_ldr_pir ORDER BY created_at DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Menampilkan data
    while ($row = $result->fetch_assoc()) {
        $ldr_status = $row['ldr_status'];
        $pir_status = $row['pir_status'];

        // Menentukan status sensor berdasarkan kondisi LDR dan PIR
        $sensor_status = ($ldr_status === "active" && $pir_status === "active") ? "active" : "ACTIVE";

        echo "<p><strong>LDR ☀️:</strong> " . $ldr_status . "</p>";
        echo "<p><strong>PIR 🛡️:</strong> " . $pir_status . "</p>";
        echo "<p><strong>Status Sensor:</strong> " . $sensor_status . "</p>";
    }
} else {
    echo "LDR Status ☀️:Loading....";
    echo "LDR Status ☀️:Loading....";
    echo "INACTIVE.";
}

$conn->close();
?>
