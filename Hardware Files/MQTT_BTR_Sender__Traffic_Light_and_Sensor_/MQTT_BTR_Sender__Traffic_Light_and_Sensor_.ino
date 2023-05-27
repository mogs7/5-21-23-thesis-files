#include <WiFi.h>
#include <PubSubClient.h>

#define LED 2

const char* ssid = "BubbleGum";
const char* password = "12345678";
const char* mqtt_server = "broker.emqx.io";
const char* mqtt_topic = "thesisviolation";
const char* mqtt_username = "emqx";
const char* mqtt_password = "public";
const int mqtt_port = 1883;

WiFiClient espClient;
PubSubClient client(espClient);

void setup_wifi() {
  delay(10);
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
}

void reconnect() {
  while (!client.connected()) {
    //Serial.print("Attempting MQTT connection...");
    if (client.connect("btr_publisher", mqtt_username, mqtt_password)) {
      //Serial.println("connected");
    } else {
      Serial.print("failed, rc=");
      Serial.print(client.state());
      Serial.println(" try again in 1.5 seconds");
      delay(1500);
    }
  }
}

const int redPin = 18, yellowPin = 19, greenPin = 21, sensorPin = 15;
void setup() {
  WiFi.setSleep(false);
  Serial.begin(115200);
  setup_wifi();
  client.setServer(mqtt_server, mqtt_port);
  
  pinMode(sensorPin, INPUT);
  pinMode(redPin, OUTPUT);
  pinMode(yellowPin, OUTPUT);
  pinMode(greenPin, OUTPUT);
  pinMode(LED, OUTPUT);
}

void loop() {
  if (!client.connected()) {
    reconnect();
  }
  else{
    digitalWrite(greenPin, HIGH);
    //Serial.println("Client state GREEN" + String(client.state()));
    delay(5000);
    digitalWrite(greenPin, LOW);
    digitalWrite(yellowPin, HIGH);
    //Serial.println("Client state YELLOW" + String(client.state()));
    delay(3000);
    digitalWrite(yellowPin, LOW);
    digitalWrite(redPin, HIGH);

    unsigned long runningTime = millis();
    while(true){
      if (!client.connected()){
        reconnect();
      }
      else{
        // read motion sensor and publish a message if motion is detected
        //Serial.println("Client state RED LIGHT" + String(client.state()));
        //Serial.println(digitalRead(sensorPin));
        if (digitalRead(sensorPin) == HIGH) {
          client.publish(mqtt_topic, "1 btr");
          //Serial.println("Client state SIGNAL READING LOOP" + String(client.state()));
          //Serial.println("Published");
          digitalWrite(LED, HIGH);
          delay(3000); 
          digitalWrite(LED, LOW);
        }
      }
      unsigned long currentTime = millis();
      //Serial.println("Running time: " + String(runningTime));
      //Serial.println("Current time: " + String(currentTime));
      //Serial.println("Time difference: "+ String(currentTime-runningTime));
      if (currentTime - runningTime >= 10000){
        break;
      }
    }
    digitalWrite(redPin, LOW);
    }
  client.loop(); 
}
