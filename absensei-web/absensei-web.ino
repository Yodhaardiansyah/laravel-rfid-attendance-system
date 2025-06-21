#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <SPI.h>
#include <MFRC522.h>

// **Konfigurasi WiFi**
const char* ssid = "Yoss";
const char* password = "06122002";

// **API Server**
const char* serverHost = "http://absen-pondok.arunovasi.my.id";

// **Konfigurasi RFID**
#define SS_PIN 15   // D8 -> GPIO15
#define RST_PIN 16  // D0 -> GPIO16
#define ALARM_PIN 4 // D2 -> GPIO4

MFRC522 rfid(SS_PIN, RST_PIN);
WiFiClient client;

unsigned long lastCardTime = 0;
const unsigned long cardTimeout = 2000; // Timeout 2 detik
String lastCardID = "";

// **Alarm**
bool alarmActive = false;
unsigned long alarmStartTime = 0;
const unsigned long alarmDuration = 1000; // 1 detik

void setup() {
    Serial.begin(115200);
    SPI.begin();
    rfid.PCD_Init();
    pinMode(ALARM_PIN, OUTPUT);
    digitalWrite(ALARM_PIN, LOW);

    // **Koneksi ke WiFi**
    WiFi.begin(ssid, password);
    Serial.print("Menghubungkan ke WiFi");
    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
    }
    Serial.println("\nTerhubung ke WiFi!");
}

void loop() {
    // **Pastikan alarm menyala selama `alarmDuration`**
    if (alarmActive && millis() - alarmStartTime >= alarmDuration) {
        digitalWrite(ALARM_PIN, LOW);
        alarmActive = false;
    }

    // **Cek apakah ada kartu RFID yang didekatkan**
    if (rfid.PICC_IsNewCardPresent() && rfid.PICC_ReadCardSerial()) {
        String cardID = getCardID();

        // **Pastikan kartu tidak sama dalam 2 detik terakhir**
        if (cardID != lastCardID || millis() - lastCardTime > cardTimeout) {
            Serial.println("RFID Terdeteksi: " + cardID);
            lastCardID = cardID;
            lastCardTime = millis();

            // **Nyalakan alarm selama 1 detik**
            digitalWrite(ALARM_PIN, HIGH);
            alarmActive = true;
            alarmStartTime = millis();

            // **Kirim data ke API**
            if (sendRFID(cardID)) {
                sendAttendance(cardID);
            }
        }
    }
    rfid.PICC_HaltA(); // Hentikan pembacaan sementara
}

// **Fungsi untuk mendapatkan ID RFID dalam format HEX**
String getCardID() {
    String id = "";
    for (byte i = 0; i < rfid.uid.size; i++) {
        id += String(rfid.uid.uidByte[i], HEX);
    }
    return id;
}

// **Mengirim RFID ke API /api/push-rfid**
bool sendRFID(String cardID) {
    if (WiFi.status() != WL_CONNECTED) return false;
    
    HTTPClient http;
    String url = String(serverHost) + "/api/push-rfid";
    String jsonData = "{\"rfid_number\":\"" + cardID + "\"}";

    http.begin(client, url);
    http.addHeader("Content-Type", "application/json");

    Serial.println("Mengirim data RFID...");
    int httpResponseCode = http.POST(jsonData);

    if (httpResponseCode > 0) {
        String response = http.getString();
        Serial.println("Respon: " + response);
        http.end();
        return true;
    } else {
        Serial.print("Gagal mengirim RFID. Kode: ");
        Serial.println(httpResponseCode);
        http.end();
        return false;
    }
}

// **Mengirim Absensi ke API /api/attendance/record**
void sendAttendance(String cardID) {
    if (WiFi.status() != WL_CONNECTED) return;
    
    HTTPClient http;
    String url = String(serverHost) + "/api/attendance/record";
    String jsonData = "{\"rfid_number\":\"" + cardID + "\", \"time\":\"\"}";

    http.begin(client, url);
    http.addHeader("Content-Type", "application/json");

    Serial.println("Mengirim absensi...");
    int httpResponseCode = http.POST(jsonData);

    if (httpResponseCode > 0) {
        String response = http.getString();
        Serial.println("HTTP Code: " + String(httpResponseCode));
        Serial.println("Respon Absensi: " + response);
    } else {
        Serial.print("Gagal mengirim absensi. Kode: ");
        Serial.println(httpResponseCode);
    }

    http.end();
}
