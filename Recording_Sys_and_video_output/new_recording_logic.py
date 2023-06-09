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
from moviepy.video.io.ffmpeg_tools import ffmpeg_extract_subclip
from moviepy.video.io.VideoFileClip import VideoFileClip

message = ""
violation = ""
ovsSpeed = ""

# Intialize AWS S3: upload_file(filename [file path], bucket name, key [name to insert in bucket])
s3client = boto3.client("s3")
bucketName = "thesis-iot-traffic-violation-detection"
# Variables for sending SMS
apikey = 'x'
sendername = 'Thesis'

# Make DB configuration dictionary
db_config = {
    'host': 'us-cdbr-east-06.cleardb.net',
    'user': 'b6a40db6e6872e',
    'password': '4af93b62',
    'database': 'heroku_2bada4c2bd2d59b'
}

# Initialize connection to database. Chance of errors happening here so used Try Except
while True:
    print("Initializing connection to database....")
    try:
        db = mysql.connector.connect(**db_config)
        print("Successfully connected to database")
        break
    except mysql.connector.Error as e:
        print("An error occured when interacting with the Database: " + str(e) + ". Retrying in 3 seconds...")
        time.sleep(3)
    except Exception as e:
        print("Error occured when intializing connection to database: " + str(e) + ". Retrying in 3 seconds....")
        time.sleep(3)

# Setup for MQTT Communication to ESP (Broker options: broker.emqx.io | broker.hivemq.com)
broker_address = "broker.emqx.io"
topic = "ovsthesisviolation"
    

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

# Error handling for unreachable MQTT broker
while True:
    print("Intializing connection to MQTT Broker....")
    try:
        client.connect(broker_address, 1883, 60)
        print("Successfully connected to MQTT Broker")
        break
    except Exception as e:
        print("Error occured when connecting to MQTT broker: " + str(e) + " Retrying in 3 seconds...")
        time.sleep(3)


# Define codec and create VideoWriter object
fourcc = cv2.VideoWriter_fourcc(*'avc1')

# Video duration flag
video_duration_exceeded = False

# Start camera
cap = cv2.VideoCapture(0)

# Counter for video name output
counter = 1

# Time string for unique name everytime the file is opened
timeName = time.monotonic()

# Boolean if recording was not triggered
triggered = False

# Initialize file path
file_path = "C:/xampp/htdocs/webapp/Recording_Sys_and_video_output/"

# Start recording
while True:
    # Create VideoWriter object for each recording session
    filename = "output_" + str(timeName) + "_" + str(counter) + ".mp4"
    print("Created video with file name: " + filename)
    out = cv2.VideoWriter(file_path + filename, fourcc, 20.0, (640, 480), True)

    start_time = cv2.getTickCount()
    recording = True

    # Input and output video for video trimming
    input_video = file_path + filename
    output_video = file_path + "TRIMMED" + filename

    # Video cut length
    video_max_duration = 10

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

        # Check if a message is received then save
        if (message != ""):
            recording = False
            triggered = False

            # Save the video to a file
            out.release()
            cv2.destroyAllWindows()

            # Load video for trimming
            clip = VideoFileClip(input_video)
            video_duration = clip.duration

            # Check if video is greater than max duration
            if (video_duration > video_max_duration):
                video_duration_exceeded = True
                cut_time = video_duration - video_max_duration
                # Trim video
                ffmpeg_extract_subclip(input_video, cut_time, video_duration, targetname = output_video)
                print("Successfully trimmed video")
                clip.close()
                os.remove(input_video)
                print("Successfully deleted old original video")
                bucketFileUpload = output_video
            else:
                video_duration_exceeded = False
                bucketFileUpload = input_video

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

            if (violation == "over speeding"):
                ovsSpeed = message.replace("1 ovs","")
                ovsSpeedFloat = float(ovsSpeed)
                smsMessage = violationType.title() + " violation detected at time and date: " + timeString + ". Detected Speed:" + str(round(ovsSpeedFloat,2)) + "km/h"
            else:
                smsMessage = violationType.title() + " violation detected at time and date: " + timeString

            message = ""
            recording = False
            triggered = True

            while True:
                print("Uploading file to S3 Bucket....")
                try:
                    s3client.upload_file(bucketFileUpload, bucketName, bucketFileUpload, ExtraArgs = {'Metadata': {'Content-Type': 'video/mp4'}})
                    break
                except Exception as e:
                    print("Error occured when uploading video to S3 bucket: " + str(e) + " Retrying in 3 seconds....")
                    time.sleep(3)

            # Upload to Database. Chance that connection to DB will fail so made a try except block
            while True:
                print("Attempting to create DB cursor object....")
                try:
                    cursor = db.cursor()
                    print("Successfully created DB cursor object")
                    break
                except mysql.connector.Error as e:
                    db = mysql.connector.connect(**db_config)
                    print("An error occured when interacting with the Database: " + str(e) + ". Retrying in 3 seconds...")
                    time.sleep(3)
                except Exception as e:
                    print("An error occured when interacting with the Database: " + str(e) + ". Retrying in 3 seconds...")
                    time.sleep(3)
            
            query = "INSERT into video (url, date_time, status, violation) VALUES (%s, %s, %s, %s)"   
            values = ("https://thesis-iot-traffic-violation-detection.s3.ap-southeast-1.amazonaws.com/"+bucketFileUpload, timeString, 'Pending', violation)
            
            # Chance of error when executing query
            while True:
                print("Attemping to execute DB query....")
                try:
                    cursor.execute(query, values)
                    db.commit()
                    print("Sucessfully executed DB query")
                    break
                except Exception as e:
                    print("Error occured when executing query: " + str(e) + ". Retrying in 3 seconds....")
                    time.sleep(3)

            # Retrieve online users
            url ='https://thesis-violation.herokuapp.com/includes/online.data.inc.php'
            online_users = []
            
            while True:
                print("Attemping to retrieve list of online users....")
                try:
                    response = requests.get(url)
                    if response.status_code == 200:
                        data = json.loads(response.text)
                        for user in data:
                            online_users.append(int(user['usersPnumber']))
                        print("Successfully obtained list of online users")
                        break
                    else:
                        print('Error retrieving data:', response.status_code)
                except Exception as e:
                    print ("Error caught when obtaining list of online users: " + str(e) + ". Retrying in 3 seconds....")
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
                    print("Attemping to post to API....")
                    try:
                        requests.post(path)
                        print("Successfully sent message to: " + str(phoneNumber))
                        break
                    except Exception as e:
                        print("Error occured when posting to API: " + str(e) + ". Retrying in 3 seconds....") 
                        time.sleep(3)
            
            # Wait after receiving message
            time.sleep(2)
            ovsSpeed = ""
            break

        counter = counter+1

        client.loop(timeout=0)
        #print("loop end")

    out.release()

    time.sleep(1)
