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

// Limpar filtros
if (!empty($_GET['limpar'])) {
    header("Location: visuAlunos.php");
    exit();
}

// Monta a consulta com filtros
$conditions = ["tipo = 'aluno'"];
$params = [];

$campos = ['nome', 'turma', 'cpf', 'telefone', 'email'];
foreach ($campos as $campo) {
    if (!empty($_GET[$campo])) {
        $conditions[] = "$campo LIKE ?";
        $params[] = "%" . $_GET[$campo] . "%";
    }
}

$whereClause = implode(" AND ", $conditions);
$stmt = $conn->prepare("SELECT nome, email, cpf, telefone FROM usuario WHERE $whereClause");

if (!empty($params)) {
    $types = str_repeat("s", count($params));
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Lista de Alunos</title>
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
      width: 200px;
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
    .options-card a, .options-card button {
      display: block;
      padding: 10px 15px;
      text-decoration: none;
      color: #333;
      font-size: 14px;
      background-color: white;
      border: none;
      width: 100%;
      text-align: left;
    }
    .options-card button:hover {
      background-color: #f5f5f5;
    }
    .header-buttons {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-bottom: 15px;
      position: relative;
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
      <button id="filterToggle" class="btn btn-outline-secondary">⚙️ Filtrar Alunos</button>
      <button class="btn btn-outline-secondary" onclick="window.location.href='adicionaAluno.php'">➕ Adicionar aluno</button>

      <!-- Card de filtro com botões -->
      <div id="filterCard" class="options-card" style="right: 0; top: 60px;">
        <div class="options-card-header">
          <span class="close-filter">×</span>
        </div>
        <form id="filterSelector" class="px-2 py-2">
          <a>Filtrar Alunos Por:</a>
          <button type="button" class="open-search" data-field="nome">Nome</button>
          <button type="button" class="open-search" data-field="turma">Turma</button>
          <button type="button" class="open-search" data-field="cpf">CPF</button>
          <button type="button" class="open-search" data-field="telefone">Telefone</button>
          <button type="button" class="open-search" data-field="email">Email</button>
          <div style="display: flex; justify-content: space-between; padding: 10px;">
            <button type="button" class="btn btn-secondary btn-sm close-filter">Sair</button>
          </div>
        </form>
      </div>

      <!-- Card de pesquisa simples -->
      <div id="searchCard" class="options-card" style="right: 220px; top: 60px;">
        <div class="options-card-header">
          <span class="close-search">×</span>
        </div>
        <form method="GET" action="" class="px-2 py-2">
          <input type="text" id="searchInput" name="" placeholder="" class="form-control mb-2">
          <div style="display: flex; justify-content: space-between; padding: 10px;">
            <button type="submit" name="limpar" value="1" class="btn btn-secondary btn-sm">Limpar</button>
            <button type="submit" class="btn btn-primary btn-sm">Buscar</button>
          </div>
        </form>
      </div>
    </div>

    <h4>Alunos:</h4>
    <table class="table">
      <thead>
        <tr>
          <th>Nome Completo</th>
          <th>Email</th>
          <th>CPF</th>
          <th>Telefone</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row["nome"]); ?></td>
              <td><?php echo htmlspecialchars($row["email"]); ?></td>
              <td><?php echo htmlspecialchars($row["cpf"]); ?></td>
              <td><?php echo htmlspecialchars($row["telefone"]); ?></td>
              <td style="position: relative;">
                <button class="btn-actions">⋮</button>
                <div class="options-card">
                  <div class="options-card-header">
                    <span class="close-menu">×</span>
                  </div>
                  <a href="excluirAluno.php?cpf=<?php echo urlencode($row["cpf"]); ?>">Excluir aluno</a>
                  <a href="alterarAluno.php?cpf=<?php echo urlencode($row["cpf"]); ?>">Alterar aluno</a>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5">Nenhum aluno encontrado.</td></tr>
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

document.addEventListener('click', (e) => {
  const isInsideCard = e.target.closest('.options-card') || e.target.classList.contains('btn-actions') || e.target.id === 'filterToggle';
  if (!isInsideCard) {
    document.querySelectorAll('.options-card').forEach(card => card.style.display = 'none');
  }
});

  const filterBtn = document.getElementById('filterToggle');
  const filterCard = document.getElementById('filterCard');
  const closeFilterBtns = document.querySelectorAll('.close-filter');

  filterBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    document.querySelectorAll('.options-card').forEach(card => card.style.display = 'none');
    filterCard.style.display = 'block';
  });

  closeFilterBtns.forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      filterCard.style.display = 'none';
    });
  });

  const searchCard = document.getElementById('searchCard');
  const searchInput = document.getElementById('searchInput');
  const closeSearchBtns = document.querySelectorAll('.close-search');

  document.querySelectorAll('.open-search').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      const field = this.getAttribute('data-field');
      searchInput.name = field;
      searchInput.placeholder = `Digite o ${field === 'cpf' ? 'CPF' : field === 'telefone' ? 'telefone' : field} do aluno`;
      searchInput.value = '';
      searchCard.style.display = 'block';
    });
  });

  closeSearchBtns.forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      searchCard.style.display = 'none';
    });
  });
</script>
</body>
</html>
<?php $conn->close(); ?>
