from flask import Flask, request
import os
import json
import face_recognition as fr
import pymysql
import base64
import hmac
import hashlib
from phpserialize import loads, dumps

from Crypto.Cipher import AES

app = Flask(__name__)
url = "/Users/mashnoor/clock_in_projects/afsclock/application/public/assets/faces/"
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
                                 password='Kslapp@c107#',
                                 db='timesheet',
                                 charset='utf8mb4',
                                 #unix_socket='/Applications/MAMP/tmp/mysql/mysql.sock',
                                 cursorclass=pymysql.cursors.DictCursor)

    try:
        with connection.cursor() as cursor:
            # Create a new record
            sql = "SELECT reference, image_name FROM tbl_employee_faces"
            cursor.execute(sql)
            results = cursor.fetchall()
            result_list = []
            for result in results:
                result_list.append((str(result['reference']), url + result['image_name']))


    finally:
        connection.close()

    return result_list


def compare_faces(file1, file2):
    # Load the jpg files into numpy arrays
    image1 = fr.load_image_file(file1)
    image2 = fr.load_image_file(file2)

    # Get the face encodings for 1st face in each image file
    image1_encoding = fr.face_encodings(image1)[0]
    image2_encoding = fr.face_encodings(image2)[0]

    # Compare faces and return True / False
    results = fr.compare_faces([image1_encoding], image2_encoding)

    return results[0]


def face_rec(file):
    # Each face is tuple of (Name,sample image)
    known_faces = get_images_with_tag()
    """
    Return name for a known face, otherwise return 'Uknown'.
    """
    for name, known_file in known_faces:
        if compare_faces(known_file, file):
            return name
    return 'Unknown'


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
    with open("tmp.png", 'wb') as f:
        f.write(imgdata)

    file = open("tmp.png", 'rb')

    id = face_rec(file)

    print(id)
    # resp_data = {'id': id}
    return id


# When debug = True, code is reloaded on the fly while saved


# print(get_images_with_tag())
app.run(port=5002, debug=True)