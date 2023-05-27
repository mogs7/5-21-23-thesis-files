#define LED 2
#include <WiFi.h>
#include <PubSubClient.h>

const char* ssid = "HG8145V5_3DDF7";
const char* password = "rjBVN5ym";
const char* mqtt_server = "broker.emqx.io";
const char* mqtt_topic = "emtechppt";
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
    Serial.print("Attempting MQTT connection...");
    if (client.connect("ovs-publisher", mqtt_username, mqtt_password)) {
      Serial.println("connected");
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
  pinMode(23, INPUT);
  pinMode(LED, OUTPUT);
}

void loop() {
  if (client.connected()) {
    client.loop();
    // read motion sensor and publish a message if motion is detected
    Serial.println(digitalRead(23));
    if (digitalRead(23) == HIGH) {
      //delay(5000);
      digitalWrite(LED, HIGH);
      client.publish(mqtt_topic, "1 ovs");
      Serial.println("Published");
      delay(2000); 
      digitalWrite(LED, LOW);
    }
  } else {
   reconnect();
  }
}
