<h1 align="center">
# 🌐 ESP32 Web Monitoring & Control System
</h1>
Sistem ini merupakan aplikasi IoT berbasis web untuk memonitor **sensor LDR** dan **PIR**, serta mengontrol **LED** melalui ESP32 dan interface web. Data dikirim dan disimpan ke dalam MySQL menggunakan koneksi WiFi dan komunikasi HTTP.

<p align="center">
  <img src="https://img.shields.io/badge/last%20commit-today-brightgreen" />
  <img src="https://img.shields.io/badge/language-PHP%20%7C%20C%2B%2B%20%7C%20JavaScript-blue" />
  <img src="https://img.shields.io/badge/platform-ESP32-informational" />
  <img src="https://img.shields.io/badge/database-MySQL-orange" />
  <img src="https://img.shields.io/badge/protocol-HTTP-green" />
  <img src="https://img.shields.io/badge/sensors-LDR%20%7C%20PIR-yellow" />
  <img src="https://img.shields.io/badge/server-Apache%2FXAMPP-red" />
  <img src="https://img.shields.io/badge/interface-Web%20UI-purple" />
</p>
---

## 📁 Struktur Proyek

```
ESP32_MySQL_Database/
│
├── home.php               # Halaman utama (UI monitoring + kontrol LED)
├── getdata.php            # Mengambil status LED berdasarkan ID (untuk ESP32)
├── fetch_data.php         # Mengambil data sensor terbaru untuk tampil di UI
├── recordtable.php        # Menampilkan riwayat data sensor dalam bentuk tabel
├── updateSensordata.php   # Update data sensor dari ESP32
├── database.php           # File koneksi database
└── README.md              # Dokumentasi proyek ini
```

---

## ⚙️ Teknologi Digunakan

- **ESP32 Dev Board**
- **Sensor:**
  - LDR (Light Dependent Resistor)
  - PIR (Passive Infrared)
- **LED (Kontrol via Web)**
- **MySQL (Database)**
- **PHP (Backend & API)**
- **HTML/CSS/JavaScript (Frontend UI)**
- **Arduino IDE (ESP32 firmware)**

---

## 🔄 Alur Kerja

### 1. ESP32

- Membaca nilai **LDR** & **PIR**.
- Mengirim data sensor via HTTP POST ke `updateSensordata.php`.
- Mengambil **status LED** dari `getdata.php` dan mengontrol LED sesuai respon server.

### 2. Web Server (PHP)

- **home.php**
  - Menampilkan UI untuk memonitor sensor dan kontrol LED.
  - Menyimpan perubahan status LED ke DB.

- **fetch_data.php**
  - Diakses via AJAX setiap 2 detik untuk update data monitoring secara real-time.

- **getdata.php**
  - Mengembalikan status LED (JSON) untuk ESP32.

- **recordtable.php**
  - Menampilkan seluruh data sensor yang tercatat dalam bentuk tabel.

---

## 🛠️ Database

### 📌 Tabel `sensor_data`

```sql
CREATE TABLE `sensor_data` (
  `id` VARCHAR(255) PRIMARY KEY,
  `ldr_value` INT NOT NULL,
  `ldr_status` VARCHAR(10) NOT NULL,
  `pir_status` VARCHAR(20) NOT NULL,
  `led_status` VARCHAR(255) NOT NULL
);
```

### 🔰 Contoh Data Awal

```sql
INSERT INTO `sensor_data` (`id`, `ldr_value`, `ldr_status`, `pir_status`, `led_status`)
VALUES ('esp32_01', 4095, 'GELAP', 'NO_MOTION', 'OFF');
```

### 📌 Tabel `sensor_ldr_pir`

Digunakan untuk mencatat histori data sensor yang dikirim dari ESP32. Struktur tidak dicontohkan penuh di sini, namun memiliki field `ldr_value`, `ldr_status`, `pir_status`, dan `created_at`.

---

## 🔧 Konfigurasi ESP32

### Informasi WiFi:

```cpp
const char* ssid = "FRISS";
const char* password = "cambeshuf6";
```

### Endpoint HTTP:

- **Kirim data sensor ke:**
  ```
  http://<server-ip>/ESP32_MySQL_Database/Final/updateSensordata.php
  ```

- **Ambil status LED dari:**
  ```
  http://<server-ip>/ESP32_MySQL_Database/Test/getdata.php
  ```

---

## 🌐 Tampilan Web

### 🎛️ Monitoring

Menampilkan status:
- **LDR** (☀️ Terang/Gelap/Sedang)
- **PIR** (🛡️ Terdeteksi/Tidak Terdeteksi)
- **Status Sensor Gabungan**

### 💡 Kontrol LED

- Switch toggle untuk menyalakan/mematikan LED dari browser.

### 📋 Riwayat

- Tabel data histori sensor lengkap (`recordtable.php`).

---

## 🚀 Cara Instalasi

### 1. Persiapan Server

1. Install **XAMPP** atau server lokal lain yang mendukung PHP + MySQL
2. Jalankan Apache dan MySQL
3. Buat database baru di phpMyAdmin
4. Import struktur tabel yang diperlukan

### 2. Konfigurasi Database

Buat file `database.php`:

```php
<?php
\$servername = "localhost";
\$username = "root";
\$password = "";
\$dbname = "esp32_database";

\$conn = new mysqli(\$servername, \$username, \$password, \$dbname);

if (\$conn->connect_error) {
    die("Connection failed: " . \$conn->connect_error);
}
?>
```

### 3. Upload Kode ESP32

1. Buka Arduino IDE
2. Install library ESP32 dan HTTPClient
3. Sesuaikan konfigurasi WiFi dan server IP
4. Upload kode ke ESP32

### 4. Akses Web Interface

Buka browser dan akses:
```
http://localhost/ESP32_MySQL_Database/home.php
```

---

## 📊 API Endpoints

| Endpoint | Method | Deskripsi |
|----------|--------|-----------|
| `updateSensordata.php` | POST | Menerima data sensor dari ESP32 |
| `getdata.php` | GET | Mengembalikan status LED untuk ESP32 |
| `fetch_data.php` | GET | Mengambil data sensor terbaru untuk UI |
| `home.php` | GET/POST | Interface utama monitoring dan kontrol |
| `recordtable.php` | GET | Menampilkan histori data sensor |

---

## 🐞 Troubleshooting

### ESP32 Tidak Terhubung ke WiFi
- Periksa SSID dan password WiFi
- Pastikan sinyal WiFi cukup kuat
- Cek Serial Monitor untuk pesan error

### Data Tidak Masuk ke Database
- Periksa koneksi database di `database.php`
- Pastikan tabel sudah dibuat dengan struktur yang benar
- Cek log error di server web

### LED Tidak Merespon
- Periksa wiring LED ke ESP32
- Pastikan endpoint `getdata.php` dapat diakses
- Cek Serial Monitor ESP32 untuk response HTTP

---

## 📌 Catatan Penting

- Pastikan file `updateSensordata.php` dan `database.php` sudah disiapkan dengan koneksi yang benar.
- Gunakan alamat IP server lokal (misalnya `192.168.1.12`) di dalam ESP32.
- Server harus menggunakan **XAMPP** atau layanan lokal lain yang mendukung **PHP + MySQL**.
- Pastikan firewall tidak memblokir komunikasi antara ESP32 dan server.

---

## 🔮 Pengembangan Selanjutnya

- [ ] Implementasi autentikasi user
- [ ] Dashboard analytics dengan grafik
- [ ] Notifikasi push/email
- [ ] Mobile app companion
- [ ] Support multiple ESP32 devices

---

## 🙋‍♂️ Kontribusi

Feel free to fork, pull request, or open an issue!

---

## 👨‍💻 Author

**Ficram Manifur Farissa**  
Elektronika & IoT Enthusiast  
[GitHub](https://github.com/ficram)

<div align="center">

**⭐ Beri bintang pada repository ini jika Anda merasa terbantu!**

<p><a href="#top">⬆ Kembali ke Atas</a></p>

</div>
