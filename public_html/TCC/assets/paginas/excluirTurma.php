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

// Pega o nome da turma da URL
if (!isset($_GET['nome'])) {
    die("Nome da turma não informado.");
}
$nomeTurma = $_GET['nome'];

// Busca os dados da turma
$sql = "SELECT * FROM turma WHERE nome = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nomeTurma);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Turma não encontrada.");
}

$turma = $result->fetch_assoc();
$stmt->close();

// Excluir turma
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $deleteSql = "DELETE FROM turma WHERE nome=?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("s", $nomeTurma);

    if ($deleteStmt->execute()) {
        header("Location: visuTurma.php");
        exit();
    } else {
        echo "Erro ao excluir: " . $conn->error;
    }
    $deleteStmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Excluir Turma</title>
<link rel="icon" href="../img/librinhaSemFundo.png" type="image/png">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* { font-family: 'Poppins', sans-serif; box-sizing: border-box; }
body { background-color: #eaeaea; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin:0; }
.container { background-color: white; max-width: 500px; width: 100%; padding: 60px 30px 50px; border-radius: 30px; min-height: 600px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); position: relative; }
.back-button { position: absolute; top: 20px; left: 20px; font-size: 20px; color: black; cursor: pointer; background: none; border: none; }
.title { text-align: center; margin-bottom: 20px; color: #3E6CAE; font-size: 24px; font-weight: 600; }
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
    <button class="back-button" onclick="window.location.href='visuTurma.php'"><i class="fas fa-arrow-left"></i></button>
    <h2 class="title">Excluir Turma</h2>

    <form method="POST">
        <div class="form-group">
            <label>Nome da Turma</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($turma['nome']); ?>" disabled>
            <i class="fas fa-users"></i>
        </div>

        <div class="form-group">
            <label>Quantidade de Alunos</label>
            <input type="text" name="qtd_aluno" value="<?php echo htmlspecialchars($turma['qtd_aluno']); ?>" disabled>
            <i class="fas fa-user-graduate"></i>
        </div>

        <div class="form-group">
            <label>Quantidade de Atividades</label>
            <input type="text" name="qtd_atividade" value="<?php echo htmlspecialchars($turma['qtd_atividade']); ?>" disabled>
            <i class="fas fa-tasks"></i>
        </div>

        <div class="form-group">
            <label>Quantidade de Aulas</label>
            <input type="text" name="qtd_aula" value="<?php echo htmlspecialchars($turma['qtd_aula']); ?>" disabled>
            <i class="fas fa-chalkboard-teacher"></i>
        </div>

        <div class="form-group">
            <label>Quantidade de Professores</label>
            <input type="text" name="qtd_prof" value="<?php echo htmlspecialchars($turma['qtd_prof']); ?>" disabled>
            <i class="fas fa-user-tie"></i>
        </div>

        <button type="submit" class="alterar-btn">Excluir</button>
    </form>
</div>

</body>
</html>
