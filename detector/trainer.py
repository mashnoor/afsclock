import face_recognition as fr
import pymysql
from sklearn import neighbors
import pickle
import math
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



def train():
    knn_algo = "auto"
    print("Training Started...")
    known_faces = get_images_with_tag()
    all_ids = []
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

    knn_clf = neighbors.KNeighborsClassifier(n_neighbors=int(round(math.sqrt(len(all_face_encodings)))), n_jobs=-1, algorithm=knn_algo, weights='distance')
    knn_clf.fit(all_face_encodings, all_ids)
    print("Training Finished...")
    with open("knn.pkl", 'wb') as f:
        pickle.dump(knn_clf, f)
    

train()