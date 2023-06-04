#include <esp_now.h>
#include <WiFi.h>

#define LED 2

unsigned long startTime = 0, timer = 0;
const float timeOut = 5000.0; // Time out timer in ms
const float DISTANCE = 13.88, MAX_SPEED = 2.7; //Distance in m and speed in m/s
const int pir1 = 23;
float serialSpeed;
bool sensor1Triggered = false, sensor2Triggered = false;

// Reading to be received from other board
int pir2Status;

// Responder MAC Address
uint8_t broadcastAddress[] = {0xC8, 0xC9, 0xA3, 0xD0, 0x51, 0xE0}; 

// Stores data to be sent / received
typedef struct struct_message {
  int sensorStatus; 
} struct_message;

// Structured object for sending
struct_message sendData;

// Structured object for receiving
struct_message receiveData; 

esp_now_peer_info_t peerInfo;

void OnDataSent(const uint8_t *mac_addr, esp_now_send_status_t status) {
  //Serial.print("\r\nLast Packet Send Status:\t");
  //Serial.println(status == ESP_NOW_SEND_SUCCESS ? "Delivery Success" : "Delivery Fail");
}

void OnDataRecv(const uint8_t * mac, const uint8_t *incomingData, int len) {
  memcpy(&receiveData, incomingData, sizeof(receiveData));
  pir2Status = receiveData.sensorStatus;
  //Serial.println(pir2Status);
}

void setup() {
  Serial.begin(115200);
  WiFi.mode(WIFI_STA);

  // Start ESP-NOW
  if (esp_now_init() != ESP_OK) {
    Serial.println("Error initializing ESP-NOW");
    return;
  } else{
    Serial.println("ESP-NOW Engaged");
  }

  esp_now_register_send_cb(OnDataSent);
  
  memcpy(peerInfo.peer_addr, broadcastAddress, 6);
  peerInfo.channel = 0;  
  peerInfo.encrypt = false;
    
  if (esp_now_add_peer(&peerInfo) != ESP_OK){
    Serial.println("Failed to add peer");
    return;
  }

  esp_now_register_recv_cb(OnDataRecv);
  
  // Sensor 1
  pinMode(pir1, INPUT);
  pinMode(LED, OUTPUT);
}

float calculateSpeed(unsigned long startTime) {
    unsigned long endTime = millis();
    /*Serial.print("Start Time: ");
    Serial.println(startTime);
    Serial.print("End Time: ");
    Serial.println(endTime);*/
    float timeElapsed = float(endTime - startTime)/1000.0f;
    /*Serial.print("Time Elapsed: ");
    Serial.println(timeElapsed);*/
    if (timeElapsed == 0){
      return 0.0;
    } else{
      float speed = (DISTANCE/timeElapsed);
      return speed;
    }
}

void loop() {
  if (digitalRead(pir1) == HIGH) {
    sensor1Triggered = true;
    digitalWrite(LED, HIGH);
    //Serial.println("Sensor 1 triggered: " + String(sensor1Triggered));
    startTime = millis(); // store the start time
  }

  if (!sensor1Triggered && sensor2Triggered){
    sensor2Triggered = false;
  }

  while (sensor1Triggered){
    if (pir2Status == HIGH) {
      sensor2Triggered = true;
      //Serial.println("Sensor 2 triggered: " + String(sensor2Triggered));
      break;
    } else if (sensor1Triggered && !sensor2Triggered){
      timer = millis();
      if ((timer - startTime) >= timeOut){
        sensor1Triggered = false;
        //Serial.println("Sensor 1 Timeout");
      }
  }
  }

  if (sensor1Triggered && sensor2Triggered){
    sensor1Triggered = false;
    sensor2Triggered = false;
    float speed = calculateSpeed(startTime);
    serialSpeed = speed*3.6;
    
    //Overspeeding:
    if (speed >= MAX_SPEED && !isinf(speed)){
      Serial.print("z" + String(serialSpeed) + " "); 
      serialSpeed = 0;
    }
  }
  delay(1000);
  digitalWrite(LED, LOW);
}
