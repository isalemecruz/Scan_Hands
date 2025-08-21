<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../index.php");
    exit();
}

$emailLogado = $_SESSION['email'];

// Conexão com o banco
$servername = "localhost";
$username = "u357936358_librinha";
$password = "Librinh4tcc#";
$dbname = "u357936358_scanhands";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Pega o CPF da URL
if (!isset($_GET['cpf'])) {
    die("CPF não informado.");
}
$cpf = $_GET['cpf'];

// Busca os dados do aluno (incluindo foto BLOB)
$sql = "SELECT * FROM usuario WHERE cpf = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cpf);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Aluno não encontrado.");
}

$aluno = $result->fetch_assoc();
$stmt->close();

// Atualizar aluno
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];

    if (!empty($_FILES['foto']['tmp_name'])) {
        $fotoData = file_get_contents($_FILES['foto']['tmp_name']);
        $updateSql = "UPDATE usuario SET nome=?, email=?, telefone=?, senha=?, foto_perfil=? WHERE cpf=?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ssssss", $nome, $email, $telefone, $senha, $fotoData, $cpf);
    } else {
        $updateSql = "UPDATE usuario SET nome=?, email=?, telefone=?, senha=? WHERE cpf=?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("sssss", $nome, $email, $telefone, $senha, $cpf);
    }

    if ($updateStmt->execute()) {
        header("Location: visuProf.php");
        exit();
    } else {
        echo "Erro ao atualizar: " . $conn->error;
    }
    $updateStmt->close();
}

$conn->close();

// Converte a foto atual para Base64 (se existir)
$fotoBase64 = !empty($aluno['foto_perfil']) ? 'data:image/jpeg;base64,' . base64_encode($aluno['foto_perfil']) : '../img/iconPerfil.png';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Alterar Professor</title>
    <link rel="icon" href="../img/librinhaSemFundo.png" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* {
    font-family: 'Poppins', sans-serif;
    box-sizing: border-box;
}
body { font-family: 'Poppins', sans-serif; background-color: #eaeaea; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin:0; }
.container { background-color: white; max-width: 500px; width: 100%; padding: 60px 30px 50px; border-radius: 30px; min-height: 700px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); position: relative; }
.back-button { position: absolute; top: 20px; left: 20px; font-size: 20px; color: black; cursor: pointer; background: none; border: none; }
.title { text-align: center; margin-bottom: 20px; color: #3E6CAE; font-size: 24px; font-weight: 600; }
.profile-wrapper { text-align: center; margin-bottom: 10px; }
.profile-pic { width: 100px; height: 100px; border-radius: 50%; background-color: #ccc; display: flex; align-items: center; justify-content: center; font-size: 50px; color: #555; margin: 0 auto 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden; }
.change-photo-btn { font-size: 12px; color: #3E6CAE; background: none; border: none; cursor: pointer; text-decoration: underline; display: inline-block; margin-top: 5px; }
.form-group { margin-bottom: 20px; position: relative; }
.form-group label { display: block; font-size: 14px; color: #444; margin-bottom: 4px; }
.form-group input, .form-group select { width: 100%; padding: 10px 0px 10px 0px; border: none; border-bottom: 1.5px solid #000; font-size: 14px; background-color: transparent; outline: none; }
.form-group i { position: absolute; right: 10px; top: 35px; transform: translateY(-50%); color: #555; pointer-events: none; }
.alterar-btn { width: 100%; margin-top: 20px; padding: 20px; background-color: #3E6CAE; color: white; border: none; border-radius: 12px; font-size: 16px; cursor: pointer; }
.alterar-btn:hover { background-color: #3E6CAE; }
input[type="file"] { display: none; }
</style>
</head>
<body>

<div class="container">
    <button class="back-button" onclick="window.location.href='visuProf.php'"><i class="fas fa-arrow-left"></i></button>
    <h2 class="title">Alterar Professor</h2>

    <form method="POST" enctype="multipart/form-data">
        <div class="profile-wrapper">
            <div class="profile-pic" id="profileImage">
                <?php if(!empty($aluno['foto_perfil'])): ?>
                    <img src="<?php echo $fotoBase64; ?>" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
                <?php else: ?>
                    <i class="fas fa-user"></i>
                <?php endif; ?>
            </div>
            <label for="fileInput" class="change-photo-btn">Alterar foto</label>
            <input type="file" id="fileInput" name="foto" accept="image/*" onchange="previewImage(event)">
        </div>

        <div class="form-group">
            <label>Nome do Professor</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($aluno['nome']); ?>" required>
            <i class="fas fa-user"></i>
        </div>

        <div class="form-group">
            <label>CPF</label>
            <input type="text" name="cpf" value="<?php echo htmlspecialchars($aluno['cpf']); ?>" required>
            <i class="fas fa-id-card"></i>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($aluno['email']); ?>" required>
            <i class="fas fa-envelope"></i>
        </div>

        <div class="form-group">
            <label>Telefone</label>
            <input type="text" name="telefone" value="<?php echo htmlspecialchars($aluno['telefone']); ?>" required>
            <i class="fas fa-mobile-alt"></i>
        </div>

        <div class="form-group">
            <label>Senha</label>
            <input type="text" name="senha" value="<?php echo htmlspecialchars($aluno['senha']); ?>">
            <i class="fas fa-lock"></i>
        </div>

        <button type="submit" class="alterar-btn">Alterar</button>
    </form>
</div>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
        document.getElementById('profileImage').innerHTML = `<img src="${reader.result}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">`;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>
