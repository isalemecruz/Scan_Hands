import os
from PIL import Image

pasta = r"C:\Users\Patrick\OneDrive\Área de Trabalho\treinamento\dataset"  # ajuste para o caminho certo

arquivos_removidos = []

for root, _, files in os.walk(pasta):
    for f in files:
        caminho = os.path.join(root, f)
        try:
            img = Image.open(caminho)
            img.verify()  # só verifica se é imagem
        except Exception as e:
            print("Removendo arquivo inválido:", caminho, "| Erro:", e)
            arquivos_removidos.append(caminho)
            os.remove(caminho)

print(f"\nConferência finalizada! {len(arquivos_removidos)} arquivos inválidos removidos.")
