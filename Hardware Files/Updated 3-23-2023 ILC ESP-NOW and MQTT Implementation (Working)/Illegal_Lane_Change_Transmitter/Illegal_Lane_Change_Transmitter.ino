#include <esp_now.h>
#include <WiFi.h>

#define LED 2

const int pir2 = 23;

// MAC Address of responder
uint8_t broadcastAddress[] = {0x30, 0xC6, 0xF7, 0x15, 0x09, 0xF4}; 

// Define a data structure
typedef struct struct_message {
  int sensorStatus;
} struct_message;

// Structured object for sending
struct_message sendData;

// Structured object for receiving
struct_message receiveData;

// Peer info
esp_now_peer_info_t peerInfo;

// Callback function called when data is sent 
void OnDataSent(const uint8_t *mac_addr, esp_now_send_status_t status) {
  Serial.print("\r\nLast Packet Send Status:\t");
  Serial.println(status == ESP_NOW_SEND_SUCCESS ? "Delivery Success" : "Delivery Fail");
}

// Callback function executed when data is received
void OnDataRecv(const uint8_t * mac, const uint8_t *incomingData, int len) {
  memcpy(&receiveData, incomingData, sizeof(receiveData));
}

void setup() {
  Serial.begin(115200);
  WiFi.mode(WIFI_STA);
  pinMode(pir2, INPUT);
  pinMode(LED, OUTPUT);
 
  // Initilize ESP-NOW
  if (esp_now_init() != ESP_OK) {
    Serial.println("Error initializing ESP-NOW");
    return;
  }

  // Register the send callback 
  esp_now_register_send_cb(OnDataSent);

  // Register peer 
  memcpy(peerInfo.peer_addr, broadcastAddress, 6);
  peerInfo.channel = 0;  
  peerInfo.encrypt = false;
  
  // Add peer
  if (esp_now_add_peer(&peerInfo) != ESP_OK){
    Serial.println("Failed to add peer");
    return;
  }
  
  // Register callback function
  esp_now_register_recv_cb(OnDataRecv);
}
 
void loop() {
  int sensor2 = digitalRead(pir2); // read the value from PIR 2 sensor

  if (digitalRead(pir2) == HIGH){
    digitalWrite(LED, HIGH);
  }
  sendData.sensorStatus = sensor2;
  // Send message via ESP-NOW
  esp_err_t result = esp_now_send(broadcastAddress, (uint8_t *) &sendData, sizeof(sendData));
   
  if (result == ESP_OK) {
    Serial.println("Sending confirmed");
    Serial.print("Sensor R reading: ");
    Serial.println(sendData.sensorStatus);
  }
  else {
    Serial.println("Sending error");
  }
  delay(250);
  digitalWrite(LED, LOW);
}
