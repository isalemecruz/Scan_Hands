<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ../../../index.php");
    exit();
}

$host = "localhost";
$usuario = "u357936358_librinha";
$senha = "Librinh4tcc#";
$banco = "u357936358_scanhands";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Erro de conexão com o banco: " . $conn->connect_error);
}

$turmas = [];
$resTurmas = $conn->query("SELECT nome FROM turma");
if ($resTurmas && $resTurmas->num_rows > 0) {
    while ($row = $resTurmas->fetch_assoc()) {
        $turmas[] = $row['nome'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $turma = $_POST['turma'] ?? '';
    $emailNovo = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $senhaUsuario = $_POST['senha'] ?? '';
    $cpf = $_POST['cpf'] ?? '';

    if ($nome && $turma && $emailNovo && $telefone && $senhaUsuario && $cpf && isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fotoBinario = file_get_contents($_FILES['foto']['tmp_name']);
        $senhaHash = password_hash($senhaUsuario, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO usuario (foto_perfil, nome, email, cpf, telefone, senha, tipo) VALUES (?, ?, ?, ?, ?, ?, 'aluno')");
        $stmt->bind_param("bsssss", $fotoBinario, $nome, $emailNovo, $cpf, $telefone, $senhaUsuario);
        $stmt->send_long_data(0, $fotoBinario);

        if ($stmt->execute()) {
            $novoId = $stmt->insert_id;
            $stmtAluno = $conn->prepare("INSERT INTO aluno (id_usuario, turma) VALUES (?, ?)");
            $stmtAluno->bind_param("is", $novoId, $turma);
            $stmtAluno->execute();
            $stmtAluno->close();
            echo "<script>alert('Aluno cadastrado com sucesso!'); window.location.href='visuAlunos.php';</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar aluno: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Preencha todos os campos e selecione uma foto.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Adicionar Aluno</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="icon" href="../img/librinhaSemFundo.png" type="image/png">
    <link rel="icon" href="../img/librinhaSemFundo.png" type="image/png">
<style>
    * { box-sizing: border-box; font-family: 'Poppins', sans-serif; margin: 0; padding: 0; }
    body { background-color: #eaeaea; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
    .container { background-color: white; max-width: 500px; width: 100%; padding: 60px 30px 50px; border-radius: 30px; min-height: 650px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); position: relative; }
    .back-button { position: absolute; top: 20px; left: 20px; font-size: 20px; color: black; cursor: pointer; }
    .title { text-align: center; margin-bottom: 20px; color: #3E6CAE; font-size: 24px; font-weight: 600; }
    .profile-wrapper { text-align: center; margin-bottom: 10px; }
    .profile-pic { width: 100px; height: 100px; border-radius: 50%; background-color: #ccc; display: flex; align-items: center; justify-content: center; font-size: 50px; color: #555; margin: 0 auto 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden; }
    .change-photo-btn { font-size: 12px; color: #3E6CAE; background: none; border: none; cursor: pointer; text-decoration: underline; display: inline-block; margin-top: 5px; }
    .form-group { margin-bottom: 20px; position: relative; }
    .form-group label { display: block; font-size: 14px; color: #444; margin-bottom: 4px; }
    .form-group input, .form-group select { width: 100%; padding: 10px 35px 10px 10px; border: none; border-bottom: 1.5px solid #000; font-size: 14px; background-color: transparent; outline: none; }
    .form-group i { position: absolute; right: 10px; top: 35px; transform: translateY(-50%); color: #555; pointer-events: none; }
    .turma-select { background-color: #3E6CAE; color: black; padding: 8px 35px 8px 15px; border-radius: 50px; border: none; appearance: none; -webkit-appearance: none; -moz-appearance: none; background-image: url('data:image/svg+xml;utf8,<svg fill="white" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>'); background-repeat: no-repeat; background-position: right 10px center; background-size: 15px; cursor: pointer; }
    .alterar-btn { width: 100%; margin-top: 20px; padding: 12px; background-color: #3E6CAE; color: white; border: none; border-radius: 12px; font-size: 16px; cursor: pointer; }
    input[type="file"] { display: none; }
</style>
</head>
<body>

<div class="container">
    <div class="back-button" onclick="window.history.back()"><i class="fas fa-arrow-left"></i></div>
    <h2 class="title">Adicionar Aluno</h2>

    <form method="POST" enctype="multipart/form-data">
        <div class="profile-wrapper">
            <div class="profile-pic" id="profileImage"><i class="fas fa-user"></i></div>
            <label for="fileInput" class="change-photo-btn">Alterar foto</label>
            <input type="file" id="fileInput" name="foto" accept="image/*" onchange="previewImage(event)">
        </div>

        <div class="form-group">
            <label>Nome do aluno</label>
            <input type="text" name="nome" required>
            <i class="fas fa-user"></i>
        </div>

        <div class="form-group">
            <label>CPF</label>
            <input type="text" name="cpf" required>
            <i class="fas fa-id-card"></i>
        </div>

        <div class="form-group">
            <label>Turma</label>
            <select class="turma-select" name="turma" required>
                <?php foreach ($turmas as $t): ?>
                    <option value="<?= htmlspecialchars($t) ?>"><?= htmlspecialchars($t) ?></option>
                <?php endforeach; ?>
            </select>
            <i class="fas fa-users"></i>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
            <i class="fas fa-envelope"></i>
        </div>
        
        <div class="form-group">
            <label>Telefone</label>
            <input type="text" name="telefone" required>
            <i class="fas fa-mobile-alt"></i>
        </div>

        <div class="form-group">
            <label>Senha</label>
            <input type="password" name="senha" required>
            <i class="fas fa-lock"></i>
        </div>

        <button type="submit" class="alterar-btn">Cadastrar Aluno</button>
    </form>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            document.getElementById('profileImage').innerHTML = `<img src="${reader.result}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">`;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

</body>
</html>
