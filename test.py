# Importing Libraries
import serial
import time
x = 0
arduino = serial.Serial(port='COM5', baudrate=115200, timeout=.1)
def write_read(x):
    arduino.write(bytes(x, 'utf-8'))
    time.sleep(0.05)
    data = arduino.readline()
    return data
time.sleep(1)
num = '1'
write_read(num)
time.sleep(1)
