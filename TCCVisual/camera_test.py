import cv2

print(">>> camera_test.py iniciado <<<")

cap = cv2.VideoCapture(0)
print("isOpened:", cap.isOpened())

if not cap.isOpened():
    print("Falha ao abrir a câmera!")
    exit(1)

print("Mostrando feed da câmera. Pressione ESC para sair.")
while True:
    ret, frame = cap.read()
    if not ret:
        print("Erro ao capturar frame.")
        break

    cv2.imshow("Camera Test", frame)
    if cv2.waitKey(1) & 0xFF == 27:
        break

cap.release()
cv2.destroyAllWindows()
print("Fim do teste de câmera.")
