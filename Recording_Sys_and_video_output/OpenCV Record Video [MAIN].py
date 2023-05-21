import cv2
import time
import os
import paho.mqtt.client as mqtt
import mysql.connector
from datetime import datetime
import json
import requests
from urllib.parse import urlencode 

message = ""
violation = ""

# Variables for sending SMS
apikey = '405b749bef6703ce4b3bf52f54386ff0'
sendername = 'Thesis'

# Initialize connection to database
db = mysql.connector.connect(
    host = "localhost",
    user = "root",
    password = "",
    database = "violationdetector"
)

# Setup for MQTT Communication to ESP (Broker options: broker.emqx.io | broker.hivemq.com)
broker_address = "broker.emqx.io"
topic = "thesisviolation"
    

def on_connect(client, userdata, flags, rc):
    print("Connected with result code " + str(rc))
    client.subscribe(topic)

def on_message(client, userdata, msg):
    global message
    print(msg.payload.decode())    
    message = msg.payload.decode()

client = mqtt.Client()
client.on_connect = on_connect
client.on_message = on_message
client.connect(broker_address, 1883, 60)


# Define the codec and create VideoWriter object
fourcc = cv2.VideoWriter_fourcc(*'avc1')

# Duration of video in seconds
duration = 30

# Start camera capture
cap = cv2.VideoCapture(0)

# Counter for video name output
counter = 0

# Time string for unique name everytime the file is opened
timeName = time.monotonic()

# Boolean if recording was not triggered
triggered = False

# Initialize file path
#file_path = "C:/Users/MIGUEL MOLINA/Desktop/Recording_Sys_and_video_output/output_" 
# IMPORTANT*: since no absolute file path is declared, it automatically saves in the current working directory
file_path = "C:/xampp/htdocs/webapp/Recording_Sys_and_video_output/"

# Start recording
while True:
    # Skip the first loop to allow the code to run
    if (counter != 0):
        # Check if last video was triggered or not
        if(triggered == False):
            #file_path = "C:/Users/MIGUEL MOLINA/Desktop/Recording_Sys_and_video_output/output_" + str(timeName) + "_" + str(counter) + ".mp4"
            file_path = "C:/xampp/htdocs/webapp/Recording_Sys_and_video_output/output_" + str(timeName) + "_" + str(counter) + ".mp4"
            os.remove(file_path)

    # Create VideoWriter object for each recording session
    counter = counter+1
    filename = "output_" + str(timeName) + "_" + str(counter) + ".mp4"
    out = cv2.VideoWriter("Recording_Sys_and_video_output/"+filename, fourcc, 20.0, (640, 480), True)

    start_time = cv2.getTickCount()
    recording = True

    while recording:
        # This captures frame by frame
        ret, frame = cap.read()

        # Write time to frame of video
        timeVar = time.time()
        localTime = time.localtime(timeVar)
        timeString = time.strftime("%Y-%m-%d %H:%M:%S", localTime)
        cv2.putText(frame, timeString, (10,50), cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 0, 255), 2)


        # Write the frame to the video
        out.write(frame)

        # Display the resulting frame
        cv2.imshow('frame', frame)
        cv2.waitKey(30)

        # Check if the recording time has exceeded the defined duration
        if (cv2.getTickCount() - start_time) / cv2.getTickFrequency() >= duration:
            recording = False
            triggered = False

        # Save the video if sensor is triggered
        if ("1" in message):
            # Identify violation
            if ("ovs" in message):
                violation = "over speeding"
            elif ("btr" in message):
                violation = "beating the red light"
            elif ("ilc" in message):
                violation = "illegal lane change"

            # Put violation type to variable
            violationType = violation
            smsMessage = violationType.title() + " violation detected at time and date: " + timeString

            # Retrieve online users
            url ='http://127.0.0.1/webapp/includes/online.data.inc.php'
            online_users = []
            response = requests.get(url)

            while True:
                if response.status_code == 200:
                    data = json.loads(response.text)
                    for user in data:
                        online_users.append(int(user['usersPnumber']))
                    break
                else:
                    print('Error retrieving data:', response.status_code)

            for phoneNumber in online_users:
                params = (
                    ('apikey', apikey),
                    ('sendername', sendername),
                    ('message', smsMessage),
                    ('number', phoneNumber)
                )
                path = 'https://api.semaphore.co/api/v4/messages?' + urlencode(params)
                requests.post(path)
                

            message = ""
            recording = False
            triggered = True
            
            # Save the video to a file
            out.release()
            cv2.destroyAllWindows()

            # Chance that connection to DB will fail so made a try except block
            while True:
                try:
                    cursor = db.cursor()
                    break
                except mysql.connector.errors.OperationalError as e:
                    print("Failed to create cursor. Retrying in 3 seconds...")
                    time.sleep(3)
            
            query = "INSERT into video (url, date_time, status, violation) VALUES (%s, %s, %s, %s)"
            values = ("Recording_Sys_and_video_output/"+filename, timeString, 'Pending', violation)
            cursor.execute(query, values)
            db.commit()
            # db.close()
            
            # Wait after receiving message
            time.sleep(2)
            break

        client.loop(timeout=0)
        #print("loop end")


    # Release the VideoWriter object for each recording session
    out.release()

    # Wait for 1 second before starting a new recording session
    time.sleep(1)