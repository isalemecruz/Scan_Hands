<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../../index.php");
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

// Buscar imagem de perfil
$stmt = $conn->prepare("SELECT foto_perfil FROM usuario WHERE email = ?");
$stmt->bind_param("s", $emailLogado);
$stmt->execute();
$result = $stmt->get_result();

$fotoBase64 = "../img/iconPerfil.png"; // imagem padrão
if ($result && $result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    if (!empty($usuario['foto_perfil'])) {
        $fotoBase64 = 'data:image/jpeg;base64,' . base64_encode($usuario['foto_perfil']);
    }
}
$stmt->close();

// Consulta: total de turmas
$sqlTurmas = "SELECT COUNT(*) as total FROM turma";
$resultTurmas = $conn->query($sqlTurmas);
$turmas = ($resultTurmas && $resultTurmas->num_rows > 0) ? $resultTurmas->fetch_assoc()['total'] : 0;

// Consulta: total de professores
$sqlProfessores = "SELECT COUNT(*) as total FROM usuario WHERE tipo = 'professor'";
$resultProfessores = $conn->query($sqlProfessores);
$professores = ($resultProfessores && $resultProfessores->num_rows > 0) ? $resultProfessores->fetch_assoc()['total'] : 0;

// Consulta: total de alunos
$sqlAlunos = "SELECT COUNT(*) as total FROM usuario WHERE tipo = 'aluno'";
$resultAlunos = $conn->query($sqlAlunos);
$alunos = ($resultAlunos && $resultAlunos->num_rows > 0) ? $resultAlunos->fetch_assoc()['total'] : 0;

// Consulta: total de dados (exemplo: somando usuários + turmas)
$sqlTotalUsuarios = "SELECT COUNT(*) as total FROM usuario";
$resultTotalUsuarios = $conn->query($sqlTotalUsuarios);
$totalUsuarios = ($resultTotalUsuarios && $resultTotalUsuarios->num_rows > 0) ? $resultTotalUsuarios->fetch_assoc()['total'] : 0;

$dadosGerais = $totalUsuarios + $turmas;

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Scan Hands - Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
        integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <link rel="icon" href="../img/librinhaSemFundo.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/inicioAluno.css">
    <style>
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            max-width: 800px;
            margin: 0 auto;
        }

        .card {
            background-color: #3366b8;
            border-radius: 50px;
            color: white;
            padding: 30px;
            text-align: center;
        }

        .card p {
            font-weight: bold;
            font-size: 18px;
        }

        .number {
            font-size: 32px;
            font-weight: bold;
            margin: 20px 0 10px;
        }

        .line {
            height: 2px;
            background-color: white;
            width: 60%;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="sidebar" style="margin-right: 50px;">
        <div class="sidebar-top">
            <button id="toggleSidebar">
                <img id="toggleIcon" src="../img/iconVoltar.png" style="width: 20px; height: 20px;" alt="Voltar">
            </button>

            <!-- Imagem de perfil dinâmica -->
            <img src="<?php echo $fotoBase64; ?>" alt="avatar" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;" />
            <p class="email"><?php echo htmlspecialchars($emailLogado); ?></p>
            <button style="font-size: 12px;" onclick="window.location.href='perfilAluno.php'">Ver Perfil</button>
            <nav>
                <a href="#"><img src="../img/iconHome.png" alt=""><span> Início</span></a>
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

    <div class="main" style="background-color: #ededed;">
        <div class="welcome">
            <div class="row">
                <div class="col-10">
                    <p style="padding-left: 50px; padding-top: 50px; font-weight: bolder;"> Seja bem-vindo(a) à<br />
                        plataforma da Scan Hands</p>
                </div>
                <div class="col-2">
                    <img style="width: 200px;" src="../img/librinhaSemFundo.png" alt="">
                </div>
            </div>
        </div>

        <div class="cards-wrapper">
            <div class="cards-grid">
                <div class="card" style="margin-left: -200px">
                    <p>Turmas criadas</p>
                    <div class="number"><?php echo $turmas; ?></div>
                    <div class="line"></div>
                </div>
                <div class="card" style="margin-left: 100px">
                    <p>Professores cadastrados</p>
                    <div class="number"><?php echo $professores; ?></div>
                    <div class="line"></div>
                </div>
                <div class="card" style="margin-left: -200px">
                    <p>Alunos cadastrados</p>
                    <div class="number"><?php echo $alunos; ?></div>
                    <div class="line"></div>
                </div>
                <div class="card" style="margin-left: 100px">
                    <p>Dados gerais</p>
                    <div class="number"><?php echo $dadosGerais; ?></div>
                    <div class="line"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.querySelector('.sidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    </script>
</body>

</html>
