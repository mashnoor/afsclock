from flask import Flask, request
import os
import json
import face_recognition as fr
import pymysql
import base64
import hmac
import hashlib

import pickle
from phpserialize import loads, dumps
from flask_cors import CORS, cross_origin
import math

app = Flask(__name__)
cors = CORS(app)
app.config['CORS_HEADERS'] = 'Content-Type'
url = "/var/www/attendancekeeper/public/assets/faces/"


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



def load_knn():
    with open("knn.pkl", 'rb') as f:
        clf = pickle.load(f)
    return clf


def train():
    knn_algo = "ball_tree"
    print("Training Started...")
    known_faces = get_images_with_tag()
    all_ids = []
    """
    Return name for a known face, otherwise return 'Uknown'.
    """
    all_face_encodings = []
    for name, known_file in known_faces:
        
        try:
            
            
            loaded_image = fr.load_image_file(known_file)
            #loaded_image_face_locations = fr.face_locations(loaded_image, number_of_times_to_upsample=0, model="cnn")
            #loaded_image_encoding =  fr.face_encodings(loaded_image, known_face_locations=loaded_image_face_locations)[0]
            loaded_image_encoding =  fr.face_encodings(loaded_image)[0]
            all_face_encodings.append(loaded_image_encoding)
            all_ids.append(name)
            print("Training: " + name)
        except:
            pass

    knn_clf = neighbors.KNeighborsClassifier(n_neighbors=int(round(math.sqrt(len(all_face_encodings)))), algorithm=knn_algo, weights='distance')
    knn_clf.fit(all_face_encodings, all_ids)
    print("Training Finished...")
    with open("knn.pkl", 'wb') as f:
        pickle.dump(knn_clf, f)
    


@app.route('/face_rec', methods=['POST'])
def face_recognition():
    clf = load_knn()

    image_data = str(request.form.get('image_data')).replace("data:image/jpeg;base64,", "")
    # image_data = request.form.get('image_data')
    imgdata = base64.b64decode(image_data)
    with open(url + "tmp.png", 'wb') as f:
        f.write(imgdata)

    file = open(url + "tmp.png", 'rb')

    loaded_image = fr.load_image_file(file)
    #loaded_image_face_locations = fr.face_locations(loaded_image, model="cnn")
    #loaded_image_encoding =  fr.face_encodings(loaded_image,known_face_locations=loaded_image_face_locations)[0]
    loaded_image_encoding =  fr.face_encodings(loaded_image)[0]
    id = clf.predict([loaded_image_encoding])

    
    return id[0]




if __name__ == "__main__":
    
    app.run()