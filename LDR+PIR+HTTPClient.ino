//---------------------------------------- ARDUINO
Arduino IDE:
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h> // Library ArduinoJson

// Informasi WiFi
const char* ssid = "FRISS";
const char* password = "cambeshuf6";

// Pin untuk LED dan sensor
#define LED 4 // LED onboard
#define LDR_PIN 34    // Pin LDR
#define PIR_PIN 13    // Pin PIR

// Variabel global
int pirState = LOW; // Status awal sensor PIR

void setup() {
  // Inisialisasi komunikasi Serial
  Serial.begin(115200);

  // Inisialisasi pin
  pinMode(LED, OUTPUT);
  pinMode(LDR_PIN, INPUT);
  pinMode(PIR_PIN, INPUT);

  // Koneksi WiFi
  WiFi.begin(ssid, password);
  Serial.println("Menghubungkan ke WiFi...");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nTerhubung ke WiFi!");
  Serial.println("IP Address: " + WiFi.localIP().toString());
}

void loop() {
  // Membaca data sensor
  int ldrValue = analogRead(LDR_PIN);
  int pirValue = digitalRead(PIR_PIN);

  // Logika untuk sensor PIR
  if (pirValue == HIGH) { // Jika ada gerakan terdeteksi
    if (pirState == LOW) {
      Serial.println("TERDETEKSI");
      digitalWrite(LED, HIGH); // Menyalakan LED
      pirState = HIGH;
    }
  } else { // Jika tidak ada gerakan
    if (pirState == HIGH) {
      Serial.println("TIDAK_TERDETEKSI");
      digitalWrite(LED, LOW); // Mematikan LED
      pirState = LOW;
    }
  }

  // Interpretasi data sensor
  String ldrStatus = (ldrValue > 800) ? "GELAP" : (ldrValue < 450) ? "TERANG" : "SEDANG";
  String pirStatus = (pirValue == HIGH) ? "TERDETEKSI" : "TIDAK_TERDETEKSI";

  // Menampilkan data sensor di Serial Monitor
  Serial.println("========== Pembacaan Sensor ==========");
  Serial.print("LDR Value: ");
  Serial.println(ldrValue);
  Serial.print("LDR Status: ");
  Serial.println(ldrStatus);
  Serial.print("PIR Status: ");
  Serial.println(pirStatus);

  // Mengirim data ke server jika terhubung ke WiFi
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;

    // Mengirim data sensor
    http.begin("http://192.168.1.12/ESP32_MySQL_Database/Final/updateSensordata.php");
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    String postData = "ldr_value=" + String(ldrValue) +
                      "&ldr_status=" + ldrStatus +
                      "&pir_status=" + pirStatus;

    int httpCode = http.POST(postData);
    String response = http.getString();

    // Menampilkan respons dari server
    Serial.println("Response from updateSensordata.php: " + response);
    http.end();

    // Ambil status LED dari database
    http.begin("http://192.168.1.12/ESP32_MySQL_Database/Test/getdata.php");
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    postData = "id=esp32_01"; // Kirim ID ke server
    httpCode = http.POST(postData);
    String payload = http.getString(); // Menyimpan respons server

    if (httpCode == 200) {
      // Parsing JSON
      StaticJsonDocument<200> doc;
      DeserializationError error = deserializeJson(doc, payload);

      if (error) {
        Serial.println("Parsing JSON gagal!");
      } else {
        String ledStatus = doc["LED STATUS"];
        if (ledStatus == "ON") {
          digitalWrite(LED, HIGH);
          Serial.println("LED Status: ON");
        } else if (ledStatus == "OFF") {
          digitalWrite(LED, LOW);
          Serial.println("LED Status: OFF");
        }
      }
    } else {
      Serial.println("HTTP request failed!");
    }
    http.end();

    delay(5000); // Delay sebelum pengulangan
  } else {
    Serial.println("Tidak ada koneksi WiFi, mencoba untuk reconnect...");
    WiFi.begin(ssid, password);
    delay(5000);
  }
}
//----------------------------------------
