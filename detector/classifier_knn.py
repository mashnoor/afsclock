#!/usr/bin/python
# -*- coding: utf-8 -*-
import sys
import pickle
import base64
import face_recognition as fr
from flask import Flask, request

app = Flask(__name__)


def getUrl(company_name):
    return '/var/www/attendancekeeper_' + company_name \
        + '/public/assets/login_faces/'


def getModelUrl(company_name):

    return '/var/www/attendancekeeper_' + company_name + '/detector/'


def load_knn(company_name):
    with open(getModelUrl(company_name) + 'knn.pkl', 'rb') as f:
        clf = pickle.load(f)
    return clf


@app.route('/get_id/<company_name>/<image_name>')
def face_recognition(company_name, image_name):

    clf = load_knn(company_name)

    file = open(getUrl(company_name) + image_name, 'rb')

    try:
        loaded_image = fr.load_image_file(file)
        #loaded_image_face_locations = fr.face_locations(loaded_image,
        #        model='cnn')
        #loaded_image_encoding = fr.face_encodings(loaded_image,
        #       known_face_locations=loaded_image_face_locations)[0]

        loaded_image_encoding =  fr.face_encodings(loaded_image)[0]

        id = clf.predict([loaded_image_encoding])
        probabilities = clf.predict_proba([loaded_image_encoding])
        prob = max(probabilities[0])
        print("Detect Confidence: " + str(prob))
        if prob > 0.6:
            return id[0]
        else:
            return 'unknown face'
    except:

        return "couldn't detect face"

if __name__ == '__main__':
    app.run(host='0.0.0.0')


			
