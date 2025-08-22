import cv2
import mediapipe as mp
import sys

# Lista de backends a testar
backends = [cv2.CAP_DSHOW, cv2.CAP_MSMF, cv2.CAP_VFW, cv2.CAP_ANY]
cap = None

# Tentar abrir a câmera com cada backend
for backend in backends:
    print(f"Tentando backend: {backend}")
    cap = cv2.VideoCapture(0, backend)
    if cap.isOpened():
        print(f"Câmera aberta com sucesso com backend {backend}!")
        break
    else:
        print(f"Falha ao abrir com backend {backend}.")
        cap.release()
        cap = None

if cap is None:
    print("Não foi possível abrir a câmera em nenhum backend. Verifique drivers ou outra aplicação usando a câmera.")
    sys.exit(1)

# Inicializar MediaPipe Hands
mp_hands = mp.solutions.hands
hands = mp_hands.Hands(static_image_mode=False, max_num_hands=1, min_detection_confidence=0.5)

print("Capturando vídeo, aproxime a mão da câmera...")
while True:
    ret, frame = cap.read()
    if not ret:
        print("Erro ao capturar o frame.")
        break

    rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
    result = hands.process(rgb)

    if result.multi_hand_landmarks:
        print("Mão detectada!")

    cv2.imshow("Teste MediaPipe", frame)
    if cv2.waitKey(1) & 0xFF == 27:  # ESC para sair
        break

cap.release()
cv2.destroyAllWindows()
