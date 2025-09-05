import cv2
import numpy as np
import tensorflow as tf


interpreter = tf.lite.Interpreter(model_path="model.tflite")
interpreter.allocate_tensors()

input_details = interpreter.get_input_details()
output_details = interpreter.get_output_details()


cap = cv2.VideoCapture(0)


if not cap.isOpened():
    print("Erro ao abrir a câmera!")
    exit()


labels = [
    "A","B","C","D","E","F","G","H","I","J",
    "K","L","M","N","O","P","Q","R","S","T",
    "U","V","W","X","Y"
]

while True:

    ret, frame = cap.read()
    if not ret:
        print("Falha ao capturar o frame!")
        break

    image = cv2.resize(frame, (224, 224))
    image_rgb = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
    image_normalized = np.array(image_rgb, dtype=np.float32) / 255.0
    input_data = np.expand_dims(image_normalized, axis=0)

    interpreter.set_tensor(input_details[0]['index'], input_data)
    interpreter.invoke()

  
    output_data = interpreter.get_tensor(output_details[0]['index'])[0]

  
    prediction = np.argmax(output_data)

   
    confidence = output_data[prediction] * 100  

   
    if prediction < len(labels):
        letter = labels[prediction]
    else:
        letter = "?"

 
    cv2.putText(frame, f"Letra: {letter} ({confidence:.2f}%)", 
                (10, 30), cv2.FONT_HERSHEY_SIMPLEX, 
                1, (0, 255, 0), 2)

    cv2.imshow("Reconhecimento de Sinais", frame)

    if cv2.waitKey(1) == 27:  # Tecla ESC
        break

    if cv2.getWindowProperty("Reconhecimento de Sinais", cv2.WND_PROP_VISIBLE) < 1:
        break

cap.release()
cv2.destroyAllWindows()
