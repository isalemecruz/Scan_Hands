import cv2
import numpy as np
import tensorflow as tf

# Carregar o modelo TFLite
interpreter = tf.lite.Interpreter(model_path="model.tflite")
interpreter.allocate_tensors()

input_details = interpreter.get_input_details()
output_details = interpreter.get_output_details()

# Inicializar a câmera
cap = cv2.VideoCapture(0)

# Verifica a câmera 
if not cap.isOpened():
    print("Erro ao abrir a câmera!")
    exit()

while True:
    # Captura o frame da câmera
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

    output_data = interpreter.get_tensor(output_details[0]['index'])
    prediction = np.argmax(output_data)

    if prediction == 0:
        letter = "A"
    elif prediction == 1:
        letter = "B"
    elif prediction == 2:
        letter = "C"
    elif prediction == 3:
        letter = "D"
    elif prediction == 4:
        letter = "E"
    elif prediction == 5:
        letter = "F"
    elif prediction == 6:
        letter = "G"
    elif prediction == 7:
        letter = "H"
    elif prediction == 8:
        letter = "I"
    elif prediction == 9:
        letter = "J"
    elif prediction == 10:
        letter = "K"
    elif prediction == 11:
        letter = "L"
    elif prediction == 12:
        letter = "M"
    elif prediction == 13:
        letter = "N"
    elif prediction == 14:
        letter = "O"
    elif prediction == 15:
        letter = "P"
    elif prediction == 16:
        letter = "Q"
    elif prediction == 17:
        letter = "R"
    elif prediction == 18:
        letter = "S"
    elif prediction == 19:
        letter = "T"
    elif prediction == 20:
        letter = "U"
    elif prediction == 21:
        letter = "V"
    elif prediction == 22:
        letter = "W"
    elif prediction == 23:
        letter = "X"
    elif prediction == 24:
        letter = "Y"
    else:
        letter = "?"

    cv2.putText(frame, f"Letra prevista: {letter}", (10, 30),
                cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 255, 0), 2)

    cv2.imshow("Reconhecimento de Sinais", frame)

    if cv2.waitKey(1) == 27:  # Tecla ESC
        break

    if cv2.getWindowProperty("Reconhecimento de Sinais", cv2.WND_PROP_VISIBLE) < 1:
        break

cap.release()
cv2.destroyAllWindows()
