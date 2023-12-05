#include <Adafruit_Sensor.h>
#include <DHT.h>
// dht sensor library from adafruit
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>

#define DHTPIN 2     // Digital pin connected to the DHT sensor
#define DHTTYPE    DHT22     

DHT dht(DHTPIN, DHTTYPE);

const char* ssid = "ssid";
const char* password = "password";

String serverName = "/upload.php";

void setup() {
  Serial.begin(115200);
  dht.begin();

  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
}

void loop() {
  // put your main code here, to run repeatedly:

    float temp = dht.readTemperature();
    float hum = dht.readHumidity();

     if (isnan(temp)) {
      Serial.println("Failed to read from DHT sensor!");
     }     else {
      Serial.printf("Temperature %.2f C, Humidity %.2f %% \n",temp,hum);
     }

     if(WiFi.status()== WL_CONNECTED){
      WiFiClient client;
      HTTPClient http;
    
      http.begin(client, serverName);
      http.addHeader("Content-Type", "application/json");

      char buffer[80];
      sprintf(buffer,"{\"room\": \"kitchen\", \"temperature\": %.2f, \"humidity\": %.2f }",temp,hum);
      int httpResponseCode = http.POST(buffer);
      Serial.println(buffer);
      Serial.println(httpResponseCode);
      http.end();
     }
    delay(5000);
}
