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
 
if not sta_if.isconnected():
    print('connecting to network...')
    sta_if.connect(ssid, password)
    while not sta_if.isconnected():
     pass

sensor_temp = machine.ADC(4)
sensor = dht.DHT22(machine.Pin(0))

# change on each sensor
sensorid = "room name"

time.sleep(5)
while True:
    
    try:
        sensor.measure()
        temp = sensor.temperature()
        hum = sensor.humidity()
    except:
        print("error: sensor not found")
    
    print(temp,hum)
    dht_readings = {'room': sensorid, 'temperature':temp, 'humidity': hum} 
   
    try:
        request = urequests.post( '/upload.php', json = dht_readings, headers = HTTP_HEADERS )  
        request.close()
    except:
        print ("error: upload failed - offline ?")
    
    time.sleep(300)
