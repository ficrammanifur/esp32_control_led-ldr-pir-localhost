# 🌐 ESP32 Web Monitoring & Control System

Sistem ini merupakan aplikasi IoT berbasis web untuk memonitor **sensor LDR** dan **PIR**, serta mengontrol **LED** melalui ESP32 dan interface web. Data dikirim dan disimpan ke dalam MySQL menggunakan koneksi WiFi dan komunikasi HTTP.

---

## 📁 Struktur Proyek

```
ESP32_MySQL_Database/
│
├── home.php               # Halaman utama (UI monitoring + kontrol LED)
├── getdata.php            # Mengambil status LED berdasarkan ID (untuk ESP32)
├── fetch_data.php         # Mengambil data sensor terbaru untuk tampil di UI
├── recordtable.php        # Menampilkan riwayat data sensor dalam bentuk tabel
├── updateSensordata.php   # (disebut di kode Arduino) Untuk update data sensor (belum ditampilkan di atas)
├── database.php           # File koneksi database (harus dibuat)
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
- Kirim data sensor ke:
  ```
  http://<server-ip>/ESP32_MySQL_Database/Final/updateSensordata.php
  ```
- Ambil status LED dari:
  ```
  http://<server-ip>/ESP32_MySQL_Database/Test/getdata.php
  ```

---

## 🌐 Tampilan Web

### 🎛️ Monitoring
- Menampilkan status:
  - LDR (☀️ Terang/Gelap/Sedang)
  - PIR (🛡️ Terdeteksi/Tidak Terdeteksi)
  - Status Sensor Gabungan

### 💡 Kontrol LED
- Switch toggle untuk menyalakan/mematikan LED dari browser.

### 📋 Riwayat
- Tabel data histori sensor lengkap (`recordtable.php`).

---

## 📌 Catatan

- Pastikan file `updateSensordata.php` dan `database.php` sudah disiapkan dengan koneksi yang benar.
- Gunakan alamat IP server lokal (misalnya `192.168.1.12`) di dalam ESP32.
- Server harus menggunakan **XAMPP** atau layanan lokal lain yang mendukung **PHP + MySQL**.

---

## 🙋‍♂️ Kontribusi

Feel free to fork, pull request, or open an issue!

---

## 👨‍💻 Author

**Ficram Manifur Farissa**  
Elektronika & IoT Enthusiast  
[GitHub](https://github.com/ficram)
