/*
 * Uses R board
  Technically this code is the receiver code since
  it just receives Sensor 2's input and performs an
  action when it is HIGH. This code isn't really sending
  any information but the code used to send data is still 
  here and just commented out for further use. Especially
  when it is time to implement the ESP32-CAM.
*/

// Include Libraries
#include <esp_now.h>
#include <WiFi.h>

#define LED 2

// Variables for test data
unsigned long startTime = 0, timer = 0;
const float timeOut = 900; // Time out timer in ms
const int DISTANCE = 500, MAX_SPEED = 5.56; //Distance in cm and speed in m/s
const int pir1 = 23;
bool sensor1Triggered = false, sensor2Triggered = false;

// Reading to be received from other board
int pir2Status;

// MAC Address of responder (not in use)
uint8_t broadcastAddress[] = {0xC8, 0xC9, 0xA3, 0xD0, 0x51, 0xE0}; 

// Stores data to be sent / received
typedef struct struct_message {
  int sensorStatus; 
} struct_message;

// Structured object for sending
struct_message sendData;

// Structured object for receiving
struct_message receiveData; 

// Store peer info
esp_now_peer_info_t peerInfo;

// Callback function called when data is sent
void OnDataSent(const uint8_t *mac_addr, esp_now_send_status_t status) {
  //Serial.print("\r\nLast Packet Send Status:\t");
  //Serial.println(status == ESP_NOW_SEND_SUCCESS ? "Delivery Success" : "Delivery Fail");
}

// Callback function executed when data is received 
void OnDataRecv(const uint8_t * mac, const uint8_t *incomingData, int len) {
  memcpy(&receiveData, incomingData, sizeof(receiveData));
  pir2Status = receiveData.sensorStatus;
  //Serial.println(pir2Status);
}

void setup() {
  
  // Set up Serial Monitor
  Serial.begin(115200);
 
  // Set ESP32 as a Wi-Fi Station
  WiFi.mode(WIFI_STA);

  // Initilize ESP-NOW
  if (esp_now_init() != ESP_OK) {
    Serial.println("Error initializing ESP-NOW");
    return;
  } else{
    Serial.println("ESP-NOW Engaged");
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

  esp_now_register_recv_cb(OnDataRecv);
  
  // Sensor 1
  pinMode(pir1, INPUT);
  pinMode(LED, OUTPUT);
}

void loop() {
  if (digitalRead(pir1) == HIGH) {
    sensor1Triggered = true;
    digitalWrite(LED, HIGH);
    //Serial.println("Sensor 1 triggered: " + String(sensor1Triggered));
    startTime = millis(); // store the start time
  }

  if (pir2Status == HIGH) {
    sensor2Triggered = true;
    //Serial.println("Sensor 2 triggered: " + String(sensor2Triggered));
  }

  /*Serial.print("Sensor 1 stat: ");
  Serial.println(sensor1Triggered);
  Serial.print("Sensor 2 stat: ");
  Serial.println(sensor2Triggered);*/
  
  if (!sensor1Triggered && sensor2Triggered){
    sensor2Triggered = false;
  } else if (sensor1Triggered && !sensor2Triggered){
      timer = millis();
      if ((timer - startTime) >= timeOut){
        sensor1Triggered = false;
        //Serial.println("Sensor 1 Timeout");
      }
  } else if (sensor1Triggered && sensor2Triggered){
    sensor1Triggered = false;
    sensor2Triggered = false;
    float speed = calculateSpeed(startTime);
    /*Serial.print("Speed of object: ");
    Serial.print(speed*3.6);
    Serial.println(" km/h");*/

    //Overspeeding:
    if (speed >= MAX_SPEED && !isinf(speed)){
      Serial.print("z"); 
    }
  }
  delay(1000);
  digitalWrite(LED, LOW);
}

float calculateSpeed(unsigned long startTime) {
    float distance = DISTANCE/100;
    float endTime = millis();
    float timeElapsed = endTime - startTime;
    timeElapsed /= 1000;
    //Serial.print("Time Elapsed :");
    //Serial.println(timeElapsed);
    float speed = (distance/timeElapsed);
    return speed;
}
