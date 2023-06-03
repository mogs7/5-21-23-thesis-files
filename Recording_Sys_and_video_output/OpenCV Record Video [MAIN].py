import cv2
import time
import os
import paho.mqtt.client as mqtt
import mysql.connector
from datetime import datetime
import json
import requests
from urllib.parse import urlencode 
import boto3

message = ""
violation = ""

# Intialize AWS S3: upload_file(filename [file path], bucket name, key [name to insert in bucket])
s3client = boto3.client("s3")
bucketName = "thesis-iot-traffic-violation-detection"
# Variables for sending SMS
apikey = 'x'
sendername = 'Thesis'

# Initialize connection to database. Chance of errors happening here so used Try Except
while True:
    try:
        db = mysql.connector.connect(
            host = "us-cdbr-east-06.cleardb.net",
            user = "b6a40db6e6872e",
            password = "4af93b62",
            database = "heroku_2bada4c2bd2d59b"
        )
        break
    except mysql.connector.Error as e:
        print("Error occured when intializing connection to database. Retrying in 3 seconds....")
        time.sleep(3)

# Setup for MQTT Communication to ESP (Broker options: broker.emqx.io | broker.hivemq.com)
broker_address = "broker.emqx.io"
topic = "thesisviolation"
    

def on_connect(client, userdata, flags, rc):
    print("Connected with result code " + str(rc))
    client.subscribe(topic, qos=2)

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
            url ='https://thesis-violation.herokuapp.com/includes/online.data.inc.php'
            online_users = []
            
            while True:
                try:
                    response = requests.get(url)
                    if response.status_code == 200:
                        data = json.loads(response.text)
                        for user in data:
                            online_users.append(int(user['usersPnumber']))
                        break
                    else:
                        print('Error retrieving data:', response.status_code)
                except requests.exceptions.ConnectionError as e:
                    print ("Error caught when obtaining online users. Retrying in 3 seconds....")
                    time.sleep(3)

            for phoneNumber in online_users:
                params = (
                    ('apikey', apikey),
                    ('sendername', sendername),
                    ('message', smsMessage),
                    ('number', phoneNumber)
                )
                path = 'https://api.semaphore.co/api/v4/messages?' + urlencode(params)
                
                # Chance of error occuring here. Used Try Except block
                while True:
                    try:
                        requests.post(path)
                        break
                    except requests.exceptions.ConnectionError as e:
                        print("Error occured when posting to API. Retrying in 3 seconds....") 
                        time.sleep(3)
                

            message = ""
            recording = False
            triggered = True
            
            # Save the video to a file
            out.release()
            cv2.destroyAllWindows()

            # Upload to bucket 
            s3client.upload_file("Recording_Sys_and_video_output/"+filename, bucketName, filename, ExtraArgs = {'Metadata': {'Content-Type': 'video/mp4'}})

            # Upload to Database. Chance that connection to DB will fail so made a try except block
            while True:
                try:
                    cursor = db.cursor()
                    #print("test")
                    break
                except mysql.connector.errors.OperationalError as e:
                    print("Failed to create cursor. Retrying in 3 seconds...")
                    time.sleep(3)
            
            query = "INSERT into video (url, date_time, status, violation) VALUES (%s, %s, %s, %s)"
            values = ("https://thesis-iot-traffic-violation-detection.s3.ap-southeast-1.amazonaws.com/"+filename, timeString, 'Pending', violation)
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
