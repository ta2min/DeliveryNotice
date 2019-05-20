#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid = "WARPSTAR-9D78AB-G";
const char* password = "D72AC1574F745";
const char* URL = "https://delivery-notification.herokuapp.com/push_test.php";

const int sound_sensor = 12;

void setup() {
  Serial.begin(115200);
  pinMode(sound_sensor, INPUT);
}

void loop() {
  int status_sensor = digitalRead(sound_sensor);
  if (status_sensor == 0) {
    Serial.println("音を検知しました。");
    send_request();
  }
  delay(10);
}

void connect_wifi() {
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.println("Connecting to WiFi..");
  }
  Serial.println("Connected to the WiFi network");
}

void send_request() {
  
  connect_wifi();
  if ((WiFi.status() == WL_CONNECTED)) { //Check the current connection status

    HTTPClient http;

    http.begin(URL); //Specify the URL
    int httpCode = http.GET();

    if (httpCode > 0) { //Check for the returning code

      String payload = http.getString();
      Serial.println(httpCode);
      Serial.println(payload);
    }

    else {
      Serial.println("Error on HTTP request");
    }

    http.end(); //Free the resources
  }
  WiFi.disconnect();
}
