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

// Busca os dados do aluno
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

// Buscar turma relacionada ao aluno
$turmaNome = 'Sem turma';

$stmtTurma = $conn->prepare("
    SELECT t.nome 
    FROM aluno a
    JOIN aluno_turma at ON a.id_aluno = at.id_aluno
    JOIN turma t ON at.id_turma = t.id_turma
    WHERE a.id_usuario = ?
    LIMIT 1
");
$stmtTurma->bind_param("i", $aluno['id_usuario']);
$stmtTurma->execute();
$resultTurma = $stmtTurma->get_result();

if ($resultTurma->num_rows > 0) {
    $turmaNome = $resultTurma->fetch_assoc()['nome'];
}

$stmtTurma->close();

// Excluir aluno e suas relações
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_usuario = $aluno['id_usuario'];

    // Buscar id_aluno
    $stmtAluno = $conn->prepare("SELECT id_aluno FROM aluno WHERE id_usuario = ?");
    $stmtAluno->bind_param("i", $id_usuario);
    $stmtAluno->execute();
    $resultAluno = $stmtAluno->get_result();

    if ($resultAluno->num_rows > 0) {
        $id_aluno = $resultAluno->fetch_assoc()['id_aluno'];

        // Excluir relações com turma
        $stmtTurmaDel = $conn->prepare("DELETE FROM aluno_turma WHERE id_aluno = ?");
        $stmtTurmaDel->bind_param("i", $id_aluno);
        $stmtTurmaDel->execute();
        $stmtTurmaDel->close();

        // Excluir da tabela aluno
        $stmtDelAluno = $conn->prepare("DELETE FROM aluno WHERE id_aluno = ?");
        $stmtDelAluno->bind_param("i", $id_aluno);
        $stmtDelAluno->execute();
        $stmtDelAluno->close();
    }

    $stmtAluno->close();

    // Excluir da tabela usuario
    $deleteSql = "DELETE FROM usuario WHERE cpf=?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("s", $cpf);

    if ($deleteStmt->execute()) {
        header("Location: visuAlunos.php");
        exit();
    } else {
        echo "Erro ao excluir: " . $conn->error;
    }

    $deleteStmt->close();
}

$conn->close();

// Foto de perfil
$fotoBase64 = !empty($aluno['foto_perfil']) ? 'data:image/jpeg;base64,' . base64_encode($aluno['foto_perfil']) : '../img/iconPerfil.png';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Excluir Aluno</title>
<link rel="icon" href="../img/librinhaSemFundo.png" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* { font-family: 'Poppins', sans-serif; box-sizing: border-box; }
body { background-color: #eaeaea; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin:0; }
.container { background-color: white; max-width: 500px; width: 100%; padding: 60px 30px 50px; border-radius: 30px; min-height: 700px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); position: relative; }
.back-button { position: absolute; top: 20px; left: 20px; font-size: 20px; color: black; cursor: pointer; background: none; border: none; }
.title { text-align: center; margin-bottom: 20px; color: #3E6CAE; font-size: 24px; font-weight: 600; }
.profile-wrapper { text-align: center; margin-bottom: 10px; }
.profile-pic { width: 100px; height: 100px; border-radius: 50%; background-color: #ccc; display: flex; align-items: center; justify-content: center; font-size: 50px; color: #555; margin: 0 auto 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden; }
.form-group { margin-bottom: 20px; position: relative; }
.form-group label { display: block; font-size: 14px; color: #444; margin-bottom: 4px; }
.form-group input { width: 100%; padding: 10px 0px; border: none; border-bottom: 1.5px solid #000; font-size: 14px; background-color: transparent; outline: none; }
.form-group i { position: absolute; right: 10px; top: 35px; transform: translateY(-50%); color: #555; pointer-events: none; }
.alterar-btn { width: 100%; margin-top: 20px; padding: 20px; background-color: #d9534f; color: white; border: none; border-radius: 12px; font-size: 16px; cursor: pointer; }
.alterar-btn:hover { background-color: #c9302c; }
</style>
</head>
<body>

<div class="container">
    <button class="back-button" onclick="window.location.href='visuAlunos.php'"><i class="fas fa-arrow-left"></i></button>
    <h2 class="title">Excluir Aluno</h2>

    <form method="POST">
        <div class="profile-wrapper">
            <div class="profile-pic" id="profileImage">
                <?php if(!empty($aluno['foto_perfil'])): ?>
                    <img src="<?php echo $fotoBase64; ?>" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
                <?php else: ?>
                    <i class="fas fa-user"></i>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label>Nome do aluno</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($aluno['nome']); ?>" disabled>
            <i class="fas fa-user"></i>
        </div>

        <div class="form-group">
            <label>CPF</label>
            <input type="text" name="cpf" value="<?php echo htmlspecialchars($aluno['cpf']); ?>" disabled>
            <i class="fas fa-id-card"></i>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($aluno['email']); ?>" disabled>
            <i class="fas fa-envelope"></i>
        </div>

        <div class="form-group">
            <label>Telefone</label>
            <input type="text" name="telefone" value="<?php echo htmlspecialchars($aluno['telefone']); ?>" disabled>
            <i class="fas fa-mobile-alt"></i>
        </div>

        <div class="form-group">
            <label>Senha</label>
            <input type="text" name="senha" value="<?php echo htmlspecialchars($aluno['senha']); ?>" disabled>
            <i class="fas fa-lock"></i>
        </div>

        <div class="form-group">
            <label>Turma</label>
            <input type="text" name="turma" value="<?php echo htmlspecialchars($turmaNome); ?>" disabled>
            <i class="fas fa-users"></i>
        </div>

        <button type="submit" class="alterar-btn">Excluir</button>
    </form>
