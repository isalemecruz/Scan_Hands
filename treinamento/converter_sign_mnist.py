import os
import shutil
import pandas as pd
import numpy as np
from PIL import Image
import zipfile

# Caminho do CSV (ajuste para onde você extraiu o archive.zip)
train_csv = "sign_mnist_train.csv"
test_csv = "sign_mnist_test.csv"
output_path = "sign_mnist_images"
output_zip = "sign_mnist_images.zip"

# Mapeamento: não existe J e Z
label_map = {
    0:"A", 1:"B", 2:"C", 3:"D", 4:"E",
    5:"F", 6:"G", 7:"H", 8:"I",
    10:"K", 11:"L", 12:"M", 13:"N", 14:"O",
    15:"P", 16:"Q", 17:"R", 18:"S", 19:"T",
    20:"U", 21:"V", 22:"W", 23:"X", 24:"Y"
}

def csv_to_images(csv_file, split_name):
    df = pd.read_csv(csv_file)
    labels = df['label'].values
    images = df.drop('label', axis=1).values

    for idx, (label, flat_img) in enumerate(zip(labels, images)):
        if label not in label_map:
            continue  # pula labels inexistentes

        letter = label_map[label]
        folder = os.path.join(output_path, split_name, letter)
        os.makedirs(folder, exist_ok=True)

        # Converter array em imagem 28x28
        img_array = flat_img.reshape(28, 28).astype(np.uint8)
        img = Image.fromarray(img_array, mode="L")

        # Salvar como PNG
        img.save(os.path.join(folder, f"{split_name}_{letter}_{idx}.png"))

# Limpar se já existir
if os.path.exists(output_path):
    shutil.rmtree(output_path)

# Converter os dois CSVs
csv_to_images(train_csv, "train")
csv_to_images(test_csv, "test")

# Compactar em ZIP
shutil.make_archive(output_zip.replace(".zip", ""), 'zip', output_path)

print(f"✅ Dataset convertido e salvo em {output_zip}")
