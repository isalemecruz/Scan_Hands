import cv2
import mediapipe as mp
import sys

print(">>> camera_hands_test.py iniciado <<<", flush=True)

# Abrir câmera com CAP_ANY
cap = cv2.VideoCapture(0, cv2.CAP_ANY)
print(f"isOpened: {cap.isOpened()}", flush=True)
if not cap.isOpened():
    print("Falha ao abrir a câmera!", flush=True)
    sys.exit(1)

# Inicializar MediaPipe Hands
mp_hands = mp.solutions.hands
hands = mp_hands.Hands(
    static_image_mode=False,
    max_num_hands=1,
    min_detection_confidence=0.5
)

print("Capturando vídeo com MediaPipe. Aproxima a mão. ESC para sair.", flush=True)
while True:
    ret, frame = cap.read()
    if not ret:
        print("Erro ao capturar frame.", flush=True)
        break

    rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
    result = hands.process(rgb)
    if result.multi_hand_landmarks:
        print("Mão detectada!", flush=True)

    cv2.imshow("Camera + Hands Test", frame)
    if cv2.waitKey(1) & 0xFF == 27:
        break

cap.release()
cv2.destroyAllWindows()
print("Fim do teste.", flush=True)
