import cv2
import mediapipe as mp

print(">>> hands_test.py iniciado <<<")

cap = cv2.VideoCapture(0)
if not cap.isOpened():
    print("Falha ao abrir a câmera!")
    exit(1)

mp_hands = mp.solutions.hands
hands = mp_hands.Hands(min_detection_confidence=0.5)

print("Agora rodando MediaPipe. Aproxima a mão e veja o console. ESC para sair.")
while True:
    ret, frame = cap.read()
    if not ret: break

    rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
    res = hands.process(rgb)
    if res.multi_hand_landmarks:
        print("Mão detectada!")

    cv2.imshow("Hands Test", frame)
    if cv2.waitKey(1) & 0xFF == 27:
        break

cap.release()
cv2.destroyAllWindows()
print("Fim do teste MediaPipe.")
