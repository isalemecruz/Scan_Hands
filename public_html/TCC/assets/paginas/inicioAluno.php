<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../../../index.php");
    exit();
}
$emailLogado = $_SESSION['email'];

// Conexão com o banco
$host = "localhost";
$usuario = "u357936358_librinha";
$senha = "Librinh4tcc#";
$banco = "u357936358_scanhands";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Erro de conexão com o banco: " . $conn->connect_error);
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
                <a href="aulasAluno.php"><img src="../img/iconAula.png" alt=""><span> Aulas</span></a>
                <a href="atividadesAluno.php"><img src="../img/iconAtv.png" alt=""><span> Atividades</span></a>
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

        <div class="cards-wrapper" style="margin-top: 60px; margin-right: 60px;">
            <div class="cards" style="margin-top: 30px;">
                <div class="card" style="margin-right: 60px;">
                    <p>Atividades Resolvidas</p>
                    <div class="progress-text" style="margin-top: 86px;">18/270</div>
                    <div class="progress-bar"></div>
                </div>
                <div class="card" style="margin-left: 60px;">
                    <p>Aulas Assistidas</p>
                    <div class="progress-text" style="margin-top: 86px;">18/270</div>
                    <div class="progress-bar"></div>
                </div>
                <div class="card">
                    <p>Capítulos concluídos</p>
                    <div class="progress-text" style="margin-top: 86px;">2/30</div>
                    <div class="progress-bar"></div>
                </div>
                <div class="card" style="margin-left: 60px;">
                    <p>Tempo de tela</p>
                    <div class="progress-text" style="margin-top: 86px;">180h</div>
                    <div class="progress-bar"></div>
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
