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

# change name for each sensor
sensorid = "back room"

time.sleep(2)
while True:
    
    try:
        sensor.measure()
        temp = sensor.temperature()
        hum = sensor.humidity()
    except:
        conversion_factor = 3.3 / (65535)
        reading = sensor_temp.read_u16() * conversion_factor 
        temperature = 20 - (reading - 0.706)/0.001721
        temp = round(temperature,1)
        hum = 50
    
    print(temp,hum)
    
    dht_readings = {'room': sensorid, 'temperature':temp, 'humidity': hum} 
    request = urequests.post( '/upload.php', json = dht_readings, headers = HTTP_HEADERS )  
    request.close() 
    
    time.sleep(300)
