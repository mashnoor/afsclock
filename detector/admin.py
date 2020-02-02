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

from Crypto.Cipher import AES

app = Flask(__name__)
cors = CORS(app)
app.config['CORS_HEADERS'] = 'Content-Type'
url = "/var/www/attendancekeeper/public/assets/faces/"
key = b'GKUE0hDEQ69y6bEaBFrehXVKlxw4G5T31/gjQ4SLD6U='


def mcrypt_decrypt(value, iv):
    global key
    AES.key_size = 128
    crypt_object = AES.new(key=key, mode=AES.MODE_CBC, IV=iv)
    return crypt_object.decrypt(value)


def mcrypt_encrypt(value, iv):
    global key
    AES.key_size = 128
    crypt_object = AES.new(key=key, mode=AES.MODE_CBC, IV=iv)
    return crypt_object.encrypt(value)


def decrypt(bstring):
    global key
    dic = json.loads(base64.b64decode(bstring).decode())
    mac = dic['mac']
    value = bytes(dic['value'], 'utf-8')
    iv = bytes(dic['iv'], 'utf-8')
    if mac == hmac.new(key, iv + value, hashlib.sha256).hexdigest():
        return loads(mcrypt_decrypt(base64.b64decode(value), base64.b64decode(iv))).decode()
    return ''


def encrypt(string):
    global key
    iv = os.urandom(16)
    string = dumps(string)
    padding = 16 - len(string) % 16
    string += bytes(chr(padding) * padding, 'utf-8')
    value = base64.b64encode(mcrypt_encrypt(string, iv))
    iv = base64.b64encode(iv)
    mac = hmac.new(key, iv + value, hashlib.sha256).hexdigest()
    dic = {'iv': iv.decode(), 'value': value.decode(), 'mac': mac}
    return base64.b64encode(bytes(json.dumps(dic), 'utf-8'))


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


def compare_faces(all_image_encodings, image_encoding):
   

    # Compare faces and return True / False
    results = fr.compare_faces(all_image_encodings, image_encoding, tolerance=0.4)
    #print(results)
    

    
    for i in range(len(results)):
        if results[i] == True:
            print(i)
            return i
    i = -1
    return i
    


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


@app.route('/face_match', methods=['POST'])
def face_match():
    if request.method == 'POST':
        # check if the post request has the file part
        if ('file1' in request.files) and ('file2' in request.files):
            file1 = request.files.get('file1')
            file2 = request.files.get('file2')
            ret = compare_faces(file1, file2)
            resp_data = {"match": bool(ret)}  # convert numpy._bool to bool for json.dumps
            return json.dumps(resp_data)


@app.route('/face_rec', methods=['POST'])
def face_recognition():
    image_data = str(request.form.get('image_data')).replace("data:image/jpeg;base64,", "")
    # image_data = request.form.get('image_data')
    imgdata = base64.b64decode(image_data)
    with open(url + "tmp.png", 'wb') as f:
        f.write(imgdata)

    file = open(url + "tmp.png", 'rb')

    id = face_rec(file)

    print(id)
    # resp_data = {'id': id}
    return id


# When debug = True, code is reloaded on the fly while saved

@app.route('/')
def hello():
    return "hello"

# print(get_images_with_tag())
if __name__ == "__main__":
    app.run()