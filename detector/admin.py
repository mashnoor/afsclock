from flask import Flask, request
import os
import json
import face_recognition as fr
import pymysql
import base64
import hmac
import hashlib
from phpserialize import loads, dumps
from flask_cors import CORS, cross_origin

import requests
from Crypto.Cipher import AES
import pickle
import subprocess

app = Flask(__name__)
cors = CORS(app)
app.config['CORS_HEADERS'] = 'Content-Type'
url = "/var/www/attendancekeeper/public/assets/faces/"
model_url = "/var/www/attendancekeeper/detector/"

key = b'GKUE0hDEQ69y6bEaBFrehXVKlxw4G5T31/gjQ4SLD6U='


def get_images_with_tag():
    connection = pymysql.connect(host='127.0.0.1',
                                 user='root',
                                 password='Mashnoor11',
                                 db='timesheet',
                                 charset='utf8mb4',
                                 #unix_socket='/Applications/MAMP/tmp/mysql/mysql.sock',
                                 cursorclass=pymysql.cursors.DictCursor)

    try:
        with connection.cursor() as cursor:
            # Create a new record
            sql = "SELECT image_name, users.idno FROM tbl_employee_faces INNER JOIN users ON tbl_employee_faces.reference=users.reference"
            cursor.execute(sql)
            results = cursor.fetchall()
            result_list = []
            for result in results:
                result_list.append((str(result['idno']), url + result['image_name']))


    finally:
        connection.close()

    return result_list


def face_rec(file):
    # Each face is tuple of (Name,sample image)
    known_faces = get_images_with_tag()
    all_ids = []
    """
    Return name for a known face, otherwise return 'Uknown'.
    """
    all_face_encodings = []
    for name, known_file in known_faces:
        
        try:
            
            loaded_image = fr.load_image_file(known_file)
            loaded_image_encoding =  fr.face_encodings(loaded_image)[0]
            all_face_encodings.append(loaded_image_encoding)
            all_ids.append(name)
            #print(name + " - " + known_file)
        except:
            pass

    detect_face_file = fr.load_image_file(file)
    detect_face_file_encoding = fr.face_encodings(detect_face_file)[0]
    get_loc = compare_faces(all_face_encodings, detect_face_file_encoding)
    if get_loc != -1:
        return str(all_ids[get_loc])
    else:
        return "-1"

def load_knn():
    with open(model_url + "knn.pkl", 'rb') as f:
        clf = pickle.load(f)
    return clf



@app.route('/face_rec', methods=['POST'])
def face_recognition():
    
    image_data = str(request.form.get('image_data')).replace("data:image/jpeg;base64,", "")
    # image_data = request.form.get('image_data')
    imgdata = base64.b64decode(image_data)
    with open(url + "tmp.png", 'wb') as f:
        f.write(imgdata)

    return requests.get("http://localhost:5004/get_id").text
    file = open(url + "tmp.png", 'rb')
    proc = subprocess.Popen(['python3', '/var/www/attendancekeeper/detector/classifier_knn.py'], stdout=subprocess.PIPE, stderr=subprocess.STDOUT)
    return(proc.communicate()[0])
    return "Done"

    loaded_image = fr.load_image_file(file)
    #loaded_image_face_locations = fr.face_locations(loaded_image, model="cnn")
    #loaded_image_encoding =  fr.face_encodings(loaded_image,known_face_locations=loaded_image_face_locations)[0]
    loaded_image_encoding =  fr.face_encodings(loaded_image)[0]
    id = clf.predict([loaded_image_encoding])

    
    return id[0]


@app.route('/')
def hello():
    return "blas"
    return requests.get("http://localhost:5004/get_id").text

# print(get_images_with_tag())
if __name__ == "__main__":
    app.run(host='0.0.0.0')
    
