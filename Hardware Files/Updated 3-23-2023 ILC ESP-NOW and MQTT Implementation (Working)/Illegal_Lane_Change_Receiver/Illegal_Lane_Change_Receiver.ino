#include <esp_now.h>
#include <WiFi.h>

#define LED 2

const int pir1 = 23;

// Reading to be received from other board
int pir2Status;

// Stores data to be sent / received
typedef struct struct_message {
  int sensorStatus;
} struct_message;

struct_message receiveData;

void OnDataSent(const uint8_t *mac_addr, esp_now_send_status_t status) {
  //Serial.print("\r\nLast Packet Send Status:\t");
  //Serial.println(status == ESP_NOW_SEND_SUCCESS ? "Delivery Success" : "Delivery Fail");
}

void OnDataRecv(const uint8_t * mac, const uint8_t *incomingData, int len) {
  memcpy(&receiveData, incomingData, sizeof(receiveData));
  pir2Status = receiveData.sensorStatus;
  //Serial.print("Sensor 2 Status: ");
  //Serial.println(pir2Status);
}

void setup() {
  Serial.begin(115200);
  WiFi.mode(WIFI_STA);
  pinMode(pir1, INPUT);
  pinMode(LED, OUTPUT);

  // Start ESP-NOW
  if (esp_now_init() != ESP_OK) {
    Serial.println("Error initializing ESP-NOW");
    return;
  } else {
    Serial.println("ESP-NOW Engaged");
  }

  esp_now_register_send_cb(OnDataSent);

  esp_now_register_recv_cb(OnDataRecv);

}

void loop() {
  //Serial.print("PIR 1 Status: ");
  //Serial.println(digitalRead(pir1));
  if (digitalRead(pir1) == HIGH){
    digitalWrite(LED, HIGH);
  }
  if (digitalRead(pir1) == HIGH && pir2Status == HIGH) {
    Serial.println("z");
    delay(3000);
  }

  delay(250);
  digitalWrite(LED, LOW);
}
