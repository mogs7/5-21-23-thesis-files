#include <WiFi.h>
#include <PubSubClient.h>

#define LED 2

const char* ssid = "BubbleGum";
const char* password = "12345678";
const char* mqtt_server = "broker.emqx.io";
const char* mqtt_topic = "ovsthesisviolation";
const char* mqtt_username = "emqx";
const char* mqtt_password = "public";
const int mqtt_port = 1883;
String speedString;

bool reconFlag = false;
bool triggered = false;

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
    if (client.connect("ovs-ilc-publisher", mqtt_username, mqtt_password)) {
      //Serial.println("connected");
      reconFlag = true;
    } else {
      Serial.print("failed, rc=");
      Serial.print(client.state());
      Serial.println(" try again in 1.5 seconds");
      delay(1500);
    }
  }
}

void setup() {
  WiFi.setSleep(false);
  Serial.begin(115200);
  setup_wifi();
  client.setServer(mqtt_server, mqtt_port);
  pinMode(LED, OUTPUT);
}


void loop() {
  if (Serial.available()){
    String serialInput = Serial.readString();
    Serial.println(serialInput);
    if (serialInput.indexOf('z') != -1){
      for (int i=0; i<serialInput.length(); i++){
        char c = serialInput.charAt(i);
        if (isdigit(c) || c == '.'){
          speedString += c;
        }
        triggered = true;
      }
      //mqttSpeedString = speedString.c_str();
    }
    if (triggered){
      if (!client.connected()){
        reconnect();
      }else{
        client.publish(mqtt_topic, ("1 ovs " + speedString).c_str());
        digitalWrite(LED, HIGH);
        reconFlag = false;
        delay(3000);
        speedString = "";
        triggered = false;
      }
    }
    if (reconFlag){
      client.publish(mqtt_topic, ("1 ovs " + speedString).c_str());
      digitalWrite(LED, HIGH);
      reconFlag = false;
      delay(3000);
      speedString = "";
      triggered = false;
    }
    digitalWrite(LED, LOW);
  }
  client.loop();
}
