from flask import Flask, request
import os
import json
import face_recognition as fr
import pymysql
import base64

app = Flask(__name__)
url = "/Users/mashnoor/clock_in_projects/afsclock/application/public/assets/faces/"


def get_images_with_tag():
    connection = pymysql.connect(host='127.0.0.1',
                                 user='root',
                                 password='root',
                                 db='timesheet',
                                 charset='utf8mb4',
                                 unix_socket='/Applications/MAMP/tmp/mysql/mysql.sock',
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
    #image_data = request.form.get('image_data')
    imgdata = base64.b64decode(image_data)
    with open("tmp.png", 'wb') as f:
        f.write(imgdata)

    file = open("tmp.png", 'rb')

    name = face_rec(file)
    #name = "hello"
    print(name)
    resp_data = {'name': name}
    return json.dumps(resp_data)


# When debug = True, code is reloaded on the fly while saved


#print(get_images_with_tag())
app.run(port=5003, debug=True)
