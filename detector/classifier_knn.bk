import sys
import pickle
import base64
import face_recognition as fr

url = "/var/www/attendancekeeper/public/assets/faces/"
model_url = "/var/www/attendancekeeper/detector/"

def load_knn():
    with open(model_url + "knn.pkl", 'rb') as f:
        clf = pickle.load(f)
    return clf

def face_recognition():
   
    clf = load_knn()
  

    file = open(url + "tmp.png", 'rb')

    loaded_image = fr.load_image_file(file)
    #loaded_image_face_locations = fr.face_locations(loaded_image, model="cnn")
    #loaded_image_encoding =  fr.face_encodings(loaded_image,known_face_locations=loaded_image_face_locations)[0]
    loaded_image_encoding =  fr.face_encodings(loaded_image)[0]
    id = clf.predict([loaded_image_encoding])

    
    return id[0]



print(face_recognition())