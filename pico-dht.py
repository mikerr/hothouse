import machine
import urequests 
import network, time    
import dht

HTTP_HEADERS = {'Content-Type': 'application/json'} 
 
ssid = 'ssid'
password = 'password'

# connect to wifi
sta_if=network.WLAN(network.STA_IF)
sta_if.active(True)
 
sensor_temp = machine.ADC(4)
sensor = dht.DHT22(machine.Pin(0))

sensorid = "room1"

while True:
    if not sta_if.isconnected():
        sta_if.connect(ssid, password)
        time.sleep(5)
        
    try:
        sensor.measure()
        temp = sensor.temperature()
        hum = sensor.humidity()
    except:
        print("error: sensor not found")
    
    print(temp,hum)
    dht_readings = {'room': sensorid, 'temperature':temp, 'humidity': hum} 
   
    try:
        request = urequests.post( 'https://test.com/upload.php', json = dht_readings, headers = HTTP_HEADERS )  
        request.close()
    except:
        print ("error: upload failed - offline ?")
    
    time.sleep(300)
