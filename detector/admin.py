from flask import Flask, request
import os
import json
import face_recognition as fr
import pymysql
import base64

import uuid

from flask_cors import CORS, cross_origin

import requests


app = Flask(__name__)
cors = CORS(app)
app.config['CORS_HEADERS'] = 'Content-Type'


def getUrl(company_name):

    return "/var/www/attendancekeeper_" + company_name + "/public/assets/login_faces/"




def my_random_string(string_length=10):
    """Returns a random string of length string_length."""
    random = str(uuid.uuid4()) # Convert UUID format to a Python string.
    random = random.upper() # Make all characters uppercase.
    random = random.replace("-","") # Remove the UUID '-'.
    return random[0:string_length] # Return the random string.


@app.route('/face_rec/<company_name>', methods=['POST'])
def face_recognition(company_name="goldenfrog"):
    
    
    image_data = str(request.form.get('image_data')).replace("data:image/jpeg;base64,", "")
    # image_data = request.form.get('image_data')
    image_name = my_random_string() + ".jpg"
    imgdata = base64.b64decode(image_data)
    with open(getUrl(company_name) + image_name, 'wb') as f:
        f.write(imgdata)

    #return "http://localhost:5004/get_id/" + company_name + "/" +  image_name
    return requests.get("http://localhost:5004/get_id/" + company_name + "/" +  image_name).text
   
@app.route('/')
def hello():
    return "blas"
    return requests.get("http://localhost:5004/get_id").text

# print(get_images_with_tag())
if __name__ == "__main__":
    app.run(host='0.0.0.0')
    
