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

// Verifica se o nome da turma foi passado
if (!isset($_GET['nome'])) {
    die("Nome da turma não informado.");
}
$nomeTurma = $_GET['nome'];

// Busca os dados da turma
$stmt = $conn->prepare("SELECT * FROM turma WHERE nome = ?");
$stmt->bind_param("s", $nomeTurma);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Turma não encontrada.");
}

$turma = $result->fetch_assoc();
$stmt->close();

// Atualiza os dados da turma
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novoNome = $_POST['nome'] ?? '';
    $qtdAluno = $_POST['qtd_aluno'] ?? '';
    $qtdAtividade = $_POST['qtd_atividade'] ?? '';
    $qtdAula = $_POST['qtd_aula'] ?? '';
    $qtdProf = $_POST['qtd_prof'] ?? '';

    if ($novoNome && is_numeric($qtdAluno) && is_numeric($qtdAtividade) && is_numeric($qtdAula) && is_numeric($qtdProf)) {
        $updateStmt = $conn->prepare("UPDATE turma SET nome=?, qtd_aluno=?, qtd_atividade=?, qtd_aula=?, qtd_prof=? WHERE nome=?");
        $updateStmt->bind_param("siiiis", $novoNome, $qtdAluno, $qtdAtividade, $qtdAula, $qtdProf, $nomeTurma);

        if ($updateStmt->execute()) {
            echo "<script>alert('Turma atualizada com sucesso!'); window.location.href='visuTurma.php';</script>";
        } else {
            echo "<script>alert('Erro ao atualizar turma: " . $updateStmt->error . "');</script>";
        }

        $updateStmt->close();
    } else {
        echo "<script>alert('Preencha todos os campos corretamente.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Alterar Turma</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="icon" href="../img/librinhaSemFundo.png" type="image/png">
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
    }
    body {
      background-color: #eaeaea;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .container {
      background-color: white;
      max-width: 500px;
      width: 100%;
      padding: 60px 30px 50px;
      border-radius: 30px;
      min-height: 650px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.05);
      position: relative;
    }
    .back-button {
      position: absolute;
      top: 20px;
      left: 20px;
      font-size: 20px;
      color: black;
      cursor: pointer;
    }
    .title {
      text-align: center;
      margin-bottom: 20px;
      color: #3E6CAE;
      font-size: 24px;
      font-weight: 600;
    }
    .form-group {
      margin-bottom: 20px;
      position: relative;
    }
    .form-group label {
      display: block;
      font-size: 14px;
      color: #444;
      margin-bottom: 4px;
    }
    .form-group input {
      width: 100%;
      padding: 10px 35px 10px 10px;
      border: none;
      border-bottom: 1.5px solid #000;
      font-size: 14px;
      background-color: transparent;
      outline: none;
    }
    .form-group i {
      position: absolute;
      right: 10px;
      top: 35px;
      transform: translateY(-50%);
      color: #555;
      pointer-events: none;
    }
    .alterar-btn {
      width: 100%;
      margin-top: 20px;
      padding: 12px;
      background-color: #3E6CAE;
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="back-button" onclick="window.location.href='visuTurma.php'"><i class="fas fa-arrow-left"></i></div>
    <h2 class="title">Alterar Turma</h2>
    <form method="POST">
      <div class="form-group">
        <label>Nome da Turma</label>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($turma['nome']); ?>" required>
        <i class="fas fa-users"></i>
      </div>
      <div class="form-group">
        <label>Quantidade de Alunos</label>
        <input type="number" name="qtd_aluno" value="<?php echo htmlspecialchars($turma['qtd_aluno']); ?>" required>
        <i class="fas fa-user-graduate"></i>
      </div>
      <div class="form-group">
        <label>Quantidade de Atividades</label>
        <input type="number" name="qtd_atividade" value="<?php echo htmlspecialchars($turma['qtd_atividade']); ?>" required>
        <i class="fas fa-tasks"></i>
      </div>
      <div class="form-group">
        <label>Quantidade de Aulas</label>
        <input type="number" name="qtd_aula" value="<?php echo htmlspecialchars($turma['qtd_aula']); ?>" required>
        <i class="fas fa-chalkboard-teacher"></i>
      </div>
      <div class="form-group">
        <label>Quantidade de Professores</label>
        <input type="number" name="qtd_prof" value="<?php echo htmlspecialchars($turma['qtd_prof']); ?>" required>
        <i class="fas fa-user-tie"></i>
      </div>
      <button type="submit" class="alterar-btn">Salvar Alterações</button>
    </form>
  </div>
</body>
</html>
