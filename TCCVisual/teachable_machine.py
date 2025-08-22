import cv2
import tensorflow as tf
import numpy as np
import mediapipe as mp

# Carregar o modelo tflite
interpreter = tf.lite.Interpreter(model_path="model_unquant.tflite")
interpreter.allocate_tensors()

# Obter informações do modelo
input_details = interpreter.get_input_details()
output_details = interpreter.get_output_details()

# Lista de nomes das classes
class_names = ['A', 'B', 'C']  # Ajuste conforme seu modelo

# Inicializar o MediaPipe Hands
mp_hands = mp.solutions.hands
hands = mp_hands.Hands(static_image_mode=False, max_num_hands=1, min_detection_confidence=0.5)
mp_draw = mp.solutions.drawing_utils

# Abrir a câmera
cap = cv2.VideoCapture(0, cv2.CAP_DSHOW)

if not cap.isOpened():
    print("Erro ao abrir a câmera")
    exit()

print("Câmera aberta com sucesso!")

while True:
    ret, frame = cap.read()

    if not ret:
        print("Erro ao capturar o frame.")
        break

    if frame is None:
        print("Frame vazio, tentando novamente...")
        continue

    # Converte a imagem para RGB (necessário para o MediaPipe)
    image = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
    image.flags.writeable = False
    results = hands.process(image)
    image.flags.writeable = True

    # Se mãos são detectadas, processar a primeira mão detectada
    if results.multi_hand_landmarks:
        for hand_landmarks in results.multi_hand_landmarks:
            # Desenhar os pontos da mão (opcional, para visualização)
            mp_draw.draw_landmarks(frame, hand_landmarks, mp_hands.HAND_CONNECTIONS)

            # Obter as coordenadas da mão
            h, w, _ = frame.shape
            hand_points = []
            for landmark in hand_landmarks.landmark:
                hand_points.append((int(landmark.x * w), int(landmark.y * h)))

            # Definir uma região de interesse (ROI) com base nos pontos da mão
            x_min = min(hand_points, key=lambda p: p[0])[0]
            y_min = min(hand_points, key=lambda p: p[1])[1]
            x_max = max(hand_points, key=lambda p: p[0])[0]
            y_max = max(hand_points, key=lambda p: p[1])[1]

            # Cortar a imagem para focar na mão
            roi = frame[y_min:y_max, x_min:x_max]

            # Se a ROI não for muito pequena, fazer o pré-processamento e passar para o modelo
            if roi.size > 0:
                # Redimensiona a ROI para o tamanho esperado pelo modelo
                image = cv2.cvtColor(roi, cv2.COLOR_BGR2RGB)
                image = cv2.resize(image, (224, 224))
                image = np.expand_dims(image, axis=0)
                image = image.astype(np.float32)

                # Inferência
                interpreter.set_tensor(input_details[0]['index'], image)

                try:
                    interpreter.invoke()
                except Exception as e:
                    print(f"Erro na inferência: {e}")
                    break

                output_data = interpreter.get_tensor(output_details[0]['index'])
                prediction = output_data[0]

                # Determinar a classe com maior probabilidade
                class_id = np.argmax(prediction)
                confidence = prediction[class_id]

                confidence_threshold = 0.80  # Limite mínimo de confiança

                if confidence > confidence_threshold:
                    predicted_label = class_names[class_id]
                else:
                    predicted_label = "Desconhecido"

                # Mostrar as probabilidades de cada classe (debug visual)
                for i, prob in enumerate(prediction):
                    text = f"{class_names[i]}: {prob:.2f}"
                    cv2.putText(frame, text, (10, 60 + i * 30), cv2.FONT_HERSHEY_SIMPLEX, 0.7, (255, 255, 255), 2)

                # Mostrar o resultado final
                cv2.putText(frame, f"Predição: {predicted_label}", (10, 30), cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 255, 0), 2)

    # Exibir o frame com as informações de predição
    cv2.imshow("Reconhecimento de Sinais", frame)

    if cv2.waitKey(1) & 0xFF == 27:
        break

cap.release()
cv2.destroyAllWindows()
