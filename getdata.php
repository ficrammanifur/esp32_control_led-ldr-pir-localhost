<?php
include 'database.php'; // Pastikan file database.php berisi koneksi database yang benar

// Periksa koneksi
if ($conn->connect_error) {
    die(json_encode(array("error" => "Koneksi database gagal: " . $conn->connect_error)));
}

// Mendapatkan data POST dari ESP32
$id = isset($_POST['id']) ? intval($_POST['id']) : null;

// Validasi data
if ($id !== null && $id > 0) {
    // Gunakan prepared statement untuk menghindari SQL Injection
    $stmt = $conn->prepare("SELECT led_status FROM sensor_data WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Periksa hasil query
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response = array("LED STATUS" => $row['led_status']);
        echo json_encode($response);
    } else {
        echo json_encode(array("error" => "Device ID tidak ditemukan!"));
    }

    // Tutup statement
    $stmt->close();
} else {
    echo json_encode(array("error" => "ID tidak valid!"));
}

// Menutup koneksi
$conn->close();
?>
