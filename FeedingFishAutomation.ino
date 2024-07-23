#include <WiFi.h>
#include <HTTPClient.h>
#include "HX711.h"
#include <ESP32Servo.h> // Tambahkan library ESP32Servo

// Definisi pin untuk sensor ultrasonik
#define echoPin 23 // pin untuk Echo dari HC-SR04
#define trigPin 22 // pin untuk Trig dari HC-SR04

// Definisi pin untuk LED, buzzer, dan servo
#define ledGreen 12 // pin untuk LED hijau
#define ledYellow 13 // pin untuk LED kuning
#define buzzerPin 18 // pin untuk Buzzer
#define servoPin 5 // Pin yang digunakan untuk servo

// Definisi pin untuk Load Cell HX711
const int LOADCELL_DOUT_PIN = 21;
const int LOADCELL_SCK_PIN = 4;

// Variabel untuk sensor ultrasonik
unsigned long duration; // variabel untuk waktu perjalanan sinyal
int Jarak; // variabel untuk hasil pengukuran

// Variabel untuk Load Cell
HX711 scale;

// Variabel untuk servo motor
Servo myServo;
const int servoPosition = 90; // Posisi servo (0-180 derajat)
unsigned long previousMillis = 0;
const unsigned long interval = 8 * 60 * 60 * 1000; // 8 jam dalam milidetik

// WiFi credentials
const char* ssid = "POWER RANGER"; 
const char* password = "bebaslepas00"; 

// URL untuk mengirimkan data
String serverUrl = "http://192.168.0.100/sensor/Input.php";

void setup() {
  // Setup untuk sensor ultrasonik
  pinMode(trigPin, OUTPUT); // Mengatur trigPin sebagai OUTPUT
  pinMode(echoPin, INPUT); // Mengatur echoPin sebagai INPUT
  pinMode(ledGreen, OUTPUT); // Mengatur pin LED hijau sebagai OUTPUT
  pinMode(ledYellow, OUTPUT); // Mengatur pin LED kuning sebagai OUTPUT
  pinMode(buzzerPin, OUTPUT); // Mengatur pin Buzzer sebagai OUTPUT

  // Setup untuk Load Cell
  Serial.begin(115200);
  Serial.println("Load Cell Interfacing with ESP32 - DIY CHEAP PERFECT");
  scale.begin(LOADCELL_DOUT_PIN, LOADCELL_SCK_PIN);
  scale.set_scale(395.2731);    // scale factor input here  
  scale.tare();       // reset scale

  // Setup untuk Servo Motor
  myServo.attach(servoPin); // Lampirkan pin servo

  // Koneksi WiFi
  connectWiFi();

  // Pesan serial untuk debugging
  Serial.println("Tes Sensor Ultrasonik HC-SR04");
  Serial.println("dengan ESP32");
}

void loop() {
  unsigned long currentMillis = millis();
  
  // Bagian untuk sensor ultrasonik
  digitalWrite(trigPin, LOW);
  delayMicroseconds(2);
  digitalWrite(trigPin, HIGH);
  delayMicroseconds(10);
  digitalWrite(trigPin, LOW);

  duration = pulseIn(echoPin, HIGH);
  Jarak = duration * 0.034 / 2;

  Serial.print("Jarak: ");
  Serial.print(Jarak);
  Serial.println(" cm");

  if (Jarak > 6) {
    digitalWrite(ledGreen, HIGH); // LED hijau menyala
    digitalWrite(ledYellow, LOW); // LED kuning mati
    digitalWrite(buzzerPin, LOW); // Buzzer mati
  } else {
    digitalWrite(ledGreen, LOW); // LED hijau mati
    digitalWrite(ledYellow, HIGH); // LED kuning menyala
    digitalWrite(buzzerPin, HIGH); // Buzzer menyala
  }

  // Bagian untuk Load Cell
  Serial.print("Berat: ");
  Serial.println(scale.get_units(10), 1);
  scale.power_down(); // set ADC to sleep mode  
  delay(1000);
  scale.power_up();

  // Kirim data ke server
  sendDataToServer();

  // Gerakkan servo setiap 8 jam
  if (currentMillis - previousMillis >= interval) {
    previousMillis = currentMillis;
    
    // Gerakkan servo ke posisi tertentu
    myServo.write(servoPosition);
    Serial.println("Servo moved to position: " + String(servoPosition));
    
    // Tunggu beberapa detik sebelum kembali ke posisi awal
    delay(1000); // Waktu tunggu, dapat disesuaikan jika perlu
    
    // Kembali ke posisi awal (0 derajat)
    myServo.write(0);
    Serial.println("Servo moved back to position: 0");
  }

  // Delay opsional untuk memperlambat output
  delay(100);
}

void sendDataToServer() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;

    // Data yang akan dikirimkan
    String postData = "Jarak=" + String(Jarak) + "&Berat=" + String(scale.get_units(10), 1);

    http.begin(serverUrl);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    int httpResponseCode = http.POST(postData);

    if (httpResponseCode > 0) {
      Serial.print("HTTP Response code: ");
      Serial.println(httpResponseCode);

      String response = http.getString();
      Serial.println(response);
    } else {
      Serial.print("Error code: ");
      Serial.println(httpResponseCode);
    }

    http.end();
  } else {
    Serial.println("WiFi Disconnected. Reconnecting...");
    connectWiFi();
  }
}

void connectWiFi() {
  WiFi.mode(WIFI_OFF);
  delay(1000);
  WiFi.mode(WIFI_STA);

  WiFi.begin(ssid, password);
  Serial.println("Connecting to WiFi");

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi connected");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
}