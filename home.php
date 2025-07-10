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

// Update LED status jika form di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['led_status'])) {
    $led_status = $_POST['led_status'];
    $update_sql = "UPDATE sensor_data SET led_status='$led_status' WHERE id='esp32_01'";
    if (!$conn->query($update_sql)) {
        die("Gagal mengupdate LED status: " . $conn->error);
    }
}

// Logika untuk mengupdate status LED
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['led_status']) && $_POST['led_status'] === 'ON') {
        // Aktifkan LED
        $update_query = "UPDATE sensor_data SET led_status='ON' WHERE id='esp32_01'";
    } else {
        // Matikan LED
        $update_query = "UPDATE sensor_data SET led_status='OFF' WHERE id='esp32_01'";
    }

    if ($conn->query($update_query) === TRUE) {
        echo "Status LED berhasil diperbarui!";
    } else {
        echo "Gagal memperbarui status LED: " . $conn->error;
    }
}

// Logika untuk ESP32 membaca status LED
if (isset($_GET['get_led_status'])) {
    $led_status_query = "SELECT led_status FROM sensor_data WHERE id='esp32_01'";
    $result = $conn->query($led_status_query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo $row['led_status']; // Kirim status LED ke ESP32
    } else {
        echo "OFF"; // Default jika data tidak ditemukan
    }
}

// Query untuk mendapatkan data sensor
$sql = "SELECT * FROM sensor_ldr_pir";
$result = $conn->query($sql);

// Periksa apakah query berhasil
if ($result === false) {
    die("Query error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME Tugas IoT</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212; /* Tema gelap */
            color: #ffffff; /* Teks terang */
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }

        h1 {
            margin-bottom: 20px;
        }

        .content {
            width: 100%;
            max-width: 600px;
            padding: 20px;
            border-radius: 8px;
        }

        .card {
            background-color: #2c2c2c; /* Warna gelap untuk kartu */
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .card.header {
            background-color: #1e1e1e; 
            color: white;
            border-radius: 12px;
            margin-bottom: 20px;
            padding: 10px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        button {
            margin-top: 10px;
            padding: 12px 24px;
            font-size: 1em;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #1976D2;
        }
    </style>
    <script>
        function fetchSensorData() {
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "fetch_data.php", true);
            xhr.onload = function () {
                if (this.status === 200) {
                    document.getElementById("sensorData").innerHTML = this.responseText;
                }
            };
            xhr.send();
        }

        setInterval(fetchSensorData, 2000);
        window.onload = fetchSensorData;
    </script>
</head>
<body>
    <h1>TUGAS IoT</h1>
    <div class="content">
        <!-- Monitoring Data -->
        <div class="card">
            <div class="card header">
                <h3 style="font-size: 1rem;">MONITORING</h3>
            </div>
            <div id="sensorData">
                <p>LDR Status ☀️: <span id="ldrValue">Loading...</span></p>
                <p>PIR Status 🛡️: <span id="pirValue">Loading...</span></p>
                <p>Status Sensor: <span id="sensorStatus">Loading...</span></p>
        </div>

        <!-- Kontrol LED -->
        <div class="card">
            <div class="card header">
                <h3 style="font-size: 1rem;">CONTROLING</h3>
            </div>
            <p>LED 💡</p>
            <form method="post" action="">
                <label class="switch">
                    <input type="checkbox" name="led_status" value="ON" onchange="this.form.submit()" <?php
                        $led_status_query = "SELECT led_status FROM sensor_data WHERE id='esp32_01'";
                        $led_status_result = $conn->query($led_status_query);
                        if ($led_status_result && $led_status_result->num_rows > 0) {
                            $row = $led_status_result->fetch_assoc();
                            if ($row['led_status'] === 'ON') {
                                echo 'checked';
                            }
                        }
                    ?>>
                    <span class="slider"></span>
                </label>
            </form>
        </div>

        <!-- Informasi Tambahan -->
        <div class="card">
            <h3>Other Information</h3>
            <p>Last Time Received Data From ESP32: </p>
            <button onclick="window.open('recordtable.php', '_blank');">Open Record Table</button>
        </div>
    </div>
</body>
</html>

<?php
