#include <WiFi.h>
#include <PubSubClient.h>

#define LED 2

const char* ssid = "BubbleGum";
const char* password = "12345678";
const char* mqtt_server = "broker.emqx.io";
const char* mqtt_topic = "thesisviolation";
bool reconFlag = false;

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
    if (client.connect("ESP8266Client")) {
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
  client.setServer(mqtt_server, 1883);
  pinMode(LED, OUTPUT);
}


void loop() {
  if (Serial.available()){
    char serialInput = Serial.read();
    Serial.println(serialInput);
    if (serialInput == 'z'){
      if (!client.connected()){
        reconnect();
      }else{
        client.publish(mqtt_topic, "1 ilc", 2);
        //Serial.println("Published");
        digitalWrite(LED, HIGH);
        reconFlag = false;
        delay(3000);
      }
      if (reconFlag){
        client.publish(mqtt_topic, "1 ilc", 2);
        //Serial.println("Published");
        digitalWrite(LED, HIGH);
        reconFlag = false;
        delay(3000);
      }
    }
    digitalWrite(LED, LOW);
  }
  client.loop();
}
