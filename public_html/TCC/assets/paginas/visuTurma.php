<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../index.php");
    exit();
}
$emailLogado = $_SESSION['email'];

// Conexão com o banco de dados
$servername = "localhost";
$username = "u357936358_librinha";
$password = "Librinh4tcc#";
$dbname = "u357936358_scanhands";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Consulta todas as turmas
$sql = "SELECT nome, qtd_aluno, qtd_atividade, qtd_aula, qtd_prof FROM turma";
$result = $conn->query($sql);
if (!$result) {
    die("Erro na consulta: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Lista de Turmas</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="icon" href="../img/librinhaSemFundo.png" type="image/png">
  <link rel="stylesheet" href="../css/padrao.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #ededed;
    }
    .main {
      padding: 20px; 
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      box-sizing: border-box;
    }
    .card-container {
      background: white;
      border-radius: 20px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      padding: 30px;
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
    }
    .table thead th {
      background-color: #f1f1f1;
      border: none;
    }
    .table tbody tr {
      border-top: 1px solid #ddd;
    }
    .table td, .table th {
      vertical-align: middle;
    }
    .btn-actions {
      border: none;
      background: none;
      font-size: 20px;
      cursor: pointer;
      position: relative;
    }
    .options-card {
      position: absolute;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
      z-index: 100;
      display: none;
      width: 180px;
      overflow: hidden;
    }
    .options-card-header {
      background-color: #0d4e96;
      color: white;
      padding: 6px 10px;
      font-size: 14px;
      display: flex;
      justify-content: flex-end;
      align-items: center;
    }
    .options-card-header span {
      cursor: pointer;
      font-size: 16px;
    }
    .options-card a {
      display: block;
      padding: 10px 15px;
      text-decoration: none;
      color: #333;
      font-size: 14px;
      background-color: white;
    }
    .options-card a:hover {
      background-color: #f5f5f5;
    }
    .header-buttons {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-bottom: 15px;
    }
  </style>
</head>
<body>

<div class="sidebar" style="margin-right: 50px;">
  <div class="sidebar-top">
    <button id="toggleSidebar">
      <img id="toggleIcon" src="../img/iconVoltar.png" style="width: 20px; height: 20px;" alt="Voltar">
    </button>
    <img src="../img/iconPerfil.png" alt="avatar" />
    <p class="email"><?php echo htmlspecialchars($emailLogado); ?></p>
    <button style="font-size: 12px;" onclick="window.location.href='perfilAluno.php'">Ver Perfil</button>
    <nav>
      <a href="inicioSecretaria.php"><img src="../img/iconHome.png" alt=""><span> Início</span></a>
      <a href="visuAlunos.php"><img src="../img/alunos.png" alt=""><span> Aluno</span></a>
      <a href="visuProf.php"><img src="../img/professor.png" alt=""><span> Professor </span></a>
      <a href="visuTurma.php"><img src="../img/turmas.png" alt=""><span> Turma </span></a>
      <a href="#"><img src="../img/iconChat.png" alt=""><span> Chat</span></a>
    </nav>
  </div>
  <a class="logout" href="../../../index.php">
    <img style="height: 40px; width: 40px;" src="../img/iconSair.png" alt="">
    <span> Sair</span>
  </a>
</div>

<div class="main">
  <div class="card-container">
    <div class="header-buttons">
      <button class="btn btn-outline-secondary">⚙️ Filtrar Turma</button>
      <button class="btn btn-outline-secondary" onclick="window.location.href='adicionaTurma.php'">➕ Adicionar Turma</button>
    </div>
    <h4>Turmas:</h4>
    <table class="table">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Quantidade de Alunos</th>
          <th>Quantidade de Atividades</th>
          <th>Quantidade de Aulas</th>
          <th>Quantidade de Professores</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row["nome"]); ?></td>
              <td><?php echo htmlspecialchars($row["qtd_aluno"]); ?></td>
              <td><?php echo htmlspecialchars($row["qtd_atividade"]); ?></td>
              <td><?php echo htmlspecialchars($row["qtd_aula"]); ?></td>
              <td><?php echo htmlspecialchars($row["qtd_prof"]); ?></td>
              <td style="position: relative;">
                <button class="btn-actions">⋮</button>
                <div class="options-card">
                  <div class="options-card-header">
                    <span class="close-menu">×</span>
                  </div>
                  <a href="excluirTurma.php?nome=<?php echo urlencode($row["nome"]); ?>">Excluir turma</a>
                  <a href="alterarTurma.php?nome=<?php echo urlencode($row["nome"]); ?>">Alterar turma</a>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="6">Nenhuma turma encontrada.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  const toggleBtn = document.getElementById('toggleSidebar');
  const sidebar = document.querySelector('.sidebar');
  toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('collapsed');
  });

  document.querySelectorAll('.btn-actions').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      document.querySelectorAll('.options-card').forEach(card => card.style.display = 'none');
      const menu = this.nextElementSibling;
      menu.style.display = 'block';
    });
  });

  document.querySelectorAll('.close-menu').forEach(closeBtn => {
    closeBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      this.closest('.options-card').style.display = 'none';
    });
  });

  document.addEventListener('click', () => {
    document.querySelectorAll('.options-card').forEach(card => card.style.display = 'none');
  });
</script>
</body>
</html>
<?php $conn->close(); ?>
