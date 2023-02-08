#include <UIPEthernet.h>
#include <SPI.h>
#include <math.h>
#include <SoftwareSerial.h> 


void sms();
void gobuzzer();
void gobuzzer2();
void bluetooth();
void connection();

String buffer;
int buzzer = 16;
int GND = 20;

SoftwareSerial MyBlue(10, 11); // RX | TX 
char receive; 
String fullbuffer;
String pulsebuffer;
String spo2buffer;
int pulse = NULL;
int SpO2 = NULL;
int index;
int person_id = 1;

int    HTTP_PORT   = 80;
String HTTP_METHOD = "GET";
char   HOST_NAME_SMS[] = "maker.ifttt.com";
char   HOST_NAME_WEB[] = "192.168.0.244";
String PATH_NAME_SMS = "/trigger/smssender/with/key/eR6yg9LvbobRYlAs4KAKrUPXnVZ_Lozhjk2zes7kMf5";
String mustr;

byte mac[] = { 0xAE, 0xBD, 0xBE, 0xDF, 0xFE, 0xED };
IPAddress ip(192, 168, 0, 192);
EthernetServer server(80);

char c;
int panicButton = 13, statusButton;


void setup() {
  Serial.begin(115200);
  MyBlue.begin(9600);  
  digitalWrite(GND, LOW);
  pinMode(buzzer, OUTPUT);
  pinMode(GND, OUTPUT);
  pinMode(panicButton, INPUT);
  statusButton = LOW;
  Serial.println("Ethernet WebServer Example");
  // start the Ethernet connection and the server:
  Ethernet.begin(mac, ip);
  // Check for Ethernet hardware present
  if (Ethernet.hardwareStatus() == EthernetNoHardware) {
    Serial.println("Ethernet shield was not found");
    while (!0) {
      delay(5);
    }
  }
  server.begin();
  Serial.print("server is at ");
  Serial.println(Ethernet.localIP());
}


/////////////////////////////////////////////////////////////////////////////////////////////////
void loop()
{
  bluetooth();
  if(pulse != NULL && SpO2 != NULL)
  {
    connection();
    if((pulse < 30) || (pulse > 110) || (SpO2 < 85))
    {
      Serial.println("call the doc");
      //sms();
      //delay(30000);
    }
  }
  statusButton = digitalRead(panicButton);
  if(statusButton == HIGH)
  {
    Serial.println("in button");
    delay(200);
    //sms();
    gobuzzer2();
  }
  if (Serial.available() > 0) 
  {
    c = Serial.read();
    mustr += c;
    int num = mustr.toInt();
    if(num == 1)
    {
      gobuzzer();
    }
    Serial.flush();
  }
  pulse = NULL;
  SpO2 = NULL;
}
/////////////////////////////////////////////////////////////////////////////////////////////////

void bluetooth()
{
    if (MyBlue.available())
  {
    delay(15);
    while(MyBlue.available())
    {
      receive = MyBlue.read(); 
      fullbuffer += receive;   
    } 
    index = fullbuffer.indexOf(' ');
    pulsebuffer = fullbuffer.substring(0,index);
    spo2buffer = fullbuffer.substring(index + 1, 12);
    pulse = pulsebuffer.toInt();
    SpO2 = spo2buffer.toInt();
    Serial.println(pulse);
    Serial.println(SpO2);
  }

fullbuffer = ""; 
}

void connection()
{ 
  EthernetClient client3;
  while(client3.connect("192.168.0.244", 80)) 
  {
    client3.print("GET /hospital/insert.php?pulse=");
    client3.print(pulse);
    client3.print("&oxygen=");
    client3.print(SpO2);
    client3.print("&person_id=");
    client3.print(person_id);
    client3.print(" ");
    client3.print("HTTP/1.1");
    client3.println();
    client3.println("Host: " + String(HOST_NAME_WEB));
    client3.println("Connection: close");
    client3.println();
    
    while(client3.connected()) {
      if(client3.available()){
        char c = client3.read();
        Serial.print(c);
      }
    }
    
    client3.stop();
    Serial.println();
    Serial.println("disconnected");
    break;
  } 
}

void sms()
{
  EthernetClient client2;
  if (Ethernet.begin(mac) == 0) 
  {
    Serial.println("Failed to obtaining an IP address using DHCP");
  }
  if(client2.connect(HOST_NAME_SMS, HTTP_PORT)) {
    Serial.println("Connected to server");
    // make a HTTP request:
    // send HTTP header
    client2.println("GET " + PATH_NAME_SMS + " HTTP/1.1");
    client2.println("Host: " + String(HOST_NAME_SMS));
    client2.println("Connection: close");
    client2.println(); // end HTTP header

    while(client2.connected()) {
      if(client2.available()){
        char c = client2.read();
        Serial.print(c);
      }
    }
    client2.stop();
    Serial.println();
    Serial.println("disconnected1");
  } else {// if not connected:
    Serial.println("connection failed");
  }
}


void gobuzzer()
{
  for(int i=0;i<3;i++)
  {
    tone(buzzer, 700); // Send 700Hz sound signal
    delay(340);
    tone(buzzer, 1200); // Send 1200Hz sound signal
    delay(340);
    tone(buzzer, 1500); // Send 1500Hz sound signal
    delay(340);
  }
  delay(50);
  noTone(buzzer);
}

void gobuzzer2()
{
    tone(buzzer, 800); // Send 1000Hz sound signal
    delay(600);
    noTone(buzzer);
 }
