import machine,time
import network, urequests    
import dht

HTTP_HEADERS = {'Content-Type': 'application/json'} 
 
ssid = 'wifi'
password = 'password'

# connect to wifi
sta_if=network.WLAN(network.STA_IF)
sta_if.active(True)
 
sensor_temp = machine.ADC(4)
sensor = dht.DHT22(machine.Pin(0))

sensorid = "kitchen"

while True:
    time.sleep(5)
    if not sta_if.isconnected():
        sta_if.connect(ssid, password)
        continue
    
    try:
        sensor.measure()
        temp = sensor.temperature()
        hum = sensor.humidity()
    except:
        print("error: sensor not found")
        continue
    
    print(temp,hum)
    dht_readings = {'room': sensorid, 'temperature':temp, 'humidity': hum} 
   
    try:
        request = urequests.post( 'https://test.com/upload.php', json = dht_readings, headers = HTTP_HEADERS )  
        request.close()
    except:
        print ("error: upload failed - offline ?")
    
    time.sleep(300)
